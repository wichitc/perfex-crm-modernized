jQuery(function() {
    var $ = jQuery;
    var form = $('#retire-form');
    var requested = parseFloat(form.attr('data-requested')) || 0;
    var projectId = parseInt(form.attr('data-project-id')) || 0;
    var categoryId = parseInt(form.attr('data-category-id')) || 0;
    var imprestId = parseInt(form.attr('data-imprest-id')) || 0;
    var requestDate = form.attr('data-request-date') || '';

    function calculate_variance() {
        var retired = parseFloat($('#amount_retired').val()) || 0;
        var variance = requested - retired;
        
        $('#variance_display').val(variance.toFixed(2));
        
        if (variance == 0) {
            $('#variance_desc').html('<span class="text-primary bold">Exact Spent:</span> Disbursed amount matches expenditure exactly.');
            $('#refund_account_container').hide();
            $('#cash_bank_account_id').prop('required', false);
        } else if (variance > 0) {
            $('#variance_desc').html('<span class="text-success bold">Under-spent (Staff Refund):</span> Staff member spent less and will refund the remaining cash to the company.');
            $('#cash_bank_label').html('<span class="text-danger">* </span>Debit: Refund Received Account (Cash/Bank)');
            $('#refund_account_container').show();
            $('#cash_bank_account_id').prop('required', true);
        } else {
            $('#variance_desc').html('<span class="text-danger bold">Over-spent (Company Payback):</span> Staff member spent more than requested. Company will reimburse the overspend difference.');
            $('#cash_bank_label').html('<span class="text-danger">* </span>Credit: Reimbursement Paid Account (Cash/Bank)');
            $('#refund_account_container').show();
            $('#cash_bank_account_id').prop('required', true);
        }
        
        $('#cash_bank_account_id').selectpicker('refresh');
        
        // Dynamic Budget Limit Check for overspend
        if (variance < 0) {
            var overspend = Math.abs(variance);
            $.post(admin_url + 'accounting/check_budget_ajax', {
                project_id: projectId,
                category_id: categoryId,
                amount: overspend,
                exclude_id: imprestId,
                type: 'imprest',
                date: requestDate
            }, function(response) {
                var data = JSON.parse(response);
                $('#acc_budget_alert').remove();
                
                if (data.exceeded) {
                    var alert_class = 'alert-warning';
                    if (data.enforcement === 'disable') {
                        alert_class = 'alert-danger';
                        $('#submit-btn, button[type="submit"]').prop('disabled', true);
                    } else {
                        $('#submit-btn, button[type="submit"]').prop('disabled', false);
                    }
                    
                    var alert_html = '<div id="acc_budget_alert" class="alert ' + alert_class + '">';
                    alert_html += '<strong>Project Budget limit reached for overspend difference:</strong> ' + data.message;
                    alert_html += '</div>';
                    
                    $('#acc_budget_alert_container').append(alert_html);
                } else {
                    $('#submit-btn, button[type="submit"]').prop('disabled', false);
                }
            });
        } else {
            $('#acc_budget_alert').remove();
            $('#submit-btn, button[type="submit"]').prop('disabled', false);
        }
    }

    $(document).on('keyup change', '#amount_retired', calculate_variance);
    calculate_variance();
});
