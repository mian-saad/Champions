<?php
/**
 * '@package AlertQuestionsPlugin
 */
namespace Cover\Base;

use \Cover\Base\BaseController;
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
        $settings_link = '<a href="admin.php?page=alert_questionnaire_plugin">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
}
