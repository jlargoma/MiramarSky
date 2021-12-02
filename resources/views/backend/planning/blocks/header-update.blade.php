<?php use \Carbon\Carbon;?>
  <div class="row">
    <div class="col-md-12 col-xs-12 center text-left0 mobil-p0">
      <div class="col-md-8 mobil-p0">
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
            <?php if ($book->external_id){ 
              if($otaURL){
                echo '/ OTA-ID: <b><a href="'.$otaURL.'" target="_black">'. $book->external_id . "</a></b>"; 
              } else {
              echo "/ OTA-ID: <b>" . $book->external_id . "</b>"; 
              }
            }?>
            <span style="display: none">{{$book->bkg_number}}</span>
            
          </h5>
          
        </div>
          
        <div class="row">
          @if (trim($book->customer->phone) != '')
          <div class="icon-lst">
            <a href="tel:<?php echo $book->customer->phone ?>" class="btn" style="padding: 4px;">
              <i class="fa fa-phone  text-success fa-2x"></i>
            </a>
          </div>
          @endif
          <div class="icon-lst">
            <button class="btn" id="open_invoice" type="button" data-id="{{$book->id}}" title="Factura" style="padding: 4px;">
              <i class="fa fa-file-invoice fa-2x"></i>
            </button>
          </div>
          <div class="icon-lst">
            <a href="{{ url('/admin/pdf/pdf-reserva/'.$book->id) }}" title="Descargar reserva" target="_black" class="btn" style="background-image: url(/img/pdf.png) !important;"></a>
          </div>
           
          @if ($book->type_book == 2)
          <div class="icon-lst">
            <button class="btn open_modal_encuesta" type="button" data-id="{{$book->id}}" title="Enviar encuesta mail">
            </button>
          </div>
          @endif
          @if($partee)
          <div class="icon-lst partee-icon">
          <?php echo $partee->print_status($book->id, $book->start, $book->pax, true); ?>
          </div>
          @endif
          <?php
          $SafetyBox = $book->SafetyBox();
          $hasSafetyBox = 0;
          $safetyBoxClass = 'fa-lock';
          $titSafetyBox = 'Asignar Buzón';
          if ($SafetyBox && !$SafetyBox->deleted) {
            $hasSafetyBox = 1;
            $safetyBoxClass = 'fa-unlock';
            $titSafetyBox = isset($lstSafetyBox[$SafetyBox->box_id]) ? $lstSafetyBox[$SafetyBox->box_id] : '';
          }
          ?>
          <div class="icon-lst">
            <button class="btn openSafetyBox" type="button" data-id="{{$book->id}}" title="{{$titSafetyBox}}" style="padding: 4px;">
                <i class="fa {{$safetyBoxClass}} fa-2x" ></i>
            </button>
          </div>
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
          <div class="icon-lst">
            <span class="cliHas <?= ($cliHasPhotos) ? 'active' : ''; ?>" title="Fotos <?= ($cliHasPhotos) ? '' : 'NO '; ?>enviadas al cliente" data-id="{{$book->id}}" data-t="photos">
              <i class="fas fa-camera"></i>
            </span>
          </div>
          <div class="icon-lst">
            <span class="cliHas <?= ($cliHasBed) ? 'active' : ''; ?>" title=" <?= ($cliHasBed) ? 'CON' : 'SIN'; ?> CAMAS SUPLETORIAS" data-id="{{$book->id}}" data-t="beds">
              <i class="fas fa-bed"></i>
            </span>
          </div>
          <?php $book->printExtraIcon();?>
          
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
      <div class="col-md-4 col-xs-12" style="max-height: 195px; overflow: auto;">
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