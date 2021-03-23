<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once "Crud_model.php";

/**
 * Class Rsa_keys_model
 * Model for the RSA keys table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Rsa_keys_model extends Crud_model {

    /**
     * @var null|string
     */
    private $table = null;

    /**
     * Rsa_keys_model constructor.
     */
    function __construct() {
        $this->table = parent::__construct('rsa_keys');
    }

    function get_keys() {
        $key = $this->Rsa_keys_model->get_one(1);
        if($key) {
            $key->private = $key->private ? base64_decode($key->private) : "";
            $key->public = $key->public ? base64_decode($key->public) : "";
            return $key;
        } else {
            return false;
        }
    }

}
