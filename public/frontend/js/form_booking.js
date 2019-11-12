$(document).ready(function () {
  
  var showFormBook = function (event) {
//    event.preventdefault();
    $('#content-form-book').slideDown('400');
    $('#fixed-book').fadeOut('400');
    var scrollTopData = $("#content-form-book").offset().top - 80;
    
    $('html,body').animate({
      scrollTop: scrollTopData
    },'slow');
  }
  $('#showFormBook').click(function(){showFormBook(event)});
  $('.showFormBook').click(function(){showFormBook(event)});
  $('.btn_reservar').click(function(){showFormBook(event)});


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

  $('.daterange1').change(function (event) {
    var date = $(this).val();

    var arrayDates = date.split('-');

    var date1 = new Date(arrayDates[0]);
    var date2 = new Date(arrayDates[1]);
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
    if (diffDays < 2) {
      $('.min-days').show();
    } else {
      $('.min-days').hide();
    }

  });



 $('input:radio[name="apto"]').click(function (event) {
  //apto-estudio
  //apto-2dorm
  //apto-3dorm
  //apto-chlt
  //apto-estudio
  var id = $(this).attr('id');
  $("#luxury-no").prop("disabled", false).show();
  $("#luxury-yes").prop("disabled", false).show();
  
  if (id == 'apto-3dorm' || id == 'apto-chlt'){
    if (id == 'apto-chlt' || id == 'apto-3dorm'){
      $("#luxury-no").trigger('click');
      $("#luxury-yes").prop("disabled", true).hide();
    }
  }
   
 });

  $('#quantity').change(function (event) {
      var pax = $(this).val();
      
      if (pax >= 1 && pax <= 6) $("#apto-chlt").prop("disabled", false);
        else $("#apto-chlt").prop("disabled", true).hide();

      if (pax >= 1 && pax <= 4) {
        $("#apto-estudio").prop("disabled", false);
        $("#apto-estudio").trigger('click');
        $("#apto-estudio").show();
      }
      if (pax >= 5 && pax <= 8) {
        $("#apto-2dorm").prop("disabled", false);
        $("#apto-2dorm").trigger('click');
        $("#apto-estudio").prop("disabled", true).hide();
      } else if (pax >= 9) {
        $("#apto-3dorm").prop("disabled", false);
        $(".apto-3dorm").trigger('click');

        $("#apto-estudio").prop("disabled", true).hide();
        $("#apto-2dorm").prop("disabled", true).hide();
        $("#apto-chlt").prop("disabled", true).hide();
      }

    });
  
   $('span.close').click(function (event) {
    $('#content-form-book').hide('400');
    unflip2();
    $('html, body').animate({
      scrollTop: $("body").offset().top
    }, 2000);
    $('#fixed-book').fadeIn();
  });
  
   function unflip2() {
      $("#content-book-response").flip(false);
      $('#content-book-response .back').empty();
    }
});
