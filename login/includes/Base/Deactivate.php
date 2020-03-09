<?php
/**
 * @package register
 */
namespace Incl\Base;

class Deactivate {

  public static function deactivate(){

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $commentsData = $wpdb->prefix . 'commentsData';
    $sql = "DROP TABLE IF EXISTS $commentsData;";
    $wpdb->query($sql);

    $arena = $wpdb->prefix . 'arena';
    $sql = "DROP TABLE IF EXISTS $arena;";
    $wpdb->query($sql);

    flush_rewrite_rules();
  }

}
