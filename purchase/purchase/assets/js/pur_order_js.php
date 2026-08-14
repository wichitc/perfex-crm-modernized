<script>

var transaction_currency_rate = 0;

$(function(){
  "use strict";

    init_ajax_search("customer", ".client-ajax-search");
    init_po_currency();
    // Maybe items ajax search
    <?php if(get_purchase_option('item_by_vendor') != 1){ ?>
      init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'purchase/pur_commodity_code_search');
    <?php } ?>

    pur_calculate_total();
    init_pur_ajax_search('vendors', '#vendor');

    validate_purorder_form();
    function validate_purorder_form(selector) {

        selector = typeof(selector) == 'undefined' ? '#pur_order-form' : selector;

        <?php if(get_option('pur_order_project_required_condition') == 1){ ?>
          appValidateForm($(selector), {
              project: 'required',
              pur_order_number: 'required',
              order_date: 'required',
              vendor: 'required',
          });
        <?php }else{ ?>
          appValidateForm($(selector), {
              
              pur_order_number: 'required',
              order_date: 'required',
              vendor: 'required',
          });
        <?php } ?>
    }

    $("body").on('change', 'select[name="item_select"]', function () {
      var itemid = $(this).selectpicker('val');
      if (itemid != '') {
        pur_add_item_to_preview(itemid);
      }
    });

    $("body").on('change', 'select.taxes', function () {
      pur_calculate_total();
    });

    $("body").on('change', 'select[name="currency"]', function () {
      var currency_id = $(this).val();
      var data = {};
      data.clients = $('select[name="clients[]"]').val();
      if(currency_id != ''){
        $.post(admin_url + 'purchase/get_currency_rate/'+currency_id, data).done(function(response){
          response = JSON.parse(response);
          if(response.currency_rate != 1){
            $('#currency_rate_div').removeClass('hide');

            $('input[name="currency_rate"]').val(response.currency_rate).change();
            if(transaction_currency_rate != 0){
              $('input[name="currency_rate"]').val(transaction_currency_rate).change();
              transaction_currency_rate = 0;

            }

            $('#convert_str').html(response.convert_str);
            $('.th_currency').html(response.currency_name);
          }else{
            $('input[name="currency_rate"]').val(response.currency_rate).change();
            if(transaction_currency_rate != 0){
              $('input[name="currency_rate"]').val(transaction_currency_rate).change();
              transaction_currency_rate = 0;
            }
            $('#currency_rate_div').addClass('hide');
            $('#convert_str').html(response.convert_str);
            $('.th_currency').html(response.currency_name);

          }

          $('select[name="sale_invoice[]"]').html(response.html);
          $('select[name="sale_invoice[]"]').selectpicker('refresh');


        });
      }else{
        alert_float('warning', "<?php echo _l('please_select_currency'); ?>" )
      }
      init_po_currency();
    });

    $("input[name='currency_rate']").on('change', function () { 
        var currency_rate = $(this).val();
        var rows = $('.table.has-calculations tbody tr.item');
        $.each(rows, function () { 
          var old_price = $(this).find('td.rate input[name="og_price"]').val();
          var new_price = currency_rate*old_price;
          $(this).find('td.rate input[type="number"]').val(accounting.toFixed(new_price, app.options.decimal_places)).change();

        });
    });


     $("body").on("change", 'select[name="discount_type"]', function () {
        // if discount_type == ''
        if ($(this).val() === "") {
          $('input[name="order_discount"]').val(0);
        }
        // Recalculate the total
        pur_calculate_total();
      });

     <?php if(isset($pur_order)){ ?>
      $.post(admin_url + 'purchase/get_html_approval_setting_for_po/'+ <?php echo pur_html_entity_decode($pur_order->id); ?>).done(function(response) {
         response = JSON.parse(response);

          $('.list_approve').html('');
          $('.list_approve').append(response);
      init_selectpicker();

      });
     <?php } ?>

      var addMoreVendorsInputKey = $('.list_approve select[name*="approver"]').length;
     $("body").on('click', '.new_vendor_requests', function() {
       if ($(this).hasClass('disabled')) { return false; }
      
         addMoreVendorsInputKey = $('.list_approve select[name*="approver"]').length;
        var newattachment = $('.list_approve').find('#item_approve').eq(0).clone().appendTo('.list_approve');
        newattachment.find('button[data-toggle="dropdown"]').remove();
        newattachment.find('select').selectpicker('refresh');

        newattachment.find('button[data-id="approver[0]"]').attr('data-id', 'approver[' + addMoreVendorsInputKey + ']');
        newattachment.find('label[for="approver[0]"]').attr('for', 'approver[' + addMoreVendorsInputKey + ']');
        newattachment.find('select[name="approver[0]"]').attr('name', 'approver[' + addMoreVendorsInputKey + ']');
        newattachment.find('select[id="approver[0]"]').attr('id', 'approver[' + addMoreVendorsInputKey + ']').selectpicker('refresh');
        newattachment.find('select[data-id="0"]').attr('data-id', addMoreVendorsInputKey);

        newattachment.find('button[data-id="staff[0]"]').attr('data-id', 'staff[' + addMoreVendorsInputKey + ']');
        newattachment.find('label[for="staff[0]"]').attr('for', 'staff[' + addMoreVendorsInputKey + ']');
        newattachment.find('select[name="staff[0]"]').attr('name', 'staff[' + addMoreVendorsInputKey + ']');
        newattachment.find('select[id="staff[0]"]').attr('id', 'staff[' + addMoreVendorsInputKey + ']').selectpicker('refresh');

        newattachment.find('button[data-id="action[0]"]').attr('data-id', 'action[' + addMoreVendorsInputKey + ']');
        newattachment.find('label[for="action[0]"]').attr('for', 'action[' + addMoreVendorsInputKey + ']');
        newattachment.find('select[name="action[0]"]').attr('name', 'action[' + addMoreVendorsInputKey + ']');
        newattachment.find('select[id="action[0]"]').attr('id', 'action[' + addMoreVendorsInputKey + ']').selectpicker('refresh');

        newattachment.find('#is_staff_0').attr('id', 'is_staff_' + addMoreVendorsInputKey);
        newattachment.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
        newattachment.find('button[name="add"]').removeClass('new_vendor_requests').addClass('remove_vendor_requests').removeClass('btn-success').addClass('btn-danger');

        $('select[name="approver[' + addMoreVendorsInputKey + ']"]').on('change', function(){
          let index = $(this).attr('data-id');
          if($(this).val() == 'staff'){
            $('#is_staff_' + index).removeClass('hide');
            $('select[name="staff['+ index +']"').attr('required', 'required');
          }else{
            $('#is_staff_' + index).addClass('hide');
            $('select[name="staff['+ index +']"').removeAttr('required');
          }
        });

        addMoreVendorsInputKey++;

    });
    $("body").on('click', '.remove_vendor_requests', function() {
        $(this).parents('#item_approve').remove();
    });

    $('body').on('change', '.approver_class' , function(){
        addMoreVendorsInputKey = $('.list_approve select[name*="approver"]').length;

        for(let i = 0; i < addMoreVendorsInputKey; i++){
          if($('select[name="approver['+i+']"]').val() == 'staff'){
            $('#is_staff_' + i).removeClass('hide');
            $('select[name="staff['+ i +']"').attr('required', 'required');
        }else{
            $('#is_staff_' + i).addClass('hide');
            $('select[name="staff['+ i +']"').removeAttr('required');
        }
        }
    });

});

var lastAddedItemKey = null;

function estimate_by_vendor(invoker){
  "use strict";
  var po_number = '<?php echo pur_html_entity_decode( $pur_order_number); ?>';
  if(invoker.value != 0){
    $.post(admin_url + 'purchase/estimate_by_vendor/'+invoker.value).done(function(response){
      response = JSON.parse(response);
      $('select[name="estimate"]').html('');
      $('select[name="estimate"]').append(response.result);
      $('select[name="estimate"]').selectpicker('refresh');
      $('#vendor_data').html('');
      $('#vendor_data').append(response.ven_html);
      $('select[name="currency"]').val(response.currency_id).change();

      <?php if(get_option('po_only_prefix_and_number') != 1){ ?>
      $('input[name="pur_order_number"]').val(po_number+'-'+response.company);
      <?php } ?>
      <?php if(get_purchase_option('item_by_vendor') == 1){ ?>
        if(response.option_html != ''){
         $('#item_select').html(response.option_html);
         $('.selectpicker').selectpicker('refresh');
        }else if(response.option_html == ''){
          init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'purchase/pur_commodity_code_search/purchase_price/can_be_purchased/'+invoker.value);
        }
        
       <?php } ?>
    });
  }
}

function coppy_pur_estimate(){
  "use strict";
  var pur_estimate = $('select[name="estimate"]').val();
  if(pur_estimate != ''){
    $.post(admin_url + 'purchase/coppy_pur_estimate/'+pur_estimate).done(function(response){
        response = JSON.parse(response);
        if(response){ 
          transaction_currency_rate = response.currency_rate;



          $('select[name="currency"]').val(response.currency).change();
          $('input[name="currency_rate"]').val(response.currency_rate).change();
          $('input[name="shipping_fee"]').val(response.shipping_fee).change();

          $('select[name="discount_type"]').val(response.discount_type).change();

          $('.invoice-item table.invoice-items-table.items tbody').find('.main').remove();
          $('.invoice-item table.invoice-items-table.items tbody').find('.delete-po-item').click();
          $('.invoice-item table.invoice-items-table.items tbody').append(response.list_item);

          setTimeout(function () {
            pur_calculate_total();
          }, 15);

          init_selectpicker();
          pur_reorder_items('.invoice-item');
          pur_clear_item_preview_values('.invoice-item');
          $('body').find('#items-warning').remove();
          $("body").find('.dt-loader').remove();
          $('#item_select').selectpicker('val', '');
        }
    });
  }
}

function coppy_pur_request(){
  "use strict";
  var pur_request = $('select[name="pur_request"]').val();
  var vendor = $('select[name="vendor"]').val();
  if(pur_request != ''){
    $.post(admin_url + 'purchase/coppy_pur_request_for_po/'+pur_request+'/'+vendor).done(function(response){
        response = JSON.parse(response);
        if(response){ 
          transaction_currency_rate = response.currency_rate;

          $('select[name="estimate"]').html(response.estimate_html);
          $('select[name="estimate"]').selectpicker('refresh');

          $('select[name="currency"]').val(response.currency).change();
          $('input[name="currency_rate"]').val(response.currency_rate).change();


          $('select[name="type"]').val(response.type).change();

          $('.invoice-item table.invoice-items-table.items tbody').find('.main').remove();
          $('.invoice-item table.invoice-items-table.items tbody').find('.delete-po-item').click();
          $('.invoice-item table.invoice-items-table.items tbody').append(response.list_item);

          setTimeout(function () {
            pur_calculate_total();
          }, 15);

          init_selectpicker();
          pur_reorder_items('.invoice-item');
          pur_clear_item_preview_values('.invoice-item');
          $('body').find('#items-warning').remove();
          $("body").find('.dt-loader').remove();
          $('#item_select').selectpicker('val', '');
        }   
    });
  }
}


function client_change(el){
  "use strict";

  var client = $(el).val();
  var data = {};
  data.client = client;
  data.currency = $('select[name="currency"]').val();

  $.post(admin_url + 'purchase/inv_by_client', data).done(function(response){
    response = JSON.parse(response);
    $('select[name="sale_invoice[]"]').html(response.html);
    $('select[name="sale_invoice[]"]').selectpicker('refresh');
  });
  
}

/**
 * { coppy sale invoice }
 */
function coppy_sale_invoice(){
  "use strict";
  var sale_invoice = $('select[name="sale_invoice[]"]').val();
  var data = {};
  data.sale_invoice = sale_invoice;

  if(sale_invoice != ''){
    $.post(admin_url + 'purchase/coppy_sale_invoice_po', data).done(function(response){
        response = JSON.parse(response);

        if(response){ 
          
          $('.invoice-item table.invoice-items-table.items tbody').find('.main').remove();
          $('.invoice-item table.invoice-items-table.items tbody').find('.delete-po-item').click();
          $('.invoice-item table.invoice-items-table.items tbody').append(response.list_item);

          setTimeout(function () {
            pur_calculate_total();
          }, 15);

          init_selectpicker();
          pur_reorder_items('.invoice-item');
          pur_clear_item_preview_values('.invoice-item');
          $('body').find('#items-warning').remove();
          $("body").find('.dt-loader').remove();
          $('#item_select').selectpicker('val', '');
        }   
    });
  }else{
    $('.invoice-item table.invoice-items-table.items tbody').find('.delete-po-item').click();
    alert_float('warning', '<?php echo _l('please_chose_sale_invoice'); ?>');
  }

}

function pur_calculate_total(from_discount_money){
  "use strict";
  if ($('body').hasClass('no-calculate-total')) {
    return false;
  }

  var calculated_tax,
    taxrate,
    item_taxes,
    row,
    _amount,
    _tax_name,
    taxes = {},
    taxes_rows = [],
    subtotal = 0,
    total = 0,
    total_money = 0,
    total_tax_money = 0,
    quantity = 1,
    total_discount_calculated = 0,
    item_total_payment,
    rows = $('.table.has-calculations tbody tr.item'),
    subtotal_area = $('#subtotal'),
    discount_area = $('#discount_area'),
    adjustment = $('input[name="adjustment"]').val(),
    // discount_percent = $('input[name="discount_percent"]').val(),
    discount_percent = 'before_tax',
    discount_fixed = $('input[name="discount_total"]').val(),
    discount_total_type = $('.discount-total-type.selected'),
    discount_type = $('select[name="discount_type"]').val(),
    additional_discount = $('input[name="additional_discount"]').val(),
    add_discount_type = $('select[name="add_discount_type"]').val();

    var shipping_fee = $('input[name="shipping_fee"]').val();
    if(shipping_fee == ''){
      shipping_fee = 0;
      $('input[name="shipping_fee"]').val(0);
    }

  $('.wh-tax-area').remove();

    $.each(rows, function () {
    var item_discount = 0;
    var item_discount_money = 0;
    var item_discount_from_percent = 0;
    var item_discount_percent = 0;
    var item_tax = 0,
        item_amount  = 0;

    quantity = $(this).find('[data-quantity]').val();
    if (quantity === '') {
      quantity = 1;
      $(this).find('[data-quantity]').val(1);
    }
    item_discount_percent = $(this).find('td.discount input').val();
    item_discount_money = $(this).find('td.discount_money input').val();

    if (isNaN(item_discount_percent) || item_discount_percent == '') {
      item_discount_percent = 0;
    }

    if (isNaN(item_discount_money) || item_discount_money == '') {
      item_discount_money = 0;
    }

    if(from_discount_money == 1 && item_discount_money > 0){
      $(this).find('td.discount input').val('');
    }

    _amount = accounting.toFixed($(this).find('td.rate input').val() * quantity, app.options.decimal_places);
    item_amount = _amount;
    _amount = parseFloat(_amount);

    $(this).find('td.into_money').html(format_money(_amount));
    $(this).find('td._into_money input').val(_amount);

    subtotal += _amount;
    row = $(this);
    item_taxes = $(this).find('select.taxes').val();

    if(discount_type == 'after_tax'){
      if (item_taxes) {
        $.each(item_taxes, function (i, taxname) {
          taxrate = row.find('select.taxes [value="' + taxname + '"]').data('taxrate');
          calculated_tax = (_amount / 100 * taxrate);
          item_tax += calculated_tax;
          if (!taxes.hasOwnProperty(taxname)) {
            if (taxrate != 0) {
              _tax_name = taxname.split('|');
              var tax_row = '<tr class="wh-tax-area"><td>' + _tax_name[0] + '(' + taxrate + '%)</td><td id="tax_id_' + slugify(taxname) + '"></td></tr>';
              $(subtotal_area).after(tax_row);
              taxes[taxname] = calculated_tax;
            }
          } else {
                      // Increment total from this tax
                      taxes[taxname] = taxes[taxname] += calculated_tax;
                  }
              });
      }
    }
    
      //Discount of item
      if( item_discount_percent > 0 && from_discount_money != 1){

        if(discount_type == 'after_tax'){
          item_discount_from_percent = (parseFloat(item_amount) + parseFloat(item_tax) ) * parseFloat(item_discount_percent) / 100;
        }else if(discount_type == 'before_tax'){
          item_discount_from_percent = parseFloat(item_amount) * parseFloat(item_discount_percent) / 100;
        }

        if(item_discount_from_percent != item_discount_money){
          item_discount_money = item_discount_from_percent;
        }
      }

      if( item_discount_money > 0){
        item_discount = parseFloat(item_discount_money);
      }

     
      // Append value to item
      total_discount_calculated += parseFloat(item_discount);
      $(this).find('td.discount_money input').val(item_discount);


      if(discount_type == 'before_tax'){ 
        if (item_taxes) {
          var after_dc_amount = _amount - parseFloat(item_discount);
          $.each(item_taxes, function (i, taxname) {
              taxrate = row.find('select.taxes [value="' + taxname + '"]').data('taxrate');
              calculated_tax = (after_dc_amount / 100 * taxrate);
              item_tax += calculated_tax;
              if (!taxes.hasOwnProperty(taxname)) {
                if (taxrate != 0) {
                  _tax_name = taxname.split('|');
                  var tax_row = '<tr class="wh-tax-area"><td>' + _tax_name[0] + '(' + taxrate + '%)</td><td id="tax_id_' + slugify(taxname) + '"></td></tr>';
                  $(subtotal_area).after(tax_row);
                  taxes[taxname] = calculated_tax;
                }
              } else {
                  // Increment total from this tax
                  taxes[taxname] = taxes[taxname] += calculated_tax;
              }
          });
        }
      }

      var after_tax = _amount + item_tax;
      var before_tax = _amount;

      item_total_payment = parseFloat(item_amount) + parseFloat(item_tax) - parseFloat(item_discount);

      $(this).find('td.total_after_discount input').val(item_total_payment);

    $(this).find('td.label_total_after_discount').html(format_money(item_total_payment));

    $(this).find('td._total').html(format_money(after_tax));
    $(this).find('td._total_after_tax input').val(after_tax);

    $(this).find('td.tax_value input').val(item_tax);

  });

  var order_discount_percent = $('input[name="order_discount"]').val();  
  var order_discount_percent_val = 0;
  // Discount by percent
  if ((order_discount_percent !== '' && order_discount_percent != 0) && discount_type == 'before_tax' && add_discount_type == 'percent') {
    total_discount_calculated += parseFloat((subtotal * order_discount_percent) / 100);
    order_discount_percent_val = (subtotal * order_discount_percent) / 100;
  } else if ((order_discount_percent !== '' && order_discount_percent != 0) && discount_type == 'before_tax' && add_discount_type == 'amount') {
    total_discount_calculated += parseFloat(order_discount_percent);
    order_discount_percent_val = order_discount_percent;
  }

  $.each(taxes, function (taxname, total_tax) {
    if ((order_discount_percent !== '' && order_discount_percent != 0) && discount_type == 'before_tax' && add_discount_type == 'percent') {
      var total_tax_calculated = (total_tax * order_discount_percent) / 100;
      total_tax = (total_tax - total_tax_calculated);
    } else if ((order_discount_percent !== '' && order_discount_percent != 0) && discount_type == 'before_tax' && add_discount_type == 'amount') {
      var t = (order_discount_percent / subtotal) * 100;
      total_tax = (total_tax - (total_tax * t) / 100);
    }

    total += total_tax;
    total_tax_money += total_tax;
    total_tax = format_money(total_tax);
    $('#tax_id_' + slugify(taxname)).html(total_tax);
  });


  total = (total + subtotal);
  total_money = total;
  // Discount by percent

  if ((order_discount_percent !== '' && order_discount_percent != 0) && discount_type == 'after_tax' && add_discount_type == 'percent') {
    total_discount_calculated += parseFloat((total * order_discount_percent) / 100);
    order_discount_percent_val = (total * order_discount_percent) / 100;
  } else if ((order_discount_percent !== '' && order_discount_percent != 0) && discount_type == 'after_tax' && add_discount_type == 'amount') {
    total_discount_calculated += parseFloat(order_discount_percent);
    order_discount_percent_val = order_discount_percent;
  }

  
  //total_discount_calculated = total_discount_calculated;

  total = parseFloat(total) - parseFloat(total_discount_calculated) - parseFloat(additional_discount);
  adjustment = parseFloat(adjustment);

  // Check if adjustment not empty
  if (!isNaN(adjustment)) {
    total = total + adjustment;
  }

  total+= parseFloat(shipping_fee);

  var discount_html = '-' + format_money(parseFloat(total_discount_calculated)+ parseFloat(additional_discount));
    $('input[name="discount_total"]').val(accounting.toFixed(total_discount_calculated, app.options.decimal_places));
    
  // Append, format to html and display
  $('.shiping_fee').html(format_money(shipping_fee));
  $('.order_discount_value').html(format_money(order_discount_percent_val));
  $('.wh-total_discount').html(discount_html + hidden_input('dc_total', accounting.toFixed(order_discount_percent_val, app.options.decimal_places))  );
  $('.adjustment').html(format_money(adjustment));
  $('.wh-subtotal').html(format_money(subtotal) + hidden_input('total_mn', accounting.toFixed(subtotal, app.options.decimal_places)));
  $('.wh-total').html(format_money(total) + hidden_input('grand_total', accounting.toFixed(total, app.options.decimal_places)));

  $(document).trigger('purchase-quotation-total-calculated');

}


function pur_add_item_to_preview(id) {
  "use strict";

  var currency_rate = $('input[name="currency_rate"]').val();

  requestGetJSON('purchase/get_item_by_id/' + id+'/'+ currency_rate ).done(function (response) {
    clear_item_preview_values();

    $('.main input[name="item_code"]').val(response.itemid);
    $('.main textarea[name="item_name"]').val(response.code_description);
    $('.main textarea[name="description"]').val(response.long_description);
    $('.main input[name="unit_price"]').val(response.purchase_price);
    $('.main input[name="unit_name"]').val(response.unit_name);
    $('.main input[name="unit_id"]').val(response.unit_id);
    $('.main input[name="quantity"]').val(1);

    $('.selectpicker').selectpicker('refresh');


    var taxSelectedArray = [];
    if (response.taxname && response.taxrate) {
      taxSelectedArray.push(response.taxname + '|' + response.taxrate);
    }
    if (response.taxname_2 && response.taxrate_2) {
      taxSelectedArray.push(response.taxname_2 + '|' + response.taxrate_2);
    }

    $('.main select.taxes').selectpicker('val', taxSelectedArray);
    $('.main input[name="unit"]').val(response.unit_name);

    var $currency = $("body").find('.accounting-template select[name="currency"]');
    var baseCurency = $currency.attr('data-base');
    var selectedCurrency = $currency.find('option:selected').val();
    var $rateInputPreview = $('.main input[name="rate"]');

    if (baseCurency == selectedCurrency) {
      $rateInputPreview.val(response.purchase_price);
    } else {
      var itemCurrencyRate = response['rate_currency_' + selectedCurrency];
      if (!itemCurrencyRate || parseFloat(itemCurrencyRate) === 0) {
        $rateInputPreview.val(response.purchase_price);
      } else {
        $rateInputPreview.val(itemCurrencyRate);
      }
    }

    $(document).trigger({
      type: "item-added-to-preview",
      item: response,
      item_type: 'item',
    });
  });
}

function pur_add_item_to_table(data, itemid) {
  "use strict";

  data = typeof (data) == 'undefined' || data == 'undefined' ? pur_get_item_preview_values() : data;

  if (data.quantity == "" || data.item_code == "" ) {
    
    return;
  }
  var currency_rate = $('input[name="currency_rate"]').val();
  var to_currency = $('select[name="currency"]').val();
  var table_row = '';
  var item_key = lastAddedItemKey ? lastAddedItemKey += 1 : $("body").find('.invoice-items-table tbody .item').length + 1;
  lastAddedItemKey = item_key;
  $("body").append('<div class="dt-loader"></div>');
  pur_get_item_row_template('newitems[' + item_key + ']',data.item_name, data.description, data.quantity, data.unit_name, data.unit_price, data.taxname, data.item_code, data.unit_id, data.tax_rate, data.discount, itemid, currency_rate, to_currency).done(function(output){
    table_row += output;

    $('.invoice-item table.invoice-items-table.items tbody').append(table_row);

    setTimeout(function () {
      pur_calculate_total();
    }, 15);
    init_selectpicker();
    pur_reorder_items('.invoice-item');
    pur_clear_item_preview_values('.invoice-item');
    $('body').find('#items-warning').remove();
    $("body").find('.dt-loader').remove();
        $('#item_select').selectpicker('val', '');

    return true;
  });
  return false;
}

function pur_get_item_preview_values() {
  "use strict";

  var response = {};
  response.item_name = $('.invoice-item .main textarea[name="item_name"]').val();
  response.description = $('.invoice-item .main textarea[name="description"]').val();
  response.quantity = $('.invoice-item .main input[name="quantity"]').val();
  response.unit_name = $('.invoice-item .main input[name="unit_name"]').val();
  response.unit_price = $('.invoice-item .main input[name="unit_price"]').val();
  response.taxname = $('.main select.taxes').selectpicker('val');
  response.item_code = $('.invoice-item .main input[name="item_code"]').val();
  response.unit_id = $('.invoice-item .main input[name="unit_id"]').val();
  response.tax_rate = $('.invoice-item .main input[name="tax_rate"]').val();
  response.discount = $('.invoice-item .main input[name="discount"]').val();


  return response;
}


function pur_clear_item_preview_values(parent) {
  "use strict";

  var previewArea = $(parent + ' .main');
  previewArea.find('input').val('');
  previewArea.find('textarea').val('');
  previewArea.find('select').val('').selectpicker('refresh');
}

function pur_reorder_items(parent) {
  "use strict";

  var rows = $(parent + ' .table.has-calculations tbody tr.item');
  var i = 1;
  $.each(rows, function () {
    $(this).find('input.order').val(i);
    i++;
  });
}

function pur_delete_item(row, itemid,parent) {
  "use strict";

  $(row).parents('tr').addClass('animated fadeOut', function () {
    setTimeout(function () {
      $(row).parents('tr').remove();
      pur_calculate_total();
    }, 50);
  });
  if (itemid && $('input[name="isedit"]').length > 0) {
    $(parent+' #removed-items').append(hidden_input('removed_items[]', itemid));
  }
}

function pur_get_item_row_template(name, item_name, description, quantity, unit_name, unit_price, taxname,  item_code, unit_id, tax_rate, discount, item_key, currency_rate, to_currency)  {
  "use strict";

  jQuery.ajaxSetup({
    async: false
  });

  var d = $.post(admin_url + 'purchase/get_purchase_order_row_template', {
    name: name,
    item_name : item_name,
    item_description : description,
    quantity : quantity,
    unit_name : unit_name,
    unit_price : unit_price,
    taxname : taxname,
    item_code : item_code,
    unit_id : unit_id,
    tax_rate : tax_rate,
    discount : discount,
    item_key : item_key,
    currency_rate: currency_rate,
    to_currency: to_currency
  });
  jQuery.ajaxSetup({
    async: true
  });
  return d;
}

// Set the currency for accounting
function init_po_currency(id, callback) {
    var $accountingTemplate = $("body").find('.accounting-template');

    if ($accountingTemplate.length || id) {
        var selectedCurrencyId = !id ? $accountingTemplate.find('select[name="currency"]').val() : id;

        requestGetJSON('misc/get_currency/' + selectedCurrencyId)
            .done(function (currency) {
                // Used for formatting money
                accounting.settings.currency.decimal = currency.decimal_separator;
                accounting.settings.currency.thousand = currency.thousand_separator;
                accounting.settings.currency.symbol = currency.symbol;
                accounting.settings.currency.format = currency.placement == 'after' ? '%v %s' : '%s%v';

                pur_calculate_total();

                if(callback) {
                    callback();
                }
            });
    }
}

</script>