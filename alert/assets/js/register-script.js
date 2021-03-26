
jQuery(document).ready(function ($) {

    var $contentBox = $('#alert_questionnaire_content_div');

    // new report button action
    $('body').on('click', '#start_registration', function (e) {
        e.preventDefault();
        debugger
        // need to find all inputs and see get the value
        var req_data = {
            action: 'retrieve_question',
            dataType: 'json',
            alert_id: $('input#alert_id').val(),
            lang: $('#lang_select').val()
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(register_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });



    // continue button action
    $('body').on('click', '#arena_continue', function (e) {
        e.preventDefault();


        var provided_answers = arena_question_validation();

        // need to find all inputs and see get the value
        var req_data = {
            action: 'retrieve_question',
            dataType: 'json',
            report_id: $('input#report_id').val(),
            alert_id: $('input#alert_id').val(),
            answer: provided_answers,
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(register_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });

    // submit button action
    $('body').on('click', '#arena_submit', function (e) {
        // need to find all inputs and see get the value
        var req_data = {
            action: 'get_question',
            dataType: 'json',
            alert_id: $('input#alert_id').val(),
            report_id: $('input#report_id').val()
        };

        jQuery.get(register_object.ajaxurl, req_data, function (response) {
            $contentBox.html(response).animate({ opacity: 1 }, 100);
        });
    });

    // back button action
    $('body').on('click', '#arena_back', function (e) {
        e.preventDefault();
        // generate request data
        var req_data = {
            action: 'go_back',
            report_id: $('input#report_id').val(),
            answer: false
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(register_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });

    function arena_question_validation() {

        var formData = $('#arena_question_form').serializeArray();

        result = {};

        for (var i in formData) {
            var fieldname = formData[i]['name'];
            var fieldvalue = formData[i]['value'];

            if (!(fieldname in result) && !(fieldname === "other_text_input")) { // if key doesnt exist, add it to array
                result[fieldname] = fieldvalue
            }
            else if (fieldname === "other_text_input" && fieldvalue !== '') {
                var v_fieldname = formData[i-1]['name'];
                result[v_fieldname].push(fieldvalue);
            }
            else if (fieldname === "other_text_input" && fieldvalue === '') {
                continue;
            }
            else { // else key already exists

                if (Array.isArray(result[fieldname])) { // if field already contains an array, lets push new element there
                    result[fieldname].push(fieldvalue);
                }
                else {
                    var newvalue = [result[fieldname], fieldvalue]  // else lets create new array with two elements in it
                    result[fieldname] = newvalue;
                }
            }
        }

        return result;
    }
});