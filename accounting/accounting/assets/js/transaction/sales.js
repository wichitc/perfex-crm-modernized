var id, type, amount;

var fnServerParams;
(function($) {
    "use strict";
  $( document ).ready(function() {

    acc_init_currency();

  fnServerParams = {
      "invoice": '[name="invoice"]',
      "payment_mode": '[name="payment_mode"]',
      "status": '[name="status"]',
      "from_date": '[name="from_date"]',
      "to_date": '[name="to_date"]',
    };

  init_ajax_search("invoice", "#invoice.ajax-search");

  appValidateForm($('#convert-form'), {
      
      },convert_form_handler);

  init_sales_table();
  init_credit_notes_table();
  init_credit_notes_apply_table();
	init_credit_notes_refund_table();
  init_sales_invoice_table();
  init_omni_sales_table();

  $('select[name="invoice"]').on('change', function() {
    init_sales_table();
    init_credit_notes_table();
    init_credit_notes_apply_table();
  });
  $('select[name="payment_mode"]').on('change', function() {
    init_sales_table();
  });
  $('select[name="status"]').on('change', function() {
    init_sales_invoice_table();
    init_sales_table();
    init_omni_sales_table();
    init_credit_notes_table();
    init_credit_notes_apply_table();
    init_credit_notes_refund_table();
  });
	$('input[name="from_date"]').on('change', function() {
		init_sales_table();
    init_sales_invoice_table();
    init_omni_sales_table();
    init_credit_notes_table();
    init_credit_notes_apply_table();
    init_credit_notes_refund_table();
	});

	$('input[name="to_date"]').on('change', function() {
		init_sales_table();
    init_sales_invoice_table();
    init_omni_sales_table();
    init_credit_notes_table();
    init_credit_notes_apply_table();
    init_credit_notes_refund_table();
	});

	$("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });

  $('input[name="mass_convert"]').on('change', function() {
    if($('#mass_convert').is(':checked') == true){
      $('#mass_delete_convert').prop( "checked", false );
    }
  });

  $('input[name="mass_delete_convert"]').on('change', function() {
    if($('#mass_delete_convert').is(':checked') == true){
      $('#mass_convert').prop( "checked", false );
    }
  });

  $("body").on('click', '.edit_conversion_rate_action', function() {
      $('input[name="exchange_rate"]').val($('input[name="edit_exchange_rate"]').val());

      $('.amount_after_convert').html(format_money(($('input[name="exchange_rate"]').val() * $('input[name="payment_amount"]').val())));
      $('.currency_converter_label').html('1 '+$('input[name="currency_from"]').val() +' = '+$('input[name="edit_exchange_rate"]').val()+' '+ $('input[name="currency_to"]').val());
  });
  });
})(jQuery);

function convert(invoker){
    "use strict";
    $('#convert-modal').find('button[id="btn_account_history"]').prop('disabled', false);

    id = $(invoker).data('id');
    type = $(invoker).data('type');
    amount = $(invoker).data('amount');

    $('input[name="id"]').val(id);
    $('input[name="type"]').val(type);
    $('input[name="amount"]').val(amount);

    requestGet('accounting/get_data_convert/' + id + '/' + type).done(function(response) {
        response = JSON.parse(response);

        $('#div_info').html(response.html);
        if(type == 'invoice' || type == 'sales_return_order'){
          init_selectpicker();
          $.each(response.list_item, function(index,item_id) {
            $('#payment_account['+item_id+']').selectpicker('refresh');
            $('#deposit_to['+item_id+']').selectpicker('refresh');
          });

        }else{
          if(response.debit != 0){
            $('select[name="deposit_to"]').val(response.debit).change();
          }

          if(response.credit != 0){
            $('select[name="payment_account"]').val(response.credit).change();
          }
        }
        init_selectpicker();
    });

  $('#convert-modal').modal('show');
}

function delete_convert(id,type) {
  "use strict";
    if (confirm("Are you sure?")) {
      var url = admin_url + 'accounting/delete_convert/'+id+'/'+type;

      requestGet(url).done(function(response){
          response = JSON.parse(response);
          if (response.success === true || response.success == 'true') { 
            alert_float('success', response.message); 
            init_sales_table();
            init_sales_invoice_table();
            init_omni_sales_table();
            init_credit_notes_table();
            init_credit_notes_apply_table();
            init_credit_notes_refund_table();
          }else{
            alert_float('danger', response.message); 
          }
      });
    }
    return false;
}

function convert_form_handler(form) {
    "use strict";
    $('#convert-modal').find('button[id="btn_account_history"]').prop('disabled', true);

    var formURL = form.action;
    var formData = new FormData($(form)[0]);

    $.ajax({
        type: $(form).attr('method'),
        data: formData,
        mimeType: $(form).attr('enctype'),
        contentType: false,
        cache: false,
        processData: false,
        url: formURL
    }).done(function(response) {
        response = JSON.parse(response);
        if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
            alert_float('success', response.message);
            init_sales_table();
            init_sales_invoice_table();
            init_omni_sales_table();
            init_credit_notes_table();
            init_credit_notes_apply_table();
            init_credit_notes_refund_table();
        }else{
          alert_float('danger', response.message);
        }
        $('#convert-modal').modal('hide');
    }).fail(function(error) {
        alert_float('danger', JSON.parse(error.mesage));
    });

    return false;
}

function init_sales_table() {
  "use strict";

  if ($.fn.DataTable.isDataTable('.table-sales')) {
     $('.table-sales').DataTable().destroy();
  }
  initDataTable('.table-sales', admin_url + 'accounting/sales_table?bulk_actions=true', [0], [0], fnServerParams, [1, 'desc']);
}

function init_sales_invoice_table() {
  "use strict";

  if ($.fn.DataTable.isDataTable('.table-sales-invoice')) {
     $('.table-sales-invoice').DataTable().destroy();
  }
  initDataTable('.table-sales-invoice', admin_url + 'accounting/sales_invoice_table', [0], [0], fnServerParams, [1, 'desc']);
}


function formatNumber(n) {
  "use strict";
  // format number 1000000 to 1,234,567 (with dynamic separator)
  var thousand_sep = (typeof(acc_thousand_separator) !== 'undefined') ? acc_thousand_separator : ((typeof(app) !== 'undefined' && app.options && app.options.thousand_separator) ? app.options.thousand_separator : ',');
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, thousand_sep);
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


// sales bulk actions action
function bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.type = $('input[name="bulk_actions_type"]').val();
            data.mass_convert = $('#mass_convert').prop('checked');
            data.mass_delete_convert = $('#mass_delete_convert').prop('checked');

        if($('input[name="bulk_actions_type"]').val() == 'payment'){
          var rows = $($('#sales_bulk_actions').attr('data-table')).find('tbody tr');
        }else if($('input[name="bulk_actions_type"]').val() == 'invoice'){
          var rows = $($('#sales_invoice_bulk_actions').attr('data-table')).find('tbody tr');
        }else if($('input[name="bulk_actions_type"]').val() == 'expense'){
          var rows = $($('#expense_bulk_actions').attr('data-table')).find('tbody tr');
        }else if($('input[name="bulk_actions_type"]').val() == 'omni_sales_return_order'){
          var rows = $($('#omni_sales_return_order_bulk_actions').attr('data-table')).find('tbody tr');
        }else if($('input[name="bulk_actions_type"]').val() == 'omni_sales_refund'){
          var rows = $($('#omni_sales_refund_bulk_actions').attr('data-table')).find('tbody tr');
        }else if($('input[name="bulk_actions_type"]').val() == 'credit_note'){
          var rows = $($('#credit_notes_bulk_actions').attr('data-table')).find('tbody tr');
        }else if($('input[name="bulk_actions_type"]').val() == 'credit_note_apply'){
          var rows = $($('#credit_notes_apply_bulk_actions').attr('data-table')).find('tbody tr');
        }else if($('input[name="bulk_actions_type"]').val() == 'credit_note_refund'){
          var rows = $($('#credit_notes_refund_bulk_actions').attr('data-table')).find('tbody tr');
        }

        $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
                ids.push(checkbox.val());
            }
        });
        data.ids = ids;
        $(event).addClass('disabled');
        setTimeout(function() {
            $.post(admin_url + 'accounting/transaction_bulk_action', data).done(function() {
                window.location.reload();
            });
        }, 200);
    }
}

// Set the currency for accounting
function acc_init_currency() {
  "use strict";
  var selectedCurrencyId = $('input[name="currency_id"]').val();
  requestGetJSON('misc/get_currency/' + selectedCurrencyId)
      .done(function(currency) {
          // Used for formatting money
          accounting.settings.currency.decimal = currency.decimal_separator;
          accounting.settings.currency.thousand = currency.thousand_separator;
          accounting.settings.currency.symbol = currency.symbol;
          accounting.settings.currency.format = currency.placement == 'after' ? '%v %s' : '%s%v';
      });
}


function init_omni_sales_table() {
"use strict";

  if ($.fn.DataTable.isDataTable('.table-omni-sales-order-return')) {
    $('.table-omni-sales-order-return').DataTable().destroy();
  }
  initDataTable('.table-omni-sales-order-return', admin_url + 'accounting/omni_sales_return_order_table', [0], [0], fnServerParams, [1, 'desc']);

  if ($.fn.DataTable.isDataTable('.table-omni-sales-refund')) {
    $('.table-omni-sales-refund').DataTable().destroy();
  }
  initDataTable('.table-omni-sales-refund', admin_url + 'accounting/omni_sales_refund_table', [0], [0], fnServerParams, [1, 'desc']);
}


function init_credit_notes_table() {
  "use strict";

  if ($.fn.DataTable.isDataTable('.table-credit-notes')) {
     $('.table-credit-notes').DataTable().destroy();
  }
  initDataTable('.table-credit-notes', admin_url + 'accounting/credit_notes_table?bulk_actions=true', [0], [0], fnServerParams, [1, 'desc']);
}

function init_credit_notes_apply_table() {
  "use strict";

  if ($.fn.DataTable.isDataTable('.table-credit-notes-apply')) {
     $('.table-credit-notes-apply').DataTable().destroy();
  }
  initDataTable('.table-credit-notes-apply', admin_url + 'accounting/credit_notes_apply_table?bulk_actions=true', [0], [0], fnServerParams, [1, 'desc']);
}

function init_credit_notes_refund_table() {
  "use strict";

  if ($.fn.DataTable.isDataTable('.table-credit-notes-refund')) {
     $('.table-credit-notes-refund').DataTable().destroy();
  }
  initDataTable('.table-credit-notes-refund', admin_url + 'accounting/credit_notes_refund_table?bulk_actions=true', [0], [0], fnServerParams, [1, 'desc']);
}
