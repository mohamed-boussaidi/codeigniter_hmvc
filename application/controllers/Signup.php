<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * register a use to the system
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Signup extends CI_Controller {

	/**
	 * Signup constructor.
	 * no login required
	 */
    function __construct() {
        parent::__construct();
    }

    /**
     * accept member invitation
     * @param string $signup_key
     * @return bool
     */
    public function accept_invitation($signup_key = "") {
        //check if there is a valid key
        $valid_key = $this->is_valid_key($signup_key);
        if ($valid_key) {
            $email = get_array_value($valid_key, "email");
            $type = get_array_value($valid_key, "type");
            //if the email exists show error message
            if ($this->Members_model->email_exists($email)) {
                $data["heading"] = "Account exists!";
                $data["message"] = lang("account_already_exists_for_your_mail") . " " .
                    anchor("signin", lang("signin"));
                $this->load->view("errors/html/error_general", $data);
                return false;
            }
            //set sign up message to member sign up
            if ($type === "member") {
                $data["signup_message"] = lang("create_an_account_as_a_member");
            }

            $data["signup_type"] = "invitation";
            $data["type"] = $type;
            $data["signup_key"] = $signup_key;
            $this->load->view("signup/index", $data);
        } else {
            $data["heading"] = "406 Not Acceptable";
            $data["message"] = lang("invitation_expired_message");
            $this->load->view("errors/html/error_general", $data);
        }
        return true;
    }

    /**
     * check if an invitation key is valid
     * @param string $signup_key
     * @return array|bool
     */
    private function is_valid_key($signup_key = "") {
        $signup_key = decode_id($signup_key, "signup");
        $signup_key = $this->encryption->decrypt($signup_key);
        $signup_key = explode('|', $signup_key);
        $type = get_array_value($signup_key, "0");
        $email = get_array_value($signup_key, "1");
        $expire_time = get_array_value($signup_key, "2");
        if ($type && $email && is_email($email) && $expire_time && $expire_time > time()) {
            return array("type" => $type, "email" => $email);
        }
        return false;
    }

    /**
     * create new account
     * @return bool
     */
    public function create_account() {
        //get the invitation key
        $signup_key = $this->input->post("signup_key");
        validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required",
            "phone" => "required",
        ));
        //generate random password
        $password = $this->randomPassword();
        $data = array(
            "first_name" => $this->input->post("first_name"),
            "last_name" => $this->input->post("last_name"),
            "phone" => $this->input->post("phone"),
            "password" => blowfish_encrypt($password),
            "gender" => "male",
            "created_at" => get_current_time(),
            "notification_checked_at" => get_current_time(),
        );
        $member_id = false;
        //if the key is valid
        if ($signup_key) {
            //it is an invitation, validate the invitation key
            $valid_key = $this->is_valid_key($signup_key);
            if ($valid_key) {
                $email = get_array_value($valid_key, "email");
                $type = get_array_value($valid_key, "type");
                //show error message if email already exists
                if ($this->Members_model->email_exists($email)) {
                    echo json_encode(
                        array(
                            "success" => false,
                            'message' => lang("account_already_exists_for_your_mail") . " " .
                                anchor("signin", lang("signin"))
                        )
                    );
                    return false;
                }
                $data["email"] = $email;
                if ($type === "member") {
                    //create a team member account
                    $member_id = $this->Members_model->save($data);
                    $this->send_info($data, $password);
                    create_notification("new_member", $member_id, array($member_id), false);
                }

            } else {
                //invalid key. show an error message
                echo json_encode(
                    array("success" => false, 'message' => lang("invitation_expired_message"))
                );
                return false;
            }
        }

        if ($member_id) {
            echo json_encode(array("success" => true, 'message' => lang('account_created') . " " .
                anchor("signin", lang("signin"))));
        } else {
            jerror();
        }
        return true;
    }

    /**
     * send login info to a member
     * @param $data
     * @param $password
     */
    private function send_info($data, $password) {
      $email_template = $this->Email_templates_model->get_final_template("login_info");
      $parser_data["SIGNATURE"] = get_property($email_template, "signature");
      $parser_data["USER_FIRST_NAME"] = $data["first_name"];
      $parser_data["USER_LAST_NAME"] = $data["last_name"];
      $parser_data["USER_LOGIN_EMAIL"] = $data["email"];
      $parser_data["USER_LOGIN_PASSWORD"] = $password;
      $parser_data["DASHBOARD_URL"] = base_url();
      $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
      send_app_mail($data["email"],
          get_property($email_template, "subject"), $message);
    }

    /**
     * generate random password
     * @return string
     */
    private function randomPassword() {
      $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789*/.-+%_@";
      $pass = array(); //remember to declare $pass as an array
      $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
      for ($i = 0; $i < 8; $i++) {
          $n = rand(0, $alphaLength);
          $pass[] = $alphabet[$n];
      }
      return implode($pass); //turn the array into a string
    }

    function index() {
        if (get_logged_member_id()) {
            redirect('dashboard');
        }
        $signup_view = build('signup_view');
        if (count($signup_view)) {
            $this->load->view(end($signup_view));
        } else {
            show_404();
        }
    }
}
