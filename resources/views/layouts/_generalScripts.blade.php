<script type="text/javascript" src="https://cdn.rawgit.com/nnattawat/flip/master/dist/jquery.flip.min.js"></script>
<script type="text/javascript">
	/* Calendario */
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
	function unflip() {

    	$("#content-book-response").flip(false);
    	$('#content-book-response .back').empty();
    }
    // function calculateDays(date1, date2){
    // 	var result = "";
    // 	$.post( '/getDiffIndays' , { date1: date1, date2: date2}, function(data) {
	   //  	result = data;
	   //  });

	   //  return result;
    // }

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


	    $("#content-book-response").flip({
	      trigger: 'manual'
	    });

	   
	    $('#form-book-apto-lujo').submit(function(event) {

	    	event.preventDefault();


	    	var _token   = $('input[name="_token"]').val();
	    	var name     = $('input[name="name"]').val();
	    	var email    = $('input[name="email"]').val();
	    	var phone    = $('input[name="telefono"]').val();
	    	var date     = $('input[name="date"]').val();
	    	var quantity = $('select[name="quantity"]').val();
	    	var apto     = $('input:radio[name="apto"]:checked').val();
	    	var luxury   = $('input:radio[name="luxury"]:checked').val();
	    	var parking  = 'si';
	    	var comment  = $('textarea[name="comment"]').val();

			var url        = $(this).attr('action');
			
			var dateAux    = date;
			
			var arrayDates = dateAux.split('-');
			var res1       = arrayDates[0].replace("Abr", "Apr");
			var date1      = new Date(res1);
			var start      = date1.getTime();
			
			var res2       = arrayDates[1].replace("Abr", "Apr");
			var date2      = new Date(res2);
			var timeDiff   = Math.abs(date2.getTime() - date1.getTime());
			

			$.post( '/getDiffIndays' , { date1: arrayDates[0], date2: arrayDates[1]}, function( data ) {
                var diffDays = data.diff;
                var minDays = data.minDays;

		    	if(diffDays >= minDays ){
		    		$.post( url , {_token : _token,  name : name,    email : email,   phone : phone,   fechas : data.dates,    quantity : quantity, apto : apto, luxury : luxury,  parking : parking, comment : comment}, function(data) {
		    			
		    			$('#content-book-response .back').empty();
		    			$('#content-book-response .back').append(data);
		    			$("#content-book-response").flip(true);

		    		});
		    	}else{
		    		alert('Estancia minima '+minDays+' NOCHES')
		    	}
			});	

			



	    	

	    });
	    
	    <?php if ($mobile->isMobile() || $mobile->isTablet()): ?>
	    	$('#banner-offert, .menu-booking').click(function(event) {
	    		$('#content-book').show('400');
	    		$('#banner-offert').hide();
	    		$('#line-banner-offert').hide();
	    		$('#desc-section').hide();
	    		$('html, body').animate({
	    		       scrollTop: $("#content-book").offset().top 
	    		   }, 2000);
	    	});

	    	$('#close-form-book').click(function(event) {
	    		$('#banner-offert').show();
	    		$('#line-banner-offert').show();
	    		$('#content-book').hide('100');
	    		$('#desc-section').show();
	    		unflip();
	    		
	    		$('html, body').animate({
	    	       scrollTop: $("body").offset().top
	    	   	}, 2000);
	    	});

	    <?php else: ?>
	    	$('#banner-offert, .menu-booking').click(function(event) {
	    		$('#content-book').show('400');
	    		$('#banner-offert').hide();
	    		$('#line-banner-offert').hide();

	    		$('html, body').animate({
	    		       scrollTop: $("#content-book").offset().top - 85
	    		   }, 2000);
	    	});


	    	$('#close-form-book').click(function(event) {
	    		$('#banner-offert').show();
	    		$('#line-banner-offert').show();
	    		$('#content-book').hide('100');
	    		
	    		unflip();
	    		$('html, body').animate({
	    		       scrollTop: $("body").offset().top
	    		   }, 2000);
	    	});
	    <?php endif; ?>

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
	    $(".apto-3dorm").click(function(event) {

            $("#luxury-yes").trigger('click');


            $("#luxury-no").prop("disabled",true);
            $("#luxury-no").hide();


        });
	    $(".apto-chlt").click(function(event) {

	    	$("#luxury-no").trigger('click');


	    	$("#luxury-yes").prop("disabled",true);
			$("#luxury-yes").hide();

			
	    });

	    $('#quantity').change(function(event) {
	    	var pax = $(this).val();

	    	if (pax <= 4) {
	    		$("#apto-estudio").prop("disabled",false);

	    		$("#apto-estudio").trigger('click');
	    		$("#apto-estudio").show();

	    	}else if (pax > 4) {
	    		if (pax <= 8) {
	    			$(".apto-2dorm").trigger('click');
	    			$("#apto-estudio").prop("disabled",true);
	    			$("#apto-estudio").hide();
	    		} else {
	    			$(".apto-3dorm").trigger('click');
	    			
	    			$("#apto-2dorm").prop("disabled",true);
	    			$("#apto-2dorm").hide();

	    			$("#apto-estudio").prop("disabled",true);
	    			$("#apto-estudio").hide();
	    		}
	    	}
	    });

	});
	
	/* Para pagina de apartamentos */

	$('span.close').click(function(event) {
		$('#content-form-book').hide('400');
		unflip();
		$('html, body').animate({
	       scrollTop: $("body").offset().top
	   }, 2000);
		$('#fixed-book').fadeIn();
	});

	<?php if (!$mobile->isMobile()): ?>

		$('#showFormBook').click(function(event) {
			$('#content-form-book').slideDown('400');
			$('html,body').animate({
			        scrollTop: $("#content-form-book").offset().top - 80},
	        'slow');
		});
	<?php else: ?>

		$('#showFormBook').click(function(event) {
			$('#content-form-book').slideDown('400');
			$('html,body').animate({
			        scrollTop: $("#content-form-book").offset().top},
	        'slow');

        	$('#fixed-book').fadeOut();

		});
	<?php endif; ?>
</script>	