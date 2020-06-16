<?php 
use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); 
$isMobile = $mobile->isMobile();
$uRole = Auth::user()->role;
?>

<div class="table-responsive">
<table class="table table-data table-striped" data-type="pendientes">
    <thead>
    <tr>
        <th class="table-{{$type}}"> Cliente</th>
        <th class="table-{{$type}}">
          @if($isMobile) <i class="fa fa-phone"></i> @else Telefono @endif
        </th>
        <th class="table-{{$type}}" style="width: 7%!important"> Pax</th>
        <th class="table-{{$type}}" style="width: 5%!important"></th>
        <th class="table-{{$type}}" style="width: 10%!important"> Apart</th>
        <th class="table-{{$type}}" style="width: 30px !important"> IN</th>
        <th class="table-{{$type}}" style="width: 30px !important"> OUT</th>
        <th class="table-{{$type}}" style="width: 6%!important"><i class="fa fa-moon-o"></i></th>
        <th class="table-{{$type}}"> Precio</th>
        @if($uRole != "agente" )
        <th class="table-{{$type}}" style="width: 12%!important"> Estado</th>
        @endif
        <th class="table-{{$type}}" style="max-width:30px !important;">&nbsp;</th>
		<?php if ($uRole != "agente" ): ?>
        <th class="table-{{$type}}" style="width: 65px!important">Acciones</th>
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

        <tr class="<?php echo $class;?>" data-id="{{$book->id}}" >
            <td class="fix-col td-b1" data-order="{{$book->id}}">
               <div class="fix-col-data">
                <?php if ($book->agency != 0): ?>
                  <img class="img-agency" src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png"/>
                <?php endif ?>
                @if($book->is_fastpayment == 1 || $book->type_book == 99 )
                 <img class="img-agency" src="/pages/fastpayment.png" />
                @endif
                <?php if (isset($payment[$book->id])): ?>
                <a class="update-book r" data-id="<?php echo $book->id ?>" href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                  <?php echo $book->customer['name']  ?>
                </a>
                <?php else: ?>
                <a class="update-book" data-id="<?php echo $book->id ?>" href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                  <?php echo $book->customer['name']  ?>
                </a>
                <?php endif ?>
               </div>
            </td>
            @if($isMobile)
            <td>
              <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
              <a href="tel:<?php echo $book->customer->phone ?>">
                <i class="fa fa-phone"></i>
              </a>
              <?php endif ?>
            </td>
            @else
            <td>
                <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
              <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?></a>
                    <?php else: ?>
                    <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
                <?php endif ?>
            </td>
            @endif
            <td>
                <?php if ($book->real_pax > 6 ): ?>
                <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                <?php else: ?>
                  <?php echo $book->pax ?>
                <?php endif ?>
            </td>
            <td>
                <?php if ($book->hasSendPicture()): ?>
                <button class="btn btn-xs getImagesCustomer a" type="button" data-toggle="modal" data-target="#modalRoomImages" data-id="<?php echo $book->room->id ?>" data-idCustomer="<?php echo $book->id ?>" onclick="return confirm('¿Quieres reenviar las imagenes');">
                    <i class="fa fa-eye"></i>
                </button>
                <?php else: ?>
                <button class="btn btn-xs getImagesCustomer b" type="button" data-toggle="modal" data-target="#modalRoomImages" data-id="<?php echo $book->room->id ?>" data-idCustomer="<?php echo $book->id ?>">
                    <i class="fa fa-eye"></i>
                </button>
                <?php endif ?>
                <?php if (!empty($book->comment) || !empty($book->book_comments)): ?>
                    <div data-booking="<?php echo $book->id; ?>" class="showBookComm" >
                      <i class="fa fa-commenting" style="color: #000;" aria-hidden="true"></i>
                      <div class="BookComm tooltiptext"></div>
                    </div>
                <?php endif ?>
            </td>
            <td>
              <?php 
              if ($book->room){
                $room = $book->room;
                ?>
              <button type="button" class="btn changeRoom" data-c="{{$room->id}}">
              <?php echo substr($room->nameRoom . " - " . $room->name, 0, 15);?>
              </button>  
                <?php
              }
              ?>
            </td>
            <td class="td-date" data-order="{{$book->start}}">
              <?php echo dateMin($book->start) ?>
            </td>
            <td class="td-date" data-order="{{$book->finish}}">
              <?php echo dateMin($book->finish) ?>
            </td>
            <td><?php echo $book->nigths ?></td>
            <td><?php echo round($book->total_price) . "€" ?><br>
            </td>
            @if($uRole != "agente" )
            <td>
              <button type="button" class="btn changeStatus" data-c="{{$book->type_book}}">
                {{$book->getStatus($book->type_book)}}
              </button>
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
            <?php if ( $uRole != "agente" ): ?>
            <td>
                <button data-id="<?php echo $book->id ?>" class="btn btn-xs btn-danger deleteBook" type="button"
                        data-toggle="tooltip" title="" data-original-title="Eliminar Reserva">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
            <?php endif ?>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
</div>