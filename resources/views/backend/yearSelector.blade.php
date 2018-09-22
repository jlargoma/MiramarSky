<select id="fechas" class="form-control minimal">
    <?php $years = \App\Years::all();?>
    <?php  foreach ($years as $index => $year) : ?>

        <option value="<?php echo $year->year; ?>" <?php if ($year->year == $date->copy()->format('Y')): ?> selected<?php endif ?>>
		    <?php echo $year->year."-".( $year->year +1 ); ?>
        </option>
    <?endforeach;?>
</select>