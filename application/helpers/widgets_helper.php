<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

if (!function_exists('social_links_widget')) {
    /**
     * get social links widget
     * @param object $weblinks
     * @return string html
     */
    function social_links_widget($weblinks)
    {
        $social_link_icons = array(
            "facebook" => "facebook",
            "twitter" => "twitter",
            "linkedin" => "linkedin",
            "googleplus" => "google-plus",
            "digg" => "digg",
            "youtube" => "youtube",
            "pinterest" => "pinterest",
            "instagram" => "instagram",
            "github" => "github",
            "tumblr" => "tumblr",
            "vine" => "vine",
        );
        $links = "";
        foreach ($social_link_icons as $key => $icon) {
            if (isset($weblinks->$key) && $weblinks->$key) {
                $address = to_url($weblinks->$key); //check http or https in url
                $links .= "<a target='_blank' href='$address' class='social-link fa fa-$icon'></a>";
            }
        }
        return $links;
    }
}

if (!function_exists("building_members_widgets")) {
    function building_members_widgets($member = stdClass::class)
    {
        $ci = get_instance(true);
        $inactive_members = $ci->Members_model->get_all_where(array("status" => "inactive"));
        $active_members = $ci->Members_model->get_all_where(array("status" => "active"));
        return array(
            array(
                "classes" => "col-md-6 col-sm-6",
                "icon" => "fa-user",
                "value" => count($active_members),
                "text" => plang("active_members"),
                "panel" => "sky",
                "url" => "members",
            ),
            array(
                "classes" => "col-md-6 col-sm-6",
                "icon" => "fa-user-times",
                "value" => count($inactive_members),
                "text" => plang("inactive_members"),
                "panel" => "coral",
                "url" => "members",
            ),
        );
    }
}

if (!function_exists("building_dashboard_elements")) {
    function building_dashboard_elements($member = stdClass::class)
    {
        $sticky_note = join("\n", array(
            "<div class='row'>",
            "<div class='col-md-12 widget-container'>",
            "<div class='panel panel-default'>",
            "<div class='panel-heading'>",
            "<i class='fa fa-book'></i> ".plang("sticky_note")."</div>",
            "<div id='upcoming-event-container'>",
            "<textarea name='note' cols='40' rows='10' id='sticky-note' class='sticky-note'>".
            get_property($member, "sticky_note").
            "</textarea>",
            "</div>",
            "</div>",
            "</div>",
            "</div>",
        ));
        $sticky_note_script = join("\n", array(
            "<script type='text/javascript'>",
            "$(document).ready(function() {",
            "$('#sticky-note').change(function() {",
            "$.ajax({",
            "url: '".base_url("dashboard/save_sticky_note")."',",
            "data: {sticky_note: $(this).val()},",
            "cache: false,",
            "type: 'POST'",
            "});",
            "});",
            "$('#sticky-note').slimscroll({",
            "width: '100%',",
            "borderRadius: '0',",
            "color: '#ccc',",
            "allowPageScroll: true",
            "});",
            "});",
            "</script>",
        ));
        return array(
            "sticky_note" => $sticky_note . $sticky_note_script
        );
    }
}