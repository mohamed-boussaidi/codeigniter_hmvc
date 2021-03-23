<?php defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

require_once "Crud_model.php";

/**
 * Class Rest_keys_model
 * Model for the REST keys table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Rest_keys_model extends Crud_model {

	/**
	 * @var null|string
	 */
	private $table = null;

	/**
	 * Keys_model constructor.
	 */
	function __construct() {
        $this->table = parent::__construct('rest_keys');
	}

	function get_keys() {
		$keys = $this->get_all();

		if ( $keys ) {
			$result = array();
			foreach ( $keys as $key ) {
				array_push( $result, $this->get_key( $key->id ) );
			}

			return $result;
		}

		return array();
	}

	function get_key( $id ) {
		$key = $this->get_one( $id );
		if ( $key ) {
			$date      = getdate( $key->date_created );
			$d         = $date["mday"];
			$m         = $date["mon"];
			$y         = $date["year"];
			$d         = strlen( $d ) == 1 ? "0" . $d : $d;
			$m         = strlen( $m ) == 1 ? "0" . $m : $m;
			$key->date = "$d/$m/$y";

			return $key;
		} else {
			return false;
		}
	}

}
