<?php echo form_open(
    get_uri("roles/save"),
    array(
        "id" => "roles-form",
        "class" => "general-form",
        "role" => "form"
    )
); ?>
<div class="modal-body clearfix">
    <?php echo form_hidden("id", get_property($role, "id")) ?>
    <div class="form-group">
        <label for="title" class=" col-md-2">
            <?php echo plang('title'); ?>
        </label>
        <div class=" col-md-10">
            <?php echo form_input(
                array(
                    "id" => "title",
                    "name" => "title",
                    "value" => get_property($role, "title"),
                    "class" => "form-control",
                    "placeholder" => plang('title'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => plang("field_required", array("title"))
                )
            ); ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="fa fa-close"></span> <?php echo plang('close'); ?>
    </button>
    <button type="submit" class="btn btn-primary">
        <span class="fa fa-check-circle"></span> <?php echo plang('save'); ?>
    </button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#roles-form").appForm({
            onSuccess: function(result) {
                $("#roles-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#title").focus();
    });
</script>