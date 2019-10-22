<?php $agencyID = $book->agency; ?>
@if (Auth::user()->role == "limpieza")
  <option value="{{$agencyID}}"  {{ $agencyID == $i ? 'selected' : '' }}>
      <?php echo $book->getAgency($agencyID) ?>
  </option>
@else
<?php 
$agencias =  $book::listAgency();
?>
  @foreach($agencias as $k=>$v)
  <option value="{{$k}}" <?php if ($agencyID == $k) echo 'selected'; ?>>{{$v}}</option>
  @endforeach
@endif
