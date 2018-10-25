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
            /*$('p.sp-layer-'+random_slide).fadeIn(1500);*/
        }, 500);
    }
}

function setSliderAuto() {
    sliderAuto = setInterval(function () {
        $('div#boxgallery span.next').click();
    }, 10000);
}

function boxgalleryLoadTextNextSlider() {
    clearInterval(sliderAuto);
    $('p.sp-layer').hide();

    setTimeout(function () {
        setTimeout(function () {
            if (typeof $('div.panel.active').attr('id') != 'undefined') {
                layer_id = $('div.panel.active').attr('id');
            } else {
                layer_id = $('div.panel.current').attr('id')
            }
            $('p.' + layer_id).fadeIn(1500);
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