<?php
/**
 * @package register
 */
namespace Contain\Base;

use \Contain\Base\BaseController;

class Enqueue extends BaseController {

    public function register() {
        add_action( 'init', array($this, 'enqueue') );
        add_action( 'wp_enqueue_scripts', array($this, 'enqueueScripts') );
    }

    function enqueue(){

        //Enqueuing Scripts
        wp_enqueue_script('login-script-bootstrap-jq', $this->plugin_url . 'assets/login-bootstrap-jq.js');
        wp_enqueue_script('login-script-bootstrap-popper', $this->plugin_url . 'assets/login-bootstrap-popper.js');
    }

    function enqueueScripts(){

        //Enqueuing Scripts
//        wp_enqueue_script('login-script', $this->plugin_url . 'assets/login-script.js');
//        wp_localize_script( 'login-script', 'login_ajax',
//            array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    }
}
