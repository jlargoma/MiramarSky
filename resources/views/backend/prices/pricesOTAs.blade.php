@extends('layouts.admin-master')

@section('title') Porcentajes OTAs @endsection

@section('externalScripts') 
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
      integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css"/>
<style>
  thead th{
    text-align: center;
  }
  td.aptos{
    width: 10em;
    text-align: left;
  }
  td.inputs{
    width: 7em;
    text-align: center;
  }
  input.form-control.changeVal {
    width: 6em;
    border: 1px solid #c3c3c3;
    text-align: center;
    margin: 4px auto;
}
</style>
@endsection
@section('content')
<style>
 
</style>

<div class="container-fluid padding-25 sm-padding-10">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-3 col-xs-12">
          <h3>Porcentajes OTAs:</h3>
        </div>
        <div class="col-xs-12 col-md-7">
          <a class="text-white btn btn-md btn-primary" href="{{route('precios.base')}}">PRECIO BASE X TEMP</a>
          <a class="text-white btn btn-md btn-primary" href="{{route('channel.price.cal')}}">UNITARIA</a>
          <a class="text-white btn btn-md btn-primary" href="{{route('channel.price.site')}}">EDIFICIO</a>
          <a class="text-white btn btn-md btn-primary" href="{{route('channel.promotions')}}">PROMOCIONES</a>
          <button class="btn btn-md btn-primary active"  disabled>PORCENTAJES OTAs</button>
        </div>
        <div class="col-md-2 row"></div>
      </div>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Apto</th>
          @if($agencies)
          @foreach($agencies as $name=>$id)
          <th colspan="2">{{$name}}</th>
          @endforeach
          @endif
        </tr>
        <tr>
          <th></th>
          @if($agencies)
          @foreach($agencies as $name=>$id)
          <th>€</th>
          <th>%</th>
          @endforeach
          @endif
        </tr>
      </thead>
      <tbody>
        @if($rooms)
        @foreach($rooms as $k=>$n)
        <tr>
          <td class="aptos">{{$n}}</td>
          @if($agencies)
          @foreach($agencies as $name=>$id)
          <td class="inputs">
            <input type="number" class="form-control changeVal" data-room="{{$k}}" data-ota="{{$id}}" data-type="f" value="{{$aPricesOta[$k.$id]['f']}}">
          </td>
          <td class="inputs">
            <input type="number" class="form-control changeVal" data-room="{{$k}}" data-ota="{{$id}}" data-type="p" value="{{$aPricesOta[$k.$id]['p']}}">
          </td>
          @endforeach
          @endif
        </tr>
        @endforeach
        @endif
      </tbody>
    </table>
  </div>
  <div class="row"></div>
</div>


@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<script type="text/javascript" src="/js/datePicker01.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  /********************************************************/
  var updValues = function(objt){
    var url = "{{route('precios.pricesOTAs.upd')}}";
    $.ajax({
      type: "POST",
      url: url,
      data: {
        _token: "{{ csrf_token() }}",
        room: objt.data('room'),
        ota: objt.data('ota'),
        val: objt.val(),
        type: objt.data('type')
      },
      success: function (response)
      {
        if (response.status == 'OK') {
          window.show_notif('OK','success',response.msg);
        } else {
          window.show_notif('Error','danger',response.msg);
        }
      }
    });
  }
      
  $('.changeVal').on('keyup', function(e){
//    if (e.keyCode == 13) {
      updValues($(this));
//    }
  });
  
  /********************************************************/
});
</script>
@endsection