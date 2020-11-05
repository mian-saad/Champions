<?php

namespace Contain\Display\View;


class ProfileUpdated {

    public function update($data, $email) {

        if (count($data['flp_skills']) > 1) {
            $data['flp_skills'] = implode(',', $data['flp_skills']);
        }
        if (!empty($data['other_text_input'])) {
            $data['flp_skills'] = $data['flp_skills'] .','. $data['other_text_input'];
        }
        unset($data['other_text_input']);
        global $wpdb;
        $wpdb->update("wp_arena", $data, array('flp_email' => $email));

        $this->render();
    }

    public function render() {
        echo "<p id='notify2' style='display: inline; color: #285b8f; font-size: 16px;'>Profile Updated</p>";
    }

}