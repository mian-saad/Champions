<?php
/**
 * @package register
 */
namespace Incl\Base;

class Activate {

  public static function activate(){

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $commentsData = $wpdb->prefix . 'commentsData';

    if ($wpdb->get_var("SHOW TABLES LIKE '$commentsData'") != $commentsData) {

        // creates states table
        $sql = "CREATE TABLE $commentsData (
            comment_data TEXT
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    flush_rewrite_rules();
  }

}
