<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Notifications_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = parent::__construct('notifications');
    }

    function count_notifications($user_id, $checked_at = false)
    {
        $sql = join(" ", array(
            "SELECT COUNT($this->table.id) AS total_notifications",
            "FROM $this->table WHERE",
            "FIND_IN_SET($user_id, $this->table.notify_to) != 0",
            "AND FIND_IN_SET($user_id, $this->table.read_by) = 0",
            $checked_at ? "AND timestamp($this->table.created_at) > timestamp('$checked_at')" : ""
        ));
        $result = $this->db->query($sql);
        if ($result->num_rows()) {
            return $result->row()->total_notifications;
        }
        return 0;
    }

    function set_notification_status_as_read($notification_id, $user_id = 0)
    {
        $notification = $this->get_one($notification_id);
        $read_by = separated_to_array(get_property($notification, "read_by"));
        $read_by[] = $user_id;
        $data["read_by"] = implode(",", $read_by);
        $this->save($data, $notification_id);
    }

    function count_unread_notifications($user_id = 0)
    {
        $sql = join(" ", array(
            "SELECT COUNT($this->table.id) as total",
            "FROM $this->table WHERE",
            "FIND_IN_SET($user_id, $this->table.notify_to) != 0",
            "AND FIND_IN_SET($user_id, $this->table.read_by) = 0"
        ));
        return $this->db->query($sql)->row()->total;
    }

    function get_notifications($user_id, $offset = 0, $limit = 20) {
        $sql = join(" ", array(
           "SELECT $this->table.* FROM $this->table WHERE",
            "FIND_IN_SET($user_id, $this->table.notify_to) != 0",
            "ORDER BY $this->table.id DESC LIMIT $offset, $limit"
        ));
        $data = new stdClass();
        $data->result = $this->db->query($sql)->result();
        $data->found_rows = $this->count_notifications(get_logged_member_id());
        return $data;
    }
}
