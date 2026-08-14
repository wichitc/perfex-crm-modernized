<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPPATH . 'libraries/pdf/App_pdf.php';

/**
 *  stock_balance pdf
 */
class Stock_balance_report_pdf extends App_pdf {
	protected $stock_balance;

	/**
	 * construct
	 * @param object
	 */
	public function __construct($stock_balance) {

		$stock_balance = hooks()->apply_filters('request_stock_balance_html_pdf_data', $stock_balance);
		$GLOBALS['stock_balance_report_pdf'] = $stock_balance;

		parent::__construct();

		$this->stock_balance = $stock_balance;

		$this->SetTitle($stock_balance['title']);
        $this->setTopMargin(55);

		# Don't remove these lines - important for the PDF layout
	}


    public function Header() {

        // Background color
        $dimensions = $this->getPageDimensions();
        $font_size = get_option('pdf_font_size');
        $pdf_font_size = ($font_size +5);

                // get organization_info 
        $organization_info = '<div style="color:#424242;" style="font-size: '.$pdf_font_size.'px">';
        $organization_info .= format_organization_info();
        $organization_info .= '</div>';

        $tbltotal = '';
        $tbltotal .= '<table cellpadding="2" >';
        $tbltotal .= '
        <tr>
            <td align="left" width="30%">'.pdf_logo_url().'</td>
            <td align="center" width="40%">'.$organization_info.'</td>
            <td align="right" width="30%" style="font-size: '.$pdf_font_size.'px">
            <span>'.$GLOBALS['stock_balance_report_pdf']['clients_invoice_dt_date'].': '.date('d/m/Y H:i').'</span><br>
            <span>'.$GLOBALS['stock_balance_report_pdf']['wh_printed_by'].': '.get_staff_full_name().'</span>
            </td>
        </tr><br>';
        $tbltotal .= '<tr><td width="100%"><h4 class="bold text-center" align="center">'._l('wh_stock_balance_detail_batch_and_serialized_by_warehouse').'</h4></td></tr><br>';

        $tbltotal .= '</table>';



        $this->Ln(10);
        $this->writeHTML($tbltotal, true, false, false, false);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

	/**
	 * prepare
	 * @return
	 */
	public function prepare() {
		$this->set_view_vars('stock_balance', $this->stock_balance);

		return $this->build();
	}

	/**
	 * type
	 * @return
	 */
	protected function type() {
		return 'stock_balance';
	}

	/**
	 * file path
	 * @return
	 */
	protected function file_path() {
		$customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
		$actualPath = APP_MODULES_PATH . '/warehouse/views/report/stock_balance_report_pdf.php';

		if (file_exists($customPath)) {
			$actualPath = $customPath;
		}

		return $actualPath;
	}

    /**
     * get format array
     * @return [type] 
     */
    public function get_format_array()
    {
        return  [
            'orientation' => 'P',
            'format'      => ['format' => 'A3'],
        ];
    }
}