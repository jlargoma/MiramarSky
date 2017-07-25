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
      .table.table-hover.demo-table-search.table-responsive.table-striped > tbody > tr > td{font-size: 10px!important;padding: 10px!important}
      .table.table-hover.demo-table-search.table-responsive.table-striped > thead > tr > th{font-size: 12px!important;padding: 10px!important}
      .fc-border-separate>tbody >tr >td{min-width: 15px!important}
      .bg-white{ margin: 0px!important;padding: 2px!important;border-bottom: 0.5px solid;font-size: 10px!important}
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

            <!-- <div class="table-responsive">
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
            </div> -->
              
              <!-- Div de pendientes -->
        
                <div class="col-xs-12 tipo-reservas text-center text-white" id="pendientes" style="background-color: grey;color: black">PENDIENTES</div>
                  
                  <!-- <div class="col-md-12 col-xs-12 reservas pendientes" style="display: none;">
                    <div class="col-xs-3 text-center bg-complete text-white"><b>NOMBRE</b></div>
                    <div class="col-xs-2 text-center bg-complete text-white"><b>IN</b></div>
                    <div class="col-xs-2 text-center bg-complete text-white"><b>OUT</b></div>
                    <div class="col-xs-5 text-center bg-complete text-white"><b>PVP</b></div>
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
                  </div>  -->
                  
                  <div class="col-md-12 col-xs-12 reservas pendientes" style="display: none">
                    
                     <!--  <table class="table table-hover table-condensed table-detailed" id="detailedTable">
                        <thead>
                          <tr>
                            <th class="text-center bg-complete text-white" hidden="">ID</th>
                            <th class="text-center bg-complete text-white" >Nombre</th>
                            <th class="text-center bg-complete text-white" hidden>Telefono</th>
                            <th class="text-center bg-complete text-white">In</th>
                            <th class="text-center bg-complete text-white">Out</th>
                            <th class="text-center bg-complete text-white">Pax</th>
                            <th class="text-center bg-complete text-white">Apto</th>
                            <th class="text-center bg-complete text-white" hidden="">PVP</th>
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
                                <td class="v-align-middle semi-bold text-center" hidden><?php echo $book->total_price ?></td>

                            </tr>
                          <?php endforeach ?>
                        </tbody>
                      </table> -->
                      <div class="table-responsive">
                        <div class="pull-left">
                            <div class="col-xs-12 ">
                                <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>
                        <table class="table table-hover demo-table-search table-responsive table-striped " id="tableWithSearch" >
                            <thead>
                                <tr> 
                                    <th class ="text-center bg-complete text-white" style="width:20%!important">  C     </th>
                                    <th class ="text-center bg-complete text-white" style="width:10%">  T     </th>
                                    <th class ="text-center bg-complete text-white" style="width:2%">   P         </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%">   Apart       </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%!important">  IN     </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%!important">  OUT      </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%">   Noc         </th>
                                    <th class ="text-center bg-complete text-white">                    Precio      </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%">   Estado      </th>
                                    <th class ="text-center bg-complete text-white">                    A  </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($arrayBooks["nuevas"] as $book): ?>
                                        <tr> 

                                            <td class ="text-center">

                                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><?php echo $book->customer['name']  ?></a>                                                        
                                            </td>

                                            <td class ="text-center"> 
                                                <?php if ($book->customer->phone != 0): ?>
                                                    <a href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone fa-2x"></i>
                                                <?php else: ?>
                                                <?php endif ?>
                                            </td>

                                            <td class ="text-center"><?php echo $book->pax ?></td>

                                            <td class ="text-center">
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

                                            <td class ="text-center" style="width: 20%!important">
                                                <?php
                                                    $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                                    echo $start->formatLocalized('%d %b');
                                                ?>
                                            </td>

                                            <td class ="text-center" style="width: 20%!important">
                                                <?php
                                                    $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                                    echo $finish->formatLocalized('%d %b');
                                                ?>
                                            </td>

                                            <td class ="text-center"><?php echo $book->nigths ?></td>

                                            <td class ="text-center"><?php echo $book->total_price."€" ?><br>
                                                                    <?php if (isset($payment[$book->id])): ?>
                                                                        <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                                                                    <?php else: ?>
                                                                    <?php endif ?>
                                            </td>

                                            <td class ="text-center">
                                                <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                                    <?php for ($i=1; $i < 9; $i++): ?> 
                                                        <?php if ($i == $book->type_book): ?>
                                                            <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                                        <?php else: ?>
                                                            <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                                        <?php endif ?>                                          
                                                         
                                                    <?php endfor; ?>
                                                </select>
                                            </td>

                                            <td>                                                        
                                                    <!--  -->
                                                    <!-- <?php if ($book->customer['phone'] != 0): ?>
                                                            <a class="btn btn-tag btn-primary" href="tel:<?php echo $book->customer['phone'] ?>"><i class="pg-phone"></i>
                                                            </a>
                                                    <?php endif ?> -->
                                                    
                                                    <?php if ($book->send == 0): ?>
                                                        <a class="btn btn-tag btn-primary sendJaime" title="Enviar Email a Jaime" data-id="<?php echo $book->id ?>"><i class=" pg-mail"></i></a>
                                                        </a>
                                                    <?php else: ?>
                                                        <a class="btn btn-tag btn-danger" title="enviado" disabled data-id="<?php echo $book->id ?>"><i class=" pg-mail "></i></a>
                                                        </a>
                                                    <?php endif ?>
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table> 
                      </div>
                      
                  </div> 

              <!-- Div de Especiales -->

                <div class="col-xs-12 tipo-reservas text-center text-white" id="especiales" style="background-color: grey;color: black;margin-top: 5px">ESPECIALES</div>
                  <div class="col-md-12 col-xs-12 reservas especiales" style="display: none">
                    <div class="col-xs-3 text-center bg-complete text-white"><b>NOMBRE</b></div>
                    <div class="col-xs-3 text-center bg-complete text-white"><b>IN</b></div>
                    <div class="col-xs-3 text-center bg-complete text-white"><b>OUT</b></div>
                    <div class="col-xs-3 text-center bg-complete text-white"><b>PVP</b></div>
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
                  
                  <div class="col-md-12 col-xs-12 reservas pagadas table-responsive" style="display: none;">
                    <div class="row">
                        <div class="pull-left">
                            <div class="col-xs-12 ">
                                <input type="text" id="search-table3" class="form-control pull-right" placeholder="Buscar">
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>

                        <table class="table table-hover demo-table-search table-responsive table-striped " id="tableWithSearch3" >
                            <thead>
                                <tr>   
                                    <th class ="text-center bg-complete text-white" style="width:5%"> Cobros </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%">  Cliente     </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%">  Telefono     </th>
                                    <th class ="text-center bg-complete text-white" style="width:2%">   Pax         </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%">   Apart       </th>
                                    <th class ="text-center bg-complete text-white" style="width:20%!important">  IN     </th>
                                    <th class ="text-center bg-complete text-white" style="width:20%!important">  OUT      </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%">   Noc         </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%">   Precio      </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%">   Estado      </th>
                                    <th class ="text-center bg-complete text-white" style="width:5%">   Acciones    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($arrayBooks["pagadas"] as $book): ?>
                                  <tr>
                                    <td class ="text-center">                                                    
                                        <?php if (isset($payment[$book->id])): ?>
                                            <?php if ($payment[$book->id] == 0): ?>
                                            <?php else:?>
                                                <p><?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?></p>
                                                <div class="progress ">
                                                <div class="progress-bar progress-bar-danger" style="width:<?php echo number_format(100/($book->total_price/$payment[$book->id]),0).'%' ?>"></div>
                                                </div>                                                            
                                            <?php endif; ?>
                                        <?php else: ?>
                                        <?php endif ?>
                                    </td> 
                                    <td class ="text-center">
                                            <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><?php echo substr($book->customer['name'], 0,10)  ?></a>                                                        
                                    </td>

                                    <td class ="text-center"><a href="tel:<?php echo $book->customer->phone ?>"><i class="fa fa-phone fa-2x"></i></td>
                                    <td class ="text-center"><?php echo $book->pax ?></td>
                                    <td class ="text-center">
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
                                    <td class ="text-center" style="width: 20%!important">
                                        <?php
                                            $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                            echo $start->formatLocalized('%d %b');
                                        ?>
                                    </td>
                                    <td class ="text-center" style="width: 20%!important">
                                        <?php
                                            $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                            echo $finish->formatLocalized('%d %b');
                                        ?>
                                    </td>
                                    <td class ="text-center"><?php echo $book->nigths ?></td>
                                    <td class ="text-center"><?php echo $book->total_price."€" ?><br>
                                                            <?php if (isset($payment[$book->id])): ?>
                                                                <?php echo "<p style='color:red'>".$payment[$book->id]."</p>" ?>
                                                            <?php else: ?>
                                                            <?php endif ?>
                                    </td>
                                    <td class ="text-center">
                                        <select class="status form-control" data-id="<?php echo $book->id ?>" >
                                            <?php for ($i=1; $i < 9; $i++): ?> 
                                                <?php if ($i == $book->type_book): ?>
                                                    <option selected value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>"><?php echo $book->getStatus($i) ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                                <?php endif ?>                                          
                                                 
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="btn-group col-md-6">
                                            <!--  -->
                                            <!-- <?php if ($book->customer['phone'] != 0): ?>
                                                    <a class="btn btn-tag btn-primary" href="tel:<?php echo $book->customer['phone'] ?>"><i class="pg-phone"></i>
                                                    </a>
                                            <?php endif ?> -->
                                            
                                            <?php if ($book->send == 0): ?>
                                                <a class="btn btn-tag btn-primary" ><i class=" pg-mail"></i></a>
                                                </a>
                                            <?php else: ?>
                                            <?php endif ?>
                                    </td>
                                  </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>  
                    </div>
                  </div>
                  <!-- <div class="col-md-12 col-xs-12 reservas pagadas" style="display: none;">
                    <div class="col-xs-3 text-center bg-complete text-white"><b>NOMBRE</b></div>
                    <div class="col-xs-2 text-center bg-complete text-white"><b>IN</b></div>
                    <div class="col-xs-2 text-center bg-complete text-white"><b>OUT</b></div>
                    <div class="col-xs-5 text-center bg-complete text-white"><b>PVP/COBRADO</b></div>
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
                        <div class="col-xs-5 text-center bg-white"><?php echo number_format($book->total_price,2,',','.') ?>€/
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
                  </div> -->
            
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

              <div class="col-md-5 col-xs-12">
                  <div class="panel">
                      <ul class="nav nav-tabs nav-tabs-simple" role="tablist" data-init-reponsive-tabs="collapse">
                          <?php $dateAux = $date->copy(); ?>
                          <?php for ($i=1; $i <= 4 ; $i++) :?>
                              <li <?php if($i == 1 ){ echo "class='active'";} ?>>
                                  <a href="#tab<?php echo $i?>" data-toggle="tab" role="tab">
                                      <?php echo substr(ucfirst($dateAux->copy()->formatLocalized('%B')),0,3)?>
                                  </a>
                              </li>
                              <?php $dateAux->addMonth(); ?>
                          <?php endfor; ?>
                      </ul>
                      <div class="tab-content">
                          
                          <?php for ($z=1; $z <= 4; $z++):?>
                          <div class="table-responsive tab-pane <?php if($z == 1){ echo 'active';} ?>" id="tab<?php echo $z ?>">
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
                                                      <td class="text-center"><b><?php echo substr($room->nameRoom, 0,5)?></b></td>
                                                          
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
    <script src="/pages/js/pages.min.js"></script>
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

    $('.status,.room').change(function(event) {
        var id = $(this).attr('data-id');
        var clase = $(this).attr('class');
        
        if (clase == 'status form-control') {
            var status = $(this).val();
            var room = "";
        }else if(clase == 'room'){
            var room = $(this).val();
            var status = "";
        }
        $.get('/admin/reservas/changeBook/'+id, {status:status,room: room}, function(data) {
            alert(data);
        });
    });

    var start  = 0;
    var finish = 0;
    var diferencia = 0;
    var price = 0;
    var cost = 0;


    $('#start').change(function(event) {
        start= $(this).val();
        var info = start.split('/');
        start = info[1] + '/' + info[0] + '/' + info[2];
        if (finish != 0) {
            diferencia = Math.floor((  Date.parse(finish)- Date.parse(start) ) / 86400000);
            $('.nigths').empty();
            $('.nigths').html(diferencia);
        }
    });

    $('#finish').change(function(event) {
        finish= $(this).val();
        var info = finish.split('/');
        finish = info[1] + '/' + info[0] + '/' + info[2];           
        if (start != 0) {
            diferencia = Math.floor((  Date.parse(finish)- Date.parse(start) ) / 86400000);
            $('.nigths').empty();
            $('.nigths').val(diferencia);
        }
    });

    $('#newroom, .pax, .parking, .agencia').change(function(event){ 

        var room = $('#newroom').val();
        var pax = $('.pax').val();
        var park = $('.parking').val();
        var beneficio = 0;
        var costPark = 0;
        var pricePark = 0;
        var agencia = 0;
        $.get('/admin/apartamentos/getPaxPerRooms/'+room).success(function( data ){
            console.log(data);
            console.log(pax);
            if (pax < data) {
                $('.pax').attr('style' , 'background-color:red');
                $('.book_comments').empty();
                $('.book_comments').append('Van menos personas que el minimo, se le cobrara el minimo de la habitacion que son :'+data);
            }else{
                $('.book_comments').empty();
                $('.pax').removeAttr('style');
            }
        });

        $.get('/admin/reservas/getPricePark', {park: park, noches: diferencia}).success(function( data ) {
            pricePark = data;
            $.get('/admin/reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                price = data;
                
                price = (parseFloat(price) + parseFloat(pricePark));
                $('.total').empty();
                $('.total').val(price);
                    $.get('/admin/reservas/getCostPark', {park: park, noches: diferencia}).success(function( data ) {
                        costPark = data;
                            $.get('/admin/reservas/getCostBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                                cost = data;
                                agencia = $('.agencia').val();
                                cost = (parseFloat(cost) + parseFloat(costPark) + parseFloat(agencia));
                                $('.cost').empty();
                                $('.cost').val(cost);
                                beneficio = price - cost;
                                $('.beneficio').empty;
                                $('.beneficio').val(beneficio);
                            });
                    });
            });
        });  
    });
    
    $('.total').change(function(event) {
        var price = $(this).val();
        var cost = $('.cost').val();
        var beneficio = (parseFloat(price) - parseFloat(cost));
        console.log(beneficio);
        $('.beneficio').empty;
        $('.beneficio').val(beneficio);
    });

    
    $('.sendJaime').click(function(event) {
        var id = $(this).attr('data-id');
        console.log(id);
        $.get('/admin/reservas/sendJaime', {id: id}).success(function( data ) {
            alert(data);
        });
    });
  });

  
</script>

@endsection