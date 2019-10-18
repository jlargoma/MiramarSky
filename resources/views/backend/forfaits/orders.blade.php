<?php

use \Carbon\Carbon;
use \App\Classes\Mobile;

$mobile = new Mobile();
?>
@extends('layouts.admin-master')

@section('title') Liquidacion @endsection

@section('externalScripts')
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
              COBRADO<br/><?php echo number_format($totals['totalPayment'], 0, ',', '.') ?> €</th>
            <th class="th-bookings th-6">
              PENDIENTE<br/><?php echo number_format($totals['totalToPay'], 0, ',', '.') ?> €</th>
            <th class="th-bookings th-2">FF</th>
          </tr>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($orders as $order):
            $book = $order['book'];
          ?>
          <tr>
            <td>
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
                            </td>
            <td class="text-center"><?php echo number_format($order['totalPrice'], 0, ',', '.') ?> €</td>
            <td class="text-center"><?php echo number_format($order['forfaits'], 0, ',', '.') ?> €</td>
            <td class="text-center"><?php echo number_format($order['material'], 0, ',', '.') ?> €</td>
            <td class="text-center"><?php echo number_format($order['class'], 0, ',', '.') ?> €</td>
            <td class="text-center"><?php echo number_format($order['totalPayment'], 0, ',', '.') ?> €</td>
            <td class="text-center text-danger"><?php echo number_format($order['totalToPay'], 0, ',', '.') ?> €</td>
            <td class="text-center">
             <a data-booking="<?php echo $book->id; ?>" class="openFF" title="Ir a Forfaits" >
              <?php
              $ff_status = $book->get_ff_status();
              if ($ff_status['icon']) {
                echo '<img src="' . $ff_status['icon'] . '" style="max-width:30px;" alt="' . $ff_status['name'] . '"/>';
              }
              ?>
              </a>
            </td>                
          </tr>
          <?php
          endforeach;
          ?>
        </tbody>
      </table>
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
        $.post('/admin/forfaits/open', { _token: "{{ csrf_token() }}",id:id }, function(data) {
          console.log(data);
          var formFF = $('#formFF');
          formFF.attr('action', data.link);
          formFF.find('#admin_ff').val(data.admin);
          formFF.submit();

        });
      });
    });
  </script>
@endsection