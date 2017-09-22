<?php use \Carbon\Carbon ;?>
<table class="table table-hover dataTable no-footer">
 <thead>
  <th class="Reservado-table text-white text-center">Nombre</th>
  <th class="Reservado-table text-white text-center" style="min-width:35px">In</th>
  <th class="Reservado-table text-white text-center" style="min-width:35px ">Out</th>
  <th class="Reservado-table text-white text-center">Pax</th>
  <th class="Reservado-table text-white text-center">Tel</th>
  <th class="Reservado-table text-white text-center" style="min-width:100px">Apart</th>
  <th class="Reservado-table text-white text-center"><i class="fa fa-moon-o"></i></th>
  <th class="Reservado-table text-white text-center" style="min-width:50px">PVP</th>
  <th class="Reservado-table text-white text-center" style="min-width:100px">Estado</th>
</thead>
<tbody>
  <?php foreach ($arrayBooks["nuevas"] as $nueva): ?>
   <tr>
    <td class="text-center sm-p-t-10 sm-p-b-10" <?php echo ($nueva->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>>
      <a href="{{url ('/admin/reservas/update')}}/<?php echo $nueva->id ?>"><?php echo $nueva->customer->name ?></a>
    </td>
    <td class="text-center sm-p-t-10 sm-p-b-10" <?php echo ($nueva->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>><?php echo Carbon::CreateFromFormat('Y-m-d',$nueva->start)->format('d-M') ?></td>
    <td class="text-center sm-p-t-10 sm-p-b-10" <?php echo ($nueva->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>><?php echo Carbon::CreateFromFormat('Y-m-d',$nueva->finish)->format('d-M') ?></td>
    <td class="text-center sm-p-t-10 sm-p-b-10" <?php echo ($nueva->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>><?php echo $nueva->pax ?></td>
    <td class="text-center sm-p-t-10 sm-p-b-10" <?php echo ($nueva->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>><a href="tel:<?php echo $nueva->customer->phone ?>"><i class="fa fa-phone"></i></a></td>
    <td class="text-center sm-p-t-10 sm-p-b-10" <?php echo ($nueva->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>><?php echo $nueva->room->name ?></td>
    <td class="text-center sm-p-t-10 sm-p-b-10" <?php echo ($nueva->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>><?php echo $nueva->nigths ?></td>
    <td class="text-center sm-p-t-10 sm-p-b-10" <?php echo ($nueva->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>><?php echo $nueva->total_price ?> €</td>
    <td class="text-center sm-p-t-10 sm-p-b-10 sm-p-l-10 sm-p-r-10" <?php echo ($nueva->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>>
      <select class="status" data-id="<?php echo $nueva->id ?>" <?php echo ($nueva->type_book == 1) ? "style='background:rgba(0,100,255,0) !important'":""; ?>>

        <?php for ($i=1; $i < 9; $i++): ?> 
          <option <?php echo $i == ($nueva->type_book) ? "selected" : ""; ?> 
            <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
            value="<?php echo $i ?>"  data-id="<?php echo $nueva->id ?>">
            <?php echo $nueva->getStatus($i) ?></option>                                    

          <?php endfor; ?>
        </select>
      </td>
    </tr>
  <?php endforeach ?>
</tbody>
</table>