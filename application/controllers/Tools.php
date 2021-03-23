<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Tools
 * works as a CLI interface
 * to help user to call other
 * class with all the CI framework
 * loaded, useful when applying
 * a terminal restriction for
 * some security reasons
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Tools extends CI_Controller
{

    private $joins_ids = array();
    private $module_name = false;

    /**
     * call the parent constructor
     * check if the request
     * is executed from terminal
     * and load the dbforge
     * Tools constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
        // can only be called from the command line
        if (!$this->input->is_cli_request()) {
            exit('Direct access is not allowed. This is a command line tool, use the terminal');
        }
        $this->load->dbforge();
    }

    private function _get_tables_names()
    {
        $sql = "SELECT table_name FROM information_schema.tables where table_schema='" . $this->db->database . "'";
        $result = $this->db->query($sql);
        $tables_names = $result->num_rows() ? $result->result() : array();
        $result = array();
        $dbprefix_length = strlen($this->db->dbprefix('a')) - 1;
        foreach ($tables_names as $table_name) {
            $result[] = substr($table_name->table_name, $dbprefix_length);
        }
        return $result;
    }

    /**
     * load the help function
     */
    public function index()
    {
        $this->help();
    }

    /**
     * output some
     * useful commands
     */
    public function help()
    {
        $result = "\nThe following are the available command line interface commands\n\n";
        $result .= "php index.php tools migration \"file_name\"         Create new migration file\n";
        $result .= "php index.php tools migrate [\"version_number\"]    Run all migrations. The version number is optional.\n";

        echo $result . PHP_EOL;
    }

    /**
     * create index.html file to prevent
     * directories listening
     *
     * @param string $path
     */
    private function _module_touch_index($path = "")
    {
        file_put_contents($path . "/index.html",
            join("\n", array(
                "<!DOCTYPE html>",
                "<html><head>",
                "<title>403 Forbidden</title>",
                "</head><body>",
                "<p>Directory access is forbidden.</p>",
                "</body></html>"
            ))
        );
    }

    /**
     * create folder or die and
     * output the error message
     *
     * @param string $container
     * @param string $path
     * @param string $name
     */
    private function _module_mkdir($container = "", $path = "", $name = "")
    {
        if (!is_dir($path)) {
            if (is_writable($container)) {
                if (mkdir($path)) {
                    $this->_module_touch_index($path);
                    echo "$name folder created successfully.\n";
                } else {
                    die("Error : could not create $path.\n");
                }
            } else {
                die("Error : $container should be writable.\n");
            }
        }
    }

    /**
     * use the $name to create
     * module folder that contains the required
     * files and folder to build a new module
     *
     * @param $name
     * @return string
     */
    public function create_module($name = "")
    {
        if (!empty($name)) {
            $modules = APPPATH . "/modules/";
            $module = $modules . $name;
            if (!in_array($name, get_modules())) {
                $this->_module_mkdir(APPPATH, $modules, "Modules");
                $this->_module_mkdir($modules, $module, "Module");
                $this->_module_mkdir($module, $module . "/config", "Config");
                file_put_contents($module . "/config/features.php",
                    "<?php defined('BASEPATH') OR exit('No direct script access allowed');" .
                    "\n\n" . '$features["' . $name . '_init"] = "";'
                );
                $this->_module_mkdir($module, $module . "/controllers", "Controllers");
                $this->_module_mkdir($module, $module . "/assets", "Assets");
                file_put_contents($module . "/assets/.htaccess", join("\n", array(
                    "<IfModule authz_core_module>",
                    "Require all granted",
                    "</IfModule>"
                )));
                $this->_module_mkdir($module, $module . "/assets/js", "Assets JS");
                $this->_module_mkdir($module, $module . "/assets/css", "Assets CSS");
                $this->_module_mkdir($module, $module . "/helpers", "Helpers");
                file_put_contents($module . "/helpers/building_helper.php",
                    "<?php defined('BASEPATH') OR exit('No direct script access allowed');"
                );
                $this->_module_mkdir($module, $module . "/language", "Language");
                $this->_module_mkdir($module . "/language",
                    $module . "/language/english", "English");
                file_put_contents($module . "/language/english/module_default_lang.php",
                    "<?php defined('BASEPATH') OR exit('No direct script access allowed');" .
                    "\n\n\$lang['init'] = '';"
                );
                file_put_contents($module . "/language/english/module_custom_lang.php",
                    "<?php defined('BASEPATH') OR exit('No direct script access allowed');" .
                    "\n\n\$lang['init'] = '';"
                );
                $this->_module_mkdir($module . "/language",
                    $module . "/language/french", "French");
                file_put_contents($module . "/language/french/module_default_lang.php",
                    "<?php defined('BASEPATH') OR exit('No direct script access allowed');" .
                    "\n\n\$lang['init'] = '';"
                );
                file_put_contents($module . "/language/french/module_custom_lang.php",
                    "<?php defined('BASEPATH') OR exit('No direct script access allowed');" .
                    "\n\n\$lang['init'] = '';"
                );
                $this->_module_mkdir($module, $module . "/migrations", "Migrations");
                $this->_module_mkdir($module, $module . "/models", "Models");
                $this->_module_mkdir($module, $module . "/views", "Views");
                return $module . '/';
            } else {
                die("Error : module already exists");
            }
        } else {
            die("Error : empty module name");
        }
    }

    /**
     * call make migration file
     * using the name
     *
     * @param $name
     * @param bool $module
     */
    public function migration($name, $module = false)
    {
        $this->_make_migration_file($name, $module);
    }

    /**
     * copy modules migration files to app migrations folder
     */
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
        foreach (
            $modules_migrations_files as
            $modules_migrations_file_name =>
            $modules_migrations_file_path
        ) {
            //explode the file name
            $modules_migrations_file_name_parts = explode(
                "_",
                $modules_migrations_file_name
            );
            //if the file name is a valid migration format
            if (count($modules_migrations_file_name_parts) > 4) {
                //get the module name
                $module_name_parts = array();
                $class_name_index = false;
                foreach ($modules_migrations_file_name_parts as $index => $element) {
                    if (ctype_upper($element{0})) {
                        $class_name_index = $index;
                        break;
                    }
                }
                if ($class_name_index) {
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

    /**
     * run the migration
     * @param $version
     */
    private function _migrate_to_version($version)
    {
        if ($this->migration->version($version) === false) {
            show_error($this->migration->error_string());
        } else {
            echo "Migrations run successfully" . PHP_EOL;
        }

        return;
    }

    public function clear_modules_migration_files() {
		$migration_folder = APPPATH . "migrations/";
		$files = glob($migration_folder . '*_ci_module_*.php');
		foreach ($files as $file) {
			if (@unlink($file)) {
				echo 'Deleted successfully : ' . $file . PHP_EOL;
			} else {
				echo 'Error while deleting : ' . $file . PHP_EOL;
			}
		}
	}

    /**
     * execute a migration
     * operation
     *
     * @param null $version
     *
     * @return bool
     */
    public function migrate($version = null)
    {
        if ($version == 'clear') {
            $tables = $this->_get_tables_names();
            foreach ($tables as $table) {
                if ($this->dbforge->drop_table($table, true)) {
                    echo "$table" . " droped" . PHP_EOL;
                }
            }
            return true;
        }
        $this->_modules_migration();
        if ($version != null && $version != "latest") {
            return $this->_migrate_to_version($version);
        } else if ($version == "latest") {
            if ($this->migration->latest() === false) {
                show_error($this->migration->error_string());

                return false;
            } else {
                echo "Migrations run successfully" . PHP_EOL;

                return true;
            }
        }
        foreach (get_migration_versions() as $version) {
            echo $version . " : ";
            $this->_migrate_to_version($version);
        }
		$this->clear_modules_migration_files();
        return true;
    }

    /**
     * takes the name as
     * parameter and make
     * a CI compatible migration
     * class that will be used
     * to update a database
     *
     * @param $name
     * @param bool|string $module
     */
    private function _make_migration_file($name, $module = false)
    {
        $date = new DateTime();
        $timestamp = $date->format('YmdHis');
        $table_name = strtolower($name);
        $path = $module ? APPPATH . "modules/$module/migrations/$timestamp" . "_" . "$name.php" :
            APPPATH . "migrations/$timestamp" . "_" . "$name.php";
        if (!is_dir(dirname($path))) {
            die("Error : invalid path " . $path . "\n");
        }
        $class = $module ? "class Migration_ci_module_{$module}_$name extends CI_Migration {" :
            "class Migration_$name extends CI_Migration \n{";
        $my_migration = fopen($path, "w") or die("Unable to create migration file!");
        $migration_template = join("\n", array(
            "<?php defined('BASEPATH') OR exit('No direct script access allowed');",
            "\n/**",
            " * Class Migration_$name",
            " * Create or delete the $name table",
            " * @author taghoutitarek@gmail.com",
            " * @company http://mind.engineering",
            " * @company http://mind.engineering",
            " */",
            $class,
            "\tpublic function up() \n\t{",
            "\t\t\$this->dbforge->add_field(array(",
            "\t\t\t'id' => array(",
            "\t\t\t\t'type' => 'INT',",
            "\t\t\t\t'constraint' => 11,",
            "\t\t\t\t'auto_increment' => TRUE",
            "\t\t\t),",
            "\t\t));",
            "\t\t\$this->dbforge->add_key('id', TRUE);",
            "\t\t\$this->dbforge->create_table('$table_name', true);",
            "\t}",
            "\n\tpublic function down() \n\t{",
            "\t\t\$this->dbforge->drop_table('$table_name', true);",
            "\t}",
            "}"
        ));
        fwrite($my_migration, $migration_template);
        fclose($my_migration);
        echo "$path migration has successfully been created." . PHP_EOL;
    }

    /**
     * generate migration file from database table
     *
     * @param $table
     * @param $module
     */
    public function generate_table_migration($table = "", $module = false)
    {
        if (empty($table)) {
            die("Table name required.\n");
        }
        $crud = new Crud_model($table);
        if (!$crud->table_exists()) {
            die("Please verify that '$table' exists in the database.\n");
        }
        $columns = $crud->get_columns_names();
        if (!is_object($columns)) {
            die("Please verify that '$table' have columns.\n");
        }
        $date = new DateTime();
        $timestamp = $date->format('YmdHis');
        sleep(1);
        $table_name = strtolower($table);
        $table = ucfirst($table);
        $path = $module ? APPPATH . "modules/$module/migrations/$timestamp" . "_" . "$table.php" :
            APPPATH . "migrations/$timestamp" . "_" . "$table.php";
        if (!is_dir(dirname($path))) {
            die("Error : invalid path " . $path . "\n");
        }
        $class = $module ? "class Migration_ci_module_{$module}_$table extends CI_Migration {" :
            "class Migration_$table extends CI_Migration \n{";
        $fields = $crud->describe();
        $migration_fields = array();
        $rows = '';
        $data = $crud->get_all();
        if (is_array($data) && count($data)) {
            $rows .= "\$data = array(";
            foreach ($data as $datum) {
                $rows .= "\n\t\t\tarray(";
                foreach ($columns as $column => $data_column) {
                    $datum->$column = str_replace("'", "\'", $datum->$column);
                    $rows .= "\n\t\t\t\t'$column' => \"" . $datum->$column . "\",";
                }
                $rows .= "\n\t\t\t),";
            }
            $rows .= "\n\t\t); \n\t\t\$this->db->insert_batch('$table_name', \$data);";
        }
        foreach ($fields as $field) {
            if (get_property($field, "Field") == 'id') {
                continue;
            }
            $name = get_property($field, "Field");
            $type = get_property($field, "Type");
            $type = str_replace("'", "\'", $type);
            $default = get_property($field, "Default");
            $default = str_replace("'", "\'", $default);
            $migration_fields[] = join("\n", array(
                "\t\t\$this->dbforge->add_field(array(",
                "\t\t\t'$name' => array(",
                "\t\t\t\t'type' => '$type',",
                !empty($default) ? "\t\t\t\t'default' => '$default'," : '',
                "\t\t\t),",
                "\t\t));",
            ));
        }
        $migration_template = join("\n", array(
            "<?php defined('BASEPATH') OR exit('No direct script access allowed');",
            "\n/**",
            " * Class Migration_$table",
            " * Create or delete the $table table",
            " * @author taghoutitarek@gmail.com",
            " * @company http://mind.engineering",
            " * @company http://mind.engineering",
            " */",
            $class,
            "\tpublic function up() \n\t{",
            "\t\t\$this->dbforge->add_field(array(",
            "\t\t\t'id' => array(",
            "\t\t\t\t'type' => 'INT',",
            "\t\t\t\t'constraint' => 11,",
            "\t\t\t\t'auto_increment' => TRUE",
            "\t\t\t),",
            "\t\t));",
            "\t\t//crudtag_migration_fields",
            "\t\t\$this->dbforge->add_key('id', TRUE);",
            "\t\t\$this->dbforge->create_table('$table_name', true);",
            "\t\t//crudtag_insert_data",
            "\t}",
            "\n\tpublic function down() \n\t{",
            "\t\t\$this->dbforge->drop_table('$table_name', true);",
            "\t}",
            "}"
        ));
        $migration_template = str_replace('//crudtag_migration_fields', join($migration_fields), $migration_template);
        $migration_template = str_replace('//crudtag_insert_data', $rows, $migration_template);
        $my_migration = fopen($path, "w") or die("Unable to create migration file!");
        fwrite($my_migration, $migration_template);
        fclose($my_migration);
        echo "$path migration has successfully been created." . PHP_EOL;
    }

    /**
     * return codeigniter form field
     * @param $type : field type (text, number ...)
     * @param $object : object name (creation_name)
     * @param $name : field name
     * @param string $classes : classes to be add to the form field
     * @param bool $required : append data rule required
     * @param bool $numeric : append data rule number
     * @return string
     */
    private function _crud_form_field($type, $object, $name, $classes = '', $required = false, $numeric = false)
    {
        $date = contains($classes, 'datepicker') ? true : false;
        $value = 'get_property($' . $object . ', "' . $name . '" )';
        $value = $date ? 'from_sql_date(' . $value . ')' : $value;
        $required = $required ? '"data-rule-required"     => true, "data-msg-required"       => "' . plang('field_required') . '",' : '';
        $numeric = $numeric ? '"data-rule-number"     => true,' : '';
        $rule = '';
        $language = str_replace('_id', '', $name);
        $language = in_array($language, $this->joins_ids) ? str_replace('_id', '', $name) : $name;
        if ($type !== 'textarea') {
            if ( contains($name, 'email') ) {
                $rule = '"data-rule-email"     => true,';
                $type = 'email';
            }
            if ( contains($name, 'password') ) {
                $type = 'password';
            }
            return <<<HTML
    <div class="form-group">
        <label for="{$name}" class=" col-md-3"><?php echo plang( '{$language}' ); ?></label>
        <div class=" col-md-9">
			<?php
			echo form_input(
				array(
				    "type"               => "{$type}",
					"id"                 => "{$name}",
					"name"               => "{$name}",
					"value"              => {$value},
					"class"              => "form-control {$classes} validate-hidden",
					"placeholder"        => plang( '{$language}' ),
					{$required}
					{$numeric}
					{$rule}
				)
			);
			?>
        </div>
    </div>
HTML;
        } else {
            return <<<HTML
    <div class="form-group">
        <label for="{$name}" class=" col-md-3"><?php echo plang( '{$language}' ); ?></label>
        <div class=" col-md-9">
			<?php
			echo form_textarea(
				array(
					"id"                 => "{$name}",
					"name"               => "{$name}",
					"value"              => get_property( \${$object}, "{$name}" ),
					"class"              => "form-control {$classes}",
					"placeholder"        => plang( '{$language}' ),
					{$required}
				)
			);
			?>
        </div>
    </div>
HTML;
        }
    }

    /**
     * modal form select2 source
     * @param $name
     * @return string
     */
    private function _crud_select2_source($name)
    {
        return ' $("#' . $name . '_id").select2({
            multiple: false, 
            data: <?php echo($' . $name . '_id_dropdown); ?>
        });';
    }

    /**
     * controller select2 sources
     * @param $model
     * @param $name
     * @param $value
     * @return string
     */
    private function _crud_select2_controller_source($model, $name, $value)
    {
        $ucname = ucfirst($model);
        return '$data["' . $name . '_id_dropdown"] = select2_dropdown(
            $this->' . $ucname . '_model->get_all(),
            array("id"), array("' . $value . '")
        );';
    }

    private function _crud_select2_controller_field($model, $name, $value)
    {
        $ucname = ucfirst($model);
        return '$' . $name . ' = $this->' . $ucname . '_model->get_one($data->' . $name . '_id);
        $data->' . $name . '_id = get_property($' . $name . ', \'' . $value . '\');
        ';
    }

    /**
     * return data tables columns
     * @param $name
     * @return string
     */
    private function _crud_datatables_fields($name)
    {
        $language = str_replace('_id', '', $name);
        $language = in_array($language, $this->joins_ids) ? str_replace('_id', '', $name) : $name;
        return '{title: \'<?php echo plang( \'' . $language . '\' ) ?>\'},';
    }

    /**
     * add an entry to language files
     * @param $path
     * @param $name
     */
    private function _crud_append_to_language($path, $name)
    {
        $language = str_replace('_id', '', $name);
        $language = in_array($language, $this->joins_ids) ? str_replace('_id', '', $name) : $name;
        file_put_contents($path . "language/english/module_custom_lang.php", "\n" . '$lang["' . $language . '"] = "' . $language . '";', FILE_APPEND);
        file_put_contents($path . "language/french/module_custom_lang.php", "\n" . '$lang["' . $language . '"] = "' . $language . '";', FILE_APPEND);
    }

    /**
     * replace crudtags and Crudtags with $creation_names
     * replace crudtag and Crudtag with $creation_name
     * @param $string
     * @param $creation_name
     * @param $creation_names
     * @return mixed
     */
    private function _replace_crud_tags($string, $creation_name, $creation_names)
    {
        $module = $this->module_name;
        $module = $module ? $module . '/' : '';
        $string = str_replace('crudtags', $creation_names, $string);
        $string = str_replace('Crudtags', ucfirst($creation_names), $string);
        $string = str_replace('crudtag', $creation_name, $string);
        $string = str_replace('Crudtag', ucfirst($creation_name), $string);
        $string = str_replace('Crudtag', ucfirst($creation_name), $string);
        $string = str_replace('crudmodulename/', $module, $string);
        return $string;
    }

    /**
     * add an entry to the top of the left menu
     * @param $name
     * @param bool $icon
     * @return string
     */
    private function _crud_building_helper($name, $icon = false)
    {
        $icon = $icon ? $icon : 'cog';
        return <<<TEXT
if ( ! function_exists( '{$name}_building_top_left_menu' ) ) {
    /**
     * left menu section
     * @return array
     */
    function {$name}_building_top_left_menu() {
        return array(
            "{$name}" => array(
                'name'    => '{$name}',
                'url'     => '{$name}',
                'class'   => 'fa-$icon',
            )
        );
    }
}
TEXT;
    }

    /**
     * Run the CRUD generation
     * In the CLI false = 0
     * the table should have ID as primary key (auto_increment required)
     * Example : php index.php tools generate_crud tests 0 test
     * where tests is the table name and test is the singular name
     * @param string $table : database table
     * @param bool $module_name : if this input != false a module with the $table as name will be created
     * @param bool $creation_name : singular version of the name
     * @param bool $icon : left menu icon
     * @param string $join
     * @param string $ignored_datatables_columns : fields which will be ignored in datatables generation ( separated by commas )
     * @param string $ignored_database_columns : fields which will be ignored in modal form ( separated by commas )
     */
    public function generate_crud($table = '', $module_name = false, $creation_name = false, $icon = false, $join = "", $ignored_datatables_columns = "", $ignored_database_columns = "")
    {
        $this->module_name = $module_name;
        $joins = array();
        $joins_ids = array();
        if ($join) {
            if (contains($join, '::')) {
                $join_lines = explode('::', $join);
            } else {
                $join_lines[] = $join;
            }
            foreach ($join_lines as $join_line) {
                if (contains($join_line, ':')) {
                    $join_line_elements = explode(':', $join_line);
                } else {
                    $join_line_elements = false;
                }
                if ($join_line_elements) {
                    if (count($join_line_elements) != 3) {
                        $join_line_elements = false;
                    }
                }
                if ($join_line_elements) {
                    $joins[] = $join_line_elements;
                }
            }
        }
        foreach ($joins as $join) {
            $joins_ids[] = $join[1];
        }
        $this->joins_ids = $joins_ids;
        $ignored_datatables_columns = contains($ignored_datatables_columns, ':') ? explode(':', $ignored_datatables_columns) : array($ignored_datatables_columns);
        $ignored_database_columns = contains($ignored_database_columns, ':') ? explode(':', $ignored_database_columns) : array($ignored_database_columns);
        $creation_name = $creation_name ? $creation_name : $table;
        $creation_names = $table;
        $creation_name = strtolower($creation_name);
        $creation_names = strtolower($creation_names);
        if ($module_name) {
            if (!in_array($module_name, get_modules()) || !is_dir(APPPATH . "modules/$module_name")) {
                die("Please verify that '$module_name' exists in the modules folder.\n");
            }
            $creation_names = strtolower($table);
            $creation_path = APPPATH . "modules/$module_name/";
            $this->generate_table_migration($table, $module_name);
        } else {
            $creation_path = $this->create_module($creation_names);
            $this->generate_table_migration($table, $creation_names);
        }
        if (empty($table)) {
            die("Table name required.\n");
        }
        $crud = new Crud_model($table);
        if (!$crud->table_exists()) {
            die("Please verify that '$table' exists in the database.\n");
        }
        $columns = $crud->get_columns_names();
        if (!is_object($columns)) {
            die("Please verify that '$table' have columns.\n");
        }
        $this->_crud_append_to_language($creation_path, $creation_name);
        $this->_crud_append_to_language($creation_path, $creation_names);
        file_put_contents($creation_path . "helpers/building_helper.php", "\n" . $this->_crud_building_helper($creation_names, $icon), FILE_APPEND);
        $controller = file_get_contents(APPPATH . "views/crud/controller.php");
        $controller = str_replace('<?php exit(\'silence_is_gold\') ?>' . "\n", '', $controller);
        $model = file_get_contents(APPPATH . "views/crud/model.php");
        $model = str_replace('<?php exit(\'silence_is_gold\') ?>' . "\n", '', $model);
        $index = file_get_contents(APPPATH . "views/crud/index.php");
        $index = str_replace('<?php exit(\'silence_is_gold\') ?>' . "\n", '', $index);
        $modal_form = file_get_contents(APPPATH . "views/crud/modal_form.php");
        $modal_form = str_replace('<?php exit(\'silence_is_gold\') ?>' . "\n", '', $modal_form);
        $controller = $this->_replace_crud_tags($controller, $creation_name, $creation_names);
        $model = $this->_replace_crud_tags($model, $creation_name, $creation_names);
        $index = $this->_replace_crud_tags($index, $creation_name, $creation_names);
        $modal_form = $this->_replace_crud_tags($modal_form, $creation_name, $creation_names);
        $controller_tags = array(
            'controller_fields' => array(),
            'server_side_validation' => array(),
            'data_to_save' => array()
        );
        $form_fields = array();
        $datatables_fields = array();
        $fields = $crud->describe();
        foreach ($fields as $field) {
            if (get_property($field, "Field") == 'id') {
                continue;
            }
            $field_class = "";
            $numeric = false;
            $name = get_property($field, "Field");
            $type = get_property($field, "Type");
            $default = get_property($field, "Default");
            $this->_crud_append_to_language($creation_path, $name);
            if (contains($type, 'int') ||
                contains($type, 'decimal') ||
                contains($type, 'double') ||
                contains($type, 'real') ||
                contains($type, 'float')) {
                $field_type = 'number';
                $numeric = true;
            } elseif (contains($type, 'date') ||
                contains($type, 'year')) {
                $field_type = 'text';
                $field_class = 'datepicker';
            } elseif (contains($type, 'time')) {
                $field_type = 'text';
                $field_class = 'timepicker';
            } elseif (contains($type, 'text')) {
                $field_type = 'textarea';
            } else {
                $field_type = 'text';
            }
            $required = empty($default) ? true : false;
            $email =  contains($name, 'email') ? true : false;
            if (!in_array($name, $ignored_database_columns)) {
                if ($required || $numeric) {
                    $server_side_rules = array();
                    if ($required) {
                        $server_side_rules[] = 'required';
                    }
                    if ($numeric) {
                        $server_side_rules[] = 'numeric';
                    }
                    if ($email) {
                        $server_side_rules[] = 'valid_email';
                    }
                    $controller_tags['server_side_validation'][] = '"' . $name . '"   => "' .  join('|', $server_side_rules) .'",';
                }
            }
            if ($field_class == 'datepicker') {
                if (!in_array($name, $ignored_database_columns)) {
                    $controller_tags['data_to_save'][] = '"' . $name . '" => to_sql_date($this->input->post("' . $name . '")),';
                }
                if (!in_array($name, $ignored_datatables_columns)) {
                    $controller_tags['controller_fields'][] = 'from_sql_date($data->' . $name . '),';
                }
                
            } else {
                if (!in_array($name, $ignored_database_columns)) {
                    $controller_tags['data_to_save'][] = '"' . $name . '" => $this->input->post("' . $name . '"),';
                }
                if (!in_array($name, $ignored_datatables_columns)) {
                    $controller_tags['controller_fields'][] = '$data->' . $name . ',';
                }
            }
            if (!in_array($name, $ignored_database_columns)) {
                $form_fields[] = $this->_crud_form_field($field_type, $creation_name, $name, $field_class, $required, $numeric);
            }
            if (!in_array($name, $ignored_datatables_columns)) {
                $datatables_fields[] = $this->_crud_datatables_fields($name);
            }
        }
        $controller = str_replace('//' . $creation_name . '_controller_fields', join("\n", $controller_tags['controller_fields']), $controller);
        $controller = str_replace('//' . $creation_name . '_server_side_validation', join("\n", $controller_tags['server_side_validation']), $controller);
        $controller = str_replace('//' . $creation_name . '_data_to_save', join("\n", $controller_tags['data_to_save']), $controller);
        $modal_form = str_replace($creation_name . '_form_fields', join("\n", $form_fields), $modal_form);
        $index = str_replace('//' . $creation_name . '_datatables_fields', join("\n", $datatables_fields), $index);
        $select2_sources = "";
        $select2_controller_sources = "";
        $select2_controller_fields = "";
        file_put_contents($creation_path . "/models/" . ucfirst($creation_names) . '_model.php', $model);
        foreach ($joins as $join) {
            $model = file_get_contents(APPPATH . "views/crud/model.php");
            $model = str_replace('<?php exit(\'silence_is_gold\') ?>' . "\n", '', $model);
            $model = $this->_replace_crud_tags($model, $join[1], $join[0]);
            file_put_contents($creation_path . "/models/" . ucfirst($join[0]) . '_model.php', $model);
            $this->generate_table_migration($join[0], $creation_names);
            $select2_sources .= $this->_crud_select2_source($join[1]) . "\n";
            $select2_controller_sources .= $this->_crud_select2_controller_source($join[0], $join[1], $join[2]) . "\n";
            $select2_controller_fields .= $this->_crud_select2_controller_field($join[0], $join[1], $join[2]) . "\n";
        }
        $controller = str_replace('//' . $creation_name . '_' . 'select2_controller_sources', $select2_controller_sources, $controller);
        $controller = str_replace('//' . $creation_name . '_' . 'select2_controller_fields', $select2_controller_fields, $controller);
        $modal_form = str_replace('//' . $creation_name . '_' . 'select2_sources', $select2_sources, $modal_form);
        file_put_contents($creation_path . "/controllers/" . ucfirst($creation_names) . '.php', $controller);
        if ($module_name) {
            $views_folder_path = $creation_path . '/views/' . $creation_names . "/";
            if (!is_dir($views_folder_path)) {
                if (is_writable($creation_path . '/views/')) {
                    mkdir($views_folder_path);
                } else {
                    die('Permission denied on ' . $views_folder_path);
                }
            }
        } else {
            $views_folder_path = $creation_path . '/views/';
        }
        file_put_contents($views_folder_path . 'index.php', $index);
        file_put_contents($views_folder_path . 'modal_form.php', $modal_form);
    }

}
