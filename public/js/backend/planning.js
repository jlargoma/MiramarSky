 $(document).ready(function() {

        // Modal de nueva reserva
        $('.btn-newBook').click(function(event) {

          $.get('/admin/reservas/new', function(data) {

            $('.contentNewBook').empty().append(data);

          });

        });

        // Modal de calcular reserva
        $('.btn-calcuteBook').click(function(event) {
          $('#modalCalculateBook .modal-content').empty().load('/admin/reservas/help/calculateBook');
        });

        $('#lastBooks').click(function(event) {
          $('#modalLastBooks .modal-content').empty().load('/admin/reservas/api/lastsBooks');
        });
        $('.btn_intercambio').click(function(event) {
          $('#modalIntercambio .contentModalIntercambio').empty().load('/admin/reservas/api/intercambio');
        });


        // Mostrar u ocultar formulario de stripe
        $('#stripePayment').click(function(event) {
          $('#stripe-conten-index').toggle(function() {
            $('#stripePayment').css('background-color', '#f55753');
          }, function() {
            $('#stripePayment').css('background-color', '#10cfbd');
          });

        });

        // Alertas de booking
        $('#btnAlertsBookking').click(function(event) {
          $('#modalAlertsBooking .modal-content').empty().load('/admin/reservas/api/alertsBooking');
        });

        // Cargar tablas de reservas
        $('.btn-tables').click(function(event) {
          var type = $(this).attr('data-type');
          var year = $('#fechas').val();
          $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {

            $('#resultSearchBook').empty();

            $('.content-tables').empty().append(data);
            $('.content-tables').show();

          });


        });
        
        $('.sendImportICal').click(function(event) {
          event.preventDefault()
          $('#modalICalImport').modal('show'); 
          $('#modal_ical_content').load("ical/getLasts");
        });
        $('#syncr_ical').click(function(event) {
          event.preventDefault()
          $('#syncr_ical_succss').hide();
          var icon = $(this).find('.fa');
          icon.addClass('fa-spin');
          
          var request = $.ajax({url: "admin/ical/importFromUrl",method: "GET"});
          request.done(function( msg ) {
            if (msg == 'ok'){
              $('#modal_ical_content').load("ical/getLasts");
              $('#syncr_ical_succss').show();
             } else {
               alert( "Sync failed: " + msg );
             }
            icon.removeClass('fa-spin');
          });

          request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            icon.removeClass('fa-spin');
          });

        });
        
        
          
      
        // Cargamos el calendario cuando acaba de cargar la pagina
        setTimeout(function(){
          $('.content-calendar').empty().load('/getCalendarMobile');
        }, 1500);
      
//        $('.cargar_calend').on('click',function(){
//          $('.content-calendar').empty().load('/getCalendarMobile');
//          $(this).remove();
//        });
        // CARGAMOS POPUP DE CALENDARIO BOOKING
        $('.btn-calendarBooking').click(function(event) {
          $('#modalCalendarBooking .modal-content').empty().load('/admin/reservas/api/calendarBooking');
        });
        // CARGAMOS POPUP DE CALENDARIO BOOKING
        $('#btnParteeToActive').click(function(event) {
          $('#modalParteeToActive').modal('show');
          $('#_alarmsPartee').empty().load('/admin/get-partee');
        });

        // Buscador al vuelo de reservas por nombre del cliente

        $('.searchabled').keyup(function(event) {
          var searchString = $(this).val();
          if (searchString.length < 3 && searchString.length != 0) {
            return false;
          }
          var year = $('#fechas').val();

          bookSearch(searchString, year);
        });

        var delayTimer;
        function bookSearch(searchString, year) {
          clearTimeout(delayTimer);

          delayTimer = setTimeout(function () {
            $.get('/admin/reservas/search/searchByName', { searchString: searchString,  year: year}, function(data) {
              $('#resultSearchBook').empty();
              $('#resultSearchBook').append(data);
              $('.content-tables').hide();
              $('#resultSearchBook').show();
            }).fail(function() {
              $('#resultSearchBook').empty();
              $('#resultSearchBook').hide();
              $('.content-tables').show();
            });
          }, 300);
        }

          

          $('.btn-cupos').click(function () {
            $.get('/admin/rooms/cupos', function(data) {

              $('#content-cupos').empty().append(data);

            });
          });
 
      $('body').on('click','.openFF', function (event) {
        
        event.preventDefault();
        var id = $(this).data('booking');
        $.post('/admin/forfaits/open', { _token: window.csrf_token,id:id }, function(data) {
//          console.log(data);
          var formFF = $('#formFF');
          formFF.attr('action', data.link);
          formFF.find('#admin_ff').val(data.admin);
          formFF.submit();
        });
      });
      
      
$('body').on('click','.deleteBook',function(event) {
      if(!confirm('¿Quieres Eliminar la reserva?')) return false;
      
        var id = $(this).attr('data-id');
    	$.get('/admin/reservas/delete/'+id, function(data) {

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
                timer: 1500
                }); 

                if (data.title == 'OK'){
                  // recargamos la actual tabla
                  var type = $('.table-data').attr('data-type');
                  var year = $('#fecha').val();
                  $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {
                      $('.content-tables').empty().append(data);
                  });

                  // recargamos el calendario

                  $('.content-calendar').empty().append('<div class="col-xs-12 text-center sending" style="padding: 120px 15px;"><i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br><h2 class="text-center">CARGANDO CALENDARIO</h2></div>');

                  $('.content-calendar').empty().load('/getCalendarMobile');
                }


        });
    });
     
      
      
      //Fianzas
      $('body').on('click','.sendFianza', function (event) {
        var bID = $(this).data('id');
        $.ajax({
          url: '/ajax/showFianza/'+bID,
          type: 'GET',
          success: function (response) {
            $('#modalSendPartee_content').html(response);
            $('#modalSendPartee_title').html('Fianza');
            $('#modalSendPartee').modal('show');
          },
          error: function (response) {
            alert('No se ha podido obtener los detalles de la consulta.');
          }
        });
      });
      $('body').on('click','.copyMsgFianza', function (event) {
        var data = $(this).data('msg');
        var dummy = document.createElement("textarea");
        $('#copyMsgFianza').append(dummy);
        //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
        dummy.value = data;
        dummy.select();
        document.execCommand("copy");
        $('#copyMsgFianza').html('');

        alert('Mensaje Fianza Copiado');
      });
      $('body').on('click','.sendFianzaMail',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Fianza error.');
          return ;
        }
        if (that.hasClass('disabled')) {
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.post('/ajax/send-fianza-mail', { _token: window.csrf_token,id:id }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Fianza Error:',data.status,data.response);
              } else {
                window.show_notif('Fianza:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
        });
        $('body').on('click','.showParteeLink',function(event) {
          $('#linkPartee').show(700);
        });
        
      $('body').on('click','.sendFianzaSMS',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Fianza error.');
          return ;
        }
        if (that.hasClass('disabled')) {
//          alert('No se puede enviar el SMS.');
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.post('/ajax/send-fianza-sms', { _token: window.csrf_token,id:id }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Fianza Error:',data.status,data.response);
              } else {
                window.show_notif('Fianza:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
        });
        
      $('body').on('click','.sendPayment',function(event) {
        var id = $(this).data('id');
        var amount = $('#amount_fianza').val();
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Fianza error.');
          return ;
        }
        if (that.hasClass('disabled')) {
//          alert('No se puede enviar el SMS.');
          return ;
        }
        $('#loadigPage').show('slow');
        
        $.post('/admin/pagos/cobrar', { _token: window.csrf_token,id:id,amount:amount }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Fianza Error:',data.status,data.response);
              } else {
                window.show_notif('Fianza:',data.status,data.response);
                that.addClass('disabled')
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
      });
      
      $('body').on('click','.createFianza',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Fianza error.');
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.get('/admin/createFianza/'+id, { _token: window.csrf_token}, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Fianza Error:',data.status,data.response);
              } else {
                window.show_notif('Fianza:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
      });
      
      
      var loadFF_resume = true;
      $('body').on('mouseover','.showFF_resume',function(){
        var id = $(this).data('booking');
          if (loadFF_resume != id){
            var tooltip = $(this).find('.FF_resume');
            tooltip.load('/admin/forfaits/resume-by-book/'+id);
            loadFF_resume = id;
          }
      });
      
      
      
      ///////////////////////////////////////////////
      
      if (!(window.uRole == "limpieza") || (window.uRole == "agente")){
        $('body').on('click','.changeRoom',function(){
          var bID = $(this).closest('tr').data('id');
          var current = $(this).data('c');
          
          $('#modalChangeBook_room').show();
          $('#modalChangeBook_status').hide();
          $('#btnChangeBook').val(bID);
          $('#modalChangeBookTit').text('Cambiar Apartamento');
          $('.btnChangeRoom').removeClass('active');
          $('#btn_CR'+current).addClass('active');
          $('#modalChangeBook').modal('show'); 
        });
        $('body').on('click','.changeStatus',function(){
          var bID = $(this).closest('tr').data('id');
          var current = $(this).data('c');
          
          $('#modalChangeBookTit').text('Cambiar Estado');
          $('#modalChangeBook_room').hide();
          $('#modalChangeBook_status').show();
          $('#btnChangeBook').val(bID);
          $('.btnChangeStatus').removeClass('active');
          $('#btn_CS'+current).addClass('active');
          $('#modalChangeBook').modal('show'); 
        });
      
      // Cambiamos las reservas
	$('.btnChangeRoom, .btnChangeStatus').on('click',function(event) {
	    var id = $('#btnChangeBook').val();
	    if ($(this).hasClass('btnChangeStatus')) {
	        var status = $(this).data('id');
	        var room = "";
	    }else if ($(this).hasClass('btnChangeRoom')) {
	        var room = $(this).data('id');
	        var status = "";
	    }
            $('#modalChangeBook').modal('hide');
	    if (status == 5) {
	        $('.modal-content.contestado').empty().load('/admin/reservas/ansbyemail/'+id);
	        $('#btnContestado').trigger('click');      
	    }else{
	       	$.get('/admin/reservas/changeBook/'+id, {status:status,room: room}, function(data) {
	            if (data.status == 'danger') {
                      window.show_notif(data.title,data.status,data.response);
	            } else {
                      window.show_notif(data.title,data.status,data.response);
	            }
	            var type = $('.table-data').attr('data-type');
                    var year = $('#fecha').val();
                    $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {
		            $('.content-tables').empty().append(data);
		        });
	       }); 
	    }
	});
      }
      
      var load_comment = true;
      $('body').on('mouseover','.showBookComm',function(){
        var id = $(this).data('booking');
          if (load_comment != id){
            var tooltip = $(this).find('.BookComm');
            tooltip.load('/ajax/get-book-comm/'+id);
            load_comment = id;
          }
      });
      
      ///////////////////////////////////////////////

      setTimeout(
        function () {
          var tutiempo_script = document.createElement('script');
          tutiempo_script.setAttribute('src', "https://www.tutiempo.net/s-widget/l_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G");
          document.body.appendChild(tutiempo_script);
        }, 700);
      
      $('body').on('click','.send_encuesta',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (!confirm('Enviar encuesta por mail?')) {
          return ;
        }
        $('#loadigPage').show('slow');
        
        $.get('/admin/sendEncuesta/'+id, function(data) {
              if (data == 'ok') {
                window.show_notif('OK','success','Encuesta enviada');
                that.addClass('disabled')
                that.prop('disabled', true);
              } else {
                window.show_notif('Error','danger',data);
              }
              $('#loadigPage').hide('slow');
          });
      });
      
      
/****************************************************************************/


    // CARGAMOS POPUP DE CLIENTES POSIBLES
  $('#btnCustomersRequest').click(function (event) {
    $('#modalBookSafetyBox').modal('show');
    $('#_BookSafetyBox').empty().load('/admin/get-CustomersRequest');
  });

  // CARGAMOS POPUP DE RESERVAS SIN CVC
  $('#btnBookingsWithoutCvc').click(function (event) {
    $('#modalBookSafetyBox').modal('show');
    $('#_BookSafetyBox').empty().load('/admin/get-books-without-cvc');
  });

  $('#_BookSafetyBox').on('click','.editCustomerRequest',function(){
    var id = $(this).data('id');
    $.post('/admin/getCustomersRequest', {_token: window.csrf_token, id: id}, function (data) {
      $('#_BookSafetyBox').find('#customerRequestTable').hide();
      var edit = $('#_BookSafetyBox').find('#customerRequestEdit');
      edit.show();
      
      edit.find('#CRE_name').html(data.name);
      edit.find('#CRE_email').html(data.email);
      edit.find('#CRE_pax').html(data.pax);
      edit.find('#CRE_phone').html(data.phone);
      edit.find('#CRE_date').html(data.date);
      edit.find('#CRE_site').html(data.site);
      edit.find('#CRE_status').html(data.status);
      edit.find('#CRE_comment').html(data.comment);
      edit.find('#CRE_booking').html(data.booking);
      edit.find('#CRE_user').val(data.user_id);
      if (data.canBooking){  
        $('#convertCustomerRequest').data('id',id);
        $('#convertCustomerRequest').attr('disable',false);
      } else {
        $('#convertCustomerRequest').attr('disable',true);
      }
      $('#hideCustomerRequest').data('id',id);
      $('#saveCustomerRequest').data('id',id);
      
      if (data.mails){  
        var table = $('#_BookSafetyBox').find('#tableSentMails');
        table.html('');
        $.each((data.mails), function(index, item) {
           var row = '<tr>';
           var content = '<i class="far fa-comment-dots  seeContentPop" data-id="cr_mail_'+item.id+'" data-content="'+nl2br(item.val)+'"></i>';
           row += '<td>' + item.username + '</td>';
           row += '<td>' + item.date + '</td>';
           row += '<td>' + content + '</td>';
           row += '</tr>';
           table.append(row);
         });
      }
      
    });
  });
  $('#_BookSafetyBox').on('click','#hideCustomerRequest',function(){
    var id = $(this).data('id');
    var ObjTR =  $('#_BookSafetyBox').find('#tr_CRT_'+id);
    var user_id = $('#CRE_user').val();
    if (user_id=="" ){
      alert('El usuario es requerido');
      return;
    }
    var data = {
      _token: window.csrf_token,
      id: id,
      userID: $('#CRE_user').val(),
      comments: $('#CRE_comment').val(),
    };
    
    $.post('/admin/hideCustomersRequest', data, function (response) {
      if (response == 'OK'){
        ObjTR.remove();
        $('#_BookSafetyBox').find('#customerRequestTable').show();
        $('#_BookSafetyBox').find('#customerRequestEdit').hide()
      } else {
        window.show_notif('','error', 'El item no pudo ser removido.');
      }
    });
  });
  
  $('#_BookSafetyBox').on('change','#CRE_send_mail',function(){
    if ($(this).is(':checked'))   $(this).closest('.form-check').addClass('alert-danger');
      else    $(this).closest('.form-check').removeClass('alert-danger');
  });
    
  $('#_BookSafetyBox').on('click','#saveCustomerRequest',function(){
    var id = $(this).data('id');
    var ObjTR =  $('#_BookSafetyBox').find('#tr_CRT_'+id);
    var user_id = $('#CRE_user').val();
    if (user_id=="" ){
      alert('El usuario es requerido');
      return;
    }
    var data = {
      _token: window.csrf_token,
      id: id,
      userID: $('#CRE_user').val(),
      comments: $('#CRE_comment').val(),
      send_mail: ($('#CRE_send_mail').is(':checked') ? 1 : 0),
    };
    
    $.post('/admin/saveCustomerRequest', data, function (response) {
      if (response == 'OK'){
        window.show_notif('','success', 'Item guardado.');
        $('#_BookSafetyBox').empty().load('/admin/get-CustomersRequest');
      } else {
        if (response == 'errorMail')
          window.show_notif('','error', 'No se pudo enviar el registro.');
        else   window.show_notif('','error', 'No se pudo guardar el registro.');
      }
    });
  });
  
  $('#_BookSafetyBox').on('click','#cancelCustomerRequest',function(){
    var ObjTR =  $('#_BookSafetyBox');
    ObjTR.find('#customerRequestTable').show();
    ObjTR.find('#customerRequestEdit').hide()
  });
  $('#_BookSafetyBox').on('click','#convertCustomerRequest',function(){
    var id = $(this).data('id');
    if (!id){
      window.show_notif('','error', 'No se puede generar la reserva.');
      return;
    }
    var data = {
      _token: window.csrf_token,
      cr_id: id,
      userID: $('#CRE_user').val(),
      comments: $('#CRE_comment').val(),
    };
    
    $('#modalCalculateBook .modal-content').empty()
            .load('/admin/reservas/help/calculateBook',data,function(){
              $('#modalBookSafetyBox').modal('hide');
              $('#modalCalculateBook').modal();
              $('#form-book-apto-lujo').submit();
    });
//    $.post('/admin/reservas/new',data, function (data) {
//      
//      $('#modalBookSafetyBox').modal('hide');
//      $("#modalNewBook").modal();
//      $('.contentNewBook').empty().append(data);
//    });
  });
 
  /****************************************************************************/
  
  $('body').on('click','.calc_createNew',function(){
    var id = $(this).data('room');
    if (!id){
      window.show_notif('','error', 'No se puede generar la reserva.');
      return;
    }
    var luxury = $(this).data('luxury');
    var info = $(this).data('info');
    
    var data = {
      _token: window.csrf_token,
      calcular_id: id,
      username: $('#calc_username').val(),
      cr_id: $('#cr_id').val(),
      calc_start: $('#calc_start').val(),
      calc_finish: $('#calc_finish').val(),
      calc_pax: $('#calc_pax').val(),
      luxury: luxury,
      info: info,
    };
    
    
    $.post('/admin/reservas/new',data, function (data) {
      $('#modalCalculateBook').modal('hide');
      $("#modalNewBook").modal();
      $('.contentNewBook').empty().append(data);

    });
  });
  
  /****************************************************************************/
    $('body').on('click','#closeUrgente',function(){
     $('.box-alerts-popup').hide();
   });
   $('.box-alerts-popup').on('click','button',function(){
     $('.box-alerts-popup').hide();
   });
  });