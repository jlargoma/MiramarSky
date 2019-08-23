<div class="box">
  <h2>Settings - reservas </h2>
  <div class="table-responsive">
    <table class="table ">
      <thead>
        <tr>
          <th >Nombre</th>
          <th class="text-center">Valor</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($settingsBooks as $code => $name): ?>
          <?php $setting = \App\Settings::where('key', $code)->first(); ?>
          <tr>
            <td class="py-1">
              <b><?php echo $name ?></b>
            </td>
            <td class="text-center">
              <input class="setting-editable" type="number" step="0.01"
                     data-code="{{ $code }}"
                     @if($setting != null) data-id="<?php echo $setting->id ?>" @else placeholder="introduce un valor" @endif
                     @if($setting != null) value="<?php echo $setting->value ?>" @endif
                     style="width: 100%;text-align: center; border-style: none none">
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>