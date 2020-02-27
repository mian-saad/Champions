<?php
/**
 * @package  register
 */
namespace Includes\Base;

use \Includes\Base\BaseController;

class Shortcode extends BaseController
{
    public function register() {
        add_shortcode('arena-register', array($this, 'add_shortcode_content'));
        add_action( 'wp_ajax_getData', array($this, 'getData'));
        add_action( 'wp_ajax_nopriv_getData', array($this, 'getData'));
    }

    public function getData(){
      $first_name = sanitize_text_field( $_POST['first_name'] );
      $last_name = sanitize_text_field( $_POST['last_name'] );
      $birthday = sanitize_text_field( $_POST['birthday'] );
      $gender = sanitize_text_field( $_POST['gender'] );
      $email = sanitize_email( $_POST['email'] );
      $password = sanitize_text_field( $_POST['password'] );
      $skills = sanitize_text_field( $_POST['skills'] );

      $data = array(
        'first_name' => $first_name,
        'last_name' => $last_name,
        'birthday' => $birthday,
        'gender' => $gender,
        'email' => $email,
        'password' => $password,
        'skills' => $skills
      );
      echo $data;

      global $wpdb;
      $tra_reports_db_name = $wpdb->prefix . 'arena';
      $wpdb->insert($tra_reports_db_name, $data);

    }

    // adds a php file to the template
    public function add_shortcode_content() {
        //turn on output buffering to capture script output
        ob_start();
        //inclde a css or js here via echo
        echo "<link href=\"$this->plugin_url/assets/main.css\" rel=\"stylesheet\"></link>";
        echo "<script src=\"$this->plugin_url/assets/script.js\"></script>";
        //include the specified file
        require_once("$this->plugin_path/frontend/FirstPage.php");
        //assign the file output to $content variable and clean buffer
        $content = ob_get_clean();
        //return the $content
        //return is important for the output to appear at the correct position
        return $content;
    }
}
