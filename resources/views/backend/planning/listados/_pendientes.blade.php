<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>

<div class="tab-pane active" id="tabPendientes">
    <div class="row column-seperation">
        <div class="pull-left">
            <div class="col-xs-12 ">
                <input type="text" id="searchPendientes" class="form-control pull-right" placeholder="Buscar">
            </div>
        </div>
    
            <div class="clearfix"></div>

            <table class="table  demo-table-search table-responsive table-striped m-l-10" id="tablePendientes" style="width: 99%">
                <thead>
                    <tr>  
                        <th style="display: none">ID</th> 
                        <th class ="text-center Reservado-table text-white" >   Cliente     </th>
                        <th class ="text-center Reservado-table text-white" >   Telefono     </th>
                        <th class ="text-center Reservado-table text-white" >   Pax         </th>
                        <th class ="text-center Reservado-table text-white" >   Apart       </th>
                        <th class ="text-center Reservado-table text-white" >   IN     </th>
                        <th class ="text-center Reservado-table text-white" >   OUT      </th>
                        <th class ="text-center Reservado-table text-white" >   Noc         </th>
                        <th class ="text-center Reservado-table text-white" >   Precio      </th>
                        <th class ="text-center Reservado-table text-white" >   Estado      </th>
                        <th class ="text-center Reservado-table text-white">A&nbsp;&nbsp;&nbsp;&nbsp;  </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arrayBooks["nuevas"] as $book): ?>
                            <tr > 
                                <td style="display: none"><?php echo $book->id ?></td>
                                <td class ="text-center" <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?> >

                                        <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><?php echo $book->customer['name']  ?></a>                                                        
                                </td>

                                <td class ="text-center" <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?> > 
                                    <?php if ($book->customer->phone != 0): ?>
                                        <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?>
                                    <?php else: ?>
                                    <?php endif ?>
                                </td>

                                <td class ="text-center" <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>><?php echo $book->pax ?></td>

                                <td class ="text-center" <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>>
                                    <select class="room" data-id="<?php echo $book->id ?>"  <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0) !important'":""; ?>>
                                        
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

                                <td class ="text-center" <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?> style="width: 20%!important">
                                    <?php
                                        $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                        echo $start->formatLocalized('%d %b');
                                    ?>
                                </td>

                                <td class ="text-center" <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?> style="width: 20%!important">
                                    <?php
                                        $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                        echo $finish->formatLocalized('%d %b');
                                    ?>
                                </td>

                                <td class ="text-center" <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>><?php echo $book->nigths ?></td>

                                <td class ="text-center" <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?>><?php echo $book->total_price."€" ?><br>
                                </td>

                                <td class ="text-center" <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0.2) !important'":""; ?> >
                                    <select class="status" data-id="<?php echo $book->id ?>" <?php echo ($book->type_book == 1) ? "style='background:rgba(0,100,255,0) !important'":""; ?>>

                                        <?php for ($i=1; $i < 9; $i++): ?> 
                                            <option <?php echo $i == ($book->type_book) ? "selected" : ""; ?> 
                                                    <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                                                    value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>">
                                                    <?php echo $book->getStatus($i) ?></option>                                    
                                             
                                        <?php endfor; ?>
                                    </select>
                                </td>

                                <td>                                                         
                                        <!--  -->
                                        <!-- <?php if ($book->customer['phone'] != 0): ?>
                                                <a class="btn btn-tag btn-primary" href="tel:<?php echo $book->customer['phone'] ?>"><i class="pg-phone"></i>
                                                </a>
                                        <?php endif ?> -->
                                        
                                       <!--  <?php if ($book->send == 0): ?>
                                            <a class="btn btn-tag btn-primary sendJaime" title="Enviar Email a Jaime" data-id="<?php echo $book->id ?>"><i class=" pg-mail"></i></a>
                                            </a>
                                        <?php else: ?>
                                            <a class="btn btn-tag btn-danger" title="enviado" disabled data-id="<?php echo $book->id ?>"><i class=" pg-mail "></i></a>
                                            </a>
                                        <?php endif ?> -->
                                    <div class="col-md-6">
                                        <a href="{{ url('/admin/reservas/delete/')}}/<?php echo $book->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Reserva" onclick="return confirm('¿Quieres Eliminar la reserva?');">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                        

                                </td>
                            </tr>
                    <?php endforeach ?>
                </tbody>
            </table>  
        </div>
</div>