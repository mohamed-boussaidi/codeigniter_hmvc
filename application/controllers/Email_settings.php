<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

/**
 * email sending configuration
 * you can access this class from the settings
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Email_settings extends Init implements Mind_controller
{
	/**
	 * Email_settings constructor.
	 * only admin have access to this class
	 */
    public function __construct()
    {
        parent::__construct();
        //only admin can access this class
        $this->only_admin();
    }

	/**
	 * load the main view
	 */
    public function index()
    {
        view("email_settings/index");
    }

    /**
     * save settings to the database
     * save smtp
     * check email sending
     * @return bool
     */
    public function save()
    {
        //validate the submitted data
        validate_submitted_data(array(
            "email_sent_from_address" => "required",
            "email_sent_from_name" => "required",
        ));
        //settings elements
        $settings = array(
            "email_sent_from_address",
            "email_sent_from_name",
            "email_protocol",
            "email_smtp_host",
            "email_smtp_port",
            "email_smtp_user",
            "email_smtp_pass",
            "email_smtp_security_type"
        );
        //for each element
        foreach ($settings as $setting) {
            //if a value was posted
            $value = $this->input->post($setting);
            //save it to the settings table
            $this->Settings_model->save_setting($setting, $value ? $value : "");
        }
        //get test email field value
        $test_email_to = $this->input->post("send_test_mail_to");
        //if the field is not empty
        if ($test_email_to) {
            //start email library
            $this->email->initialize(get_smtp_config());
            $this->email->set_newline("\r\n");
            $this->email->from(
                $this->input->post("email_sent_from_address"),
                $this->input->post("email_sent_from_name")
            );
            $this->email->to($test_email_to);
            $this->email->subject("Test message");
            $this->email->message("This is a test message to check mail configuration.");
            //send the email and return a result
            if ($this->email->send()) {
                echo json_encode(array("success" => true, 'message' => plang('test_mail_sent')));
                return false;
            } else {
                echo json_encode(array("success" => false, 'message' => plang('test_mail_send_failed')));
                show_error($this->email->print_debugger());
                return false;
            }
        }
        //if the test field is empty return success
        return jsuccess(plang('element_updated', array("settings")));
    }

    public function delete()
    {
        show_404();
    }

    public function list_data()
    {
        show_404();
    }

    public function make_row($data = stdClass::class, $id = false)
    {
        show_404();
    }

    public function view()
    {
        show_404();
    }

    public function modal_form()
    {
        show_404();
    }
}
