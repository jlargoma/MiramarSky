<!-- Si no hay dos reservas el mismo dia  -->
<?php
$calendar = ($calendars[0]) ? $calendars[0] : $calendars;
$calendar->classTd = str_replace('class="', 'class=" '.$consumClass.' ', $calendar->classTd);

if ($calendar->start == $inicio):
  ?> 
  <td <?php echo $calendar->classTd; ?> <?php echo $dayKey; ?>>
    <a href="#" class="tip ddd">
      <div class="<?php echo $calendar->class; ?> start">&nbsp;</div>
      <span><?php echo $calendar->titulo.$consumVal ?></span>
    </a>
  </td>
  <?php
elseif ($calendar->finish == $inicio):
  ?>  
  <td <?php echo $calendar->classTd; ?> <?php echo $dayKey; ?>>
    <a href="#" class="tip ddd">
      <div class="<?php echo $calendar->class; ?> end">
        &nbsp;
      </div>
      <span><?php echo $calendar->titulo.$consumVal ?></span>
    </a>
  </td>
<?php else: ?>
  <td   <?php echo $calendar->classTd; ?> <?php echo $dayKey; ?>>
    <?php if ($calendar->type_book == 9): ?>
      <div class="<?php echo $calendar->class; ?> total">
        &nbsp;
      </div>
    <?php else: ?>
      <a href="#" class="tip ddd <?php echo $calendar->class; ?>" style="display:block;">
          <span><?php echo $calendar->titulo.$consumVal ?></span>
        <div class="total">
          &nbsp;
        </div>
      </a>
    <?php endif ?>
  </td>
<?php endif ?>
                                         