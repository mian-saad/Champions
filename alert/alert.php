<?php
/**
 * @package TakedownQuestionsPlugin
 */
/*
Plugin Name: Alert
Plugin URI: localhost
Description: Alert Module
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

function activate_tqc(){
    Inc\Base\Activate::activate();
}

function deactivate_tqc(){
    Inc\Base\Deactivate::deactivate();
}

register_activation_hook(__FILE__, 'activate_tqc');
register_deactivation_hook(__FILE__, 'deactivate_tqc');

if (class_exists('Inc\\Init')) {
    Inc\Init::register_services();
}


