@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

    <style>

      td{      
        padding: 10px 5px!important;
      }
      .pagos{
        background-color: rgba(255,255,255,0.5)!important;
      }

      td[class$="bi"] {border-left: 1px solid black;}
      td[class$="bf"] {border-right: 1px solid black;}
      
      .coste{
        background-color: rgba(200,200,200,0.5)!important;
      }
      
      .red{
        color: red;
      }
      .blue{
        color: blue;
      }
    </style>
@endsection
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES");?>  
@section('content')

<div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
        <div class="col-md-12 col-xs-12 push-20">
            <div class="col-xs-12 col-md-2 pull-right">
                <select id="date" class="form-control">
                    <?php $fecha = $date->copy()->subYear(); ?>
                    <?php for ($i=1; $i <= 3; $i++): ?>
                        <?php if( $date->copy()->format('Y') == $fecha->format('Y') ){ $selected = "selected"; }else{$selected = "";} ?>
                        <option value="<?php echo $fecha->copy()->format('Y'); ?>" <?php echo $selected ?>>
                            <?php echo $fecha->copy()->format('Y')."-".$fecha->copy()->addYear()->format('Y'); ?> 
                        </option>
                        <?php $fecha->addYear(); ?>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="clearfix"></div>
                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >

                    <thead>
                        <th class ="text-center bg-complete text-white"> Habitacion    </th>
                        <th class ="text-center bg-complete text-white"> Tipo de apto. </th>
                        <th class ="text-center bg-complete text-white"> Total a pagar </th>
                        <th class ="text-center bg-complete text-white"> Total pagado  </th>
                        <th class ="text-center bg-complete text-white"> Total deuda   </th>
                    </thead>
                    <tbody>
                        <?php foreach ($rooms as $room): ?>
                          <tr style="margin: 0px">
                            <td class="text-center"><a class="update-payments" type="button" data-debt="<?php echo $debt[$room->id] ?>" data-month="<?php echo $date->copy()->format('Y') ?>" data-id="<?php echo $room->id ?>" data-toggle="modal" data-target="#payments" title="Añadir pago" ><?php echo $room->nameRoom ?></a></td>
                            <td class="text-center"><?php echo $room->typeAptos->name ?></td>
                            <td class="text-center">
                              <?php if (isset($total[$room->id])): ?>
                                <?php echo number_format($total[$room->id],2,',','.')." €" ?>
                              <?php else: ?>
                                  No hay reservas para este apartamento
                              <?php endif ?>
                            </td>
                            <td class="text-center">
                              <?php if (isset($totalPayment[$room->id])): ?>
                                <?php echo number_format($totalPayment[$room->id],2,',','.')." €" ?>
                              <?php else: ?>
                                  No hay pagos para este apartamento
                              <?php endif ?>
                            </td>
                            <td class="text-center pendiente">
                              <?php if (isset($debt[$room->id])): ?>
                                <?php echo number_format($debt[$room->id],2,',','.')." €" ?>
                              <?php else: ?>
                                  --------
                              <?php endif ?>
                            </td>
                          </tr>
                          
                        <?php endforeach ?>
                    </tbody>

                </table>

        </div>
    </div>
</div>


<div class="modal fade slide-up disable-scroll in" id="payments" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 70%;">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-50"></i>
        </button>
        <div class="container-xs-height full-height">
          <div class="row-xs-height">
            <div class="modal-body col-xs-height col-middle text-center   ">

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


@endsection

@section('scripts')

   <script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
   <script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
   <script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
   <script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
   <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>

  <script type="text/javascript">
      // var colorPendienteCobro = function(){
      //   var pendientes  = $('.pendiente');


      //   for(ind in pendientes){
            
      //       var pendCobro = pendientes[ind];

      //       if ($(pendCobro).text() == '0,00 €') {
      //         $(pendCobro).addClass("blue");
      //       }else{
      //         $(pendCobro).addClass("red");
      //       };
      //   }
      // }

    $(document).ready(function() {
        
        $('.update-payments').click(function(event) {
            var debt = $(this).attr('data-debt');
            var id   = $(this).attr('data-id');
            var month = $(this).attr('data-month');
            $.get('/admin/pagos-propietarios/update/'+id+'/'+month,{ debt: debt}, function(data) {
                $('.modal-body').empty().append(data);
            });
        });

        $('#date').change(function(event) {
            
            var month = $(this).val();
            window.location = '/admin/pagos-propietarios/'+month;
        });
        
      colorPendienteCobro();
      $('.dataTables_paginate').click(function(event) {
        colorPendienteCobro();
      });
    });
  </script>


@endsection