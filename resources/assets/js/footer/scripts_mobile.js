/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {


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
  function showLoad() {
    $('#loading-book').show();
  }

  function hideLoad() {
    $('#loading-book').hide();
  }

  $('#form-book-apto-lujo').submit(function (event) {
    $('#content-book-response .back').empty();
    $('#content-book-payland').empty();
    event.preventDefault();

    var _token = $('input[name="_token"]').val();
    var name = $('input[name="name"]').val();
    var email = $('input[name="email"]').val();
    var phone = $('input[name="telefono"]').val();
    var date = $('input[name="date"]').val();
    var quantity = $('select[name="quantity"]').val();
    var apto = $('input:radio[name="apto"]:checked').val();
    var luxury = $('input:radio[name="luxury"]:checked').val();
    var parking = 'si';
    var comment = $('textarea[name="comment"]').val();

    var url = $(this).attr('action');

    var dateAux = date;

    var arrayDates = dateAux.split('-');
    var res1 = arrayDates[0].replace("Abr", "Apr");
    var date1 = new Date(res1);
    var start = date1.getTime();

    var res2 = arrayDates[1].replace("Abr", "Apr");
    var date2 = new Date(res2);
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());


    $.post('/getDiffIndays', {date1: arrayDates[0], date2: arrayDates[1]}, function (data) {
      var diffDays = data.diff;
      var minDays = data.minDays;
      if (diffDays >= 2) {
        if (data.specialSegment != false && minDays > diffDays) {
          $('#content-book-response .back').empty();
          alert('ESTANCIA MÍNIMA EN ESTAS FECHAS:' + minDays + ' DÍAS');
        } else {
          $.post(url, {
            _token: _token,
            name: name,
            email: email,
            phone: phone,
            fechas: data.dates,
            quantity: quantity,
            apto: apto,
            luxury: luxury,
            parking: parking,
            comment: comment
          }, function (data) {

            $('#content-book-response .back').empty();
            $('#content-book-response .back').append(data);
//                $("#content-book-response").flip(true);

            if ($(document).width() < 991) {
              var scrollTopData = $("#content-book-response").offset().top;
              $('html, body').animate({
                /*scrollTop: $("section#content").offset().top*/
                scrollTop: scrollTopData
              }, 2000);
            }
            if (data.specialSegment != false) {
              $('.content-alert-min-special-days').append('<h2 class="text-center text-white white" ' +
                      'style="line-height: 1; letter-spacing: -1px;">ESTANCIA MÍNIMA EN ' +
                      'ESTAS ' +
                      'FECHAS: ' + minDays + ' DÍAS</h2>');
            }
          });
        }
      } else {
        alert('Estancia minima ' + minDays + ' NOCHES')
      }



    });


  });

  $('#banner-offert, .menu-booking').click(function (event) {
    $('#content-book').show('400');
    $('#banner-offert').hide();
    $('#line-banner-offert').hide();
    $('#desc-section').hide();
    $('section#content').css('z-index', '20000');
    if ($(document).width() > 991)
      var scrollTopData = $("#content-book").offset().top - 80;
    else
      var scrollTopData = $("#content-book").offset().top - 10;

    $('html, body').animate({
      /*scrollTop: $("section#content").offset().top*/
      scrollTop: scrollTopData
    }, 2000);
  });

  $('#close-form-book').click(function (event) {
    $('#banner-offert').show();
    $('#line-banner-offert').show();
    $('#content-book').hide('100');
    $('#desc-section').show();
    $('section#content').css('z-index', '0');

    $('html, body').animate({
      scrollTop: $("body").offset().top
    }, 2000);
  });



});