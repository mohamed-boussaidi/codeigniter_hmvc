<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Init
 * this class is the first controller which will be
 * executed by the framework all the other controllers
 * should extend this class, and this class will extend the
 * CI controller, the role of this class is running some code
 * before the controller constructor executed like the
 * authentication and the user permissions.
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Init extends MX_Controller
{

    public $logged_member;

    /**
     * call parent constructor
     * check if the user is logged in
     * Init constructor.
     */
    public function __construct()
    {
        parent::__construct();
        //check user's login status, if not logged in redirect to signin page
        if (!$this->Members_model->member_id()) {
            redirect('signin?redirect=' . get_uri(uri_string()));
        }
        $this->logged_member = get_logged_member();
    }

    /**
     * if the logged in user not an admin go to forbidden
     */
    public function only_admin() {
        if(!logged_is_admin()) {
            redirect("forbidden");
        }
    }

    /**
     * if the logged in user not an admin go to forbidden
     * @param $target_id
     */
    public function only_owner($target_id) {
        if(!logged_is_owner($target_id)) {
            redirect("forbidden");
        }
    }

    /**
     * if the logged in user not have the permission go to forbidden
     * @param string $permission
     * @param $owner_can_edit
     */
    public function only_logged_have_permission($permission = "", $owner_can_edit = false) {
        if(!logged_have_permission($permission, $owner_can_edit)) {
            redirect("forbidden");
        }
    }

    /**
     * if the logged in user not have the set of permissions go to forbidden
     * @param array $permissions
     * @param $owner_can_edit
     */
    public function only_logged_have_permissions($permissions = array(), $owner_can_edit = false) {
        if(!logged_have_permissions($permissions, $owner_can_edit)) {
            redirect("forbidden");
        }
    }

    /**
     * check if the user have access and if he can change the other user if he is an admin
     * @param array $permissions
     * @param bool $target
     */
    public function only_logged_have_permission_and_can_edit_an_admin($permissions = array(), $target = false) {
        $have_permission = logged_have_permissions($permissions, $target);
        if (!((logged_is_admin() || !is_admin($target)) && $have_permission)) {
            redirect("forbidden");
        }

    }


}