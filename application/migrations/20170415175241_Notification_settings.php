<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Notification_settings
 * Create or delete the Notification_settings table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 * @company http://mind.engineering
 */
class Migration_Notification_settings extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'event' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
            'enable_email' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ),
            'enable_web' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ),
            'notify_to_roles' => array(
                'type' => 'TEXT',
            ),
            'notify_to_members' => array(
                'type' => 'TEXT',
            ),
            'notify_to_types' => array(
                'type' => 'TEXT',
            ),
            'module_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => 'mind_kernel',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('notification_settings', true);
        $data = array(
            array(
                'event' => "new_member",
            ),
        );
        $this->db->insert_batch('notification_settings', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('notification_settings', true);
    }
}