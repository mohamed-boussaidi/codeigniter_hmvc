<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

if (!function_exists("role_permissions")) {
    /**
     * Use the $role_id
     * to return an array contain
     * all the permissions related
     * to the specific role
     * @param string $role_id
     * @return array|stdClass
     */
    function role_permissions($role_id)
    {
        if (is_numeric($role_id) && $role_id > -1) {
            $ci = get_instance(true);
            $permissions = $ci->Role_permissions_model->get_all_where(array("role_id" => $role_id));
            $result = array();
            foreach ($permissions as $permission) {
                $result[$permission->permission_id] = $permission;
            }
            return $result;
        }
        return array();
    }
}

if(!function_exists("get_role_members")) {
    /**
     * return all members of a role
     * @param $role_id
     * @return array
     */
    function get_role_members($role_id = 0) {
        $ci = get_instance(true);
        $members = $ci->Members_model->get_all_where(
            array("role_id" => $role_id), false
        );
        $result = array();
        foreach ($members as $member) {
            if(!empty(get_property($member, "id"))) {
                $result[] = get_property($member,"id");
            }
        }
        return $result;
    }
}

if(!function_exists("get_all_members")) {
    /**
     * get all members except admins
     * @return array
     */
    function get_all_members() {
        $ci = get_instance(true);
        $members = $ci->Members_model->get_all_where(
            array("role_id !=" => "0"), false
        );
        $result = array();
        foreach ($members as $member) {
            if(!empty(get_property($member, "id"))) {
                $result[] = get_property($member,"id");
            }
        }
        return $result;
    }
}

if (!function_exists("permissions")) {
    /**
     * get the list of the permissions
     * if a permission $title passed
     * return that specific permission
     * @param string $title
     * @return bool|mixed|stdClass
     */
    function permissions($title = "")
    {
        $ci = get_instance(true);
        if (empty($title)) {
            $result = array();
            $permissions = $ci->Permissions_model->get_all();
            foreach ($permissions as $permission) {
                $result[$permissions->id] = $permission;
            }
            return $permissions;
        }
        return $ci->Permissions_model->get_one_where(array("title" => $title));
    }
}

if (!function_exists("permission_id")) {
    /**
     * takes permission title and
     * return the permission id
     * @param string $title
     * @return string
     */
    function permission_id($title = "")
    {
        $permission = permissions($title);
        return get_property($permission, "id");
    }
}

if (!function_exists("have_permission")) {
    /**
     * return true if the $permissions
     * contain the $permission else
     * return false
     * @param string $permission_id
     * @param array $permissions
     * @return bool
     */
    function have_permission($permission_id = "", $permissions = array())
    {
        foreach ($permissions as $key => $permission) {
            if ($key == $permission_id) {
                return true;
            }
        }
        return false;
    }
}

if(!function_exists("logged_have_permission")) {
    /**
     * check if the logged member have a permission
     * @param string $title
     * @param bool $owner_can_edit
     * @param bool $logged
     * @return bool
     */
    function logged_have_permission($title = "", $owner_can_edit = false, $logged = false) {
        $logged = $logged ? $logged : get_logged_member();
        $logged_id = $logged ? get_property($logged, "id") : get_logged_member_id();
        if(logged_is_admin()) {
            return true;
        }
        if($owner_can_edit) {
            if($logged_id == $owner_can_edit) {
                return true;
            }
        }
        $permissions = member_permissions($logged_id);
        return have_permission(permission_id($title), $permissions);
    }
}

if(!function_exists("logged_have_permissions")) {
    /**
     * check if the logged member have a set of permissions
     * @param array $titles
     * @param bool $owner_can_edit
     * @param bool $logged
     * @return bool
     */
    function logged_have_permissions($titles = array(), $owner_can_edit = false, $logged = false) {
        foreach ($titles as $title) {
            if(!logged_have_permission($title, $owner_can_edit, $logged)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists("member_permissions")) {
    /**
     * Use the $member_id
     * to return an array contain
     * all the permissions related
     * to the role of the specific member
     * @param $member_id
     * @return array|stdClass|string
     */
    function member_permissions($member_id)
    {
        if (is_numeric($member_id) && $member_id > -1) {
            $ci = get_instance(true);
            $member = $ci->Members_model->get_one($member_id);
            return role_permissions(get_property($member,"role_id"));
        }
        return array();
    }
}

if(!function_exists("is_admin")) {
    /**
     * check if the user is admin
     * @param $member_id
     * @return bool
     */
    function is_admin($member_id) {
        if (is_numeric($member_id) && $member_id > -1) {
            $ci = get_instance(true);
            $member = $ci->Members_model->get_one($member_id);
            return get_property($member,"role_id") == 0 ? true : false;
        }
        return false;
    }
}

if(!function_exists("is_member")) {
    /**
     * check if the user is admin
     * @param $member_id
     * @return bool
     */
    function is_member($member_id) {
        if (is_numeric($member_id) && $member_id > -1) {
            $ci = get_instance(true);
            $member = $ci->Members_model->get_one($member_id);
            return ($member && (is_numeric(get_property($member,"role_id")))) ? true : false;
        }
        return false;
    }
}

if(!function_exists("is_owner")) {
    /**
     * check if the user is owner
     * @param $member_id
     * @param $target_id
     * @return bool
     */
    function is_owner($member_id, $target_id) {
        if (is_numeric($member_id) && $member_id > -1) {
            return $target_id == $member_id;
        }
        return false;
    }
}

if(!function_exists("logged_can_edit_admin")) {
    /**
     * check if user can edit admin
     * @param array $permissions
     * @param $target
     * @return bool
     * @internal param bool $owner_can_edit
     * @internal param $member_id
     */
    function logged_can_edit_admin($permissions = array(), $target) {
        $have_permission = logged_have_permissions($permissions, $target);
        return (logged_is_admin() || !is_admin($target)) && $have_permission;
    }
}

if(!function_exists("logged_is_admin")) {
    /**
     * check if the logged member is an admin
     * @param bool $logged_id
     * @return bool
     */
    function logged_is_admin($logged_id = false) {
        $logged_id = $logged_id ? $logged_id : get_logged_member_id();
        return is_admin($logged_id);
    }
}

if(!function_exists("logged_is_member")) {
    /**
     * check if the logged member is an member
     * prevent session injections
     * @return bool
     */
    function logged_is_member() {
        return is_member(get_logged_member_id());
    }
}

if(!function_exists("logged_is_owner")) {
    /**
     * check if the logged member is an admin
     * @param $target_id
     * @param bool $logged_id
     * @return bool
     */
    function logged_is_owner($target_id, $logged_id = false) {
        $logged_id = $logged_id ? $logged_id : get_logged_member_id();
        return is_owner($logged_id, $target_id);
    }
}

if(!function_exists("logged_is_admin_or_owner")) {
    /**
     * check if the logged in user is admin or owner
     * @param $id
     * @param bool $logged_id
     * @return bool
     */
    function logged_is_admin_or_owner($id, $logged_id = false) {
        $logged_id = $logged_id ? $logged_id : get_logged_member_id();
        return logged_is_admin() && ($logged_id == $id);
    }
}

if(!function_exists("get_logged_member")) {
    /**
     * return the logged member info
     * @return stdClass
     */
    function get_logged_member() {
        $ci = get_instance(true);
        $member = $ci->Members_model->get_one($ci->Members_model->member_id(), TRUE);
        return $member;
    }
}

if(!function_exists("get_logged_member_id")) {
    /**
     * return the logged member id
     * @return string
     */
    function get_logged_member_id() {
        return get_property(get_logged_member(), "id");
    }
}
