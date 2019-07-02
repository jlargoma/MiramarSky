<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES");  
?>
<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
    <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
</button>

<div class="col-md-12 not-padding content-last-books">
    <div class="alert alert-info fade in alert-dismissable" style="max-height: 600px; overflow-y: auto;">
        <!-- <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> -->
        <!-- <strong>Info!</strong> This alert box indicates a neutral informative change or action. -->
        <h4 class="text-center"> ALARMAS DE BAJO BENEFICIOS </h4>
        <table class="table" style="margin-top: 0;">
            <tbody>
                <?php foreach ($alarms as $key => $book): ?>
                    <tr>
                        <td class="text-center"  style="width: 30px; padding: 5px 0!important">
                            <?php if ($book->agency != 0): ?>
                                <img style="width: 20px;margin: 0 auto;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
                            <?php endif ?>
                        </td>
                        <td class ="text-center" style="color: black;padding: 5px!important;">  
                            <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                                <?php echo $book->customer->name ?>
                            </a> 
                            
                        </td>
                        <td class="text-center" style="color: black; padding: 5px!important">   
                            <?php echo substr($book->room->nameRoom,0,5) ?>       
                        </th>
                        <td class="text-center" style="color: black; padding: 5px!important">   
                            <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?>
                        </th>
                        <td class="text-center" style="color: black;padding: 5px 10px!important">   
                            <b>
                                <?php
                                    $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                    echo $start->formatLocalized('%d %b');
                                ?>        
                            </b> - 
                            <b>
                                <?php
                                    $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                    echo $finish->formatLocalized('%d %b');
                                ?>        
                            </b>           
                        </td>
                        <td class="text-center" style="color: black;padding: 5px!important;">
                             <b><?php echo $book->total_price ?>€</b>
                        </td>
                        
                        <td class="text-center" style="color:red;">
                          <?php
                          $profit = $book->profit;
                          $total_price = $book->total_price;
                          $inc_percent = 0;
                          if($book->room->luxury == 0 && $book->cost_lujo > 0) {
                           $profit     = $book->profit - $book->cost_lujo;
                           $total_price = ( $book->total_price - $book->sup_lujo );
                          }
                          ?>
                          <?php 
                          if ($total_price != 0):
                            $inc_percent = ($profit/ $total_price )*100;
                            echo round($inc_percent);
                          else:
                            echo '0';
                          endif;
                          ?>%
                        </td>
                        <td class="text-center">
                          <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-default toggleAlertLowProfits" type="button" data-toggle="tooltip" title="" data-original-title="Activa / Desactiva el control de Beneficio para este registro." data-sended="1">
                              <i class="fa fa-bell" aria-hidden="true"></i>
                          </button> 
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
      $('.toggleAlertLowProfits').click(function(event) {
      var id = $(this).attr('data-id');
      var objectIcon = $(this).find('i');
      $.get('/admin/reservas/api/toggleAlertLowProfits', { id:id }, function(data) {
                    if (data.status == 'danger') {
                        $.notify({
                            title: '<strong>'+data.title+'</strong>, ',
                            icon: 'glyphicon glyphicon-star',
                            message: data.response
                        },{
                            type: data.status,
                            animate: {
                                enter: 'animated fadeInUp',
                                exit: 'animated fadeOutRight'
                            },
                            placement: {
                                from: "top",
                                align: "left"
                            },
                            offset: 80,
                            spacing: 10,
                            z_index: 1031,
                            allow_dismiss: true,
                            delay: 60000,
                            timer: 60000,
                        }); 
                    } else {
                      if (objectIcon.hasClass('fa-bell-slash')){
                        objectIcon.removeClass('fa-bell-slash').addClass('fa-bell');
                      } else {
                        objectIcon.removeClass('fa-bell').addClass('fa-bell-slash');
                      }
                        
                        $.notify({
                            title: '<strong>'+data.title+'</strong>, ',
                            icon: 'glyphicon glyphicon-star',
                            message: data.response
                        },{
                            type: data.status,
                            animate: {
                                enter: 'animated fadeInUp',
                                exit: 'animated fadeOutRight'
                            },
                            placement: {
                                from: "top",
                                align: "left"
                            },
                            allow_dismiss: false,
                            offset: 80,
                            spacing: 10,
                            z_index: 1031,
                            delay: 5000,
                            timer: 1500,
                        }); 
                    }
                });
         
            
        });
         
    $('#activateAlertLowProfits').click(function(event) {
    
    if (confirm("Esto activará las alarmas de todos los registros. Desea continuar?")){
      
      var id = $(this).attr('data-id');
      var objectIcon = $(this).find('i');
      $.get('/admin/reservas/api/activateAlertLowProfits', function(data) {
                    if (data.status == 'danger') {
                        $.notify({
                            title: '<strong>'+data.title+'</strong>, ',
                            icon: 'glyphicon glyphicon-star',
                            message: data.response
                        },{
                            type: data.status,
                            animate: {
                                enter: 'animated fadeInUp',
                                exit: 'animated fadeOutRight'
                            },
                            placement: {
                                from: "top",
                                align: "left"
                            },
                            offset: 80,
                            spacing: 10,
                            z_index: 1031,
                            allow_dismiss: true,
                            delay: 60000,
                            timer: 60000,
                        }); 
                        location.reload();
                    } else {
                        $.notify({
                            title: '<strong>'+data.title+'</strong>, ',
                            icon: 'glyphicon glyphicon-star',
                            message: data.response
                        },{
                            type: data.status,
                            animate: {
                                enter: 'animated fadeInUp',
                                exit: 'animated fadeOutRight'
                            },
                            placement: {
                                from: "top",
                                align: "left"
                            },
                            allow_dismiss: false,
                            offset: 80,
                            spacing: 10,
                            z_index: 1031,
                            delay: 5000,
                            timer: 1500,
                        }); 
                        location.reload();
                    }
                });
         
        }    
        });
        
    });
</script>