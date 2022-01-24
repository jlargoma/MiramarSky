<?php
$temporada = $oYear->year . ' - ' . ($oYear->year + 1);
?>

<div class="block">
  <div class="block-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important;color: black!important"></i>
    </button>
    
    <h2 class="text-center">
      @if($sign)
      Contrato <a href="{{route('contratoDelegacion.see',[$contract->id])}}" title="Ver contrato"  target="_black" ><i class="fa fa-eye"></i></a>
      <span class="sing_true">Documento firmado</span>
      @else
      Contrato 
      <a href="{{route('contratoDelegacion.see',[$contract->id])}}" title="Ver contrato"  target="_black" ><i class="fa fa-eye"></i></a> 
      <i class="fa fa-paper-plane  sendContrato" data-send="0" title="Enviar Contrato por mail"></i>
      <span class="sing_false">Documento no firmado</span>
      @endif
    </h2>
    <h2 class="text-center">CONTRATATO PARA DELEGACION DE REPRESENTACION</h2>
  </div>
  <div class="px-1em">
    <form method="post" action="{{route('contractsDelegacion.save')}}">
      <input type="hidden" name="id" id="idContr" value="{{$contract->id}}">
      <input type="hidden" name="_token" id="tokenContr" value="<?php echo csrf_token(); ?>">
      <div class="row">
      @if($sign)
        <input type="hidden" name="alreadySing" value="1">
        <div class="col-md-12">
          <div class="box-signed">
            <h5>Documento ya firmado</h5>
            <a href="{{route('contratoDelegacion.see',[$contract->id])}}" title="Ver contrato" target="_black" ><i class="fa fa-eye"></i> Ver Contrato</a>
            <div class="delSing">
            <input type="checkbox" name="delSign">Eliminar documento
            </div>
          </div>
        </div>
      @else
        <div class="col-md-9">
          <textarea class="form-control" name="contract_main_content" id="contract_main_content"><?php echo $contract->content; ?></textarea>
        </div>
        <div class="col-md-3">
          <h5 class="showVars">VARIABLES<i class="fa fa-arrow-down "></i></h5>
          <u style="display: none;"><!-- comment -->
            <li>{usuario_nombre}</li>
            <li>{usuario_dni}</li>
            <li>{usuario_representacion} (en nombre y representación de..)</li>
            <li>{room_name}</li>
            <li>{room_plaza_garaje}</li>
            <li>{room_plaza_garaje}</li>
            <li>{cede_voto_nombre}</li>
            <li>{cede_voto_dni}</li>
            <li>{break} (sallto de linea)</li>
          </u>
        </div>
        @endif
        <div class="col-md-12 text-center">
          <button type="submit" class="btn btn-success">Guardar</button>
          <br/><br/>
        </div>
      </div>
    </form>
  </div>
</div>


<script type="text/javascript">

  $(document).ready(function () {

      CKEDITOR.replace('contract_main_content',
        {
            toolbar:
              [
                  {name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']},
                  {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']},
                  {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv',
                          '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
                  {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                  '/',
                  {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']},
                  {name: 'colors', items: ['TextColor', 'BGColor']},
                  {name: 'tools', items: ['Maximize', 'ShowBlocks', '-', 'About']}
              ]
        });
        
    $('.showVars').on('click',function(){
      var that = $(this);
      var icon = that.find('i');
      var lst = that.closest('div').find('u');
      if (icon.hasClass('fa-arrow-down')){
        lst.show();
        icon.removeClass('fa-arrow-down').addClass('fa-arrow-up');
      } else {
        lst.hide();
        icon.removeClass('fa-arrow-up').addClass('fa-arrow-down');
      }
    });
    
    $('.sendContrato').on('click',function(){
      var data = {
        _token: $('#tokenContr').val(),
        id: $('#idContr').val()
      };
      var that = $(this);
      if (that.data('send') == 0){
        $.post('{{route("contractsDelegacion.send")}}', data, function (resp) {
            if (resp == 'OK'){
              window.show_notif('','success','Email enviado');
              that.data('send', 1);
              that.addClass('text-success');
            } else {

              window.show_notif('','danger',resp);
            }
        });
      } else {
        alert('El contrato ya ha sido enviado. Vuelve a cargar la ventana para enviarlo nuevamente');
      }
    });
  });

</script>
<style>
  .sing_true,
  .sing_false{
    padding: 0px 1em;
    font-size: 14px;
    background-color: red;
    border-radius: 8px;
    color: #FFF;
    font-weight: 700;
    display: inline-block;
    margin-left: 2em;
  }
  .sing_true{
    background-color: green;
  }
  .sendContrato{cursor: pointer;}
  .sendContrato:hover{
    color: #10cfbd ;
  }
  </style>