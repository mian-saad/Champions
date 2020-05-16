jQuery(document).ready(function ($) {

    const $contentBox = $('#contentBox');
    var recommendationID;


    // On click at Login
    $('body').on('click', '#toLoginForm', function (e) {
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

    // On click at Case
    $('body').on('click', '#login', function (e) {
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

    // On click at Login within Login Form
    $('body').on('click', '#case', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        const req_data = {
            action: 'summon',
            id: 'decide_case'
        };

        // try to animate
        $contentBox.animate(100);

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
    });

    // On click at Expert
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

    // On click at Close Case
    $('body').on('click', '.decide', function (e) {
        //e.preventDefault();

        let decision = $(this).attr('id');
        let decision_class = $(this).attr('class');
        decision_class = decision_class.split(" ").pop();
        // decision_class = "." + decision_class;
        // $(decision_class).prop('disabled', true);

        // let Id = decision.split('-').pop();
        // $(this).prop('disabled', true);

        /*if (decision.includes("Accepted")) {
            console.log("working ..")
        }
        else {
            console.log("not working ..")
        }*/

        const req_data = {
            action: 'summon',
            id: 'decide_case_state',
            close: decision
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {});
        console.log("Determined");
    });

    // On click at Accept/Reject
    $('body').on('click', '.decide', function (e) {
        //e.preventDefault();

        let decision = $(this).attr('id');
        let decision_class = $(this).attr('class');
        decision_class = decision_class.split(" ").pop();
        decision_class = "." + decision_class;
        $(decision_class).prop('disabled', true);

        // let Id = decision.split('-').pop();
        // $(this).prop('disabled', true);

        /*if (decision.includes("Accepted")) {
            console.log("working ..")
        }
        else {
            console.log("not working ..")
        }*/

        const req_data = {
            action: 'summon',
            id: 'decide',
            determine: decision
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {});
        console.log("Determined");
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


