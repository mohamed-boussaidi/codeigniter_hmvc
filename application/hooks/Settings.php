<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings hook
 * used to initialize necessary
 * functions and attributes
 * before any class constructor
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

/**
 * initialize function
 * get all the setting
 * from at the database
 * and join them to the
 * CI configs
 * load application languages
 * located under application/languages
 */
function init_settings() {
    $ci = & get_instance(true);
    if($ci->Settings_model->table_exists()) {
        $settings = $ci->Settings_model->get_all();
        foreach ($settings as $setting) {
            $ci->config->set_item($setting->setting_name, $setting->setting_value);
        }
    }
    $language = get_setting("language");
    $ci->lang->load('form_validation', $language);
    $ci->lang->load('default', $language);
    $ci->lang->load('custom', $language);
    $ci->lang->load('countries', $language);
	$db_features = array();
	foreach ($ci->Features_model->get_all_where(array("enabled" => 1)) as $db_feature) {
		$db_features[] = get_property($db_feature, "name");
	}
    foreach (get_modules() as $module) {
        $ci->lang->load($module.'/module_default', $language);
        $ci->lang->load($module.'/module_custom', $language);
        $features_file = FCPATH . "application/modules/$module/config/features.php";
        if(file_exists($features_file)) {
			include $features_file;
			if(isset($features)) {
				foreach ($features as $feature_name => $feature) {
					if($feature) {
						$ci->config->set_item("system_features_configs_$feature_name", $feature);
						if(in_array($feature_name, $db_features)) {
							$ci->config->set_item("features_configs_$feature_name", $feature);
						}
					}
				}
				unset($features);
			}
        }
    }
}
