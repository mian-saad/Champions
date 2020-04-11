<?php
/**
 * @package  register
 */
namespace Incl\Base;

//use \Incl\Base\BaseController;

class Shortcode extends BaseController
{
    public function register() {
        add_shortcode('login', array($this, 'add_shortcode_content'));
    }

    // adds a php file to the template
    public function add_shortcode_content() {

        //turn on output buffering to capture script output
        ob_start();

        //include a css or js here via echo just for the part which is being rendered after adding shortcode
        //echo "<link href=\"$this->plugin_url/assets/style.css\" rel=\"stylesheet\"></link>";
        //echo "<script src=\"$this->plugin_url/assets/script.js\"></script>";


        //include the specified file
        require_once("$this->plugin_path/frontend/arena.php");

        //assign the file output to $content variable and clean buffer
        $content = ob_get_clean();

        //return the $content
        //return is important for the output to appear at the correct position
        return $content;
    }
}
