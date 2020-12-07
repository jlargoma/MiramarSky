<?php   
use \App\Classes\Mobile;
$mobile = new Mobile();
$isMobile = $mobile->isMobile();
?>
<div class="row">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
        <i class="pg-close fs-20" style="color: #000!important;"></i>
    </button>
</div>
<div class="col-md-12 not-padding content-last-books">
    <div class="alert alert-info fade in alert-dismissable" style="background-color: #daeffd!important;">
        <table style="width: 100%;"> 
            <tr>
                <td>
                  <h4 class="text-center">Últimas Pagadas 
                    <span style="color: #000;font-weight: 600;">{{moneda($total)}}</span>
                  </h4>
                </td>
                
                <td>
                  <button type="button" class="btn getAll <?= !$type ? 'active' : ''?>">Todos</button>
                  <button type="button" class="btn getLastMonth <?= $type == 'month' ? 'active' : ''?>">Últ mes</button>
                  <button type="button" class="btn getLastWeek <?= $type == 'week'  ? 'active' : ''?>">Últ semana</button>
                </td>
            </tr>
        </table>
        
<div class="table-responsive">
<table class="table table-data table-striped" data-type="pendientes">
    <thead>
    <tr>
        <th style="width: 20%"> Cliente</th>
        <th style="width: 10%; text-align: center;"> Apart</th>
        <th style="width: 20%; text-align: center;">Fechas</th>
        <th style="width: 10%; text-align: center;">PVP</th>
        <th style="width: 10%; text-align: center;">Pagado</th>
        <th style="width: 10%; text-align: center;">A pagar</th>
        <th style="width: 20%; text-align: center;">estado</th>
    </tr>
    </thead>
    <tbody>
            <tbody>
                <?php foreach ($books as $key => $b): 
                  if (!$b) continue;
                  ?>
                    <tr>
                      @if($isMobile)
                        <td class ="text-left static" style="width: 130px;color: black;overflow-x: scroll;    padding: 9px !important; ">  
                      @else
                        <td class="text-left" style="padding-left: 1em !important;">
                      @endif
                           <?php if ( $b['agency']): ?>
                              <img src="<?=$b['agency']?>" class="img-agency" />
                            <?php endif ?>
                            <a class="update-book" data-id="<?=$b['id'] ?>"  title="Editar Reserva"  href="<?=$b['url'] ?>">
                                <?=$b['name'] ?>
                            </a> 
                            
                        </td>
                      @if($isMobile)
                        <td class="text-center first-col" style="padding-left: 130px!important">   
                      @else
                        <td class="text-center" >
                      @endif
                            <?=$b['room'] ?>       
                        </th>
                        <td class="text-center">   
                            <b><?=$b['start'] ?></b> - <b><?=$b['finish'] ?></b>
                        </td>
                        <td class="text-center">
                          <b><?= $b['pvp'] ?></b>
                        </td>
                        <td class="text-center">
                          <?= moneda($b['payment']) ?> <br>
                          <b class="<?= $b['percent']>99 ? 'text-success' : 'text-danger' ?> "><?= $b['percent'] ?>%</b>
                        </td>
                        <td class="text-center <?= $b['percent']>99 ? 'text-success' : 'text-danger' ?> ">
                          <b><?= moneda($b['toPay']) ?></b>
                        </td>
                        <td class="text-center">
                          <b><?= $b['status'] ?></b> 
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div> 
