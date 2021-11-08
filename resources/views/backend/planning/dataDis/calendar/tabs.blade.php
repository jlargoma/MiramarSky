<?php
foreach ($lstMonths as $timestamp => $d):?>
  <button <?php if ($d['m'] == $currentM) echo 'id="btn-active"'; ?> 
    class="btn btn-rounded btn-sm btn-default btn-fechas-calendar reloadCalend"
    data-month="<?php echo $d['m']; ?>" data-time="<?php echo $timestamp; ?>">
        <?php echo $d['t'] ?>
  </button>
<?php endforeach; ?>
  