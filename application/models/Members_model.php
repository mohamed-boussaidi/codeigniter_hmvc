<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * Class Members_model
 * Model for the members table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Members_model extends Crud_model
{
    private $table = null;

    function __construct()
    {
        $this->table = parent::__construct('members');
    }

    /**
     * check the login data
     * and init the session
     * if the member exists
     * set member_id on session
     * @param $email
     * @param $password
     * @return bool
     */
    function authenticate($email, $password)
    {
        $result = $this->get_one_where(
            array(
                'email' => $email,
                'status' => 'active',
                'disable_login' => 0
            )
        );
        if ($result) {
            if(blowfish_decrypt($password, get_property($result, "password"))) {
	            foreach (build("unset_session_userdata") as $userdata) {
		            $this->session->unset_userdata($userdata);
	            }
                $this->session->set_userdata('member_id', $result->id);
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * if there is a member_id
     * saved in the session
     * return it else return false
     * @return bool|mixed
     */
    function member_id()
    {
        return $this->session->member_id ? $this->session->member_id : false;
    }

    /**
     * destroy the session
     * and redirect the member
     * to the signin page
     */
    function signout()
    {
        $this->session->sess_destroy();
        redirect('signin');
    }

    /**
     * check if an email
     * exists
     * @param $email
     * @param int $id
     * @return bool
     */
    function email_exists($email, $id = 0)
    {
        $result = $this->get_one_where(
            array("email" => $email)
        );
        if ($result && $result->id != $id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * fetch all members with the passed
     * role id and set their role id to 1
     * which is equal to the member role id
     * @param string $role_id
     */
    function set_role_to_member($role_id = "") {
        $members = $this->get_all_where(array("role_id" => $role_id));
        foreach ($members as $member) {
            $data["role_id"] = "1";
            $this->save($data, $member->id);
        }
    }

}
