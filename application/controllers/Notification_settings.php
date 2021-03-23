<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

/**
 * Class Notification_settings
 * manage notification settings
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Notification_settings extends Init implements Mind_controller
{
	/**
	 * Notification_settings constructor.
	 * only admin have access to this class
	 */
    public function __construct()
    {
        parent::__construct();
        //only admins could access this controller
        $this->only_admin();
    }

    /**
     * get the main view content
     */
    public function index()
    {
        //load the view
        view("notification_settings/index");
    }

    public function view()
    {
        show_404();
    }

    /**
     * build edit a notification popup html
     */
    public function modal_form()
    {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $id = $this->input->post("id");
        $notification_setting = $this->Notification_settings_model->get_details($id);
        //get all system members
        $data["members"] = select2_dropdown(
            $this->Members_model->get_all_where(array("status" => "active", "disable_login" => "0")),
            array("id"), array("first_name", "last_name")
        );
        //get all system roles
        $data["roles"] = select2_dropdown($this->Roles_model->get_all(false), array("id"), array("title"));
        //get all targets types
        $types = $this->Notification_settings_model->notify_to_types(
            get_property($notification_setting, "event")
        );
        //get all system defined types
        $data["types"] = array();
        foreach ($types as $type) {
            $data["types"][] = array("id" => $type, "text" => plang($type));
        }
        $data["types"] = json_encode($data["types"]);
        $data["notification_setting"] = $notification_setting;
        //load the view
        $this->load->view("notification_settings/modal_form", $data);
    }

    //edit notification settings
    public function save()
    {
        validate_submitted_data(array(
            "id" => "required|numeric",
            "enable_email" => "numeric",
            "enable_web" => "numeric",
        ));
        $data = array(
          "enable_email" => $this->input->post("enable_email"),
          "enable_web" => $this->input->post("enable_web"),
          "notify_to_members" => $this->input->post("members"),
          "notify_to_roles" => $this->input->post("roles"),
          "notify_to_types" => $this->input->post("types"),
        );
        return saving_result($this, $this->Notification_settings_model->save(
            $data, $this->input->post("id")
        ));
    }

    public function delete()
    {
        show_404();
    }

    /**
     * get all notification settings list
     */
    public function list_data()
    {
        //get all notification settings object
        $data = $this->Notification_settings_model->get_details();
        $result = array();
        foreach ($data as $datum) {
            $result[] = $this->make_row($datum);
        }
        //build datatables
        echo json_encode(array("data" => $result));
    }

    /**
     * build datatables ros
     * @param string $data
     * @param bool $id
     * @return array
     */
    public function make_row($data = stdClass::class, $id = false)
    {
        //if there is an id get object of the specific id
        $data = $id ? $this->Notification_settings_model->get_details($id) : $data;
        //build icons
        $yes = "<i class='fa fa-check-circle'></i>";
        $no = "<i class='fa fa-check-circle' style='opacity:0.2'></i>";
        //notify to container
        $notify_to = "";
        //get a specific settings row notify to types
        $all_types = get_property($data, "notify_to_types");
        if (!empty($all_types) && contains($all_types, ",")) {
            $types = explode(",", $all_types);
            foreach ($types as $type) {
                $notify_to .= "<li>" . plang($type) . "</li>";
            }
        } else if(!empty($all_types)) {
            $notify_to .= "<li>" . plang($all_types) . "</li>";
        }
        //get a specific settings row notify to members
        if (!empty(get_property($data, "members"))) {
            $notify_to .= "<li>" . plang("members") . ": " . get_property($data, "members") . "</li>";
        }
        //get a specific settings row notify to roles
        if (!empty(get_property($data, "roles"))) {
            $notify_to .= "<li>" . plang("roles") . ": " . get_property($data, "roles") . "</li>";
        }
        //parse notify_to container
        if (!empty($notify_to)) {
            $notify_to = "<ul class='pl15'>" . $notify_to . "</ul>";
        }
        return array(
            dt_row_trigger(),
            plang(get_property($data, "event")),
            $notify_to,
            !empty(get_property($data, "enable_email")) ? $yes : $no,
            !empty(get_property($data, "enable_web")) ? $yes : $no,
            modal_anchor(
                get_uri("notification_settings/modal_form"),
                "<i class='fa fa-pencil'></i>",
                array(
                    "class" => "edit", "title" => plang('notification_settings'),
                    "data-post-id" => get_property($data, "id")
                )
            )
        );
    }
}
