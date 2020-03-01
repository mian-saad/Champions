<?php
/**
 * @package register
 */
namespace Incl\Base;

use \Incl\Base\BaseController;

class Addcomment extends BaseController {

    public function register() {
        add_action('wp_ajax_addcomment', array($this, 'addcomment'));
        add_action('wp_ajax_nopriv_addcomment', array($this, 'addcomment'));
    }

    public function addcomment() {

        global $wpdb;
        $comment_data = sanitize_text_field( $_GET['comment_data'] );
//        $comment_idd = sanitize_text_field( $_GET['comment_idd'] );
        $alertID = $wpdb->get_results( "SELECT report_id FROM {$wpdb->prefix}tra_reports", OBJECT );

        $data = array( 'comment_data' => $comment_data, 'comment_idd' => $alertID );
        $commentsData = $wpdb->prefix . 'commentsData';
        $wpdb->insert($commentsData, $data);


        $results = $wpdb->get_results( "SELECT comment_data, comment_idd FROM {$wpdb->prefix}commentsData", OBJECT );
        //Comment filtering logic will go here
        for ($i=0; $i<count($results); $i++) {
            echo "Comment ";
            echo $results[$i] -> comment_data;
            echo "</br>";
        }
        wp_die();
    }

}