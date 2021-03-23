<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Features
 * Create or delete the Features table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 * @company http://mind.engineering
 */
class Migration_Features extends CI_Migration 
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
				'constraint' => 256,
				'NOT NULL' => 1
			),
			'enabled' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 1,
				'NOT NULL' => 1,
			),
			'module' => array(
				'type' => "VARCHAR",
				'constraint' => 256,
				'NOT NULL' => 1
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('features', true);
	}

	public function down() 
	{
		$this->dbforge->drop_table('features', true);
	}
}