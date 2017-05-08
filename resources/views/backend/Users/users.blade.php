@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('headerButtoms')
    <li class="text-center">
        <button class="btn btn-sm btn-success new-user" data-toggle="modal" data-target="#modal-user">
            <i class="fa fa-plus"></i> Usuario
        </button>
    </li>
@endsection
    
@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <table class="table table-bordered table-striped js-dataTable-full table-header-bg">
                <thead>
                    <tr>
                        <th class ="text-center hidden" style="width: 25%">id</th>
                        <th class ="text-center" style="width: 25%">Nombre</th>
                        <th class ="text-center" style="width: 15%"> Tipo</th>
                        <th class ="text-center" style="width: 35%">Email</th>
                        <th class ="text-center" style="width: 25%">Editar</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="text-center hidden">
                                <input type="text" name="<?php echo $user->id?>" value="<?php echo $user->id?>">
                            </td>
                            <td class="text-center ">
                                <input class="form-control editables text-center name-user-<?php echo $user->id?>"  data-id="<?php echo $user->id; ?>"  type="text" name="<?php echo $user->name?>" value  ="<?php echo $user->name?>">
                            </td>
                            <td class="text-center ">
                                <?php echo $user->role?>
                            </td>
                            <td class="text-center ">
                                <input class="form-control editables text-center email-user-<?php echo $user->id?>"  data-id="<?php echo $user->id; ?>" style="width: 100%" type="text" name="<?php echo $user->email?>" value ="<?php echo $user->email?>">
                            </td>

                            <td class="text-center">
                                <div class="btn-group">
                                    <!--  -->
                                    <a href="{{ url('/admin/usuarios/delete/')}}/<?php echo $user->id ?>" class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Usuario" onclick="return confirm('¿Quieres eliminar el usuario?');">
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
<div class="modal fade" id="modal-user" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
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
                    <div class="row block-content" id="content-user">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('.new-user').click(function(event) {
                $.get('/admin/usuarios/new', function(data) {
                    $('#content-user').empty().append(data);
                });
            });


            $('.editables').change(function(event) {
                console.log('llega aqui');
                var id = $(this).attr('data-id');

                var name = $('.name-user-'+id).val();
                var role = $('.role-user-'+id).val();
                var email = $('.email-user-'+id).val();

                $.get('/admin/usuarios/update', {  id: id, name: name, role: role, email: email}, function(data) {
                    alert(data);
                });
            });
        });
    </script>
@endsection