<script>
	$("#wh_enter_activity").on('click', function() {
		"use strict"; 

		var message = $('#wh_activity_textarea').val();
		var interview_schedule_id = $('input[name="_attachment_sale_id"]').val();

		if (message === '') { return; }
		$.post(admin_url + 'recruitment/re_add_activity', {
			interview_schedule_id: interview_schedule_id,
			activity: message,
			rel_type: 'rec_interview',
		}).done(function(response) {
			response = JSON.parse(response);
			if(response.status == true){
				alert_float('success', response.message);
				init_recruitment_interview_schedules(interview_schedule_id)
			}else{
				alert_float('danger', response.message);
			}
		}).fail(function(data) {
			alert_float('danger', data.message);
		});
	});

	function delete_wh_activitylog(wrapper, id) {
		"use strict"; 

		if (confirm_delete()) {
			requestGetJSON('recruitment/delete_activitylog/' + id).done(function(response) {
				if (response.success === true || response.success == 'true') { $(wrapper).parents('.feed-item').remove(); }
			}).fail(function(data) {
				alert_float('danger', data.responseText);
			});
		}
	}
</script>