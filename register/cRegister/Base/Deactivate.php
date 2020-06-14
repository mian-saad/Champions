<?php
/**
 * @package TakedownQuestionsPlugin
 */
namespace Comprise\Base;

class Deactivate
{
    public static function deactivate()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $register_reports_db_name = $wpdb->prefix . 'arena';
        $sql = "DROP TABLE IF EXISTS $register_reports_db_name;";
        $wpdb->query($sql);


        flush_rewrite_rules();
    }
}
