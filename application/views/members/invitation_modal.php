<?php echo form_open(
    get_uri("members/send_invitation"),
    array("id" => "invitation-form", "class" => "general-form", "role" => "form")
); ?>
<div class="modal-body clearfix"><br/>
    <div class="form-group mb15">
        <label for="email" class=" col-md-12">
            <?php echo plang('invite_someone_to_join_as_a_member'); ?>
        </label>
        <div class="col-md-12">
            <?php echo form_input(array(
                "id" => "email",
                "name" => "email",
                "class" => "form-control",
                "placeholder" => plang('email'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => plang("field_required", array("email")),
                "data-rule-email" => true,
                "data-msg-email" => plang("field_valid_email", array("email"))
            )); ?>
        </div>
    </div>
    <br/>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="fa fa-close"></span>
        <?php echo plang('close'); ?>
    </button>
    <button type="submit" class="btn btn-primary">
        <span class="fa fa-send"></span>
        <?php echo plang('send'); ?>
    </button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#invitation-form").appForm({
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        $("#email").focus();
    });
</script>    