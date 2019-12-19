@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts')
<link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css"/>

<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"
      media="screen">

<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css"/>
<style>
  .pgn-wrapper[data-position$='-right'] {
    right: 82% !important;
  }

  input[type=number]::-webkit-outer-spin-button,
  input[type=number]::-webkit-inner-spin-button {

    -webkit-appearance: none;

    margin: 0;

  }

  input[type=number] {

    -moz-appearance: textfield;

  }

  #overlay {
    position: absolute;
    left: 0;

    opacity: .1;
    background-color: blue;
    height: 35px;
    width: 100%;
  }

  .StripeElement {
    background-color: white;
    padding: 8px 12px;
    border-radius: 4px;
    border: 1px solid transparent;
    box-shadow: 0 1px 3px 0 #e6ebf1;
    -webkit-transition: box-shadow 150ms ease;
    transition: box-shadow 150ms ease;
  }

  .StripeElement--focus {
    box-shadow: 0 1px 3px 0 #cfd7df;
  }

  .StripeElement--invalid {
    border-color: #fa755a;
  }

  .StripeElement--webkit-autofill {
    background-color: #fefde5 !important;
  }

  .stripe-price {
    background-color: white !important;
    padding: 8px 12px !important;
    border-radius: 4px !important;
    border: 1px solid transparent !important;
    box-shadow: 0 1px 3px 0 #e6ebf1 !important;
    -webkit-transition: box-shadow 150ms ease !important;
    transition: box-shadow 150ms ease !important;
  }

</style>
<script type="text/javascript" src="{{ assetV('/js/partee.js')}}"></script>
@endsection

@section('content')
<?php

use \Carbon\Carbon;
use App\Classes\Mobile;

$mobile = new Mobile();
?>
@if($errors->any())
<p class="alert alert-danger">{{$errors->first()}}</p>
@endif
@if (\Session::has('success'))
<p class="alert alert-success">{!! \Session::get('success') !!}</p>
@endif
<div class="container-fluid padding-10 sm-padding-10">
  <div class="row">
    <div class="col-md-12 col-xs-12 center text-left0">
      <div class="col-md-6">
        <div class="col-md-9 col-xs-12">
          @if( url()->previous() != "" )
          @if( url()->previous() == url()->current() )
          <a href="{{ url('/admin/reservas') }}" class=" m-b-10" style="min-width: 10px!important">
            <img src="{{ asset('/img/miramarski/iconos/close.png') }}" style="width: 20px"/>
          </a>
          @else
          <a href="{{ url()->previous() }}" class=" m-b-10" style="min-width: 10px!important">
            <img src="{{ asset('/img/miramarski/iconos/close.png') }}" style="width: 20px"/>
          </a>
          @endif
          @else
          <a href="{{ url('/admin/reservas') }}" class=" m-b-10"  style="min-width: 10px!important">
            <img src="{{ asset('/img/miramarski/iconos/close.png') }}" style="width: 20px"/>
          </a>
          @endif

          <h4 class="" style="line-height: 1; letter-spacing: -1px">
<?php echo "<b>" . strtoupper($book->customer->name) . "</b>" ?> creada el
<?php $fecha = Carbon::createFromFormat('Y-m-d H:i:s', $book->created_at); ?>
            <br>
            <span class="font-s18"><?php echo $fecha->copy()->formatLocalized('%d %B %Y') . " Hora: " . $fecha->copy()->format('H:m') ?></span>
          </h4>
          <h5>
            Creado por <?php echo "<b>" . strtoupper($book->user->name) . "</b>" ?>
            / ID: <?php echo "<b>" . $book->id . "</b>" ?>
          </h5>
<?php if ($book->type_book == 2): ?>
            <div class="col-md-2 col-xs-3 icon-lst">
              <a href="{{ url('/admin/pdf/pdf-reserva/'.$book->id) }}">
                <img src="/img/pdf.png"
                     style="width: 50px; float:left; margin: 0 auto;">
              </a>
            </div>
            <div class="col-md-2 col-xs-3 icon-lst">
  <?php $text = "Hola, esperamos que hayas disfrutado de tu estancia con nosotros." . "\n" . "Nos gustaria que valorarás, para ello te dejamos este link : https://www.apartamentosierranevada.net/encuesta-satisfaccion/" . base64_encode($book->id);
  ?>

              <a href="whatsapp://send?text=<?php echo $text; ?>"
                 data-action="share/whatsapp/share"
                 data-original-title="Enviar encuesta de satisfacción"
                 data-toggle="tooltip">
                <i class="fa fa-share-square fa-3x" aria-hidden="true"></i><br>Encuesta
              </a>
            </div>
<?php endif ?>
          <div class="col-md-2 col-xs-3 icon-lst">
            <a href="tel:<?php echo $book->customer->phone ?>"
               style="width: 50px; float:left;">
              <i class="fa fa-phone  text-success"
                 style="font-size: 48px;"></i>
            </a>
          </div>
          <div class="col-md-2 col-xs-3 icon-lst hidden-lg hidden-md">
            <h2 class="text-center"
                style="font-size: 18px; line-height: 18px; margin: 0;">
<?php $text = "En este link podrás realizar el pago de la señal por el 25% del total." . "\n" . " En el momento en que efectúes el pago, te legará un email confirmando tu reserva - https://www.apartamentosierranevada.net/reservas/stripe/pagos/" . base64_encode($book->id);
?>

              <a href="whatsapp://send?text=<?php echo $text; ?>"
                 data-action="share/whatsapp/share">
                <i class="fa fa-eye fa-2x" aria-hidden="true"></i>
              </a>
            </h2>
          </div>

          <div class="col-md-2 col-xs-3 icon-lst hiddenOnlyRiad showFF_resume">
            
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
          <div class="col-md-4 col-xs-6 icon-lst partee-icon" style="position:relative">
            <?php
            $policeman = 'error';
            $partee = $book->partee();
             if ($partee):
              echo $partee->print_status($book->id,$book->start,$book->pax,true);
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
          <select class="status form-control minimal" data-id="<?php echo $book->id ?>" name="status" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
            <?php for ($i = 1; $i <= 12; $i++): ?>
              <?php if ($i == 5 && $book->customer->email == ""): ?>
              <?php else: ?>
                <option <?php echo $i == ($book->type_book) ? "selected" : ""; ?>
                    <?php echo ($i == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                  value="<?php echo $i ?>" data-id="<?php echo $book->id ?>">
                <?php echo $book->getStatus($i) ?>

                </option>
              <?php endif ?>
            <?php endfor; ?>
                <option <?php echo 99 == ($book->type_book) ? "selected" : ""; ?>
                  value="<?php echo 99 ?>" data-id="<?php echo $book->id ?>">
                <?php echo $book->getStatus(99) ?>
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
<?php if (!$mobile->isMobile()): ?>
    <div class="row center text-center">
      <!-- DATOS DE LA RESERVA -->
      <div class="col-md-6 col-xs-12">
        <div class="overlay loading-div" style="background-color: rgba(255,255,255,0.6); ">
          <div style="position: absolute; top: 50%; left: 35%; width: 40%; z-index: 1011; color: #000;">
            <i class="fa fa-spinner fa-spin fa-5x"></i><br>
            <h3 class="text-center font-w800" style="letter-spacing: -2px;">CALCULANDO...</h3>
          </div>
        </div>
        <form role="form" id="updateForm"
              action="{{ url('/admin/reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post">
          <textarea id="computed-data" style="display: none"></textarea>
          <input id="book-id" type="hidden" name="book_id" value="{{ $book->id }}">
          <!-- DATOS DEL CLIENTE -->
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="customer_id" value="<?php echo $book->customer->id ?>">
          <div class="col-xs-12 bg-white padding-block push-0" style="padding-bottom:0">
            <div class="col-xs-12 bg-black push-20">
              <h4 class="text-center white">
                DATOS DEL CLIENTE
              </h4>
            </div>

            <div class="col-md-4 push-10">
              <label for="name">Nombre</label>
              <input class="form-control cliente" type="text" name="nombre"
                     value="<?php echo $book->customer->name ?>"
                     data-id="<?php echo $book->customer->id ?>"  <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
            </div>
            <div class="col-md-4 push-10">
              <label for="email">Email</label>
              <input class="form-control cliente" type="email" name="email"
                     value="<?php echo $book->customer->email ?>"
                     data-id="<?php echo $book->customer->id ?>" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
            </div>
            <div class="col-md-4 push-10">
              <label for="phone">Telefono</label>
              <?php if ($book->customer->phone == 0): ?>
    <?php $book->customer->phone = "" ?>
  <?php endif ?>
              <input class="form-control only-numbers cliente" type="text" name="phone"
                     value="<?php echo $book->customer->phone ?>"
                     data-id="<?php echo $book->customer->id ?>" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
            </div>
          </div>
          <div class="col-xs-12 bg-white">
            <div class="col-md-3 col-xs-12 push-10">
              <label for="dni">DNI</label>
              <input class="form-control cliente" type="text" name="dni"
                     value="<?php echo $book->customer->DNI ?>" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
            </div>
            <div class="col-md-3 col-xs-12 push-10">
              <label for="address">DIRECCION</label>
              <input class="form-control cliente" type="text" name="address"
                     value="<?php echo $book->customer->address ?>" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
            </div>
            <div class="col-md-3 col-xs-12 push-10">
              <label for="country">PAÍS</label>
              <select class="form-control country minimal" name="country" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                <option value="">--Seleccione país --</option>
                <?php foreach (\App\Countries::orderBy('code', 'ASC')->get() as $country): ?>
                  <option value="<?php echo $country->code ?>" <?php
                          if ($country->code == $book->customer->country) {
                            echo "selected";
                          }
                          ?>>
    <?php echo $country->country ?>
                  </option>
  <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3 col-xs-12 push-10 content-cities">
              <label for="city">CIUDAD</label>
              <select class="form-control city minimal" name="city" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                <option>--Seleccione ciudad --</option>
                <?php foreach (\App\Cities::orderBy('city', 'ASC')->get() as $city): ?>
                  <option value="<?php echo $city->id ?>" <?php
                            if ($city->id == $book->customer->city) {
                              echo "selected";
                            }
                            ?>>
    <?php echo $city->city ?>
                  </option>
  <?php endforeach; ?>
              </select>
            </div>
          </div>
          <!-- DATOS DE LA RESERVA -->
          <div class="col-md-12 col-xs-12 bg-white padding-block" style="padding-bottom:0">
            <div class="col-xs-12 bg-black push-20">
              <h4 class="text-center white">
                DATOS DE LA RESERVA
                <i class="fas fa-sync-alt" id="reset"
                   style="cursor:pointer; position:absolute; right:2rem"></i>
              </h4>
            </div>
            <div class="col-md-4 push-10">
              <label>Entrada</label>
              <div class="input-prepend input-group">
                <?php
                $start1 = Carbon::createFromFormat('Y-m-d', $book->start)->format('d M, y');
                // $start1 = str_replace('Apr','Abr',$start->format('d M, y'));
                $finish1 = Carbon::createFromFormat('Y-m-d', $book->finish)->format('d M, y');
                // $finish1 = str_replace('Apr','Abr',$finish->format('d M, y'));
                ?>

                <input type="text" class="form-control daterange1" id="fechas" name="fechas" required=""
                       style="cursor: pointer; text-align: center; backface-visibility: hidden;min-height: 28px;"
                       value="<?php echo $start1; ?> - <?php echo $finish1 ?>" readonly="" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>

              </div>
            </div>
            <div class="col-md-1 col-xs-3 push-10 p-l-0">
              <label>Noches</label>
              <input type="number" class="form-control nigths" name="nigths" style="width: 100%" disabled
                     value="<?php echo $book->nigths ?>">
              <input type="hidden" class="form-control nigths" name="nigths" style="width: 100%"
                     value="<?php echo $book->nigths ?>">
            </div>
            <div class="col-md-2 col-xs-3">
              <label>Pax</label>
              <select class=" form-control pax minimal" name="pax" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                <?php for ($i = 1; $i <= 14; $i++): ?>
                    <option value="<?php echo $i ?>" <?php echo ($i == $book->pax) ? "selected" : ""; ?>>
                      <?php echo $i ?>
                    </option>
                <?php endfor; ?>
              </select>

            </div>
            <div class="col-md-2 col-xs-3 ">
              <label style="color: red">Pax-Real</label>
              <select class=" form-control real_pax minimal" name="real_pax" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                        <?php for ($i = 1; $i <= 14; $i++): ?>
                  <?php if ($i != 9 && $i != 11): ?>
                    <option value="<?php echo $i ?>"
                    <?php echo ($i == $book->real_pax) ? "selected" : ""; ?> style="color: red">
      <?php echo $i ?>
                    </option>
    <?php endif; ?>
  <?php endfor; ?>
              </select>
            </div>
            <div class="col-md-3 col-xs-6 push-10">
              <label>Apartamento</label>

              <select class="form-control full-width minimal newroom" name="newroom" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                      id="newroom" <?php if (isset($_GET['saveStatus']) && !empty($_GET['saveStatus'])): echo "style='border: 1px solid red'";
  endif ?>>
                <?php foreach ($rooms as $room): ?>
                  <option data-size="<?php echo $room->sizeApto ?>"
                          data-luxury="<?php echo $room->luxury ?>"
                          <?php if ($room->state==0) echo 'disabled'; ?>
                          value="<?php echo $room->id ?>" {{ $room->id == $book->room_id ? 'selected' : '' }} >
    <?php echo substr($room->nameRoom . " - " . $room->name, 0, 15) ?>
                  </option>
  <?php endforeach ?>
              </select>
            </div>

            <div class="col-md-2 col-xs-6 push-20 hiddenOnlyRiad">
              <label>Parking</label>
              <select class=" form-control parking minimal" name="parking" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
  <?php for ($i = 1; $i <= 4; $i++): ?>
                  <option value="<?php echo $i ?>" {{ $book->type_park == $i ? 'selected' : '' }}><?php echo $book->getParking($i) ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="col-md-2 col-xs-6 push-20 hiddenOnlyRiad">
              <label>Sup. Lujo</label>
              <select class=" form-control full-width type_luxury minimal" name="type_luxury" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
  <?php for ($i = 1; $i <= 4; $i++): ?>
                  <option value="<?php echo $i ?>" {{ $book->type_luxury == $i ? 'selected' : '' }}><?php echo $book->getSupLujo($i) ?></option>
  <?php endfor; ?>
              </select>
            </div>
            <div class="col-md-2 col-xs-6 push-20">
              <label>IN</label>
              <select id="schedule" class="form-control minimal" style="width: 100%;" name="schedule" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                <option>-- Sin asignar --</option>
                          <?php for ($i = 0; $i < 24; $i++): ?>
                  <option value="<?php echo $i ?>" <?php
                        if ($i == $book->schedule) {
                          echo 'selected';
                        }
                        ?>>
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
            </div>
            <div class="col-md-2 col-xs-6 push-20">
              <label>Out</label>
              <select id="scheduleOut" class="form-control minimal" style="width: 100%;"
                      name="scheduleOut" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                <option>-- Sin asignar --</option>
                          <?php for ($i = 0; $i < 24; $i++): ?>
                  <option value="<?php echo $i ?>" <?php
                    if ($i == $book->scheduleOut) {
                      echo 'selected';
                    }
                    ?>>
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
            </div>
          </div>
          <div class="col-xs-12 bg-white">
            <div class="col-md-6 col-xs-12 push-20 not-padding">
              <div class="col-md-6 col-xs-6 push-10">
                <label>Agencia</label>
                <select class="form-control full-width agency minimal" name="agency" >
              @include('backend.blocks._select-agency', ['agencyID'=>$book->agency,'book' => $book])
                </select>
              </div>
              <div class="col-md-6 col-xs-6 push-10">
                <label>Cost Agencia</label>
  <?php if ($book->PVPAgencia == 0.00): ?>
                  <input type="number" step='0.01' class="agencia form-control" name="agencia" value="" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
  <?php else: ?>
                  <input type="number" step='0.01' class="agencia form-control" name="agencia" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                         value="<?php echo $book->PVPAgencia ?>">
  <?php endif ?>
              </div>
            </div>
            <div class="col-md-3 col-xs-6 push-20 ">
              <label>promoción 3x2</label>
              <input type="number" step='0.01' class="promociones only-numbers form-control" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                     name="promociones"
                     value="<?php echo ($book->promociones > 0) ? $book->promociones : "" ?>">
            </div>
            <div class="col-md-3 col-xs-6 push-20 hiddenOnlyRiad">
              <label>DTO FF</label>
              <div class="row">
                <div class="col-xs-6">
                  <span class="input-group-addon">
                        <input type="checkbox" name="has_ff_discount" id="has_ff_discount"  data-init-plugin="switchery" data-size="small" data-color="primary" <?php echo ($book->has_ff_discount == 0) ? "" : "checked" ?>/>
                    </span>
                </div>
                <div class="col-xs-6">
                   <input type="text"  class="only-numbers form-control"  name="ff_discount" id="ff_discount"  value="{{$book->ff_discount}}">
              <input type="hidden" name="old_ff_discount" id="old_ff_discount"    value="{{$book->ff_discount}}">
                </div>
              </div>
             
               
            </div>
  <?php if ($book->book_owned_comments != "" && $book->promociones != 0): ?>
              <div class="col-md-2 col-xs-6 push-10 content_image_offert">
                <img src="/pages/oferta.png" style="width: 90px;">
              </div>
  <?php endif ?>

            <div class="col-md-12 col-xs-12 push-20 not-padding">
              <div class="col-md-3 col-xs-12 text-center boxtotales" style="background-color: #0c685f;">
                <label class="font-w800 text-white" for="">PVP</label>
                <input type="number" step='0.01' class="form-control total m-t-10 m-b-10 white" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                       name="total" value="<?php echo $book->total_price ?>"
                       data-edited="<?php if ($book->real_price != $book->total_price): echo '1';
  else: echo '0';
  endif ?>">
              </div>
  <?php if (Auth::user()->role == "admin"): ?>
                <div class="col-md-3 col-xs-12 text-center boxtotales" style="background: #99D9EA;">
                  <label class="font-w800 text-white" for="">COSTE TOTAL</label>
                  <input type="number" step='0.01' class="form-control cost m-t-10 m-b-10 white"
                         name="cost" value="<?php echo $book->cost_total ?>">
                </div>
                <div class="col-md-3 col-xs-12 text-center boxtotales" style="background: #91cf81;">
                  <label class="font-w800 text-white" for="">COSTE APTO</label>
                  <input type="number" step='0.01' class="form-control costApto m-t-10 m-b-10 white"
                         name="costApto" value="<?php echo $book->cost_apto ?>">
                </div>
                <div class="col-md-3 col-xs-12 text-center boxtotales" style="background: #337ab7;">
                  <label class="font-w800 text-white" for="">COSTE PARKING</label>
                  <input type="number" step='0.01' class="form-control costParking m-t-10 m-b-10 white"
                         name="costParking" value="<?php echo $book->cost_park ?>">
                </div>
                <div class="col-md-3 col-xs-12 text-center boxtotales not-padding"
                     style="background: #ff7f27;">
                  <label class="font-w800 text-white" for="">BENEFICIO</label>
                  <input type="number" step='0.01'
                         class="form-control text-left beneficio m-t-10 m-b-10 white" name="beneficio"
                         value="<?php echo $book->total_ben ?>">
                  <div class="beneficio-text font-w400 font-s18 white"><?php echo number_format($book->inc_percent, 0) . "%" ?></div>
                </div>
  <?php endif ?>

            </div>
            <?php if (Auth::user()->role == "admin"): ?>
            <div class="col-md-12 col-xs-12 push-20 not-padding">
            <p>Cáculos desde nueva fórmula</p>
              <?php
              $price = $book->getPriceBook($book->start,$book->finish,$book->room_id,$book->real_pax);
              if ($price['status'] == 'error'){
                echo '<p class="alert alert-warning">'.$price['msg'].'</p>';
              }
//              dd($price);
              ?>
              <div class="col-md-2 text-white" style="background-color: #0c685f;">
                PVP<br>{{$price['t_price']}}
              </div>
              <div class="col-md-2 text-white" style="background-color: #99D9EA;">
                COSTE TOTAL<br>{{$price['t_cost']}}
              </div>
              <div class="col-md-2 text-white" style="background-color: #91cf81;">
                COSTE APTO<br>{{$price['cost']}}
              </div>
              <div class="col-md-3 text-white" style="background-color: #337ab7;">
                COSTE PARKING<br>{{$price['parking_cost']}}
              </div>
              <div class="col-md-3 text-white" style="background-color: #ff7f27;">
                BENEFICIO<br>{{$price['t_benef']}}<br/>
                <small>(<?php echo number_format($price['benef'], 0) . "%" ?>)</small>
              </div>
            </div>
            <?php endif ?>
            
            <div class="col-md-12 col-xs-12 push-20 not-padding">
              <p class="personas-antiguo" style="color: red">
  <?php if ($book->pax < $book->room->minOcu): ?>
                  Van menos personas que la ocupacion minima del apartamento.
  <?php endif ?>
              </p>
              @if ($book->real_price != $book->total_price)
              <div class="col-xs-9">
                <table class="table-striped price-references-table">
                  <tbody>
                    <tr>
                      <td>PVP REAL GUARDADO EN BD</td>
                      <td class="price-references"
                          id="saved-real-price">{{ $book->real_price }}</td>
                    </tr>
                    <tr>
                      <td>PVP MODIFICADO MANUALMENTE</td>
                      <td class="price-references"
                          id="modified-price">{{ $book->total_price }}</td>
                    </tr>
                    <tr>
                      <td>PVP REAL GENERADO DINÁMICAMENTE</td>
                      <td class="price-references" id="real-price">{{ $book->real_price }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-xs-3">
                <i id="arrow-price-modification"></i>
              </div>
              @endif
            </div>
            <div class="col-md-12 col-xs-12 not-padding text-left">
              <p class="precio-antiguo font-s18">
              <!--El precio asignado-<b>El precio asignado <?php echo $book->total_price ?> y el precio de tarifa es <?php echo $book->real_price ?></b> -->
              </p>
            </div>
            <div class="col-xs-12 bg-white padding-block">
              <div class="col-md-4 col-xs-12">
                <label>Comentarios Cliente </label>
                <textarea class="form-control" name="comments" rows="5"
                          data-idBook="<?php echo $book->id ?>"
                          data-type="1"><?php echo $book->comment ?></textarea>
              </div>
              <div class="col-md-4 col-xs-12">
                <label>Comentarios Internos</label>
                <textarea class="form-control book_comments" name="book_comments" rows="5"
                          data-idBook="<?php echo $book->id ?>"
                          data-type="2"><?php echo $book->book_comments ?></textarea>
              </div>
              <div class="col-md-4 col-xs-12 content_book_owned_comments">
                <label>Comentarios Propietario</label>
                <textarea class="form-control book_owned_comments" name="book_owned_comments" rows="5"
                          data-idBook="<?php echo $book->id ?>"
                          data-type="3"><?php if (!empty($book->book_owned_comments)): ?><?php echo $book->book_owned_comments ?><?php endif; ?></textarea>
              </div>
            </div>
            <div class="row push-40 bg-white padding-block">
              <div class="col-md-4 col-md-offset-4 text-center">
                <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit"
                        style="min-height: 50px;width: 100%;" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>Guardar
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="col-md-6 col-xs-12 padding-block">
  <?php if (Auth::user()->role != "limpieza"): ?>
          <div class="row">
            <div class="col-xs-12 bg-black push-0">
              <h4 class="text-center white">
                {{ $totalpayment }}€ COBRADO
              </h4>
            </div>
            <table class="table table-hover demo-table-search table-responsive-block" style="margin-top: 0;">
              <thead>
                <tr>
                  <th class="text-center bg-success text-white" style="width:25%">fecha</th>
                  <th class="text-center bg-success text-white" style="width:25%">importe</th>
                  <th class="text-center bg-success text-white" style="width:30%">Tipo</th>
                  <th class="text-center bg-success text-white" style="width:20%">comentario</th>
                  <th class="text-center bg-success text-white" style="width:20%">Eliminar</th>

                </tr>
              </thead>
              <tbody><?php $total = 0; ?>
    <?php if (count($payments) > 0): ?>

      <?php foreach ($payments as $payment): ?>
                    <tr>
                      <td class="text-center p-t-25">
        <?php
        $fecha = new Carbon($payment->datePayment);
        echo $fecha->format('d-m-Y')
        ?>
                      </td>
                      <td class="text-center">
                        <input class="editable payment-<?php echo $payment->id ?> m-t-5" type="text" name="cost"
                               data-id="<?php echo $payment->id ?>" value="<?php echo $payment->import ?>"
                               style="width: 50%;text-align: center;border-style: none none ">€
                      </td>
                      <td class="text-center p-t-25"><?php echo $typecobro->getTypeCobro($payment->type) ?> </td>

                      <td class="text-center p-t-25"><?php echo $payment->comment ?></td>
                      <td>
                        <a href="{{ url('/admin/reservas/deleteCobro/')}}/<?php echo $payment->id ?>"
                           class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title=""
                           data-original-title="Eliminar Cobro"
                           onclick="return confirm('¿Quieres Eliminar el obro?');">
                          <i class="fa fa-trash"></i>
                        </a>
                      </td>
                    </tr>
        <?php $total = $total + $payment->import ?>
      <?php endforeach ?>
                  <tr>
                    <td class="text-center">
                      <div class="input-daterange input-group" id="datepicker-range">
                        <input type="text" class="input-sm form-control fecha-cobro" name="start"
                               data-date-format="dd-mm-yyyy"
                               value="<?php $hoy = Carbon::now();
      echo $hoy->format('d/m/Y') ?>">
                      </div>
                    </td>
                    <td class="text-center">
                      <input class="importe m-t-5" type="number" name="importe"
                             style="width: 100%;text-align: center;border-style: none none ">
                    </td>
                    <td class="text-center">
                      <select class="full-width select2-hidden-accessible type_payment"
                              data-init-plugin="select2" name="type_payment" tabindex="-1" aria-hidden="true">
                            <option value="0"><?php echo $book->getTypeCobro(0) ?></option>
                            <option value="2"><?php echo $book->getTypeCobro(2) ?></option>
                      </select>
                    </td>
                    <td class="text-center">
                      <input class="comment" type="text" name="comment"
                             style="width: 100%;text-align: center;border-style: none">
                    </td>
                    <td>
                    </td>

                  </tr>
    <?php else: ?>
                  <tr>
                    <td class="text-center">
                      <div class="input-daterange input-group" id="datepicker-range">
                        <input type="text" class="input-sm form-control fecha-cobro" name="start"
                               data-date-format="dd-mm-yyyy"
                               value="<?php $hoy = Carbon::now();
      echo $hoy->format('d/m/Y') ?>">
                      </div>
                    </td>
                    <td class="text-center">
                      <input class="importe m-t-5" type="text" name="importe"
                             style="width: 100%;text-align: center;border-style: none">
                    </td>
                    <td class="text-center">
                      <select class="full-width select2-hidden-accessible type_payment"
                              data-init-plugin="select2" name="type_payment" tabindex="-1" aria-hidden="true">
                            <option value="0"><?php echo $book->getTypeCobro(0) ?></option>
                            <option value="2"><?php echo $book->getTypeCobro(2) ?></option>
                      </select>
                    </td>
                    <td class="text-center">
                      <input class="comment m-t-5" type="text" name="comment"
                             style="width: 100%;text-align: center;border-style: none">
                    </td>

                  </tr>
    <?php endif ?>
                <tr>
                  <td></td>
                  <?php if ($total < $book->total_price): ?>
                    <td class="text-center"><p
                        style="color:red;font-weight: bold;font-size:15px"><?php echo $total - $book->total_price ?>
                        €</p></td>
                    <td class="text-left" colspan="2"><p style="color:red;font-weight: bold;font-size:15px">
                        Pendiente de pago</p></td>
    <?php elseif ($total > $book->total_price): ?>
                    <td class="text-center"><p
                        style="color:black;font-weight: bold;font-size:15px"><?php echo $total - $book->total_price ?>
                        €</p></td>
                    <td class="text-left" colspan="2">Sobrante</td>
    <?php else: ?>
                    <td class="text-center"><p style="color:black;font-weight: bold;font-size:15px">0€</p></td>
                    <td class="text-left" colspan="2">Al corriente de pago</td>
    <?php endif ?>

                </tr>
              </tbody>
            </table>
            <input type="button" name="cobrar" class="btn btn-success  m-t-10 cobrar" value="GUARGAR"
                   data-id="<?php echo $book->id ?>" style="width: 30%;min-height: 50px">
          </div>

  <?php endif ?>
        <div class="row">

          <div class="col-xs-12 push-20 ">
            <?php if ($book->type_book == 2): ?>
              <?php if (!$hasFiance): ?>
                <div class="col-md-6">

                  <button class="btn btn-primary btn-lg" type="button" id="fianza"> COBRAR FIANZA</button>
                </div>
    <?php else: ?>
                <div class="col-md-6">
                  <a class="btn btn-primary btn-lg"
                     href="{{ url('/admin/reservas/fianzas/cobrar/'.$book->id) }}"> RECOGER FIANZA</a>
                </div>
    <?php endif ?>
  <?php endif ?>
          </div>
  <?php if ($book->type_book == 2): ?>
            <div class="row content-fianza" >
    <?php if ($hasFiance): ?>
                <div class="col-md-6 col-md-offset-3 alert alert-info fade in alert-dismissable" style="margin-top: 30px; background-color: #10cfbd70!important;">
                  <h3 class="text-center font-w300"> CARGAR LA FIANZA DE <span class="font-w800"><?php echo($hasFiance->amount / 100) ?> €</span>
                  </h3>
                  <div class="row">
                    <form action="{{ url('admin/reservas/stripe/pay/fianza') }}" method="post">
                      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                      <input type="hidden" name="id_fianza" value="<?php echo $hasFiance->id; ?>">
                      <div class="col-xs-12 text-center">
                        <button class="btn btn-primary">COBRAR</button>
                      </div>
                    </form>
                  </div>
                </div>
    <?php endif ?>

            </div>
  <?php endif; ?>
        </div>
  <?php if (Auth::user()->role != "limpieza"): ?>
          <div class="row">
            @include('Paylands.payment', ['routeToRedirect' => route('payland.thanks.payment',
            ['id' => $book->id]), 'id' => $book->id, 'customer' => $book->customer->id])
          </div>
  <?php endif ?>
        <div class="col-xs-12 bg-black push-0">
          <h4 class="text-center white">HISTORICO EMAILS CON EL CLIENTE <span id="loadchatbox">desplegar</span></h4>
        </div>
      <div id="chatbox" class="chat-lst">
        </div>
  <button class="btn btn-success btn-cons m-b-10" type="button"
          data-toggle="modal" data-target="#modalResponseEmail">Enviar Nueva Respuesta</button>
      </div>
    </div>
<?php else: ?>
    <style type="text/css">
      @media screen and (max-width: 767px) {
        .daterangepicker {
          position: fixed;
          top: 8% !important;
        }
      }
    </style>
    <div class="row">
      <div class="col-xs-12">
        <div class="overlay loading-div" style="background-color: rgba(255,255,255,0.4);">
          <div style="position: absolute; top: 50%; left: 35%;">
            <i class="fa fa-loading fa-spin fa-5x"></i><br>
            <h3 class="text-center">CALCULANDO...</h3>
          </div>
        </div>
        <!-- DATOS DE LA RESERVA -->
        <form role="form" id='updateForm'
              action="{{ url('/admin/reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post">
          <textarea id="computed-data" style="display:none"></textarea>
          <input id="book-id" type="hidden" name="book_id" value="{{ $book->id }}">
          <!-- DATOS DEL CLIENTE -->
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="customer_id" value="<?php echo $book->customer->id ?>">
          <div class="col-xs-12 bg-white padding-block">
            <div class="col-xs-12 bg-black push-20">
              <h4 class="text-center white">
                DATOS DEL CLIENTE
              </h4>
            </div>

            <div class="col-xs-12 push-10">
              <label for="name">Nombre</label>
              <input class="form-control cliente" type="text" name="nombre" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                     value="<?php echo $book->customer->name ?>"
                     data-id="<?php echo $book->customer->id ?>">
            </div>
            <div class="col-xs-12 push-10">
              <label for="email">Email</label>
              <input class="form-control cliente" type="email" name="email" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                     value="<?php echo $book->customer->email ?>"
                     data-id="<?php echo $book->customer->id ?>">
            </div>
  <?php if ($book->customer->phone == 0): ?>
    <?php $book->customer->phone = "" ?>
  <?php endif ?>
            <div class="col-xs-12 push-10">
              <label for="phone">Telefono</label>
              <input class="form-control only-numbers cliente" type="text" name="phone" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                     value="<?php echo $book->customer->phone ?>"
                     data-id="<?php echo $book->customer->id ?>">
            </div>
            <div class="col-xs-12 push-10">
              <label for="dni">DNI</label>
              <input class="form-control cliente" type="text" name="dni" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                     value="<?php echo $book->customer->DNI ?>">
            </div>
            <div class="col-xs-12 push-10">
              <label for="address">DIRECCION</label>
              <input class="form-control cliente" type="text" name="address" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                     value="<?php echo $book->customer->address ?>">
            </div>
            <div class="col-xs-12 push-10">
              <label for="country">PAÍS</label>
              <select class="form-control country minimal" name="country" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                <option value="">--Seleccione país --</option>
  <?php foreach (\App\Countries::orderBy('code', 'ASC')->get() as $country): ?>
                  <option value="<?php echo $country->code ?>" <?php
    if ($country->code == $book->customer->country) {
      echo "selected";
    }
    ?>>
                  <?php echo $country->country ?>
                  </option>
                        <?php endforeach; ?>
              </select>
            </div>
            <div class="col-xs-12 push-10 content-cities">
              <label for="city">CIUDAD</label>
              <select class="form-control city minimal" name="city" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                <option>--Seleccione ciudad --</option>
  <?php foreach (\App\Cities::where('code_country', $book->customer->country)
          ->orderBy('city', 'ASC')->get() as $city):
    ?>
                  <option value="<?php echo $city->id ?>" <?php
    if ($city->id == $book->customer->city) {
      echo "selected";
    }
    ?>>
    <?php echo $city->city ?>
                  </option>
  <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- DATOS DE LA RESERVA -->
          <div class="col-xs-12 bg-white padding-block">
            <div class="col-xs-12 bg-black push-20">
              <h4 class="text-center white">
                DATOS DE LA RESERVA
                <i class="fas fa-sync-alt" id="reset"
                   style="cursor:pointer; position:absolute; font-size:1rem; right:2%; transform: translateY(50%)"></i>
              </h4>
            </div>
            <div class="col-md-4  col-xs-8 push-20">
              <label>Entrada</label>
  <?php $start1 = Carbon::createFromFormat('Y-m-d', $book->start)->format('d M, y');
  // $start1 = str_replace('Apr','Abr',$start->format('d M, y')); 
  ?>
  <?php $finish1 = Carbon::createFromFormat('Y-m-d', $book->finish)->format('d M, y');
  // $finish1 = str_replace('Apr','Abr',$finish->format('d M, y')); 
  ?>

              <input type="text" class="form-control daterange1" id="fechas" name="fechas" required=""
                     style="cursor: pointer; text-align: center; backface-visibility: hidden;min-height: 28px;"
                     value="<?php echo $start1; ?> - <?php echo $finish1 ?>" readonly="" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
            </div>
            <div class="col-md-1 col-xs-4 push-20 ">
              <label>Noches</label>
              <input type="number" class="form-control nigths" name="nigths" style="width: 100%" disabled
                     value="<?php echo $book->nigths ?>">
              <input type="hidden" class="form-control nigths" name="nigths" style="width: 100%"
                     value="<?php echo $book->nigths ?>">
            </div>
            <div class="col-md-1 col-xs-4 push-20 ">
              <label>Pax</label>
              <select class=" form-control pax minimal" name="pax" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
  <?php for ($i = 1; $i <= 14; $i++): ?>
                  <option value="<?php echo $i ?>" <?php echo ($i == $book->pax) ? "selected" : ""; ?>>
    <?php echo $i ?>
                  </option>
                        <?php endfor; ?>
              </select>
              <label class="m-t-20" style="color: red">Pax-Real</label>
              <select class=" form-control real_pax minimal" name="real_pax" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                          <?php for ($i = 1; $i <= 14; $i++): ?>
                  <option value="<?php echo $i ?>"
                  <?php echo ($i == $book->real_pax) ? "selected" : ""; ?> style="color: red">
                  <?php echo $i ?>
                  </option>
  <?php endfor; ?>
              </select>
            </div>
            <div class="col-md-3 col-xs-8 push-20">
              <label>Apartamento</label>

              <select class="form-control full-width minimal newroom" name="newroom" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                      id="newroom" <?php if (isset($_GET['saveStatus']) && !empty($_GET['saveStatus'])): echo "style='border: 1px solid red'";
              endif ?>>
  <?php foreach ($rooms as $room): ?>
                  <option data-size="<?php echo $room->sizeApto ?>"
                          " data-luxury="<?php echo $room->luxury ?>" value="<?php echo $room->id ?>
                          " {{ $room->id == $book->room_id ? 'selected' : '' }} >
                  <?php echo substr($room->nameRoom . " - " . $room->name, 0, 15) ?>
                  </option>
  <?php endforeach ?>
              </select>
            </div>
            <div class="col-md-1 col-xs-6 push-20 hiddenOnlyRiad">
              <label>Parking</label>
              <select class=" form-control parking minimal" name="parking" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                  <option value="<?php echo $i ?>" {{ $book->type_park == $i ? 'selected' : '' }}><?php echo $book->getParking($i) ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="col-md-2 col-xs-6 push-0 hiddenOnlyRiad">
              <label>Sup. Lujo</label>
              <select class=" form-control full-width type_luxury minimal" name="type_luxury" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                  <?php for ($i = 1; $i <= 4; $i++): ?>
                  <option value="<?php echo $i ?>" {{ $book->type_luxury == $i ? 'selected' : '' }}><?php echo $book->getSupLujo($i) ?></option>
                  <?php endfor; ?>
              </select>
            </div>
            <div class="col-md-2 col-xs-3 push-0" style="padding: 0 5px;">
              <label>IN</label>
              <select id="schedule" class="form-control " style="width: 100%;" name="schedule" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                <option>-- Sin asignar --</option>
  <?php for ($i = 0; $i < 24; $i++): ?>
                  <option value="<?php echo $i ?>" <?php
    if ($i == $book->schedule) {
      echo 'selected';
    }
    ?>>
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
            </div>
            <div class="col-md-2 col-xs-3 push-0" style="padding: 0 5px;">
              <label>Out</label>
              <select id="scheduleOut" class="form-control " style="width: 100%;" name="scheduleOut" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
                <option>-- Sin asignar --</option>
  <?php for ($i = 0; $i < 24; $i++): ?>
                  <option value="<?php echo $i ?>" <?php
    if ($i == $book->scheduleOut) {
      echo 'selected';
    }
    ?>>
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
            </div>
          </div>

          <div class="col-xs-12 bg-white">
            <div class="col-md-4 col-xs-12 push-20 not-padding">
              <div class="col-md-6 col-xs-6 push-10">
                <label>Agencia</label>
                <select class="form-control full-width agency minimal" name="agency" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
  <?php for ($i = 0; $i <= 7; $i++): ?>
                    <option value="<?php echo $i ?>"
                            {{ $book->agency == $i ? 'selected' : '' }} <?php if (Auth::user()->role == "agente" && $book->getAgency($i) == "S.essence"): ?>
                        selected<?php endif ?>>
    <?php echo $book->getAgency($i) ?>
                    </option>
  <?php endfor; ?>
                </select>
              </div>
              <div class="col-md-6 col-xs-6 push-10">
                <label>Cost Agencia</label>
            <?php if ($book->PVPAgencia == 0.00): ?>
                  <input type="number" step='0.01' class="agencia form-control" name="agencia" value="" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>>
  <?php else: ?>
                  <input type="number" step='0.01' class="agencia form-control" name="agencia" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                         value="<?php echo $book->PVPAgencia ?>">
  <?php endif ?>
              </div>
            </div>
            <div class="col-md-2 col-xs-6 push-20 ">
              <label>promoción 3x2</label>
              <input type="number" step='0.01' class="promociones only-numbers form-control" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                     name="promociones"
                     value="<?php echo ($book->promociones > 0) ? $book->promociones : "" ?>">
            </div>
            <div class="col-md-2 col-xs-6 push-20 hiddenOnlyRiad">
              <label>DTO FF</label>
              <input type="text"  class="promociones only-numbers form-control" disabled  value="{{$book->ff_discount}}">
            </div>

  <?php if ($book->book_owned_comments != "" && $book->promociones != 0): ?>
              <div class="col-md-2 col-xs-6 push-10 content_image_offert">
                <img src="/pages/oferta.png" style="width: 90px;">
              </div>
  <?php endif ?>

            <div class="col-md-8 col-xs-12 push-20 not-padding">
              <div class="col-md-3 col-xs-12 text-center" style="background-color: #0c685f;">
                <label class="font-w800 text-white" for="">PVP</label>
                <input type="number" step='0.01' class="form-control total m-t-10 m-b-10 white" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                       name="total" value="<?php echo $book->total_price ?>"
                       data-edited="<?php if ($book->real_price != $book->total_price): echo '1';
  else: echo '0';
  endif ?>">
              </div>
  <?php if (Auth::user()->role == "admin"): ?>

                <div class="col-md-3 col-xs-6 text-center" style="background: #91cf81;">
                  <label class="font-w800 text-white" for="">COSTE APTO</label>
                  <input type="number" step='0.01' class="form-control costApto m-t-10 m-b-10 white"
                         name="costApto" value="<?php echo $book->cost_apto ?>">
                </div>
                <div class="col-md-3 col-xs-6 text-center" style="background: #337ab7;">
                  <label class="font-w800 text-white" for="">COSTE PARKING</label>
                  <input type="number" step='0.01' class="form-control costParking m-t-10 m-b-10 white"
                         name="costParking" value="<?php echo $book->cost_park ?>">
                </div>
                <div class="col-md-3 col-xs-6 text-center" style="background: #99D9EA;">
                  <label class="font-w800 text-white" for="">COSTE TOTAL</label>
                  <input type="number" step='0.01' class="form-control cost m-t-10 m-b-10 white"
                         name="cost" value="<?php echo $book->cost_total ?>">
                </div>
                <div class="col-md-3 col-xs-6 text-center not-padding" style="background: #ff7f27;">
                  <label class="font-w800 text-white" style="width: 100%;" for="">BENEFICIO</label>
                  <input type="number" step='0.01'
                         class="form-control text-center beneficio m-t-10 m-b-10 white" name="beneficio"
                         value="<?php echo $book->total_ben ?>">
                  <div class="beneficio-text font-w400 font-s18 white">
    <?php echo number_format($book->inc_percent, 0) . "%" ?>
                  </div>
                </div>
  <?php endif ?>

            </div>

            <div class="col-md-8 col-xs-12 push-20 not-padding text-center">
              <p class="personas-antiguo" style="color: red">
  <?php if ($book->pax < $book->room->minOcu): ?>
                  Van menos personas que la ocupacion minima del apartamento.
  <?php endif ?>
              </p>
              @if ($book->real_price != $book->total_price)
              <div class="col-xs-9">
                <table class="table-striped price-references-table">
                  <tbody>
                    <tr>
                      <td>PVP REAL GUARDADO EN BD</td>
                      <td class="price-references"
                          id="saved-real-price">{{ $book->real_price }}</td>
                    </tr>
                    <tr>
                      <td>PVP MODIFICADO MANUALMENTE</td>
                      <td class="price-references"
                          id="modified-price">{{ $book->total_price }}</td>
                    </tr>
                    <tr>
                      <td>PVP REAL GENERADO DINÁMICAMENTE</td>
                      <td class="price-references" id="real-price">{{ $book->real_price }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-xs-3">
                <i id="arrow-price-modification"></i>
              </div>
              @endif
            </div>
            <div class="col-md-8 col-xs-12 not-padding text-left">
              <p class="precio-antiguo font-s18">
              <!-- <b>El precio asignado <?php echo $book->total_price ?> y el precio de tarifa es <?php echo $book->real_price ?></b> -->
              </p>
            </div>
            <div class="col-xs-12 bg-white padding-block">
              <div class="col-md-4 col-xs-12">
                <label>Comentarios Cliente </label>
                <textarea class="form-control" name="comments" rows="5"
                          data-idBook="<?php echo $book->id ?>"
                          data-type="1"><?php echo $book->comment ?></textarea>
              </div>
              <div class="col-md-4 col-xs-12">
                <label>Comentarios Internos</label>
                <textarea class="form-control book_comments" name="book_comments" rows="5"
                          data-idBook="<?php echo $book->id ?>"
                          data-type="2"><?php echo $book->book_comments ?></textarea>
              </div>
              <div class="col-md-4 col-xs-12 content_book_owned_comments">
                <label>Comentarios Propietario</label>
                <textarea class="form-control book_owned_comments" name="book_owned_comments" rows="5"
                          data-idBook="<?php echo $book->id ?>"
                          data-type="3"><?php if ($book->book_owned_comments != "" ): ?><?php echo $book->book_owned_comments ?><?php endif; ?></textarea>
              </div>
            </div>
            <div class="row push-40 bg-white padding-block">
              <div class="col-md-4 col-md-offset-4 col-xs-12 text-center">
                <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit" <?php if (Auth::user()->role == "limpieza"): ?>disabled<?php endif ?>
                        style="min-height: 50px;width: 100%;">Guardar reserva
                </button>
              </div>
            </div>
        </form>
      </div>

      <div class="col-md-6 col-xs-12 padding-block">
        <div class="row">
          <div class="col-xs-12 push-20 ">
  <?php if ($book->type_book == 2): ?>
    <?php if ($hasFiance): ?>
                <div class="col-md-6">
                  <button class="btn btn-primary btn-lg" type="button" id="fianza"> COBRAR FIANZA</button>
                </div>
    <?php else: ?>
                <div class="col-md-6">
                  <a class="btn btn-primary btn-lg"
                     href="{{ url('/admin/reservas/fianzas/cobrar/'.$book->id) }}"> RECOGER FIANZA</a>
                </div>
              <?php endif ?>
            <?php endif ?>
          </div>
          <?php if ($book->type_book == 2): ?>
            <div class="row content-fianza" style="display: none;">
          <?php if ($hasFiance): ?>
                <div class="col-md-6 col-md-offset-3 alert alert-info fade in alert-dismissable"
                     style="margin-top: 30px; background-color: #10cfbd70!important;">
                  <h3 class="text-center font-w300">
                    CARGAR LA FIANZA DE <span class="font-w800"><?php echo($hasFiance->amount / 100) ?>
                      €</span>
                  </h3>
                  <div class="row">
                    <form action="{{ url('admin/reservas/stripe/pay/fianza') }}" method="post">
                      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                      <input type="hidden" name="id_fianza" value="<?php echo $hasFiance->id; ?>">
                      <div class="col-xs-12 text-center">
                        <button class="btn btn-primary">COBRAR</button>
                      </div>
                    </form>
                  </div>
                </div>
    <?php endif ?>
            </div>
  <?php endif; ?>
        </div>
  <?php if (Auth::user()->role != "limpieza"): ?>
          <div class="row">
            <div class="col-xs-12 bg-black push-0">
              <h4 class="text-center white">
                {{ $totalpayment }}€ COBRADO
              </h4>
            </div>
            <div class="col-xs-12 not-padding">
              <div class="col-xs-4 not-padding bg-success text-white text-center" style="min-height: 50px">
                <span class="font-s18">Total:</span><br>
                <span class="font-w600 font-s18"><?php echo number_format($book->total_price, 2, ',', '.') ?>
                  €</span>
              </div>
              <div class="col-xs-4 not-padding bg-primary text-white text-center" style="min-height: 50px">
                <span class="font-s18">Cobrado:</span><br>
                <span class="font-w600 font-s18"><?php echo number_format($totalpayment, 2, ',', '.') ?>
                  €</span>
              </div>
              <div class="col-xs-4 not-padding bg-danger text-white text-center" style="min-height: 50px">
                <span class="font-s18">Pendiente:</span><br>
                <!-- si esta pendiente nada,.si esta de mas +X -->
                <span class="font-w600 font-s18"><?php echo ($book->total_price - $totalpayment) >= 0 ? "" : "+";
    echo number_format($totalpayment - $book->total_price, 2, ',', '.') ?>
                  €</span>
              </div>
            </div>
            <div class="col-md-12 table-responsive not-padding ">
              <table class="table  table-responsive table-striped" style="margin-top: 0;">
                <thead>
                  <tr>
                    <th class="text-center" style="min-width: 100px">fecha</th>
                    <th class="text-center" style="min-width: 100px">importe</th>
                    <th class="text-center" style="min-width: 200px">Tipo</th>
                    <th class="text-center" style="min-width: 100px">comentario</th>
                    <th class="text-center" style="width:20%">Eliminar</th>
                  </tr>
                </thead>
                <tbody>
    <?php $total = 0; ?>

    <?php foreach ($payments as $payment): ?>
                    <tr>
                      <td class="text-center">
                    <?php
                    $fecha = new Carbon($payment->datePayment);
                    echo $fecha->format('d-m-Y')
                    ?>
                      </td>
                      <td class="text-center">
      <?php echo $payment->import . " €" ?>
                      </td>
                      <td class="text-center"><?php echo $typecobro->getTypeCobro($payment->type) ?> </td>
                      <td class="text-center"><?php echo $payment->comment ?></td>

                      <td>
                        <a href="{{ url('/admin/reservas/deleteCobro/')}}/<?php echo $payment->id ?>"
                           class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title=""
                           data-original-title="Eliminar Cobro"
                           onclick="return confirm('¿Quieres Eliminar el obro?');">
                          <i class="fa fa-trash"></i>
                        </a>
                      </td>
                    </tr>

                                  <?php $total = $total + $payment->import ?>
                                <?php endforeach ?>
                  <tr>
                    <td class="text-center" style="padding: 20px 0px 0px 0px;">
                      <div class="input-daterange input-group" id="datepicker-range" style="width: 100%">
                        <input type="text" class="input-sm form-control fecha-cobro" name="start"
                               data-date-format="dd-mm-yyyy"
                               value="<?php $hoy = Carbon::now();
                            echo $hoy->format('d/m/Y') ?>"
                               style="min-height: 35px" readonly>
                      </div>
                    </td>
                    <td class="text-center">
                      <input class="importe form-control" type="number" name="importe"
                             style="width: 100%;text-align: center;">
                    </td>

                    <td class="text-center">
                      <select class="form-control type_payment minimal" name="type_payment" tabindex="-1"
                              aria-hidden="true">
    <?php for ($i = 0; $i < 4; $i++): ?>
      <?php if (Auth::user()->id == 39 && $i == 2): ?>
                            <option value="<?php echo $i ?>"
                                    selected><?php echo $book->getTypeCobro($i) ?></option>
      <?php elseif (Auth::user()->id == 28 && $i == 1): ?>
                            <option value="<?php echo $i ?>"
                                    selected><?php echo $book->getTypeCobro($i) ?></option>
      <?php else: ?>
                            <option value="<?php echo $i ?>"><?php echo $book->getTypeCobro($i) ?></option>
      <?php endif ?>

    <?php endfor; ?>
                      </select>
                    </td>
                    <td class="text-center">
                      <input class="comment form-control" type="text" name="comment"
                             style="width: 100%;text-align: center;min-height: 35px">
                    </td>

                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-xs-12 text-center push-40">
              <input type="button" name="cobrar" class="btn btn-success  m-t-10 cobrar" value="Cobrar"
                     data-id="<?php echo $book->id ?>" style="width: 50%;min-height: 50px">
            </div>
          </div>
          <div class="row">
            @include('Paylands.payment', ['routeToRedirect' => route('payland.thanks.payment',
            ['id' => $book->id]),'id' => $book->id, 'customer' => $book->customer->id])
          </div>
  <?php endif ?>
      </div>
      <div class="col-xs-12 bg-black push-0">
        <h4 class="text-center white">HISTORICO EMAILS CON EL CLIENTE <span id="loadchatbox" class="mobile">desplegar</span></h4>
      </div>
      <div id="chatbox" class="chat-lst">
        </div>
  <button class="btn btn-success btn-cons m-b-10" type="button"
          data-toggle="modal" data-target="#modalResponseEmail">Enviar Nueva Respuesta</button>
      
<?php endif ?>
  </div>
  <button style="display: none;" id="btnEmailing" class="btn btn-success btn-cons m-b-10" type="button"
          data-toggle="modal" data-target="#modalEmailing"></button>
  <div class="modal fade slide-up in" id="modalEmailing" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content-wrapper">
        <div class="modal-content" id="contentEmailing"></div>
      </div>
    </div>
  </div>

  <form role="form">
    <div class="form-group form-group-default required" style="display: none">
      <label class="highlight">Message</label>
      <input type="text" hidden="" class="form-control notification-message"
             placeholder="Type your message here" value="This notification looks so perfect!" required>
    </div>
    <button class="btn btn-success show-notification hidden" id="boton">Show</button>
  </form>
  <input type="hidden" class="precio-oculto" value="<?php echo $book->total_price ?>">

  <div class="modal fade slide-up in" id="modalSendPartee" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xd">
              <div class="modal-content-classic">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
                  <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
                </button>
                <h3 id="modalSendPartee_title"></h3>
                <div class="row" id="modalSendPartee_content" style="margin-top:1em;">
                </div>
              </div>
            </div>
        </div>
  
             
            <form method="post" id="formFF" action="" <?php if (!$mobile->isMobile()){ echo 'target="_blank"';} ?>>
              <input type="hidden" name="admin_ff" id="admin_ff">
            </form>
  
  

  <div class="modal fade slide-up in" id="modalResponseEmail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xd">
      <div class="modal-content-classic">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
    <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
</button>
        <form method="POST" action="/admin/response-email">
          <input type="hidden" id="booking" name="booking" value="{{$book->id}}">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <div class="form-group text-left">
            <label for="subject">Asunto</label>
            <input type="text" class="form-control" id="subject" name="subject" value="Repuesta desde {{env('APP_NAME')}}" />
          </div>
          <div class="form-group text-left">
            <label for="content">Contenido</label>
            <textarea class="form-control" id="content" name="content" rows="5"></textarea>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary" >Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade slide-up in" id="modal_seeLog" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xd text-left">
      <div class="modal-content-classic">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
    <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
</button>
        <div class="msl-data">
          <label>Asunto</label>
          <div id="msl_subj"></div>
        </div>
        <div class="msl-data">
          <label>Fecha</label>
          <div id="msl_date"></div>
        </div>
        <div class="msl-data">
          <label>Usuario</label>
          <div id="msl_user"></div>
        </div>
        <div class="msl-data">
          <label>Apto</label>
          <div id="msl_room"></div>
        </div>
        <div class="msl-data">
          <label>Mensaje</label>
          <div id="msl_content"></div>
        </div>
      </div>
    </div>
  </div>

  @endsection

  @section('scripts')

  <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
  <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

  <script src="/assets/js/notifications.js" type="text/javascript"></script>
  @include('backend.planning._bookScripts', ['update' => 1])
  <script>
    calculate(null, false);
  </script>

  <script type="text/javascript">
    $(document).ready(function () {
      $('#sendShareImagesEmail').click(function (event) {
        if (confirm('¿Quieres reenviar las imagenes')) {
          var email = $('#shareEmailImages').val();
          var register = $('#registerData').val();
          var roomId = $('#newroom').val();

          $.get('/admin/sendImagesRoomEmail', {email: email, roomId: roomId, register: register, returned: '1'},
                  function
                          (data) {
                    location.reload();
                  });
        }
      });

      function getScrollButton() {
        $('#chatbox').find("#chats").animate({scrollTop: $('#chats').prop("scrollHeight")}, 1000);
      }
      
            
      $('#chatbox').on('click','#loadchatboxMore',function (event) {
        var date = $(this).data('date');
        var _that = $(this);
        event.preventDefault();
        $.ajax({
          url: '/admin/book-logs/{{$book->id}}/'+date,
          cache: false
        }).done(function (data) {
          $('#chatbox').prepend(data);
          _that.remove();
        });
      });
      
      $('#loadchatbox').click(function () {
        $('#chatbox').load('/admin/book-logs/{{$book->id}}', getScrollButton);
      });
      setTimeout(function(){
        $('#chatbox').load('/admin/book-logs/{{$book->id}}', getScrollButton);
      },1000);
      $('#chatbox').on('click', '.see_more', function (event) {
        event.preventDefault();

        $.ajax({
          url: '/admin/book-logs/see-more/' + $(this).data('id'),
          cache: false
        })
                .done(function (data) {
                  var obj = $('#modal_seeLog');
                  obj.find('#msl_subj').text(data.subj);
                  obj.find('#msl_room').text(data.room);
                  obj.find('#msl_user').text(data.user);
                  obj.find('#msl_content').html(data.content);
                  obj.find('#msl_date').text(data.date);
                  obj.modal('show');
                });

      });
            $('#chatbox').on('click', '.see_more_mail', function (event) {
        event.preventDefault();

        $.ajax({
          url: '/admin/book-logs/see-more-mail/' + $(this).data('id'),
          cache: false
        })
                .done(function (data) {
                  var obj = $('#modal_seeLog');
                  obj.find('#msl_subj').text(data.subj);
                  obj.find('#msl_room').text(data.room);
                  obj.find('#msl_user').text(data.user);
                  obj.find('#msl_content').html(data.content);
                  obj.find('#msl_date').text(data.date);
                  obj.modal('show');
                });

      });
      $('.openFF').on('click', function (event) {
        event.preventDefault();
        var id = $(this).data('booking');
        $.post('/admin/forfaits/open', { _token: "{{ csrf_token() }}",id:id }, function(data) {
//          console.log(data);
          var formFF = $('#formFF');
          formFF.attr('action', data.link);
          formFF.find('#admin_ff').val(data.admin);
          formFF.submit();

        });
      });


 $('body').on('click','.sendSMS',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Partee error.');
          return ;
        }
        if (that.hasClass('disabled')) {
//          alert('No se puede enviar el SMS.');
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.post('/ajax/send-partee-sms', { _token: "{{ csrf_token() }}",id:id }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Partee Error:',data.status,data.response);
              } else {
                window.show_notif('Partee:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
        });
 $('body').on('click','.sendParteeMail',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Partee error.');
          return ;
        }
        if (that.hasClass('disabled')) {
//          alert('No se puede enviar el SMS.');
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.post('/ajax/send-partee-mail', { _token: "{{ csrf_token() }}",id:id }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Partee Error:',data.status,data.response);
              } else {
                window.show_notif('Partee:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
        });
        $('body').on('click','.showParteeLink',function(event) {
          $('#linkPartee').show(700);
        });
        

        var loadFF_resume = true;
        $('.showFF_resume').on('mouseover',function(){
          if (loadFF_resume){
            var tooltip = $(this).find('.FF_resume');
            tooltip.load('/admin/forfaits/resume-by-book/{{$book->id}}');
            loadFF_resume = false;
          }
        });
    });
  
  </script>
  <style>
    button.partee-cp {
      position: relative;
      background-color: #fff;
      color: #10cfbd;
      font-size: 2.52em;
      border: none;
    }
    button.partee-cp:hover {
      /*background-color: #ff5a5f;*/
      color:#00d8c4;
    }
    .tooltip .tooltiptext::after {
      content: "";
      position: absolute;
      top: 100%;
      left: 15%;
      margin-left: -5px;
      border-width: 5px;
      border-style: solid;
      border-color: #929292 transparent transparent transparent;
    }

    .tooltip:hover .tooltiptext {
      visibility: visible;
      opacity: 1;
    }
    button.partee-cp .tooltip.show{
      display: block;
      opacity: 1;
      width: 100%;
      top: -3em;
      left: 0;
    }
    .tooltiptext {
      position: absolute;
      font-size: 11px;
      color: #fff;
      background-color: rgba(0, 0, 0, 0.42);
      padding: 2px 5px;
      width: 10em;
      border-radius: 7px;
    }
    span#loadchatbox {
      float: right;
      font-size: 0.75em;
      color: #daeffd;
      cursor: pointer;
    }
    .partee-icon .pf-icon {
        float: left;
        width: 50%;
        text-align: center;
        display: block;
        font-size: 20px;
    }
    .partee-icon .policeman{
      display: block;
      float: left;
      width: 50%;
      background-repeat: no-repeat;
      height: 2.3em;
      margin-top: 00;
    }
    .tooltiptext.FF_resume{
      color: inherit;
      text-align: left;
      z-index: 9;
      width: 22em;
      display: none;
    }
    .showFF_resume:hover .tooltiptext.FF_resume{
      display: block;
    }
    .tooltiptext.FF_resume .table tbody tr td{
          padding: 8px !important;
          font-size: 1.3em;
              font-weight: 600;
    }
    .tooltiptext.FF_resume span.Pendiente {
      color: red;
    }
    .tooltiptext.FF_resume p{
          color: #fff;
        }
  </style>
  @endsection