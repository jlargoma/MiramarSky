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

$('div#requests_header input.datepicker_init_start_date').daterangepicker({
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

function instance_extra_datepickers(){
    $('div#requests_container div.request_row input.datepicker_init_start_date').daterangepicker({
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
}

instance_extra_datepickers();

$('.datepicker_init_start_date').val('');
$('.datepicker_init_end_date').val('');
$('.today.active.start-date.active.end-date.available').removeClass('active start-date active end-date');

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
var supParking = 0;
var precioTotalEstancia = 0;

//var regExpre = new Array();
var regExpre = {'email':/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$/,
				'texto':/^[.,:;0-9a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]*$/,
				'nombre':/^[.a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/,
				'fecha':/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/,
				'telefono':/^[0-9]{9}$/
				}
$('#confirm-reserva').click(function(){

        if($('input[name^="carrito"]').length > 0){
            cc_name = $('input[name="cc_name"]');
            cc_pan = $('input[name="cc_pan"]');
            cc_expiry = $('input[name="cc_expiry"]');
            cc_cvc = $('input[name="cc_cvc"]');

            cc_name_value = cc_name.val();
            cc_pan_value = cc_pan.val();
            cc_expiry_value = cc_expiry.val()
            cc_cvc_value = cc_cvc.val();

            if( cc_name_value.length == 0){
                cc_name.attr('style','border: 1px solid red;');
            }else{
                cc_name.removeAttr('style');
            }
            if( cc_pan_value.length == 0){
                cc_pan.attr('style','border: 1px solid red;');
            }else{
                cc_pan.removeAttr('style');
            }
            if( cc_expiry_value.length == 0){
                cc_expiry.attr('style','border: 1px solid red;');
            }else{
                cc_expiry.removeAttr('style');
            }
            if( cc_cvc_value.length == 0){
                cc_cvc.attr('style','border: 1px solid red;');
            }else{
                cc_cvc.removeAttr('style');
            }

            if(!setValues() &&
                cc_name_value.length > 0 &&
                cc_pan_value.length > 0 &&
                cc_expiry_value.length > 0 &&
                cc_cvc_value.length > 0 ){
            
                public_key = '6LdOoYYUAAAAAPKBszrHm6BWXPE8Gfm3ywnoOEUV';
                
                grecaptcha.ready(function() {
                    grecaptcha.execute(public_key, {action: 'launch_form_submit'})
                    .then(function(token) {
                    // Verify the token on the server.

                        var recaptchaResponse = document.getElementById('recaptchaResponse');
                        recaptchaResponse.value = token;
                        
                        $.ajax({
                            type: "POST",
                            url: "/ajax/checkRecaptcha",
                            data: {token:token, public_key:public_key},
                            dataType:'json',
                            success: function(response){
    //                            price = JSON.stringify(response).replace('.',',');
//                                console.log(response.status);
//                                alert(response.status);
                                if(response.status == 'true'){
                                    $('form').submit();
                                }
                            },
                            error: function(response){
            //                    console.log(response);
                            }
                        });
                    });
                });
                
            }
        }

});
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

	if($('#nombre').val() == ''){
		error = true;
		$('#nombre').css("border-bottom", "3px solid red");
	} else 
		$('#nombre').css("border-bottom", "3px solid #6ab165");

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

$(document).on('click','.datepicker_init_end_date,#btnCalendarEntrada,#btnCalendarSalida',function(){
//    console.log($(this).parent('div').parent('div'));
    $(this).parent('div').parent('div').find('.datepicker_init_start_date').focus();
});
