<?php
$temporada = $oYear->year . ' - ' . ($oYear->year + 1);
?>

<div class="block">
  <div class="block-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important;color: black!important"></i>
    </button>
    <h2 class="text-center">Contrato <a href="{{route('contract.see',[$contract->id])}}" title="Ver contrato"  target="_black" ><i class="fa fa-eye"></i></a> <i class="fa fa-paper-plane  sendContrato"></i></h2>
    <h2 class="text-center">Temporada {{$temporada}}</h2>
  </div>
  <div class="px-1em">
    <form method="post" action="{{route('contracts.save')}}">
      <input type="hidden" name="id" id="idContr" value="{{$contract->id}}">
      <input type="hidden" name="_token" id="tokenContr" value="<?php echo csrf_token(); ?>">
      <div class="row">
      @if($sign)
        <input type="hidden" name="alreadySing" value="1">
        <div class="col-md-12">
          <div class="box-signed">
            <h5>Documento ya firmado</h5>
            <a href="{{route('contract.see',[$contract->id])}}" title="Ver contrato" target="_black" ><i class="fa fa-eye"></i> Ver Contrato</a>
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
            <li>{temporada_calendario} (calendario temporada)</li>
            <li>{temporada_costos} (tabla de costos)</li>
            <li>{temporada_rango} (rango en Años de la temporada)</li>
            <li>{break} (sallto de linea)</li>
          </u>
          <div class="singBox">
          @if(!$sign)
          <p>Documento no firmado</p>
          @endif
          </div>
        </div>

        <div class="col-md-7 mt-1em">
          <?php
          echo $sessionTypes;
          echo $calStyles;
          ?>
          <div class="rateCalendar">
            <?php
            foreach ($calendar as $c) {
              echo '<div class="item">' . $c . '</div>';
            }
            ?>
          </div>
        </div>
        <div class="col-md-5 rateCost  mt-1em">
          <?php echo $roomTarifas; ?>
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
      console.log(data);
      $.post('{{route("contracts.send")}}', data, function (resp) {
          if (resp == 'OK'){
            window.show_notif('','success','Email enviado');
          } else {
            
            window.show_notif('','danger',resp);
          }
      });
    });
  });

</script>