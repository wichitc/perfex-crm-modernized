var fnServerParams;
(function ($) {
    "use strict";

    fnServerParams = {};
    $(document).ready(function () {
        init_budget_categories_table();

        $('.add-new-budget-category').on('click', function (e) {
            e.preventDefault();
            $('#budget-category-modal').find('button[type="submit"]').prop('disabled', false);
            $('#budget-category-modal').modal('show');
            $('input[name="id"]').val('');
            $('input[name="name"]').val('');
        });

        appValidateForm($('#budget-category-form'), {
            name: 'required',
        }, budget_category_form_handler);
    });
})(jQuery);

function init_budget_categories_table() {
    "use strict";

    if ($.fn.DataTable.isDataTable('.table-budget-categories')) {
        $('.table-budget-categories').DataTable().destroy();
    }
    initDataTable('.table-budget-categories', admin_url + 'accounting/budget_category_table', false, false, fnServerParams);
}

function edit_budget_category(id) {
    "use strict";
    $('#budget-category-modal').find('button[type="submit"]').prop('disabled', false);

    requestGetJSON(admin_url + 'accounting/get_data_budget_category/' + id).done(function (response) {
        $('input[name="id"]').val(id);
        $('input[name="name"]').val(response.name);
        $('#budget-category-modal').modal('show');
    });
}

function budget_category_form_handler(form) {
    "use strict";
    $('#budget-category-modal').find('button[type="submit"]').prop('disabled', true);

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
            init_budget_categories_table();
        } else {
            alert_float('danger', response.message);
        }
        $('#budget-category-modal').modal('hide');
    }).fail(function (error) {
        alert_float('danger', 'Error processing request');
    });

    return false;
}
