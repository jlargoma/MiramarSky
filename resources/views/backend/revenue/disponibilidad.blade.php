@extends('layouts.admin-master')

@section('title') Revenue @endsection

@section('externalScripts')
<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('/assets/css/font-icons.css')}}" type="text/css"/>

<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{ asset('/js/datePicker01.js')}}"></script>
<script type="text/javascript">
$(document).ready(function () {
var sendFormRevenue = function () {
$('#revenu_filters').submit();
}
$('#site').on('change', function (event) {
sendFormRevenue();
});
$('#month').on('change', function (event) {
sendFormRevenue();
});
});</script>
<style>


  .contenedor .table tr th,
  .contenedor .table tr td{
    text-align: center;
  }
  .contenedor .table tr th.static,
  .contenedor .table tr td.static{
    text-align: left;
  }

  .contenedor{
    max-width: 98%;
    margin: 1em auto;
    clear: both;
    content: "";
    overflow: auto;
    position: relative;
  }
  .contenedor .static-empty {
    background-color: #fafafa !important;
    width: 110px;
  }
  .table-excel .resume-head th {
    min-width: 48px;
  }

  .resume-head th{
    white-space: nowrap;
  }
  .resume-head .static {
    width: 107px;
    white-space: nowrap;
  }
  .table-resumen tfoot th{
    color: #545454 !important;
  }
  .table-resumen tfoot th.static {
    margin: 0px;
    height: 38px;
    width: 107px;
  }


  .table-excel td.tdSpecial {
    width: 100px;
    position: absolute;
    text-align: left;
    left: 7px;
  }
  .table-excel td.tdSpecial.td1 {
    height: 102px;
    background-color: #e6e6e6;
    display: block;
    left: 0;
    text-align: center;
    font-size: 11px;
    font-weight: 800;
    padding-top: 39px !important;
  }
  .table-excel.table-resumen td, .table-excel.table-resumen th {
    height: 2.5em;
  }
  .table-excel.table-resumen td.tdSpecial {
    padding-top: 7px !important;
  }
  .table-excel td.tdSpecial.td2 {
    left: 100px;
    text-align: left;
    display: block;
    padding-left: 10px !important;
  }
  .table-excel td.totals {
    background-color: #808080 !important;
    color: #FFF;
    margin-top: 2px;
  }
  .table-excel .avails{
    color: #19ca19;
    font-weight: 800;
  }
  .table-excel  td.avails.number {
    background-color: #69ff69;
    color: #383838;
  }
  th.thSpecial {
    position: absolute;
    width: 200px;
    height: 65px !important;
  }

  .table-excel.table-resumen td, .table-excel.table-resumen th{
    text-align: center;
  }
  .table-excel.table-resumen .first-col,
  .table-excel.table-resumen th.first-col {
    padding-left: 210px !important;
    text-align: right;
  }

  .tabChannels {
    background-color: #6d5cae;
    display: inline-block;
    padding: 7px 14px;
    border-radius: 2px;
    margin: -1px;
    color: #fff;
    cursor: pointer;
  }
  .tabChannels.active {
    font-weight: 800;
    background-color: #51b1f7;
  }
  .filter-field {
    float: left;
    margin-right: 1em;
  }
  #revenu_filters{
    clear: both;
    width: 100%;
    overflow: auto;
    margin: 1em auto;
  }
  input.editable {
    width: 25px;
    text-align: center;
    padding: 0px;
    border: none;
    background-color: #d4d4d4;
  }
  @media only screen and (max-width: 768px){
    .contenedor{
      max-width: 98%;
    }

    h2.text-center {
      font-size: 13px;
      line-height: 1;
      margin-top: 15px;
    }
    .filter-field {
      max-width: 49%;
    }

    .contenedor.mt-2em {
      margin-top: 0;
    }
    td.tdSpecial.td1 {
      width: 60px;
      white-space: normal !important;
      overflow: hidden;
      font-size: 11px !important;
    }
    .table-excel td.tdSpecial.td2 {
      left: 60px;
      width: 75px !important;
      overflow: hidden;
    }
    .table-excel.table-resumen .first-col, .table-excel.table-resumen th.first-col {
      padding-left: 136px !important;
    }
    th.thSpecial {
      width: 136px;
    }
  }
  @media only screen and (max-width: 425px){
    h2.text-center {
      font-size: 13px !important;
      line-height: 1  !important;
    }
  }

</style>
@endsection

@section('content')

<div class="box-btn-contabilidad">
  <div class="row bg-white">
    <div class="col-md-12 col-xs-12">

      <div class="col-md-6  col-xs-7 text-right">
        <h2 class="text-center">
          DISPONIBLIDAD x ALOJAMIENTO
        </h2>
      </div>
      <div class="col-md-2 col-xs-4 sm-padding-10" style="padding: 10px">
        @include('backend.years._selector')
      </div>
    </div>
  </div>
  <div class="row mb-1em text-center">
    @include('backend.revenue._buttons')
  </div>
</div>

<div class=" contenedor  mt-2em">
  <div class="col-md-4 col-xs-12">
    <form id="revenu_filters" method="get" action="{{route('revenue.disponibilidad')}}">
      <div class="filter-field">
        <label>Mes</label>
        <select name="month" id="month" class="form-control">
          @foreach($lstMonths as $k=>$n)
          <option value="{{$k}}" @if($month == $k) selected @endif>{{$n}}</option>
          @endforeach
        </select>
      </div>
    </form>
    <div >
      <form method="post" action="{{route('revenue.donwlDisponib')}}">
        <input id="month" name="month" value="{{$month}}"  type="hidden">
        <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
        <button class="btn btn-primary">Descargar</button>
      </form>
    </div>
  </div>
  <div class="col-md-4 col-xs-12">
    @include('backend.revenue.disponibilidad.summary-month')
  </div>
  <div class="col-md-4 col-xs-12">
    @include('backend.revenue.disponibilidad.summary')
  </div>
</div>
<div class="contenedor">
  <div class="table-responsive ">
    <table class="table table-resumen table-excel">
      <thead>
        <tr class="resume-head">
          <th class="thSpecial" colspan="2"></th>
          <td class="first-col"></td>
          @if($aLstDays)
          @foreach($aLstDays as $d=>$w)
          <th>{{$w}}<br>{{$d}}</th>
          @endforeach
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach($otas as $ch=>$nro)
        <tr>
          <td rowspan="3" class="tdSpecial td1">{{show_isset($chNames,$ch)}}</td>
          <td class="tdSpecial td2 totals">Total</td>
          <td class="totals first-col"></td>
          @foreach($aLstDays as $d=>$w)
          <td class="totals ">{{$nro}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="tdSpecial td2">Libres</td>
          <td class="first-col"></td>
          @foreach($listDaysOtas[$ch] as $avail)
          <td class="avails {{($avail>0) ? 'number' : ''}}">{{($avail>0) ? $avail : '-'}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="tdSpecial td2">Ocupadas</td>
          <td class="first-col"></td>
          @foreach($listDaysOtas[$ch] as $avail)
          <td class=" ">{{$nro-$avail}}</td>
          @endforeach
        </tr>
        @endforeach
        <tr class="tr-total">
          <td rowspan="3" class="tdSpecial td1">TOTAL</td>
          <td class="tdSpecial td2 totals">Total</td>
          <td class="first-col totals"></td>
          @foreach($aLstDays as $d=>$w)
          <td class="totals  ">{{$totalOtas}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="tdSpecial td2">Libres</td>
          <td class="first-col"></td>
          @foreach($listDaysOtasTotal as $v)
          <td class="avails  {{($v>0) ? 'number' : ''}}">{{($v>0) ? $v : '-'}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="tdSpecial td2">Ocupadas</td>
          <td class="first-col"></td>
          @foreach($listDaysOtasTotal as $v)
          <td class=" ">{{$totalOtas-$v}}</td>
          @endforeach
        </tr>
      </tbody>
    </table>
  </div>
</div>


@endsection

@section('scripts')
<script type="text/javascript">

  $(document).ready(function () {

  $('.summary').on('keyup', '.editable', function (e) {
    $(this).val($(this).val().replace(/[^\d|^.]/g, ''));
    if (e.keyCode == 13) {
      var id = $(this).data('id');
      var key = $(this).data('key');
      var input = $(this).val();
      $.ajax({
        type: "POST",
        method : "POST",
        url: "/admin/revenue/upd-disponibl",
        data: {_token: "{{ csrf_token() }}",key: key, id: id, input: input},
        success: function (response)
        {
          if (response.status == 'OK') {
            window.show_notif('OK','success','Registro Actualizado');
            if (id == 'pres_n_hab' || id == 'foresc_n_hab'){
              var obj = $('#'+id+'_percent_'+key);
              if (input>0) obj.text(Math.round((input/obj.data('total'))*100));
              else obj.text(0);
            }
          } else {
                window.show_notif('Error','danger',response.msg);
          }
        }
      });
    }
  });
  });
</script>
@endsection