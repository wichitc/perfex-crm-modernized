<?php init_head(); ?><div id="wrapper" class="customer_profile"><br><br>   <div class="content">      <div class="row">         <div class="col-md-12">            <?php if(isset($client) && $client->registration_confirmed == 0 && is_admin()){ ?>               <div class="alert alert-warning">                  <?php echo htmlspecialchars(_l('customer_requires_registration_confirmation')); ?>                  <br />                  <a href="<?php echo admin_url('clients/confirm_registration/'.$client->userid); ?>"><?php echo htmlspecialchars(_l('confirm_registration')); ?></a>               </div>            <?php } else if(isset($client) && $client->active == 0 && $client->registration_confirmed == 1){ ?>            <div class="alert alert-warning">               <?php echo htmlspecialchars(_l('customer_inactive_message')); ?>               <br />               <a href="<?php echo admin_url('clients/mark_as_active/'.$client->userid); ?>"><?php echo htmlspecialchars(_l('mark_as_active')); ?></a>            </div>            <?php } ?>            <?php if(isset($client) && $client->leadid != NULL){ ?>            <div class="alert alert-info">               <a href="<?php echo admin_url('leads/index/'.$client->leadid); ?>" onclick="init_lead(<?php echo htmlspecialchars($client->leadid); ?>); return false;"><?php echo htmlspecialchars(_l('customer_from_lead',_l('lead'))); ?></a>            </div>            <?php } ?>            <?php if(isset($client) && (!has_permission('customers','','view') && is_customer_admin($client->userid))){?>            <div class="alert alert-info">               <?php echo htmlspecialchars(_l('customer_admin_login_as_client_message',get_staff_full_name(get_staff_user_id()))); ?>            </div>            <?php } ?>
            <?php if (!empty($pending_update_requests) && isset($pending_update_requests)) { ?>
            <div class="alert alert-warning">
               <i class="fa fa-bell"></i> <strong><?php echo _l('ap_client_requested_update'); ?></strong>
               <?php echo sprintf(_l('ap_pending_update_requests_count'), count($pending_update_requests)); ?>
               <a href="<?php echo admin_url('accountplanning/mark_update_request_handled/' . $account->id); ?>" class="alert-link"><?php echo _l('ap_mark_handled'); ?></a>
            </div>
            <?php } ?>
         </div>         <div class="col-md-3">            <div class="panel_s mbot5">               <div class="panel-body padding-10">                  <h4 class="bold">                     <?php if(has_permission('accountplanning','','view') || has_permission('accountplanning','','delete') || is_admin()){ ?>                     <div class="btn-group pull-left mright10">                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">                        <span class="caret"></span>                        </a>                        <ul class="dropdown-menu dropdown-menu-left">                           <?php if(has_permission('accountplanning','','view')){ ?>                           <li>                              <a href="<?php echo admin_url('accountplanning/export_pdf/' . $account->id); ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> <?php echo htmlspecialchars(_l('ap_export_pdf')); ?></a>                           </li>                           <?php } ?>                           <?php if(has_permission('accountplanning','','create')){ ?>                           <li>                              <a href="<?php echo admin_url('accountplanning/create_next_period/' . $account->id); ?>" onclick="return ap_confirm_create_next();"><i class="fa fa-copy"></i> <?php echo htmlspecialchars(_l('ap_create_next_period')); ?></a>                           </li>                           <?php } ?>                           <?php if(has_permission('accountplanning','','view')){ ?>                           <li>                              <a href="<?php echo admin_url('accountplanning/compare/' . $account->id); ?>"><i class="fa fa-columns"></i> <?php echo htmlspecialchars(_l('ap_compare_plans')); ?></a>                           </li>                           <?php } ?>                           <?php if(has_permission('accountplanning','','delete')){ ?>                           <li>                              <a href="<?php echo admin_url('accountplanning/delete/' . $account->id); ?>" class="text-danger delete-text _delete"><i class="fa fa-remove"></i> <?php echo htmlspecialchars(_l('delete')); ?></a>                           </li>                           <?php } ?>                        </ul>                     </div>                     <?php } ?>                     #<?php echo htmlspecialchars($account->id.' - '.$account->client_name); ?>                  </h4>               </div>            </div>            <?php $this->load->view('accountplanning/tabs'); ?>         </div>         <div class="col-md-9">            <div class="panel_s">               <div class="panel-body">                  <div>                     <div class="tab-content">                        <?php $this->load->view('accountplanning/groups/'.$group); ?>                     </div>                  </div>               </div>            </div>         </div>      </div>   </div></div><?php init_tail(); ?><script>$('select[name="client_id"]').on('change', function() {     var val = $(this).val();     requestGetJSON('accountplanning/client_change_data/' + val).done(function(response) {      $('.billing_street').text(response['billing_shipping'][0]['billing_street']);      $('.billing_city').text(response['billing_shipping'][0]['billing_city']);      $('.billing_state').text(response['billing_shipping'][0]['billing_state']);      $('.billing_country').text(response['billing_shipping'][0]['billing_country']);      $('.billing_zip').text(response['billing_shipping'][0]['billing_zip']);     }); });$('.due-diligence-form-submiter').on('click', function() {   $('input[name="financial"]').val(hot.getData());   $('input[name="marketing_activities"]').val(hot_2.getData());});$('.service-ability-offering-form-submiter').on('click', function() {   $('input[name="service_ability_offering"]').val(service_ability_offering.getData());   $('input[name="current_service"]').val(current_service.getData());});$('.planning-form-submiter').on('click', function() {
    if (typeof tinymce !== 'undefined' && tinymce.triggerSave) {
        tinymce.triggerSave();
    }
    $('input[name="todo_list"]').val(hot.getData());
});$('#radioBtn a').on('click', function(){    var sel = $(this).data('title');    var tog = $(this).data('toggle');    $('input[name="'+tog+'"]').prop('value', sel);        $('a[data-toggle="'+tog+'"]').not('[data-title="'+sel+'"]').removeClass('active').addClass('notActive');    $('a[data-toggle="'+tog+'"][data-title="'+sel+'"]').removeClass('notActive').addClass('active');})</script>  <script id="code">
var _apTinymceBase = <?php echo json_encode(base_url('modules/accountplanning/assets/plugins/tinymce')); ?>;

function new_objective() {
    _validate_form($('form'), { objective_name: 'required' });
    $('#objective_hidden').find('input').remove();
    $('#new_objective .add-title').removeClass('hide');
    $('#new_objective .edit-title').addClass('hide');
    $('#new_objective').modal('show');
    $('#new_objective input[name="objective_name"]').val('');
}

function new_items() {
    _validate_form($('form'), { items_name: 'required', objective: 'required' });
    $('#item_hidden').find('input').remove();
    $('#new_items .add-title').removeClass('hide');
    $('#new_items .edit-title').addClass('hide');
    $('#new_items').modal('show');
    $('#new_items input[name="items_name"]').val('');
}

function edit_objective(invoker, id) {
    _validate_form($('form'), { objective_name: 'required' });
    var name = $(invoker).data('name');
    $('#objective_hidden').append(hidden_input('id', id));
    $('#new_objective input[name="objective_name"]').val(name);
    $('#objective_hidden').append(hidden_input('id', id));
    $('#new_objective .edit-title').removeClass('hide');
    $('#new_objective .add-title').addClass('hide');
    $('#new_objective').modal('show');
}

function edit_items(invoker, id) {
    _validate_form($('form'), { items_name: 'required', objective: 'required' });
    var name = $(invoker).data('name');
    var objective = $(invoker).data('objective');
    $('#item_hidden').append(hidden_input('id', id));
    $('#new_items input[name="items_name"]').val(name);
    $('#new_items select[name="objective"]').val(objective).change();
    $('#new_items .edit-title').removeClass('hide');
    $('#new_items .add-title').addClass('hide');
    $('#new_items').modal('show');
}

function edit_task(invoker, id) {
    _validate_form($('form'), { items_id: 'required', task_name: 'required' });
    var name = $(invoker).data('name');
    var item = $(invoker).data('item');
    var prioritization = $(invoker).data('prioritization');
    var pic = $(invoker).data('pic');
    var deadline = $(invoker).data('deadline');
    var status = $(invoker).data('status');
    $('#task_hidden').append(hidden_input('id', id));
    $('#new_task input[name="task_name"]').val(name);
    $('#new_task input[name="pic"]').val(pic);
    $('#new_task input[name="deadline"]').val(deadline);
    $('#new_task select[name="status"]').val(status).change();
    $('#new_task select[name="prioritization"]').val(prioritization).change();
    $('#new_task select[name="items_id"]').val(item).change();
    $('#new_task .edit-title').removeClass('hide');
    $('#new_task .add-title').addClass('hide');
    $('#new_task').modal('show');
}

function edit_user(invoker, id) {
    _validate_form($('form'), { user: 'required', name: 'required', password: 'unrequired' });
    $('label[for="password"] small').remove();
    var user = $(invoker).data('user');
    var name = $(invoker).data('name');
    $('#additional').append(hidden_input('id', id));
    $('#user_api input[name="user"]').val(user);
    $('#user_api input[name="name"]').val(name);
    $('input[name="password"]').val('');
    $('#password_note').removeClass('hide');
    $('#user_api').modal('show');
    $('.add-title').addClass('hide');
}

function ap_confirm_create_next() {
    return confirm('<?php echo _l('ap_recurring_plan_help'); ?>');
}
$('select[name="client_status"]').on('change', function() {
    if (this.value == 'Green') {
        $('#client_status_color').css('background', '#84C529');
    }
    if (this.value == 'Red') {
        $('#client_status_color').css('background', '#fc2d42');
    }
    if (this.value == 'Yellow') {
        $('#client_status_color').css('background', '#FF0');
    }
});

function convert_to_task(invoker, id, task_id) {
    var new_task_url = admin_url + 'tasks/task?rel_id=' + id + '&rel_type=accountplanning&account_task_id=' + task_id + '&accountplanning_to_task=true';
    new_task(new_task_url, invoker);
    var subject = $(invoker).data('subject');
    var description = $(invoker).data('description');
    var priority = $(invoker).data('priority');
    var deadline = $(invoker).data('deadline');
    var pic = $(invoker).data('pic') + '|';
    if (priority == 'Low') {
        priority = 1;
    } else if (priority == 'Medium') {
        priority = 2;
    } else if (priority == 'High') {
        priority = 3;
    }
    $('body').on('shown.bs.modal', '#_task_modal', function() {
        if (!is_mobile()) {
            $(this).find('#description').click();
        } else {
            $(this).find('#description').focus();
        }
        setTimeout(function() {
            $.each(pic.split("|"), function(i, e) {
                $("#add_task_assignees option[value='" + e + "']").prop("selected", true);
            });
            $('#_task_modal select[id="add_task_assignees"]').change();
            $('#_task_modal input[name="name"]').val(subject);
            $('#_task_modal input[name="duedate"]').val(deadline);
            tinymce.get("description").setContent(description);
            $('#_task_modal select[name="priority"]').val(priority).change();
        }, 100);
    });
}

$('a[name="preview-inv-btn"]').on('click', function() {
    var id = $(this).attr('id');
    var rel_id = $(this).attr('rel_id');
    view_inv_file(id, rel_id);
});

function view_inv_file(id, rel_id) {
    $('#inv_file_data').empty();
    $("#inv_file_data").load(admin_url + 'accountplanning/file/' + id + '/' + rel_id, function(response, status, xhr) {
        if (status == "error") {
            alert_float('danger', xhr.statusText);
        }
    });
}

function delete_invoice_attachment(id) {
    if (confirm_delete()) {
        requestGet('accountplanning/delete_attachment/' + id).done(function(success) {
            if (success == 1) {
                $("body").find('[data-attachment-id="' + id + '"]').remove();
                init_invoice($("body").find('input[name="_attachment_sale_id"]').val());
            }
        }).fail(function(error) {
            alert_float('danger', error.responseText);
        });
    }
}

function init_ap_editors() {
    if (typeof tinymce === 'undefined') { return false; }
    var regularEditors = $('.ap-tinymce');
    if (regularEditors.length === 0) { return false; }
    var tinymceBaseURL = (typeof _apTinymceBase !== 'undefined' && _apTinymceBase) ? _apTinymceBase : (window.location.origin + '/modules/accountplanning/assets/plugins/tinymce');
    if (tinymce.baseURL !== tinymceBaseURL) {
        tinymce.baseURL = tinymceBaseURL;
        tinymce.EditorManager.baseURL = tinymceBaseURL;
    }
    regularEditors.each(function() {
        var id = $(this).attr('id');
        if (id && !tinymce.get(id)) {
            tinymce.init({
                selector: '#' + id,
                branding: false,
                height: 400,
                theme: 'modern',
                skin: 'lightgray',
                plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat'
            });
        }
    });
    return true;
}

function ap_export_mindmap() {
    var ed = typeof tinymce !== 'undefined' ? tinymce.get('data_tree') : null;
    if (!ed) { alert_float('warning', 'MindMap editor not ready.'); return; }
    var content = ed.getContent();
    if (!content || content.trim() === '') { alert_float('warning', 'MindMap is empty.'); return; }
    var iframe = ed.getBody();
    if (typeof html2canvas !== 'undefined') {
        html2canvas(iframe, { useCORS: true, allowTaint: true, scale: 2 }).then(function(canvas) {
            var link = document.createElement('a');
            link.download = 'mindmap-' + Date.now() + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            alert_float('success', 'MindMap exported.');
        }).catch(function() {
            ap_export_mindmap_fallback(content);
        });
    } else {
        ap_export_mindmap_fallback(content);
    }
}
function ap_export_mindmap_fallback(html) {
    var w = window.open('', '_blank');
    w.document.write('<html><head><style>body{font-family:Arial;padding:20px;}</style></head><body>' + html + '</body></html>');
    w.document.close();
    w.focus();
    setTimeout(function() { w.print(); }, 500);
}
function ap_export_mindmap_svg() {
    var ed = typeof tinymce !== 'undefined' ? tinymce.get('data_tree') : null;
    if (!ed) { alert_float('warning', 'MindMap editor not ready.'); return; }
    var content = ed.getContent();
    if (!content || content.trim() === '') { alert_float('warning', 'MindMap is empty.'); return; }
    var w = 800, h = 600;
    try {
        var body = ed.getBody();
        if (body && body.scrollWidth) w = Math.max(w, body.scrollWidth + 40);
        if (body && body.scrollHeight) h = Math.max(h, body.scrollHeight + 40);
    } catch (e) {}
    content = content.replace(/<\/foreignObject>/gi, '').replace(/<\/svg>/gi, '');
    var svg = '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" xmlns:xhtml="http://www.w3.org/1999/xhtml" width="' + w + '" height="' + h + '"><foreignObject width="100%" height="100%"><div xmlns="http://www.w3.org/1999/xhtml" style="font-family:Arial,sans-serif;font-size:12pt;padding:20px;">' + content + '</div></foreignObject></svg>';
    var blob = new Blob([svg], { type: 'image/svg+xml;charset=utf-8' });
    var link = document.createElement('a');
    link.download = 'mindmap-' + Date.now() + '.svg';
    link.href = (window.URL || window.webkitURL).createObjectURL(blob);
    link.click();
    if (link.href) (window.URL || window.webkitURL).revokeObjectURL(link.href);
    alert_float('success', 'MindMap exported as SVG.');
}

function init_editor_mindmap(selector, settings) {
    selector = typeof(selector) == 'undefined' ? '#data_tree' : selector;
    var _editor_selector_check = $(selector);
    if (_editor_selector_check.length === 0) { return false; }
    $.each(_editor_selector_check, function() {
        if ($(this).hasClass('tinymce-manual')) {
            $(this).removeClass('tinymce');
        }
    });
    var tinymceBaseURL = (typeof _apTinymceBase !== 'undefined' && _apTinymceBase) ? _apTinymceBase : (window.location.origin + '/modules/accountplanning/assets/plugins/tinymce');
    if (typeof tinymce !== 'undefined') {
        tinymce.baseURL = tinymceBaseURL;
        tinymce.EditorManager.baseURL = tinymceBaseURL;
    }
    var _settings = {
        branding: false,
        selector: selector,
        browser_spellcheck: true,
        height: 400,
        theme: 'modern',
        skin: 'lightgray',
        relative_urls: false,
        inline_styles: true,
        verify_html: false,
        cleanup: false,
        autoresize_bottom_margin: 25,
        valid_elements: '+*[*]',
        valid_children: "+body[style], +style[type]",
        apply_source_formatting: false,
        remove_script_host: false,
        removed_menuitems: 'newdocument restoredraft',
        forced_root_block: 'p',
        autosave_restore_when_empty: false,
        fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
        setup: function(ed) {
            ed.on('init', function() {
                this.getDoc().body.style.fontSize = '12pt';
            });
        },
        table_default_styles: {
            width: '100%'
        },
        plugins: [
            'advlist',
            'autoresize',
            'autosave',
            'lists',
            'link',
            'image',
            'print',
            'hr',
            'codesample',
            'visualblocks',
            'code',
            'fullscreen',
            'media',
            'save',
            'table',
            'paste',
            'leaui_mindmap'
        ],
        toolbar1: 'leaui_mindmap | fontselect fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | image link | bullist numlist | restoredraft',
        file_browser_callback: elFinderBrowser
    };
    if (isRTL == 'true') {
        _settings.directionality = 'rtl';
        _settings.plugins.push('directionality');
    }
    if (typeof app.tinymce_lang !== 'undefined' && app.tinymce_lang !== '' && app.tinymce_lang !== null) {
        _settings.language = app.tinymce_lang;
    }
    if (typeof(settings) != 'undefined') {
        for (var key in settings) {
            if (key != 'append_plugins') {
                _settings[key] = settings[key];
            } else {
                _settings['plugins'].push(settings[key]);
            }
        }
    }
    if (typeof tinymce === 'undefined') {
        console.error('TinyMCE is not loaded. Please ensure tinymce.min.js is included before this script.');
        return null;
    }
    try {
        var editor = tinymce.init(_settings);
        $(document).trigger('app.editor.initialized');
        return editor;
    } catch (error) {
        console.error('TinyMCE Mindmap: Failed to initialize:', error);
        return null;
    }
}

$(document).ready(function() {
    if (typeof tinymce !== 'undefined' && typeof tinymce.init === 'function') {
        setTimeout(function() {
            init_ap_editors();
            var r = init_editor_mindmap();
            if (r === false) {
                setTimeout(function() { init_editor_mindmap(); }, 800);
                setTimeout(function() { init_editor_mindmap(); }, 1600);
            }
        }, 500);
    } else {
        var checkTinyMCE = setInterval(function() {
            if (typeof tinymce !== 'undefined' && typeof tinymce.init === 'function') {
                clearInterval(checkTinyMCE);
                setTimeout(function() {
                    init_ap_editors();
                    var r = init_editor_mindmap();
                    if (r === false) {
                        setTimeout(function() { init_editor_mindmap(); }, 800);
                        setTimeout(function() { init_editor_mindmap(); }, 1600);
                    }
                }, 500);
            }
        }, 100);
        setTimeout(function() { clearInterval(checkTinyMCE); }, 15000);
    }
});
</script>
</body>
</html>