jQuery(document).ready(function ($) {

    const $contentBox = $('#contentBox');

    // On click at Login
    $('body').on('click', '#send', function (e) {
        e.preventDefault();

        // need to find all inputs and see get the value
        const req_data = {
            action: 'fetch',
            naam: $('#naam').val(),
            paas: $('#paas').val()
        };

        // try to animate
        $contentBox.animate(100);

        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
            $contentBox.html(response);
        });
    });

    // On click at add comment
    $('body').on('click', '.submit_comment', function (e) {
        e.preventDefault();

        let $id = $(this).attr('id').match(/\d+$/).toString();

        const $comment = $('#display_comment'+$id);
        const req_data = {
            action: 'addcomment',
            comment_data: $('#get_comment'+$id).val(),
            comment_idd: $id
        };

        jQuery.get(myAjax.ajaxurl, req_data, function (response) {
            console.log("Comment Added");
            $comment.html(response);
        });
    });
});











































// jQuery.ajax({
//     type: "GET",
//     dataType: "json",
//     url: myAjax.ajaxUrl,
//     data: "formData",
//     success: function(msg){
//         console.log(msg);
//     }
// });
//
//
//
// document.addEventListener('DOMContentLoaded', function(e){
//   let arenaForm = document.getElementById('arena-login');
//
//   arenaForm.addEventListener('submit', (e) => {
//      e.preventDefault();
//
//     jQuery(document).ready(contentBox.innerHTML = 'Saad');
//     // console.log('Prevent From Submit');
//
//     // let data = {
//     //   email: arenaForm.querySelector('[name="email"]').value,
//     //   password: arenaForm.querySelector('[name="password"]').value
//     // };
//     //
//     // console.log(data);
//     //
//     // let url = arenaForm.dataset.url;
//     // let params = new URLSearchParams(new FormData(arenaForm));
//     //
//     // fetch(url, {
//     //   method: "POST",
//     //   body: params
//     // }).then(res => res.json());
//     //
//     // contentBox.animate({transform: [0, 10]});
//
//     // jQuery.get(admin_url('admin-ajax.php'), "req_data", function (response) {
//     //     contentBox.html(response).animate({ opacity: 1 }, 100);
//     // });
//
//
//
//     // contentBox.innerHTML = '<?php echo <h1>Saad</h1> <h2>Faiza</h2> ?>';
//     // document.write('<?php echo include_once \"SecondPage.php\";?>');
//   });
// });
//
// /*let contentBox = document.getElementById('contentBox');
// let arenaForm = document.getElementById('arena-login');
//
// function loadDoc() {
//
//   var xhttp = new XMLHttpRequest();
//   var method = "GET";
//   var url = "Shortcode.php";
//   var asynchronous = true;
//
//   xhttp.open(method, url, asynchronous);
//   //Sending Ajax Requests
//   xhttp.send();
//
//   //Receiving response from
//   xhttp.onreadystatechange = function() {
//     if (this.readyState == 4 && this.status == 200) {
//       contentBox.innerHTML = this.responseText;
//     }
//   };
// }*/
//
// /*let url = arenaForm.dataset.url;
// let params = new URLSearchParams(new FormData(arenaForm));
//
// arenaForm.querySelector('[name="submit"]').value = "Logging in ...";
// arenaForm.querySelector('[name="submit"]').disabled = true;
//
// fetch(url, {
//   method: "POST",
//   body: params
// }).then(res => res.json()).catch(error => 'error').then(response => {
//   if (response === 0 || !response.status){
//     status.innerHTML = response.message;
//     status.classList.add('Error');
//     return;
//   }
//   status.innerHTML = response.message;
//   status.classList.add('Success');
//   arenaForm.reset();
//
//   window.location.reload();
// });*/
//
//
//
// /*
// function getData(email){
//   let email_id = this.email;
//   let xhttp = new XMLHttpRequest();
//   let method = "GET";
//   let url = myAJAX.ajax_url;
//   let asynchronous = true;
//
//   xhttp.open(method, url, asynchronous);
//   //Sending Ajax Requests
//   xhttp.send();
//
//   //Receiving response from
//   xhttp.onreadystatechange = function() {
//     if (this.readyState == 4 && this.status == 200) {
//       contentBox.innerHTML = this.responseText;
//     }
//   };
// }
//
// */
//
// // let num = <?php echo json_encode($num) ?>