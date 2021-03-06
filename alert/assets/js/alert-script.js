
jQuery(document).ready(function ($) {
    var $contentBox = $('#alert_questionnaire_content_div');

    // Authenticate user when he wants to submit alert
    $('body').on('click', '#authenticate', function (e) {
        e.preventDefault();
        var provided_answers = alert_question_validation();
        // need to find all inputs and get the value
        var req_data = {
            action: 'get_question',
            answer: provided_answers,
            validate: 'validate',
            dataType: 'json',
            alert_id: $('input#alert_id').val()
        };
        $contentBox.animate({ opacity: 0.5 }, 100);
        jQuery.get(alert_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });

    // new alert button action
    $('body').on('click', '#alert_new_report', function (e) {
        e.preventDefault();

        // need to find all inputs and get the value
        var req_data = {
            action: 'get_question',
            dataType: 'json',
            lang: $('#lang_select').val()
        };
        $contentBox.animate({ opacity: 0.5 }, 100);
        jQuery.get(alert_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
            // Initialize dateTimePicker here
            $('.picker').datetimepicker({
                allowBlank: true,
                maxDate: 0,
                minDate: 0,
                onChangeDateTime: function (ct, $i) {
                    date = new Date();
                    if (ct.getDay() === date.getDay() && ct.getMonth() === date.getMonth() && ct.getHours() > date.getHours()) {
                        $i.datetimepicker('reset');
                    }
                },
            });
        });
    });

    // continue button action
    $('body').on('click', '#alert_continue', function (e) {
        e.preventDefault();

        var provided_answers = alert_question_validation();
        // need to find all inputs and get the value
        var req_data = {
            action: 'get_question',
            dataType: 'json',
            alert_id: $('input#alert_id').val(),
            answer: provided_answers,
        };
        $contentBox.animate({ opacity: 0.5 }, 100);
        jQuery.get(alert_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
            // if ($("#check-platform").val().length == 0)
            if ($("#check-platform").length > 0 ) {
                $('.post-title').text("Arena");
            }
            else {
                $('.post-title').text("Alert");
            }
            if ($( "#date_picker" ).hasClass( "picker" )) {
                // Initialize dateTimePicker here
                $('.picker').datetimepicker({
                    allowBlank: true,
                    maxDate: 0,
                    onChangeDateTime: function (ct, $i) {
                        date = new Date();
                        if (ct.getDay() === date.getDay() && ct.getMonth() === date.getMonth() && ct.getHours() > date.getHours()) {
                            $i.datetimepicker('reset');
                        }
                    },
                });
            }
            else {
                // Initialize dateTimePicker here
                $('.picker').datetimepicker({
                    allowBlank: true,
                    minDate: -0,
                    timepicker: false,
                    onChangeDateTime: function (ct, $i) {
                        date = new Date();
                        if (ct.getDay() === date.getDay() && ct.getMonth() === date.getMonth() && ct.getHours() > date.getHours()) {
                            $i.datetimepicker('reset');
                        }
                    },
                });
            }

        });
    });

    // submit button action before authentication
    $('body').on('click', '#alert_register', function (e) {
        // need to find all inputs and see get the value
        // $('.post-title').text("Arena");
        var req_data = {
            action: 'get_question',
            dataType: 'json',
            answer: 'register',
            alert_id: $('input#alert_id').val(),
        };

        jQuery.get(alert_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });

    // submit button action
    $('body').on('click', '#alert_submit', function (e) {
        // need to find all inputs and see get the value
        var req_data = {
            action: 'get_report',
            dataType: 'json',
            alert_id: $('input#alert_id').val(),
        };
        jQuery.get(alert_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
        });
    });

    $('body').on('click', '#ok', function (e) {
        window.location.reload();
    });

    // Done button action leads to Thank You
    $('body').on('click', '#done', function (e) {
        done();
    });

    function done() {
        var req_data = {
            action: 'done',
            dataType: 'json',
            alert_id: 'done',
        };
        jQuery.get(alert_object.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
            setTimeout(function(){
                window.location.reload();
            }, 3000);
        });
    }

    // back button action
    $('body').on('click', '#alert_back', function (e) {
        e.preventDefault();
        // generate request data
        var req_data = {
            action: 'get_back',
            alert_id: $('input#alert_id').val(),
            answer: false
        };
        $contentBox.animate({ opacity: 0.5 }, 100);
        jQuery.get(alert_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
            if ($("#check-platform").length > 0 ) {
                $('.post-title').text("Arena");
            }
            else {
                $('.post-title').text("Alert");
            }
            $('.picker').datetimepicker({
                allowBlank: true,
                maxDate: 0,
                onChangeDateTime: function (ct, $i) {
                    date = new Date();
                    if (ct.getDay() === date.getDay() && ct.getMonth() === date.getMonth() && ct.getHours() > date.getHours()) {
                        $i.datetimepicker('reset');
                    }
                },
            });
        });
    });

    function alert_question_validation() {

        var formData = $('#alert_question_form').serializeArray();
        var title = $('#title').val();

        result = {};

        for (var i in formData) {
            var fieldname = formData[i]['name'];
            var fieldvalue = formData[i]['value'];

            if (!(fieldname in result) && !(fieldname === "other_text_input")) {                // if key doesnt exist, add it to array
                result[fieldname] = fieldvalue
            }
            else if (!(formData[i-1]['name'] in result) && fieldname === "other_text_input" && fieldvalue !== '') {
                var v_fieldname = formData[i-1]['name'];
                result[v_fieldname] = fieldvalue;
            }
            else if ((formData[i-1]['name'] in result) && fieldname === "other_text_input" && fieldvalue !== '') {
                var v_fieldname = formData[i-1]['name'];
                result[v_fieldname].push(fieldvalue);
            }
            else if (fieldname === "other_text_input" && fieldvalue === '') {
                continue;
            }
            else {                                  // else key already exists

                if (Array.isArray(result[fieldname])) {    // if field already contains an array, lets push new element there
                    result[fieldname].push(fieldvalue);
                }
                else {
                    var newvalue = [result[fieldname], fieldvalue]    // else lets create new array with two elements in it
                    result[fieldname] = newvalue;
                }
            }
        }

        return result;
    }
});