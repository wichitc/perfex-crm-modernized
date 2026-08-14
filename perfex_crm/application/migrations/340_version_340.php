<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_340 extends CI_Migration
{
    public function up(): void
    {
        if (! $this->db->field_exists('is_optional', db_prefix() . 'itemable')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'itemable` ADD `is_optional` TINYINT NOT NULL DEFAULT \'0\' AFTER `unit`;');
        }
        if (! $this->db->field_exists('is_selected', db_prefix() . 'itemable')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'itemable` ADD `is_selected` TINYINT NOT NULL DEFAULT \'1\' AFTER `is_optional`;');
        }

        if (! $this->db->field_exists('title', db_prefix() . 'project_notes')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'project_notes` ADD `title` VARCHAR(255) NULL AFTER `project_id`;');
        }

        if (! $this->db->field_exists('dateadded', db_prefix() . 'project_notes')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'project_notes` ADD COLUMN `dateadded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP() AFTER `staff_id`;');
        }

        if (! $this->db->field_exists('content_type', db_prefix() . 'templates')) {
            $this->db->query('ALTER TABLE `' . db_prefix() . 'templates` ADD `content_type` VARCHAR(20) NOT NULL DEFAULT "html" AFTER `content`;');
        }
    }
}
