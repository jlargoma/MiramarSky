<div class="modal fade" id="modalSEPA19" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 98%; width: 660px;">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title" style="font-size: 1.4em;">SEPA 19</strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ url('/admin/paymentspro/payPropGroup') }}" method="post" id="formAddGasto">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                    <div class="col-xs-12 col-md-3 col-sm-6 mb-1em">
                        <label for="date">fecha</label>
                        <div id="datepicker-component" class="input-group date col-xs-12">
                            <input type="text" class="form-control" name="fecha" id="fechasepa19" value="<?php echo date('d/m/Y') ?>" style="font-size: 12px">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class=" col-xs-12 col-md-5 col-sm-6 mb-1em">
                        <label for="concept">Concepto</label>
                        <input type="text" class="form-control" name="concept" id="concept" />
                    </div>
                    <div class="col-xs-12  col-md-4 col-sm-6 mb-1em">
                        <label for="type">T. Gasto</label>
                        <select class="form-control" id="type" name="type" style="width: 100%;" data-placeholder="Seleccione un tipo" required>
                            <option value=""></option>
                            @foreach($gTypeLst as $k=>$v)
                            <option value="{{$k}}" @if($k=='prop_pay' ) selected @endif>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-12  col-md-4 col-sm-6 mb-1em">
                        <label for="pay_for">Met de pago</label>
                        <select class="form-control" id="type_payment" name="type_payment" style="width: 100%;" required>
                            <option></option>
                            @foreach($typePaymentLst as $k=>$v)
                            <option value="{{$k}}" @if($k=='prop_pay' ) selected @endif>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 col-xs-12 mb-1em">
                        <label for="comment">Observaciones</label>
                        <textarea class="form-control" name="comment" id="comment" style="height: 35px;"></textarea>
                    </div>
                    <div class="table_responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Prop.</th>
                                    <th>C.Prop.</th>
                                </tr>
                            </thead>
                            <?php foreach ($rooms as $room) : ?>
                                <?php if ($room->state == 1) : ?>
                                    <?php
                                    $costPropTot = $data[$room->id]['coste_prop'];
                                    $pendiente = $costPropTot - $data[$room->id]['pagos'];
                                    ?>
                                    <tr>
                                        <td class="text-left">
                                            <a href="#" class=" liquidationByRoom" data-toggle="modal" data-target="#liquidationByRoom" data-id="{{$room->id}}">
                                                <?php echo (isset($room->user->name)) ? ucfirst($room->user->name) : '-' ?> (<?php echo $room->nameRoom ?>)

                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control cPropRooms" name="cProp_{{$room->id}}" />
                                        </td>
                                    </tr>
                                <?php endif ?>

                            <?php endforeach ?>
                            <tr>
                                <th>TOTAL REMESA</th>
                                <th class="text-center"><span id="totalRemesa"></span>€</th>
                            </tr>
                            </tbody>

                        </table>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-lg btn-success">Añadir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>