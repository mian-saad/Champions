<?php
/**
 * @package  register
 */
namespace Contain\Base;

//use \Contain\Base\BaseController;

class Shortcode extends BaseController
{
    public function register() {
        add_shortcode('login', array($this, 'add_shortcode_content'));
    }

    // adds a php file to the template
    public function add_shortcode_content() {

        //turn on output buffering to capture script output
        ob_start();

        $this->enqueue();
        echo "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css\" />";
        echo "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js\"></script>";

        //include the specified file
        require_once("$this->plugin_path/frontend/arena.php");

        //assign the file output to $content variable and clean buffer
        $content = ob_get_clean();

        //return the $content
        //return is important for the output to appear at the correct position
        return $content;
    }
    function enqueue(){

        // Enqueue JS and CSS
        wp_enqueue_style('login_css_bootstrap-grid', $this->plugin_url . 'assets/login-bootstrap-grid.css');
        wp_enqueue_style('login_css_bootstrap-reboot', $this->plugin_url . 'assets/login-bootstrap-reboot.css');
        wp_enqueue_style('login_css_style', $this->plugin_url . 'assets/login-style.css');
        wp_enqueue_style('register_css_style', $this->plugin_url . 'assets/register-style.css');

        //Enqueuing Scripts
        wp_enqueue_script('login-script', $this->plugin_url . 'assets/login-script.js');
        wp_localize_script( 'login-script', 'login_ajax',
            array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

        wp_enqueue_script('register_js_script', $this->plugin_url . 'assets/register-script.js');
        wp_localize_script('register_js_script', 'register_object',
            array('ajaxurl' => admin_url('admin-ajax.php')));

    }

}
