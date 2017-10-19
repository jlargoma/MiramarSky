<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>

<div class="tab-pane " id="tabEspeciales">
    <div class="row">
        <div class="col-md-12">
            <table class="table  table-condensed table-striped" style="margin-top: 0;">
                <thead>
                    <tr>  
                        <th style="display: none">ID</th> 
                        <th class ="text-center Reserva Propietario text-white" >   Cliente     </th>
                        <th class ="text-center Reserva Propietario text-white" >   Telefono     </th>
                        <th class ="text-center Reserva Propietario text-white" style="width: 7%!important">   Pax         </th>
                        <th class ="text-center Reserva Propietario text-white" style="width: 10%!important">   Apart       </th>
                        <th class ="text-center Reserva Propietario text-white" style="width: 6%!important">   IN     </th>
                        <th class ="text-center Reserva Propietario text-white" style="width: 8%!important">   OUT      </th>
                        <th class ="text-center Reserva Propietario text-white" style="width: 6%!important">  <i class="fa fa-moon-o"></i> </th>
                        <th class ="text-center Reserva Propietario text-white" >   Precio      </th>
                        <th class ="text-center Reserva Propietario text-white" style="width: 17%!important">   Estado      </th>
                        <th class ="text-center Reserva Propietario text-white" style="width: 6%!important">A</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arrayBooks["especiales"] as $book): ?>
                            <tr>
                                <td class ="text-center" style="padding: 10px 15px!important">                                                            
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><?php echo $book->customer['name'] ?></a>
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
                                <td class ="text-center"><?php echo $book->total_price."€" ?><br>
                                                        <?php if (isset($payment[$book->id])): ?>
                                                            <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                                                        <?php else: ?>
                                                        <?php endif ?>
                                </td>
                                <td class ="text-center">
                                    <select class="status form-control minimal" data-id="<?php echo $book->id ?>" >
                                        <?php for ($i=1; $i < 9; $i++): ?> 
                                            <?php if ($i == 5 && $book->customer->email == ""): ?>
                                            <?php else: ?>
                                                <option <?php echo $i == ($book->type_book) ? "selected" : ""; ?> 
                                                <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                                                value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>">
                                                    <?php echo $book->getStatus($i) ?>
                                                    
                                                </option>   
                                            <?php endif ?>
                                                                             

                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>                                                        
                                    <!--  -->
                                    <!-- <?php if ($book->customer['phone'] != 0): ?>
                                            <a class="btn btn-tag btn-primary" href="tel:<?php echo $book->customer['phone'] ?>"><i class="pg-phone"></i>
                                            </a>
                                    <?php endif ?> -->
                                    
                                    <!-- <?php if ($book->send == 0): ?>
                                        <a class="btn btn-tag btn-primary" ><i class=" pg-mail"></i></a>
                                        </a>
                                    <?php else: ?>
                                    <?php endif ?> -->
                                    <a href="{{ url('/admin/reservas/delete/')}}/<?php echo $book->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Reserva" onclick="return confirm('¿Quieres Eliminar la reserva?');">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                    <?php endforeach ?>
                </tbody>
            </table> 
        </div>
    </div>
</div>