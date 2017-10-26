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
    input.calculate-total{
        background: #0c685f!important;
        border-color: #0c685f!important;
        color: white!important;
        font-size: 60px;
        font-weight: 800;
        text-align: center;
        height: 90px;
    }
</style>
<div class="modal-header clearfix text-left">
    <div class="row">
        <div class="col-xs-12 bg-black push-20">
            <h4 class="text-center white">
                CALCULAR RESERVA
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
                <i class="pg-close fs-20" style="color: #e8e8e8;"></i>
            </button>
        </div>
    </div>
</div>
<div class="row push-20">
    <div class="col-md-12">
        <form role="form"  action="{{ url('/admin/reservas/create') }}" method="post" >
            <!-- DATOS DEL CLIENTE -->
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden"  name="status" class="status form-control minimal" data-id="<?php echo $book->id ?>" value="5">

            <div class="col-md-8 col-xs-12 push-20">

                <div class="col-md-4 col-xs-12 push-10">
                    <label for="name">Nombre</label> 
                    <input class="form-control calculate-cliente" type="text" name="name">
                </div> 
                <div class="col-md-5 col-xs-12 push-10">
                    <label>Entrada</label>
                    <div class="input-prepend input-group">
                        <input type="text" class="form-control calculate-daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center;min-height: 28px;" readonly="">
                    </div>
                </div>
                <div class="col-md-3 col-xs-12 push-10">
                    <label>Apartamento</label>
                    <select class="form-control full-width calculate-newroom minimal" name="calculate-newroom" id="calculate-newroom">
                        <option ></option>
                        <?php foreach ($rooms as $room): ?>
                            <option value="<?php echo $room->id ?>" data-luxury="<?php echo $room->luxury ?>" data-size="<?php echo $room->sizeApto ?>">
                                <?php echo $room->name ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-3 col-xs-12 push-10">
                    <label>Parking</label>
                    <select class=" form-control calculate-parking minimal"  name="parking">
                        <?php for ($i=1; $i <= 4 ; $i++): ?>
                            <option value="<?php echo $i ?>">
                                <?php echo $book->getParking($i) ?>
                            </option>
                        <?php endfor;?>
                    </select>
                </div>
                 <div class="col-md-3 col-xs-12 push-10">
                    <label>Sup. Lujo</label>
                    <select class=" form-control full-width calculate-type_luxury minimal" name="type_luxury">
                        <?php for ($i=1; $i <= 4 ; $i++): ?>
                            <option value="<?php echo $i ?>" <?php echo ($i == 2)?"selected": "" ?>>
                                <?php echo $book->getSupLujo($i) ?>
                            </option>
                        <?php endfor;?>
                    </select>
                </div>
                <div class="col-md-2 col-xs-12 push-10">
                    <label>Noches</label>
                    <input type="text" class="form-control calculate-nigths" name="nigths" style="width: 100%" disabled>
                    <input type="hidden" class="form-control calculate-nigths" name="nigths" style="width: 100%" >
                </div> 
                <div class="col-md-2 col-xs-12 push-10">
                    <label>Pax</label>
                    <select class=" form-control calculate-pax minimal"  name="pax">
                        <?php for ($i=1; $i <= 10 ; $i++): ?>
                            <option value="<?php echo $i ?>">
                                <?php echo $i ?>
                            </option>
                        <?php endfor;?>
                    </select>
                    
                </div>
                <div class="col-md-2 col-xs-12 push-10">
                    <label style="color: red">Px-real</label>
                    <select class=" form-control calculate-real_pax minimal"  name="real_pax" style="color:red">
                        <?php for ($i=1; $i <= 10 ; $i++): ?>
                            <option value="<?php echo $i ?>" style="color:red">
                                <?php echo $i ?>
                            </option>
                        <?php endfor;?>
                    </select>
                </div>
                
            </div>
            <div class="col-md-4 col-xs-12 push-20">
             <div class="col-xs-12 text-center" style="background-color: #0c685f;">
                    <label class="font-w800 text-white" for="">TOTAL</label>
                    <input type="text" class="form-control calculate-total m-t-10 m-b-10 white" name="total" >
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-md btn-cons btn-complete font-s24 font-w400" type="submit" style="min-height: 50px;">Guardar</button>
                </div>  
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">

    $(function() {
      $(".calculate-daterange1").daterangepicker({
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
            var room       = $('#calculate-newroom').val();
            var pax        = $('.calculate-pax').val();
            var park       = $('.calculate-parking').val();
            var lujo       = $('select[name=type_luxury]').val();
            var status     = $('input[name=status]').val();
            var sizeApto   = $('option:selected', 'select[name=calculate-newroom]').attr('data-size');
            var beneficio  = 0;
            var costPark   = 0;
            var pricePark  = 0;
            var costLujo   = 0;
            var priceLujo  = 0;
            var agencia    = 0;
            var beneficio_ = 0;
            var comentario = "";
            var date       = $('.calculate-daterange1').val();
            
            var arrayDates = date.split('-');
            var res1       = arrayDates[0].replace("Abr", "Apr");
            var date1      = new Date(res1);
            var start      = date1.getTime();
            
            var res2       = arrayDates[1].replace("Abr", "Apr");
            var date2      = new Date(res2);
            var timeDiff   = Math.abs(date2.getTime() - date1.getTime());
            var diffDays   = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
            $('.calculate-nigths').val(diffDays);
            
            var start      = date1.toLocaleDateString();
            var finish     = date2.toLocaleDateString();



            
            if ( status == 8) {
                $('.calculate-total').empty();
                $('.calculate-total').val(0);
            }else if ( status == 7 ){
                if (sizeApto == 1) {
                    $('.calculate-total').empty();
                    $('.calculate-total').val(30);
                }else{
                    $('.calculate-total').empty();
                    $('.calculate-total').val(50);
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
                                $('.calculate-total').empty();
                                $('.calculate-total').val(price);
                            }
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

        $('.calculate-daterange1').change(function(event) {
            var date = $(this).val();

            var arrayDates = date.split('-');
            var res1 = arrayDates[0].replace("Abr", "Apr");
            var date1 = new Date(res1);
            var start = date1.getTime();

            var res2 = arrayDates[1].replace("Abr", "Apr");
            var date2 = new Date(res2);
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            $('.calculate-nigths').val(diffDays);

            calculate();

        });



        
        $('#calculate-newroom').change(function(event){ 

            var room = $('#calculate-newroom').val();
            var pax = $('.calculate-pax').val();
            $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){

                if (pax < data) {
                    $('.calculate-personas-antiguo').empty();
                    $('.calculate-personas-antiguo').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
                }else{
                    $('.calculate-personas-antiguo').empty();
                }
            });

            
            var dataLuxury = $('option:selected', this).attr('data-luxury');;

            if (dataLuxury == 1) {
                $('.calculate-type_luxury option[value=1]').attr('selected','selected');
                $('.calculate-type_luxury option[value=2]').removeAttr('selected');
            } else {
                $('.calculate-type_luxury option[value=1]').removeAttr('selected');
                $('.calculate-type_luxury option[value=2]').attr('selected','selected');
            }



            calculate();
        });


        $('.calculate-pax').change(function(event){ 
            var room = $('#calculate-newroom').val();
            var real_pax =$('.calculate-real_pax').val();
            var pax = $('.calculate-pax').val();

            $('.real_pax option[value='+pax+']').attr('selected','selected');
            $('.real_pax option[value='+real_pax+']').removeAttr('selected');

            $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){

                if (pax < data) {
                    $('.calculate-personas-antiguo').empty();
                    $('.calculate-personas-antiguo').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
                }else{
                    $('.calculate-personas-antiguo').empty();
                }
            });


            calculate();
        });
        
        $('.calculate-total').change(function(event) {
            var price = $(this).val();
            var cost = $('.cost').val();
            var beneficio = (parseFloat(price) - parseFloat(cost));
            console.log(beneficio);
            $('.beneficio').empty;
            $('.beneficio').val(beneficio);
        });

    });
</script>
