@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
    <link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css" />

    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">

    <link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
    <script src="//js.stripe.com/v3/"></script>
    <style>
        .pgn-wrapper[data-position$='-right'] {
            right: : 82%!important;
        }

        input[type=number]::-webkit-outer-spin-button,

        input[type=number]::-webkit-inner-spin-button {

            -webkit-appearance: none;

            margin: 0;

        }
        input[type=number] {

            -moz-appearance:textfield;

        }
        #overlay{
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
        .stripe-price{
            background-color: white!important;
            padding: 8px 12px!important;
            border-radius: 4px!important;
            border: 1px solid transparent!important;
            box-shadow: 0 1px 3px 0 #e6ebf1!important;
            -webkit-transition: box-shadow 150ms ease!important;
            transition: box-shadow 150ms ease!important;
        }

    </style>
@endsection

@section('content')
<?php use \Carbon\Carbon; ?>
<?php   
    use App\Classes\Mobile; 
    $mobile = new Mobile(); 
?>
<div class="container-fluid padding-10 sm-padding-10">
    <div class="row">
        <div class="col-md-12 col-xs-12 center text-left0">
            <div class="col-md-6">
                <div class="col-md-9 col-xs-12">
                    <a href="{{ url('/admin/reservas')}}" class=" m-b-10" style="min-width: 10px!important">
                        <img src="{{ asset('/img/miramarski/iconos/close.png') }}" style="width: 20px" />
                    </a>
                    <h4 class="" style="line-height: 1; letter-spacing: -1px">
                        <?php echo "<b>".strtoupper($book->customer->name)."</b>" ?> creada el 
                        <?php $fecha = Carbon::createFromFormat('Y-m-d H:i:s' ,$book->created_at);?><br>
                        <span class="font-s18"><?php echo $fecha->copy()->formatLocalized('%d %B %Y')." Hora: ".$fecha->copy()->format('H:m')?></span>
                    </h4>
                    <h5>Creado por <?php echo "<b>".strtoupper($book->user->name)."</b>" ?></h5>
                    <?php if ($book->type_book == 2): ?>
                        <div class="col-md-2 col-xs-3 text-center push-10">
                            <a href="{{ url('/admin/pdf/pdf-reserva/'.$book->id) }}">
                                <img src="/img/pdf.png" style="width: 50px; float:left; margin: 0 auto;">
                            </a>
                        </div>
                        <div class="col-md-2 col-xs-3 text-center push-10">
                            <?php $text = "Hola, esperamos que hayas disfrutado de tu estancia con nosotros."."\n"."Nos gustaria que valorarás, para ello te dejamos este link : https://www.apartamentosierranevada.net/encuesta-satisfaccion/".base64_encode($book->id);
                                ?>
                                    
                            <a href="whatsapp://send?text=<?php echo $text; ?>" data-action="share/whatsapp/share" data-original-title="Enviar encuesta de satisfacción" data-toggle="tooltip">
                                <i class="fa fa-share-square fa-3x" aria-hidden="true"></i><br>Encuesta
                            </a>
                        </div>
                    <?php endif ?>
                    <div class="col-md-2 col-xs-3 text-center push-10">
                        <a href="tel:<?php echo $book->customer->phone ?>" style="width: 50px; float:left;">
                            <i class="fa fa-phone  text-success" style="font-size: 48px;"></i>
                        </a>
                    </div>
                    <div class="col-md-2 col-xs-3 text-center push-10 hidden-lg hidden-md">
                        <h2 class="text-center" style="font-size: 18px; line-height: 18px; margin: 0;">
                            <?php $text = "En este link podrás realizar el pago de la señal por el 25% del total."."\n"." En el momento en que efectúes el pago, te legará un email confirmando tu reserva - https://www.apartamentosierranevada.net/reservas/stripe/pagos/".base64_encode($book->id);
                            ?>
                                
                            <a href="whatsapp://send?text=<?php echo $text; ?>" data-action="share/whatsapp/share" >
                                <i class="fa fa-whatsapp fa-3x" aria-hidden="true"></i>
                            </a>
                        </h2>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12 content-guardar" style="padding: 20px 0;">
                    <div id="overlay" style="display: none;"></div>  
                    <select class="status form-control minimal" data-id="<?php echo $book->id ?>" name="status" >
                        <?php for ($i=1; $i < 9; $i++): ?> 
                            <?php if ($i == 5 && $book->customer->email == ""): ?>
                            <?php else: ?>
                                <option <?php echo $i == ($book->type_book) ? "selected" : ""; ?> 
                                <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                                value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>">
                                    <?php echo $book->getStatus($i) ?>
                                    
                                </option>   
                            <?php endif ?>
                        <?php endfor; ?>
                    </select>
                    <h5 class="guardar" style="font-weight: bold; display: none; font-size: 15px;"></h5>
                </div>
            </div>
            <div class="col-md-6">

                <?php if ( isset($_GET['saveStatus']) && !empty($_GET['saveStatus']) ): ?>
                    <div class="col-lg-6 content-alert-error2" >
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="right: 0">×</button>
                            <h3 class="font-w300 push-15">Error</h3>
                            <p><a class="alert-link" href="javascript:void(0)">Ya hay una reserva para ese apartamento</a>!</p>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php if (!$mobile->isMobile()): ?>
        <div class="col-md-12 col-xs-12 center text-center">
            <div class="col-xs-12">
                <!-- DATOS DE LA RESERVA -->
                <div class="col-md-6 col-xs-12">
                    <form role="form"  action="{{ url('/admin/reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post" >
                        <!-- DATOS DEL CLIENTE -->
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <input type="hidden" name="customer_id" value="<?php echo $book->customer->id ?>">
                        <div class="col-xs-12 bg-white padding-block">
                            <div class="col-xs-12 bg-black push-20">
                                <h4 class="text-center white">
                                    DATOS DEL CLIENTE
                                </h4>
                            </div>

                            <div class="col-md-4 push-10">
                                <label for="name">Nombre</label> 
                                <input class="form-control cliente" type="text" name="nombre" value="<?php echo $book->customer->name ?>" data-id="<?php echo $book->customer->id ?>">
                            </div>
                            <div class="col-md-4 push-10">
                                <label for="email">Email</label> 
                                <input class="form-control cliente" type="email" name="email" value="<?php echo $book->customer->email ?>" data-id="<?php echo $book->customer->id ?>">  
                            </div>
                            <div class="col-md-4 push-10">
                                <label for="phone">Telefono</label>
                                <?php if ( $book->customer->phone == 0): ?>
                                     <?php  $book->customer->phone = "" ?>
                                 <?php endif ?> 
                                <input class="form-control only-numbers cliente" type="text" name="phone" value="<?php echo $book->customer->phone ?>" data-id="<?php echo $book->customer->id ?>"> 
                            </div> 
                            <div class="col-md-3 col-xs-12 push-10">
                                <label for="dni">DNI</label> 
                                <input class="form-control cliente" type="text" name="dni" value="<?php echo $book->customer->DNI ?>">
                            </div>
                            <div class="col-md-3 col-xs-12 push-10">
                                <label for="address">DIRECCION</label> 
                                <input class="form-control cliente" type="text" name="address"  value="<?php echo $book->customer->address ?>">
                            </div>
                            <div class="col-md-3 col-xs-12 push-10">
                                <label for="country">PAÍS</label> 
                                <select class="form-control country minimal"  name="country">
                                    <option>--Seleccione país --</option>
                                    <?php foreach (\App\Countries::orderBy('code', 'ASC')->get() as $country): ?>
                                        <option value="<?php echo $country->code ?>" <?php if ($country->code == $book->customer->country){echo "selected";} ?>>
                                            <?php echo $country->country ?>
                                        </option>
                                    <?php endforeach;?>
                                </select>
                            </div>  
                            <div class="col-md-3 col-xs-12 push-10 content-cities">
                                <label for="city">CIUDAD</label>
                                <select class="form-control city minimal"  name="city">
                                    <option>--Seleccione ciudad --</option>
                                    <?php foreach (\App\Cities::orderBy('city', 'ASC')->get() as $city): ?>
                                        <option value="<?php echo $city->id ?>" <?php if ($city->id == $book->customer->city){ echo "selected";} ?>>
                                            <?php echo $city->city ?>
                                        </option>
                                    <?php endforeach;?>
                                </select>
                            </div>  
                        </div>
                        <!-- DATOS DE LA RESERVA -->
                        <div class="col-xs-12 bg-white padding-block">
                            <div class="col-xs-12 bg-black push-20">
                                <h4 class="text-center white">
                                    DATOS DE LA RESERVA
                                </h4>
                            </div>
                            <div class="col-md-4 push-20">
                                <label>Entrada</label>
                                <div class="input-prepend input-group">
                                    <?php $start = Carbon::createFromFormat('Y-m-d', $book->start);
                                            $start1 = str_replace('Apr','Abr',$start->format('d M, y')); ?>
                                    <?php $finish = Carbon::createFromFormat('Y-m-d', $book->finish); 
                                            $finish1 = str_replace('Apr','Abr',$finish->format('d M, y')); ?>

                                    <input type="text" class="form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center; backface-visibility: hidden;min-height: 28px;" value="<?php echo $start1 ;?> - <?php echo $finish1 ?>" readonly="">

                                </div>
                            </div>
                            <div class="col-md-1 col-xs-3 push-20 p-l-0">
                                <label>Noches</label>
                                <input type="number" class="form-control nigths" name="nigths" style="width: 100%" disabled value="<?php echo $book->nigths ?>">
                            <input type="hidden" class="form-control nigths" name="nigths" style="width: 100%" value="<?php echo $book->nigths ?>">
                            </div> 
                            <div class="col-md-1 col-xs-3 p-l-0 p-r-0" style="margin-bottom: -10px!important">
                                <label>Pax</label>
                                <select class=" form-control pax minimal"  name="pax">
                                    <?php for ($i=1; $i <= 10 ; $i++): ?>
                                        <option value="<?php echo $i ?>" <?php echo ($i == $book->pax)?"selected":""; ?>>
                                            <?php echo $i ?>
                                        </option>
                                    <?php endfor;?>
                                </select>
                                <label class="m-t-5" style="color: red">Pax-Real</label>
                                <select class=" form-control real_pax minimal"  name="real_pax">
                                    <?php for ($i=1; $i <= 10 ; $i++): ?>
                                        <option value="<?php echo $i ?>" <?php echo ($i == $book->real_pax)?"selected":""; ?> style="color: red">
                                            <?php echo $i ?>
                                        </option>
                                    <?php endfor;?>
                                </select>
                            </div>
                            <div class="col-md-3 col-xs-6 push-20">
                                <label>Apartamento</label>

                                <select class="form-control full-width minimal newroom" name="newroom" id="newroom" <?php if ( isset($_GET['saveStatus']) && !empty($_GET['saveStatus']) ): echo "style='border: 1px solid red'"; endif ?>>
                                    <?php foreach ($rooms as $room): ?>
                                        <option data-luxury="<?php echo $room->luxury ?>" value="<?php echo $room->id ?>" {{ $room->id == $book->room_id ? 'selected' : '' }} >
                                            <?php echo substr($room->nameRoom." - ".$room->name, 0, 15)  ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-1 col-xs-6 push-20 p-l-0 p-r-0">
                                <label>Parking</label>
                                <select class=" form-control parking minimal"  name="parking">
                                    <?php for ($i=1; $i <= 4 ; $i++): ?>
                                        <option value="<?php echo $i ?>" {{ $book->type_park == $i ? 'selected' : '' }}><?php echo $book->getParking($i) ?></option>
                                    <?php endfor;?>
                                </select>
                                <label class="m-t-5">Hora IN</label>
                                <select id="schedule" class="form-control minimal" style="width: 100%;" name="schedule">
                                    <option>-- Sin asignar --</option>
                                    <?php for ($i = 0; $i < 24; $i++): ?>
                                        <option value="<?php echo $i ?>" <?php if($i == $book->schedule) { echo 'selected';}?>>
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
                                
                            </select>
                            </div>
                            <div class="col-md-2 col-xs-6 push-20">
                                <label>Sup. Lujo</label>
                                <select class=" form-control full-width type_luxury minimal" name="type_luxury">
                                    <?php for ($i=1; $i <= 4 ; $i++): ?>
                                        <option value="<?php echo $i ?>" {{ $book->type_luxury == $i ? 'selected' : '' }}><?php echo $book->getSupLujo($i) ?></option>
                                    <?php endfor;?>
                                </select>

                                <label class="m-t-5">Hora Out</label>
                                <select id="scheduleOut" class="form-control minimal" style="width: 100%;" name="scheduleOut">
                                    <option>-- Sin asignar --</option>
                                    <?php for ($i = 0; $i < 24; $i++): ?>
                                        <option value="<?php echo $i ?>" <?php if($i == $book->scheduleOut) { echo 'selected';}?>>
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
                            <div class="col-md-4 col-xs-6 push-20 not-padding" style="min-height: 150px">
                                <div class="col-md-6 col-xs-12 push-10">
                                    <label>Agencia</label>
                                    <select class="form-control full-width agency minimal" name="agency">
                                        <?php for ($i=0; $i <= 2 ; $i++): ?>
                                            <option value="<?php echo $i ?>" {{ $book->agency == $i ? 'selected' : '' }}><?php echo $book->getAgency($i) ?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                                <div class="col-md-6 col-xs-12 push-10">                                                        
                                    <label>Cost Agencia</label>
                                    <?php if ($book->PVPAgencia == 0.00): ?>
                                        <input type="number" class="agencia form-control" name="agencia" value="">
                                    <?php else: ?>
                                        <input type="number" class="agencia form-control" name="agencia" value="<?php echo $book->PVPAgencia ?>">
                                    <?php endif ?>
                                </div>
                                <div style="clear: both;"></div>
                                <div class="col-md-6">
                                    <label>Extras</label>
                                    <select class="full-width form-control select2-hidden-accessible " data-init-plugin="select2" multiple="" name="extras[]" tabindex="-1" aria-hidden="true" style="cursor: pointer">
                                        <?php foreach ($extras as $extra): ?>
                                            <option value="<?php echo $extra->id ?>"><?php echo $extra->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                           
                                
                           
                            <div class="col-md-8 col-xs-6 push-20 not-padding">
                                <div class="col-md-4 col-xs-12 text-center" style="background-color: #0c685f;">
                                    <label class="font-w800 text-white" for="">TOTAL</label>
                                    <input type="text" class="form-control total m-t-10 m-b-10 white" name="total" value="<?php echo $book->total_price ?>">
                                </div>
                                <?php if (Auth::user()->role == 'admin'): ?>
                                    <div class="col-md-4 col-xs-12 text-center" style="background: #99D9EA;">
                                        <label class="font-w800 text-white" for="">COSTE</label>
                                        <input type="text" class="form-control cost m-t-10 m-b-10 white" name="cost" value="<?php echo $book->cost_total ?>">
                                    </div>
                                    <div class="col-md-4 col-xs-12 text-center not-padding" style="background: #ff7f27;">
                                        <label class="font-w800 text-white" for="">BENEFICIO</label>
                                        <input type="text" class="form-control text-left beneficio m-t-10 m-b-10 white" name="beneficio" value="<?php echo $book->total_ben ?>" style="width: 80%; float: left;">
                                        <div class="beneficio-text font-w400 font-s18 white" style="width: 20%; float: left;padding: 25px 0; padding-right: 5px;"><?php echo number_format($book->inc_percent,0)."%" ?></div>
                                    </div>
                                <?php endif ?>    
                                                     
                        </div>
                        <div class="col-md-8 col-xs-12 push-20 not-padding">
                            <p class="personas-antiguo" style="color: red">
                                <?php if ($book->pax < $book->room->minOcu): ?>
                                    Van menos personas que la ocupacion minima del apartamento.
                                <?php endif ?>
                            </p>
                        </div>
                        <div class="col-md-8 col-xs-12 not-padding text-left">
                            <p class="precio-antiguo font-s18">
                                <b>El precio asignado <?php echo $book->total_price ?> y el precio de tarifa es <?php echo $book->real_price ?></b>
                            </p>
                        </div> 
                        <div class="col-xs-12 bg-white padding-block">
                            <div class="col-md-6 col-xs-12">
                                <label>Comentarios Cliente </label>
                                <textarea class="form-control" name="comments" rows="5" ><?php echo $book->comment ?></textarea>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <label>Comentarios Internos</label>
                                <textarea class="form-control book_comments" name="book_comments" rows="5" ><?php echo $book->book_comments ?></textarea>
                            </div>
                        </div>
                        <div class="row push-40 bg-white padding-block">
                            <div class="col-md-4 col-md-offset-4 text-center">
                                <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit" style="min-height: 50px;width: 100%;">Guardar</button>
                            </div>  
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6 col-xs-12 padding-block">
                <div class="row">
                    <div class="col-xs-12 bg-black push-0">
                        <h4 class="text-center white">
                            COBROS
                        </h4>
                    </div>
                    <table class="table table-hover demo-table-search table-responsive-block" style="margin-top: 0;">
                        <thead>
                            <tr>
                                <th class ="text-center bg-success text-white" style="width:25%">fecha</th>
                                <th class ="text-center bg-success text-white" style="width:25%">importe</th>
                                <th class ="text-center bg-success text-white" style="width:30%">Tipo</th>                           
                                <th class ="text-center bg-success text-white" style="width:20%">comentario</th>
                                <th class ="text-center bg-success text-white" style="width:20%">Eliminar</th>

                            </tr>
                        </thead>
                        <tbody><?php $total = 0; ?>
                            <?php if (count($payments)>0): ?>

                                <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td class ="text-center p-t-25">
                                            <?php 
                                            $fecha = new Carbon($payment->datePayment);
                                            echo $fecha->format('d-m-Y') 
                                            ?>
                                        </td>
                                        <td class ="text-center">
                                            <input class="editable payment-<?php echo $payment->id?> m-t-5" type="text" name="cost" data-id="<?php echo $payment->id ?>" value="<?php echo $payment->import ?>" style="width: 50%;text-align: center;border-style: none none ">€
                                        </td>
                                        <td class ="text-center p-t-25"><?php echo $typecobro->getTypeCobro($payment->type) ?> </td>

                                        <td class ="text-center p-t-25"><?php echo $payment->comment ?></td>
                                        <td>
                                            <a href="{{ url('/admin/reservas/deleteCobro/')}}/<?php echo $payment->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Cobro" onclick="return confirm('¿Quieres Eliminar el obro?');">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $total = $total + $payment->import ?>
                                <?php endforeach ?>
                                <tr>
                                    <td class ="text-center">
                                        <div class="input-daterange input-group" id="datepicker-range">
                                            <input type="text" class="input-sm form-control fecha-cobro" name="start" data-date-format="dd-mm-yyyy" value="<?php $hoy = Carbon::now() ;echo $hoy->format('d/m/Y') ?>">
                                        </div>
                                    </td>
                                    <td class ="text-center">
                                        <input class="importe m-t-5" type="number" name="importe"  style="width: 100%;text-align: center;border-style: none none ">
                                    </td>
                                    <td class="text-center">
                                        <select class="full-width select2-hidden-accessible type_payment" data-init-plugin="select2" name="type_payment"  tabindex="-1" aria-hidden="true">
                                            <?php for ($i=0; $i < 4 ; $i++): ?>
                                                <?php if (Auth::user()->id == 39 && $i == 2): ?>
                                                    <option value="<?php echo $i ?>" selected><?php echo $book->getTypeCobro($i) ?></option>
                                                <?php elseif (Auth::user()->id == 28 && $i == 1):?>
                                                    <option value="<?php echo $i ?>" selected><?php echo $book->getTypeCobro($i) ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo $i ?>"><?php echo $book->getTypeCobro($i) ?></option>
                                                <?php endif ?>

                                            <?php endfor ;?>
                                        </select>
                                    </td>
                                    <td class ="text-center"> 
                                        <input class="comment" type="text" name="comment"  style="width: 100%;text-align: center;border-style: none">
                                    </td>
                                    <td>
                                    </td>
                                    
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td class ="text-center">
                                        <div class="input-daterange input-group" id="datepicker-range">
                                            <input type="text" class="input-sm form-control fecha-cobro" name="start" data-date-format="dd-mm-yyyy" value="<?php $hoy = Carbon::now() ;echo $hoy->format('d/m/Y') ?>">
                                        </div>
                                    </td>
                                    <td class ="text-center">
                                        <input class="importe m-t-5" type="text" name="importe"  style="width: 100%;text-align: center;border-style: none">
                                    </td>
                                    <td class="text-center">
                                        <select class="full-width select2-hidden-accessible type_payment" data-init-plugin="select2" name="type_payment"  tabindex="-1" aria-hidden="true">
                                            <?php for ($i=0; $i < 4 ; $i++): ?>
                                                <?php if (Auth::user()->id == 39 && $i == 2): ?>
                                                    <option value="<?php echo $i ?>" selected><?php echo $book->getTypeCobro($i) ?></option>
                                                <?php elseif (Auth::user()->id == 28 && $i == 1):?>
                                                    <option value="<?php echo $i ?>" selected><?php echo $book->getTypeCobro($i) ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo $i ?>"><?php echo $book->getTypeCobro($i) ?></option>
                                                <?php endif ?>

                                            <?php endfor ;?>
                                     </select>
                                 </td>
                                 <td class ="text-center"> 
                                    <input class="comment m-t-5" type="text" name="comment"  style="width: 100%;text-align: center;border-style: none">
                                </td>
                                
                                </tr>
                            <?php endif ?>
                            <tr>
                                <td></td>
                                <?php if ($total < $book->total_price): ?>
                                    <td class="text-center" ><p style="color:red;font-weight: bold;font-size:15px"><?php echo $total-$book->total_price ?>€</p></td>
                                    <td class="text-left" colspan="2"><p style="color:red;font-weight: bold;font-size:15px">Pendiente de pago</p></td>
                                <?php elseif($total > $book->total_price): ?>
                                    <td class="text-center" ><p style="color:black;font-weight: bold;font-size:15px"><?php echo $total-$book->total_price ?>€</p></td>
                                    <td class="text-left" colspan="2">Sobrante</td>
                                <?php else: ?>
                                    <td class="text-center" ><p style="color:black;font-weight: bold;font-size:15px">0€</p></td>
                                    <td class="text-left" colspan="2">Al corriente de pago</td>
                                <?php endif ?>
                                
                            </tr>
                        </tbody>
                    </table>
                    <input type="button" name="cobrar" class="btn btn-success  m-t-10 cobrar" value="GUARGAR" data-id="<?php echo $book->id ?>" style="width: 30%;min-height: 50px">                            
                </div>

                <div class="row push-20" style="margin-top: 20px;">
                    <h2 class="text-center" style="font-size: 24px; line-height: 15px">
                        <span style="font-size: 20px;">En este link podrás realizar el pago de la señal por el 25% del total.<br> En el momento en que efectúes el pago, te legará un email confirmando tu reserva</span><br>
                        <a target="_blank" href="https://www.apartamentosierranevada.net/reservas/stripe/pagos/<?php echo base64_encode($book->id) ?>">
                            https://www.apartamentosierranevada.net/reservas/stripe/pagos/<?php echo base64_encode($book->id) ?>     
                        </a>
                    </h2>
                </div>

                <div class="row">
                    <?php $hasFiance = \App\Fianzas::where('book_id', $book->id)->first(); ?>
                    <div class="col-xs-12 push-20 ">
                        <?php if ($book->type_book == 2): ?>
                            <?php if ( count($hasFiance) > 0): ?>
                                <div class="col-md-6">

                                    <button class="btn btn-primary btn-lg" type="button" id="fianza"> COBRAR FIANZA</button>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6">

                                    <a class="btn btn-primary btn-lg" href="{{ url('/admin/reservas/fianzas/cobrar/'.$book->id) }}"> RECOGER FIANZA</a>
                                </div>
                            <?php endif ?>
                            
                        <?php endif ?>
                    </div>
                    <?php if ($book->type_book == 2): ?>
                        <div class="row content-fianza" style="display: none;">
                            <?php if ( count($hasFiance) > 0): ?>
                                <div class="col-md-6 col-md-offset-3 alert alert-info fade in alert-dismissable" style="margin-top: 30px; background-color: #10cfbd70!important;">
                                    <h3 class="text-center font-w300">
                                        CARGAR LA FIANZA
                                    </h3>
                                    <div class="row">
                                        <form action="{{ url('admin/reservas/stripe/pay/fianza') }}" method="post">

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
                <div class="row">
                    @include('backend.stripe.stripe', ['bookTocharge' => $book])
                </div>
            </div>
        </div>
    <?php else: ?>
        <style type="text/css">
            @media screen and (max-width: 767px){
                .daterangepicker {
                    position: fixed;
                    top: 8%!important;
                }
            }
        </style>
        <div class="row">
            <div class="col-xs-12">
                <!-- DATOS DE LA RESERVA -->
                <form role="form"  action="{{ url('/admin/reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post" >
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
                            <input class="form-control cliente" type="text" name="nombre" value="<?php echo $book->customer->name ?>" data-id="<?php echo $book->customer->id ?>">
                        </div>
                        <div class="col-xs-12 push-10">
                            <label for="email">Email</label> 
                            <input class="form-control cliente" type="email" name="email" value="<?php echo $book->customer->email ?>" data-id="<?php echo $book->customer->id ?>">  
                        </div>
                        <?php if ( $book->customer->phone == 0): ?>
                             <?php  $book->customer->phone = "" ?>
                         <?php endif ?> 
                        <div class="col-xs-12 push-10">
                            <label for="phone">Telefono</label> 
                            <input class="form-control cliente" type="text" name="phone" value="<?php echo $book->customer->phone ?>" data-id="<?php echo $book->customer->id ?>"> 
                        </div>  
                        <div class="col-xs-12 push-10">
                            <label for="dni">DNI</label> 
                            <input class="form-control cliente" type="text" name="dni" value="<?php echo $book->customer->DNI ?>">
                        </div>
                        <div class="col-xs-12 push-10">
                            <label for="address">DIRECCION</label> 
                            <input class="form-control cliente" type="text" name="address"  value="<?php echo $book->customer->address ?>">
                        </div>
                        <div class="col-xs-12 push-10">
                            <label for="country">PAÍS</label> 
                            <select class="form-control country minimal"  name="country">
                                <option>--Seleccione país --</option>
                                <?php foreach (\App\Countries::orderBy('code', 'ASC')->get() as $country): ?>
                                    <option value="<?php echo $country->code ?>" <?php if ($country->code == $book->customer->country){echo "selected";} ?>>
                                        <?php echo $country->country ?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>  
                        <div class="col-xs-12 push-10 content-cities">
                            <label for="city">CIUDAD</label>
                            <select class="form-control city minimal"  name="city">
                                <option>--Seleccione ciudad --</option>
                                <?php foreach (\App\Cities::where('code_country', $book->customer->country)->orderBy('city', 'ASC')->get() as $city): ?>
                                    <option value="<?php echo $city->id ?>" <?php if ($city->id == $book->customer->city){ echo "selected";} ?>>
                                        <?php echo $city->city ?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>  
                    </div>

                    <!-- DATOS DE LA RESERVA -->
                    <div class="col-xs-12 bg-white padding-block">
                        <div class="col-xs-12 bg-black push-20">
                            <h4 class="text-center white">
                                DATOS DE LA RESERVA
                            </h4>
                        </div>
                        <div class="col-md-4  col-xs-8 push-20">
                            <label>Entrada</label>
                            <?php $start = Carbon::createFromFormat('Y-m-d', $book->start);
                                    $start1 = str_replace('Apr','Abr',$start->format('d M, y')); ?>
                            <?php $finish = Carbon::createFromFormat('Y-m-d', $book->finish); 
                                    $finish1 = str_replace('Apr','Abr',$finish->format('d M, y')); ?>

                            <input type="text" class="form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center; backface-visibility: hidden;min-height: 28px;" value="<?php echo $start1 ;?> - <?php echo $finish1 ?>" readonly="">
                        </div>
                        <div class="col-md-1 col-xs-4 push-20 ">
                            <label>Noches</label>
                            <input type="number" class="form-control nigths" name="nigths" style="width: 100%" disabled value="<?php echo $book->nigths ?>">
                             <input type="hidden" class="form-control nigths" name="nigths" style="width: 100%" value="<?php echo $book->nigths ?>">
                        </div> 
                        <div class="col-md-1 col-xs-4 push-20 ">
                            <label>Pax</label>
                            <select class=" form-control pax minimal"  name="pax">
                                <?php for ($i=1; $i <= 10 ; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php echo ($i == $book->pax)?"selected":""; ?>>
                                        <?php echo $i ?>
                                    </option>
                                <?php endfor;?>
                            </select> 
                            <label class="m-t-20" style="color: red">Pax-Real</label>
                            <select class=" form-control real_pax minimal"  name="real_pax">
                                <?php for ($i=1; $i <= 10 ; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php echo ($i == $book->real_pax)?"selected":""; ?> style="color: red">
                                        <?php echo $i ?>
                                    </option>
                                <?php endfor;?>
                            </select>
                        </div>
                        <div class="col-md-3 col-xs-8 push-20">
                            <label>Apartamento</label>

                            <select class="form-control full-width minimal newroom" name="newroom" id="newroom" <?php if ( isset($_GET['saveStatus']) && !empty($_GET['saveStatus']) ): echo "style='border: 1px solid red'"; endif ?>>
                                <?php foreach ($rooms as $room): ?>
                                    <option data-luxury="<?php echo $room->luxury ?>" value="<?php echo $room->id ?>" {{ $room->id == $book->room_id ? 'selected' : '' }} >
                                        <?php echo substr($room->nameRoom." - ".$room->name, 0, 15)  ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-1 col-xs-6 push-20">
                            <label>Parking</label>
                            <select class=" form-control parking minimal"  name="parking">
                                <?php for ($i=1; $i <= 4 ; $i++): ?>
                                    <option value="<?php echo $i ?>" {{ $book->type_park == $i ? 'selected' : '' }}><?php echo $book->getParking($i) ?></option>
                                <?php endfor;?>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-6 push-20">
                            <label>Sup. Lujo</label>
                            <select class=" form-control full-width type_luxury minimal" name="type_luxury">
                                <?php for ($i=1; $i <= 4 ; $i++): ?>
                                    <option value="<?php echo $i ?>" {{ $book->type_luxury == $i ? 'selected' : '' }}><?php echo $book->getSupLujo($i) ?></option>
                                <?php endfor;?>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-3 push-20">
                            <label >IN</label>
                            <select id="schedule" class="form-control " style="width: 100%;" name="schedule">
                                <option>-- Sin asignar --</option>
                                <?php for ($i = 0; $i < 24; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php if($i == $book->schedule) { echo 'selected';}?>>
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
                        <div class="col-md-2 col-xs-3 push-20">
                            <label>Out</label>
                            <select id="scheduleOut" class="form-control " style="width: 100%;" name="scheduleOut">
                                <option>-- Sin asignar --</option>
                                <?php for ($i = 0; $i < 24; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php if($i == $book->scheduleOut) { echo 'selected';}?>>
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
                        <div class="col-md-4 col-xs-6 push-20 not-padding" style="min-height: 150px">
                            <div class="col-md-6 col-xs-12 push-10">
                                <label>Agencia</label>
                                <select class="form-control full-width agency minimal" name="agency">
                                    <?php for ($i=0; $i <= 2 ; $i++): ?>
                                        <option value="<?php echo $i ?>" {{ $book->agency == $i ? 'selected' : '' }}><?php echo $book->getAgency($i) ?></option>
                                    <?php endfor;?>
                                </select>
                            </div>
                            <div class="col-md-6 col-xs-12 push-10">                                                        
                                <label>Cost Agencia</label>
                                <?php if ($book->PVPAgencia == 0.00): ?>
                                    <input type="number" class="agencia form-control" name="agencia" value="">
                                <?php else: ?>
                                    <input type="number" class="agencia form-control" name="agencia" value="<?php echo $book->PVPAgencia ?>">
                                <?php endif ?>
                            </div>
                            <div style="clear: both;"></div>
                            <div class="col-md-6">
                                <label>Extras</label>
                                <select class="full-width form-control select2-hidden-accessible " data-init-plugin="select2" multiple="" name="extras[]" tabindex="-1" aria-hidden="true" style="cursor: pointer">
                                    <?php foreach ($extras as $extra): ?>
                                        <option value="<?php echo $extra->id ?>"><?php echo $extra->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                       
                            
                       
                        <div class="col-md-8 col-xs-6 push-20 not-padding">
                            <div class="col-md-4 col-xs-12 text-center" style="background-color: #0c685f;">
                                <label class="font-w800 text-white" for="">TOTAL</label>
                                <input type="text" class="form-control total m-t-10 m-b-10 white" name="total" value="<?php echo $book->total_price ?>">
                            </div>
                            <?php if (Auth::user()->role == 'admin'): ?>
                                <div class="col-md-4 col-xs-12 text-center" style="background: #99D9EA;">
                                    <label class="font-w800 text-white" for="">COSTE</label>
                                    <input type="text" class="form-control cost m-t-10 m-b-10 white" name="cost" value="<?php echo $book->cost_total ?>">
                                </div>
                                <div class="col-md-4 col-xs-12 text-center not-padding" style="background: #ff7f27;">
                                    <label class="font-w800 text-white" for="">BENEFICIO</label>
                                    <input type="text" class="form-control text-right beneficio m-t-10 m-b-10 white" name="beneficio" value="<?php echo $book->total_ben ?>" style="width: 80%; float: left;">
                                    <div class="beneficio-text font-w400 font-s18 white" style="width: 20%; float: left;padding: 25px 0; padding-right: 5px;"><?php echo number_format($book->inc_percent,0)."%" ?></div>
                                </div>
                            <?php endif ?>    
                                                 
                    </div>

                    <div class="col-md-8 col-xs-12 push-20 not-padding">
                        <p class="personas-antiguo" style="color: red">
                            <?php if ($book->pax < $book->room->minOcu): ?>
                                Van menos personas que la ocupacion minima del apartamento.
                            <?php endif ?>
                        </p>
                    </div>
                    <div class="col-md-8 col-xs-12 not-padding text-left">
                        <p class="precio-antiguo font-s18">
                            <b>El precio asignado <?php echo $book->total_price ?> y el precio de tarifa es <?php echo $book->real_price ?></b>
                        </p>
                    </div> 
                    <div class="col-xs-12 bg-white padding-block">
                        <div class="col-md-6 col-xs-12">
                            <label>Comentarios Cliente </label>
                            <textarea class="form-control" name="comments" rows="5" ><?php echo $book->comment ?></textarea>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label>Comentarios Internos</label>
                            <textarea class="form-control book_comments" name="book_comments" rows="5" ><?php echo $book->book_comments ?></textarea>
                        </div>
                    </div>
                    <div class="row push-40 bg-white padding-block">
                        <div class="col-md-4 col-md-offset-4 col-xs-12 text-center">
                            <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit" style="min-height: 50px;width: 100%;">Guardar reserva</button>
                        </div>  
                    </div>
                </form>
            </div>

            <div class="col-md-6 col-xs-12 padding-block">
                <div class="row push-20">
                    <?php $hasFiance = \App\Fianzas::where('book_id', $book->id)->first(); ?>
                    <div class="col-xs-12 push-20 ">
                        <?php if ($book->type_book == 2): ?>
                            <?php if ( count($hasFiance) > 0): ?>
                                <div class="col-md-6 col-xs-12 text-center">

                                    <button class="btn btn-primary btn-lg" type="button" id="fianza" style="color: #fff;background-color: #337ab7;border-color: #2e6da4;"> COBRAR FIANZA</button>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6 col-xs-12 text-center">

                                    <a class="btn btn-primary btn-lg" href="{{ url('/admin/reservas/fianzas/cobrar/'.$book->id) }}" style="color: #fff;background-color: #337ab7;border-color: #2e6da4;"> RECOGER FIANZA</a>
                                </div>
                            <?php endif ?>
                            
                        <?php endif ?>
                    </div>
                    <?php if ($book->type_book == 2): ?>
                        <div class="row content-fianza" style="display: none;">
                            <?php if ( count($hasFiance) > 0): ?>
                                <div class="col-md-6 col-md-offset-3 alert alert-info fade in alert-dismissable" style="margin-top: 30px; background-color: #10cfbd70!important;">
                                    <h3 class="text-center font-w300">
                                        CARGAR LA FIANZA
                                    </h3>
                                    <div class="row">
                                        <form action="{{ url('admin/reservas/stripe/pay/fianza') }}" method="post">

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
                <div class="row">
                    <div class="col-xs-12 bg-black push-0">
                        <h4 class="text-center white">
                           COBROS
                        </h4>
                    </div>
                    <div class="col-xs-12 not-padding">
                       <div class="col-xs-4 not-padding bg-success text-white text-center" style="min-height: 50px">
                           <span class="font-s18">Total:</span><br>
                           <span class="font-w600 font-s18"><?php echo number_format($book->total_price,2,',','.') ?> €</span>
                       </div>
                       <div class="col-xs-4 not-padding bg-primary text-white text-center" style="min-height: 50px">
                           <span class="font-s18">Cobrado:</span><br>
                           <span class="font-w600 font-s18"><?php echo number_format($totalpayment,2,',','.') ?> €</span>
                       </div>
                       <div class="col-xs-4 not-padding bg-danger text-white text-center" style="min-height: 50px">
                           <span class="font-s18">Pendiente:</span><br>
                           <!-- si esta pendiente nada,.si esta de mas +X -->
                           <span class="font-w600 font-s18"><?php echo ($book->total_price-$totalpayment) >= 0 ? "" : "+";echo number_format($totalpayment-$book->total_price,2,',','.') ?> €</span>
                       </div>
                    </div>
                    <div class="col-md-12 table-responsive not-padding ">
                       <table class="table  table-responsive table-striped" style="margin-top: 0;">
                           <thead>
                               <tr>
                                   <th class ="text-center" style="min-width: 100px">fecha</th>
                                   <th class ="text-center" style="min-width: 100px">importe</th>
                                   <th class ="text-center" style="min-width: 200px">Tipo</th>
                                   <th class ="text-center" style="min-width: 100px">comentario</th>
                                   <th class ="text-center" style="width:20%">Eliminar</th>
                               </tr>
                           </thead>
                           <tbody>
                               <?php $total = 0; ?>
                                  
                                   <?php foreach ($payments as $payment): ?>
                                       <tr>
                                           <td class ="text-center">
                                               <?php 
                                                   $fecha = new Carbon($payment->datePayment);
                                                   echo $fecha->format('d-m-Y') 
                                               ?>
                                           </td>
                                           <td class ="text-center">
                                               <?php echo $payment->import." €" ?>
                                           </td>
                                           <td class ="text-center"><?php echo $typecobro->getTypeCobro($payment->type) ?> </td>
                                           <td class ="text-center"><?php echo $payment->comment ?></td>
                                           
                                           <td>
                                               <a href="{{ url('/admin/reservas/deleteCobro/')}}/<?php echo $payment->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Cobro" onclick="return confirm('¿Quieres Eliminar el obro?');">
                                                   <i class="fa fa-trash"></i>
                                               </a>
                                           </td>
                                       </tr>

                                       <?php $total = $total + $payment->import ?>
                                   <?php endforeach ?>
                                   <tr>
                                       <td class ="text-center" style="padding: 20px 0px 0px 0px;">
                                           <div class="input-daterange input-group" id="datepicker-range" style="width: 100%">
                                               <input type="text" class="input-sm form-control fecha-cobro" name="start" data-date-format="dd-mm-yyyy" value="<?php $hoy = Carbon::now() ;echo $hoy->format('d/m/Y') ?>" style="min-height: 35px" readonly>
                                           </div>
                                       </td>
                                       <td class ="text-center">
                                       <input class="importe form-control" type="number" name="importe"  style="width: 100%;text-align: center;">
                                       </td>
                                       
                                       <td class="text-center">
                                           <select class="form-control type_payment minimal" name="type_payment"  tabindex="-1" aria-hidden="true">
                                               <?php for ($i=0; $i < 4 ; $i++): ?>
                                                   <?php if (Auth::user()->id == 39 && $i == 2): ?>
                                                       <option value="<?php echo $i ?>" selected><?php echo $book->getTypeCobro($i) ?></option>
                                                   <?php elseif (Auth::user()->id == 28 && $i == 1):?>
                                                       <option value="<?php echo $i ?>" selected><?php echo $book->getTypeCobro($i) ?></option>
                                                   <?php else: ?>
                                                       <option value="<?php echo $i ?>"><?php echo $book->getTypeCobro($i) ?></option>
                                                   <?php endif ?>

                                               <?php endfor ;?>
                                           </select>
                                       </td>
                                       <td class ="text-center"> 
                                       <input class="comment form-control" type="text" name="comment"  style="width: 100%;text-align: center;min-height: 35px">
                                       </td>
                                       
                                   </tr>
                           </tbody>
                       </table>
                    </div>  
                    <div class="col-xs-12 text-center push-40">
                        <input type="button" name="cobrar" class="btn btn-success  m-t-10 cobrar" value="Cobrar" data-id="<?php echo $book->id ?>" style="width: 50%;min-height: 50px"> 
                    </div>
            </div>

            
            <div class="row">
                @include('backend.stripe.stripe', ['bookTocharge' => $book])
            </div>
        </div>
    <?php endif ?>
</div>
<button style="display: none;" id="btnEmailing" class="btn btn-success btn-cons m-b-10" type="button" data-toggle="modal" data-target="#modalEmailing"> </button>
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
        <input type="text" hidden="" class="form-control notification-message" placeholder="Type your message here" value="This notification looks so perfect!" required>
    </div>
    <button class="btn btn-success show-notification hidden" id="boton">Show</button>
</form>
<input type="hidden"  class="precio-oculto" value="<?php echo $book->total_price ?>">

@endsection

@section('scripts')

<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

<script src="/assets/js/notifications.js" type="text/javascript"></script>

<script type="text/javascript">


    $('#fianza').click(function(event) {
        $('.content-fianza').toggle(function() {
            $('#fianza').css('background-color', '#f55753');
        }, function() {
            $('#fianza').css('background-color', '#10cfbd');
        });

    });

        $(function() {
          $(".daterange1").daterangepicker({
            "buttonClasses": "button button-rounded button-mini nomargin",
            "applyClass": "button-color",
            "cancelClass": "button-light",
            locale: {
                format: 'DD MMM, YY',
                "applyLabel": "Aplicar",
                  "cancelLabel": "Cancelar",
                  "fromLabel": "From",
                  "toLabel": "To",
                  "customRangeLabel": "Custom",
                  "daysOfWeek": [
                      "Do",
                      "Lu",
                      "Mar",
                      "Mi",
                      "Ju",
                      "Vi",
                      "Sa"
                  ],
                  "monthNames": [
                      "Enero",
                      "Febrero",
                      "Marzo",
                      "Abril",
                      "Mayo",
                      "Junio",
                      "Julio",
                      "Agosto",
                      "Septiembre",
                      "Octubre",
                      "Noviembre",
                      "Diciembre"
                  ],
                  "firstDay": 1,
              },
              
          });
        });

        function calculate( notModifyPrice = 0){
                var room       = $('#newroom').val();
                var pax        = $('.pax').val();
                var park       = $('.parking').val();
                var lujo       = $('select[name=type_luxury]').val();
                var status     = $('select[name=status]').val();
                var sizeApto   = $('option:selected', 'select[name=newroom]').attr('data-size');;
                var beneficio  = 0;
                var costPark   = 0;
                var pricePark  = 0;
                var costLujo   = 0;
                var priceLujo  = 0;
                var agencia    = 0;
                var beneficio_ = 0;
                var comentario =$('.book_comments').val();
                var date       = $('.daterange1').val();
                
                var arrayDates = date.split('-');
                var res1       = arrayDates[0].replace("Abr", "Apr");
                var date1      = new Date(res1);
                var start      = date1.getTime();
                
                var res2       = arrayDates[1].replace("Abr", "Apr");
                var date2      = new Date(res2);
                var timeDiff   = Math.abs(date2.getTime() - date1.getTime());
                var diffDays   = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
                $('.nigths').val(diffDays);
                
                var start      = date1.toLocaleDateString();
                var finish     = date2.toLocaleDateString();



                
                if ( status == 8) {
                    $('.total').empty();
                    $('.total').val(0);
                    $('.cost').empty();
                    $('.cost').val(0);

                    $('.beneficio').empty();
                    $('.beneficio').val(0);
                }else if ( status == 7 ){
                    if (sizeApto == 1) {
                        $('.total').empty();
                        $('.total').val(30);

                        $('.cost').empty();
                        $('.cost').val(30);

                        $('.beneficio').empty();
                        $('.beneficio').val(0);
                    }else{
                        $('.total').empty();
                        $('.total').val(50);

                        $('.cost').empty();
                        $('.cost').val(40);

                        $('.beneficio').empty();
                        $('.beneficio').val(10);
                    }
                }else{
                    $.get('/admin/reservas/getPricePark', {park: park, noches: diffDays}).success(function( data ) {
                        pricePark = data;
                        $.get('/admin/reservas/getPriceLujoAdmin', {lujo: lujo}).success(function( data ) {
                            priceLujo = data;

                            $.get('/admin/reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                                price = data;
                                
                                price = (parseFloat(price) + parseFloat(pricePark) + parseFloat(priceLujo));

                                if ( notModifyPrice == 0) {
                                    $('.total').empty();
                                    $('.total').val(price);
                                }
                                    $.get('/admin/reservas/getCostPark', {park: park, noches: diffDays}).success(function( data ) {
                                        costPark = data;
                                        $.get('/admin/reservas/getCostLujoAdmin', {lujo: lujo}).success(function( data ) {
                                            costLujo = data;
                                            $.get('/admin/reservas/getCostBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                                                cost = data;
                                                agencia = $('.agencia').val();
                                                if (agencia == "") {
                                                    agencia = 0;
                                                }
                                                cost = (parseFloat(cost) + parseFloat(costPark) + parseFloat(agencia) + parseFloat(costLujo));
                                                $('.cost').empty();
                                                $('.cost').val(cost);
                                                beneficio = price - cost;
                                                $('.beneficio').empty;
                                                $('.beneficio').val(beneficio);
                                                beneficio_ = (beneficio / price)*100
                                                $('.beneficio-text').empty();
                                                $('.beneficio-text').html(beneficio_.toFixed(0)+"%")

                                            });
                                        });
                                    });
                            });
                        });
                    }); 
                }
                 

                

        }


        $(document).ready(function() {          


            var start  = 0;
            var finish = 0;
            var noches = 0;
            var price = 0;
            var cost = 0;

            $('.daterange1').change(function(event) {
                var date = $(this).val();

                var arrayDates = date.split('-');
                var res1 = arrayDates[0].replace("Abr", "Apr");
                var date1 = new Date(res1);
                var start = date1.getTime();

                var res2 = arrayDates[1].replace("Abr", "Apr");
                var date2 = new Date(res2);
                var timeDiff = Math.abs(date2.getTime() - date1.getTime());
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
                $('.nigths').val(diffDays);

                calculate();
                $('.status').attr("disabled", "disabled");
                $('.btn-complete').removeAttr('disabled');

            });


            
            $('#newroom').change(function(event){ 

                var room = $('#newroom').val();
                var pax = $('.pax').val();
                $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){

                    if (pax < data) {
                        $('.personas-antiguo').empty();
                        $('.personas-antiguo').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
                    }else{
                        $('.personas-antiguo').empty();
                    }
                });

                
                var dataLuxury = $('option:selected', this).attr('data-luxury');;

                if (dataLuxury == 1) {
                    $('.type_luxury option[value=1]').attr('selected','selected');
                    $('.type_luxury option[value=2]').removeAttr('selected');
                } else {
                    $('.type_luxury option[value=1]').removeAttr('selected');
                    $('.type_luxury option[value=2]').attr('selected','selected');
                }



                calculate();
            });

            $('.pax').change(function(event){ 
                var room = $('#newroom').val();
                var real_pax =$('.real_pax').val();
                var pax = $('.pax').val();

                $('.real_pax option[value='+pax+']').attr('selected','selected');
                $('.real_pax option[value='+real_pax+']').removeAttr('selected');

                $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){

                    if (pax < data) {
                        $('.personas-antiguo').empty();
                        $('.personas-antiguo').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
                    }else{
                        $('.personas-antiguo').empty();
                    }
                });

                calculate();
            });

            $('.parking').change(function(event){ 
                var commentBook = $('.book_comments').val();
                $('.book_comments').empty();
                var res = commentBook.replace("Parking: Si\n","");
                res = res.replace("Parking: No\n","");
                res = res.replace("Parking: Gratis\n","");
                res = res.replace("Parking: 50 %\n","");
                calculate();
                
                $('.book_comments').text( $.trim(res+'Parking: '+ $('option:selected', this).text())+"\n");
            });

            $('.type_luxury').change(function(event){ 
                var commentBook = $('.book_comments').val();
                $('.book_comments').empty();
                var res = commentBook.replace("Suplemento de lujo: Si\n","");
                res = res.replace("Suplemento de lujo: No\n","");
                res = res.replace("Suplemento de lujo: Gratis\n","");
                res = res.replace("Suplemento de lujo: 50 %\n","");
                calculate();
                $('.book_comments').text( $.trim(res+'Suplemento de lujo: '+ $('option:selected', this).text())+"\n");
            });

            $('.agencia').change(function(event){ 
                calculate(1);
            });

           
                
            
            $('.total').change(function(event) {
                var price = $(this).val();
                var cost = $('.cost').val();
                var beneficio = (parseFloat(price) - parseFloat(cost));
                
                $('.precio-antiguo').empty;
                $('.precio-antiguo').text('El precio asignado '+price+' y el precio tarifa '+price);
                
                $('.beneficio').empty;
                $('.beneficio').val(beneficio);
                
            });


            $('.editable').change(function(event) {
                var id = $(this).attr('data-id');               
                var importe = $(this).val();
                console.log(id);
                $.get('/admin/pagos/update', {  id: id, importe: importe}, function(data) {
                    window.location.reload();
                });

            });

            $('.cliente').change(function(event) {
                var id = $(this).attr('data-id');;
                var name = $('[name=nombre]').val();
                var email = $('[name=email]').val();
                var phone = $('[name=phone]').val();
                $.get('/admin/clientes/save', { id: id,  name: name, email: email,phone: phone}, function(data) {
                        $('.notification-message').val(data);
                        document.getElementById("boton").click();
                        setTimeout(function(){ 
                            $('.pgn-wrapper .pgn .alert .close').trigger('click');
                             }, 1000);
                });
            });

            $('#overlay').hover(function() {
                $('.guardar').show();
            }, function() {
                $('.guardar').hide();
            });
            $('.status').change(function(event) {



                $('.content-alert-success').hide();
                $('.content-alert-error1').hide();
                $('.content-alert-error2').hide();
                // content-alert-success
                // content-alert-error1
                // content-alert-error2
                var status = $(this).val();
                var id     = $(this).attr('data-id');
                var clase  = $(this).attr('class');
                var email = $('input[name=email]').val();

                if (email == "") {
                    $('.guardar').emtpy;

                    $('.guardar').text("Usuario sin e-mail");
                    $('.guardar').show();
                }

                if (status == 5) {
                    
                    

                    $('#contentEmailing').empty().load('/admin/reservas/ansbyemail/'+id);  
                    $('#btnEmailing').trigger('click');

                       
                }else{
                    $.get('/admin/reservas/changeStatusBook/'+id, { status:status }, function(data) {
                        if (data.status == 'danger') {
                            $.notify({
                                title: '<strong>'+data.title+'</strong>, ',
                                icon: 'glyphicon glyphicon-star',
                                message: data.response
                            },{
                                type: data.status,
                                animate: {
                                    enter: 'animated fadeInUp',
                                    exit: 'animated fadeOutRight'
                                },
                                placement: {
                                    from: "top",
                                    align: "left"
                                },
                                offset: 80,
                                spacing: 10,
                                z_index: 1031,
                                allow_dismiss: true,
                                delay: 60000,
                                timer: 60000,
                            }); 
                        } else {
                            $.notify({
                                title: '<strong>'+data.title+'</strong>, ',
                                icon: 'glyphicon glyphicon-star',
                                message: data.response
                            },{
                                type: data.status,
                                animate: {
                                    enter: 'animated fadeInUp',
                                    exit: 'animated fadeOutRight'
                                },
                                placement: {
                                    from: "top",
                                    align: "left"
                                },
                                allow_dismiss: false,
                                offset: 80,
                                spacing: 10,
                                z_index: 1031,
                                delay: 5000,
                                timer: 1500,
                            }); 
                        }
                    }); 
               }

           });


        });

        $('.cobrar').click(function(event){ 
            var id = $(this).attr('data-id');
            var date = $('.fecha-cobro').val();
            var importe = $('.importe').val();
            var comment = $('.comment').val();
            var type = $('.type_payment').val();
            if (importe == 0) {

            }else{
                $.get('/admin/pagos/create', {id: id, date: date, importe: importe, comment: comment, type: type}).success(function( data ) {
                    window.location.reload();
                });
            }
            
        });

</script>
@endsection