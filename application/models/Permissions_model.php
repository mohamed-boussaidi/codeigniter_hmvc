<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * Class Permissions_model
 * Model for the permissions table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Permissions_model extends Crud_model
{
    private $table = null;

    function __construct()
    {
        $this->table = parent::__construct('permissions');
    }
}
