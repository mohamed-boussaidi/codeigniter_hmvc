<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Keys
 * Create or delete the Keys table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 * @company http://mind.engineering
 */
class Migration_Rest_keys extends CI_Migration
{
	public function up() 
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'NOT NULL' => 1
			),
			'key' => array(
				'type' => 'VARCHAR',
				'constraint' => 40,
				'NOT NULL' => 1
			),
			'level' => array(
				'type' => 'INT',
				'constraint' => 2,
				'NOT NULL' => TRUE
			),
			'ignore_limits' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'NOT NULL' => 1,
				'DEFAULT' => 0
			),
			'is_private_key' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'NOT NULL' => 1,
				'DEFAULT' => 0
			),
			'ip_addresses' => array(
				'type' => 'TEXT',
			),
			'date_created' => array(
				'type' => 'INT',
				'NOT NULL' => 1,
				'constraint' => 11
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table( 'rest_keys', true);
	}

	public function down() 
	{
		$this->dbforge->drop_table( 'rest_keys', true);
	}
}