<?php
/**
 * @package TakedownstatesPlugin
 */
namespace Inc\Base;

class Activate
{

    public static function activate()
    {
        /*
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $tra_states_db_name = $wpdb->prefix . 'tra_states_engl';

        // handles states table
        if ($wpdb->get_var("SHOW TABLES LIKE '$tra_states_db_name'") != $tra_states_db_name) {

            // creates states table
            $sql = "CREATE TABLE $tra_states_db_name (
                state_id mediumint(9) AUTO_INCREMENT,
                state_text TEXT,
                state_code TEXT,
                state_type TEXT,
                state_answers TEXT,
                state_button_text TEXT,
                mandatory TEXT,
                PRIMARY KEY  (state_id)
            ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);

            // fills states table
            foreach (Activate::$states_engl as $state) {
                // INSERT INTO `wp_trp_states_engl2` (`state_id`, `state_text`, `state_code`, `state_type`, `state_answers`, `state_button_text`, `mandatory`) VALUES (NULL, 'a', 'b', 'c', 'd', 'e', 'f');

                $base64_serial = base64_encode(serialize($state['state_answers']));
                $sql = "INSERT INTO $tra_states_db_name (state_id, state_text, state_code, state_type, state_answers, state_button_text, mandatory)
                        VALUES (
                            NULL,
                            '" . $state['state_text'] . "',
                            '" . $state['state_code'] . "',
                            '" . $state['state_type'] . "',
                            '" . $base64_serial . "',
                            '" . $state['state_button_text'] . "',
                            '" . $state['mandatory'] . "')";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql);
            }
        }
        */

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $tra_reports_db_name = $wpdb->prefix . 'tra_reports';

        if ($wpdb->get_var("SHOW TABLES LIKE '$tra_reports_db_name'") != $tra_reports_db_name) {

            // creates states table
            $sql = "CREATE TABLE $tra_reports_db_name (
                report_id VARCHAR(32),
                report_time TEXT,
                reporter_fName TEXT,
                reporter_lName TEXT,
                reporter_email TEXT,
                reporter_residence TEXT,
                title TEXT,
                event_time TEXT,
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
