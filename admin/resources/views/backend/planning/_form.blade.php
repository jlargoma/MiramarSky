<link href="assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

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
                                <div class="col-md-3">
                                    <label>Entrada</label>
                                    <input type="date" name="start" id="start">
                                </div>
                                <div class="col-md-3">
                                    <label>Salida</label>
                                    <input type="date" name="finish" id="finish">  
                                </div>
                                <div class="col-md-1">
                                    <label>Noches</label>
                                    <input type="text" class="noches" name="noches" value="" disabled style="width: 100%">
                                </div> 
                                <div class="col-md-1">
                                    <label>Pax</label>
                                    <select class="full-width pax" data-init-plugin="select2" name="pax">
                                        <?php for ($i=$min; $i <= $max ; $i++): ?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Pax</label>
                                    <select class="full-width" data-init-plugin="select2" name="room">
                                        <?php foreach ($rooms as $room): ?>
                                            <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Park</label>
                                    <select class="full-width" data-init-plugin="select2" name="parking">
                                        <?php for ($i=1; $i <= 4 ; $i++): ?>
                                            <option value="<?php echo $i ?>"><?php echo $book->getParking($i) ?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>    
                            </div>
                            <br>
                            <div class="input-group col-md-12">
                            	<label>Comentarios</label>
                                <TEXTAREA name="book_comments" style="width: 100%">
                                    
                                </TEXTAREA>
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
                                Nombre: <input type="text" name="name">
                            </div>
                            <div class="col-md-4">
                                Email: <input type="email" name="email">  
                            </div>
                            <div class="col-md-4">
                                Telefono: <input type="number" name="phone"> 
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

<script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
<script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>

<script src="assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script type="text/javascript" src="assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
<script type="text/javascript" src="assets/plugins/dropzone/dropzone.min.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
<script src="assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
<script src="assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
<script src="assets/plugins/handlebars/handlebars-v4.0.5.js"></script>
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