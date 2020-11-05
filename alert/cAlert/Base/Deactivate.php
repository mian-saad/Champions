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

        $alert_db = $wpdb->prefix . 'alert';
        $sql = "DROP TABLE IF EXISTS $alert_db;";
        $wpdb->query($sql);

        flush_rewrite_rules();
    }
}
