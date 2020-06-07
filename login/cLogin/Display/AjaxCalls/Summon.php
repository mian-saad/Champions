<?php
/**
 * @package register
 */
namespace Contain\Display\AjaxCalls;

use \Contain\Base\BaseController;
use Contain\Display\Controller;
use Contain\Display\View\FailurePage;
use Contain\Display\View\LandingPage;

session_start();

class Summon extends BaseController {

    // Registers Summon
    public function register() {
        add_action('wp_ajax_summon', array($this, 'summon'));
        add_action('wp_ajax_nopriv_summon', array($this, 'summon'));
    }

    // Summon Controller
    public function summon(){

        $clicked = sanitize_text_field( $_GET['id'] );
        $loggedState = new Controller\ControlCenter();
        $loggedState->controlLogic($clicked);


        wp_die();
    }
}
