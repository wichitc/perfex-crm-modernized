<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once APPPATH . 'libraries/pdf/App_pdf.php';

/**
 *  movement_summary_report_ pdf
 */
class Movement_summary_report_pdf extends App_pdf {
	protected $movement_summary;

	/**
	 * construct
	 * @param object
	 */
	public function __construct($movement_summary) {

		$movement_summary = hooks()->apply_filters('request_movement_summary_html_pdf_data', $movement_summary);
		$GLOBALS['movement_summary_report_pdf'] = $movement_summary;

		parent::__construct();

		$this->movement_summary = $movement_summary;

		$this->SetTitle($movement_summary['title']);
        $this->setTopMargin(60);

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
            <span>'.$GLOBALS['movement_summary_report_pdf']['clients_invoice_dt_date'].': '.date('d/m/Y H:i').'</span><br>
            <span>'.$GLOBALS['movement_summary_report_pdf']['wh_printed_by'].': '.get_staff_full_name().'</span>
            </td>
        </tr><br>';
        $tbltotal .= '<tr><td width="100%"><h4 class="bold text-center" align="center">'._l('wh_stock_movement_summary_batch_and_serialized_by_warehouse').'</h4></td></tr><br>';

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
		$this->set_view_vars('movement_summary', $this->movement_summary);

		return $this->build();
	}

	/**
	 * type
	 * @return
	 */
	protected function type() {
		return 'movement_summary';
	}

	/**
	 * file path
	 * @return
	 */
	protected function file_path() {
		$customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_requestpdf.php';
		$actualPath = APP_MODULES_PATH . '/warehouse/views/report/movement_summary_report_pdf.php';

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
            'orientation' => 'L',
            'format'      => ['format' => 'A3'],
        ];
    }


    public function new_header($warehouse_name, $from_date, $to_date)
    {
        $font_size     = get_option('pdf_font_size');
        $pdf_font_size = ($font_size + 0);
        $table_font_size = 'font-size:'.$pdf_font_size.'px;';

        $items = '</tbody>
        </table>';
        $close_table = $items;

        // header
        $items = '';
        $items = '<table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop" cellpadding="2" >
        <thead style="' . $table_font_size . '">';
        $items .= '
        <tr style="font-size: '.$pdf_font_size.'px">
        <td align="left" width="20%"><span>'._l('from_date') .': '.$from_date .'</span></td>
        <td align="center" width="20%"><span>'. _l('to_date') .': '. $to_date .'</span></td>
        <td align="right" width="0%"></td>
        </tr>';

        $items .= '
        <tr style="font-size: '.$pdf_font_size.'px">
        <td align="left" width="80%"><span>'. _l('warehouse_filter') .': '. $warehouse_name .'</span></td>
        <td align="left" width="20%"></td>
        <td align="right" width="0%"></td>
        </tr>';

        $items .= '<tr height="30" style="color:black;' . $table_font_size . '; ">';
        $items .= '
        <th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="5%"><strong style="vertical-align:middle;">' . _l('wh_item_code') . '</strong></th>
        <th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="16%"><strong>' . _l('description') . '</strong></th>
        <th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="6%"><strong>' . _l('wh_item_type') . '</strong></th>
        <th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="5%"><strong>' . _l('wh_group') . '</strong></th>
        <th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="5%"><strong>' . _l('wh_sub_group') . '</strong></th>
        <th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="4%"><strong>' . _l('wh_uom') . '</strong></th>
        <th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="6%"><strong>' . _l('wh_batch_no') . '</strong></th>
        <th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="6%"><strong>' . _l('wh_serial_hashtag') . '</strong></th>
        <th rowspan="2" align="left" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="4%"><strong>' . _l('expiry_date') . '</strong></th>
        <th rowspan="2" align="center" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="4%"><strong>' . _l('wh_b_f') . '</strong></th>
        <th colspan="5" align="center" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="12%"><strong>' . _l('wh_als_purchase') . '</strong></th>
        <th colspan="6" align="center" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="12%"><strong>' . _l('als_sales') . '</strong></th>
        <th colspan="4" align="center" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="10%"><strong>' . _l('wh_als_inventory') . '</strong></th>
        <th rowspan="2" align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;" width="5%"><strong>' . _l('wh_bal_qty') . '</strong></th>

        </tr>

        <tr style="color:black;' . $table_font_size . '; ">
        <td align="right" width="3.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_gr') . '</strong></td>
        <td align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_pi') . '</strong></td>
        <td align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_cp') . '</strong></td>
        <td align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_pr') . '</strong></td>
        <td align="right" width="2.2%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_grt') . '</strong></td>
        <td align="right" width="3%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_do') . '</strong></td>
        <td align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_si') . '</strong></td>
        <td align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_cs') . '</strong></td>
        <td align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_drt') . '</strong></td>
        <td align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_srt') . '</strong></td>
        <td align="right" width="1.8%" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_br') . '</strong></td>
        <td align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_stf') . '</strong></td>
        <td align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_adj') . '</strong></td>
        <td align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_rec') . '</strong></td>
        <td align="right" style="border-top: 1px hair black !important; border-bottom: 1.5px hair black !important;"><strong>' . _l('wh_iss') . '</strong></td>
        </tr>
        </thead>
        <tbody class="tbody-main"  style="' . $table_font_size . '" >';
        $start_table = $items;

        return ['close_table' => $close_table, 'start_table' => $start_table];
    }
}