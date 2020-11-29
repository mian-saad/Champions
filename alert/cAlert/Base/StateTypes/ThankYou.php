<?php

namespace Cover\Base\StateTypes;

use Cover\Base\ReportController;

class ThankYou {

    public $lang;

    public function __construct($language) {
        $this->lang = $language;
    }

    public function show_message() {
        $plugin_path = plugin_dir_path( dirname(__FILE__, 3));
        $string_file = json_decode(file_get_contents($plugin_path . "assets/base/" . $this->lang . "/alert_strings.json"), true);
        $html = "<h2>".$string_file['thanks']."</h2>";
        echo $html;
    }
}
