/***
 * JS Update Bookings
 */
$(document).ready(function() {
    let dateRangeObj = Object.assign({}, window.dateRangeObj);
    dateRangeObj.locale.format = 'DD MMM, YY';
    $(".daterange1").daterangepicker(dateRangeObj);
    var newPvp = 0;
    var newDisc = null;
    var newPromo = null;
    /**   */
    function getDatesBooking( data, override = true ) { }
    /***************************/
    function fixedPrices(status){
      var status     = $('select[name=status]').val();
      var sizeApto   = $('select[name=newroom] option:selected').attr('data-size');
      if ( status == 8) {
        $('.total').empty().val(0);
        $('.cost').empty().val(0);
        $('.beneficio').empty().val(0);
        return true;
      }
      if ( status == 7) {
        var room  = $('#newroom').val();
        $.get('/admin/api/reservas/getRoomsCostProp/'+room)
            .done(function( data ) {
              if (!data) return null;
              var coste = data[0];
              var price = data[1];
              $('.total').empty().val(price);
              $('.cost').empty().val(coste);
              $('.beneficio').empty().val(price-coste);
            });
//                  $('.total').empty().val(100);
//          $('.cost').empty().val(70);
//          $('.beneficio').empty().val(30);
//          
//          
//        $('.total').empty().val(0);
//        $('.cost').empty().val(0);
//        $('.beneficio').empty().val(0);
        return true;
      }
      return false;
    }
    
    /********************************************/
    function sendCalculatePrices(){
      
      var dates = getDatesBooking();
      
      var room  = $('#newroom').val();
      var start_date  = $('#start').val();
      var finish_date  = $('#finish').val();
      var nigths  = $('.nigths').val();
      var pax   = $('.pax').val();
      var park  = $('.parking').val();
      var lujo  = $('select[name=type_luxury]').val();
        
      if ( room == "" || pax == "") return null;
        
      $('.loading-div').show();
      var auxTotal = $('.total').val();
      var auxCosteApto = parseInt($('.costApto').val());
      var auxCoste = parseInt($('.cost').val());
      var agencyCost = $('.agencia').val();
      var promotion = $('.promociones').val();
      var agencyType = $('.agency').val();
      var totalPrice = $('.total').val();
      var totalCost = $('.cost').val();
      var luz_cost = $('.luz_cost').val();
      var currentRoom = $('#currentRoom').val();
      var apartmentCost = $('.costApto').val();
      var parkingCost = $('.costParking').val();
      var book_id = $('#book-id').val();

      $.get('/admin/api/reservas/getDataBook', {
          start: start_date,
          finish: finish_date,
          pax: pax,
          room: room,
          currentRoom: currentRoom,
          park: park,
          lujo: lujo,
          agencyCost: agencyCost,
          luzCost: luz_cost,
          promotion: promotion,
          agencyType: agencyType,
          book_id: book_id
      }).done(function( data ) {
        if (!data) return null;
        
          $('#computed-data').html(JSON.stringify(data));
            $('#minDay').val(data.aux.min_day).removeClass('danger');
            if (nigths<data.aux.min_day){
              $('#minDay').addClass('danger');
            }
      
//          var isEdited = $('.total').attr('data-edited');
          if (data.public.promo_pvp<1) {
//              $('.promociones').val('');
//              $('.book_owned_comments').empty();
              $('.content_image_offert').hide();
          } else {
//              $('.promociones').val(data.public.promo_pvp);
//              $('.book_owned_comments').html('('+data.public.promo_name+' : '+ Math.abs(data.public.promo_pvp) +' €)');
              $('.book_owned_comments').html(data.public.promo_name);
              $('.content_image_offert').show();
          }
          
          newPvp = data.calculated.total_price;
          var promos = '';
          if (data.public.discount_pvp>0)
            promos += '<b>Descuento '+data.public.discount_name+' ('+data.public.discount+'%):</b> -'+window.formatterEuro.format(data.public.discount_pvp)+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
          if (data.public.promo_pvp>0)
            promos += '<b>Promo '+data.public.promo_name+':</b> -'+window.formatterEuro.format(data.public.promo_pvp)+' ';
          
          if ($('#new_book').val() == 1)     $('#total_pvp').val(data.calculated.total_price);
          $('.promociones').val(data.costes.promotion);
          $('.cost').val(data.calculated.total_cost);
          $('.costApto').val(data.costes.book);
          $('.costParking').val(data.costes.parking);
          $('.luz_cost').val(data.costes.luz);
          $('#currentRoom').val(room);
          $('#real-price').html(data.calculated.real_price);
          $('#publ_price').html(data.public.pvp_init);
          $('#publ_disc').html(parseInt(data.public.PRIVEE)+parseInt(data.public.discount_pvp));
          $('#promos_aplic').html(promos);
          $('#publ_promo').html(data.public.promo_pvp+data.public.discount_pvp);
          $('#publ_limp').html(data.public.price_limp);
          $('#publ_total').html(data.public.pvp);
          $('#publ_beneficio').html(data.calculated.profit);
          $('#publ_beneficio_perc').html(data.calculated.profit_percentage+ '%');
          
          if (data.aux.moreInfo){
            $('.textAptoInfo').show().text(data.aux.moreInfo);
          } else {
            $('.textAptoInfo').hide().text('');
            }
          
          var total_pvp = $('#total_pvp').val();
          var benef = total_pvp - data.calculated.total_cost;
          $('.beneficio').val(benef);
          var profit_percentage = (total_pvp>0) ? parseInt((benef / total_pvp) * 100) : 0;
          $('.beneficio-text').html(profit_percentage + '%');
      });
      $('.loading-div').hide();
    }
    
    
    /**     */
    function calculate( data, override = true ) {
        var comentario = $('.book_comments').val();
        if ( override ){
	  if (fixedPrices()){
            return null;
          }
        }
       sendCalculatePrices();
    };




    $('.daterange1').change(function(event) {
        var date = $(this).val();
        var aDate = date.trim().split(' - ');
        var start = new Date(aDate[0]);
        var end = new Date(aDate[1]);

        var timeDiff = Math.abs(start.getTime() - end.getTime());
        var diffDays = Math.ceil(timeDiff / (1090 * 3600 * 24));
        $('.nigths').val(diffDays);
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

      var dataLuxury = $('option:selected', this).attr('data-luxury');
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

        $('.recalc').change(function(event){
            calculate();
        });

        $('.total').focus(function(event) {
            // If this doesn't have main data attached
            if ($(this).attr('old-data') === undefined) {
                // We attach it to know that has been modified
                $(this).attr('old-data', $(this).val());
            }
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

            var totalCost = parseFloat($('.cost').val() - diff).toFixed(2);
            $('.cost').val(totalCost);

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

            var totalCost = parseFloat($('.cost').val() - diff).toFixed(2);
            $('.cost').val(totalCost);

            $(this).attr('data-cost-on-focus', $(this).val());
            calculateProfit();
        });

        function calculateProfit() {
            var pvp = $('#total_pvp').val();
            var totalCost = $('.cost').val();

            var profit = (parseFloat(pvp) - parseFloat(totalCost)).toFixed(2);
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
        
        
        
        
        if ($('#update_php').val() == 1){ // Datepicker and more for update book?>

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
                        window.show_notif(data.title,data.status,data.response);
                    });
               }

            });

            $('.confirm_PVP_send').click(function(event) {
              var type = 1;
              if ($(this).attr('id') == 'cpvps_refuse') type = 0;
              sendFormBooking($(this).data('value'),type);
            });
            $('#updateForm').submit(function(event) {
                event.preventDefault();
                newPvp = parseFloat(newPvp);
                var totalPvp = parseFloat($('input[name="total"]').val());
                 
                if (newPvp == 0){
                  sendFormBooking(totalPvp,0);
                  return;
                }
               
                if (newPvp == totalPvp){
                  sendFormBooking(totalPvp,0);
                  return;
                }
                $('#confirm_PVP_current').html( window.formatterEuro.format(totalPvp));
                $('#confirm_PVP_modif').html(window.formatterEuro.format(newPvp));
                $('#confirm_PVP_disc').html(newDisc);
                $('#confirm_PVP_promo').html(newPromo);
                
                $('#modal_confirm_PVP').modal();
                $('#cpvps_acept').data('value',newPvp);
                $('#cpvps_refuse').data('value',totalPvp);
                return false;
              });
              
              
    function sendFormBooking(totalPvp,updMetaPrice=0){
      $('.loading-div').show();
      var data = {
          _token : $('input[name="_token"]').val(),
          nombre : $('input[name="nombre"]').val(),
          email  : $('input[name="email"]').val(),
          phone  : $('input[name="phone"]').val(),
          dni      : $('input[name="dni"]').val(),
          address  :  $('input[name="address"]').val(),
          country  : $('select[name="country"]').val(),
          province : $('select[name="province"]').val(),
          fechas   : $('input[name="fechas"]').val(),
          nigths   : $('input[name="nigths"]').val(),
          pax      : $('select[name="pax"]').val(),
          real_pax : $('select[name="real_pax"]').val(),
          newroom  : $('select[name="newroom"]').val(),
          parking  : $('select[name="parking"]').val(),
          type_luxury : $('select[name="type_luxury"]').val(),
          schedule    : $('select[name="schedule"]').val(),
          scheduleOut : $('select[name="scheduleOut"]').val(),
          agency      : $('select[name="agency"]').val(),
          agencia     : $('input[name="agencia"]').val(),
          promociones : $('.promociones').val(),
          luz_cost : $('.luz_cost').val(),
          total_pvp   : totalPvp,
          cost        : $('input[name="cost"]').val(),
          costApto    : $('input[name="costApto"]').val(),
          costParking : $('input[name="costParking"]').val(),
          beneficio   : $('input[name="beneficio"]').val(),
          start       : $('#start').val(),
          finish      : $('#finish').val(),
          comments    : $('textarea[name="comments"]').val(),
          computed_data : $('#computed-data').html(),
          book_comments : $('textarea[name="book_comments"]').val(),
          customer_id   : $('input[name="customer_id"]').val(),
          book_owned_comments  : $('textarea[name="book_owned_comments"]').val(),
          updMetaPrice  : updMetaPrice
          }
          var url = $('#updateForm').attr('action');
          $.post( url ,data,
          function(data) {
              window.show_notif(data.title,data.status,data.response);
              if (data.status == "success") {
                var current_url = $('#current_url').val();
                if (current_url){
                setTimeout(function(){location.href = current_url;},150);
                }
              }
              $('.loading-div').hide();
          });
      };

      $('textarea[name="comments"],textarea[name="book_comments"], textarea[name="book_owned_comments"]').change(function(event) {

          var value = $(this).val();
          var type = $(this).attr('data-type');
          var book = $(this).attr('data-idBook');

          $.get('/admin/books/'+book+'/comments/'+type+'/save', { value: value }, function(data) {
            window.show_notif(data.title,data.status,data.response);

              
          });

      });
}

    $('.loading-div').hide();
        
    if ($('#new_book').val() == 1)  
      setTimeout(function () { calculate(null, false);}, 250);
    
    
 });