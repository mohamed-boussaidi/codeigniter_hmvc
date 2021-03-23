<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Websocket_model extends Crud_model
{
    private $table = null;

    function __construct()
    {
        $this->table = parent::__construct('websocket');
    }
}
