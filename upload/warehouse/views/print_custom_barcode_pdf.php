<?php
defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

if ($print_barcode['select_item'] == 0) {
    $items = $CI->warehouse_model->get_commodity();
} else {
    //select item check
    if (isset($print_barcode['item_select_print_barcode'])) {
        $item_select_print_barcode_id = array_filter($print_barcode['item_select_print_barcode']);
        $sql_where = 'select * from ' . db_prefix() . 'items where id IN (' . implode(", ", $item_select_print_barcode_id) . ') order by id desc';
        $items = $CI->db->query($sql_where)->result_array();
    }
}

$display_product_name = get_warehouse_option('display_product_name_when_print_barcode');
$display_price = get_warehouse_option('wh_show_price_when_print_barcode');
$print_qty = $print_barcode['print_qty'];
$number_of_label_in_row = $print_barcode['number_of_label_in_row'];
$number_of_row = $print_barcode['number_of_row'];
$label_width = $print_barcode['label_width'];
$barcode_width = $print_barcode['label_width'] - 5;
$label_height = $print_barcode['label_height'];
$barcode_height = (int)($print_barcode['label_height']*0.3);
$horizontal_distance = $print_barcode['horizontal_distance'];
$vertical_distance = $print_barcode['vertical_distance'];
$distance_from_page_left = $print_barcode['distance_from_page_left'];
$distance_from_the_top_of_the_page = $print_barcode['distance_from_the_top_of_the_page'];


$table_font_size = 'font-size:11px;';
$barcode_font_size = 'font-size:13px !important;';
$html_child = '';
$br_tem = 1;
$item_index = 0;
$get_base_currency = get_base_currency();
$currency_id = '';
if ($get_base_currency) {
    $currency_id = $get_base_currency->id;
}

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false);

// Xác định mode
$is_a4_mode = $print_barcode['print_mode'] == 'a4' ? true : false;

$col = 0;
$row = 0;

foreach ($items as $value) {

    // Barcode
    if (!empty($value['commodity_barcode'])) {
        if (!file_exists(WAREHOUSE_PRINT_ITEM . md5($value['commodity_barcode']) . '.svg')) {
            $CI->warehouse_model->getBarcode($value['commodity_barcode']);
        }
        $barcode_path = FCPATH . 'modules/warehouse/uploads/print_item/' . md5($value['commodity_barcode'] ?? '') . '.svg';

    } else {
        $barcode_path = '';
    }

    /*get frist 100 character */
    if (new_strlen($value['description']) > 30) {
        $description = substr($value['description'], 0, 30);
    } else {
        $description = $value['description'];
    }
    $description = strip_tags($description ?? '', []);

    /*get frist 100 character */
    if (new_strlen($value['long_description']) > 30) {
        $description_sub = substr($value['long_description'], 0, 30);
    } else {
        $description_sub = $value['long_description'];
    }
    $description_sub = strip_tags($description_sub ?? '', []);

    //final price: price*Vat
    $tax_value = 0;
    if ($value['tax'] != 0 && $value['tax'] != '') {
        $tax_rate = get_tax_rate($value['tax']);
        if (!is_array($tax_rate) && isset($tax_rate)) {
            $tax_value = $tax_rate->taxrate;
        }
    }

    $rate_after_tax = (float) $value['rate'] + (float) $value['rate'] * $tax_value / 100;
    $price_html = '';
    if ($display_price) {
        $price_html = _l('print_barcode_sale_price') . ': ' . app_format_money($rate_after_tax, $currency_id);
    }


    for ($i = 1; $i <= $print_qty; $i++) {

        // ===== BARCODE paper roll MODE =====
        if (!$is_a4_mode) {

            $pdf->AddPage();

            for ($col = 0; $col < max(1, $number_of_label_in_row); $col++) {

                $x = $distance_from_page_left + ($col * $horizontal_distance);
                $y = $distance_from_the_top_of_the_page;


                $label_html = build_label_html(
                    $value,
                    $description,
                    $description_sub,
                    $price_html,
                    $barcode_path,
                    $label_width,
                    $label_height,
                    $barcode_width,
                    $barcode_height,
                    $barcode_font_size,
                    $display_product_name
                );

                $pdf->writeHTMLCell(
                    $label_width,
                    $label_height,
                    $x,
                    $y,
                    $label_html,
                    0,
                    0,
                    false,
                    false
                );
            }

            continue;
        }

        // ===== A4 MODE =====
        $x = $distance_from_page_left + ($col * $horizontal_distance);
        $y = $distance_from_the_top_of_the_page + ($row * $vertical_distance);

        $label_html = build_label_html(
            $value,
            $description,
            $description_sub,
            $price_html,
            $barcode_path,
            $label_width,
            $label_height,
            $barcode_width,
            $barcode_height,
            $barcode_font_size,
            $display_product_name
        );
        
        $pdf->writeHTMLCell(
            $label_width,
            $label_height,
            $x,
            $y,
            $label_html,
            0,
            0,
            false,
            false
        );

        $col++;

        if ($col >= $number_of_label_in_row) {
            $col = 0;
            $row++;
        }

        if ($row >= $number_of_row) {
            $pdf->AddPage();
            $row = 0;
            $col = 0;
        }
    }
}
