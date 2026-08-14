<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

// delete option from here if any
delete_option('perfex_multi_theme_clients', 1);
if ($CI->db->table_exists(db_prefix() . '_multi_theme')) {
    $CI->db->query('DROP TABLE `' . db_prefix() . '_multi_theme`');
}

if ($CI->db->table_exists(db_prefix() . 'multi_theme_setup')) {
    $CI->db->query('DROP TABLE `' . db_prefix() . 'multi_theme_setup`');
}
