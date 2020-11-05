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

    // On click at Recomendations
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

    $('body').on('click', '#to_arena_login_module', function (e) {
        const req_data = {
            action: 'summon',
            id: 'SelectLanguage'
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

    // $(".RecommendClass").hover(function() {
    //
    // });

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
    /* <-- OLD SECTION --> */












    // On click at JOIN
    $('body').on('click', '.join', function (e) {
        //e.preventDefault();

        const $alertBox = $('#alertPanel');
        var alert = $(this).attr('value');
        const $alertPost = $('.alertPost').text();
        $('.tojoin').prop('disabled', true);
        $('.tojoin').addClass('grey');
        const req_data = {
            action: 'summon',
            id: 'join',
            post: $alertPost,
            alertV: alert
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            // console.log("disble");
            $alertBox.html(response);
        });
        console.log("logged");
    });

    // On click at In progress
    $('body').on('click', '.inprogress', function (e) {
        //e.preventDefault();

        // var inprogress = $(this).attr('id');
        var  inprogress = $(this).attr('value');
        // const $alertPost = $('.alertPost').text();
        // $('.tojoin').prop('disabled', true);
        // $('.tojoin').addClass('grey');
        const $alertBox = $('#alertPanel');
        const req_data = {
            action: 'summon',
            id: 'in_progress',
            alertID: inprogress
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            // console.log("disble");
            $alertBox.html(response);
        });
        console.log("logged");
    });

    // On click at ALERT
    $('body').on('click', '#toAlert', function (e) {
        e.preventDefault();

        var  alertID = $(this).attr('value');
        const $alertBox = $('#alertPanel');

        const req_data = {
            action: 'summon',
            id: 'alerter',
            alertID: alertID
        };

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $alertBox.html(response);
        });


    });

    // On click at Invitation in Discussion Page
    $('body').on('click', '#invite', function (e) {
        e.preventDefault();
        const $inviteEmail = $('#invite_address');
        const req_data = {
            action: 'summon',
            inviteEmail: $inviteEmail.val(),
            id: 'invitation'
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $inviteEmail.val('');
        });
    });

    // On click at Add Recommendation
    $('body').on('click', '#addRecommendation', function (e) {
        e.preventDefault();

        const $btnText = $('#addRecommendation');

        if ($btnText.text() == "Update") {

            const $added_recommendation = $('#added_recommendation');
            const $user = $('.userEmail').text();
            const $recommendation = $('#recommend');
            const $rId = recommendationID;
            const req_data = {
                action: 'summon',
                recommendation: $recommendation.val(),
                id: 'updateRecommendation',
                mail: $user,
                rId: $rId
            };
            jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
                $added_recommendation.html(response);
                // $recommendation.val('');
            });
        }
        else {
            $btnText.text("Add");

            const $added_recommendation = $('#added_recommendation');
            const $user = $('.userEmail').text();
            const $recommendation = $('#recommend');
            const $rId = recommendationID;
            const req_data = {
                action: 'summon',
                recommendation: $recommendation.val(),
                id: 'addRecommendation',
                rId: $rId,
                mail: $user
            };
            jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
                $('#added_recommendation').empty();
                $added_recommendation.html(response);
                $recommendation.val('');
            });
        }
    });

    // On click at Recommendation Text
    $('body').on('click', '.getRecomendID', function (e) {
        e.preventDefault();

        const $btnText = $('#addRecommendation');
        $btnText.text("Update");
        const $user = $('.userEmail').text();
        recommendationID = $(this).attr('value');
        const $recommendation = $('#recommend');
        $recommendation.val(recommendationID);

    });

        // On click at BACK
    $('body').on('click', '#alertBack', function (e) {
        e.preventDefault();

        const $alertBox = $('#alertPanel');
        const $j = $('#toAlert').val();

        const req_data = {
            action: 'summon',
            id: 'alertBack',
            iterator: $j
        };

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $alertBox.html(response);
        });


    });

    // On click at add comment
    $('body').on('click', '.submit_comment', function (e) {
        e.preventDefault();

        // let $id = $(this).attr('id').match(/\d+$/).toString();

        const $user = $('.userEmail').text();

        const $txtfield = $('#get_comment');
        const $comment = $('#display_comment');
        const req_data = {
            action: 'summon',
            comment_data: $('#get_comment').val(),
            // comment_idd: $id,
            id: 'cmnt',
            mail: $user
        };


        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            console.log("Comment Added");
            $comment.html(response);
            $txtfield.val('');
        });
        RefreshComment();
    });

    function RefreshComment() {
        setInterval(function (e) {

            const $comment = $('#display_comment');
            const req_data = {
                action: 'summon',
                id: 'showcmnt',
            };
            jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
                $comment.html(response);
            });
        }, 3000);
    }
});


