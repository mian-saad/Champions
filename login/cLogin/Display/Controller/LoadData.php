<?php

namespace Contain\Display\Controller;

class LoadData {

    public function loadDEmail($Data) {
        global $wpdb;
        $counter = 0;
        $alertData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}tra_reports", OBJECT );
        $arenaData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}arena", OBJECT );

        for ($counter; $counter<count($alertData); $counter++) {
            $FirstName[$counter] = $alertData[$counter] -> reporter_fName;
            $LastName[$counter] = $alertData[$counter] -> reporter_lName;
            $Email[$counter] = $alertData[$counter] -> reporter_email;
            $Residence[$counter] = $alertData[$counter] -> reporter_residence;
            $Title[$counter] = $alertData[$counter] -> title;
            $EventTime[$counter] = $alertData[$counter] -> event_time;
            $EventCategory[$counter] = $alertData[$counter] -> event_category;
            $EventDescription[$counter] = $alertData[$counter] -> event_description;
            $EventDescriptionSubject[$counter] = $alertData[$counter] -> description_subject;
            $AlertStatus[$counter] = $alertData[$counter] -> alert_status;
        }

        if ($Data === 'email') {
            return $Email;
        }
    }

}