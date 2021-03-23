### MODULES DOCUMENTATION

#### Creation

A module should have a specific structure

`assets/`  module libs.

`config/` configurations folder.

`config/features.php` system features file.

`controllers/` controllers folder.

`controllers/Module_name.php` module main controller.

`helpers/` helpers folder.

`helpers/building_helper.php` injection methods helper.

`language/` module languages files.

`migrations/` module migrations files.

`models/` module models folder.

`views/` module views folder.

#### Tools CLI

*Tools* is a command line of the project that can be used to create module, migrate tables.

* generate a module  *MODULE_NAME*, you run: 
 
	```php index.php tools create_module MODULE_NAME```

* create a migration file *FILE_NAME* for the module *MODULE_NAME*, you run:
 
	```php index.php tools migration FILE_NAME MODULE_NAME```

* migrate migration files:

	```php index.php tools migrate```

* create a module with it's controller, index, and modal_form and models
    ```php index.php tools generate_crud produits 0 produit 0```
first you have to create a table *produits* for example using
    ```
  CREATE TABLE `mind_kernel_products` (
    `id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  ALTER TABLE `mind_kernel_products`
    ADD PRIMARY KEY (`id`);
  ALTER TABLE `mind_kernel_products`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
    ```

* create a module with the modal form linked to other table
    ```php index.php tools generate_crud prospects 0 prospect 0 products:product:title::clients:client:fullname```
first you have to create the 3 tables used 
    ```
    CREATE TABLE `mind_kernel_clients` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `mind_kernel_clients`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mind_kernel_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

  CREATE TABLE `mind_kernel_products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `mind_kernel_products`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `mind_kernel_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;



CREATE TABLE `mind_kernel_prospects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mind_kernel_prospects`
--
ALTER TABLE `mind_kernel_prospects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mind_kernel_prospects`
--
ALTER TABLE `mind_kernel_prospects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
    ```
#### Installation

To install a module go to `settings/modules/add_module` and 
upload the module zipped archive, the module should contain 
at least `helpers/building_helper.php`

N.B: the root of the module archive file should look like the picture next
```
    .
    ├──assets
    ├──config
    ├──controllers
    ├──helpers
    ├──index.php
    ├──language
    ├──migrations
    ├──models
    └──views
```

### Module controller
module contoller have to be created in the `controllers` folder of the module, the created controller have to extends the `Init` class and implements `Mind_controller` interface, it should be like:

```
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH . "modules/Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

/**
 * member actions like loading views and executing queries.
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class ModuleName extends Init implements Mind_controller
{   
    public function __construct()
    {
        //put your code here
    }

    public function index()
    {
        //put your code here
    }

    public function view()
    {
        //put your code here
    }

    public function modal_form()
    {
        //put your code here
    }

    public function save()
    {
        //put your code here
    }

    public function delete()
    {
        //put your code here
    }

    public function list_data()
    {
        //put your code here
    }

    public function make_row($data = stdClass::class, $id = false)
    {
        //put your code here
    }



}
```
N.B: all the functions in the above class are required, you can than add your own functions 

### ModuleName/helpers/building_helpers.php

**Show/hide module in left menu**

add the next snippet in the end of *ModuleName/helpers/building_helpers.php* file, to add the module link to the top of the left menu
```
if(!function_exists("ModuleName_building_top_left_menu")) { 
    function ModuleName_building_top_left_menu() { 
        return array( 
            "ModuleName" => array('name' => 'nameOfModule', 'url' => 'ModuleName', 'class' =>'fa-calendar') 
        ); 
    } 
}
``` 
also you can add link of module to the end of the left menu, use: 
```
if(!function_exists("ModuleName_building_bottom_left_menu")) { 
    function ModuleName_building_bottom_left_menu() { 
        return array( 
            "ModuleName" => array('name' => 'nameOfModule', 'url' => 'ModuleName', 'class' =>'fa-calendar') 
        ); 
    } 
}
```
links that can be seen only by admin are added using:
```
if (!function_exists('ModuleName_building_admin_left_menu')) {
	function ModuleName_building_admin_left_menu(&$priority) {
		$priority = 0;
		return array(
			array(
				'name'    => 'settings',
				'url'     => 'members',
				'class'   => 'fa-wrench'
			)
		);
	}
}
```
also you can use the below snippet to open module using settings in left menu and highlight settings when the module is opened:
```
if (!function_exists('ModuleName_building_admin_left_menu')) {
	function ModuleName_building_admin_left_menu(&$priority) {
		$priority = 0;
		$settings_contain = array_merge(
			build( "settings_tabs_list_contain" ),
			array(
				'ModuleName'
			) );
		return array(
			array(
				'name'    => 'settings',
				'url'     => 'ModuleName',
				'class'   => 'fa-wrench',
				'contain' => $settings_contain
			)
		);
	}
}
```
you can also remove a link from left menu(E.g: to remove link to members), to do that use:
```
if (!function_exists('ModuleName_building_removed_left_menu')) {
	function ModuleName_building_removed_left_menu() {
		return array('members');
	}
}
``` 
NB:
* *ModuleName* in 'url' => 'ModuleName' should point to an existant *ModuleName* file in controllers folder of Module.
* *nameOfModule*  can be changed as you wish but make sure that the name correspond to an entry in LANGUAGE/english/module_default_lang.php or LANGUAGE/english/module_custom_lang.php
* *fa-calendar* can be changed 

**import css and js of the module**

add this snippet  to add *modcss.css* to header, *modcss.css* should be in *ModuleName/assets/css*
```
if(!function_exists("ModuleName_building_head_css")) { 
    function ModuleName_building_head_css() { 
        return array( 
            "ModuleName" => modules_assets("ModuleName", "css/modcss.css", false) 
        ); 
    } 
}
```

for JS file, put the file(s) in *ModuleName/assets/js*, then add the snippet
```
    if(!function_exists("ModuleName_building_head_js")) { 
        function ModuleName_building_head_js() { 
            return array( 
                "ModuleName1" => modules_assets("events", "js/js1.js", false), 
                "ModuleName2" => modules_assets("events", "js/js2.js", false)
            ); 
        } 
    } 
```

**Notifications**

to create a notification for a module, a row must be added to `notification_settings` table that resemble to this:
```
            notification_settings
|`event`|`enable_email`|`enable_web`|`module_name`|
|name_ev|     1/0      |    1/0     |   testMod   | 
```
for a module, the creation can be done in the migration files of the module)


also those function have to be added to ```building_helper.php``` of the module
```
if (!function_exists("building_notification_name_ev_content")) {
    function building_notification_name_ev_content($event)
    {
        $ci = get_instance(true);
        $member = $ci->Members_model->get_one(get_property($event, "related"), true);
        return array(
            "events" => "<br>".plang("notification_".get_property($event, "event")."_title")." ".
            get_property($member, "first_name")."  ".
            get_property($member, "last_name"). " " .plang("created_an_event")
        );
    }
}

if(!function_exists("building_notification_name_ev_url")) {
    function building_notification_name_ev_url($event) {
        return array("events" => base_url("events/index"));
    }
}
```

for the notification email use:
```
if(!function_exists("building_notification_name_ev_email_content")) {
    function building_notification_name_ev_email_content( $event ) {
    		$ci     = get_instance( true );
    		$member = $ci->Members_model->get_one( get_array_value( $event, "related" ), true );
    
    		return array(
    			"<br>" . plang( "notification_" . get_property( $event, "event" ) . "_title" ) . " " .
    			get_property( $member, "first_name" ) . " " .
    			get_property( $member, "last_name" )
    		);
    	}
}
```
**Permissions**

to add permission for a module, rows must be added to `permissions` table:
```
                permissions
|       `title`     |     `module_name`   |
|permissions_title_1|     ModuleName      |
|permissions_title_2|     ModuleName      |
|permissions_title_3|     ModuleName      |
```
also `building_helper.php` should contain

```
if ( ! function_exists( "ModuleName_building_permissions_row" ) ) {
    function ModuleName_building_permissions_row( $role_id = "" ) {
        return array(
            "events" => array(
                "permissions_title_1",
                "permissions_title_2",
                "permissions_title_3"
            ),
        );
    }
}
```

to add disable/enable relation between permissions use:
```php
if ( ! function_exists( "ModuleName_building_linked_checkbox_list" ) ) {
    function ModuleName_building_linked_checkbox_list() {
        return array(
                    "events" => array(
                        "main" => array(
                            "name"       => "permissions_title_1",
                            "related"    => array(
                                "permissions_title_2",
                                "permissions_title_3"
                            ),
                            "to_execute" => array(
                                "permissions_title_2"
                            )
                        ),
                        "1"    => array(
                            "name"       => "permissions_title_2",
                            "related"    => array(
                                "permissions_title_3"
                            ),
                            "to_execute" => array()
                        ),
                        )
                );
    }
}
```

**remove elements from left menu**

in a module, to remove elements from the left menu use this snippet: 
```
if ( ! function_exists( "ModuleName_building_removed_left_menu" ) ) {
    function ModuleName_building_removed_left_menu() {
        return array( 
                    "elementToRemove1" => array('name' => 'dashboard'),
                    "elementToRemove2" => array('name' => 'members')  
                ); 
    }
}
```
in this case dashboard and members will be removed from left menu
**add elements to settings/tabs**
to add elements in the top of settings/tabs, use this snippet:
```
if ( ! function_exists( "ModuleName_building_top_settings_tabs_list" ) ) {
    function ModuleName_building_top_settings_tabs_list() {
        return array(
            "tabToAdd1" => array( "url" => "moduleName/tabs", "name" => "ModuleNameTabs" ),
            "tabToAdd2" => array( "url" => "moduleName/tabs1", "name" => "ModuleNameTabs1" )
        );
    }
}
```
to add elements in the bottom of settings/tabs use this snippet:
```
if ( ! function_exists( "ModuleName_building_bottom_settings_tabs_list" ) ) {
    function ModuleName_building_bottom_settings_tabs_list() {
        return array(
            "tabToAdd1" => array( "url" => "moduleNameController", "name" => "ModuleNameTabs" )
        );
    }
}
```
when the settings/tab element that we added is selected, we cant highlight the settings row in the left menu using:
```
if(!function_exists("ModuleName_building_settings_tabs_list_contain")) {
    function ModuleName_building_settings_tabs_list_contain() {
        return array("tabToAdd1" => "moduleNameController");
    }
}
```
you can also remove elements from settings/tabs using:
```
if (!function_exists('ModuleName_building_removed_settings_tabs_list')) {
	function ModuleName_building_removed_settings_tabs_list() {
		return array(
			'general',
			'email_settings',
			'email_templates',
			'roles',
			'websocket',
			'notification_settings',
			'rsa_keys',
			'rest_keys',
			'modules_manager',
		);
	}
}
```
**remove elements from settings/tabs**
for the tabs in settings menu, you can remove whatever element you want using:
```
if ( ! function_exists( "ModuleName_building_removed_settings_tabs_list" ) ) {
    function ModuleName_building_removed_settings_tabs_list() {
        return array(
            "tabToRemove1" => array('name' => 'general'),
            "tabToRemove2" => array('name' => 'websocket')
        );
    }
}
```
the above snippet will remove general & websocket tab

**email template**
to create an email template for a module use:
```
if ( ! function_exists( "ModuleName_building_email_templates" ) ) {
    function ModuleName_building_email_templates() {
        return array(
            "email_module" => array("USER_FIRST_NAME", "USER_LAST_NAME", "SIGNATURE")
        );
}
```
also you have to create a row in `email_templates` table:
```
            email_templates
|`template_name`|`email_subject`|                   `default_message`                       |`custom_message`|`module_name`|
|  email_module | email_module  |Hello {USER_FIRST_NAME}, &nbsp;{USER_LAST_NAME} {SIGNATURE}|                | `ModuleName`|

```
NB:
 * `default_message` can contain html and css but make sure that it contain {USER_FIRST_NAME}, {USER_LAST_NAME} and {SIGNATURE}
 * *USER_FIRST_NAME*, *USER_LAST_NAME* and *SIGNATURE* should be replaced with the variable name used in the array in *ModuleName_building_email_templates* function