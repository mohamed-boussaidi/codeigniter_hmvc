<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * provide ready html  components
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

if (!function_exists("dt_row_trigger")) {
    /**
     * return data table row trigger span
     * @return string
     */
    function dt_row_trigger()
    {
        return "<span id='dt-hide-show' class='fa fa-plus-circle'></span>";
    }
}

if (!function_exists('load_css')) {
    /**
     * link the css files
     * @param array $array
     * @return string css links
     */
    function load_css(array $array)
    {
        $css = "";
        foreach ($array as $uri) {
            if (contains("$uri", "#min")) {
                $uri = (ENVIRONMENT == "development") ? str_replace("#min", "min", $uri) : $uri;
            } else {
                $uri = (ENVIRONMENT == "development") ? str_replace(".min", "", $uri) : $uri;
            }
            $css .= "<link rel='stylesheet' type='text/css' href='" . base_url($uri) . "' />\n";
        }
        return $css;
    }
}

if (!function_exists('load_js')) {
    /**
     * link the javascript files
     * @param array $array
     * @return string js links
     */
    function load_js(array $array)
    {
        $js = "";
        foreach ($array as $uri) {
            if (contains("$uri", "#min")) {
                $uri = (ENVIRONMENT == "development") ? str_replace("#min", "min", $uri) : $uri;
            } else {
                $uri = (ENVIRONMENT == "development") ? str_replace(".min", "", $uri) : $uri;
            }
            $js .= "<script type='text/javascript'  src='" . base_url($uri) . "'></script>\n";
        }
        return $js;
    }
}

if (!function_exists('active_menu')) {
    /**
     * get the selected menu
     * @param array $contain
     * @param string $menu
     * @param array $submenu
     * @return string "active" mark the active page
     */
    function active_menu($contain = array(), $menu = "", $submenu = array())
    {
        $ci = get_instance(true);
        $controller_name = strtolower(get_class($ci));
        if (in_array($controller_name, $contain)) {
            return "active";
            //compare with controller name. if not found, check in submenu values
        } else if ($menu === $controller_name) {
            return "active";
        } else if (is_array($submenu)) {
            foreach ($submenu as $sub_menu) {
                if ($sub_menu['name'] === $controller_name) {
                    return "active";
                }
            }
        }
        return "";
    }
}

if (!function_exists('active_submenu')) {
    /**
     * get the selected submenu
     * @param string $submenu
     * @param boolean $is_controller
     * @return string "active" indecating the active sub page
     */
    function active_submenu($submenu = "", $is_controller = false)
    {
        $ci = &get_instance(true);
        //if submenu is a controller then compare with controller name, otherwise compare with method name
        if ($is_controller && $submenu === strtolower(get_class($ci))) {
            return "active";
        } else if ($submenu === strtolower($ci->router->method)) {
            return "active";
        }
        return "";
    }
}

if (!function_exists("make_linked_checkbox")) {
    /**
     * take building linked checkbox list result
     * and format it to a working js code
     * @param array $linked_checkbox_list
     * @return string
     */
    function make_linked_checkbox($linked_checkbox_list = array())
    {
        $main = "";
        $to_executes = "";
        foreach ($linked_checkbox_list as $linked_checkbox_element) {
            foreach ($linked_checkbox_element as $key => $linked_checkbox) {
                if ($key == "main") {
                    $main .= "linked_checkbox('";
                    if (isset($linked_checkbox["name"])) $main .= $linked_checkbox["name"];
                    else $main .= "";
                    $main .= "',[";
                    if (isset($linked_checkbox["related"])) {
                        foreach ($linked_checkbox["related"] as $related) {
                            $main .= "'$related',";
                        }
                    }
                    $main .= "],[";
                    if (isset($linked_checkbox["to_execute"])) {
                        foreach ($linked_checkbox["to_execute"] as $to_execute) {
                            $main .= "$to_execute,";
                        }
                    }
                    $main .= "]);";
                } else {
                    if (isset($linked_checkbox["name"])) {
                        $to_executes .= "var " . $linked_checkbox["name"] . " = function() {linked_checkbox('";
                        if (isset($linked_checkbox["name"])) $to_executes .= $linked_checkbox["name"];
                        $to_executes .= "',[";
                        if (isset($linked_checkbox["related"])) {
                            foreach ($linked_checkbox["related"] as $related) {
                                $to_executes .= "'$related',";
                            }
                        }
                        $to_executes .= "],[";
                        if (isset($linked_checkbox["to_execute"])) {
                            foreach ($linked_checkbox["to_execute"] as $to_execute) {
                                $to_executes .= "$to_execute,";
                            }
                        }
                        $to_executes .= "]);};";
                    }
                }
            }
        }
        return $to_executes . $main;
    }
}

if (!function_exists("simple_file_upload")) {
    /**
     * inject simple js file upload
     * @param string $upload_but
     * @param string $browse_but
     * @param string $upload_input
     * @param string $uri
     * @param string $form
     * @param string $file_name
     */
    function simple_file_upload($upload_but = "", $browse_but = "", $upload_input = "", $uri = "", $form = "", $file_name = "file")
    {
        echo "
            $(\"#$upload_but\").click(function () {
                $(\"#$browse_but\").click();
                $(\"#$browse_but\").change(function () {
                    $.ajax({
                        url: \"" . get_uri($uri) . "\",
                        data: { $file_name: $(\"#$browse_but\").val()},
                        cache: false,
                        type: 'POST',
                        dataType: \"json\",
                        success: function (response) {
                            if (response.success) {
                                $(\"#$upload_but\").css(\"background\",\"#c1f1c1\");
                                $(\"#$upload_input\").val($(\"#$browse_but\").val().replace(/^.*\\\\/, \"\"));
                            } else {
                                appAlert.error(\"" . lang('invalid_file_type') . " \" + $(\"#$browse_but\").val().replace(/^.*\\\\/, \"\"), {duration: 3000});
                                $(\"#$upload_input\").val(\"\");
                                $(\"#$upload_but\").css(\"background\",\"#ffd3d3\");
                            }
                            $(\"#$form\").validate().element(\"#$upload_input\");
                        }
                    });
                });
            });
        ";
    }
}

if (!function_exists('full_simple_file_upload')) {
    /**
     * exactly the same work of the simple file upload function
     * with the form fields pre defined
     * @param string $file_field_name
     * @param string $uri
     * @param string $form
     * @param string $label
     * @param string $value
     * @return string
     */
    function full_simple_file_upload($file_field_name = 'file', $uri = "", $form = "", $label = 'file', $value = '')
    {
        $form_field = form_input(
            array(
                "id" => "{$file_field_name}_path",
                "name" => "{$file_field_name}_path",
                "value" => "{$value}",
                "class" => "form-control",
                "placeholder" => plang('file_path'),
                "data-rule-required" => true,
                "data-msg-required" => plang("field_required", array("file_path")),
            )
        );
        $uri = get_uri($uri);
        $upload_but = "{$file_field_name}_path";
        $browse_but = "{$file_field_name}_brows_btn";
        $upload_input = "{$file_field_name}_path";
        $label = lang($label);
        return <<<HTML
<div class="form-group">
        <label for="{$file_field_name}_path" class=" col-md-3 col-xs-12">{$label}</label>
        <div class=" col-md-9 col-xs-12">
            {$form_field}
        </div>
        <div class="col-md-1 col-xs-1">
            <input name="{$file_field_name}" type="file" id="{$file_field_name}_brows_btn" style="display: none">
        </div>
    </div>
    <script>
    $(document).on("focusin", "#{$file_field_name}_path", function() {
                $(this).prop('readonly', true);
            });
            $(document).on("focusout", "#{$file_field_name}_path", function() {
                $(this).prop('readonly', false);
            });
      $("#{$upload_but}").click(function () {
                $("#{$browse_but}").click();
                $("#{$browse_but}").change(function () {
                    $.ajax({
                        url: "{$uri}",
                        data: { 'file': $("#{$browse_but}").val()},
                        cache: false,
                        type: 'POST',
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                let file = $("#{$browse_but}").val();
                                $("#{$upload_but}").css("background","#c1f1c1");
                                $("#{$upload_input}").val(file);
                            } else {
                                $("#{$upload_input}").val("");
                                $("#{$upload_but}").css("background","#ffd3d3");
                            }
                            $("#{$form}").validate().element("#{$upload_input}");
                        }
                    });
                });
            });
</script>
HTML;

    }
}

if (!function_exists('simple_file_upload_execution_with_edit')) {
    /**
     * if request is edit check if the file
     * modified then run the upload
     * @param string $file
     * @param array $extensions
     * @param string $folder
     * @param bool $random_name
     * @param string $old_file_name
     * @return bool|string
     */
    function simple_file_upload_execution_with_edit($file = '', $extensions = array(), $folder = '', $random_name = false, $old_file_name = '') {
        $run_upload = false;
        if (empty($old_file_name)) {
            $run_upload = true;
        } else {
            $ci = get_instance(true);
            if ($old_file_name !== $ci->input->post($file . '_path')) {
                $run_upload = true;
            }
        }
        if ($run_upload) {
            $target_path = getcwd() . '/' . $folder . '/' . $old_file_name;
            @unlink($target_path);
            return simple_file_upload_execution(
                $file,
                $extensions,
                $folder,
                $random_name
            );
        }
        return $old_file_name;
    }
}

if (!function_exists('simple_file_upload_execution')) {
    /**
     * validate and execute simple file upload
     * do not forgot to add extension verification method to the controller
     * @param string $file
     * @param array $extensions
     * @param string $folder
     * @param bool $random_name
     * @return bool
     */
    function simple_file_upload_execution($file = '', $extensions = array(), $folder = '', $random_name = false)
    {
        validate_submitted_data(array($file . '_path'));
        if (!empty($_FILES) && isset($_FILES[$file])) {
            $temp_file = $_FILES[$file]['tmp_name'];
            $file_name = $_FILES[$file]['name'];
            if (count($extensions)) {
                if (!is_valid_file_to_upload($file_name, $extensions)) {
                    jerror();
                    die();
                }
            }
            $target_path = getcwd() . '/' . $folder . '/';
            if (!is_dir($target_path)) {
                if (!mkdir($target_path, 0777, true)) {
                    jerror();
                    die();
                }
            }
            $file_name = $random_name ?
                (uniqid(substr($file_name, 0, 5)) . '.' .
                    strtolower(pathinfo($file_name, PATHINFO_EXTENSION))) :
                $file_name;
            $target_file = $target_path . $file_name;
            if (@copy($temp_file, $target_file)) {
                return $file_name;
            } else {
                jerror();
                die();
            }
        } else {
            jerror();
            die();
        }
    }
}

if (!function_exists("sidebar_menu_items")) {
    /**
     * return  the side bar menu items
     * @param array $sidebar_menu
     * @return string
     */
    function sidebar_menu_items($sidebar_menu = array())
    {
        $html = "";
        foreach ($sidebar_menu as $main_menu) {
            $submenu = get_array_value($main_menu, 'submenu');
            $expend_class = $submenu ? ' expand ' : '';
            $name = $main_menu['name'];
            $label = (isset($main_menu['label']) && !empty($main_menu['label'])) ? $main_menu['label'] : $main_menu['name'];
            $contain = isset($main_menu['contain']) ? $main_menu['contain'] : array();
            $active_class = active_menu($contain, $name, $submenu);
            $submenu_open_class = '';
            if ($expend_class && $active_class) {
                $submenu_open_class = ' open ';
            }
            $submenu_is_a_controller = false;
            if ($main_menu['name'] === 'settings') {
                $submenu_is_a_controller = true;
            }
            $submenu_is_a_controller = isset($main_menu["submenu_is_a_controller"]) ? true : $submenu_is_a_controller;
            $submenu_parent = $submenu ? "submenu-parent" : "";
            $submenu_parent_url = $submenu ? " href='#' onclick='return false;' " : " href='" . get_uri($main_menu['url']) . "' ";
            $devider_class = get_array_value($main_menu, 'devider') ? 'devider' : '';
            $badge = get_array_value($main_menu, 'badge');
            $badge_class = get_array_value($main_menu, 'badge_class');
            $html .= "\n\n<li class='$active_class $expend_class $submenu_open_class $devider_class $submenu_parent main'>";
            $html .= "<a $submenu_parent_url>";
            $html .= "<i class='fa $main_menu[class] '></i>";
            $html .= "<span>" . plang($label) . "</span>";
            $html .= $badge ? "<span class='badge $badge_class'>$badge</span>" : "";
            $html .= "</a>";
            if ($submenu) {
                $html .= '<ul>';
                foreach ($submenu as $s_menu) {
                    $label = (isset($s_menu['label']) && !empty($s_menu['label'])) ? $s_menu['label'] : $s_menu['name'];
                    $active_class = active_submenu($s_menu["name"], $submenu_is_a_controller);
                    $html .= "\n\n\t<li class='" . $active_class . "'>";
                    $html .= "<a href='" . get_uri($s_menu['url']) . "'>";
                    $html .= "<i class='dot fa fa-circle'></i>";
                    $html .= "<span>" . plang($label) . "</span>";
                    $html .= "</a></li>";
                }
                $html .= "</ul>";
            }
            $html .= "</li>";
        }
        return $html;
    }
}

if (!function_exists("select2_dropdown")) {
    /**
     * get select 2 tags data input
     * @param array $data
     * @param array $id
     * @param array $text
     * @param bool $translation
     * @param string $prefix
     * @param string $postfix
     * @param bool $required
     * @return bool|string
     */
    function select2_dropdown($data = array(), $id = array(), $text = array(), $translation = false, $prefix = '', $postfix = '', $required = false)
    {
        if (!is_array($id) || !is_array($text)) {
            return false;
        }
        if (!count($id) || !count($text)) {
            return false;
        }
        $result = array();
        if (!$required) {
            $result[] = array("id" => '0', "text" => '-');
        }
        foreach ($data as $datum) {
            $element_id = "";
            foreach ($id as $item) {
                $element_id .= get_property($datum, $item) . " ";
            }
            $element_id = trim($element_id);
            $element_text = "";
            foreach ($text as $item) {
                $element_text .= get_property($datum, $item) . " ";
            }
            $element_text = trim($element_text);
            $element_text = $translation ? plang($prefix . $element_text . $postfix) : $element_text;
            if (!empty($element_id)) {
                $result[] = array("id" => $element_id, "text" => $element_text);
            }
        }
        return json_encode($result);
    }
}

if (!function_exists("tabs_list")) {
    /**
     * return parsed li list
     * to use it as a html tabs
     * @param $active_tab
     * @param array $tabs
     * @return string
     */
    function tabs_list($active_tab, $tabs = array())
    {
        $html = "";
        foreach ($tabs as $tab) {
            $url = $tab["url"];
            $name = isset($tab["name"]) ? $tab["name"] : $url;
            $class = ($active_tab == $name) ? 'active' : '';
            $html .= "<li role='presentation' class='$class'>";
            $html .= "<a href='" . get_uri($url) . "'>" . plang($name) . "</a></li>";
        }
        return $html;
    }
}

if (!function_exists("cropbox_file")) {
    /**
     * return a ready to use
     * file input that will
     * be used in the cropbox
     * options passed as string
     * separated by ,
     * js lib
     * @param string $name
     * @return string
     */
    function cropbox_file($name = "")
    {
        $options = explode(",", $name);
        $name = isset($options[0]) ? $options[0] : "";
        $width = isset($options[1]) ? $options[1] : "";
        $height = isset($options[2]) ? $options[2] : "";
        $html = "";
        $html .= "<input id='{$name}_file' class='upload' ";
        $html .= "type='file' data-height='$height' data-width='$width' ";
        $html .= "data-preview-container='#{$name}_preview'";
        $html .= "data-input-field='#$name'/>";
        return $html;
    }
}

if (!function_exists('get_avatar')) {
    /**
     * get the url of member avatar
     *
     * @param string $image_name
     * @param bool|string $path
     *
     * @return string url of the avatar of given image reference
     */
    function get_avatar($image_name = "", $path = false)
    {
        if ($image_name) {
            $path = $path ? "files/$path" : get_setting("profile_file_path");
            return base_url($path) . "/" . $image_name;
        } else {
            return base_url("assets/images/avatar.jpg");
        }
    }
}

if (!function_exists('get_thumbnail')) {
    /**
     * get the url of a specific thumbnail
     *
     * @param string $image_name
     * @param bool|string $path
     *
     * @return string url of the avatar of given image reference
     */
    function get_thumbnail($image_name = "", $path = false)
    {
        if ($image_name) {
            $path = $path ? $path : "files/thumbnails";
            if (file_exists(getcwd() . '/' . $path . '/' . $image_name)) {
                return base_url($path) . "/" . $image_name;
            }
        }
        return base_url("assets/images/thumbnail.png");
    }
}

if (!function_exists("profile_menu")) {
    /**
     * return the profile menu
     * @return array
     */
    function profile_menu()
    {
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

if (!function_exists("view")) {
    /**
     * load a page with in
     * the global template
     * @param $view
     * @param array $data
     * @param bool $return
     * @return object|string
     */
    function view($view, $data = array(), $return = FALSE)
    {
        $ci = get_instance(true);
        $template_uri = build("template_uri");
        if (isset($template_uri["uri"])) {
            $template_uri = $template_uri["uri"];
        } else {
            $template_uri = "";
        }
        $data["view"] = $view;
        return $ci->load->view("$template_uri/template/layout", $data, $return);
    }
}

if (!function_exists("permission_html_line")) {
    /**
     * return setting permission line
     *
     * @param string $title
     * @param array $permissions
     *
     * @return string
     */
    function permission_html_line($title = "", $permissions = array())
    {
        $permission_id = permission_id($title);
        $html = '<div id="' . $title . '-row">';
        $html .= '<div class="inline-block">';
        $html .= form_checkbox(
            array(
                "id" => "$title",
                "name" => "permissions[]",
                "checked" => have_permission($permission_id, $permissions),
                "value" => $permission_id
            )
        );
        $html .= '</div>';
        $html .= '<label for="' . $title . '">';
        $html .= "&nbsp&nbsp" . plang("$title");
        $html .= '</label>';
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists("webSocket_event")) {
    /**
     * execute websocket event
     * @param string $event
     * @param string $message
     * @return string
     */
    function webSocket_event($event = "", $message = "")
    {
        echo '<script language="javascript">';
        echo '$(document).ready(function() {';
        echo 'websocket.addEvent("' . $event . '", "' . $message . '");';
        echo '});';
        echo '</script>';
    }
}