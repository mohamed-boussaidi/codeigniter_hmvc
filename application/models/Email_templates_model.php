<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Email_templates_model extends Crud_model
{
    private $table = null;

    function __construct()
    {
        $this->table = parent::__construct('email_templates');
    }

    function get_final_template($template_name = "") {
        $info = new stdClass();
        $result = $this->get_one_where(array("template_name" => $template_name), true, false);
        $info->subject = get_property($result, "email_subject");
        $info->message = empty(get_property($result, "custom_message")) ?
            get_property($result, "default_message") :
            get_property($result, "custom_message");
        $result = $this->get_one_where(array("template_name" => "signature"), true, false);
        $info->signature = empty(get_property($result, "custom_message")) ?
            get_property($result, "default_message") :
            get_property($result, "custom_message");
        return $info;

    }
}
