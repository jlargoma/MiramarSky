<?php 
if (!is_array($calendars)):
  
else:
  foreach ($calendars as $calendar):
    if ($calendar->type_book != 5):
      
      if($calendar->finish == $inicio): 
        $class = str_contains($calendar->classTd,'bordander') ? 'bordander' : '';
      ?>
        <a href="#" class="tip ddd <?php echo $class; ?>">
          <div class="<?php echo $calendar->class ;?> end" style="width: 45%;float: left;">  &nbsp; </div><span><?php echo $calendar->titulo.$consumVal ?></span>
        </a>
      <?php elseif ($calendar->start == $inicio ): ?>
        <a href="#" class="tip ddd">
          <div class="<?php echo $calendar->class ;?> start" style="width: 45%;float: right;">&nbsp;</div><span><?php echo $calendar->titulo.$consumVal ?></span>
        </a>
      <?php else: ?>
          <?php if ($calendar->type_book != 9 ): ?>
          <a href="#" class="tip ddd">
            <div class="<?php echo $calendar->class ;?> total">&nbsp;</div>
            <span><?php echo $calendar->titulo.$consumVal ?></span>
          </a>
          <?php endif ?>
      <?php endif ?>
    <?php endif ?>
  <?php endforeach; ?>
<?php endif;

?>