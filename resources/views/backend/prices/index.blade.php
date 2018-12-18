@extends('layouts.admin-master')

@section('title') Precios de apartamentos @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css"/>
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
        color: white;

    }

    span.Alta{
        background-color: transparent!important;
        color: #f0513c;
        text-transform: uppercase;
    }
    span.Media{
        background-color: transparent!important;
        color: #127bbd;
        text-transform: uppercase;
    }
    span.Baja{
        background-color: transparent!important;
        color: #91b85d;
        text-transform: uppercase;
    }
    span.Premium{
        background-color: transparent!important;
        color: #ff00b1;
        text-transform: uppercase;
    }
    .extras{
        background-color: rgb(150,150,150);
    }
    
</style>


<div class="container-fluid padding-25 sm-padding-10">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 text-center">
                <?php $year = $date->copy();?>
                <h2 class="font-w800">
                    Precios de Temporadas <?php echo $year->copy()->format('Y') ?> -  <?php echo $year->copy()
                    ->addYear()->format('Y') ?>
                </h2>
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
                                            <?php if ( $price): ?>
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
                                                        <?php $ben = ( 1 - ($price->cost/$price->price) ) * 100; ?>
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
        </div>
        
        
    </div>


    <div class="row">
        <div class="col-md-12 text-center">
            <h2 class="font-w800">Temporadas</h2>
        </div>
        <div class="col-md-5">
            <div class="col-xs-12 push-10">
                <button class="btn btn-complete" style="float:right;" type="button" data-toggle="modal"
                        data-target="#typeSeason">
                    <i class="fa fa-plus"></i> Tipo Temporada
                </button>

                <button class="btn btn-primary" style="float:right; margin: 0 5px" type="button" data-toggle="modal"
                        data-target="#season">
                    <i class="fa fa-plus"></i> Temporada
                </button>
            </div>
            <table class="table table-hover  table-condensed table-striped" >
                <thead>
                    <tr>
                        <th class ="text-center hidden">id</th>
                        <th class ="text-center bg-complete text-white">Tipo</th>
                        <th class ="text-center bg-complete text-white">Inicio</th>
                        <th class ="text-center bg-complete text-white">Fin</th>
                        <th class ="text-center bg-complete text-white">Accion</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($seasonsTemp as $season): ?>
                        <tr>
                            <td class="text-center" style="padding: 10px!important">
                               <span class="<?php echo $season->typeSeasons->name ?> font-w600"> <?php echo $season->typeSeasons->name ?></span>
                            </td>
                            <td class="text-center" hidden style="padding: 10px!important"><?php echo $season->id ?></td>
                            <td class="text-center" style="padding: 10px!important">
                                <?php  echo date('d-M-Y',strtotime($season->start_date))?>
                            </td>
                            <td class="text-center" style="padding: 10px!important">
                                <?php echo date('d-M-Y',strtotime($season->finish_date)) ?>
                            </td>

                            <td class="text-center" style="padding: 10px!important">
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-sm updateSeason" type="button"
                                            data-toggle="modal" data-target="#season"
                                            data-id="<?php echo $season->id ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <a href="{{ url('/admin/temporadas/delete/')}}/<?php echo $season->id ?>" class="btn  btn-danger btn-sm" onclick="return confirm('¿Quieres eliminar la ' +
                                     'temporada?');">
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
        </div>
        
    </div>
</div>

<div class="modal fade slide-up in" id="season" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="block">
                    <div class="block-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="pg-close fs-14"
                                    style="font-size: 40px!important;color: black!important"></i>
                        </button>
                        <h2 class="text-center">
                            Temporada
                        </h2>
                    </div>
                    <div class="block block-content" id="contentSeason" style="padding:20px">
                        @include('backend.seasons.create')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade slide-up in" id="typeSeason" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="block">
                    <div class="block-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="pg-close fs-14"
                                    style="font-size: 40px!important;color: black!important"></i>
                        </button>
                        <h2 class="text-center">
                            Tipos de Temporada
                        </h2>
                    </div>
                    <div class="block block-content" style="padding:20px">
                        @include('backend.seasons.typesSeason.create')
                    </div>
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
          $('.updateSeason').click(function (event) {
            var id = $(this).attr('data-id');
            $.get('/admin/temporadas/update/' + id, function (data) {
              $('#contentSeason').empty().append(data);
            });
          });
        });
    </script>
@endsection