var fnServerParams;
(function ($) {
  "use strict";

  fnServerParams = {
  };
  $(document).ready(function () {
    init_class_table();

    $('.add-new-class').on('click', function () {

      $('#class-modal').find('button[type="submit"]').prop('disabled', false);
      $('#class-modal').modal('show');
      $('input[name="id"]').val('');
      $('select[name="type"]').val('segment').change();
      $('input[name="name"]').val('');
      $('input[name="color"]').val('');
      $('textarea[name="description"]').val('');
    });

    appValidateForm($('#class-form'), {
      name: 'required',
      type: 'required',
    }, class_form_handler);
  });

})(jQuery);

function init_class_table() {
  "use strict";

  if ($.fn.DataTable.isDataTable('.table-class')) {
    $('.table-class').DataTable().destroy();
  }
  initDataTable('.table-class', admin_url + 'accounting/class_table', false, false, fnServerParams);
}

function edit_class(id) {
  "use strict";
  $('#class-modal').find('button[type="submit"]').prop('disabled', false);

  requestGetJSON(admin_url + 'accounting/get_data_class/' + id).done(function (response) {
    $('select[name="type"]').val(response.type).change();
    $('input[name="name"]').val(response.name);
    $('.colorpicker-input').colorpicker('setValue', response.color);
    $('input[name="id"]').val(id);
    $('textarea[name="description"]').val(response.description.replace(/(<|&lt;)br\s*\/*(>|&gt;)/g, " "));
    $('#class-modal').modal('show');

  });
}


function class_form_handler(form) {
  "use strict";
  $('#class-modal').find('button[type="submit"]').prop('disabled', true);

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
  }).done(function (response) {
    response = JSON.parse(response);
    if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
      alert_float('success', response.message);
      init_class_table();
    } else {
      alert_float('danger', response.message);
    }
    $('#class-modal').modal('hide');
  }).fail(function (error) {
    alert_float('danger', JSON.parse(error.mesage));
  });

  return false;
}