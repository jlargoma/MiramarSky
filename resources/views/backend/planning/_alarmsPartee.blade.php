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
        <h4 class="text-center"> ALARMAS DE PARTEE </h4>
        
        @if(count($alarms)>0)
         <div class="col-md-12 col-xs-12" style="padding-right: 0;">
           <table class="table table-striped" id="list_lowProf">
                <thead >
                    <th>Nombre</th>
                    <th>Apto</th>
                    <th>IN - OUT</th>
                    <th class="text-center">Ocupantes</th>
                    <th class="text-center">Actualizado</th>
                    <th class="text-center">SEND TO POLICE</th>
                </thead>
                <tbody >
                    <!-- Totales -->

                    <?php foreach ($alarms as $bookPartee): ?>
                    <?php $book = $bookPartee->book(); ?>
                        <tr >
                            <td class="p-8">
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
                            <td class="p-8">
                                <!-- apto -->
                                <?php echo $book->room->nameRoom ?>
                            </td>
                            <td class="p-8">
                                <?php
                                    $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                    echo $start->formatLocalized('%d %b');
                                ?> -
                                <?php
                                    $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                    echo $finish->formatLocalized('%d %b');
                                ?>
                            </td>
                            <td class="text-center guestNumber">
                              {{$bookPartee->guestNumber}}
                            </td>
                            <td class="text-center updated_at">
                              {{\Carbon\Carbon::parse($bookPartee->updated_at)->format('d/m/Y H:i')}}
                            </td>
                           
                            <td class="text-center " >
                              <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-primary finish_partee sending" type="button" data-toggle="tooltip" title="" data-original-title="" data-sended="1">
                                Finalizar
                              </button> 
                            </td>
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
           <hr>
           <div>
           La finalización de un check-in online implica que Partee:<br />
           <ul>
             <li>Crea los partes de entrada de viajeros.</li>
             <li>Realiza el envío al cuerpo policial correspondiente.</li>
             <li>Envía los partes de entrada de viajeros en formato PDF al e-mail de la cuenta de Partee a la que pertenece el alojamiento.</li>
           </ul>
           </div>
        </div>
        @else
        <p class="alert alert-warning">
          No existen registros.
        </p>
        @endif
    </div>
</div> 

<script type="text/javascript">
    $(document).ready(function() {
      
      $('.finish_partee').click(function(event) {
        var id = $(this).attr('data-id');
        var that = $(this);
        var rowTr = that.closest('tr');
        var thatBtn = that.closest('td');
        that.remove();
        thatBtn.html('<div>Sending...</div>');
        $.post('/ajax/send-partee-finish', { _token: "{{ csrf_token() }}",id:id }, function(data) {
                    if (data.status == 'danger') {
                      thatBtn.html('<div class="text-danger">Error</div>');
                        $.notify({
                            title: '<strong>Partee</strong>, ',
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
                        $.notify({
                            title: '<strong>Partee</strong>, ',
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
                        
                        that.closest('tr').remove();
                        
                    }
                });
        });
    });
</script>