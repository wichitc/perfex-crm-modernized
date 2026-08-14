(function($) {
"use strict";
	var table_contracts = $('.table-table_contracts');
	var Params = {
        "vendor": "[name='vendor[]']",
        "department": "[name='department[]']",
        "project": "[name='project[]']",
    };

	initDataTable(table_contracts, admin_url+'purchase/table_contracts',[], [], Params, [11, 'desc']);
    init_pur_ajax_search('vendors', '#vendor');

	 $.each(Params, function(i, obj) {
        $('select' + obj).on('change', function() {  
            table_contracts.DataTable().ajax.reload()
                .columns.adjust();
        });
    });
})(jQuery);