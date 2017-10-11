<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css" />

    <script src="/assets/plugins/summernote/css/summernote.css"></script>

    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">

    <link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
    <style type="text/css">
    
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
        .bg-info-light>li>a{
            color: white;
        }
        .active.res{
            background-color: #295d9b !important; 
        }
        .active.bloq{
            background-color: orange !important; 
        }
        .active.pag{
            background-color: green !important; 
        }
        .res,.bloq,.pag{
            background-color: rgba(98,108,117,0.5);
        }
        .nav-tabs > li > a:hover, .nav-tabs > li > a:focus{
            color: white!important;
        }

        .fechas > li.active{
            background-color: rgb(81,81,81);
        }
        .nav-tabs ~ .tab-content{
            padding: 0px;
        }
        .paginate_button.active>a{
            color: black!important;
        }
        .table.table-hover tbody tr:hover td {
            background: #99bce7 !important;
        }

        .table.table-striped tbody tr.Reservado td select.minimal{
            background-color: rgba(0,200,10,0.0)  !important;
            color: black!important;
            font-weight: bold!important;
        }

            
        
        .table.table-striped tbody tr.Bloqueado td select.minimal{
            background-color: #D4E2FF  !important;
            color:red!important;
            font-weight: bold!important;

        }
        .nav-tabs-simple > li.active a{
            font-weight: 800;
        }
    </style>

@endsection
    
@section('content')
    <div class="container-fluid  p-l-15 p-r-15 p-t-20">
        <div class="row bg-white">
            <div class="col-md-12 text-center">
                <div class="col-md-3 col-md-offset-3 not-padding">
                    <h2><b>Planning de reservas</b>  Fechas:
                    </h2>
                </div>  
                <div class="col-md-1" style="padding: 15px 0px;">
                    <select id="fecha" class="form-control minimal">
                         <?php $fecha = $inicio->copy()->SubYear(2); ?>
                         <?php if ($fecha->copy()->format('Y') < 2015): ?>
                             <?php $fecha = new Carbon('first day of September 2015'); ?>
                         <?php endif ?>
                     
                         <?php for ($i=1; $i <= 3; $i++): ?>                           
                             <option value="<?php echo $fecha->copy()->format('Y'); ?>" {{ $date->copy()->format('Y') == $fecha->format('Y') ? 'selected' : '' }}>
                                 <?php echo $fecha->copy()->format('Y')."-".$fecha->copy()->addYear()->format('Y'); ?> 
                             </option>
                             <?php $fecha->addYear(); ?>
                         <?php endfor; ?>
                     </select>     
                </div>      
            </div>
            <div class="col-xs-12">
                <div class="col-md-6 not-padding">
                    <button class="btn btn-success btn-cons m-b-10" type="button" data-toggle="modal" data-target="#modalNewBook">
                        <i class="fa fa-plus-square" aria-hidden="true"></i> <span class="bold">Nueva Reserva</span>
                    </button>
                </div>
            </div>
            
    
            <!-- Planning reservas  -->
            <div class="col-md-7">
                
                <div class="col-md-12 col-xs-12 not-padding">
                    <div class="row">
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
                    </div>
                </div>
            </div>
            <!-- Planning reservas -->
            
            <div class="col-md-5">
                <!-- Seccion Calendario -->
                @include('backend.planning.calendar')
                <!-- Seccion Calendario -->


            </div>
        </div>
    </div>

    <form role="form">
        <div class="form-group form-group-default required" style="display: none">
            <label class="highlight">Message</label>
            <input type="text" hidden="" class="form-control notification-message" placeholder="Type your message here" value="This notification looks so perfect!" required>
        </div>
        <button class="btn btn-success show-notification hidden" id="boton">Show</button>
    </form>



    <div class="modal fade slide-up in" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content-wrapper">
                <div class="modal-content contestado" id="contentEmailing"></div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade slide-up in" id="modalNewBook" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content-wrapper">
                <div class="modal-content">
                    @include('backend.planning.listados._nuevas')
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('scripts')
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
    <script type="text/javascript" src="/assets/js/canvasjs.min.js"></script>
        

    <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
    <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>


    <script src="/assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
    <script type="text/javascript" src="/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
    <script src="/assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/moment/moment.min.js"></script>
    <script src="/assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
    <script src="/assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
    <script src="/assets/plugins/handlebars/handlebars-v4.0.5.js"></script>

    <script type="text/javascript" src="/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>

    <script src="/assets/js/notifications.js" type="text/javascript"></script>
    

        
    <script type="text/javascript">

        

        $(document).ready(function() {          

            $('.status,.room').change(function(event) {
                var id = $(this).attr('data-id');
                var clase = $(this).attr('class');
                
                if (clase == 'status form-control minimal') {
                    var status = $(this).val();
                    var room = "";
                }else if(clase == 'room form-control minimal'){
                    var room = $(this).val();
                    var status = "";
                }



                if (status == 5) {
                    $('#myModal').modal({
                        show: 'false'
                    }); 
                    $.get('/admin/reservas/ansbyemail/'+id, function(data) {
                       $('.modal-content.contestado').empty().append(data);
                   });
                }else{
                    
                   $.get('/admin/reservas/changeBook/'+id, {status:status,room: room}, function(data) {
                        $('.notification-message').val(data);
                        document.getElementById("boton").click();
                        if (data == "Ya hay una reserva para ese apartamento" || data == "No se puede cambiar el estado" || data == "Valor nulo o vacio" || data == "No tiene Email asignado") {
                            setTimeout(function(){ 
                                $('.pgn-wrapper .pgn .alert .close').trigger('click');
                                 }, 1000);
                            setTimeout('document.location.reload()',1000);
                        }else{
                            setTimeout('document.location.reload()',1000);
                        }                        
                   }); 
                }
                
            });

            $('#fecha').change(function(event) {
                
                var year = $(this).val();
                window.location = '/admin/reservas/'+year;
            });
            
               
        });
    </script>

    

@endsection