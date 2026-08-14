<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{
    public function up()
    {
    	$CI = &get_instance();

        if (!$CI->db->table_exists(db_prefix() . 'multi_theme_setup')) {
            $CI->db->query('CREATE TABLE `' . db_prefix() . 'multi_theme_setup` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `theme_name` varchar(100) NOT NULL,
                `theme_name_slug` varchar(100) NOT NULL,
                `bakground_image` varchar(200) DEFAULT NULL,
                `theme_color` varchar(100) DEFAULT NULL,
                `is_default` int(4) DEFAULT NULL,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
            $CI->db->query("INSERT INTO `" . db_prefix() . "multi_theme_setup` (`theme_name`, `theme_name_slug`, `bakground_image`, `theme_color`, `is_default`) VALUES ('Default', 'default', NULL, '#50719d',1),('Dark', 'dark', NULL,'#131c28',0),('Light', 'light', NULL,'#ffffff',0),('Orange', 'orange', NULL,'#fb923c',0),('Purple', 'purple', NULL,'#c084fc',0),('Green', 'green', NULL,'#4ade80',0);");
        }
        if (!is_dir(PERFEX_MULTI_THEME_MODULE_UPLOADS_FOLDER)) {
            mkdir(PERFEX_MULTI_THEME_MODULE_UPLOADS_FOLDER, 0777, TRUE);
            fopen(PERFEX_MULTI_THEME_MODULE_UPLOADS_FOLDER . 'index.html', 'w');
            $fp = fopen(PERFEX_MULTI_THEME_MODULE_UPLOADS_FOLDER . 'index.html', 'a+');
            if ($fp) {
                fclose($fp);
            }
        }
    }
}