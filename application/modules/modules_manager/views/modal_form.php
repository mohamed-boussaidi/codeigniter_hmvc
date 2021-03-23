<?php echo form_open(
    get_uri("modules_manager/save"),
    array("id" => "modules-manager-form", "class" => "general-form", "role" => "form")
); ?>
<div class="modal-body clearfix">
    <div class="form-group">
        <label for="file_path" class=" col-md-2 col-xs-12"><?php echo plang('file'); ?></label>
        <div class=" col-md-9 col-xs-10">
            <?php
            echo form_input(
                array(
                    "id" => "file_path",
                    "name" => "file_path",
                    "value" => "",
                    "class" => "form-control",
                    "placeholder" => plang('file_path'),
                    "data-rule-required" => true,
                    "data-msg-required" => plang("field_required", array("file_path")),
                )
            );
            ?>
        </div>
        <div class="col-md-1 col-xs-1">
            <input name="file" type="file" id="file_brows_btn" style="display: none">
            <button id="upload-file-btn"
                    class="btn btn-default upload-file-btn pull-right btn-sm round"
                    type="button" style="color:#7988a2">
                <i class='fa fa-file'></i>
            </button>
        </div>
    </div>
</div>
<div id="modules-manager-files-modal-footer" class="modal-footer">
    <button type="button" class="btn btn-default cancel-upload" data-dismiss="modal">
        <span class="fa fa-close"></span>
        <?php echo plang('close'); ?>
    </button>
    <button id="modules-manager-files-save-button" type="submit"
            class="btn btn-primary start-upload">
        <span class="fa fa-check-circle"></span> <?php echo plang('save'); ?>
    </button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#modules-manager-form").appForm({
            onSuccess: function (result) {
                $("#modules-manager-table").appTable({reload: true});
            }
        });
        <?php validate_readonly("file_path") ?>
        <?php simple_file_upload(
            "upload-file-btn",
            "file_brows_btn",
            "file_path",
            "modules_manager/validate_zip_file",
            "modules-manager-form"
        ) ?>
    });
</script>
