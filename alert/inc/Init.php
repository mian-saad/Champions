<?php
/**
 * @package TakedownQuestionsPlugin
 */
namespace Inc;

final class Init
{
    /**
     * Store all the classes inside an array
     * @return array Full list of classes
     */
    public static function get_services()
    {
        return [
            Base\AjaxActions::class,
            Pages\Admin::class,
            Base\Enqueue::class,
            Base\ActionLinks::class,
            //Base\TemplateController::class,
            Base\Shortcodes::class,
            
        ];
    }

    /**
     * Loop through the classes, initialize them,
     * and call the register() method if it exists
     * @return
     */
    public static function register_services()
    {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    /**
     * Initialize the class
     * @param  class $class    class from the services array
     * @return class instance  new instance of the class
     */
    private static function instantiate($class)
    {
        $service = new $class();

        return $service;
    }
}

/*
use Inc\TestActivate;
use Inc\TestDeactivate;

class TakedownQuestionsPlugin
{
// Plugin Lifecycle: activation, deactivation, uninstallation
public $plugin_name;

// constructor
function __construct()
{
$this->plugin_name = plugin_basename(__FILE__);
}

// adds action to enqueue a document

// add custom settings link to the plugins page
function settings_link($links){
$settings_link = '<a href="admin.php?page=takedown_questionnaire_plugin">Settings</a>';
array_push($links, $settings_link);
return $links;
}

// triggers on plugin activation
function activate()
{
// generate a CPT
$this->custom_post_type();
TestActivate::doSomething();
// flush rewrite rules
flush_rewrite_rules();
}

// triggers on plugin deactivation
function deactivate()
{
TestDeactivate::doSomething();
// flush rewrite rules
flush_rewrite_rules();
}

// triggers on plugin uninstallation
function uninstall()
{
// delete CPT
// delete the plugin data from the DB
}

function custom_post_type()
{
register_post_type('tq', ['public' => true, 'label'=>'Takedown Questionaire']);
}

}

// SECURITY CHECK: only instantiate the class if it exists
if (class_exists('TakedownQuestionsPlugin')) {
$tqp = new TakedownQuestionsPlugin();
$tqp ->  register();
}

// register activation, deactivation, uninstallation hooks
register_activation_hook(__FILE__, array( $tqp, 'activate'));
register_deactivation_hook(__FILE__, array( $tqp, 'deactivate'));
 */
