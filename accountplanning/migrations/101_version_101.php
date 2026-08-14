<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Migration for version 1.0.1
 * Fixed TinyMCE mindmap editor loading by resolving conflicts with Perfex CRM's TinyMCE 6.x
 * - Moved module's TinyMCE 4.9.3 loading to footer to override Perfex's version
 * - Set correct baseURL for module's TinyMCE
 * - Ensured leaui_mindmap plugin loads correctly
 */

// No database changes required for this version
