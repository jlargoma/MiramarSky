<?php 

$oOtaConfig = new App\Services\OtaGateway\Config();

$aChRooms = $oOtaConfig->getRoomsName();
$aAgenc   = [];
foreach ($oOtaConfig->getAllAgency() as $name=>$id){
  $aAgenc[$id] = $name;
}
        
?>


<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
  <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
</button>
<div class="contentOtaPrices">
<h4 class="text-center">Revisar Precios en Otas: </h4>
  <?php 
  foreach ($alarms as $plan=>$channels){
  ?>
    <h5><?php echo isset($aAgenc[$plan]) ? $aAgenc[$plan] : 'OTA'; ?></h5>
    <?php
    foreach ($channels as $ch=>$lst){
      ?>
      <h6><?php echo isset($aChRooms[$ch]) ? $aChRooms[$ch] : 'APARTAMENTO'; ?></h6>
      <div class="lst cleafix">
      <?php
        foreach ($lst as $d=>$p){
      ?>
        <div class="box">
          <div class="date">{{convertDateToShow($d)}}</div>
          <span class="admin">{{moneda($p[0])}}</span> / 
          <span class="ota ">{{moneda($p[1])}}</span>
        </div>
      <?php
      }
      ?>
      </div>
      <?php
    }
  }
  ?>
</div>
<style>
  .contentOtaPrices{
    padding: 2em 1em;
  }
  .contentOtaPrices h5 {
    text-align: center;
    text-transform: capitalize;
    background-color: #295d9b!important;
    color: #FFF;
    font-weight: bold !important;
    padding: 5px;
  }
.contentOtaPrices .lst {
    clear: both;
    overflow: auto;
    content: "";
    padding: 0 7px;
}
.contentOtaPrices h6 {
    padding: 0 4px 3px;
    border-bottom: 1px solid #bfbfbf;
    margin-left: 9px;
}
.contentOtaPrices .box {
    float: left;
    text-align: center;
}
.contentOtaPrices span.ota {
    color: #e26d6d;
    font-weight: bold;
}
.contentOtaPrices span.admin {
    color: #2a5d9b;
    font-weight: bold;
}
</style>
