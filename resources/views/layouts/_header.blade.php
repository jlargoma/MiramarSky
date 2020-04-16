
  <div id="top-bar" class="transparent-topbar" style="display: none;";>
          <ul class="header-extras-2 ">
            <li>
              <a class="facebook" href="https://www.facebook.com/alquilerlujosierranevada/?ref=bookmarks" ><i class="fa fa-facebook-official fa-2x">&nbsp;</i></a>
            </li>
            <li>
              <a class="instagram" href="#" ><i class="fa fa-instagram fa-2x"></i>&nbsp;</a>
            </li>
            <li>
              <a class="whatsapp"  href="https://api.whatsapp.com/send?phone=155123456" data-action="share/whatsapp/share" ><i class="fa fa-whatsapp fa-2x">&nbsp;</i></a>
            </li>
            <li>
              <a class="email" href="mailto:reservas@apartamentosierranevada.net" ><i class="fa fa-envelope fa-2x">&nbsp;</i></a>
            </li>

            <li>
              <a class="map" href="https://www.google.com/maps?ll=37.093311,-3.396972&z=17&t=m&hl=es-ES&gl=ES&mapclient=embed&cid=335969053959651753" target="_blank"><i class="fa fa-map-marker fa-2x">&nbsp;</i></a>
            </li>
          </ul>
  </div>
<!-- Header
============================================= -->	
<header id="header" class="static-sticky transparent-header  not-dark " style="display: none;z-index: 100000 !important;">

  <div id="header-wrap">
    <div id="primary-menu-trigger"><i class="fa fa-bars"></i></div>

    <!-- Primary Navigation
    ============================================= -->
    <nav id="primary-menu" class="with-arrows style-2 center">

      <ul>
        <?php if (Request::path() != '/'): ?>
          <li>
            <a  href="{{ url('/') }}"><div style="text-align: center; font-size: 18px;"><i class="fa fa-home fa-2x" style="margin-right: 0;font-size: 20px!important"></i> </div></a></li>
          </li>
        <?php endif ?>
        <li class="mega-menu"><a href="#"><div>Apartamentos</div></a>
          <div class="mega-menu-content style-2 clearfix">
            <ul class="mega-menu-column col-md-6">
              <li class="mega-menu-title">

                <ul>

                  <li>
                    <a class="font-w600" href="{{ url('/apartamentos/apartamento-lujo-sierra-nevada')}}"><div>Apartamento 2 DORM de lujo</div></a>
                  </li>

                  <li>
                    <a class="font-w300" href="{{ url('/apartamentos/apartamento-standard-sierra-nevada')}}"><div>Apartamento 2 DORM Standard</div></a>
                  </li>
                  <li>
                    <a class="font-w600" href="{{ url('/apartamentos/apartamento-lujo-gran-capacidad-sierra-nevada')}}"><div>Apartamento 4 DORM GRAN OCUPACION</div></a>
                  </li>
                </ul>
              </li>
            </ul>
            <ul class="mega-menu-column col-md-6">
              <li class="mega-menu-title">
                <ul>
                  <li>
                    <a class="font-w300" href="{{ url('/apartamentos/estudio-lujo-sierra-nevada')}}"><div>Estudio de lujo</div></a>
                  </li>

                  <li>
                    <a class="font-w300" href="{{ url('/apartamentos/estudio-standard-sierra-nevada')}}"><div>Estudio Standard</div></a>
                  </li>
                  <li>
                    <a class="font-w300" href="{{ url('/apartamentos/chalet-los-pinos-sierra-nevada')}}"><div>Chalet</div></a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </li>
        <!-- <li>
                <a href="{{ url('/reserva') }}"><div>Reserva</div></a></li>
        </li> -->
        <li>
          <a href="{{ route('web.edificio') }}"><div>El Edificio</div></a></li>
        </li>
        <li>
          <a href="/actividades"><div>¿Qué hacer en sierra nevada?</div></a></li>
        </li>

        <li >
          @if(Request::path() == 'contacto')
           <a href="/"><div>Reservar</div></a> 
          @else
          <a class="menu-booking showFormBook" href="#" data-href="#wrapper"><div>Reservar</div></a>
          @endif
        </li>
        <li>
          <a href="{{ url('/contacto') }}"><div>Contacto</div></a></li>
        </li>
      </ul>

    </nav><!-- #primary-menu end -->

  </div>
  <h1 style="display: none;">Alquiler apartamento Sierra Nevada</h1>
  <h2 style="display: none;">Edificio Miramar Ski a pie de pista, zona baja</h2>
  <h3 style="display: none;">Alquiler alojamientos en Sierra Nevada recién reformados</h3>
</header>
