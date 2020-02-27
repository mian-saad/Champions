
<div class="card-body" id="contentBox">
    <h2 class="title">Login Form</h2>
    <form id="arena-login" method="POST" action="#" >
        <div class="row row-space">
            <div class="col-2">
                <div class="input-group">
                    <label class="label">Email</label>
                    <input id="naam" type="email" name="email">
                </div>
            </div>
            <div class="col-2">
                <div class="input-group">
                    <label class="label">Password</label>
                    <input id="paas" type="password" name="password" minlength="4">
                </div>
            </div>
        </div>
        <div class="p-t-15">
            <button id="send" type="submit" onclick="return false;">Submit</button>
        </div>
        <input type="hidden" name="action" value="getData">
    </form>
</div>
