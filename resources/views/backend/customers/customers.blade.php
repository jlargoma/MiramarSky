@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('headerButtoms')
    <li class="text-center">
        <button class="btn btn-sm btn-success new-client" data-toggle="modal" data-target="#modal-client">
            <i class="fa fa-plus"></i> Cliente
        </button>
    </li>
@endsection
    
@section('content')
<?php use \Carbon\Carbon; ?>

<div class="container">
    <div class="row">
        <div class="col-md-11 col-md-offset-1">
            <table class="table table-bordered table-striped js-dataTable-full table-header-bg">
                <thead>
                    <tr>
                        <th class ="text-center hidden">id          </th>
                        <th class ="text-center">       Nombre      </th>
                        <th class ="text-center">       Email       </th>
                        <th class ="text-center">       Telefono    </th>                  
                        <th class ="text-center">       Comentarios </th>                  
                        <th class ="text-center">       Editar      </th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td class="text-center" hidden><?php echo $customer->id ?></td>
                            <td class="text-center">
                                <input class="form-control editables text-center name-customer-<?php echo $customer->id?>"  data-id="<?php echo $customer->id; ?>"  type="text" name="<?php echo $customer->name ?>" value="<?php  echo $customer->name?>" disabled>
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center email-customer-<?php echo $customer->id?>"  data-id="<?php echo $customer->id; ?>"  type="text" name="<?php echo $customer->email ?>" value="<?php  echo $customer->email?>" disabled>
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center phone-customer-<?php echo $customer->id?>"  data-id="<?php echo $customer->id; ?>"  type="text" name="<?php echo $customer->phone ?>" value="<?php  echo $customer->phone?>" disabled>
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center comments-customer-<?php echo $customer->id?>"  data-id="<?php echo $customer->id; ?>"  type="text" name="<?php echo $customer->comments ?>" value="<?php  echo $customer->comments?>" disabled>
                            </td>
                            <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ url('/admin/clientes/delete/')}}/<?php echo $customer->id ?>" class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Usuario" onclick="return confirm('¿Quieres eliminar el cliente?');">
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
<div class="modal fade" id="modal-seasons" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
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
                    <div class="row block-content" id="content-seasons">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('.new-seasons').click(function(event) {
                $.get('/admin/temporadas/new', function(data) {
                    $('#content-seasons').empty().append(data);
                });
            });
            $('.new-type-seasons').click(function(event) {
                $.get('/admin/temporadas/new-type', function(data) {
                    $('#content-seasons').empty().append(data);
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