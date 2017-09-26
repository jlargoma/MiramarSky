<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>

<div class="tab-pane" id="tabPagadas">
    <div class="row">
        <table class="table  table-condensed table-striped" style="margin-top: 0;">
            <thead>
                <tr>   
                    <th style="display: none">ID</th> 
                    <th class ="text-center Pagada-la-señal text-white" style="width: 11%!important">   Cliente     </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width: 10%!important">   Telefono     </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width: 7%!important">   Pax         </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width: 10%!important">   Apart       </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width: 6%!important">   IN     </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width: 8%!important">   OUT      </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width: 5%!important">  <i class="fa fa-moon-o"></i> </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width: 17%!important">   Precio      </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width: 17%!important">   Estado      </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arrayBooks["pagadas"] as $book): ?>
                        <tr>
                            <td class ="text-center">
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><?php echo substr($book->customer['name'], 0,10)  ?></a>                                                        
                            </td>

                            <td class ="text-center"><?php echo $book->customer->phone ?></td>
                            <td class ="text-center"><?php echo $book->pax ?></td>
                            <td class ="text-center">
                                <select class="room form-control minimal" data-id="<?php echo $book->id ?>" >
                                    
                                    <?php foreach ($rooms as $room): ?>
                                        <?php if ($room->id == $book->room_id): ?>
                                            <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                                <?php echo substr($room->name,0,5) ?>
                                            </option>
                                        <?php else:?>
                                            <option value="<?php echo $room->id ?>"><?php echo substr($room->name,0,5) ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>

                                </select>
                            </td>
                            <td class ="text-center" style="width: 20%!important">
                                <?php
                                    $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                    echo $start->formatLocalized('%d %b');
                                ?>
                            </td>
                            <td class ="text-center" style="width: 20%!important">
                                <?php
                                    $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                    echo $finish->formatLocalized('%d %b');
                                ?>
                            </td>
                            <td class ="text-center"><?php echo $book->nigths ?></td>
                            <td class ="text-center">
                                <div class="col-md-6">
                                    <?php echo $book->total_price."€" ?><br>
                                    <?php if (isset($payment[$book->id])): ?>
                                        <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                                    <?php else: ?>
                                    <?php endif ?>
                                </div>

                                <?php if (isset($payment[$book->id])): ?>
                                    <?php if ($payment[$book->id] == 0): ?>
                                        <div class="col-md-5 bg-success m-t-10">
                                        <b style="color: red;font-weight: bold">0%</b>
                                        </div>
                                    <?php else:?>
                                        <div class="col-md-5 bg-success ">
                                            <p class="text-white m-t-10"><b style="color: red;font-weight: bold"><?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?></b></p>
                                        </div> 
                                                                                                   
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="col-md-5 bg-success">
                                        <b style="color: red;font-weight: bold">0%</b>
                                        </div>
                                <?php endif ?>
                                                
                            </td>
                            <td class ="text-center">
                                <select class="status form-control minimal" data-id="<?php echo $book->id ?>" style="width: 95%">
                                    <?php for ($i=1; $i < 9; $i++): ?> 
                                        <option <?php echo $i == ($book->type_book) ? "selected" : ""; ?> 
                                                <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                                                value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>">
                                                <?php echo $book->getStatus($i) ?></option>                                    
                                    <?php endfor; ?>
                                </select>
                            </td>
                        </tr>
                <?php endforeach ?>
            </tbody>
        </table>  
    </div>
</div>