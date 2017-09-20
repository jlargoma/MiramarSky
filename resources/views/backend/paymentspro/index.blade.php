@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
    </script>
    <script type="text/javascript" src="/assets/js/canvasjs/canvasjs.min.js"></script>
    <style>

      td{      
        padding: 10px 5px!important;
      }
      .pagos{
        background-color: rgba(200,200,200,0.5)!important;
        font-weight: bold;
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
        <div class="col-md-12 text-center">
            <h2>Pagos a propietarios
              <select id="fechas">
                <?php $fecha = $date->copy()->subYear(); ?>
                <?php for ($i=1; $i <= 3; $i++): ?>
                  <?php echo $date->copy()->format('Y') ?>
                  <?php echo $fecha->copy()->format('Y') ?>
                    <option value="<?php echo $fecha->copy()->format('Y'); ?>" {{ $date->copy()->format('Y') == $fecha->format('Y') ? 'selected' : '' }}>
                        <?php echo $fecha->copy()->format('Y')."-".$fecha->copy()->addYear()->format('Y'); ?> 
                    </option>
                    <?php $fecha->addYear(); ?>
                <?php endfor; ?>
            </select>
          </h2>

        </div>
        <div class="col-md-12 col-xs-12 push-20">

            <div class="col-xs-12 col-md-2 pull-right">
                
            </div>
        </div>
        <div class="col-md-4 col-md-offset-3">
           <table class="table table-hover demo-table-search table-block" id="tableWithSea">
              <thead>
                  <th class ="text-center bg-complete text-white"> PVP    </th>
                  <th class ="text-center bg-complete text-white"> Coste Apto   </th>
                  <th class ="text-center bg-complete text-white"> Beneficio    </th>
                  <th class ="text-center bg-complete text-white"> Ben    </th>
                  <th class ="text-center bg-complete text-white"> Pagado    </th>
                  <th class ="text-center bg-complete text-white"> Pendiente    </th>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center"><?php echo number_format(array_sum($totalPVP),2,',','.') ?> €</td>
                  <td class="text-center"><?php echo number_format(array_sum($totalCost),2,',','.') ?> €</td>
                  <td class="text-center"><?php echo number_format((array_sum($totalPVP) - array_sum($totalCost)),2,',','.') ?> €</td>
                  <td class="text-center"><?php echo number_format(((array_sum($totalPVP) - array_sum($totalCost))/array_sum($totalPVP) * 100 ),2,',','.') ?>%</td>
                  <td class="text-center"><?php echo number_format(array_sum($totalPayment),2,',','.') ?> €</td>
                  <td class="text-center"><?php echo number_format(array_sum($debt),2,',','.') ?> €</td>
                </tr>
              </tbody>
           </table>
        </div>
        <div style="clear: both"></div>
        <div class="col-md-7">
            <div class="clearfix"></div>
                <table class="table table-hover demo-table-search table-block" id="tableWithSearch" >

                    <thead>
                        <th class ="text-center bg-complete text-white"> Propietario    </th>
                        <th class ="text-center bg-complete text-white"> Nick    </th>
                        <th class ="text-center bg-complete text-white"> Tipo </th>
                        <th class ="text-center bg-complete text-white" style="width: 20%!important"> PVP&nbsp;&nbsp;  </th>
                        <th class ="text-center bg-complete text-white"> Coste Apto </th>
                        <th class ="text-center bg-complete text-white"> Beneficio </th>
                        <th class ="text-center bg-complete text-white"> % Ben </th>
                        <th class ="text-center bg-complete text-white"> Pagado  </th>
                        <th class ="text-center bg-complete text-white"> Pendiente   </th>
                        <!-- <th class ="text-center bg-complete text-white"> Pendiente   </th>
                        <th class ="text-center bg-complete text-white"> Pendiente   </th> -->
                    </thead>
                    <tbody>
                        <?php foreach ($rooms as $room): ?>
                          <tr style="margin: 0px">
                            <td> <?php echo $room->user->name ?></td>
                            <td class="text-center"><a style="cursor: pointer" class="update-payments" type="button" data-debt="<?php echo $debt[$room->id] ?>" data-month="<?php echo $date->copy()->format('Y') ?>" data-id="<?php echo $room->id ?>" data-toggle="modal" data-target="#payments" title="Añadir pago" ><?php echo $room->nameRoom ?></a></td>
                            <td class="text-center"><?php echo $room->typeAptos->name ?></td>
                            <td class="text-center">
                              <?php if (isset($totalPVP[$room->id])): ?>
                                <?php echo number_format($totalPVP[$room->id],2,',','.')." €" ?>
                              <?php else: ?>
                                  -----
                              <?php endif ?>
                            </td>
                            <td class="text-center  pagos bi">
                              <?php if (isset($totalCost[$room->id])): ?>
                                <?php echo number_format($totalCost[$room->id],2,',','.')." €" ?>
                              <?php else: ?>
                                  -----
                              <?php endif ?>
                            </td>
                            <td class="text-center  pagos bf" >
                              <?php if (isset($totalCost[$room->id]) && isset($totalPVP[$room->id])): ?>
                                <?php echo number_format((($totalPVP[$room->id]) - ($totalCost[$room->id])),2,',','.') ?> €
                              <?php else: ?>
                                  -----
                              <?php endif ?>
                            </td>
                            <td class="text-center">
                              <?php if (isset($totalCost[$room->id]) && isset($totalPVP[$room->id])): ?>
                                <?php echo number_format((($totalPVP[$room->id]-$totalCost[$room->id])/$totalPVP[$room->id]*100),2,',','.') ?> %
                              <?php else: ?>
                                  -----
                              <?php endif ?>
                            </td>
                            <td class="text-center">
                              <?php if (isset($totalPayment[$room->id])): ?>
                                <?php echo number_format($totalPayment[$room->id],2,',','.')." €" ?>
                              <?php else: ?>
                                  -----
                              <?php endif ?>
                            </td>
                            <td class="text-center pendiente">
                              <?php if (isset($debt[$room->id])): ?>
                                <?php echo number_format($debt[$room->id],2,',','.')." €" ?>
                              <?php else: ?>
                                  --------
                              <?php endif ?>
                            </td>
                            <!-- <td></td>
                            <td></td> -->
                          </tr>
                          
                        <?php endforeach ?>
                    </tbody>

                </table>

        </div>
        <!-- <div class="col-md-5">
            <div class="pull-right" id="chartContainer" style="height: 700px; width: 73%;"></div>
            

        </div> -->
            
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
    $(document).ready(function() {

        $('.update-payments').click(function(event) {
            var debt = $(this).attr('data-debt');
            var id   = $(this).attr('data-id');
            var month = $(this).attr('data-month');
            $.get('/admin/pagos-propietarios/update/'+id+'/'+month,{ debt: debt}, function(data) {
                $('.modal-body').empty().append(data);
            });
        });

        $('#fechas').change(function(event) {
            
            var month = $(this).val();
            window.location = '/admin/pagos-propietarios/'+month;
        });


    });
    window.onload = function () {
       var chart = new CanvasJS.Chart("chartContainer",
       {
          title:{
                  text: "Grafico  de pagos a propietarios"
                  },
          axisX: {
                  labelAngle: -90,
                  labelFontSize: 15,
                  },
          axisY: {
                  title: "Porcentaje",
                  labelFontSize: 15,
                  },
          dataPointWidth: 25,
           data: [
                    {
                      type: "stackedColumn100",
                      legendText: "Pagado",
                      showInLegend: "true",
                      indexLabel: "{y}",
                      indexLabelOrientation: "vertical",
                      indexLabelFontColor: "black",
                      color: "Green",
                      bevelEnabled: true,

                      dataPoints: [
                                   <?php foreach ($rooms as $room): ?>
                                      <?php if (isset($totalPayment[$room->id])): ?>
                                        {  y: <?php echo $totalPayment[$room->id] ?> , label: "<?php echo $room->nameRoom ?>"},
                                      <?php else: ?>
                                        {  y: 0 , label: "<?php echo $room->nameRoom ?>"},
                                      <?php endif ?>
                                     
                                   <?php endforeach ?>

                                  ]
                    },  
                    {
                      indexLabel: "#total",
                      legendText: "Deuda",
                      showInLegend: "true",
                      indexLabelPlacement: "outside", 
                      indexLabelOrientation: "vertical",
                      indexLabelFontColor: "black",
                      type: "stackedColumn100",
                      color:"LightCoral ",
                      dataPoints: [
                                    <?php foreach ($rooms as $room): ?>
                                      <?php if (isset($debt[$room->id])): ?>
                                        {  y: <?php echo $debt[$room->id] ?> , label: "<?php echo $room->nameRoom ?>"},
                                      <?php else: ?>
                                        {  y: 0 , label: "<?php echo $room->nameRoom ?>"},
                                      <?php endif ?>
                                    <?php endforeach ?>
                                  ]
                    }
                  ]
       });

       chart.render();
     }


  </script>


@endsection