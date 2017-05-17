@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('headerButtoms')

@endsection
    
@section('content')

    <div class="col-md-6" style="width: 50%">
        <table class="table table-bordered table-striped js-dataTable-full table-header-bg">
            <thead>
                <tr>
                    <th class ="text-center hidden">    id            </th>
                    <th class ="text-center" style="width: 5%">           Cliente       </th>
                    <th class ="text-center" style="width: 5%">           Personas          </th>
                    <th class ="text-center" style="width: 5%">           Apartamento   </th>
                    <th class ="text-center" style="width: 10%">           Entrada </th>
                    <th class ="text-center" style="width: 10%">           Salida </th>
                    <th class ="text-center" style="width: 5%">           Noches          </th>                        
                    <th class ="text-center" style="width: 7%">           Precio          </th>                        
                    <th class ="text-center">           Estado          </th>                        
                    <th class ="text-center">           Editar        </th>

                </tr>
            </thead>
            <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                     <td class="text-center hidden"><?php echo $book->id?></td>
                     <td class="text-center">                        
                        <?php echo $book->customer->name?>
                    </td>
                    <td class="text-center">
                        <?php echo $book->pax?>
                    </td>
                    <td class="text-center">
                         <select>
                            <?php foreach ($rooms as $apartment): ?>
                                <?php if ($apartment->id == $book->room->id): ?>
                                    <option selected="selected" value="<?php echo $book->room->id ?>"><?php echo $book->room->name ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $apartment->id ?>"><?php echo $apartment->name ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td class="text-center">
                        <?php  echo date('d-m-Y',strtotime($book->start))?>
                    </td>
                    <td class="text-center">
                        <?php  echo date('d-m-Y',strtotime($book->finish))?>
                    </td>
                    <td class="text-center">
                        <?php echo $book->nigths?>
                    </td>
                    <td class="text-center">
                        <?php echo $book->total_price?>
                    </td>
                    <td class="text-center">
                        <select>
                            <?php for ($i=1; $i <= 8 ; $i++):?>
                                <?php if ($book->type_book == $i): ?>
                                    <option selected="selected"><?php echo $book->getStatus($i)?></option>
                                <?php else :?>
                                    <option ><?php echo $book->getStatus($i) ?></option>
                                <?php endif ?>
                            <?php endfor ;?>
                        </select>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6" style="width: 50%">
@include('backend.planning.calendar')
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