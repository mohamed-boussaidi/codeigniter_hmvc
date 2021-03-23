<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * Class Settings_model
 * Model for the settings table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Settings_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = parent::__construct('settings');
    }

    /**
     * use the name of a setting
     * to get the value
     * or return false
     * @param $setting_name
     * @return bool
     */
    function get_setting($setting_name) {
        $result = $this->db->get_where($this->table, array('setting_name' => $setting_name));
        if ($result->num_rows() > 0) {
            return $result->row()->setting_value;
        }
        return false;
    }

    /**
     * save or update a setting row
     * to the database
     * @param $setting_name
     * @param $setting_value
     * @return mixed
     */
    function save_setting($setting_name, $setting_value) {
        $fields = array(
            'setting_name' => $setting_name,
            'setting_value' => $setting_value
        );
        $exists = $this->get_setting($setting_name);
        if(is_string($exists)) {
            return $this->update_where($fields, array("setting_name" => $setting_name));
        }
        return $this->save($fields);
    }

}
