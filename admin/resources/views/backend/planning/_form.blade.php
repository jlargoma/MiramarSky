<link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link href="assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" media="screen">
<link href="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen">



<div class="container-fixed-lg">
    <div>
        <div style="width: 100%">
            <!-- START PANEL -->
            <div class="panel panel-default">
                <form role="form"  action="{{ url('reservas/getPriceBook') }}" method="post">

                    <!-- Seccion Reserva -->
                    <div class="panel-heading">
                        <div class="panel-title">
                            Crear reserva
                        </div>
                    </div>

                    <div class="panel-body">
                        
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                            <div class="input-group col-md-12">
                                <div class="col-md-6">
                                    <label>Entrada</label>
                                   <div class="input-daterange input-group" id="datepicker-range">

                                        <input id="start" type="text" class="input-sm form-control" name="start" data-date-format="dd-mm-yyyy">
                                        <span class="input-group-addon">to</span>
                                        <input id="finish" type="text" class="input-sm form-control" name="finish" data-date-format="dd-mm-yyyy">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label>Noches</label>
                                    <input type="text" class="form-control noches" name="noches" value="" disabled style="width: 100%">
                                </div> 
                                <div class="col-md-1">
                                    <label>Pax</label>
                                    <select class="form-control full-width pax" data-init-plugin="select2" name="pax">
                                        <?php for ($i=$min; $i <= $max ; $i++): ?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Pax</label>
                                    <select class="form-control full-width room" data-init-plugin="select2" name="room" id="room">
                                        <?php foreach ($rooms as $room): ?>
                                            <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Park</label>
                                    <select class=" form-control full-width parking" data-init-plugin="select2" name="parking">
                                        <?php for ($i=1; $i <= 4 ; $i++): ?>
                                            <option value="<?php echo $i ?>"><?php echo $book->getParking($i) ?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>    
                            </div>
                            <div class="input-group col-md-12">
                                <div class="col-md-3">
                                    <label>Total</label>
                                    <input type="text" class="form-control total" name="total" value="" disabled style="width: 100%">
                                </div> 
                                <div class="col-md-3">
                                    <label>Coste</label>
                                    <input type="text" class="form-control cost" name="cost" value="" disabled style="width: 100%">
                                </div>
                                <div class="col-md-3">
                                    <label>Beneficio</label>
                                    <input type="text" class="form-control beneficio" name="beneficio" value="" disabled style="width: 100%">
                                </div>
                            </div>
                            <br>
                            <div class="input-group col-md-12">
                            	<label>Comentarios</label>
                                <textarea class="form-control" name="book_comments" style="width: 100%">
                                    
                                </textarea>
                            </div>                         
                    </div>

                    <!-- Seccion Cliente -->
                    <div class="panel-heading">
                        <div class="panel-title">
                            Crear Cliente
                        </div>
                    </div>

                    <div class="panel-body">

                        <div class="input-group col-md-12">
                            <div class="col-md-4">
                                Nombre: <input class="form-control" type="text" name="name">
                            </div>
                            <div class="col-md-4">
                                Email: <input class="form-control" type="email" name="email">  
                            </div>
                            <div class="col-md-4">
                                Telefono: <input class="form-control" type="number" name="phone"> 
                            </div>  
                            <div style="clear: both;"></div>
                            <br>
                            <div class="input-group col-md-12">
                                <button class="btn btn-complete" type="submit">Guardar</button>
                            </div> 
                        </div>
                        
                    </div>


                </form>
            </div>
            <!-- END PANEL -->
        </div>
            <!-- END PANEL -->      
    </div>
</div>

<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="assets/js/form_elements.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() { 
        var start  = 0;
        var finish = 0;
        var diferencia = 0;
        var price = 0;
        var cost = 0;


        $('#start').change(function(event) {
            start= $(this).val();
            var info = start.split('/');
            start = info[1] + '/' + info[0] + '/' + info[2];
            if (finish != 0) {
                diferencia = Math.floor((  Date.parse(finish)- Date.parse(start) ) / 86400000);
                $('.noches').empty();
                $('.noches').html(diferencia);
            }
        });

        $('#finish').change(function(event) {
            finish= $(this).val();
            var info = finish.split('/');
            finish = info[1] + '/' + info[0] + '/' + info[2];           
            if (start != 0) {
                diferencia = Math.floor((  Date.parse(finish)- Date.parse(start) ) / 86400000);
                $('.noches').empty();
                $('.noches').val(diferencia);
            }
        });

        $('#room, .pax, .parking').change(function(event){ 

            var room = $('#room').val();
            var pax = $('.pax').val();
            var park = $('.parking').val();
            var beneficio = 0;

            $.get('reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                $('.total').empty();
                $('.total').val(data);
                price = data;
                    $.get('reservas/getCostBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                    $('.cost').empty();
                    $('.cost').val(data);  
                    cost = data;
                    beneficio = price - cost;
                    $('.beneficio').empty;
                    $('.beneficio').val(beneficio);
                    
                });
            });
        });
    });
</script>