<ul class="nav navbar-nav navbar-left">


  <?php if (Auth::user()->role == "propietario"): ?>
    <li class="{{  (preg_match('/propietario/i',Request::path()))  ? 'active' : '' }}">
      <a href="{{ url('admin/propietario/8D') }}" class="detailed">Area Propietario</a>
    </li>
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

    <li class="{{ Request::path() == 'admin/forfaits' ? 'active' : '' }}">
      <a href="{{ url('admin/forfaits') }}" class="detailed">Forfaits</a>
    </li>

    <li class="{{ Request::path() == 'admin/settings' ? 'active' : '' }}">
      <a href="{{ url('admin/settings') }}" class="detailed">Settings</a>
    </li>
            
    <li class="{{ Request::path() == 'admin/settings_msgs' ? 'active' : '' }}">
        <a href="{{ url('admin/settings_msgs') }}" class="detailed">Txt Email</a>
    </li>
    <li class="{{ Request::path() == 'admin/galleries' ? 'active' : '' }}">
          <a href="{{ url('admin/galleries') }}" class="detailed">Galerías</a>
      </li>
<?php endif ?>
      <?php if (Auth::user()->role == "limpieza"): ?>
    <li class="{{ Request::path() == 'admin/limpieza' ? 'active' : '' }}">
        <a href="{{ url('admin/limpieza') }}" class="detailed">Planning</a>
    </li>
<?php endif ?>
      <?php if (Auth::user()->role == "admin" || Auth::user()->role == "limpieza"): ?>
    <li class="{{ Request::path() == 'admin/limpiezas' ? 'active' : '' }}">
        <a href="{{ url('admin/limpiezas/') }}" class="detailed">Limpiezas</a>
    </li>
<?php endif ?>

</ul>