<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Roles
 * Create or delete the Roles table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Migration_Roles extends CI_Migration
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
                'default' => ''
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('roles', true);
        $data = array(
            array(
                'id' => "1",
                'title' => "Member",
            ),
        );
        $this->db->insert_batch('roles', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('roles', true);
    }

}