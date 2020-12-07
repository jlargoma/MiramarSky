<h4 class="calcBkg">CALCULAR RESERVA</h4>
<form id="formCalcularReserva" action="{{url('/admin/reservas/help/getTotalBook')}}" method="post">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" class="form-control" name="name" id="cal-nombre" placeholder="Nombre..." maxlength="40" aria-label="Escribe tu nombre" value="{{$name}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="date" >*Entrada - Salida</label>
                <input type="text" class="form-control daterange1" id="date"   name="date" required style="cursor: pointer;text-align: center;" readonly="" value="{{$date}}">
                <input type="hidden" class="date_start" name="start" value="">
                <input type="hidden" class="date_finish" name="finish" value="">
                <p  class="help-block min-days" style="display:none;line-height:1.2;color:red;">
                    <b>* ESTANCIA MÍNIMA: 2 NOCHES</b>
                </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="quantity">*Personas</label>
                <div class="quantity">
                    <select id="quantity" class="form-control minimal" name="quantity">
                        <?php for ($i = 1; $i <= 14; $i++): ?>
                          <option value="<?php echo $i ?>" <?php echo ($i == $pax) ? 'selected' : '' ?>><?php echo $i ?></option>  
                        <?php endfor ?>
                    </select>
                </div>
                <p class="help-block hidden-sm hidden-xs" style="line-height:1.2">Máx 12</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Tipo Apto</label>
                <select class="form-control minimal" name="size_apto_id">
                    <option value="0">Todos</option>
                    <?php foreach (\App\SizeRooms::allSizeApto() as $k => $v): ?>
                      <option value="{{$k}}">{{$v}}</option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group not-padding-mobile">
                <label>Lujo</label>
                <select class="form-control minimal" name="luxury">
                    <option value="-1">Todos</option>
                    <option value="1">SI</option>
                    <option value="0">NO</option>
                </select>
            </div>
        </div>
        <div class="col-md-3 ">
            <div class="form-group  text-center ">
                <br/>
                <button type="submit" class="btn btn-success btn-cons btn-lg" id="confirm-reserva">Calcular reserva</button>
            </div>
        </div>
    </div>
</form>
<div class="row" id="calcReserv_result" style="display: none;">

</div>
<script type="text/javascript" src="{{ asset('/js/datePicker01.js')}}"></script>
<script type="text/javascript" src="{{ asset('/js/backend/calculateBook.js')}}"></script>

<style>
    h4.calcBkg {
    background-color: #295d9b;
    color: white;
    text-align: center;
    padding: 4px;
    text-align: center;
}
</style>