<?php

namespace Contain\Display\Controller;

class LoadData {

    public function loadAlertData($Data='All', $Node='All') {

        global $wpdb;
        if ($Node=='All') {
            $alertData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}alert", OBJECT );
        }
        else {
            $alertData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}alert WHERE alert_id='$Node'", OBJECT );
        }

        for ($counter = 0; $counter<count($alertData); $counter++) {
            $Id[$counter] = $alertData[$counter] -> alert_id;
            $Language[$counter] = $alertData[$counter] -> alert_report_locale;
            $Country[$counter] = $alertData[$counter] -> alert_country;
            $City[$counter] = $alertData[$counter] -> alert_city;
            $Category[$counter] = $alertData[$counter] -> alert_category;
            $Subject[$counter] = $alertData[$counter] -> alert_subject;
            $Description[$counter] = $alertData[$counter] -> alert_description;
            $Deadline[$counter] = $alertData[$counter] -> alert_deadline;
            $FLP[$counter] = $alertData[$counter] -> flp_id;
            $CaseStatus[$counter] = $alertData[$counter] -> alert_case_status;
            $StatusModerator[$counter] = $alertData[$counter] -> alert_status_moderator;
            $StatusFLP[$counter] = $alertData[$counter] -> alert_status_flp;
            $StatusMutual[$counter] = $alertData[$counter] -> alert_status_mutual;
        }

        if ($Data === 'report_id') {
            return $Id;
        }
        if ($Data === 'deadline') {
            return $Deadline;
        }
        if ($Data === 'report_locale') {
            return $Language;
        }
        if ($Data === 'flp_id') {
            return $FLP;
        }
        if ($Data === 'city') {
            return $City;
        }
        if ($Data === 'residence') {
            return $Country;
        }
        if ($Data === 'event_category') {
            return $Category;
        }
        if ($Data === 'event_description') {
            return $Description;
        }
        if ($Data === 'description_subject') {
            return $Subject;
        }
        if ($Data === 'alert_status_moderator') {
            return $StatusModerator;
        }
        if ($Data === 'alert_status_flp') {
            return $StatusFLP;
        }
        if ($Data === 'alert_case_status') {
            return $CaseStatus;
        }
        if ($Data === 'alert_status_mutual') {
            return $StatusMutual;
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
            $arenaData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}arena WHERE flp_email='$Node'", OBJECT );
        }

        for ($counter = 0; $counter<count($arenaData); $counter++) {
            $FLP[$counter] = $arenaData[$counter] -> flp_id;
            $Language[$counter] = $arenaData[$counter] -> flp_locale;
            $Title[$counter] = $arenaData[$counter] -> flp_title;
            $Country[$counter] = $arenaData[$counter] -> flp_country;
            $FirstName[$counter] = $arenaData[$counter] -> flp_first_name;
            $LastName[$counter] = $arenaData[$counter] -> flp_last_name;
            $Email[$counter] = $arenaData[$counter] -> flp_email;
            $Password[$counter] = $arenaData[$counter] -> flp_password;
            $Organisation[$counter] = $arenaData[$counter] -> flp_organisation;
            $YearsExperience[$counter] = $arenaData[$counter] -> flp_years_of_experience;
            $City[$counter] = $arenaData[$counter] -> flp_city;
            $Visibility[$counter] = $arenaData[$counter] -> flp_visibility_level;
            $ExperienceRadicalisation[$counter] = $arenaData[$counter] -> flp_experience_with_radicalisation;
            $WorkingWith[$counter] = $arenaData[$counter] -> flp_working_with;
            $AreaExpertise[$counter] = $arenaData[$counter] -> flp_area_of_expertise;
            $Skill[$counter] = $arenaData[$counter] -> flp_skills;
            $Description[$counter] = $arenaData[$counter] -> flp_description;
            $ExpertStatus[$counter] = $arenaData[$counter] -> flp_status;
            $ALERT[$counter] = $arenaData[$counter] -> alert_id;
            $AssociatedAlert[$counter] = $arenaData[$counter] -> flp_associatedAlert;
            $NotAssociatedAlert[$counter] = $arenaData[$counter] -> flp_notAssociatedAlert;
            $ClosedAssociatedAlert[$counter] = $arenaData[$counter] -> flp_ClosedAssociatedAlert;
        }

        if ($Data === 'flp_id') {
            return $FLP;
        }
        if ($Data === 'alert_id') {
            return $ALERT;
        }
        if ($Data === 'expert_status') {
            return $ExpertStatus;
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
        if ($Data === 'description') {
            return $Description;
        }
        if ($Data === 'associated_alert') {
            return $AssociatedAlert;
        }
        if ($Data === 'not_associated_alert') {
            return $NotAssociatedAlert;
        }
        if ($Data === 'closed_associated_alert') {
            return $ClosedAssociatedAlert;
        }
        if ($Data === 'All') {
            return $arenaData;
        }
    }

    public function loadCommentData($Data='All', $Node='All') {

        global $wpdb;
        $Comments = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}commentsData WHERE alert_ID='$Node'", OBJECT );

        for ($counter = 0; $counter<count($Comments); $counter++) {
            $Commenter[$counter] = $Comments[$counter] -> comment_name;
            $AssociatedAlert[$counter] = $Comments[$counter] -> alert_ID;
            $Comment[$counter] = $Comments[$counter] -> comment_data;
        }

        if ($Data === 'comment_name') {
            return $Commenter;
        }
        if ($Data === 'alert_ID') {
            return $AssociatedAlert;
        }
        if ($Data === 'comment_data') {
            return $Comment;
        }
    }

    public function loadRecommendationData($Data='All', $Node='All') {

        global $wpdb;
        $Recommendations = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}recommendationData WHERE alert_ID='$Node'", OBJECT );

        for ($counter = 0; $counter<count($Recommendations); $counter++) {
            $RecommendationData[$counter] = $Recommendations[$counter] -> recommendation_data;
            $RecommendationName[$counter] = $Recommendations[$counter] -> recommendation_name;
            $RecommendationEmail[$counter] = $Recommendations[$counter] -> recommendation_email;
            $RecommendationId[$counter] = $Recommendations[$counter] -> recommendation_id;
            $AlertID[$counter] = $Recommendations[$counter] -> alert_ID;
        }

        if ($Data === 'recommendation_data') {
            return $RecommendationData;
        }
        if ($Data === 'recommendation_name') {
            return $RecommendationName;
        }
        if ($Data === 'recommendation_email') {
            return $RecommendationEmail;
        }
        if ($Data === 'recommendation_id') {
            return $RecommendationId;
        }
        if ($Data === 'alert_ID') {
            return $AlertID;
        }
    }
    
}