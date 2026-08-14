document.addEventListener("DOMContentLoaded", function() {
    var $ = jQuery;
    
    function check_imprest_budget() {
        var project_id = $('#project_id').val();
        var category_id = $('#category_id').val();
        var amount = parseFloat($('#amount_requested').val()) || 0;
        
        if (!project_id || !category_id || amount <= 0) {
            $('#acc_budget_alert').remove();
            $('#submit-btn, button[type="submit"]').prop('disabled', false);
            return;
        }
        
        var date = $('#request_date').val();
        $.post(admin_url + 'accounting/check_budget_ajax', {
            project_id: project_id,
            category_id: category_id,
            amount: amount,
            type: 'imprest',
            date: date
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
                alert_html += '<strong>Project Budget limit reached:</strong> ' + data.message;
                alert_html += '</div>';
                
                $('#acc_budget_alert_container').append(alert_html);
            } else {
                $('#submit-btn, button[type="submit"]').prop('disabled', false);
            }
        });
    }

    $(document).on('change', '#project_id, #category_id, #request_date', check_imprest_budget);
    $(document).on('keyup change', '#amount_requested', check_imprest_budget);
});
