<?php
/**
 * @package register
 */
namespace Includes\Base;

class Deactivate {

  public static function deactivate(){

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $arena = $wpdb->prefix . 'arena';
    $sql = "DROP TABLE IF EXISTS $arena;";
    $wpdb->query($sql);

    flush_rewrite_rules();
  }

}
