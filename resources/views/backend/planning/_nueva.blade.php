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
        <form role="form"  action="{{ url('/admin/reservas/create') }}" method="post" >
            <!-- DATOS DEL CLIENTE -->
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <div class="col-md-6 center text-left0">
                <div class="col-md-4 m-t-10">
                    <label for="status">Estado</label>
                </div> 
                <div class="col-md-8">
                    <select name="status" class="form-control minimal" >
                        <?php for ($i=1; $i <= 9; $i++): ?> 
                            <option <?php echo $i == 3 ? "selected" : ""; ?> 
                            <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                            value="<?php echo $i ?>">
                                <?php echo \App\Book::getStatus($i) ?>
                                
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

                <div class="col-md-4 col-xs-12 push-10">
                    <label for="name">Nombre</label> 
                    <input class="form-control cliente" type="text" name="name">
                </div>
                <div class="col-md-4 col-xs-12 push-10">
                    <label for="email">Email</label> 
                    <input class="form-control cliente" type="email" name="email" >
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
                        <?php for ($i=1; $i <= 12 ; $i++): ?>
                            <?php if ($i != 9 && $i != 11): ?>
                                <option value="<?php echo $i ?>">
                                    <?php echo $i ?>
                                </option>
                            <?php endif; ?>
                        <?php endfor;?>
                    </select>
                   
                </div>
                <div class="col-md-2 col-xs-5 push-xs-10">
                     <label style="color: red">Pax-reales</label>
                     <select class="form-control real_pax "  name="real_pax" style="color:red">
                        <?php for ($i=1; $i <= 12 ; $i++): ?>
                            <?php if ($i != 9 && $i != 11): ?>
                                <option value="<?php echo $i ?>">
                                    <?php echo $i ?>
                                </option>
                            <?php endif; ?>
                        <?php endfor;?>
                     </select>
                   
                </div>
                 
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
                <div class="col-xs-4 not-padding">
                    <div class="col-md-6 col-xs-12 push-10">
                        <label>Agencia</label>
                        <select class="form-control full-width agency minimal" name="agency">
                            <?php for ($i=0; $i <= 2 ; $i++): ?>
                                <option value="<?php echo $i ?>">
                                    <?php echo \App\Book::getAgency($i) ?>
                                </option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <div class="col-md-6 col-xs-12 push-10">                                                        
                        <label>Cost Agencia</label>
                        <input type="number" class="agencia form-control" name="agencia">
                    </div>
                    <div style="clear: both;"></div>
                    <div class="col-md-6 col-xs-12">
                        <label>Extras</label>
                        <select class="full-width form-control select2-hidden-accessible " data-init-plugin="select2" multiple="" name="extras[]" tabindex="-1" aria-hidden="true" style="cursor: pointer">
                            <?php  foreach ($extras as $extra): ?>
                                <option value="<?php echo $extra->id ?>">
                                    <?php  echo $extra->name ?>
                                </option>
                            <?php  endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-8 not-padding">
                    <p class="personas-antiguo" style="color: red">
                    </p>
                </div>
                <div class="col-xs-8 not-padding">
                    <div class="col-md-4 col-xs-12 text-center" style="background-color: #0c685f;">
                        <label class="font-w800 text-white" for="">TOTAL</label>
                        <input type="text" class="form-control total m-t-10 m-b-10 white" name="total" >
                    </div>
                    <?php if (Auth::user()->role == "admin"): ?>
                        <div class="col-md-4 col-xs-12 text-center" style="background: #99D9EA;">
                            <label class="font-w800 text-white" for="">COSTE</label>
                            <input type="text" class="form-control cost m-t-10 m-b-10 white" name="cost" >
                        </div>
                        <div class="col-md-4 col-xs-12 text-center not-padding" style="background: #ff7f27;">
                            <label class="font-w800 text-white" for="">BENEFICIO</label>
                            <input type="text" class="form-control text-left beneficio m-t-10 m-b-10 white" name="beneficio"  style="width: 80%; float: left;">
                            <div class="beneficio-text font-w400 font-s18 white" style="width: 20%; float: left;padding: 25px 0; padding-right: 5px;">

                            </div>
                        </div>
                    <?php endif ?>
                    
                </div>
                
            </div>
            <div class="col-xs-12 bg-white padding-block">
                <div class="col-md-6 col-xs-12">
                    <label>Comentarios Cliente </label>
                    <textarea class="form-control" name="comments" rows="5" ></textarea>
                </div>
                <div class="col-md-6 col-xs-12">
                    <label>Comentarios Internos</label>
                    <textarea class="form-control book_comments" name="book_comments" rows="5" ></textarea>
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
<script type="text/javascript">

    $(function() {
        $(".daterange1").daterangepicker({
            "buttonClasses": "button button-rounded button-mini nomargin",
            "applyClass": "button-color",
            "cancelClass": "button-light",            
            "startDate": '01 Dec, 17',
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


            if ( room != "" && pax != "") {

                $.get('/admin/reservas/getPricePark', {park: park, noches: diffDays, room: room}).success(function( data ) {
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
                            $.get('/admin/reservas/getCostPark', {park: park, noches: diffDays, room: room}).success(function( data ) {
                                costPark = data;
                                $.get('/admin/reservas/getCostLujoAdmin', {lujo: lujo}).success(function( data ) {
                                    costLujo = data;
                                    $.get('/admin/reservas/getCostBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                                        var cost = data;
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
            var room     = $('#newroom').val();
            var real_pax = $('.real_pax').val();
            var pax      = parseInt( $('.pax').val() );

            $('.real_pax option').each(function(index, el) {
                $(this).attr('selected',false);
            });

            
            $('.real_pax option[value='+pax+']').attr('selected','selected');
            

            if (room != "") {
                $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){

                    if (pax < data) {
                        $('.personas-antiguo').empty();
                        $('.personas-antiguo').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
                    }else{
                        $('.personas-antiguo').empty();
                    }
                });
            }


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

            $('.book_comments').text( $.trim(res+'Parking:'+ $('option:selected', this).text())+"\n");
        });

        $('.type_luxury').change(function(event){ 
            var commentBook = $('.book_comments').val();
            $('.book_comments').empty();
            var res = commentBook.replace("Suplemento de lujo: Si\n","");
            res = res.replace("Suplemento de lujo: No\n","");
            res = res.replace("Suplemento de lujo: Gratis\n","");
            res = res.replace("Suplemento de lujo: 50 %\n","");

            calculate();
            $('.book_comments').text( $.trim(res+'Suplemento de lujo:'+ $('option:selected', this).text())+"\n");
        });

        $('.agencia').change(function(event){ 
            calculate(1);
        });




        $('.total').change(function(event) {
            var price = $(this).val();
            var cost = $('.cost').val();
            var beneficio = (parseFloat(price) - parseFloat(cost));
            // console.log(beneficio);
            $('.beneficio').empty;
            $('.beneficio').val(beneficio);
        });

        $('.country').change(function(event) {
            var value = $(this).val();
            if ( value != 'ES') {
                $('.content-cities').hide();
            } else {
                $('.content-cities').show();

            }
        });

        $('.only-numbers').keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 32, 107 ]) !== -1 ||
                 // Allow: Ctrl/cmd+A
                (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                 // Allow: Ctrl/cmd+C
                (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                 // Allow: Ctrl/cmd+X
                (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                 // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
</script>
