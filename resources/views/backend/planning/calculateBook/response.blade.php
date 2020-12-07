<div class="col-xs-12">
  <div class="line" style="margin-bottom: 10px;"></div>
  <input type="hidden" id="calc_username" value="{{$name}}">
  <input type="hidden" id="calc_start" value="{{$start}}">
  <input type="hidden" id="calc_finish" value="{{$finish}}">
  <input type="hidden" id="calc_pax" value="{{$pax}}">
  
  <div class="table-responsive" style="overflow-y: hidden;">
    <table class="table table-mobile">
      <thead>
        <tr class ="text-center bg-success text-white">
          <th class="th-bookings text-center th-2">Disp.</th>
          <th class="th-bookings">Apto.</th>
          <th class="th-bookings text-center th-2">Precio</th>
          <th class="th-bookings text-center th-2">Desc</th>
          <th class="th-bookings text-center th-2">Promo</th>
          <th class="th-bookings text-center th-2">Supl Limp</th>
          <th class="th-bookings text-center th-2">total</th>
          <th class="th-bookings text-center th-2">&nbsp;</th>
          <th class="th-bookings text-center th-2">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rooms as $room)
        <tr >
          <td class="text-center">{{$room['availiable']}}</td>
          <td class="th-bookings text-left">{{$room['title']}}</td>
          <td class="text-center"><b >{{$room['price']}}</b></td>
          <td class="text-center text-danger"><b ><?php echo '-'.moneda($room['pvp_discount'],false); ?></b></td>
          <td class="text-center text-danger"><b ><?php echo ($room['pvp_promo']>0)? '-'.moneda($room['pvp_promo'],false) : '--'; ?></b></td>
          <td class="text-center"><b >{{moneda($room['extr_costs'])}}</b></td>
          <td class="text-center"><b >{{moneda($room['pvp_1']+$room['extr_costs'])}}</b>
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
            <td colspan="9"><p class="text-danger">Estadía mínima {{$room['minStay']}}</p></td>
          </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="col-md-6">
    <button class="btn btn-danger btn-lg btn-cons  text-white center hvr-grow-shadow btn-back-calculate">VOLVER</button>
  </div>

</div>
<script type="text/javascript">
  $(document).ready(function () {
    $('.btn-back-calculate').click(function (event) {
      $('#content-book-response .back').empty();
      $("#content-book-response .back").hide();
      $("#content-book-response .front").show();
    });
  });
</script>
