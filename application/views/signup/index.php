<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('template/head'); ?>
</head>
<body>

<div id="page-content" class="clearfix">
    <div class="scrollable-page" >
        <div class="signin-box">
            <div class="panel panel-default clearfix" style="padding-top:20px">
                <div style="text-align:center">
                    <img style="margin:10px"
                         src="<?php echo files_url("system", get_setting("site_logo")) ?>">
                </div>
                <div class="panel-heading text-center">
                    <h2 class="form-signin-heading">
                        <?php echo plang('signup'); ?>
                    </h2>
                    <p><?php echo $signup_message; ?></p>
                </div>
                <div class="panel-body">

                    <?php echo form_open(
                        "signup/create_account",
                        array("id" => "signup-form", "class" => "text-left general-form", "role" => "form")
                    ); ?>

                    <div class="form-group">
                        <label for="name" class=" col-md-12">
                            <?php echo plang('first_name'); ?>
                        </label>
                        <div class="col-md-12">
                            <?php echo form_input(array(
                                "id" => "first_name",
                                "name" => "first_name",
                                "class" => "form-control",
                                "placeholder" => plang("first_name"),
                                "autofocus" => true,
                                "data-rule-required" => true,
                                "data-msg-required" => plang("field_required", array("first_name")),
                            )); ?>
                        </div>
                    </div>

                    <input type="hidden" name="signup_key"
                           value="<?php echo isset($signup_key) ? $signup_key : ''; ?>"/>
                    <div class="form-group">
                        <label for="last_name" class=" col-md-12">
                            <?php echo plang('last_name'); ?>
                        </label>
                        <div class=" col-md-12">
                            <?php
                            echo form_input(array(
                                "id" => "last_name",
                                "name" => "last_name",
                                "placeholder" => plang("last_name"),
                                "class" => "form-control",
                                "data-rule-required" => true,
                                "data-msg-required" => plang("field_required", array("last_name")),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone " class=" col-md-12">
                            <?php echo plang('phone'); ?>
                        </label>
                        <div class=" col-md-12">
                            <?php echo form_input(array(
                                "type" => "phone",
                                "id" => "phone",
                                "name" => "phone",
                                "placeholder" => plang("phone"),
                                "class" => "form-control",
                                "autofocus" => true,
                                "data-rule-required" => true,
                                "data-msg-required" => plang("field_required", array("phone")),
                            )); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class=" col-md-12">
                            <button class="btn btn-lg btn-primary btn-block" type="submit">
                                <?php echo plang('signup'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div id="signin_link" class="text-center">
                <?php echo
                    lang("already_have_an_account") .
                    " " .
                    anchor("signin", lang("signin"));
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#signup-form").appForm({
            isModal: false,
            onSubmit: function () {
                appLoader.show();
            },
            onSuccess: function (result) {
                appLoader.hide();
                appAlert.success(result.message, {container: '.panel-body', animate: false});
                $("#signup-form").remove();
                $("#signin_link").remove();
                websocket.addEvent("notification", "new member");
            },
            onError: function (result) {
                appLoader.hide();
                appAlert.error(result.message, {container: '.panel-body', animate: false});
                return false;
            }
        });
    });
</script>
</body>
</html>
