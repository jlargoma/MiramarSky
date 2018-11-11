<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
<?php if (count($books) > 0): ?>
    <?php if (!$mobile->isMobile()): ?> 
        <table class="table table-condensed table-striped table-data"  data-type="pendientes" style="margin-top: 0;">
            <thead>
                <tr>  
                    <th style="display: none">ID</th> 
                    <th class ="bg-danger text-center text-white" style="width: 4%!important">&nbsp;</th> 
                    <th class ="bg-danger text-center text-white" >   Cliente     </th>
                    <th class ="bg-danger text-center text-white" >   Telefono     </th>
                    <th class ="bg-danger text-center text-white" style="width: 7%!important">   Pax         </th>
                    <th class ="bg-danger text-center text-white" style="width: 10%!important">   Apart       </th>
                    <th class ="bg-danger text-center text-white" style="width: 6%!important">   IN     </th>
                    <th class ="bg-danger text-center text-white" style="width: 8%!important">   OUT      </th>
                    <th class ="bg-danger text-center text-white" style="width: 6%!important">  <i class="fa fa-moon-o"></i> </th>
                    <th class ="bg-danger text-center text-white" >   Precio      </th>
                    <th class ="bg-danger text-center text-white" style="width: 17%!important">   Estado      </th>
                    <th class ="bg-danger text-center text-white" style="width: 6%!important">&nbsp;</th>
                    <th class ="bg-danger text-center text-white" style="width: 6%!important">A</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <?php $class = ucwords($book->getStatus($book->type_book)) ?>
                    <tr class="<?php echo $class ;?>">  
                        <td style="display: none"><?php echo $book->id ?></td>
                        <td class="text-center">
                            <?php if ($book->agency != 0): ?>
                                <img style="width: 20px;margin: 0 auto;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
                            <?php endif ?>
                        </td>
                        <td class ="text-center"  style="padding: 10px 15px!important">
                            <a class="update-book" data-id="<?php echo $book->id ?>" title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="color: red">
                                <?php echo substr( $book->customer->name , 0, 8)  ?>
                            </a>

                            <?php if (!empty($book->comment) || !empty($book->book_comments)): ?>
                                <?php 
                                    $textComment = "";
                                    if (!empty($book->comment)) {
                                        $textComment .= "<b>COMENTARIOS DEL CLIENTE</b>:"."<br>"." ".$book->comment."<br>";
                                    }
                                    if (!empty($book->book_comments)) {
                                        $textComment .= "<b>COMENTARIOS DE LA RESERVA</b>:"."<br>"." ".$book->book_comments;
                                    }
                                ?>
                                <?php if( preg_match( '/Antiguos cobros/i', $book->comment ) ): ?>
                                    <span>
                                        <i class="fa fa-dollar-sign" style="color: #000;" aria-hidden="true"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="icons-comment" data-class-content="content-comment-<?php echo $book->id?>">
                                        <i class="fa fa-comments" style="color: #000;" aria-hidden="true"></i>
                                    </span>
                                <?php endif;?>


                                <div class="comment-floating content-comment-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $textComment ?></p></div>
                            <?php endif ?>                                                   
                        </td>

                        <td class ="text-center"  > 
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

                        <td class ="text-center" >
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

                        <td class ="text-center"  style="width: 20%!important">
                            <?php
                                $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                echo $start->formatLocalized('%d %b');
                            ?>
                        </td>

                        <td class ="text-center"  style="width: 20%!important">
                            <?php
                                $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                echo $finish->formatLocalized('%d %b');
                            ?>
                        </td>

                        <td class ="text-center" ><?php echo $book->nigths ?></td>

                        <td class ="text-center" ><?php echo round($book->total_price)."€" ?><br>
                        </td>

                        <td class ="text-center"  >
                            <select class="status form-control minimal" data-id="<?php echo $book->id ?>" >

                                <?php for ($i=0; $i <= 11; $i++): ?> 
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
                            <?php if (!empty($book->book_owned_comments)): ?>
                                <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                                    <img src="/pages/oferta.png" style="width: 40px;">
                                </span>
                                <div class="comment-floating content-commentOwned-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $book->book_owned_comments ?></p></div>
                                
                            <?php endif ?>
                        </td>

                        <td class="text-center">                                                         
                            <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-primary restoreBook" type="button" data-toggle="tooltip" title="" data-original-title="Restaurar Reserva" onclick="return confirm('¿Quieres restaurar la reserva?');">
                               <i class="fa fa-undo" aria-hidden="true"></i>
                            </button>                            

                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table> 
    <?php else: ?>
        <div class="table-responsive" style="border: none!important">
            <table class="table table-striped table-data"  data-type="pendientes" style="margin-top: 0;">
                <thead>
                    <th class="bg-danger text-white text-center">Nombre</th>
                    <th class="bg-danger text-white text-center" style="min-width:50px">In</th>
                    <th class="bg-danger text-white text-center" style="min-width:50px ">Out</th>
                    <th class="bg-danger text-white text-center">Pax</th>
                    <th class="bg-danger text-white text-center">Tel</th>
                    <th class="bg-danger text-white text-center" style="min-width:100px">Apart</th>
                    <th class="bg-danger text-white text-center"><i class="fa fa-moon-o"></i></th>
                    <th class="bg-danger text-white text-center" style="min-width:65px">PVP</th>
                    <th class="bg-danger text-white text-center" style="min-width:200px">Estado</th>
                    <th class="bg-danger text-white text-center" style="min-width:50px">&nbsp;</th>
                    <th class="bg-danger text-white text-center" style="min-width:50px">A</th>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <?php $class = ucwords($book->getStatus($book->type_book)) ?>
                        <tr class="<?php echo $class ;?>"> 
                            
                            <td class="text-left">
                                <?php if ($book->agency != 0): ?>
                                    <img style="width: 15px;margin: 0 auto; margin-right: 3px;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
                                    <a title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>" href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" >
                                        <?php echo str_pad(substr($book->customer->name, 0, 10), 10, " ")  ?> 
                                    </a>
                                <?php else: ?>
                                    <a title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>" href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="margin-left: 15px;">
                                        <?php echo str_pad(substr($book->customer->name, 0, 10), 10, " ")  ?> 
                                    </a>
                                <?php endif ?>
                                <?php if (!empty($book->comment)): ?>
                                    <?php if( preg_match( '/Antiguos cobros/i', $book->comment ) ): ?>
                                        <i class="fa fa-dollar-sign" style="color: #000;" aria-hidden="true"></i>
                                    <?php else: ?>
                                        <i class="fa fa-comments" style="color: #000;" aria-hidden="true"></i>
                                    <?php endif;?>
                                <?php endif ?>   
                            </td>
                            <td class="text-center"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b') ?></td>
                            <td class="text-center"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b') ?></td>
                            <td class ="text-center" >
                                <?php if ($book->real_pax > 6 ): ?>
                                    <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                                <?php else: ?>
                                    <?php echo $book->pax ?>
                                <?php endif ?>
                                
                            </td>
                            <td class="text-center">
                                <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
                                    <a href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone"></i></a>
                                <?php else: ?>
                                    <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>" />
                                <?php endif ?>
                                
                            </td>
                            <td class ="text-center" >
                                <select class="room form-control minimal" data-id="<?php echo $book->id ?>"  >
                                    
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
                            <td class="text-center"><?php echo $book->nigths ?></td>
                            <td class="text-center"><?php echo round($book->total_price) ?> €</td>
                            <td class="text-center sm-p-l-10 sm-p-r-10">
                                <select class="status form-control minimal" data-id="<?php echo $book->id ?>">

                                    <?php for ($i=0; $i <= 11; $i++): ?> 
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
                                <?php if (!empty($book->book_owned_comments)): ?>
                                    <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                                        <img src="/pages/oferta.png" style="width: 40px;">
                                    </span>
                                    <div class="comment-floating content-commentOwned-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $book->book_owned_comments ?></p></div>
                                    
                                <?php endif ?>
                            </td>
                            <td class="text-center">
                                <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-primary restoreBook" type="button" data-toggle="tooltip" title="" data-original-title="Restaurar Reserva" onclick="return confirm('¿Quieres restaurar la reserva?');">
                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                </button>   
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif ?>
<?php else: ?>
    <h2 class="text-center font-w300">
        Lo sentimos, no hay reservas <span class="font-w800">ELIMINADAS</span> aún.
    </h2>
<?php endif ?>
