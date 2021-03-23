<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Migrate
 * Migrate the database
 * to a specific version
 * user access this class
 * only from the CLI interface
 * using the Tools class
 * located under
 * application/controllers/Tools.php
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Migrate extends CI_Controller
{
	/**
	 * Migrate constructor.
	 */
    public function __construct()
    {
        parent::__construct();
        //load the migration library
        $this->load->library('migration');
    }

    /**
     * use the version number
     * to load a specific migration
     * @param $version
     */
    public function index($version) {
        //check if the version is a valid number
        is_numeric($version) ? : exit("Version error");
        //call the version function
        $this->version($version);
    }

    /**
     * load a specific
     * migration which will
     * change the database
     * structure from in the code
     * @param $version
     */
    public function version($version)
    {
        //check if the query was executed from a cli interface
        if (PHP_SAPI === 'cli' OR defined('STDIN')) {
            //execute migration
            $migration = $this->migration->version($version);
            if (!$migration) {
                echo $this->migration->error_string();
            } else {
                echo 'Migration(s) done' . PHP_EOL;
            }
        } else {
            //if this function called from a web browser kill the script
            show_error('You don\'t have permission for this action');;
        }
    }
}
