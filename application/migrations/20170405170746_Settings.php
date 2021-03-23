<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Settings
 * Create or delete the Settings table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */
class Migration_Settings extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field('id');
        $this->dbforge->add_field(array(
            'setting_name' => array(
                'type' => 'varchar(100)',
                'NOT NULL' => TRUE
            ),
            'setting_value' => array(
                'type' => 'mediumtext',
                'NOT NULL' => TRUE
            ),
            'module_name' => array(
                'type' => 'varchar(256)',
                'default' => 'mind_kernel',
            )
        ));
        $this->dbforge->add_key('setting_name', FALSE);
        $this->dbforge->create_table('settings', true);
        $data = array(
            array(
                'setting_name' => "media_replace",
                'setting_value' => "enabled",
            ),
            array(
                'setting_name' => "site_favicon",
                'setting_value' => "site_favicon.png",
            ),
            array(
                'setting_name' => "site_title",
                'setting_value' => "Mind Power Kernel",
            ),
            array(
                'setting_name' => "language",
                'setting_value' => "english",
            ),
            array(
                'setting_name' => "profile_image_max_size",
                'setting_value' => "5000",
            ),
            array(
                'setting_name' => "date_format",
                'setting_value' => "dd/mm/yyyy",
            ),
            array(
                'setting_name' => "timezone",
                'setting_value' => "Africa/Tunis",
            ),
            array(
                'setting_name' => "first_day_of_week",
                'setting_value' => "1",
            ),
            array(
                'setting_name' => "rows_per_page",
                'setting_value' => "25",
            ),
            array(
                'setting_name' => "site_logo",
                'setting_value' => "site_logo.png",
            ),
            array(
                'setting_name' => "email_sent_from_address",
                'setting_value' => "contact@mind.engineering",
            ),
            array(
                'setting_name' => "email_sent_from_name",
                'setting_value' => "Mind Engineering",
            ),
            array(
                'setting_name' => "email_protocol",
                'setting_value' => "smtp",
            ),
            array(
                'setting_name' => "email_smtp_host",
                'setting_value' => "smtp.gmail.com",
            ),
            array(
                'setting_name' => "email_smtp_port",
                'setting_value' => "587",
            ),
            array(
                'setting_name' => "email_smtp_user",
                'setting_value' => "mind.engineering.smtp@gmail.com",
            ),
            array(
                'setting_name' => "email_smtp_pass",
                'setting_value' => "engineering",
            ),
            array(
                'setting_name' => "email_smtp_security_type",
                'setting_value' => "tls",
            ),
            array(
                'setting_name' => "websocket_port",
                'setting_value' => "1788",
            ),
            array(
                'setting_name' => "websocket_ip",
                'setting_value' => gethostbyname(gethostname()),
            ),
            array(
                'setting_name' => "websocket_process",
                'setting_value' => "",
            ),
            array(
                'setting_name' => "accepted_file_formats",
                'setting_value' => "png,jpeg",
            ),
        );
        $this->db->insert_batch('settings', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('settings', true);
    }

}