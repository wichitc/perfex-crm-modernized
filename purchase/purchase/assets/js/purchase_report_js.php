<script>
var report_import_goods, report_po_voucher,
report_from_choose, report_po, report_pur_inv, report_pur_inv_payment,
fnServerParams, 
statistics_number_of_purchase_orders, 
statistics_cost_of_purchase_orders,
statistics_po_by_type,
statistics_payable_receivable;
 var report_from = $('input[name="report-from"]');
 var report_to = $('input[name="report-to"]');
  var date_range = $('#date-range');
(function($) {
  "use strict";
  report_pur_inv = $('#list_purchase_inv_report');
  report_pur_inv_payment = $('#list_purchase_inv_payment_report');
  report_po = $('#list_po_report');
  report_po_voucher = $('#list_po_voucher');
  report_import_goods = $('#list_import_goods');
  statistics_number_of_purchase_orders = $('#number-purchase-orders-report');
  statistics_cost_of_purchase_orders = $('#cost-purchase-orders-report');
  statistics_po_by_type = $('#statistics-po-by-type');
   statistics_payable_receivable = $('#payable-receivable-report');
  report_from_choose = $('#report-time');
  fnServerParams = {
    "products_services": '[name="products_services"]',
    "report_months": '[name="months-report"]',
    "report_from": '[name="report-from"]',
    "report_to": '[name="report-to"]',
    "year_requisition": "[name='year_requisition']",
    "report_currency": '[name="currency"]',
    "payment_status": '[name="payment_status"]',
    "approve_status": '[name="approve_status"]',
     "payment_mode": '[name="payment_mode"]',
  }
  
  $('select[name="products_services"]').on('change', function() {
    gen_reports();
  });

  $('select[name="currency"]').on('change', function() {
    gen_reports();
  });

  $('select[name="payment_status"]').on('change', function() {
    gen_reports();
  });

  $('select[name="approve_status"]').on('change', function() {
    gen_reports();
  });

   $('select[name="payment_mode"]').on('change', function() {
    gen_reports();
  });


  $('select[name="months-report"]').on('change', function() {
    if($(this).val() != 'custom'){
     gen_reports();
    }
   });

   $('select[name="year_requisition"]').on('change', function() {
     gen_reports();
   });

   report_from.on('change', function() {
     var val = $(this).val();
     var report_to_val = report_to.val();
     if (val != '') {
       report_to.attr('disabled', false);
       if (report_to_val != '') {
         gen_reports();
       }
     } else {
       report_to.attr('disabled', true);
     }
   });

   report_to.on('change', function() {
     var val = $(this).val();
     if (val != '') {
       gen_reports();
     }
   });

   $('.table-import-goods-report').on('draw.dt', function() {
     var paymentReceivedReportsTable = $(this).DataTable();
     var sums = paymentReceivedReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
     $(this).find('tfoot td.total').html(sums.total);
   });

   $('.table-po-report').on('draw.dt', function() {
     var poReportsTable = $(this).DataTable();
     var sums = poReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
     $(this).find('tfoot td.total').html(sums.total);
     $(this).find('tfoot td.total_tax').html(sums.total_tax);
     $(this).find('tfoot td.total_value').html(sums.total_value);
   });

   $('.table-purchase-inv-report').on('draw.dt', function() {
     var piReportsTable = $(this).DataTable();
     var sums = piReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
     $(this).find('tfoot td.total').html(sums.total);
     $(this).find('tfoot td.total_tax').html(sums.total_tax);
     $(this).find('tfoot td.total_value').html(sums.total_value);
   });

   $('.table-purchase-inv-payment-report').on('draw.dt', function() {
     var paymentReportsTable = $(this).DataTable();
     var sums = paymentReportsTable.ajax.json().sums;
     console.log(paymentReportsTable.ajax.json());
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
     $(this).find('tfoot td.total').html(sums.total);
     
   });

   $('select[name="months-report"]').on('change', function() {
     var val = $(this).val();
     report_to.attr('disabled', true);
     report_to.val('');
     report_from.val('');
     if (val == 'custom') {
       date_range.addClass('fadeIn').removeClass('hide');
       return;
     } else {
       if (!date_range.hasClass('hide')) {
         date_range.removeClass('fadeIn').addClass('hide');
       }
     }
     gen_reports();
   });
})(jQuery);


 function init_report(e, type) {
  "use strict";

   var report_wrapper = $('#report');

   if (report_wrapper.hasClass('hide')) {
        report_wrapper.removeClass('hide');
   }

   $('head title').html($(e).text());
   

   report_from_choose.addClass('hide');

   $('#year_requisition').addClass('hide');

   report_po.addClass('hide');
   report_po_voucher.addClass('hide');
   report_pur_inv.addClass('hide');
   report_pur_inv_payment.addClass('hide');
   report_import_goods.addClass('hide');
  statistics_cost_of_purchase_orders.addClass('hide');
  statistics_payable_receivable.addClass('hide');
  statistics_po_by_type.addClass('hide');
  statistics_number_of_purchase_orders.addClass('hide');
  $('#payment_status_f').addClass('hide');
  $('#papprove_status_f').addClass('hide');

  $('select[name="months-report"]').selectpicker('val', 'this_month');
    // Clear custom date picker
      $('#currency').removeClass('hide');

      if (type != 'statistics_number_of_purchase_orders' && type != 'statistics_cost_of_purchase_orders') {
        report_from_choose.removeClass('hide');
      }
      if (type == 'list_import_goods') {
        report_import_goods.removeClass('hide');
      }else if(type == 'statistics_number_of_purchase_orders'){
        $('#currency').addClass('hide');
        statistics_number_of_purchase_orders.removeClass('hide');
        $('#year_requisition').removeClass('hide');
      }else if(type == 'statistics_cost_of_purchase_orders'){
        statistics_cost_of_purchase_orders.removeClass('hide');
        $('#year_requisition').removeClass('hide');
      }else if(type == 'po_voucher_report'){
        $('#currency').addClass('hide');
        report_po_voucher.removeClass('hide');
      }else if(type == 'po_report'){
        report_po.removeClass('hide');
      }else if(type == 'purchase_invoice_rp'){
        report_pur_inv.removeClass('hide');
        $('#payment_status_f').removeClass('hide');
      }else if(type == 'purchase_invoice_payment_rp'){
        report_pur_inv_payment.removeClass('hide');
        $('#approve_status_f').removeClass('hide');
        $('#payment_mode_f').removeClass('hide');
      }else if(type == 'statistics_payable_receivable'){
        statistics_payable_receivable.removeClass('hide');
        $('#year_requisition').removeClass('hide');
        $('#report-time').addClass('hide');
      }else if(type == 'statistics_po_by_type'){
        statistics_po_by_type.removeClass('hide');
        $('#year_requisition').addClass('hide');
        $('#report-time').removeClass('hide');
      }



      gen_reports();
}


function import_goods_report() {
  "use strict";

 if ($.fn.DataTable.isDataTable('.table-import-goods-report')) {
   $('.table-import-goods-report').DataTable().destroy();
 }
 initDataTable('.table-import-goods-report', admin_url + 'purchase/import_goods_report', false, false, fnServerParams);
}

function po_voucher_report() {
  "use strict";

 if ($.fn.DataTable.isDataTable('.table-po-voucher-report')) {
   $('.table-po-voucher-report').DataTable().destroy();
 }
 initDataTable('.table-po-voucher-report', admin_url + 'purchase/po_voucher_report', false, false, fnServerParams);
}

function po_report() {
  "use strict";

 if ($.fn.DataTable.isDataTable('.table-po-report')) {
   $('.table-po-report').DataTable().destroy();
 }
 initDataTable('.table-po-report', admin_url + 'purchase/po_report', false, false, fnServerParams);
}

function purchase_inv_report() {
  "use strict";

 if ($.fn.DataTable.isDataTable('.table-purchase-inv-report')) {
   $('.table-purchase-inv-report').DataTable().destroy();
 }
 initDataTable('.table-purchase-inv-report', admin_url + 'purchase/purchase_inv_report', false, false, fnServerParams);
}

function purchase_inv_payment_report() {
  "use strict";

 if ($.fn.DataTable.isDataTable('.table-purchase-inv-payment-report')) {
   $('.table-purchase-inv-payment-report').DataTable().destroy();
 }
 initDataTable('.table-purchase-inv-payment-report', admin_url + 'purchase/purchase_inv_payment_report', false, false, fnServerParams);
}



function number_of_purchase_orders_analysis() {
  "use strict";

  var data = {};
   data.year = $('select[name="year_requisition"]').val();
  $.post(admin_url + 'purchase/number_of_purchase_orders_analysis/', data).done(function(response) {
     response = JSON.parse(response);
        Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'inherit !important',
              fill: 'black'
          }
      },
      colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
     });
        Highcharts.chart('container_number_purchase_orders', {
         chart: {
             type: 'column'
         },
         title: {
             text: '<?php echo _l('number_of_purchase_orders') ?>'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: ['<?php echo _l('month_1') ?>',
                '<?php echo _l('month_2') ?>',
                '<?php echo _l('month_3') ?>',
                '<?php echo _l('month_4') ?>',
                '<?php echo _l('month_5') ?>',
                '<?php echo _l('month_6') ?>',
                '<?php echo _l('month_7') ?>',
                '<?php echo _l('month_8') ?>',
                '<?php echo _l('month_9') ?>',
                '<?php echo _l('month_10') ?>',
                '<?php echo _l('month_11') ?>',
                '<?php echo _l('month_12') ?>'],
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: ''
             }
         },
         tooltip: {
             headerFormat: '<span>{point.key}</span><table>',
             pointFormat: '<tr><td>{series.name}: </td>' +
                 '<td><b>{point.y:.0f}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
             column: {
                 pointPadding: 0.2,
                 borderWidth: 0
             }
         },

         series: [{
            type: 'column',
            colorByPoint: true,
            name: '<?php echo _l('purchase_quantity') ?>',
            data: response,
            showInLegend: false
         }]
     });
        
  })
}

function cost_of_purchase_orders_analysis() {
  "use strict";

  var data = {};
   data.year = $('select[name="year_requisition"]').val();
   data.report_currency = $('select[name="currency"]').val();
  $.post(admin_url + 'purchase/cost_of_purchase_orders_analysis', data).done(function(response) {
     response = JSON.parse(response);
        Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'inherit !important',
              fill: 'black'
          }
      },
      colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
     });
        Highcharts.chart('container_cost_purchase_orders', {
         chart: {
             type: 'column'
         },
         title: {
             text: '<?php echo _l('cost_of_purchase_orders') ?>'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: ['<?php echo _l('month_1') ?>',
                '<?php echo _l('month_2') ?>',
                '<?php echo _l('month_3') ?>',
                '<?php echo _l('month_4') ?>',
                '<?php echo _l('month_5') ?>',
                '<?php echo _l('month_6') ?>',
                '<?php echo _l('month_7') ?>',
                '<?php echo _l('month_8') ?>',
                '<?php echo _l('month_9') ?>',
                '<?php echo _l('month_10') ?>',
                '<?php echo _l('month_11') ?>',
                '<?php echo _l('month_12') ?>'],
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: response.name
             }
         },
         tooltip: {
             headerFormat: '<span >{point.key}</span><table>',
             pointFormat: '<tr>' +
                 '<td><b>{point.y:.0f} {series.name}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
             column: {
                 pointPadding: 0.2,
                 borderWidth: 0
             }
         },

         series: [{
            type: 'column',
            colorByPoint: true,
            name: response.unit,
            data: response.data,
            showInLegend: false,
         }]
     });
        
  })
}


function payable_receivable_analysis() {
  "use strict";

  var data = {};
   data.year = $('select[name="year_requisition"]').val();
   data.report_currency = $('select[name="currency"]').val();
  $.post(admin_url + 'purchase/payable_receivable_analysis', data).done(function(response) {
     response = JSON.parse(response);
        Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'inherit !important',
              fill: 'black'
          }
      },
      colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
     });
        Highcharts.chart('container_payable_receivable_rp', {
         chart: {
             type: 'column'
         },
         title: {
             text: '<?php echo _l('payable_receivable_analysis') ?>'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: ['<?php echo _l('month_1') ?>',
                '<?php echo _l('month_2') ?>',
                '<?php echo _l('month_3') ?>',
                '<?php echo _l('month_4') ?>',
                '<?php echo _l('month_5') ?>',
                '<?php echo _l('month_6') ?>',
                '<?php echo _l('month_7') ?>',
                '<?php echo _l('month_8') ?>',
                '<?php echo _l('month_9') ?>',
                '<?php echo _l('month_10') ?>',
                '<?php echo _l('month_11') ?>',
                '<?php echo _l('month_12') ?>'],
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: response.name
             }
         },
         tooltip: {
             headerFormat: '<span >{point.key}</span><table>',
             pointFormat: '<tr>' +
                 '<td><b>{point.y:.0f}'+response.unit +' {series.name}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
             column: {
                 pointPadding: 0.2,
                 borderWidth: 0
             }
         },

         series: [
            {
                name: ' <?php echo _l('payable'); ?>',
                data: response.data_payable
            },
            {
                name: ' <?php echo _l('receivable'); ?>',
                data: response.data_receivable
            }
    ]
     });
        
  })
}

 function statistics_po_by_type_count(){
    var data = {};
    data.report_months = $('select[name="months-report"]').val();
    data.report_currency = $('select[name="currency"]').val();
    data.report_from = $('input[name="report-from"]').val();
    data.report_to = $('input[name="report-to"]').val();
    $.post(admin_url + 'purchase/statistics_po_by_type_count', data).done(function(response) {
        response = JSON.parse(response); 

            Highcharts.setOptions({
              chart: {
                  style: {
                      fontFamily: 'inherit !important',
                      fontWeight:'normal',
                      fill: 'black'
                  }
              },
              colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
             });

            Highcharts.chart('statistics_po_by_type_count_rp', {
                chart: {
                    backgroundcolor: '#fcfcfc8a',
                    type: 'variablepie'
                },
                accessibility: {
                    description: null
                },
                title: {
                    text: '<?php echo _l('count_po_by_type'); ?>'
                },
                credits: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '{point.y}',
                    shared: true
                },
                 plotOptions: {
                    variablepie: {
                        dataLabels: {
                            enabled: false,
                            },
                        showInLegend: true        
                    }
                },
                series: [{
                    minPointSize: 10,
                    innerSize: '20%',
                    zMin: 0,
                    name: <?php echo json_encode(_l('invoice_table_quantity_heading')); ?>,
                    data: response.data_count,
                    point:{
                          events:{
                              click: function (event) {
                                 if(this.statusLink !== undefined)
                                 { 
                                   window.location.href = this.statusLink;

                                 }
                              }
                          }
                      }
                }]
            });

        });
    }


 function statistics_po_by_type_cost(){
    var data = {};
    data.report_months = $('select[name="months-report"]').val();
    data.report_currency = $('select[name="currency"]').val();
    data.report_from = $('input[name="report-from"]').val();
    data.report_to = $('input[name="report-to"]').val();
    $.post(admin_url + 'purchase/statistics_po_by_type_cost', data).done(function(response) {
        response = JSON.parse(response); 

            Highcharts.setOptions({
              chart: {
                  style: {
                      fontFamily: 'inherit !important',
                      fontWeight:'normal',
                      fill: 'black'
                  }
              },
              colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
             });

            Highcharts.chart('statistics_po_by_type_cost_rp', {
                chart: {
                    backgroundcolor: '#fcfcfc8a',
                    type: 'variablepie'
                },
                accessibility: {
                    description: null
                },
                title: {
                    text: '<?php echo _l('total_po_value_by_type'); ?>'
                },
                credits: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '{point.y} '+response.unit,
                    shared: true
                },
                 plotOptions: {
                    variablepie: {
                        dataLabels: {
                            enabled: false,
                            },
                        showInLegend: true        
                    }
                },
                series: [{
                    minPointSize: 10,
                    innerSize: '20%',
                    zMin: 0,
                    name: <?php echo json_encode(_l('invoice_table_quantity_heading')); ?>,
                    data: response.data_count,
                    point:{
                          events:{
                              click: function (event) {
                                 if(this.statusLink !== undefined)
                                 { 
                                   window.location.href = this.statusLink;

                                 }
                              }
                          }
                      }
                }]
            });

        });
    }

// Main generate report function
function gen_reports() {
  "use strict";

 if (!report_import_goods.hasClass('hide')) {
   import_goods_report();
 }else if (!statistics_number_of_purchase_orders.hasClass('hide')) {
    number_of_purchase_orders_analysis();
 }else if (!statistics_cost_of_purchase_orders.hasClass('hide')) {
    cost_of_purchase_orders_analysis();
 }else if(!report_po_voucher.hasClass('hide')){
    po_voucher_report();
 }else if(!report_po.hasClass('hide')){
    po_report();
 }else if(!report_pur_inv.hasClass('hide')){
    purchase_inv_report();
 }else if(!report_pur_inv_payment.hasClass('hide')){
    purchase_inv_payment_report();
 }else if (!statistics_payable_receivable.hasClass('hide')) {
    payable_receivable_analysis();
 }else if (!statistics_po_by_type.hasClass('hide')) {
    statistics_po_by_type_count();
    statistics_po_by_type_cost();
 }
}
</script>


