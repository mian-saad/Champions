<?php

namespace Contain\Display\View;

class SelectLanguage {

    public function render() {
        $html = "<div id='alert_questionnaire_div'>
                    <div id='alert_questionnaire_content_div'>
                        <h1> Welcome to ARENA Login Module</h1>
                        <p>Chose your preferred language:</p>
                        <form>
                            <select name='lang' id='lang_select'>
                                <option value='en'>🇬🇧 English</option>
                                <option value='ge'>🇩🇪 German</option>
                                <option value='ro'>🇭🇺 Hungarian</option>
                                <option value='pol'>🇵🇱 Polish</option>
                                <option value='ro'>🇷🇴 Romanian</option>
                            </select>
                        </form><br>
                        <div id='alert_button_pane'><a class='button' type='submit' id='login_session' href='#' onclick='return false;'>Start</a></div>
                    </div >
                 </div >";

        echo $html;
    }

}