<script>
var Input_debit_total = $('#bill-debit-account').children().length;
var Input_credit_total = $('#bill-credit-account').children().length;
var Input_item_total = $('#bill-item-list').children().length;
var customer_currency = '';
var max_amount = '';
var limit = '';
Dropzone.options.expenseForm = false;
var expenseDropzone;
var timer = null;

(function($) {
  "use strict";
    $( document ).ready(function() {
  $('.menu-item-accounting_expenses ').addClass('active');
  $('.menu-item-accounting_expenses ul').addClass('in');
  $('.sub-menu-item-accounting_bills').addClass('active');

  var input = document.getElementById('debit_amount[0]');
  if (input) {
    input.addEventListener('change', caculate_total);
  }


  $("body").on('click', '.new_debit_template', function() {
    var new_template = $('#bill-debit-account').find('.template_children').eq(0).clone().appendTo('#bill-debit-account');

    for(var i = 0; i <= new_template.find('#template-item').length ; i++){
        if(i > 0){
          new_template.find('#template-item').eq(i).remove();
        }
        new_template.find('#template-item').eq(1).remove();
    }

    new_template.find('.template').attr('value', Input_debit_total);
    new_template.find('button[role="combobox"]').remove();
    new_template.find('select').selectpicker('refresh');
    // start expense
    
    new_template.find('label[for="debit_account[0]"]').attr('for', 'debit_account[' + Input_debit_total + ']');
    new_template.find('select[name="debit_account[0]"]').attr('name', 'debit_account[' + Input_debit_total + ']');
    new_template.find('select[id="debit_account[0]"]').attr('id', 'debit_account[' + Input_debit_total + ']').selectpicker('refresh');

    new_template.find('input[id="debit_amount[0]"]').attr('name', 'debit_amount['+Input_debit_total+']').val('');
    new_template.find('input[id="debit_amount[0]"]').attr('id', 'debit_amount['+Input_debit_total+']').val('');

    new_template.find('button[name="add_template"] i').removeClass('fa-plus').addClass('fa-minus');
    new_template.find('button[name="add_template"]').removeClass('new_debit_template').addClass('remove_debit_template').removeClass('btn-success').addClass('btn-danger');

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
        clearTimeout(timer); 
        timer = setTimeout(caculate_total, 1000);
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });

    var input = document.getElementById('debit_amount['+Input_debit_total+']');
    if (input) {
      input.addEventListener('change', caculate_total);
    }
      
  $('input[id="debit_amount[0]"]')

    Input_debit_total++;
  });

  $("body").on('click', '.new_credit_template', function() {
    var new_template = $('#bill-credit-account').find('.template_children').eq(0).clone().appendTo('#bill-credit-account');

    for(var i = 0; i <= new_template.find('#template-item').length ; i++){
        if(i > 0){
          new_template.find('#template-item').eq(i).remove();
        }
        new_template.find('#template-item').eq(1).remove();
    }

    new_template.find('.template').attr('value', Input_credit_total);
    new_template.find('button[role="combobox"]').remove();
    new_template.find('select').selectpicker('refresh');
    // start expense
    
    new_template.find('label[for="credit_account[0]"]').attr('for', 'credit_account[' + Input_credit_total + ']');
    new_template.find('select[name="credit_account[0]"]').attr('name', 'credit_account[' + Input_credit_total + ']');
    new_template.find('select[id="credit_account[0]"]').attr('id', 'credit_account[' + Input_credit_total + ']').selectpicker('refresh');

    new_template.find('input[id="credit_amount[0]"]').attr('name', 'credit_amount['+Input_credit_total+']').val('');
    new_template.find('input[id="credit_amount[0]"]').attr('id', 'credit_amount['+Input_credit_total+']').val('');

    new_template.find('button[name="add_template"] i').removeClass('fa-plus').addClass('fa-minus');
    new_template.find('button[name="add_template"]').removeClass('new_credit_template').addClass('remove_template').removeClass('btn-success').addClass('btn-danger');

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });
    Input_credit_total++;
  });
  $("body").on('click', '.remove_debit_template', function() {
      $(this).parents('.template_children').remove();
      caculate_total();
  });

  $("body").on('click', '.remove_template', function() {
      $(this).parents('.template_children').remove();
      caculate_total();
  });

  if($('#dropzoneDragArea').length > 0){
      expenseDropzone = new Dropzone("#expense-form", appCreateDropzoneOptions({
        autoProcessQueue: false,
        clickable: '#dropzoneDragArea',
        previewsContainer: '.dropzone-previews',
        addRemoveLinks: true,
        maxFiles: 1,
        success:function(file,response){
         response = JSON.parse(response);
         if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
           window.location.assign(response.url);
         }
       },
     }));
  }

  appValidateForm($('#expense-form'),{
    vendor:'required',
    date:'required',
  },expenseSubmitHandler);

  $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
        clearTimeout(timer); 
        timer = setTimeout(caculate_total, 1000);
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });


  $("body").on('click', '.new_item_template', function() {
    var new_template = $('#bill-item-list').find('.template_children').eq(0).clone().appendTo('#bill-item-list');

    for(var i = 0; i <= new_template.find('#template-item').length ; i++){
        if(i > 0){
          new_template.find('#template-item').eq(i).remove();
        }
        new_template.find('#template-item').eq(1).remove();
    }
    
    new_template.attr('data-index', Input_item_total);

    new_template.find('.template').attr('value', Input_item_total);
    new_template.find('button[role="combobox"]').remove();
    new_template.find('select').selectpicker('refresh');
    // start expense
    
    new_template.find('label[for="item_id[0]"]').attr('for', 'item_id[' + Input_item_total + ']');
    new_template.find('select[name="item_id[0]"]').attr('name', 'item_id[' + Input_item_total + ']');
    new_template.find('select[id="item_id[0]"]').attr('id', 'item_id[' + Input_item_total + ']').selectpicker('refresh');

    new_template.find('input[id="item_description[0]"]').attr('name', 'item_description['+Input_item_total+']').val('');
    new_template.find('input[id="item_description[0]"]').attr('id', 'item_description['+Input_item_total+']').val('');

    new_template.find('input[id="item_qty[0]"]').attr('name', 'item_qty['+Input_item_total+']').val('');
    new_template.find('input[id="item_qty[0]"]').attr('id', 'item_qty['+Input_item_total+']').val('');
    
    new_template.find('input[id="item_cost[0]"]').attr('name', 'item_cost['+Input_item_total+']').val('');
    new_template.find('input[id="item_cost[0]"]').attr('id', 'item_cost['+Input_item_total+']').val('');

    new_template.find('input[id="item_amount[0]"]').attr('name', 'item_amount['+Input_item_total+']').val('');
    new_template.find('input[id="item_amount[0]"]').attr('id', 'item_amount['+Input_item_total+']').val('');

    new_template.find('button[name="add_template"] i').removeClass('fa-plus').addClass('fa-minus');
    new_template.find('button[name="add_template"]').removeClass('new_item_template').addClass('remove_template').removeClass('btn-success').addClass('btn-danger');

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });
    Input_item_total++;
  });
  $("body").on('click', '.remove_item_template', function() {
      $(this).parents('.template_children').remove();
      caculate_total();
  });
    });
})(jQuery);

function subtract_tax_amount_from_expense_total(){
     var $amount = $('#amount'),
     total = parseFloat($amount.val()),
     taxDropdown1 = $('select[name="tax"]'),
     taxDropdown2 = $('select[name="tax2"]'),
     taxRate1 = parseFloat(taxDropdown1.find('option[value="'+taxDropdown1.val()+'"]').attr('data-percent')),
     taxRate2 = parseFloat(taxDropdown2.find('option[value="'+taxDropdown2.val()+'"]').attr('data-percent'));

     var totalTaxPercentExclude = taxRate1;
     if(taxRate2) {
      totalTaxPercentExclude+= taxRate2;
    }

    if($amount.attr('data-original-amount')) {
      total = parseFloat($amount.attr('data-original-amount'));
    }

    $amount.val(exclude_tax_from_amount(totalTaxPercentExclude, total));

    if($amount.attr('data-original-amount') == undefined) {
      $amount.attr('data-original-amount', total);
    }
}

    
 function expenseSubmitHandler(form){
  var debit_amount = 0;
  $('input[name^="debit_amount"]').each(function() {
    if($(this).val() != ''){
      debit_amount += parseFloat(unFormatNumber($(this).val()));
    }
  });

  var credit_amount = 0;
  $('input[name^="credit_amount"]').each(function() {
    if($(this).val() != ''){
      credit_amount += parseFloat(unFormatNumber($(this).val()));
    }
  });
  var bill_amount = $('input[name="amount"]').val();  
  if(debit_amount != credit_amount){
    alert("<?php echo _l('please_balance_debits_and_credits'); ?>");
    return false;
  }else if(bill_amount <= 0){
    alert("<?php echo _l('the_total_bill_must_be_greater_than_0'); ?>");
    return false;
  }


  $('select[name="tax2"]').prop('disabled',false);
  $('input[name="billable"]').prop('disabled',false);
  $('input[name="date"]').prop('disabled',false);

  $.post(form.action, $(form).serialize()).done(function(response) {
    response = JSON.parse(response);
    if (response.billid) {
      if(typeof(expenseDropzone) !== 'undefined'){
        if (expenseDropzone.getQueuedFiles().length > 0) {
          expenseDropzone.options.url = admin_url + 'accounting/add_bill_attachment/' + response.billid;
          expenseDropzone.processQueue();
        } else {
          window.location.assign(response.url);
        }
      } else {
        window.location.assign(response.url);
      }
    } else {
      if(response.message){
        alert_float('warning',response.message);
      }

      if(response.url){
        window.location.assign(response.url);
      }
    }
  });
  return false;
}

function debit_account_change (){
  var debit_account = $("select[name^='debit_account']")
              .map(function(){return $(this).val();}).get();
  var debit_amount = $("input[name^='debit_amount']")
              .map(function(){return $(this).val();}).get();

  var data = {};
  data.account = debit_account;
  data.amount = debit_amount;

  var debit_amount = 0;
  $('input[name^="debit_amount"]').each(function() {
      debit_amount += parseFloat(unFormatNumber($(this).val()));
  });

  var credit_amount = 0;
  $('input[name^="credit_amount"]').each(function() {
      credit_amount += parseFloat(unFormatNumber($(this).val()));
  });
  $('input[name="amount"]').val(debit_amount);  
  $('#bill-total').html(acc_format_money(debit_amount));
}


function bill_item_change(invoker){
  item_id = $(invoker).val();
  bill_item_index = $(invoker).parents('tr').data('index');
  if(item_id != ''){
    requestGetJSON(admin_url + 'accounting/get_item_data/'+item_id).done(function(response) { 
      $('input[name="item_description['+bill_item_index+']"]').val('');
      $('input[name="item_qty['+bill_item_index+']"]').val(1);
      $('input[name="item_cost['+bill_item_index+']"]').val(response.purchase_price);  
      $('input[name="item_amount['+bill_item_index+']"]').val(response.purchase_price);  
      caculate_total();  
    });
  }else{
    $('input[name="item_description['+bill_item_index+']"]').val('');
    $('input[name="item_qty['+bill_item_index+']"]').val('');
    $('input[name="item_cost['+bill_item_index+']"]').val('');  
    $('input[name="item_amount['+bill_item_index+']"]').val('');  
    caculate_total();  
  }
}

function bill_item_qty_change(invoker){
  item_qty = $(invoker).val();

  bill_item_index = $(invoker).parents('tr').data('index');
  item_cost = $('input[name="item_cost['+bill_item_index+']"]').val();  

  if(item_cost != '' && item_qty != ''){
    item_cost = unFormatNumber(item_cost);
    item_amount = item_cost * item_qty;
    $('input[name="item_amount['+bill_item_index+']"]').val(formatNumberWithDecimals(item_amount.toFixed(2)));  
  }else{
    $('input[name="item_amount['+bill_item_index+']"]').val(0);  
  }
  caculate_total();
}

function bill_item_cost_change(invoker){
  item_cost = $(invoker).val();

  bill_item_index = $(invoker).parents('tr').data('index');
  item_qty = $('input[name="item_qty['+bill_item_index+']"]').val();  

  if(item_cost != '' && item_qty != ''){
    item_cost = unFormatNumber(item_cost);
    item_amount = item_cost * item_qty;
    $('input[name="item_amount['+bill_item_index+']"]').val(formatNumberWithDecimals(item_amount.toFixed(2)));  
  }else{
    $('input[name="item_amount['+bill_item_index+']"]').val(0);  
  }

  caculate_total();
}


function caculate_total(){
  var debit_amount = 0;
  $('input[name^="debit_amount"]').each(function() {
    if($(this).val() != ''){
      debit_amount += parseFloat(unFormatNumber($(this).val()));
    }
  });

  var credit_amount = 0;
  $('input[name^="credit_amount"]').each(function() {
    if($(this).val() != ''){
      credit_amount += parseFloat(unFormatNumber($(this).val()));
    }
  });

  var item_amount = 0;
  $('input[name^="item_amount"]').each(function() {
    if($(this).val() != ''){
      item_amount += parseFloat(unFormatNumber($(this).val()));
    }
  });

  bill_total = 0;

  if(item_amount > 0 ){
    bill_total = item_amount;
  }

  if(debit_amount > 0){
    bill_total = bill_total + debit_amount;
  }
 
  $('input[name="amount"]').val(bill_total);  
  $('#bill-total').html(acc_format_money(bill_total));
}

function formatNumber(n) {
  "use strict";
  // format number 1000000 to 1,234,567 (with dynamic separator)
  var thousand_sep = (typeof(acc_thousand_separator) !== 'undefined') ? acc_thousand_separator : ((typeof(app) !== 'undefined' && app.options && app.options.thousand_separator) ? app.options.thousand_separator : ',');
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, thousand_sep);
}

function formatNumberWithDecimals(n) {
  "use strict";
  var decimal_sep = (typeof(acc_decimal_separator) !== 'undefined') ? acc_decimal_separator : ((typeof(app) !== 'undefined' && app.options && app.options.decimal_separator) ? app.options.decimal_separator : '.');
  var thousand_sep = (typeof(acc_thousand_separator) !== 'undefined') ? acc_thousand_separator : ((typeof(app) !== 'undefined' && app.options && app.options.thousand_separator) ? app.options.thousand_separator : ',');

  // Split by standard "." first because javascript float string uses "."
  var parts = n.toString().split(".");
  parts[0] = parts[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, thousand_sep);
  if (parts.length > 1) {
    parts[1] = parts[1].replace(/\D/g, "").substring(0, 2);
    return parts[0] + decimal_sep + parts[1];
  }
  return parts[0];
}

function unFormatNumber(n) {
  "use strict";
  if (typeof(n) !== 'string') {
    n = n.toString();
  }
  var decimal_sep = (typeof(acc_decimal_separator) !== 'undefined') ? acc_decimal_separator : ((typeof(app) !== 'undefined' && app.options && app.options.decimal_separator) ? app.options.decimal_separator : '.');
  var thousand_sep = (typeof(acc_thousand_separator) !== 'undefined') ? acc_thousand_separator : ((typeof(app) !== 'undefined' && app.options && app.options.thousand_separator) ? app.options.thousand_separator : ',');
  
  // Escape special characters for regex
  var esc_thousand = thousand_sep.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
  var esc_decimal = decimal_sep.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
  
  var clean_num = n.replace(new RegExp(esc_thousand, 'g'), '');
  clean_num = clean_num.replace(new RegExp(esc_decimal, 'g'), '.');
  return clean_num;
}

function formatCurrency(input, blur) {
  "use strict";
  var decimal_sep = (typeof(acc_decimal_separator) !== 'undefined') ? acc_decimal_separator : ((typeof(app) !== 'undefined' && app.options && app.options.decimal_separator) ? app.options.decimal_separator : '.');
  var thousand_sep = (typeof(acc_thousand_separator) !== 'undefined') ? acc_thousand_separator : ((typeof(app) !== 'undefined' && app.options && app.options.thousand_separator) ? app.options.thousand_separator : ',');

  // get input value
  var input_val = input.val();

  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // initial caret position
  var caret_pos = input.prop("selectionStart");

  // check for decimal
  if (input_val.indexOf(decimal_sep) >= 0) {

    // get position of first decimal
    var decimal_pos = input_val.indexOf(decimal_sep);
    var minus = input_val.substring(0, 1);
    if(minus != '-'){
      minus = '';
    }

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos + 1);

    left_side = formatNumber(left_side);

    // validate right side (only digits)
    right_side = right_side.replace(/\D/g, "");

    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by decimal separator
    input_val = minus + left_side + decimal_sep + right_side;

  } else {
    // no decimal entered
    var minus = input_val.substring(0, 1);
    if(minus != '-'){
      minus = '';
    }
    input_val = formatNumber(input_val);
    input_val = minus + input_val;

  }

  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;  
}

</script>
