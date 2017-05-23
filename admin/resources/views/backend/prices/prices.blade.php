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
        <div class="col-md-9">

            <div class="clearfix"></div>
            <table class="table table-hover  table-responsive-block" >
                <thead>
                    <tr>
                        <th class ="text-center bg-complete text-white" style="width: 1%"> Ocupación  </th>
                        <?php foreach ($seasons as $key => $season): ?>
                            <th class ="text-center bg-complete text-white" style="width: 10%"> <?php echo $season->name ?> </th>
                        <?php endforeach ?>
                    </tr>
                </thead>
                <tbody>
                  <?php foreach ($countOccupations as $key => $occupation): ?>
                        <?php for ($i=4; $i <=8 ; $i++): ?>
                            <tr>
                            
                                <td class ="text-center"> 
                                  <b><?php echo $i ?></b>
                                </td>
                                <?php foreach ($seasons as $key => $season): ?>
                                    <?php $price =  \App\Prices::where('occupation', $i)->where('season', $season->id )->first(); ?>
                                    <?php if ( count($price) > 0): ?>
                                        <td class ="text-center" style="padding-top:0 ">
                                            <table class="table bordered">
                                                <tr>
                                                    <th class="text-center bg-complete text-white">
                                                        PVP
                                                    </th>
                                                    <th class="text-center bg-complete text-white">
                                                        Cost
                                                    </th>
                                                    <th class="text-center bg-complete text-white">
                                                        % BEN
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        <input class="editable price-<?php echo $price->id?>" type="text" name="cost" data-id="<?php echo $price->id ?>" value="<?php echo $price->price ?>" style="width: 100%;text-align: right;">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="editable cost-<?php echo $price->id?>" type="text" name="cost" data-id="<?php echo $price->id ?>" value="<?php echo $price->cost ?>" style="width: 100%;text-align: right;">
                                                    </td>
                                                    <td class="text-center">
                                                        <?php $ben = ( ($price->price * 100) / $price->cost)-100; ?>
                                                        <?php echo number_format($ben, 2 , ',', '.') ?>%
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    <?php else: ?>
                                    <?php endif ?>
                                    
                                <?php endforeach ?>
                            </tr>
                        <?php endfor?>    
                  <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
            <div class="container-fluid container-fixed-lg">
                <div class="row">
                    <div class="col-md-3">
                        <!-- START PANEL -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title">Agregar Ocupacion
                                </div>
                            </div>
                            <div class="panel-body">
                                <form role="form"  action="{{ url('precios/create') }}" method="post">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    <div class="input-group transparent">
                                        <span class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </span>
                                        <input type="number" class="form-control" name="occupation" placeholder="Ocupacion" required="" aria-required="true" aria-invalid="false">
                                    </div>
                                        <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="pg-plus_circle"></i>
                                        </span>
                                        <select class="full-width" data-init-plugin="select2" name="seasson">
                                            <option></option>
                                            <?php foreach ($newseasons as $newseason): ?>
                                                <option value="<?php echo $newseason->id ?>"><?php echo $newseason->name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                        <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-key"></i>
                                        </span>
                                            <input type="number" class="form-control" name="price" placeholder="Precio" required="" aria-required="true" aria-invalid="false">
                                    </div>
                                        <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="pg-mail"></i>
                                        </span>
                                            <input type="number" class="form-control" name="cost" placeholder="Coste" required="" aria-required="true" aria-invalid="false">
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
                var id = $(this).attr('data-id');               
                var price = $('.price-'+id).val();
                var cost  = $('.cost-'+id).val();

                $.get('precios/update', {  id: id, price: price,cost: cost}, function(data) {
                    alert(data);
                });
            });
        });
    </script>
@endsection