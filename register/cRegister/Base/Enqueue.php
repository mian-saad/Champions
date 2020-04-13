<?php
/**
 * @package  TakedownQuestionsPlugin
 */
namespace Comprise\Base;

use \Comprise\Base\BaseController;

/**
 *
 */
class Enqueue extends BaseController
{
    public function register()
    {
//        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend'));
    }

    // Enqueues Scripts and Styles
    public function enqueue_frontend()
    {
        // Enqueue JS and CSS
        wp_enqueue_style('register_css_bootstrap', $this->plugin_url . 'assets/css/register-bootstrap.css');
        wp_enqueue_style('register_css_bootstrap-grid', $this->plugin_url . 'assets/css/register-bootstrap-grid.css');
        wp_enqueue_style('register_css_bootstrap-reboot', $this->plugin_url . 'assets/css/register-bootstrap-reboot.css');
        wp_enqueue_style('register_css_style', $this->plugin_url . 'assets/css/register-style.css');
        wp_enqueue_script('register_js_script', $this->plugin_url . 'assets/js/register-script.js', array('jquery'));
        wp_localize_script('register_js_script', 'register_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nextNonce' => wp_create_nonce('myajax-next-nonce'),
        ));
    }
}
