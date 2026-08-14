<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration for version 1.1.0
 * Enable client portal by default
 */

class Migration_Version_110 extends App_module_migration
{
    public function up()
    {
        update_option('accountplanning_client_portal_enabled', '1');
    }
}
