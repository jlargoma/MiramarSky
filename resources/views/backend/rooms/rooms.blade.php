@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('headerButtoms')
    <li class="text-center">
        <button class="btn btn-sm btn-success new-room" data-toggle="modal" data-target="#modal-room">
            <i class="fa fa-plus"></i> Apartamento
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
                        <th class ="text-center hidden">    id          </th>
                        <th class ="text-center">           Nombre      </th>
                        <th class ="text-center">           Tipo        </th>
                        <th class ="text-center">           Propietario </th>
                        <th class ="text-center">           Temp. Alta  </th>
                        <th class ="text-center">           Temp. Media </th>
                        <th class ="text-center">           Temp. Baja  </th>
                        <th class ="text-center">           Cost. Alta  </th>
                        <th class ="text-center">           Cost. Med   </th>
                        <th class ="text-center">           Cost. Baja  </th>
                        <th class ="text-center">           Editar      </th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $room): ?>
                        <tr>
                            <td class="text-center hidden"><?php echo $room->id?></td>
                            <td class="text-center">
                                <input class="form-control editables text-center name-room-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->name?>" value  ="<?php echo $room->name?>">
                            </td>
                            <td class="text-center"><?php echo $room->sizeRooms->name?></td>
                            <td class="text-center">
                                <!-- <input class="form-control editables text-center name-user-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->user->name?>" value  ="<?php echo $room->user->name?>"> -->
                                <?php echo $room->user->name ?>
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center price-High-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->priceHigh?>" value  ="<?php echo $room->priceHigh?>">
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center price-Med-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->priceMed?>" value  ="<?php echo $room->priceMed?>">
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center price-Low-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->priceLow?>" value  ="<?php echo $room->priceLow?>">
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center Cost-High-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->costHigh?>" value  ="<?php echo $room->costHigh?>">
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center Cost-Med-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->costMed?>" value  ="<?php echo $room->costMed?>">
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center Cost-Low-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->costLow?>" value  ="<?php echo $room->costLow?>">
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <!--  -->
                                    <a href="{{ url('/admin/apartamento/delete/')}}/<?php echo $room->id ?>" class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Usuario" onclick="return confirm('¿Quieres eliminar el usuario?');">
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
<div class="modal fade" id="modal-room" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
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
                    <div class="row block-content" id="content-room">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('.new-room').click(function(event) {
                $.get('/admin/apartamento/new', function(data) {
                    $('#content-room').empty().append(data);
                });
            });
            $('.update-room').click(function(event) {
                 var id = $(this).attr('data-id');
                $.get('/admin/usuarios/update/'+id, function(data) {
                    $('#content-room').empty().append(data);
                });
            });


            $('.editables').change(function(event) {
                var id = $(this).attr('data-id');

                var name = $('.name-room-'+id).val();
                var priceHigh = $('.price-High-'+id).val();
                var priceMed  = $('.price-Med-'+id).val();
                var priceLow  = $('.price-Low-'+id).val();
                var costHigh  = $('.cost-High-'+id).val();
                var costMed   = $('.cost-Med-'+id).val();
                var costLow   = $('.cost-Low-'+id).val();

                $.get('/admin/apartamento/update/', {  id: id, name: name, priceHigh: priceHigh, priceMed: priceMed, priceLow: priceLow, costHigh: costHigh, costMed: costMed, costLow: costLow}, function(data) {
                    alert(data);
                });
            });
        });
    </script>
@endsection