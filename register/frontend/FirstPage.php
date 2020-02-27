<div id="contain">
    <h2>Registration Form</h2>
    <form id="arena-registration" method="POST" action="#" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
        <div class="row row-space">
            <div class="col-2">
                <div class="input-group">
                    <label class="label">First Name</label>
                    <input type="text" name="first_name" required>
                </div>
            </div>
            <div class="col-2">
                <div class="input-group">
                    <label class="label">Last Name</label>
                    <input class="field-msg" type="text" name="last_name" required>
                </div>
            </div>
        </div>
        <div class="row row-space">
            <div class="col-2">
                <div class="input-group">
                    <label class="label">Birthday</label>
                    <div class="input-group-icon">
                        <input class="field-msg js-datepicker" type="date" name="birthday" required>
                        <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="input-group">
                    <label class="label">Gender</label>
                    <div class="p-t-10">
                        <label class="radio-container m-r-45">Male
                            <input type="radio" checked="checked" name="gender">
                            <span class="checkmark"></span>
                        </label>
                        <label class="radio-container">Female
                            <input type="radio" name="gender">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-space">
            <div class="col-2">
                <div class="input-group">
                    <label class="label">Email</label>
                    <input type="email" name="email" required>
                </div>
            </div>
            <div class="col-2">
                <div class="input-group">
                    <label class="label">Password</label>
                    <input type="password" name="password" minlength="4" required>
                </div>
            </div>
        </div>
        <div class="input-group">
            <label class="label">Title</label>
            <div class="rs-select2 js-select-simple select--no-search">
                <select name="skills" required>
                    //disabling to make <i>required</i> work
<!--                    <option disabled="disabled" selected="selected">Choose Option</option>-->
                    <option>Teacher</option>
                    <option>Doctor</option>
                    <option>Police</option>
                </select>
                <div class="select-dropdown"></div>
            </div>
        </div>
        <div class="p-t-15">
            <button type="submit">Submit</button>
        </div>
        <input type="hidden" name="action" value="getData">
    </form>
</div>
