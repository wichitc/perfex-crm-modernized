<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_341 extends CI_Migration
{
    public function up(): void
    {
        $this->db->empty_table(db_prefix() . 'user_auto_login');

        $this->db->query('ALTER TABLE `' . db_prefix() . 'user_auto_login` MODIFY COLUMN `key_id` VARCHAR(64) NOT NULL;');

        add_option('proposal_auto_convert_leads_to_client_on_client_accept', 0);
        add_option('einvoice_send_as_credit_note_email_attachment', 0);
    }
}
