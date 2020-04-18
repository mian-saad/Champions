<?php
/**
 * @package register
 */
namespace Contain\Base;

use \Contain\Base\BaseController;

session_start();

class Summon extends BaseController {

    // Registers Summon
    public function register() {
        add_action('wp_ajax_summon', array($this, 'summon'));
        add_action('wp_ajax_nopriv_summon', array($this, 'summon'));
    }

    // Redirection Conditions
    public function summon(){

        $clicked = sanitize_text_field( $_GET['id'] );

        switch ($clicked) {

            case 'FormLogin':
                $loggedState = new LoggedStates\LoginForm();
                $loggedState->arenaLogin();
                break;

            case 'alertBack':
            case 'login':
                $loggedState = new LoggedStates\LandingPage();
                $loggedState->loggedMain();
                break;

            case 'alerter':
                $loggedState = new LoggedStates\DiscussionPage();
                $loggedState->loggedParticipants();
                break;

            case 'cmnt':
                $loggedState = new LoggedStates\DiscussionPage();
                $loggedState->loggedAddComments();
                break;

            case 'showcmnt':
                $loggedState = new LoggedStates\DiscussionPage();
                $loggedState->loggedDisplayComments();
                break;

            case 'join':
                $loggedState = new LoggedStates\LandingPage();
                $loggedState->loggedAlert();
                break;

            case 'inpro':
                $loggedState = new LoggedStates\DiscussionPage();
                $loggedState->loggedPageSection();
                break;

            case 'addRecommendation':
                $loggedState = new LoggedStates\DiscussionPage();
                $loggedState->loggedDisplayRecommendation();
                break;

            case 'updateRecommendation':
                $loggedState = new LoggedStates\DiscussionPage();
                $loggedState->loggedUpdateRecommendation();
                break;

            case 'invitation':
                $loggedState = new LoggedStates\DiscussionPage();
                $inviteEmail = sanitize_text_field( $_GET['inviteEmail'] );
                $loggedState->send_invite($inviteEmail);
                break;
        }

        wp_die();
    }
}