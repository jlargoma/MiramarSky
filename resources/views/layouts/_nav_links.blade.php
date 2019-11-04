<ul class="nav navbar-nav navbar-left">


  <?php if (Auth::user()->role == "propietario"): ?>
    @yield('nav_link')
  <?php endif ?>  
    
  <?php if (Auth::user()->role == "admin" || Auth::user()->role == "subadmin"): ?>
    <li class="{{ Request::path() == 'admin/reservas' ? 'active' : '' }}">
      <a href="{{ url('admin/reservas') }}" class="detailed">Reservas</a>
    </li>
    <li class="{{ Request::path() == 'admin/liquidacion'  ? 'active' : '' }}">
      <a href="{{ url('admin/liquidacion') }}" class="detailed">Liq. por reservas</a>
    </li>

    <li class="{{ Request::path() == 'admin/pagos-propietarios'  ? 'active' : '' }}">
      <a href="{{ url('admin/pagos-propietarios') }}" class="detailed">Pagos a propietarios</a>
    </li>
    <li class="{{ Request::path() == 'admin/contabilidad'  ? 'active' : '' }}">
      <a href="{{ url('admin/contabilidad') }}" class="detailed">Contabilidad</a>
    </li>
  <?php endif ?>
    
  <?php if (Auth::user()->role == "admin"): ?>
    <li class="{{  (preg_match('/\/propietario/i',Request::path()))  ? 'active' : '' }}">
      <a href="{{ url('admin/propietario/8D') }}" class="detailed">Area Propietario</a>
    </li>
    <li class="{{ Request::path() == 'admin/precios' ? 'active' : '' }}">
      <a href="{{ url('admin/precios') }}" class="detailed">Precios y temporadas</a>
    </li>

    <li class="{{ Request::path() == 'admin/usuarios' ? 'active' : '' }}">
      <a href="{{ url('admin/usuarios') }}"  class="detailed">Usuarios</a>
    </li>

    <li class="{{ Request::path() == 'admin/clientes' ? 'active' : '' }}">
      <a href="{{ url('admin/clientes') }}" class="detailed">Clientes</a>
    </li>

    <li class="{{ Request::path() == 'admin/apartamentos' ? 'active' : '' }}">
      <a href="{{ url('admin/apartamentos') }}" class="detailed">Aptos</a>
    </li>

    <li class="{{ Request::path() == 'admin/facturas' ? 'active' : '' }}">
      <a href="{{ url('admin/facturas') }}"  class="detailed">Facturas</a>
    </li>
    @if (config('show_ASN'))
    <li class="{{ Request::path() == 'admin/encuestas' ? 'active' : '' }}">
      <a href="{{ url('admin/encuestas') }}" class="detailed">Encuestas</a>
    </li>
    @endif

    <!--                    <li class="{{ Request::path() == 'admin/supermercado' ? 'active' : '' }}">
                            <a href="#" class="detailed">Super</a>
                        </li>-->
 @if (env('APP_APPLICATION') != "riad")
    <li class="{{ Request::path() == 'admin/forfaits/orders' ? 'active' : '' }}">
      <a href="{{ url('admin/forfaits/orders') }}" class="detailed">Forfaits</a>
    </li>
 @endif
    <li class="{{ Request::path() == 'admin/settings' ? 'active' : '' }}">
      <a href="{{ url('admin/settings') }}" class="detailed">Settings</a>
    </li>
            
    <li class="{{ Request::path() == 'admin/settings_msgs' ? 'active' : '' }}">
        <a href="{{ url('admin/settings_msgs') }}" class="detailed">Txt Email</a>
    </li>
    
    <li class="{{ Request::path() == 'admin/orders-payland' ? 'active' : '' }}">
          <a href="{{ url('admin/orders-payland') }}" class="detailed">PAYLAND</a>
    </li>
<?php endif ?>
    
<?php if (Auth::user()->role == "limpieza"): ?>
    <li class="{{ Request::path() == 'admin/limpieza' ? 'active' : '' }}">
        <a href="{{ url('admin/limpieza') }}" class="detailed">Planning</a>
    </li>
<?php endif ?>
    
<?php if (Auth::user()->role == "admin" || Auth::user()->role == "limpieza" || Auth::user()->role == "subadmin"): ?>
    <li class="{{ Request::path() == 'admin/limpiezas' ? 'active' : '' }}">
        <a href="{{ url('admin/limpiezas/') }}" class="detailed">Limpiezas</a>
    </li>
<?php endif ?>
    
<?php if (Auth::user()->role == "subadmin"): ?>
    <li class="{{ Request::path() == 'admin/orders-payland' ? 'active' : '' }}">
          <a href="{{ url('admin/orders-payland') }}" class="detailed">PAYLAND</a>
    </li>
<?php endif ?>
    <?php if ( (env('APP_APPLICATION') != "riad") && Auth::user()->role == "admin"): ?>
    <li class="{{  (preg_match('/\/contents-home/i',Request::path()))  ? 'active' : '' }}">
      <a href="{{ url('/admin/contents-home') }}" class="detailed">Contenidos Front</a>
    </li>
    <?php endif ?>

</ul>