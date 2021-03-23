<?php echo form_open(
    get_uri("members/save"),
    array("id" => "members-form", "class" => "general-form", "role" => "form")
); ?>
<div class="modal-body clearfix">
    <div class="form-widget">
        <div class="widget-title clearfix">
            <div id="general-info-label" class="col-sm-4"><i class="fa fa-check-circle"></i>
                <strong> <?php echo plang('general_info'); ?></strong>
            </div>
            <div id="account-info-label" class="col-sm-4"><i class="fa fa-circle-o"></i>
                <strong> <?php echo plang('element_settings', array("account")); ?></strong>
            </div>
        </div>
        <div class="progress ml15 mr15">
            <div id="form-progress-bar"
                 class="progress-bar progress-bar-success progress-bar-striped"
                 role="progressbar"
                 aria-valuenow="40"
                 aria-valuemin="0"
                 aria-valuemax="100"
                 style="width: 50%">
            </div>
        </div>
    </div>
    <div class="tab-content mt15">
        <div role="tabpanel" class="tab-pane active" id="general-info-tab">
            <div class="form-group">
                <label for="first_name" class=" col-md-2">
                    <?php echo plang('first_name'); ?>
                </label>
                <div class=" col-md-10">
                    <?php echo form_input(
                        array(
                            "id" => "first_name",
                            "name" => "first_name",
                            "value" => get_property($member, "first_name"),
                            "class" => "form-control",
                            "placeholder" => plang('first_name'),
                            "data-rule-required" => true,
                            "data-msg-required" => plang("field_required", array("first_name"))
                        )
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="last_name" class=" col-md-2">
                    <?php echo plang('last_name'); ?>
                </label>
                <div class=" col-md-10">
                    <?php echo form_input(
                        array(
                            "id" => "last_name",
                            "name" => "last_name",
                            "value" => get_property($member, "last_name"),
                            "class" => "form-control",
                            "placeholder" => plang('last_name'),
                            "data-rule-required" => true,
                            "data-msg-required" => plang("field_required", array("last_name"))
                        )
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="address" class=" col-md-2">
                    <?php echo plang('address'); ?>
                </label>
                <div class=" col-md-10">
                    <?php echo form_textarea(
                        array(
                            "id" => "address",
                            "name" => "address",
                            "value" => get_property($member, "address"),
                            "class" => "form-control",
                            "placeholder" => plang('address'),
                            "data-rule-required" => true,
                            "data-msg-required" => plang("field_required", array("address"))
                        )
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="alternative_address" class=" col-md-2">
                    <?php echo plang('alternative_address'); ?>
                </label>
                <div class=" col-md-10">
                    <?php echo form_textarea(
                        array(
                            "id" => "alternative_address",
                            "name" => "alternative_address",
                            "value" => get_property($member, "alternative_address"),
                            "class" => "form-control",
                            "placeholder" => plang('alternative_address'),
                        )
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class=" col-md-2">
                    <?php echo plang('phone'); ?>
                </label>
                <div class=" col-md-10">
                    <?php echo form_input(
                        array(
                            "id" => "phone",
                            "name" => "phone",
                            "type" => "tel",
                            "value" => get_property($member, "phone"),
                            "class" => "form-control",
                            "placeholder" => plang('phone'),
                            "data-rule-required" => true,
                            "data-msg-required" => plang("field_required", array("phone"))
                        )
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="alternative_phone" class=" col-md-2">
                    <?php echo plang('alternative_phone'); ?>
                </label>
                <div class=" col-md-10">
                    <?php echo form_input(
                        array(
                            "id" => "alternative_phone",
                            "name" => "alternative_phone",
                            "type" => "tel",
                            "value" => get_property($member, "alternative_phone"),
                            "class" => "form-control",
                            "placeholder" => plang('alternative_phone'),
                        )
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="dob" class=" col-md-2">
                    <?php echo plang('dob'); ?>
                </label>
                <div class=" col-md-10">
                    <?php echo form_input(
                        array(
                            "id" => "dob",
                            "name" => "dob",
                            "value" => from_sql_date(get_property($member, "dob")),
                            "class" => "form-control",
                            "placeholder" => plang('dob'),
                            "data-rule-formattedDate" => true,
                            "data-rule-required" => true,
                            "data-msg-required" => plang("field_required", array("dob"))
                        )
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="skype" class=" col-md-2">
                    Skype
                </label>
                <div class=" col-md-10">
                    <?php echo form_input(
                        array(
                            "id" => "skype",
                            "name" => "skype",
                            "value" => get_property($member, "skype"),
                            "class" => "form-control",
                            "placeholder" => "Skype",
                        )
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="gender" class=" col-md-2">
                    <?php echo plang('gender'); ?>
                </label>
                <div class=" col-md-10">
                    <?php echo form_radio(
                        array(
                            "id" => "male",
                            "name" => "gender",
                            "data-msg-required" => plang("field_required"),
                        ),
                        "male", (get_property($member, "gender") === "female") ? false : true);
                    ?>
                    <label for="male" class="mr15">
                        <?php echo plang('male'); ?>
                    </label>

                    <?php echo form_radio(
                        array(
                            "id" => "female",
                            "name" => "gender",
                            "data-msg-required" => plang("field_required"),
                        ),
                        "female", (get_property($member, "gender") === "female") ? true : false);
                    ?>
                    <label for="female" class="">
                        <?php echo plang('female'); ?>
                    </label>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="account-info-tab">
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
                <label for="password" class="col-md-2 col-xs-12"><?php echo lang('password'); ?></label>
                <div class=" col-md-9 col-xs-11">
                    <div class="input-group">
                        <?php echo form_password(
                            array(
                                "id" => "password",
                                "name" => "password",
                                "class" => "form-control",
                                "placeholder" => plang('password'),
                                "autocomplete" => "off",
                                "data-rule-minlength" => 6,
                                "data-msg-required" => plang("field_required", array("password")),
                                "data-msg-minlength" => plang(
                                    "field_min_length",
                                    array("password", "6")
                                ),
                            )
                        ); ?>
                        <label for="password" class="input-group-addon clickable" id="generate_password"><span
                                    class="fa fa-key"></span> <?php echo plang('generate'); ?></label>
                    </div>
                </div>
                <div class="col-md-1 col-xs-1 p0">
                    <a href="#" id="show_hide_password" class="btn btn-default"
                       title="<?php echo plang('show_text'); ?>"><span class="fa fa-eye"></span></a>
                </div>
            </div>
            <div class="form-group">
                <label for="role" class="col-md-2"><?php echo plang('role'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_dropdown("role", $roles, array(), "class='select2' id='role'");
                    ?>
                    <div id="member-role-help-block" class="help-block ml10 hide">
                        <i class="fa fa-warning text-warning"></i>
                        <?php echo plang("admin_member_has_all_power"); ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="role" class="p15 pb0 pt0"><?php echo plang('send_element', array("details")); ?></label>
                <div style="display: inline;">
                    <?php echo form_checkbox(
                        array(
                            "id" => "send_details",
                            "name" => "send_details",
                            "data-on" => plang("yes"),
                            "data-off" => plang("no"),
                            "data-onstyle" => "success",
                            "checked" => false,
                            "value" => "enabled"
                        )
                    ); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal">
        <span class="fa fa-close"></span>
        <?php echo plang('close'); ?>
    </button>
    <button id="form-previous" type="button" class="btn btn-default hide">
        <span class="fa fa-arrow-circle-left"></span>
        <?php echo plang('previous'); ?>
    </button>
    <button id="form-next" type="button" class="btn btn-info">
        <span class="fa  fa-arrow-circle-right"></span>
        <?php echo plang('next'); ?>
    </button>
    <button id="form-submit" type="button" class="btn btn-primary hide">
        <span class="fa fa-check-circle"></span>
        <?php echo plang('save'); ?>
    </button>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#members-form").appForm({
            onSuccess: function (result) {
                if (result.success) {
                    websocket.addEvent("notification", "new member");
                    $("#members-table").appTable({newData: result.data, dataId: result.id});
                }
            },
            onSubmit: function () {
                $("#form-previous").attr('disabled', 'disabled');
            },
            onAjaxSuccess: function () {
                $("#form-previous").removeAttr('disabled');
            }
        });
        $("#members-form input").keydown(function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                if ($('#form-submit').hasClass('hide')) {
                    $("#form-next").trigger('click');
                } else {
                    $("#members-form").trigger('submit');
                }
            }
        });
        $("#first_name").focus();
        setDatePicker("#dob");
        $("#form-previous").click(function () {
            var $generalTab = $("#general-info-tab"),
                $accountTab = $("#account-info-tab"),
                $previousButton = $("#form-previous"),
                $nextButton = $("#form-next"),
                $submitButton = $("#form-submit");
            if ($accountTab.hasClass("active")) {
                $accountTab.removeClass("active");
                $generalTab.addClass("active");
                $nextButton.removeClass("hide");
                $submitButton.addClass("hide");
                $previousButton.hide();
                $("#form-progress-bar").width("50%");
                $("#general-info-label i").removeClass("fa-circle-o");
                $("#general-info-label i").addClass("fa-check-circle");
                $("#account-info-label i").addClass("fa-circle-o");
                $("#account-info-label i").removeClass("fa-check-circle");
            }
        });
        $(".select2").select2();
        $("#form-next").click(function () {
            var $generalTab = $("#general-info-tab"),
                $accountTab = $("#account-info-tab"),
                $previousButton = $("#form-previous"),
                $nextButton = $("#form-next"),
                $submitButton = $("#form-submit");
            if (!$("#members-form").valid()) {
                return false;
            }
            if ($generalTab.hasClass("active")) {
                $previousButton.show();
                $generalTab.removeClass("active");
                $accountTab.addClass("active");
                $previousButton.removeClass("hide");
                $nextButton.addClass("hide");
                $submitButton.removeClass("hide");
                $("#form-progress-bar").width("100%");
                $("#account-info-label i").removeClass("fa-circle-o");
                $("#account-info-label i").addClass("fa-check-circle");
                $("#membername").focus();
            }
        });
        $("#generate_password").click(function() {
            $("#password").val(getRandomString(8));
        });
        $("#show_hide_password").click(function() {
            var $target = $("#password"),
                type = $target.attr("type");
            if (type === "password") {
                $(this).attr("title", "<?php echo lang("hide_text"); ?>");
                $(this).html("<span class='fa fa-eye-slash'></span>");
                $target.attr("type", "text");
            } else if (type === "text") {
                $(this).attr("title", "<?php echo lang("show_text"); ?>");
                $(this).html("<span class='fa fa-eye'></span>");
                $target.attr("type", "password");
            }
        });
        $("#form-submit").click(function () {
            $("#members-form").trigger('submit');
        });
        $("#send_details").bootstrapToggle();
        var role = $("#role");
        var member_role_help_block = $("#member-role-help-block");
        if(role.val() === "0") {
            member_role_help_block.removeClass("hide");
        }
        role.change(function () {
            if(role.val() === "0") {
                member_role_help_block.removeClass("hide");
            } else {
                member_role_help_block.removeClass("hide");
                member_role_help_block.addClass("hide");
            }
        });
    });
</script>