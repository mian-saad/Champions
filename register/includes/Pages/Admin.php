<?php
/**
 * @package register
 */
namespace Includes\Pages;

use \Includes\Base\BaseController;

class Admin extends BaseController {

  public function register(){
    add_action( 'admin_menu', array( $this, 'add_admin_pages'));
  }

  function add_admin_pages(){
    add_menu_page( 'Register', 'Arena Register', 'manage_options', 'register_plugin', array($this, 'admin_index'), 'dashicons-buddicons-buddypress-logo', 110);
  }

  function admin_index(){
    require_once $this->plugin_path . 'templates/admin.php';
  }

}
