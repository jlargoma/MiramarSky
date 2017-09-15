@extends('layouts.admin-master')

@section('title') Precios de apartamentos @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

@endsection

@section('content')

<style>
    .Alta{
        background: #f0513c;
    }
    .Media{
        background-color: #127bbd;
    }
    .Baja{
        background-color: #91b85d;

    }
    .extras{
        background-color: rgb(150,150,150);
    }
    
</style>


<div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 text-center">
                <h2>Precios de Temporadas</h2>
            </div>
            <div class="clearfix"></div>
            <div class="tab-content">

                <div class="col-md-12 table-responsive">
                    <table class="table table-hover  table-responsive" >
                        <thead>
                            <tr>
                                <th class ="text-center bg-white text-complete" style="width: 1%" rowspan="2"> Ocupación  </th>
                                <?php foreach ($seasons as $key => $season): ?>
                                    <th class ="text-center bg-complete text-white <?php echo $season->name ?>" style="width: 20%" colspan="3"> <?php echo $season->name ?> </th>
                                <?php endforeach ?>
                            </tr>
                            <tr>                          
                                <?php foreach ($seasons as $key => $season): ?>
                                    <th class ="text-center bg-complete text-white <?php echo $season->name ?>" style="width: 10%" >PVP</th>
                                    <th class ="text-center bg-complete text-white <?php echo $season->name ?>" style="width: 10%">Cost</th>
                                    <th class ="text-center bg-complete text-white <?php echo $season->name ?>" style="width: 10%">% Ben</th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i=4; $i <= 8 ; $i++): ?>
                                <tr>
                                
                                    <td class ="text-center"> 
                                      <b><?php echo $i ?></b>
                                    </td>
                                    <?php foreach ($seasons as $key => $season): ?>
                                        <?php $price =  \App\Prices::where('occupation', $i)->where('season', $season->id )->first(); ?>
                                        <?php if ( count($price) > 0): ?>
                                                        <td class="text-center" style="border-left: 1px solid #48b0f7">
                                                            <input class="editable price-<?php echo $price->id?>" type="text" name="cost" data-id="<?php echo $price->id ?>" value="<?php echo $price->price ?>" style="width: 100%;text-align: center;border-style: none none">
                                                        </td>
                                                        <td class="text-center">
                                                            <input class="editable cost-<?php echo $price->id?>" type="text" name="cost" data-id="<?php echo $price->id ?>" value="<?php echo $price->cost ?>" style="width: 100%;text-align: center;border-style: none none">
                                                        </td>
                                                        <td class="text-center" style="border-right: : 1px solid #48b0f7">
                                                            <?php if ($price->price == 0 || $price->cost == 0): ?>
                                                                0%
                                                            <?php else: ?>
                                                                <?php $ben = ( ($price->price * 100) / $price->cost)-100; ?>
                                                                <?php echo number_format($ben, 2 , ',', '.') ?>%
                                                            <?php endif ?>
                                                        </td>
                                        <?php else: ?>
                                            <td style="border-left: 1px solid #48b0f7"></td>
                                            <td></td>
                                            <td style="border-right: : 1px solid #48b0f7"></td>
                                        <?php endif ?>
                                        
                                    <?php endforeach ?>
                                </tr>
                            <?php endfor?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 text-center">
                <h2>Precios de Extras</h2>
            </div>
            <div class="clearfix"></div>
            <div class="tab-content">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <table class="table table-hover  table-responsive-block" >
                            <thead>
                                <tr>
                                    <th class ="text-center extras text-white" style="width: 1%"> Nombre  </th>                          
                                    <th class ="text-center extras text-white" style="width: 5%" >PVP</th>
                                    <th class ="text-center extras text-white" style="width: 5%">Cost</th>
                                    <th class ="text-center extras text-white" style="width: 5%">% Ben</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($extras as $extra): ?>
                                    <tr>
                                        <td class="text-center" ><?php echo $extra->name ?></td>
                                        <td class="text-center" >
                                            <input class="extra-editable extra-price-<?php echo $extra->id?>" type="text" name="cost" data-id="<?php echo $extra->id ?>" value="<?php echo $extra->price ?>" style="width: 100%;text-align: center;border-style: none none">
                                        </td>
                                        <td class="text-center" >
                                            <input class="extra-editable extra-cost-<?php echo $extra->id?>" type="text" name="cost" data-id="<?php echo $extra->id ?>" value="<?php echo $extra->cost ?>" style="width: 100%;text-align: center;border-style: none none">
                                        </td>
                                        <td class="text-center" >
                                            <?php $ben = ( ($extra->price * 100) / $extra->cost)-100; ?>
                                            <?php echo number_format($ben, 2 , ',', '.') ?>%
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-3">
                        <div class="sm-m-l-5 sm-m-r-5">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <!-- <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
                                                Agregar Ocupacion
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                                        <div class="panel-body">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="panel-title col-md-12">Agregar Ocupacion
                                                    </div>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="col-md-12">
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
                                                                <select class="full-width" data-init-plugin="select2" name="season">
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
                                            </div>
                                        </div>
                                    </div>

                                </div> -->
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Extras
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false" style="height: 0px;">
                                        <div class="panel-body">
                                            <div class="panel panel-default">                                
                                                <form role="form"  action="{{ url('/admin/precios/createExtras') }}" method="post">
                                                    <div class="panel-body">
                                                        <div class="col-md-12">
                                                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                            <div class="input-group transparent">
                                                                <span class="input-group-addon">
                                                                    Nombre
                                                                </span>
                                                                <input type="text" class="form-control" name="name" placeholder="Nombre" required="" aria-required="true" aria-invalid="false">
                                                            </div>
                                                            <br>
                                                            <div class="input-group transparent">
                                                                <span class="input-group-addon">
                                                                    Precio
                                                                </span>
                                                                <input type="number" class="form-control" name="price" placeholder="Precio" required="" aria-required="true" aria-invalid="false">
                                                            </div>
                                                            <br>
                                                            <div class="input-group transparent">
                                                                <span class="input-group-addon">
                                                                    Coste
                                                                </span>
                                                                <input type="number" class="form-control" name="cost" placeholder="Coste" required="" aria-required="true" aria-invalid="false">
                                                            </div>
                                                            <br>
                                                            <div class="input-group">
                                                                <button class="btn btn-complete" type="submit">Guardar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
    


    <script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>

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
                    // alert(data);
                    window.location.reload();
                });

            });

            $('.extra-editable').change(function(event) {
                var id = $(this).attr('data-id');
                var extraprice = $('.extra-price-'+id).val();
                var extracost  = $('.extra-cost-'+id).val();

                $.get('precios/updateExtra', {  id: id, extraprice: extraprice,extracost: extracost}, function(data) {
                    // alert(data);
                    window.location.reload();
                });

            });
        });
    </script>
@endsection