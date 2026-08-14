<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Internal QA aid: renders every UI primitive in every state so a
 * reviewer can visually diff design changes. Gated to super-admin
 * because it's not a customer-facing screen — and not linked from
 * the menu so it's only reachable by typing the URL.
 *
 * URL: /admin/woocommerce/kitchen_sink
 *
 * @property CI_Output $output
 * @property CI_Loader $load
 */
class Kitchen_sink extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (function_exists('staff_logged_in') && ! staff_logged_in()) {
            redirect(admin_url('authentication/admin'));
            return;
        }

        if (function_exists('is_admin') && ! is_admin()) {
            show_404();
            return;
        }
    }

    public function index(): void
    {
        $this->load->view('kitchen_sink');
    }
}
