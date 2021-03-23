<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Init.php";

/**
 * members dashboard
 * this is the kernel default controller
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Dashboard extends Init
{
	/**
	 * Dashboard constructor.
	 */
    public function __construct()
    {
        parent::__construct();
    }

	/**
	 * Load index page
	 * build members_widgets
	 * build dashboard_elements
	 */
    public function index()
    {
        $logged_member = get_logged_member();
        //get members widgets
        $widgets = build("members_widgets", array(), array($logged_member));
        //get dashboard elements
        $elements = build("dashboard_elements", array(), array($logged_member));
        //get dashboard views
        $views = build("dashboard_views", array(), array($logged_member));
        //load the view
        view("dashboard/index", array(
            "widgets" => $widgets,
            "elements" => $elements,
            "views" => $views,
        ));
    }

	/**
	 * Save sticky note using ajax
	 */
    public function save_sticky_note() {
        $data = array("sticky_note" => $this->input->post("sticky_note"));
        $this->Members_model->save($data, get_logged_member_id());
    }
}