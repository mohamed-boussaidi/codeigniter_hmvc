<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Notification_settings_model extends Crud_model
{

    private $table = null;

    function __construct()
    {
        $this->table = parent::__construct('notification_settings');
    }

    /**
     * get all the types
     * @param bool $event
     * @return array
     */
    function notify_to_types($event = false)
    {
        $types = array(
          "new_member" => array(
              "members",
              "admins"
          )
        );
        $types = array_merge($types, build("notify_to_types"));
        $final_types = array();
        $notification_settings = $this->get_all(false);
        foreach ($notification_settings as $notification_setting) {
            $event = get_property($notification_setting, "event");
            foreach ($types as $type_event => $type) {
                if($type_event == $event) {
                    $type = is_array($type) ? $type : array();
                    $final_types[$type_event] = $type;
                    unset($types[$type_event]);
                }
            }
        }
        return $event ? isset($final_types[$event]) ? $final_types[$event] : array() : $final_types;
    }

    /**
     * get all the notification settings
     * @param bool $id
     * @return mixed
     */
    function get_details($id = false)
    {
        $notification_settings_table = $this->db->dbprefix('notification_settings');
        $members_table = $this->db->dbprefix('members');
        $roles_table = $this->db->dbprefix('roles');
        $sql = join(" ", array(
            "SELECT $notification_settings_table.*,",
            "(SELECT GROUP_CONCAT(' ',$members_table.first_name,' ',$members_table.last_name) FROM $members_table " .
            "WHERE FIND_IN_SET($members_table.id, $notification_settings_table.notify_to_members)) as members,",
            "(SELECT GROUP_CONCAT(' ',$roles_table.title) FROM $roles_table " .
            "WHERE FIND_IN_SET($roles_table.id, $notification_settings_table.notify_to_roles)) as roles",
            "FROM $notification_settings_table",
            $id ? "WHERE $notification_settings_table.id=$id" : ""
        ));
        $result = $this->db->query($sql);
        if($id) {
            return $result->num_rows() ? $result->row() : false;
        }
        return $result->num_rows() ? $result->result() : array();
    }

}
