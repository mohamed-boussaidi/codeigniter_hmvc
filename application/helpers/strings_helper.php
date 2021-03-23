<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

if (!function_exists("concat")) {
    /**
     * join array elements
     * with empty string
     * as glue
     * @param array $array
     * @return string
     */
    function concat($array = array())
    {
        return join("", $array);
    }
}

if (!function_exists('starts_with')) {
    /**
     * check if a string starts with a specified sting
     * @param string $string
     * @param string $needle
     * @return true/false
     */
    function starts_with($string, $needle)
    {
        return $needle === "" || strrpos($string, $needle, -strlen($string)) !== false;
    }
}

if (!function_exists('ends_with')) {
    /**
     * check if a string ends with a specified sting
     * @param string $string
     * @param string $needle
     * @return true/false
     */
    function ends_with($string, $needle)
    {
        return $needle === "" ||
            (($temp = strlen($string) - strlen($string)) >= 0 && strpos($string, $needle, $temp) !== false);
    }
}

if (!function_exists('contains')) {
    /**
     * check if a string
     * contains another string
     * and return a boolean
     * value
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function contains($haystack = "", $needle = "")
    {
        return (strpos($haystack, $needle) !== false) ? true : false;
    }
}

if (!function_exists('getStringBefore')) {
    /**
     * Given a string $haystack, remove everything before $delimiter ($delimiter
     * including) and return the string before $delimiter or return false if
     * $delimiter is not found in $haystack. Return false if $haystack or $delimiter
     * is empty. Return false if $haystack does not contains $delimiter.
     * @param string $haystack
     * @param string $delimiter
     * @return mixed string or false
     */
    function getStringBefore($haystack, $delimiter)
    {
        if (!empty($haystack) && !empty($delimiter)) {
            if (strpos($haystack, $delimiter) !== false) {
                // separate $haystack in two strings and put each string in an array
                $filter = explode($delimiter, $haystack);
                if (isset($filter[0])) {
                    // return the string before $delimiter
                    return $filter[0];
                }
                return false;
            }
            return false;
        }
        return false;
    }
}

if (!function_exists('getStringBetween')) {
    /**
     * Given a string $haystack, remove everything before $delimiter1 ($delimiter1
     * including), and remove everything after $delimiter2 ($delimiter2 including).
     * Return the string between $delimiter1 and $delimiter2 or return false if
     * $delimiter1 or $delimiter2 is not found in $haystack.  Return false if
     * $haystack or $delimiter1 or $delimiter2 is empty. Return false if
     * $haystack does not contains $delimiter1 and $delimiter2.
     *
     * @param string $haystack
     * @param string $delimiter1
     * @param string $delimiter2
     * @return mixed string or false
     */
    function getStringBetween($haystack, $delimiter1, $delimiter2)
    {
        if (!empty($haystack) && !empty($delimiter1) && !empty($delimiter2)) {
            if (strpos($haystack, $delimiter1) !== false && strpos($haystack, $delimiter2) !== false) {
                // separate $haystack in two strings and put each string in an array
                $pre_filter = explode($delimiter1, $haystack);
                if (isset($pre_filter[1])) {
                    // remove everything after the $delimiter2 in the second line of the
                    // $pre_filter[] array
                    $post_filter = explode($delimiter2, $pre_filter[1]);
                    if (isset($post_filter[0])) {
                        // return the string between $delimiter1 and $delimiter2
                        return $post_filter[0];
                    }
                    return false;
                }
                return false;
            }
            return false;
        }
        return false;
    }
}

if (!function_exists('getStringAfter')) {
    /**
     * Given a string $haystack, remove everything before $delimiter ($delimiter
     * including) and return the string after $delimiter or return false if
     * $delimiter is not found in $haystack.  Return false if $haystack or
     * $delimiter is empty. Return false if $haystack does not contains $delimiter.
     *
     * @param string $haystack
     * @param string $delimiter
     * @return mixed string or false
     */
    function getStringAfter($haystack, $delimiter)
    {
        if (!empty($haystack) && !empty($delimiter)) {
            if (strpos($haystack, $delimiter) !== false) {
                // separate $haystack in two strings and put each string in an array
                $filter = explode($delimiter, $haystack);
                if (isset($filter[1])) {
                    // return the string after $delimiter
                    return $filter[1];
                }
                return false;
            }
            return false;
        }
        return false;
    }
}

if (!function_exists('getStringWithout')) {
    /**
     * Given a string haystack, remove every occurrence of the string $needle in
     * $haystack and return the result string. Return false if $haystack or
     * $needle is empty. Return false if $haystack does not contains $needle.
     *
     * @param string $haystack
     * @param string $needle
     * @return mixed string or false
     */
    function getStringWithout($haystack, $needle)
    {
        if (!empty($haystack) && !empty($needle)) {
            if (strpos($haystack, $needle) !== false) {
                return str_replace($needle, '', $haystack);
            }
            return false;
        }
        return false;
    }
}

if (!function_exists("get_property")) {
    /**
     * return property value or empty
     * string
     * @param string $obj
     * @param string $property
     * @param string $output
     * @return string
     */
    function get_property($obj = stdClass::class, $property = "", $output = "")
    {
        if (gettype($obj) === "object") {
            return property_exists($obj, $property) ?
                (($obj->$property == "null") || ($obj->$property == "NULL") || ($obj->$property == null)) ?
                    $output : $obj->$property :
                $output;
        }
        return "";
    }
}

if (!function_exists("get_random_string")) {
    /**
     * Generate a random string
     * @param int $length
     * @param bool $alpha
     * @return string
     */
    function get_random_string($length = 8, $alpha = false)
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789*/.-+%_@";
        $alphabet = !$alpha ? $alphabet : "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}

/**
 * convert a number to currency format
 *
 * @param number $number
 * @param string $currency
 * @return number with currency symbol
 */
if (!function_exists('to_currency')) {
    function to_currency($number = 0, $currency = "")
    {
        $thous_separator = get_setting("thous_separator");
        $currency_number = get_setting("currency_number");
        $decimal_separator = get_setting("decimal_separator");
        $negative_sign = "";
        if ($number < 0) {
            $number = $number * -1;
            $negative_sign = "-";
        }
        return $negative_sign .
            "{$currency} " .
            number_format(
                $number,
                $currency_number,
                $decimal_separator,
                $thous_separator
            );
    }

}

/**
 * convert a number to quantity format
 *
 * @param number $number
 * @return number
 */
if (!function_exists('to_decimal_format')) {
    function to_decimal_format($number = 0)
    {
        $decimal_separator = get_setting("decimal_separator");
        $decimal = 0;
        if (is_numeric($number) && floor($number) != $number) {
            $decimal = 2;
        }
        if ($decimal_separator === ",") {
            return number_format($number, $decimal, ",", ".");
        } else {
            return number_format($number, $decimal, ".", ",");
        }
    }
}

/**
 * convert a currency value to data format
 *
 * @param number $currency
 * @return number
 */
if (!function_exists('unformat_currency')) {
    function unformat_currency($currency = "")
    {
        // remove everything except a digit "0-9", a comma ",", and a dot "."
        $new_money = preg_replace('/[^\d,-\.]/', '', $currency);
        $decimal_separator = get_setting("decimal_separator");
        if ($decimal_separator === ",") {
            $new_money = str_replace(".", "", $new_money);
            $new_money = str_replace(",", ".", $new_money);
        } else {
            $new_money = str_replace(",", "", $new_money);
        }
        return $new_money;
    }
}