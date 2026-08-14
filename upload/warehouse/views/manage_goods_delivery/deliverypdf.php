<?php defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . mb_strtoupper(_l('delivery')) . '</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># '.$GLOBALS['goods_delivery']->goods_delivery_code.'</b>';
// purchase_pdf
// Add logo
$info_left_column .= pdf_logo_url();

// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->ln(9);

// Theese lines should aways at the end of the document left side. Dont indent these lines
$html = <<<EOF
<div>
$delivery
</div>
EOF;
$pdf->writeHTML($html, true, false, true, false, '');
