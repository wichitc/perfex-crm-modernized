(function($) {
	"use strict";

	var _project_id = $('input[name="_project_id"]').val();
	var GoodsreceiptParams = {
		"day_vouchers": "input[name='date_add']",
		"_project_id": "input[name='_project_id']",
	};

	var table_manage_goods_receipt = $('.table-table_manage_goods_receipt');

	initDataTable(table_manage_goods_receipt, admin_url+'warehouse/table_manage_goods_receipt', [], [], GoodsreceiptParams, [0, 'desc']);
	$('.purchase_sm').DataTable().columns([0]).visible(false, false);

})(jQuery);
