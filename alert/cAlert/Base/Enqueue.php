<?php
/**
 * @package  TakedownQuestionsPlugin
 */
namespace Cover\Base;

use \Cover\Base\BaseController;

/**
 *
 */
class Enqueue extends BaseController
{
    public function register()
    {
//        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend'));
//        add_action('init', array($this, 'timepicker_style'));
    }

    // enqueues documents
    public function enqueue_frontend()
    {
        // Enqueue JS and CSS
        wp_enqueue_style('alert_css_bootstrap', $this->plugin_url . 'assets/css/alert-bootstrap.css');
        wp_enqueue_style('alert_css_bootstrap-grid', $this->plugin_url . 'assets/css/alert-bootstrap-grid.css');
        wp_enqueue_style('alert_css_bootstrap-reboot', $this->plugin_url . 'assets/css/alert-bootstrap-reboot.css');
        wp_enqueue_style('alert_css_style', $this->plugin_url . 'assets/css/alert-style.css');
        wp_enqueue_script('alert_js_script', $this->plugin_url . 'assets/js/alert-script.js', array('jquery'));

        wp_enqueue_script('alert_js_script', $this->plugin_url . 'assets/js/alert-script.js', array('jquery'));
        wp_localize_script('alert_js_script', 'alert_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nextNonce' => wp_create_nonce('myajax-next-nonce'),
        ));
    }

    public function timepicker_style()
    {
        wp_enqueue_style('datetime-css', $this->plugin_url . 'assets/css/jquery.datetimepicker.min.css');
        wp_enqueue_script('datetime_script', $this->plugin_url . 'assets/js/jquery.datetimepicker.full.js', array('jquery'));
    }
}
