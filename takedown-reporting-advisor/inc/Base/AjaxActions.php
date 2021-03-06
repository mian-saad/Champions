<?php
/**
 * @package  TakedownQuestionsPlugin
 */
namespace Inc\Base;

/**
 *
 */
class AjaxActions
{
    public function register()
    {
        add_action('wp_ajax_nopriv_fetch_question', array($this, 'fetch_question'));
        add_action('wp_ajax_fetch_question', array($this, 'fetch_question'));

        add_action('wp_ajax_nopriv_step_back', array($this, 'step_back'));
        add_action('wp_ajax_step_back', array($this, 'step_back'));

        add_action('wp_ajax_nopriv_submit_report', array($this, 'submit_report'));
        add_action('wp_ajax_submit_report', array($this, 'submit_report'));
    }

    // in data field, we want to store the answers to the question
    public function fetch_question()
    {
        $html = "";
        if (!empty($_GET['report_id'])) { // if we are in a report scope
            $report_id = $_GET['report_id'];

            $report_controller = unserialize(base64_decode(get_transient($report_id)));

            $report_controller->process_response($_GET['answer']);

            $html .= $report_controller->generate_content();

            $base64_serial = base64_encode(serialize($report_controller));
            set_transient($report_id, $base64_serial, 60 * 60 * 12); // store the controller in the wp-cache or transient for further use (for 12 hours)

        } else { // we are starting new report here
            # if language is not set properly, we will throw errors
            if (empty($_GET['lang']) or !in_array($_GET['lang'], ['en', 'it', 'ge', 'spa', 'ro', 'no', 'pol', 'cz', 'sl', 'ne', 'is', 'fr', 'gr', 'bu', 'por' ])) {
                echo "Don't make me laugh!";
                wp_die();
            }
            $report_id = $this->getPseudoRandomString(19) . uniqid(); // 19+13 = 32
            $report_controller = new ReportController();
            // lets read the language input value and pass to the shit
            $report_controller->init($report_id, $_GET['lang']);
            $html .= $report_controller->generate_content();
            // serialize the object and store the controller in the wp-cache or transient for further use (for 12 hours)
            $base64_serial = base64_encode(serialize($report_controller));
            set_transient($report_id, $base64_serial, 60 * 60 * 12);
        }
        echo $html;

        wp_die();
    }

    public function step_back()
    {
        $html = "";

        if (!empty($_GET['report_id'])) { // if we are in a report scope
            $report_id = $_GET['report_id'];
            $report_controller = unserialize(base64_decode(get_transient($report_id)));

            $report_controller->step_back();

            $html .= $report_controller->generate_content();

            $base64_serial = base64_encode(serialize($report_controller));
            set_transient($report_id, $base64_serial, 60 * 60 * 12); // store the controller in the wp-cache or transient for further use (for 12 hours)

        } else { // something went wrong not consistent parameter, throw security warning or exception maybe, lets reload the page
            wp_die();
        }
        echo $html;

        wp_die();
    }

    public function submit_report()
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
