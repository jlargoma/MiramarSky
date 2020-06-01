<ul class="nav navbar-nav navbar-left">

  <?php 
  $uRole = Auth::user()->role;
  $pathRequest = Request::path(); 
  ?>
  <?php if ($uRole == "propietario"): ?>
    @yield('nav_link')
  <?php endif ?>  
  <?php if ($uRole == "recepcionista"): ?>
    <li class="{{ $pathRequest == 'admin/reservas' ? 'active' : '' }}">
      <a href="{{ url('admin/reservas') }}" class="detailed">Reservas</a>
    </li>
    <li class="{{ $pathRequest == 'admin/liquidacion'  ? 'active' : '' }}">
      <a href="{{ url('admin/liquidacion') }}" class="detailed">Liq. por reservas</a>
    </li>
     <li class="{{ $pathRequest == 'admin/orders-payland' ? 'active' : '' }}">
          <a href="{{ url('admin/orders-payland') }}" class="detailed">PAYLAND</a>
    </li>
        
    <li class="{{ $pathRequest == 'admin/settings' ? 'active' : '' }}">
      <a href="{{ url('admin/settings') }}" class="detailed">Settings</a>
    </li>
            
    <li class="{{ $pathRequest == 'admin/settings_msgs' ? 'active' : '' }}">
        <a href="{{ url('admin/settings_msgs') }}" class="detailed">Txt Email</a>
    </li>
   
  <?php endif ?>  
    
  <?php if ($uRole == "admin" || $uRole == "subadmin"): ?>
    <li class="{{ $pathRequest == 'admin/reservas' ? 'active' : '' }}">
      <a href="{{ url('admin/reservas') }}" class="detailed">Reservas</a>
    </li>
    <li class="{{ $pathRequest == 'admin/liquidacion'  ? 'active' : '' }}">
      <a href="{{ url('admin/liquidacion') }}" class="detailed">Liq. por reservas</a>
    </li>

    <li class="{{ $pathRequest == 'admin/pagos-propietarios'  ? 'active' : '' }}">
      <a href="{{ url('admin/pagos-propietarios') }}" class="detailed">Pagos a propietarios</a>
    </li>
    <li class="{{ $pathRequest == 'admin/contabilidad'  ? 'active' : '' }}">
      <a href="{{ url('admin/contabilidad') }}" class="detailed">Contabilidad</a>
    </li>
  <?php endif ?>
    
  <?php if ($uRole == "admin"): ?>
    <li class="{{  (preg_match('/\/propietario/i',$pathRequest))  ? 'active' : '' }}">
      <a href="{{ url('admin/propietario/8D') }}" class="detailed">Area Propietario</a>
    </li>
    <li class="{{ $pathRequest == 'admin/usuarios' ? 'active' : '' }}">
      <a href="{{ url('admin/usuarios') }}"  class="detailed">Usuarios</a>
    </li>

    <li class="{{ $pathRequest == 'admin/clientes' ? 'active' : '' }}">
      <a href="{{ url('admin/clientes') }}" class="detailed">Clientes</a>
    </li>

    <li class="{{ $pathRequest == 'admin/apartamentos' ? 'active' : '' }}">
      <a href="{{ url('admin/apartamentos') }}" class="detailed">Aptos</a>
    </li>

    <li class="{{ $pathRequest == 'admin/facturas' ? 'active' : '' }}">
      <a href="{{ url('admin/facturas') }}"  class="detailed">Facturas</a>
    </li>
    @if (config('show_ASN'))
    <li class="{{ $pathRequest == 'admin/encuestas' ? 'active' : '' }}">
      <a href="{{ url('admin/encuestas') }}" class="detailed">Encuestas</a>
    </li>
    @endif

    <!--                    <li class="{{ $pathRequest == 'admin/supermercado' ? 'active' : '' }}">
                            <a href="#" class="detailed">Super</a>
                        </li>-->
 @if (env('APP_APPLICATION') != "riad")
    <li class="{{ $pathRequest == 'admin/forfaits/orders' ? 'active' : '' }}">
      <a href="{{ url('admin/forfaits/orders') }}" class="detailed">Forfaits</a>
    </li>
 @endif
    <li class="{{ $pathRequest == 'admin/settings' ? 'active' : '' }}">
      <a href="{{ url('admin/settings') }}" class="detailed">Settings</a>
    </li>
            
    <li class="{{ $pathRequest == 'admin/settings_msgs' ? 'active' : '' }}">
        <a href="{{ url('admin/settings_msgs') }}" class="detailed">Txt Email</a>
    </li>
    
    <li class="{{ $pathRequest == 'admin/orders-payland' ? 'active' : '' }}">
          <a href="{{ url('admin/orders-payland') }}" class="detailed">PAYLAND</a>
    </li>
<?php endif ?>
    
<?php if ($uRole == "limpieza"): ?>
    <li class="{{ $pathRequest == 'admin/limpieza' ? 'active' : '' }}">
        <a href="{{ url('admin/limpieza') }}" class="detailed">Planning</a>
    </li>
<?php endif ?>
    
<?php if ($uRole == "admin" || $uRole == "limpieza" || $uRole == "subadmin" || $uRole == "recepcionista"): ?>
    <li class="{{ $pathRequest == 'admin/limpiezas' ? 'active' : '' }}">
        <a href="{{ url('admin/limpiezas/') }}" class="detailed">Limpiezas</a>
    </li>
<?php endif ?>
    
<?php if ($uRole == "subadmin"): ?>
    @if (env('APP_APPLICATION') != "riad")
        <li class="{{ $pathRequest == 'admin/forfaits/orders' ? 'active' : '' }}">
          <a href="{{ url('admin/forfaits/orders') }}" class="detailed">Forfaits</a>
        </li>
     @endif
    <li class="{{ $pathRequest == 'admin/orders-payland' ? 'active' : '' }}">
          <a href="{{ url('admin/orders-payland') }}" class="detailed">PAYLAND</a>
    </li>
<?php endif ?>
    <?php if ( (env('APP_APPLICATION') != "riad") && $uRole == "admin"): ?>
    <li class="{{  (preg_match('/\/contents-home/i',$pathRequest))  ? 'active' : '' }}">
      <a href="{{ url('/admin/contents-home') }}" class="detailed">Contenidos Front</a>
    </li>
    <?php endif ?>

 
    <?php if ($uRole == "admin" || $uRole == "subadmin" || $uRole == "recepcionista"): ?>
    <li class="{{  (preg_match('/\/channel-manager/i',$pathRequest) || $pathRequest == 'admin/precios')  ? 'active' : '' }}">
        <a href="{{ url('/admin/precios') }}" class="detailed">CHANNEL</a>
    </li>
<?php endif ?>
    
<?php if ($uRole == "admin" ): ?>
    <li class="{{  (preg_match('/\/show-INE/i',$pathRequest))  ? 'active' : '' }}">
        <a href="{{ url('/admin/show-INE') }}" class="detailed">Estad. INE</a>
    </li>
<?php endif ?>
    
</ul>