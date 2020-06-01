@extends('layouts.admin-master')

@section('title') Precios de apartamentos @endsection

@section('externalScripts') 
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/selectable-table-plugin@latest/selectable.table.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('content')
<div class="container-fluid padding-25 sm-padding-10">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-4 col-xs-12">
          <h3>Listado de Precios:</h3>
        </div>
        <div class="col-xs-12 col-md-4">
          <a class="text-white btn btn-md btn-primary" href="{{route('precios.base')}}">PRECIO BASE X TEMP</a>
          <a class="text-white btn btn-md btn-primary" href="{{route('channel.price.cal')}}">UNITARIA</a>
          <button class="btn btn-md btn-primary active"  disabled>EDIFICIO</button>
        </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3 col-xs-12">
          <form action="{{route('Wubook.sendPrices')}}" method="POST">
            <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" value="{{$month}}" name="month">
            <input type="hidden" value="{{$year}}" name="year">
            <button class="btn btn-primary">Enviar Precios a WuBook</button>
          </form>
        </div>
        <div class="col-md-6 col-xs-12">
          <div class="select-month">
            <a href="/admin/channel-manager/price-site/1/{{$prev}}"><i class="fa fa-arrow-left"></i> </a>
              <span>{{$current}}</span>
            <a href="/admin/channel-manager/price-site/1/{{$next}}"><i class="fa fa-arrow-right"></i> </a>
          </div>
        </div>
        <div class="col-md-3 col-xs-12">
          <div class="col-xs-6">
            <label style="font-size: 1.15em;font-weight: 800;text-align: right;">Seleccionar Columna: </label>
          </div>
          <div class="col-xs-6">
            <select id="sel_by_column" class="form-control">
              <option value="1">Precios</option>
              <option value="2">Min. Ocupación</option>
            </select>
          </div>
        </div>
      </div>
      
                                                                                                                 
      <div class="table-responsive table-resumen-content">
        <table class="table table-resumen" id="table_prices">
          <thead>
            <tr>
              <th class=" white">&nbsp;</th>
              @foreach($aMonth as $k=>$item)
              <th class="month" colspan="{{$item['colspan']}}">{{$item['text']}}</th>
              @endforeach
            </tr>
            <tr>
              <th class=" white">&nbsp;</th>
              @foreach($days as $k=>$day)
              <th class="day w select_column" data-day="{{$k}}">{{$dw[$day['w']]}}<br>{{$day['day']}}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
        @foreach($rooms as $kRoom=>$item)
        <tr class="room-name">
          <td  class="room-name static static-header" colspan="9" >
            <div class="col-2">
              <h3 class="white">{{$item['tit']}}</h3>
            </div>
            <div class="col-2">
              <table class="table-prices">
                <tr>
                   <td><span class="price-booking">{{$item['price_booking']}}</span></td>
                   <td><span class="price-airbnb">{{$item['price_airbnb']}}</span></td>
                   <td><span class="price-expedia">{{$item['price_expedia']}}</span></td>
                 </tr>
               </table>
            </div>
          </td>
          <td colspan="{{count($days)+1}}"></td>
        </tr>

        <tr>
          <th>Precio €</th>
          <?php $priceLst = $item['data']['priceLst']; ?>
          @foreach($days as $k=>$day)
            @if(isset($priceLst[$k]))
              <td class="day tPriceEdit price_{{$k}}" data-id="{{$kRoom.'@'.$k}}">{{$priceLst[$k][0]}}</td>
            @else
              <td class="day tPriceEdit price_{{$k}}" data-id="{{$kRoom.'@'.$k}}">-</td>
            @endif
          @endforeach
        </tr>
        <tr>
          <th>Min. Ocup.</th>
          <?php $priceLst = $item['data']['priceLst']; ?>
          @foreach($days as $k=>$day)
            @if(isset($priceLst[$k]))
              <td class="day tMinEdit min_st_{{$k}}" data-id="{{$kRoom.'@'.$k}}">{{$priceLst[$k][1]}}</td>
            @else
              <td class="day tPriceEdit  min_st_{{$k}}" data-id="{{$kRoom.'@'.$k}}">-</td>
            @endif
          @endforeach
        </tr>
        <tr>
          <th>Disponibilidad</th>
           <?php 
           $avail = $item['data']['avail']; 
           $t_rooms = $item['data']['t_rooms'];
           ?>
          @foreach($days as $k=>$day)
          <td class="day <?php if ($avail[$k]==0) echo 'red'; ?>">{{$avail[$k]}}</td>
          @endforeach
        </tr>
        <tr>
          <th>Reservado</th>
          @foreach($days as $k=>$day)
          <td class="day">{{$t_rooms-$avail[$k]}}</td>
          @endforeach
        </tr>
        @endforeach
        </tbody>
      </table>
      </div>
      <div class="calendar-blok">
      <div id='calendar'></div>   
      </div>
    </div>
    <div class="col-md-4">
      
    </div>
  </div>
</div>


@endsection

@section('scripts')


<script type="text/javascript">
$(document).ready(function () {
  const hTable = $('#table_prices').find('tbody');
     
     
      function edit (currentElement) {
        var input = $('<input>', {class: "only-numbers"})
          .val(currentElement.html())
        currentElement.html(input)
        input.focus(); 
      }

      $('#table_prices').find('thead').find('th').click( function () {
        var that = $(this);
        console.log(that);
        if (that.hasClass('select_column')){
          var date = that.data('day');
          var type = $('#sel_by_column').val();
//          var type = $("input[name='sel_by_column']:checked").val();
          console.log(type);
          if (type == 1){
            $('.tPriceEdit.price_'+date).trigger('click');
          }
          if (type == 2){
            $('.tMinEdit.min_st_'+date).trigger('click');
          }
          
        }
      });
      hTable.find('td').click( function () {
        var that = $(this);
        /*** Edit prices       ****/
        if (that.hasClass('tPriceEdit')){
          //Clear other input
          hTable.find('.tMinEdit').each(function() {
            var value = $(this).find('input').val();
            $(this).text(value).removeClass('tSelect');
          });
          
          //prepare input
          if (that.hasClass('tSelect')){
            that.removeClass('tSelect')
            that.text(that.data('val'));
          } else {
            that.data('val',that.text());
            that.addClass('tSelect')
            edit($(this));
          }
        }
        /*** Edit Min. Ocup       ****/
        if (that.hasClass('tMinEdit')){
          //Clear other input
          hTable.find('.tPriceEdit').each(function() {
            var value = $(this).find('input').val();
            $(this).text(value).removeClass('tSelect');
          });
          
          //prepare input
          if (that.hasClass('tSelect')){
            that.removeClass('tSelect')
            that.text(that.data('val'));
          } else {
            that.data('val',that.text());
            that.addClass('tSelect')
            edit($(this));
          }
        
        }
      });

      hTable.on('keyup','.tSelect',function (e) {
        if (e.keyCode == 13) {
          var data = new Array();
          var value = 0;
          var last = null;
          hTable.find('.tSelect').each(function() {
            value = $(this).find('input').val();
            data.push($(this).data('id'));
            $(this).text(value).removeClass('tSelect');
            last = $(this);
          });
          var type = 'minDay';
          if (last.hasClass('tPriceEdit'))  type = 'price';
          updValues(data,value,type);
        } else {
          hTable.find('.tSelect').find('input').val($(this).find('input').val());
        }
      });
      
      var updValues = function(data,value,type){
        var url = "{{route('channel.price.site.upd')}}";
        $.ajax({
          type: "POST",
          url: url,
          data: {_token: "{{ csrf_token() }}",items: data, val: value,type:type},
          success: function (response)
          {
            if (response.status == 'OK') {
              hTable.find('.tSelect').each(function() {
                $(this).text(value).removeClass('tSelect');
              });
              window.show_notif('OK','success',response.msg);
            } else {
            window.show_notif('Error','danger',response.msg);
            }
            console.log(data.msg); // show response from the php script.
          }
        });
    
      }
        
        
        
    $('#select_site').on('change', function(){
     location.href = '/admin/channel-manager/price-site/'+$(this).val()+'/{{$month}}/{{$year}}';
    });
        
    $("#table_prices").on('keydown','.only-numbers',function (e) {
      console.log(e.keyCode);
      // Allow: backspace, delete, tab, escape, enter and .
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 188,109]) !== -1 ||
              // Allow: home, end, left, right, down, up
                      (e.keyCode >= 35 && e.keyCode <= 40)) {
        // let it happen, don't do anything
        return;
      }
      // Ensure that it is a number and stop the keypress
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
      }
    });
});
</script>
<style>
  .select-month{
    font-size: 2em;
    font-weight: 800;
    text-align: center;
  }
  td.static {
    min-width: 12em;
    border: none !important;
  }
  td.day {
    min-width: 60px;
    border-left: 1px solid #000;
    padding: 4px !important;
    text-align: center;
  }
  td.month {
    border-left: 1px solid #000;
    padding: 4px !important;
    font-weight: 600;
  }
  td.day.w {
    font-weight: 600;
  }
  th.room-name{
    font-size: 1.7em;
  }
  .room-name table.table-prices {
    padding-top: 10px;
    display: block;
  }
  td.day.red {
    background-color: #e49f9f;
    color: #fff;
  }
  .day.tPriceEdit,
  .day.tMinEdit{
    cursor: pointer;
    min-width: 65px;
  }
  .day.tPriceEdit.tSelect{
    background-color: yellow;
  }
  .day.tMinEdit.tSelect{
    background-color: #FF9800;
  }
  .day.tPriceEdit.tSelect input[type="number"],
  .day.tMinEdit.tSelect input[type="number"] {
    width: 55px;
    padding: 4px 2px;
    text-align: center;
    border: none;
    font-weight: 600;
  }

  #table_prices td.month {
    text-align: center;
  }
  #table_prices.table-resumen .static.white{
    background-color: #fff !important;
    height: 37px;
    min-width: 131px;
  }

  #table_prices.table-resumen th.room-name.static {
    min-height: 82px;
    background-color: #fff !important;
    border: none !important;
  }
  #table_prices.table-resumen .static{
    height: 37px;
    min-width: 138px;
    text-align: left;
    background-color: #f9f9f9 !important;
    overflow: hidden;
    margin: 0px auto;
    border-right: 2px solid #000;
    padding: 4px !important;
  }

  
  #table_prices.table-resumen tr.room-name {
    height: 65px;
  }

  #table_prices.table-resumen tr.room-name .name{
    font-size: 1.7em;
    padding: 13px;
  }
    
    
  #table_prices.table-resumen tr.room-name td {
   background-color: #2b5d9b !important;
    color: #fff;
    min-height: 65px;
    border: none !important;
    min-width: 6em;
    width: 19em;
  }
  
  #table_prices.table-resumen .room-name table.table-prices td {
    text-align: left;
  }
  
  #table_prices.table-resumen tr.room-name td .col-md-8 {
    min-width: 4em;
    margin-top: 10px;
    padding-left: 7px;
    font-size: 23px;
  }
  
  td.room-name.static.static-header {
    width: 57em !important;
}

.table-responsive input{
  width: 55px;
}
.table-resumen-content{
  max-height: 50em;
  position: relative;
  overflow: scroll;
}
table#table_prices .col-2{
  width: 48%;
  float: left;
}
table#table_prices  thead th {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  top: 0;
  background: #2b5d9b;
  color: #FFF;
  z-index: 10;
  text-align: center;
}

table#table_prices  tbody th {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  left: 0;
  background: #FFF;
  border-right: 1px solid #CCC;
  text-align: center;
}
.select_column{
  cursor: pointer;
}
.day.tPriceEdit{
    cursor: pointer;
    min-width: 65px;
    font-weight: 800;
    color: #000;
}

</style>
@endsection