<?php

namespace Cover\Base\StateTypes;

use Cover\Base\ReportController;

class Done {

    public $lang;
    public $path;

    public function __construct($language) {
        $this->lang = $language;
        $this->path = plugin_dir_path( dirname(__FILE__, 3));

    }

    public function show_message() {
        $string_file = json_decode(file_get_contents($this->path . "assets/base/" . $this->lang . "/alert_strings.json"), true);
        $html = "<p>".$string_file['alert_confirmation']."</p>";
        $html .= "<a class='button' id='ok'>".$string_file['ok']."<a/> ";
        echo $html;
    }

    public function done() {
        $string_file = json_decode(file_get_contents($this->path . "assets/base/" . $this->lang . "/alert_strings.json"), true);
        $html = "<h5>".$string_file['thanks']."</h5>";
        echo $html;
    }
}
