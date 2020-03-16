<script type="text/javascript" src="{{ asset('/js/datePicker01.js')}}"></script>
<script type="text/javascript">

    <?php if ($update == 0): // Datepicker pfor new book ?>
        $(function() {

            $(".daterange1").daterangepicker({
                "buttonClasses": "button button-rounded button-mini nomargin",
                "applyClass": "button-color",
                "cancelClass": "button-light",
                "startDate": moment().format("DD MMM, YY"),
//                "startDate": '10 Dec, YY',
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
    <?php elseif($update == 1): ?>
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
    <?php endif ?>

    function calculate( data, override = true ) {
        var room       = $('#newroom').val();
        var pax        = $('.pax').val();
        var park       = $('.parking').val();
        var lujo       = $('select[name=type_luxury]').val();
        var status     = $('select[name=status]').val();
        var sizeApto   = $('select[name=newroom] option:selected').attr('data-size');
        var comentario = $('.book_comments').val();
        var has_ff_discount = ($('#has_ff_discount').is(':checked')) ? 1 : 0;
        var ff_discount_val = $('#ff_discount').val();

        var date       = $('.daterange1').val();
        var arrayDates = date.split('-');
        var res1       = arrayDates[0].replace("Abr", "Apr");
        var date1      = new Date(res1);
        var start      = date1.getTime();

        var res2       = arrayDates[1].replace("Abr", "Apr");
        var date2      = new Date(res2);
        var timeDiff   = Math.abs(date2.getTime() - date1.getTime());
        var diffDays   = Math.ceil(timeDiff / (1000 * 3600 * 24));


        var start      = date1.yyyymmmdd();
        var finish     = date2.yyyymmmdd();

		if ( override ){
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

	            }else if (sizeApto == 3 || sizeApto == 4){
	                  $('.total').empty();
	                  $('.total').val(100);

	                  $('.cost').empty();
	                  $('.cost').val(70);

	                  $('.beneficio').empty();
	                  $('.beneficio').val(30);

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
	                $('.loading-div').show();

	                var auxTotal = $('.total').val();
	                var auxCosteApto = parseInt($('.costApto').val());
	                var auxCoste = parseInt($('.cost').val());
	                var agencyCost = $('.agencia').val();
	                var promotion = $('.promociones').val();
	                var agencyType = $('.agency').val();
	                var totalPrice = $('.total').val();
	                var totalCost = $('.cost').val();
	                var apartmentCost = $('.costApto').val();
	                var parkingCost = $('.costParking').val();
	                var book_id = $('#book-id').val();

	                $.get('/admin/api/reservas/getDataBook', {
	                    start: start,
	                    finish: finish,
	                    noches: diffDays,
	                    pax: pax,
	                    room: room,
	                    park: park,
	                    lujo: lujo,
	                    agencyCost: agencyCost,
	                    promotion: promotion,
	                    agencyType: agencyType,
                            has_ff_discount:has_ff_discount,
                            ff_discount_val:ff_discount_val,
	                    //total_price: data && data.hasOwnProperty('pvp') ? data.pvp : '',
	                    book_id: book_id
	                }).done(function( data ) {
	                    $('#computed-data').html(JSON.stringify(data));

	                    var isEdited = $('.total').attr('data-edited');
	                    if (data.costes.promotion == 0) {

	                        $('.book_owned_comments').empty();
	                    } else {
	                        $('.book_owned_comments').html('(PROMOCIÓN 3x2 DESCUENTO : '+ Math.abs(data.costes.promotion) +' €)');
	                    }
	                    $('.total').val(data.calculated.total_price);
	                    $('#total_pvp').val(data.calculated.total_price);
	                    $('.cost').val(data.calculated.total_cost);
	                    $('.costApto').val(data.costes.book);
	                    $('.costParking').val(data.costes.parking);
	                    $('.beneficio').val(data.calculated.profit);
	                    $('.beneficio-text').html(data.calculated.profit_percentage + '%');
	                    $('#real-price').html(data.calculated.real_price);
	                    $('#realPVP').html(data.calculated.real_price);
	                    $('#real_parking').html(data.totales.parking);
	                    $('#real_limp').html(data.totales.limp);
	                    $('#real_lujo').html(data.totales.lujo);
	                    $('#real_book').html(data.totales.book);
                        


                            /* fix data.aux.price_modified UNDEFINED */
                                    var price_modified = 0;
                                    if (data.aux){
                                         price_modified = data.aux.price_modified;
                                     }
                                     $('#modified-price').html(price_modified);
	                    if (data.calculated.real_price < price_modified) {
	                        $('#arrow-price-modification').fadeIn().removeClass().addClass('fa fa-arrow-up');
	                    } else if (data.calculated.real_price == price_modified) {
	                        $('#arrow-price-modification').fadeOut();
	                    } else {
	                        $('#arrow-price-modification').fadeIn().removeClass().addClass('fa fa-arrow-down');
	                    }
	                });
	                $('.loading-div').hide();
	            }
	        }

		}else{
          var auxTotal = $('.total').val();
          var auxCosteApto = parseInt($('.costApto').val());
          var auxCoste = parseInt($('.cost').val());
          var agencyCost = $('.agencia').val();
          var promotion = $('.promociones').val();
          var agencyType = $('.agency').val();
          var totalPrice = $('.total').val();
          var totalCost = $('.cost').val();
          var apartmentCost = $('.costApto').val();
          var parkingCost = $('.costParking').val();
          var book_id = $('#book-id').val();
          
          $.get('/admin/api/reservas/getDataBook', {
            start: start,
            finish: finish,
            noches: diffDays,
            pax: pax,
            room: room,
            park: park,
            lujo: lujo,
            agencyCost: agencyCost,
            promotion: promotion,
            agencyType: agencyType,
            has_ff_discount:has_ff_discount,
            ff_discount_val:ff_discount_val,
            //                    total_price: data && data.hasOwnProperty('pvp') ? data.pvp : '',
            book_id: book_id
          }).done(function( data ) {
            $('#computed-data').html(JSON.stringify(data));
          });
        }


    };

    $(document).ready(function() {

        $('.daterange1').change(function(event) {
            var date = $(this).val();

            var arrayDates = date.split('-');
            var res1 = arrayDates[0].replace("Abr", "Apr");
            var date1 = new Date(res1);
            var start = date1.getTime();
            var res2 = arrayDates[1].replace("Abr", "Apr");
            var date2 = new Date(res2);
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            var diffDays = Math.ceil(timeDiff / (1090 * 3600 * 24));
            $('.nigths').val(diffDays);
            
            var aDate = date.trim().split(' - ');
            var start = new Date(aDate[0]);
            var end = new Date(aDate[1]);
            $(this).closest('.input_dates').find('.date_start').val(start.yyyymmmdd());
            $(this).closest('.input_dates').find('.date_finish').val(end.yyyymmmdd());

            calculate();

        });


        $('#newroom').change(function(event){

            var room = $('#newroom').val();
            var pax  = parseFloat($('.pax').val());

             if ( room != "" && pax != "") {
                $.get('/admin/apartamentos/getPaxPerRooms/'+room).done(function( data ){

                    if (pax < data) {
                        $('.personas-antiguo').empty().append('Van menos personas que el mínimo, se cobrará el mínimo de personas que son :'+data);
                    }else{
                        $('.personas-antiguo').empty();
                    }
                });
            }


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
                $.get('/admin/apartamentos/getPaxPerRooms/'+room).done(function( data ){
                    if (pax < data) {
                        $('.personas-antiguo').empty();
                        $('.personas-antiguo').append('Van menos personas que el mínimo, se cobrará el mínimo de personas que son :'+data);
                    }else{
                        $('.personas-antiguo').empty();
                    }
                });
            }


            calculate();
        });

        $('.parking').change(function(event){
            calculate();
        });

        $('.type_luxury').change(function(event){
            calculate();
        });

        $('.agencia').change(function(event){
            calculate(1);
        });

        $('.agency').change(function(event){
            calculate();
        });
        $('.agency').change(function(event){
            calculate();
        });
        $('#has_ff_discount').change(function(event){
            calculate();
        });
        $('#ff_discount').change(function(event){
            calculate();
        });

        $('.promociones').change(function(event){
            calculate(2);
            $('.content_book_owned_comments').show();
            $('.content_image_offert').toggle();

        });

        $('.total').focus(function(event) {
            // If this doesn't have main data attached
            if ($(this).attr('old-data') === undefined) {
                // We attach it to know that has been modified
                $(this).attr('old-data', $(this).val());
            }
        });

        $('td[class="price-references"]').click(function() {
            $('.total').val($(this).html());
            $('.total').trigger("change");
        });

        // Total Cost Calc
        $('.total, .cost').change(function(event) {
            calculateProfit();
        });
        $('.total').change(function(event) {
            calculateProfit();
        });

        // Coste Apto
        $('.costApto').focus(function(event) {
            $(this).attr('data-cost-on-focus', $(this).val());
        });
        $('.costApto').change(function(event) {
            var oldValue = ($(this).attr('data-cost-on-focus'));
            var diff = oldValue - $(this).val();

            var totalCost = $('.cost').val();
            $('.cost').val(totalCost - diff);

            $(this).attr('data-cost-on-focus', $(this).val());
            calculateProfit();
        });

        // Coste Parking
        $('.costParking').focus(function(event) {
            $(this).attr('data-cost-on-focus', $(this).val());
        });
        $('.costParking').change(function(event) {
            var oldValue = ($(this).attr('data-cost-on-focus'));
            var diff = oldValue - $(this).val();

            var totalCost = $('.cost').val();
            $('.cost').val(totalCost - diff);

            $(this).attr('data-cost-on-focus', $(this).val());
            calculateProfit();
        });

        function calculateProfit() {
            var pvp = $('.total').val();
            var totalCost = $('.cost').val();

            var profit = pvp - totalCost;
            var profitPercentage = Math.round((profit / pvp) * 100);

            $('.beneficio').val(profit);
            $('.beneficio-text').html(profitPercentage + ' %');
        }

        // Reset Changes
        $('#reset').click(function() {
            calculate();

            var $el = $(this);
            $el.addClass('fa-spin');

            $('.loading-div').show();

            setTimeout(function() {
                $el.removeClass('fa-spin');
                $('.loading-div').hide();
            }, 1000);
        });


        $('.country').change(function(event) {
            var value = $(this).val();
            if ( value != 'ES') {
                $('.content-cities').hide();
            } else {
                $('.content-cities').show();

            }
        });
        <?php if ($update == 1): // Datepicker and more for update book?>

            $('#fianza').click(function(event) {
                $('.content-fianza').toggle(function() {
                    $('#fianza').css('background-color', '#f55753');
                }, function() {
                    $('#fianza').css('background-color', '#10cfbd');
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

            $('#updateForm').submit(function(event) {
                event.preventDefault();
                var _token              = $('input[name="_token"]').val();
                var nombre              = $('input[name="nombre"]').val();
                var email               = $('input[name="email"]').val();
                var phone               = $('input[name="phone"]').val();
                var customer_id         = $('input[name="customer_id"]').val();
                var dni                 = $('input[name="dni"]').val();
                var address             = $('input[name="address"]').val();
                var country             = $('select[name="country"]').val();
                var province            = $('select[name="province"]').val();
//                var city                = $('input[name="city"]').val();
                var fechas              = $('input[name="fechas"]').val();
                var nigths              = $('input[name="nigths"]').val();
                var pax                 = $('select[name="pax"]').val();
                var real_pax            = $('select[name="real_pax"]').val();
                var newroom             = $('select[name="newroom"]').val();
                var parking             = $('select[name="parking"]').val();
                var type_luxury         = $('select[name="type_luxury"]').val();
                var schedule            = $('select[name="schedule"]').val();
                var scheduleOut         = $('select[name="scheduleOut"]').val();
                var agency              = $('select[name="agency"]').val();
                var agencia             = $('input[name="agencia"]').val();
                var promociones         = $('.promociones').val();
                var total               = $('input[name="total"]').val();
                var cost                = $('input[name="cost"]').val();
                var costApto            = $('input[name="costApto"]').val();
                var costParking         = $('input[name="costParking"]').val();
                var beneficio           = $('input[name="beneficio"]').val();
                var external_id         = $('#external_id').val();
                var start               = $('#start').val();
                var finish              = $('#finish').val();
                var comments            = $('textarea[name="comments"]').val();
                var book_comments       = $('textarea[name="book_comments"]').val();
                var book_owned_comments = $('textarea[name="book_owned_comments"]').val();
                var computed_data       = $('#computed-data').html();

                var has_ff_discount = ($('#has_ff_discount').is(':checked')) ? 1 : 0;
                var ff_discount = $('#ff_discount').val();

                var url        = $('#updateForm').attr('action');




                $.post( url , { _token: _token,
                                nombre: nombre,
                                email: email,
                                phone: phone,
                                dni: dni,
                                customer_id: customer_id,
                                address: address,
                                country: country,
                                province: province,
                                fechas: fechas,
                                nigths: nigths,
                                pax: pax,
                                real_pax: real_pax,
                                newroom: newroom,
                                parking: parking,
                                type_luxury: type_luxury,
                                schedule: schedule,
                                scheduleOut: scheduleOut,
                                agency: agency,
                                agencia: agencia,
                                total: total,
                                cost: cost,
                                costApto: costApto,
                                costParking: costParking,
                                beneficio: beneficio,
                                comments: comments,
                                promociones: promociones,
                                book_comments: book_comments,
                                book_owned_comments: book_owned_comments,
                                computed_data: computed_data,
                                has_ff_discount:has_ff_discount,
                                ff_discount:ff_discount,
                                external_id:external_id,
                                start:start,
                                finish:finish
                },
                function(data) {

                    if (data.status == 'danger') {
                        $.notify({
                            title: '<strong>'+data.title+'</strong>, ',
                            icon: 'glyphicon glyphicon-star',
                            message: '<strong>'+data.response+'</strong> '
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
                            allow_dismiss: true,
                            delay: 1000,
                            timer: 1500,
                        });
                    }


                    if (data.status == "success") {
                        location.reload();
                    }

                });


            });

            $('textarea[name="comments"],textarea[name="book_comments"], textarea[name="book_owned_comments"]').change(function(event) {

                var value = $(this).val();
                var type = $(this).attr('data-type');
                var book = $(this).attr('data-idBook');

                $.get('/admin/books/'+book+'/comments/'+type+'/save', { value: value }, function(data) {

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
                        allow_dismiss: true,
                        delay: 1000,
                        timer: 1500,
                    });

                });

            });



        <?php endif ?>

        $('.loading-div').hide();
        
    });

</script>