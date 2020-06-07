
jQuery(document).ready(function ($) {

    var $contentBox = $('#tra_questionnaire_content_div');

    // new report button action
    $('body').on('click', '#tra_new_report', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        var req_data = {
            action: 'fetch_question',
            dataType: 'json',
            lang: $('#lang_select').val()
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(tra_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);


            // lets initiialize dateTimePicker here
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
            // jQuery.datetimepicker.setLocale('ru');
        });
    });

    // continue button action
    $('body').on('click', '#tra_continue', function (e) {
        e.preventDefault();


        var provided_answers = takedown_question_validation();

        // need to find all inputs and see get the value
        var req_data = {
            action: 'fetch_question',
            dataType: 'json',
            report_id: $('input#report_id').val(),
            answer: provided_answers,
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(tra_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);

            // let x = $('h3.tra_question').text();

            if ($('h3.tra_question').text() === 'Event time') {
                // lets initiialize dateTimePicker here
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
                // lets initiialize dateTimePicker here
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

    // submit button action
    $('body').on('click', '#tra_submit', function (e) {
        // need to find all inputs and see get the value
        var req_data = {
            action: 'submit_report',
            dataType: 'json',
            report_id: $('input#report_id').val(),
        };

        jQuery.get(tra_object.ajaxurl, req_data, function (response) {});
    });

    // yes button action
    $('body').on('click', '#tra_yes', function (e) {
        e.preventDefault();
        // generate request data
        var req_data = {
            action: 'fetch_question',
            report_id: $('input#report_id').val(),
            answer: true
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(tra_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
            // lets initiialize dateTimePicker here
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

    // no button action
    $('body').on('click', '#tra_no', function (e) {
        e.preventDefault();
        // generate request data
        var req_data = {
            action: 'fetch_question',
            report_id: $('input#report_id').val(),
            answer: false
        };

        // try to animate
        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(tra_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);
            // lets initiialize dateTimePicker here
            $('.picker').datetimepicker({
                allowBlank: true,
                maxDate: '0',
                onChangeDateTime: function (ct, $i) {
                    date = new Date();
                    if (ct.getDay() === date.getDay() && ct.getMonth() === date.getMonth() && ct.getHours() > date.getHours()) {
                        $i.datetimepicker('reset');
                    }
                },
            });
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

        jQuery.get(tra_object.ajaxurl, req_data, function (response) {
            $contentBox
                .html(response)
                .animate({ opacity: 1 }, 100);

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

    function takedown_question_validation() {

        var formData = $('#tra_question_form').serializeArray();
        var title = $('#title').val();

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