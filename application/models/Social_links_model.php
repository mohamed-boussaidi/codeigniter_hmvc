<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * Class Settings_model
 * Model for the social_links table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Social_links_model extends Crud_model
{
    private $table = null;

    function __construct()
    {
        $this->table = parent::__construct('social_links');
    }
}
