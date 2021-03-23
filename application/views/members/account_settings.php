<div class="tab-content">
    <?php echo form_open(
        get_uri("members/save_account_settings/"),
        array(
            "id" => "account-settings-form",
            "class" => "general-form dashed-row white",
            "role" => "form"
        )
    ); ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php echo plang('element_settings', array("account")); ?></h4>
        </div>
        <div class="panel-body">
            <?php echo form_hidden("id", get_property($member, "id")) ?>
            <div class="form-group">
                <label for="email" class=" col-md-2">
                    <?php echo plang('email'); ?>
                </label>
                <div class=" col-md-10">
                    <?php echo form_input(
                        array(
                            "id" => "email",
                            "name" => "email",
                            "type" => "email",
                            "value" => get_property($member, "email"),
                            "class" => "form-control",
                            "placeholder" => plang('email'),
                            "data-rule-required" => true,
                            "data-rule-email" => true,
                            "data-msg-required" => plang("field_required", array("email")),
                            "data-msg-email" => plang("field_valid_email", array("email"))
                        )
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-md-2">
                    <?php echo plang('password'); ?>
                </label>
                <div class=" col-md-10">
                    <?php echo form_password(
                        array(
                            "id" => "password",
                            "name" => "password",
                            "class" => "form-control",
                            "placeholder" => plang('password'),
                            "autocomplete" => "off",
                            "data-rule-minlength" => 6,
                            "data-msg-minlength" => plang(
                                "field_min_length",
                                array("password", "6")
                            ),
                        )
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="retype_password" class="col-md-2">
                    <?php echo plang('retype_element', array("password")); ?>
                </label>
                <div class=" col-md-10">
                    <?php echo form_password(
                        array(
                            "id" => "retype_password",
                            "name" => "retype_password",
                            "class" => "form-control",
                            "placeholder" => plang('retype_element', array("password")),
                            "autocomplete" => "off",
                            "data-rule-equalTo" => "#password",
                            "data-msg-equalTo" => plang(
                                "field_matches",
                                array("retype_password", "password")
                            )
                        )
                    ); ?>
                </div>
            </div>
            <?php if (logged_is_admin()) { ?>
                <style>
                    .general-form.white label {
                        margin: 0 !important;
                    }
                </style>
                <div class="form-group">
                    <label for="role" class="col-md-2"><?php echo plang('role'); ?></label>
                    <div class="col-md-3">
                        <?php
                        echo form_dropdown(
                            "role",
                            $roles,
                            array(get_property($member, "role_id")),
                            "class='select2' id='role'"
                        );
                        ?>
                        <div id="member-role-help-block" class="help-block ml10 hide">
                            <i class="fa fa-warning text-warning"></i>
                            <?php echo plang("admin_member_has_all_power"); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="disable_login" class="col-md-2">
                        <?php echo plang('disable_login'); ?>
                    </label>
                    <div class="col-md-10">
                        <?php echo form_checkbox(
                            "disable_login",
                            "1",
                            empty(get_property($member, "disable_login")) ? false : true,
                            "id='disable_login' class='ml15'"
                        ); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-md-2">
                        <?php echo plang('mark_as_inactive'); ?>
                    </label>
                    <div class="col-md-10">
                        <?php echo form_checkbox(
                            "status",
                            "inactive",
                            get_property($member, "status") === "inactive" ? true : false,
                            "id='status' class='ml15' style='margin-top:0'"
                        ); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary">
                <span class="fa fa-check-circle"></span>
                <?php echo plang('save'); ?>
            </button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#account-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        var role = $("#role");
        var member_role_help_block = $("#member-role-help-block");
        if (role.val() === "0") {
            member_role_help_block.removeClass("hide");
        }
        role.change(function () {
            if (role.val() === "0") {
                member_role_help_block.removeClass("hide");
            } else {
                member_role_help_block.removeClass("hide");
                member_role_help_block.addClass("hide");
            }
        });
        $("#disable_login").bootstrapToggle({
            on: '<?php echo plang("yes") ?>',
            off: '<?php echo plang("no") ?>',
            onstyle: 'warning'
        });
        $("#status").bootstrapToggle({
            on: '<?php echo plang("yes") ?>',
            off: '<?php echo plang("no") ?>',
            onstyle: 'warning'
        });
        $(".select2").select2();
    });
</script>