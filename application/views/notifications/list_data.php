<div>
    <?php
        echo !empty($notifications_list) ? $notifications_list :
            '<span class="list-group-item">'.plang("no_new_notifications").'</span>'
    ?>
</div>
<?php if ($result_remaining) { $next_container_id = "load" . $next_page_offset; ?>
    <div id="<?php echo $next_container_id; ?>">
    </div>
    <div id="loader-<?php echo $next_container_id; ?>">
        <div class="text-center p20 clearfix mt-5">
            <?php echo ajax_anchor(
                get_uri("notifications/load_more/" . $next_page_offset),
                plang("load_more"),
                array(
                    "class" => "btn btn-default load-more mt15 p10",
                    "data-remove-on-success" => "#loader-" . $next_container_id,
                    "title" => plang("load_more"),
                    "data-inline-loader" => "1",
                    "data-real-target" => "#" . $next_container_id
                )
            ); ?>
        </div>
    </div>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function () {
        $(".unread-notification").click(function (e) {
            $.ajax({
                url: '<?php echo
                get_uri("notifications/set_notification_status_as_read") ?>/' +
                $(this).attr("data-notification-id")
            });
            $(this).removeClass("unread-notification");
        });
    });
</script>
