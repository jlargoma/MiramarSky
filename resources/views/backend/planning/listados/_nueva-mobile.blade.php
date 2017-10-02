<div class="col-md-12 push-30">
    <div class="col-md-12">
        <div class="row">
            <div class="container-fluid padding-10 sm-padding-10" style="background-color: rgba(0,0,10,0.1)">
                <div class="col-xs-12 bg-black ">
                    <div class="col-md-10">
                        <h4 class="text-center white">
                            NUEVA RESERVA
                        </h4>
                    </div>                    
                </div>
                
                <div class="col-xs-12 sm-p-l-0 sm-p-r-0">
                    <div class="panel">
                        <form class="form-horizontal" action="{{ url('/admin/reservas/create') }}" method="post">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <div class="col-md-6 center text-left0">
                                <div class="col-md-4 m-t-10">
                                    <label for="status">Estado</label>
                                </div> 
                                <div class="col-md-8">
                                    <select name="status" class="status form-control minimal" data-id="<?php echo $book->id ?>" >
                                        <?php for ($i=1; $i < 9; $i++): ?> 
                                            <option <?php echo $i == 3 ? "selected" : ""; ?> 
                                            <?php echo ($i  == 1 || $i == 5) ? "style='font-weight:bold'" : "" ?>
                                            value="<?php echo $i ?>"  data-id="<?php echo $book->id ?>">
                                                <?php echo $book->getStatus($i) ?>
                                                
                                            </option>                                    

                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 bg-white padding-block">
                                <div class="col-xs-12 bg-black push-20">
                                    <h4 class="text-center white">
                                        DATOS DEL CLIENTE
                                    </h4>
                                </div>

                                <div class="col-xs-6">
                                    <label for="name">Nombre</label> 
                                    <input class="form-control cliente" type="text" name="name">
                                </div>
                                <div class="col-xs-6">
                                    <label for="email">Email</label> 
                                    <input class="form-control cliente" type="email" name="email" >
                                </div>
                                <div class="col-xs-12">
                                    <label for="phone">Telefono</label> 
                                    <input class="form-control cliente" type="text" name="phone" >
                                </div>  
                            </div>
                            <br>
                            <div class="col-xs-12 bg-white padding-block">
                                <div class="col-xs-12 bg-black push-20">
                                    <h4 class="text-center white">
                                        DATOS DE LA RESERVA
                                    </h4>
                                </div>
                                <div class="col-xs-12">
                                    <label>Entrada</label>
                                    <div class="input-prepend input-group">
                                        <input type="text" class="form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center;min-height: 28px;" readonly="">
                                    </div>
                                </div>
                                <div class="col-xs-3  p-t-10">
                                    <label><i class="fa fa-moon-o"></i></label>
                                    <input type="text" class="form-control nigths" name="nigths" style="width: 100%">
                                </div> 
                                <div class="col-xs-4 p-r-0  p-t-10">
                                    <label><i class="fa fa-users"></i></label>
                                    <select class=" form-control pax minimal"  name="pax">
                                        <?php for ($i=1; $i <= 10 ; $i++): ?>
                                            <option value="<?php echo $i ?>">
                                                <?php echo $i ?>
                                            </option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                                <div class="col-xs-5  p-t-10">
                                    <label><i class="fa fa-home"></i></label>
                                    <select class="form-control full-width newroom minimal" name="newroom" id="newroom">
                                        <option ></option>
                                        <?php foreach ($rooms as $room): ?>
                                            <option value="<?php echo $room->id ?>" data-luxury="<?php echo $room->luxury ?>" data-size="<?php echo $room->sizeApto ?>">
                                                <?php echo $room->name ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-xs-4 p-r-0 p-t-10">
                                    <label>Parking</label>
                                    <select class=" form-control parking minimal"  name="parking">
                                        <?php for ($i=1; $i <= 4 ; $i++): ?>
                                            <option value="<?php echo $i ?>">
                                                <?php echo $book->getParking($i) ?>
                                            </option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                                <div class="col-xs-4 p-r-0 p-t-10">
                                    <label>Sup. Lujo</label>
                                    <select class=" form-control full-width type_luxury minimal" name="type_luxury">
                                        <?php for ($i=1; $i <= 4 ; $i++): ?>
                                            <option value="<?php echo $i ?>" <?php echo ($i == 2)?"selected": "" ?>>
                                                <?php echo $book->getSupLujo($i) ?>
                                            </option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 bg-white">
                                    <div class="col-xs-6 push-10">
                                        <label>Agencia</label>
                                        <select class="form-control full-width agency minimal" name="agency">
                                            <?php for ($i=0; $i <= 2 ; $i++): ?>
                                                <option value="<?php echo $i ?>">
                                                    <?php echo $book->getAgency($i) ?>
                                                </option>
                                            <?php endfor;?>
                                        </select>
                                    </div>
                                    <div class="col-xs-6 col-xs-12 push-10">                                                        
                                        <label>Cost Agencia</label>
                                        <input type="text" class="agencia form-control" name="agencia">
                                    </div>
                                    <div style="clear: both;"></div>
                                    <div class="col-xs-6">
                                        <label>Extras</label>
                                        <select class="full-width form-control select2-hidden-accessible " data-init-plugin="select2" multiple="" name="extras[]" tabindex="-1" aria-hidden="true" style="cursor: pointer">
                                            <?php // foreach ($extras as $extra): ?>
                                                <option value="<?php // echo $extra->id ?>">
                                                    <?php // echo $extra->name ?>
                                                </option>
                                            <?php // endforeach ?>
                                        </select>
                                    </div>
                                <div class="col-xs-12 p-l-0 p-r-0 p-t-10">
                                    <div class="col-xs-4 p-l-0 p-r-0 text-center" style="background-color: #0c685f;">
                                        <label class="font-w800 text-white" for="">TOTAL</label>
                                        <input type="text" class="form-control total m-t-10 m-b-10 white" name="total" >
                                    </div>
                                    <div class="col-xs-4 p-l-0 p-r-0 text-center" style="background: #99D9EA;">
                                        <label class="font-w800 text-white" for="">COSTE</label>
                                        <input type="text" class="form-control cost m-t-10 m-b-10 white" name="cost" >
                                    </div>
                                    <div class="col-xs-4 p-l-0 p-r-0 text-center not-padding" style="background: #ff7f27;">
                                        <label class="font-w800 text-white" for="">BENEFICIO</label>
                                        <input type="text" class="form-control text-left beneficio m-t-10 m-b-10 white" name="beneficio"  style="width: 80%; float: left;">
                                        <div class="beneficio-text font-w400 font-s18 white" style="width: 20%; float: left;padding: 25px 0; padding-right: 5px;">

                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-xs-12 bg-white padding-block">
                                <div class="col-md-6 col-xs-12">
                                    <label>Comentarios Cliente </label>
                                    <textarea class="form-control" name="comments" rows="5" >
                                        
                                    </textarea>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <label>Comentarios Internos</label>
                                    <textarea class="form-control book_comments" name="book_comments" rows="5" >
                                        
                                    </textarea>
                                </div>
                            </div>
                            <div class="row bg-white padding-block">
                                <div class="col-md-4 col-md-offset-4 text-center">
                                    <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit" style="min-height: 50px;width: 100%;" disabled>Guardar</button>
                                </div>  
                            </div>
                        </form>                  
                    </div>
                        
                </div>
            </div>
        </div> 
    </div>
</div>

