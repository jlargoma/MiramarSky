@extends('layouts.admin-master')

@section('title') Vtas X día @endsection

@section('externalScripts')
<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('/assets/css/font-icons.css')}}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('/css/backend/revenue.css')}}" type="text/css"/>

<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{ asset('/js/datePicker01.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function () {
    var sendFormRevenue = function(){
      $('.daterange02').remove()
      $('#revenu_filters').submit();
    }
    $('.daterange02').on('change',function (event) {
      var date = $(this).val();

      var arrayDates = date.split('-');
      var res1 = arrayDates[0].replace("Abr", "Apr");
      var date1 = new Date(res1);
      var start = date1.getTime();
      var res2 = arrayDates[1].replace("Abr", "Apr");
      var date2 = new Date(res2);

      $('#start').val(date1.yyyymmmdd());
      $('#finish').val(date2.yyyymmmdd());
      sendFormRevenue();
    });
    $('.tabChannels').on('click',function (event) {
      
      $('#ch_sel').val($(this).data('k'));
       sendFormRevenue();
    });
    
  });
</script>


@endsection

@section('content')
<div class="row">

    <div class="col-md-4 col-xs-12 mt-3em">
        <div class="col-xs-12">
      <div class="text-center">
        @include('backend.revenue.vtas-dia._actions')
      </div>
          
        </div>
    </div>
    <div class="col-md-4 col-xs-12">
  <div class="row bg-white">
      <div class="col-md-6 col-xs-6 text-right">
        <h2 class="text-center">
          Ventas por día
        </h2>
      </div>
      <div class="col-md-4 col-xs-4 sm-padding-10" style="padding: 10px">
        @include('backend.years._selector')
      </div>
      <div class="col-md-12 mb-1em text-center">
        @include('backend.revenue._buttons')
       </div>
      <div class="col-md-12 mb-1em text-center">
        
       </div>
    </div>
  </div>
    <div class="col-md-4 col-xs-12 mt-3em">
    </div>
  
</div>
<div class=" contenedor ">
  <div class="row">
      <div class="col-md-3 col-xs-4 text-left">
        @include('backend.revenue.vtas-dia._filters')
      </div>
      <div class="col-md-9 col-xs-8">
        @include('backend.revenue.vtas-dia._summary')
        <div class="cleafix col-xs-12">
        @include('backend.revenue.vtas-dia._tableItems')
        </div>
      </div>
      
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

 $(document).ready(function () {
    var sendFormRevenue = function(){
      $('.daterange02').remove()
      $('#revenu_filters').submit();
    } 
        
});

    
</script>

<style>
@media only screen and (max-width: 767px) {
  .summary{
    padding: 7px 0;
  }
  .filter-field {
    width: 44%;
    overflow: hidden;
  }
  .filter-field .form-control{
    width: 98% !important;
  }
  
  .table-responsive.summary .table{
    overflow: hidden;
  }
  
  th.first-col, td.first-col {
    padding-left: 5em !important;
    width: 0;
  }
  th.static{
    padding: 0px !important;
    border: none !important;
    height: 28px;
    margin: 0;
    width: 5em;
  }
  td.static{
    background-color: #FFF;
    width: 5em;
  }
}
</style>
@endsection