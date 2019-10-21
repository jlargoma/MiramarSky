<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>

<?php if(!$mobile->isMobile() ): ?>
<div class="tab-pane " id="tabEspeciales">
    <div class="row">
        <div class="col-md-12">
            <table class="table  table-condensed table-striped table-data"  data-type="especiales" style="margin-top: 0;">
                <thead>
                    <tr>  
                      <th class ="text-center Reserva Propietario text-white" style="width: 15px!important">&nbsp;</th>
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
                    <?php foreach ($books as $book): ?>
                    <?php $class = ucwords($book->getStatus($book->type_book)) ?>
                            <?php if ($class == "Contestado(EMAIL)"): ?>
                        <?php $class = "contestado-email" ?>
                    <?php endif ?>

                    <tr class="<?php echo strtolower( $class) ;?>">

                                <td class ="text-center">
                                @if($book->is_fastpayment == 1 || $book->type_book == 99 )
                                <img style="width: 18px;margin: 0 auto;" src="/pages/fastpayment.png" align="center"/>
                                @endif
                                </td>
                                <td class ="text-center" style="padding: 10px 15px!important">                                                            
                                    <?php if (isset($payment[$book->id])): ?>
                                        <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="color: red"><?php echo $book->customer['name']  ?></a>
                                    <?php else: ?>
                                        <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" ><?php echo $book->customer['name']  ?></a>
                                    <?php endif ?> 
                                </td>

                                <td class ="text-center">
                                    <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
                                        <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?>
                                    <?php else: ?>
                                        <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>" />
                                    <?php endif ?>
                                </td>
                                <td class ="text-center" >
                                    <?php if ($book->real_pax > 6 ): ?>
                                        <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                                    <?php else: ?>
                                        <?php echo $book->pax ?>
                                    <?php endif ?>
                                        
                                </td>
                                <td class ="text-center">
                                    <select class="room form-control minimal" data-id="<?php echo $book->id ?>" >
                                        
                                        <?php foreach ($rooms as $room): ?>
                                            <?php if ($room->id == $book->room_id): ?>
                                                <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                                   <?php echo substr($room->nameRoom." - ".$room->name, 0, 15)  ?>
                                                </option>
                                            <?php else:?>
                                                <option value="<?php echo $room->id ?>"><?php echo substr($room->nameRoom." - ".$room->name, 0, 15)  ?></option>
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
                                    <?php echo round($book->total_price)."€" ?><br>
                                    <?php if (isset($payment[$book->id])): ?>
                                        <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                                    <?php endif ?>
                                </td>
                                <td class ="text-center">
                                    <select class="status form-control minimal" data-id="<?php echo $book->id ?>" >
                                        <?php for ($i=1; $i <= 11; $i++): ?> 
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
                                <td class="text-center"> 
                                    <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-danger deleteBook" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Reserva" onclick="return confirm('¿Quieres Eliminar la reserva?');">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                    <?php endforeach ?>
                </tbody>
            </table> 
        </div>
    </div>
</div>
<?php else: ?>
<div class="table-responsive" style="border: none!important">
    <table class="table  table-responsive table-striped table-data"  data-type="especiales" style="margin-top: 0;">
        <thead>
            <th class="Reserva Propietario text-white text-center">Nombre</th>
            <th class="Reserva Propietario text-white text-center" style="min-width:50px">In</th>
            <th class="Reserva Propietario text-white text-center" style="min-width:50px ">Out</th>
            <th class="Reserva Propietario text-white text-center">Pax</th>
            <th class="Reserva Propietario text-white text-center">Tel</th>
            <th class="Reserva Propietario text-white text-center" style="min-width:100px">Apart</th>
            <th class="Reserva Propietario text-white text-center"><i class="fa fa-moon-o"></i></th>
            <th class="Reserva Propietario text-white text-center" style="min-width:65px">PVP</th>
            <th class="Reserva Propietario text-white text-center" style="min-width:200px">Estado</th>
            <th class="Reserva Propietario text-white text-center">A</th>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <?php $class = ucwords($book->getStatus($book->type_book)) ?>
                <?php if ($class == "Contestado(EMAIL)"): ?>
                            <?php $class = "contestado-email" ?>
                        <?php endif ?>

                <tr class="<?php echo strtolower( $class) ;?>">
                    <td class="text-center sm-p-t-10 sm-p-b-10" title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>">
                        <?php if (isset($payment[$book->id])): ?>
                            <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="color: red"><?php echo $book->customer['name']  ?></a>
                        <?php else: ?>
                            <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" ><?php echo $book->customer['name']  ?></a>
                        <?php endif ?> 
                      @if($book->is_fastpayment == 1 || $book->type_book == 99 )
                      <img style="width: 18px;margin: 0 auto;" src="/pages/fastpayment.png" align="center"/>
                      @endif
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b') ?></td>
                    <td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b') ?></td>
                    <td class ="text-center" >
                        <?php if ($book->real_pax > 6 ): ?>
                            <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                        <?php else: ?>
                            <?php echo $book->pax ?>
                        <?php endif ?>
                            
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
                            <a href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone"></i></a>
                        <?php else: ?>
                            <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>" />
                        <?php endif ?>
                        </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <select class="room form-control minimal" data-id="<?php echo $book->id ?>"  >
                            
                            <?php foreach ($rooms as $room): ?>
                                <?php if ($room->id == $book->room_id): ?>
                                    <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                       <?php echo substr($room->nameRoom." - ".$room->name, 0, 15)  ?>
                                    </option>
                                <?php else:?>
                                    <option value="<?php echo $room->id ?>">
                                        <?php echo substr($room->nameRoom." - ".$room->name, 0, 15)  ?>
                                    </option>
                                <?php endif ?>
                            <?php endforeach ?>

                        </select>
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10"><?php echo $book->nigths ?></td>
                    <td class="text-center sm-p-t-10 sm-p-b-10"><?php echo round($book->total_price) ?> €</td>
                    <td class="text-center sm-p-t-10 sm-p-b-10 sm-p-l-10 sm-p-r-10">
                        <select class="status form-control minimal" data-id="<?php echo $book->id ?>">

                            <?php for ($i=1; $i <= 11; $i++): ?> 
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
                    <td class="text-center">
                        <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-danger deleteBook" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Reserva" onclick="return confirm('¿Quieres Eliminar la reserva?');">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php endif; ?>