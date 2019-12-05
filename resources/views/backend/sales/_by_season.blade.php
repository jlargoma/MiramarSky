
  <div class="row ">
    <?php $oldTotalPVP = 0; ?>
    <?php $arrayColors = [1 => 'bg-info', 2 => 'bg-complete', 3 => 'bg-primary',]; ?>
    <?php $lastThreeSeason = \Carbon\Carbon::createFromFormat('Y', $year->year)->subYears(2) ?>
    <?php for ($i = 1; $i < 4; $i++): ?>
      <div class="col-md-4 m-b-10">

        <div class="widget-9 no-border <?php echo $arrayColors[$i] ?> no-margin widget-loader-bar">
          <div class="full-height d-flex flex-column">

            <div class="p-l-20" style="padding: 10px 20px;">
              <h4 class="no-margin p-b-5 text-white ">
                Temp <b><?php echo $lastThreeSeason->copy()->format('y'); ?>-<?php echo $lastThreeSeason->copy()->addYear()->format('y'); ?></b>
              </h4>
              <?php $totalPVP = \App\Rooms::getPvpByYear($lastThreeSeason->copy()->format('Y')); ?>
              <h4 class="no-margin p-b-5 text-white">
                <?php echo number_format($totalPVP, 0, ',', '.'); ?>€ 
                <span style="font-size: 14px;">
                  <?php if ($i > 1): ?>
                    <?php if ($totalPVP > $oldTotalPVP): ?>
                      <i class="fa fa-arrow-up text-success" style="font-size: 20px;"></i>
                    <?php else: ?>
                      <i class="fa fa-arrow-down text-danger" style="font-size: 20px;"></i>

                    <?php endif ?>
                  <?php endif ?>
                </span>
              </h4>
            </div>
          </div>
        </div>

      </div>
      <?php $oldTotalPVP = $totalPVP; ?>
      <?php $lastThreeSeason->addYear(); ?>
    <?php endfor; ?>

  </div>
  