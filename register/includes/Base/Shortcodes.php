<?php
/**
 * @package  TakedownQuestionsPlugin
 */
namespace Includes\Base;

use \Includes\Base\BaseController;
/**
 *
 */
class Shortcodes extends BaseController
{
    public function register()
    {
        add_shortcode('register', array($this, 'add_shortcode_content'));
    }

    // adds a php file to the template
    public function add_shortcode_content(){
        //turn on output buffering to capture script output
        ob_start();
        //include the specified file
        include( $this->plugin_path.'page-templates/questionnaire-template.php');
        //assign the file output to $content variable and clean buffer
        $content = ob_get_clean();
        //return the $content
        //return is important for the output to appear at the correct position
        return $content;        
    }

    
}



