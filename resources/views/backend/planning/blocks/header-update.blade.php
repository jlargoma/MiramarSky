<?php use \Carbon\Carbon;?>
  <div class="row">
    <div class="col-md-12 col-xs-12 center text-left0 mobil-p0">
      <div class="col-md-6 mobil-p0">
        <div class="col-md-9 col-xs-12">
          <div class="" id="headerFixed">
          @if( url()->previous() != "" )
          @if( url()->previous() == url()->current() )
          <a href="{{ url('/admin/reservas') }}" class=" m-b-10 mobile-right" style="min-width: 10px!important">
            <img src="{{ asset('/img/miramarski/iconos/close.png') }}" style="width: 20px"/>
          </a>
          @else
          <a href="{{ url()->previous() }}" class=" m-b-10 mobile-right" style="min-width: 10px!important">
            <img src="{{ asset('/img/miramarski/iconos/close.png') }}" style="width: 20px"/>
          </a>
          @endif
          @else
          <a href="{{ url('/admin/reservas') }}" class=" m-b-10 mobile-right"  style="min-width: 10px!important">
            <img src="{{ asset('/img/miramarski/iconos/close.png') }}" style="width: 20px"/>
          </a>
          @endif

          <h4 class="" style="line-height: 1.25; letter-spacing: -1px">
            <?php echo "<b>" . strtoupper($book->customer->name) . "</b>" ?> 
            <span class="hidden-mobile"><br/>creada el
            <?php $fecha = Carbon::createFromFormat('Y-m-d H:i:s', $book->created_at); ?>
              <?php echo $fecha->copy()->formatLocalized('%d %B %Y') . " Hora: " . $fecha->copy()->format('H:m') ?>
              </span>
            <span class="show-mobile"><?php echo convertDateTimeToShow_text($book->created_at); ?> </span>
          </h4>
          <h5>
            Creado por <?php echo "<b>" . strtoupper($book->user->name) . "</b>" ?>
            / ID: <?php echo "<b>" . $book->id . "</b>" ?>
            <?php if ($book->external_id){ echo "/ OTA-ID: <b>" . $book->external_id . "</b>"; }?>
          </h5>
          
        </div>
          
          
        <div class="row">
          @if ($book->type_book == 2)
          <div class="icon-lst">
            <a href="{{ url('/admin/pdf/pdf-reserva/'.$book->id) }}" title="Descargar reserva">
              <img src="/img/pdf.png" >
            </a>
          </div>
          <div class="icon-lst">
              <?php $text = "Hola, esperamos que hayas disfrutado de tu estancia con nosotros." . "\n" . "Nos gustaria que valorarás, para ello te dejamos este link : https://www.apartamentosierranevada.net/encuesta-satisfaccion/" . base64_encode($book->id);?>
              <a href="whatsapp://send?text=<?php echo $text; ?>"
                 data-action="share/whatsapp/share"
                 data-original-title="Enviar encuesta de satisfacción"
                 data-toggle="tooltip">
                <i class="fa fa-whatsapp" title="Encuestas"></i><br><span>Encuesta</span>
              </a>
          </div>
          <div class="icon-lst">
          <a class="send_encuesta" type="button" data-id="{{$book->id}}" title="Enviar encuesta de satisfacción por mail">
            <i class="fa fa-paper-plane" aria-hidden="true"></i><br><span>Encuesta</span>
          </a>
            </div>
          @endif
          @if (trim($book->customer->phone) != '')
           <div class="icon-lst">
            <a href="tel:<?php echo $book->customer->phone ?>">
              <i class="fa fa-phone  text-success"></i>
            </a>
          </div>
          @endif
          <div class="icon-lst showFF_resume">
            
              <a data-booking="<?php echo $book->id; ?>" class="openFF"   >
              <?php
              $ff_status = $book->get_ff_status();
              if ($ff_status['icon']) {
                echo '<img src="' . $ff_status['icon'] . '" style="max-width:40px;" alt="' . $ff_status['name'] . '"/>';
              }
              ?>
            </a>
            <div class="FF_resume tooltiptext"></div>
          </div>
          
          <div class="icon-lst show-mobile">
            <?php $text = "En este link podrás realizar el pago de la señal por el 25% del total." . "\n" . " En el momento en que efectúes el pago, te legará un email confirmando tu reserva - https://www.apartamentosierranevada.net/reservas/stripe/pagos/" . base64_encode($book->id);?>
            <a href="whatsapp://send?text=<?php echo $text; ?>"  data-action="share/whatsapp/share" title="pago de la señal">
              <i class="fa fa-eye" aria-hidden="true"></i>
            </a>
          </div>
          
          <?php
          $policeman = 'error';
          $partee = $book->partee();
          if ($partee):
          ?>
          <div class="icon-lst partee-icon" style="position:relative">
            <?php  echo $partee->print_status($book->id,$book->start,$book->pax,true); ?>
          </div>
          <?php 
          endif;
          ?>
        </div>
        </div>
        <div class="col-md-3 col-xs-12 content-guardar" style="padding: 20px 0;">
          @if($low_profit)
          <div class="btn btn-danger btn-cons btn-alarms m-b-10">BAJO BENEFICIO</div>
          @endif
          <div id="overlay" style="display: none;"></div>
          @if($book->type_book == 0)
          <select class="form-control" disabled style="font-weight: 600;">
            <option style=""><strong></strong>Eliminado</option>
          </select>
          @else
          <select class="status form-control minimal" data-id="<?php echo $book->id ?>" name="status" <?php if (getUsrRole() == "limpieza"): ?>disabled<?php endif ?>>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <?php if ($i == 5 && $book->customer->email == ""): ?>
              <?php else: ?>
                <option <?php echo $i == ($book->type_book) ? "selected" : ""; ?> <?php echo ($i == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>  value="<?php echo $i ?>" data-id="<?php echo $book->id ?>">
                <?php echo $book->getStatus($i) ?>
                </option>
              <?php endif ?>
            <?php endfor; ?>
                <option <?php echo 99 == ($book->type_book) ? "selected" : ""; ?>
                  value="<?php echo 99 ?>" data-id="<?php echo $book->id ?>">
                <?php echo $book->getStatus(99) ?>
                </option>
                <option <?php echo 98 == ($book->type_book) ? "selected" : ""; ?>
                  value="<?php echo 98 ?>" data-id="<?php echo $book->id ?>">
                <?php echo $book->getStatus(98) ?>
                </option>
                
          </select>
          @endif
          <h5 class="guardar" style="font-weight: bold; display: none; font-size: 15px;"></h5>
        </div>
      </div>
      <div class="col-md-6 col-xs-12" style="max-height: 195px; overflow: auto;">
        @if(Request::has('msg_type'))
        <div class="col-lg-12 col-xs-12 content-alert-error2">
          <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"
                    style="right: 0">×
            </button>
            <h3 class="font-w300 push-15"><?php echo str_replace('_', ' ', $_GET['msg_text']) ?></h3>
          </div>
        </div>
        @endif
        <div class="col-md-12 text-center col-xs-12 push-20">
          <input type="hidden" id="shareEmailImages" value="<?php echo $book->customer->email; ?>">
          <input type="hidden" value="<?php echo $book->id; ?>" id="registerData">
          <div class=" col-md-4 col-md-offset-4 col-xs-12 text-center">
            <button class="btn btn-complete btn-md" id="sendShareImagesEmail">
              <i class="fa fa-eye"></i> Enviar
            </button>
          </div>
        </div>
        <div class=" col-md-8 col-md-offset-2 col-xs-12 text-left">
          <?php $logSendImages = $book->getSendPicture(); ?>
          <?php if ($logSendImages): ?>
            <?php foreach ($logSendImages as $index => $logSendImage): ?>
              <?php
              $roomSended = \App\Rooms::find($logSendImage->room_id);
              $adminSended = \App\User::find($logSendImage->admin_id);
              $dateSended = Carbon::createFromFormat('Y-m-d H:i:s', $logSendImage->created_at)
              ?>
              <div class="col-xs-12 push-5">
                <p class="text-center" style="font-size: 18px; ">
                  <i class="fa fa-eye"></i>
                  Fotos <b><?php echo strtoupper($roomSended->nameRoom) ?></b> enviadas
                  por <b><?php echo strtoupper($adminSended->name) ?></b> el
                  <b><?php echo $dateSended->formatLocalized('%d %B de %Y') ?></b>
                </p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>