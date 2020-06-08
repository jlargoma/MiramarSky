<div class="box">
  <h2>Extras</h2>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th class="text-center" style="width: 1%"> Nombre</th>
          <th class="text-center" style="width: 5%">PVP</th>
          <th class="text-center" style="width: 5%">Cost</th>
          <th class="text-center" style="width: 5%">% Ben</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($extras as $extra): ?>
          <tr>
            <td class="py-1"><?php echo $extra->name ?></td>
            <td class="text-center">
              <input class="extra-editable extra-price-<?php echo $extra->id ?>" type="text"
                     name="cost" data-id="<?php echo $extra->id ?>"
                     value="<?php echo $extra->price ?>"
                     style="width: 100%;text-align: center;border-style: none none">
            </td>
            <td class="text-center">
              <input class="extra-editable extra-cost-<?php echo $extra->id ?>" type="text"
                     name="cost" data-id="<?php echo $extra->id ?>"
                     value="<?php echo $extra->cost ?>"
                     style="width: 100%;text-align: center;border-style: none none">
            </td>
            <td class="text-center">
              <?php $cost = ($extra->cost == 0) ? 1 : $extra->cost ?>
              <?php $ben = (($extra->price * 100) / $cost); ?>
              <?php $ben = ($ben == 0) ? $ben : ($ben - 100); ?>
              <?php echo number_format($ben, 2, ',', '.') ?>%
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>