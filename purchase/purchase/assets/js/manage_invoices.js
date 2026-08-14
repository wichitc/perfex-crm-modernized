(function($) {
	"use strict"; 
	var table_invoice = $('.table-table_pur_invoices');
	var Params = {
		"from_date": 'input[name="from_date"]',
        "to_date": 'input[name="to_date"]',
        "contract": "[name='contract[]']",
        "pur_orders": "[name='pur_orders[]']",
        "vendors": "[name='vendor_ft[]']",
        "payment_status": "[name='payment_status[]']",
        "currency": "[name='currency[]']",
    };
    init_pur_ajax_search('vendors', '#vendor_ft');
	initDataTable(table_invoice, admin_url+'purchase/table_pur_invoices',[], [], Params, [5, 'desc']);
	$.each(Params, function(i, obj) {
        $('select' + obj).on('change', function() {  
            table_invoice.DataTable().ajax.reload()
                .columns.adjust();
        });
    });

    $('input[name="from_date"]').on('change', function() {
        table_invoice.DataTable().ajax.reload()
                .columns.adjust();
    });
    $('input[name="to_date"]').on('change', function() {
        table_invoice.DataTable().ajax.reload()
                .columns.adjust();
    });
})(jQuery);


function pur_add_batch_payment() {
  $("#modal-wrapper").load(
    admin_url + "purchase/batch_payment_modal",
    function () {
      if ($("#batch-payment-modal").is(":hidden")) {
        $("#batch-payment-modal").modal({
          backdrop: "static",
          show: true,
        });
      }
      init_selectpicker();
      init_datepicker();

      var $filterByClientSelect = $("#batch-payment-filter");
      $filterByClientSelect.on("changed.bs.select", function () {
        if ($filterByClientSelect.val() !== "") {
          $(".batch_payment_item").each(function () {
            if ($(this).data("vendorid") == $filterByClientSelect.val()) {
              $(this).find("input, select").prop("disabled", false);
              $(this).removeClass("hide");
            } else {
              $(this).addClass("hide");
              $(this).find("input, select").prop("disabled", true);
            }
          });
        } else {
          $(".batch_payment_item").each(function () {
            $(this).removeClass("hide");
            $(this).find("input, select").prop("disabled", false);
          });
        }
      });
      appValidateForm($("#batch-payment-form"), {});
      $(".batch_payment_item").each(function () {
        var invoiceLine = $(this).find('[name^="invoice"]');

        invoiceLine
          .filter('select[name$="[paymentmode]"],input[name$="[amount]"]')
          .each(function () {
            var field = $(this);
            field.rules("add", {
              required: function () {
                var isRequired = false;
                var rowFields = field
                  .closest(".batch_payment_item")
                  .find("input, select");
                rowFields
                  .filter(
                    'select[name$="[paymentmode]"],input[name$="[transactionid]"],input[name$="[amount]"]'
                  )
                  .each(function () {
                    if ($(this).val() != "") {
                      isRequired = true;
                    }

                    if ($(this).hasClass("selectpicker") && isRequired) {
                      field.prop("required", true);
                      $(this).selectpicker("refresh");
                    }
                  });
                return isRequired;
              },
            });
          });
      });
    }
  );
}