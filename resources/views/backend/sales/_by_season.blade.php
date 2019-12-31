<div class="row ">
  <?php 
  $oldTotalPVP = 0;
  $totalPVP = 0; 
  $showFF = true;
  if(env('APP_APPLICATION') == "riad"){
    $showFF = false;
  }
  ?>
  <?php $arrayColors = [1 => 'bg-info', 2 => 'bg-complete', 3 => 'bg-primary',]; ?>
  <?php $lastThreeSeason = \Carbon\Carbon::createFromFormat('Y', $year->year)->subYears(2) ?>
  <?php for ($i = 1; $i < 4; $i++): ?>
    <?php $totalPVP = \App\Rooms::getPvpByYear($lastThreeSeason->copy()->format('Y')); ?>
    <?php 
    if ($showFF) $totalFF = App\Models\Forfaits\Forfaits::getTotalByYear($lastThreeSeason->copy()->format('Y')); ?>
    <div class="col-xs-4 m-b-10">

      <div class="widget-9 no-border <?php echo $arrayColors[$i] ?> widget-loader-bar">
        <div class="full-height d-flex flex-column">
          <div style="width: 94%;margin: 2px auto;">
            <h4 class="no-margin p-b-5 text-white ">
              Temp  <b><?php echo $lastThreeSeason->copy()->format('y'); ?>-<?php echo $lastThreeSeason->copy()->addYear()->format('y'); ?></b>
            </h4>
            <div class="row">
              <div class="col-xs-10">
                @if($showFF)
                <h5 class="text-white" style="border-bottom: 1px solid;">
                  <?php echo number_format($totalPVP, 0, ',', '.'); ?> € 
                  
                  <br/>
                  <?php echo number_format($totalFF, 0, ',', '.'); ?> € <span style="font-size:9px;">(FF)</span>
                </h5>
                <h5 class="text-white" >
                   <?php echo number_format(($totalPVP + $totalFF), 0, ',', '.'); ?> € 
                </h5>
                @else
                <h5 class="text-white" style="font-size: 1.75em;">
                  <?php echo number_format($totalPVP, 0, ',', '.'); ?> € 
                </h5>
                @endif
              </div>
              <div class="col-xs-2">
                <span style="font-size: 14px;">
                  <?php if ($i > 1 && $totalPVP > $oldTotalPVP): ?>
                    <i class="fa fa-arrow-up text-success" style="font-size: 20px;"></i>
                  <?php else: ?>
                    <i class="fa fa-arrow-down text-danger" style="font-size: 20px;"></i>
                  <?php endif ?>
              </span>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    
    <?php $oldTotalPVP = $totalPVP; ?>
    <?php $lastThreeSeason->addYear(); ?>
<?php endfor; ?>

</div>
