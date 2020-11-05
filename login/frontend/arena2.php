
<div class="card-body" id="contentBox">
    <h1>ARENA Module</h1>
    <div class='row'>
        <div class='col-2'></div>
        <div class='col-8'>
            <form id='arena-login' method='POST' action='#' >
                <label>Email</label>
                <input id='naam' type='email' name='email'><br>

                <label>Password</label>
                <input id='paas' type='password' name='password' minlength='4'>

                <button class='button login-button' id='Login' type='submit' >Login as FLP Expert</button>

                <a id="forgot" href="#" onclick="return false;">Forgot Password</a>
            </form>
<!--                <input type='hidden' name='action' value='getData'>-->
        </div>
        <div class='col-2'></div>
    </div>

    <div class="row">
        <div class='col-2'></div>
        <div class="col-8">
            <div id='login_button_pane'>
                <a class='button' href='https://www.firstlinepractitioners.com/arena/' >Register as FLP Expert</a>
                <a id="ToBackdoorLogin" class='button' href='#' onclick='return false;'>Login As National Moderator</a>
            </div>
        </div>
        <div class='col-2'></div>
    </div>


</div>
