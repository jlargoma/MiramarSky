<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
$uRole = getUsrRole();
$is_mobile = $mobile->isMobile();
?>
@extends('layouts.admin-master')

@section('title') Planning @endsection

@section('externalScripts')

<link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<link rel="stylesheet" href="{{ assetV('/css/backend/planning.css')}}" type="text/css" />
<script type="text/javascript" src="{{ assetV('/js/backend/buzon.js')}}"></script>
<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
<style>
  .bloq-btn-tabs{
    width: 100%;
    overflow: auto;
  }
  .btnMob button.btn,
  .buttonsTops button.btn {
    height: 38px;
    padding: 0 14px;
    margin-bottom: 10px;
  }
  .buttonsTops a.btn {
    margin-top: -24px;
    height: 38px;
    padding: 8px;
  }
  .boxPlanning{
    padding: 1em;
  }
  .dataTables_length{
    display: none;
  }
  .btnMob button.btn, .buttonsTops button.btn i {
    margin-right: 7px;
  }
  @media screen and (max-width:450px){
    .panel-mobile, .table-responsive.content-calendar{
      margin-bottom: 0px;
    }
    .btn-cons{
      min-width: 3em!important;
    }

    .bloq-btn-tabs {
      margin-bottom: 6px;
    }

    .boxPlanning{
      padding: 5px;
    }
    .btnMob button.btn,
    .buttonsTops button.btn {
      font-size: 14px;
      height: 39px;
      padding: 8px;
    }
    input#nameCustomer {
      height: 40px;
    }
    .buttonsTops a.btn {
      margin-top: -7px;
      margin-bottom: 1em;
    }

    .btnMob button.btn, .buttonsTops button.btn i {
    font-size: 19px;
}
  .btnMob button.btn, .buttonsTops button.btn i {
    margin-right: 0px;
  }
    .bloq-btn-tabs .btn-tables span.bold {
      width: 35px !important;
      display: inline-grid;
      overflow: hidden;
    }
    button.btn.btn-tables {
      width: 78px;
      padding: 7px 2px;
      padding-right: 0px;
      padding-left: 4px;
      text-align: left;
    }
  }
</style>
@endsection

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
  {{ implode('', $errors->all(':message')) }}
</div>
@endif
<div class="row boxPlanning" >
  <div class="col-md-12">
    @include('backend.years.selector', ['minimal' => true])
    @include('backend.planning.blocks._buttons_top',[
    'alarms'=>$alarms,
    'lastBooksPayment'=>$lastBooksPayment,
    'alert_lowProfits'=>$alert_lowProfits,
    'parteeToActive'=>$parteeToActive,
    'ff_pendientes'=>$ff_pendientes
    ])
  </div>
  <div class="col-md-7 p_mobil-0">
    <div class="bloq-btn-tabs">
      <div class="btn-tabs">
        @include('backend.planning.blocks.buttons_table_tabs')
      </div>
    </div>
    @if($uRole != "agente")
    @include('backend.planning.blocks.min_blocks.searchs')  
    @endif
    <div class="col-xs-12" id="resultSearchBook" style="display: none; padding-left: 0;"></div>
    <div class="col-xs-12 content-tables" style="padding: 0;">
    </div>

  </div>
  <div class="col-md-5" style="overflow: auto;">
    @if($ff_mount !== null)
    <div class="wallt_forfait ">
      Wallet de Forfait Express: 
      <span <?php if ($ff_mount < 100) echo 'class="text-danger"'; ?>>{{moneda($ff_mount)}}</span>
    </div>
    @endif
    <div class="row content-calendar push-20" style="min-height: 515px;">
      <div class="col-xs-12 text-center sending" style="padding: 120px 15px;">
        <i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br>
        <h2 class="text-center">CARGANDO CALENDARIO</h2>
      </div>
    </div>
  </div>

</div>

@include('backend.planning.blocks.min_blocks.modalsPlanning')    
<?php if ($uRole != "agente"): ?>
  <!-- GENERADOR DE LINKS PAYLAND  -->
  <div class="modal fade slide-up in" id="modalLinkStrip" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xd">
      <div class="modal-content-classic">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
          <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
        </button>
        @include('backend.stripe.link')
      </div>
    </div>
  </div>
<?php endif ?>

@if($alarmsCheckPaxs)
<div class="modal fade slide-up in" id="modalPAXs" tabindex="-1" role="dialog" aria-hidden="true" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        @include('backend.planning._alarmsPAXs', ['alarms' => $alarmsCheckPaxs])
      </div>
    </div>
  </div>
</div>
@endif


<form method="post" id="formFF" action=""  <?php
if (!$is_mobile) {
  echo 'target="_blank"';
}
?>>
  <input type="hidden" name="admin_ff" id="admin_ff">
</form>


@endsection

@section('scripts')

<script type="text/javascript">
window["csrf_token"] = "{{ csrf_token() }}";
window["uRole"] = "{{ $uRole }}";
window["URLCalendar"] = '/getCalendarMobile/';
</script>

<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>


<script src="/assets/js/notifications.js" type="text/javascript"></script>
<script src="{{assetV('/js/backend/planning.js')}}" type="text/javascript"></script>
<script src="{{assetV('/js/backend/booking_script.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('button[data-type="pendientes"]').trigger('click');
});
</script>

<script>
  /**************************************************************/
  /****   SEND VISA           **********************************/
  $(document).ready(function () {
      var sendVisa = true;
      $('body').on('change', '.cc_upd', function (event) {
          if (sendVisa) {
              sendVisa = false;
              var that = $(this);
              var bID = that.data('book_id');
              var idCustomer = that.data('customer_id');
              $('#loadigPage').show('slow');

              var params = {
                  data: $('#visa_' + bID).val(),
                  _token: "{{ csrf_token() }}",
                  bID: bID
              };

              $.post("{{route('booking.save_creditCard')}}", params, function (data) {
                  window.show_notif(data.title, data.status, data.response);
                  $('#loadigPage').hide('slow');
                  sendVisa = true;
              });
          }
      });

      setTimeout(function () {
          $('.automatic_click').trigger('click');
      }, 150);

      $('#goOtasPrices').on('click', function(){
          window.location.href = "/admin/channel-manager/controlOta";
      })
  });
</script>
<script type="text/javascript">
  window["csrf_token"] = "{{ csrf_token() }}";
  window["uRole"] = "{{ $uRole }}";
  window["URLCalendar"] = '/getCalendarMobile/';
</script>
@include('backend.planning.calendar.scripts');

@endsection