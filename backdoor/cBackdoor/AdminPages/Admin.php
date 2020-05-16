<?php
/**
 * @package register
 */
namespace Route\AdminPages;

use \Route\Base\BaseController;

class Admin extends BaseController {

  public function register(){
    add_action( 'admin_menu', array( $this, 'add_admin_pages'));
  }

  function add_admin_pages(){
    add_menu_page( 'Login', 'Arena Login', 'manage_options', 'login_plugin', array($this, 'admin_index'), 'dashicons-admin-users', 120);
  }

  function admin_index(){
    require_once $this->plugin_path . 'templates/admin.php';
  }

}
