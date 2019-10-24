<?php $agencyID = $book->agency; ?>
@if (Auth::user()->role == "limpieza")
  <option value="{{$agencyID}}"  {{ $agencyID == $i ? 'selected' : '' }}>
      <?php echo $book->getAgency($agencyID) ?>
  </option>
@else

<?php if ( Auth::user()->role != "agente"): ?>
<?php 
  $agencias =  $book::listAgency();
?>
  @foreach($agencias as $k=>$v)
  <option value="{{$k}}" <?php if ($agencyID == $k) echo 'selected'; ?>>{{$v}}</option>
  @endforeach
<?php else: ?>
  <option value="<?php echo Auth::user()->agent->agency_id ?>" selected>
    <?php echo \App\Book::getAgency(Auth::user()->agent->agency_id) ?>
  </option>
<?php endif ?>

@endif
