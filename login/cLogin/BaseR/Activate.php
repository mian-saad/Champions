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

        $register_reports_db_name = $wpdb->prefix . 'arena';

        if ($wpdb->get_var("SHOW TABLES LIKE '$register_reports_db_name'") != $register_reports_db_name) {
            $sql = "CREATE TABLE $register_reports_db_name (
                flp_id VARCHAR(32),
                flp_locale TEXT,
                flp_registration_time TEXT,
                flp_reporting_ip TEXT,
                flp_title TEXT,
                flp_country TEXT,
                flp_first_name TEXT,
                flp_last_name TEXT,
                flp_email VARCHAR(32),
                flp_password TEXT,
                flp_organisation TEXT,
                flp_years_of_experience TEXT,
                flp_city TEXT,
                flp_visibility_level TEXT,
                flp_experience_with_radicalisation TEXT,
                flp_working_with TEXT,
                flp_area_of_expertise TEXT,
                flp_skills TEXT,
                flp_description TEXT,
                flp_status TEXT,
                alert_id TEXT,
                flp_ClosedAssociatedAlert TEXT,
                flp_associatedAlert TEXT,
                flp_notAssociatedAlert TEXT,
                PRIMARY KEY  (flp_id)
            ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
        }

        flush_rewrite_rules();
    }
}
