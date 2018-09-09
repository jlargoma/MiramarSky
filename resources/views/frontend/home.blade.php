@extends('layouts.master')

@section('title')Apartamentos de lujo en Sierra Nevada a pie de pista @endsection

@section('content')
    <section id="content" style="">

        <div class="content-wrap notoppadding" style="padding-bottom: 0;">

		<?php if (!$mobile->isMobile()): ?>
        <!-- DESKTOP -->

            <div class="row clearfix"
                 style="background-color: #3F51B5; background-image: url({{asset('/img/miramarski/esquiadores.png')}}); background-position: left bottom; background-repeat: no-repeat; background-size: 50%;">
                <div id="close-form-book"
                     style="position: absolute; top: 20px; right: 10px; z-index: 50;  cursor: pointer;">
                    <span class="white text-white"><i class="fa fa-times fa-2x"></i></span>
                </div>
                <div id="content-book" class="container clearfix push-10" style="display: none; min-height: 615px;">
                    <div class="container clearfix" style="padding: 20px 0;">
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="row" id="content-book-response">
                                    <div class="front" style="min-height: 550px;">
                                        <div class="col-xs-12">
                                            <h3 class="text-center white">FORMULARIO DE RESERVA</h3>
                                        </div>
                                        <div id="form-content">
                                            @include('frontend._formBook')
                                        </div>
                                    </div>
                                    <div class="back" style="background-color: #3F51B5; min-height: 550px;">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="clear: both;"></div>
            <section class="row full-screen noborder"
                     style="background-image: url('/img/miramarski/mountain.jpg'); background-color:white; background-size: cover; background-position:50%;">


                <div class="col-xs-12">

                    <div class="col-xs-12 ">

                        <div class="col-lg-6 col-md-7 center fadeInUp animated" data-animation="fadeInUp"
                             style="padding: 40px 15px;">
                            <div class="col-xs-12 push-20">
                                <div class="heading-block  black" style="margin-bottom: 20px">
                                    <h1 class="font-w800 black" style="letter-spacing: 0; font-size: 26px;">APARTAMENTOS
                                        DE LUJO <span class="font-w800 green">A PIE DE PISTA</span></h1>
                                </div>
                                <p class="lead  text-justify black font-s14 black" style="line-height: 1.2">
                                    Todos nuestros Apartamentos están en el Edificio Miramar Ski, situado <b>en la zona
                                        baja de Sierra Nevada</b>. Tienen excelentes vistas y todos disponen del
                                    equipamiento completo.

                                </p>
                                <h2 class="font-w300 text-center black push-18"
                                    style="line-height: 1;font-size: 16px!important; text-transform: uppercase;">
                                    <span class="font-w800 black"> El edificio tiene salida directa a las pistas, solo tienes que salir de casa y esquiar!!</span>
                                </h2>

                                <p class="lead  text-justify black font-s14 black" style="line-height: 1.2">
                                    Se encuentran <b>a 5 minutos andando de la plaza de Andalucía</b>, en Pradollano
                                    centro neurálgico de la estación.<br><br>

                                    <b>Piscina climatizada, gimnasio, parking cubierto, taquilla guardaesquis, acceso
                                        directo a las pistas.</b>

                                    A pocos metros, tienes varios supermercados, bares y restaurantes, lo que es muy
                                    importante para que disfrutes tus vacaciones sin tener que coger el coche ni
                                    remontes<br><br>

                                    Nuestro edificio Miramar Ski es <b>uno de los edificios más modernos de Sierra
                                        Nevada</b> (2006).<br><br>

                                    Tenemos disponibles <b>apartamentos con dos habitaciones</b> ( ocupación 6 / 8 pers)
                                    y también <b>estudios</b> (ocupación 4 / 5 pers).<br><br>

                                    Dentro de estos dos modelos, podrás elegir entre los estándar y de lujo, que están
                                    recién reformados.<br><br>

                                    <b>En todas las reservas las sabanas y toallas están incluidas</b><br><br>

                                    Queremos ofrecerte un servicio especial, por eso incluimos en todas nuestras
                                    reservas un obsequio de bienvenida. <br><br>

                                    <b>Para tu comodidad, te llevamos los fortfaits a tu apartamento para evitarle las
                                        largas filas de la temporada alta</b><br><br>


                                    <b>En el botón de <span class="menu-booking">reserva</span> podrás calcular el coste
                                        de tu petición y si lo consideras hacer tu solicitud de disponibilidad.</b>

                                </p>
                            </div>
                            <div class="col-xs-12 clearfix">

                                <div id="oc-clients" class="owl-carousel image-carousel carousel-widget"
                                     data-margin="60" data-loop="true" data-nav="false" data-autoplay="1000"
                                     data-pagi="false" data-items-xxs="2" data-items-xs="3" data-items-sm="6"
                                     data-items-md="8" data-items-lg="8">

                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="tele-esqui"></li>
                                            A pie de pista
                                        </a>
                                    </div>
                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="parking"></li>
                                            Parking cubierto
                                        </a>
                                    </div>
                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="ascensor"></li>
                                            Ascensor
                                        </a>
                                    </div>
                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="piscina"></li>
                                            Piscina climatizada
                                        </a>
                                    </div>
                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="gimnasio"></li>
                                            gimnasio
                                        </a>
                                    </div>
                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="guarda-esqui"></li>
                                            Guarda Esqíes
                                        </a>
                                    </div>
                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="mascota"></li>
                                            Prohibido mascotas
                                        </a>
                                    </div>
                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="toalla"></li>
                                            Ropa y toallas
                                        </a>
                                    </div>
                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="cocina"></li>
                                            Cocina
                                        </a>
                                    </div>
                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="ducha"></li>
                                            Baño
                                        </a>
                                    </div>
                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="calefaccion"></li>
                                            Calefaccion
                                        </a>
                                    </div>
                                    <div class="oc-item">
                                        <a class="pc-characteristics">
                                            <li id="shopping"></li>
                                            Shopping
                                        </a>
                                    </div>

                                </div>


                            </div>
                        </div>

                        <div class="col-lg-6 col-md-5 center  hidden-sm hidden-xs" style="padding: 40px 15px;">

                            <div class="fslider" data-easing="easeInQuad">
                                <div class="flexslider">
                                    <div class="slider-wrap">

										<?php foreach ($slidesEdificio as $key => $slide): ?>
										<?php $fotos = explode(",", $slide->getFilename()) ?>
                                        <div class="slide"
                                             data-thumb="{{ asset('/img/miramarski/edificio/piscina_climatizadalquiler_apartamento_sierra_nevada_miraramarski.jpg') }}">
                                            <a href="#">
                                                <img src="{{ asset('/img/miramarski/edificio/')}}/<?php echo $slide->getFilename() ?>"
                                                     alt="apartamento sierra nevada <?php echo $key + 1?>"
                                                     title="apartamentosierra nevada <?php echo $key + 1?>"
                                                     style="height: 450px;">
                                                <div class="flex-caption slider-caption-bg">Fotos del edificio</div>
                                            </a>
                                        </div>
										<?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <section class="page-section">
                <div class="row push-30" style="margin-top: 20px;">
                    <h2 class="text-center black font-w300">
                        GALERÍA DE <span class="font-w800 green ">APARTAMENTOS</span>
                    </h2>
                    <div class="col-md-12 col-xs-12">

                        <div class="col-md-2 col-xs-12 push-mobile-20 hover-effect">
                            <a href="{{url('/apartamentos/apartamento-lujo-gran-capacidad-sierra-nevada')}}">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">
                                        <img class="img-responsive imga"
                                             src="{{ asset('/img/miramarski/small/apartamento-lujo-gran-capacidad-sierra-nevada.jpeg')}}"
                                             alt="Apartamento de lujo sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal"
                                            style="padding:55px 10px;width: 90%;">APARTAMENTO<br>GRAN CAPACIDAD DE LUJO
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-xs-12 push-mobile-20 hover-effect">
                            <a href="{{url('/apartamentos/apartamento-lujo-sierra-nevada')}}">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">
                                        <img class="img-responsive imga"
                                             src="{{ asset('/img/miramarski/small/apartamento-lujo-sierra-nevada.jpg')}}"
                                             alt="Apartamento de lujo sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal"
                                            style="padding: 55px 10px;width: 90%;">APARTAMENTO<br>2 DORMITORIOS DE LUJO
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-2 col-xs-12 push-mobile-20 hover-effect">
                            <a href="{{url('/apartamentos/apartamento-standard-sierra-nevada')}}">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">
                                        <img class="img-responsive imga"
                                             src="{{ asset('/img/miramarski/small/apartamento-standard-sierra-nevada.jpg')}}"
                                             alt="Apartamento standard sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal"
                                            style="padding: 55px 10px;width: 90%;">
                                            APARTAMENTO<br>2 DORMITORIOS STANDARD
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-2 col-xs-12 push-mobile-20 hover-effect">
                            <a href="{{url('/apartamentos/estudio-lujo-sierra-nevada')}}">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">

                                        <img class="img-responsive"
                                             src="{{ asset('/img/miramarski/small/estudio-lujo-sierra-nevada.jpg')}}"
                                             alt="Estudio de lujo sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal"
                                            style="padding:65px 70px;">
                                            ESTUDIO DE LUJO
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>


                        <div class="col-md-2 col-xs-12 push-mobile-20 hover-effect">
                            <a href="{{url('/apartamentos/estudio-standard-sierra-nevada')}}">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">

                                        <img class="img-responsive"
                                             src="{{ asset('/img/miramarski/small/estudio-standard-sierra-nevada.jpg')}}"
                                             alt="Estudio standard sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal"
                                            style="padding:65px 70px;">
                                            ESTUDIO STANDARD
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-2 col-xs-12 push-mobile-20 hover-effect">
                            <a href="{{url('/apartamentos/chalet-los-pinos-sierra-nevada')}}">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">

                                        <img class="img-responsive"
                                             src="{{ asset('/img/miramarski/galerias/chalet-los-pinos-sierra-nevada/chalet-pinos-(1).jpg')}}"
                                             alt="Estudio standard sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal"
                                            style="padding:65px 70px;">
                                            CHALET ADOSADO
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-md-10 col-md-offset-1  push-20" style="margin-top: 20px;">
                    <h2 class="text-center black font-w300">
                        OTROS <span class="font-w800 green ">SERVICIOS</span>
                    </h2>
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">
                            <a href="{{ url('/restaurantes')}}">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">
                                        <img class="img-responsive imga"
                                             src="{{ asset('/img/posts/restaurante-sierra-nevada.jpg')}}"
                                             alt="Apartamento standard sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w200 center push-10 text-center text font-s24 white">
                                            <span class="font-w800 white"> BARES Y  RESTAURANTES</span>
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">
                            <a href="//miramarski.com/forfait">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">
                                        <img class="img-responsive imga"
                                             src="{{ asset('/img/miramarski/fortfait.jpg')}}"
                                             alt="Apartamento standard sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w200 center push-10 text-center text font-s24 white">
                                            <span class="font-w800 white" style="letter-spacing: -2px;">SOLICITA<br>CLASES DE SKI, ALQUILER MATERIAL Y EN FORFAITS</span>
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">
                            <a href="{{ url('/actividades')}}">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">

                                        <img class="img-responsive"
                                             src="{{ asset('/img/miramarski/ski-con-niños.jpg')}}"
                                             alt="Estudio de lujo sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w200 center push-10 text-center text font-s24 white">
                                            ¿QUÉ HACER EN SIERRA NEVADA?
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">

                            <a href="http://miramarski.com/supermercado">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">
                                        <img class="img-responsive imga"
                                             src="{{ asset('/img/miramarski/supermercado.jpg')}}"
                                             alt="Apartamento de lujo sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w200 center push-10 text-center text font-s24 white">TE LLEVAMOS
                                            <span class="font-w800 white">LA COMPRA A CASA</span>
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>


                    </div>
                </div>

            </section>
            <div style="clear: both;"></div>
            <section class="page-section" style="margin-top: 60px;">
                <div class="container container-mobile clearfix">
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="text-justify black font-s14 font-w300" style="line-height: 1.3">
                                <span class="font-w600 font-s16">Sabemos lo importante que son tus vacaciones de esqui, por eso cuidamos cada detalle al máximo, intentando siempre conseguir que tu estancia sea lo más agradable posible.</span>
                                <br><br>
                                <b>En nuestra web encontraras una oferta de calidad</b>, en Sierra Nevada la oferta de
                                alojamiento es muy dispar puediendo encontrarse apartamentos muy viejos y en mal estado,<b>
                                    no es nuestro caso, <u>todos nuestros apartamentos se revisan y actualizan cada
                                        temporada</u></b>
                                <br><br>
                                Nuestros apartamentos ( de dos dormitorios y estudios) están ubicados en la mejor zona,
                                Tu experiencia será de máximo confort:<br> <b> Piscina climatizada, gimnasio, ascensor ,
                                    taquilla guarda esquíes, Parking cubierto, ropa de cama incluida</b>.
                                <br><br>
                                <b>A pie de pista... Sin coger remontes. El acceso a las pistas es directo, todo pensado
                                    para que disfrutes.</b>
                                <br><br>
                                Tambien <b>te ofrecemos nuestra gestión para la compra de Forfaits y descuentos en
                                    alquiler de material y packs combinados con cursillos de Ski. <a
                                            href="http://miramarski.com/forfait" target="_blank">Pincha aquí. </a></b>
                                <br><br>
                                Si quieres saber más información sobre la estación de Sierra Nevada <b><a
                                            href="{{url('/actividades')}}">consulta nuestro blog</a></b>, encontraras
                                información sobre la estación, cosas que hacer,actividades de esqui y de a preski, que
                                visitar, como divertirte con los niños, bares y restaurantes de la estación y de
                                Padrollano….etc
                            </p>
                        </div>
                    </div>
                </div>

            </section>
            <!-- END DESKTOP -->

		<?php else: ?>
        <!-- MOBILE -->
            <section class="page-section degradado-background1"
                     style="letter-spacing: 0;line-height: 1;color: #fff!important;">

                <div class="row degradado-background1" style="">

                    <div id="content-book" class="container-mobile clearfix push-10" style="display: none; ">
                        <div id="close-form-book"
                             style="position: absolute; top: 20px; right: 10px; z-index: 50;  cursor: pointer;">
                            <span class="white text-white"><i class="fa fa-times fa-2x"></i></span>
                        </div>
                        <div class="container clearfix" style="padding: 20px 0;">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3 not-padding">
                                    <div class="row" id="content-book-response">
                                        <div class="front" style="min-height: 600px!important;">
                                            <div class="col-xs-12">
                                                <h3 class="text-center white">FORMULARIO DE RESERVA</h3>
                                            </div>
                                            <div id="form-content">
                                                @include('frontend._formBook')
                                            </div>
                                        </div>
                                        <div class="back degradado-background1" style="min-height: 600px!important;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            <section id="desc-section" class="page-section" style="letter-spacing: 0;line-height: 1; ">
                <div class="col-xs-12" style="padding: 30px 0 0;">
                    <div class="col-xs-12 black" style="margin-bottom: 20px">
                        <h1 class="font-w800 black center " style="letter-spacing: 0;
   						margin-bottom: 10px; line-height: 1;font-size: 28px; letter-spacing: -2px;">
                            APARTAMENTOS DE LUJO<br>A PIE DE PISTA
                        </h1>
                        <h3 class="text-black black push-0" data-animate="fadeInUp" data-delay="400" style="text-align:center;font-size: 24px;letter-spacing: -2px;line-height: 1;
   						">SERVICIO EXCLUSIVO</h3>

                        <h4 class="text-black black font-w300 push-40" data-animate="fadeInUp" data-delay="600"
                            style="text-align:center;font-size: 22px;line-height: 1;letter-spacing: -1px;">Piscina,
                            gimnasio, parking, guarda esquis, salida directa a las pistas</h4>

                        <p class="lead  text-justify black ls-15 font-s13 black">
                            Todos nuestros Apartamentos están en el Edificio Miramar Ski, situado <b>en la zona baja de
                                Sierra Nevada</b>. <br>Tienen excelentes vistas y todos disponen del equipamiento
                            completo.
                        </p>
                        <h2 class="font-w300 text-center nobottommargin black push-20"
                            style="line-height: 1;font-size: 16px!important; text-transform: uppercase; ">
                            <span class="font-w800 black">Edificio con salida directa a las pistas</span><br>
                            <span class="font-s14 black">solo tienes que salir de casa y esquiar!!</span>
                        </h2>

                        <div class="col-xs-12 push-20">
                            <div class="row clearfix push-0">

                                <div class="col-xs-12" data-lightbox="gallery" style="padding: 0 5px;">
                                    <div class="col-xs-6 not-padding">
                                        <a href="/img/miramarski/edificio/1.jpg ?>"
                                           data-lightbox="gallery-item">
                                            <img class="image_fade" src="/img/miramarski/edificio/1.jpg" alt="Apartamento sierra nevada" title="Apartamento sierra nevada" style="min-height: 150px;">
                                        </a>
                                    </div>
                                    <div class="col-xs-6 not-padding">
                                        <a href="/img/miramarski/edificio/2.jpg ?>"
                                           data-lightbox="gallery-item">
                                            <img class="image_fade" src="/img/miramarski/edificio/2.jpg" alt="Apartamento sierra nevada" title="Apartamento sierra nevada" style="min-height: 150px;">
                                        </a>
                                    </div>
                                    <div class="col-xs-6 not-padding">
                                        <a href="/img/miramarski/edificio/3.jpg ?>"
                                           data-lightbox="gallery-item">
                                            <img class="image_fade" src="/img/miramarski/edificio/3.jpg" alt="Apartamento sierra nevada" title="Apartamento sierra nevada" style="min-height: 150px;">
                                        </a>
                                    </div>
                                    <div class="col-xs-6 not-padding">
                                        <a href="/img/miramarski/edificio/4.jpg ?>"
                                           data-lightbox="gallery-item">
                                            <img class="image_fade" src="/img/miramarski/edificio/4.jpg" alt="Apartamento sierra nevada" title="Apartamento sierra nevada" style="min-height: 150px;">
                                        </a>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="col-xs-12 clearfix push-20">

                        <div id="oc-clients" class="owl-carousel image-carousel carousel-widget" data-margin="60"
                             data-loop="true" data-nav="false" data-autoplay="3000" data-pagi="false" data-items-xxs="4"
                             data-items-xs="4" data-items-sm="6" data-items-md="6" data-items-lg="8">

                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/teleesqui.png') }}" alt="piepista"
                                         title="piepista"/> A pie de pista
                                </a>
                            </div>
                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/parking.png') }}" alt="parking"
                                         title="parking"/> Parking cubierto
                                </a>
                            </div>
                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/ascensor.png') }}" alt="ascensor"
                                         title="ascensor"/> Ascensor
                                </a>
                            </div>
                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/piscina.png') }}" alt="piscina"
                                         title="piscina"/> Piscina
                                </a>
                            </div>
                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/gimnasio.png') }}" alt="gimnasio"
                                         title="gimnasio"/> Gimnasio
                                </a>
                            </div>
                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/guardaesqui.png') }}"
                                         alt="guardaesqui" title="guardaesqui"/> Guarda Esqíes
                                </a>
                            </div>
                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/mascotas.png') }}" alt="mascotas"
                                         title="mascotas"/> No mascotas
                                </a>
                            </div>
                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/ropa-toallas.png') }}"
                                         alt="ropa-toallas" title="ropa-toallas"/> Ropa y toallas
                                </a>
                            </div>
                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/cocina.png') }}" alt="cocina"
                                         title="cocina"/> Cocina
                                </a>
                            </div>
                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/ducha.png') }}" alt="ducha"
                                         title="ducha"/> Baño
                                </a>
                            </div>
                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/calefaccion.png') }}"
                                         alt="calefaccion" title="calefaccion"/> Calefaccion
                                </a>
                            </div>
                            <div class="oc-item">
                                <a class="pc-characteristics black center font-s12">
                                    <img src="{{ asset('/img/miramarski/iconos/small/shopping.png') }}" alt="shopping"
                                         title="shopping"/> Shopping
                                </a>
                            </div>

                        </div>


                    </div>

                    <div class="col-xs-12">
                        <p class="lead text-justify font-s13 black ls-5">
                            Se encuentran <b>a 5 minutos andando de la plaza de Andalucía</b>, en Pradollano centro
                            neurálgico de la estación.<br><br>

                            <b>Piscina climatizada, gimnasio, parking cubierto, taquilla guardaesquis, acceso directo a
                                las pistas.</b>

                            A pocos metros, tienes varios supermercados, bares y restaurantes, lo que es muy importante
                            para que disfrutes tus vacaciones sin tener que coger el coche ni remontes<br><br>

                            Nuestro edificio Miramar Ski es <b>uno de los edificios más modernos de Sierra Nevada</b>
                            (2006).<br><br>

                            Tenemos disponibles <b>apartamentos con dos habitaciones</b> ( ocupación 6 / 8 pers) y
                            también <b>estudios</b> (ocupación 4 / 5 pers).<br><br>

                            Dentro de estos dos modelos, podrás elegir entre los estándar y de lujo, que están recién
                            reformados.<br><br>

                            <b>En todas las reservas las sabanas y toallas están incluidas</b><br><br>

                            Queremos ofrecerte un servicio especial, por eso incluimos en todas nuestras reservas un
                            obsequio de bienvenida. <br><br>

                            <b>Para tu comodidad, te llevamos los fortfaits a tu apartamento para evitarle las largas
                                filas de la temporada alta</b>
                        </p>
                    </div>

                    <div class="col-xs-12 push-0">
                        <p class="lead  text-justify ls-15 font-s13">
                            En el <span class="menu-booking"><b><u>botón de reserva</u></b></span> podrás calcular el
                            coste de tu petición y si lo consideras hacer tu solicitud de disponibilidad.
                        </p>
                    </div>

                </div>

            </section>

            <div style="clear: both;"></div>

            <section class="page-section" style="letter-spacing: 0;line-height: 1; margin: 45px 0;">
                <div class="heading-block center push-20">
                    <h3 class="green">NUESTROS APARTAMENTOS</h3>
                </div>

                <div class=" row animatable" data-aos="zoom-in">
                    <a href="{{url('/apartamentos/apartamento-lujo-gran-capacidad-sierra-nevada')}}">
                        <div class="section parallax noborder center"
                             style="background-image: url({{ asset('/img/miramarski/small/apartamento-lujo-gran-capacidad-sierra-nevada.jpeg')}}); padding: 70px 0; margin: 20px 0;"
                             data-stellar-background-ratio="0.4">
                            <h3 class="h2 text-center white text-white font-w800 wst"
                                style="text-shadow: 2px 1px #000;">APTO GRAN <br>CAPACIDAD DE LUJO</h3>
                        </div>
                    </a>
                </div>

                <div class=" row animatable" data-aos="zoom-in">
                    <a href="{{url('/apartamentos/apartamento-lujo-sierra-nevada')}}">
                        <div class="section parallax noborder center"
                             style="background-image: url({{ asset('/img/miramarski/galerias/apto-lujo.jpg') }}); padding: 70px 0; margin: 20px 0;"
                             data-stellar-background-ratio="0.4">
                            <h3 class="h2 text-center white text-white font-w800 wst"
                                style="text-shadow: 2px 1px #000;">2 DORMITORIOS<br> DE LUJO</h3>
                        </div>
                    </a>
                </div>
                <div class=" row animatable" data-aos="zoom-in">
                    <a href="{{url('/apartamentos/apartamento-standard-sierra-nevada')}}">
                        <div class="section parallax noborder center"
                             style="background-image: url({{ asset('/img/miramarski/galerias/apto-standard.jpg') }}); padding: 70px 0; margin: 20px 0;"
                             data-stellar-background-ratio="0.4">
                            <h3 class="h2 text-center white text-white font-w800 wst" style="text-shadow: 2px 1px
							#000; line-height: 1;">2 DORMITORIOS
                                <br>STANDARD</h3>
                        </div>
                    </a>
                </div>
                <div class=" row animatable" data-aos="zoom-in">
                    <a href="{{url('/apartamentos/estudio-lujo-sierra-nevada')}}">
                        <div class="section parallax noborder center"
                             style="background-image: url({{ asset('/img/miramarski/galerias/estudio-lujo.jpg') }}); padding: 70px 0; margin: 20px 0;"
                             data-stellar-background-ratio="0.4">
                            <h3 class="h2 text-center white text-white font-w800 wst"
                                style="text-shadow: 2px 1px #000;">ESTUDIO DE LUJO</h3>
                        </div>
                    </a>
                </div>
                <div class=" row animatable" data-aos="zoom-in">
                    <a href="{{url('/apartamentos/estudio-standard-sierra-nevada')}}">
                        <div class="section parallax noborder center"
                             style="background-image: url({{ asset('/img/miramarski/galerias/estudio-standard.jpg') }}); padding: 70px 0; margin: 20px 0;"
                             data-stellar-background-ratio="0.4">
                            <h3 class="h2 text-center white text-white font-w800 wst"
                                style="text-shadow: 2px 1px #000;">ESTUDIO STANDARD</h3>
                        </div>
                    </a>
                </div>
            </section>

            <section class="page-section" style="letter-spacing: 0;line-height: 1; margin: 45px 0;">

                <div class="row">
                    <h2 class="text-center black font-w300">
                        OTROS <span class="font-w800 green ">SERVICIOS</span>
                    </h2>
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">
                            <a href="{{ url('/restaurantes')}}">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">
                                        <img class="img-responsive imga"
                                             src="{{ asset('/img/posts/restaurante-sierra-nevada.jpg')}}"
                                             alt="Apartamento standard sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w200 center push-10 text-center text font-s24 white">
                                            <span class="font-w800 white"> BARES Y  RESTAURANTES</span>
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">
                            <a href="http://miramarski.com/forfait" target="_blank">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">
                                        <img class="img-responsive imga"
                                             src="{{ asset('/img/miramarski/descuento-forfait.jpg')}}"
                                             alt="Apartamento de lujo sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w200 center push-10 text-center text font-s24 white">
                                            <span class="font-w800 white" style="letter-spacing: -2px;">SOLICITA<br>CLASES DE SKI, ALQUILER MATERIAL Y EN FORFAITS</span>
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">
                            <a href="{{ url('/actividades')}}">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">

                                        <img class="img-responsive"
                                             src="{{ asset('/img/miramarski/ski-con-niños.jpg')}}"
                                             alt="Estudio de lujo sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w200 center push-10 text-center text font-s24 white">
                                            <span class="font-w800 white" style="letter-spacing: -2px;">¿QUÉ HACER EN SIERRA NEVADA?</span>
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-xs-12 push-mobile-20 hover-effect">
                            <a href="http://miramarski.com/supermercado">
                                <div class="col-xs-12 not-padding  container-image-box">
                                    <div class="col-xs-12 not-padding push-0">
                                        <img class="img-responsive imga"
                                             src="{{ asset('/img/miramarski/supermercado.jpg')}}"
                                             alt="Apartamento standard sierra nevada"/>
                                    </div>
                                    <div class="col-xs-12 not-padding text-right overlay-text">
                                        <h2 class="font-w200 center push-10 text-center text font-s24 white">
                                            TE LLEVAMOS LA <span class="font-w800 white"> COMPRA A CASA</span>
                                        </h2>
                                    </div>
                                </div>
                            </a>
                        </div>


                    </div>
                </div>

            </section>

            <section class="page-section" style="margin-top: 60px;">
                <div class="col-xs-12">
                    <p class="text-justify black font-s14 font-w300" style="line-height: 1.3">
                        <span class="font-w600 font-s16">Sabemos lo importante que son tus vacaciones de esqui, por eso cuidamos cada detalle al máximo, intentando siempre conseguir que tu estancia sea lo más agradable posible.</span>
                        <br><br>
                        <b>En nuestra web encontraras una oferta de calidad</b>, en Sierra Nevada la oferta de
                        alojamiento es muy dispar puediendo encontrarse apartamentos muy viejos y en mal estado,<b> no
                            es nuestro caso, <u>todos nuestros apartamentos se revisan y actualizan cada
                                temporada</u></b>
                        <br><br>
                        Nuestros apartamentos ( de dos dormitorios y estudios) están ubicados en la mejor zona, Tu
                        experiencia será de máximo confort:<br> <b> Piscina climatizada, gimnasio, ascensor , taquilla
                            guarda esquíes, Parking cubierto, ropa de cama incluida</b>.
                        <br><br>
                        <b>A pie de pista... Sin coger remontes. El acceso a las pistas es directo, todo pensado para
                            que disfrutes.</b>
                        <br><br>
                        Tambien <b>te ofrecemos nuestra gestión para la compra de Forfaits y descuentos en alquiler de
                            material y packs combinados con cursillos de Ski. <a href="http://miramarski.com/forfait"
                                                                                 target="_blank">Pincha aquí. </a></b>
                        <br><br>
                        Si quieres saber más información sobre la estación de Sierra Nevada <b><a
                                    href="{{url('/actividades')}}">consulta nuestro blog</a></b>, encontraras
                        información sobre la estación, cosas que hacer,actividades de esqui y de a preski, que visitar,
                        como divertirte con los niños, bares y restaurantes de la estación y de Padrollano….etc
                    </p>
                </div>

            </section>


            <!-- END MOBILE -->
			<?php endif; ?>


        </div>

    </section>
@endsection

