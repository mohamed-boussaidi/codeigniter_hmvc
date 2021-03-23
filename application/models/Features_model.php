<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * Class Features_model
 * Model for the features table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Features_model extends Crud_model {

	private $table = null;

	function __construct() {
		$this->table = parent::__construct('features');
	}

	/**
	 * get all the system features
	 * @return array
	 */
	public function get_all_features() {
		$tables = $this->get_tables_names();
		$features = array();
		foreach ($tables as $table) {
			$features[] = $this->get_features($table);
		}
		return $features;
	}

	/**
	 * get module features
	 *
	 * @param bool $module
	 *
	 * @return array
	 *
	 */
	public function get_features($module = false) {
		$where = array();
		if($module) {
			$where["module"] = $module;
		}
		return $this->get_all_where($where);
	}


	/**
	 * save or update a features row
	 * to the database
	 *
	 * @param $enabled
	 *
	 * @return mixed
	 *
	 */
	function save_feature($enabled) {
		$saved = true;
		$features = $this->get_all();
		foreach ($features as $feature) {
			$name = get_property($feature, "name");
			$data = array("enabled" => "0");
			if(isset($enabled[$name])) {
				$data = array("enabled" => "1");
			}
			$saved &= $this->save($data, get_property($feature, "id"));
		}
		return $saved;
	}

}
