<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES");  
        $total_pvp = 0;
        $total_coste = 0;
?>
<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
    <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
</button>

<div class="col-md-12 not-padding content-last-books">
    <div class="alert alert-info fade in alert-dismissable" style="max-height: 600px; overflow-y: auto;">
        <!-- <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> -->
        <!-- <strong>Info!</strong> This alert box indicates a neutral informative change or action. -->
        <h4 class="text-center"> ALARMAS DE BAJO BENEFICIOS </h4>
        
        
         <div class="col-md-12 col-xs-12" style="padding-right: 0;">
           <table class="table table-striped" id="list_lowProf">
                <thead >
                    <th class ="text-center bg-complete text-white sorting_disabled" style="width: 20% !important; font-size:
                    10px!important">Nombre</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important">Apto</th>
                    <th class ="text-center bg-complete text-white" style="width: 12% !important;font-size:10px!important">IN - OUT</th>
                    <th class ="text-center bg-complete text-white" style="width: 7% !important;font-size:10px!important">
                      PVP<br/><b id="alarms_totalPVP"></b></th>
                    <th class ="text-center bg-complete text-white" style="width: 7% !important;font-size:10px!important">
                      Coste Total<br/><b id="alarms_totalCosteTotal"></b></th>
                    <th class ="text-center bg-complete text-white" style="width: 7% !important;font-size:10px!important">%Benef</th>
                    <th class ="text-center bg-complete text-white" style="width: 5% !important;font-size:10px!important"></th>
                </thead>
                <tbody >
                    <!-- Totales -->

                    <?php foreach ($alarms as $book): ?>
                        <tr >
                            <td class="text-center">
                                <span style="display: none;"><?php echo strtotime($book->start);?></span>
                                <div class="col-xs-2">
                                    <?php if ($book->agency != 0): ?>
                                        <img style="width: 20px;margin: 0 auto;position: absolute; left: 0px;" src="/pages/<?php  echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
	                               <?php endif ?>

                                </div>
                                <div class="col-xs-8">
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                                        <?php  echo $book->customer->name ?>
                                    </a>
                                </div>
                                <div class="col-xs-2">
                                    <?php if (!empty($book->book_owned_comments) && $book->promociones != 0 ): ?>
                                        <img src="/pages/oferta.png" style="width: 40px;" title="<?php echo $book->book_owned_comments ?>">
                                    <?php endif ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <!-- apto -->
                                <?php echo $book->room->nameRoom ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                    echo $start->formatLocalized('%d %b');
                                ?> -
                                <?php
                                    $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                    echo $finish->formatLocalized('%d %b');
                                ?>
                            </td>
                            <td class="text-center PVP" style="border-left: 1px solid black;">
                              <?php $total_pvp += $book->total_price;?>
                              <?php echo number_format($book->total_price,0,',','.') ?> €</b>
                            </td>
                            <td class="text-center coste bi " style="border-left: 1px solid black;">
                              <?php $total_coste += $book->get_costeTotal();?>
                              <?php echo number_format($book->get_costeTotal(),2,',','.') ?> €</b>
                            </td>
                            <?php $inc_percent = $book->get_inc_percent();?>
                            <?php if(round($inc_percent) <= $percentBenef && round($inc_percent) > 0): ?>
                                <?php $classDanger = "background-color: #f8d053!important; color:black!important;" ?>
                            <?php elseif(round($inc_percent) <= 0): ?>
                                <?php $classDanger = "background-color: red!important; color:white!important;" ?>
                            <?php else: ?>
                                <?php $classDanger = "" ?>
                            <?php endif; ?>
                            <td class="text-center beneficio bf " style="border-left: 1px solid black; <?php echo $classDanger ?>">
	                            <?php echo number_format($inc_percent,0)."%" ?>
                            </td>
                            
                            <td class="text-center " >
                              <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-default toggleAlertLowProfits" type="button" data-toggle="tooltip" title="" data-original-title="Activa / Desactiva el control de Beneficio para este registro." data-sended="1">
                                @if($book->has_low_profit)
                                  <i class="fa fa-bell-slash" aria-hidden="true"></i>
                                @else
                                  <i class="fa fa-bell" aria-hidden="true"></i>
                                @endif
                              </button> 
                            </td>
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
        </div>
        <button id="activateAlertLowProfits" class="btn btn-xs btn-default " type="button" >
          <i class="fa fa-bell" aria-hidden="true"></i>&nbsp;Activar para todos
        </button>
        <button class="btn btn-xs btn-default " type="button" onclick="location.reload();">
          Actualizar los datos
        </button>
    </div>
</div> 

<script type="text/javascript">
    $(document).ready(function() {
      
      $('#alarms_totalPVP').text("<?php echo $total_pvp.' €';?>");
      $('#alarms_totalCosteTotal').text("<?php echo $total_coste.' €';?>");
      
      $('.toggleAlertLowProfits').click(function(event) {
      var id = $(this).attr('data-id');
      var objectIcon = $(this).find('i');
      var totalCount = $("#btnLowProfits").find('.numPaymentLastBooks');
      $.get('/admin/reservas/api/toggleAlertLowProfits', { id:id }, function(data) {
                    if (data.status == 'danger') {
                      window.show_notif(data.title,data.status,data.response);
                    } else {
                      var currentCount = totalCount.data('val');
                      
                      if (objectIcon.hasClass('fa-bell-slash')){
                        objectIcon.removeClass('fa-bell-slash').addClass('fa-bell');
                        currentCount++;
                      } else {
                        currentCount--;
                        objectIcon.removeClass('fa-bell').addClass('fa-bell-slash');
                      }
                      totalCount.data('val',currentCount)
                      totalCount.text(currentCount)
                      window.show_notif(data.title,data.status,data.response);
                        /**Change button alert class */
                        if ($('#list_lowProf').find('.fa-bell').length>0){
                          //hasn't active items
                          if ( !$('#btnLowProfits').hasClass('btn-alarms') )
                            $('#btnLowProfits').addClass('btn-alarms')
                        } else {
                          if ( $('#btnLowProfits').hasClass('btn-alarms') )
                            $('#btnLowProfits').removeClass('btn-alarms')
                        }
                        
                    }
                });
         
            
        });
         
    $('#activateAlertLowProfits').click(function(event) {
    
    if (confirm("Esto activará las alarmas de todos los registros. Desea continuar?")){
      
      var id = $(this).attr('data-id');
      var objectIcon = $(this).find('i');
      $.get('/admin/reservas/api/activateAlertLowProfits', function(data) {
                    if (data.status == 'danger') {
                        window.show_notif(data.title,data.status,data.response);
                        location.reload();
                    } else {
                        window.show_notif(data.title,data.status,data.response);
                        location.reload();
                    }
                });
         
        }    
        });
        
    });
</script>