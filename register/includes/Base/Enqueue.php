<?php
/**
 * @package  TakedownQuestionsPlugin
 */
namespace Includes\Base;

use \Includes\Base\BaseController;

/**
 *
 */
class Enqueue extends BaseController
{
    public function register()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend'));
    }

    // enqueues documents
    public function enqueue_frontend()
    {
        // enqueue our js and css
        wp_enqueue_style('registration_css_bootstrap', $this->plugin_url . 'assets/css/registration_bootstrap.css');
        wp_enqueue_style('registration_css_bootstrap-grid', $this->plugin_url . 'assets/css/registration_bootstrap-grid.css');
        wp_enqueue_style('registration_css_bootstrap-reboot', $this->plugin_url . 'assets/css/registration_bootstrap-reboot.css');
        wp_enqueue_style('registration_css_style', $this->plugin_url . 'assets/css/registration_style.css');
        wp_enqueue_script('registration_js_script', $this->plugin_url . 'assets/js/registration_script.js', array('jquery'));
        wp_localize_script('registration_js_script', 'registration_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nextNonce' => wp_create_nonce('myajax-next-nonce'),
        ));
    }
}
