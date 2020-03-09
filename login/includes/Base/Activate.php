<?php
/**
 * @package register
 */
namespace Incl\Base;

class Activate {

  public static function activate(){




    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();


    $arena = $wpdb->prefix . 'arena';
    $commentsData = $wpdb->prefix . 'commentsData';

    if ($wpdb->get_var("SHOW TABLES LIKE '$commentsData'") != $commentsData) {
        // creates states table
        $sql = "CREATE TABLE $commentsData (
            comment_data TEXT,
            comment_name TEXT
        ) $charset_collate;";
          require_once ABSPATH . 'wp-admin/includes/upgrade.php';
          dbDelta($sql);
    }

      if ($wpdb->get_var("SHOW TABLES LIKE '$arena'") != $arena) {
          // creates states table
          $sql = "CREATE TABLE $arena (
            first_name TEXT,
            last_name TEXT,
            country TEXT,
            title TEXT,
            email TEXT,
            password TEXT,
            description TEXT,
            skill TEXT,
            associatedAlert TEXT
        ) $charset_collate;";
          require_once ABSPATH . 'wp-admin/includes/upgrade.php';
          dbDelta($sql);
      }

    flush_rewrite_rules();
  }
}
