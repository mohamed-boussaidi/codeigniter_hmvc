<?php echo form_open(
    get_uri("notification_settings/save"),
    array(
        "id" => "notification-settings-form",
        "class" => "general-form",
        "role" => "form"
    )
); ?>
<div class="modal-body clearfix">
    <?php echo form_hidden("id", get_property(
            $notification_setting,
            "id"
    )) ?>
    <div class="form-group">
        <label for="title" class=" col-md-2">
            <strong><?php echo plang('event'); ?></strong>
        </label>
        <div class=" col-md-10">
            <strong>
                <?php
                echo plang(get_property($notification_setting, "event"));
                ?>
            </strong>
        </div>
    </div>
    <div class="form-group">
        <label for="enable_email" class="col-md-2">
            <?php echo plang('enable_email'); ?>
        </label>
        <div class="col-md-10">
            <?php
            echo form_checkbox(
                "enable_email",
                "1",
                get_property(
                        $notification_setting,
                        "enable_email"
                ) == 1 ? true : false,
                "id='enable_email'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="enable_web" class="col-md-2">
            <?php echo plang('enable_web'); ?>
        </label>
        <div class="col-md-10">
            <?php
            echo form_checkbox(
                "enable_web",
                "1",
                get_property($notification_setting, "enable_web") == 1 ? true : false,
                "id='enable_web'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="members" class="col-md-2">
            <?php echo plang('members'); ?>
        </label>
        <div class="col-md-10">
            <?php
            echo form_input(array(
                "id" => "members",
                "name" => "members",
                "value" => get_property($notification_setting, "notify_to_members"),
                "class" => "form-control toggle_specific",
                "placeholder" => plang('members')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="roles" class="col-md-2">
            <?php echo plang('roles'); ?>
        </label>
        <div class="col-md-10">
            <?php
            echo form_input(array(
                "id" => "roles",
                "name" => "roles",
                "value" => get_property($notification_setting, "notify_to_roles"),
                "class" => "form-control",
                "placeholder" => plang('roles')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="types" class="col-md-2">
            <?php echo plang('types'); ?>
        </label>
        <div class="col-md-10">
            <?php
            echo form_input(array(
                "id" => "types",
                "name" => "types",
                "value" => get_property($notification_setting, "notify_to_types"),
                "class" => "form-control",
                "placeholder" => plang('types')
            ));
            ?>
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
    $(document).ready(function () {
        $("#notification-settings-form").appForm({
            onSuccess: function (result) {
                $("#notification-settings-table").appTable(
                    {newData: result.data, dataId: result.id}
                );
            }
        });
        $("#members").select2({
            multiple: true,
            formatResult: memberSelect2Format,
            formatSelection: memberSelect2Format,
            data: <?php echo($members); ?>
        });
        $("#roles").select2({
            data: <?php echo $roles ?>,
            formatResult: roleSelect2Format,
            formatSelection: roleSelect2Format,
            multiple: true
        });
        $("#types").select2({
            data: <?php echo $types ?>,
            formatResult: typeSelect2Format,
            formatSelection: typeSelect2Format,
            multiple: true
        });
    });
</script>    