<?php   
    use \Carbon\Carbon;  
    setlocale(LC_TIME, "ES"); 
    setlocale(LC_TIME, "es_ES"); 
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts')
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
        @media only screen and (max-width: 767px){
           .daterangepicker {
                left: 12%!important;
                top: 3%!important; 
            }
        }

    </style>
@endsection
    
@section('content') 
   
    <style type="text/css" media="screen">
        .daterangepicker{
            z-index: 10000!important;
        }
    </style>
    <div class="container">
        <div class="col-md-12 m-t-10">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close " data-dismiss="alert" aria-hidden="true" style="right: 0; ">×</button>
                <h3 class="font-w300 push-15">Error</h3>
                <p class="font-s18">Este apartamento 
                    <a class="alert-link" href="javascript:void(0)"><u>ya tiene una reserva confirmada</u>  Puedes cambiar los datos aquí mismo</a>!
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 bg-black push-20">
                <h4 class="text-center white">
                    NUEVA RESERVA
                </h4>
            </div>
            <div class="col-md-12">
                <form role="form"  action="{{ url('/admin/reservas/create') }}" method="post" >
                    <!-- DATOS DEL CLIENTE -->
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="col-md-6 center text-left0">
                        <div class="col-md-4 m-t-10">
                            <label for="status">Estado</label>
                        </div> 
                        <div class="col-md-8 col-xs-12">
                            <select name="status" class="status form-control minimal" data-id="<?php echo $book->id ?>" >
                                <?php for ($i=1; $i < 9; $i++): ?> 
                                    <option <?php echo $i == $request->status ? "selected" : ""; ?> 
                                    <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                                    value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>">
                                        <?php echo $book->getStatus($i) ?>
                                    </option>                                    

                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 bg-white padding-block">
                        <div class="col-xs-12 bg-black push-20">
                            <h4 class="text-center white">
                                DATOS DEL CLIENTE
                            </h4>
                        </div>

                        <div class="col-md-4">
                            <label for="name">Nombre</label> 
                            <input class="form-control cliente" type="text" name="name" value="<?php echo $request->name; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="email">Email</label> 
                            <input class="form-control cliente" type="email" name="email"  value="<?php echo $request->email; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="phone">Telefono</label> 
                            <input class="form-control cliente" type="text" name="phone"  value="<?php echo $request->phone; ?>">
                        </div>  
                        <div class="col-md-3 col-xs-12 push-10">
                            <label for="dni">DNI</label> 
                            <input class="form-control cliente" type="text" name="dni" value="<?php echo $request->dni; ?>">
                        </div>
                        <div class="col-md-3 col-xs-12 push-10">
                            <label for="address">DIRECCION</label> 
                            <input class="form-control cliente" type="text" name="address" value="<?php echo $request->address; ?>">
                        </div>
                        <div class="col-md-3 col-xs-12 push-10">
                            <label for="country">PAÍS</label> 
                            <select class="form-control country minimal"  name="country">
                                <option>--Seleccione país --</option>
                                <?php foreach (\App\Countries::orderBy('code', 'ASC')->get() as $country): ?>
                                    <option value="<?php echo $country->code ?>">
                                        <?php echo $country->country ?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>  
                        <div class="col-md-3 col-xs-12 push-10">
                            <label for="city">CIUDAD</label>
                            <select class="form-control country minimal" name="city">
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
                        <div class="col-md-3 col-xs-9">
                            <label>Entrada</label>
                            <div class="input-prepend input-group push-20">
                                <!-- <?php echo $request->fechas; ?> -->
                                <input type="text" class="form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center;min-height: 28px; width: 100%" value="<?php echo $request->fechas ?>">

                            </div>
                        </div>
                        <div class="col-md-1 col-xs-3 p-l-0 push-20">
                            <label>Noches</label>
                            <input type="text" class="form-control nigths" name="nigths" style="width: 100%"  value="<?php echo $request->nigths; ?>">
                        </div> 
                        <div class="col-md-2 col-xs-3 p-l-0 push-20">
                            <label>Pax</label>
                            <select class=" form-control pax minimal"  name="pax">
                                <?php for ($i=1; $i <= 10 ; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php echo $i == $request->pax ? "selected" : ""; ?>>
                                        <?php echo $i ?>
                                    </option>
                                <?php endfor;?>
                            </select>
                        </div>
                        <div class="col-md-3 col-xs-9 push-20">
                            <label>Apartamento</label>
                            <select class="form-control full-width newroom minimal" name="newroom" id="newroom">
                              <?php foreach ($rooms as $room): ?>
                                <?php if($room->state>0):?>
                                    <option value="<?php echo $room->id ?>" data-luxury="<?php echo $room->luxury ?>" <?php echo $room->id == $request->newroom ? "selected" : ""; ?>> 
                                        <?php echo substr($room->nameRoom." - ".$room->name, 0, 8)  ?>
                                    </option>
                                <?php endif; ?>
                              <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-1 col-xs-6 p-l-0 p-r-0 push-20">
                            <label>Parking</label>
                            <select class=" form-control parking minimal"  name="parking">
                                <?php for ($i=1; $i <= 4 ; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php echo $i == $request->parking ? "selected" : ""; ?>>
                                        <?php echo $book->getParking($i) ?>
                                    </option>
                                <?php endfor;?>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-6 push-20">
                            <label>Sup. Lujo</label>
                            <select class=" form-control full-width type_luxury minimal" name="type_luxury">
                                <?php for ($i=1; $i <= 4 ; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php echo $i == $request->type_luxury ? "selected" : ""; ?>>
                                        <?php echo $book->getSupLujo($i) ?>
                                    </option>
                                <?php endfor;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 bg-white">
                        <div class="col-md-5 col-xs-12 push-20 not-padding">
                            <div class="col-md-5 col-xs-6 push-10">
                                <label>Agencia</label>
                                <select class="form-control full-width agency minimal" name="agency">
                                    <?php for ($i=0; $i <= 4 ; $i++): ?>
                                        <option value="<?php echo $i ?>" {{ $request->agency == $i ? 'selected' : '' }}><?php echo $book->getAgency($i) ?></option>
                                    <?php endfor;?>
                                </select>
                            </div>
                            <div class="col-md-7 col-xs-6 push-10">                                                        
                                <label>Cost Agencia</label>
                                <?php if ($request->agencia == 0.00): ?>
                                    <input type="number" class="agencia form-control" name="agencia" value="">
                                <?php else: ?>
                                    <input type="number" class="agencia form-control" name="agencia" value="<?php echo $request->agencia ?>">
                                <?php endif ?>
                            </div>
                        
                        </div>
                        <div class="col-md-2 col-xs-6 not-padding push-20">
                            <label>promoción 3x2</label>
                            <input type="text" class="promociones only-numbers form-control" name="promociones" value="<?php echo $request->promociones ?>">
                        </div>
                        <div class="col-md-12 col-xs-12 push-20 not-padding">
                            <div class="col-md-3 col-xs-12 text-center " style="background-color: #0c685f;">
                                <label class="font-w800 text-white" for="">TOTAL</label>
                                <input type="text" class="form-control only-numbers total m-t-10 m-b-10 white" name="total" value="<?php echo $request->total ?>">
                            </div>
                            
                            <div class="col-md-3 col-xs-6 text-center " style="background: #99D9EA;">
                                <label class="font-w800 text-white" for="">COSTE TOTAL</label>
                                <input type="text" class="form-control only-numbers cost m-t-10 m-b-10 white" name="cost"  value="<?php echo $request->cost ?>">
                            </div>
                            <div class="col-md-3 col-xs-6 text-center " style="background: #91cf81;">
                                <label class="font-w800 text-white" for="">APTO</label>
                                <input type="text" class="form-control only-numbers costApto m-t-10 m-b-10 white" name="costApto"  value="<?php echo $request->costApto ?>">
                            </div>
                            <div class="col-md-3 col-xs-6 text-center " style="background: #337ab7;">
                                <label class="font-w800 text-white" for="">PARKING</label>
                                <input type="text" class="form-control only-numbers costParking m-t-10 m-b-10 white" name="costParking"  value="<?php echo $request->costParking ?>">
                            </div>
                            <?php if (Auth::user()->role == "admin"): ?>
                                <div class="col-md-3 col-xs-6 text-center  not-padding" style="background: #ff7f27;">
                                    <label class="font-w800 text-white" for="">BENEFICIO</label>
                                    <input type="text" class="form-control text-left beneficio m-t-10 m-b-10 white" name="beneficio" value="<?php echo $request->beneficio ?>">
                                    <div class="beneficio-text font-w400 font-s18 white"></div>
                                </div>
                            <?php endif ?>
                                                 
                        </div>
                        
                        <div class="col-md-12 col-xs-12 not-padding text-left">
                            <p class="precio-antiguo font-s18">
                                <b>El precio asignado <?php echo $book->total_price ?> y el precio de tarifa es <?php echo $book->real_price ?></b>
                            </p>
                        </div> 
                        <div class="col-xs-12 bg-white padding-block">
                            <div class="col-md-4 col-xs-12">
                                <label>Comentarios Cliente </label>
                                <textarea class="form-control" name="comments" rows="5" ><?php echo $book->comment ?></textarea>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label>Comentarios Internos</label>
                                <textarea class="form-control book_comments" name="book_comments" rows="5" ><?php echo $book->book_comments ?></textarea>
                            </div>
                            <div class="col-md-4 col-xs-12 content_book_owned_comments">
                                <label>Comentarios Propietario</label>
                                <textarea class="form-control book_owned_comments" name="book_owned_comments" rows="5" ><?php echo $book->book_owned_comments ?></textarea>
                            </div>
                        </div>
                        <div class="row push-40 bg-white padding-block">
                            <div class="col-md-4 col-md-offset-4 col-xs-12 text-center">
                                <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit" style="min-height: 50px;width: 100%;">Guardar</button>
                            </div>  
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>

    <!-- <script src="{{ asset('assets/plugins/jquery/jquery-1.11.1.min.js') }}" type="text/javascript"></script> -->


@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
@include('backend.planning._bookScripts', ['update' => 0])
@endsection