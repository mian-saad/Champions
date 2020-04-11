
jQuery(document).ready(function ($) {

    var $contentBox = $('#registration_questionnaire_content_div');

    // new report button action
    $('body').on('click', '#arena_new_expert', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        var req_data = {
            action: 'fetc_question',
            dataType: 'json',
            lang: "en"
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(registration_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });

    // Login button action
    $('body').on('click', '#arena_login', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        var req_data = {
            action: 'fetc_question',
            dataType: 'json',
            lang: "en",
            type: 'login'
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(registration_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });

    // continue button action
    $('body').on('click', '#tra_continue', function (e) {
        e.preventDefault();


        var provided_answers = takedown_question_validation();

        // need to find all inputs and see get the value
        var req_data = {
            action: 'fetc_question',
            dataType: 'json',
            report_id: $('input#report_id').val(),
            answer: provided_answers,
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(registration_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });

    // submit button action
    $('body').on('click', '#tra_submit', function (e) {
        // need to find all inputs and see get the value
        var req_data = {
            action: 'submit_report',
            dataType: 'json',
            report_id: $('input#report_id').val(),
        };

        jQuery.get(registration_object.ajaxurl, req_data, function (response) {});
    });

    // yes button action
    $('body').on('click', '#tra_yes', function (e) {
        e.preventDefault();
        // generate request data
        var req_data = {
            action: 'fetc_question',
            report_id: $('input#report_id').val(),
            answer: true
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(registration_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });

    // no button action
    $('body').on('click', '#tra_no', function (e) {
        e.preventDefault();
        // generate request data
        var req_data = {
            action: 'fetc_question',
            report_id: $('input#report_id').val(),
            answer: false
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(registration_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });

    // back button action
    $('body').on('click', '#tra_back', function (e) {
        e.preventDefault();
        // generate request data
        var req_data = {
            action: 'step_back',
            report_id: $('input#report_id').val(),
            answer: false
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(registration_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });

    function takedown_question_validation() {

        var formData = $('#tra_question_form').serializeArray();

        result = {};

        for (var i in formData) {
            var fieldname = formData[i]['name'];
            var fieldvalue = formData[i]['value'];

            if (!(fieldname in result)) {                // if key doesnt exist, add it to array
                result[fieldname] = fieldvalue
            } else {                                  // else key already exists

                if (Array.isArray(result[fieldname])) {    // if field already contains an array, lets push new element there
                    result[fieldname].push(fieldvalue);
                } else {
                    var newvalue = [result[fieldname], fieldvalue]    // else lets create new array with two elements in it
                    result[fieldname] = newvalue;
                }
            }
        }

        return result;
    }
});