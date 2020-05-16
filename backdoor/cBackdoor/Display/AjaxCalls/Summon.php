<?php
/**
 * @package register
 */
namespace Route\Display\AjaxCalls;

use \Route\Base\BaseController;
use Route\Display\Controller;
use Route\Display\View\FailurePage;
use Route\Display\View\BackdoorLandingPage;

//session_start();

class Summon extends BaseController {

    // Registers Summon
    public function register() {
        add_action('wp_ajax_summon', array($this, 'summon'));
        add_action('wp_ajax_nopriv_summon', array($this, 'summon'));
    }

    // Summon Controller
    public function summon(){

        $clicked = sanitize_text_field( $_GET['id'] );


        $loggedState = new Controller\ControlCenter1();
        $loggedState->controlLogic($clicked);


        wp_die();
    }
}
