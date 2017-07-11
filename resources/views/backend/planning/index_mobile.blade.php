@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
    <link href="/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="/pages/css/pages-icons.css" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="/pages/css/pages.css" rel="stylesheet" type="text/css" />
    <!--[if lte IE 9]>
  <link href="/assets/plugins/codrops-dialogFx/dialog.ie.css" rel="stylesheet" type="text/css" media="screen" />
  <![endif]-->
@endsection
<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>

    <style>
      .table.table-condensed.table-detailed > tbody > tr > td{font-size: 10px!important;margin: 0px!important;padding: 0px!important}
      .table.table-condensed.table-detailed > thead > tr > th{font-size: 12px!important;margin: 0px!important;padding: 0px!important}
      .fc-border-separate>tbody >tr >td{min-width: 15px!important}
      .table.table-condensed.table-detailed > tbody > tr > td:first-child:before{ display: none!important;}
      .bg-white{ margin: 0px!important;padding: 0px!important;border-bottom: 0.5px solid;}
      .reservas{padding: 0px!important;margin-top: 5px;}

      .Reservado{
          background-color: #0DAD9E !important;
          color: black;
      }
      .Reservado.start{
          background: red; /* For browsers that do not support gradients */
          background: -webkit-linear-gradient(left, white, green); /* For Safari 5.1 to 6.0 */
          background: -o-linear-gradient(right, white, green); /* For Opera 11.1 to 12.0 */
          background: -moz-linear-gradient(right, white, green); /* For Firefox 3.6 to 15 */
          background: linear-gradient(to right, white, green); /* Standard syntax */
      }
      .Reservado.end{
          background: red; /* For browsers that do not support gradients */
          background: -webkit-linear-gradient(left, green, white); /* For Safari 5.1 to 6.0 */
          background: -o-linear-gradient(right, green, white); /* For Opera 11.1 to 12.0 */
          background: -moz-linear-gradient(right, green, white); /* For Firefox 3.6 to 15 */
          background: linear-gradient(to right, green, white); /* Standard syntax */
      }
      .Pagada-la-señal{
          background-color: #F77975  !important;
          color: black;
      }
      .Pagada-la-señal.start{
          background: red; /* For browsers that do not support gradients */
          background: -webkit-linear-gradient(left, yellow , red); /* For Safari 5.1 to 6.0 */
          background: -o-linear-gradient(right, yellow, red); /* For Opera 11.1 to 12.0 */
          background: -moz-linear-gradient(right, yellow, red); /* For Firefox 3.6 to 15 */
          background: linear-gradient(to right, yellow , red); /* Standard syntax */
      }
      .Pagada-la-señal.end{
          background: red; /* For browsers that do not support gradients */
          background: -webkit-linear-gradient(left, red , yellow); /* For Safari 5.1 to 6.0 */
          background: -o-linear-gradient(right, red, yellow); /* For Opera 11.1 to 12.0 */
          background: -moz-linear-gradient(right, red, yellow); /* For Firefox 3.6 to 15 */
          background: linear-gradient(to right, red , yellow); /* Standard syntax */
      }
      .Bloqueado{
          background-color: #F9D975 !important;
          color: black;
      }
      .Bloqueado.start{
          background: red; /* For browsers that do not support gradients */
          background: -webkit-linear-gradient(left, white, yellow); /* For Safari 5.1 to 6.0 */
          background: -o-linear-gradient(right, white, yellow); /* For Opera 11.1 to 12.0 */
          background: -moz-linear-gradient(right, white, yellow); /* For Firefox 3.6 to 15 */
          background: linear-gradient(to right, white, yellow); /* Standard syntax */
      }
      .Bloqueado.end{
          background: red; /* For browsers that do not support gradients */
          background: -webkit-linear-gradient(left, yellow, white); /* For Safari 5.1 to 6.0 */
          background: -o-linear-gradient(right, yellow, white); /* For Opera 11.1 to 12.0 */
          background: -moz-linear-gradient(right, yellow, white); /* For Firefox 3.6 to 15 */
          background: linear-gradient(to right, yellow, white); /* Standard syntax */
      }
      .SubComunidad{
          background-color: #8A7DBE !important;
          color: black;
      }
      .SubComunidad.start{
          background: red; /* For browsers that do not support gradients */
          background: -webkit-linear-gradient(left, white, purple); /* For Safari 5.1 to 6.0 */
          background: -o-linear-gradient(right, white, purple); /* For Opera 11.1 to 12.0 */
          background: -moz-linear-gradient(right, white, purple); /* For Firefox 3.6 to 15 */
          background: linear-gradient(to right, white, purple); /* Standard syntax */
      }
      .SubComunidad.end{
          background: red; /* For browsers that do not support gradients */
          background: -webkit-linear-gradient(left, purple, white); /* For Safari 5.1 to 6.0 */
          background: -o-linear-gradient(right, purple, white); /* For Opera 11.1 to 12.0 */
          background: -moz-linear-gradient(right, purple, white); /* For Firefox 3.6 to 15 */
          background: linear-gradient(to right, purple, white); /* Standard syntax */
      }
      /*.row-details{pointer-events: none;}*/
    </style>
@section('content')

    <div class="container-fluid container-fixed-lg">
      <div class="row">
        <div class="col-md-8">
          <!-- START PANEL -->
          <div class="panel panel-transparent">
            <div class="panel-heading text-center">
              <div class="panel-title">Planing de reservas
              </div>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-hover table-condensed table-detailed" id="detailedTable">
                  <thead>
                    <tr>
                      <th class="text-center bg-complete text-white" hidden="">ID</th>
                      <th class="text-center bg-complete text-white" style="width:25%">Nombre</th>
                      <th class="text-center bg-complete text-white" hidden>Telefono</th>
                      <th class="text-center bg-complete text-white">In</th>
                      <th class="text-center bg-complete text-white">Out</th>
                      <th class="text-center bg-complete text-white">Pax</th>
                      <th class="text-center bg-complete text-white">Pax</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($arrayBooks["nuevas"] as $book): ?>
                      <tr> 
                          <td hidden><?php echo $book->id ?></td>
                          <td class="v-align-middle semi-bold text-center"><?php echo $book->customer['name']  ?></td>
                          <td class="v-align-middle semi-bold text-center" hidden><?php echo $book->customer['phone']  ?></td>
                          <td class="v-align-middle semi-bold text-center">
                            <?php
                                $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                echo $start->formatLocalized('%d %b');
                            ?>
                          </td>
                          <td class="v-align-middle semi-bold text-center">
                            <?php
                                $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                echo $finish->formatLocalized('%d %b');
                            ?>
                          </td>
                          <td class="v-align-middle semi-bold text-center"><?php echo $book->pax ?></td>
                          <td class="v-align-middle semi-bold text-center">
                            <select class="room" data-id="<?php echo $book->id ?>" >                                                              
                              <?php foreach ($rooms as $room): ?>
                                  <?php if ($room->id == $book->room_id): ?>
                                      <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                          <?php echo substr($room->name,0,5) ?>
                                      </option>
                                  <?php else:?>
                                      <option value="<?php echo $room->id ?>"><?php echo substr($room->name,0,5) ?></option>
                                  <?php endif ?>
                              <?php endforeach ?>
                            </select>
                          </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
              
              <!-- Div de pendientes -->

                <div class="col-xs-12 tipo-reservas text-center text-white" id="pendientes" style="background-color: grey;color: black">PENDIENTES</div>
                  <div class="col-md-12 col-xs-12 reservas pendientes" style="display: none;">
                    <div class="col-xs-3 text-center bg-complete text-white">NOMBRE</div>
                    <div class="col-xs-2 text-center bg-complete text-white">IN</div>
                    <div class="col-xs-2 text-center bg-complete text-white">OUT</div>
                    <div class="col-xs-5 text-center bg-complete text-white">PVP</div>
                    <div style="clear: both;"></div>
                    <?php foreach ($arrayBooks["nuevas"] as $key => $book): ?>
                      <div class="desplegable" id="<?php echo $book->id ?>">
                        <div class="col-xs-3 text-center bg-white nombre" ><?php echo substr($book->customer['name'] , 0,7) ?></div>
                        <div class="col-xs-2 text-center bg-white">
                          <?php
                              $start = Carbon::createFromFormat('Y-m-d',$book->start);
                              echo $start->formatLocalized('%d %b');
                          ?>
                        </div>
                        <div class="col-xs-2 text-center bg-white">
                          <?php
                              $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                              echo $finish->formatLocalized('%d %b');
                          ?>
                        </div>
                        <div class="col-xs-5 text-center bg-white"><?php echo $book->total_price ?>€</div>
                      </div>
                      <div style="clear: both;"></div>
                      <div style="display:none" class="oculto" id="div-<?php echo $book->id; ?>">
                          <div class="col-xs-2"><a href="tel:<?php echo $book->customer['phone'] ?>"><i class="fa fa-phone fa-2x"></i></a></div>
                          <div class="col-xs-2"><a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><i class="fa fa-pencil fa-2x"></i></a></div>
                          <div class="col-xs-3">
                              <select class="room" data-id="<?php echo $book->id ?>" >
                                  
                                  <?php foreach ($rooms as $room): ?>
                                      <?php if ($room->id == $book->room_id): ?>
                                          <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                              <?php echo substr($room->name,0,5) ?>
                                          </option>
                                      <?php else:?>
                                          <option value="<?php echo $room->id ?>"><?php echo substr($room->name,0,5) ?></option>
                                      <?php endif ?>
                                  <?php endforeach ?>

                              </select>
                          </div>
                          <div class="col-xs-5">
                              <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                  <?php for ($i=1; $i < 9; $i++): ?> 
                                      <?php if ($i == $book->type_book): ?>
                                          <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                      <?php else: ?>
                                          <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                      <?php endif ?>                                          
                                       
                                  <?php endfor; ?>
                              </select>
                          </div>

                      </div>
                      <div style="clear: both;"></div>
                    <?php endforeach ?>
                  </div>

              <!-- Div de Especiales -->

                <div class="col-xs-12 tipo-reservas text-center text-white" id="especiales" style="background-color: grey;color: black;margin-top: 5px">ESPECIALES</div>
                  <div class="col-md-12 col-xs-12 reservas especiales" style="display: none">
                    <div class="col-xs-3 text-center bg-complete text-white">NOMBRE</div>
                    <div class="col-xs-3 text-center bg-complete text-white">IN</div>
                    <div class="col-xs-3 text-center bg-complete text-white">OUT</div>
                    <div class="col-xs-3 text-center bg-complete text-white">PVP</div>
                    <div style="clear: both;"></div>
                    <?php foreach ($arrayBooks["especiales"] as $key => $book): ?>
                      <div class="desplegable" id="<?php echo $book->id ?>">
                        <div class="col-xs-3 text-center bg-white nombre" ><?php echo substr($book->customer['name'] , 0,7) ?></div>
                        <div class="col-xs-3 text-center bg-white">
                          <?php
                              $start = Carbon::createFromFormat('Y-m-d',$book->start);
                              echo $start->formatLocalized('%d %b');
                          ?>
                        </div>
                        <div class="col-xs-3 text-center bg-white">
                          <?php
                              $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                              echo $finish->formatLocalized('%d %b');
                          ?>
                        </div>
                        <div class="col-xs-3 text-center bg-white"><?php echo $book->total_price ?>€</div>
                      </div>
                      <div style="clear: both;"></div>
                      <div style="display:none" class="oculto" id="div-<?php echo $book->id; ?>">
                          <div class="col-xs-2"><a href="tel:<?php echo $book->customer['phone'] ?>"><i class="fa fa-phone fa-2x"></i></a></div>
                          <div class="col-xs-2"><a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><i class="fa fa-pencil fa-2x"></i></a></div>
                          <div class="col-xs-3">
                              <select class="room" data-id="<?php echo $book->id ?>" >
                                  
                                  <?php foreach ($rooms as $room): ?>
                                      <?php if ($room->id == $book->room_id): ?>
                                          <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                              <?php echo substr($room->name,0,5) ?>
                                          </option>
                                      <?php else:?>
                                          <option value="<?php echo $room->id ?>"><?php echo substr($room->name,0,5) ?></option>
                                      <?php endif ?>
                                  <?php endforeach ?>

                              </select>
                          </div>
                          <div class="col-xs-5">
                              <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                  <?php for ($i=1; $i < 9; $i++): ?> 
                                      <?php if ($i == $book->type_book): ?>
                                          <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                      <?php else: ?>
                                          <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                      <?php endif ?>                                          
                                       
                                  <?php endfor; ?>
                              </select>
                          </div>

                      </div>
                      <div style="clear: both;"></div>
                    <?php endforeach ?>
                  </div>
              
              <!-- Div pagadas -->
                <div class="col-xs-12 tipo-reservas text-center text-white" id="pagadas" style="background-color: grey;color: black;margin-top: 5px">PAGADAS</div>
                  <div class="col-md-12 col-xs-12 reservas pagadas" style="display: none;">
                    <div class="col-xs-3 text-center bg-complete text-white">NOMBRE</div>
                    <div class="col-xs-2 text-center bg-complete text-white">IN</div>
                    <div class="col-xs-2 text-center bg-complete text-white">OUT</div>
                    <div class="col-xs-5 text-center bg-complete text-white">PVP/COBRADO</div>
                    <div style="clear: both;"></div>
                    <?php foreach ($arrayBooks["pagadas"] as $key => $book): ?>
                      <div class="desplegable" id="<?php echo $book->id ?>">
                        <div class="col-xs-3 text-center bg-white nombre" ><?php echo substr($book->customer['name'] , 0,7) ?></div>
                        <div class="col-xs-2 text-center bg-white">
                          <?php
                              $start = Carbon::createFromFormat('Y-m-d',$book->start);
                              echo $start->formatLocalized('%d %b');
                          ?>
                        </div>
                        <div class="col-xs-2 text-center bg-white">
                          <?php
                              $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                              echo $finish->formatLocalized('%d %b');
                          ?>
                        </div>
                        <div class="col-xs-5 text-center bg-white"><?php echo $book->total_price ?>€/
                                                                    <?php if (isset($payment[$book->id])): ?>
                                                                        <b style="color: red"><?php echo $payment[$book->id] ?>€</b>
                                                                    <?php else: ?>
                                                                    <?php endif ?>
                        </div>
                      </div>
                      <div style="clear: both;"></div>
                      <div style="display:none" class="oculto" id="div-<?php echo $book->id; ?>">
                          <div class="col-xs-2"><a href="tel:<?php echo $book->customer['phone'] ?>"><i class="fa fa-phone fa-2x"></i></a></div>
                          <div class="col-xs-2"><a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><i class="fa fa-pencil fa-2x"></i></a></div>
                          <div class="col-xs-3">
                              <select class="room" data-id="<?php echo $book->id ?>" >
                                  
                                  <?php foreach ($rooms as $room): ?>
                                      <?php if ($room->id == $book->room_id): ?>
                                          <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                              <?php echo substr($room->name,0,5) ?>
                                          </option>
                                      <?php else:?>
                                          <option value="<?php echo $room->id ?>"><?php echo substr($room->name,0,5) ?></option>
                                      <?php endif ?>
                                  <?php endforeach ?>

                              </select>
                          </div>
                          <div class="col-xs-5">
                              <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                  <?php for ($i=1; $i < 9; $i++): ?> 
                                      <?php if ($i == $book->type_book): ?>
                                          <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                      <?php else: ?>
                                          <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                      <?php endif ?>                                          
                                       
                                  <?php endfor; ?>
                              </select>
                          </div>

                      </div>
                      <div style="clear: both;"></div>
                    <?php endforeach ?>
                  </div>
            

              <!-- Calendario de reservas -->
              
              <div class="col-md-12 col-xs-12 sm-padding-10">
                  <div class="col-xs-12 col-md-2 pull-right">
                      Fechas:
                      <select id="date" class="form-control">
                          <?php $fecha = $date->copy()->startOfYear(); ?>

                          <?php for ($i=1; $i <= 12; $i++): ?>
                              <?php if( $date->copy()->format('n') == $i ){ $selected = "selected"; }else{$selected = "";} ?>
                              <option value="<?php echo $fecha->format('d-m-Y'); ?>" <?php echo $selected ?>>
                                  <?php echo ucfirst($fecha->formatLocalized('%B')); ?>  <?php echo $fecha->formatLocalized('%Y'); ?> 
                              </option>
                              <?php $fecha->addMonth(); ?>
                          <?php endfor; ?>
                      </select>
                  </div>
              </div>

                <div class="table-responsive sm-padding-10">
                  <?php for ($z=1; $z <= 4; $z++):?>
                  <div class="tab-pane <?php if($z == 1){ echo 'active';} ?>" id="tab<?php echo $z ?>">
                      <div class="row">
                          <div class="col-md-12">
                              <table class="fc-border-separate" style="width: 100%">
                                 <thead>
                                      <tr >
                                          <td class="text-center" colspan="<?php echo $arrayMonths[$date->copy()->format('n')]+1 ?>">
                                              <?php echo  ucfirst($date->copy()->formatLocalized('%B %Y'))?>
                                          </td> 
                                      </tr>
                                      <tr>
                                          <td rowspan="2" style="width: 1%!important"></td>
                                              <?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
                                                  <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center">
                                                      <?php echo $i?> 
                                                  </td> 
                                               <?php endfor; ?>
                                      </tr>
                                      <tr>
                                          
                                          <?php for ($i=1; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 
                                              <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center <?php echo $days[$date->copy()->format('n')][$i]?>">
                                                  <?php echo $days[$date->copy()->format('n')][$i]?> 
                                              </td> 
                                           <?php endfor; ?> 
                                      </tr>
                                 </thead>
                                 <tbody>
                                 
                                      <?php foreach ($roomscalendar as $room): ?>
                                          <tr>
                                              <?php $date = $date->startOfMonth() ?>
                                              <td class="text-center"><b style="font-size: 10px"><?php echo substr($room->nameRoom, 0,5)?></b></td>
                                                  
                                              <?php for ($i=01; $i <= $arrayMonths[$date->copy()->format('n')] ; $i++): ?> 

                                                      <?php if (isset($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i])): ?>
                                                          <?php if ($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->start == $date->copy()->format('Y-m-d')): ?>
                                                                  <td style='border:1px solid grey;width: 3%'>
                                                                      <div style="width: 50%;float: left;">
                                                                          &nbsp;
                                                                      </div>
                                                                      <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> start" style="width: 50%;float: left;">
                                                                          &nbsp;
                                                                      </div>

                                                                  </td>    
                                                          <?php elseif($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->finish == $date->copy()->format('Y-m-d')): ?>
                                                                  <td style='border:1px solid grey;width: 3%'>
                                                                      <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?> end" style="width: 50%;float: left;">
                                                                          &nbsp;
                                                                      </div>
                                                                      <div style="width: 50%;float: left;">
                                                                          &nbsp;
                                                                      </div>
                                                                      

                                                                  </td>
                                                          <?php else: ?>
                                                              
                                                                  <td style='border:1px solid grey;width: 3%' title="<?php echo $arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->customer['name'] ?>" class="<?php echo $book->getStatus($arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->type_book) ?>">

                                                                 <a href="{{url ('/admin/reservas/update')}}/<?php echo $arrayReservas[$room->id][$date->copy()->format('Y')][$date->copy()->format('n')][$i]->id ?>">
                                                                     <div style="width: 100%;height: 100%">
                                                                         &nbsp;
                                                                     </div>
                                                                 </a>

                                                              </td>

                                                          <?php endif ?>
                                                      <?php else: ?>
                                                          <td class="<?php echo $days[$date->copy()->format('n')][$i]?>" style='border:1px solid grey;width: 3%'>
                                                              
                                                          </td>
                                                      <?php endif; ?>
                                                      <?php if ($date->copy()->format('d') != $arrayMonths[$date->copy()->format('n')]): ?>
                                                          <?php $date = $date->addDay(); ?>
                                                      <?php else: ?>
                                                          <?php $date = $date->startOfMonth() ?>
                                                      <?php endif ?>
                                                  
                                              <?php endfor; ?> 
                                          </tr>
                                          
                                      <?php endforeach; ?>
                                 </tbody>
                              </table>
                              <?php $date = $date->addMonth(); ?>
                          </div>
                      </div>
                  </div>
                  <?php endfor; ?>
                </div>

              
            </div>

            
          </div>
          <!-- END PANEL -->
        </div>
      </div>
    </div>



@endsection

@section('scripts')

    
    <!-- END OVERLAY -->
    <!-- BEGIN VENDOR JS -->
    <script src="/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/modernizr.custom.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-bez/jquery.bez.min.js"></script>
    <script src="/assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-actual/jquery.actual.min.js"></script>
    <script src="/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/classie/classie.js"></script>
    <script src="/assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="pages/js/pages.min.js"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="/assets/js/tables.js" type="text/javascript"></script>
    <script src="/assets/js/scripts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->

<script>     

  $(document).ready(function() {            
    $(".oculto").hide();              
      $(".desplegable").click(function(){
        var id = $(this).attr('id');
        if ($('#div-'+id).is(":visible")) {
          $('.oculto').hide();
        }else{
          $('.oculto').hide();
        $('#div-'+id).show();
        }
        
      });

    $(".reservas").hide();              
      $(".tipo-reservas").click(function(){
        var id = $(this).attr('id');
        if ($('.'+id).is(":visible")) {
          $('.reservas').hide();
        }else{
          $('.reservas').hide();
        $('.'+id).show();
        }
        
      });
      
    $('#date').change(function(event) {
        
        var month = $(this).val();
        window.location = '/admin/reservas/'+month;
    });

  });

  
</script>

@endsection