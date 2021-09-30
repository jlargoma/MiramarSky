<style>
  .btn-primary{
    background-color: #295d9b!important;
  }
  .btn-sub-menu{
    display: inline-block;
  }
  .btn-sub-menu .active{
    background-color: #6d5cae;
    box-shadow: 1px 2px 1px #51457f;
    border: 1px solid #5a42b3;
  }
  @media only screen and (max-width: 425px) {
  .select-site{
    clear: both;
    float: none;
    padding-top: 1em;
  }
  .buttons-lst{
    width: 42em;
  }
}
</style>
<?php
$route = \Request::route()->getName();
$lstBtn = [
    'revenue'=>'DASHBOARD',
    'revenue.pickUpNew'=>'PICK UP',
    'revenue.daily'=>'X Día',
    'revenue.disponibilidad'=>'DISPONIBLIDAD x ALOJAMIENTO',
];

?>
<div class="buttons-box">
  <div class="buttons-lst">
    <?php foreach ($lstBtn as $k=>$v): ?>
    <div class="btn-sub-menu">
      <?php if ($route == $k): ?>
          <button class="btn btn-md text-white active"  disabled>{{$v}}</button>
        <?php else: ?>
          <a class="text-white btn btn-md btn-primary" href="{{route($k)}}">{{$v}}</a>
        <?php endif ?>	
    </div>
  <?php endforeach; ?>
  </div>
</div>
