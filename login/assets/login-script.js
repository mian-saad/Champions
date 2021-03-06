jQuery(document).ready(function ($) {

    const $contentBox = $('#contentBox');
    var recommendationID;
    let ID;


    // On click at Login on First Page
    $('body').on('click', '#ToLoginForm', function (e) {
        e.preventDefault();

        const req_data = {
            action: 'summon',
            id: 'FirstLogin'
        };

        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response).animate({ opacity: 1 }, 100);
        });

    });

    // On click at Login within Login Form
    $('body').on('click', '#Login', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        const req_data = {
            action: 'summon',
            email: $('#naam').val(),
            pass: $('#paas').val(),
            id: 'login'
        };

        // try to animate
        $contentBox.animate(100);

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
    });

    // On click at Login as Moderator
    $('body').on('click', '#ToBackdoorLogin', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        const req_data = {
            action: 'summon',
            id: 'BackdoorLogin'
        };

        // try to animate
        $contentBox.animate(100);

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
    });

    // On click at Login within Backdoor Login Form
    $('body').on('click', '#BackdoorLogin', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        const req_data = {
            action: 'summon',
            email: $('#naam').val(),
            pass: $('#paas').val(),
            id: 'Backdoor'
        };

        // try to animate
        $contentBox.animate(100);

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
    });

    // On click at Expert on Direction Page
    $('body').on('click', '#expert', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        const req_data = {
            action: 'summon',
            id: 'decide_expert'
        };

        // try to animate
        $contentBox.animate(100);

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
    });

    // On click at Accept/Reject Alert Case on Direction Page
    $('body').on('click', '#alert_case', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        const req_data = {
            action: 'summon',
            id: 'alert_case'
        };

        // try to animate
        $contentBox.animate(100);

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {

            $contentBox.html(response);
        });
    });

    // On click at Back
    $('body').on('click', '#back', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        const req_data = {
            action: 'summon',
            id: 'go_back'
        };

        // try to animate
        $contentBox.animate(100);

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
    });

    // On click at Close Case
    $('body').on('click', '.close_case', function (e) {
        //e.preventDefault();
        let decision = $(this).attr('id');
        let Id1 = decision.split('-').pop();
        const req_data = {
            action: 'summon',
            id: 'CloseCase',
            close: decision
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            if (response.includes("alert")) {
                console.log("good good");
                alert("Other Modules Needs to be Closed First");
            }
            else {
                $("#"+Id1).html(response);
            }
        });
    });

    // On click at Accept/Reject Case
    $('body').on('click', '.decide_case', function (e) {
        //e.preventDefault();

        let ID = $(this).attr('id');
        let Id1 = ID.split('-').pop();
        const req_data = {
            action: 'summon',
            id: 'AcceptRejectCase',
            DecideId: ID
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $("#"+Id1).html(response);
        });
    });

    // On click at Accept/Reject Expert
    $('body').on('click', '.decide_expert', function (e) {

        let ID = $(this).attr('id');
        let Id1 = ID.split('-').pop();
        const req_data = {
            action: 'summon',
            id: 'decide_expert_case',
            DecideId: ID
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
    });

    // On click at Join, Decline, Close
    $('body').on('click', '.ArenaClickableButtons', function (e) {
        //e.preventDefault();
        let ID = $(this).attr('id');
        // let Id1 = ID.split('-').pop();
        // let RowID = "#Row-"+Id1;

        // $("#Row-"+Id1).prop('disabled', true);
        const req_data = {
            action: 'summon',
            id: 'ArenaClickableButtons',
            ID: ID
        };

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
        // $(RowID).css("background", "green");
    });

    // On click at Recommendations
    $('body').on('click', '.Recommend', function (e) {
        //e.preventDefault();
        ID = $(this).attr('id');
        let Ident = ID.split('-').pop();
        // let RowID = "#Row-"+Id1;

        // $("#Row-"+Id1).prop('disabled', true);
        const req_data = {
            action: 'summon',
            id: 'Recommend',
            ID: Ident
        };

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
        // $(RowID).css("background", "green");
    });

    // On click at Subject
    $('body').on('click', '.subject', function (e) {
        //e.preventDefault();
        let ID = $(this).attr('id');
        const req_data = {
            action: 'summon',
            id: 'OnClickSubject',
            ID: ID
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
    });

    // click back on discussion page
    $('body').on('click', '#BackDiscussion', function (e) {
        //e.preventDefault();

        const req_data = {
            action: 'summon',
            id: 'OnClickBack',
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
    });

    // On click comment
    $('body').on('click', '#comment', function (e) {
        //e.preventDefault();

        const req_data = {
            action: 'summon',
            id: 'OnClickComment',
            ID: $(this).attr('value'),
            Data: $('#commentText').val()
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
            $('#commentText').val('');
        });
    });

    // On click Add Recommendation
    $('body').on('click', '#Recommend', function (e) {
        //e.preventDefault();

        const req_data = {
            action: 'summon',
            id: 'AddRecommendation',
            ID: $(this).attr('value'),
            Data: $('#RecommendationText').val()
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
            $('#RecommendationText').val('');
        });
    });

    // On click at Specific Recommendation
    $('body').on('click', '.RecommendClass', function (e) {
        //e.preventDefault();

        ID = $(this).attr('id');
        let Name = $('#MyName').attr('name')
        let CheckName = $('#'+ID).attr('name')

        if (Name === CheckName) {
            let Data = $('#'+ID).text();
            $('#RecommendationText').val(Data);

            let Ident = $('#Recommend');
            let ButtonText = $(Ident).text();
            if (ButtonText.indexOf('Add') > -1) {
                $('#Recommend').text('Update Recommendation');
                $('#Recommend').attr("id", "Update")
            }
        }
        // else {
        //     alert('haan bhyee?');
        // }


    });

    $('body').on('hover', '.RecommendClass', function (e) {

        ID = $(this).attr('id');
        let Name = $('#MyName').attr('name')
        let CheckName = $('#'+ID).attr('name')

        if (Name === CheckName) {
            $(this).toggleClass('HoverRecommendation');
        }
    });

    $('body').on('click', '#start_arena', function (e) {
        const req_data = {
            action: 'summon',
            id: 'LanguageSelect'
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });

    });

    $('body').on('click', '#login_session', function (e) {


        const req_data = {
            action: 'summon',
            id: 'MainPage',
            data: $('#lang_select').val()
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });

    });

    $('body').on('click', '#backto', function (e) {


        const req_data = {
            action: 'summon',
            id: 'MainPage'
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });

    });

    $('body').on('click', '#backtolanguage', function (e) {


        const req_data = {
            action: 'summon',
            id: 'SelectLanguage'
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });

    });

    // On click at Update Recommendation
    $('body').on('click', '#Update', function (e) {
        //e.preventDefault();

        let Ident = ID;
        const req_data = {
            action: 'summon',
            id: 'UpdateRecommendation',
            ID: $(this).attr('value'),
            RecommendationId: Ident,
            Data: $('#RecommendationText').val()
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
            $('#RecommendationText').val('');
        });

    });

    // On click at Invitation button
    $('body').on('click', '#invitation-button', function (e) {
        //e.preventDefault();

        const req_data = {
            action: 'summon',
            id: 'InvitationMechanism',
            alert: $('#invitation-button').val(),
            InvitationEmail: $('#invitation-email').val()
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            // $contentBox.html(response);
            $('#invitation-email').val('');
        });

    });

    $('body').on('click', '#forgot', function (e) {
        //e.preventDefault();

        const req_data = {
            action: 'summon',
            id: 'Forgot'
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });

    });

    $('body').on('click', '#forgot_password', function (e) {
        //e.preventDefault();

        const req_data = {
            action: 'summon',
            data: $('#naam').val(),
            id: 'ForgotPassword'
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });

    });

    $('body').on('click', '#edit', function (e) {
        //e.preventDefault();

        const req_data = {
            action: 'summon',
            data: $('#edit').data("value"),
            id: 'Edit'
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });

    });

    // on clicking update
    $('body').on('click', '#update', function (e) {
        //e.preventDefault();

        const $notify = $('#notify');
        var provided_answers = arena_question_validation();

        const req_data = {
            action: 'summon',
            data: provided_answers,
            id: 'Update'
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {

            $notify.html(response);
        });
        setTimeout(function() {
            $('#notify2').fadeOut();
        }, 3000);
    });


    function arena_question_validation() {

        var formData = $('#arena_question_form').serializeArray();

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

    // -->

    //
    // var $contentBoxR = $('#registration_questionnaire_content_div');
    //
    // // new report button action
    // $('body').on('click', '#start_registration', function (e) {
    //     e.preventDefault();
    //     // need to find all inputs and see get the value
    //     var req_data = {
    //         action: 'retrieve_question',
    //         dataType: 'json',
    //         lang: $('#lang_select').val()
    //     };
    //
    //     // try to animate
    //     $contentBoxR.animate({ opacity: 0.5 }, 100);
    //
    //     jQuery.get(register_object.ajaxurl, req_data, function (response) {
    //         $contentBoxR
    //             .html(response)
    //             .animate({ opacity: 1 }, 100);
    //     });
    // });
    //
    // // Login button action
    // // $('body').on('click', '#arena_login', function (e) {
    // //     e.preventDefault();
    // //
    // //     // need to find all inputs and see get the value
    // //     var req_data = {
    // //         action: 'retrieve_question',
    // //         dataType: 'json',
    // //         lang: "en",
    // //         type: 'login'
    // //     };
    // //
    // //     // try to animate
    // //     $contentBoxR.animate({ opacity: 0.5 }, 100);
    // //
    // //     jQuery.get(register_object.ajaxurl, req_data, function (response) {
    // //         $contentBoxR
    // //             .html(response)
    // //             .animate({ opacity: 1 }, 100);
    // //     });
    // // });
    //
    // // continue button action
    // $('body').on('click', '#arena_continue', function (e) {
    //     e.preventDefault();
    //
    //
    //     var provided_answers = arena_question_validationR();
    //
    //     // need to find all inputs and see get the value
    //     var req_data = {
    //         action: 'retrieve_question',
    //         dataType: 'json',
    //         report_id: $('input#report_id').val(),
    //         answer: provided_answers,
    //     };
    //
    //     // try to animate
    //     $contentBoxR.animate({ opacity: 0.5 }, 100);
    //
    //     jQuery.get(register_object.ajaxurl, req_data, function (response) {
    //         $contentBoxR
    //             .html(response)
    //             .animate({ opacity: 1 }, 100);
    //     });
    // });
    //
    // // submit button action
    // $('body').on('click', '#arena_submit', function (e) {
    //     // need to find all inputs and see get the value
    //     var req_data = {
    //         action: 'submit_case',
    //         dataType: 'json',
    //         report_id: $('input#report_id').val()
    //     };
    //
    //     jQuery.get(register_object.ajaxurl, req_data, function (response) {
    //         $contentBoxR.html(response).animate({ opacity: 1 }, 100);
    //     });
    //     // setTimeout(function() {
    //     //     location.reload(true);
    //     // }, 3000);
    // });
    //
    // // back button action
    // $('body').on('click', '#arena_back', function (e) {
    //     e.preventDefault();
    //     // generate request data
    //     var req_data = {
    //         action: 'go_back',
    //         report_id: $('input#report_id').val(),
    //         answer: false
    //     };
    //
    //     // try to animate
    //     $contentBoxR.animate({ opacity: 0.5 }, 100);
    //
    //     jQuery.get(register_object.ajaxurl, req_data, function (response) {
    //         $contentBoxR
    //             .html(response)
    //             .animate({ opacity: 1 }, 100);
    //     });
    // });
    //
    // function arena_question_validationR() {
    //
    //     var formData = $('#arena_question_form').serializeArray();
    //
    //     result = {};
    //
    //     for (var i in formData) {
    //         var fieldname = formData[i]['name'];
    //         var fieldvalue = formData[i]['value'];
    //
    //         if (!(fieldname in result)) {                // if key doesnt exist, add it to array
    //             result[fieldname] = fieldvalue
    //         } else {                                  // else key already exists
    //
    //             if (Array.isArray(result[fieldname])) {    // if field already contains an array, lets push new element there
    //                 result[fieldname].push(fieldvalue);
    //             } else {
    //                 var newvalue = [result[fieldname], fieldvalue]    // else lets create new array with two elements in it
    //                 result[fieldname] = newvalue;
    //             }
    //         }
    //     }
    //
    //     return result;
    // }
});
