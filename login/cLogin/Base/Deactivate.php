<?php
/**
 * @package register
 */
namespace Contain\Base;

class Deactivate {

  public static function deactivate(){

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $commentsData = $wpdb->prefix . 'commentsData';
    $sqlComment = "DROP TABLE IF EXISTS $commentsData;";
    $wpdb->query($sqlComment);

    $recommendation = $wpdb->prefix . 'recommendationData';
    $sqlRecommend = "DROP TABLE IF EXISTS $recommendation;";
    $wpdb->query($sqlRecommend);

    flush_rewrite_rules();
  }

}
