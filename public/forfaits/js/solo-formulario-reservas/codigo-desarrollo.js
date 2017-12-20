// Init page helpers (BS Datepicker + BS Colorpicker + Select2 + Masked Input + Tags Inputs plugins)
//App.initHelpers(['datepicker']);
/**
 * En este trozo de script añadimos o disminuimos
 * los clientes de la reserva
*/
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
$('#date-entrada').daterangepicker({
    "locale": {
        "format": "DD-MM-YYYY",
        "separator": " - ",
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
        "firstDay": 1
    },
	"minDate": dd+"/"+mm+"/"+yyyy,
}, function(start, end, label) {
  console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
});






$('#date-entrada').val('');
$('#date-salida').val('');
$('.today.active.start-date.active.end-date.available').removeClass('active start-date active end-date');

var maxClients = 8;
var clients = 0;

$('#clients').val(clients);

//añadir clientes
$("#add-client").click(function(){
	if(clients < maxClients)
	{
		clients += 1;
		$('#clients').val(clients);
	} else 
		swal("Oops!", "Solo permitimos un maximo de 8 personas por reserva", "info");  	
	showSuplApto();
});

//restar clientes
$("#rest-client").click(function(){
	if(clients > 1)
	{
		clients -= 1;
		$('#clients').val(clients);
	} 
	showSuplApto();
});
/***** Fin de añadir y disminuir clientes ****/

/**
 * En esta parte gestionaremo todo lo relacionado
 * con le fofirmacion de la reserva y darle el precio 
 * de la misma, para ver todos los ejemplos visitar 
 * la siguiente pagina http://t4t5.github.io/sweetalert/
*/
var noRelleno = "";
var nombre = "";
var email = "";
var telefono = "";
var fechEntrada = "";
var fechSalida = "";
var ocupantes = 0;
var comentarios = "";
var noches = 0;
var precio = 0;
var supLimpieza = 0;
var supLujo = 0;
var supParking = 0;
var precioTotalEstancia = 0;

//var regExpre = new Array();
var regExpre = {'email':/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$/,
				'texto':/^[.,:;0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]*$/,
				'nombre':/^[.a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/,
				'fecha':/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/,
				'telefono':/^[0-9]{9}$/
				}
function confirmReserva()
{
	supLimpieza = getSupleLimpie();
	supParking = getSuplParking();
	supLujo = getSuplLujo();

	if (clients > 5) {
		precioTotalEstancia = parseInt(precio) + parseInt(supLimpieza) + parseInt(supParking) + parseInt(supLujo);
	}else{
		precioTotalEstancia = parseInt(precio) + parseInt(supLimpieza) + parseInt(supParking);
	}
	
	
	var texto = "<div class='text-left' style='margin-top:-10px;margin-left:13%''>";		
		texto += "<p style='margin-bottom:5px'>Revisa los datos de tu reserva y a solicitar !!!</p>";	
		texto += "<p style='margin-bottom:5px'>A nombre de: <strong style='font-size:18px'>"+ nombre +"</strong></p>";
		texto += "<p style='margin-bottom:5px'>Email de contacto: <strong style='font-size:18px'>"+ email +"</strong></p>";
		texto += "<p style='margin-bottom:5px'>Teléfono de contacto: <strong style='font-size:18px'>"+ telefono +"</strong></p>";
		texto += "<p style='margin-bottom:5px'>Entrada / Salida: <strong style='font-size:18px'>"+ fechEntrada +" / "+ fechSalida +"</strong></p>";				
		texto += "<p style='margin-bottom:5px'>Noches: <strong style='font-size:18px'>"+ noches +"</strong></p>";
		texto += "<p style='margin-bottom:5px'>Ocupantes: <strong style='font-size:18px'>"+ ocupantes +"</strong></p>";	
		if(supParking > 0){			
		texto += "<p style='margin-bottom:5px'>Suplemento parking: <strong style='font-size:18px'>"+ supParking +" €</strong></p>";
		}
		if(supLujo > 0 && clients > 5){
			texto += "<p style='margin-bottom:5px'>Suplemento Apto. Lujo: <strong style='font-size:18px'>"+ supLujo +" €</strong></p>";	
		}
		texto += "<p style='margin-bottom:5px'>Precio total de la solicitud de reserva: <strong style='font-size:18px'>"+ precioTotalEstancia +" €</strong></p>";
		texto += "<p style='margin-bottom:5px'>Comentarios:</p>";
		texto += "<p style='margin-bottom:5px'>"+ comentarios +"</p></div>";
	swal({   
		title: "Solicita tu reserva",   
		text: texto,   
		html: true,
		showCancelButton: true,   
		closeOnConfirm: false, 
		closeOnCancel: true,  
		showLoaderOnConfirm: true, 
	}, function(){
		$.ajax({
			method: "GET",
			url: "http://admin.apartamentosierranevada.net/index.php/booking/book/externconnect",
			dataType : 'jsonp',
			data: { 
				nombre: nombre, 
				telefono: telefono, 
				email: email, 
				entrada: fechEntrada, 
				salida: fechSalida, 
				ocupantes: ocupantes, 								
				comentarios: comentarios, 
				noches: noches.toString(),
				parking: supParking.toString(),
				lujo: supLujo.toString(),
				precioTotalEstancia: precioTotalEstancia.toString(),						
			}
		}).done(function(data){
			unsetValues();
			swal("Solicitada!!","Te hemos enviado un email con los datos de tu reserva, en cuanto la confirmemos te lo haremos saber.","success");
		});

	});
}
$('#confirm-reserva').click(function(){	
	if(!setValues())
	{	
		$.ajax({
			method: "GET",
			url: "http://admin.apartamentosierranevada.net/index.php/booking/book/precionoche",
			dataType : 'jsonp',
			data: { 
				ocupantes: '4',
				dateIn : fechEntrada,
				dateOut :  fechSalida,
			}
		}).done(function(data){
			noches = parseInt(data.noches);
			if(noches >= 2)
			{
				$.ajax({
					method: "GET",
					url: "http://admin.apartamentosierranevada.net/index.php/booking/book/precionoche",
					dataType : 'jsonp',
					data: { 
						ocupantes: ocupantes,
						dateIn : fechEntrada,
						dateOut :  fechSalida,
					}
				}).done(function(data){
					precio = data.precio.toString();
					confirmReserva();
				});
				$('#date-entrada').css("border-color", "#e6e6e6");
			}else{
				swal("Upss!! ", "Las fechas estan vacias o tienen menos de dos noches.", "error"); 
				$('#date-entrada').css("border-bottom", "3px solid red");
			}

		});
	} else{
		swal("Upss!! ", "Algunos de los datos estan vacios o mal completados, revisa los que estan en rojo.", "error");
	}
});
function unsetValues(){
	$('#nombre').val('');
	$('#email').val('');
	$('#telefono').val('');
	$('input[name="daterangepicker_start"]').val('');
	$('input[name="daterangepicker_end"]').val('') ;
	$('#clients').val('0');			
	$('#coment').val('');
	$('#date-entrada').val('');
	$('#date-entrada').css("border-bottom", "3px solid #6ab165");
	clients = 0;

}
/**
 * Metodo que da valor a la variables de formulario
 * valids los datos del mismo
 * @return si estan bien validados o no
*/
function setValues(){
	var error = false;
	fechEntrada = $('input[name="daterangepicker_start"]').val();
	fechSalida = $('input[name="daterangepicker_end"]').val();
	nombre = $('#nombre').val() != '' ? $('#nombre').val() : noRelleno;
	email = $('#email').val() != '' ? $('#email').val() : noRelleno;
	telefono = $('#telefono').val() != '' ? $('#telefono').val() : noRelleno;
	ocupantes = $('#clients').val();			
	comentarios = $('#coment').val() != '' ? $('#coment').val() : "Sin comentarios expuestos";

	if(!parseInt($('#clients').val()) > 0){
		error = true;
		$('#clients').css("border-bottom", "3px solid red");
	} else 
		$('#clients').css("border-bottom", "3px solid #6ab165");
	if(!$('#email').val().match(regExpre['email'])){
		error = true;
		$('#email').css("border-bottom", "3px solid red");
	}else 
		$('#email').css("border-bottom", "3px solid #6ab165");

	if(!$('#telefono').val().match(regExpre['telefono'])){
		error = true;
		$('#telefono').css("border-bottom", "3px solid red");
	}else 
		$('#telefono').css("border-bottom", "3px solid #6ab165");

	return error;				
}
/**
 * @return precio por noche segun cantidad de ocupantes
*/
function getPrecio()
{
	console.log("Aqui me llaman");
	$.ajax({
		method: "GET",
		url: "http://admin.apartamentosierranevada.net/index.php/booking/book/precionoche",
		dataType : 'jsonp',
		data: { 
			ocupantes: ocupantes,
			dateIn : fechEntrada,
			dateOut :  fechSalida,
		}
	}).done(function(data){
		precio = data.precio.toString();
	});
}
/**
 * @return precio de suplemento segun ocupantes
*/
function getSupleLimpie()
{
	return parseInt(ocupantes) <= 4 ? 30 : 50;
}

/**
 * Se calcura solo si esta puesto que si
 * @return precio de suplemento parking por noche
*/
function getSuplParking()
{
	 var radioValue = $("input[name='parking']:checked").val();
	 var suple = parseInt(noches) * 15;
	return radioValue == 'true' ? suple : 0;
}

/**
 * Se calcura solo si esta puesto que si
 * @return precio de suplemento de lujo
*/
function getSuplLujo()
{
	 var supLujo = $("input[name='apto-lujo']:checked").val();
	return supLujo == 'true' ? 50 : 0;
}


// Hacer header transparente en mobile
if ($(window).width() < 767) {
   $('#site-header').removeClass('clr header-one fixed-scroll');
   $('#site-header').addClass('overlay-header fix-overlay-header dark-style ');
}
$('#date-salida,#btnCalendarEntrada,#btnCalendarSalida').click(function(){
	$('#date-entrada').focus();
});

function isDateOK(){
	var dateOK = true;
	var dateEntrada = $('input[name="daterangepicker_start"]').val();
	var dateSalida = $('input[name="daterangepicker_end"]').val();
	if(dateEntrada != '' && dateSalida != ''){
		/*dateEntrada = dateEntrada.split('-');
		dateEntrada = new Date(dateEntrada[2],dateEntrada[1],dateEntrada[0]);

		dateSalida = dateSalida.split('-');
		dateSalida = new Date(dateSalida[2],dateSalida[1],dateSalida[0]);

		var one_day = 1000*60*60*24; 
		var daysApart = Math.abs(Math.ceil((dateEntrada.getTime()-dateSalida.getTime())/one_day));*/
		console.log("Aqui me llaman");
		$.ajax({
			method: "GET",
			url: "http://admin.apartamentosierranevada.net/index.php/booking/book/precionoche",
			dataType : 'jsonp',
			data: { 
				ocupantes: '4',
				dateIn : dateEntrada,
				dateOut :  dateSalida,
			}
		}).done(function(data){
			noches = parseInt(data.noches);
		});

		if(parseInt(noches) < 2)
			dateOK = false;

	}else 
		dateOK = false; 	
   	return dateOK; 
} 


//popups con sweetalert
$('.popup-maps').click(function(){
	swal({   
			title: "Donde estamos",   			
			text: '<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12"><iframe width="100%" height="400"src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3182.4959187857507!2d-3.396972!3d37.093311!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd71dd38d505f85f%3A0x4a99a1314ca01a9!2sAlquiler+Apartamento+de+Lujo+Sierra+Nevada+-+Edif+Miramar+Ski!5e0!3m2!1ses!2ses!4v1444143503089"  frameborder="0" style="border:0" allowfullscreen></iframe></div>',   
			type: 'input',
			html: true,
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Cerrar",   
			closeOnConfirm: true 
		});
});

var showSuplApto = function(){
	if(clients >= 6)
		$('#form-apto-lujo').fadeIn(400);
	else
		$('#form-apto-lujo').fadeOut(400);

};