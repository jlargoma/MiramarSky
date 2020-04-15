<h1>{{$nameRoom}}</h1>
@include('backend.sales._tableSummaryBoxes',['hide'=>['t_day_1']])

<div class="col-xs-12" style="overflow-y: auto; max-height: 650px;">
  <table class="table  table-condensed table-striped" style="margin-top: 0;">
    <thead>
      <tr>  
        <th class ="text-center bg-complete text-white" >   
          Cliente     
        </th>
        <th class ="text-center bg-complete text-white" >   
          Telefono     
        </th>
        <th class ="text-center bg-complete text-white" style="width: 7%!important">
          Pax         
        </th>
        <th class ="text-center bg-complete text-white" style="width: 10%!important">
          Apart       
        </th>
        <th class ="text-center bg-complete text-white" style="width: 6%!important">
          IN     
        </th>
        <th class ="text-center bg-complete text-white" style="width: 8%!important">
          OUT      
        </th>
        <th class ="text-center bg-complete text-white" style="width: 6%!important">
          <i class="fa fa-moon-o"></i> 
        </th>
        <th class ="text-center bg-complete text-white" >
          Precio      
        </th>
        <th class ="text-center bg-complete text-white" style="width: 17%!important">
          Estado      
        </th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($books as $book): ?>
        <tr> 
          <td class="text-left"  style="padding: 10px 15px!important">
            <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="color: black; text-decoration: underline;">
              <?php echo $book->customer['name'] ?>

            </a>                   
          </td>

          <td class ="text-center"  > 
            <?php if ($book->customer->phone != 0): ?>
              <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?></a>
            <?php endif ?>
          </td>

          <td class ="text-center" >
            <?php if ($book->real_pax > 6): ?>
              <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
            <?php else: ?>
              <?php echo $book->pax ?>
            <?php endif ?>

          </td>

          <td class ="text-center" >
            <?php echo $book->room->nameRoom ?> - <?php echo substr($book->room->name, 0, 5) ?>
          </td>

          <td class ="text-center"  style="width: 20%!important">
            {{convertDateToShow_text($book->start)}}
          </td>

          <td class ="text-center"  style="width: 20%!important">
            {{convertDateToShow_text($book->finish)}}
          </td>

          <td class ="text-center" ><?php echo $book->nigths ?></td>

          <td class ="text-center font-w800" >
            <?php echo number_format($book->total_price, 0, ',', '.') ?>€
          </td>

          <td class ="text-center">
            <!-- 1,3,4,5,6 -->
            <?php if ($book->type_book == 1 || $book->type_book == 3 || $book->type_book == 4 || $book->type_book == 5 || $book->type_book == 6): ?>
              <b>PENDIENTE</b>
            <?php elseif ($book->type_book == 2): ?>
              <b>PAGADA</b>
            <?php elseif ($book->type_book == 7): ?>
              <b>RESERV. PROPIETARIO</b>
            <?php elseif ($book->type_book == 8): ?>
              <b>SUBCOMUNIDAD</b>
            <?php endif ?>


          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>  
</div>

