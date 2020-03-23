@extends('layouts.admin-master')

@section('title') Precios de apartamentos @endsection

@section('externalScripts') 
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/selectable-table-plugin@latest/selectable.table.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<script src="path/to/selectable.min.js"></script>-->
<script src="/vendors/selectable/selectable.table.js"></script>

@endsection

@section('content')
<div class="container-fluid padding-25 sm-padding-10">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-6">
          <h3>Listado de Precios:</h3>
        </div>
        <div class="col-md-6">
         
        </div>
      </div>
      
                                                                                                                 
      <a href="/admin/channel-manager/price-site/1/{{$prev}}"><i class="fa fa-arrow-circle-left"></i> </a>
      <span>{{$current}}</span>
      <a href="/admin/channel-manager/price-site/1/{{$next}}"><i class="fa fa-arrow-circle-right"></i> </a>
      <div class="table-responsive">
        <table class="table table-resumen" id="table_prices">
        <tr>
          <td class="static white">&nbsp;</td>
          <td class="first-col">&nbsp;</td>
          @foreach($aMonth as $k=>$item)
          <td class="month" colspan="{{$item['colspan']}}">{{$item['text']}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="static white">&nbsp;</td>
          <td class="first-col">&nbsp;</td>
          @foreach($days as $k=>$day)
          <td class="day w">{{$dw[$day['w']]}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="static white">&nbsp;</td>
          <td class="first-col">&nbsp;</td>
          @foreach($days as $k=>$day)
          <td class="day">{{$day['day']}}</td>
          @endforeach
        </tr>
        @foreach($rooms as $kRoom=>$item)
        <tr class="room-name">
          <td  class="room-name static static-header" colspan="16" >
            <div class="row">
              <div class="col-md-7">
                {{$item['tit']}}
              </div>
              <div class="col-md-5">
                <table class="table-prices">
                  <tr>
                     <td><span class="price-booking">{{$item['price_booking']}}</span></td>
                     <td><span class="price-airbnb">{{$item['price_airbnb']}}</span></td>
                     <td><span class="price-expedia">{{$item['price_expedia']}}</span></td>
                   </tr>
                 </table>
              </div>
            </div>
          </td>
          <td colspan="{{count($days)+1}}"></td>
        </tr>

        <tr>
          <th class="static">Precio €</th>
          <td class="first-col">&nbsp;</td>
          <?php $priceLst = $item['data']['priceLst']; ?>
          @foreach($days as $k=>$day)
            @if(isset($priceLst[$k]))
              <td class="day tPriceEdit" data-id="{{$kRoom.'@'.$k}}">{{$priceLst[$k][0]}}</td>
            @else
              <td class="day tPriceEdit" data-id="{{$kRoom.'@'.$k}}">-</td>
            @endif
          @endforeach
        </tr>
        <tr>
          <th class="static">Min. Ocup.</th>
          <td class="first-col">&nbsp;</td>
          <?php $priceLst = $item['data']['priceLst']; ?>
          @foreach($days as $k=>$day)
            @if(isset($priceLst[$k]))
              <td class="day tMinEdit" data-id="{{$kRoom.'@'.$k}}">{{$priceLst[$k][1]}}</td>
            @else
              <td class="day tPriceEdit" data-id="{{$kRoom.'@'.$k}}">-</td>
            @endif
          @endforeach
        </tr>
        <tr>
          <th class="static">Disponibilidad</th>
          <td class="first-col">&nbsp;</td>
           <?php 
           $avail = $item['data']['avail']; 
           $t_rooms = $item['data']['t_rooms'];
           ?>
          @foreach($days as $k=>$day)
          <td class="day <?php if ($avail[$k]==0) echo 'red'; ?>">{{$avail[$k]}}</td>
          @endforeach
        </tr>
        <tr>
          <th class="static">Reservado</th>
          <td class="first-col">&nbsp;</td>
          @foreach($days as $k=>$day)
          <td class="day">{{$t_rooms-$avail[$k]}}</td>
          @endforeach
        </tr>
        @endforeach
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
        var input = $('<input>', {type: "number"})
          .val(currentElement.html())
        currentElement.html(input)
        input.focus(); 
      }

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
        
});
</script>
<style>
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
    height: 5em;
  }

  #table_prices.table-resumen tr.room-name td {
   background-color: #2b5d9b !important;
    color: #fff;
    min-height: 58px;
    border: none !important;
    min-width: 6em;
    width: 19em;
  }
  
  #table_prices.table-resumen .room-name table.table-prices td {
    text-align: left;
  }
  
  #table_prices.table-resumen tr.room-name td .col-md-7 {
    min-width: 4em;
    margin-top: 10px;
    padding-left: 7px;
    font-size: 23px;
  }
  
  td.room-name.static.static-header {
    width: 76em !important;
}

</style>
@endsection