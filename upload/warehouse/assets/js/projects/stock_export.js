(function($) {
	"use strict";

	var _project_id = $('input[name="_project_id"]').val();
	var InvoiceServerParams = {
		"_project_id": "input[name='_project_id']",
	};

	var table_manage_delivery = $('.table-table_manage_delivery');

	initDataTable(table_manage_delivery, admin_url+'warehouse/table_manage_delivery',[],[], InvoiceServerParams, [0 ,'desc']);

	$('.delivery_sm').DataTable().columns([0]).visible(false, false);

})(jQuery);
