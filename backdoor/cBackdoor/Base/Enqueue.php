<?php
/**
 * @package register
 */
namespace Route\Base;

use \Route\Base\BaseController;

class Enqueue extends BaseController {

    public function register() {
//        add_action( 'wp_enqueue_scripts', array($this, 'enqueue') );
    }

    function enqueue(){

        //Enqueuing JQuery
        wp_enqueue_script('jquery');

        // Enqueue JS and CSS
        wp_enqueue_style('login_css_bootstrap', $this->plugin_url . 'assets/login-bootstrap.css');
        wp_enqueue_style('login_css_bootstrap-grid', $this->plugin_url . 'assets/login-bootstrap-grid.css');
        wp_enqueue_style('login_css_bootstrap-reboot', $this->plugin_url . 'assets/login-bootstrap-reboot.css');
        wp_enqueue_style('login_css_style', $this->plugin_url . 'assets/login-style.css');

        //Enqueuing Scripts
        wp_enqueue_script('login-script', $this->plugin_url . 'assets/login-script.js');
        wp_localize_script( 'login-script', 'login_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));


    }

}
