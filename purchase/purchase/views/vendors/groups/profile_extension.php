<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('purchase_vendor_profile_extension_content', [
    'group' => $group ?? '',
    'vendor' => $client ?? null,
]); ?>
