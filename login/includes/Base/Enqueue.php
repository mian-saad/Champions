<?php
/**
 * @package register
 */
namespace Incl\Base;

use \Incl\Base\BaseController;

class Enqueue extends BaseController {

    public function register() {
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue') );
    }

    function enqueue(){

        //Enqueuing JQuery
        wp_enqueue_script('jquery');

        //Enqueuing Styles
        wp_enqueue_style( 'my-style', $this->plugin_url . ( 'assets/pStyle.css' ));

        //Enqueuing Scripts
        wp_enqueue_script('my-script', $this->plugin_url . 'assets/pScript.js');
        wp_localize_script( 'my-script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    }

}
