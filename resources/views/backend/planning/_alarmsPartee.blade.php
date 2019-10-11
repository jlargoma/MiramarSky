<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES");  
        $total_pvp = 0;
        $total_coste = 0;
        $today         = Carbon::now();
?>
<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
    <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
</button>

<div class="col-md-12 not-padding content-last-books">
    <div class="alert alert-info fade in alert-dismissable" style="max-height: 600px; overflow-y: auto;">
        <!-- <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> -->
        <!-- <strong>Info!</strong> This alert box indicates a neutral informative change or action. -->
        <h4 class="text-center"> ALARMAS DE PARTEE 
          <a href="https://app.partee.es/#/" target="_black" title="Ir a Partee">
            <i class="fa fa-external-link" aria-hidden="true"></i>
          </a>
        </h4>
        
        @if(count($books)>0)
       
        
          <div class="tab-pane" id="tabPagadas">
        <div class="row  table-responsive">
            <table class="table <?php if (Auth::user()->role != "limpieza"): ?>table-condensed<?php endif ?> table-striped table-data"  data-type="confirmadas" style="margin-top: 0;">
                <thead>
                    <tr class ="text-center bg-success text-white">
                      <th class="th-bookings th-1" >&nbsp;</th> 
                        <th class="th-bookings th-name">Cliente</th>
                        <th class="th-bookings">Telefono</th>
                        <th class="th-bookings th-2">Pax</th>
                        <th class="th-bookings">Apart</th>
                        <th class="th-bookings th-2">  <i class="fa fa-moon-o"></i> </th>
                        <th class="th-bookings th-2"> <i class="fa fa-clock-o"></i></th>
                        <th class="th-bookings  th-4">   IN     </th>
                        <th class="th-bookings th-4">   OUT      </th>
                        <th class="th-bookings th-6">   Precio      </th>
                        <?php if (Auth::user()->role != "limpieza"): ?>
                            <th class="th-bookings th-6">   a      </th>
                        <?php endif ?>
                        <th class="th-bookings th-2">&nbsp;</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 0 ?>
                    <?php foreach ($books as $book): ?>
                        <?php $class = ( $book->start <= $today) ? "blurred-line"  : '' ?>
                        <tr class="<?php if($count <= 1){echo $class;} ?>">
                            <?php 
                                $dateStart = Carbon::createFromFormat('Y-m-d', $book->start);
                            ?>
                            <td class="text-center">
                                <?php if ( $payment[$book->id] == 0): ?>
                                    <?php if ( $today->diffInDays($dateStart) <= 15 ):?>
                                        <span class=" label label-danger alertDay heart text-white">
                                            <i class="fa fa-bell"></i>
                                        </span>
                                    <?php elseif($today->diffInDays($dateStart) <= 7):?>
                                        <span class=" label label-danger alertDay heart text-white">
                                            <i class="fa fa-bell"></i>
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php $percent = 100 / ( $book->total_price / $payment[$book->id] ); ?>
                                    <?php if ( $percent <= 25 ): ?>

                                        <?php if ( $today->diffInDays($dateStart) <= 15 ):?>
                                            <span class=" label label-danger alertDay heart text-white">
                                                <i class="fa fa-bell"></i>
                                            </span>
                                        <?php elseif($today->diffInDays($dateStart) <= 7):?>
                                            <span class=" label label-danger alertDay heart text-white">
                                                <i class="fa fa-bell"></i>
                                            </span>
                                        <?php endif; ?>
                                        
                                    <?php endif ?>
                                    

                                <?php endif ?>



                                <?php if ($book->agency != 0): ?>
                                    <img style="width: 20px;margin: 0 auto;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
                                <?php endif ?>
                            </td>
                            <td class="text-center" style="padding: 10px !important">
                                <?php if (isset($payment[$book->id])): ?>
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="color: red"><?php echo $book->customer['name']  ?></a>
                                <?php else: ?>
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" ><?php echo $book->customer['name']  ?></a>
                                <?php endif ?>
                                <?php if (Auth::user()->role != "limpieza"): ?>
                                <?php if (!empty($book->comment) || !empty($book->book_comments)): ?>
                                    <?php 
                                        $textComment = "";
                                        if (!empty($book->comment)) {
                                            $textComment .= "<b>COMENTARIOS DEL CLIENTE</b>:"."<br>"." ".$book->comment."<br>";
                                        }
                                        if (!empty($book->book_comments)) {
                                            $textComment .= "<b>COMENTARIOS DE LA RESERVA</b>:"."<br>"." ".$book->book_comments;
                                        }
                                    ?>
                                    
                                    <div class="tooltip-2">
                                      <i class="fa fa-commenting" style="color: #000;" aria-hidden="true"></i>
                                      <div class="tooltiptext"><p class="text-left"><?php echo $textComment ?></p></div>
                                    </div>
                            <?php endif ?>
                                <?php endif ?>
                            </td>
                            <td class="text-center">
                                <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
                                    <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?>
                                <?php else: ?>
                                    <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
                                <?php endif ?>
                            </td>
                            <td class ="text-center" >
                                <?php if ($book->real_pax > 6): ?>
                                    <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                                <?php else: ?>
                                    <?php echo $book->pax ?>
                                <?php endif ?>
                                    
                            </td>
                            <td class ="text-center">
                                <select class="room form-control minimal" disabled data-id="<?php echo $book->id ?>" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                            
                                    <?php foreach ($rooms as $room): ?>
                                        <?php if ($room->id == $book->room_id): ?>
                                            <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                               <?php echo substr($room->nameRoom." - ".$room->name, 0, 15)  ?>
                                            </option>
                                        <?php else:?>
                                            <option value="<?php echo $room->id ?>"><?php echo substr($room->nameRoom." - ".$room->name, 0, 15)  ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>

                                </select>
                            </td>
                            <td class ="text-center"><?php echo $book->nigths ?></td>
                            <td class="text-center sm-p-t-10 sm-p-b-10">
                                {{$book->schedule}}
                            </td>

                            <?php $start = Carbon::createFromFormat('Y-m-d',$book->start); ?>
                            <td class ="text-center" data-order="<?php echo strtotime($start->copy()->format('Y-m-d'))?>"  style="width:20%!important">
                                <b><?php echo $start->formatLocalized('%d %b'); ?></b>
                            </td>

                            <?php $finish = Carbon::createFromFormat('Y-m-d',$book->finish);?>
                            <td class ="text-center" data-order="<?php echo strtotime($finish->copy()->format('Y-m-d'))?>"  style="width: 20%!important">
                                <b><?php echo $finish->formatLocalized('%d %b'); ?></b>
                            </td>
                            <td class ="text-center">
                                <?php if (Auth::user()->role != "limpieza"): ?>
                                    <div class="col-md-6 col-xs-12 not-padding">
                                    <?php echo round($book->total_price)."€" ?><br>
                                        <?php if (isset($payment[$book->id])): ?>
                                        <p style="color: <?php if ($book->total_price == $payment[$book->id]):?>#008000<?php else: ?>red<?php endif ?>;">
                                                <?php echo $payment[$book->id] ?> €
                                            </p>
                                        <?php else: ?>
                                    <?php endif ?>
                                </div>

                                    <?php if (isset($payment[$book->id])): ?>
                                    <?php if ($payment[$book->id] == 0): ?>
                                    <div class="col-md-5 col-xs-12 not-padding bg-success">
                                        <b style="color: red;font-weight: bold">0%</b>
                                        </div>
                                    <?php else:?>
                                    <div class="col-md-5  col-xs-12 not-padding">
	                                        <?php $total = number_format(100/($book->total_price/$payment[$book->id]),0);?>
                                        <p class="text-white m-t-10">
                                                <b style="color: <?php if ($total == 100):?>#008000<?php else: ?>red<?php endif ?>;font-weight: bold"><?php echo $total.'%' ?></b>
                                            </p>
                                        </div>

                                    <?php endif; ?>
                                    <?php else: ?>
                                    <div class="col-md-5 col-xs-12 not-padding bg-success">
                                        <b style="color: red;font-weight: bold">0%</b>
                                        </div>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php echo round($book->total_price)."€" ?>
                                <?php endif ?>
                            </td>

                            <?php if (Auth::user()->role != "limpieza"): ?>
                            <td class="text-center sm-p-t-10 sm-p-b-10">
                              
                                <?php if ($book->send == 1): ?>
                                    <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-default sendSecondPay" type="button" data-toggle="tooltip" title="" data-original-title="Enviar recordatorio segundo pago" data-sended="1">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                    </button> 
                                <?php else: ?>
                                    <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-primary sendSecondPay" type="button" data-toggle="tooltip" title="" data-original-title="Enviar recordatorio segundo pago" data-sended="0">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                    </button> 
                                <?php endif ?>
                                  <?php
                                $partee = $book->partee();
                                if ($partee):
                                  echo $partee->print_status($book->id,$book->pax,true);
                                 endif;
                                ?>
                            </td>
                            <?php endif ?>
                            <td class="text-center">
                                <?php if ($book->promociones> 0 ): ?>
                                    <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                                        <img src="/pages/oferta.png" style="width: 40px;">
                                    </span>
                                    <div class="comment-floating content-commentOwned-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $book->book_owned_comments ?></p></div>
                                    
                                <?php endif ?>
                            </td>
                            
                            
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>  
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
     
      $('.sendPartee').click(function(event) {
         var bID = $(this).data('id');
      var sms = $(this).data('sms');
        $.ajax({
        url: '/ajax/showSendRemember/'+bID,
        type: 'GET',
        success: function (response) {
          $('#modalSendPartee_sms').text(sms+" Sms enviados");
          $('#modalSendPartee_content').html(response);
          $('#modalSendPartee').modal('show');
        },
        error: function (response) {
          alert('No se ha podido obtener los detalles de la consulta.');
        }
      });
      
        

        
      });
      
      $('body').on('click','.showParteeData',function(event) {
         var partee_id= $(this).data('partee_id');
        $.ajax({
        url: '/ajax/partee-checkHuespedes/'+partee_id,
        type: 'GET',
        success: function (response) {
          $('#modalSendPartee_content').html(response);
        },
        error: function (response) {
          alert('No se ha podido obtener los detalles de la consulta.');
        }
      });
      });
      
      $('.finish_partee').click(function(event) {
//        var id = $(this).attr('data-id');
        var id = $(this).data('id');
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
                        thatBtn.html('<div>Enviado</div>');
                        that.closest('tr').remove();
                    }
                });
        });
    });
</script>