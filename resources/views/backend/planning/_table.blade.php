<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
<script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
<script type="text/javascript" src="/assets/js/datatables.js"></script>

<?php if ( $type == 'pendientes'): ?>

	@include('backend.planning.listados._pendientes', ['books' => $books ])
    <?php if (Auth::user()->role != "agente" ): ?>
        <?php if (!$mobile->isMobile() ): ?>
            <script>
              $('.table-data').dataTable({
                "searching": false,
                "paging":  false,
                "aaSorting": [],
                "columnDefs": [
                  { "targets": [0,1,2,3,4,8,9,10,11,12], "orderable": false }
                  //5,6,7,
                ],

              });
            </script>
        <?php else: ?>
            <script>
              $('.table-data').dataTable({
                "searching": false,
                "paging":   false,
                "aaSorting": [],
                "columnDefs": [
                  {"targets": [0,3,4,6,7,8,9,10,11], "orderable": false }
                  //1,2,5
                ],

              });
            </script>
        <?php endif ?>
    <?php endif ?>
<?php elseif( $type == 'especiales'): ?>

	@include('backend.planning.listados._especiales', ['books' => $books ])

<?php elseif( $type == 'confirmadas' || $type == 'blocked-ical' ): ?>

	@include('backend.planning.listados._pagadas', ['books' => $books, "type" => $type ])
    <?php if (Auth::user()->role != "agente" ): ?>
        <?php if (!$mobile->isMobile() ): ?>
            <script>
              $('.table-data').dataTable({
                "searching": false,
                "aaSorting": [],
                "paging":   false,
                "columnDefs": [
                  {"targets": [0,1,2,3,4,6,9,10], "orderable": false }
                ],

              });
            </script>
        <?php else: ?>
            <script>
              $('.table-data').dataTable({
                "searching": false,
                "aaSorting": [],
                "paging":   false,
                "columnDefs": [
                  {"targets": [0,2,4,5,6,7,8], "orderable": false }
                ],

              });
            </script>
        <?php endif ?>
    <?php endif ?>
<?php elseif( $type == 'checkin'): ?>
	@include('backend.planning.listados._checkin', ['books' => $books ])
    <?php if (Auth::user()->role != "agente" && Auth::user()->role != "limpieza"): ?>
        <?php if (!$mobile->isMobile() ): ?>
            <script>
          $('.table-data').dataTable({
            "searching": false,
            "order": [[ 7, "asc" ]],
            "paging":   false,
            "columnDefs": [
              {"targets": [0,1,2,3,4,5,6,8,9,10,11], "orderable": false }
            ],
          });
        </script>
        <?php else: ?>
            <script>
              $('.table-data').dataTable({
                "searching": false,
                "order": [[ 5, "asc" ], [6, "asc" ]],
                "paging":   false,
                "columnDefs": [
                  {"targets": [0,1,2,3,4,7,8,9,10, 11], "orderable": false }
                ],

              });
            </script>
        <?php endif ?>
    <?php endif ?>

<?php elseif( $type == 'checkout'): ?>
	@include('backend.planning.listados._checkout', ['books' => $books ])

<?php elseif( $type == 'eliminadas'): ?>
	@include('backend.planning.listados._eliminadas', ['books' => $books ])
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

	$('.only-numbers').keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 32, 107, 17, 67, 86, 88 ]) !== -1 ||
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


</script>