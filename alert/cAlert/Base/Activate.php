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

        $alert_db = $wpdb->prefix . 'alert';

        if ($wpdb->get_var("SHOW TABLES LIKE '$alert_db'") != $alert_db) {

            // creates states table
            $sql = "CREATE TABLE $alert_db (
                alert_id VARCHAR(32),
                alert_report_time TEXT,
                alert_report_locale TEXT,
                alert_report_ip TEXT,
                alert_country TEXT,
                alert_city TEXT,
                alert_time TEXT,
                alert_category TEXT,
                alert_subject TEXT,
                alert_description TEXT,
                alert_deadline TEXT,
                flp_id TEXT,
                alert_case_status TEXT,
                alert_status_moderator TEXT,
                alert_status_flp TEXT,
                alert_status_mutual TEXT,
                PRIMARY KEY  (alert_id)
            ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
        }

        flush_rewrite_rules();
    }
}
