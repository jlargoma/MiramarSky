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

    .Premium{
        background-color: #ff00b1;

    }
    .extras{
        background-color: rgb(150,150,150);
    }
    
</style>


<div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 text-center">
                <h2 class="font-w800">Precios de Temporadas</h2>
            </div>
            <div class="clearfix"></div>
            <div class="tab-content">

                <div class="col-md-12 table-responsive">
                    <table class="table table-hover  table-responsive" >
                        <thead>
                            <tr>
                                <th class ="text-center bg-white text-complete" style="width: 1%" rowspan="2"> Ocupación  </th>
                                <?php foreach ($seasons as $key => $season): ?>
                                    <th class ="text-center bg-complete text-white <?php echo $season->name ?>" style="width: 25%" colspan="3"> <?php echo $season->name ?> </th>
                                <?php endforeach ?>
                            </tr>
                            <tr>                          
                                <?php foreach ($seasons as $key => $season): ?>
                                    <th class ="text-center bg-complete text-white <?php echo $season->name ?>" style="width: 5%" >PVP</th>
                                    <th class ="text-center bg-complete text-white <?php echo $season->name ?>" style="width: 5%">Cost</th>
                                    <th class ="text-center bg-complete text-white <?php echo $season->name ?>" style="width: 5%">% Ben</th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i=4; $i <= 14 ; $i++): ?>
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
                    <div class="col-md-3">
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
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
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
                     <div class="col-md-3">
                         
                        
                        <table class="table table-hover  table-responsive" >
                            <thead>
                                <tr>
                                    <th class ="text-center bg-complete text-white" style="width: 1%" colspan="2"> Condiciones cobro link stripe  
                                    </th>
                                </tr>
                                <tr>
                                                                    
                                    <th class ="text-center bg-complete text-white" style="width: 1%" rowspan="2"> PORCENTAJE  </th>
                                    <th class ="text-center bg-complete text-white" style="width: 1%" rowspan="2"> > DIAS  </th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (\App\RulesStripe::all() as $key => $rule): ?>
                                    <tr>
                                        <td class="text-center" style="border-left: 1px solid #48b0f7">
                                            <input class="rules percent-<?php echo $rule->id?>" type="text" name="cost" data-id="<?php echo $rule->id ?>" value="<?php echo $rule->percent ?>" style="width: 100%;text-align: center;">
                                        </td>
                                        <td class="text-center">
                                            <input class="rules days-<?php echo $rule->id?>" type="text" name="cost" data-id="<?php echo $rule->id ?>" value="<?php echo $rule->numDays ?>" style="width: 100%;text-align: center;">
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>


                     </div>
                    <div class="col-md-3">


                        <table class="table table-hover  table-responsive" >
                            <thead>
                            <tr>
                                <th class ="text-center bg-complete text-white" style="width: 1%" colspan="2"> Dias del segundo pago
                                </th>
                            </tr>
                            <tr>

                                <th class ="text-center bg-complete text-white" style="width: 1%" rowspan="2"> DIAS  </th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (\App\DaysSecondPay::all() as $key => $day): ?>
                            <tr>
                                <td class="text-center" style="border-left: 1px solid #48b0f7">
                                    <input class="daysSecondPayment" type="number" name="days" data-id="<?php echo $day->id ?>" value="<?php echo $day->days ?>" style="width: 100%;text-align: center;">
                                </td>
                            </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>


                    </div>
                </div>

            </div>
        </div>
        
        
    </div>


    <div class="row">
        <div class="col-md-12 text-center">
            <h2 class="font-w800">Temporadas</h2>
        </div>
        <div class="col-md-5">
                <table class="table table-hover  table-condensed table-striped" >
                    <thead>
                        <tr>
                            <th class ="text-center hidden">    id      </th>
                            <th class ="text-center bg-complete text-white"> Nombre    </th> 
                            <th class ="text-center bg-complete text-white"> Inicio  </th>
                            <th class ="text-center bg-complete text-white"> Fin     </th>
                                             
                            <th class ="text-center bg-complete text-white"> Eliminar  </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($seasonsTemp as $season): ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $season->typeSeasons->name ?>
                                </td>  
                                <td class="text-center" hidden><?php echo $season->id ?></td>
                                <td class="text-center">
                                    <?php  echo date('d-M-Y',strtotime($season->start_date))?>
                                </td>
                                <td class="text-center">
                                    <?php echo date('d-M-Y',strtotime($season->finish_date)) ?>
                                </td>
                                            
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ url('/admin/temporadas/delete/')}}/<?php echo $season->id ?>" class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Temporada" onclick="return confirm('¿Quieres eliminar la temporada?');">
                                            <i class="fa fa-trash"></i>
                                        </a>                                     
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
        </div>
        <div class="col-md-7">
            @include('backend.seasons.calendar')
            <div class="row">

                <div class="col-md-4">
                    <div class="sm-m-l-5 sm-m-r-5">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
                                            Tipo de temporada
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title col-md-6">Agregar Tipo Temporada
                                        </div>
                                        <div class="panel-title col-md-6">Temporada
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-6">
                                            <form role="form"  action="{{ url('/admin/temporadas/create-type') }}" method="post">
                                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                <div class="input-group transparent">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-user"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="name" placeholder="nombre" required="" aria-required="true" aria-invalid="false">
                                                </div>
                                                    <br>
                                                <div class="input-group">
                                                    <button class="btn btn-complete" type="submit">Guardar</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                        <?php foreach ($newtypeSeasonsTemp as $newtypeSeason): ?>
                                            <div>
                                                <?php echo $newtypeSeason->name ?>
                                            </div>
                                        <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sm-m-l-5 sm-m-r-5">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Temporada
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="panel-title col-md-6">Agregar Temporada
                                                </div>
                                                <div class="panel-title col-md-6">Temporada
                                                </div>
                                            </div>
                                            <form role="form"  action="{{ url('/admin/temporadas/create') }}" method="post">
                                                <div class="panel-body">
                                                    <div class="col-md-12">
                                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                        <div class="input-group transparent">
                                                            <span class="input-group-addon">
                                                                <i class="pg-plus_circle"></i>
                                                            </span>
                                                            <select class="full-width" data-init-plugin="select2" name="type">
                                                                <?php foreach ($typeSeasonsTemp as $typeSeason): ?>
                                                                     <option value="<?php echo $typeSeason->id ?>"><?php echo $typeSeason->name ?></option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>
                                                        <br>
                                                        <div class="input-daterange input-group" id="datepicker-range">

                                                            <input id="start" type="text" class="input-sm form-control" name="start" data-date-format="dd-mm-yyyy">
                                                            <span class="input-group-addon">Hasta</span>
                                                            <input id="finish" type="text" class="input-sm form-control" name="finish" data-date-format="dd-mm-yyyy">
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

            $('.rules').change(function(event) {
                var id = $(this).attr('data-id');               
                var percent = $('.percent-'+id).val();
                var numDays  = $('.days-'+id).val();

                $.get('/admin/rules/stripe/update', {  id: id, percent: percent,numDays: numDays}, function(data) {
                    // alert(data);
                    window.location.reload();
                });

            });

            $('.daysSecondPayment').change(function(event) {
                var id = $(this).attr('data-id');
                var numDays  = $(this).val();

                $.get('/admin/days/secondPay/update/'+id+'/'+numDays, function(data) {
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