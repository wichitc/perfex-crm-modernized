  var addMoreCandidateInputKey;
(function($) {
  "use strict"; 

  var InterviewServerParams = {
    "cp_from_date_filter": "[name='cp_from_date_filter']",
    "cp_to_date_filter": "[name='cp_to_date_filter']",
    "cp_manager_filter": "[name='cp_manager_filter[]']",
  };

  var table_interview = $('.table-table_interview');

  initDataTable('.table-table_interview', admin_url+'recruitment/table_interview', '', '', InterviewServerParams);
  $.each(InterviewServerParams, function(i, obj) {
      $('select' + obj).on('change', function() {
          table_interview.DataTable().ajax.reload();
      });
  });

  $('input[name="cp_from_date_filter"]').on('change', function() {  
    table_interview.DataTable().ajax.reload();
  });
  $('input[name="cp_to_date_filter"]').on('change', function() {  
    table_interview.DataTable().ajax.reload();
  });

  appValidateForm($('#interview_schedule-form'), {
             rec_campaign: 'required',is_name: 'required', interview_day:'required', from_time:'required', to_time:'required', interview_location:'required'
         }); 

  init_recruitment_interview_schedules();   

  $('#from_time').datetimepicker({
    datepicker: false,
    format: 'H:i'
  });
  $('#to_time').datetimepicker({
    datepicker: false,
    format: 'H:i'
  });

   addMoreCandidateInputKey = $('.list_candidates input[name*="email"]').length;
    $("body").on('click', '.new_candidates', function() {
         if ($(this).hasClass('disabled')) { return false; }

        var newattachment = $('.list_candidates').find('#candidates-item').eq(0).clone().appendTo('.list_candidates');
        newattachment.find('button[data-toggle="dropdown"]').remove();
        newattachment.find('select').selectpicker('refresh');

        newattachment.find('select[name="candidate[0]"]').attr('name', 'candidate[' + addMoreCandidateInputKey + ']').val('');
        newattachment.find('select[id="candidate[0]"]').attr('id', 'candidate[' + addMoreCandidateInputKey + ']').selectpicker('refresh');

        newattachment.find('label[id="email0"]').attr('id', 'email'+addMoreCandidateInputKey).text('');
        newattachment.find('label[id="phonenumber0"]').attr('id', 'phonenumber'+addMoreCandidateInputKey).text('');

        newattachment.find('input[name="cd_from_hours[0]"]').attr('name', 'cd_from_hours[' + addMoreCandidateInputKey + ']').val('');
        newattachment.find('input[id="cd_to_hours[0]"]').attr('id', 'cd_to_hours[' + addMoreCandidateInputKey + ']');

        newattachment.find('input[name="cd_to_hours[0]"]').attr('name', 'cd_to_hours[' + addMoreCandidateInputKey + ']').val('');
        newattachment.find('input[id="cd_to_hours[0]"]').attr('id', 'cd_to_hours[' + addMoreCandidateInputKey + ']');

        newattachment.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
        newattachment.find('button[name="add"]').removeClass('new_candidates').addClass('remove_candidates').removeClass('btn-success').addClass('btn-danger');

        addMoreCandidateInputKey++;
        $('.cd_from_time').datetimepicker({
          datepicker: false,
          format: 'H:i'
        });

    });

    $("body").on('click', '.remove_candidates', function() {
        $(this).parents('#candidates-item').remove();
    });


    
})(jQuery);
 var job_position;

function new_interview_schedule() {
  "use strict";
   $('#interview_schedules_modal').modal({show: true,backdrop: 'static'});
   $('.add-title').removeClass('hide');
   $('.edit-title').addClass('hide');
   $('#additional_interview').html('');

   $('select[id="candidate"]').val('').change();
   $('select[id="interviewer"]').val('').change();
   $('input[id="is_name"]').val('').change();
   $('input[id="from_time"]').val('');
   $('input[id="to_time"]').val('');
   $('select[id="campaign"]').val('').change();
   job_position ='';
   $('input[id="email"]').val('');
   $('input[id="phonenumber"]').val('');
   $('input[id="interview_location"]').val('');

   requestGetJSON('recruitment/get_candidate_sample').done(function (response) {
    addMoreCandidateInputKey = response.total_candidate;
    $('#custom_fields_items').html(response.custom_fields_html);
    
    $('.list_candidates').html('');
    $('.list_candidates').append(response.html);
    $('.selectpicker').selectpicker('refresh');

    $('.cd_from_time').datetimepicker({
      datepicker: false,
      format: 'H:i'
    });

  });

 }


 function edit_interview_schedule(invoker,id){
   "use strict";
  $('#interview_schedules_modal').modal({show: true,backdrop: 'static'});
  $('.add-title').addClass('hide');
  $('.edit-title').removeClass('hide');
  $('#additional_interview').html('');
  $('#additional_interview').append(hidden_input('id',id));
  $('#interview_schedules_modal input[name="is_name"]').val($(invoker).data('is_name'));

  if($(invoker).data('position') != 0 && $(invoker).data('position') != ''){
    job_position = $(invoker).data('position');

  }else{
    job_position = '';

  }
  if($(invoker).data('campaign') != 0){

    $('#interview_schedules_modal select[name="campaign"]').val($(invoker).data('campaign')).change();
  }else{
    $('#interview_schedules_modal select[name="campaign"]').val('').change();

  }


  $('#interview_schedules_modal input[name="interview_day"]').val($(invoker).data('interview_day'));
  $('#interview_schedules_modal input[name="from_time"]').val($(invoker).data('from_time'));
  $('#interview_schedules_modal input[name="to_time"]').val($(invoker).data('to_time'));
  $('#interview_schedules_modal input[name="interview_location"]').val($(invoker).data('interview_location'));

    var interviewer = $(invoker).data('interviewer');
    if(typeof(interviewer) == "string"){
        $('#interview_schedules_modal select[name="interviewer[]"]').val( ($(invoker).data('interviewer')).split(',')).change();
    }else{
       $('#interview_schedules_modal select[name="interviewer[]"]').val($(invoker).data('interviewer')).change();

    }
     $('.selectpicker').selectpicker('refresh');


  $.post(admin_url + 'recruitment/get_candidate_edit_interview/'+id).done(function(response) {
    response = JSON.parse(response);
     $('#custom_fields_items').html(response.custom_fields_html);
    addMoreCandidateInputKey = response.total_candidate;

    $('.list_candidates').html('');
    $('.list_candidates').append(response.html);
    $('.selectpicker').selectpicker('refresh');
    $('.cd_from_time').datetimepicker({
      datepicker: false,
      format: 'H:i'
    });
  });
 }
function init_recruitment_interview_schedules(id) {
  load_small_table_item_interview_schedules(id, '#interview_sm_view', 'interview_id', 'recruitment/get_interview_data_ajax', '.interview_sm');
}
function load_small_table_item_interview_schedules(pr_id, selector, input_name, url, table) {
   "use strict";
  var _tmpID = $('input[name="' + input_name + '"]').val();
  // Check if id passed from url, hash is prioritized becuase is last
  if (_tmpID !== '' && !window.location.hash) {
      pr_id = _tmpID;
      // Clear the current id value in case user click on the left sidebar credit_note_ids
      $('input[name="' + input_name + '"]').val('');
  } else {
      // check first if hash exists and not id is passed, becuase id is prioritized
      if (window.location.hash && !pr_id) {
          pr_id = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
      }
  }
  if (typeof(pr_id) == 'undefined' || pr_id === '') { return; }
  if (!$("body").hasClass('small-table')) { toggle_small_view_interview_schedules(table, selector); }
  $('input[name="' + input_name + '"]').val(pr_id);
  do_hash_helper(pr_id);
  $(selector).load(admin_url + url + '/' + pr_id);
  if (is_mobile()) {
      $('html, body').animate({
          scrollTop: $(selector).offset().top + 150
      }, 600);
  }
}
function toggle_small_view_interview_schedules(table, main_data) {
 "use strict";
  var hidden_columns = [4,6,7];
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
function candidate_infor_change(invoker){
  "use strict";
  var result = invoker.name.match(/\d/g);
  var data = {};
  data.interview_day = $('input[name="interview_day"]').val();
  data.from_time = $('input[name="from_time"]').val();
  data.to_time = $('input[name="to_time"]').val();
  data.candidate = invoker.value;
  data.id = $('input[name="id"]').val();

  console.log('result', result);

  result = result[0];
  console.log('result', result);

  if(invoker.value == ''){
    $('#email'+result).text('');
    $('#phonenumber'+result).text('');

  }else{
    $.post(admin_url + 'recruitment/get_candidate_infor_change/'+invoker.value).done(function(response) {
        response = JSON.parse(response);
        $('#email'+result).text(response.email);
        $('#phonenumber'+result).text(response.phonenumber);

    });
    $.post(admin_url + 'recruitment/check_time_interview',data).done(function(response) {
        response = JSON.parse(response);
        if(response.return == true){
          alert_float('warning',response.rs,6000);
          $('select[name="candidate['+result+']"]').val('').change();

        }
    });
  }
}


function campaign_change(){

  var data_select = {};
    data_select.campaign = $('select[name="campaign"]').val();

    $.post(admin_url + 'recruitment/get_position_fill_data',data_select).done(function(response){
         response = JSON.parse(response);
         $("select[name='position']").html('');
         
         $("select[name='position']").append(response.position);
         $("select[name='position']").selectpicker('refresh');

        if(job_position != 0 || job_position != ''){

          $('#interview_schedules_modal select[name="position"]').val(job_position).change();

        }


       });

};