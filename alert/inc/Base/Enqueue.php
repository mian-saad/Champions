<?php
/**
 * @package  TakedownQuestionsPlugin
 */
namespace Inc\Base;

use \Inc\Base\BaseController;

/**
 *
 */
class Enqueue extends BaseController
{
    public function register()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend'));
        add_action('init', array($this, 'timepicker_style'));
    }

    // enqueues documents
    public function enqueue_frontend()
    {
        // enqueue our js and css
        wp_enqueue_style('tra_css_style', $this->plugin_url . 'assets/css/tra_style.css');
        wp_enqueue_script('tra_js_script', $this->plugin_url . 'assets/js/tra_script.js', array('jquery'));
        wp_localize_script('tra_js_script', 'tra_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nextNonce' => wp_create_nonce('myajax-next-nonce'),
        ));

        //wp_enqueue_script('jquery_map_script', $this->plugin_url . 'assets/js/locationpicker.jquery.min.js', array('jquery'));

        
    }

    public function timepicker_style()
    {
        wp_enqueue_style('datetime-css', $this->plugin_url . 'assets/css/jquery.datetimepicker.min.css');
        wp_enqueue_script('datetime_script', $this->plugin_url . 'assets/js/jquery.datetimepicker.full.js', array('jquery'));
    }
}
