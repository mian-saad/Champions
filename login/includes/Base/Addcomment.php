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
        $data = array( 'comment_data' => $comment_data );
        $commentsData = $wpdb->prefix . 'commentsData';
        $wpdb->insert($commentsData, $data);


        $results = $wpdb->get_results( "SELECT comment_data FROM {$wpdb->prefix}commentsData", OBJECT );
        for ($i=0; $i<count($results); $i++) {
            echo "Comment ";
            echo $results[$i] -> comment_data;
            echo "</br>";
        }
        wp_die();
    }

}