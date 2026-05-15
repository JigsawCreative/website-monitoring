<?php
/**
 * Plugin Name: Cross Brand Website Monitoring
 * Description: Database tables and dashboard for website monitoring tool
 * Author: Neil Williams
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) exit;

// Constants
define( 'CBWM_PATH', plugin_dir_path( __FILE__ ) );
define( 'CBWM_URL',  plugin_dir_url( __FILE__ ) );
define( 'CBWM_VERSION', '1.0.0' );

// Path to composer also bring in dotenv for environment variable handling
if (file_exists(CBWM_PATH . 'vendor/autoload.php')) {
    require_once CBWM_PATH . 'vendor/autoload.php';

    // Load environment variables from .env file in root
    // $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    // $dotenv->load();
}

// Initialize the plugin
add_action('plugins_loaded', [\WebsiteMonitoring\Plugin::class, 'init']);

// Register activation hook to set up database tables
register_activation_hook(__FILE__, ['\WebsiteMonitoring\Database\Installer', 'install']);

// Register REST API routes
add_action( 'rest_api_init', [ \WebsiteMonitoring\API\ResultsController::class, 'register_routes' ] );