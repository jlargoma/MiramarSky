<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>

  <table class="table  table-responsive table-striped" style="margin-top: 0;">
    <thead>
      <th class="Reservado-table text-white text-center">Nombre</th>
      <th class="Reservado-table text-white text-center" style="min-width:50px">In</th>
      <th class="Reservado-table text-white text-center" style="min-width:50px ">Out</th>
      <th class="Reservado-table text-white text-center">Pax</th>
      <th class="Reservado-table text-white text-center">Tel</th>
      <th class="Reservado-table text-white text-center" style="min-width:100px">Apart</th>
      <th class="Reservado-table text-white text-center"><i class="fa fa-moon-o"></i></th>
      <th class="Reservado-table text-white text-center" style="min-width:65px">PVP</th>
      <th class="Reservado-table text-white text-center" style="min-width:200px">Estado</th>
      <th class="Reservado-table text-white text-center" style="min-width:50px">A</th>
    </thead>
    <tbody>
      <?php foreach ($arrayBooks["nuevas"] as $nueva): ?>
        <?php $class = ucwords($book->getStatus($book->type_book)) ?>
        <?php if ($class == "Contestado(EMAIL)"): ?>
             <?php $class = "contestado-email" ?>
        <?php endif ?>
            
        <tr class="<?php echo $class ;?>"> 
          <td class="text-center sm-p-t-10 sm-p-b-10">
            <a href="{{url ('/admin/reservas/update')}}/<?php echo $nueva->id ?>"><?php echo $nueva->customer->name ?></a>
          </td>
          <td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$nueva->start)->formatLocalized('%d %b') ?></td>
          <td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$nueva->finish)->formatLocalized('%d %b') ?></td>
          <td class ="text-center" >
              <?php if ($book->real_pax > 6 ): ?>
                  <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
              <?php elseif($book->pax != $book->real_pax): ?>
                  <?php echo $book->real_pax ?><i class="fa fa-exclamation-circle" aria-hidden="true" style="color: red"></i>
              <?php else: ?>
                  <?php echo $book->pax ?>
              <?php endif ?>
                  
          </td>
          <td class="text-center sm-p-t-10 sm-p-b-10"><a href="tel:<?php echo $nueva->customer->phone ?>"><i class="fa fa-phone"></i></a></td>
          <td class ="text-center sm-p-t-10 sm-p-b-10" >
              <select class="room form-control minimal" data-id="<?php echo $nueva->id ?>"  >
                  
                  <?php foreach ($rooms as $room): ?>
                      <?php if ($room->id == $nueva->room_id): ?>
                          <option selected value="<?php echo $nueva->room_id ?>" data-id="<?php echo $room->name ?>">
                              <?php echo substr($room->name,0,5) ?>
                          </option>
                      <?php else:?>
                          <option value="<?php echo $room->id ?>"><?php echo substr($room->name,0,5) ?></option>
                      <?php endif ?>
                  <?php endforeach ?>

              </select>
          </td>
          <td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $nueva->nigths ?></td>
          <td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $nueva->total_price ?> €</td>
          <td class="text-center sm-p-t-10 sm-p-b-10 sm-p-l-10 sm-p-r-10">
            <select class="status form-control minimal" data-id="<?php echo $nueva->id ?>">

                <?php for ($i=1; $i < 9; $i++): ?> 
                  <?php if ($i == 5 && $nueva->customer->email == ""): ?>
                  <?php else: ?>
                      <option <?php echo $i == ($nueva->type_book) ? "selected" : ""; ?> 
                      <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                      value="<?php echo $i ?>"  data-id="<?php echo $nueva->id ?>">
                          <?php echo $nueva->getStatus($i) ?>
                          
                      </option>   
                  <?php endif ?>
                                                   

              <?php endfor; ?>
            </select>
          </td>
          <td>
            <div class="col-xs-12">
                <a href="{{ url('/admin/reservas/delete/')}}/<?php echo $nueva->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Reserva" onclick="return confirm('¿Quieres Eliminar la reserva?');">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>