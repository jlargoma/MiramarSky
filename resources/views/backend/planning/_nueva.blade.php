<?php
use App\Classes\Mobile;
$mobile = new Mobile();
?>
<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<style type="text/css" media="screen"> 
    .daterangepicker{
        z-index: 10000!important;
    }
    .pg-close{
        font-size: 45px!important;
        color: white!important;
    }
    .push-xs-10{
        margin-bottom: 10px;
    }
    @media only screen and (max-width: 767px){
        .daterangepicker {
            left: 12%!important;
            top: 3%!important;
        }
    }

</style>
<div class="row padding-block">

    <div class="col-xs-12 bg-black push-20">
        <div class="col-md-10">
            <h4 class="text-center white">
                NUEVA RESERVA
            </h4>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
            <i class="pg-close fs-20" style="color: #e8e8e8;"></i>
        </button>
    </div>

    <div class="col-md-12">
        <form role="form"  action="{{ url('/admin/reservas/create') }}" method="post" id="newForm">
            <!-- DATOS DEL CLIENTE -->
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="col-md-6 center text-left0">
                <div class="col-md-4 m-t-10">
                    <label for="status">Estado</label>
                </div>
                <div class="col-md-8">
                    <select name="status" class="form-control minimal" >
	                    <?php  $status = [ 1 => 1, 2 => 2 ]; ?>
                        <?php if ( Auth::user()->role != "agente"): ?>
                        <?php for ($i=1; $i <= 10; $i++): ?>
                        <option <?php echo $i == 3 ? "selected" : ""; ?>
                                <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?> value="<?php echo $i ?>">
                                <?php echo \App\Book::getStatus($i) ?>
                            </option>
                        <?php endfor; ?>
                        <?php else: ?>
                        <?php for ($i=1; $i <= count($status); $i++): ?>
                        <option <?php echo $status[$i] == 3 ? "selected" : ""; ?>
                                <?php echo ($status[$i]  == 1 || $status[$i] == 5) ? "style='font-weight:bold'" : "" ?> value="<?php echo $status[$i] ?>">
			                    <?php echo \App\Book::getStatus($status[$i]) ?>
                            </option>
                        <?php endfor; ?>
                        <?php endif ?>

                    </select>
                </div>
            </div>
            <div class="col-xs-12 bg-white padding-block">
                <div class="col-xs-12 bg-black push-20">
                    <h4 class="text-center white">
                        DATOS DEL CLIENTE
                    </h4>
                </div>

                <div class="col-md-4 col-xs-12 push-10">
                    <label for="name">Nombre</label>
                    <input class="form-control cliente nombre-cliente" type="text" name="name">
                </div>
                <div class="col-md-4 col-xs-12 push-10">
                    <label for="email">Email</label>
                    <input class="form-control cliente email-cliente" type="email" name="email" >
                </div>
                <div class="col-md-4 col-xs-12 push-10">
                    <label for="phone">Telefono</label>
                    <input class="form-control cliente only-numbers" type="text" name="phone" >
                </div>
                <div class="col-md-3 col-xs-12 push-10">
                    <label for="dni">DNI</label>
                    <input class="form-control cliente" type="text" name="dni">
                </div>
                <div class="col-md-3 col-xs-12 push-10">
                    <label for="address">DIRECCION</label>
                    <input class="form-control cliente" type="text" name="address" >
                </div>
                <div class="col-md-3 col-xs-12 push-10">
                    <label for="country">PAÍS</label>
                    <select class="form-control country minimal"  name="country">
                        <option>--Seleccione país --</option>
                        <?php foreach (\App\Countries::orderBy('code', 'ASC')->get() as $country): ?>
                        <option value="<?php echo $country->code ?>" <?php if( $country->code == 'ES'){ echo "selected";} ?>>
                                <?php echo $country->country ?>
                            </option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="col-md-3 col-xs-12 push-10 content-cities">
                    <label for="city">CIUDAD</label>
                    <select class="form-control country minimal" name="city">
                        <option value="">----</option>
                        <?php foreach (\App\Cities::all() as $city): ?>
                        <option value="<?php echo $city->id ?>"><?php echo $city->city ?></option>
                        <?php endforeach ?>
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
                <div class="col-md-3 col-xs-12 push-xs-10">
                    <label>Entrada</label>
                    <div class="input-prepend input-group">
                        <input type="text" class="form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center;min-height: 28px;" readonly="">

                    </div>
                </div>
                <div class="col-md-1 col-xs-6 push-xs-10">
                    <label>Noches</label>
                    <input type="text" class="form-control nigths" name="nigths" style="width: 100%" disabled>
                    <input type="hidden" class="form-control nigths" name="nigths" style="width: 100%" >
                </div>
                <div class="col-md-2 col-xs-6 push-xs-10">
                    <label>Pax</label>
                    <select class=" form-control pax minimal"  name="pax">
                        <?php for ($i=1; $i <= 14 ; $i++): ?>
                        <?php if ($i != 9 && $i != 11): ?>
                        <option value="<?php echo $i ?>">
                                    <?php echo $i ?>
                                </option>
                        <?php endif; ?>
                        <?php endfor;?>
                    </select>
                </div>
                <?php if ( Auth::user()->role != "agente" ): ?>
                <div class="col-md-2 col-xs-5 push-xs-10">
                     <label style="color: red">Pax-reales</label>
                     <select class="form-control real_pax "  name="real_pax" style="color:red">
                        <?php for ($i=1; $i <= 14 ; $i++): ?>
                         <?php if ($i != 9 && $i != 11): ?>
                         <option value="<?php echo $i ?>">
                                    <?php echo $i ?>
                                </option>
                         <?php endif; ?>
                         <?php endfor;?>
                     </select>

                </div>
                <?php endif ?>
                <div class="col-md-3 col-xs-7 push-xs-10">
                    <label>Apartamento</label>
                    <select class="form-control full-width newroom minimal" name="newroom" id="newroom" required>
                        <option ></option>
                        <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room->id ?>" data-luxury="<?php echo $room->luxury ?>" data-size="<?php echo $room->sizeApto ?>">
                                <?php echo substr($room->nameRoom." - ".$room->name, 0,12)  ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <?php if ( Auth::user()->role == "agente" ): ?>
                <div style="clear: both;"></div>
                <?php endif ?>
                <?php if ( Auth::user()->role != "agente" ): ?>
                <div class="col-md-4 col-xs-6 push-xs-10">
                    <label>Parking</label>
                    <select class=" form-control parking minimal"  name="parking">
                        <?php for ($i=1; $i <= 4 ; $i++): ?>
                        <option value="<?php echo $i ?>">
                                <?php echo \App\Book::getParking($i) ?>
                            </option>
                        <?php endfor;?>
                    </select>
                </div>
                <div class="col-md-4 col-xs-6 push-xs-10">
                    <label>Sup. Lujo</label>
                    <select class=" form-control full-width type_luxury minimal" name="type_luxury">
                        <?php for ($i=1; $i <= 4 ; $i++): ?>
                        <option value="<?php echo $i ?>" <?php echo ($i == 2)?"selected": "" ?>>
                                <?php echo \App\Book::getSupLujo($i) ?>
                            </option>
                        <?php endfor;?>
                    </select>
                </div>
                <?php else: ?>
                <input type="hidden" step='0.01' class="parking white" name="parking" value="1">
                    <input type="hidden" step='0.01' class="type_luxury white" name="type_luxury" value="1">
                <?php endif ?>
                <div class="col-md-2 col-xs-6 push-xs-10">
                    <label >IN</label>
                    <select id="schedule" class="form-control " style="width: 100%;" name="schedule">
                        <option>-- Sin asignar --</option>
                        <?php for ($i = 0; $i < 24; $i++): ?>
                        <option value="<?php echo $i ?>" >
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
                <div class="col-md-2 col-xs-6 push-xs-10" style="padding: 0 5px;">
                    <label>Out</label>
                    <select id="scheduleOut" class="form-control " style="width: 100%;" name="scheduleOut">
                        <option>-- Sin asignar --</option>
                        <?php for ($i = 0; $i < 24; $i++): ?>
                        <option value="<?php echo $i ?>" >
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
                <div class="col-xs-12 col-md-4 not-padding">
                    <div class="col-md-6 col-xs-6 push-10">
                        <label>Agencia</label>
                        <select class="form-control full-width agency minimal" name="agency">
                            <?php for ($i=0; $i <= 4 ; $i++): ?>
                            <option value="<?php echo $i ?>">
                                    <?php echo \App\Book::getAgency($i) ?>
                                </option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <div class="col-md-6 col-xs-6 push-10">
                        <label>Cost Agencia</label>
                        <input type="number" class="agencia form-control" step='0.01' name="agencia">
                    </div>

                </div>
                <?php if ( Auth::user()->role != "agente" ): ?>
                <div class="col-xs-12 col-md-6 not-padding">
                    <div class="col-md-6 col-xs-6 push-10">
                        <label>promoción 3x2</label>
                        <input type="number" class="promociones form-control" step='0.01'  name="promociones">
                    </div>
                    <div class="col-md-6 col-xs-6 push-10 content_image_offert" style="display: none;">
                        <img src="/pages/oferta.png" style="width: 90px;">
                    </div>
                </div>
                <?php endif ?>
            </div>
            <div class="col-xs-12">
                <div class="col-xs-12 not-padding">
                    <p class="personas-antiguo" style="color: red">
                    </p>
                </div>
            </div>
            <div class="col-xs-12 bg-white">
                <div class="col-xs-12 not-padding">
                    <div class="col-md-3 col-xs-12 text-center  first" style="background-color: #0c685f;">
                        <label class="font-w800 text-white" for="">TOTAL</label>
                        <input type="number" step='0.01' class="form-control total m-t-10 m-b-10 white" name="total" >
                    </div>
                    <?php if ( Auth::user()->role != "agente" ): ?>
                    <div class="col-md-3 col-xs-6 text-center " style="background: #99D9EA;">
                            <label class="font-w800 text-white" for="">COSTE TOTAL</label>
                            <input type="number" step='0.01' class="form-control  cost m-t-10 m-b-10 white" name="cost" >
                        </div>

                        <div class="col-md-2 col-xs-6 text-center " style="background: #91cf81;">
                            <label class="font-w800 text-white" for="">APTO</label>
                            <input type="number" step='0.01' class="form-control costApto m-t-10 m-b-10 white" name="costApto" >
                        </div>
                        <div class="col-md-2 col-xs-6 text-center " style="background: #337ab7;">
                            <label class="font-w800 text-white" for="">PARKING</label>
                            <input type="number" step='0.01' class="form-control costParking m-t-10 m-b-10 white" name="costParking" >
                        </div>
                    <?else: ?>
                    <input type="hidden" step='0.01' class="cost white" name="cost" >
                        <input type="hidden" step='0.01' class="costApto white" name="costApto" >
                        <input type="hidden" step='0.01' class="costParking white" name="costParking" >
                    <?php endif ?>
                    <?php if (Auth::user()->role == "admin"): ?>
                    <div class="col-md-2 col-xs-6 text-center  not-padding" style="background: #ff7f27;">
                            <label class="font-w800 text-white" style="width: 100%;" for="">BENEFICIO</label>
                            <input type="text" class="form-control text-left beneficio m-t-10 m-b-10 white" name="beneficio"  style="width: 80%; float: left;">
                            <div class="beneficio-text font-w400 font-s18 white" style="width: 20%; float: left;padding: 25px 0; padding-right: 5px;">

                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
            <div class="col-xs-12 bg-white padding-block">
                <div class="col-md-4 col-xs-12">
                    <label>Comentarios Cliente </label>
                    <textarea class="form-control" name="comments" rows="5" ></textarea>
                </div>
                <div class="col-md-4 col-xs-12">
                    <label>Comentarios Internos</label>
                    <textarea class="form-control book_comments" name="book_comments" rows="5" ></textarea>
                </div>
                <div class="col-md-4 col-xs-12 content_book_owned_comments" style="display: none;">
                    <label>Comentarios Propietario</label>
                    <textarea class="form-control book_owned_comments" name="book_owned_comments" rows="5" ></textarea>
                </div>
            </div>
            <div class="row bg-white padding-block">
                <div class="col-md-4 col-md-offset-4 col-xs-12 text-center">
                    <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit" style="min-height: 50px;width: 100%;">Guardar</button>
                </div>  
            </div>
        </form>
    </div>
</div>


<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
@include('backend.planning._bookScripts', ['update' => 0])