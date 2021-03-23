<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Logs
 * Create or delete the Logs table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Migration_Logs extends CI_Migration 
{
	public function up() 
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'uri' => array(
				'type' => 'MEDIUMTEXT',
			),
			'method' => array(
				'type' => 'MEDIUMTEXT',
			),
			'params' => array(
				'type' => 'MEDIUMTEXT',
				'DEFAULT' => NULL,
			),
			'api_key' => array(
				'type' => 'MEDIUMTEXT',
			),
			'ip_address' => array(
				'type' => 'MEDIUMTEXT',
			),
			'time' => array(
				'type' => 'TIMESTAMP',
				'NOT NULL' => 1,
				'default' => "0000-00-00 00:00:00",
				'on update' => "0000-00-00 00:00:00",
			),
			'rtime' => array(
				'type' => 'TIMESTAMP',
				'NOT NULL' => 1,
				'default' => "0000-00-00 00:00:00",
			),
			'authorized' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'NOT NULL' => 1,
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('logs', true);
		$logs_table = $this->db->dbprefix("logs");
		$this->db->query(join(array(
			"ALTER TABLE `$logs_table` ".
			"CHANGE `time` `time` ".
			"TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP();"
		)));
	}

	public function down() 
	{
		$this->dbforge->drop_table('logs', true);
	}
}