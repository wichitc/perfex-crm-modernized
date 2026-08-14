<script>
var hidden_columns = [];
(function($) {
"use strict";

init_pur_ajax_search('vendors', '#vendor_ft');
var faf_requestServerParams = {
	"vendor": "[name='vendor_ft[]']",
	"from_date": 'input[name="from_date"]',
    "to_date": 'input[name="to_date"]',
    "status": "[name='status[]']",
    "department": "[name='department[]']",
    "requestor": "[name='requestor[]']",
    };

var table_faf_requests = $('.table-faf_requests');    
initDataTable('.table-faf_requests', window.location.href, [], [],
        faf_requestServerParams, [1, 'desc']);

 $.each(faf_requestServerParams, function(i, obj) {
    $('select' + obj).on('change', function() {  
        table_faf_requests.DataTable().ajax.reload()
            .columns.adjust();
    });
});

$('input[name="from_date"]').on('change', function() {
    table_faf_requests.DataTable().ajax.reload()
            .columns.adjust();
});
$('input[name="to_date"]').on('change', function() {
    table_faf_requests.DataTable().ajax.reload()
            .columns.adjust();
});

init_faf_request();

})(jQuery);


function init_faf_request(id) {
    "use strict";
    load_small_faf_request_table_item(id, '#faf_request_div', 'fafid', 'purchase/get_faf_request_data_ajax', '.table-faf_requests');
}
function load_small_faf_request_table_item(id, selector, input_name, url, table) {
    "use strict";
    var _tmpID = $('input[name="' + input_name + '"]').val();
    // Check if id passed from url, hash is prioritized becuase is last
    if (_tmpID !== '' && !window.location.hash) {
        id = _tmpID;
        // Clear the current id value in case user click on the left sidebar credit_note_ids
        $('input[name="' + input_name + '"]').val('');
    } else {
        // check first if hash exists and not id is passed, becuase id is prioritized
        if (window.location.hash && !id) {
            id = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        }
    }
    if (typeof(id) == 'undefined' || id === '') { return; }
    destroy_dynamic_scripts_in_element($(selector))
    if (!$("body").hasClass('small-table')) { toggle_small_faf_request_view(table, selector); }
    $('input[name="' + input_name + '"]').val(id);
    do_hash_helper(id);
    $(selector).load(admin_url + url + '/' + id);
    if (is_mobile()) {
        $('html, body').animate({
            scrollTop: $(selector).offset().top + 150
        }, 600);
    }
}

function toggle_small_faf_request_view(table, main_data) {
        "use strict";
        $("body").toggleClass('small-table');
        var tablewrap = $('#small-table');
        if (tablewrap.length === 0) { return; }
        var _visible = false;
        if (tablewrap.hasClass('col-md-5')) {
            tablewrap.removeClass('col-md-5').addClass('col-md-12');
            _visible = true;
            $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-right').addClass('fa fa-angle-double-left');
        } else {
            tablewrap.addClass('col-md-5').removeClass('col-md-12');
            $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-left').addClass('fa fa-angle-double-right');
        }
        var _table = $(table).DataTable();
        // Show hide hidden columns
        _table.columns(hidden_columns).visible(_visible, false);
        _table.columns.adjust();
        $(main_data).toggleClass('hide');
        $(window).trigger('resize');
}	


// Toggle full view for small tables like proposals
function small_faf_table_full_view() {
  $("#small-table").toggleClass("hide");
  $(".small-table-right-col").toggleClass("col-md-12 col-md-7");

  $("#detail_div").toggleClass("col-md-12 col-md-offset-0 col-md-8 col-md-offset-2");

  $(window).trigger("resize");
}

</script>