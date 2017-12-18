<?php if ( $type == 'pendientes'): ?>

	@include('backend.planning.listados._pendientes', ['books' => $books ])

<?php elseif( $type == 'especiales'): ?>

	@include('backend.planning.listados._especiales', ['books' => $books ])

<?php elseif( $type == 'confirmadas'): ?>

	@include('backend.planning.listados._pagadas', ['books' => $books ])

<?php elseif( $type == 'checkin'): ?>
	@include('backend.planning.listados._checkin', ['books' => $books ])

<?php elseif( $type == 'checkout'): ?>
	@include('backend.planning.listados._checkout', ['books' => $books ])

<?php endif ?>
<script type="text/javascript">
	// Cambiamos los horarios para Check IN y Check Out
	$('#schedule, #scheduleOut').change(function(event) {

        var type = $(this).attr('data-type');
        var id = $(this).attr('data-id');
        var schedule = $(this).val();

        if (type == "in") {
            var typeNum = 1;
        }else{
            var typeNum = 0;

        }

        $.get('/admin/reservas/changeSchedule/'+id+'/'+typeNum+'/'+schedule, function(data) {
            alert(data);
        });
    });

    $('.deleteBook').click(function(event) {
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
</script>