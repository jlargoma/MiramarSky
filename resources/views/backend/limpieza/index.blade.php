<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts')
   <link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
    <link href="{{ assetV('/css/backend/planning.css')}}" rel="stylesheet" type="text/css" />
    

@endsection
@section('content')
  <div class="container-fluid  p-l-15 p-r-15 p-t-20 bg-white">
    <div class="row push-10">
      <div class="container">
        <div class="col-xs-12 text-center">
          <div class="col-md-4 col-md-offset-4 not-padding">
            <h2 style="margin: 0;">
              <b>Planning</b>
            </h2>
          </div>
        </div>
      </div>
    </div>
    <div class="row ">
      <div class="col-md-7">
        <div class="col-md-8 col-xs-12">
          <button class="btn btn-success tab_books" type="button" data-type="checkin">
            <span class="bold">Check IN</span>
          </button>
          <button class="btn btn-primary tab_books" type="button" data-type="checkout">
            <span class="bold">Check OUT</span>
          </button>
          <button class="btn btn-primary tab_books" type="button" data-type="block" style="background-color: #448eff;">
            <span class="bold">Bloqueos</span>
          </button>
          <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal_bloqueo">Generar Bloqueo</button>
        </div>
        <div class="col-md-4 col-xs-12" >
          <form method="GET" id="formFilter">
            <input type="text" class="form-control daterange02" id="filter" name="filter" value="{{$dateFiltrer}}">
          </form>
        </div>
        <div class="col-xs-12" id="resultSearchBook" style="display: none; padding-left: 0;"></div>
        <div class="col-xs-12 content-tables" style="padding-left: 0;padding-right: 0;">
          <div id="checkin" class="list_bookings">
            @include('backend.limpieza._checkin')
          </div>
          <div id="checkout" style="display:none;" class="list_bookings">
            @include('backend.limpieza._checkout')
          </div>
          <div id="block" style="display:none;" class="list_bookings">
            @include('backend.limpieza._block')
          </div>
        </div>
      </div>
      <div class="col-md-5" style="overflow: auto;">
        <div class="calendar-mobile content-calendar" style="min-height: 515px;">
          <div class="col-xs-12 text-center sending" style="padding: 120px 15px;">
            <i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
            <h2 class="text-center">CARGANDO CALENDARIO</h2>
          </div>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade slide-up in" id="modal_bloqueo" tabindex="-1" role="dialog" aria-hidden="true" style=" z-index: 9999;">
  <div class="modal-dialog modal-xd">
    <div class="modal-content-classic">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
        <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
      </button>
      <h3 id="modal_bloqueo_title">Bloqueo de Apartamentos</h3>
      <div class="row" id="modal_bloqueo_content" style="margin-top:1em;">
        
        <form method="post" style="clear:both;" action="/admin/limpieza/bloquear">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <div class="row">
            <div class="col-md-5 col-xs-8 push-xs-10">
                <label>Entrada</label>
                <div class="">
                  <input type="text" class="form-control daterange02" id="fechas" name="fechas" required="" >
                    <input type="hidden" class="date_start" id="start" name="start">
                    <input type="hidden" class="date_finish" id="finish" name="finish">
                </div>
            </div>
            <div class="col-md-2 col-xs-4 push-xs-10">
                <label>Noches</label>
                <input type="text" class="form-control nigths" name="nigths" style="width: 100%" disabled >
            </div>
            <div class="col-md-4 col-xs-12 push-xs-10">
                <label>Apartamento</label>
                <select class="form-control full-width newroom minimal" name="newroom" id="newroom" required>
                    <option ></option>
                    <?php foreach ($rooms as $room): ?>
                      <?php if($room->state>0):?>
                        <option value="<?php echo $room->id ?>" data-luxury="<?php echo $room->luxury ?>" data-size="<?php echo $room->sizeApto ?>" <?php if ( isset($data['newRoomID']) && $data['newRoomID'] == $room->id ){ echo 'selected';}?>>
                          <?php echo substr($room->nameRoom." - ".$room->name, 0,12)  ?>
                        </option>
                      <?php endif; ?>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-xs-12 push-xs-10 mt-1em text-center">
              <label>Comentarios Internos</label>
              <textarea class="form-control book_comments" name="book_comments" rows="5" > </textarea>
            </div>
            <div class="col-xs-12 push-xs-10 mt-1em text-center">
              <button class="btn btn-success" >Enviar</button>
            </div>
          </div>
        </form>
        
       
      </div>
    </div>
  </div>
</div>

@endsection
@section('scripts')
<link href="{{ assetV('/css/backend/planning.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>
<script src="/assets/js/notifications.js" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/datePicker01.js')}}"></script>
  <script type="text/javascript">
     window["csrf_token"] = "{{ csrf_token() }}";
     window["uRole"] = "{{ $uRole }}";
     window["URLCalendar"] = '/getCalendarMobile/';
  </script>
<script>
$(document).ready(function () {
  $('.calend').click(function (event) {
  $('html, body').animate({
    scrollTop: $(".calendar-mobile").offset().top
  }, 2000);
  });
  
  
  $('.daterange02').change(function (event) {
    var date = $(this).val();
    var arrayDates = date.split('-');
    var res1 = arrayDates[0].replace("Abr", "Apr");
    var date1 = new Date(res1);
    var start = date1.getTime();
    var res2 = arrayDates[1].replace("Abr", "Apr");
    var date2 = new Date(res2);
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var diffDays = Math.ceil(timeDiff / (1090 * 3600 * 24));
    $('.nigths').val(diffDays);
    $('#start').val(date1.yyyymmmdd());
    $('#finish').val(date2.yyyymmmdd());


  });
  
  
  $('body').on('click', '.deleteBook', function (event) {
    if (!confirm('¿Quieres Eliminar la reserva?'))
      return false;
    var tr = $(this).closest('tr');
    var id = $(this).attr('data-id');
    $.get('/admin/limpieza/delete-block/' + id, function (data) {
      window.show_notif(data.title, data.status, data.response);
      if (data.title == 'OK') {
        tr.remove();
      }
    });
  });

  
  // Cargar tablas de reservas
  var chechoutEmpty=true;
  $('.tab_books').click(function (event) {
    $('.list_bookings').hide();
    $('#'+$(this).data('type')).show();
    if (chechoutEmpty){
       var dataTable = $('.tableCheckOut').DataTable({
          "paging":   false,
          paging:  true,
          "columnDefs": [
            {"targets": [1,2,5], "orderable": false }
          ],
          order: [[ 3, "asc" ]],
          pageLength: 30,
          pagingType: "full_numbers",
          @if($isMobile)
            scrollX: true,
            scrollY: false,
            scrollCollapse: true,
            fixedColumns:   {
              leftColumns: 1
            },
          @endif

        });
      chechoutEmpty = false;
    }
  });

  setTimeout(function () {
  $('.content-calendar').empty().load('/getCalendarMobile');
  }, 1500);
  
      var dataTable = $('.tableCheckIn').DataTable({
          "paging":   false,
          paging:  true,
          "columnDefs": [
            {"targets": [1,2,5], "orderable": false }
          ],
          order: [[ 6, "asc" ]],
          pageLength: 30,
          pagingType: "full_numbers",
          @if($isMobile)
            scrollX: true,
            scrollY: false,
            scrollCollapse: true,
            fixedColumns:   {
              leftColumns: 1
            },
          @endif

        });
        
      var dataTable = $('.tableBlock').DataTable({
          "paging":   false,
          paging:  true,
          "columnDefs": [
            {"targets": [1,6], "orderable": false }
          ],
          order: [[ 4, "asc" ]],
          pageLength: 30,
          pagingType: "full_numbers",
          @if($isMobile)
            scrollX: true,
            scrollY: false,
            scrollCollapse: true,
            fixedColumns:   {
              leftColumns: 1
            },
          @endif

        });
        
    $('#filter').on('change',function(){$('#formFilter').submit()});
});

</script>
  @include('backend.planning.calendar.scripts');
<style>

  .show-comment:hover{
    background-color: #6d5cae;
  }
  .show-comment:hover .comment-floating{
    display: block !important;
  }
  table.dataTable thead th,
  .table thead tr th[class*='sorting_']:not([class='sorting_disabled']){
    color: #FFF;
  }
/*  table.table.tableCheckIn.table-data.table-striped.dataTable.no-footer.DTFC_Cloned {
    display: none;
}
table#DataTables_Table_0 thead {
    display: none;
}*/
</style>
@endsection