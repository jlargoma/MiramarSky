<div class="col-xs-12">
  <div class="row" style="font-size: 1.3em;">
    <div class="col-xs-12  mb-1em" >
      <div class="row">
        <div class="col-md-4"> Nombre: <b>{{ucfirst($name)}}</b></div>
        <div class="col-md-5"> <b>{{$email}}</b></div>
        <div class="col-md-3"> Tel.: <b><a href="tel:+{{$phone}}">{{$phone}}</a></b></div>
      </div>
      
    </div>
    <div class="col-xs-6 col-md-6 mb-1em">
      Fechas: <b><?php echo convertDateToShow_text($start) . ' - ' . convertDateToShow_text($finish); ?></b>
    </div>
    <div class="col-xs-6 col-md-3 mb-1em text-center">
      <b>{{$nigths}} </b>noches
    </div>
    <div class="col-xs-6 col-md-3 mb-1em text-center">
      <b> <?php echo $pax ?> <?php if ($pax == 1): ?>Per<?php else: ?>Pers <?php endif ?>	</b>
    </div>
  </div>

  <div class="line" style="margin-bottom: 10px;"></div>


  <input type="hidden" id="calc_username" value="{{$name}}">
  <input type="hidden" id="calc_start" value="{{$start}}">
  <input type="hidden" id="calc_finish" value="{{$finish}}">
  <input type="hidden" id="calc_pax" value="{{$pax}}">

  <div class="table-responsive" style="overflow-y: hidden;">
    <table class="table table-mobile">
      <thead>
        <tr class ="text-center bg-success text-white">
          @if($isMobile)
          <th class="th-bookings static" style="width: 130px; padding: 14px !important;background-color: #10cfbd;">  
            Apto.
          </th>
          <th class="th-bookings first-col" style="padding-left: 130px!important"></th>
          @else
          <th class="th-bookings static" style="background-color: #10cfbd;">  
            Apto.
          </th>
          <th class="th-bookings first-col"></th> 
          @endif
          <th class="th-bookings text-center th-2">Precio</th>
          <th class="th-bookings text-center th-2">Disp.</th>
          <th class="th-bookings text-center th-2">&nbsp;</th>
          <th class="th-bookings text-center th-2">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rooms as $room)
        <tr >
          @if($isMobile)
          <td class ="text-left static" style=" width: 130px;color: black;overflow-x: scroll;    padding: 21px 6px !important; ">  
            @else
          <td class ="text-left" style="position: relative; padding: 7px !important;">  
            @endif
            {{$room['tiposApto']}}
          </td>
          @if($isMobile)
          <td class="text-center first-col" style="height: 2em;padding: 5px; padding-left: 130px!important">
            @else
          <td class="text-center">
            @endif
          </td>
          <td class="text-center"><b >{{moneda($room['price'])}}</b></td>
          <td class="text-center">{{$room['avail']}}</td>
          <td> 
            <?php if (Auth::user()->role != "agente"): ?>
              <button 
                type="button" 
                class="btn btn-success text-white calc_createNew"
                data-room="{{$room['roomID']}}"
                data-luxury="{{$room['luxury']}}"
                >RESERVAR</button>
              <?php endif; ?>
          </td>
          <td>
            @if (isset($urlGH))
            <a href="{{$urlGH}}" target="_blank" class="">GHotels</a>
            @endif
          </td>
        </tr>
          @if($room['msg'])
          <tr>
            <td colspan="3"><p class="text-danger">{{$room['msg']}}</p></td>
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
