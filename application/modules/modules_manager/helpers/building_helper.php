<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists("modules_manager_building_bottom_settings_tabs_list")) {
    function modules_manager_building_bottom_settings_tabs_list() {
        return array("modules_manager" => array("url" => "modules_manager", "name" => "modules_manager"));
    }
}

if(!function_exists("modules_manager_building_settings_tabs_list_contain")) {
    function modules_manager_building_settings_tabs_list_contain() {
        return array("modules_manager" => "modules_manager");
    }
}