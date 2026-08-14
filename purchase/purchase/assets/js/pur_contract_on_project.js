(function($) {
  "use strict";

  	var _project_id = $('input[name="_project_id"]').val();
	initDataTable('.table-table_contracts', admin_url+'purchase/table_project_pur_contract/'+_project_id,[], [], {}, [11, 'desc']);

})(jQuery);
