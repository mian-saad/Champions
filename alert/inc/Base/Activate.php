<?php
/**
 * @package TakedownstatesPlugin
 */
namespace Inc\Base;

class Activate
{

    public static function activate()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $tra_reports_db_name = $wpdb->prefix . 'tra_reports';

        if ($wpdb->get_var("SHOW TABLES LIKE '$tra_reports_db_name'") != $tra_reports_db_name) {

            // creates states table
            $sql = "CREATE TABLE $tra_reports_db_name (
                report_id VARCHAR(32),
                report_time TEXT,
                report_locale TEXT,
                report_ip TEXT,
                reporter_fName TEXT,
                reporter_lName TEXT,
                reporter_email TEXT,
                reporter_residence TEXT,
                title TEXT,
                event_time TEXT,
                tempID TEXT,
                event_category TEXT,
                event_description TEXT,
                description_subject TEXT,
                alert_status TEXT,
                PRIMARY KEY  (report_id)
            ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
        }

        flush_rewrite_rules();
    }
}
