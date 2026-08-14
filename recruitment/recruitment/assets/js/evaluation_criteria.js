function new_evaluation_criteria(){
    "use strict";
    $('#evaluation_criteria').modal({show: true,backdrop: 'static'});
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
    $('#additional_criteria').html('');
    $('#evaluation_criteria select[name="criteria_type"]').val('').change();

    var criteria_id = {};
    criteria_id.id = 0;
    criteria_id.group_criteria = 0;
    criteria_id.status = 'add';

    $.get(admin_url+'recruitment/get_criteria_group', criteria_id).done(function(response){
        response = JSON.parse(response);
        $('#evaluation_criteria select[name="group_criteria"]').html('');
        $('#evaluation_criteria select[name="group_criteria"]').html(response.html);

        init_selectpicker();
        $('.selectpicker').selectpicker('refresh');
    }).fail(function(error) {
    });
}
function edit_evaluation_criteria(invoker,id){
    "use strict";
    $('#additional_criteria').html('');
    $('#additional_criteria').append(hidden_input('id',id));

    $('#evaluation_criteria input[name="criteria_title"]').val($(invoker).data('criteria_title'));
    $('#evaluation_criteria textarea[name="description"]').val($(invoker).data('description'));
    $('#evaluation_criteria input[name="score_des1"]').val($(invoker).data('score_des1'));
    $('#evaluation_criteria input[name="score_des2"]').val($(invoker).data('score_des2'));
    $('#evaluation_criteria input[name="score_des3"]').val($(invoker).data('score_des3'));
    $('#evaluation_criteria input[name="score_des4"]').val($(invoker).data('score_des4'));
    $('#evaluation_criteria input[name="score_des5"]').val($(invoker).data('score_des5'));
    $('#evaluation_criteria select[name="criteria_type"]').val($(invoker).data('criteria_type'));
    $('#evaluation_criteria select[name="criteria_type"]').change();

    var criteria_id = {};
        criteria_id.id = $('#evaluation_criteria input[name="id"]').val();
        criteria_id.group_criteria = $(invoker).data('group_criteria');
        criteria_id.status = 'edit';

        $.get(admin_url+'recruitment/get_criteria_group', criteria_id).done(function(response){
            response = JSON.parse(response);
            $('#evaluation_criteria select[name="group_criteria"]').html('');
            $('#evaluation_criteria select[name="group_criteria"]').html(response.html);

            init_selectpicker();
            $('.selectpicker').selectpicker('refresh');
        }).fail(function(error) {
        });


    $('#evaluation_criteria').modal({show: true,backdrop: 'static'});
    $('.add-title').addClass('hide');
    $('.edit-title').removeClass('hide');
}

function criteria_type_change(invoker){
    "use strict";
    if(invoker.value == 'criteria'){
        $('select[name="group_criteria"]').attr('required','');
        $('#select_group_criteria').removeClass('hide');
    }else{
        $('select[name="group_criteria"]').removeAttr('required');
        $('#select_group_criteria').addClass('hide');
    }
}
