@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('headerButtoms')
    <li class="text-center">
        <button class="btn btn-sm btn-success new-seasons" data-toggle="modal" data-target="#modal-seasons">
            <i class="fa fa-plus"></i> Temporada
        </button>
    </li>
    <li class="text-center">
        <button class="btn btn-sm btn-success new-type-seasons" data-toggle="modal" data-target="#modal-seasons">
            <i class="fa fa-plus"></i> Tipo de Temporada
        </button>
    </li>
@endsection
    
@section('content')
<?php use \Carbon\Carbon; ?>

<div class="container">
    <div class="row">
        <div class="col-md-11 col-md-offset-1">
        <?php echo "<pre>";
              echo $from."<br>";
              echo $to."<br>".$user;
        ?>
            <table class="table table-bordered table-striped js-dataTable-full table-header-bg">
                <thead>
                    <tr>
                        <th class ="text-center hidden">    id      </th>
                        <th class ="text-center">           Inicio  </th>
                        <th class ="text-center">           Fin     </th>
                        <th class ="text-center">           Tipo    </th>                  
                        <th class ="text-center">           Editar  </th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($seasons as $season): ?>
                        <tr>
                            <td class="text-center" hidden><?php echo $season->id ?></td>
                            <td class="text-center">
                                <input class="form-control editables text-center start-season-<?php echo $season->id?>"  data-id="<?php echo $season->id; ?>"  type="text" name="<?php echo $season->start_date?>" value  ="<?php  echo date('d-m-Y',strtotime($season->start_date))?>" disabled>
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center finish-date-<?php echo $season->id?>"  data-id="<?php echo $season->id; ?>"  type="text" name="<?php echo $season->finish_date ?>" value  ="<?php echo date('d-m-Y',strtotime($season->finish_date)) ?>" disabled>
                            </td>
                            <td class="text-center">
                                <input class="form-control editables text-center type-season-<?php echo $season->id?>"  data-id="<?php echo $season->id; ?>"  type="text" name="<?php echo $season->typeSeasons->name ?>" value  ="<?php echo $season->typeSeasons->name ?>" disabled>
                            </td>              
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ url('/admin/temporadas/delete/')}}/<?php echo $season->id ?>" class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Usuario" onclick="return confirm('¿Quieres eliminar el apartamento?');">
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