@extends('layouts.admin-master')

@section('title') Precios de apartamentos @endsection

@section('headerButtoms')
    <li class="text-center">
        <button class="btn btn-sm btn-success new-prices" data-toggle="modal" data-target="#modal-prices">
            <i class="fa fa-plus"></i> Precios
        </button>
    </li>
    <li class="text-center">
        <button class="btn btn-sm btn-success new-special-prices" data-toggle="modal" data-target="#modal-prices">
            <i class="fa fa-plus"></i> Precio Especial
        </button>
    </li>
@endsection
    
@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-11 col-md-offset-1">
            <table class="table table-bordered table-striped js-dataTable-full table-header-bg">
                <thead>
                    <tr>
                        <th class ="text-center hidden">    id              </th>
                        <th class ="text-center">           Ocupacion       </th>
                        <th class ="text-center">           Temporada       </th>
                        <th class ="text-center">           Precio          </th>
                        <th class ="text-center">           Coste           </th>
                        <th class ="text-center">           Editar          </th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prices as $price): ?>
                        <tr>
                            <td class="text-center hidden"><?php echo $price->id?></td>
                            <td class="text-center">
                                <input class="form-control editables text-center occupation-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>"  type="text" name="<?php echo $price->occupation?>" value  ="<?php echo $price->occupation?>" disabled>
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center price-High-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>"  type="text" value  ="<?php echo $price->typeSeasons->name?>" disabled>
                            </td>
                             <td class="text-center">
                                <input class="form-control editables text-center price-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>" type="text" value  ="<?php echo $price->price?>">
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center cost-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>" type="text" value  ="<?php echo $price->cost?>">
                            </td>            
                            <td class="text-center">
                                <div class="btn-group">
                                     
                                    <a href="{{ url('/admin/precios/delete/')}}/<?php echo $price->id ?>" class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Usuario" onclick="return confirm('¿Quieres eliminar el Precio?');">
                                        <i class="fa fa-times"></i>
                                    </a>                                     
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
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

            $('.editables').change(function(event) {
                var id = $(this).attr('data-id');

                var price = $('.price-'+id).val();
                var cost  = $('.cost-'+id).val();

                $.get('/admin/precios/update/', {  id: id, price: price,cost: cost, }, function(data) {
                    alert(data);
                });
            });
        });
    </script>
@endsection