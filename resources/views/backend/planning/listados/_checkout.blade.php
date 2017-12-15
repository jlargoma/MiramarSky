<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>

<div class="table-responsive">
    <table class="table table-striped no-footer" style="margin: 0;">
        <thead>
            <th class="bg-primary text-white text-center">Nombre</th>
            <th class="bg-primary text-white text-center">Tel</th>
            <th class="bg-primary text-white text-center">Pax</th>
            <th class="bg-primary text-white text-center">Out</th>
            <th class="bg-primary text-white text-center">Apto</th>
            <th class="bg-primary text-white text-center"><i class="fa fa-clock-o" aria-hidden="true"></i>Salida</th>
            <th class="bg-primary text-white text-center">A</th>
        </thead>
        <tbody>
            <?php $count = 0 ?>
            <?php foreach ($books as $book): ?>
                <?php if ( $book->start >= $startWeek->copy()->format('Y-m-d') && $book->start <= $endWeek->copy()->format('Y-m-d')): ?>
                    <?php $class = "" ?>
                <?php else: ?>
                    <?php $class = "lined"; $count++ ?>
                <?php endif ?>
                <tr class="<?php if($count <= 1){echo $class;} ?>">
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                            <?php echo substr($book->customer->name, 0, 10) ?>
                        </a> 
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?></a>
                    </td>
                    <td class ="text-center" >
                        <?php if ($book->real_pax > 6 ): ?>
                            <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                        <?php else: ?>
                            <?php echo $book->pax ?>
                        <?php endif ?>
                            
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d-%b') ?></td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <b><?php echo $book->room->nameRoom ?></b>
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <select id="scheduleOut" class="<?php if(!$mobile->isMobile() ): ?>form-control minimal<?php endif; ?>" style="width: 100%;" data-type="out" data-id="<?php echo $book->id ?>">
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
                    <td class="text-center">
                        <?php $text = "Hola, esperamos que hayas disfrutado de tu estancia con nosotros."."\n"."Nos gustaria que valorarás, para ello te dejamos este link : https://www.apartamentosierranevada.net/encuesta-satisfaccion/".base64_encode($book->id);
                            ?>
                                
                        <a href="whatsapp://send?text=<?php echo $text; ?>" data-action="share/whatsapp/share" class="btn btn-success btn-sm" data-original-title="Enviar encuesta de satisfacción" data-toggle="tooltip">
                            <i class="fa fa-whatsapp" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>