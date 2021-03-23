<?php

$settings = json_encode(build("js_settings"));
$languages = json_encode(build("js_language"));

?>

    <script type="text/javascript">

        function extend(a, b) {
            for (var key in b)
                if (b.hasOwnProperty(key))
                    a[key] = b[key];
            return a;
        }

        AppHelper = {};
        AppHelper.baseUrl = "<?php echo base_url() ?>";
        AppHelper.assetsDirectory = "<?php echo base_url() ?>assets/";
        AppHelper.onEvent = <?php echo json_encode(build("websocket_onEvent")) ?>;
        AppHelper.onClose = <?php echo json_encode(build("websocket_onClose")) ?>;
        AppHelper.webSocket = {
            "ip": "<?php echo get_setting("websocket_ip") ?>",
            "port": "<?php echo get_setting("websocket_port") ?>"
        };
        AppHelper.settings = {
            "dateFormat": "<?php echo get_setting("date_format") ?>",
            "displayLength": <?php echo get_setting("rows_per_page") ?>,
            "firstDayOfWeek": "<?php echo get_setting("first_day_of_week") ?>",
            "decimalSeparator": ",",
            "thousandsSeparator": ".",
            "currencySymbol": "DT",
            "locale": "<?php echo plang(get_setting("language")) ?>",
            "language": "<?php echo plang(get_setting("language")) ?>",
            "countNotificationUrl": "<?php echo get_uri('notifications/count_notifications'); ?>",
            "notificationUrl": "<?php echo get_uri('notifications/get_notifications'); ?>",
            "profileImageSize": '<?php echo get_setting("profile_image_max_size") ?>'
        };

        AppHelper.settings = extend(AppHelper.settings, <?php echo $settings ?>);

        AppHelper.language = {
            "enabled": "<?php echo plang('enabled') ?>",
            "disabled": "<?php echo plang('disabled') ?>",
            "delete_box_title": "<?php echo plang('delete_box_title') ?>",
            "delete_box_text": "<?php echo plang('delete_box_text') ?>",
            "select2Required": "<?php echo plang('select2_required') ?>",
            "alphanumericExtendedRequired": "<?php echo plang('alphanumeric_extended_required') ?>",
            "delete_box_timer": "<?php echo plang('delete_box_timer') ?>",
            "delete_box_confirm": "<?php echo plang('delete_box_confirm') ?>",
            "validDate": "<?php echo plang('valid_date') ?>",
            "validIP": "<?php echo plang('valid_IP') ?>",
            "delete_box_cancel": "<?php echo plang('delete_box_cancel') ?>",
            "delete_box_canceled": "<?php echo plang('delete_box_canceled') ?>",
            "printButtonToolTip": "<?php echo plang('printButtonToolTip') ?>",
            "lengthMenu": "<?php echo plang('lengthMenu') ?>",
            "zeroRecords": "<?php echo plang('zeroRecords') ?>",
            "search": "<?php echo plang('search') ?>",
            "searchPlaceholder": "<?php echo plang('searchPlaceholder') ?>",
            "today": "<?php echo plang('today') ?>",
            "yesterday": "<?php echo plang('yesterday') ?>",
            "tomorrow": "<?php echo plang('tomorrow') ?>",
            "sSortAscending": "<?php echo plang('sSortAscending') ?>",
            "sSortDescending": "<?php echo plang('sSortDescending') ?>",
            "sFirst": "<?php echo plang('sFirst') ?>",
            "sLast": "<?php echo plang('sLast') ?>",
            "sNext": "<?php echo plang('sNext') ?>",
            "sPrevious": "<?php echo plang('sPrevious') ?>",
            "sEmptyTable": "<?php echo plang('sEmptyTable') ?>",
            "sInfo": "<?php echo plang('sInfo') ?>",
            "sInfoEmpty": "<?php echo plang('sInfoEmpty') ?>",
            "sInfoFiltered": "<?php echo plang('sInfoFiltered') ?>",
            "sInfoPostFix": "<?php echo plang('sInfoPostFix') ?>",
            "sLoadingRecords": "<?php echo plang('sLoadingRecords') ?>",
            "sProcessing": "<?php echo plang('sProcessing') ?>",
            "exportButtonPdf": "<?php echo plang('exportButtonPdf') ?>",
            "exportButtonExcel": "<?php echo plang('exportButtonExcel') ?>",
            "exportButtonPrint": "<?php echo plang('exportButtonPrint') ?>",
            "all": "<?php echo plang('all') ?>",
            "exportationTitle": "<?php echo get_setting("site_title") ?>",
        };

        AppHelper.language = extend(AppHelper.language, <?php echo $settings ?>);
    </script>

<?php

foreach (build("php_helper") as $php_helper) {
    $this->load->view($php_helper);
}

?>