<?php

namespace Cover\Base\StateTypes;

/**
 * Default implementations of some methods for all questions
 */
abstract class TraState
{
    public $state_code;
    public $alert_id;
    public $response;
    public $warning;
    public $show_warning;
    public $field_warning;
    public $back_string;
    public $isValidated;

    // this function should generate the html string for the question
    abstract public function generate_html();

    // this function is a helper function to generate the buttons
    abstract public function generate_buttons();

    // this function validates the input of the user stores a response value
    abstract public function validate($response);

    // this function generates hidden fields for the report
    public function generate_hidden_fields($alert_id)
    {
        return $html = "<input type='hidden' id='alert_id' name='alert_id' value='" . $this->alert_id . "'>";
    }

    public function generate_question_title($text)
    {
        return "<h3 class='register_question'>" . $text . "</h3>";
    }

    public function generate_question_text($text)
    {
        return "<p class='alert_question'><b>" . $text . "</b></p>";
    }

    // prints warning message only if show_warning is set
    public function generate_warning() {
        if ($this->show_warning) {
            return "<p class='alert_warning'>" . $this->warning . "</p>";
        } else {
            return "";
        }
    }

    // smart print - receiving the id of field value, checks whether there is a cached answer... if not - prints field warning message
    public function generate_field_warning($fieldId)
    {
        if (!empty($this->response) and $this->show_warning and (!array_key_exists($fieldId, $this->response) or $this->response[$fieldId] == "")) {
            return "<p class='alert_warning'>" . $this->field_warning . "</p>";
        } else {
            return "";
        }
    }
}