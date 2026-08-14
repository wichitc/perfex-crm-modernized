<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_161 extends App_module_migration
{
   public function up()
   {
     create_email_template('Forgot Password', '<h2>Create a new password</h2>
     Forgot your password?<br /> To create a new password, just follow this link:<br /> <br /><a href="{reset_password_url}">Reset Password</a><br /> <br /> You received this email, because it was requested by a {companyname}&nbsp;user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same. <br /><br /> {email_signature}', 'purchase_order', 'Create New Password', 'vendor-contact-forgot-password');

    create_email_template('Password Reset - Confirmation', '<strong><span style="font-size: 14pt;">You have changed your password.</span><br /></strong><br /> Please, keep it in your records so you dont forget it.<br /> <br /><br /><br />If this wasnt you, please contact us.<br /><br />{email_signature}', 'purchase_order', 'Your password has been changed', 'vendor-contact-password-reseted');
   }

}