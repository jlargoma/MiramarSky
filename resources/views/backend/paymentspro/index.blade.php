<?php

use \App\Classes\Mobile;

$mobile = new Mobile();
$isMobile = $mobile->isMobile()
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
<link rel="stylesheet" type="text/css" href="{{ asset ('/frontend/css/font-icons.css')}}">
<link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="/assets/js/canvasjs/canvasjs.min.js"></script>
<style>

  td{      
    padding: 10px 5px!important;
  }

  .table.tableRooms tbody tr td {
    padding: 10px 12px!important;
  }
  .costeApto{
    background-color: rgba(200,200,200,0.5)!important;
    font-weight: bold;
  }

  .pendiente{
    background-color: rgba(200,200,200,0.5)!important;
    font-weight: bold;
  }

  td[class$="bordes"] {border-left: 1px solid black;border-right: 1px solid black;}

  .coste{
    background-color: rgba(200,200,200,0.5)!important;
  }

  .red{
    color: red;
  }
  .blue{
    color: blue;
  }

  .modal-big{
    width: 75%;
  }

  #expencesByRoom .col-md-8.col-xs-12.not-padding{
    padding-right: 20px !important;
  }
  @media screen and (max-width: 767px){
    .modal-big{
      width: 100%;
    }
  }
  .btn-transparent{
    background: transparent;
    border: 0;
  }
  .btn-transparent:hover{
    color: #48b0f7;
    text-decoration: underline;
  }
</style>

<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<style type="text/css" media="screen"> 
  .daterangepicker{
    z-index: 10000!important;
  }
  .pg-close{
    font-size: 45px!important;
    color: white!important;
  }
  @media only screen and (max-width: 767px){
    .daterangepicker {
      left: 12%!important;
      top: 3%!important; 
    }
  }

</style>

@endsection
<?php

use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>  
@section('content')

<div class="container-fluid padding-25 sm-padding-10">
  <div class="row push-20">
    <div class="col-md-3 col-xs-12 text-center">
      <form method="POST" id="form_filterByRange"> 
        <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" id="filter_startDate" name="filter_startDate" value="">
        <input type="hidden" id="filter_endDate" name="filter_endDate" value="">
      <h5 class="text-center push-10">FILTRAR LIQUIDACIONES:</h5>
      <button class="btn btn-xs btn-primary" type="button" id="refreshFilters" dat title="Limpiar filtros">
        <i class="fa fa-trash"></i>
      </button>
      <input type="text" class="form-control dateRange" id="dateRangefilter" name="dateRangefilter" required="" readonly="">
      <button class="btn btn-xs btn-primary"  title="Liquidacion total" id="filterByRange">
        <i class="fa fa-eye"></i>
      </button>
      </form>
    </div>
    <div class="col-md-2 col-md-offset-1 col-xs-12 text-center">
      <h2 class="font-w300">Pagos a <span class="font-w800">propietarios</span> </h2>
    </div>
    <div class="col-md-1 col-xs-12">
      @include('backend.years._selector')
    </div>
  </div>
  <div class="row">
    <div class="col-md-8 col-xs-12">
      <div class="col-md-11 col-xs-12 pull-right not-padding table-responsive" style="width: 90.80%;">
        <table class="table table-hover" >
          <thead>
            <tr>
              <th class ="text-center bg-complete text-white">
                C. Prop.   
              </th>
              <th class ="text-center bg-complete text-white">
                PVP    
              </th>
              <th class ="text-center bg-complete text-white">
                C. Total.   
              </th>
              <th class ="text-center bg-complete text-white">
                C. Apto.   
              </th>
              <th class ="text-center bg-complete text-white">
                C. Park   
              </th>
              <th class ="text-center bg-complete text-white">
                C. Lujo   
              </th>
              <th class ="text-center bg-complete text-white">
                C. Agen   
              </th>
              <th class ="text-center bg-complete text-white">
                C. Limp.   
              </th>
              <th class ="text-center bg-complete text-white">
                Benef.    
              </th>
              <th class ="text-center bg-complete text-white">
                %Ben.    
              </th>
              <th class ="text-center bg-complete text-white">
                Pagado    
              </th>
              <th class ="text-center bg-complete text-white">
                Pend.
              </th>
            </tr>
          </thead>
          <tbody>
            <tr> 
              <td class="text-center costeApto bordes" style="background: #89cfff;">
<?php $costeProp = $summary['totalApto'] + $summary['totalParking'] + $summary['totalLujo'] ?>
                <b><?php echo number_format($costeProp, 0, ',', '.') ?>€</b>
              </td>
              <td class="text-center" style="padding: 8px;background: #89cfff;">
                <b><?php echo number_format($summary['totalPVP'], 0, ',', '.') ?>€</b>
              </td>
              <td class="text-center costeApto bordes">
                <b><?php echo number_format($summary['totalCost'], 0, ',', '.') ?>€</b>
              </td>
              <td class="text-center">
                <b><?php echo number_format($summary['totalApto'], 0, ',', '.') ?>€</b>
              </td>
              <td class="text-center" style="padding: 8px;">
<?php echo number_format($summary['totalParking'], 0, ',', '.') ?>€
              </td>
              <td class="text-center" style="padding: 8px;">
<?php echo number_format($summary['totalLujo'], 0, ',', '.') ?>€
              </td>
              <td class="text-center" style="padding: 8px;">
<?php echo number_format($summary['totalAgencia'], 0, ',', '.') ?>€
              </td>
              <td class="text-center" style="padding: 8px;">
                <?php echo number_format($summary['totalLimp'], 0, ',', '.') ?>€
              </td>
              <td class="text-center" style="padding: 8px;">
                <?php $beneficio = $summary['totalPVP'] - $summary['totalCost']; ?>
                <?php if ($beneficio > 0): ?>
                  <span class="text-success font-w800"><?php echo number_format($beneficio, 0, ',', '.') ?>€</span>
<?php else: ?>
                  <span class="text-danger font-w800"><?php echo number_format($beneficio, 0, ',', '.') ?>€</span>
                <?php endif ?>

              </td>
              <td class="text-center" style="padding: 8px;">
<?php $summary['totalPVP'] = ($summary['totalPVP'] == 0) ? 1 :
        $summary['totalPVP'];
?>
<?php $benPercentage = ($beneficio / $summary['totalPVP']) * 100; ?>
                <?php echo number_format($benPercentage, 0, ',', '.') ?>%
              </td>
              <td class="text-center" style="padding: 8px;">
                <?php echo number_format($summary['pagos'], 0, ',', '.') ?> €
              </td>
              <td class="text-center pendiente bordes" style="padding: 8px;">
<?php
$summaryCostPropTot = $summary['totalApto'] +
        $summary['totalParking'] +
        $summary['totalLujo'];
$pendiente = $summaryCostPropTot - $summary['pagos'];
?>
                <span class="text-danger font-w800">
                  <b><?php echo number_format($pendiente, 0, ',', '.') ?>€</b>
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-4 col-xs-12 ">
      <button class="btn btn-md btn-complete pull-right" data-toggle="modal" data-target="#expencesByRoom">
              Hoja de gastos
      </button>
      <button class="btn btn-md btn-complete pull-right" id="costeByMonths">
              Coste Prop por mes
      </button>
    </div>
  </div>
  <div class="row">
    <div class="col-md-8 col-xs-12 push-0">
      <div class="table-responsive">
        <table class="table tableRooms">
          <thead>
            <tr>
              @if($isMobile)
              <th class ="text-center bg-complete text-white  static" style="width: 130px;padding-top: 9px !important;height: 3em;"> 
              @else
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;" >
              @endif
                Prop.
              </th>
              @if($isMobile)
              <th class ="text-center bg-complete text-white first-col" style="padding-right:13px !important;padding-left: 135px!important">
              @else
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;" >
              @endif
                C. Prop.   
              </th>
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;" >
                PVP  
              </th>
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
                C. Total.   
              </th>

              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
                C. Apto.   
              </th>
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
                C. Park   
              </th>
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
                C. Lujo   
              </th>
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
                C. Agen   
              </th>
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
                C. Limp.   
              </th>
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
                Benef
              </th>
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
                % Ben 
              </th>
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
                Pagado  
              </th>
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
                Pendiente   
              </th>

            </tr>
          </thead>
          <tbody>
            <?php foreach ($rooms as $room): ?>
              <?php if ($room->state == 1): ?>
    <?php
    $costPropTot = $data[$room->id]['totales']['totalApto'] +
            $data[$room->id]['totales']['totalParking'] +
            $data[$room->id]['totales']['totalLujo']
    ?>
                      <?php $pendiente = $costPropTot - $data[$room->id]['pagos'] ?>
                <tr>
                @if($isMobile)
                  <td class="text-left static" style="width: 130px;color: black;overflow-x: scroll;   margin-top: 4px; ">  
                @else
                <td class="text-left">
                @endif
                    <a class="update-payments" data-debt="<?php echo $pendiente ?>"
                       data-month="{{ $year->year }}" data-id="<?php echo $room->id ?>"
                       data-toggle="modal"
                       data-target="#payments">
                      <?php echo ucfirst($room->user->name) ?> (<?php echo $room->nameRoom ?>)
                    </a>

                  </td>
                   @if($isMobile)
                  <td class="text-center  costeApto bordes first-col" style="padding-right:13px !important;padding-left: 135px!important">   
              @else
              <td class="text-left costeApto bordes ">
              @endif
                    <button class="btn-transparent liquidationByRoom" data-id="<?php echo $room->id ?>" data-costeProp="<?php echo $costPropTot; ?>" data-toggle="modal" data-target="#liquidationByRoom" style="cursor: pointer; font-weight: 600" title="Liquidación de <?php echo $room->nameRoom ?>">
    <?php if ($costPropTot != 0): ?>
      <?php echo number_format($costPropTot, 0, ',', '.'); ?>€
    <?php else: ?>
                        -----
    <?php endif ?>
                    </button>


                  </td>
                  <td class="text-center"  style="padding: 10px 5px ; background: #89cfff;">

                    <button class="btn-transparent bookByRoom" data-id="<?php echo $room->id ?>"  data-toggle="modal" data-target="#bookByRoom">
    <?php if (isset($data[$room->id]['totales']['totalPVP'])): ?>
      <?php echo number_format($data[$room->id]['totales']['totalPVP'], 0, ',', '.'); ?>€
    <?php else: ?>
                        -----
                    <?php endif ?>
                    </button>

                  </td>

                  <td class="text-center  costeApto bordes"  style="padding: 10px 5px ;">

    <?php if (isset($data[$room->id]['totales']['totalCost'])): ?>
      <?php echo number_format($data[$room->id]['totales']['totalCost'], 0, ',', '.'); ?>€
                    <?php else: ?>
                      -----
                    <?php endif ?>
                  </td>


                  <td class="text-center"  style="padding: 10px 5px ;">
    <?php if (isset($data[$room->id]['totales']['totalApto'])): ?>
                      <?php echo number_format($data[$room->id]['totales']['totalApto'], 0, ',', '.'); ?>€
                    <?php else: ?>
                      -----
                    <?php endif ?>
                  </td>

                  <td class="text-center"  style="padding: 10px 5px ;">
    <?php if (isset($data[$room->id]['totales']['totalParking'])): ?>
                      <?php echo number_format($data[$room->id]['totales']['totalParking'], 0, ',', '.'); ?>€
                    <?php else: ?>
                      -----
                    <?php endif ?>
                  </td>

                  <td class="text-center"  style="padding: 10px 5px ;">
    <?php if (isset($data[$room->id]['totales']['totalLujo'])): ?>
                      <?php echo number_format($data[$room->id]['totales']['totalLujo'], 0, ',', '.'); ?>€
                    <?php else: ?>
                      -----
                    <?php endif ?>
                  </td>

                  <td class="text-center"  style="padding: 10px 5px ;">
    <?php if (isset($data[$room->id]['totales']['totalAgencia'])): ?>
                      <?php echo number_format($data[$room->id]['totales']['totalAgencia'], 0, ',', '.'); ?>€
                    <?php else: ?>
                      -----
                    <?php endif ?>
                  </td>

                  <td class="text-center"  style="padding: 10px 5px ;">
    <?php if (isset($data[$room->id]['totales']['totalLimp'])): ?>
                      <?php echo number_format($data[$room->id]['totales']['totalLimp'], 0, ',', '.'); ?>€
                    <?php else: ?>
                      -----
                    <?php endif ?>
                  </td>

                  <td class="text-center   "   style="padding: 10px 5px ;">
                    <?php
                    $benefRoom = $data[$room->id]['totales']['totalPVP'] - $data[$room->id]['totales']['totalCost']
                    ?>
                    <?php if ($benefRoom > 0): ?>
                      <span class="text-success font-w800"><?php echo number_format($benefRoom, 0, ',', '.') ?>€</span>
    <?php elseif ($benefRoom == 0): ?>
                      -----
                    <?php elseif ($benefRoom < 0): ?>
                      <span class="text-danger font-w800"><?php echo number_format($benefRoom, 0, ',', '.') ?>€</span>
                    <?php endif ?>
                  </td>

                  <td class="text-center"  style="padding: 10px 5px ;">
                    <?php
                    $divisor = ($data[$room->id]['totales']['totalPVP'] == 0) ? 1 : $data[$room->id]['totales']['totalPVP'];
                    $benefPercentageRoom = ( $benefRoom / $divisor ) * 100;
                    ?>
                    <?php if ($benefPercentageRoom > 0): ?>
                      <span class="text-success font-w800"><?php echo number_format($benefPercentageRoom, 0, ',', '.') ?>%</span>
    <?php elseif ($benefPercentageRoom == 0): ?>
                      -----
                    <?php elseif ($benefPercentageRoom < 0): ?>
                      <span class="text-danger font-w800"><?php echo number_format($benefPercentageRoom, 0, ',', '.') ?>%</span>
                    <?php endif ?>
                  </td>

                  <td class="text-center"  style="padding: 10px 5px ;">
    <?php if ($data[$room->id]['pagos'] != 0): ?>
      <?php echo number_format($data[$room->id]['pagos'], 0, ',', '.') ?>€
                    <?php else: ?>
                      -----
                    <?php endif ?>
                  </td>

                  <td class="text-center pendiente bordes"  style="padding: 10px 5px ;">

                <?php if ($pendiente <= 0): ?>
                      <span class="text-success font-w800"><?php echo number_format($pendiente, 0, ',', '.') ?>€</span>
                <?php else: ?>
                      <span class="text-danger font-w800"><?php echo number_format($pendiente, 0, ',', '.') ?>€</span>
    <?php endif ?>
                  </td>
                </tr>
  <?php endif ?>

<?php endforeach ?>
          </tbody>

        </table>
      </div>
    </div>
    <div class="col-md-4 col-xs-12">
      <h3 class="text-center font-w300">
        RESUMEN <span class="font-w800">PAGOS PROP</span>.
      </h3>
      <table class="table table-striped">
        <thead>
          <tr>
            <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
              Apart
            </th>
            <?php $lastThreeSeason = Carbon::createFromFormat('Y', $year->year)->subYears(2) ?>
<?php for ($i = 1; $i < 4; $i++): ?>
              <th class ="text-center bg-complete text-white" style="padding: 10px 5px;">
                Temp. <?php echo $lastThreeSeason->copy()->format('y'); ?> - <?php echo $lastThreeSeason->copy()->addYear()->format('y'); ?>
              </th>
            <?php $lastThreeSeason->addYear(); ?>
          <?php endfor; ?>

          </tr>
        </thead>
        <tbody>
<?php foreach ($rooms as $room): ?>
              <?php if ($room->state == 1): ?>
              <tr>
                <td class="text-center"  style="padding: 10px 5px ;">
                  <a class="historic-production" data-id="<?php echo $room->id ?>" data-toggle="modal" data-target="#payments">
                  <?php echo ucfirst(substr($room->user->name, 0, 6)) ?> (<?php echo substr($room->nameRoom, 0, 6) ?>)
                  </a>
                </td>
                <?php $lastThreeSeason = Carbon::createFromFormat('Y', $year->year)->subYears(2) ?>
              <?php for ($i = 1; $i < 4; $i++): ?>
                  <td class="text-center  costeApto bordes"  style="padding: 10px 5px ;">
                <?php echo number_format($room->getCostPropByYear($lastThreeSeason->copy()->format('Y')), 0, ',', '.'); ?> €
                  </td>
      <?php $lastThreeSeason->addYear(); ?>
    <?php endfor; ?>
              </tr>
  <?php endif; ?>
<?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>





</div>
</div>


<div class="modal fade slide-up in" id="payments" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important;color: black!important"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="contentPayments row"></div>
        </div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade slide-up disable-scroll in" id="bookByRoom" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-big">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close fa-2x"></i></button>
        <div class="container-xs-height full-height">
          <div class="row-xs-height">
            <div class="modal-body contentBookRoom">

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="modal fade slide-up disable-scroll in" id="liquidationByRoom" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 95%;">
    <div class="modal-content-wrapper" >
      <div class="modal-content" style="padding: 15px 5px;">
        <div class="modal-body contentLiquidationByRoom"></div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="modal fade slide-up disable-scroll in" id="expencesByRoom" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-big">
		<div class="modal-content-wrapper">
			<div class="modal-content">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close fa-2x"></i></button>
				<div class="container-xs-height full-height">
					<div class="row-xs-height">
						<div class="modal-body contentExpencesByRoom">
							@include('backend.sales.gastos._expensesByRoom', ['gastos' => $gastos, 'room' => 'all',
							'year' => $year])
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<h1>ddddddddddddddddddd</h1>
<div class="modal fade" id="modalCosteByMonths" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog" role="document" style="min-width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <strong class="modal-title" style="font-size: 1.4em;">Coste Prop por mes</strong>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')

<script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>

<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

<script type="text/javascript">
$(function () {
$(".dateRange").daterangepicker({
"buttonClasses": "button button-rounded button-mini nomargin",
"applyClass": "button-color",
"cancelClass": "button-light",

//endYear
"startDate": new Date("{{$startYear}}"),
"endDate": new Date("{{$endYear}}"),
//                "startDate": '01 Nov, <?php // echo $date->copy()->format('y')  ?>',
//                "endDate": '01 Nov, <?php // echo $date->copy()->addYear()->format('y')  ?>',
locale: {
format: 'DD MMM, YY',
"applyLabel": "Aplicar",
"cancelLabel": "Cancelar",
"fromLabel": "From",
"toLabel": "To",
"customRangeLabel": "Custom",
"daysOfWeek": [
"Do",
"Lu",
"Mar",
"Mi",
"Ju",
"Vi",
"Sa"
],
"monthNames": [
"Enero",
"Febrero",
"Marzo",
"Abril",
"Mayo",
"Junio",
"Julio",
"Agosto",
"Septiembre",
"Octubre",
"Noviembre",
"Diciembre"
],
"firstDay": 1,
},

});
});
$(document).ready(function () {

$('.update-payments').click(function (event) {
var debt = $(this).attr('data-debt');
var id = $(this).attr('data-id');
var month = $(this).attr('data-month');
$.get('/admin/pagos-propietarios/update/' + id + '/' + month, {debt: debt}, function (data) {
$('.contentPayments').empty().append(data);
});
});

$('.historic-production').click(function (event) {
var room_id = $(this).attr('data-id');
$.get('/admin/pagos-propietarios/get/historic_production/' + room_id, function (data) {
$('.contentPayments').empty().append(data);
});
});

$('#fechas').change(function (event) {

var month = $(this).val();
window.location = '/admin/pagos-propietarios/' + month;
});


});

$('.ver').click(function (event) {

var year = $('#fechas').val();
var idRoom = 'all';
alert(year + ' ' + idRoom);
// $.get('/admin/paymentspro/getBooksByRoom/'+idRoom,{ idRoom: idRoom, year: year}, function(data) {
// 	$('.contentBookRoom').empty().append(data);
// });


});


$('button.bookByRoom').click(function (event) {
event.preventDefault();
var year = $('#fechas').val();
var idRoom = $(this).attr('data-id');
var startDate = "{{$startYear}}";
var endDate = "{{$endYear}}";

$.get('/admin/paymentspro/getBooksByRoom/' + idRoom, {idRoom: idRoom, year: year,start:startDate,end:endDate}, function (data) {
$('.contentBookRoom').empty().append(data);
});

});
$('#refreshFilters').click(function (event) {
  window.location = window.location.href;
});
$('#filterByRange').click(function (event) {
  event.preventDefault();
  var date = $('#dateRangefilter').val();
  var start = window.formatDate($('#dateRangefilter').data('daterangepicker').startDate._d);
  var end = window.formatDate($('#dateRangefilter').data('daterangepicker').endDate._d);
  $('#filter_startDate').val(start);
  $('#filter_endDate').val(end);
  $('#form_filterByRange').submit();
});
$('button.liquidationByRoom').click(function (event) {
event.preventDefault();
var date = $('#dateRange').val();

var idRoom = $(this).attr('data-id');
var costeProp = $(this).attr('data-costeProp');
var startDate = "{{$startYear}}";
var endDate = "{{$endYear}}";
$.get('/admin/paymentspro/getLiquidationByRoom', {idRoom: idRoom, date: date, costeProp: costeProp,start:startDate,end:endDate}, function (data) {
$('.contentLiquidationByRoom').empty().append(data);
});

});
$('#costeByMonths').on('click', function(){
  $('#modalCosteByMonths').find('.modal-body').load('/admin/paymentspro/getLiquidationByMonth');
  $('#modalCosteByMonths').modal('show');   
});
</script>
@endsection