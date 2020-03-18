jQuery(document).ready(function ($) {

    const $contentBox = $('#contentBox');


    // On click at Login
    $('body').on('click', '#login', function (e) {
        e.preventDefault();

        const req_data = {
            action: 'fetch',
            id: 'flogin'
        };

        $contentBox.animate({ opacity: 0.5 }, 100);

        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
            $contentBox.html(response).animate({ opacity: 1 }, 100);
        });

    });

    // On click at Register
    $('body').on('click', '#register', function (e) {
        e.preventDefault();

        const req_data = {
            action: 'fetch',
            id: 'fregister'
        };
        $contentBox.animate(100);
        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });

    });

    // On click at Login within Login Form
    $('body').on('click', '#send', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        const req_data = {
            action: 'fetch',
            email: $('#naam').val(),
            pass: $('#paas').val(),
            id: 'login'
        };

        // try to animate
        $contentBox.animate(100);

        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
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
                action: 'fetch',
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                country: $('#country').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                title: $('#title').val(),
                id: 'register'
            };

            jQuery.get(myAjax.ajaxurl, req_data, function (response) {
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
            action: 'fetch',
            skill: jsonString,
            id: 'register1'
        };

        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });


    });

    // On click at Register within Registration3 Form
    $('body').on('click', '#registered', function (e) {
        e.preventDefault();

        const req_data = {
            action: 'fetch',
            description: $('#description').val(),
            id: 'registered'
        };

        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });


    });

    // On click at ALERT
    $('body').on('click', '#toAlert', function (e) {
        e.preventDefault();

        var  alertID = $(this).attr('value');
        const $alertBox = $('#alertPanel');

        const req_data = {
            action: 'fetch',
            id: 'alerter',
            alertID: alertID
        };

        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
            $alertBox.html(response);
        });


    });

    // On click at BACK
    $('body').on('click', '#alertBack', function (e) {
        e.preventDefault();

        const $alertBox = $('#alertPanel');
        const $j = $('#toAlert').val();

        const req_data = {
            action: 'fetch',
            id: 'alertBack',
            iterator: $j
        };

        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
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
            action: 'fetch',
            comment_data: $('#get_comment').val(),
            // comment_idd: $id,
            id: 'cmnt',
            mail: $user
        };


        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
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
            action: 'fetch',
            id: 'join',
            post: $alertPost,
            alertV: alert
        };
        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
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
            action: 'fetch',
            id: 'inpro',
            alertID: inprogress
        };
        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
            // console.log("disble");
            $alertBox.html(response);
        });
        console.log("logged");
    });


    setInterval(function (e) {

        const $comment = $('#display_comment');
        const req_data = {
            action: 'fetch',
            id: 'showcmnt',
        };
        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
            $comment.html(response);
        });
    }, 3000);
});
