<div class="panel panel-default mb15">
    <div class="panel-heading text-center no-border">
        <h2><?php echo plang("signin") ?></h2>
    </div>
    <div class="panel-body p30">
        <?php echo form_open(
            get_uri("signin"),
            array(
                "id" => "signin-form",
                "class" => "general-form text-left",
                "role" => "form"
            )
        );
        if (validation_errors()) { ?>
            <div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <?php echo validation_errors() ?>
            </div>
        <?php } ?>
        <div class="form-group">
            <?php echo form_input(
                array(
                    "id" => "email",
                    "name" => "email",
                    "value" => $email,
                    "type" => "email",
                    "class" => "form-control p10",
                    "placeholder" => plang('email'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => plang("field_required", array("email")),
                    "data-rule-email" => true,
                    "data-msg-email" => plang("field_valid_email", array("email"))
                )
            ); ?>
        </div>
        <div class="form-group">
            <?php echo form_password(
                array(
                    "id" => "password",
                    "name" => "password",
                    "class" => "form-control p10",
                    "placeholder" => plang('password'),
                    "data-rule-required" => true,
                    "data-msg-required" => plang("field_required", array("password"))
                )
            ); ?>
        </div>
        <input type="hidden" name="redirect" value="<?php echo $redirect ?>"/>
        <div class="form-group mb0">
            <button class="btn btn-lg btn-primary btn-block mt15" type="submit">
                <?php echo plang("signin") ?>
            </button>
        </div>
        <?php
            build('signin_form');
        ?>
        <?php echo form_close() ?>
        <div class="mt5">
            <?php echo anchor("signin/request_reset_password", plang("forgot_password")); ?>
        </div>
    </div>
</div>
<?php
build('signin_form_down');
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#signin-form").appForm({ajaxSubmit: false, isModal: false});
    });
</script>