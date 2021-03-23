<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Notifications
 * Create or delete the Notifications table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 * @company http://mind.engineering
 */
class Migration_Notifications extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'created_by' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
            ),
            'notify_to' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'read_by' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'event' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'related' => array(
                'type' => 'TEXT',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('notifications', true);
    }

    public function down()
    {
        $this->dbforge->drop_table('notifications', true);
    }
}