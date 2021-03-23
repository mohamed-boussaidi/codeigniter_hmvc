<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

if (!function_exists("create_notification")) {
    /**
     * create web notification
     * @param $event
     * @param string $related
     * @param array $ignore
     * @param bool $logged_is_creator
     * @return bool
     */
    function create_notification($event, $related = "", $ignore = array(), $logged_is_creator = true)
    {
        $ci = get_instance(true);
        if (!$event) {
            show_404();
        }
        $event = $ci->Notification_settings_model->get_one_where(
            array("event" => $event), true, false
        );
        if (!$event) {
            show_404();
        }
        $notify_to_members = separated_to_array(get_property($event, "notify_to_members"));
        $notify_to_roles = separated_to_array(get_property($event, "notify_to_roles"));
        foreach ($notify_to_roles as $notify_to_role) {
            $notify_to_members = array_merge($notify_to_members, get_role_members($notify_to_role));
        }
        $notify_to_types = separated_to_array(get_property($event, "notify_to_types"));
        foreach ($notify_to_types as $notify_to_type) {
            switch ($notify_to_type) {
                case 'members' : {
                    $notify_to_members = array_merge($notify_to_members, get_all_members());
                    break;
                }
                case 'admins' : {
                    $notify_to_members = array_merge($notify_to_members, get_role_members());
                    break;
                }
                default : {
                    break;
                }
            }
        }
        $notify_to = array();
        foreach ($notify_to_members as $notify_to_member) {
            if (is_numeric($notify_to_member)) {
                $notify_to[] = $notify_to_member;
            }
        }
        $notify_to = array_merge(build("notify_to", array(), array($event)), $notify_to);
        $notify_to = array_unique($notify_to);
        $notify_to = array_diff($notify_to, $ignore);
        sort($notify_to);
        $data = array();
        $data["created_at"] = get_current_time();
        $creator = false;
        if($logged_is_creator) {
            $creator = get_logged_member();
            $data["created_by"] = get_property($creator, "id");
            $notify_to = array_diff($notify_to, array(get_property($creator, "id")));
        }
        $data["related"] = $related;
        $data["notify_to"] = is_array($notify_to) && count($notify_to) ?
            implode(",", $notify_to) : "";
        $data["event"] = get_property($event, "event");
        if(get_property($event, "enable_web") === "1") {
            $ci->Notifications_model->save($data);
        }
        if(get_property($event, "enable_email") === "1") {
            send_email_notification($notify_to, $data, $creator);
        }
    }
}

if(!function_exists("send_email_notification")) {
    /**
     * Send notification as email to a list of ids
     * @param array $ids
     * @param $data
     * @param bool $creator
     */
    function send_email_notification($ids = array(), $data, $creator = false) {
        $event = get_array_value($data, "event");
        $ci = get_instance(true);
        $email_template = $ci->Email_templates_model->get_final_template(
            "general_notification"
        );
        $parser_data["SIGNATURE"] = get_property($email_template, "signature");
        $parser_data["EVENT_TITLE"] = plang("notification_" . $event);
        $parser_data["NOTIFICATION_URL"] = join("", build(
            "notification_".$event."_url",
            array(), array(array_to_object($data))));
        $parser_data["EVENT_DETAILS"] = join(" ", build(
            "notification_".$event."_email_content",
            array(), array($data)));
        $parser_data["APP_TITLE"] = get_setting("site_title");
        $message = $ci->parser->parse_string($email_template->message, $parser_data, TRUE);
        $subject = plang($event)." - ".get_setting("site_title");
        $options = array();
        if($creator) {
            $options["from_address"] = get_property($creator, "email");
            $options["from_name"] = get_property($creator, "first_name")." ".
                get_property($creator, "last_name");
        }
        send_app_mail_to_members($ids, $subject, $message, $options);
    }
}

if(!function_exists("create_websocket_onEvent")) {
    function create_websocket_onEvent($event = "", $code = "") {
        return 'if(event === "'.$event.'") {'
        . '$(document).ready(function () {'
        . $code
        . '});'
        . '}';
    }
}

if(!function_exists("create_websocket_onEvent_notification")) {
	/**
	 * create websocket notification
	 * @param string $name
	 * @param string $countUrl
	 * @param string $icon
	 *
	 * @return string
	 */
    function create_websocket_onEvent_notification($name = "notification",
                                                   $countUrl = "countNotificationUrl",
                                                   $icon = "bell-o")
    {
        $code = 'var notificationOptions = {},'
            . 'notificationIcon = $("#'.$name.'-icon");'
            . 'notificationOptions.notificationUrl = AppHelper.settings.'.$countUrl.';'
            . 'notificationOptions.icon = "fa-'.$icon.'";'
            . 'notificationOptions.notificationSelector = notificationIcon;'
            . 'checkNotifications(notificationOptions, false, false);';
        return create_websocket_onEvent($name, $code);
    }
}

if(!function_exists("create_websocket_onClose")) {
	/**
	 * create websocket notification
	 * @param string $name
	 * @param string $countUrl
	 * @param string $icon
	 *
	 * @return string
	 */
    function create_websocket_onClose($name = "notification",
                                      $countUrl = "countNotificationUrl",
                                      $icon = "bell-o") {
        return "var notificationOptions = {},
                notificationIcon = $(\"#$name-icon\");
            notificationOptions.notificationUrl = AppHelper.settings.$countUrl;
            notificationOptions.checkNotificationAfterEvery = \"\";
            notificationOptions.icon = \"fa-$icon\";
            notificationOptions.notificationSelector = notificationIcon;
            checkNotifications(notificationOptions, false, true);";
    }
}

if(!function_exists("create_notification_row")) {
	/**
	 * add notification to the list
	 * @param string $name
	 * @param string $countUrl
	 * @param string $notifications_url
	 * @param string $see_all_url
	 * @param string $icon
	 *
	 * @return string
	 */
    function create_notification_row($name = "notification",
                                     $countUrl = "countNotificationUrl",
                                     $notifications_url = "notificationUrl",
                                     $see_all_url = "notifications", $icon = "bell-o")
    {
        return join("\n", array(
            "<li>", js_anchor(
                "<i class='fa fa-$icon'></i>",
                array(
                    "id" => "$name-icon",
                    "class" => "dropdown-toggle",
                    "data-toggle" => "dropdown"
                )
            ), '<div class="dropdown-menu aside-xl m0 p0 font-100p" style="width: 400px;" >',
            '<div class="dropdown-details panel bg-white m0">',
            '<div class="list-group">',
            '<span class="list-group-item inline-loader p10"></span>',
            '</div>',
            '</div>',
            '<div class="panel-footer text-sm text-center">',
            anchor($see_all_url, lang('see_all')),
            '</div></div></li>'
        ))."<script type=\"text/javascript\">
            $(document).ready(function () {
                var notificationOptions = {},
                    notificationIcon = $(\"#$name-icon\");
                notificationOptions.notificationUrl = AppHelper.settings.$countUrl;
                notificationOptions.checkNotificationAfterEvery = \"\";
                notificationOptions.icon = \"fa-$icon\";
                notificationOptions.notificationSelector = notificationIcon;
                checkNotifications(notificationOptions, false, false);
                notificationIcon.click(function () {
                    notificationOptions.notificationUrl = AppHelper.settings.$notifications_url;
                    checkNotifications(notificationOptions, true, false);
                });
            });
        </script>";
    }
}