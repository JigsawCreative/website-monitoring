<?php
/**
 * Plugin Name: Website Monitoring
 * Description: Database tables and dashboard for website monitoring tool
 * Author: Neil Williams
 */

if (!defined('ABSPATH')) exit;

// Constants
define( 'WM_PATH', plugin_dir_path( __FILE__ ) );
define( 'WM_URL',  plugin_dir_url( __FILE__ ) );
define( 'WM_VERSION', '1.0.0' );

// Path to composer also bring in dotenv for environment variable handling
if (file_exists(WM_PATH . 'vendor/autoload.php')) {
    require_once WM_PATH . 'vendor/autoload.php';

    // Load environment variables from .env file in root
    // $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    // $dotenv->load();
}

// Initialize the plugin
use WebsiteMonitoring\PLugin;

add_action('plugins_loaded', [Plugin::class, 'init']);