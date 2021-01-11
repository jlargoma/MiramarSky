<?php

use \Carbon\Carbon; ?>
<div class="row">
    <div class="col-md-12 col-xs-12 center text-left0 mobil-p0">
        <div class="col-md-6 mobil-p0">
            <div class="col-md-9 col-xs-12">
                <div class="" id="headerFixed">
                    <a href="{{ url('/admin/reservas') }}" class=" m-b-10 mobile-right"  style="min-width: 10px!important">
                        <img src="{{ asset('/img/miramarski/iconos/close.png') }}" style="width: 20px"/>
                    </a>
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
                        <?php
                        if ($book->external_id) {
                          if ($otaURL) {
                            echo '/ OTA-ID: <b><a href="' . $otaURL . '" target="_black">' . $book->external_id . "</a></b>";
                          } else {
                            echo "/ OTA-ID: <b>" . $book->external_id . "</b>";
                          }
                        }
                        ?>
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
                        <a href="{{ url('/admin/pdf/pdf-reserva/'.$book->id) }}" title="Descargar reserva" target="_black" class="btn" style="background-image: url(/img/pdf.png) !important;"></a>
                    </div>
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
                </div>
            </div>
            <div class="col-md-3 col-xs-12 content-guardar" style="padding: 20px 0;">
                <strong>Estado:</strong>
                <div id="overlay" style="display: none;"></div>
                @if($book->type_book == 0)
                <select class="form-control" disabled style="font-weight: 600;">
                    <option style=""><strong></strong>Eliminado</option>
                </select>
                @else
                
<?php echo $book->getStatus($book->type_book) ?>
                @endif
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
        </div>
    </div>
</div>