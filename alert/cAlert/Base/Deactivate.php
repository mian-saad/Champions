<?php
/**
 * @package TakedownQuestionsPlugin
 */
namespace Cover\Base;

class Deactivate
{
    public static function deactivate()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $alert_reports_db_name = $wpdb->prefix . 'alert';
        $sql = "DROP TABLE IF EXISTS $alert_reports_db_name;";
        $wpdb->query($sql);

        flush_rewrite_rules();
    }
}
