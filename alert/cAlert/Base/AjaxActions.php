<?php
/**
 * @package  TakedownQuestionsPlugin
 */
namespace Cover\Base;

use \Cover\Base\StateTypes;

class AjaxActions
{
    public function register() {
        add_action('wp_ajax_nopriv_get_question', array($this, 'get_question'));
        add_action('wp_ajax_get_question', array($this, 'get_question'));

        /*add_action('wp_ajax_nopriv_authenticate', array($this, 'authenticate'));
        add_action('wp_ajax_authenticate', array($this, 'authenticate'));*/

        add_action('wp_ajax_nopriv_get_back', array($this, 'get_back'));
        add_action('wp_ajax_get_back', array($this, 'get_back'));

        add_action('wp_ajax_nopriv_get_report', array($this, 'get_report'));
        add_action('wp_ajax_get_report', array($this, 'get_report'));

        add_action('wp_ajax_nopriv_thanks', array($this, 'thanks'));
        add_action('wp_ajax_thanks', array($this, 'thanks'));
    }

    // in data field, we want to store the answers to the question
    public function get_question() {
        $html = "";
        if (!empty($_GET['alert_id'])) {

            // if we are in a report scope
            $alert_id = $_GET['alert_id'];
            $report_controller = unserialize(base64_decode(get_transient($alert_id)));
            $report_controller->process_response($_GET['answer']);
            $html .= $report_controller->generate_content();
            if ($report_controller->oldstate == "M1.9") {
                $flp_id = $this->getPseudoRandomString(19) . uniqid();
                $report_controller->flp_id = $flp_id;
                $report_controller->flp_db_store($flp_id);
            }
            $base64_serial = base64_encode(serialize($report_controller));
            set_transient($alert_id, $base64_serial, 60 * 60 * 12); // store the controller in the wp-cache or transient for further use (for 12 hours)
        }
        else { // we are starting new report here

            # if language is not set properly, we will throw errors
            if (empty($_GET['lang']) or !in_array($_GET['lang'], ['en', 'ge', 'hun', 'ro', 'pol'])) {
                echo "Language Not Set Properly";
                wp_die();
            }
            $_SESSION['language'] = $_GET['lang'];
            $alert_id = $this->getPseudoRandomString(19) . uniqid(); // 19+13 = 32
            $report_controller = new ReportController();
            // lets read the language input value and pass to the shit
            $report_controller->init($alert_id, $_GET['lang']);
            $html .= $report_controller->generate_content();
            // serialize the object and store the controller in the wp-cache or transient for further use (for 12 hours)
            $base64_serial = base64_encode(serialize($report_controller));
            set_transient($alert_id, $base64_serial, 60 * 60 * 12);
        }
        echo $html;

        wp_die();
    }

    public function get_back()
    {
        $html = "";

        if (!empty($_GET['alert_id'])) { // if we are in a report scope
            $alert_id = $_GET['alert_id'];
            $report_controller = unserialize(base64_decode(get_transient($alert_id)));

            $report_controller->step_back();

            $html .= $report_controller->generate_content();

            $base64_serial = base64_encode(serialize($report_controller));
            set_transient($alert_id, $base64_serial, 60 * 60 * 12); // store the controller in the wp-cache or transient for further use (for 12 hours)

        } else { // something went wrong not consistent parameter, throw security warning or exception maybe, lets reload the page
            wp_die();
        }
        echo $html;

        wp_die();
    }

    public function get_report() {
        if (!empty($_GET['alert_id'])) { // if we are in a report scope

            // unserialize report_controller, generate content
            $alert_id = $_GET['alert_id'];

            $report_controller = unserialize(base64_decode(get_transient($alert_id)));

            $report_controller->db_store();

        }

        wp_die();
    }

    public function thanks() {

        if ($_GET['alert_id'] === 'thankyou') {
            $thankyou = new StateTypes\ThankYou($_SESSION['language']);
            $thankyou->show_message();
        }

        wp_die();
    }

    // random string generator for the report ids
    public function getPseudoRandomString($length) {
        $base64Chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';

        for ($i = 0; $i < $length; ++$i) {
            $result .= $base64Chars[mt_rand(0, strlen($base64Chars) - 1)];
        }

        return $result;
    }

    // No Use
    public function authenticate() {
        /*global $wpdb;
        $Data = $wpdb->get_results( "SELECT email, password FROM {$wpdb->prefix}arena", OBJECT );
        $email = sanitize_text_field( $_GET['email'] );
        $pass = sanitize_text_field( $_GET['pass'] );
        for ($counter = 0; $counter<count($Data); $counter++) {
            if ($email === $Data[$counter] -> email) {
                if ($pass === $Data[$counter] -> password) {
                    $NewReport = new StateTypes\TraAlert();
                    echo $NewReport->generate_html();
                    wp_die();
                }
            }
        }
        return false;*/

        /*$alert_id = $_GET['alert_id'];
        $report_controller = unserialize(base64_decode(get_transient($alert_id)));
        $report_controller->process_response($_GET['answer']);
        echo $report_controller->generate_content();
        wp_die();*/
    }
}
