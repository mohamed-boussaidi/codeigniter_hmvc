<div id="page-content" class="p20 row">
    <div class="col-sm-3 col-lg-2">
        <?php $this->load->view("settings/tabs", array("tab" => "email_settings")) ?>
    </div>

    <div class="col-sm-9 col-lg-10">
        <?php echo form_open(
            get_uri("email_settings/save"),
            array(
                "id" => "email-settings-form",
                "class" => "general-form dashed-row",
                "role" => "form"
            )
        ); ?>
        <div class="panel">
            <div class="panel-default panel-heading">
                <h4><?php echo plang("element_settings", array("email")); ?></h4>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label for="email_sent_from_address" class=" col-md-2">
                        <?php echo plang('email_sent_from_address'); ?>
                    </label>
                    <div class=" col-md-10">
                        <?php echo form_input(array(
                            "id" => "email_sent_from_address",
                            "name" => "email_sent_from_address",
                            "value" => get_setting('email_sent_from_address'),
                            "class" => "form-control",
                            "placeholder" => "contact@mind.engineering",
                            "data-rule-required" => true,
                            "data-msg-required" => plang(
                                "field_required",
                                array("email_sent_from_address")
                            ),
                        )); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email_sent_from_name" class=" col-md-2">
                        <?php echo plang('email_sent_from_name'); ?>
                    </label>
                    <div class="col-md-10">
                        <?php echo form_input(array(
                            "id" => "email_sent_from_name",
                            "name" => "email_sent_from_name",
                            "value" => get_setting('email_sent_from_name'),
                            "class" => "form-control",
                            "placeholder" => plang("email_sent_from_name_placeholder"),
                            "data-rule-required" => true,
                            "data-msg-required" => plang(
                                "field_required",
                                array("email_sent_from_name")
                            ),
                        )); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="use_smtp" class=" col-md-2">
                        <?php echo plang('email_use_smtp'); ?>
                    </label>
                    <div class="col-md-10">
                        <?php echo form_checkbox(
                            "email_protocol",
                            "smtp",
                            get_setting('email_protocol') === "smtp" ? true : false,
                            "id='use_smtp'"
                        ); ?>
                    </div>
                </div>

                <div id="smtp_settings" class="
                <?php echo get_setting('email_protocol') === "smtp" ? "" : "hide"; ?>">
                    <div class="form-group">
                        <label for="email_smtp_host" class=" col-md-2">
                            <?php echo plang('email_smtp_host'); ?>
                        </label>
                        <div class="col-md-10">
                            <?php echo form_input(array(
                                "id" => "email_smtp_host",
                                "name" => "email_smtp_host",
                                "value" => get_setting('email_smtp_host'),
                                "class" => "form-control",
                                "placeholder" => plang('email_smtp_host'),
                                "data-rule-required" => true,
                                "data-msg-required" => plang(
                                    "field_required",
                                    array("email_smtp_host")
                                ),
                            )); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email_smtp_user" class=" col-md-2">
                            <?php echo plang('email_smtp_user'); ?>
                        </label>
                        <div class="col-md-10">
                            <?php echo form_input(array(
                                "id" => "email_smtp_user",
                                "name" => "email_smtp_user",
                                "value" => get_setting('email_smtp_user'),
                                "class" => "form-control",
                                "placeholder" => plang('email_smtp_user'),
                                "data-rule-required" => true,
                                "data-msg-required" => plang(
                                    "field_required",
                                    array("email_smtp_user")
                                ),
                            )); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email_smtp_pass"
                               class=" col-md-2"><?php echo plang('email_smtp_password'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "email_smtp_pass",
                                "name" => "email_smtp_pass",
                                "value" => get_setting('email_smtp_pass'),
                                "class" => "form-control",
                                "placeholder" => plang('email_smtp_password'),
                                "data-rule-required" => true,
                                "data-msg-required" => plang(
                                    "field_required",
                                    array("email_smtp_password")
                                ),
                            )); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email_smtp_port" class=" col-md-2">
                            <?php echo plang('email_smtp_port'); ?>
                        </label>
                        <div class="col-md-10">
                            <?php echo form_input(array(
                                "id" => "email_smtp_port",
                                "name" => "email_smtp_port",
                                "value" => get_setting('email_smtp_port'),
                                "class" => "form-control",
                                "placeholder" => plang('email_smtp_port'),
                                "data-rule-required" => true,
                                "data-msg-required" => plang(
                                    "field_required",
                                    array("email_smtp_port")
                                ),
                            )); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email_smtp_security_type" class=" col-md-2">
                            <?php echo plang('security_type'); ?>
                        </label>
                        <div class="col-md-10">
                            <?php echo form_dropdown(
                                "email_smtp_security_type", array(
                                "tls" => "TLS",
                                "ssl" => "SSL"
                            ), get_setting('email_smtp_security_type'), "class='select2 mini'"
                            ); ?>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <label for="send_test_mail_to" class=" col-md-2">
                        <?php echo plang('send_test_mail_to'); ?>
                    </label>
                    <div class="col-md-10">
                        <?php echo form_input(array(
                            "id" => "send_test_mail_to",
                            "name" => "send_test_mail_to",
                            "value" => get_setting('send_test_mail_to'),
                            "class" => "form-control",
                            "placeholder" => plang("send_test_mail_to_placeholder"),
                        )); ?>
                    </div>
                </div>
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
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#email-settings-form").appForm({
            isModal: false,
            onSubmit: function () {
                appLoader.show();
            },
            onSuccess: function (result) {
                appLoader.hide();
                appAlert.success(result.message, {duration: 10000});
            },
            onError: function () {
                appLoader.hide();
            }
        });

        $("#email-settings-form .select2").select2();

        $("#use_smtp").bootstrapToggle({
            on: '<?php echo plang("yes") ?>',
            off: '<?php echo plang("no") ?>',
            onstyle: 'success'
        });

        $("#use_smtp").change(function () {
            if ($(this).is(":checked")) {
                $("#smtp_settings").removeClass("hide");
            } else {
                $("#smtp_settings").addClass("hide");
            }
        });
    });
</script>