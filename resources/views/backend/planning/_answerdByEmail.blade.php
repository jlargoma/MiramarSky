<?php if (!$mobile->isMobile()): ?> 
  <link href="/assets/plugins/summernote/css/summernote.css" rel="stylesheet" type="text/css" media="screen">
<?php endif; ?>
<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
<style>
  .note-editor, .note-editable{
    min-height: 500px!important;
  }
  .dropdown-toggle > i{
    font-size: 10px!important;
  }
  div#contentEmailing {
      overflow: auto !important;
      max-height: 88vh !important;
  }
</style>
<div class="modal-content">
  <div class="modal-header clearfix text-left">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important;color: black!important"></i>
    </button>
    <h5>Mensaje para <span class="semi-bold"><?php echo $book->customer->name ?></span></h5>
  </div>
  <div class="modal-body">
    <div class="loading" style="display: none;  position: absolute;top: 0;width: 100%;background-color: rgba(255,255,255,0.6);z-index: 15;min-height: 600px;left: 0;padding: 210px 0;">
      <div class="col-xs-12 text-center sending" style="display: none;">
        <i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
        <h2 class="text-center">ENVIANDO</h2>
      </div>

      <div class="col-xs-12 text-center sended" style="display: none;">
        <i class="fa fa-check-circle-o text-black" aria-hidden="true"></i><br>
        <h2 class="text-center">ENVIADO</h2>
      </div>
    </div>
    <form  action="{{ url('/admin/reservas/sendEmail') }}" method="post" id="formSendEmail">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <input type="hidden" class="id" name="id" value="<?php echo $book->id; ?>">

      <div class="summernote-wrapper" style="margin-bottom: 30px;">
          <textarea class="form-control" id="textEmail" style="width: 100%;">
            <?php echo $text_mail ?> 
          </textarea>
      </div>

      <div class="wrapper push-20" style="text-align: center;">
        <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Contestar</button>
        <button type="button" class="btn btn-lg btn-info btnCopyCKEDITOR" data-instance="textEmail"><i class="fa fa-copy" aria-hidden="true"></i> Copiar</button>
      </div>
      <textarea id="copyCKEditorCode" style="height: 1px;width: 1px;border: none;"></textarea>
    </form>
  </div>
</div>


<script type="text/javascript">

function sending() {
  $('.loading').show();
  $('.loading .sending').show();
}

function sended() {
  $('.loading').hide();
//  $('.loading .sendend').show();
}


  var objEditor = CKEDITOR.replace('textEmail',
          {
            toolbar:
                    [
            { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
            { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
            { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
                '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
            { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
            '/',
            { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
            { name: 'colors', items : [ 'TextColor','BGColor' ] },
            { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
                    ]
          });
          
$('#formSendEmail').submit(function (event) {
  event.preventDefault();

  sending();

  var formURL = $(this).attr("action");
  var token = $('input[name="_token"]').val();
  var id = $('.id').val();
  var textEmail = $('#textEmail').val();
  var type = 1;
  $.post(formURL, {_token: token, textEmail: textEmail, id: id, type: type}, function (data) {
    if (data == 'OK') {
      var type = $('.table-data').attr('data-type');
      var year = $('#fecha').val();
      $.get('/admin/reservas/api/getTableData', {type: type, year: year}, function (data) {
        $('.content-tables').empty().append(data);
      });
      $('.close').trigger('click');
    } else {
      alert('Error al guardar estado: '+data);
    }
    
    sended();

  });
});
</script>