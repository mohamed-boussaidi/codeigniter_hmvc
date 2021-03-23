<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * provide file managing methods
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

if (!function_exists('move_temp_file')) {
    /**
     * this method process 3 types of files
     * 1. direct upload
     * 2. move a uploaded file which has been uploaded in temp folder
     * 3. copy a text based image
     *
     * @param $file_name
     * @param $target_path
     * @param string $related_to
     * @param null $source_path
     * @return bool|string
     */
    function move_temp_file($file_name, $target_path, $related_to = "", $source_path = NULL)
    {
        //to make the file name unique we'll add a prefix
        $filename_prefix = $related_to . "_" . uniqid("file") . "-";
        //if not provide any source path we'll find the default path
        if (!$source_path) {
            $source_path = getcwd() . "/" . get_setting("temp_file_path") . $file_name;
        }
        //check destination directory. if not found try to create a new one
        if (!is_dir($target_path)) {
            if (!mkdir($target_path, 0777, true)) {
                die('Failed to create file folders.');
            }
        }
        //remove unsupported values from the file name
        $new_filename = $filename_prefix . preg_replace('/\s+/', '-', $file_name);
        //check the file type is data or file. then copy to destination and remove temp file
        if (starts_with($source_path, "data")) {
            copy_text_based_image($source_path, $target_path . $new_filename);
            return $new_filename;
        } else {
            if (file_exists($source_path)) {
                copy($source_path, $target_path . $new_filename);
                unlink($source_path);
                return $new_filename;
            }
        }
        return false;
    }
}

if (!function_exists('validate_date')) {
    /**
     * @param $date
     * @param string $format
     *
     * @return bool
     */
    function validate_date($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}

if (!function_exists('copy_text_based_image')) {
    /**
     * Convert to a file from text based image
     * @param string $source_path
     * @param string $target_path
     * @return INT file size
     */
    function copy_text_based_image($source_path, $target_path)
    {
        $buffer_size = 3145728;
        $byte_number = 0;
        //read the file
        $file_open = fopen($source_path, "rb");
        //open the new file location
        $file_write = fopen($target_path, "w");
        while (!feof($file_open)) {
            $byte_number += fwrite($file_write, fread($file_open, $buffer_size));
        }
        fclose($file_open);
        fclose($file_write);
        return $byte_number;
    }

}

if (!function_exists('remove_file_prefix')) {
    /**
     * remove file name prefix which was added by move_temp_file() method
     *
     * @param string $file_name
     * @return string $file_name
     */
    function remove_file_prefix($file_name = "")
    {
        return substr($file_name, strpos($file_name, "-") + 1);
    }
}