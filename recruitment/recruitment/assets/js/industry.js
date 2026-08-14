function new_industry(){
    "use strict";
    $('#industry').modal({show: true,backdrop: 'static'});
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
    
    $('#industry input[name="industry_name"]').val('');
    $('#additional').html('');
}
function edit_industry(invoker,id){
    "use strict";
    $('#additional').append(hidden_input('id',id));
    $('#industry input[name="industry_name"]').val($(invoker).data('name'));

    $('#industry').modal({show: true,backdrop: 'static'});
    $('.add-title').addClass('hide');
    $('.edit-title').removeClass('hide');
}
