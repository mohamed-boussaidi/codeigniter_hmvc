<?php
$notification_class = "";
if (!$is_read) {
    $notification_class = "unread-notification";
}
if (!$url || $url == "#") {
    $notification_class .= " not-clickable";
}
?>

<a class="list-group-item <?php echo $notification_class; ?>" data-notification-id="
<?php echo get_property($notification, "id"); ?>"
    <?php echo $url_attributes; ?> href="<?php echo $url ?>" >
    <div class="media-left">
        <?php if (!empty(get_property($member, "id"))) { ?>
            <span class="avatar avatar-xs">
            <img src="<?php echo get_avatar(get_property($member, "image")) ?>" alt="..."/>
        </span>
        <?php } ?>
    </div>
    <div class="media-body w100p">
        <div class="media-heading">
            <strong>
                <?php echo get_property($member, "first_name") ?>
                <?php echo get_property($member, "last_name") ?>
            </strong>
            <span class="text-off pull-right">
                <small>
                    <?php echo from_sql_date(
                        get_property($notification, "created_at"), true
                    ) ?>
                </small>
            </span>
        </div>
        <div class="media m0">
            <?php echo plang("notification_" . get_property($notification, "event")) ?>
            <?php echo $notification_content ?>
        </div>
    </div>
</a>
