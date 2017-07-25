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

    <style type="text/css">
        
        /* Estados */
          .Reservado{
              background-color: green !important;
              color: black;
          }
          .Pagada-la-señal{
              background-color: red  !important;
              color: black;
          }
          .Bloqueado{
              background-color: orange !important;
              color: black;
          }
          .SubComunidad{
              background-color: rgba(138,125,190,1) !important;
              color: black;
          }
        /* Estados */

        .botones{
            padding-top: 0px!important;
            padding-bottom: 0px!important;
        }
        td{
            margin: 0px;
            padding: 0px!important;
            vertical-align: middle!important;
        }
        a {
            color: black;
            cursor: pointer;
        }

        .S, .D{
            background-color: rgba(0,0,0,0.2);
            color: red;
        }
        .active>a{
            color: white!important;
        }
        .bg-info-light{
          background-color: white!important
        }
        .bg-info-light>li>a{
            color: black!important;
        }
        .active.res{
            background-color: green !important; 
        }
        .active.bloq{
            background-color: orange !important; 
        }
        .active.pag{
            background-color: red !important; 
        }
        .res,.bloq,.pag{
            background-color: white;
        }
        .nav-tabs > li > a:hover, .nav-tabs > li > a:focus{
            color: black!important;
        }
        .row{margin: -2px!important;}
        .rev.nav-tabs-simple > li a, .rev.nav-tabs-simple > li a:hover, .rev.nav-tabs-simple > li a:focus {padding:5px!important; }
       
        /* Bordes de seccion */          
          .panel.resv,.panel.in-out{border: 5px solid blue;}
          .active.resv,.active.cob{border: 5px solid blue;}
        /* Bordes de seccion */

    </style>

@section('content')

    <div class="container-fluid container-fixed-lg">
      <div class="row">
        <div class="panel" style="margin-bottom: 0px!important">
          <ul class="nav nav-tabs nav-tabs-simple bg-info-light " role="tablist" data-init-reponsive-tabs="collapse">
              <li class="active resv" >
                  <a href="#reservas" data-toggle="tab" role="tab" style="font-size: 11px!important"> RESERVAS </a>
              </li>
              <li class="cob">
                  <a href="#cobros" data-toggle="tab" role="tab" style="font-size: 11px!important"> COBROS </a>
              </li>
              <li>
                <a href="#calendario"> <i class="fa fa-calendar " aria-hidden="true" style="font-size: 15px!important"></i> </a>
              </li>
              <li style="background-color: white">
                <a href="#tabNueva" data-toggle="tab" role="tab" >
                  <i class="fa fa-plus-circle " style="color:green;font-size: 15px!important" aria-hidden="true"></i>
                </a>
              </li>
          </ul>
        </div>
        <div class="tab-content ">
          <div class="tab-pane active" id="reservas">
              <div class="row column-seperation ">
                  <div class="panel resv">
                    <ul class="nav nav-tabs nav-tabs-simple bg-info-light rev" role="tablist" data-init-reponsive-tabs="collapse">
                        <li class="active res" >
                            <a href="#tabPendientes" data-toggle="tab" role="tab" style="font-size: 11px;">Pendientes 
                                <span class="badge"><?php echo count($arrayBooks["nuevas"]) ?></span>
                            </a>
                        </li>
                        <li class="bloq">
                            <a href="#tabEspeciales" data-toggle="tab" role="tab" style="font-size: 11px;">Especiales
                                <span class="badge"><?php echo count($arrayBooks["especiales"]) ?></span>
                            </a>
                        </li>
                        <li class="pag">
                            <a href="#tabPagadas" data-toggle="tab" role="tab" style="font-size: 11px;">Confirmadas 
                                <span class="badge"><?php echo count($arrayBooks["pagadas"]) ?></span>
                            </a>
                        </li>
                    </ul>
                  </div>
                  <div class="tab-content ">
                    <div class="tab-pane active table-responsive" id="tabPendientes">
                        <div class="container column-seperation ">
                           
                        </div>
                    </div>
                    <div class="tab-pane table-responsive" id="tabEspeciales">
                        <div class="container column-seperation ">
                          <div>
                            Prueba b
                          </div>
                        </div>
                    </div>
                    <div class="tab-pane table-responsive" id="tabPagadas">
                        <div class="container column-seperation ">
                          <div>
                            Prueba C
                          </div>
                        </div>
                    </div>
                  </div>
              </div>
          </div>

          <div class="tab-pane" id="cobros">
              <div class="row column-seperation">
                  <div class="panel in-out">
                    <ul class="nav nav-tabs nav-tabs-simple bg-info-light rev" role="tablist" data-init-reponsive-tabs="collapse">
                        <li class="active in text-center res" style="width: 50%">
                            <a href="#tabIn" data-toggle="tab" role="tab" style="font-size: 11px;">IN
                            </a>
                        </li>
                        <li class="out text-center pag"  style="width: 50%">
                            <a href="#tabOut" data-toggle="tab" role="tab" style="font-size: 11px;">OUT
                            </a>
                        </li>
                    </ul>
                  </div>
                  <div class="tab-content">
                    <div class="tab-pane active table-responsive" id="reservas">
                      
                    </div>
                  </div>
              </div>
          </div>

        </div>
        <div id="calendario" style="border-top: 5px solid black">
          <div class="container">
            <div class="panel">
                <ul class="nav nav-tabs nav-tabs-simple bg-info-light" role="tablist" data-init-reponsive-tabs="collapse">
                    <?php $dateAux = $inicio->copy(); ?>
                    <?php for ($i=1; $i <= 5 ; $i++) :?>
                        <li <?php if($i == 1 ){ echo "class='active'";} ?> style="width:20%!important">
                            <a href="#tab<?php echo $i?>" data-toggle="tab" role="tab" style="padding:5px;font-size: 17px;">
                                <?php echo ucfirst($dateAux->copy()->formatLocalized('%b'))?>
                            </a>
                        </li>
                        <?php $dateAux->addMonth(); ?>
                    <?php endfor; ?>
                </ul>
                <div class="tab-content">
                    
                    <?php for ($z=1; $z <= 4; $z++):?>
                    <div class="table-responsive tab-pane <?php if($z == 1){ echo 'active';} ?>" id="tab<?php echo $z ?>" style="padding-bottom: 10px">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="fc-border-separate" style="width: 100%">
                                   <thead>
                                        <tr >
                                            <td class="text-center" colspan="<?php echo $arrayMonths[$inicio->copy()->format('n')]+1 ?>">
                                                <?php echo  ucfirst($inicio->copy()->formatLocalized('%B %Y'))?>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <td rowspan="2" style="width: 1%!important"></td>
                                                <?php for ($i=1; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 
                                                    <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center">
                                                        <?php echo $i?> 
                                                    </td> 
                                                 <?php endfor; ?>
                                        </tr>
                                        <tr>
                                            
                                            <?php for ($i=1; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 
                                                <td style='border:1px solid black;width: 3%;font-size: 10px' class="text-center <?php echo $days[$inicio->copy()->format('n')][$i]?>">
                                                    <?php echo $days[$inicio->copy()->format('n')][$i]?> 
                                                </td> 
                                             <?php endfor; ?> 
                                        </tr>
                                   </thead>
                                   <tbody>
                                   
                                        <?php foreach ($roomscalendar as $room): ?>
                                            <tr>
                                                <?php $date = $inicio->startOfMonth() ?>
                                                <td class="text-center"><b><?php echo substr($room->nameRoom, 0,5)?></b></td>
                                                    
                                                <?php for ($i=01; $i <= $arrayMonths[$inicio->copy()->format('n')] ; $i++): ?> 

                                                        <?php if (isset($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i])): ?>
                                                            <?php if ($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->start == $inicio->copy()->format('Y-m-d')): ?>
                                                                    <td style='border:1px solid grey;width: 3%'>
                                                                        <div style="width: 50%;float: left;">
                                                                            &nbsp;
                                                                        </div>
                                                                        <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->type_book) ?> start" style="width: 50%;float: left;">
                                                                            &nbsp;
                                                                        </div>

                                                                    </td>    
                                                            <?php elseif($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->finish == $inicio->copy()->format('Y-m-d')): ?>
                                                                    <td style='border:1px solid grey;width: 3%'>
                                                                        <div class="<?php echo $book->getStatus($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->type_book) ?> end" style="width: 50%;float: left;">
                                                                            &nbsp;
                                                                        </div>
                                                                        <div style="width: 50%;float: left;">
                                                                            &nbsp;
                                                                        </div>
                                                                        

                                                                    </td>
                                                            <?php else: ?>
                                                                
                                                                    <td style='border:1px solid grey;width: 3%' title="<?php echo $arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->customer['name'] ?>" class="<?php echo $book->getStatus($arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->type_book) ?>">

                                                                   <a href="{{url ('/admin/reservas/update')}}/<?php echo $arrayReservas[$room->id][$inicio->copy()->format('Y')][$inicio->copy()->format('n')][$i]->id ?>">
                                                                       <div style="width: 100%;height: 100%">
                                                                           &nbsp;
                                                                       </div>
                                                                   </a>

                                                                </td>

                                                            <?php endif ?>
                                                        <?php else: ?>
                                                            <td class="<?php echo $days[$inicio->copy()->format('n')][$i]?>" style='border:1px solid grey;width: 3%'>
                                                                
                                                            </td>
                                                        <?php endif; ?>
                                                        <?php if ($inicio->copy()->format('d') != $arrayMonths[$inicio->copy()->format('n')]): ?>
                                                            <?php $date = $inicio->addDay(); ?>
                                                        <?php else: ?>
                                                            <?php $date = $inicio->startOfMonth() ?>
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
                  
        $(".cob").click(function(){
            $('#calendario').hide();
        });

        $(".resv").click(function(){
            $('#calendario').show();
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