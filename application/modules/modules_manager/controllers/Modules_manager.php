<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH . "modules/Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Modules_manager extends Init implements Mind_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
        $this->only_admin();
    }

    public function index()
    {
        view("modules_manager/index");
    }

    public function view()
    {
        show_404();
    }

    public function modal_form()
    {
        $this->load->view("modules_manager/modal_form");
    }

    public function config() {
	    $this->load->view("modules_manager/config/modal_form");
    }

    public function save()
    {
        validate_submitted_data(array(
            "file_path" => "required",
        ));
        if(upload_file_to_temp(array("zip"))) {
            if(isset($_FILES["file"])) {
                $file_path = FCPATH."/".get_setting("temp_file_path") . "/" .
                    $_FILES["file"]["name"];
                if(file_exists($file_path)) {
                    if(!is_valid_module_archive($file_path)) {
                        if(!@unlink($file_path)) {
                            jerror("error_while_removing_zipped_file");
                        }
                        return jerror("invalid_module_archive");
                    }
                    $folder_path = str_replace(".zip", "", $_FILES["file"]["name"]);
                    $folder_path = APPPATH."/modules/".$folder_path;
                    if(is_dir($folder_path)) {
                        return jerror("module_exists");
                    }
                    if(unzip_file($file_path, $folder_path)) {
                        if(@unlink($file_path)) {
                            $this->migrate();
                            return jsuccess();
                        } else {
                            @unlink($folder_path);
                            return jerror("error_while_removing_zipped_file");
                        }
                    } else {
                        return jerror("error_while_unzipping_file");
                    }
                } else {
                    return jerror("error_while_uploading_file");
                }
            } else {
                return jerror("no_file_to_upload");
            }
        } else {
            return jerror("error_while_uploading_file");
        }
    }

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for project */

    function validate_zip_file() {
        return validate_post_file($this->input->post("file"), array("zip"));
    }

    public function delete()
    {
        validate_submitted_data(array(
            "id" => "required|alpha_dash|trim"
        ));
        $module = $this->input->post("id");
        $path = APPPATH . "/modules/" . $module;
        if(!is_writable($path)) {
            jerror("permission_denied");
            return false;
        }
        if (rrmdir($path)) {
            echo json_encode(array(
                "success" => true,
                'message' => plang('element_deleted', array("record"))
            ));
            $this->migrate();
            return true;
        } else {
            echo json_encode(array(
                "success" => false,
                'message' => plang('error_occurred')
            ));
            return false;
        }
    }

    public function list_data()
    {
        $data = get_modules();
        $result = array();
        foreach ($data as $datum) {
            $result[] = $this->make_row($datum);
        }
        echo json_encode(array("data" => $result));
    }

    public function make_row($data = stdClass::class, $id = false)
    {
    	$config = "";
    	if(file_exists(FCPATH."/application/modules/$data/views/config/modal_form.php")) {
    		if(module_have_features($data)) {
			    $config = modal_anchor(
				    get_uri("$data/config"),
				    "<i class='fa fa-cogs'></i>",
				    array(
					    "class" => "config",
					    "title" => plang('config_element', array($data)),
				    )
			    );
		    }
	    }
        return array(
            dt_row_trigger(),
            empty(plang($data)) ? $data : plang($data),
            empty(plang($data . "_author")) ? "Mind Engineering" : plang($data . "_author"),
            empty(plang($data . "_company")) ? "Mind Engineering" : plang($data . "_company"),
	        $config . js_anchor(
                "<i class='fa fa-times fa-fw'></i>",
                array(
                    'title' => plang('delete_element', array("module")),
                    "class" => "delete", "data-id" => $data,
                    "data-action-url" => get_uri("modules_manager/delete"),
                    "data-action" => "delete"
                )
            )
        );
    }

    private function migrate()
    {
        $this->_modules_migration();
        foreach (get_migration_versions() as $version) {
            $this->migration->version($version);
        }
    }

    private function _modules_migration()
    {
        //get all the modules
        foreach (get_modules() as $module) {
            //get module migration path
            $migrations_path = APPPATH . "/modules/" . $module . "/migrations/";
            //if the folder exists
            if (is_dir($migrations_path)) {
                //get sub files
                $folder_content = scandir($migrations_path);
                //set files container
                $files = array();
                //for each file in the folder
                foreach ($folder_content as $item) {
                    //if the target is a valid file
                    if (is_file($migrations_path . $item)) {
                        //set the file path
                        $file_path = $migrations_path . $item;
                        //set the file name
                        $file_name = basename($file_path);
                        //if the name format is correct
                        if (contains($file_name, ".php") &&
                            contains($file_name, "_")
                        ) {
                            //explode the name using _
                            $file_name_parts = explode("_", $file_name);
                            //if there is 2 fields or more
                            if (count($file_name_parts) > 1) {
                                //if the first part is a valid timestamp
                                if (is_timestamp($file_name_parts[0])) {
                                    //build the migration file name
                                    $migration_name = $file_name_parts;
                                    unset($migration_name[0]);
                                    $migration_name = join("_", $migration_name);
                                    $migration_file_name =
                                        $file_name_parts[0] .
                                        "_ci_module_" .
                                        $module . "_" . $migration_name;
                                    $files[$migration_file_name] = $file_path;
                                }
                            }
                        }
                    }
                }
                //for each file extracted from the module migrations folder
                foreach ($files as $app_migration_file_name => $module_migration_file_path) {
                    //set the app migration file path
                    $app_migration_file_path = APPPATH . "/migrations/" . $app_migration_file_name;
                    //if the file already exists
                    if (is_file($app_migration_file_path)) {
                        //remove the file
                        if (!unlink($app_migration_file_path)) {
                            break;
                        }
                    }
                    //copy the file from the module migrations folder
                    //to the app migrations folder under the new name
                    copy($module_migration_file_path, $app_migration_file_path);
                }
            }
        }
        //set the app migrations folder path
        $migrations_path = APPPATH . "/migrations/";
        //set modules migrations files container
        $modules_migrations_files = array();
        //get all files under app migrations folder
        $migrations_folder_content = scandir($migrations_path);
        //for each one of them
        foreach ($migrations_folder_content as $item) {
            //get the file full path
            $migration_file_path = $migrations_path . $item;
            //if the file exists
            if (is_file($migration_file_path)) {
                //if the file related to one module
                if (contains($migration_file_path, "_ci_module_")) {
                    //get the file name
                    $migration_file_name = basename($migration_file_path);
                    //push the result to the container
                    $modules_migrations_files[$migration_file_name] = $migration_file_path;
                }
            }
        }
        //for each module migration file located under the
        //app migrations folder
        foreach ($modules_migrations_files as
                 $modules_migrations_file_name =>
                 $modules_migrations_file_path) {
            //explode the file name
            $modules_migrations_file_name_parts = explode(
                "_",
                $modules_migrations_file_name
            );
            //if the file name is a valid migration format
            if (count($modules_migrations_file_name_parts) > 4) {
                //get the module name
                $module_name_parts = "";
                $class_name_index = false;
                foreach ($modules_migrations_file_name_parts as $index => $element) {
                    if(ctype_upper($element{0})) {
                        $class_name_index = $index;
                        break;
                    }
                }
                if($class_name_index) {
                    for ($i = 3; $i < $class_name_index; $i++) {
                        $module_name_parts[] = $modules_migrations_file_name_parts[$i];
                    }
                }
                $module_name = join("_", $module_name_parts);
                if (!in_array($module_name, get_modules())) {
                    $module_migration_version = $modules_migrations_file_name_parts[0];
                    //execute the migration file down method
                    $this->migration->drop_version($module_migration_version);
                    //remove the migration file
                    unlink($modules_migrations_file_path);
                }
            }
        }
    }
}
