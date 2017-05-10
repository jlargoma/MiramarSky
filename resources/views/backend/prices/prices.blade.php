@extends('layouts.admin-master')

@section('title') Precios de apartamentos @endsection

@section('headerButtoms')
    <li class="text-center">
        <button class="btn btn-sm btn-success new-prices" data-toggle="modal" data-target="#modal-prices">
            <i class="fa fa-plus"></i> Precios
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
                        <th class ="text-center">           Nombre          </th>
                        <th class ="text-center">           Ocupacion       </th>
                        <th class ="text-center">           Temp. Alta      </th>
                        <th class ="text-center">           Temp. Media     </th>
                        <th class ="text-center">           Temp. Baja      </th>
                        <th class ="text-center">           Cost. Alta      </th>
                        <th class ="text-center">           Cost. Media     </th>
                        <th class ="text-center">           Cost. Baja      </th>
                        <th class ="text-center">           Editar          </th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prices as $price): ?>
                        <tr>
                            <td class="text-center hidden"><?php echo $price->id?></td>
                            <td class="text-center">
                                <input class="form-control editables text-center name-price-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>"  type="text" name="<?php echo $price->name?>" value  ="<?php echo $price->name?>">
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center occupation-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>"  type="text" name="<?php echo $price->occupation?>" value  ="<?php echo $price->occupation?>">
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center price-High-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>"  type="text" name="<?php echo $price->priceHigh?>" value  ="<?php echo $price->priceHigh?>">
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center price-Med-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>"  type="text" name="<?php echo $price->priceMed?>" value  ="<?php echo $price->priceMed?>">
                            </td>    
                            <td class="text-center">
                                <input class="form-control editables text-center price-Low-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>"  type="text" name="<?php echo $price->priceLow?>" value  ="<?php echo $price->priceLow?>">
                            </td>  
                            <td class="text-center">
                                <input class="form-control editables text-center cost-High-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>"  type="text" name="<?php echo $price->costHigh?>" value  ="<?php echo $price->costHigh?>">
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center cost-Med-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>"  type="text" name="<?php echo $price->costMed?>" value  ="<?php echo $price->costMed?>">
                            </td>    
                            <td class="text-center">
                                <input class="form-control editables text-center cost-Low-<?php echo $price->id?>"  data-id="<?php echo $price->id; ?>"  type="text" name="<?php echo $price->costLow?>" value  ="<?php echo $price->costLow?>">
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


            $('.editables').change(function(event) {
                var id = $(this).attr('data-id');

                var name = $('.name-price-'+id).val();
                var occupation = $('.occupation-'+id).val();
                var priceHigh = $('.price-High-'+id).val();
                var priceMed  = $('.price-Med-'+id).val();
                var priceLow  = $('.price-Low-'+id).val();
                var costHigh  = $('.cost-High-'+id).val();
                var costMed   = $('.cost-Med-'+id).val();
                var costLow   = $('.cost-Low-'+id).val();

                $.get('/admin/precios/update/', {  id: id, name: name,occupation: occupation, priceHigh: priceHigh, priceMed: priceMed, priceLow: priceLow, costHigh: costHigh, costMed: costMed, costLow: costLow}, function(data) {
                    alert(data);
                });
            });
        });
    </script>
@endsection