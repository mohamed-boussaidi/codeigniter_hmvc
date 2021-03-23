<div id="page-content" class="p20 row">
    <div class="col-sm-3 col-lg-2">
        <?php $this->load->view("settings/tabs", array("tab" => "notification_settings")) ?>
    </div>
    <div class="col-sm-9 col-lg-10">
        <div class="row">
            <div class="col-md-12">
                <div id="role-list-box" class="panel panel-default">
                    <div class="page-title clearfix">
                        <h1> <?php echo plang('element_settings', array("notifications")); ?></h1>
                    </div>
                    <div class="table-responsive">
                        <table id="notification-settings-table" class="display" cellspacing="0" width="100%">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#notification-settings-table").appTable({
            source: '<?php echo get_uri("notification_settings/list_data") ?>',
            columns: [
                {title: "<?php echo lang('event'); ?>", "class": "w30p all"},
                {title: "<?php echo lang('notify_to'); ?>"},
                {title: "<?php echo lang('enable_email'); ?>", "class": "w10p text-center"},
                {title: "<?php echo lang('enable_web'); ?>", "class": "w10p text-center"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center all option w50"}
            ],
            order: [[1, "asc"]],
            displayLength: -1
        });
    });
</script>
