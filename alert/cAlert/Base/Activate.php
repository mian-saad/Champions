<?php
/**
 * @package TakedownstatesPlugin
 */
namespace Cover\Base;

class Activate
{

    public static function activate()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $alert_reports_db_name = $wpdb->prefix . 'alert';

        if ($wpdb->get_var("SHOW TABLES LIKE '$alert_reports_db_name'") != $alert_reports_db_name) {

            // creates states table
            $sql = "CREATE TABLE $alert_reports_db_name (
                report_id VARCHAR(32),
                report_time TEXT,
                report_locale TEXT,
                report_ip TEXT,
                reporter_fName TEXT,
                reporter_lName TEXT,
                reporter_email TEXT,
                reporter_residence TEXT,
                reporter_residence_city TEXT,
                title TEXT,
                event_time TEXT,
                tempID TEXT,
                event_category TEXT,
                event_description TEXT,
                description_subject TEXT,
                deadline TEXT,
                alert_case_status TEXT,
                alert_status_moderator TEXT,
                alert_status_flp TEXT,
                alert_status_mutual TEXT,
                PRIMARY KEY  (report_id)
            ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
        }

        flush_rewrite_rules();
    }
}
