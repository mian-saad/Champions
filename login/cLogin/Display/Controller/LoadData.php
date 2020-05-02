<?php

namespace Contain\Display\Controller;

class LoadData {

    public function loadAlertData($Data) {
        global $wpdb;
        $alertData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}tra_reports", OBJECT );

        for ($counter = 0; $counter<count($alertData); $counter++) {
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

        if ($Data === 'first_name') {
            return $FirstName;
        }
        if ($Data === 'last_name') {
            return $LastName;
        }
        if ($Data === 'email') {
            return $Email;
        }
        if ($Data === 'residence') {
            return $Residence;
        }
        if ($Data === 'title') {
            return $Title;
        }
        if ($Data === 'event_time') {
            return $EventTime;
        }
        if ($Data === 'event_category') {
            return $EventCategory;
        }
        if ($Data === 'event_description') {
            return $EventDescription;
        }
        if ($Data === 'event_description_subject') {
            return $EventDescriptionSubject;
        }
        if ($Data === 'alert_status') {
            return $AlertStatus;
        }
    }

    public function loadArenaData($Data) {
        global $wpdb;
        $arenaData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}arena", OBJECT );

        for ($counter = 0; $counter<count($arenaData); $counter++) {
            $Title[$counter] = $arenaData[$counter] -> title;
            $country[$counter] = $arenaData[$counter] -> country;
            $FirstName[$counter] = $arenaData[$counter] -> first_name;
            $LastName[$counter] = $arenaData[$counter] -> last_name;
            $Email[$counter] = $arenaData[$counter] -> email;
            $Password[$counter] = $arenaData[$counter] -> password;
            $Skill[$counter] = $arenaData[$counter] -> skill;
            $ArenaTempId[$counter] = $arenaData[$counter] -> arenaTempID;
            $Description[$counter] = $arenaData[$counter] -> description;
            $AssociatedAlert[$counter] = $arenaData[$counter] -> associatedAlert;
        }

        if ($Data === 'title') {
            return $Title;
        }
        if ($Data === 'country') {
            return $country;
        }
        if ($Data === 'first_name') {
            return $FirstName;
        }
        if ($Data === 'last_name') {
            return $LastName;
        }
        if ($Data === 'email') {
            return $Email;
        }
        if ($Data === 'password') {
            return $Password;
        }
        if ($Data === 'skill') {
            return $Skill;
        }
        if ($Data === 'arena_temp_id') {
            return $ArenaTempId;
        }
        if ($Data === 'description') {
            return $Description;
        }
        if ($Data === 'associated_alert') {
            return $AssociatedAlert;
        }
    }

}