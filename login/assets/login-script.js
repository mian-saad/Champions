jQuery(document).ready(function ($) {

    const $contentBox = $('#contentBox');
    var recommendationID;


    // On click at Login
    $('body').on('click', '#login', function (e) {
        e.preventDefault();

        const req_data = {
            action: 'summon',
            id: 'FormLogin'
        };

        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response).animate({ opacity: 1 }, 100);
        });

    });

    // On click at Register
    $('body').on('click', '#register', function (e) {
        e.preventDefault();

        const req_data = {
            action: 'summon',
            id: 'fregister'
        };
        $contentBox.animate(100);
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });

    });

    // On click at Login within Login Form
    $('body').on('click', '#send', function (e) {
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

    // On click at Register within Registration Form
    $('body').on('click', '#next_register', function (e) {
        e.preventDefault();

        var required1 = $('#first_name').val();
        var required2 = $('#last_name').val();
        var required3 = $('#country').val();
        var required4 = $('#email').val();
        var required5 = $('#password').val();
        var required6 = $('#skills').val();

        if(required1.length > 0 && required2.length > 0 && required4.length > 0 && required5.length > 0 ) {
            // not all required fields are filled in
            console.log('checking .. ');
            const req_data = {
                action: 'summon',
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                country: $('#country').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                title: $('#title').val(),
                id: 'register'
            };

            jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
                $contentBox.html(response);
            });
        }
        else {
            console.log("Required Fields");
            alert("Please fill in the required fields !");
        }


    });

    // On click at Register within Registration2 Form
    $('body').on('click', '#next1_register', function (e) {
        e.preventDefault();


        var skill = [];
        var othr = $('#othr').val();


        $.each($("input[name='skills']:checked"), function(){
            skill.push($(this).val());
        });
        if (othr != ""){
            skill.push(othr);
        }

        var jsonString = JSON.stringify(skill);
        console.log(skill);

        const req_data = {
            action: 'summon',
            skill: jsonString,
            id: 'register1'
        };

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });


    });

    // On click at Register within Registration3 Form
    $('body').on('click', '#registered', function (e) {
        e.preventDefault();

        $(".error").html("").hide();
        var number = $("#mobile").val();
        // sendOTP();
        var input = {
            "mobile_number" : number,
            "m_action" : "send_otp"
        };

        const req_data = {
            action: 'summon',
            description: $('#description').val(),
            id: 'registered'
            // mobile_number : number,
            // m_action : "send_otp"
        };

        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });


    });

    // On click at Register within Registration3 Form
    $('body').on('click', '#verify', function (e) {
        e.preventDefault();
        var eCode = $("#emailCode").val();
        const req_data = {
            action: 'summon',
            eCode: eCode,
            id: 'registerVerify'
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
    });

    // On click at Invitation in Discussion Page
    $('body').on('click', '#invite', function (e) {
        e.preventDefault();
        var inviteEmail = $("#invite_address").val();
        const req_data = {
            action: 'summon',
            inviteEmail: inviteEmail,
            id: 'invitation'
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {});
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

    // On click at Add Recommendation
    $('body').on('click', '.getRecomendID', function (e) {
        e.preventDefault();

        const $btnText = $('#addRecommendation');
        $btnText.text("Update");
        const $user = $('.userEmail').text();
        recommendationID = $(this).attr('value');
        const $recommendation = $('#recommend');
        $recommendation.val(recommendationID);

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
    });

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
            id: 'inpro',
            alertID: inprogress
        };
        jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
            // console.log("disble");
            $alertBox.html(response);
        });
        console.log("logged");
    });


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


    function sendOTP() {
        $(".error").html("").hide();
        var number = $("#mobile").val();
        if (number.length == 10 && number != null) {
            var input = {
                "mobile_number" : number,
                "m_action" : "send_otp"
            };
            $.ajax({
                url : 'controller.php',
                type : 'POST',
                data : input,
                success : function(response) {
                    $(".container").html(response);
                }
            });
        } else {
            $(".error").html('Please enter a valid number!')
            $(".error").show();
        }
    }

    function verifyOTP() {
        $(".error").html("").hide();
        $(".success").html("").hide();
        var otp = $("#mobileOtp").val();
        var input = {
            "otp" : otp,
            "action" : "verify_otp"
        };
        if (otp.length == 6 && otp != null) {
            $.ajax({
                url : 'controller.php',
                type : 'POST',
                dataType : "json",
                data : input,
                success : function(response) {
                    $("." + response.type).html(response.message)
                    $("." + response.type).show();
                },
                error : function() {
                    alert("ss");
                }
            });
        } else {
            $(".error").html('You have entered wrong OTP.')
            $(".error").show();
        }
    }
});


// $('body').on('click', '.inprogress', function (e) {
//     //e.preventDefault();
//
//     // var inprogress = $(this).attr('id');
//     var  inprogress = $(this).attr('value');
//     // const $alertPost = $('.alertPost').text();
//     // $('.tojoin').prop('disabled', true);
//     // $('.tojoin').addClass('grey');
//     const $alertBox = $('#alertPanel');
//     const req_data = {
//         action: 'summon',
//         id: 'inpro',
//         alertID: inprogress
//     };
//     jQuery.get(login_ajax.ajaxurl, req_data, function (response) {
//         // console.log("disble");
//         $alertBox.html(response);
//     });
//     console.log("logged");
// });
// function sendOTP() {
//     $(".error").html("").hide();
//     var number = $("#mobile").val();
//     if (number.length == 10 && number != null) {
//         var input = {
//             "mobile_number" : number,
//             "action" : "send_otp"
//         };
//         $.ajax({
//             url : 'controller.php',
//             type : 'POST',
//             data : input,
//             success : function(response) {
//                 $(".container").html(response);
//             }
//         });
//     } else {
//         $(".error").html('Please enter a valid number!')
//         $(".error").show();
//     }
// }


