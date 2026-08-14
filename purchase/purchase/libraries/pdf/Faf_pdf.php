<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Faf_pdf extends App_pdf
{
    protected $faf;

    public function __construct($faf)
    {
        $faf                = hooks()->apply_filters('request_html_pdf_data', $faf);
        $GLOBALS['faf_pdf'] = $faf;

        parent::__construct();

        $this->faf = $faf;

        $this->SetTitle(_l('faf'));
        # Don't remove these lines - important for the PDF layout
        $this->faf = $this->fix_editor_html($this->faf);
    }

    public function prepare()
    {
        $this->set_view_vars('faf', $this->faf);

        return $this->build();
    }

    protected function type()
    {
        return 'faf';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
        $actualPath = APP_MODULES_PATH . '/purchase/views/faf_requests/fafpdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}