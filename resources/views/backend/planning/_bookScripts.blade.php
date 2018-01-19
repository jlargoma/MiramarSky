<script type="text/javascript">

    <?php if ($update == 0): // Datepicker pfor new book ?>
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

    function calculate( notModifyPrice = 0){
        var room       = $('#newroom').val();
        var pax        = $('.pax').val();
        var park       = $('.parking').val();
        var lujo       = $('select[name=type_luxury]').val();
        var status     = $('select[name=status]').val();
        var sizeApto   = $('option:selected', 'select[name=newroom]').attr('data-size');
        var comentario = $('.book_comments').val();

        var date       = $('.daterange1').val();
        var arrayDates = date.split('-');
        var res1       = arrayDates[0].replace("Abr", "Apr");
        var date1      = new Date(res1);
        var start      = date1.getTime();

        var res2       = arrayDates[1].replace("Abr", "Apr");
        var date2      = new Date(res2);
        var timeDiff   = Math.abs(date2.getTime() - date1.getTime());
        var diffDays   = Math.ceil(timeDiff / (1000 * 3600 * 24)); 

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
                $('.loading-div').show();

                var auxTotal = $('.total').val();
                var auxCosteApto = parseInt($('.costApto').val());
                var auxCoste = parseInt($('.cost').val());

                $.get('/admin/api/reservas/getDataBook', {start: start, finish: finish, noches: diffDays, pax: pax, room: room, park: park, lujo: lujo,}).done(function( data ) {

                    var costeApto  = data.costes.book +  data.costes.limp;
                    var costeTotal = costeApto + data.costes.parking + data.costes.lujo;
                    var total      = data.totales.book + data.totales.parking + data.totales.lujo +  data.totales.limp;
                    var parking    = data.costes.parking;

                    if (notModifyPrice == 1) {
                       
                        costeTotal = parseFloat($('.cost').val()) + parseFloat($('.agencia').val());
                        $('.total').val( parseFloat(auxTotal) );
                        $('.cost').val( parseFloat(costeTotal) );

                    }else if (notModifyPrice == 2) {
                        var promo = $('.promociones').val();
                        if (promo == "") {
                            promo = 0;
                        }

                        


                        costeApto = costeApto - parseFloat(promo);
                        costeTotal = auxCoste - parseFloat(promo);

                        $('.cost').val( parseFloat(costeTotal) );
                        $('.total').val( parseFloat(auxTotal) );
                        if (promo == 0) {
                            $('.book_owned_comments').empty();
                        }else{
                            $('.book_owned_comments').empty().append('(PROMOCIÓN 3x2 DESCUENTO : -'+ promo +' €)');
                        }
                         

                    }else if(notModifyPrice == 0){
                        $('.total').val( parseFloat(total) );
                        $('.cost').val( parseFloat(costeTotal) );
                    }

                    total = $('.total').val();
                    
                    $('.costApto').val( parseFloat(costeApto) );
                    $('.costParking').val( parseFloat(parking) );


                    var beneficio = total - costeTotal;
                    $('.beneficio').empty;
                    $('.beneficio').val(beneficio);
                    beneficio_ = (beneficio / total)*100
                    $('.beneficio-text').empty();
                    $('.beneficio-text').html(beneficio_.toFixed(0)+"%");
                    
                });

                $('.loading-div').hide();
                                
            }
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
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
            $('.nigths').val(diffDays);

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
        $('.promociones').change(function(event){ 
            calculate(2);
            $('.content_book_owned_comments').show();

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

        $('.costApto').change(function(event) {

            var cost     = 0;
            var costLujo = 0;
            var lujo     = $('select[name=type_luxury]').val();

            if (lujo == 1) {
                costLujo = 40;
            } else if(lujo == 2 && lujo == 3) {
                costLujo = 0;
            }else{
                costLujo == 20;
            }

            cost = parseFloat( $(this).val() ) + parseFloat( $('.costParking').val() ) + costLujo;

            $('.cost').val(cost);
            // $('.content_book_owned_comments').show();
            
        });


        $('.only-numbers').keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 32, 107, 17, 67, 86, 88  ]) !== -1 ||
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
                var city                = $('select[name="city"]').val();
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
                var comments            = $('textarea[name="comments"]').val();
                var book_comments       = $('textarea[name="book_comments"]').val();
                var book_owned_comments = $('textarea[name="book_owned_comments"]').val();


                var url        = $(this).attr('action');

                


                $.post( url , { _token: _token,
                                nombre: nombre,
                                email: email,
                                phone: phone,
                                dni: dni,
                                customer_id: customer_id,
                                address: address,
                                country: country,
                                city: city,
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
                                book_owned_comments: book_owned_comments }, 
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
                            delay: 5000,
                            timer: 1500,
                        }); 
                    }


                    if (data.status == "success") {
                        location.reload();
                    }

                });

            });

        <?php endif ?>

        $('.loading-div').hide();
    });

</script>