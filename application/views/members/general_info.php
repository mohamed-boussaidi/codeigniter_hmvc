<div class="tab-content">
    <?php echo form_open(
        get_uri("members/save_general_info/"),
        array(
            "id" => "general-info-form",
            "class" => "general-form dashed-row white",
            "role" => "form"
        )
    ); ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php echo plang('general_info'); ?></h4>
        </div>
        <div class="panel-body">
            <?php echo form_hidden("id", get_property($member, "id")) ?>
            <div class="form-group">
                <label for="first_name" class=" col-md-2">
                    <?php echo plang('first_name'); ?>
                </label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), get_property($member, "id"))) {
                        echo form_input(
                            array(
                                "id" => "first_name",
                                "name" => "first_name",
                                "value" => get_property($member, "first_name"),
                                "class" => "form-control",
                                "placeholder" => plang('first_name'),
                                "data-rule-required" => true,
                                "data-msg-required" => plang("field_required", array("first_name"))
                            )
                        );
                    } else {
                        echo form_label(get_property($member, "first_name", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="last_name" class=" col-md-2">
                    <?php echo plang('last_name'); ?>
                </label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), get_property($member, "id"))) {
                        echo form_input(
                            array(
                                "id" => "last_name",
                                "name" => "last_name",
                                "value" => get_property($member, "last_name"),
                                "class" => "form-control",
                                "placeholder" => plang('last_name'),
                                "data-rule-required" => true,
                                "data-msg-required" => plang("field_required", array("last_name"))
                            )
                        );
                    } else {
                        echo form_label(get_property($member, "last_name", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="address" class=" col-md-2">
                    <?php echo plang('address'); ?>
                </label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), get_property($member, "id"))) {
                        echo form_textarea(
                            array(
                                "id" => "address",
                                "name" => "address",
                                "value" => get_property($member, "address"),
                                "class" => "form-control",
                                "placeholder" => plang('address'),
                                "data-rule-required" => true,
                                "data-msg-required" => plang("field_required", array("address"))
                            )
                        );
                    } else {
                        echo form_label(get_property($member, "address", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="alternative_address" class=" col-md-2">
                    <?php echo plang('alternative_address'); ?>
                </label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), get_property($member, "id"))) {
                        echo form_textarea(
                            array(
                                "id" => "alternative_address",
                                "name" => "alternative_address",
                                "value" => get_property($member, "alternative_address"),
                                "class" => "form-control",
                                "placeholder" => plang('alternative_address'),
                            )
                        );
                    } else {
                        echo form_label(get_property($member, "alternative_address", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class=" col-md-2">
                    <?php echo plang('phone'); ?>
                </label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), get_property($member, "id"))) {
                        echo form_input(
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
                        );
                    } else {
                        echo form_label(get_property($member, "phone", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="alternative_phone" class=" col-md-2">
                    <?php echo plang('alternative_phone'); ?>
                </label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), get_property($member, "id"))) {
                        echo form_input(
                            array(
                                "id" => "alternative_phone",
                                "name" => "alternative_phone",
                                "type" => "tel",
                                "value" => get_property($member, "alternative_phone"),
                                "class" => "form-control",
                                "placeholder" => plang('alternative_phone'),
                            )
                        );
                    } else {
                        echo form_label(get_property($member, "alternative_phone", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="dob" class=" col-md-2">
                    <?php echo plang('dob'); ?>
                </label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), get_property($member, "id"))) {
                        echo form_input(
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
                        );
                    } else {
                        echo form_label(from_sql_date(get_property($member, "dob", "-")));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="skype" class=" col-md-2">
                    Skype
                </label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), get_property($member, "id"))) {
                        echo form_input(array(
                                "id" => "skype",
                                "name" => "skype",
                                "value" => get_property($member, "skype"),
                                "class" => "form-control",
                                "placeholder" => "Skype",
                            )
                        );
                    } else {
                        echo form_label(get_property($member, "skype", "-"));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="gender" class=" col-md-2">
                    <?php echo plang('gender'); ?>
                </label>
                <div class=" col-md-10">
                    <?php if (logged_can_edit_admin(array("view_member","edit_member"), get_property($member, "id"))) {
                        echo form_radio(
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
                    <?php } else {
                        echo form_label(plang(get_property($member, "gender", "-")));
                    } ?>
                </div>
            </div>
        </div>
        <?php if (logged_can_edit_admin(array("view_member","edit_member"), get_property($member, "id"))) { ?>
            <div class="panel-footer">
                <button type="submit" class="btn btn-primary">
                    <span class="fa fa-check-circle"></span>
                    <?php echo plang('save'); ?>
                </button>
            </div>
        <?php } ?>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#general-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        setDatePicker("#dob");
    });
</script>