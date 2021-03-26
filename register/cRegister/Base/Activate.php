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

//            $link = mysqli_connect("localhost:8888", "admin", "yb#mq0z\$qRBc8HHg5&", "champions");
//            $moderator_data = "INSERT INTO `wp_arena` (`flp_id`, `flp_locale`, `flp_registration_time`, `flp_reporting_ip`, `flp_title`, `flp_country`, `flp_first_name`, `flp_last_name`, `flp_email`, `flp_password`, `flp_organisation`, `flp_years_of_experience`, `flp_city`, `flp_skills`, `flp_description`, `flp_status`, `alert_id`, `flp_ClosedAssociatedAlert`, `flp_associatedAlert`, `flp_notAssociatedAlert`) VALUES
//            ('moderator1', 'en', '2020/08/05 09:15', '::1', 'Moderator', 'Romania', 'Admin', 'Romania', 'moderator1@romania.ro', 'Romania01', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
//            ('moderator2', 'en', '2020/08/05 09:15', '::1', 'Moderator', 'Poland',  'Admin', 'Poland',  'moderator1@poland.pl',  'Poland01', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
//            ('moderator3', 'en', '2020/08/05 09:15', '::1', 'Moderator', 'Germany', 'Admin', 'Germany', 'moderator0@germany.de', 'Germany01', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
//            ('moderator4', 'en', '2020/08/05 09:15', '::1', 'Moderator', 'Austria', 'Admin', 'Austria', 'moderator1@austria.at', 'Austria01', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
//            ('moderator5', 'en', '2020/08/05 09:15', '::1', 'Moderator', 'Hungary', 'Admin', 'Hungary', 'moderator1@hungary.hu', 'Hungary01', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
//            ('moderator6', 'en', '2020/08/05 09:15', '::1', 'Moderator', 'Italy',   'Admin', 'Italy',   'moderator1@italy.it',    'Italy01', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL)";
//            mysqli_query($link, $moderator_data);
        }

        flush_rewrite_rules();
    }
}
