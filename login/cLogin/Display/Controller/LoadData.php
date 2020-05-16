<?php

namespace Contain\Display\Controller;

class LoadData {

    public function loadAlertData($Data='All', $Node='All') {

        global $wpdb;
        if ($Node=='All') {
            $alertData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}tra_reports", OBJECT );
        }
        else {
            $alertData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}tra_reports WHERE report_id='$Node'", OBJECT );
        }

        for ($counter = 0; $counter<count($alertData); $counter++) {
            $ReportId[$counter] = $alertData[$counter] -> report_id;
            $Language[$counter] = $alertData[$counter] -> report_locale;
            $FirstName[$counter] = $alertData[$counter] -> reporter_fName;
            $LastName[$counter] = $alertData[$counter] -> reporter_lName;
            $Email[$counter] = $alertData[$counter] -> reporter_email;
            $Residence[$counter] = $alertData[$counter] -> reporter_residence;
            $Deadline[$counter] = $alertData[$counter] -> deadline;
            $Title[$counter] = $alertData[$counter] -> title;
            $EventTime[$counter] = $alertData[$counter] -> event_time;
            $EventCategory[$counter] = $alertData[$counter] -> event_category;
            $EventDescription[$counter] = $alertData[$counter] -> event_description;
            $EventDescriptionSubject[$counter] = $alertData[$counter] -> description_subject;
            $AlertStatusModerator[$counter] = $alertData[$counter] -> alert_status_moderator;
            $AlertStatusFLP[$counter] = $alertData[$counter] -> alert_status_flp;
            $AlertStatusMutual[$counter] = $alertData[$counter] -> alert_status_mutual;
        }

        if ($Data === 'report_id') {
            return $ReportId;
        }
        if ($Data === 'deadline') {
            return $Deadline;
        }
        if ($Data === 'report_locale') {
            return $Language;
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
        if ($Data === 'description_subject') {
            return $EventDescriptionSubject;
        }
        if ($Data === 'alert_status_moderator') {
            return $AlertStatusModerator;
        }
        if ($Data === 'alert_status_flp') {
            return $AlertStatusFLP;
        }
        if ($Data === 'alert_status_mutual') {
            return $AlertStatusMutual;
        }
        if ($Data === 'All') {
            return $alertData;
        }
    }

    public function loadArenaData($Data='All', $Node='All') {

        global $wpdb;
        if ($Node=='All') {
            $arenaData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}arena", OBJECT );
        }
        else {
            $arenaData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}arena WHERE email='$Node'", OBJECT );
        }

        for ($counter = 0; $counter<count($arenaData); $counter++) {
            $Language[$counter] = $arenaData[$counter] -> report_locale;
            $Title[$counter] = $arenaData[$counter] -> title;
            $Country[$counter] = $arenaData[$counter] -> country;
            $FirstName[$counter] = $arenaData[$counter] -> first_name;
            $LastName[$counter] = $arenaData[$counter] -> last_name;
            $Email[$counter] = $arenaData[$counter] -> email;
            $Password[$counter] = $arenaData[$counter] -> password;
            $Skill[$counter] = $arenaData[$counter] -> skill;
            $ArenaTempId[$counter] = $arenaData[$counter] -> arenaTempID;
            $Description[$counter] = $arenaData[$counter] -> description;
            $AssociatedAlert[$counter] = $arenaData[$counter] -> associatedAlert;
            $ExpertType[$counter] = $arenaData[$counter] -> expert_type;
        }

        if ($Data === 'report_locale') {
            return $Language;
        }
        if ($Data === 'title') {
            return $Title;
        }
        if ($Data === 'country') {
            return $Country;
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
        if ($Data === 'expert_type') {
            return $ExpertType;
        }
        if ($Data === 'All') {
            return $arenaData;
        }
    }

}