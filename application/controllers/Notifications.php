<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

/**
 * provide notification methods
 * such read more and count notifications
 * this class is the main notifications manager
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Notifications extends Init implements Mind_controller
{

	/**
	 * Notifications constructor.
	 * all members have access to this class
	 */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * get some notifications
     */
    function index()
    {
        $notifications_list = $this->_prepare_notification_list();
         view(
            "notifications/index",
            array(
                "notifications_list" => $this->list_data($notifications_list),
                "result_remaining" => $notifications_list["result_remaining"],
                "next_page_offset" => $notifications_list["next_page_offset"],
            )
        );
    }

    /**
     * load more notifications
     * @param int $offset
     */
    function load_more($offset = 0)
    {
        $notifications_list = $this->_prepare_notification_list($offset);
        $this->load->view(
            "notifications/list_data",
            array(
                "notifications_list" => $this->list_data($notifications_list),
                "result_remaining" => $notifications_list["result_remaining"],
                "next_page_offset" => $notifications_list["next_page_offset"],
            )
        );    
    }

    /**
     * get all unchecked notifications count
     */
    function count_notifications()
    {
        $notifications = $this->Notifications_model->count_notifications(
            get_property($this->logged_member, "id"),
            get_property($this->logged_member, "notification_checked_at")
        );
        echo json_encode(array("success" => true, 'total_notifications' => $notifications));
    }

    /**
     * get notifications list
     */
    function get_notifications()
    {
        $this->update_notification_checking_status();
        $data = array(
            "notifications_list" => $this->list_data($this->_prepare_notification_list()),
            "result_remaining" => false,
        );
        echo json_encode(array(
            "success" => true,
            'notification_list' => $this->load->view(
                "notifications/list", $data, true
            )
        ));
    }

    /**
     * set the notification last check time
     */
    function update_notification_checking_status()
    {
        $now = get_current_time();
        $data = array("notification_checked_at" => $now);
        $this->Members_model->save($data, get_property($this->logged_member, "id"));
    }

    //make a notification read
    function set_notification_status_as_read($notification_id = 0)
    {
        if ($notification_id) {
            $this->Notifications_model->set_notification_status_as_read(
                $notification_id,
                get_property($this->logged_member, "id")
            );
        }
    }

    /**
     * format db result to build the notifications list
     * @param int $offset
     * @return mixed
     */
    private function _prepare_notification_list($offset = 0)
    {
        $notifications = $this->Notifications_model->get_notifications(
            get_property($this->logged_member, "id"), $offset
        );
        $data['notifications'] = $notifications->result;
        $data['found_rows'] = $notifications->found_rows;
        $next_page_offset = $offset + 20;
        $data['next_page_offset'] = $next_page_offset;
        $data['result_remaining'] = $notifications->found_rows > $next_page_offset;
        return $data;
    }

    /**
     * get all the notifications
     * @param array $notifications
     * @return string
     */
    public function list_data($notifications = array())
    {
        $notifications_list = "";
        foreach ($notifications["notifications"] as $notification) {
            $notifications_list .= $this->make_row($notification);
        }
        return $notifications_list;
    }

    /**
     * build datatables row
     * @param string $data
     * @param bool $id
     * @return mixed
     */
    public function make_row($data = stdClass::class, $id = false)
    {
        $member = $this->Members_model->get_one(
            get_property($data, "created_by"), true, false
        );
        $notification_content = join("", build(
            "notification_".get_property($data, "event")."_content",
            array(), array($data)));
        $is_read_by = separated_to_array(get_property($data, "read_by"));
        $url = join("", build(
            "notification_".
            get_property($data, "event").
            "_url", array(), array($data)
        ));
        $data = array(
            "url" => empty($url) ? base_url() : $url,
            "url_attributes" => "",
            "is_read" => in_array(get_property($this->logged_member, "id"), $is_read_by),
            "member" => $member,
            "notification" => $data,
            "notification_content" => $notification_content
        );
        return $this->load->view("notifications/row", $data, true);
    }

    public function view()
    {
        show_404();
    }

    public function modal_form()
    {
        show_404();
    }

    public function save()
    {
        show_404();
    }

    public function delete()
    {
        show_404();
    }
}
