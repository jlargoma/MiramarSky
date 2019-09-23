<?php 
    use \Carbon\Carbon;
    use \App\Classes\Mobile;
    $mobile = new Mobile();
?>
@extends('layouts.admin-master')

@section('title') Liquidacion @endsection

@section('externalScripts')

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css"
          media="screen"/>
    <style>
        .table > thead > tr > th {
            padding: 3px 5px !important;
        }

        th {
            /*font-size: 15px!important;*/
        }

        td {
            font-size: 11px !important;
            padding: 5px 5px !important;
        }

        .pagos {
            background-color: rgba(255, 255, 255, 0.5) !important;
        }

        .beneficio {
            background-color: rgba(153, 188, 231, 0.4) !important;
        }

        td[class$="bi"] {
            border-left: 1px solid black;
        }

        td[class$="bf"] {
            border-right: 1px solid black;
        }

        .coste {
            background-color: rgba(200, 200, 200, 0.5) !important;
        }

        th.text-center.bg-complete.text-white {
            padding: 10px 5px;
            font-weight: 300;
            font-size: 12px !important;
            text-transform: capitalize !important;
        }

        .red {
            color: red;
        }

        .blue {
            color: blue;
        }

        .updateLimp {
            background-color: rgba(200, 200, 200, 0.5) !important;
            color: black;
            width: 85%;
            height: 25px;
            text-align: center;
            border: 0px;
        }

        .updateExtraCost,
        .updateCostApto,
        .updateCostPark,
        .updateCostTotal,
        .updatePVP {
            background-color: rgba(200, 200, 200, 0.5) !important;
            color: black;
            width: 90%;
            height: 25px;
            text-align: center;
            border: 0px;
        }

        .updateLimp.alert-limp, .updateExtraCost.alert-limp {
            background-color: #f8d053 !important;
        }

        .alert-limp {
            background-color: #f8d053 !important;
        }
        .title-year-selector{
            display: none;
        }
    </style>
@endsection

@section('content')


    <div class="container-fluid padding-5 sm-padding-10">

        <div class="row push-10">
            <div class="col-md-4 push-20">
                <div class="col-md-6">
                    <label>Nombre del cliente:</label>
                    <input id="nameCustomer" type="text" name="searchName" class="searchabled form-control"
                           placeholder="nombre del cliente" value="{{ old('searchName') }}"/>
                </div>
                <div class="col-md-3">
                    <label>APTO:</label>
                    <select class="form-control searchSelect minimal" name="searchByRoom">
                        <option value="all">Todos</option>
						<?php foreach (\App\Rooms::where('state', 1)->orderBy('order')->get() as $key => $room): ?>
                        <option value="<?php echo $room->id ?>">
							<?php echo substr($room->nameRoom . " - " . $room->name, 0, 8)  ?>
                        </option>
						<?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>AGENCIA:</label>
                    <select class="form-control searchAgency minimal" name="searchByAgency">
                        <option value="0">Todas</option>
                        <option value="1">Booking</option>
                        <option value="2">Trivago</option>
                        <option value="3">Bed&Snow</option>
                        <option value="4">AirBnb</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <h2>Liquidación por reservas {{ $year->year }} - {{ $year->year + 1 }}</h2>
            </div>
            <div class="col-md-1" style="padding: 10px 0;">
                @include('backend.years._selector', ['minimal' => true])
            </div>

            <div class="col-md-1 pull-right">
                <button class="btn btn-md btn-primary exportExcel">
                    Exportar Excel
                </button>
            </div>
            
        </div>
        <div class="row">
            <?php if ( !$mobile->isMobile() ): ?>
                <div class="col-lg-1 col-lg-offset-3 text-center">
                    <button id="booking_agency_details" class="btn btn-primary btn-xs">Ventas por Agencia</button>
                </div>
            <?php else: ?>
                <div class="col-lg-1 col-lg-offset-3 text-right">
                    <button id="booking_agency_details" class="btn btn-primary btn-xs">Ventas por Agencia</button>
                </div>
            <?php endif; ?>
          
          @if ( $mobile->isMobile() ): 
            <div class="col-lg-1 col-lg-offset-3 text-right m-t-5">
          @else:
            <div class="col-lg-1 col-lg-offset-3 text-center">
          @endif
          <button class="btn btn-danger btn-cons btn-xs <?php if($alert_lowProfits) echo 'btn-alarms'; ?> " id="btnLowProfits" type="button" data-toggle="modal" data-target="#modalLowProfits">
                <i class="fa fa-bell" aria-hidden="true"></i> <span class="bold">BAJO BENEFICIO</span>
                <span class="numPaymentLastBooks"  data-val="{{$alert_lowProfits}}"><?php echo  $alert_lowProfits; ?></span>
            </button>
          </div>
        </div>

        <div class="row">
            <div class="liquidationSummary">
                @include('backend.sales._tableSummary-subadmin', ['totales' => $totales, 'books' => $books, 'year' => $year])
            </div>
        </div>
        <div class="modal fade slide-up in" id="modalLowProfits" tabindex="-1" role="dialog" aria-hidden="true" >
          @if ($mobile->isMobile() )
            <div class="modal-dialog modal-xs">
          @else
            <div class="modal-dialog modal-lg">
          @endif
                <div class="modal-content-wrapper">
                    <div class="modal-content">
                        @include('backend.planning._alarmsLowProfits', ['alarms' => $lowProfits])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
    <script type="text/javascript" src="/assets/plugins/dropzone/dropzone.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
    <script src="/assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js"
            type="text/javascript"></script>
    <script src="/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="/assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/moment/moment.min.js"></script>
    <script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="/assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
    <script src="/assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
    <script src="/assets/plugins/handlebars/handlebars-v4.0.5.js"></script>
    <script type="text/javascript" src="{{asset('/js/bootbox.min.js')}}"></script>

    <script type="text/javascript">

      $(document).ready(function () {

        $('#date').change(function (event) {
          var year = $(this).val();
          window.location = '/admin/liquidacion/' + year;
        });


        $('.searchabled').keyup(function (event) {
          var searchString = $(this).val();
          var searchRoom = $('.searchSelect').val();
          var year = "{{ $year->year }}";
          var searchAgency = $('.searchAgency').val();
          $.get('/admin/liquidation/searchByName', {
            searchString: searchString,
            year: year,
            searchRoom: searchRoom,
            searchAgency: searchAgency,
          }, function (data) {
            $('.liquidationSummary').empty();
            $('.liquidationSummary').append(data);
          });
        });

        $('.searchSelect, .searchAgency').change(function (event) {
          var searchRoom = $('.searchSelect').val();
          var searchString = $('.searchabled').val();
          var searchAgency = $('.searchAgency').val();
          var year = "{{ $year->year }}";

          $.get('/admin/liquidation/searchByRoom', {
            searchRoom: searchRoom,
            searchString: searchString,
            searchAgency: searchAgency,
            year: year
          }, function (data) {

            $('.liquidationSummary').empty();
            $('.liquidationSummary').append(data);

          });
        });

        $('.seasonDays').change(function (event) {
          var numDays = $(this).val();
          $.get('/admin/update/seasonsDays/' + numDays, {numDays: numDays}, function (data) {
            alert(data);
            location.reload();
          });
        });

        $('.percentBenef').change(function (event) {
          var percentBenef = $(this).val();
          $.get('/admin/update/percentBenef/' + percentBenef, {percentBenef: percentBenef}, function (data) {
            alert(data);
            location.reload();
          });
        });

        $('.orderPercentBenef').click(function () {
          var searchRoom = $('.searchSelect').val();
          var searchString = $('.searchabled').val();
          var searchAgency = $('.searchAgency').val();
          var year = "{{ $year->year }}";
          $.get('/admin/liquidation/orderByBenefCritico', {
            searchRoom: searchRoom,
            searchString: searchString,
            searchAgency: searchAgency,
            year: year
          }, function (data) {

            $('.liquidationSummary').empty();
            $('.liquidationSummary').append(data);

          });
        });


        $('.exportExcel').click(function (event) {
          var searchString = $('.searchabled').val();
          var searchRoom = $('.searchSelect').val();
          var year = "{{ $year->year }}";

          window.open('/admin/liquidacion/export/excel?searchString=' + searchString + '&year=' + year + '&searchRoom=' + searchRoom, '_blank');


        });

        $('.updateLimp').change(function () {
          var id = $(this).attr('data-idBook');
          var limp = $(this).val();
          $.get("/admin/sales/updateLimpBook/" + id + "/" + limp).done(function (data) {

          });
        });

        $('.updateExtraCost').change(function () {
          var id = $(this).attr('data-idBook');
          var extraCost = $(this).val();
          $.get("/admin/sales/updateExtraCost/" + id + "/" + extraCost).done(function (data) {

          });
        });
        
        function formatNumber (n) {

            var n_array = n.toString().split('.');
            console.log(n,n_array);
            if(n_array.length == 1){
                 return n === '' ? n : Number(n).toLocaleString();
            }else{
                n = Number(n_array[0]).toLocaleString();
                if (n_array[1][0]) n +='.'+n_array[1][0];
                if (n_array[1][2]) n += n_array[1][1];
                return n;
            }
            
        }
        
        function toFixed(n,length){
            
            if(n % 1 != 0){
                return n.toFixed(length);
            }else{
                return n;
            }
            
        }

        $('button#booking_agency_details').click(function(){
          $.ajax({
            type: "POST",
            url: "/ajax/booking/getBookingAgencyDetails",
            dataType:'json',
            async: false,
            success: function(response){
              if(response.status === 'true'){

                var agencies_count = Object.keys(response.agencyBooks.data).length;
                var agencyBookHTML = '<div class="table-responsive">';
                agencyBookHTML += '<table class="table liq-agencia" border="1"><thead><tr><th>AGENCIA</th>';
                $.each(response.yearLst,function(index,year){
                  if (response.agencyBooks.years[year]){
                    agencyBookHTML += '<th colspan="5">TEMP '+response.agencyBooks.years[year]+'</th><th rowspan="2"></th>';
                  }
                });
                agencyBookHTML += '</tr><tr><th></th>';
                for(i=0;i<3;i++){
                  agencyBookHTML += '<th>Vtas</th><th>Vtas. %</th><th>Reservas</th><th>Res. %</th><th>Comisión</th>';
                }
                agencyBookHTML += '</th></thead><body>';

                x = 1;
                var agencyName = response.agencyBooks.items;
                $.each(response.agencyBooks.data,function(agency,seasons){
                  agencyBookHTML += '<tr class="text-right"><td class="bold" style="font-size:16px !important;">'+agencyName[agency]+'</td>';
                  var a = 2;
                  $.each(seasons,function(season,data){
                      agencyBookHTML += '<td>'+formatNumber(toFixed(data.total,0))+' €</td><td>'+toFixed(data.total_rate,0)+' %</td><td>'+formatNumber(data.reservations)+'</td><td>'+toFixed(data.reservations_rate,0)+' %</td><td>'+formatNumber(toFixed(data.commissions,0))+' €</td>';
                      if (a>0){
                        agencyBookHTML += '<td style="background-color:#48b0f7;"></td>';
                      }
                      a--;
                  });

                  agencyBookHTML += '</tr>';

                  x++;
                  });

                  agencyBookHTML += '<tr class="footer-table"><td>Total</td>';
                  var a = 2;
                  $.each(response.yearLst,function(index,year){
                  if (response.agencyBooks.totals[year]){
                    var aux = response.agencyBooks.totals[year];
                    agencyBookHTML += '<td>'+formatNumber(aux.total,0)+'</td><td>-</td>';
                    agencyBookHTML += '<td>'+aux.reservations+'</td><td>-</td>';
                    agencyBookHTML += '<td>'+formatNumber(aux.commissions,0)+'</td>';
                     if (a>0){
                        agencyBookHTML += '<td style="background-color:#48b0f7;"></td>';
                      }
                      a--;
                  }
                  });
                  agencyBookHTML += '</tr>';


                  agencyBookHTML += '<tbody></table>';
                  agencyBookHTML += '</div>';

                  bootbox.alert({
                      message: agencyBookHTML,
                      size: 'large',
                      backdrop: true
                  });

            <?php if (!$mobile->isMobile()): ?>
                          $('.modal-lg').attr('style','width: 70% !important;');
            <?php else: ?>
                          $('.modal-lg').attr('style','width: 100% !important;');
            <?php endif; ?>

              }else{
                  bootbox.alert({
                      message: '<div class="text-danger bold" style="margin-top:10px">Se ha producido un ERROR. El PAN no ha sido guardado.<br/>Contacte con el administrador.</div>',
                      backdrop: true
                  });
              }
            },
            error: function(response){
                bootbox.alert({
                    message: '<div class="text-danger bold" style="margin-top:10px">Se ha producido un ERROR. No se ha podido obtener los detalles de la consulta.<br/>Contacte con el administrador.</div>',
                    backdrop: true
                });
            }
          });
        });

      });
    </script>
@endsection