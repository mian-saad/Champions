<?php
/**
 * @package  TakedownQuestionsPlugin
 */
namespace Comprise\Base;

/**
 *
 */
class AjaxCalls
{
    public function register()
    {
        add_action('wp_ajax_nopriv_retrieve_question', array($this, 'retrieve_question'));
        add_action('wp_ajax_retrieve_question', array($this, 'retrieve_question'));

        add_action('wp_ajax_nopriv_go_back', array($this, 'go_back'));
        add_action('wp_ajax_go_back', array($this, 'go_back'));

        add_action('wp_ajax_nopriv_submit_case', array($this, 'submit_case'));
        add_action('wp_ajax_submit_case', array($this, 'submit_case'));
    }

    // in data field, we want to store the answers to the question
    public function retrieve_question()
    {
        $html = "";
        if (!empty($_GET['report_id'])) { // if we are in a report scope
            $report_id = $_GET['report_id'];

            $report_controller = unserialize(base64_decode(get_transient($report_id)));

            $report_controller->process_response($_GET['answer']);

            $html .= $report_controller->generate_fuel();

            $base64_serial = base64_encode(serialize($report_controller));
            set_transient($report_id, $base64_serial, 60 * 60 * 12); // store the controller in the wp-cache or transient for further use (for 12 hours)

        }
        else { // we are starting new report here
            # if language is not set properly, we will throw errors
            $d = $_GET['lang'];
            if (empty($_GET['lang']) or !in_array($_GET['lang'], ['en', 'it', 'ge', 'spa', 'ro', 'no', 'pol', 'cz', 'sl', 'ne', 'is', 'fr', 'gr', 'bu', 'por' ])) {
                echo "Don't make me laugh!";
                wp_die();
            }
            $report_id = $this->getPseudoRandomString(19) . uniqid(); // 19+13 = 32
            $report_controller = new ReportController();
            // lets read the language input value and pass to the shit
            $report_controller->init($report_id, $_GET['lang']);
            $html .= $report_controller->generate_fuel();
            // serialize the object and store the controller in the wp-cache or transient for further use (for 12 hours)
            $base64_serial = base64_encode(serialize($report_controller));
            set_transient($report_id, $base64_serial, 60 * 60 * 12);
        }
        echo $html;

        wp_die();
    }

    public function go_back()
    {
        $html = "";

        if (!empty($_GET['report_id'])) { // if we are in a report scope
            $report_id = $_GET['report_id'];
            $report_controller = unserialize(base64_decode(get_transient($report_id)));

            $report_controller->go_back();

            $html .= $report_controller->generate_fuel();

            $base64_serial = base64_encode(serialize($report_controller));
            set_transient($report_id, $base64_serial, 60 * 60 * 12); // store the controller in the wp-cache or transient for further use (for 12 hours)

        } else { // something went wrong not consistent parameter, throw security warning or exception maybe, lets reload the page
            wp_die();
        }
        echo $html;

        wp_die();
    }

    public function submit_case()
    {
        if (!empty($_GET['report_id'])) { // if we are in a report scope

            // unserialize report_controller, generate content
            $report_id = $_GET['report_id'];

            $report_controller = unserialize(base64_decode(get_transient($report_id)));

            $report_controller->db_store();
        }

        wp_die();
    }

    // random string generator for the report ids
    public function getPseudoRandomString($length)
    {
        $base64Chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';

        for ($i = 0; $i < $length; ++$i) {
            $result .= $base64Chars[mt_rand(0, strlen($base64Chars) - 1)];
        }

        return $result;
    }
}
