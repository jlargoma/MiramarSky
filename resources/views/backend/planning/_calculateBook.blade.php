<?php $mobile = new \App\Classes\Mobile(); ?>
<style type="text/css">
    .radio-style:checked + .radio-style-3-label:before{
        background: #1ABC9C!important;
        color: white!important;
    }
    .black {
        color: black!important;
    }
    @media only screen and (max-width: 767px){
        .not-padding-mobile{
            padding: 0!important;
        }
        .daterangepicker{
            top: 5%!important
        }
    }
</style>
<link href="{{ asset('/frontend/hover.css')}}" rel="stylesheet" media="all">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css" />

<div class="modal-header clearfix text-left">
    <div class="row">
        <div class="col-xs-12 bg-black push-20">
            <h4 class="text-center white">
                CALCULAR RESERVA
            </h4>
            <button type="button" class="close close-calculate" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
                <i class="pg-close fs-20" style="color: #e8e8e8;"></i>
            </button>
        </div>
    </div>
</div>

<div id="content-book" class="row clearfix push-10" >    
    <div class="col-xs-12 clearfix"  style="padding: 20px 0;">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="row" id="content-book-response">
                    <div class="col-xs-12 front" >
                        <div id="form-content">
                            <form id="form-book-apto-lujo" action="{{url('/admin/reservas/help/getTotalBook')}}" method="post">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                                <div class="col-md-12">
                                    <div class="form-group col-sm-12 col-xs-12 col-md-6 col-lg-6 white">
                                        <label for="name">Nombre</label>
                                        <input type="text" class="form-control" name="name" id="cal-nombre" placeholder="Nombre..." maxlength="40" aria-label="Escribe tu nombre">
                                    </div>

                                    <div class="form-group col-sm-12 col-xs-6 col-md-6 white">
                                        <label for="date" style="display: inherit!important;">*Entrada - Salida</label>
                                        <input type="text" class="form-control daterange1" id="date"   name="date" required style="cursor: pointer;text-align: center;" readonly="">
                                        <p  class="help-block min-days" style="display:none;line-height:1.2;color:red;">
                                            <b>* ESTANCIA MÍNIMA: 2 NOCHES</b>
                                        </p>
                                    </div>

                                    <div class="hidden-xs hidden-sm" style="clear: both;"></div>

                                    <div class="form-group col-sm-12 col-xs-6 col-md-3 white">
                                        <label for="quantity" style="display: inherit!important;">*Personas</label>
                                        <div class="quantity center clearfix divcenter">
                                            <select id="quantity" class="form-control minimal" name="quantity">
                                                <?php for ($i = 1;  $i <= 14 ; $i++): ?>
                                                  <option value="<?php echo $i ?>"><?php echo $i ?></option>  
                                                <?php endfor ?>
                                            </select>
                                        </div>
                                        <p class="help-block hidden-sm hidden-xs" style="line-height:1.2">Máx 12 pers</p>
                                    </div>
                                        
                                    <div class="form-group col-sm-12 col-xs-4 col-md-5" style="padding: 0">
                                        <label for="parking" style="display: inline!important;" class="col-md-12 parking">* Tipo Apto</label>
                                        <div class="col-md-3 col-xs-6">
                                            <input id="apto-3dorm" class="radio-style apto-3dorm form-control" name="apto" type="radio" value="3dorm">
                                            <label for="apto-3dorm" class="radio-style-3-label">3Dor</label>
                                        </div>
                                        <div class="col-md-3 col-xs-6">
                                            <input id="apto-2dorm" class="radio-style apto-2dorm form-control" name="apto" type="radio" value="2dorm">
                                            <label for="apto-2dorm" class="radio-style-3-label">2Dor</label>
                                        </div>
                                        <div class="col-md-3 col-xs-6">
                                            <input id="apto-chlt" class="radio-style apto-chlt form-control" name="apto" type="radio" value="chlt">
                                            <label for="apto-chlt" class="radio-style-3-label">Chlt</label>
                                        </div>
                                        <div class="col-md-3 col-xs-6">
                                            <input id="apto-estudio" class="radio-style apto-estudio form-control" name="apto" type="radio" value="estudio">
                                            <label for="apto-estudio" class="radio-style-3-label">Est.</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12 col-xs-4 col-md-2 not-padding-mobile">
                                        <label style="display: inline!important;" class="col-md-12 luxury">*lujo</label>
                                        <div class="col-md-6"> 
                                            <input id="luxury-yes" class="radio-style" name="luxury" type="radio"  value="si">
                                            <label for="luxury-yes" class="radio-style-3-label">Si</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="luxury-no" class="radio-style" name="luxury" type="radio" value="no" checked="">
                                            <label for="luxury-no" class="radio-style-3-label">No</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12 col-xs-4 col-md-2 not-padding-mobile">
                                        <label style="display: inline!important;" class="col-md-12 parking">*Parking</label>
                                        <div class="col-md-6">
                                            <input id="parking-yes" class="radio-style" name="parking" type="radio" checked="" value="si">
                                            <label for="parking-yes" class="radio-style-3-label">Si</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="parking-no" class="radio-style" name="parking" type="radio" value="no">
                                            <label for="parking-no" class="radio-style-3-label">No</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12 col-xs-12 col-md-12 col-lg-12 text-center">
                                        <button type="submit" class="btn btn-success btn-cons btn-lg" id="confirm-reserva">Calcular reserva</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-xs-12 back" style="display: none;">

                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('/frontend/js/jquery.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
<script type="text/javascript">
    /* Calendario */
    $(function() {
        $(".daterange1").daterangepicker({
            "buttonClasses": "button button-rounded button-mini nomargin",
            "applyClass": "button-color",
            "cancelClass": "button-light",
            "startDate": moment().format("DD MMM, YY"),
//            "startDate": '01 Dec, YY',
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


    $(document).ready(function() {

        $(".only-numbers").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                 // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
                 // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

       
        $('#form-book-apto-lujo').submit(function(event) {

            event.preventDefault();


            var _token   = $('input[name="_token"]').val();
            var name     = $('#cal-nombre').val();
            var email    = $('input[name="email"]').val();
            var phone    = $('input[name="telefono"]').val();
            var date     = $('input[name="date"]').val();
            var quantity = $('select[name="quantity"]').val();
            var apto     = $('input:radio[name="apto"]:checked').val();
            var luxury   = $('input:radio[name="luxury"]:checked').val();
            var parking  = $('input:radio[name="parking"]:checked').val();
            var comment  = "";

            var url = $(this).attr('action');

            $.post( url , {_token : _token,  name : name,  email : email,   phone : phone,   fechas : date,    quantity : quantity, apto : apto, luxury : luxury,  parking : parking, comment : comment}, function(data) {
                
                $('#content-book-response .back').empty();
                $('#content-book-response .back').append(data);

                $("#content-book-response .front").hide();

                $("#content-book-response .back").show();
                

            });

        });
        

        $('.daterange1').change(function(event) {
            var date = $(this).val();

            var arrayDates = date.split('-');

            var date1 = new Date(arrayDates[0]);
            var date2 = new Date(arrayDates[1]);
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
            console.log(diffDays);
            if (diffDays < 2) {
                $('.min-days').show();
            }else{
                $('.min-days').hide();
            }

        });

        $('#quantity').change(function(event) {
          var pax = $(this).val();

          if (pax <= 4) {
            $("#apto-estudio").prop("disabled", false);

            $("#apto-estudio").trigger('click');
            $("#apto-estudio").show();

          }
          if (pax == 8) {
            $(".apto-2dorm").trigger('click');
            $("#apto-estudio").prop("disabled", true);
            $("#apto-estudio").hide();

          } else if (pax > 8) {
            $(".apto-3dorm").trigger('click');

            $("#apto-2dorm").prop("disabled", true);
            $("#apto-2dorm").hide();

            $("#apto-estudio").prop("disabled", true);
            $("#apto-estudio").hide();
          }
        });


    });

</script>   
