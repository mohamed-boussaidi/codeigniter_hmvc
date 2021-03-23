<?php defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Class Migration_Rsa_keys
 * Create or delete the Rsa_keys table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 * @company http://mind.engineering
 */
class Migration_Rsa_keys extends CI_Migration {
	public function up() {
		$this->dbforge->add_field( array(
			'id'      => array(
				'type'           => 'INT',
				'constraint'     => 11,
				'auto_increment' => true
			),
			'private' => array(
				'type' => 'text',
			),
			'public'  => array(
				'type' => 'text',
			)
		) );
		$this->dbforge->add_key( 'id', true );
		$this->dbforge->create_table( 'rsa_keys', true );
		$data = array(
			array(
				'private' => "",
				'public'  => "",
			)
		);
		$this->db->insert_batch( 'rsa_keys', $data );
	}

	public function down() {
		$this->dbforge->drop_table( 'rsa_keys', true );
	}
}