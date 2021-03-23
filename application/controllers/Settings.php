<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Init.php";
include_once APPPATH."interfaces/Mind_controller.php";

/**
 * Class Settings
 * the main settings for
 * the application
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Settings extends Init implements Mind_controller
{

	/**
	 * Settings constructor.
	 * only admin have access to this class
	 */
    public function __construct()
    {
        parent::__construct();
        $this->only_admin();
    }

    /**
     * load the main settings
     */
    public function index()
    {
        $data = array();
        $language = array();
        //set the languages folder path
        $dir = "./application/language/";
        //if the folder exists
        if (is_dir($dir)) {
            //if the folder can be visualised
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file && $file != "." && $file != ".." && $file != "index.html") {
                        //get all languages folders names
                        $language[$file] = ucfirst($file);
                    }
                }
                closedir($dh);
            }
        }
        //set the languages names array
        $data["language"] = form_dropdown(
            "language",
            $language,
            get_setting('language'),
            "class='select2 mini'"
        );
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $data['timezone_dropdown'] = array();
        foreach ($timezones as $timezone) {
            $data['timezone_dropdown'][$timezone] = $timezone;
        }
        //load the view
        view('settings/index', $data);
    }

    /**
     * save general settings
     */
    public function save()
    {
        //validate the submitted data to
        //fit the javascript rules
        validate_submitted_data(array(
            "site_title" => "required",
            "date_format" => "required",
            "first_day_of_week" => "required",
            "rows_per_page" => "required",
            "timezone" => "required",
            "language" => "required",
            "profile_image_max_size" => "required|numeric"
        ));
        //get the posted data
        $settings = array(
            "media_replace",
            "date_format",
            "first_day_of_week",
            "rows_per_page",
            "profile_image_max_size",
            "site_title",
            "timezone",
            "accepted_file_formats",
            "language",
        );
        //foreach setting
        foreach ($settings as $setting) {
            //get the setting value
            $value = $this->input->post($setting);
            //save setting to the database
            if ($setting == "media_replace") $value = $value ? "enabled" : "disabled";
            $value ? $this->Settings_model->save_setting($setting, $value) : false;
        }
        //upload and save the site_logo
        $this->_save_image("site_logo");
        //upload and save the site favicon
        $this->_save_image("site_favicon");
        jsuccess(plang('element_updated', array("settings")));
    }

    /***
     * upload and save image
     * name to the settings
     * @param string $name
     * @return bool
     */
    private function _save_image($name = "")
    {
        //upload the text based image
        $image = upload_posted_file($name, "png");
        $old_file = false;
        //if the media replace is enabled
        if (get_setting("media_replace") === "enabled") {
            //save the file name
            $old_file = $this->Settings_model->get_setting($name);
        }
        $saved = ($image !== false) ? $this->Settings_model->save_setting($name, $image) : false;
        if ($old_file && $saved) {
            //remove the old file after the upload
            $old_file = get_setting("system_file_path") . $old_file;
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }
        return $saved;
    }

    public function view()
    {
        show_404();
	}

    public function modal_form()
    {
        show_404();
	}

    public function delete()
    {
        show_404();
	}

    public function list_data()
    {
        show_404();
	}

    public function make_row($data = stdClass::class, $id = false)
    {
        show_404();
    }
}
