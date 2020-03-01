<?php
/**
 * @package register
 */
namespace Incl\Base;

use \Incl\Base\BaseController;

class Fetch extends BaseController {

    public function register() {
        add_action('wp_ajax_fetch', array($this, 'fetch'));
        add_action('wp_ajax_nopriv_fetch', array($this, 'fetch'));
    }

    public function fetch(){

        global $wpdb;
        $results = $wpdb->get_results( "SELECT event_description FROM {$wpdb->prefix}tra_reports", OBJECT );
        $user_data = $wpdb->get_results( "SELECT email, password FROM {$wpdb->prefix}arena", OBJECT );

        $naam = sanitize_text_field( $_GET['naam'] );
        $paas= sanitize_text_field( $_GET['paas'] );


        for ($j=0; $j<count($user_data); $j++) {
            if ($naam === $user_data[$j]->email && $paas === $user_data[$j]->password) {
                for ($i=0; $i<count($results); $i++){
                    echo "Post ";
                    echo $i+1;
                    echo ": ";
                    echo $results[$i] -> event_description;

                    echo "</br>";
                    echo "<div class='display_comment' id='display_comment$i'></div>";
                    echo "</br>";
                    echo "<input class='get_comment' type='text' id='get_comment$i' placeholder='Add Comment'>";
                    echo "</br>";
                    echo "<button class='submit_comment' id='submit_comment$i' >Add Comment</button>";
                    echo "</br></br>";
                }
                wp_die();
            }
        }
    }
}