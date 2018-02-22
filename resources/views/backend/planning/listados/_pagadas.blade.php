<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>

<?php if(!$mobile->isMobile() ): ?>
    <div class="tab-pane" id="tabPagadas">
        <div class="row">
            <table class="table  table-condensed table-striped table-data"  data-type="confirmadas" style="margin-top: 0;">
                <thead>
                    <tr>   
                        <th style="display: none">ID</th> 
                        <th class ="text-center @if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white" style="width: 4%!important">&nbsp;</th> 
                        <th class ="text-center @if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white" style="width: 11%!important">   Cliente     </th>
                        <th class ="text-center @if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white" style="width: 10%!important">   Telefono     </th>
                        <th class ="text-center @if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white" style="width: 7%!important">   Pax         </th>
                        <th class ="text-center @if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white" style="width: 10%!important">   Apart       </th>
                        <th class ="text-center @if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white" style="width: 5%!important">  <i class="fa fa-moon-o"></i> </th>
                        <th class ="text-center @if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white" style="width: 6%!important">   IN     </th>
                        <th class ="text-center @if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white" style="width: 8%!important">   OUT      </th>
                        <th class ="text-center @if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white" style="width: 17%!important">   Precio      </th>
                        <th class ="text-center @if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white" style="width: 50px!important">   &nbsp;      </th>
                        <th class ="text-center @if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white" style="width: 17%!important">   Estado      </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>

                            <td class="text-center">
                                <?php if ($book->agency != 0): ?>
                                    <img style="width: 20px;margin: 0 auto;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
                                <?php endif ?>
                            </td>
                            <td class ="text-center" style="padding: 10px 15px!important">
                               

                                <?php if (isset($payment[$book->id])): ?>
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="color: red"><?php echo $book->customer['name']  ?></a>
                                <?php else: ?>
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" ><?php echo $book->customer['name']  ?></a>
                                <?php endif ?>
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
                                    <span class="icons-comment" data-class-content="content-comment-<?php echo $book->id?>">
                                        <i class="fa fa-commenting" style="color: #000;" aria-hidden="true"></i>
                                    </span>
                                    <div class="comment-floating content-comment-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $textComment ?></p></div>
                                <?php endif ?>                                                                   
                            </td>

                            <td class ="text-center">
                                <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
                                    <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?>
                                <?php else: ?>
                                    <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
                                <?php endif ?>
                            </td>
                            <td class ="text-center" >
                                <?php if ($book->real_pax > 6): ?>
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
                            <td class ="text-center"><?php echo $book->nigths ?></td>
                            <td class ="text-center" style="width: 20%!important">
                                <b><?php
                                    $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                    echo $start->formatLocalized('%d %b');
                                ?></b>
                            </td>
                            <td class ="text-center" style="width: 20%!important">
                                <b><?php
                                    $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                    echo $finish->formatLocalized('%d %b');
                                ?></b>
                            </td>
                            
                            <td class ="text-center">
                                <div class="col-md-6">
                                    <?php echo round($book->total_price)."€" ?><br>
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
                                        <div class="col-md-5 ">
                                            <p class="text-white m-t-10"><b style="color: red;font-weight: bold"><?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?></b></p>
                                        </div> 
                                                                                                   
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="col-md-5 bg-success">
                                        <b style="color: red;font-weight: bold">0%</b>
                                        </div>
                                <?php endif ?>
                                                
                            </td>
                            <td class="text-center">
                                <?php if (!empty($book->book_owned_comments)): ?>
                                    <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                                        <img src="/pages/oferta.png" style="width: 40px;">
                                    </span>
                                    <div class="comment-floating content-commentOwned-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $book->book_owned_comments ?></p></div>
                                    
                                <?php endif ?>
                            </td>
                            <td class ="text-center">
                                <select class="status form-control minimal" data-id="<?php echo $book->id ?>" style="width: 95%">
                                    <?php for ($i=1; $i <= 12; $i++): ?> 
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
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>  
        </div>
    </div>
<?php else: ?>
<div class="table-responsive" style="border: none!important">
    <table class="table table-striped" style="margin-top: 0;">
        <thead>
            <th class="@if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white text-center" ></th>
            <th class="@if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white text-center" >Nombre</th>
            <th class="@if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white text-center" style="min-width:50px">In</th>
            <th class="@if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white text-center" style="min-width:50px ">Out</th>
            <th class="@if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white text-center">Pax</th>
            <th class="@if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white text-center">Tel</th>
            <th class="@if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white text-center" style="min-width:100px">Apart</th>
            <th class="@if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white text-center"><i class="fa fa-moon-o"></i></th>
            <th class="@if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-white text-center" style="min-width:65px">PVP</th>
            <th class="@if($type == 'confirmadas')Pagada-la-señal @else blocked-ical @endif text-center  text-white" style="width: 50px!important">   &nbsp;      </th>
            
            <th class="Pagada-la-señal text-white text-center" style="min-width:200px">Estado</th>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr class="<?php echo ucwords($book->getStatus($book->type_book)) ;?>">
                    
                    <td class="text-center">
                        <?php if ($book->agency != 0): ?>
                            <img style="width: 15px;margin: 0 auto;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center" />
                        <?php endif ?>
                    </td>
                    <td class="text-left">
                        <?php if (isset($payment[$book->id]) && empty($payment[$book->id])): ?>
                            <span class="bg-danger text-white" style="padding: 1px 1px 1px 5px; margin-left:5px;border-radius: 100%; margin-right: 5px;">
                                <i class="fa fa-eur" aria-hidden="true"></i>
                            </span>
                        <?php endif; ?>
                        <a href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                            <?php echo str_pad(substr($book->customer->name, 0, 10), 10, " ")  ?> 
                        </a>
                        <?php if (!empty($book->comment)): ?>
                           <i class="fa fa-commenting" style="color: #000;" aria-hidden="true"></i>
                        <?php endif ?>   
                    </td>
                    <td class="text-center"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b') ?></td>
                    <td class="text-center"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b') ?></td>
                    <td class ="text-center" >
                        <?php if ($book->real_pax > 6 ): ?>
                            <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                        <?php elseif($book->pax != $book->real_pax): ?>
                            <?php echo $book->real_pax ?><i class="fa fa-exclamation-circle" aria-hidden="true" style="color: red"></i>
                        <?php else: ?>
                            <?php echo $book->pax ?>
                        <?php endif ?>
                            
                    </td>
                    <td class="text-center">
                        <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
                             <a href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone"></i></a>
                        <?php else: ?>
                            <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
                        <?php endif ?>
                           
                    </td>
                    <td class="text-center">
                        <select class="room form-control minimal" data-id="<?php echo $book->id ?>"  >
                            
                            <?php foreach ($rooms as $room): ?>
                                <?php if ($room->id == $book->room_id): ?>
                                    <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                        <?php echo substr($room->nameRoom." - ".$room->name, 0, 8)  ?>
                                    </option>
                                <?php else:?>
                                    <option value="<?php echo $room->id ?>"><?php echo substr($room->nameRoom." - ".$room->name, 0, 8)  ?></option>
                                <?php endif ?>
                            <?php endforeach ?>

                        </select>
                    </td>
                    <td class="text-center"><?php echo $book->nigths ?></td>
                    <td class="text-center">
                        <?php echo round($book->total_price)."€" ?><br>
                        <?php if (isset($payment[$book->id])): ?>
                            <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                        <?php else: ?>
                        <?php endif ?>
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
                        <select class="status form-control minimal" data-id="<?php echo $book->id ?>">

                            <?php for ($i=1; $i <= 12; $i++): ?> 
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
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php endif; ?>