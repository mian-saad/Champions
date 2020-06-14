<?php


namespace Cover\Base\StateTypes;


class TraDescriptionQuestion extends TraState
{
    public $continue_string;

    public function __construct($report_id, $state_code, $state, $continue_string, $back_string, $field_warning, $warning)
    {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->continue_string = $continue_string;
        $this->warning = $warning;
    }

    public function generate_html()
    {
        $html = $this->generate_hidden_fields($this->report_id);
        if($this->state['show_header']=="true"){
            $html .= "<h4 class='alert_question'>" . $this->state['state_text'] . "</h4>";
        }
        $html .= "<form id='alert_question_form'>";
        foreach ($this->state['state_answers'] as $answer) {
            $html .= $this->generate_question_text($answer['text']);
            $html .= $this->generate_field_warning($answer['id']);

            if ($answer['type'] == 'text') {
                $html .= "<input type='text' name='" . $answer['id'] . "' value='" . $this->response[$answer['id']] . "' required><br>";
            } else if ($answer['type'] == 'description') {
                $html .= "<textarea id='alert_text_big' name='" . $answer['id'] . "' rows='10'>" . $this->response[$answer['id']] . "</textarea>";
            } else if ($answer['type'] == 'date') {
                $html .= "<input rows='10' class='picker' type='text' name='" . $answer['id'] . "' value='" . $this->response[$this->state['id']] . "' />";
//                $html .= "<textarea id='alert_text_big' name='" . $answer['id'] . "' rows='10'>" . $this->response[$answer['id']] . "</textarea>";
            }
        }
        $html .= "</form>";
        $html .= $this->generate_buttons();
        $html .= $this->generate_warning();
        return $html;
    }

    // if at least one category is chosen, then we pass the validation
    public function validate($response) { // how do we wanna store the items to the db
        $this->response = $response;

        foreach ($this->state['state_answers'] as $state_answer) {
            if (!(array_key_exists($state_answer['id'], $response) and $response[$state_answer['id']] != "")) {
                return false;
            }
        }
        return true;
    }

    public function generate_buttons()
    {
        return "<div id='alert_button_pane'><a class='button' id='alert_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='alert_continue' href='#' onclick='return false;'>$this->continue_string</a></div>";
    }

    public function generate_readable_response_array()
    {
        $result = [];

        foreach ($this->state['state_answers'] as $answer) {
            $key = $answer['short_text'];
            if ($answer['type'] == 'text') { // for text answers , just store the received input
                $value = $this->response[$answer['id']];
            } else if ($answer['type'] == 'description') {
                $value = $this->response[$answer['id']];
            } else if ($answer['type'] == 'date') {
                $value = $this->response[$answer['id']];
            }
            $result[$key] = $value;
        }
        return $result;
    }
}