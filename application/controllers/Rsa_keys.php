<?php if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );
}

include_once "Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

/**
 * manage rsa keys
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Rsa_keys extends Init implements Mind_controller {

	/**
	 * Rsa_keys constructor.
	 * only admin have access to this class
	 */
	public function __construct() {
		parent::__construct();
		$this->only_admin();
	}

	/**
	 * load the main view
	 */
	public function index() {
		view( "rsa_keys/index", array(
				"rsa_keys" => $this->Rsa_keys_model->get_keys()
			)
		);
	}

	/**
	 * generate RSA public and private key then store them into the database
	 */
	public function generate() {
		$keys    = generate_rsa_keys();
		$keys[0] = isset( $keys[0] ) ? base64_encode( $keys[0] ) : "";
		$keys[1] = isset( $keys[1] ) ? base64_encode( $keys[1] ) : "";
		$data    = array( "private" => $keys[0], "public" => $keys[1] );
		if ( $this->Rsa_keys_model->save( $data, 1 ) ) {
			echo json_encode( array( "reload" => true ) );
		}
	}

	public function view() {
		show_404();
	}

	public function modal_form() {
		show_404();
	}

	public function save() {
		show_404();
	}

	public function delete() {
		show_404();
	}

	public function list_data() {
		show_404();
	}

	public function make_row( $data = stdClass::class, $id = false ) {
		show_404();
	}
}
