@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('headerButtoms')
    <li class="text-center">
        <button class="btn btn-sm btn-success new-room" data-toggle="modal" data-target="#modal-room">
            <i class="fa fa-plus"></i> Apartamentos
        </button>
    </li>
    <li class="text-center">
        <button class="btn btn-sm btn-success new-size-room" data-toggle="modal" data-target="#modal-room">
            <i class="fa fa-plus"></i> Tamaño de Apartamentos
        </button>
    </li>
    <li class="text-center">
        <button class="btn btn-sm btn-success new-type-room" data-toggle="modal" data-target="#modal-room">
            <i class="fa fa-plus"></i> Tipo de Apartamentos
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
                        <th class ="text-center">           Ocupacion min </th>
                        <th class ="text-center">           Ocupacion max </th>
                        <th class ="text-center">           Lujo </th>                        
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
                            <td class="text-center">
                                <input class="form-control editables text-center size-room-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  data-size="<?php echo $room->sizeRooms->id; ?>" type="text" name="<?php echo $room->sizeRooms->name?>" value  ="<?php echo $room->sizeRooms->name?>" disabled>
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center name-propieratio-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->user->name?>" value  ="<?php echo $room->user->name?>" disabled>
                            </td>    
                            <td class="text-center">
                                <input class="form-control editables text-center min-occupation-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->sizeRooms->minOcu?>" value  ="<?php echo $room->sizeRooms->minOcu?>" disabled>
                            </td>  
                            <td class="text-center">
                                <input class="form-control editables text-center max-occupation-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->sizeRooms->maxOcu?>" value  ="<?php echo $room->sizeRooms->maxOcu?>" disabled>
                            </td> 
                            <td class="text-center">
                                <?php if ($room->typeApto == 0): ?>
                                    <input class="form-control editables text-center type-apto-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->typeApto?>" value  ="No" disabled>
                                <?php else: ?>
                                    <input class="form-control editables text-center type-apto-<?php echo $room->id?>"  data-id="<?php echo $room->id; ?>"  type="text" name="<?php echo $room->typeApto?>" value  ="Si" disabled>
                                <?php endif ?>
                                
                            </td>                      
                            <td class="text-center">
                                <div class="btn-group">
                                    <!--  -->
                                    <a href="{{ url('/admin/apartamentos/delete/')}}/<?php echo $room->id ?>" class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Apartamento" onclick="return confirm('¿Quieres eliminar el apartamento?');">
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
        <div class="modal-dialog modal-lg">
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
            //Añadir una nueva habitacion
            $('.new-room').click(function(event) {
                $.get('/admin/apartamentos/new', function(data) {
                    $('#content-room').empty().append(data);
                });
            });

            //añadir un nuevo tipo de apartamento EJ:Propietario-subcominudad
            $('.new-type-room').click(function(event) {
                $.get('/admin/apartamentos/new-type', function(data) {
                    $('#content-room').empty().append(data);
                });
            });

            //añadir un nuevo tamaño de apartamento EJ:estudio-apartamento
            $('.new-size-room').click(function(event) {
                $.get('/admin/apartamentos/new-size', function(data) {
                    $('#content-room').empty().append(data);
                });
            });


            $('.editables').change(function(event) {
                var id = $(this).attr('data-id');

                var name = $('.name-room-'+id).val();

                $.get('/admin/apartamentos/update/', {  id: id, name:name}, function(data) {
                    alert(data);
                });
            });
        });
    </script>
@endsection