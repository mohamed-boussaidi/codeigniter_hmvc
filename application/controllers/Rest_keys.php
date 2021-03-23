<?php if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );
}

include_once "Init.php";
include_once APPPATH . "interfaces/Mind_controller.php";

/**
 * this class provide REST API key configuration
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Rest_keys extends Init implements Mind_controller {

	/**
	 * Rest_keys constructor.
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
		view( "rest_keys/index" );
	}

	public function view() {
		show_404();
	}

	/**
	 * edit a REST key
	 */
	public function modal_form() {
		$id       = $this->input->post( 'id' );
		$rest_key = $id ? $this->Rest_keys_model->get_one( $id ) : new stdClass();
		$this->load->view( "rest_keys/modal_form", array( "rest_key" => $rest_key ) );
	}

	/**
	 * generate a 40 chars random key and save it to the database
	 */
	public function save() {
		validate_submitted_data( array(
			"id"   => "numeric",
			"name" => "required",
		) );
		$id = $this->input->post( 'id' );
		if ( $id ) {
			$data = array(
				"name" => $this->input->post( 'name' ),
			);
		} else {
			$date         = new DateTime();
			$date_created = $date->getTimestamp();
			$data         = array(
				"name"         => $this->input->post( 'name' ),
				"key"          => get_random_string( 40, true ),
				"date_created" => $date_created,
			);
		}
		saving_result(
			$this,
			$this->Rest_keys_model->save( $data, $id )
		);
	}

	/**
	 * delete a specific REST key
	 */
	public function delete() {
		validate_submitted_data( array(
			"id" => "numeric|required"
		) );
		$id = $this->input->post( 'id' );
		deleting_result( $this, $this->Rest_keys_model->delete_one( $id ) );
	}

	/**
	 * list REST keys
	 */
	public function list_data() {
		$keys = $this->Rest_keys_model->get_keys();
		$data = array();
		foreach ( $keys as $key ) {
			array_push( $data, $this->make_row( $key ) );
		}
		echo json_encode( array( "data" => $data ) );
	}

	/**
	 * Parse REST keys row
	 * @param string $data
	 * @param bool $id
	 *
	 * @return array
	 */
	public function make_row( $data = stdClass::class, $id = false ) {
		if ( $id ) {
			$data = $this->Rest_keys_model->get_key( $id );
		}

		return array(
			dt_row_trigger(),
			get_property( $data, "name" ),
			get_property( $data, "key" ),
			get_property( $data, "date" ),
			modal_anchor(
				get_uri( "rest_keys/modal_form" ),
				"<i class='fa fa-pencil'></i>",
				array(
					"class"        => "edit",
					"title"        => plang( 'edit_element', array( "key" ) ),
					"data-post-id" => get_property( $data, "id" )
				)
			)
			. js_anchor(
				"<i class='fa fa-times fa-fw'></i>",
				array(
					'title'           => plang( 'delete_element', array( "key" ) ),
					"class"           => "delete",
					"data-id"         => get_property( $data, "id" ),
					"data-action-url" => get_uri( "rest_keys/delete" ),
					"data-action"     => "delete"
				)
			)
		);
	}
}
