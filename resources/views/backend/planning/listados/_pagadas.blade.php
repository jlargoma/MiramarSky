<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
<?php
$isMobile = $mobile->isMobile();
$uRole = Auth::user()->role;
$classTH = 'text-center text-white ';
if($type == 'confirmadas'):
  $classTH .= ' Pagada-la-señal';
else:
  $classTH .= ' blocked-ical';
endif
?>
<div class="tab-pane" id="tabPagadas">
    <div class="table-responsive">
        <table class="table table-data table-striped"  data-type="confirmadas">
            <thead>
                <tr>
                  <th class ="{{$classTH}}" style="min-width: 130px;">   Cliente     </th>
                  <th class ="{{$classTH}}" style="width: 10%">
                    @if($isMobile) <i class="fa fa-phone"></i> @else Telefono @endif
                  </th>
                    <th class ="{{$classTH}}" style="width: 25px">   Pax         </th>
                    <th class ="{{$classTH}}" style="width: 30px"> </th>
                    <th class ="{{$classTH}}" style="width: 100px">   Apart       </th>
                    @if($uRole != "agente" )
                    <th class ="{{$classTH}}" style="width: 10%">   Estado      </th>
                    @endif
                    <th class ="{{$classTH}}" style="width: 25px">  <i class="fa fa-moon"></i> </th>
                    <th class ="{{$classTH}}" style="width: 80px">   IN     </th>
                    <th class ="{{$classTH}}" style="width: 80px">   OUT      </th>
                    <th class ="{{$classTH}}" style="min-width: 120px;">   Precio      </th>
                    <th class ="{{$classTH}}" style="width:25px">   &nbsp;      </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr data-id="{{$book->id}}" >
                      <td class="fix-col td-b1">
                        <div class="fix-col-data">
                            <?php if ($book->agency != 0): ?>
                        <img src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" class="img-agency" />
                            <?php endif;?>
                            <a class="update-book" data-id="<?php echo $book->id ?>" href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>">
                              <?php echo $book->customer['name']  ?></a>
                        </div>
                        </td>
                        @if($isMobile)
                         <td>
                            <?php if ($book->customer->phone != 0 && $book->customer->phone != ""): ?>
                              <a href="tel:<?php echo $book->customer->phone ?>">
                                <i class="fa fa-phone"></i>
                              </a>
                            <?php endif ?>
                          </td>
                          @else
                          <td>
                            <?php if ($book->customer->phone != 0 && $book->customer->phone != ""): ?>
                              <a href="tel:<?php echo $book->customer->phone ?>"><?php echo $book->customer->phone ?></a>
                            <?php else: ?>
                              <input type="text" class="only-numbers customer-phone" data-id="<?php echo $book->customer->id ?>"/>
                            <?php endif ?>
                          </td>
                          @endif
                        <td>
                            <?php if ($book->real_pax > 6): ?>
                                <?php echo $book->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
                            <?php else: ?>
                                <?php echo $book->pax ?>
                            <?php endif ?>

                        </td>
                        <td class="p-rel">
                          <?php if ($book->hasSendPicture()): ?>
                            <button class="btn btn-xs getImagesCustomer a" type="button" data-toggle="modal" data-target="#modalRoomImages" data-id="<?php echo $book->room->id ?>" data-idCustomer="<?php echo $book->id ?>" onclick="return confirm('¿Quieres reenviar las imagenes');">
                                <i class="fa fa-eye"></i>
                            </button>
                                    <?php else: ?>
                            <button class="btn btn-xs getImagesCustomer b" type="button" data-toggle="modal" data-target="#modalRoomImages" data-id="<?php echo $book->room->id ?>" data-idCustomer="<?php echo $book->id ?>">
                                <i class="fa fa-eye"></i>
                            </button>
                            <?php endif ?>
                             <?php if (!empty($book->comment) || !empty($book->book_comments) || !empty($book->book_owned_comments)): ?>
                                  <div data-booking="<?php echo $book->id; ?>" class="showBookComm" >
                                    <i class="far fa-comment-dots" style="color: #000;" aria-hidden="true"></i>
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
                          @if($uRole != "agente" )
                        <td>
                          <button type="button" class="btn changeStatus" data-c="{{$book->type_book}}">
                            {{$book->getStatus($book->type_book)}}
                          </button>
                        </td>
                        @endif
                        <td class ="text-center"><?php echo $book->nigths ?></td>
                        <td class="td-date" data-order="{{$book->start}}">
                          <?php echo dateMin($book->start) ?>
                        </td>
                        <td class="td-date" data-order="{{$book->finish}}">
                          <?php echo dateMin($book->finish) ?>
                        </td>
                        <td>
                            <?php 
                            if ($uRole != "limpieza"):
                              echo $book->showPricePlanning($payment);
                            endif;
                            ?>
                        </td>
                        <td>
                                <?php if (!empty($book->book_owned_comments) && $book->promociones != 0 ): ?>
                                <span class="icons-comment" data-class-content="content-commentOwned-<?php echo $book->id?>">
                                    <img src="/pages/oferta.png" style="width: 40px;">
                                </span>
                                <div class="comment-floating content-commentOwned-<?php echo $book->id?>" style="display: none;"><p class="text-left"><?php echo $book->book_owned_comments ?></p></div>
                            <?php endif ?>
                                
                        </td>
                      
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
