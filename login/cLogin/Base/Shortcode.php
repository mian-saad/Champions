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

        //Include a css or js here via echo just for the part which is being rendered after adding shortcode
        //echo "<link href=\"$this->plugin_url/assets/style.css\" rel=\"stylesheet\"></link>";
        //echo "<script src=\"$this->plugin_url/assets/script.js\"></script>";

        $this->enqueue();

        //include the specified file
        require_once("$this->plugin_path/frontend/arena.php");

        //assign the file output to $content variable and clean buffer
        $content = ob_get_clean();

        //return the $content
        //return is important for the output to appear at the correct position
        return $content;
    }
    function enqueue(){

        //Enqueuing JQuery
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery');
//        wp_enqueue_script('media', $this->plugin_url . 'assets/login.png');

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
