<?php

    namespace WebsiteMonitoring\API;

    use WP_REST_Request;

    class ResultsController {

        /**
         * Register REST API routes for results endpoint
         *
         * @return void
         */
        public static function register_routes() {
            register_rest_route( 'website-monitoring/v1', '/results', [
                'methods' => 'POST',
                'callback' => self::process_results(...),
                'permission_callback' => '__return_true', // For testing, allow all. Implement proper permissions later.
            ] );

            register_rest_route( 'website-monitoring/v1', '/results', [
                'methods' => 'GET',
                'callback' => self::fetch_results(...),
                'permission_callback' => '__return_true', // For testing, allow all. Implement proper permissions later.
            ] );
        }

        /**
         * Fetch results from the database
         *
         * @param WP_REST_Request $request
         * @return \WP_REST_Response
         */
        public static function fetch_results(WP_REST_Request $request) {

            global $wpdb;

            // Fetch results with associated run and site data
            $results = $wpdb->get_results(
                "SELECT r.*, ru.site_id, s.name AS site_name 
                 FROM {$wpdb->prefix}cbwm_results r
                 JOIN {$wpdb->prefix}cbwm_runs ru ON r.run_id = ru.id
                 JOIN {$wpdb->prefix}cbwm_sites s ON ru.site_id = s.id",
                ARRAY_A
            );

            return rest_ensure_response($results);
        }

        public static function process_results(WP_REST_Request $request) {
            
            // Extract payload from request
            $payload = $request->get_json_params();

            // Early validation: check for required top-level keys
            if ( !isset($payload['run']) || !isset($payload['results']) || !is_array($payload['results'])) {
                return new \WP_Error( 'invalid_payload', 'Missing required fields: run or results', ['status' => 400] );
            }

            // Validate payload data structure and types
            $validated = self::validate_payload($payload);
            if (is_wp_error($validated)) {
                return $validated;
            }

            // Sanitize and insert into database
            self::insert_results($validated['run'], $validated['results']);

            // Use $validated['run'] and $validated['results'] for DB insert, etc.
            error_log('Received results: ' . print_r($validated, true));

            // For testing: return a simple string
            return 'OK';
            
        }

        /**
         * Validate the payload structure and types
         *
         * @param array $payload
         * @return \WP_Error|array
         */
        public static function validate_payload($payload) {
            
        // Assign run and results to variables for easier access
        [
            'run' => $run,
            'results' => $results
        ] = $payload;

        // Define required fields and their sanitizers for 'run' and 'results'
        $run_fields = [
            'site_id' => 'intval',
            'started_at' => 'sanitize_text_field',
            'completed_at' => 'sanitize_text_field',
            'overall_status' => 'sanitize_text_field',
        ];
        $result_fields = [
            'name' => 'sanitize_text_field',
            'category' => 'sanitize_text_field',
            'started_at' => 'sanitize_text_field',
            'completed_at' => 'sanitize_text_field',
            'overall_status' => 'sanitize_text_field',
            'response_time' => 'intval',
        ];

        // Validate and sanitize 'run'
        if (!is_array($run)) {
            return new \WP_Error('invalid_run', 'Run must be an array', ['status' => 400]);
        }
        foreach ($run_fields as $field => $sanitizer) {

            if (!isset($run[$field])) {
                return new \WP_Error('invalid_run', "Missing field: $field in run", ['status' => 400]);
            }

            // Run callback from string name of sanitizer function above to sanitize value
            $run[$field] = call_user_func($sanitizer, $run[$field]);
        }

        // Validate and sanitize 'results'
        if (!is_array($results) || empty($results)) {
            return new \WP_Error('invalid_results', 'Results must be a non-empty array', ['status' => 400]);
        }
        foreach ($results as $i => &$result) {
            if (!is_array($result)) {
                return new \WP_Error('invalid_result', "Result at index $i must be an array", ['status' => 400]);
            }
            foreach ($result_fields as $field => $sanitizer) {
                if (!isset($result[$field])) {
                    return new \WP_Error('invalid_result', "Missing field: $field in results[$i]", ['status' => 400]);
                }
                $result[$field] = call_user_func($sanitizer, $result[$field]);
            }
        }

        // break reference to last result item
        unset($result); 

        return [
            'run' => $run,
            'results' => $results
        ];

        }

        /**
         * Insert the validated results into the database
         *
         * @param array $run
         * @param array $results
         * @return void
         */
        public static function insert_results($run, $results) {
            global $wpdb;

            // Insert run data into runs table
            $wpdb->insert(
                $wpdb->prefix . 'cbwm_runs',
                [
                    'site_id' => $run['site_id'],
                    'started_at' => $run['started_at'],
                    'completed_at' => $run['completed_at'],
                    'overall_status' => $run['overall_status']
                ],
                [
                    '%d',
                    '%s',
                    '%s',
                    '%s'
                ]
            );

            $run_id = $wpdb->insert_id;

            // Insert each result into results table
            foreach ($results as $result) {
                $wpdb->insert(
                    $wpdb->prefix . 'cbwm_results',
                    [
                        'name' => $result['name'],
                        'category' => $result['category'],
                        'started_at' => $result['started_at'],
                        'completed_at' => $result['completed_at'],
                        'overall_status' => $result['overall_status'],
                        'response_time' => $result['response_time'] ?? null,
                        'run_id' => $run_id
                    ],
                    [
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        isset($result['response_time']) ? '%d' : null,
                        '%d'
                    ]
                );
            }
        }
    }