<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Email_templates
 * Create or delete the Email_templates table
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 * @company http://mind.engineering
 */
class Migration_Email_templates extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'template_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ), 'email_subject' => array(
                'type' => 'TEXT',
            ), 'default_message' => array(
                'type' => 'TEXT',
            ), 'custom_message' => array(
                'type' => 'TEXT',
            ), 'module_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => "mind_kernel"
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('email_templates', true);
        $data = array(
            array(
                'template_name' => "login_info",
                'email_subject' => "Login info",
                'default_message' => '<div style="background-color: #eeeeef; padding: 50px 0; "><div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;">  <h1>Login Details</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);">            <p style="color: rgb(85, 85, 85); font-size: 14px;"> Hello {USER_FIRST_NAME}, &nbsp;{USER_LAST_NAME},<br><br>An account has been created for you.</p>            <p style="color: rgb(85, 85, 85); font-size: 14px;"> Please use the following info to login your dashboard:</p>            <hr>            <p style="color: rgb(85, 85, 85); font-size: 14px;">Dashboard URL:&nbsp;<a href="{DASHBOARD_URL}" target="_blank">{DASHBOARD_URL}</a></p>            <p style="color: rgb(85, 85, 85); font-size: 14px;"></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Email: {USER_LOGIN_EMAIL}</span><br></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Password:&nbsp;{USER_LOGIN_PASSWORD}</span></p>            <p style="color: rgb(85, 85, 85);"><br></p>            <p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>        </div>    </div></div>',
            ),
            array(
                'template_name' => "reset_password",
                'email_subject' => "Reset password",
                'default_message' => '<div style="background-color: #eeeeef; padding: 50px 0; "><div style="max-width:640px; margin:0 auto; "><div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Reset Password</h1>
 </div>
 <div style="padding: 20px; background-color: rgb(255, 255, 255); color:#555;">                    <p style="font-size: 14px;"> Hello {ACCOUNT_HOLDER_NAME},<br><br>A password reset request has been created for your account.&nbsp;</p>
                    <p style="font-size: 14px;"> To initiate the password reset process, please click on the following link:</p>
                    <p style="font-size: 14px;"><a href="{RESET_PASSWORD_URL}" target="_blank">Reset Password</a></p>
                    <p style="font-size: 14px;"></p>
                    <p style=""><span style="font-size: 14px; line-height: 20px;"><br></span></p>
<p style=""><span style="font-size: 14px; line-height: 20px;">If you\'ve received this mail in error, it\'s likely that another user entered your email address by mistake while trying to reset a password.</span><br></p>
<p style=""><span style="font-size: 14px; line-height: 20px;">If you didn\'t initiate the request, you don\'t need to take any further action and can safely disregard this email.</span><br></p>
<p style="font-size: 14px;"><br></p>
<p style="font-size: 14px;">{SIGNATURE}</p>
                </div>
            </div>
        </div>',
            ),
            array(
                'template_name' => "member_invitation",
                'email_subject' => "Member invitation",
                'default_message' => '<div style="background-color: #eeeeef; padding: 50px 0; "><div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>Account Invitation</h1>   </div>  <div style="padding: 20px; background-color: rgb(255, 255, 255);">            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">Hello,</span><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="font-weight: bold;"><br></span></span></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><span style="font-weight: bold;">{INVITATION_SENT_BY}</span> has sent you an invitation to join with a team.</span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{INVITATION_URL}" target="_blank">Accept this Invitation</a></span></p>            <p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><br></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;">If you don\'t want to accept this invitation, simply ignore this email.</span><br><br></p>            <p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>        </div>    </div></div>',
            ),
            array(
                'template_name' => "general_notification",
                'email_subject' => "General notification",
                'default_message' => '<div style="background-color: #eeeeef; padding: 50px 0; "> <div style="max-width:640px; margin:0 auto; "> <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>{APP_TITLE}</h1></div><div style="padding: 20px; background-color: rgb(255, 255, 255);"><p style=""><span style="line-height: 18.5714px;">{EVENT_TITLE}</span></p><p style=""><span style="line-height: 18.5714px;">{EVENT_DETAILS}</span></p><p style=""><span style="line-height: 18.5714px;"><br></span></p><p style=""><span style="line-height: 18.5714px;"></span></p><p style=""><span style="color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;"><a style="background-color: #00b393; padding: 10px 15px; color: #ffffff;" href="{NOTIFICATION_URL}" target="_blank">View Details</a></span></p><p style="color: rgb(85, 85, 85); font-size: 14px;"><br></p><p style="color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>  </div> </div></div>',
            ),
            array(
                'template_name' => "signature",
                'email_subject' => "Signature",
                'default_message' => 'Powered By: <a href="http://mind.engineering/" target="_blank">Mind Engineering </a>',
            ),
        );
        $this->db->insert_batch('email_templates', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('email_templates', true);
    }
}