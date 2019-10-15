
    $(document).ready(function() {
     
      $('.sendPartee').click(function(event) {
         var bID = $(this).data('id');
      var sms = $(this).data('sms');
        $.ajax({
        url: '/ajax/showSendRemember/'+bID,
        type: 'GET',
        success: function (response) {
          $('#modalSendPartee_sms').text(sms+" Sms enviados");
          $('#modalSendPartee_content').html(response);
          $('#modalSendPartee_title').html('Enviar Recordatorio para completar Partee');
          $('#modalSendPartee').modal('show');
        },
        error: function (response) {
          alert('No se ha podido obtener los detalles de la consulta.');
        }
      });
      
        

        
      });
      
      $('body').on('click','.showParteeData',function(event) {
        var partee_id= $(this).data('partee_id');
        if ($('#modalSendPartee').is(':visible') == false){
          $('#modalSendPartee').modal('show');
        }
        $('#modalSendPartee_content').html('cargando información...');
        
        $.ajax({
        url: '/ajax/partee-checkHuespedes/'+partee_id,
        type: 'GET',
        success: function (response) {
          $('#modalSendPartee_title').html('');
          $('#modalSendPartee_content').html(response);
         
        },
        error: function (response) {
          alert('No se ha podido obtener los detalles de la consulta.');
        }
      });
      });
      
      $('.finish_partee').click(function(event) {
//        var id = $(this).attr('data-id');
        var id = $(this).data('id');
        var that = $(this);
        var rowTr = that.closest('tr');
        var thatBtn = that.closest('td');
        that.remove();
        thatBtn.html('<div>Sending...</div>');
        $.post('/ajax/send-partee-finish', { _token: "{{ csrf_token() }}",id:id }, function(data) {
                    if (data.status == 'danger') {
                      thatBtn.html('<div class="text-danger">Error</div>');
                        $.notify({
                            title: '<strong>Partee</strong>, ',
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
                            title: '<strong>Partee</strong>, ',
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
                        thatBtn.html('<div>Enviado</div>');
                        that.closest('tr').remove();
                    }
                });
        });
        
    });
  var copyParteeMsg = function (bookID,elem=null,tooltip='tooltipPartee') {
      $.get('/get-partee-msg', {bookID: bookID},
              function (data) {
                if (data == 'empty') {
                  alert('No se ha encontrado un registro asociado');
                } else {
                  var dummy = document.createElement("textarea");
                  // to avoid breaking orgain page when copying more words
                  // cant copy when adding below this code
                  // dummy.style.display = 'none'
                  if (elem){
                    $('#'+elem).append(dummy);
                  } else {
                    document.body.appendChild(dummy);
                  }
                  //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
                  dummy.value = data;
                  dummy.select();
                  document.execCommand("copy");
                  if (elem){
                    $('#'+elem).html('');
                  } else {
                    document.body.removeChild(dummy);
                  }
                  if (tooltip){
                    $('#'+tooltip).addClass('show');
                    setTimeout(function () {
                      $('#'+tooltip).removeClass('show');
                    }, 5000);
                  } else {
                    alert('Mensaje Partee Copiado');
                  }
                }

              });
    }