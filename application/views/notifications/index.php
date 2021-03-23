<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo plang('notifications'); ?></h1>
        </div>
        <?php $this->load->view("notifications/list_data", array(
            "notifications_list" => $notifications_list,
            "result_remaining" => $result_remaining,
            "next_page_offset" => $next_page_offset
        )) ?>
    </div >
</div>

<script type="text/javascript">
    $(document).ready(function () {
        if ($.fn.slimscroll) {
            //don't apply slimscroll for mobile devices
            if ($(window).width() > 640) {
                if ($('#notificaion-popup-list').height() >= 400) {
                    $('#notificaion-popup-list').slimscroll({
                        borderRadius: "0",
                        height: "400px"
                    });
                } else {
                    $('#notificaion-popup-list').css({"overflow-y": "auto"});
                }

            }
        }
    });
</script>
