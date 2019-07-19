<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
<?php if (!$mobile->isMobile()): ?>


<table class="table table-condensed table-striped table-data" data-type="pendientes" style="margin-top: 0;">
    <thead>
    <tr>
        <th class="text-center Reservado-table text-white" style="width: 4%!important">&nbsp;</th>
        <th class="text-center Reservado-table text-white"> Cliente</th>
        <th class="text-center Reservado-table text-white"> Telefono</th>
        <th class="text-center Reservado-table text-white" style="width: 7%!important"> Pax</th>
        <th class="text-center Reservado-table text-white" style="width: 5%!important"></th>
        <th class="text-center Reservado-table text-white" style="width: 12%!important"> Apart</th>
        <th class="text-center Reservado-table text-white" style="width: 6%!important"> IN</th>
        <th class="text-center Reservado-table text-white" style="width: 8%!important"> OUT</th>
        <th class="text-center Reservado-table text-white" style="width: 6%!important"><i class="fa fa-moon-o"></i></th>
        <th class="text-center Reservado-table text-white"> Precio</th>
        <th class="text-center Reservado-table text-white" style="width: 17%!important"> Estado</th>
        <th class="text-center Reservado-table text-white" style="width: 6%!important">&nbsp;</th>
		<?php if ( Auth::user()->role != "agente" ): ?>
        <th class="text-center Reservado-table text-white" style="width: 65px!important">Acciones</th>
		<?php endif ?>
    </tr>
    </thead>
    <tbody>
	<?php foreach ($books as $book): ?>
	<?php $class = ucwords($book->getStatus($book->type_book)) ?>
	<?php if ($class == "Contestado(EMAIL)"): ?>
                <?php $class = "contestado-email" ?>
            <?php endif ?>

    <tr class="<?php echo $class;?>">
        <td class="text-center">
			<?php if ($book->agency != 0): ?>
            <img style="width: 20px;margin: 0 auto;"
                 src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" align="center"/>
			<?php endif ?>
        </td>
        <td class="text-center" style="padding: 10px 15px!important">
			<?php if (isset($payment[$book->id])): ?>
            <a class="update-book" data-id="<?php echo $book->id ?>"
               title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"
			   <?php if ( Auth::user()->role != "agente" ): ?>
               href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"
			   <?php endif ?>

               style="color: red"
            >
				<?php echo $book->customer['name']  ?>
            </a>
			<?php else: ?>
            <a class="update-book" data-id="<?php echo $book->id ?>"
               title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"
			   <?php if ( Auth::user()->role != "agente"): ?>
               href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"
			<?php endif ?>
            >
				<?php echo $book->customer['name']  ?>
            </a>
			<?php endif ?>
        </td>

        <td class="text-center">
			<?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
            <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?>
				<?php else: ?>
                <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
			<?php endif ?>
        </td>

        <td class="text-center">
			<?php if ($book->real_pax > 6 ): ?>
			<?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
			<?php else: ?>
                        <?php echo $book->pax ?>
                    <?php endif ?>

        </td>

        <td class ="text-center" >
		    <?php if ($book->hasSendPicture()): ?>
            <button class="font-w800 btn btn-xs getImagesCustomer" type="button" data-toggle="modal" data-target="#modalRoomImages" style="z-index: 99; border: none; background-color:transparent!important; color: lightgray; padding: 0;"
                    data-id="<?php echo $book->room->id ?>"
                    data-idCustomer="<?php echo $book->id ?>"
                    onclick="return confirm('¿Quieres reenviar las imagenes');">
                <i class="fa fa-eye"></i>
            </button>
		    <?php else: ?>
            <button class="font-w800 btn btn-xs getImagesCustomer" type="button" data-toggle="modal" data-target="#modalRoomImages" style="z-index: 99; border: none; background-color: transparent!important; color:black; padding: 0;"
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
            <select class="room form-control minimal" data-order="<?php echo $book->id ?>" data-id="<?php
			echo  $book->id ?>"   >
				<?php if ( Auth::user()->role != "agente" ): ?>
				<?php foreach ($rooms as $room): ?>
				<?php if ($room->id == $book->room_id): ?>
                <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
					<?php echo substr($room->nameRoom . " - " . $room->name, 0, 15)  ?>
                </option>
				<?php else:?>
                <option value="<?php echo $room->id ?>"><?php echo substr($room->nameRoom . " - " . $room->name, 0, 15)  ?></option>
				<?php endif ?>
				<?php endforeach ?>
				<?php else: ?>
                <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $book->room->name ?>">
					<?php echo substr($book->room->nameRoom . " - " . $book->room->name, 0, 15)  ?>
                </option>
				<?php endif ?>

            </select>
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

        <td class="text-center">
            <select class="status form-control minimal" data-id="<?php echo $book->id ?>">
				<?php

				$status = [ 1 => 1, 2 => 2 ];
				if (!in_array($book->type_book, $status))
					$status[] = $book->type_book;

				?>
				<?php if ( Auth::user()->role != "agente" && in_array($book->type_book, $status)): ?>
				<?php for ($i = 1; $i <= 12; $i++): ?>
				<?php if ($i == 5 && $book->customer->email == ""): ?><?php else: ?>
                <option
					<?php echo $i == ($book->type_book) ? "selected" : ""; ?> <?php echo ($i == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?> value="<?php echo $i ?>"
                    data-id="<?php echo
					$book->id ?>">
					<?php echo $book->getStatus($i) ?>
                </option>
				<?php endif ?>
				<?php endfor; ?>
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

        <td class="text-center">
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
          if (($partee = $book->partee())):
            $active = $phoneSMS = '';
            if ($partee->status == "FINALIZADO"){
              $active = 'active';
              $phoneSMS = 'disabled';
            }
            if ($partee->partee_id<1){
              $active = 'disabled-error';
              $phoneSMS = 'disabled-error';
            }
          ?>
          <div class="policeman {{$active}}"></div>
          <div class="sendSMS {{$phoneSMS}}" data-id="{{$book->id}}" {{$phoneSMS}}></div>
          <?php
          endif;
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
<?php else: ?>
<div class="table-responsive" style="border: none!important">
    <table class="table table-striped table-data" data-type="pendientes" style="margin-top: 0;">
        <thead>
        <th class="Reservado-table text-white text-center">Nombre</th>
        <th class="Reservado-table text-white text-center" style="min-width:50px">In</th>
        <th class="Reservado-table text-white text-center" style="min-width:50px ">Out</th>
        <th class="Reservado-table text-white text-center">Pax</th>
        <th class="Reservado-table text-white text-center">Tel</th>
        <th class="Reservado-table text-white text-center" style="min-width:100px">Apart</th>
        <th class="Reservado-table text-white text-center"><i class="fa fa-moon-o"></i></th>
        <th class="Reservado-table text-white text-center" style="min-width:65px">PVP</th>
        <th class="Reservado-table text-white text-center" style="min-width:200px">Estado</th>
        <th class="Reservado-table text-white text-center" style="min-width:50px">&nbsp;</th>
		<?php if ( Auth::user()->role != "agente" ): ?>
        <th class="Reservado-table text-white text-center" style="min-width:50px">A</th>
		<?php endif ?>
        </thead>
        <tbody>
		<?php foreach ($books as $book): ?>
		<?php $class = ucwords($book->getStatus($book->type_book)) ?>
		<?php if ($class == "Contestado(EMAIL)"): ?>
                    <?php $class = "contestado-email" ?>
                <?php endif ?>

        <tr class="<?php echo $class;?>">

            <td class="text-left">
				<?php if ($book->agency != 0): ?>
                <img style="width: 15px;margin: 0 auto; margin-right: 3px;" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png"
                     align="center"/>
                <a title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"
				   <?php if ( Auth::user()->role != "agente"): ?>
                   href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"
				<?php endif ?>>
					<?php echo str_pad(substr($book->customer->name, 0, 10), 10, " ")  ?>
                </a>
				<?php else: ?>
                <a title="<?php echo $book->customer->name ?> - <?php echo $book->customer->email ?>"
				   <?php if ( Auth::user()->role != "agente"): ?>
                   href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"
				   <?php endif ?> style="margin-left: 15px;">
					<?php echo str_pad(substr($book->customer->name, 0, 10), 10, " ")  ?>
                </a>
				<?php endif ?>
            </td>
			<?php $start = Carbon::createFromFormat('Y-m-d',$book->start); ?>
            <td class ="text-center" data-order="<?php echo strtotime($start->copy()->format('Y-m-d'))?>"  style="width: 20%!important">
				<?php echo $start->formatLocalized('%d %b'); ?>
            </td>
			<?php $finish = Carbon::createFromFormat('Y-m-d',$book->finish);?>
            <td class ="text-center" data-order="<?php echo strtotime($finish->copy()->format('Y-m-d'))?>"  style="width: 20%!important">
				<?php echo $finish->formatLocalized('%d %b'); ?>
            </td>
            <td class="text-center">
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
                <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
				<?php endif ?>

            </td>
            <td class="text-center">
                <select class="room form-control minimal" data-id="<?php echo $book->id ?>" data-order="<?php echo $book->id ?>">

					<?php if ( Auth::user()->role != "agente" ): ?>
					<?php foreach ($rooms as $room): ?>
					<?php if ($room->id == $book->room_id): ?>
                    <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
						<?php echo substr($room->nameRoom . " - " . $room->name, 0, 15)  ?>
                    </option>
					<?php else:?>
                    <option value="<?php echo $room->id ?>"><?php echo substr($room->nameRoom . " - " . $room->name, 0, 15)  ?></option>
					<?php endif ?>
					<?php endforeach ?>
					<?php else: ?>
                    <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $book->room->name ?>">
						<?php echo substr($book->room->nameRoom . " - " . $book->room->name, 0, 15)  ?>
                    </option>
					<?php endif ?>
                </select>
            </td>
            <td class="text-center"><?php echo $book->nigths ?></td>
            <td class="text-center"><?php echo round($book->total_price) ?> €</td>
            <td class="text-center sm-p-l-10 sm-p-r-10">
                <select class="status form-control minimal" data-id="<?php echo $book->id ?>">
					<?php

					$status = [ 1 => 1, 2 => 2 ];
					if (!in_array($book->type_book, $status))
						$status[] = $book->type_book;

					?>
					<?php if ( Auth::user()->role != "agente" && in_array($book->type_book, $status)): ?>
					<?php for ($i = 1; $i <= 12; $i++): ?>
					<?php if ($i == 5 && $book->customer->email == ""): ?><?php else: ?>
                    <option
						<?php echo $i == ($book->type_book) ? "selected" : ""; ?> <?php echo ($i == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?> value="<?php echo $i ?>"
                        data-id="<?php echo
						$book->id ?>">
						<?php echo $book->getStatus($i) ?>
                    </option>
					<?php endif ?>
					<?php endfor; ?>
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
            <td class="text-center">
				<?php if (!empty($book->book_owned_comments) && $book->promociones != 0 ): ?>
                <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                        <img src="/pages/oferta.png" style="width: 40px;">
                    </span>
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

				<?php endif ?>
            </td>
			<?php if ( Auth::user()->role != "agente" ): ?>
            <td class="text-center">
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

<?php endif ?>