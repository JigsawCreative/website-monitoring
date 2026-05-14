<?php 

    namespace WebsiteMonitoring;

    class Plugin {
        public static function init() {
            
            // Ensure classes exist before calling init
            if(class_exists('WebsiteMonitoring\REST\ResultsEndpoint')) {
                \WebsiteMonitoring\REST\ResultsEndpoint::register_routes();
            }
        }
    }