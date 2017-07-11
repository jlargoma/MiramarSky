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
      .table.table-condensed.table-detailed > tbody > tr > td:first-child:before{ display: none!important;}
      .bg-white{ margin: 0px!important;padding: 0px!important;border-bottom: 0.5px solid;}

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
              <div class="col-md-12 col-xs-12">
                <div class="col-xs-3 text-center bg-complete text-white">NOMBRE</div>
                <div class="col-xs-3 text-center bg-complete text-white">IN</div>
                <div class="col-xs-3 text-center bg-complete text-white">OUT</div>
                <div class="col-xs-3 text-center bg-complete text-white">PVP</div>
                <div style="clear: both;"></div>
                <?php foreach ($arrayBooks["nuevas"] as $key => $book): ?>
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
                    <div class="col-xs-3 text-center bg-white"><?php echo $book->total_price ?></div>
                  </div>
                  <div style="clear: both;"></div>
                  <div style="display:none" class="oculto" id="div-<?php echo $book->id; ?>">
                      <div class="col-xs-1"><a href="tel:<?php echo $book->customer['phone'] ?>"><i class="fa fa-phone fa-2x"></i></a></div>
                      <div class="col-xs-1"><a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('/admin/reservas/update')}}/<?php echo $book->id ?>"><i class="fa fa-pencil fa-2x"></i></a></div>
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
        $('.oculto').hide();
        $('#div-'+id).show();
      });
  });

  
</script>

@endsection