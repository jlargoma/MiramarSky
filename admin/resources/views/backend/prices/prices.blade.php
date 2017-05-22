@extends('layouts.admin-master')

@section('title') Precios de apartamentos @endsection

@section('externalScripts') 

    <link href="assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

@endsection

@section('content')


<div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
        <div class="col-md-12">

            <div class="clearfix"></div>
            <table class="table table-hover  table-responsive-block" >
                <thead>
                    <tr>
                        <th class ="text-center bg-complete text-white"> Ocupación  </th>
                        <?php foreach ($seasons as $key => $season): ?>
                            <th class ="text-center bg-complete text-white"> <?php echo $season->name ?> </th>
                        <?php endforeach ?>
                    </tr>
                </thead>
                <tbody>
                  <?php foreach ($countOccupations as $key => $occupation): ?>
                        <tr>
                            <td class ="text-center"> 
                              <b><?php echo $occupation->minOcu ?></b>
                            </td>
                            <?php foreach ($seasons as $key => $season): ?>
                                <?php $price =  \App\Prices::where('occupation', $occupation->minOcu)->where('season', $season->id )->first(); ?>
                                <?php if ( count($price) > 0): ?>
                                    <td class ="text-center" style="padding-top:0 ">
                                        <table class="table bordered">
                                            <tr>
                                                <th class="text-center bg-complete text-white">
                                                    CTE
                                                </th>
                                                <th class="text-center bg-complete text-white">
                                                    PVP
                                                </th>
                                                <th class="text-center bg-complete text-white">
                                                    % BEN
                                                </th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <?php echo $price->cost ?>€
                                                </td>
                                                <td class="text-center">
                                                    <?php echo $price->price ?>€
                                                </td>
                                                <td class="text-center">
                                                    <?php $ben = ( ($price->price * 100) / $price->cost)-100; ?>
                                                    <?php echo number_format($ben, 2 , ',', '.') ?>%
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                <?php else: ?>
                                    <td class ="text-center" style="padding-top:0 ">
                                        <table class="table bordered">
                                            <tr>
                                                <th class="text-center bg-complete text-white">
                                                    Coste
                                                </th>
                                                <th class="text-center bg-complete text-white">
                                                    PVP
                                                </th>
                                                <th class="text-center bg-complete text-white">
                                                    % BEN
                                                </th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <input class="editable" type="number" id="cost-<?php echo $key ?>" name="cost">
                                                </td>
                                                <td class="text-center">
                                                    <input class="editable" type="number" id="price-<?php echo $key ?>" name="price">
                                                </td>
                                                <td class="text-center">                                                    
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                <?php endif ?>
                                
                            <?php endforeach ?>
                        </tr>
                  <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="quickview-wrapper  builder hidden-sm hidden-xs" id="builder">
    <div class="p-l-30 p-r-30 ">
        <a class="builder-close quickview-toggle pg-close" data-toggle="quickview" data-toggle-element="#builder" href="#"></a>
        <a class="builder-toggle" data-toggle="quickview" data-toggle-element="#builder"><i class="pg pg-theme"></i></a>
        <ul class="nav nav-tabs nav-tabs-simple nav-tabs-primary m-t-10">
            <li class="active">
                <a data-toggle="tab" href="#tabLayouts">Agregar tarifa</a>
            </li>
            <li>
                <a data-toggle="tab" href="#tabThemes">Colors</a>
            </li>
        </ul>
        <div class="tab-content m-b-30 p-l-0">
            <div class="tab-pane active " id="tabLayouts">
                <div class="scroll-wrapper scrollable" style="position: relative;">
                    <div class="scrollable scroll-content" style="height: 891px; margin-bottom: 0px; margin-right: 0px; max-height: none;">
                    <div class="p-l-10 p-r-50">
                        <div class="container-fluid container-fixed-lg">
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- START PANEL -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Agregar Usuario
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <form role="form"  action="{{ url('usuarios/create') }}" method="post">
                                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                <div class="input-group transparent">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="name" placeholder="Nombre" required="" aria-required="true" aria-invalid="false">
                                                </div>
                                                    <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="pg-plus_circle"></i>
                                                    </span>
                                                    <select class="full-width" data-init-plugin="select2" name="role">
                                                        <option></option>
                                                        <option value="Admin">Admin</option>
                                                        <option value="Jaime">Jaime</option>
                                                        <option value="Limpieza">Limpieza</option>
                                                        <option value="Agente">Agente</option>
                                                        <option value="Propietario">Propietario</option>
                                                    </select>
                                                </div>
                                                    <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-key"></i>
                                                    </span>
                                                        <input type="password" class="form-control" name="password"  required="" aria-required="true" aria-invalid="false">
                                                </div>
                                                    <br>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="pg-mail"></i>
                                                    </span>
                                                        <input type="email" class="form-control" name="email" placeholder="Email" required="" aria-required="true" aria-invalid="false">
                                                </div>
                                                    <br>
                                                <div class="input-group">
                                                    <button class="btn btn-complete" type="submit">Guardar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- END PANEL -->
                                </div>
                                    <!-- END PANEL -->      
                            </div>
                        </div>
                    </div>
                                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-prices" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                </div>
                <div class="row block-content" id="content-prices">

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

    <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('.new-prices').click(function(event) {
                $.get('/admin/precios/new', function(data) {
                    $('#content-prices').empty().append(data);
                });
            });
            $('.new-special-prices').click(function(event) {
                $.get('/admin/precios/newSpecial', function(data) {
                    $('#content-prices').empty().append(data);
                });
            });

            $('.editable').change(function(event) {
                var id = $(this).attr('id');
                console.log(id);
                // var price = $('.price-'+id).val();
                // var cost  = $('.cost-'+id).val();

                // $.get('/admin/precios/update/', {  id: id, price: price,cost: cost, }, function(data) {
                //     alert(data);
                // });
            });
        });
    </script>
@endsection