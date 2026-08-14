<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration for version 1.0.0
 * Initial migration - database schema was created via install.php on first activation.
 * This migration exists for version tracking when upgrading from pre-migration installations.
 */

class Migration_Version_001 extends App_module_migration
{
    public function up()
    {
        // No database changes - install.php handles new installations
    }
}
