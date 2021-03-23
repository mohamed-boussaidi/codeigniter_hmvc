<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Social_links
 * Create or delete the Social_links table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Migration_Social_links extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'address' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'facebook' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'twitter' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'linkedin' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'googleplus' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'digg' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'youtube' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'pinterest' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'instagram' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'github' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'tumblr' => array(
                'type' => 'MEDIUMTEXT',
            ),
            'vine' => array(
                'type' => 'MEDIUMTEXT',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('social_links', true);
    }

    public function down()
    {
        $this->dbforge->drop_table('social_links', true);
    }

}