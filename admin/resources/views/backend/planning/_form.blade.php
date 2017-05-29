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
                                    <select class="form-control full-width" data-init-plugin="select2" name="room">
                                        <?php foreach ($rooms as $room): ?>
                                            <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Park</label>
                                    <select class=" form-control full-width" data-init-plugin="select2" name="parking">
                                        <?php for ($i=1; $i <= 4 ; $i++): ?>
                                            <option value="<?php echo $i ?>"><?php echo $book->getParking($i) ?></option>
                                        <?php endfor;?>
                                    </select>
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
        $('#start').change(function(event) {
            start= $(this).val();
            if (finish != 0) {
                diferencia = Math.floor((  Date.parse(finish)- Date.parse(start) ) / 86400000);
                $('.noches').empty();
                $('.noches').html(diferencia);
            }
            console.log(start);
        });
        $('#finish').change(function(event) {
            finish= $(this).val();
            if (start != 0) {
                diferencia = Math.floor((  Date.parse(finish)- Date.parse(start) ) / 86400000);
                $('.noches').empty();
                $('.noches').val(diferencia);
               
            }
        });
        $('#room').change(function(event){
            var room = $(this).val();
            var pax = $('.pax').val();
             $.get('reservas/getPriceBook/'+start+'/'+finish+'/'+room, function(data) {
                    alert(data);
                });
        })

    });
</script>