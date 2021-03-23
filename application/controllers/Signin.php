<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Signin
 * Manage member session
 * and password reset
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Signin extends CI_Controller {

	/**
	 * Signin constructor.
	 * all members have access to this class
	 */
    public function __construct() {
        parent::__construct();
    }

    /**
     * load the signin view
     * @param string $email
     */
    public function index($email = '') {
        $default_controllers = build('not_logged_default_controller');
        if (count($default_controllers)) {
            $default_controller = end($default_controllers);
        } else {
            $default_controller = "default_controller";
        }
        //if the member is logged in
        if ($this->Members_model->member_id()) {
            //redirect him to the dashboard interface
            redirect($default_controller);
        } else {
            $redirect = "";
            //if a redirect variable was passed
            if (isset($_REQUEST["redirect"])) {
                //set it as redirect uri
                $redirect = $_REQUEST["redirect"];
            }
            //validate the submitted data
            $this->form_validation->set_rules('email', '', 'callback_authenticate');
            $this->form_validation->set_error_delimiters('<span>', '</span>');
            if ($this->form_validation->run() == FALSE) {
                $data["redirect"] = $redirect;
                $data["email"] = urldecode($email);
                $this->load->view('signin/index', $data);
            } else {
                if ($redirect) {
                    redirect($redirect);
                } else {
                    redirect($default_controller);
                }
            }
        }
    }

    /**
     * check authentication
     * @param $email
     * @return bool
     */
    public function authenticate($email) {
        //get password
        $password = $this->input->post("password");
        //load member authenticate
        if (!$this->Members_model->authenticate($email, $password)) {
            //set the validation text
            $this->form_validation->set_message('authenticate', plang("authentication_failed"));
            return false;
        }
        return true;
    }

    /**
     * destroy the session
     * and redirect to signin
     */
    public function signout() {
        //remove the session
        $this->Members_model->signout();
    }

    /**
     * send an email to users mail with reset password link
     * @return bool
     */
    function send_reset_password_mail() {
        validate_submitted_data(array(
            "email" => "required|valid_email"
        ));
        $email = $this->input->post("email");
        //send reset password email if found account with this email
        if ($this->Members_model->email_exists($email)) {
            $user = $this->Members_model->get_one_where(array("email" => $email), true, false);
            $email_template = $this->Email_templates_model->get_final_template("reset_password");
            $parser_data["ACCOUNT_HOLDER_NAME"] =
            get_property($user, "first_name"). " " . get_property($user, "last_name");
            $parser_data["SIGNATURE"] = get_property($email_template, "signature");
            $parser_data["SITE_URL"] = get_uri();
            $key = encode_id($this->encryption->encrypt($email . '|' . (time() + (60 * 15))), "reset_password");
            $parser_data['RESET_PASSWORD_URL'] = get_uri("signin/new_password/" . $key);
            $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
            if (send_app_mail($email, $email_template->subject, $message)) {
                echo json_encode(array('success' => true, 'message' => plang("reset_info_send")));
            } else {
                echo json_encode(array('success' => false, 'message' => plang('error_occurred')));
            }
        } else {
            echo json_encode(array("success" => false, 'message' => plang("no_account_found_with_this_email")));
            return false;
        }
        return true;
    }

    /**
     * show forgot password recovery form
     */
    function request_reset_password() {
        $data["form_type"] = "request_reset_password";
        $this->load->view('signin/index', $data);
    }

    /**
     * when user clicks to reset password link from his/her email, redirect to this url
     * @param $key
     * @return bool
     */
    function new_password($key) {
        $valid_key = $this->is_valid_reset_password_key($key);
        if ($valid_key) {
            $email = get_array_value($valid_key, "email");
            if ($this->Members_model->email_exists($email)) {
                $data["key"] = $key;
                $data["form_type"] = "new_password";
                $this->load->view('signin/index', $data);
                return false;
            }
        }
        //else show error
        $data["heading"] = plang("invalid_request");
        $data["message"] = plang("request_expired_message");
        $this->load->view("errors/html/error_general", $data);
        return true;
    }

    /**
     * finally reset the old password and save the new password
     * @return bool
     */
    function do_reset_password() {
        validate_submitted_data(array(
            "key" => "required",
            "password" => "required"
        ));
        $key = $this->input->post("key");
        $password = $this->input->post("password");
        $valid_key = $this->is_valid_reset_password_key($key);
        if ($valid_key) {
            $email = get_array_value($valid_key, "email");
            if($this->Members_model->email_exists($email)) {
                $user = $this->Members_model->get_one_where(array("email" => $email), true, false);
                $data = array("password" => blowfish_encrypt($password));
                if (!empty(get_property($user, "id")) &&
                    $this->Members_model->save($data, get_property($user, "id"))) {
                    echo json_encode(array("success" => true, 'message' => plang("password_reset_successfully") . " " . anchor("signin", plang("signin"))));
                    return true;
                }
            } else {
                echo json_encode(array("success" => false, 'message' => plang("email_not_found")));
                return false;
            }
        }
        echo json_encode(array("success" => false, 'message' => plang("error_occurred")));
        return false;
    }

    /**
     * check valid key
     * @param string $key
     * @return array
     */
    private function is_valid_reset_password_key($key = "") {
        if ($key) {
            $key = decode_id($key, "reset_password");
            $key = $this->encryption->decrypt($key);
            $key = explode('|', $key);

            $email = get_array_value($key, "0");
            $expire_time = get_array_value($key, "1");

            if ($email && is_email($email) && $expire_time && $expire_time > time()) {
                return array("email" => $email);
            }
        }
        return array();
    }

}
