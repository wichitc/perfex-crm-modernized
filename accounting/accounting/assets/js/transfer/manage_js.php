<script type="text/javascript">
	var fnServerParams;
	(function($) {
		"use strict";
  $( document ).ready(function() {

		appValidateForm($('#transfer-form'), {
			transfer_funds_from: 'required',
			transfer_funds_to: 'required',
      amount: 'required',
      date: 'required',
			transfer_amount: 'required',
    },transfer_form_handler);

		fnServerParams = {
      "from_date": '[name="from_date"]',
      "to_date": '[name="to_date"]',
      "ft_transfer_funds_from": '[name="ft_transfer_funds_from"]',
      "ft_transfer_funds_to": '[name="ft_transfer_funds_to"]',
    };

		$('.add-new-transfer').on('click', function(){
    $('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
      $('#transfer-modal').modal('show');
      $('input[name="id"]').val('');
      $('select[name="transfer_funds_from"]').val('').change();
      $('select[name="transfer_funds_to"]').val('').change();
      $('select[name="acc_class"]').val('').change();
      $('input[name="date"]').val('');
      $('input[name="transfer_amount"]').val('');
        tinyMCE.activeEditor.setContent('');
      $('textarea[name="description"]').val('');
    });

    $('select[name="ft_transfer_funds_from"]').on('change', function() {
      init_transfer_table();
    });

    $('select[name="ft_transfer_funds_to"]').on('change', function() {
      init_transfer_table();
    });

    $('input[name="from_date"]').on('change', function() {
      init_transfer_table();
    });

    $('input[name="to_date"]').on('change', function() {
      init_transfer_table();
    });

    init_transfer_table();

	$("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
  });
    });
})(jQuery);

function init_transfer_table() {
  "use strict";

  if ($.fn.DataTable.isDataTable('.table-transfer')) {
    $('.table-transfer').DataTable().destroy();
  }
  initDataTable('.table-transfer', admin_url + 'accounting/transfer_table', [0], [0], fnServerParams, [1, 'desc']);
  $('.dataTables_filter').addClass('hide');
}

function edit_transfer(id) {
  "use strict";
    $('#transfer-modal').find('button[type="submit"]').prop('disabled', false);

  requestGetJSON(admin_url + 'accounting/get_data_transfer/'+id).done(function(response) {
      $('#transfer-modal').modal('show');

      $('select[name="transfer_funds_from"]').val(response.transfer_funds_from).change();
      $('select[name="transfer_funds_to"]').val(response.transfer_funds_to).change();
      $('select[name="acc_class"]').val(response.acc_class).change();
      $('input[name="date"]').val(response.date);
      $('input[name="id"]').val(id);
      $('input[name="transfer_amount"]').val(response.transfer_amount);
      if(response.description != '' && response.description != null){
        tinyMCE.activeEditor.setContent(response.description);
      }else{
        tinyMCE.activeEditor.setContent('');
      }
      $('textarea[name="description"]').val(response.description);

  });
}

function transfer_form_handler(form) {
    "use strict";
    $('#transfer-modal').find('button[type="submit"]').prop('disabled', true);

    var formURL = form.action;
    var formData = new FormData($(form)[0]);
    formData.append("description", tinymce.activeEditor.getContent());
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
        if (response.success == 'close_the_book' || $.isNumeric(response.success)) {
          alert_float('warning', response.message);
        }else if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
          alert_float('success', response.message);
	 		    init_transfer_table();
        }else {
          alert_float('danger', response.message);
        }
        $('#transfer-modal').modal('hide');
    }).fail(function(error) {
        alert_float('danger', JSON.parse(error.mesage));
    });

    return false;
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

// transfer bulk actions action
function bulk_action(event) {
  "use strict";
    if (confirm_delete()) {
        var ids = [],
            data = {};
            data.mass_delete = $('#mass_delete').prop('checked');

        var rows = $($('#transfer_bulk_actions').attr('data-table')).find('tbody tr');

        $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
                ids.push(checkbox.val());
            }
        });
        data.ids = ids;
        $(event).addClass('disabled');
        setTimeout(function() {
            $.post(admin_url + 'accounting/transfer_bulk_action', data).done(function() {
                window.location.reload();
            });
        }, 200);
    }
}
</script>

