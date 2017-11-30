<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
<div class="table-responsive">
    <table class="table table-striped dataTable no-footer" style="margin: 0;">
        <thead>
            <th class="bg-success text-white text-center">Nombre</th>
            <th class="bg-success text-white text-center">Tel</th>
            <th class="bg-success text-white text-center">Apto</th>
            <th class="bg-success text-white text-center">Pax</th>
            <th class="bg-success text-white text-center">Reserva</th>
            <th class="bg-success text-white text-center"><i class="fa fa-clock-o" aria-hidden="true"></i> in</th>
            <th class="bg-success text-white text-center">Pendiente</th>
            <th class="bg-success text-white text-center">A</th>
            
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td class="text-center" style="padding: 0px 10px!important;">
                        <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                             <?php echo substr($book->customer->name, 0, 10) ?>
                        </a> 
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <a class="hidden-sm hidden-xs" href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?></a>
                        <a class="hidden-md hidden-lg" href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone"></i></a>
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <b><?php echo $book->room->nameRoom ?></b>
                    </td>
                    <td class ="text-center" >
                        <?php if ($book->real_pax > 6 ): ?>
                            <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                        <?php else: ?>
                            <?php echo $book->pax ?>
                        <?php endif ?>
                            
                    </td>
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d-%b') ?> 
                        <?php echo Carbon::CreateFromFormat('Y-m-d',$book->finish)->formatLocalized('%d-%b') ?>
                            
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
                    <td class="text-center sm-p-t-10 sm-p-b-10">
                        <?php if (isset($payment[$book->id])): ?>
                            <p style="{{ $book->total_price - $payment[$book->id] > 0 ? 'color:red' : '' }}"><?php echo number_format($book->total_price - $payment[$book->id],2,',','.') ?> €</p>
                        <?php else: ?>
                            <p style="color:red"><?php echo number_format($book->total_price,2,',','.') ?> €<p>
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