<?php
/**
 * @package  TakedownQuestionsPlugin
 */
namespace Inc\Pages;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;

/**
 *
 */
class Admin extends BaseController
{
    public $settings;

    public $callbacks;

    public $pages = array();

    public $subpages = array();

    public function register()
    {
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->setPages();
        $this->setSubPages();
        $this->setSections();
        $this->setSettings();        
        $this->setFields();
        $this->settings->addPages($this->pages)->withSubPage('Dashboard')->register();
    }

    public function setPages(){
        $this->pages = array(
            array(
                'page_title' => 'Takedown Questionnaire Plugin',
                'menu_title' => 'Alert',
                'capability' => 'manage_options',
                'menu_slug' => 'tqc_plugin',
                'callback' => array($this->callbacks, 'adminDashboard'),
                'icon_url' => 'dashicons-universal-access-alt',
                'position' => 110,
            ),
        );
    }

    public function setSubPages(){
        $this->subpages = array(
            array(
                'parent_slug' => 'tqc_plugin',
                'page_title' => 'Custom Post Types',
                'menu_title' => 'testitem1',
                'capability' => 'manage_options',
                'menu_slug' => 'alecaddd_cpt',
                'callback' => array($this->callbacks, 'testitem1'),
            ),
        );
    }  

    public function setSettings(){
        $args =[
            [
                'option_group' => 'tqc_options_group',
                'option_name' => 'tqc_text_example',
                'callback' => array( $this->callbacks, 'tqcOptionsGroup')
            ],
            [
                'option_group' => 'tqc_options_group',
                'option_name' => 'first_name',
            ],
        ];
        $this->settings->setSettings($args);
    }

    public function setSections(){
        $args =[
            [
                'id' => 'tqc_admin_index',
                'title' => 'Settings',
                'callback' => array( $this->callbacks, 'tqcAdminSection'),
                'page' => 'tqc_plugin'
            ],
        ];
        $this->settings->setSections($args);
    }

    public function setFields(){
        $args =[
            [
                'id' => 'tqc_text_example',
                'title' => 'TQC Text Example',
                'callback' => array( $this->callbacks, 'tqcTextExample'),
                'page' => 'tqc_plugin',
                'section' => 'tqc_admin_index',
                'args' => array(
                    'label_for' => 'tqc_text_example',
                    'class' => 'example-class'
                ),
            ],
            [
                'id' => 'tqc_first_name',
                'title' => 'TQC First Name',
                'callback' => array( $this->callbacks, 'tqcFirstName'),
                'page' => 'tqc_plugin',
                'section' => 'tqc_admin_index',
                'args' => array(
                    'label_for' => 'tqc_first_name',
                    'class' => 'example-class'
                ),
            ],
        ];
        $this->settings->setFields($args);
    }
}
