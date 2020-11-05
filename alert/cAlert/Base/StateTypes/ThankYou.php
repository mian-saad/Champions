<?php

namespace Cover\Base\StateTypes;

use Cover\Base\ReportController;

class ThankYou {

    public function show_message() {
        $html = "<h2>Thank You for using the Platform. Redirecting back to ALERT</h2>";
        echo $html;
    }
}
