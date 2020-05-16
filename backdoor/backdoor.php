<?php
/**
* @package register
*/
/*
Plugin Name: Backdoor
Plugin URI: localhost:8888/backdoor
Description: This is a plugin to register the domain experts.
Version: 1.0.0
Author: Saad Aziz
Author URI: localhost
License: TU Darmstadt
*/

defined('ABSPATH') or die('Not Accessible !');

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
  require_once dirname(__FILE__) . '/vendor/autoload.php';
}

if (class_exists( 'Route\\Init' )) {
    Route\Init::register_services();
}

/**
 * Basic methods to Instantiate
 * Activates creates the Database Table Arena
 * Deactivates drops the created table
 */

class registerPath {

  function activate() {
      Route\Base\Activate::activate();
  }

  function deactivate() {
      Route\Base\Deactivate::deactivate();
  }
}

$registerPath = new registerPath;

register_activation_hook( __file__, array($registerPath, 'activate') );
register_deactivation_hook( __file__, array($registerPath, 'deactivate') );
