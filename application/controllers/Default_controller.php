<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * members dashboard
 * this is the kernel default controller
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Default_controller extends CI_Controller
{
    /**
     * Dashboard constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index($uri = "") {
        $default_controllers = build('default_controller');
        if (count($default_controllers)) {
            redirect(end($default_controllers));
        } else {
            redirect('dashboard');
        }
    }

}