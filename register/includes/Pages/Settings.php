<?php
/**
 * @package register
 */
namespace Includes\Pages;

use \Includes\Base\BaseController;

class Settings extends BaseController {

  public function register(){
    add_action( "plugin_action_links_" . $this->plugin_basename, array($this, 'infoSection') );
  }

  function infoSection($links){
    $usage = '<a href="admin.php?page=register_plugin">How to Use</a>';
    array_push($links,$usage);
    return $links;
  }
}
