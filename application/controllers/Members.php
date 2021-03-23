<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

/**
 * member actions like loading views and executing queries.
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Members extends Init implements Mind_controller
{

	/**
	 * Members constructor.
	 * Only team members have access to this class
	 */
    public function __construct()
    {
        parent::__construct();
        build('members_construct');
        //if the user is not a member redirect him
        //to the forbidden page
        $this->only_member();
    }

    /**
     * main view
     */
    public function index()
    {
        //check if the user have the permissions to view the members list
        $this->only_logged_have_permission("view_members");
        view("members/index");
    }

    /**
     * get all members
     */
    public function list_data()
    {
        //check if the user have the permissions to view the members list
        $this->only_logged_have_permission("view_members");
        //filter
        $options = array("status" => $this->input->post("status"));
        //load members
        $data = $this->Members_model->get_all_where($options);
        $result = array();
        foreach ($data as $datum) {
            //format the row
            $result[] = $this->make_row($datum);
        }
        echo json_encode(array("data" => $result));
        return true;
    }

    /**
     * build pop up html
     */
    public function modal_form()
    {
        //check if the user have the permission to add a new member
        $this->only_logged_have_permission("add_member");
        //validate the submitted data to fit the java script rules
        validate_submitted_data(array("id" => "numeric"));
        //get the member
        $data["member"] = $this->Members_model->get_one($this->input->post('id'), TRUE);
        //fetch all roles
        $roles = $this->Roles_model->get_dropdown_list(array("title"));
        if (logged_is_admin()) {
            $roles = array("0" => plang("admin")) + $roles;
        }
        $data['roles'] = array();
        $add_member_ignored_ids = build('add_member_ignored_ids');
        foreach ($roles as $role_id => $role) {
            if (!in_array($role_id, $add_member_ignored_ids)) {
                $data['roles'][] = $role;
            }
        }
        //load the view
        $this->load->view('members/modal_form', $data);
    }

    /**
     * create or edit a member
     */
    public function save()
    {
        //check if the user have the permission to add a new user
        $this->only_logged_have_permission("add_member");
        $members_table = $this->db->dbprefix("members");
        //validate the submitted data to fit the java script rules
        validate_submitted_data(array(
            "email" => "required|valid_email|is_unique[$members_table.email]",
            "password" => "required|min_length[6]",
            "first_name" => "required",
            "last_name" => "required",
            "address" => "required",
            "role" => "required",
            "phone" => "required",
            "dob" => "required",
            "gender" => "required",
        ));
        //get the posted data
        $data = array(
            "email" => $this->input->post('email'),
            "password" => blowfish_encrypt($this->input->post('password')),
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
            "address" => $this->input->post('address'),
            "alternative_address" => $this->input->post('alternative_address'),
            "phone" => $this->input->post('phone'),
            "alternative_phone" => $this->input->post('alternative_phone'),
            "skype" => $this->input->post('skype'),
            "gender" => $this->input->post('gender'),
            "dob" => to_sql_date($this->input->post('dob')),
            "created_at" => get_current_time(),
            "notification_checked_at" => get_current_time(),
        );
        //if a role was posted
        $data["role_id"] = $this->input->post('role');
        if($data["role_id"] === 0 && !logged_is_admin()) {
            redirect("forbidden");
        }
        //execute the query
        $send_details_to_member = function ($data) {
            if ($this->input->post("send_details")) {
                //get the login details template
                $email_template = $this->Email_templates_model->get_final_template("login_info");
                $parser_data["SIGNATURE"] = get_property($email_template, "signature");
                $parser_data["USER_FIRST_NAME"] = $data["first_name"];
                $parser_data["USER_LAST_NAME"] = $data["last_name"];
                $parser_data["USER_LOGIN_EMAIL"] = $data["email"];
                $parser_data["USER_LOGIN_PASSWORD"] = $this->input->post('password');
                $parser_data["DASHBOARD_URL"] = base_url();
                $message = $this->parser->parse_string(
                    get_property($email_template, "message"),
                    $parser_data, TRUE
                );
                send_app_mail(
                    $this->input->post('email'),
                    get_property($email_template, "subject"),
                    $message
                );
            }
        };
        //output the query result
        $member_id = saving_result(
            $this,
            $this->Members_model->save($data),
            true,
            //send an email to the member
            $send_details_to_member($data)
        );
        if($member_id) {
            //create web and/or email notification
            create_notification("new_member", $member_id, array($member_id));
        }
    }

    /**
     * build datatables row
     * @param string $data
     * @param bool $id
     * @return array
     */
    public function make_row($data = stdClass::class, $id = false)
    {
        //check if the user have the permission to access all members list
        $this->only_logged_have_permission("view_members");
        //if this is an inserting result
        if ($id) {
            //get the specific member
            $data = $this->Members_model->get_one($id, true);
        }
        //get member image name
        $image_url = get_avatar(get_property($data, "image"));
        //make member avatar
        $member_avatar = "<span class='avatar avatar-xs'><img src='$image_url' alt='...'></span>";
        //build the member full name
        $full_name = get_property($data, "first_name") . " " . get_property($data, "last_name") . " ";
        //build member name
        $member_name = logged_have_permission("view_member") ?
            anchor("members/view/" . get_property($data, "id"), $full_name) :
            $full_name;
        //return the formatted data
        return array(
            dt_row_trigger(),
            $member_avatar,
            $member_name,
            get_property($data, "email"),
            get_property($data, "phone"),
            from_sql_date(get_property($data, "dob")),
        );
    }

    /**
     * get a specific user details
     * @param int $id
     * @param string $tab
     */
    public function view($id = 0, $tab = "")
    {
        //check if the user have the permission to view the members list
        //or if the user is the thr target (owner)
        $this->only_logged_have_permission("view_member", $id);
        //if the id is valid value
        if ($id * 1) {
            //get the member
            $member = $this->Members_model->get_one($id, true);
            //show 404 page if the member not found
            not_empty_id($member);
            //get member social links
            $data['social_link'] = $this->Social_links_model->get_one($id);
            $data['member'] = $member;
            if ($member->role_id !== '0') {
                $role = $this->Roles_model->get_one(get_property($member, 'role_id'));
                $data['role'] = get_property($role, 'title');
            } else {
                $data['role'] = 'ADMIM';
            }
            $data['tab'] = $tab;
            view("members/view", $data);
        } else {
            $this->only_logged_have_permission("view_member");
            //else load all the members
            $data['members'] = $this->Members_model->get_all_where(array("status" => "active"));
            view("members/grid", $data);
        }
    }

    /**
     * show general information
     * of a specific member
     * @param $id
     */
    public function general_info($id)
    {
        //check if the user have the permission to view the members list
        //or if the user is the thr target (owner)
        $this->only_logged_have_permission("view_member", $id);
        //get the member
        $data['member'] = $this->Members_model->get_one($id, TRUE);
        //check if the user exists
        not_empty_id($data['member']);
        $this->load->view("members/general_info", $data);
    }

    /**
     * save general information of a team member
     */
    public function save_general_info()
    {
        //validate the submitted data to fit the java script rules
        validate_submitted_data(array(
            "id" => "required|numeric",
            "first_name" => "required",
            "last_name" => "required",
            "address" => "required",
            "phone" => "required",
            "dob" => "required|date",
            "gender" => "required",
        ));
        $id = $this->input->post('id');
        //if the target is an admin the logged user should be an admin or system will deny the access
        $this->only_logged_have_permission_and_can_edit_an_admin(array("view_members", "view_member", "edit_member"), $id);
        //get the posted data
        $data = array(
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
            "address" => $this->input->post('address'),
            "alternative_address" => $this->input->post('alternative_address'),
            "phone" => $this->input->post('phone'),
            "alternative_phone" => $this->input->post('alternative_phone'),
            "skype" => $this->input->post('skype'),
            "gender" => $this->input->post('gender'),
            "dob" => to_sql_date($this->input->post('dob')),
        );
        //execute the query
        return jsuccess_or_jerror($this->Members_model->save($data, $id));
    }

    /**
     * show social links of a team member
     * @param $id
     */
    public function social_links($id)
    {
        //check if the user have the permission to view the members list
        //or if the user is the thr target (owner)
        $this->only_logged_have_permission("view_member", $id);
        $data['id'] = $id;
        //get the social links of a specific member
        $data['social_links'] = $this->Social_links_model->get_one($id, TRUE);
        $this->load->view("members/social_links", $data);
    }

    /**
     * save social links of a team member
     */
    public function save_social_links()
    {
        //validate the submitted data to fit the java script rules
        validate_submitted_data(array(
            "id" => "required|numeric",
            "facebook" => "valid_url",
            "twitter" => "valid_url",
            "linkedin" => "valid_url",
            "googleplus" => "valid_url",
            "digg" => "valid_url",
            "youtube" => "valid_url",
            "pinterest" => "valid_url",
            "instagram" => "valid_url",
            "github" => "valid_url",
            "tumblr" => "valid_url",
            "vine" => "valid_url",
        ));
        $id = $this->input->post('id');
        //check if the user have the permission to view the members list
        //or if the user is the thr target (owner)
        $this->only_logged_can_edit_admin(array("view_member", "edit_member"), $id);
        //check if the member have any social link
        $have_social_links = $this->Social_links_model->get_one($id);
        $data = array(
            "facebook" => $this->input->post('facebook'),
            "twitter" => $this->input->post('twitter'),
            "linkedin" => $this->input->post('linkedin'),
            "googleplus" => $this->input->post('googleplus'),
            "digg" => $this->input->post('digg'),
            "youtube" => $this->input->post('youtube'),
            "pinterest" => $this->input->post('pinterest'),
            "instagram" => $this->input->post('instagram'),
            "github" => $this->input->post('github'),
            "tumblr" => $this->input->post('tumblr'),
            "vine" => $this->input->post('vine'),
        );
        foreach ($data as $key => $datum) {
            if ($datum) {
                $data[$key] = prep_url($datum);
            }
        }
        //if the member have social links
        if ($have_social_links) {
            //update them
            $this->Social_links_model->save($data, $id);
        } else {
            //else create new list
            $data["id"] = $id;
            $this->Social_links_model->save($data);
        }
        return jsuccess();
    }

    /**
     * show account settings of a team member
     * @param $id
     */
    public function account_settings($id)
    {
        //check if the user have the permission to view the members list
        //or if the user is the thr target (owner)
        $this->only_logged_can_edit_admin(array("view_member", "edit_member"), $id);
        //get the member
        $roles = $this->Roles_model->get_dropdown_list(array("title"));
        if (logged_is_admin()) {
            $roles = array("0" => plang("admin")) + $roles;
        }
        $data['roles'] = array();
        $add_member_ignored_ids = build('add_member_ignored_ids');
        foreach ($roles as $role_id => $role) {
            if (!in_array($role_id, $add_member_ignored_ids)) {
                $data['roles'][] = $role;
            }
        }
        $data['member'] = $this->Members_model->get_one($id, TRUE);
        $this->load->view("members/account_settings", $data);
    }

    /**
     * save account settings of a team member
     */
    public function save_account_settings()
    {
        $id = $this->input->post('id');
        //check if the user have the permission to view the members list
        //or if the user is the thr target (owner)
        $this->only_logged_can_edit_admin(array("view_member", "edit_member"), $id);
        $email = $this->input->post('email');
        //check if the email is unique, ignore the member existed email
        $is_unique = unique_update("members", "Members_model", $id, "email", $email);
        //validate the submitted data to fit the java script rules
        validate_submitted_data(array(
            "id" => "required|numeric",
            "email" => "required|valid_email$is_unique",
            "password" => "min_length[6]",
            "retype_password" => "matches[password]",
            "role" => "numeric",
        ));
        $data = array(
            "status" => "active",
            "disable_login" => "0",
            "email" => $email
        );
        if (logged_is_admin()) {
            $data["role_id"] = $this->input->post('role');
        }
        //if a password posted
        if ($this->input->post('password')) {
            //encrypt it to md5
            $data["password"] = blowfish_encrypt($this->input->post('password'));
        }
        if($this->input->post("status") && logged_is_admin()) {
            $data["status"] = "inactive";
        }

        if($this->input->post("disable_login") && logged_is_admin()) {
            $data["disable_login"] = "1";
        }
        //execute the query
        return jsuccess_or_jerror($this->Members_model->save($data, $id));
    }

    /**
     * save profile image of a team member
     */
    public function save_profile_image()
    {
        //validate the submitted data to fit the java script rules
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $id = $this->input->post('id');
        //check if the user have the permission to view the members list
        //or if the user is the thr target (owner)
        $this->only_logged_can_edit_admin(array("view_member", "edit_member"), $id);
        //upload the image
        $image = upload_posted_file("profile_image", "png", get_setting("profile_file_path"));
        $old_file = false;
        //if media replace setting enabled
        if (get_setting("media_replace") === "enabled") {
            //save the old file name
            $old_file = $this->Members_model->get_one($id, TRUE);
            $old_file = get_property($old_file, "image");
        }
        $data = array("image" => $image);
        $saved = ($image !== false) ? $this->Members_model->save($data, $id) : false;
        if ($old_file && $saved) {
            //if there is a old file name saved
            $old_file = get_setting("profile_file_path") . $old_file;
            if (file_exists($old_file)) {
                //delete it
                unlink($old_file);
            }
        }
        return jsuccess();
    }

    /**
     * build the send invitation pop up
     */
    public function send_invitation_modal_form()
    {
        //check if the user have the permission to add a new member
        $this->only_logged_have_permission("add_member");
        //load the view
        $this->load->view("members/invitation_modal");
    }

    /**
     * send member invitation
     */
    public function send_invitation()
    {
        //check if the user have the permission to add a new member
        $this->only_logged_have_permission("add_member");
        validate_submitted_data(array(
            "email" => "required|valid_email"
        ));
        $email = $this->input->post('email');
        //if the email already exists show error message
        if($this->Members_model->email_exists($email)) {
            return jerror(plang('this_email_already_exist'));
        }
        //get the send invitation template
        $email_template = $this->Email_templates_model->get_final_template("member_invitation");
        $logged_member = get_logged_member();
        $parser_data["INVITATION_SENT_BY"] = get_property($logged_member, "first_name") . " " .
            get_property($logged_member, "last_name");
        $parser_data["SIGNATURE"] = get_property($email_template, "signature");
        $parser_data["SITE_URL"] = get_uri();
        //make the invitation url with 24hrs validity
        $key = encode_id($this->encryption->encrypt('member|' . $email . '|' . (time() + (24 * 60 * 60))), "signup");
        $parser_data['INVITATION_URL'] = get_uri("signup/accept_invitation/" . $key);
        //send invitation email
        $message = $this->parser->parse_string(
            get_property($email_template, "message"),
            $parser_data, TRUE
        );
        if (send_app_mail($email, get_property($email_template, "subject"), $message)) {
            return jsuccess(plang("invitation_sent"));
        } else {
            return jerror();
        }
    }

    public function delete()
    {
        show_404();
    }

}
