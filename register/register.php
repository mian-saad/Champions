<?php
/**
* @package register
*/
/*
Plugin Name: Register
Plugin URI: localhost:8888/register
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

if (class_exists( 'Includes\\Init' )) {
  Includes\Init::register_services();
}

/**
 * Basic methods to Instantiate
 * Activates creates the Database Table Arena
 * Deactivates drops the created table
 */


class registerServices{

  function activate() {
    Includes\Base\Activate::activate();
  }

  function deactivate() {
    Includes\Base\Deactivate::deactivate();
  }
}

$registerServices = new registerServices;

register_activation_hook( __file__, array($registerServices, 'activate') );
register_deactivation_hook( __file__, array($registerServices, 'deactivate') );
