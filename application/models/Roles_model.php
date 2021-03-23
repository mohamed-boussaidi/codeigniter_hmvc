<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * Class Roles_model
 * Model for the roles table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Roles_model extends Crud_model
{
    private $table = null;

    function __construct()
    {
        $this->table = parent::__construct('roles');
    }
}
