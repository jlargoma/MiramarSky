<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.admin-master')

@section('title') Configuración @endsection

@section('externalScripts')
    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css"
          media="screen"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

    <div class="container-fluid padding-25 sm-padding-10">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="font-w800">CONFIGURACIONES</h2>
            </div>
            <div class="col-md-12">
                <div class="col-md-6 col-xs-12">
                    <div class="col-md-12 text-center">
                        <h2>Extras</h2>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <table class="table table-hover  table-responsive-block">
                            <thead>
                            <tr>
                                <th class="text-center bg-complete text-white" style="width: 1%"> Nombre</th>
                                <th class="text-center bg-complete text-white" style="width: 5%">PVP</th>
                                <th class="text-center bg-complete text-white" style="width: 5%">Cost</th>
                                <th class="text-center bg-complete text-white" style="width: 5%">% Ben</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php foreach ($extras as $extra): ?>
                            <tr>
                                <td class="text-center"><?php echo $extra->name ?></td>
                                <td class="text-center">
                                    <input class="extra-editable extra-price-<?php echo $extra->id?>" type="text"
                                           name="cost" data-id="<?php echo $extra->id ?>"
                                           value="<?php echo $extra->price ?>"
                                           style="width: 100%;text-align: center;border-style: none none">
                                </td>
                                <td class="text-center">
                                    <input class="extra-editable extra-cost-<?php echo $extra->id?>" type="text"
                                           name="cost" data-id="<?php echo $extra->id ?>"
                                           value="<?php echo $extra->cost ?>"
                                           style="width: 100%;text-align: center;border-style: none none">
                                </td>
                                <td class="text-center">
									<?php $ben = (($extra->price * 100) / $extra->cost) - 100; ?>
									<?php echo number_format($ben, 2, ',', '.') ?>%
                                </td>
                            </tr>
							<?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="row">
                            <form role="form" action="{{ url('/admin/precios/createExtras') }}" method="post">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <h5 class="text-center">Nuevo extra</h5>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                            <div class="col-md-4 col-xs-12 push-10">
                                                <input type="text" class="form-control" name="name"
                                                       placeholder="Nombre" required=""
                                                       aria-required="true" aria-invalid="false">
                                            </div>
                                            <div class="col-md-4 col-xs-12 push-10">
                                                <input type="number" class="form-control" name="price"
                                                       placeholder="Precio" required=""
                                                       aria-required="true" aria-invalid="false">
                                            </div>
                                            <div class="col-md-4 col-xs-12 push-10">
                                                <input type="number" class="form-control" name="cost"
                                                       placeholder="Coste" required=""
                                                       aria-required="true" aria-invalid="false">
                                            </div>
                                            <div class="col-xs-12 push-10">
                                                <button class="btn btn-complete" type="submit">Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="col-md-12 text-center">
                        <h2>Stripe y pagos</h2>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <table class="table table-hover  table-responsive">
                            <thead>
                            <tr>
                                <th class="text-center bg-complete text-white" style="width: 1%" colspan="2">
                                    Condiciones cobro link stripe
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center bg-complete text-white" style="width: 1%" rowspan="2">
                                    PORCENTAJE
                                </th>
                                <th class="text-center bg-complete text-white" style="width: 1%" rowspan="2"> > DIAS
                                </th>
                            </tr>
                            </thead>
                            <tbody>
							<?php foreach (\App\RulesStripe::all() as $key => $rule): ?>
                            <tr>
                                <td class="text-center" style="border-left: 1px solid #48b0f7">
                                    <input class="rules percent-<?php echo $rule->id?>" type="text" name="cost"
                                           data-id="<?php echo $rule->id ?>" value="<?php echo $rule->percent ?>"
                                           style="width: 100%;text-align: center;">
                                </td>
                                <td class="text-center">
                                    <input class="rules days-<?php echo $rule->id?>" type="text" name="cost"
                                           data-id="<?php echo $rule->id ?>" value="<?php echo $rule->numDays ?>"
                                           style="width: 100%;text-align: center;">
                                </td>
                            </tr>
							<?php endforeach ?>
                            </tbody>
                        </table>


                    </div>
                    <div class="col-md-6 col-xs-12">
                        <table class="table table-hover  table-responsive">
                            <thead>
                            <tr>
                                <th class="text-center bg-complete text-white" style="width: 1%" colspan="2"> Dias del
                                    segundo pago
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center bg-complete text-white" style="width: 1%" rowspan="2"> DIAS</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php foreach (\App\DaysSecondPay::all() as $key => $day): ?>
                            <tr>
                                <td class="text-center" style="border-left: 1px solid #48b0f7">
                                    <input class="daysSecondPayment" type="number" name="days"
                                           data-id="<?php echo $day->id ?>" value="<?php echo $day->days ?>"
                                           style="width: 100%;text-align: center;">
                                </td>
                            </tr>
							<?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6 col-xs-12">
                    <div class="col-md-12 text-center">
                        <h2>Días minimos</h2>
                    </div>
                    <div class="col-xs-12">
                        <div class="col-xs-12 push-10">
                            <button class="btn btn-primary" style="float:right;" type="button" data-toggle="modal"
                                    data-target="#segment">
                                <i class="fa fa-plus"></i> Rango
                            </button>
                        </div>
						<?php if ( count($specialSegments) > 0): ?>
                        <table class="table table-condensed table-responsive-block">
                            <thead>
                            <tr>
                                <th class="text-center bg-complete text-white">Inicio</th>
                                <th class="text-center bg-complete text-white">Fin</th>
                                <th class="text-center bg-complete text-white">Min Días</th>
                                <th class="text-center bg-complete text-white">Accion</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php foreach ($specialSegments as $segment): ?>
                            <tr>
                                <td class="text-center" style="padding: 12px 20px!important">
									<?php echo $segment->start ?>
                                </td>
                                <td class="text-center" style="padding: 12px 20px!important">
									<?php echo $segment->finish ?>
                                </td>
                                <td class="text-center" style="padding: 12px 20px!important">
									<?php echo $segment->minDays; ?>
									<?php if($segment->minDays > 1): ?>
                                    Días
									<?php else: ?>
                                    Día
									<?php endif?>
                                </td>
                                <td class="text-center" style="padding: 12px 20px!important">
                                    <button class="btn btn-primary btn-sm updateSegment" type="button"
                                            data-toggle="modal" data-target="#segment"
                                            data-id="<?php echo $segment->id ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <a class="btn btn-danger btn-sm"
                                       href="{{ url('/admin/specialSegments/delete/'.$segment->id )}}"
                                       title="Eliminar Segmento">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
							<?php endforeach ?>
                            </tbody>
                        </table>
						<?php else: ?>
                        <h3 class="font-w300 text-center">
                            No has establecido ningún Rango de días
                        </h3>
						<?php endif?>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="col-md-12 text-center">
                        <h2>Agentes - Rooms</h2>
                    </div>
                    <div class="col-xs-12 push-10">
                        <button class="btn btn-primary" style="float:right;" type="button" data-toggle="modal"
                                data-target="#agentRoom">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <div class="col-xs-12 push-10" style="overflow: auto; max-height: 335px;">
                        <?php if ( count($agentsRooms) > 0): ?>
                        <table class="table table-condensed table-responsive-block">
                            <thead>
                            <tr>
                                <th class="text-center bg-complete text-white">ID</th>
                                <th class="text-center bg-complete text-white">Agente</th>
                                <th class="text-center bg-complete text-white">Apart</th>
                                <th class="text-center bg-complete text-white">Accion</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($agentsRooms as $agent): ?>
                            <tr>
                                <td class="text-center" style="padding: 12px 20px!important">
                                    <?php echo $agent->id ?>
                                </td>
                                <td class="text-center" style="padding: 12px 20px!important">
                                    <?php echo $agent->user->name ?>
                                </td>
                                <td class="text-center" style="padding: 12px 20px!important">
                                    <?php echo $agent->room->nameRoom; ?>
                                </td>
                                <td class="text-center" style="padding: 12px 20px!important">

                                    <a class="btn btn-danger btn-sm"
                                       href="{{ url('/admin/agentRoom/delete/'.$agent->id )}}"
                                       title="Eliminar">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <h3 class="font-w300 text-center">
                            No has establecido ningún Agente para habitaciones
                        </h3>
                        <?php endif?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade slide-up in" id="segment" tabindex="-1" role="dialog" aria-hidden="true">
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
                                Rangos
                            </h2>
                        </div>
                        <div class="block block-content" id="contentSegments" style="padding:20px">
                            @include('backend.segments.create')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade slide-up in" id="agentRoom" tabindex="-1" role="dialog" aria-hidden="true">
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
                                Agentes - Aparts
                            </h2>
                        </div>
                        <div class="block block-content" id="contentAgentRoom" style="padding:20px">
                            <div class="row">
                                <form action="{{ url('/admin/agentRoom/create') }}" method="post">
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    <?php $roomsToAgents = \App\Rooms::where('state', '=', 1)->orderBy('order')->get();?>
                                    <?php $agents = \App\User::where('role', 'agente')->get();?>
                                    <div class="col-md-4 col-xs-12 push-10">
                                        <label>Agente</label>
                                        <select class="form-control full-width minimal" name="user_id" required>
                                            <option ></option>
                                            <?php foreach ($agents as $agent): ?>
                                                <option value="<?php echo $agent->id ?>">
                                                    <?php echo $agent->name  ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-xs-12 push-10">
                                        <label>Apartamento</label>
                                        <select class="form-control full-width minimal" name="room_id" required>
                                            <option ></option>
                                            <?php foreach ($roomsToAgents as $room): ?>
                                            <option value="<?php echo $room->id ?>">
                                                <?php echo substr($room->nameRoom." - ".$room->name, 0,12)  ?>
                                            </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-xs-12 push-10">
                                        <br>
                                        <button class="btn btn-complete font-w400" type="submit">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js"
            type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js"
            type="text/javascript"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>

    <script type="text/javascript">
      $(document).ready(function () {

        $('.rules').change(function (event) {
          var id = $(this).attr('data-id');
          var percent = $('.percent-' + id).val();
          var numDays = $('.days-' + id).val();

          $.get('/admin/rules/stripe/update', {id: id, percent: percent, numDays: numDays}, function (data) {
            // alert(data);
            window.location.reload();
          });

        });

        $('.daysSecondPayment').change(function (event) {
          var id = $(this).attr('data-id');
          var numDays = $(this).val();

          $.get('/admin/days/secondPay/update/' + id + '/' + numDays, function (data) {
            // alert(data);
            window.location.reload();
          });

        });

        $('.extra-editable').change(function (event) {
          var id = $(this).attr('data-id');
          var extraprice = $('.extra-price-' + id).val();
          var extracost = $('.extra-cost-' + id).val();

          $.get('precios/updateExtra', {id: id, extraprice: extraprice, extracost: extracost}, function (data) {
            // alert(data);
            window.location.reload();
          });

        });

        $('.updateSegment').click(function (event) {
          var id = $(this).attr('data-id');
          $.get('/admin/specialSegments/update/' + id, function (data) {
            $('#contentSegments').empty().append(data);
          });
        });
      });
    </script>
@endsection