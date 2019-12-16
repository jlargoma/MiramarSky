<?php 
use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); 
?>
<div class="table-responsive">
<table class="table table-data table-striped" data-type="pendientes">
    <thead>
    <tr>
        <th > Cliente</th>
        <th >
          <i class="fa fa-phone"></i> 
        </th>
        <th> Pax</th>
        <th></th>
        <th> Apart</th>
        <th> IN</th>
        <th> OUT</th>
        <th><i class="fa fa-moon-o"></i></th>
        <th > Precio</th>
        <th  style="width: 12%!important"> Estado</th>
        <th  style="max-width:30px !important;">&nbsp;</th>
        <th  style="width: 65px!important">Acciones</th>
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
            <td>
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
                   href="/admin/reservas/update/<?php echo $book->id ?>"
                >
                    <?php echo $book->customer['name']  ?>
                </a>
                <?php endif ?>
               </div>
            </td>

            <td>
              <?php if ($book->customer->phone != 0 && $book->customer->phone != "" ): ?>
              <a href="tel:<?php echo $book->customer->phone ?>">
                <i class="fa fa-phone"></i>
              </a>
              <?php endif ?>
            </td>
         
            

            <td>
                <?php if ($book->real_pax > 6 ): ?>
                <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                <?php else: ?>
                            <?php echo $book->pax ?>
                        <?php endif ?>

            </td>

            <td>
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

            <td>
               
            </td>
            <td>
                <?php echo $book->start; ?>
            </td>
            <td>
                <?php echo $book->finish; ?>
            </td>

            <td><?php echo $book->nigths ?></td>

            <td><?php echo ($book->total_price) . "€" ?><br>
            </td>
         
            <td>
               <?php echo $book->getStatus($book->type_book) ?>
            </td>
             <td>
                <?php if (!empty($book->book_owned_comments) && $book->promociones != 0 ): ?>
                <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                                <img src="/pages/oferta.png" style="width: 40px;">
                            </span>
                <div class="comment-floating content-commentOwned-<?php echo $book->id?>" style="display: none;"><p
                            class="text-left"><?php echo $book->book_owned_comments ?></p></div>

                <?php endif ?>
            </td>
            <td>
             
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
</div>