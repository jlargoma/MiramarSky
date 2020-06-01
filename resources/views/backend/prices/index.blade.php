@extends('layouts.admin-master')

@section('title') Precios de apartamentos @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

<style>
    .Alta{
        background: #f0513c;
    }
    .Media{
        background-color: #127bbd;
    }
    .Baja{
        background-color: #91b85d;
    }

    .Premium{
        background-color: #ff00b1;
        color: white;
    }

    span.Alta{
        background-color: transparent!important;
        color: #f0513c;
        text-transform: uppercase;
    }
    span.Media{
        background-color: transparent!important;
        color: #127bbd;
        text-transform: uppercase;
    }
    span.Baja{
        background-color: transparent!important;
        color: #91b85d;
        text-transform: uppercase;
    }
    span.Premium{
        background-color: transparent!important;
        color: #ff00b1;
        text-transform: uppercase;
    }
    .extras{
        background-color: rgb(150,150,150);
    }
    .btn-inline{
      float:right; margin: 0 5px
    }
    .pg-close{
      font-size: 40px!important;color: black!important
    }
    input.datepicker2 {
        padding: 6px;
        border: 1px solid #000;
        text-align: center;
        color: #000;
    }
    i.fa.fa-trash.deleteSegment {
        color: red;
        font-size: 11px;
        cursor: pointer;
    }
</style>

<div class="container-fluid padding-25 sm-padding-10">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-4 col-xs-12">
          <h3>Precios de Temporadas:</h3>
        </div>
        <div class="col-xs-12 col-md-4">
          <button class="btn btn-md btn-primary active"  disabled>PRECIO BASE X TEMP</button>
          <a class="text-white btn btn-md btn-primary" href="{{route('channel.price.cal')}}">UNITARIA</a>
          <a class="text-white btn btn-md btn-primary" href="{{route('channel.price.site')}}">EDIFICIO</a>
        </div>
        <div class="col-md-4">
           @include('backend.years._selector', ['minimal' => true])
        </div>
      </div>
    </div>
  </div>
  @if (Auth::user()->email == "jlargo@mksport.es")
  <div class="col-md-12">
    <form action="{{route('precios.prepare-crom')}}" method="post">
      @if (\Session::has('sent'))
    <p class="alert alert-success">{!! \Session::get('sent') !!}</p>
    @endif
      <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
      <button class="btn btn-success" title="{{$sendDataInfo}}">Enviar precios a OTAs</button>
    </form>
  </div>
  @endif
  
  <div class="row">
    @include('backend.prices.blocks._table_types')
 </div>
  
  <div class="row">
    <div class="col-md-5">
    @include('backend.prices.blocks._seasons')
    </div>
    <div class="col-md-7">
    @include('backend.seasons.calendar')
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-6">
      </div>
      <div class="col-md-6">
    @include('backend.prices.blocks.dias-min')
      </div>
    </div>
    
    </div>
 </div>
  
  </div>

  @include('backend.prices.blocks._modals')

@endsection

@section('scripts')
  <script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
  <script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
  <script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
  <script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
  <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
  <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>

  <script type="text/javascript">
      $(document).ready(function() {

          $('.new-prices').click(function(event) {
              $.get('/admin/precios/new', function(data) {
                  $('#content-prices').empty().append(data);
              });
          });
          $('.new-special-prices').click(function(event) {
              $.get('/admin/precios/newSpecial', function(data) {
                  $('#content-prices').empty().append(data);
              });
          });

          $('.editable').change(function(event) {
              var id = $(this).attr('data-id');               
              var price = $('.price-'+id).val();
              var cost  = $('.cost-'+id).val();

              $.get('precios/update', {  id: id, price: price,cost: cost}, function(resp) {

                if (resp == 'OK') {
                  window.show_notif('Registro modificado','success','');
                } else {
                  window.show_notif(resp,'danger','');
                }

                  // alert(data);
//                    window.location.reload();
              });

          });
        $('.updateSeason').click(function (event) {
          var id = $(this).attr('data-id');
          $.get('/admin/temporadas/update/' + id, function (data) {
            $('#contentSeason').empty().append(data);
          });
        });


/********************************************************/
    $(".datepicker2").datepicker();

    $('#defineSeason').on('submit',function(event){
      event.preventDefault();
       $.post("{{ route('years.change.month') }}", $(this).serialize()).done(function (resp) {
         if (resp == 'OK') {
          window.show_notif('Registro modificado','success','');
        } else {
          window.show_notif(resp,'danger','');
        }
      });
    });

    $(".s_years").on('change',function() {
      $.get("{{ route('years.get') }}", {id: $(this).val()}).done(function (resp) {
        $('#year_start').val(resp[0]);
        $('#year_end').val(resp[1]);
      });
    });
      
      
      /********************************************************/

      
  });
</script>
@endsection