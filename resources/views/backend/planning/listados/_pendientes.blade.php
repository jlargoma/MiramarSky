<?php 
use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); 
$isMobile = $mobile->isMobile();
?>

<div class="table-responsive">
<table class="table table-data table-striped" data-type="pendientes">
    <thead>
    <tr>
        <th class="text-center Reservado-table text-white"> Cliente</th>
        <th class="text-center Reservado-table text-white">
          @if($isMobile) <i class="fa fa-phone"></i> @else Telefono @endif
        </th>
        <th class="text-center Reservado-table text-white" style="width: 7%!important"> Pax</th>
        <th class="text-center Reservado-table text-white" style="width: 5%!important"></th>
        <th class="text-center Reservado-table text-white" style="width: 10%!important"> Apart</th>
        <th class="text-center Reservado-table text-white" style="width: 30px !important"> IN</th>
        <th class="text-center Reservado-table text-white" style="width: 30px !important"> OUT</th>
        <th class="text-center Reservado-table text-white" style="width: 6%!important"><i class="fa fa-moon-o"></i></th>
        <th class="text-center Reservado-table text-white"> Precio</th>
        @if(Auth::user()->role != "agente" )
        <th class="text-center Reservado-table text-white" style="width: 12%!important"> Estado</th>
        @endif
        <th class="text-center Reservado-table text-white" style="max-width:30px !important;">&nbsp;</th>
		<?php if ( Auth::user()->role != "agente" ): ?>
        <th class="text-center Reservado-table text-white" style="width: 65px!important">Acciones</th>
		<?php endif ?>
    </tr>
    </thead>
    <tbody>
    <?php $bookPhone = 0; ?>
	<?php foreach ($books as $book): ?>
	    <?php
	        if ($book->type_book == 99)
	        {
	            if ($book->customer->phone == $bookPhone)
	            {
	                continue;
	            }else{
	                $bookPhone = $book->customer->phone;
	            }
	        }
        ?>
        <?php $class = ucwords($book->getStatus($book->type_book)) ?>
        <?php if ($class == "Contestado(EMAIL)"): ?>
                    <?php $class = "contestado-email" ?>
                <?php endif ?>

        <tr class="<?php echo $class;?>">
            <td class="text-left fix-col" style="padding: 10px 5px!important">
               <div class=" fix-col-data">
                <?php if ($book->agency != 0): ?>
                  <img style="width: 20px;margin: 0 auto;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center"/>
                <?php endif ?>
                @if($book->is_fastpayment == 1 || $book->type_book == 99 )
                 <img style="width: 30px;margin: 0 auto;" src="/pages/fastpayment.png" align="center"/>
                @endif
                <?php if (isset($payment[$book->id])): ?>
                <a class="update-book" data-id="<?php echo $book->id ?>"
                   title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"
                   href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"
                   style="color: red"
                >
                    <?php echo $book->customer['name']  ?>
                </a>
                <?php else: ?>
                <a class="update-book" data-id="<?php echo $book->id ?>"
                   title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"
                   href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"
                >
                    <?php echo $book->customer['name']  ?>
                </a>
                <?php endif ?>
               </div>
            </td>
            @if($isMobile)
            <td class="text-center ">
              <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
              <a href="tel:<?php echo $book->customer->phone ?>">
                <i class="fa fa-phone"></i>
              </a>
              <?php endif ?>
            </td>
            @else
            <td class="text-center">
                <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
              <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?></a>
                    <?php else: ?>
                    <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
                <?php endif ?>
            </td>
            @endif
            

            <td class="text-center">
                <?php if ($book->real_pax > 6 ): ?>
                <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                <?php else: ?>
                            <?php echo $book->pax ?>
                        <?php endif ?>

            </td>

            <td class ="text-center" >
                <?php if ($book->hasSendPicture()): ?>
                <button class="font-w800 btn btn-xs getImagesCustomer" type="button" data-toggle="modal" data-target="#modalRoomImages" style="border: none; background-color:transparent!important; color: lightgray; padding: 0;"
                        data-id="<?php echo $book->room->id ?>"
                        data-idCustomer="<?php echo $book->id ?>"
                        onclick="return confirm('¿Quieres reenviar las imagenes');">
                    <i class="fa fa-eye"></i>
                </button>
                <?php else: ?>
                <button class="font-w800 btn btn-xs getImagesCustomer" type="button" data-toggle="modal" data-target="#modalRoomImages" style="border: none; background-color: transparent!important; color:black; padding: 0;"
                        data-id="<?php echo $book->room->id ?>"
                        data-idCustomer="<?php echo $book->id ?>"
                >
                    <i class="fa fa-eye"></i>
                </button>
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

            <td class="text-center">
                @include('backend.planning.listados._select-rooms', ['rooms'=>$rooms,'bookID' => $book->id,'select'=>$book->room_id])
            </td>

            <?php $start = Carbon::createFromFormat('Y-m-d',$book->start); ?>
            <td class ="text-center" data-order="<?php echo strtotime($start->copy()->format('Y-m-d'))?>"  style="width: 20%!important">
                <?php echo $start->formatLocalized('%d %b'); ?>
            </td>
            <?php $finish = Carbon::createFromFormat('Y-m-d',$book->finish);?>
            <td class ="text-center" data-order="<?php echo strtotime($finish->copy()->format('Y-m-d'))?>"  style="width: 20%!important">
                <?php echo $finish->formatLocalized('%d %b'); ?>
            </td>

            <td class="text-center"><?php echo $book->nigths ?></td>

            <td class="text-center"><?php echo round($book->total_price) . "€" ?><br>
            </td>
            @if(Auth::user()->role != "agente" )
            <td class="text-center">
                <select class="status form-control minimal" data-id="<?php echo $book->id ?>">
                    <?php

                        $status = [ 1 => 1, 2 => 2 ];
                        if (!in_array($book->type_book, $status))
                            $status[] = $book->type_book;

                    ?>
                  
                    <?php if (in_array($book->type_book, $status)): ?>
                        <?php foreach($book->getTypeBooks() as $key => $typeStatusBook ): ?>
                            <?php if ($key == 5 && $book->customer->email == ""): ?>

                            <?php else: ?>
                                <option
                                    <?php echo $key == ($book->type_book) ? "selected" : ""; ?> <?php echo ($key == 1 || $key == 5) ? "style='font-weight:bold'" : "" ?> value="<?php echo $key ?>"
                                    data-id="<?php echo
                                    $book->id ?>">
                                    <?php echo $book->getStatus($key) ?>
                                </option>
                            <?php endif ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php for ($i = 1; $i <= count($status); $i++): ?>
                            <?php if ($i == 5 && $book->customer->email == ""): ?> <?php else: ?>
                                <option <?php echo $status[$i] == ($book->type_book) ? "selected" : ""; ?> <?php
                                        echo ($status[$i] == 1 || $status[$i] == 5) ? "style='font-weight:bold'" : "" ?> value="<?php echo $status[$i] ?>"
                                        data-id="<?php echo
                                        $book->id ?>">
                                    <?php echo $book->getStatus($status[$i]) ?>
                                </option>
                            <?php endif ?>
                        <?php endfor; ?>
                    <?php endif ?>
                   
                </select>
            </td>
             @endif
             <td class="text-center" style="max-width:30px !important;">
                <?php if (!empty($book->book_owned_comments) && $book->promociones != 0 ): ?>
                <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                                <img src="/pages/oferta.png" style="width: 40px;">
                            </span>
                <div class="comment-floating content-commentOwned-<?php echo $book->id?>" style="display: none;"><p
                            class="text-left"><?php echo $book->book_owned_comments ?></p></div>

                <?php endif ?>
            </td>
            <?php if ( Auth::user()->role != "agente" ): ?>
            <td class="text-center">
              <?php
                $ff_status = $book->get_ff_status(false);
                if ($ff_status['icon']){
                      echo '<img data-booking="'.$book->id.'" src="'.$ff_status['icon'].'" class="showFF_resume" style="max-width:25px;" alt="'.$ff_status['name'].'"/>';
                      echo '<div class="FF_resume tooltiptext"></div>';
                }
              ?>
             
                <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-danger deleteBook" type="button"
                        data-toggle="tooltip" title="" data-original-title="Eliminar Reserva"
                        onclick="return confirm('¿Quieres Eliminar la reserva?');">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
            <?php endif ?>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
</div>