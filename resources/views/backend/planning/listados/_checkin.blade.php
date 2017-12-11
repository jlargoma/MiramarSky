<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>

<?php if(!$mobile->isMobile() ): ?>
    <div class="tab-pane" id="tabPagadas">
        <div class="row">
            <table class="table  table-condensed table-striped table-data"  data-type="confirmadas" style="margin-top: 0;">
                <thead>
                    <tr>   
                        <th style="display: none">ID</th> 
                        <th class ="text-center bg-success text-white" style="width: 4%!important">&nbsp;</th> 
                        <th class ="text-center bg-success text-white" style="width: 20%!important">   Cliente     </th>
                        <th class ="text-center bg-success text-white" style="width: 10%!important">   Telefono     </th>
                        <th class ="text-center bg-success text-white" style="width: 7%!important">   Pax         </th>
                        <th class ="text-center bg-success text-white" style="width: 7%!important">   Apart       </th>
                        <th class ="text-center bg-success text-white" style="width: 9%!important">  <i class="fa fa-moon-o"></i> </th>
                        <th class ="text-center bg-success text-white" style="width: 8%!important">   IN     </th>
                        <th class ="text-center bg-success text-white" style="width: 8%!important">   OUT      </th>
                        <th class="bg-success text-white text-center" style="width: 8%!important">
                            <i class="fa fa-clock-o" aria-hidden="true"></i> Hora
                        </th>
                        <th class ="text-center bg-success text-white" style="width: 15%!important">   Precio      </th>
                        <th class ="text-center bg-success text-white" style="width: 4%!important">   a      </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>

                            <td class="text-center">
                                <?php if ($book->agency != 0): ?>
                                    <img style="width: 20px;margin: 0 auto;" src="/pages/booking.png" align="center" />
                                <?php endif ?>
                            </td>
                            <td class ="text-center" style="padding: 10px 15px!important">
                               

                                <?php if (isset($payment[$book->id])): ?>
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="color: red"><?php echo $book->customer['name']  ?></a>
                                <?php else: ?>
                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" ><?php echo $book->customer['name']  ?></a>
                                <?php endif ?>   
                                <?php if (!empty($book->comment)): ?>
                                   <i class="fa fa-commenting" style="color: #000;" aria-hidden="true"></i>
                                <?php endif ?>                                                        
                            </td>

                            <td class ="text-center"><?php echo $book->customer->phone ?></td>
                            <td class ="text-center" >
                                <?php if ($book->real_pax > 6): ?>
                                    <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                                <?php else: ?>
                                    <?php echo $book->pax ?>
                                <?php endif ?>
                                    
                            </td>
                            <td class ="text-center">
                                <b><?php echo $book->room->nameRoom ?></b>
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
                            <td class="text-center sm-p-t-10 sm-p-b-10">
                                <select id="schedule" style="width: 100%;" class="<?php if(!$mobile->isMobile() ): ?>form-control minimal<?php endif; ?>" data-type="in" data-id="<?php echo $book->id ?>">
                                    <option>-- Sin asignar --</option>
                                    <?php for ($i = 0; $i < 24; $i++): ?>
                                        <option value="<?php echo $i ?>" <?php if($i == $book->schedule) { echo 'selected';}?>>
                                            <?php if ($i < 10): ?>
                                                <?php if ($i == 0): ?>
                                                    --
                                                <?php else: ?>
                                                    0<?php echo $i ?>
                                                <?php endif ?>
                                                
                                            <?php else: ?>
                                                <?php echo $i ?>
                                            <?php endif ?>
                                        </option>
                                    <?php endfor ?>
                                </select>
                            </td>
                            <td class ="text-center">
                                <div class="col-md-6">
                                    <?php echo round($book->total_price)."€" ?><br>
                                    <?php if (isset($payment[$book->id])): ?>
                                        <?php echo "<p style='color:red'>".$payment[$book->id]."€</p>" ?>
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
                            <td class="text-center sm-p-t-10 sm-p-b-10">
                                <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-primary sendSecondPay" type="button" data-toggle="tooltip" title="" data-original-title="Enviar recordatorio segundo pago" >
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </button> 
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
            <th class="bg-success text-white text-center" ></th>
            <th class="bg-success text-white text-center" >Nombre</th>
            <th class="bg-success text-white text-center" style="min-width:50px">In</th>
            <th class="bg-success text-white text-center" style="min-width:50px ">Out</th>
            <th class="bg-success text-white text-center">Pax</th>
            <th class="bg-success text-white text-center">Tel</th>
            <th class="bg-success text-white text-center" style="min-width:50px">Apart</th>
            <th class="bg-success text-white text-center"><i class="fa fa-moon-o"></i></th>
            <th class="bg-success text-white text-center" style="min-width:65px">PVP</th>
            <th class="bg-success text-white text-center" style="min-width:60px">a</th>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr class="<?php echo ucwords($book->getStatus($book->type_book)) ;?>">
                    
                    <td class="text-center">
                        <?php if ($book->agency != 0): ?>
                            <img style="width: 15px;margin: 0 auto;" src="/pages/booking.png" align="center" />
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
                    <td class="text-center"><a href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone"></i></a></td>
                    <td class="text-center">
                        <b><?php echo $book->room->nameRoom ?></b>
                    </td>
                    <td class="text-center"><?php echo $book->nigths ?></td>
                    <td class="text-center">
                       <div class="col-md-6">
                           <?php echo round($book->total_price)."€" ?><br>
                           <?php if (isset($payment[$book->id])): ?>
                               <?php echo "<p style='color:red'>".$payment[$book->id]."€</p>" ?>
                           <?php else: ?>
                           <?php endif ?>
                       </div>
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-primary sendSecondPay" type="button" data-toggle="tooltip" title="" data-original-title="Enviar recordatorio segundo pago" >
                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        </button> 
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php endif; ?>