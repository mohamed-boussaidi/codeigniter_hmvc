<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * manage dates functions
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

if (!function_exists("to_sql_date")) {
    /**
     * convert date to sql format
     * @param string $old_date
     * @param bool $time
     * @return false|string
     */
    function to_sql_date($old_date = "", $time = false)
    {
        $format = $time ? "Y-m-d H:i:s" : "Y-m-d";
        $old_date = str_replace("/", "-", $old_date);
        $old_date = str_replace(".", "-", $old_date);
        return @date($format, @strtotime($old_date));
    }
}

if (!function_exists("from_sql_date")) {
    /**
     * convert sql date to system date format
     * @param string $old_date
     * @param bool $time
     * @return false|string
     */
    function from_sql_date($old_date = "", $time = false)
    {
        $parse_date = date_parse($old_date);
        if ($old_date == '1970-01-01') {
            return '';
        }
        if ($parse_date['error_count'] > 0) {
            return "";
        }
        if(contains($old_date, "0000-00-00")) {
            $old_date = str_replace($old_date, "0000-00-00", "");
        }
        $format = get_setting("date_format");
        $format = $time ? $format." H:i:s" : $format;
        $format = str_replace("yyyy", "Y", $format);
        $format = str_replace("dd", "d", $format);
        $format = str_replace("mm", "m", $format);
        return empty($old_date) ? "" : @date($format, @strtotime($old_date));
    }
}

if (!function_exists('get_current_time')) {
    /**
     * get current utc time
     * @param string $format
     * @return string date
     */
    function get_current_time($format = "Y-m-d H:i:s")
    {
        $d = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
        $d->setTimeZone(new DateTimeZone(get_setting("timezone")));
        return $d->format($format);
    }
}

if(!function_exists("is_timestamp")) {
    /**
     * check if the passed $string is a valid timestamp
     * @param $string
     * @return bool
     */
    function is_timestamp($string)
    {
        if(is_numeric($string)) {
            try {
                new DateTime('@' . $string);
            } catch(Exception $e) {
                return false;
            }
            return true;
        }
        return false;
    }
}