<?php
/**
 * @package register
 */
namespace Incl\Base;

use \Incl\Base\BaseController;

class Fetchcomment extends BaseController {

    public function register() {
        add_action('wp_ajax_fetchcomment', array($this, 'fetchcomment'));
        add_action('wp_ajax_nopriv_fetchcomment', array($this, 'fetchcomment'));
    }

    public function fetchcomment() {

        global $wpdb;
        $results = $wpdb->get_results( "SELECT comment_data FROM {$wpdb->prefix}commentsData", OBJECT );
//        echo $results;

        for ($i=0; $i<count($results); $i++) {
            echo "Comment ";
            echo $results[$i] -> comment_data;
            echo "</br>";
        }
        wp_die();
    }
}