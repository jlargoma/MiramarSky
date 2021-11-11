<div class="col-xs-12">
  <div class="line" style="margin-bottom: 10px;"></div>
  <input type="hidden" id="calc_username" value="{{$name}}">
  <input type="hidden" id="calc_start" value="{{$start}}">
  <input type="hidden" id="calc_finish" value="{{$finish}}">
  <input type="hidden" id="calc_pax" value="{{$pax}}">
  

  <div class="table-responsive" style="overflow-y: hidden;">
    <table class="table table-resumen table-mobile_cr">
      <thead>
        <tr>
          <td colspan="2">  <h5 class="text-center">Del <b>{{dateMin($start)}}</b> al <b>{{dateMin($finish)}}</b></h5></td>
          <td colspan="8"></td>
        </tr>
        <tr class ="text-center bg-success text-white">
          <th class="th-bookings text-center th-2 static">Disp.</th>
          <th class="th-bookings static-2" >Apto.</th>
          <th class="first-col"></th>
          <th class="th-bookings text-center th-2 BRight">Precio Final</th>
          <th class="th-bookings text-center th-2">Precio Inicial</th>
          <th class="th-bookings text-center th-2">Desc</th>
          <th class="th-bookings text-center th-2">Promo</th>
          <th class="th-bookings text-center th-2">Supl Limp</th>
          <th class="th-bookings text-center th-2">&nbsp;</th>
          <th class="th-bookings text-center th-2">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rooms as $room)
        <tr >
          <td class="static">{{$room['availiable']}}</td>
          <td class="static-2">
            <?php 
            if(isset($otaPrices[$room['channel_group']])):
              $pOta = $otaPrices[$room['channel_group']];
            ?>
            <div class="showOtaPvp"
                 data-booking="{{moneda($pOta['booking'])}}"
                 data-airbnb="{{moneda($pOta['airbnb'])}}"
                 data-expedia="{{moneda($pOta['expedia'])}}"
                 data-google="{{moneda($pOta['google-hotel'])}}"
                 data-benef="{{$pOta['benef']}}%"
                 >
              {{$room['title']}}
            </div>
            <?php
            
            else:
              echo $room['title'];
            endif; 
            ?>
            
          </td>
          <td class="first-col"></td>
          <td class="text-center BRight">
            <?php 
            $finalPrice = '<b>'.moneda($room['pvp_1']+$room['extr_costs']).'</b>';
            if(isset($otaPrices[$room['channel_group']])):
              $pOta = $otaPrices[$room['channel_group']];
            ?>
            <div class="tip">
                <?php echo $finalPrice; ?>
                <table class="table-prices">
                  <tr>
                    <td><span class="price-booking">{{moneda($pOta['booking'])}}</span></td>
                    <td><span class="price-airbnb">{{moneda($pOta['airbnb'])}}</span></td>
                    <td rowspan="2"><span class="benef">{{$pOta['benef']}}%</span></td>
                  </tr>
                  <tr>
                    <td><span class="price-expedia">{{moneda($pOta['expedia'])}}</span></td>
                    <td><span class="price-google">{{moneda($pOta['google-hotel'])}}</span></td>
                  </tr>
                </table>
            </div>
            <?php
            
            else:
              echo $finalPrice;
            endif; 
            ?>
          </td>
          <td class="text-center"><b >{{$room['price']}}</b></td>
          <td class="text-center text-danger"><b ><?php echo '-'.moneda($room['pvp_discount'],false); ?></b></td>
          <td class="text-center text-danger">
              <b >
              <?php $tot_aux = ($room['pvp_promo']+$room['promo_discount_pvp']); ?>
              <?php echo ($tot_aux>0)? '-'.moneda($tot_aux,false) : '--'; ?>
              </b>
               <i class="far fa-eye show_promo" style="color: #000;" aria-hidden="true">
                   <div class="tooltiptext">
                       @if($room['promo_discount_pvp']>0)
               <p class="text-left promo">Promo: <b >{{$room['promo_discount_name']}}</b></p> 
              @endif
              @if($room['pvp_promo']>0)
               <p class="text-left promo">Promo: <b >{{$room['promo_name']}}</b></p> 
              @endif           
                       
               </div>
              </i>
            
          </td>
          <td class="text-center"><b >{{moneda($room['extr_costs'])}}</b></td>
          
          </td>
          <td> 
            <?php if (Auth::user()->role != "agente"): ?>
              <button 
                type="button" 
                class="btn btn-success text-white calc_createNew"
                data-room="{{$room['code']}}"
                data-luxury="{{$room['lux']}}"
                data-info="{{serialize($room)}}"
                >RESERVAR</button>
              <?php endif; ?>
          </td>
          <td>
            @if (isset($urlGH))
            <a href="{{$urlGH}}" target="_blank" class="">GHotels</a>
            @endif
          </td>
        </tr>
          @if($room['minStay']>$nigths)
          <tr>
              <td colspan="2" class="minStay BRight"><p class="text-danger">Estadía mínima {{$room['minStay']}}</p></td>
              <td colspan="6"></td>
          </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  </div>
  
   
  
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $('.btn-back-calculate').click(function (event) {
      $('#content-book-response .back').empty();
      $("#content-book-response .back").hide();
      $("#content-book-response .front").show();
    });
    
//    $('.showOtaPvp').hover(function(event){
//      var obj = $('.boxOtaPvp');
//      var data = $(this)
//      obj.find('.price-booking').text(data.data('booking'));
//      obj.find('.price-airbnb').text(data.data('airbnb'));
//      obj.find('.price-expedia').text(data.data('expedia'));
//      obj.find('.price-google').text(data.data('google'));
//      obj.find('.benef').text(data.data('benef'));
//      obj.show();
//      if (screen.width<768){
//        obj.css('top','auto');
//        obj.css('bottom','3px');
//        obj.css('left', 'auto');
//        obj.css('right', '3px');
//      } else {
//        obj.css('top', (event.screenY));
//      }
//    },
//    function(){
//      $('.boxOtaPvp').hide();
//    });
  
  });
</script>
<style>


div#calcReserv_result {
    background-color: white;
}
.table-resumen.table-mobile_cr th.static,
.table-resumen.table-mobile_cr td.static {
    width: 15px !important;
    text-align: center !important;
    min-width: 42px;
    padding: 10px 0 0px 0px !important;
    min-height: 36px;
}
.table-resumen.table-mobile_cr td.static-2,
.table-resumen.table-mobile_cr th.static-2 {
position: absolute;
    background-color: white;
    border-right: 1px solid #efefef;
    z-index: 9;
    padding: 10px 0 0 6px !important;
    text-align: left;
    overflow-y: hidden;
    min-height: 36px;
    width: 163px;
    left: 48px;
}
td.minStay {
    text-align: left;
    padding: 6px 0 0 11px !important;
}
.table-resumen.table-mobile_cr th.th-bookings.static,
.table-resumen.table-mobile_cr th.th-bookings.static-2 {
    height: 61px;
    background-color: #0fcfbd;
    border: none;
    text-align: center;
    padding: 15px 0 0 0px !important;
}
.table-resumen.table-mobile_cr th.th-bookings.static{
    padding-top: 11px !important;
}
.table-resumen.table-mobile_cr td.first-col,
.table-resumen.table-mobile_cr th.first-col {
    margin-left: 62px;
    display: block;
    height: 36px;
    width: 1px;
    max-width: 1px;
    min-width: 1px;
}
  
.table-resumen.table-mobile_cr td.static-2::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.table-resumen.table-mobile_cr td.static-2 {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
.show_promo .tooltiptext{display: none;}
.show_promo:hover .tooltiptext{
      display: block;
    width: 160px;
    background-color: rgba(0, 0, 0, 0.68);
}
.BRight{
  border-right: 1px solid;
}

.showOtaPvp{cursor: pointer}
.showOtaPvp:hover .boxOtaPvp{
  display: block;
}
.boxOtaPvp{
    display: none;
    position: fixed;
    top: 0;
    left: 15em;
    z-index: 999999;
    background-color: #6a6a6a;
    padding: 3px;
}
.boxOtaPvp table.table-prices { padding: 3px; background-color: #FFF;}
  span.benef{
    padding: 0 3px 0 8px !important;
    color: #ff6b00;
    font-size: 18px;
  }
  div.tip{
    position: relative;
    cursor: pointer;
  }
 div.tip table.table-prices {
   display: none;
   padding:  0px !important;
 }
 div.tip table.table-prices tbody tr td {
    min-width: 75px;
 }
 div.tip:hover table.table-prices {
    display: block;
    bottom: 40px;
    left: 45px;
    top: auto !important;
    position: absolute;
    cursor: default;
    border: 1px solid #c3c3c3;
    background-color: #e1e1e1;
    padding: 2px;
  }
@media (min-width: 1760px){
  .table-resumen.table-mobile_cr td.static-2 {
    width: 238px;
}
}
@media (max-width: 540px){
  div.tip:hover table.table-prices {
    position: fixed;
    background-color: #000000;
    z-index: 99;
    padding: 4px !important;
    bottom: 3px;
    right: 2px;
    left: auto;

  }
}
</style>
