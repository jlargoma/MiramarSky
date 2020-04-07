<?php   use \Carbon\Carbon;
setlocale( LC_TIME , "ES" );
setlocale( LC_TIME , "es_ES" );

$roomID = isset($room->id) ? $room->id : 'all';
?>
<div class="row bg-white">
	<div class="container">
		<div class="col-md-4 col-md-offset-1 col-xs-12">
			<h2 class="text-center">HOJA DE GASTOS</h2>
		</div>
		<div class="col-md-2 col-xs-12" style="padding: 10px;">
                  <?php 
                  $rooms = \App\Rooms::where( 'state' , 1 )->orderBy( 'nameRoom')->get();
                  ?>
                  <select class="form-control full-width minimal selectorRoom">
                      <option <?php if ( $roomID == 'all' ) { echo "selected";}?> value="all"> TODAS </option>
                      <?php foreach ($rooms as $roomX): ?>
                      <option value="<?php echo $roomX->id ?>" <?php echo ($roomX->id == $roomID) ? 'selected' : '';?> >
                      <?php echo $roomX->nameRoom  ?>
                      </option>
                      <?php endforeach ?>
                  </select>
		</div>
	</div>

</div>
<div class="col-md-12 col-xs-12 bg-white" id="containerTableExpensesByRoom">
	@include('backend.paymentspro.gastos._tableExpensesByRoom', ['gastos' => $gastos, 'room' => $room])
</div>
<script type="text/javascript">
	$(document).ready(function () {
      $('.selectorRoom').change(function (event) {
        $.get('/admin/gastos/getHojaGastosByRoom/' + {{ $year->year }} + '/' + $(this).val(), function (data) {

          $('.contentExpencesByRoom').empty().append(data);
        });
      });
      $('.deleteExpenseByRoom').click(function (event) {
        var id = $(this).attr('data-id');
        var room = $(".selectorRoom").val();
        var year = '{{ $year->year }}';
        $.get('/admin/gastos/delete/' + id, function (data) {console.log(data)});
        $('#containerTableExpensesByRoom').empty().load('/admin/gastos/containerTableExpensesByRoom/' + year + "/" + room);
      });
    });

</script>