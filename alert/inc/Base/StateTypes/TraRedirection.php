<?php


namespace Inc\Base\StateTypes;


class TraRedirection extends TraState
{



    public function generate_html() {

        echo "<h3 class='cus_margin'>Alert Summary</h3>";
//        $html = $this->generate_hidden_fields($this->report_id);
//
//        $html .= "<p>FLP: " . $this->flp . "</p>";
//        foreach ($this->answers as $short_text => $value) {
//            $html .= "<p>" . $short_text . " : " . $value . "</p>";
//        }
//
//        $html .= $this->generate_buttons();
//        return $html;
    }

    // if at least one category is chosen, then we pass the validation
//    public function validate($response)
//    { // how do we wanna store the items to the db
//        return true;
//    }
//
//    public function generate_buttons()
//    {
//        echo '<script>';
//        echo 'function myFunction() {';
//        echo 'alert(\'Your alert has been sent via email!\')}';
//        //echo 'window.location.href = "http://localhost:8888/alert";';
//        echo '</script>';
//        return "<div id='tra_button_pane'><a class='button' href='#' onclick='location.reload();'>DONE</a> <a class='button' id='tra_submit' href='$this->pdfurl' onclick='myFunction()' download>$this->submit_string</a></div>";
//    }
}
