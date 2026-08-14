(function ($) {
  "use strict";
  $(document).ready(function () {

    if (typeof acc_classes !== 'undefined' && (acc_classes.length > 0 || (typeof acc_enable_class_tracking !== 'undefined' && acc_enable_class_tracking == 1))) {
      if ($('#filter-form').length > 0 && $('select[name="class_filter"]').length === 0) {
        var submit_container = $('#filter-form').find('.btn-submit').closest('div');
        if (submit_container.length > 0) {
          var class_select = '<div class="col-md-3">';
          class_select += '<label for="class_filter" class="control-label">' + acc_class_lang + '</label>';
          class_select += '<select name="class_filter" id="class_filter" class="selectpicker" data-width="100%" data-none-selected-text="None">';
          class_select += '<option value="">All</option>';
          $.each(acc_classes, function (i, c) {
            class_select += '<option value="' + c.id + '">' + c.name + '</option>';
          });
          class_select += '</select>';
          class_select += '</div>';

          $(class_select).insertBefore(submit_container);
          $('#class_filter').selectpicker('refresh');

          // Trigger report reload when class changes
          $('#class_filter').on('change', function () {
            $('#filter-form').submit();
          });
        }
      }
    }

    appValidateForm($('#filter-form'), {
      from_date: 'required',
      to_date: 'required',
    }, filter_form_handler);

    $('#filter-form').submit();

    $('select[name="reconcile_account"]').on('change', function () {
      $('input[name="hidden_reconcile_account"]').val($(this).val());
      $.post(admin_url + 'accounting/reconcile_account_change/' + $(this).val()).done(function (response) {
        response = JSON.parse(response);
        $('select[name="reconcile"]').html(response);
        $('select[name="reconcile"]').selectpicker('refresh');
        $('input[name="hidden_reconcile"]').val($('select[name="reconcile"]').val());
      });
    });

    $('select[name="date_filter_type"]').on('change', function () {
      if ($(this).val() == 'to_date') {
        $('input[name="from_date"]').parents('.col-md-3').addClass('hide');
      } else {
        $('input[name="from_date"]').parents('.col-md-3').removeClass('hide');
      }
    });

    var toggler = document.getElementsByClassName("caret");
    var i;

    for (i = 0; i < toggler.length; i++) {
      toggler[i].addEventListener("click", function () {
        this.parentElement.querySelector(".nested").classList.toggle("active");
        this.classList.toggle("caret-down");
      });
    }
  });
})(jQuery);


function printDiv() {
  "use strict";
  var btn_html = '';
  if (document.getElementById('load_more_td') != null) {
    btn_html = document.getElementById('load_more_td').innerHTML;
  }


  $('tr.load_more_btn').html('');
  var element = document.getElementById('accordion');


  var pages = document.getElementById('accordion');

  $('input[name="html"]').val(pages.innerHTML);
  $('input[name="pdf_name"]').val($('input[name="type"]').val());
  $('input[name="orientation"]').val('portrait');

  $('#render_pdf-form').submit();


  setTimeout(function () {
    $('tr.load_more_btn').html('<td id="btn_html">' + btn_html + '</td>');
  }, 3000);
}

function printDiv2() {
  "use strict";
  var btn_html = '';
  if (document.getElementById('load_more_td') != null) {
    btn_html = document.getElementById('load_more_td').innerHTML;
  }


  $('tr.load_more_btn').html('');
  var element = document.getElementById('accordion');


  var pages = document.getElementById('accordion');

  $('input[name="html"]').val(pages.innerHTML);
  $('input[name="pdf_name"]').val($('input[name="type"]').val());
  $('input[name="orientation"]').val('landscape');

  $('#render_pdf-form').submit();


  setTimeout(function () {
    $('tr.load_more_btn').html('<td id="btn_html">' + btn_html + '</td>');
  }, 3000);
}


function printExcel() {
  "use strict";
  $(".tree").tableHTMLExport({
    type: 'csv',
    filename: $('input[name="type"]').val() + '.csv',
  });
}

function filter_form_handler(form) {
  "use strict";
  if ($('select[name="display_rows_by"]').val() != undefined) {
    if ($('select[name="display_rows_by"]').val() == $('select[name="display_columns_by"]').val()) {
      alert('Warning: Row and column headings must be different.');
      return false;
    }
  }

  if ($('input[name="type"]').val() == 'custom_summary_report') {
    if ($('select[name="page_type"]').val() == 'vertical') {
      $('#DivIdToPrint').addClass('page');
      $('#DivIdToPrint').removeClass('page-size2');

      $('#export_to_pdf_btn').attr('onclick', 'printDiv(); return false;');
    }

    if ($('select[name="page_type"]').val() == 'horizontal') {
      $('#DivIdToPrint').removeClass('page');
      $('#DivIdToPrint').addClass('page-size2');
      $('#export_to_pdf_btn').attr('onclick', 'printDiv2(); return false;');
    }
  }

  var formURL = form.action;
  var formData = new FormData($(form)[0]);
  //show box loading
  var html = '';
  html += '<div class="accounting-Box">';
  html += '<span>';
  html += '<span></span>';
  html += '</span>';
  html += '</div>';
  $('#box-loading').html(html);
  $('#accounting-box-loading').html(html);

  $.ajax({
    type: $(form).attr('method'),
    data: formData,
    mimeType: $(form).attr('enctype'),
    contentType: false,
    cache: false,
    processData: false,
    url: formURL
  }).done(function (response) {
    const page = document.getElementsByName('page');

    if (page.length > 0) {
      if ($('input[name="page"]').val() > 1) {
        $('#DivIdToPrint tbody').append(response);
      } else {
        $('#DivIdToPrint tbody').html(response);
      }
    } else {
      $('#DivIdToPrint').html(response);
    }
    $('.tree').simpleTreeTable({
      expander: $('#expander'),
      collapser: $('#collapser')
    });

    //hide boxloading
    $('#accounting-box-loading').html('');
    $('#box-loading').html('');
    $('button[id="uploadfile"]').removeAttr('disabled');
  }).fail(function (error) {
    alert_float('danger', JSON.parse(error.mesage));
  });

  return false;
}

function report_loadmore(page) {
  "use strict";
  $('input[name="page"]').val(page);
  $('tr.load_more_btn').remove();
  $('#filter-form').submit();
}

function report_load() {
  "use strict";
  $('input[name="page"]').val(1);
  $('#filter-form').submit();
}