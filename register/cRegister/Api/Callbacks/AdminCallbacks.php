<?php
/**
 * @package  TakedownQuestionsPlugin
 */
namespace Comprise\Api\Callbacks;

use Comprise\Base\BaseController;

class AdminCallbacks extends BaseController
{
    public function adminDashboard(){
        return require_once("$this->plugin_path/templates/admin.php");
    }

    public function testitem1(){
        return require_once("$this->plugin_path/templates/test1.php");
    }

    public function testitem2(){
        return require_once("$this->plugin_path/templates/test2.php");
    }

    public function testitem3(){
        return require_once("$this->plugin_path/templates/test3.php");
    }

    public function tqcOptionsGroup($input){
        // maybe validate the input
        return $input;
    }

    public function tqcAdminSection(){
        echo "Check this beautiful section!";
    }

    public function tqcTextExample(){
        $value = esc_attr( get_option( 'tqc_text_example' ) );
        echo "<input type='text' class='regular-text' name='tqc_text_example' value='".$value."' placeholder='Test Example'>";
    }

    public function tqcFirstName(){
        $value = esc_attr( get_option( 'first_name' ) );
        echo "<input type='text' class='regular-text' name='tqc_text_example' value='".$value."' placeholder='First Name here!'>";
    }

    
}