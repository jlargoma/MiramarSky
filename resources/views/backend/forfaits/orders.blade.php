<?php

use \Carbon\Carbon;
use \App\Classes\Mobile;

$mobile = new Mobile();
?>
@extends('layouts.admin-master')

@section('title') Liquidacion @endsection

@section('externalScripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
<style>
  .table thead tr.text-white th{
    color: #fff !important;
  }
</style>
@endsection

@section('content')

<div class="container-fluid padding-25 sm-padding-10 table-responsive">
  <div class="row">
    <div class="col-md-12 text-center">
      <h2>Liquidación Forfaits {{ $year->year }} - {{ $year->year + 1 }}</h2>
    </div>
    <div class="col-md-12 text-center">
      <div class="btn-contabilidad">
        <?php if (Request::path() == 'admin/forfaits/orders'): ?>
          <button class="btn btn-md text-white active"  disabled>Control FF</button>
        <?php else: ?>
          <a class="text-white btn btn-md btn-primary" href="{{url('/admin/forfaits/orders')}}">Control FF</a>
<?php endif ?>	
      </div>

      <div class="btn-contabilidad">
        <?php if (Request::path() == 'admin/forfaits'): ?>
          <button class="btn btn-md text-white active"  disabled>Items FF</button>
        <?php else: ?>
          <a class="text-white btn btn-md btn-primary" href="{{url('/admin/forfaits')}}">Items FF</a>
<?php endif ?>	
      </div>
    </div>
  </div>

  
  
  
<div class="clearfix"></div>
  <div class="row">
    <div class="col-md-3 col-xs-8">
      <table class="table table-hover table-striped table-ingresos" style="background-color: #92B6E2">
        <thead class="bg-complete" style="background: #d3e8f7">
        <th colspan="2" class="text-black text-center"> Ingresos Temporada</th>
        </thead>
        <tbody>
          <tr>
            <td class="" style="padding: 5px 8px!important; background-color: #d3e8f7!important;"><b>VENTAS TEMPORADA</b></td>
            <td class=" text-center" style="padding: 5px 8px!important; background-color: #d3e8f7!important;">
              <b><?php echo number_format(round($totals['totalSale']), 0, ',', '.') ?> €</b>
            </td>
          </tr>
          <tr style="background-color: #ef6464;">
            <td class="text-white" style="padding: 5px 8px!important;background-color: #ef6464!important;">
              Cobrado Temporada
            </td>
            <td class="text-white text-center" style="padding: 5px 8px!important;background-color: #ef6464!important;">
              <?php echo number_format(round($totals['totalPayment']), 0, ',', '.') ?> € 
            </td>
          </tr>
          <tr style="background-color: #38C8A7;">
            <td class="text-white" style="padding: 5px 8px!important;background-color: #38C8A7!important;">Pendiente Cobro</td>
            <td class="text-white text-center" style="padding: 5px 8px!important;background-color: #38C8A7!important;">
              <?php echo number_format(round($totals['totalToPay']), 0, ',', '.') ?> €
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-md-2 col-xs-4">
      <canvas id="pieIng" style="width: 100%; height: 250px;"></canvas>
    </div>
    <div class="col-md-4 col-xs-7">
       <div class="row bg-white push-30">
        <div class="col-md-6 bordered text-center">
          <h4 class="hint-text">Cobrado Temporada</h4>
          <h3 ><?php echo number_format(round($totals['totalPayment']), 0, ',', '.') ?> €</h3>
        </div>
        <div class="col-md-6 bordered text-center">
          <h4 class="hint-text bold">Vendido Temporada</h4>
            <h3 ><?php echo number_format(round($totals['totalSale']), 0, ',', '.') ?> €</h3>
        </div>
        <div class="col-md-6 bordered text-center">
          <h4 class="hint-text">Total de Ordenes</h4>
          <div class="p-l-20">
            <h3 ><?php echo $totals['orders']; ?></h3>
          </div>
        </div>
        <div class="col-md-6 bordered text-center">
          <h4 class="hint-text">Promedio por Orden</h4>
            <h3 >
              <?php 
              $promedio = 0;
              if ($totals['orders']>0){
                $promedio = round($totals['totalPrice'])/$totals['orders'];
              }
              echo number_format(round($promedio), 0, ',', '.')
              ?>
              €</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-xs-5 text-center">
      <div class="bordered bg-white p-8 ">
        <h4 class="hint-text bold black">Wallet de Forfait Express</h4>
        <h3 class="<?php if($ff_mount<100) echo 'text-danger';?>"><?php echo number_format($ff_mount, 0, ',', '.')?>€</h3>
      </div>
    </div>
</div>
  
  
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-9 col-xs-12 content-table-rooms">
      <table class="table ">
        <thead>
          <tr>
          <tr class ="text-center bg-complete text-white">
            <th class="th-bookings text-white th-1" >&nbsp;</th> 
            <th class="th-bookings th-name">Cliente</th>
            <th class="th-bookings">Telefono</th>
            <th class="th-bookings th-2">Pax</th>
            <th class="th-bookings">Apart</th>
            <th class="th-bookings th-2"><i class="fa fa-moon-o"></i> </th>
            <th class="th-bookings th-2"><i class="fa fa-clock-o"></i></th>
            <th class="th-bookings th-4">IN - OUT</th>
            <th class="th-bookings th-6">
              TOTAL<br/><?php echo number_format($totals['totalPrice'], 0, ',', '.') ?> €</th>
            <th class="th-bookings th-6">
              FF<br/><?php echo number_format($totals['forfaits'], 0, ',', '.') ?> €</th>
            <th class="th-bookings th-6">
              ALQ<br/><?php echo number_format($totals['material'], 0, ',', '.') ?> €</th>
            <th class="th-bookings th-6">
              CLASS<br/><?php echo number_format($totals['class'], 0, ',', '.') ?> €</th>
            <th class="th-bookings th-6">
              ORDEN RÁPIDA<br/><?php echo number_format($totals['quick_order'], 0, ',', '.') ?> €</th>
            <th class="th-bookings th-6">
              COBRADO<br/><?php echo number_format($totals['totalPayment'], 0, ',', '.') ?> €</th>
            <th class="th-bookings th-2">FF</th>
            <th class="th-bookings th-2" title="Reservas hechas en Forfait Express">FFExpress</th>
          </tr>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($orders as $order):
            $book = $order['book'];
          ?>
          <tr>
            <?php if ($book): ?>
            <td>
            <?php if ( $book->agency != 0): ?>
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
                              <?php foreach ($rooms as $room): ?>
                                  <?php if ($room->id == $book->room_id): ?>
                                         <?php echo substr($room->nameRoom." - ".$room->name, 0, 15)  ?>
                                  <?php endif ?>
                              <?php endforeach ?>
                            </td>
                            <td class ="text-center"><?php echo $book->nigths ?></td>
                            <td class="text-center sm-p-t-10 sm-p-b-10">
                                {{$book->schedule}}
                            </td>
                            <td class ="text-center">
                            <?php $start = Carbon::createFromFormat('Y-m-d',$book->start); ?>
                            <b><?php echo $start->formatLocalized('%d %b'); ?></b>
                            <span> - </span>
                            <?php $finish = Carbon::createFromFormat('Y-m-d',$book->finish);?>
                            <b><?php echo $finish->formatLocalized('%d %b'); ?></b>
            <?php else: ?>
                            <td></td>
                            <td class="text-center"><?php echo $order['name'].'<br>'.$order['email']; ?></td>
                            <td class="text-center"><?php echo $order['name']; ?></td>
                            <td class="text-center"> - </td>
                            <td class="text-center"> - </td>
                            <td class="text-center"> - </td>
                            <td class="text-center"> - </td>
                            <td class="text-center"> - </td>
            <?php endif ?>
                            </td>
            <td class="text-center"><?php echo number_format($order['totalPrice'], 0, ',', '.') ?> €</td>
            <td class="text-center"><?php echo number_format($order['forfaits'], 0, ',', '.') ?> €</td>
            <td class="text-center"><?php echo number_format($order['material'], 0, ',', '.') ?> €</td>
            <td class="text-center"><?php echo number_format($order['class'], 0, ',', '.') ?> €</td>
            <td class="text-center"><?php echo number_format($order['quick_order'], 0, ',', '.') ?> €</td>
            <td class="text-center">
              <div class="col-md-6">
                <?php echo number_format($order['totalPayment'], 0, ',', '.') ?> €
                 <?php 
                  $porcent = 0;
                  $color = 'style="color: red;"';
                  if ($order['totalPrice']>0){
                    if ($order['totalPayment']>0){
                      $porcent = ceil(($order['totalPayment']/$order['totalPrice'])*100);
                      if ($porcent>=100){
                        $color = 'style="color: #008000;"';
                        $porcent = 100;
                      }
                    }
                  } else {
                    $color = 'style="color: #008000;"';
                    $porcent = 100;
                  }
              
//               text-danger
              ?>
                <p <?php echo $color; ?>><?php echo number_format($order['totalToPay'], 0, ',', '.');  ?>€</p>
              </div>
             
              <div class="col-md-6"><span <?php echo $color; ?>><?php echo $porcent; ?>%</span></div>
              
            </td>
            <td class="text-center">
             <a data-booking="<?php echo $order['id']; ?>" class="openFF" title="Ir a Forfaits" >
              <?php
                $ff_status = $order['status'];
                
                  if ($ff_status['icon']) {
                    echo '<img src="' . $ff_status['icon'] . '" style="max-width:30px;" alt="' . $ff_status['name'] . '"/>';
                  } else {
                     echo '<img src="/img/miramarski/ski_icon_status_transparent.png" style="max-width:30px;" alt="Externo"/>';
                  }
                
              ?>
              </a>
            </td>  
            <td class="text-center">
                <?php echo $order['ff_sent'].'/'.$order['ff_item_total']; ?>
            </td>
          </tr>
          <?php
          endforeach;
          ?>
        </tbody>
      </table>
    </div>
    <div class="col-md-3" >
       <canvas id="barChartMounth" style="width: 100%; height: 250px;"></canvas>
       <div class="box-errors" style="display:none;">
         <h3 class="text-danger">Errores Forfaits</h3>
        <?php 
        if($errors):
          foreach ($errors as $er): 
        ?>
         <div class="item-error row">
           <div class="col-md-8">
              <?php echo $er->detail; ?><br/>
              <small><?php echo date('d M H:i', strtotime($er->created_at)); ?></small>
           </div>
           <div class="col-md-4">
             <a data-booking="<?php echo $er->book_id; ?>" class="openFF text-danger" title="Ir a Forfaits" >
              Ver Forfait
              </a>
           </div>
           
         </div>
        <?php 
          endforeach; 
        endif;
        ?>
       </div>
    </div>
  </div>
</div>
<form method="post" id="formFF" action="" target="_blank">
              <input type="hidden" name="admin_ff" id="admin_ff">
            </form>
@endsection

@section('scripts')
  <script type="text/javascript">
    $(document).ready(function () {
      $('.openFF').on('click', function (event) {
        event.preventDefault();
        var id = $(this).data('booking');
        $.post('/admin/forfaits/open', { _token: "{{ csrf_token() }}",order_id:id }, function(data) {
          var formFF = $('#formFF');
          formFF.attr('action', data.link);
          formFF.find('#admin_ff').val(data.admin);
          formFF.submit();

        });
      });
      
      new Chart(document.getElementById("pieIng"), {
        type: 'pie',
        data: {
          labels: ["Cobrado", "Pendiente", ],
          datasets: [{
              label: "Population (millions)",
              backgroundColor: ["#ef6464", "#38C8A7"],
              data: [
                //Comprobamos si existen cobros
              <?php echo round($totals['totalPayment']) ?>,
              <?php echo round($totals['totalToPay']) ?>,
              ]
            }]
        },
        options: {
          title: {
            display: false,
            text: 'Ingresos de la temporada'
          }
        }
      });
      
      new Chart(document.getElementById("barChartMounth"), {
        type: 'line',
                data: {
                labels: [{!!$months_label!!}],
                        datasets: [
                        {
                        data: [{!!$monthValue!!}],
                                label: 'Pagadas',
                                borderColor: "rgba(104, 255, 0, 1)",
                                fill: false
                        },
                     
                        ]
                },
                options: {
                title: {
                display: false,
                        text: ''
                }
                }
        });
  
    });
  </script>
@endsection