<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>

<div class="tab-pane" id="tabPagadas">
    <div class="row">
        <div class="pull-left">
            <div class="col-xs-12 ">
                <input type="text" id="search-table3" class="form-control pull-right" placeholder="Buscar">
            </div>
        </div>
        
        <div class="clearfix"></div>

        <table class="table table-hover demo-table-search table-responsive table-striped" id="tableWithSearch3" >
            <thead>
                <tr>   
                    <th class ="text-center Pagada-la-señal text-white" style="width:5%">  Cliente     </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width:5%">  Telefono     </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width:2%">   Pax         </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width:25%!important">   Apart&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width:50px!important">  IN     </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width:50px!important">  OUT      </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Noc         </th>
                    <th class ="text-center Pagada-la-señal text-white" style="width:35%!important">Precio&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Estado&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;      </th>
                    <!-- <th class ="text-center Pagada-la-señal text-white" style="width:5%">   Acciones    </th> -->
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
                                <select class="room" class="form-control" data-id="<?php echo $book->id ?>" >
                                    
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
                                <div class="col-md-5 m-r-15">
                                    <?php echo $book->total_price."€" ?><br>
                                    <?php if (isset($payment[$book->id])): ?>
                                        <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                                    <?php else: ?>
                                    <?php endif ?>
                                </div>
                                <?php if (isset($payment[$book->id])): ?>
                                    <?php if ($payment[$book->id] == 0): ?>
                                        <div class="col-md-5 bg-primary m-t-10 m-r-15">
                                        0%
                                        </div>
                                    <?php else:?>
                                        <div class="col-md-5 bg-danger m-r-15">
                                            <p class="text-white m-t-10"><?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?></p>
                                        </div> 
                                                                                                   
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="col-md-5 bg-success">
                                        0%
                                        </div>
                                <?php endif ?>
                                                
                            </td>
                            <td class ="text-center">
                                <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                    <?php for ($i=1; $i < 9; $i++): ?> 
                                        <option <?php echo $i == ($book->type_book) ? "selected" : ""; ?> 
                                                <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                                                value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>">
                                                <?php echo $book->getStatus($i) ?></option>                                    
                                         
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <!-- <td> -->
                                <!-- <div class="btn-group col-md-6"> -->
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
                            <!-- </td> -->
                        </tr>
                <?php endforeach ?>
            </tbody>
        </table>  
    </div>
</div>