<div class="panel panel-default">
    <div class='panel-heading'>
        <i class='fa fa-envelope-o mr10'></i>
        <?php echo plang(get_property($email_template, "template_name")) ?>
    </div>
    <?php echo form_open(
        get_uri("email_templates/save"),
        array("id" => "email-template-form", "class" => "general-form", "role" => "form")
    ); ?>
    <div class="modal-body clearfix">
        <?php echo form_hidden("id", get_property($email_template, "id")) ?>
        <div class='row'>
            <div class="form-group">
                <div class=" col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "email_subject",
                        "name" => "email_subject",
                        "value" => get_property($email_template, "email_subject"),
                        "class" => "form-control",
                        "placeholder" => plang('subject'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => plang("field_required", array("subject")),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class=" col-md-12">
                    <?php
                    echo form_textarea(array(
                        "id" => "custom_message",
                        "name" => "custom_message",
                        "value" => empty(get_property($email_template, "custom_message")) ?
                            get_property($email_template, "default_message") :
                            get_property($email_template, "custom_message"),
                        "class" => "form-control"
                    ));
                    ?>
                </div>
            </div>
        </div>
        <?php if(count($variables)) { ?>
            <div>
                <strong><?php echo plang("available_variables"); ?></strong>:
                <?php
                foreach ($variables as $variable) {
                    echo "{" . $variable . "}, ";
                }
                ?>
            </div>
        <?php } ?>
        <hr/>
        <div class="form-group m0">
            <button type="submit" class="btn btn-primary mr15">
                <span class="fa fa-check-circle"></span> <?php echo plang('save'); ?>
            </button>
            <button id="restore_to_default" data-id="<?php echo $email_template->id; ?>
            " data-placement="top" type="button" class="btn btn-danger">
                <span class="fa fa-refresh"></span>
                <?php echo plang('restore_to_default'); ?>
            </button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#email-template-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                var custom_message = encodeAjaxPostData($("#custom_message").code());
                $.each(data, function (index, obj) {
                    if (obj.name === "custom_message") {
                        data[index]["value"] = custom_message;
                    }
                });
            },
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    appAlert.error(result.message);
                }
            }
        });
        $("#custom_message").summernote({
            height: 480,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'hr']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });

        $('#restore_to_default').confirmation({
            btnOkLabel: "<?php echo plang('yes'); ?>",
            btnCancelLabel: "<?php echo plang('no'); ?>",
            onConfirm: function () {
                $.ajax({
                    url: "<?php echo get_uri('email_templates/restore_to_default') ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {id: this.id},
                    success: function (result) {
                        if (result.success) {
                            $('#custom_message').code(result.data);
                            appAlert.success(result.message, {duration: 10000});
                        } else {
                            appAlert.error(result.message);
                        }
                    }
                });

            }
        });
    });
</script>    