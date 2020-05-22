<?php


namespace Cover\Base\StateTypes;


class TraComposedQuestion extends TraState {
    public $continue_string;

    public function __construct($report_id, $state_code, $state, $continue_string, $back_string, $field_warning, $warning) {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->continue_string = $continue_string;
        $this->warning = $warning;
    }

    public function generate_html() {
        $html="";
        if($this->state['show_header']=="true"){
            $html .= "<h3 class='tra_question'>" . $this->state['state_text'] . "</h3>";
        }
        $html .= $this->generate_hidden_fields($this->report_id);
        $html .= "<form id='tra_question_form'>";

        $totalItemPerLine = 1;
        $totalItem = 1;
        for($i = 0; $i < $totalItem; $i++) {
            if($i % $totalItemPerLine == 0) {
                $html .= '<div class="row">'; // OPEN ROW
            }
            foreach ($this->state['state_answers'] as $answer) {
                if ($answer['type'] == 'text') {
                    $html .= "<div class='col-6'>";
                    $html .= $this->generate_field_warning($answer['id']);
                    $html .= $this->generate_question_text($answer['text']);
                    $html .= "<input type='text' name='" . $answer['id'] . "' value='" . $this->response[$answer['id']] . "'><br>";
                    $html .= "</div>";
                }
                else if ($answer['type'] == 'select') {
                    $html .= "<div class='col-12'>";
                    $html .= $this->generate_question_text($answer['text']);
                    $html .= $this->generate_select_question($answer);
                    $html .= "</div>";
                }
            }
            if($i % $totalItemPerLine == ($totalItemPerLine-1)) {
                $html .= '</div>'; // CLOSE ROW
            }
        }

        $html .= "</form>";
        $html .= $this->generate_buttons();
        $html .= $this->generate_warning();
        return $html;
    }

    // if every answer id is in response, and is not empty, return true
    public function validate($response) {
        $this->response = $response;

        foreach ($this->state['state_answers'] as $state_answer) {
            if (!(array_key_exists($state_answer['id'], $response) and $response[$state_answer['id']] != "")) {
                return false;
            }
        }

        global $wpdb;
        $arenaData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}arena", OBJECT );
        for ($counter = 0; $counter<count($arenaData); $counter++) {
            if ($arenaData[$counter] -> email === $response['reporter_email']) {
                return true;
            }
        }
        return "Unregistered";
//        return true;
    }

    public function generate_buttons() {
        return "<div id='tra_button_pane'><a class='button' id='tra_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='tra_continue' href='#' onclick='return false;'>$this->continue_string</a></div>";
    }

    public function generate_select_question($answer) {
        $counter = 0; // need it for labeling and stuff
        $html = '<div class="tra_select_answers ">';
        $html .= "<select id='title' name='title'>";

        foreach ($answer['answers'] as $answer_option) {
            // if we got something in response
            if (!empty($this->response) and array_key_exists($answer['id'], $this->response) and $this->response[$answer['id']] == $answer_option['id']) {
                // this checkbox was checked previously
                $html .= '<div class="tra_horizontal_choice"><option class="tra_quiz_select" name="' . $answer['id'] . '" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" selected><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label></div>';
            } else { // else
                $html .= '<div class="tra_horizontal_choice"><option class="tra_quiz_select" name="' . $answer['id'] . '" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" ><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label></div>';
            }
            $counter += 1;
        }

        $html .= "</select>";
        $html .= '</div>';

        return $html;
    }

    public function generate_readable_response_array() {
        $result = [];

        foreach ($this->state['state_answers'] as $answer) {
            $key = $answer['short_text'];
            if ($answer['type'] == 'text') { // for text answers , just store the received input
                $value = $this->response[$answer['id']];
            } else if ($answer['type'] == 'select') { // need to search in the answer array for the response id in the answers
                foreach ($answer['answers'] as $radio_answer) {
                    if ($this->response[$answer['id']] == $radio_answer['id']) {
                        $value = $radio_answer['short_text'];
                    }
                }
            }
            $result[$key] = $value;
        }
        return $result;
    }
}