<?php 
if ($uRole == 'propietario'){
  $lstRooms = \App\Rooms::where('owned', $room->user->id)->orderBy('nameRoom', 'ASC')->get();
} else {
  $lstRooms = \App\Rooms::where('state', 1)->orderBy('nameRoom', 'ASC')->get();
}
?>



<div class="row">
  <div class="col-md-6 col-sm-12 text-center">
    
    
    <?php 
      if ($uRole == 'propietario'):
        $fecha = $startYear->copy(); 
    ?>
      <h2 class="text-center">
          <b>Planning de reservas</b> {{ $year->year }}-{{ $year->year + 1 }}
      </h2>
    <?php else: ?>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h2 class="text-center"><b>Planning de reservas</b></h2>
        </div>
        <div class="col-xs-6" style="padding: 15px;">  
            @include('backend.years._selector')
        </div> 
    </div>
    <?php endif ?>
  </div>
  <div class="col-md-6 col-sm-12 text-center">
  <?php if (count($lstRooms) == 1): ?>
      <h1 class="text-complete font-w800"><?php echo strtoupper($room->user->name) ?> <?php echo strtoupper($room->nameRoom) ?></h1>
  <?php else: ?>
      <select class="form-control full-width minimal selectorRoom" style="    max-width: 320px;">
        <?php foreach ($lstRooms as $roomX): ?>
          <option value="<?php echo $roomX->nameRoom ?>" {{ $roomX->id == $room->id ? 'selected' : '' }} >
            <?php echo substr($roomX->nameRoom . " - " . $roomX->name, 0, 15) ?>
          </option>
        <?php endforeach ?>
      </select>
  <?php endif ?>
  </div>
</div>