<?php
/**
 * @package TakedownQuestionsPlugin
 */
/*
Plugin Name: Register
Plugin URI: localhost
Description: Arena Registration Module
Version: 1.0.0
Author: Maksim Melnik / Saad Aziz
Author URI: localhost
License: TU Darmstadt
 */

// SECURITY CHECK: die if the plugin gets accessed to externally
defined('ABSPATH') or die('Die!');

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

function activate_reg(){
    Comprise\Base\Activate::activate();
}

function deactivate_reg(){
    Comprise\Base\Deactivate::deactivate();
}

register_activation_hook(__FILE__, 'activate_reg');
register_deactivation_hook(__FILE__, 'deactivate_reg');

if (class_exists('Comprise\\Init')) {
    Comprise\Init::register_services();
}


