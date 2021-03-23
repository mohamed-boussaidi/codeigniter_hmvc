<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Role_permissions
 * Create or delete the Role_permissions table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Migration_Role_permissions extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'role_id' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'permission_id' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('role_permissions', true);
    }

    public function down()
    {
        $this->dbforge->drop_table('role_permissions', true);
    }

}