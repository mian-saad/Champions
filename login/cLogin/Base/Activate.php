<?php
/**
 * @package register
 */
namespace Contain\Base;

class Activate {

  public static function activate(){

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $commentsData = $wpdb->prefix . 'commentsData';
    $recommendationData = $wpdb->prefix . 'recommendationData';

      if ($wpdb->get_var("SHOW TABLES LIKE '$recommendationData'") != $recommendationData) {
          $sql = "CREATE TABLE $recommendationData (
            recommendation_data TEXT,
            recommendation_name TEXT,
            recommendation_id TEXT,
            alert_ID TEXT
        ) $charset_collate;";
          require_once ABSPATH . 'wp-admin/includes/upgrade.php';
          dbDelta($sql);
      }

    if ($wpdb->get_var("SHOW TABLES LIKE '$commentsData'") != $commentsData) {
        $sql = "CREATE TABLE $commentsData (
            comment_data TEXT,
            comment_name TEXT,
            alert_ID TEXT
        ) $charset_collate;";
          require_once ABSPATH . 'wp-admin/includes/upgrade.php';
          dbDelta($sql);
    }

    flush_rewrite_rules();
  }
}
