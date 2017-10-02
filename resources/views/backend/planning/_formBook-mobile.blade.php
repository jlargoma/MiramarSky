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
                <p class="font-s18">Este apartamento ya tiene una reserva confirmada 
                    <a class="alert-link" href="javascript:void(0)">Puedes cambiar los datos aquí mismo</a>!
                </p>
            </div>
        </div>
        <div class="row not-padding">
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
                
                        <div class="col-xs-6">
                            <label for="name">Nombre</label> 
                            <input class="form-control cliente" type="text" name="name" value="<?php echo $request->name; ?>">
                        </div>
                        <div class="col-xs-6">
                            <label for="email">Email</label> 
                            <input class="form-control cliente" type="email" name="email"  value="<?php echo $request->email; ?>">
                        </div>
                        <div style="clear:both;"></div>
                        <div class="col-xs-12">
                            <label for="phone">Telefono</label> 
                            <input class="form-control cliente" type="text" name="phone"  value="<?php echo $request->phone; ?>">
                        </div>  
                        <div style="clear:both;"></div>

                    </div>
                    <!-- DATOS DE LA RESERVA -->
                    <div class="col-xs-12 bg-white padding-block">
                        <div class="col-xs-12 bg-black push-20">
                            <h4 class="text-center white">
                                DATOS DE LA RESERVA
                            </h4>
                        </div>
                        <div class="col-xs-12">
                            <label>Entrada</label>
                            <div class="input-prepend input-group">
                                <input type="text" class="form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center;min-height: 28px;" value="<?php echo $request->fechas; ?>" readonly="">
                            </div>
                        </div>
                        <div style="clear:both;"></div>

                        <div class="col-xs-3  p-t-10">
                            <label><i class="fa fa-moon-o"></i></label>
                            <input type="text" class="form-control nigths" name="nigths" style="width: 100%" value="<?php echo $request->nigths; ?>">
                        </div>
                        <div class="col-xs-4 p-r-0  p-t-10">
                            <label><i class="fa fa-users"></i></label>
                            <select class=" form-control pax minimal"  name="pax">
                                <?php for ($i=1; $i <= 10 ; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php echo $i == $request->pax ? "selected" : ""; ?>>
                                        <?php echo $i ?>
                                    </option>
                                <?php endfor;?>
                            </select>
                        </div>
                        <div class="col-xs-5  p-t-10">
                            <label><i class="fa fa-home"></i></label>
                            <select class="form-control full-width newroom minimal" name="newroom" id="newroom">
                                <option ></option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?php echo $room->id ?>" data-luxury="<?php echo $room->luxury ?>" <?php echo $room->id == $request->newroom ? "selected" : ""; ?>> 
                                        <?php echo $room->name ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div style="clear:both;"></div>


                        <div class="col-xs-4 p-r-0 p-t-10">
                            <label>Parking</label>
                            <select class=" form-control parking minimal"  name="parking">
                                <?php for ($i=1; $i <= 4 ; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php echo $i == $request->parking ? "selected" : ""; ?>>
                                        <?php echo $book->getParking($i) ?>
                                    </option>
                                <?php endfor;?>
                            </select>
                        </div>
                        <div class="col-xs-4 p-r-0 p-t-10">
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
                        <div style="clear:both;"></div>

                    <div class="col-xs-12 bg-white">
                        <div class="col-xs-6 push-10">
                            <label>Agencia</label>
                            <select class="form-control full-width agency minimal" name="agency">
                                <?php for ($i=0; $i <= 2 ; $i++): ?>
                                    <option value="<?php echo $i ?>" <?php echo $i == $request->agency ? "selected" : ""; ?>>
                                        <?php echo $book->getAgency($i) ?>
                                    </option>
                                <?php endfor;?>
                            </select>
                        </div>
                        <div style="clear:both;"></div>

                        <div class="col-xs-6 col-xs-12 push-10">                                                        
                            <label>Cost Agencia</label>
                            <input type="text" class="agencia form-control" name="agencia" value="<?php echo $request->agencia ?>">
                        </div>
                        <div style="clear: both;"></div>
    

                        <div class="col-xs-12 not-padding">
                            <div class="col-md-4 col-xs-4 text-center" style="background-color: #0c685f;">
                                <label class="font-w800 text-white" for="">TOTAL</label>
                                <input type="text" class="form-control total m-t-10 m-b-10 white" name="total"  value="<?php echo $request->total ?>">
                            </div>
                            <div class="col-md-4 col-xs-4 text-center" style="background: #99D9EA;">
                                <label class="font-w800 text-white" for="">COSTE</label>
                                <input type="text" class="form-control cost m-t-10 m-b-10 white" name="cost"  value="<?php echo $request->cost ?>">
                            </div>
                            <div class="col-md-4 col-xs-4 text-center not-padding" style="background: #ff7f27;">
                                <label class="font-w800 text-white" for="">BENEFICIO</label>
                                <input type="text" class="form-control text-left beneficio m-t-10 m-b-10 white" name="beneficio"  style="width: 80%; float: left;" value="<?php echo $request->beneficio ?>">
                                <div class="beneficio-text font-w400 font-s18 white" style="width: 20%; float: left;padding: 25px 0; padding-right: 5px;">

                                </div>
                            </div>
                        </div>
                        
                        <div style="clear:both;"></div>
                        
                    </div>
                    <div class="col-xs-12 bg-white padding-block">
                        <div class="col-md-6 col-xs-12">
                            <label>Comentarios Cliente </label>
                            <textarea class="form-control" name="comments" rows="5" >
                                <?php echo trim( $request->comments ); ?>
                            </textarea>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <label>Comentarios Internos</label>
                            <textarea class="form-control book_comments" name="book_comments" rows="5" >
                                <?php echo trim( $request->book_comments ); ?>
                            </textarea>
                        </div>
                    </div>
                    <div class="row bg-white padding-block">
                        <div class="col-md-4 col-md-offset-4 text-center">
                            <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit" style="min-height: 50px;width: 100%;" disabled>Guardar</button>
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
<script type="text/javascript">

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

        function calculate(){
                var room = $('#newroom').val();
                var pax = $('.pax').val();
                var park = $('.parking').val();
                var lujo = $('.type_luxury').val();

                var beneficio = 0;
                var costPark = 0;
                var pricePark = 0;
                var costLujo = 0;
                var priceLujo = 0;
                var agencia = 0;
                var beneficio_ = 0;

                var date = $('.daterange1').val();

                var arrayDates = date.split('-');
                var date1 = new Date(arrayDates[0]);
                var date2 = new Date(arrayDates[1]);
                var timeDiff = Math.abs(date2.getTime() - date1.getTime());
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
                
                var start = date1.toLocaleDateString();
                var finish = date2.toLocaleDateString();

                $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){

                    if (pax < data) {
                        $('.pax').attr('style' , 'background-color:red');
                        // $('.book_comments').empty();
                        $('.book_comments').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
                    }else{
                        // $('.book_comments').empty();
                        $('.pax').removeAttr('style');
                    }
                });
                

                $.get('/admin/reservas/getPricePark', {park: park, noches: diffDays}).success(function( data ) {
                    pricePark = data;
                    $.get('/admin/reservas/getPriceLujoAdmin', {lujo: lujo}).success(function( data ) {
                        priceLujo = data;

                        $.get('/admin/reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                            price = data;
                            
                            price = (parseFloat(price) + parseFloat(pricePark) + parseFloat(priceLujo));
                            $('.total').empty();
                            $('.total').val(price);
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
                                            $('.beneficio-text').empty;
                                            $('.beneficio-text').html(beneficio_.toFixed(0)+"%")

                                        });
                                    });
                                });
                        });
                    });
                });  

                $('.btn-complete').removeAttr('disabled');

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

                var date1 = new Date(arrayDates[0]);
                var start = date1.getTime();

                var date2 = new Date(arrayDates[1]);
                var timeDiff = Math.abs(date2.getTime() - date1.getTime());
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
                $('.nigths').val(diffDays);

            });



            
            $('#newroom').change(function(event){ 

                var dataLuxury = $('option:selected', this).attr('data-luxury');;

                // alert(dataLuxury);
                if (dataLuxury == 1) {
                    $('.type_luxury option[value=1]').attr('selected','selected');
                } else {
                    $('.type_luxury option[value=2]').attr('selected','selected');
                }


                calculate();
            });

            $('.pax').change(function(event){ 
                calculate();
            });

            $('.parking').change(function(event){ 
                var commentBook = $('.book_comments').val();
                $('.book_comments').empty();

                calculate();
                
                $('.book_comments').text( $.trim(commentBook+'Parking: '+ $('option:selected', this).text())+"\n");
            });

            $('.type_luxury').change(function(event){ 
                var commentBook = $('.book_comments').val();
                $('.book_comments').empty();
                calculate();
                $('.book_comments').text( $.trim(commentBook+'Suplemento de lujo '+ $('option:selected', this).text())+"\n");
            });

            $('.agencia').change(function(event){ 
                calculate();
            });

           
                
            
            $('.total').change(function(event) {
                var price = $(this).val();
                var cost = $('.cost').val();
                var beneficio = (parseFloat(price) - parseFloat(cost));
                console.log(beneficio);
                $('.beneficio').empty;
                $('.beneficio').val(beneficio);
            });

        });
</script>
@endsection