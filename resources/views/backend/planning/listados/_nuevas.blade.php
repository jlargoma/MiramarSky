<div class="tab-pane " id="tabNueva">
    <div class="row">
        <div class="col-md-10">
            <form role="form"  action="{{ url('/admin/reservas/create') }}" method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <!-- Seccion Cliente -->
                <div class="panel-heading">
                    <div class="panel-title col-md-12">
                        <hr class="cliente">
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
                    </div>                                            
                </div>

                <!-- Seccion Reserva -->
                <div class="panel-heading p-t-0">
                    <div class="panel-title col-md-12">
                        <hr class="reserva">
                    </div>
                </div>

                <div class="panel-body">
                    
                    

                        <div class="input-group col-md-12">
                            <div class="col-md-4">
                                <label>Entrada</label>
                                <div class="input-prepend input-group">
                                  <span class="add-on input-group-addon"><i
                                                class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                  <input type="text" class="sm-form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center; backface-visibility: hidden;min-height: 28px;   " readonly="">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <label>Noches</label>
                                <input type="text" class="form-control nigths" name="nigths" value="" style="width: 100%;display:none">
                                <input type="text" class="form-control nigths" name="noches" value="" disabled style="width: 100%">
                            </div> 
                            <div class="col-md-1">
                                <label>Pax</label>
                                <select name="pax" class="pax" id="" style="min-height: 35px;">
                                    <?php for ($i=1; $i < 8; $i++):?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php endfor; ?>
                                </select>
                                

                                    
                            </div>
                            <div class="col-md-2">
                                <label>Apartamento</label>
                                <select class="newroom" name="newroom" id="newroom" style="min-height: 35px;">
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2 p-l-40">
                                <label>Parking</label><br>
                                <select class="parking"  name="parking" style="min-height: 35px;">
                                    <?php for ($i=1; $i <= 4 ; $i++): ?>
                                        <option value="<?php echo $i ?>"><?php echo $book->getParking($i) ?></option>
                                    <?php endfor;?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Sup. Lujo</label>
                                <select class="type_luxury" id="type_luxury" name="type_luxury" style="min-height: 35px;">
                                    <?php for ($i=1; $i <= 4 ; $i++): ?>
                                        <option class="luxury-<?php echo $i ?>" value="<?php echo $i ?>"><?php echo $book->getSupLujo($i) ?></option>
                                    <?php endfor;?>
                                </select>
                            </div>                                                    
                        </div>
                        <div class="input-group col-md-12 m-t-20">
                            <!-- <div class="col-md-2">
                                <label>Extras</label>
                                <select class="full-width select2-hidden-accessible" data-init-plugin="select2" multiple="" name="extras[]" tabindex="-1" aria-hidden="true">
                                    <?php foreach ($extras as $extra): ?>
                                        <option value="<?php echo $extra->id ?>"><?php echo $extra->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div> -->
                            
                            <div class="col-md-2">
                                <label>Agencia</label>
                                <select class=" form-control full-width agency" data-init-plugin="select2" name="agency">
                                    <?php for ($i=0; $i <= 2 ; $i++): ?>
                                        <?php if ($i == 0): ?>
                                            <option></option>
                                        <?php else: ?>
                                            <option value="<?php echo $i ?>"><?php echo $book->getAgency($i) ?></option>
                                        <?php endif ?>
                                        
                                    <?php endfor;?>
                                </select>
                            </div>
                            <div class="col-md-2">                                                        
                                <label>Cost Agencia</label>
                                <input type="text" class="agencia form-control" name="agencia" value="0">
                            </div>
                            
                            
                            
                        </div>
                        <br>
                        <div class="input-group col-md-12">
                            <div class="col-md-6">
                                <label>Comentarios Cliente</label>
                                <textarea class="form-control" name="comments" rows="5" style="width: 80%">
                                </textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Comentarios Internos</label>
                                <textarea class="form-control book_comments" name="book_comments" rows="5" style="width: 80%">
                                </textarea>
                            </div>
                        </div> 
                        <div class="input-group col-md-12">
                            
                        </div> 
                        <br>
                        <div class="input-group col-md-12 text-center">
                            <button class="btn btn-complete" type="submit" style="width: 50%;min-height: 50px">Guardar</button>
                        </div>                        
                </div>
            </form>
        </div>
        <div class="col-md-2">

            <div class="col-md-12" style="padding: 0px">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="panel-title col-md-12">
                            <hr class="cotizacion">
                        </div>
                    </div>
                </div>
                <table>

                    <tbody>
                        <tr class="text-white" style="background-color: #0c685f">
                            <th style="padding-left: 5px">PVP</th>
                            <th style="padding-right: 5px;padding-left: 5px">
                                <input type="text" class="form-control total m-t-10 m-b-10 text-white" name="total" value="" style="width: 100%;background-color: #0c685f;border:none;font-weight: bold;font-size: 17px">
                            </th>
                        </tr>
                        <tr class=" text-white m-t-5" style="background-color: #99D9EA">
                            <th style="padding-left: 5px">COSTE</th>
                            <th style="padding-right: 5px;padding-left: 5px">
                                <input type="text" class="form-control cost m-t-10 m-b-10 text-white" name="cost" value="" disabled style="width: 100%;color: black;background: #99D9EA;border:none;font-weight: bold;font-size: 17px">
                            </th>
                        </tr>
                        <tr class="text-white m-t-5" style="background-color: #ff7f27">
                            <th style="padding-left: 5px">BENº</th>
                            <th style="padding-right: 5px;padding-left: 5px">
                                <div class="col-md-7 p-r-0 p-l-0">
                                    <input type="text" class="form-control beneficio m-t-10 m-b-10 text-white" name="beneficio" value="" disabled style="width: 100%;color: black;background: #ff7f27;border:none;font-weight: bold;font-size: 17px">
                                </div>
                                <div class="col-md-2 m-t-5"><div class="m-t-10 m-l-10 beneficio-text">0%</div></div>
                                
                            </th>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>