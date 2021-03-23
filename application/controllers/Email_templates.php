<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

/**
 * email templates configurations
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Email_templates extends Init implements Mind_controller
{
	/**
	 * Email_templates constructor.
	 * only admin have access to this class
	 */
    public function __construct()
    {
        parent::__construct();
        //only admin can access this class
        $this->only_admin();
    }

    /**
     * return a list of templates
     * @return array
     */
    private function _templates() {
        //make templates container
        $templates = array(
            "login_info" => array("USER_FIRST_NAME", "USER_LAST_NAME", "DASHBOARD_URL", "USER_LOGIN_EMAIL", "USER_LOGIN_PASSWORD", "SIGNATURE"),
            "reset_password" => array("ACCOUNT_HOLDER_NAME", "RESET_PASSWORD_URL", "SITE_URL", "SIGNATURE"),
            "member_invitation" => array("INVITATION_SENT_BY", "INVITATION_URL", "SITE_URL", "SIGNATURE"),
            "general_notification" => array("EVENT_TITLE", "EVENT_DETAILS", "APP_TITLE", "NOTIFICATION_URL", "SIGNATURE"),
            "signature" => array()
        );
        //merge the container with the build result
        $templates = array_merge(
            $templates,
            build("email_templates")
        );
        //make a finale container
        $final_templates = array();
        //get the templates saved at the database
        $email_templates = $this->Email_templates_model->get_all(false);
        //for each element if it exists in the $templates container push it to the finale container
        foreach ($email_templates as $email_template) {
            $template_name = get_property($email_template, "template_name");
            if(isset($templates[$template_name])) {
                $final_templates[$template_name] = $templates[$template_name];
            }
        }
        //return the result
        return $final_templates;
    }

	/**
	 * load the main view
	 */
    public function index() {
        //load the view
        view("email_templates/index");
    }

	/**
	 * save data to the database
	 * @return bool
	 */
    public function save() {
        //validate submitted data
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        //get the element id
        $id = $this->input->post('id');
        //get template configs
        $data = array(
            "email_subject" => $this->input->post('email_subject'),
            "custom_message" => decode_ajax_post_data($this->input->post('custom_message'))
        );
        //save them to the database
        return saving_result($this, $this->Email_templates_model->save($data, $id), false);
    }

    /**
     * restore the template to the default value
     * by setting the custom value to empty
     */
    public function restore_to_default() {
        //validate the submitted data
        validate_submitted_data(array(
            "id" => "required"
        ));
        //get the template id
        $template_id = $this->input->post('id');
        //build data container
        $data = array(
            "custom_message" => ""
        );
        //set the custom message to empty
        $save_id = $this->Email_templates_model->save($data, $template_id);
        //if success
        if ($save_id) {
            //return the default message
            $default_message = get_property(
                $this->Email_templates_model->get_one($save_id, true, false),
                "default_message"
            );
            echo json_encode(
                array(
                    "success" => true,
                    "data" => $default_message,
                    'message' => plang('template_restored')
                )
            );
            return true;
        } else {
            //show message error
            return jerror();
        }
    }

    /**
     * get all the email templates
     */
    public function list_data() {
        $list = array();
        //prepare all the templates list
        foreach ($this->_templates() as $template_name => $variables) {
            $list[] = array("<span class='template-row' data-name='$template_name'>" . plang($template_name) . "</span>");
        }
        echo json_encode(array("data" => $list));
        return true;
    }

    /**
     * build the form html
     * @param string $template_name
     */
    public function form($template_name = "") {
        $data['email_template'] = $this->Email_templates_model->get_one_where(
            array("template_name" => $template_name), true, false
        );
        $variables = get_array_value($this->_templates(), $template_name);
        $data['variables'] = $variables ? $variables : array();
        $this->load->view('email_templates/form', $data);
    }

    public function view()
    {
        show_404();
    }

    public function modal_form()
    {
        show_404();
    }

    public function delete()
    {
        show_404();
    }

    public function make_row($data = stdClass::class, $id = false)
    {
        show_404();
    }
}
