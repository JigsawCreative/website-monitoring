<?php

    namespace WebsiteMonitoring\REST;

    class ResultsController {
        public static function register_routes() {
            register_rest_route( 'website-monitoring/v1', '/results', [
                'methods' => 'POST',
                'callback' => self::process_results(...),
                'permission_callback' => function () {
                    return current_user_can( 'manage_options' ); // Only allow admins to access this endpoint
                }
            ] );
        }

        public static function process_results() {
            // Process and return the results here
        }
    }