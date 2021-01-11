
<?php 
$isMobile = $mobile->isMobile();
$role = Auth::user()->role;
?>
<?php 
  $columnDefs = null;
  $orden = null;
  switch ($type):
    case 'pendientes':
    case 'overbooking':
    case 'reservadas':
    case 'blocks':
    case 'cancel-xml':
      ?> @include('backend.planning.listados._pendientes', ['books' => $books ])<?php
      if ($role != "agente"){
        $columnDefs = '0,1,2,3,4,8,9,10,11';
      }
      $orden = '  "order": [[ 0, "desc" ]],';
      break;
    case 'especiales':
      ?> @include('backend.planning.listados._especiales', ['books' => $books ])<?php
      $columnDefs = '0,1,2,3,6,7,8,9';
      break;
    case 'confirmadas':
    case 'blocked-ical':
      ?> @include('backend.planning.listados._pagadas', ['books' => $books ])<?php
      if ( $role != "agente"){
        $columnDefs = '0,1,2,3,4,9,10';
      }
      break;
    case 'checkin':
    case 'ff_pdtes':
      ?> @include('backend.planning.listados._checkin', ['books' => $books ])<?php
      if  ($role != "agente" && $role != "limpieza"){
        $columnDefs = '0,1,2,3,4,5,8,9,10,11';
      }
      $orden = '  "order": [[ 6, "asc" ]],';
      break;
    case 'checkout':
      ?> @include('backend.planning.listados._checkout', ['books' => $books ])<?php
      if ($role != "limpieza"){
        $columnDefs = '0,1,2,5,6';
      }
      break;
    case 'eliminadas':
      ?> @include('backend.planning.listados._eliminadas', ['books' => $books ])<?php
      break;
  endswitch;
?>

      
@if($columnDefs)
<script>   
      $(document).ready(function() {

        $('.table-data').dataTable({
          "searching": false,
          "aaSorting": [],
          "paging":   false,
          "columnDefs": [
            {"targets": [{{$columnDefs}}], "orderable": false }
          ],
          @if($orden) {!!$orden!!}@endif
            paging:  true,
            pageLength: 30,
            pagingType: "full_numbers",
          @if($isMobile)
            scrollX: true,
            scrollY: false,
            scrollCollapse: true,
            fixedColumns:   {
              leftColumns: 1
            },
          @endif

        });
        });
</script>

@endif


<script type="text/javascript">
	// Cambiamos los horarios para Check IN y Check Out
	$('body').on('change','.schedule',function(event) {

        event.preventDefault();
        event.stopImmediatePropagation();
        var type = $(this).attr('data-type');
        var id = $(this).attr('data-id');
        var schedule = $(this).val();

        if (type == "in") {
            var typeNum = 1;
        }else{
            var typeNum = 0;

        }

        $.get('/admin/reservas/changeSchedule/'+id+'/'+typeNum+'/'+schedule, function(data) {
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
                delay: 500,
                timer: 500,
            }); 
        });
    });

    $('.getImagesCustomer').click(function(event) {
      var idRoom = $(this).attr('data-id');
      var idCustomer = $(this).attr('data-idCustomer');
      $.get('/admin/rooms/api/getImagesRoom/'+idRoom+'/'+idCustomer, function(data) {
        $('#modalRoomImages .modal-content').empty().append(data);
      });
    });

    


	// Cambiamos las reservas
	$('.status, .room').change(function(event) {
	    var id = $(this).attr('data-id');
	    var clase = $(this).attr('class');
	    
	    if (clase == 'status form-control minimal') {
	        var status = $(this).val();
	        var room = "";

	    }else if(clase == 'room form-control minimal'){
	        var room = $(this).val();
	        var status = "";
	    }



	    if (status == 5) {

	        $('.modal-content.contestado').empty().load('/admin/reservas/ansbyemail/'+id);
	        $('#btnContestado').trigger('click');      

	    }else{
	        
	       	$.get('/admin/reservas/changeBook/'+id, {status:status,room: room}, function(data) {

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

	            var type = $('.table-data').attr('data-type');
		        var year = $('#fecha').val();
		        $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {
		    
		            $('.content-tables').empty().append(data);

		        });

		        $('.content-calendar').empty().append('<div class="col-xs-12 text-center sending" style="padding: 120px 15px;"><i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br><h2 class="text-center">CARGANDO CALENDARIO</h2></div>');

                $('.content-calendar').empty().load('/getCalendarMobile');

	       }); 
	    }
	});

	$('.sendSecondPay').click(function(event) {
		var id = $(this).attr('data-id');
		var sended = $(this).attr('data-sended');

		if (sended == 0) {
			$.get('/admin/reservas/api/sendSencondEmail', { id:id }, function(data) {
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
		} else {
			if (confirm("Quieres reenviarlo!")) {
				$.get('/admin/reservas/api/sendSencondEmail', { id:id }, function(data) {
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
			}else{
				alert('NO actuamos');
			}
			
		}
		
	});

	// Comentarios flotantes
	$('.icons-comment').hover(function() {
		var content = $(this).attr('data-class-content');
		$('.'+content).show();
	}, function() {
		var content = $(this).attr('data-class-content');
		$('.'+content).hide();
	});

     
	$('.customer-phone').change(function(event) {
		var idCustomer = $(this).attr('data-id');
		var phone = $(this).val();
		$.get('/admin/customer/change/phone/'+idCustomer+'/'+phone, function(data) {
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
			    allow_dismiss: true,
			    offset: 80,
			    spacing: 10,
			    z_index: 1031,
			    delay: 1500,
			    timer: 1500,
			}); 

    		// recargamos la actual tabla
    		var type = $('.table-data').attr('data-type');
	        var year = $('#fecha').val();
	        $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {
	            $('.content-tables').empty().append(data);
	        });
		});
	});

    $('.restoreBook').click(function(event) {
    
      if (!confirm('¿Quieres restaurar la reserva?')){
        return '';
      }
    	var id = $(this).attr('data-id');
    	$.get('/admin/reservas/restore/'+id, function(data) {
           	

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

    		// recargamos la actual tabla
    		var type = $('.table-data').attr('data-type');
	        var year = $('#fecha').val();
	        $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {
	            $('.content-tables').empty().append(data);
	        });

    		// recargamos el calendario

	        $('.content-calendar').empty().append('<div class="col-xs-12 text-center sending" style="padding: 120px 15px;"><i class="fa fa-spinner fa-5x fa-spin" aria-hidden="true"></i><br><h2 class="text-center">CARGANDO CALENDARIO</h2></div>');

            $('.content-calendar').empty().load('/getCalendarMobile');



        });
    });


 $('body').on('click','.sendSMS',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Partee error.');
          return ;
        }
        if (that.hasClass('disabled')) {
//          alert('No se puede enviar el SMS.');
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.post('/ajax/send-partee-sms', { _token: "{{ csrf_token() }}",id:id }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Partee Error:',data.status,data.response);
              } else {
                window.show_notif('Partee:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
        });
 $('body').on('click','.sendParteeMail',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Partee error.');
          return ;
        }
        if (that.hasClass('disabled')) {
//          alert('No se puede enviar el SMS.');
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.post('/ajax/send-partee-mail', { _token: "{{ csrf_token() }}",id:id }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Partee Error:',data.status,data.response);
              } else {
                window.show_notif('Partee:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
        });
        $('body').on('click','.showParteeLink',function(event) {
          $('#linkPartee').show(700);
        });
        
     
</script>