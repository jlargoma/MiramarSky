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
        <div class="col-md-3 col-xs-12">
          <h3>Precios de Temporadas:</h3>
        </div>
        <div class="col-xs-12 col-md-7">
          <button class="btn btn-md btn-primary active"  disabled>PRECIO BASE X TEMP</button>
          <a class="text-white btn btn-md btn-primary" href="{{route('channel.price.cal')}}">UNITARIA</a>
          <a class="text-white btn btn-md btn-primary" href="{{route('channel.price.site')}}">EDIFICIO</a>
          @include('backend.zodomus.sendToWubook')
          
        </div>
        <div class="col-md-2 row">
          @include('backend.years._selector', ['minimal' => true])
        </div>
      </div>
    </div>
  </div>
  @if (Auth::user()->email == "jlargo@mksport.es")
  <div class="col-md-12">
    <form action="{{route('precios.prepare-cron')}}" method="post" class="inline">
      <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
      <button class="btn btn-success" title="{{$sendDataInfo}}">Sincr. precios OTAs</button>
    </form>
    <form action="{{route('precios.prepare-cron-minStay')}}" method="post" class="inline">
      <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
      <button class="btn btn-success" title="{{$sendDataInfo_minStay}}">Sincr. Estadías Mínimas OTAs</button>
    </form>
    <small>(Sincronizar toda la temporada)</small>
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
        @include('backend.prices.blocks.extr-paxs')
        @include('backend.prices.blocks.extras')
      </div>
      <div class="col-md-6">
        @include('backend.prices.blocks.settings-reservas')
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
        $('.setting-editable').change(function () {
          var code = $(this).attr('data-code');
          var value = $(this).val();
          $.post("{{ route('settings.createUpdate') }}", {code: code, value: value}).done(function (data) {
            var response = jQuery.parseJSON(data);
            window.show_notif(response.status, 'success', response.message)
          }).fail(function (data) {
            var response = jQuery.parseJSON(data);
            window.show_notif(response.status, 'danger', response.message)
          });
        });
        
        $('.extra-editable').change(function (event) {
          var id = $(this).attr('data-id');
          var extraprice = $('.extra-price-' + id).val();
          var extracost = $('.extra-cost-' + id).val();

          $.get('precios/updateExtra', {id: id, extraprice: extraprice, extracost: extracost}, function (data) {
            // alert(data);
            window.location.reload();
          });

        });
        
        $('.deleteSegment').click(function (event) {
          
          if (confirm('Eliminar el Extra '+$(this).data('name')+'?')){
            var data = {
              id: $(this).data('id'),
              _token: "{{csrf_token()}}"
            };
            
            var elemet = $(this).closest('tr');
            
            $.ajax({
                url: "{{route('precios.extr_price.del')}}",
                data: data,
                type: 'DELETE',
                success: function(result) {
                  if (result == 'OK'){
                    window.show_notif('OK','success','Registro Eliminado.');
                    elemet.remove(); 
                  } else{
                    window.show_notif('ERROR','danger','Registro no encontrado');
                  }
                },
                error: function(e){
                  console.log(e);
                  window.show_notif('ERROR','danger','Error de sistema');
                }
            });
          }
        });
      
  });
</script>
@endsection