<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

/**
 * Class Roles
 * this class will allow users to control the
 * roles system and the permissible methods
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Roles extends Init implements Mind_controller
{

	/**
	 * Roles constructor.
	 * only admin have access to this class
	 */
    public function __construct()
    {
        parent::__construct();
        //only admin have permissions to access this class
        $this->only_admin();
    }

    /**
     * load the main page
     */
    public function index()
    {
        //load the view
        view("roles/index");
    }

    /**
     * get the roles list
     */
    public function list_data()
    {
        //load the roles
        $data = $this->Roles_model->get_all();
        $result = array();
        foreach ($data as $datum) {
            //format the row
            $result[] = $this->make_row($datum);
        }
        echo json_encode(array("data" => $result));
    }

    /**
     * build edit and/or add role html
     */
    public function modal_form()
    {
        //validate that the id is a number
        validate_submitted_data(
            array("id" => "numeric")
        );
        //get the role or an empty object
        $data['role'] = $this->Roles_model->get_one($this->input->post('id'), TRUE);
        //load the modal
        $this->load->view('roles/modal_form', $data);

    }

    /**
     * edit or save a role
     */
    public function save()
    {
        //get the posted id if this is an edit request
        $id = $this->input->post('id');
        //get the posted title if this is an edit request
        $title = $this->input->post('title');
        //check if the title is an unique value
        $is_unique = unique_update("roles", "Roles_model", $id, "title", $title);
        //validate the submitted data
        validate_submitted_data(
            array(
                "id" => "numeric",
                "title" => "required$is_unique"
            )
        );
        $data = array(
            "title" => $this->input->post("title")
        );
        //execute the query
        saving_result($this, $this->Roles_model->save($data, $this->input->post("id")));
    }

    /**
     * delete some role from the database
     * @return bool
     */
    public function delete()
    {
        //validate that an id is posted
        validate_submitted_data(array("id" => "required"));
        $id = $this->input->post("id");
        //if the role is member
        if ($id == 1) {
            echo json_encode(array("success" => false, 'message' => plang('error_occurred')));
            return false;
        }
        //set related members role to 1
        $this->Members_model->set_role_to_member($id);
        //delete the posted id
        deleting_result($this, $this->Roles_model->delete_with_elements($id, "role_id", array("role_permissions")));
    }

    /**
     * load the permissions tab of the selected role
     */
    public function permissions()
    {
        //validate that an id is posted
        validate_submitted_data(array("id" => "required|numeric"));
        //get the role
        $data["role"] = $this->Roles_model->get_one($this->input->post("id"), TRUE);
        $data["permissions_list"] = build("permissions_row", array(), array($data["role"]));
        $data["linked_checkbox_list"] = build("linked_checkbox_list");
        //load the tab content
        $this->load->view("roles/permissions", $data);
    }

    /**
     * save role permissions
     * @return bool
     */
    public function save_permissions()
    {
        //validate that tje id submitted correctly
        validate_submitted_data(array("id" => "required|numeric"));
        //get the posted permissions
        $permissions = $this->input->post("permissions");
        //get the role id
        $id = $this->input->post("id");
        //check if the permissions id valid array
        $permissions = is_array($permissions) ? $permissions : array();
        $this->Role_permissions_model->delete(array("role_id" => $id));
        foreach ($permissions as $permission) {
            if (is_numeric($permission)) {
                if ($this->Permissions_model->get_one($permission)) {
                    $data = array("role_id" => $id, "permission_id" => $permission);
                    $this->Role_permissions_model->save($data);
                    continue;
                }
            }
            jerror();
            return false;
        }
        jsuccess();
        return true;
    }

    /**
     * build datatables row
     * @param string $data
     * @param bool $id
     * @return array
     */
    public function make_row($data = stdClass::class, $id = false)
    {
        if ($id) {
            $data = $this->Roles_model->get_one($id, TRUE);
        }
        $title = "<span data-id='" .
            get_property($data, "id") .
            "' class='role-row'>" .
            get_property($data, "title") .
            "</span>";
        $edit = modal_anchor(
            get_uri("roles/modal_form"),
            "<i class='fa fa-pencil'></i>",
            array(
                "class" => "edit",
                "title" => plang('edit_element', array("role")),
                "data-post-id" => get_property($data, "id")
            )
        );
        $delete = "";
        $role_can_be_deleted = build('role_can_be_deleted');
        $can_be_deleted = true;
        if (count($role_can_be_deleted)) {
            foreach ($role_can_be_deleted as $item) {
                if ($item === false) {
                    $can_be_deleted = false;
                }
            }
        }
        if ($can_be_deleted) {
            //if the role is not member show delete button
            if (get_property($data, "id") != "1") {
                $delete = js_anchor(
                    "<i class='fa fa-times fa-fw'></i>",
                    array(
                        'title' => plang('delete_element', array("role")),
                        "class" => "delete", "data-id" => get_property($data, "id"),
                        "data-action-url" => get_uri("roles/delete"),
                        "data-action" => "delete"
                    )
                );
            }
        }
        return array(dt_row_trigger(), $title, $edit . $delete);
    }

    public function view()
    {
        show_404();
    }
}
