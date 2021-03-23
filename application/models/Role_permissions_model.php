<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * Class Role_permissions_model
 * Model for the role_permissions table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Role_permissions_model extends Crud_model
{
    private $table = null;

    function __construct()
    {
        $this->table = parent::__construct('role_permissions');
    }
}
