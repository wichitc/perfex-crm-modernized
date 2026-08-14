<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Debug controller - minimal test to verify accountplanning module routing.
 * URL: accountplanning/debug
 * Remove this file after debugging.
 */
class Debug extends CI_Controller
{
    public function index()
    {
        header('Content-Type: text/plain; charset=utf-8');
        echo "accountplanning module routing OK\n";
        echo "Module: accountplanning\n";
        echo "Controller: Debug\n";
        echo "Time: " . date('Y-m-d H:i:s') . "\n";
    }
}
