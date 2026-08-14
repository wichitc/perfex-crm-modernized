var signaturePad;
document.addEventListener("DOMContentLoaded", function() {
  "use strict";

  jQuery('input[name="sign_type"]').on('click', function(){
    if(jQuery('#sign').is(':checked')){
      jQuery('#sign_pad').removeClass('hide');
      jQuery('#upload_sign').addClass('hide');
    }else if(jQuery('#upload').is(':checked')){
      jQuery('#sign_pad').addClass('hide');
      jQuery('#upload_sign').removeClass('hide');
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

  var canvas = document.getElementById("signature");
  if (canvas) {
    signaturePad = new SignaturePad(canvas, {
      maxWidth: 2,
      onEnd: function() {
        signaturePadChanged();
      }
    });
  }
});

function signaturePadChanged() {
  var input = document.getElementById('signatureInput');
  var $signatureLabel = jQuery('#signatureLabel');
  $signatureLabel.removeClass('text-danger');

  if (signaturePad.isEmpty()) {
    $signatureLabel.addClass('text-danger');
    input.value = '';
    return false;
  }

  jQuery('#signatureInput-error').remove();
  var partBase64 = signaturePad.toDataURLAndRemoveBlanks();
  partBase64 = partBase64.split(',')[1];
  input.value = partBase64;
}

function signature_clear() {
  "use strict"; 
  var canvas = document.getElementById("signature");
  signaturePad = new SignaturePad(canvas, {
    maxWidth: 2,
    onEnd: function() {
      signaturePadChanged();
    }
  });
  signaturePad.clear();
  var input = document.getElementById('signatureInput');
  if (input) input.value = '';
}

function sign_request_imprest(id) {
  "use strict"; 
  if(jQuery('#upload').is(':checked')){
    if( document.getElementById("sign_attachment_file").files.length == 0 ){
       alert_float('success', 'Please select sign image');
       setTimeout(() => {
          var btn = jQuery('#sign_button');
          btn.removeAttr('disabled');
          btn.removeClass('disabled');
          btn.html(btn.data('original-text') || 'Sign');
        },"1000");
    } else {
      change_imprest_approval_status(id, 2, true);
      jQuery('#sign_attachment-form').submit();
    }
  } else if(jQuery('#sign').is(':checked')) {
    change_imprest_approval_status(id, 2, true);
  }
}

function approve_request_imprest(id) {
  "use strict"; 
  change_imprest_approval_status(id, 2);
  if( document.getElementById("sign_attachment_file") && document.getElementById("sign_attachment_file").files.length > 0 ){
    jQuery('#sign_attachment-form').submit();
  }
}

function deny_request_imprest(id) {
  "use strict"; 
  change_imprest_approval_status(id, 3);
  if( document.getElementById("sign_attachment_file") && document.getElementById("sign_attachment_file").files.length > 0 ){
    jQuery('#sign_attachment-form').submit();
  }
}

function change_imprest_approval_status(id, status, sign_code = false) {
  var data = {};
  data.rel_id = id;
  data.rel_type = 'imprest';
  data.approve = status;
  if(sign_code == true){
    data.signature = jQuery('input[name="signature"]').val();
    if(jQuery('#sign').is(':checked')){
      data.sign_type = 'sign';
    }else if(jQuery('#upload').is(':checked')){
      data.sign_type = 'upload';
    }
  }else{
    data.note = jQuery('textarea[name="reason"]').val();
  }
  jQuery.post(admin_url + 'accounting/approve_request/' + id, data).done(function(response){
     response = JSON.parse(response); 
      if (response.success === true || response.success == 'true') {
          alert_float('success', response.message);
          if(sign_code != true || (sign_code == true && jQuery('#sign').is(':checked'))){
            window.location.reload();
          }
      }
  });
}

function accept_action() {
  "use strict"; 
  jQuery('#add_action').modal('show');
}
