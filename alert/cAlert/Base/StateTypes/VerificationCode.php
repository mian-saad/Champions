<?php


namespace Cover\Base\StateTypes;


class VerificationCode extends TraState
{
    public $continue_string;

    public function __construct($alert_id, $state_code, $state, $continue_string, $back_string, $field_warning, $warning, $complete_registration_string)
    {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->alert_id = $alert_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->continue_string = $continue_string;
        $this->complete_registration_string = $complete_registration_string;
        $this->warning = $warning;
    }

    public function generate_html()
    {
        $html = $this->generate_hidden_fields($this->alert_id);
        $html .= $this->generate_question_title($this->state['short_text']);
        $html .= "<form id='alert_question_form'>";
        $html .= $this->generate_question_text($this->state['state_text']);
        $html .= '<input id="' . $this->state['id'] . '" type="number" name="' . $this->state['id'] . '" value="' . $this->response[$this->state['id']] . '" />';
        $html .= "</form>";
        $html .= $this->generate_buttons();
        $html .= $this->generate_warning();
        return $html;
    }

    // if every answer id is in response, and is not empty, return true
    public function validate($response)
    {
        $x= $_SESSION["verifyCode"];
        $y = $response;
        // how do we wanna store the items to the db
        if ((array_key_exists($this->state['id'], $response)) && ($_SESSION["verifyCode"] == $response['verification'])) {
            $this->response = $response;
            $_SESSION['validate'] = true;
            return true;
        }
        return false;
    }

    public function generate_buttons()
    {
        return "<div id='register_button_pane'><a class='button' id='alert_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='alert_continue' href='#' onclick='return false;'>$this->complete_registration_string</a></div>";
    }

    public function generate_readable_response_array()
    {
        $result[$this->state['short_text']] = $this->response[$this->state['id']];
        return $result;
    }
}