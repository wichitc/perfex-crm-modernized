<script>
(function($) {
  "use strict"; 

  var data_send_mail = {};
  <?php if(isset($send_mail_approve)){ 
    ?>
    data_send_mail = <?php echo json_encode($send_mail_approve); ?>;
    data_send_mail.rel_id = <?php echo pur_html_entity_decode($faf->id); ?>;
    data_send_mail.rel_type = 'faf_requests';
    data_send_mail.addedfrom = <?php echo pur_html_entity_decode($faf->created_by); ?>;
    $.post(admin_url+'purchase/send_mail', data_send_mail).done(function(response){
    });
  <?php } ?>

   $('input[name="sign_type"]').on('click', function(){

    if($('#sign').is(':checked')){
      $('#sign_pad').removeClass('hide');
      $('#upload_sign').addClass('hide');
    }else if($('#upload').is(':checked')){
      $('#sign_pad').addClass('hide');
      $('#upload_sign').removeClass('hide');
    }

  });

   $('input[name="requestor_sign_type"]').on('click', function(){

    if($('#requestor_sign').is(':checked')){
      $('#requestor_sign_pad').removeClass('hide');
      $('#requestor_upload_sign').addClass('hide');
    }else if($('#requestor_upload').is(':checked')){
      $('#requestor_sign_pad').addClass('hide');
      $('#requestor_upload_sign').removeClass('hide');
    }

  });

  SignaturePad.prototype.toDataURLAndRemoveBlanks = function() {
     var canvas = this._ctx.canvas;
       // First duplicate the canvas to not alter the original
       var croppedCanvas = document.createElement('canvas'),
       croppedCtx = croppedCanvas.getContext('2d');

       croppedCanvas.width = canvas.width;
       croppedCanvas.height = canvas.height;
       croppedCtx.drawImage(canvas, 0, 0);

       // Next do the actual cropping
       var w = croppedCanvas.width,
       h = croppedCanvas.height,
       pix = {
         x: [],
         y: []
       },
       imageData = croppedCtx.getImageData(0, 0, croppedCanvas.width, croppedCanvas.height),
       x, y, index;

       for (y = 0; y < h; y++) {
         for (x = 0; x < w; x++) {
           index = (y * w + x) * 4;
           if (imageData.data[index + 3] > 0) {
             pix.x.push(x);
             pix.y.push(y);

           }
         }
       }
       pix.x.sort(function(a, b) {
         return a - b
       });
       pix.y.sort(function(a, b) {
         return a - b
       });
       var n = pix.x.length - 1;

       w = pix.x[n] - pix.x[0];
       h = pix.y[n] - pix.y[0];
       var cut = croppedCtx.getImageData(pix.x[0], pix.y[0], w, h);

       croppedCanvas.width = w;
       croppedCanvas.height = h;
       croppedCtx.putImageData(cut, 0, 0);

       return croppedCanvas.toDataURL();
     };


 function requestor_signaturePadChanged() {

   var input = document.getElementById('requestor_signatureInput');
   var $signatureLabel = $('#requestor_signatureLabel');
   $signatureLabel.removeClass('text-danger');

   if (requestor_canvas_signaturePad.isEmpty()) {
     $signatureLabel.addClass('text-danger');
     input.value = '';
     return false;
   }

   $('#requestor_signatureInput-error').remove();
   var partBase64 = requestor_canvas_signaturePad.toDataURLAndRemoveBlanks();
   partBase64 = partBase64.split(',')[1];
   input.value = partBase64;
 }


  function signaturePadChanged() {

   var input = document.getElementById('signatureInput');
   var $signatureLabel = $('#signatureLabel');
   $signatureLabel.removeClass('text-danger');

   if (signaturePad.isEmpty()) {
     $signatureLabel.addClass('text-danger');
     input.value = '';
     return false;
   }

   $('#signatureInput-error').remove();
   var partBase64 = signaturePad.toDataURLAndRemoveBlanks();
   partBase64 = partBase64.split(',')[1];
   input.value = partBase64;
 }

 var requestor_canvas = document.getElementById("requestor_signature");
 var requestor_canvas_signaturePad = new SignaturePad(requestor_canvas, {
  maxWidth: 2,
  onEnd:function(){
    requestor_signaturePadChanged();
  }
  
});

var canvas = document.getElementById("signature");
 var signaturePad = new SignaturePad(canvas, {
  maxWidth: 2,
  onEnd:function(){
    signaturePadChanged();
  }
 });


$('#identityConfirmationForm').submit(function() {
   requestor_signaturePadChanged();
   signaturePadChanged();
 });  


})(jQuery);  

//preview faf_request attachment
function preview_faf_request_btn(invoker){
  "use strict"; 
    var id = $(invoker).attr('id');
    var rel_id = $(invoker).attr('rel_id');
    view_faf_request_file(id, rel_id);
}

function view_faf_request_file(id, rel_id) {
  "use strict"; 
      $('#faf_request_file_data').empty();
      $("#faf_request_file_data").load(admin_url + 'purchase/file_faf_request/' + id + '/' + rel_id, function(response, status, xhr) {
          if (status == "error") {
              alert_float('danger', xhr.statusText);
          }
      });
}
function close_modal_preview(){
  "use strict"; 
 $('._project_file').modal('hide');
}

function delete_faf_request_attachment(id) {
  "use strict"; 
    if (confirm_delete()) {
        requestGet('purchase/delete_faf_request_attachment/' + id).done(function(success) {
            
                $("#faf_request_pv_file").find('[data-attachment-id="' + id + '"]').remove();
            
        }).fail(function(error) {
            alert_float('danger', error.responseText);
        });
    }
}


function get_sales_notes_faf(id, controller) {
  "use strict";
    requestGet(controller + '/get_sales_notes_faf/' + id).done(function(response) {
        $('#sales_notes_area').html(response);
        var totalNotesNow = $('#sales-notes-wrapper').attr('data-total');
        if (totalNotesNow > 0) {
            $('.notes-total').html('<span class="badge">' + totalNotesNow + '</span>').removeClass('hide');
        }
    });
}


function change_status_faf(invoker,id){
  "use strict"; 
   $.post(admin_url+'purchase/change_status_pur_faf/'+invoker.value+'/'+id).done(function(reponse){
    reponse = JSON.parse(reponse);
    window.location.href = admin_url + 'purchase/faf_detail/'+id;
    alert_float('success',reponse.result);
  });
}	


function requestor_sign_faf() {
  "use strict";
  $('#requestor_sign_faf').modal('show');
}



function requestor_sign_request(id){
  "use strict";
    

    if($('#requestor_upload').is(':checked')){
      if( document.getElementById("requestor_sign_attachment_file").files.length == 0 ){
         alert_float('success', '<?php echo _l('please_select_sign_image'); ?>');

         setTimeout(() => {
            $('#requestor_sign_button').removeAttr('disabled');
            $('#requestor_sign_button').removeClass('disabled');
            $('#requestor_sign_button').html('<?php echo _l('e_signature_sign') ?>');
          },"1000");

      }else{


        $('#requestor_sign_attachment-form').submit();

      }
    }else if($('#requestor_sign').is(':checked')){

      requestor_upload_sign(id, true);
    }
}

function requestor_signature_clear(){
	"use strict";
	var canvas = document.getElementById("requestor_signature");
	var signaturePad = new SignaturePad(canvas, {
	  maxWidth: 2,
	  onEnd:function(){

	  }
	});
	signaturePad.clear();
}


function requestor_upload_sign(id, sign_code){
  "use strict";
    var data = {};
    data.id = id;

    if(sign_code == true){
      data.signature = $('input[name="requestor_signature"]').val();

    }
    $.post(admin_url + 'purchase/requestor_upload_sign/' + id, data).done(function(response){
        response = JSON.parse(response); 
        if (response.success === true || response.success == 'true') {
            window.location.reload();
        }
    });
}

function send_request_approve(id){
  "use strict";
    var data = {};
    data.rel_id = <?php echo pur_html_entity_decode($faf->id); ?>;
    data.rel_type = 'faf_requests';
    data.addedfrom = <?php echo pur_html_entity_decode($faf->created_by); ?>;
  $("body").append('<div class="dt-loader"></div>');
    $.post(admin_url + 'purchase/send_request_approve', data).done(function(response){
        response = JSON.parse(response);
        $("body").find('.dt-loader').remove();
        if (response.success === true || response.success == 'true') {
            alert_float('success', response.message);
            window.location.reload();
        }else{
          alert_float('warning', response.message);
            window.location.reload();
        }
    });
}


function sign_request(id){
  "use strict";
    

    if($('#upload').is(':checked')){
      if( document.getElementById("sign_attachment_file").files.length == 0 ){
         alert_float('success', '<?php echo _l('please_select_sign_image'); ?>');

         setTimeout(() => {
            $('#sign_button').removeAttr('disabled');
            $('#sign_button').removeClass('disabled');
            $('#sign_button').html('<?php echo _l('e_signature_sign') ?>');
          },"1000");

      }else{
        
        change_request_approval_status(id,2, true);

        $('#sign_attachment-form').submit();

      }
    }else if($('#sign').is(':checked')){

      change_request_approval_status(id,2, true);
    }
}
function approve_request(id){
  "use strict";
  change_request_approval_status(id,2);

  if( document.getElementById("sign_attachment_file").files.length > 0 ){
    $('#sign_attachment-form').submit();
  }
}
function deny_request(id){
  "use strict";
    change_request_approval_status(id,3);

    if( document.getElementById("sign_attachment_file").files.length > 0 ){
      $('#sign_attachment-form').submit();
    }
}
function change_request_approval_status(id, status, sign_code){
  "use strict";
    var data = {};
    data.rel_id = id;
    data.rel_type = 'faf_requests';
    data.approve = status;
    if(sign_code == true){
      data.signature = $('input[name="signature"]').val();

      if($('#sign').is(':checked')){
        data.sign_type = 'sign';
      }else if($('#upload').is(':checked')){
        data.sign_type = 'upload';
      }

    }else{
      data.note = $('textarea[name="reason"]').val();
    }
    $.post(admin_url + 'purchase/approve_request/' + id, data).done(function(response){
        response = JSON.parse(response); 
        if (response.success === true || response.success == 'true') {
            alert_float('success', response.message);

            if(sign_code != true || (sign_code == true && $('#sign').is(':checked'))){
              window.location.reload();
            }

        }
    });
}
function accept_action() {
  "use strict";
  $('#add_action').modal('show');
}


function signature_clear(){
"use strict";
var canvas = document.getElementById("signature");
var signaturePad = new SignaturePad(canvas, {
  maxWidth: 2,
  onEnd:function(){

  }
});
signaturePad.clear();

}
</script>