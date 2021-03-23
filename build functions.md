**top_bar_profile_menu**

used to change top right menu
the output is an array that contain 2 element (each one is a link)
e.g:
```
if (!function_exists('building_top_bar_profile_menu')) {
    function building_top_bar_profile_menu() {
        $member = get_logged_member();
        $profile = "<li>" . anchor(
                "members/view/" . get_property($member, "id") . "/general",
                "<i class='fa fa-user mr10'></i>" . plang('my_profile')
            ) . "</li>";
        $account = "<li>" . anchor(
                "members/view/" . get_property($member, "id") . "/account",
                "<i class='fa fa-key mr10'></i>" . plang('change_element', array("password"))
            ) . "</li>";
        return array(
            $profile,
            $account
        );
    }
}
```

**members_construct**

used to override the func construct() in members controller,
e.g:
```
if (!function_exists('minimal_members_building_members_construct')) {
	function minimal_members_building_members_construct() {
		redirect('forbidden');
	}
}
```
this will make members inacessible to everyone

**permissions_row**
used to add permission, the permission that are added should be added to 'permissions' table first
the output is an array that contain list of permissions, for more explanation you can refer to *README_MODULES.md*

//linked_checkbox_list
used to to define dependency between permissions defined in *permissions_row*, check *README_MODULES.md* for more explanation

**notify_to_types**
used to add types that should be notified
if (!function_exists('MODULE_building_notify_to_type')) {
    fucntion MODULE_building_notify_to_type() {
        return array(
          "MODULE" => array(
              "members",
              "admins"
          )
        );
    }
}

**email_templates**
used to add an email template, the html email template should be added to `email_templates` table 

//dashboard_elements
used to add elements to the dashboard,
the output is an array which contain 1 elements: 
'name' => '\n' . element_html_content . '\n' . element_js_content;

**members_widgets**  /members can be replaced by whatever name you want
the output is an array of eleements, each element is an array 
which resemble to

    ```
    array(
                "classes" => "col-md-6 col-sm-6",
                "icon" => "fa-user",
                "value" => count($active_members),
                "text" => plang("active_members"),
                "panel" => "sky",
                "url" => "members",
    )
    ```

**unset_session_userdata**
it will receive an array which contain list of element to be unset from session