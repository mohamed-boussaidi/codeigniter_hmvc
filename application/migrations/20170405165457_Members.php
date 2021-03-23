<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Members
 * Create or delete the Members table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Migration_Members extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'first_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'NOT NULL' => TRUE,
            ),
            'last_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'NOT NULL' => TRUE,
            ),
            'role_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'NOT NULL' => TRUE,
                'DEFAULT' => 1,
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'NOT NULL' => TRUE,
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'NOT NULL' => TRUE,
            ),
            'image' => array(
                'type' => 'TEXT',
            ),
            'status' => array(
                'type' => 'enum("active","inactive")',
                'DEFAULT' => "active",
                'NOT NULL' => TRUE,
            ),
            'disable_login' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'NOT NULL' => TRUE,
                'DEFAULT' => 0,
            ),
            'address' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'alternative_address' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'phone' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'DEFAULT' => "NULL",
            ),
            'alternative_phone' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'DEFAULT' => "NULL",
            ),
            'dob' => array(
                'type' => 'DATE',
                'DEFAULT' => "0000-00-00",
            ),
            'gender' => array(
                'type' => 'enum("male","female")',
                'DEFAULT' => "male",
                'NOT NULL' => TRUE,
            ),
            'skype' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'DEFAULT' => "0000-00-00 00:00:00",
                'NOT NULL' => TRUE,
            ),
            'notification_checked_at' => array(
                'type' => 'DATETIME',
                'DEFAULT' => "0000-00-00 00:00:00",
                'NOT NULL' => TRUE,
            ),
            'sticky_note' => array(
                'type' => 'LONGTEXT',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('email', FALSE);
        $this->dbforge->create_table('members', true);
        $data = array(
            array(
                "first_name" => "admin",
                "last_name" => "admin",
                "role_id" => "0",
                "email" => "admin@mind.engineering",
                "password" => blowfish_encrypt("admin"),
                "image" => "",
                "status" => "active",
                "disable_login" => "0",
                "address" => "",
                "alternative_address" => "",
                "phone" => "52259212",
                "alternative_phone" => "",
                "dob" => "1917-03-29",
                "gender" => "male",
            ),
        );
        $this->db->insert_batch("members", $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('members', true);
    }

}