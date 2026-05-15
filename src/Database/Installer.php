<?php

    namespace WebsiteMonitoring\Database;

    class Installer {

        /**
         * Install the database tables for the plugin.
         *
         * @return void
         */
        public static function install() {

            self::create_sites_table();
            self::create_runs_table();
            self::create_results_table();
            
        }

        /**
         * Create the table for storing monitored sites.
         *
         * @return void
         */
        public static function create_sites_table() {

            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            // Table for storing monitored sites
            $table_name = $wpdb->prefix . 'cbwm_sites';

            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                name varchar(255) NOT NULL,
                domain varchar(255) NOT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

        /**
         * Create the table for storing test runs (groups)
         *
         * @return void
         */
        public static function create_runs_table() {

            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            // Table for storing monitoring runs
            $table_name = $wpdb->prefix . 'cbwm_runs';

            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                site_id mediumint(9) NOT NULL,
                started_at datetime NOT NULL,
                completed_at datetime NOT NULL,
                overall_status varchar(20) NOT NULL,
                PRIMARY KEY  (id),
                FOREIGN KEY (site_id) REFERENCES {$wpdb->prefix}cbwm_sites(id) ON DELETE CASCADE
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

        /**
         * Create table for storing individual tests
         *
         * @return void
         */
        public static function create_results_table() {

            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            // Table for storing monitoring results
            $table_name = $wpdb->prefix . 'cbwm_results';

            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                run_id mediumint(9) NOT NULL,
                name varchar(255) NOT NULL,
                category varchar(50) NOT NULL,
                started_at datetime NOT NULL,
                completed_at datetime NOT NULL,
                response_time int NULL,
                overall_status varchar(20) NOT NULL,
                PRIMARY KEY  (id),
                KEY run_id (run_id),
                FOREIGN KEY (run_id) REFERENCES {$wpdb->prefix}cbwm_runs(id) ON DELETE CASCADE
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }