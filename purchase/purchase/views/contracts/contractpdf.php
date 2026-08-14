<?php defined('BASEPATH') or exit('No direct script access allowed');

// Theese lines should aways at the end of the document left side. Dont indent these lines
$html = <<<EOF
<div >
$contract->content
</div>
EOF;

$html .= '<table><tbody>';
$html .= '<tr>';

if($contract->signed_status == 'signed' || $contract->signed == 1) {

	

	if($contract->signed == 1){
		$html .= '<td>';
		$html .= '<h2>'.get_vendor_company_name($contract->vendor).'</h2>';
		if(file_exists(PURCHASE_MODULE_UPLOAD_FOLDER.'/vendor_sign/'.$contract->id.'/signature.png')){
			$html .= '<img src="'.site_url(PURCHASE_PATH.'vendor_sign/'.$contract->id.'/signature.png'). '" class="img-responsive" alt=""><br><br><br>';
		}

			$html .= '<h2></h2>';
			$html .= '<span class="no-mbot">
		                    &nbsp;&nbsp;'.e(_l('contract_signed_by') . ": {$contract->acceptance_firstname} {$contract->acceptance_lastname}").'
		                </span><br>
		                <span class="no-mbot">
		                    '.e(_l('contract_signed_date') . ': ' . _dt($contract->acceptance_date)).'
		                </span><br>
		                <span class="no-mbot">
		                    '.e(_l('email') . ': ' . ($contract->acceptance_email)).'
		                </span><br>
		                <span class="no-mbot">
		                    '.e(_l('contract_signed_ip') . ": {$contract->acceptance_ip}").'
		                </span>';
		$html .= '</td>';
	}

	if($contract->signed_status == 'signed'){
		$html .= '<td>';
		$html .= '<h2>'.get_option('invoice_company_name').'</h2>';
		if(file_exists(PURCHASE_MODULE_UPLOAD_FOLDER.'/contract_sign/'.$contract->id.'/signature.png')){
			$html .= '<img src="'.site_url(PURCHASE_PATH.'contract_sign/'.$contract->id.'/signature.png'). '" class="img-responsive" alt=""><br><br><br>';
		}

			$html .= '<h2></h2>';
			$html .= '<span class="no-mbot">
		                    &nbsp;&nbsp;'._l('contract_signed_by') . ': '.get_staff_full_name($contract->signer).'
		                </span><br>
		                <span class="no-mbot">
		                    '.e(_l('contract_signed_date') . ': ' . _d($contract->signed_date)).'
		                </span>';
		$html .= '</td>';
	}

	

}else{
	$html .= '<td></td>';
}

$html .= '</tr>';
$html .= '</tbody></table>';


$pdf->writeHTML($html, true, false, true, false, '');
