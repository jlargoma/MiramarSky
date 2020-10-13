<?php
$mobile = new \App\Classes\Mobile();
$isMobile = $mobile->isMobile();
?>
<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
  <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
</button>

<div class="col-md-12 not-padding content-last-books">
  <div class="alert alert-info fade in alert-dismissable" style="max-height: 600px; overflow-y: auto;position: relative;">
    <!-- <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> -->
    <!-- <strong>Info!</strong> This alert box indicates a neutral informative change or action. -->
    <h4 class="text-center">CAJAS DE SEGURIDAD ASIGNADAS <div id="safeBoxLst">Cajas</div></h4>
    @if(count($books)>0)
     <div class="table-responsive" style="    overflow-y: hidden;">
      <table class="table" id="table_partee">
        <thead>
          <tr class ="text-center bg-success text-white">
            @if($isMobile)
            <th class="th-bookings static" style="width: 130px; padding: 14px !important;background-color: #57d0bd;">  
              Nombre
            </th>
            <th class="th-bookings first-col" style="padding-left: 130px!important">Tel.</th>
            @else
            <th class="th-bookings static" style="background-color: #57d0bd;">  
              Nombre
            </th>
            <th class="th-bookings first-col">Tel.</th> 
            @endif
            <th class="th-bookings text-center th-2">Pax</th>
            <th class="th-bookings text-center">Apart</th>
            <th class="th-bookings text-center th-2"><i class="fa fa-moon-o" title="cantidad de noches"></i> </th>
            <th class="th-bookings text-center th-2"><i class="fa fa-clock-o" title="Hora de llegada"></i></th>
            <th class="th-bookings text-center th-4">IN - OUT </th>
            <th class="th-bookings text-center th-2">Caja</th>
            <th class="th-bookings text-center th-2">Contraseña</th>
            <th class="th-bookings text-center th-1">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
              <?php $class = ( $book->start <= $today) ? "blurred-line" : '' ?>
            <tr class="<?php echo $class; ?>">
              @if($isMobile)
              <td class ="text-left static" style="width: 130px;color: black;overflow-x: scroll;    padding: 30px 6px !important; ">  
                @else
              <td class ="text-left" style="position: relative; padding: 7px !important;">  
                @endif
                <?php if ($book->agency != 0): ?>
                  <img src="/pages/<?php echo strtolower($book->getAgency($book->agency)) ?>.png" class="img-agency" />
                <?php endif ?>
                <?php if (isset($payment[$book->id])): ?>
                  <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" style="color: red"><?php echo $book->customer['name'] ?></a>
                <?php else: ?>
                  <a class="update-book" data-id="<?php echo $book->id ?>"  title="<?php echo $book->customer['name'] ?> - <?php echo $book->customer['email'] ?>"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>" ><?php echo $book->customer['name'] ?></a>
                <?php endif ?>
                  <?php if (!empty($book->comment) || !empty($book->book_comments)): ?>
                    <?php
                    $textComment = "";
                    if (!empty($book->comment)) {
                      $textComment .= "<b>COMENTARIOS DEL CLIENTE</b>:" . "<br>" . " " . $book->comment . "<br>";
                    }
                    if (!empty($book->book_comments)) {
                      $textComment .= "<b>COMENTARIOS DE LA RESERVA</b>:" . "<br>" . " " . $book->book_comments;
                    }
                    ?>
                    @if($isMobile)
                    <i class="fa fa-commenting msgs fa-2x" 
                       style="color: #000;" 
                       aria-hidden="true"
                       data-msg="{{$textComment}}"
                       ></i>
                    @else
                    <div class="tooltip-2">
                      <i class="fa fa-commenting" style="color: #000;" aria-hidden="true"></i>
                      <div class="tooltiptext comment"><p class="text-left"><?php echo $textComment ?></p></div>
                    </div>
                    @endif
                  <?php endif ?>
              </td>
              @if($isMobile)
              <td class="text-center first-col" style="padding: 35px 0px !important; padding-left: 130px!important">
              @else
              <td class="text-center">
              @endif
                <?php if ($book->customer->phone != 0 && $book->customer->phone != ""): ?>
                  <a href="tel:<?php echo $book->customer->phone ?>">
                    <i class="fa fa-phone"></i>
                  </a>
                <?php endif ?>
              </td>
              <td class ="text-center" >{{$book->pax}}</td>
              <td class ="text-center">{{$book->room->nameRoom }}</td>
              <td class ="text-center">{{$book->nigths}}</td>
              <td class="text-center sm-p-t-10 sm-p-b-10">{{$book->schedule}}</td>
              <td class ="text-center" data-order="{{$book->start}}"  style="width:20%!important">
                <b>{{dateMin($book->start)}}</b>
                <span>-</span>
                <b>{{dateMin($book->finish)}}</b>
              </td>
              <td class="text-center">{{show_isset($boxs,$book->box_id)}}</td>
              <td class="text-center">{{show_isset($keys,$book->box_id)}}</td>
              <td class="text-center">
                <button data-id="<?php echo $book->id ?>" class="btn openSafetyBox" type="button" data-toggle="tooltip" title="" data-original-title="Centro Mensajería">
                   <i class="fas fa-mail-bulk"></i>
                    </button> 
              </td>
            </tr>
        <?php endforeach ?>
        </tbody>
      </table>
      <div id="conteiner_msg_lst">
        <div class="box-msg-lst">
          <div id="box_msg_lst"></div>
          <button type="button" class="btn btn-default" id="box_msg_close">Cerrar</button>
        </div>
      </div>
      @else
      <p class="alert alert-warning">
        No existen registros.
      </p>
      @endif
    </div>
  </div> 


