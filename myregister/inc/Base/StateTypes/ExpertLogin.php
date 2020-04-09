<?php


namespace Inc\Base\StateTypes;


class ExpertLogin extends TraState {

    public $continue_string;

    public function __construct($report_id, $state_code, $state, $login_string, $back_string, $field_warning, $warning)
    {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->login_string = $login_string;
        $this->warning = $warning;
    }

    public function generate_html()
    {
        $html = $this->generate_hidden_fields($this->report_id);

        $html .= "<div class='row'>";
        $html .= "<div class='col-3'></div>";
        $html .= "<div class='col-6'>";
        $html .= "<form id='tra_question_form'>";
        foreach ($this->state['state_answers'] as $answer) {
            $html .= $this->generate_question_text($answer['text']);
            $html .= "<input type='text' name='" . $answer['id'] . "' value='" . $this->response[$answer['id']] . "'><br>";
        }
        $html .= "</form>";

        $html .= $this->generate_buttons();
        $html .= $this->generate_warning();
        $html .= "</div>";
        $html .= "<div class='col-3'></div>";
        $html .= "</div>";
        return $html;
    }

    // if every answer id is in response, and is not empty, return true
    public function validate($response)
    {
        // how do we wanna store the items to the db
        if (array_key_exists($this->state['id'], $response)) {
            $this->response = $response;
            return true;
        }
        return false;
    }

    public function generate_buttons()
    {
        return "<div id='tra_button_pane'><a class='button'  id='tra_back' href='#' onclick='window.location.reload();'>$this->back_string</a> <a class='button' id='tra_continue' href='#' onclick='return false;'>$this->login_string</a></div>";
    }

    public function generate_readable_response_array()
    {
        $result[$this->state['short_text']] = $this->response[$this->state['id']];
        return $result;
    }

}