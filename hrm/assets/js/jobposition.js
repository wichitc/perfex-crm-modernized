        function new_job_position(){
		"use strict";
            $('#additional').empty();
            $('#job_position input[name="position_name"]').val('');
            $('#job_position select[name="job_description_group_id"]').val('');
            $('#job_position textarea[name="duties_responsibilities"]').val('');
            $('#job_position').modal('show');
            $('.edit-title').addClass('hide');
            $('.add-title').removeClass('hide');
        }
        function edit_job_position(invoker,id){
		"use strict";
            $('#additional').empty().append(hidden_input('id',id));
            $('#job_position input[name="position_name"]').val($(invoker).data('name'));
            var groupId = $(invoker).data('group-id') || '';
            var $sel = $('#job_position select[name="job_description_group_id"]');
            $sel.val(groupId);
            if ($sel.hasClass('selectpicker')) { $sel.selectpicker('refresh'); }
            var duties = $('.job-duties-data[data-position-id="' + id + '"]').text();
            $('#job_position textarea[name="duties_responsibilities"]').val(duties || '');
            $('#job_position').modal('show');
            $('.add-title').addClass('hide');
            $('.edit-title').removeClass('hide');
        }