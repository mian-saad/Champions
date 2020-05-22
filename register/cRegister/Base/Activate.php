<?php
/**
 * @package TakedownstatesPlugin
 */
namespace Comprise\Base;

class Activate
{

    public static function activate()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $tra_reports_db_name = $wpdb->prefix . 'arena';

        if ($wpdb->get_var("SHOW TABLES LIKE '$tra_reports_db_name'") != $tra_reports_db_name) {
            $sql = "CREATE TABLE $tra_reports_db_name (
                report_id VARCHAR(32),
                report_locale TEXT,
                report_time TEXT,
                report_ip TEXT,
                language TEXT,
                title TEXT,
                expert_type TEXT,
                expert_status TEXT,
                country TEXT,
                first_name TEXT,
                last_name TEXT,
                email VARCHAR(32),
                password TEXT,
                organisation TEXT,
                years_of_experience TEXT,
                expert_residence_city TEXT,
                skill TEXT,
                arenaTempID TEXT,
                description TEXT,
                ClosedAssociatedAlert TEXT,
                associatedAlert TEXT,
                notAssociatedAlert TEXT,
                PRIMARY KEY  (email)
            ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
        }

        flush_rewrite_rules();
    }
}
