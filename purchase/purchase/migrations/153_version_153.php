<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_153 extends App_module_migration
{
    public function up()
    {
        
    	add_option('show_vendor_note_on_po_pdf', 1);
    }

}