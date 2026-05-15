<?php

use WebsiteMonitoring\API\ResultsController;

describe('ResultsController payload validation', function () {
    it('rejects missing required fields', function () {
        $controller = new ResultsController();
        $invalidPayload = [
            // e.g. missing 'run_id', 'results', etc.
            'site' => 'test-site',
        ];
        $request = new WP_REST_Request('POST', '/wp-json/website-monitoring/v1/results');
        $request->set_body_params($invalidPayload);
        $response = $controller->post_results($request);
        expect($response->get_status())->toBe(400);
        expect($response->get_data()['error'])->toBe('Invalid payload');
    });

    it('accepts valid payload', function () {
        $controller = new ResultsController();
        $validPayload = [
            'run_id' => 'abc123',
            'site' => 'test-site',
            'environment' => 'staging',
            'release' => '1.0.0',
            'commit' => 'deadbeef',
            'results' => [
                [
                    'test_name' => 'checkout',
                    'status' => 'pass',
                    'duration_ms' => 1234,
                ]
            ]
        ];
        $request = new WP_REST_Request('POST', '/wp-json/website-monitoring/v1/results');
        $request->set_body_params($validPayload);
        $response = $controller->post_results($request);
        expect($response->get_status())->toBe(201);
        expect($response->get_data()['message'])->toBe('Results received');
    });
});
