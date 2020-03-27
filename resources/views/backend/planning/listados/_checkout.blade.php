<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
        $isMobile = $mobile->isMobile();
?>
<?php $startWeek = Carbon::now()->startOfWeek(); ?>
<?php $endWeek = Carbon::now()->endOfWeek(); ?>
<div class="table-responsive">
    <table class="table table-data table-striped"  style="margin: 0;">
        <thead>
          <th class="bg-primary text-white" style="min-width: 180px !important;">Cliente</th>
          <th class="bg-primary text-white">
            @if($isMobile) <i class="fa fa-phone"></i> @else Telefono @endif
          </th>
            <th class="bg-primary text-white text-center">Pax</th>
            <th class="bg-primary text-white text-center">Out</th>
            <th class="bg-primary text-white text-center">Apto</th>
            <th class="bg-primary text-white text-center"><i class="fa fa-clock-o" aria-hidden="true"></i>Salida</th>
            <?php if (Auth::user()->role != "limpieza"): ?><th class="bg-primary text-white text-center">A</th><?php endif ?>
        </thead>
        <tbody>
            <?php $count = 0 ?>
            <?php foreach ($books as $book): ?>
                <?php if ( $book->start >= $startWeek->copy()->format('Y-m-d') && $book->start <= $endWeek->copy()->format('Y-m-d')): ?>
                    <?php $class = "blurred-line" ?>
                <?php else: ?>
                    <?php $class = "lined"; $count++ ?>
                <?php endif ?>
                <tr class="<?php if($count <= 1){echo $class;} ?>" data-id="{{$book->id}}" >
                  
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                            <?php echo substr($book->customer->name, 0, 10) ?>
                        </a> 
                    @if($book->is_fastpayment == 1 || $book->type_book == 99 )
                    <img style="width: 30px;margin: 0 auto;" src="/pages/fastpayment.png" align="center"/>
                    @endif
                    </td>
                    @if($isMobile)
                    <td class="text-center">
                      <?php if ($book->customer->phone != 0 && $book->customer->phone != ""): ?>
                        <a href="tel:<?php echo $book->customer->phone ?>">
                          <i class="fa fa-phone"></i>
                        </a>
                      <?php endif ?>
                    @else
                    <td class="text-center">
                      <?php if ($book->customer->phone != 0 && $book->customer->phone != ""): ?>
                        <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?></a>
                      <?php else: ?>
                        <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
                      <?php endif ?>
                    @endif
                      <?php if (Auth::user()->role != "limpieza" && (!empty($book->comment) || !empty($book->book_comments))): ?>
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
                    <td class ="text-center" >
                        <?php if ($book->real_pax > 6 ): ?>
                            <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                        <?php else: ?>
                            <?php echo $book->pax ?>
                        <?php endif ?>
                            
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10" data-order="<?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->format("U") ?>">
                        <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d-%b-%y') ?>
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                      <?php if ($mobile->isMobile()): ?>
                        <b><?php echo substr($book->room->nameRoom, 0, 8);?></b>
                      <?php else:?>
                        <b><?php echo substr($book->room->nameRoom." - ".$book->room->name, 0, 15)  ?></b>
                      <?php endif;?>
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <select name="scheduleOut" class=" schedule <?php if(!$mobile->isMobile() ): ?>form-control minimal<?php endif; ?>" style="width: 100%;" data-type="out" data-id="<?php echo $book->id ?>" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                            <option>---</option>
                            <?php for ($i = 0; $i < 24; $i++): ?>
                                <option value="<?php echo $i ?>" <?php if($i == $book->scheduleOut) { echo 'selected';}?>>
                                    <?php if ($i != 12): ?><b><?php endif ?>
                                    <?php if ($i < 10): ?>
                                        0<?php echo $i ?>
                                    <?php else: ?>
                                        <?php echo $i ?>
                                    <?php endif ?>
                                    <?php if ($i != 12): ?></b><?php endif ?>
                                </option>
                            <?php endfor ?>
                        </select>
                       <!--  <?php if (isset($payment[$book->id])): ?>
                            <?php echo number_format($book->total_price - $payment[$book->id],2,',','.') ?> €
                        <?php else: ?>
                            <?php echo number_format($book->total_price,2,',','.') ?> €
                        <?php endif ?> -->
                    </td>

                    <?php if (Auth::user()->role != "limpieza"): ?>
                    <td class="text-center">
                        <?php $text = "Hola, esperamos que hayas disfrutado de tu estancia con nosotros."."\n"."Nos gustaria que valorarás, para ello te dejamos este link : https://www.apartamentosierranevada.net/encuesta-satisfaccion/".base64_encode($book->id);
                            ?>
                                
                        <a href="whatsapp://send?text=<?php echo $text; ?>" data-action="share/whatsapp/share" class="btn btn-success btn-sm" data-original-title="Enviar encuesta de satisfacción" data-toggle="tooltip">
                            <i class="fa fa-whatsapp" aria-hidden="true"></i>
                        </a>
                       <button class="btn btn-primary send_encuesta" type="button" data-id="{{$book->id}}" title="Enviar encuesta mail">
                           <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        </button>
                    </td>
                    <?php endif ?>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>