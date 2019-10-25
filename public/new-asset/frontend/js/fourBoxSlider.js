new BoxesFx(document.getElementById('boxgallery'));

function setRandomSlide() {
    random_slide = 1 + Math.floor(Math.random() * 3);

    if (random_slide == 2) {
        $('div#boxgallery span.next').click();
    } else if (random_slide == 3) {
        $('div#boxgallery span.prev').click();
    } else {
        setTimeout(function () {
            $('p.sp-layer-1').fadeIn(1500);
            $('nav.sp-layer-1').fadeIn(1500);
            /*$('p.sp-layer-'+random_slide).fadeIn(1500);*/
        }, 500);
    }
}

function setSliderAuto() {
    sliderAuto = setInterval(function () {
        $('div#boxgallery span.next').click();
    }, 50000);
}

function boxgalleryLoadTextNextSlider() {
    clearInterval(sliderAuto);
    $('p.sp-layer, nav.sp-layer').hide();

    setTimeout(function () {
        setTimeout(function () {
            if (typeof $('div.panel.active').attr('id') != 'undefined') {
                layer_id = $('div.panel.active').attr('id');
            } else {
                layer_id = $('div.panel.current').attr('id')
            }
            $('p.' + layer_id).fadeIn(1500);
            $('nav.'+layer_id).fadeIn(1500);
        }, 1500);
        setSliderAuto();
    }, 500);
}
;

$('div#boxgallery span.next').on('click', function () {
    layer = $('div.panel.current');
    layer_next_bgr = layer.attr("data-next-url");
    layer.attr("style", layer_next_bgr);

    boxgalleryLoadTextNextSlider();
});

$('div#boxgallery span.prev').on('click', function () {
    layer = $('div.panel.current');
    layer_prev_bgr = layer.attr("data-prev-url");
    layer.attr("style", layer_prev_bgr);

    boxgalleryLoadTextNextSlider();
});

$('div#primary-menu-trigger').click(function(){
    if($('ul.sf-js-enabled').is(':visible')){
        $('header.codrops-header').fadeIn('slow');
    }else{
        $('header.codrops-header').hide();
    }
});

function checkOfferOneNightFree(){

         setTimeout(function(){

            dates = $('input#date').val().split('-');
            
            //$('input[name="daterangepicker_start"]').val()
            //$('input[name="daterangepicker_end"]').val()
               
            booking_startDate = moment(dates[0],'DD MMM, YY');
            booking_endDate = moment(dates[1],'DD MMM, YY');
            booking_days_diff = booking_endDate.diff(booking_startDate, 'days');

            if(booking_days_diff >= 5){
               $('textarea#coment').text('Descuento no aplicado en esta pantalla de solicitud. Te lo mandamos por email en 10 minutos.');
            }else{
               $('textarea#coment').text('');
            }
         
         },500);

}

$('div.range_inputs button.applyBtn, button.menu-booking, a.menu-booking, button#showFormBook, a.menu-booking-apt').click(function(){
         checkOfferOneNightFree();
   });

$(document).ready(function(){
   $('div.range_inputs button.applyBtn, button.menu-booking, a.menu-booking, button#showFormBook, a.menu-booking-apt').click(function(){
         checkOfferOneNightFree();
   });
});