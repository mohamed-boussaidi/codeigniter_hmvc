<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * provide global functions
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */

if (!function_exists('get_countries')) {
    /**
     * return countries array with country id as key and country name as value
     * @return array
     */
    function get_countries()
    {
        $db_countries = $ci->Countries_model->get_all();
        $countries = array();
        foreach ($db_countries as $db_country) {
            $translation = plang("country_" . $db_country->code);
            $countries[$db_country->id] = $translation;
        }
        return $countries;
    }
}

if (!function_exists('get_country')) {
    /**
     * return a country based on the id
     * @param $country_id
     *
     * @return mixed
     */
    function get_country($country_id)
    {
        $countries = get_countries();
        return isset($countries[$country_id]) ? $countries[$country_id] : $country_id;
    }
}

if (!function_exists('get_array_value')) {
    /**
     * check the array key and return the value
     *
     * @param array $array
     * @param $key
     *
     * @return string extract array value safely
     */
    function get_array_value(array $array, $key)
    {
        return isset($array[$key]) ? $array[$key] : "";
    }
}

if (!function_exists('get_setting')) {
    /**
     * get the defined config value by a key
     *
     * @param string $key
     *
     * @return string|bool config value
     */
    function get_setting($key = "")
    {
        $ci = get_instance(true);

        return $ci->config->item($key);
    }
}

if (!function_exists('get_feature')) {
    /**
     * get the defined config value by a key
     *
     * @param string $key
     *
     * @return string|bool config value
     */
    function get_feature($key = "")
    {
        $ci = get_instance(true);

        $feature = $ci->config->item("features_configs_$key");

        return $feature != null ? $feature : false;
    }
}

if (!function_exists('get_system_feature')) {
    /**
     * get the defined config value by a key
     *
     * @param string $key
     *
     * @return string|bool config value
     */
    function get_system_feature($key = "")
    {
        $ci = get_instance(true);
        $system_feature = $ci->config->item("system_features_configs_$key");

        return $system_feature != null ? $system_feature : false;
    }
}

if (!function_exists("system_features_exists")) {
    /**
     * check if there is any system feature
     *
     * @return bool
     */
    function system_features_exists()
    {
        $ci = get_instance(true);
        foreach ($ci->config as $item) {
            if (contains($item, "system_features_configs_")) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists("module_have_features")) {
    /**
     * check if a module have any system feature
     *
     * @param string $module
     *
     * @return bool
     */
    function module_have_features($module = "")
    {
        if (file_exists(FCPATH . "/application/modules/$module/config/features.php")) {
            include FCPATH . "/application/modules/$module/config/features.php";
            if (isset($features)) {
                foreach ($features as $feature) {
                    if ($feature) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}

if (!function_exists("get_smtp_config")) {
    /**
     * return the smtp config
     * @return array
     */
    function get_smtp_config()
    {
        $email_config = Array(
            'charset' => 'utf-8',
            'mailtype' => 'html'
        );
        //check mail sending method from settings
        if (get_setting("email_protocol") == "smtp") {
            $email_config["protocol"] = "smtp";
            $email_config["smtp_host"] = get_setting("email_smtp_host");
            $email_config["smtp_port"] = get_setting("email_smtp_port");
            $email_config["smtp_user"] = get_setting("email_smtp_user");
            $email_config["smtp_pass"] = get_setting("email_smtp_pass");
            $email_config["smtp_crypto"] = get_setting("email_smtp_security_type");
            if (!$email_config["smtp_crypto"]) {
                $email_config["smtp_crypto"] = "tls";
            }
        }

        return $email_config;
    }
}

/**
 * create a encoded id for sequrity pupose
 *
 * @param string $id
 * @param string $salt
 *
 * @return endoded value
 */
if (!function_exists('encode_id')) {

    function encode_id($id, $salt)
    {
        $ci = get_instance();
        $id = $ci->encryption->encrypt($id . $salt);
        $id = str_replace("=", "~", $id);
        $id = str_replace("+", "_", $id);
        $id = str_replace("/", "-", $id);

        return $id;
    }

}


/**
 * decode the id which made by encode_id()
 *
 * @param string $id
 * @param string $salt
 *
 * @return decoded value
 */
if (!function_exists('decode_id')) {

    function decode_id($id, $salt)
    {
        $ci = get_instance();
        $id = str_replace("_", "+", $id);
        $id = str_replace("~", "=", $id);
        $id = str_replace("-", "/", $id);
        $id = $ci->encryption->decrypt($id);
        if ($id && strpos($id, $salt) !== false) {
            return str_replace($salt, "", $id);
        }
    }

}

if (!function_exists("upload_posted_file")) {
    /**
     * upload posted file
     * as text format
     *
     * @param string $name
     * @param string $extension
     * @param string $path
     *
     * @return bool|string
     */
    function upload_posted_file($name = "", $extension = "", $path = "")
    {
        $path = empty($path) ? get_setting("system_file_path") : $path;
        if (isset($_POST[$name]) && $_POST[$name] != "") {
            $full_name = !empty($extension) ? "$name.$extension" : "$name";

            return move_temp_file($full_name, $path, "", $_POST[$name]);
        }

        return false;
    }
}

if (!function_exists("separated_to_array")) {
    /**
     * convert separated string to array
     *
     * @param string $string
     * @param string $separator
     *
     * @return array|string
     */
    function separated_to_array($string = "", $separator = ",")
    {
        if (contains($string, $separator)) {
            $string = explode($separator, $string);
        } else {
            if (empty($string)) {
                $string = array();
            } else {
                $string = array($string);
            }
        }
        $array = array();
        foreach ($string as $element) {
            if (!empty($element)) {
                $array[] = $element;
            }
        }

        return $array;
    }
}

if (!function_exists('validate_submitted_data')) {
    /**
     * validate post data using the codeigniter's form validation method
     *
     * @param array $fields
     *
     * @return null throw error if found any mismatch
     */
    function validate_submitted_data($fields = array())
    {
        $ci = get_instance(true);
        foreach ($fields as $field_name => $requirement) {
            if (!contains($requirement, "trim")) {
                $requirement = "trim|$requirement";
            }
            $ci->form_validation->set_rules($field_name, $field_name, $requirement);
        }
        if ($ci->form_validation->run() == false) {
            if (ENVIRONMENT === 'production') {
                $message = plang('something_went_wrong');
            } else {
                $message = validation_errors();
            }
            echo json_encode(array("success" => false, 'message' => $message));
            exit();
        }
    }
}

if (!function_exists('decode_ajax_post_data')) {
    /**
     * decode html data which submited using a encode method of encodeAjaxPostData() function
     *
     * @param string $html
     *
     * @return htmle
     */
    function decode_ajax_post_data($html)
    {
        $html = str_replace("~", "=", $html);
        $html = str_replace("^", "&", $html);

        return $html;
    }
}

if (!function_exists("unique_update")) {
    /**
     * check if the value unique
     * without counting the old value
     *
     * @param string $table
     * @param string $model
     * @param string $id
     * @param string $field
     * @param string $value
     *
     * @return string
     */
    function unique_update($table = "", $model = "", $id = "", $field = "", $value = "")
    {
        $ci = get_instance(true);
        $table = $ci->db->dbprefix($table);
        $row = $ci->$model->get_one($id, true);
        $is_unique = "";
        if (get_property($row, $field) != $value) {
            $is_unique = "|is_unique[$table.$field]";
        }

        return $is_unique;
    }
}

if (!function_exists('force_download_and_continue')) {
    /**
     * Force Download
     *
     * Generates headers that force a download to happen
     *
     * @param    string    filename
     * @param    mixed    the data to be downloaded
     * @param    bool    whether to try and send the actual file MIME type
     * @return    void
     */
    function force_download_and_continue($filename = '', $data = '', $set_mime = FALSE)
    {
        if ($filename === '' OR $data === '') {
            return;
        } elseif ($data === NULL) {
            if (!@is_file($filename) OR ($filesize = @filesize($filename)) === FALSE) {
                return;
            }

            $filepath = $filename;
            $filename = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
            $filename = end($filename);
        } else {
            $filesize = strlen($data);
        }

        // Set the default MIME type to send
        $mime = 'application/octet-stream';

        $x = explode('.', $filename);
        $extension = end($x);

        if ($set_mime === TRUE) {
            if (count($x) === 1 OR $extension === '') {
                /* If we're going to detect the MIME type,
                 * we'll need a file extension.
                 */
                return;
            }

            // Load the mime types
            $mimes =& get_mimes();

            // Only change the default MIME if we can find one
            if (isset($mimes[$extension])) {
                $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
            }
        }

        /* It was reported that browsers on Android 2.1 (and possibly older as well)
         * need to have the filename extension upper-cased in order to be able to
         * download it.
         *
         * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
         */
        if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT'])) {
            $x[count($x) - 1] = strtoupper($extension);
            $filename = implode('.', $x);
        }

        if ($data === NULL && ($fp = @fopen($filepath, 'rb')) === FALSE) {
            return;
        }

        // Clean output buffer
        if (ob_get_level() !== 0 && @ob_end_clean() === FALSE) {
            @ob_clean();
        }

        // Generate the server headers
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $filesize);
        header('Cache-Control: private, no-transform, no-store, must-revalidate');

        // If we have raw data - just dump it
        if ($data !== NULL) {
            echo($data);
        } else {
            // Flush 1MB chunks of data
            while (!feof($fp) && ($data = fread($fp, 1048576)) !== FALSE) {
                echo $data;
            }

            fclose($fp);
            //exit;
        }


    }
}


if (!function_exists("saving_result")) {
    /**
     * Takes the $class and the $save_id, if the
     * record saved correctly inject a row in the datatables
     * else show an error message
     *
     * @param $class
     * @param $save_id
     * @param bool $data
     * @param bool $success
     * @param bool $fail
     * @param string $method
     * @param array $options
     *
     * @return bool
     */
    function saving_result($class, $save_id, $data = true, $success = false, $fail = false, $method = "make_row", $options = array())
    {
        if ($save_id) {
            if ($success) {
                call_user_func($success);
            }
            echo json_encode($data ?
                array(
                    "success" => true,
                    'id' => $save_id,
                    "data" => $class->$method(new stdClass(), $save_id),
                    'message' => plang('element_saved', array("record")),
                    'options' => $options
                ) :
                array(
                    "success" => true,
                    'id' => $save_id,
                    'message' => plang('element_saved', array("record")),
                    'options' => $options
                )
            );

            return $save_id;
        } else {
            if ($fail) {
                call_user_func($fail);
            }
            echo json_encode(array("success" => false, 'message' => plang('error_occurred')));

            return false;
        }
    }
}

if (!function_exists("not_empty_id")) {
    /**
     * check if a query result object have a valid ID,
     * if this is not the case show a 404 page
     *
     * @param string $object
     */
    function not_empty_id($object = stdClass::class)
    {
        if (!is_numeric(get_property($object, "id"))) {
            show_404();
        }
        if (get_property($object, "id") < 1) {
            show_404();
        }
        if (!is_object($object)) {
            show_404();
        }
        if (empty(get_property($object, "id"))) {
            show_404();
        }
    }
}

if (!function_exists("validate_image_file")) {
    /**
     * return true if the file ext == png or jpeg or jpg
     *
     * @param string $file_name
     *
     * @return bool
     */
    function validate_image_file($file_name = "")
    {
        if (!$file_name) {
            echo json_encode(array("success" => false, 'message' => lang('invalid_file_type') . " ($file_name)"));

            return false;
        }
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if ($file_ext === "png" || $file_ext === "jpeg" || $file_ext === "jpg") {
            echo json_encode(array("success" => true));

            return true;
        } else {
            echo json_encode(array("success" => false, 'message' => lang('invalid_file_type') . " ($file_name)"));

            return false;
        }
    }
}

if (!function_exists("not_empty_number")) {
    /**
     * if the passed number is not a numeric or
     * is not a positive number show 404 page
     *
     * @param string $number
     */
    function not_empty_number($number = "")
    {
        if (!is_numeric($number) || $number < 1) {
            show_404();
        }
    }
}

if (!function_exists("is_email")) {
    /**
     * check if a string is a valid email
     *
     * @param string $email
     *
     * @return bool
     */
    function is_email($email = "")
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}

if (!function_exists('send_app_mail')) {
    /**
     * send mail
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param array $options
     *
     * @return true/false
     */
    function send_app_mail($to, $subject, $message, $options = array())
    {
        $ci = get_instance(true);
        $ci->email->initialize(get_smtp_config());
        $ci->email->clear();
        $ci->email->set_newline("\r\n");
        $ci->email->to($to);
        $ci->email->subject($subject);
        $ci->email->message($message);

        //add attachment
        $attachments = get_array_value($options, "attachments");
        if (is_array($attachments)) {
            foreach ($attachments as $value) {
                $ci->email->attach(trim($value));
            }
        }

        //check cc
        $cc = get_array_value($options, "cc");
        if ($cc) {
            $ci->email->cc($cc);
        }

        $from_address = get_array_value($options, "from_address");
        $from_address = $from_address ? $from_address : get_setting("email_sent_from_address");
        $from_name = get_array_value($options, "from_name");
        $from_name = $from_name ? $from_name : get_setting("email_sent_from_name");
        $ci->email->from($from_address, $from_name);

        //check bcc
        $bcc = get_array_value($options, "bcc");
        if ($bcc) {
            $ci->email->bcc($bcc);
        }

        //send email
        if ($ci->email->send()) {
            return true;
        } else {
            //show error message in none production version
            if (ENVIRONMENT !== 'production') {
                show_error($ci->email->print_debugger());
            }

            return false;
        }
    }
}

if (!function_exists("send_app_mail_to_members")) {
    /**
     * execute send_app_mails on members ids array
     *
     * @param $ids
     * @param $subject
     * @param $message
     * @param array $options
     */
    function send_app_mail_to_members($ids, $subject, $message, $options = array())
    {
        $ci = get_instance(true);
        foreach ($ids as $id) {
            $member = $ci->Members_model->get_one($id);
            $email = get_property($member, "email");
            send_app_mail($email, $subject, $message, $options);
        }
    }
}

if (!function_exists("deleting_result")) {
    /**
     * Takes the $class and $deleted result, if the
     * record deleted correctly delete the row from the datatables
     * else show an error message
     *
     * @param $class
     * @param $deleted
     */
    function deleting_result($class, $deleted)
    {
        if ($deleted) {
            echo json_encode(array("success" => true, 'message' => plang('element_deleted', array("record"))));
        } else {
            echo json_encode(array("success" => false, 'message' => plang('error_occurred')));
        }
    }
}

if (!function_exists("array_to_object")) {
    /**
     * convert array to object
     *
     * @param array $array
     *
     * @return stdClass
     */
    function array_to_object($array = array())
    {
        $object = new stdClass();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $object->$key = $value;
            }
        }

        return $object;
    }
}

if (!function_exists("rrmdir")) {
    /**
     * recursively delete a directory and its entire contents
     *
     * @param $dir
     *
     * @return bool
     */
    function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            rmdir($dir);

            return true;
        }

        return false;
    }
}

if (!function_exists("jsuccess")) {
    /**
     * ready to use json success message
     * can implements a custom message
     *
     * @param bool|string $message
     *
     * @return bool
     */
    function jsuccess($message = false)
    {
        $message = $message ? $message : plang('element_updated', array("record"));
        echo json_encode(array("success" => true, 'message' => $message));

        return true;
    }
}

if (!function_exists("jerror")) {
    /**
     * ready to use json error message
     *
     * @param bool $message
     *
     * @return bool
     */
    function jerror($message = false)
    {
        $message = $message ? $message : 'error_occurred';
        echo json_encode(array("success" => false, 'message' => plang($message)));

        return false;
    }
}

if (!function_exists("jsuccess_or_jerror")) {
    /**
     * return a jsuccess or jerror
     * depends on the passed condition
     *
     * @param bool $condition
     * @param bool|string $message
     *
     * @return bool
     */
    function jsuccess_or_jerror($condition, $message = false)
    {
        if ($condition) {
            return jsuccess($message);
        } else {
            return jerror();
        }
    }
}

if (!function_exists("is_valid_file_to_upload")) {
    /**
     * check if the file have valid extension
     *
     * @param string $file_name
     * @param array $extensions
     *
     * @return bool
     */
    function is_valid_file_to_upload($file_name = "", $extensions = array())
    {
        if (!$file_name) {
            return false;
        }
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $setting_extensions = separated_to_array(get_setting("accepted_file_formats"));
        $extensions = count($extensions) ? $extensions : $setting_extensions;
        if (in_array($file_ext, $extensions)) {
            return true;
        }
    }
}

/**
 * upload a file to temp folder when using dropzone autoque=true
 *
 * @param file $_FILES
 *
 * @return void
 */
if (!function_exists('upload_file_to_temp')) {

    function upload_file_to_temp($extensions = array(), $file = "file")
    {
        if (!empty($_FILES)) {
            $temp_file = $_FILES[$file]['tmp_name'];
            $file_name = $_FILES[$file]['name'];
            if (count($extensions)) {
                if (!is_valid_file_to_upload($file_name, $extensions)) {
                    return false;
                }
            }

            $temp_file_path = get_setting("temp_file_path");
            $target_path = getcwd() . '/' . $temp_file_path;
            if (!is_dir($target_path)) {
                if (!mkdir($target_path, 0777, true)) {
                    die('Failed to create file folders.');
                }
            }
            $target_file = $target_path . $file_name;
            copy($temp_file, $target_file);
        }

        return true;
    }

}

if (!function_exists('unzip_file')) {
    /**
     * unzip file
     *
     * @param $file
     * @param $destination
     *
     * @return bool
     */
    function unzip_file($file, $destination)
    {
        // create object
        $zip = new ZipArchive();
        // open archive
        if ($zip->open($file) !== true) {
            return false;
        }
        // extract contents to destination directory
        $result = $zip->extractTo($destination);
        rchmod($destination, 0777, 0777);
        // close archive
        $zip->close();

        return $result;
    }
}

if (!function_exists("rchmod")) {
    /**
     * For recursive chmod'ing both files and directories
     * in one step you can use the function below.
     * Note that this function has one argument
     * for directory permissions and one for file permissions.
     *
     * @param $path
     * @param $filemode
     * @param $dirmode
     */
    function rchmod($path, $filemode, $dirmode)
    {
        if (is_dir($path)) {
            if (!chmod($path, $dirmode)) {
                $dirmode_str = decoct($dirmode);
                print "Failed applying filemode '$dirmode_str' on directory '$path'\n";
                print "  `-> the directory '$path' will be skipped from recursive chmod\n";

                return;
            }
            $dh = opendir($path);
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {  // skip self and parent pointing directories
                    $fullpath = $path . '/' . $file;
                    rchmod($fullpath, $filemode, $dirmode);
                }
            }
            closedir($dh);
        } else {
            if (is_link($path)) {
                print "link '$path' is skipped\n";

                return;
            }
            if (!chmod($path, $filemode)) {
                $filemode_str = decoct($filemode);
                print "Failed applying filemode '$filemode_str' on file '$path'\n";

                return;
            }
        }
    }
}

if (!function_exists('read_zip_files')) {
    /**
     * read zip file elements names
     *
     * @param string $file $
     * @param bool $icons
     * @param bool $echo
     *
     * @return array|string
     */
    function read_zip_files($file = "", $icons = false, $echo = false)
    {

        $zip = zip_open($file);
        if ($zip) {
            if ($icons) {
                $result = "";
                while ($zip_entry = zip_read($zip)) {
                    $result .= "<p>";
                    $result .= "<i class='fa fa-" . get_file_icon(get_file_ext(zip_entry_name($zip_entry))) . "'></i> " .
                        zip_entry_name($zip_entry) . "<br />";
                    if ($echo) {
                        echo $result;
                    }
                }
                zip_close($zip);

                return $result;
            } else {
                $result = array();
                while ($zip_entry = zip_read($zip)) {
                    $result[] = zip_entry_name($zip_entry);
                }
                zip_close($zip);

                return $result;
            }
        }
    }
}

if (!function_exists('db_row_data_exists')) {
    /**
     * check if values exists in database table
     * @param $table
     * @param $id_key
     * @param $id_values
     * @return bool
     */
    function db_row_data_exists($table, $id_key, $id_values)
    {
        if (is_array($id_values) && count($id_values)) {
            $ci = get_instance(true);
            $table = $ci->db->dbprefix($table);
            $ci->db->where($id_key, $id_values[0]);
            unset($id_values[0]);
            foreach ($id_values as $id_value) {
                $ci->db->or_where($id_key, $id_value);
            }
            $q = $ci->db->get($table);
            return $q->num_rows() > 0;
        }
        return false;
    }
}

if (!function_exists('get_file_icon')) {
    /**
     * get some predefined icons for some known file types
     *
     * @param string $file_ext
     *
     * @return string
     */
    function get_file_icon($file_ext = "")
    {
        switch ($file_ext) {
            case "jpeg":
            case "jpg":
            case "png":
            case "gif":
            case "bmp":
            case "svg":
                return "file-image-o";
                break;
            case "doc":
            case "dotx":
                return "file-word-o";
                break;
            case "xls":
            case "xlsx":
            case "csv":
                return "file-excel-o";
                break;
            case "ppt":
            case "pptx":
            case "pps":
            case "pot":
                return "file-powerpoint-o";
                break;
            case "zip":
            case "rar":
            case "7z":
            case "s7z":
            case "iso":
                return "file-zip-o";
                break;
            case "pdf":
                return "file-pdf-o";
                break;
            case "html":
            case "css":
                return "file-code-o";
                break;
            case "txt":
                return "file-text-o";
                break;
            case "mp3":
            case "wav":
            case "wma":
                return "file-sound-o";
                break;
            case "mpg":
            case "mpeg":
            case "flv":
            case "mkv":
            case "webm":
            case "avi":
            case "mp4":
            case "3gp":
                return "file-movie-o";
                break;
            default:
                return "file-o";
        };
    }
}

if (!function_exists("get_file_ext")) {
    /**
     * return a file extension
     *
     * @param string $file
     *
     * @return mixed
     */
    function get_file_ext($file = "")
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }
}


/**
 * check post file is valid or not
 *
 * @param string $file_name
 *
 * @return boolean data of success or error message
 */
if (!function_exists('validate_post_file')) {

    function validate_post_file($file_name = "", $extensions = array())
    {
        if (is_valid_file_to_upload($file_name, $extensions)) {
            echo json_encode(array("success" => true));

            return true;
        } else {
            echo json_encode(array("success" => false, 'message' => lang('invalid_file_type') . " ($file_name)"));

            return false;
        }
    }
}

if (!function_exists("validate_readonly")) {
    /**
     * validate read only fields ignored by the form validation
     *
     * @param string $id
     */
    function validate_readonly($id = "")
    {
        echo "
            $(document).on(\"focusin\", \"#$id\", function() {
                $(this).prop('readonly', true);
            });
    
            $(document).on(\"focusout\", \"#$id\", function() {
                $(this).prop('readonly', false);
            });
        ";
    }
}

/**
 * move file to a parmanent direnctory from the temp dirctory
 *
 * dropzone file post data example
 * the input should be named as file_names and file_sizes
 *
 * for old borwsers which doesn't supports dropzone the files will be handaled using manual process
 * the post data should be named as manualFiles
 *
 * @param string $target_path
 * @param string $name
 *
 * @return array of file ids
 */
if (!function_exists('move_files_from_temp_dir_to_permanent_dir')) {

    function move_files_from_temp_dir_to_permanent_dir($target_path = "", $related_to = "")
    {

        $ci = get_instance();

        //process the files which has been uploaded by dropzone
        $files_data = array();
        $file_names = $ci->input->post("file_names");
        $file_sizes = $ci->input->post("file_sizes");

        if ($file_names && get_array_value($file_names, 0)) {
            foreach ($file_names as $key => $file_name) {
                $new_file_name = move_temp_file($file_name, $target_path, $related_to);
                $files_data[] = array(
                    "file_name" => $new_file_name,
                    "file_size" => get_array_value($file_sizes, $key)
                );
            }
        }

        //process the files which has been submitted manually
        if ($_FILES) {
            $files = $_FILES['manualFiles'];
            if ($files && count($files) > 0) {
                foreach ($files["tmp_name"] as $key => $file) {
                    $temp_file = $file;
                    $file_name = $files["name"][$key];
                    $file_size = $files["size"][$key];

                    $new_file_name = move_temp_file($file_name, $target_path, $related_to, $temp_file);
                    $files_data[] = array(
                        "file_name" => $new_file_name,
                        "file_size" => $file_size,
                    );
                }
            }
        }

        return serialize($files_data);
    }

};

if (!function_exists('get_member_profile_link')) {
    /**
     * return a specific member profile link
     *
     * @param int $id
     * @param string $name
     * @param array $attributes
     *
     * @return string
     */
    function get_member_profile_link($id = 0, $name = "", $attributes = array())
    {
        return anchor("members/view/" . $id, $name, $attributes);
    }

}

if (!function_exists("blowfish_encrypt")) {
    /**
     * encrypt the password with blowfish algorithm
     * using a salt and a cost of 8
     * steps
     * the algorithm used
     * parameters
     * salt
     * actual password hash
     *
     * @param string $password
     *
     * @return bool|mixed|string
     */
    function blowfish_encrypt($password = "")
    {
        $timeTarget = 0.1;
        $cost = 8;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("calculate_cost", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);
        $options = ['cost' => $cost];

        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
}

if (!function_exists("blowfish_decrypt")) {
    /**
     * return true if the password confirmed with the hash
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    function blowfish_decrypt($password = "", $hash = "")
    {
        return password_verify($password, $hash);
    }
}

if (!function_exists("close_modal_result_with_message")) {
    /**
     * Takes the $class and  result, if the
     * record saved correctly, it show $messages
     * else show an error message
     *
     * @param $class
     * @param $id
     * @param $messages
     */
    function close_modal_result_with_message($class, $id, $messages = "")
    {
        if ($id) {
            echo json_encode(array("success" => true, 'message' => plang($messages)));
        } else {
            echo json_encode(array("success" => false, 'message' => plang('error_occurred')));
        }
    }
}

if (!function_exists("rsa_decrypt")) {
    /**
     * decrypt a string using private key
     *
     * @param string $encrypted
     *
     * @return string
     */
    function rsa_decrypt($encrypted = "")
    {
        $ci = get_instance();
        $private_key = $ci->Rsa_keys_model->get_keys()->private;
        $res = openssl_get_privatekey($private_key);
        $decrypted = "";
        openssl_private_decrypt($encrypted, $decrypted, $res);

        return $decrypted;
    }
}

if (!function_exists("generate_rsa_keys")) {
    /**
     * generate and return
     * a private and public
     * rsa keys
     * @return array
     */
    function generate_rsa_keys()
    {
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 1024,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $res = @openssl_pkey_new($config);
        //TODO: CHECK IF THERE IS AN ERROR
        $private_key = "";
        var_dump($res);
        openssl_pkey_export($res, $private_key);
        $public_key = openssl_pkey_get_details($res);
        $pubKey = $public_key["key"];

        return array($private_key, $pubKey);
    }
}

if (!function_exists('get_related_table_value')) {
    /**
     * get a value from a specific database table using the ID
     * @param $table
     * @param $id
     * @param string $value
     * @return string
     */
    function get_related_table_value($table, $id, $value = 'text')
    {
        $ci = get_instance(true);
        $ci->load->model('Crud_model');
        $ci->Crud_model->__construct($table);
        $row = $ci->Crud_model->get_one($id);
        return get_property($row, $value);
    }
}


if(!function_exists("plang")) {
    /**
     * Fetches a language variable with passed args
     * @param string $text
     * @param array $parameters
     * @param $word
     * @return string
     */
    function plang($text = "", $parameters = array(), $word = "") {
        foreach ($parameters as $key => $parameter) {
            $parameters[$key] = lang($parameter);
        }
        $text = lang($text);
        if(!empty($word)) {
            return vsprintf($text, array($word));
        }
        if(count($parameters) == 0) {
            $parameters = array("");
        }
        return vsprintf($text, $parameters);
    }
}

if (!function_exists('validation_lang')) {
    /**
     * this function will provide text translation
     * based on the form validation language file
     * the second value will take the key field as default
     * and you can use as many as you want as the third parameter
     * @param string $text
     * @param bool $field
     * @param array $args
     * @return mixed|string
     */
    function validation_lang($text = '', $field = false, $args = array()) {
        if ($field) {
            $args['field'] = $field;
        }
        $text = lang($text);
        foreach ($args as $name => $value) {
            $text = str_replace('{' . $name . '}', $value, $text);
        }
        return $text;
    }
}