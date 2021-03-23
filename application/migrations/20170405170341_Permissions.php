<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Permissions
 * Create or delete the Permissions table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Migration_Permissions extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
            'module_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => "mind_kernel",
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('permissions', true);
        $data = array(
            array("title" => "view_members"),
            array("title" => "view_member"),
            array("title" => "add_member"),
            array("title" => "edit_member"),
        );
        $this->db->insert_batch("permissions", $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('permissions', true);
    }

}