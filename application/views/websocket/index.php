<div id="page-content" class="p20 row">
    <div class="col-sm-3 col-lg-2">
        <?php $this->load->view("settings/tabs", array("tab" => "websocket")) ?>
    </div>
    <div class="col-sm-9 col-lg-10">
        <?php echo form_open(
            get_uri("websocket/save"),
            array(
                "id" => "websocket-form",
                "class" => "general-form dashed-row",
                "role" => "form"
            )
        ); ?>
        <div class="panel">
            <div class="panel-default panel-heading">
                <h4>
                    <?php echo plang("element_settings", array("websocket")); ?>
                </h4>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label for="websocket_ip" class=" col-md-2">
                        <?php echo plang('websocket_ip'); ?>
                    </label>
                    <div class="col-md-10">
                        <?php echo form_input(array(
                            "id" => "websocket_ip",
                            "name" => "websocket_ip",
                            "value" => get_setting('websocket_ip'),
                            "class" => "form-control",
                            "placeholder" => plang("websocket_ip_placeholder"),
                            "data-rule-IPOrEmpty" => true,
                        )); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="websocket_port" class=" col-md-2">
                        <?php echo plang('websocket_port'); ?>
                    </label>
                    <div class="col-md-10">
                        <?php echo form_input(array(
                            "id" => "websocket_port",
                            "name" => "websocket_port",
                            "type" => "number",
                            "value" => get_setting('websocket_port'),
                            "class" => "form-control",
                            "placeholder" => plang("websocket_port"),
                            "data-rule-maxlength" => "5",
                            "data-rule-required" => true,
                            "data-msg-maxlength" => plang("field_max_length", array("websocket_port", "5")),
                            "data-msg-required" => plang("field_required", array("websocket_port")),
                        )); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class=" col-md-2">
                        <?php echo plang('websocket_status'); ?>
                    </label>
                    <div class="col-md-10">
                        <?php echo form_checkbox(
                            "websocket_status",
                            "",
                            get_setting("websocket_process"),
                            "id='websocket_status'"
                        ); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="websocket_process" class=" col-md-2">
                        <?php echo plang('websocket_process'); ?>
                    </label>
                    <div class="col-md-10">
                        <?php echo form_input(array(
                            "id" => "websocket_process",
                            "readonly" => true,
                            "value" => get_setting('websocket_process'),
                            "class" => "form-control",
                            "placeholder" => plang("websocket_process"),
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
    </div>
    <?php echo form_close(); ?>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $("#websocket-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                kill_websocket_server();
                location.reload();
            }
        });
        $(".select2").select2();
        $(".upload").change(function () {
            showCropBox(this);
        });

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

        $("#websocket_status").bootstrapToggle({
            on: 'ON',
            off: 'OFF',
            onstyle: 'success'
        });

        $("#websocket_status").change(function () {
            $("#websocket_status").bootstrapToggle('disable');
            appLoader.show();
            if ($(this).is(":checked")) {
                $.post("<?php echo base_url("websocket/run") ?>", function (data) {
                    $("#websocket_status").bootstrapToggle('enable');
                    if (data.success) {
                        $("#websocket_process").val(data.pid);
                        appAlert.success(data.message, {duration: 3000});
                        appLoader.hide();
                    } else {
                        $("#websocket_process").val("");
                        appAlert.error(data.message, {duration: 3000});
                        appLoader.hide();
                    }
                }, "json");
            } else {
                kill_websocket_server();
            }
        });

        $("#email-settings-form .select2").select2();

        function kill_websocket_server() {
            $.post("<?php echo base_url("websocket/kill") ?>", function (data) {
                $("#websocket_status").bootstrapToggle('enable');
                if (data.success) {
                    $("#websocket_process").val("");
                    appAlert.success(data.message, {duration: 3000});
                    appLoader.hide();
                } else {
                    appAlert.error(data.message, {duration: 3000});
                    appLoader.hide();
                }
            }, "json");
        }
    });
</script>