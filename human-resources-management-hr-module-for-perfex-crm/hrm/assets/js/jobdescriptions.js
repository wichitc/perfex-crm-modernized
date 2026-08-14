function new_job_description_group(){
    "use strict";
    $('#additional_job_description_group').empty();
    $('#job_description_group input[name="name"]').val('');
    $('#job_description_group').modal('show');
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
}
function edit_job_description_group(invoker, id){
    "use strict";
    $('#additional_job_description_group').empty().append(hidden_input('id', id));
    $('#job_description_group input[name="name"]').val($(invoker).data('name'));
    $('#job_description_group').modal('show');
    $('.add-title').addClass('hide');
    $('.edit-title').removeClass('hide');
}
