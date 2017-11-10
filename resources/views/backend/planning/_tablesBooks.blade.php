 <ul class="nav nav-tabs nav-tabs-simple bg-info-light " role="tablist" data-init-reponsive-tabs="collapse">
    <li class="active res" >
        <a href="#tabPendientes" data-toggle="tab" role="tab" class="pendientes">Pendientes 
            <span class="badge font-w800 "><?php echo count($arrayBooks["nuevas"]) ?></span>
        </a>
    </li>
    <li class="bloq">
        <a href="#tabEspeciales" data-toggle="tab" role="tab" class="especiales">Especiales
            <span class="badge font-w800 "><?php echo count($arrayBooks["especiales"]) ?></span>
        </a>
    </li>
    <li class="pag">
        <a href="#tabPagadas" data-toggle="tab" role="tab" class="confirmadas">Confirmadas 
            <span class="badge font-w800 "><?php echo count($arrayBooks["pagadas"]) ?></span>
        </a>
    </li>
</ul>
<div class="tab-content">
    
    @include('backend.planning.listados._pendientes')
    
    @include('backend.planning.listados._especiales')

    @include('backend.planning.listados._pagadas')
    

</div>