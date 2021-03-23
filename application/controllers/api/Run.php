<?php

if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );
}
require_once( "REST_Controller.php" );
require_once( "RSA.php" );

/**
 * Class Run
 * manipulate the rest API
 * * Supported formats
 * _from_json
 * _from_serialize
 * _from_xml
 * 'xml' => 'application/xml',
 * 'json' => 'application/json',
 * 'jsonp' => 'application/javascript',
 * 'serialized' => 'application/vnd.php.serialized',
 * 'php' => 'text/plain',
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Run extends REST_Controller {

	/**
	 * Run constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * parse HTTP GET requests
	 */
	public function index_get() {
		try {
			if ( $this->get( "arg" ) ) {
				echo "get";
			} else {
				$this->error( 1 );
			}
		} catch ( ErrorException $e ) {
			$this->phpError( $e );
		}
	}

	/**
	 * parse HTTP POST requests
	 * any post request should contain
	 * a base64 encoded key which is the encryption key that you
	 * already encrypted using the RSA public key
	 */
	public function index_post() {
		try {
			if ( $this->post( "key" ) ) {
				$key          = base64_decode( $this->post( "key" ) );
				$dec          = new RSADec();
				$_POST["key"] = null;
				$keys         = $this->Rsa_keys_model->get_keys();
				if ( isset( $_POST["data"] ) ) {
					$value            = base64_decode( $_POST["data"] );
					$decrypted        = $dec->result( $value, $keys->private, $key )->result;
					$decrypted        = unserialize( $decrypted );
					$_POST            = is_array( $decrypted ) ? $decrypted : array();
					$this->_post_args = $_POST;
				}
			}
			if ( $this->post( "arg" ) ) {
				echo "post";
			} else {
				$this->error( 1 );
			}
		} catch ( ErrorException $e ) {
			$this->phpError( $e );
		}
	}

}