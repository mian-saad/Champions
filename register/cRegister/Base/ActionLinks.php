<?php
/**
 * @package  TakedownQuestionsPlugin
 */
namespace Comprise\Base;

use \Comprise\Base\BaseController;
/**
 *
 */
class ActionLinks extends BaseController
{
    public function register()
    {
        add_filter("plugin_action_links_". $this->plugin_basename, array($this, 'settings_link'));
    }

    // enqueues documents
    public function settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=takedown_questionnaire_plugin">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
}
