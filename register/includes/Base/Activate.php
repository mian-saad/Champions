<?php
/**
 * @package register
 */
namespace Includes\Base;

class Activate {

  public static function activate(){

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $arena = $wpdb->prefix . 'arena';

    if ($wpdb->get_var("SHOW TABLES LIKE '$arena'") != $arena) {

        // creates states table
        $sql = "CREATE TABLE $arena (
            first_name TEXT,
            last_name TEXT,
            birthday TEXT,
            gender TEXT,
            email TEXT,
            password TEXT,
            skills TEXT
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    flush_rewrite_rules();
  }

}
