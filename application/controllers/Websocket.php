<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

/**
 * this class will manage websocket methods
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Websocket extends Init implements Mind_controller
{

	/**
	 * Websocket constructor.
	 * only admin have access to this class
	 */
    public function __construct()
    {
        parent::__construct();
        $this->only_admin();
    }

    /**
     * websocket main page
     */
    public function index()
    {
        //check and update the server status
        $this->update_status();
        //load the view
        view("websocket/index");
    }

    /**
     * execute websocket server
     */
    public function run() {
        $command = "php ".APPPATH."/third_party/Realtime/bin/mind-websocket-server.php ";
        $command .= get_setting("websocket_port");
        $this->process->run($command);
        $pid = $this->process->getPid();
        if($this->process->status(false, 5)) {
            $this->Settings_model->save_setting("websocket_process", $pid);
            echo json_encode(array(
                "success" => true,
                "message" => plang("websocket_server_started", array(), $pid),
                "pid" => $pid
            ));
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => plang("error_occurred"),
            ));
        }
    }

    /**
     * kill websocket server by his process id
     */
    public function kill() {
        $this->process->stop(get_setting("websocket_process"), "9");
        if($this->process->status(get_setting("websocket_process"), 5)) {
            echo json_encode(array(
                "success" => false,
                "message" => plang("error_occurred"),
            ));
        } else {
            $this->Settings_model->save_setting("websocket_process", "");
            echo json_encode(array(
                "success" => true,
                "message" => plang("websocket_server_stopped")
            ));
        }
    }

    /**
     * set the server status
     */
    private function update_status() {
        $process = get_setting("websocket_process");
        if(!empty($process)) {
            if(!$this->process->status($process)) {
                $this->Settings_model->save_setting("websocket_process", "");
            }
        }
    }

    /**
     * save the server settings
     */
    public function save()
    {
        validate_submitted_data(array(
            "websocket_ip" => "valid_ip",
            "websocket_port" => "required|numeric"
        ));
        //get the posted data
        $settings = array(
            "websocket_ip",
            "websocket_port"
        );
        //foreach setting
        foreach ($settings as $setting) {
            //get the setting value
            $value = $this->input->post($setting);
            if($setting == "websocket_ip") {
                $value = $value ? $value : gethostbyname(gethostname());
            }
            //save setting to the database
            $value ? $this->Settings_model->save_setting($setting, $value) : false;
        }
        jsuccess(plang('element_updated', array("settings")));

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
