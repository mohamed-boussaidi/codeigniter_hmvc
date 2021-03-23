<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

if (!function_exists('get_uri')) {
    /**
     * prepare uri
     * @param string $uri
     * @return string full url
     */
    function get_uri($uri = "")
    {
        $ci = get_instance(true);
        $index_page = $ci->config->item('index_page');
        return base_url($index_page . '/' . $uri);
    }
}

if (!function_exists('ajax_anchor')) {
    /**
     * prepare a anchor tag for ajax request
     * @param string $url
     * @param string $title
     * @param array|string $attributes
     * @return string link of anchor tag
     */
    function ajax_anchor($url, $title = '', $attributes = '')
    {
        $attributes["data-act"] = "ajax-request";
        $attributes["data-action-url"] = $url;
        return js_anchor($title, $attributes);
    }

}

if (!function_exists('ajax_anchor_msg')) {
    /**
     * prepare a anchor tag for ajax request
     * @param string $url
     * @param string $title
     * @param array|string $attributes
     * @return string link of anchor tag
     */
    function ajax_anchor_msg($url, $title = '', $attributes = '')
    {
        $attributes["data-act"] = "ajax-request-msg";
        $attributes["data-action-url"] = $url;
        return js_anchor($title, $attributes);
    }

}

if (!function_exists('link_it')) {
    /**
     * convert simple link text to clickable link
     * @param string $text
     * @return html link
     */
    function link_it($text)
    {
        return preg_replace(
            '@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@',
            '<a href="$1" target="_blank">$1</a>',
            $text
        );
    }

}

if (!function_exists("apppath_url")) {
    /**
     * returned the app path
     * joined with the uri
     * passed as arg
     * @param string $uri
     * @return string
     */
    function apppath_url($uri = "")
    {
        return APPPATH . $uri;
    }
}

if (!function_exists("files_url")) {
    /**
     * return files url
     * @param string $dir
     * @param string $uri
     * @return string
     */
    function files_url($dir = "", $uri = "")
    {
        return base_url(get_setting($dir . "_file_path") . $uri);
    }
}

if (!function_exists('to_url')) {
    /**
     * convert string to url
     * @param string $address
     * @return string url
     */
    function to_url($address = "")
    {
        if (strpos($address, 'http://') === false &&
            strpos($address, 'https://') === false) {
            $address = "https://" . $address;
        }
        return $address;
    }
}

if (!function_exists('modal_anchor')) {
    /**
     * prepare a anchor tag for modal
     * @param string $url
     * @param string $title
     * @param array $attributes
     * @return string html link of anchor tag
     */
    function modal_anchor($url, $title = '', $attributes = array())
    {
        $attributes["data-act"] = "ajax-modal";
        if (get_array_value($attributes, "data-modal-title")) {
            $attributes["data-title"] = get_array_value($attributes, "data-modal-title");
        } else {
            $attributes["data-title"] = get_array_value($attributes, "title");
        }
        $attributes["data-action-url"] = $url;
        return js_anchor($title, $attributes);
    }
}

if (!function_exists('js_anchor')) {
    /**
     * prepare a anchor tag for any js request
     * @param string $title
     * @param array|string $attributes
     * @return string html link of anchor tag
     */
    function js_anchor($title = '', $attributes = '')
    {
        $title = (string)$title;
        $html_attributes = "";
        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $html_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }
        return '<a href="#"' . $html_attributes . '>' . $title . '</a>';
    }
}

if(!function_exists("module_url")) {
    /**
     * return a specific module url
     * @param string $module
     * @param string $uri
     * @param bool $base_url
     * @return string
     */
    function module_url($module = "", $uri = "", $base_url = true) {
        $uri = "application/modules/$module/$uri";
        return $base_url ? base_url($uri) : $uri;
    }
}

if(!function_exists("modules_assets")) {
    /**
     * return the module url for assets
     * @param string $module
     * @param string $uri
     * @param bool $base_url
     * @return string
     */
    function modules_assets($module = "", $uri = "", $base_url = true) {
    	if(starts_with($uri, "http")) {
    		return $uri;
	    }
        return module_url($module, "assets/$uri", $base_url);
    }
}