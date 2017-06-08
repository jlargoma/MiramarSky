@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

    <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" media="screen">
    <link href="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen">

@endsection
    
@section('content')
<?php use \Carbon\Carbon; ?>
    <style type="text/css">
        .Reservado{
            background-color: #0DAD9E !important;
            color: black;
        }
        .Pagada-la-señal{
            background-color: #F77975  !important;
            color: black;
        }
        .Bloqueado{
            background-color: #F9D975 !important;
            color: black;
        }
        .SubComunidad{
            background-color: #8A7DBE !important;
            color: black;
        }
        .botones{
            padding-top: 0px!important;
            padding-bottom: 0px!important;
        }
        .end, .start{
            opacity: 0.50;
        }
        a {
            color: black;
            cursor: pointer;
        }
    </style>
<div class="container-fluid padding-10 sm-padding-10">
    <div class="row">
        
        <div class="col-md-7">
            <div class="panel">
                <ul class="nav nav-tabs nav-tabs-simple" role="tablist" data-init-reponsive-tabs="collapse">
                    <li><a href="#tabNueva" data-toggle="tab" role="tab">Nueva</a>
                    </li>
                    <li class="active"><a href="#tabPendientes" data-toggle="tab" role="tab">Pendientes </a>
                    </li>
                    <li><a href="#tabEspeciales" data-toggle="tab" role="tab">Especiales </a>
                    </li>
                    <li><a href="#tabPagadas" data-toggle="tab" role="tab">Pagadas </a>
                    </li>
                </ul>
                <div class="tab-content">

                    <div class="tab-pane " id="tabNueva">
                        <div class="row">
                            <div class="col-md-12">
                                <form role="form"  action="{{ url('reservas/create') }}" method="post">
                                    
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
                                        </div>                                            
                                    </div>

                                    <!-- Seccion Reserva -->
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            Crear reserva
                                        </div>
                                    </div>

                                    <div class="panel-body">
                                        
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                                            <div class="input-group col-md-12">
                                                <div class="col-md-4">
                                                    <label>Entrada</label>
                                                    <div class="input-daterange input-group" id="datepicker-range">

                                                        <input id="start" type="text" class="input-sm form-control" name="start" data-date-format="dd-mm-yyyy">
                                                        <span class="input-group-addon">Hasta</span>
                                                        <input id="finish" type="text" class="input-sm form-control" name="finish" data-date-format="dd-mm-yyyy">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>Noches</label>
                                                    <input type="text" class="form-control nigths" name="nigths" value="" style="width: 100%">
                                                </div> 
                                                <div class="col-md-1">
                                                    <label>Pax</label>
                                                    <input  type="text" class="form-control full-width pax" name="pax" style="width: 100%">
                                                        
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Apartamento</label>
                                                    <select class="form-control full-width newroom" data-init-plugin="select2" name="newroom" id="newroom">
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
                                                    <label>Extras</label>
                                                    <select class="full-width select2-hidden-accessible" data-init-plugin="select2" multiple="" name="extras" tabindex="-1" aria-hidden="true">
                                                        <?php foreach ($extras as $extra): ?>
                                                            <option value="<?php echo $extra->id ?>"><?php echo $extra->name ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Total</label>
                                                    <input type="text" class="form-control total" name="total" value="" style="width: 100%">
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
                                                <label>Comentarios Usuario</label>
                                                <textarea class="form-control" name="comments" style="width: 100%">
                                                    
                                                </textarea>
                                                <label>Comentarios reserva</label>
                                                <textarea class="form-control" name="book_comments" style="width: 100%">
                                                    
                                                </textarea>
                                            </div> 
                                            <div class="input-group col-md-12">
                                                
                                            </div> 
                                            <br>
                                            <div class="input-group col-md-12">
                                                <button class="btn btn-complete" type="submit">Guardar</button>
                                            </div>                        
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane active" id="tabPendientes">
                        <div class="row column-seperation">
                            <div class="pull-left">
                                    <div class="col-xs-12 ">
                                        <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                    </div>
                                </div>
                        
                                <div class="clearfix"></div>
    
                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
                                    <thead>
                                        <tr>
                                            <th class ="text-center bg-complete text-white" style="width:10%">  Cliente     </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Telefono    </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Pax         </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Apart       </th>
                                            <th class ="text-center bg-complete text-white" style="width:15%">  Entrada     </th>
                                            <th class ="text-center bg-complete text-white" style="width:15%">  Salida      </th>
                                            <th class ="text-center bg-complete text-white" style="width:5%">   Noc         </th>
                                            <th class ="text-center bg-complete text-white">                    Precio      </th>
                                            <th class ="text-center bg-complete text-white">                    Estado      </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($arrayBooks["nuevas"] as $book): ?>
                                            <tr>
                                                <td class ="text-center <?php echo $book->getStatus($book->type_book) ?>">
                                                    <a class="update-book" data-id="<?php echo $book->id ?>"  title="Editar Reserva"  href="{{url ('reservas/update')}}/<?php echo $book->id ?>"><?php echo $book->Customer->name ?></a>
                                                </td>
                                                <td class ="text-center">
                                                    <a href="tel:<?php echo $book->Customer->phone ?>"><?php echo $book->Customer->phone ?></a>
                                                </td>
                                                <td class ="text-center"><?php echo $book->pax ?></td>
                                                <td class ="text-center">
                                                    <select class="room" class="form-control" data-id="<?php echo $book->id ?>" >
                                                        
                                                        <?php foreach ($rooms as $room): ?>
                                                            <?php if ($room->id == $book->room_id): ?>
                                                                <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                                                    <?php echo $room->name ?>
                                                                </option>
                                                            <?php else:?>
                                                                <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                                            <?php endif ?>
                                                        <?php endforeach ?>
                                                    </select>
                                                </td>
                                                <td class ="text-center">
                                                    <?php
                                                        $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                                        echo $start->format('d M');
                                                    ?>
                                                </td>
                                                <td class ="text-center">
                                                    <?php
                                                        $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                                        echo $finish->format('d M');
                                                    ?>
                                                </td>
                                                <td class ="text-center"><?php echo $book->nigths ?></td>
                                                <td class ="text-center"><?php echo $book->total_price."€" ?></td>
                                                <td class ="text-center">
                                                    <select class="status" class="form-control" data-id="<?php echo $book->id ?>" >
                                                        <?php for ($i=1; $i < 9; $i++): ?> 
                                                            <?php if ($i == $book->type_book): ?>
                                                                <option selected value="<?php echo $i ?>"  data-id="aaaa"><?php echo $book->getStatus($i) ?></option>
                                                            <?php else: ?>
                                                                <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                                            <?php endif ?>                                          
                                                             
                                                        <?php endfor; ?>
                                                    </select>
                                                </td>
                                                
                                            </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table>  
                            </div>
                    </div>

                    <div class="tab-pane " id="tabEspeciales">
                        <div class="row">
                            <div class="pull-left">
                                <div class="col-xs-12 ">
                                    <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                </div>
                            </div>
                            
                            <div class="clearfix"></div>

                            <div class="col-md-12">
                                <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
                                <thead>
                                    <tr>
                                        <th class ="text-center bg-complete text-white" style="width:10%">  Cliente     </th>
                                        <th class ="text-center bg-complete text-white" style="width:5%">   Telefono    </th>
                                        <th class ="text-center bg-complete text-white" style="width:5%">   Pax    </th>
                                        <th class ="text-center bg-complete text-white" style="width:5%">   Apart       </th>
                                        <th class ="text-center bg-complete text-white" style="width:15%">  Entrada     </th>
                                        <th class ="text-center bg-complete text-white" style="width:20%">  Salida      </th>
                                        <th class ="text-center bg-complete text-white" style="width:2%">   Noc         </th>
                                        <th class ="text-center bg-complete text-white">                    Precio      </th>
                                        <th class ="text-center bg-complete text-white">                    Estado      </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($arrayBooks["especiales"] as $book): ?>
                                    <tr>
                                        <td class ="text-center <?php echo $book->getStatus($book->type_book) ?>">
                                            <?php echo $book->Customer->name ?>                                                    
                                        </td>

                                        <td class ="text-center">
                                            <a href="tel:<?php echo $book->Customer->phone ?>"><?php echo $book->Customer->phone ?></a>
                                        </td>

                                        <td class ="text-center">
                                            <?php echo $book->pax ?>                                            
                                        </td>

                                        <td class ="text-center">
                                            <select class="room" class="form-control" data-id="<?php echo $book->id ?>" >
                                                
                                                <?php foreach ($rooms as $room): ?>
                                                    <?php if ($room->id == $book->room_id): ?>
                                                        <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                                            <?php echo $room->name ?><span>
                                                        </option>
                                                    <?php else:?>
                                                        <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                                    <?php endif ?>
                                                <?php endforeach ?>
                                            </select>
                                        </td>
                                        <td class ="text-center">
                                            <?php
                                                $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                                echo $start->format('d-M');
                                            ?>
                                        </td>
                                        <td class ="text-center">
                                            <?php
                                                $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                                echo $finish->format('d-M');
                                            ?>
                                        </td>
                                        <td class ="text-center"><?php echo $book->nigths ?></td>
                                        <td class ="text-center"><?php echo $book->total_price."€" ?></td>
                                        <td class ="text-center">
                                            <select class="status" class="form-control" data-id="<?php echo $book->id ?>" >
                                                <?php for ($i=1; $i < 9; $i++): ?> 
                                                    <?php if ($i == $book->type_book): ?>
                                                        <option selected value="<?php echo $i ?>"  data-id="aaaa"><?php echo $book->getStatus($i) ?></option>
                                                    <?php else: ?>
                                                        <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                                    <?php endif ?>                                          
                                                     
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                        
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table> 
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tabPagadas">
                        <div class="row">
                            <div class="pull-left">
                                <div class="col-xs-12 ">
                                    <input type="text" id="search-table" class="form-control pull-right" placeholder="Buscar">
                                </div>
                            </div>
                            
                            <div class="clearfix"></div>

                            <table class="table table-hover demo-table-search table-responsive-block" id="tableWithSearch" >
                                <thead>
                                    <tr>
                                        <th class ="text-center bg-complete text-white" style="width:10%">  Cliente     </th>
                                        <th class ="text-center bg-complete text-white" style="width:5%">   Telefono    </th>
                                        <th class ="text-center bg-complete text-white" style="width:5%">   Pax    </th>
                                        <th class ="text-center bg-complete text-white" style="width:5%">   Apart       </th>
                                        <th class ="text-center bg-complete text-white" style="width:15%">  Entrada     </th>
                                        <th class ="text-center bg-complete text-white" style="width:15%">  Salida      </th>
                                        <th class ="text-center bg-complete text-white" style="width:5%">   Noc         </th>
                                        <th class ="text-center bg-complete text-white">                    Precio      </th>
                                        <th class ="text-center bg-complete text-white">                    Estado      </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($arrayBooks["pagadas"] as $book): ?>
                                    <tr>
                                        <td class ="text-center <?php echo $book->getStatus($book->type_book) ?>">
                                            <?php echo $book->Customer->name ?>                                            
                                        </td>

                                        <td class ="text-center">
                                            <a href="tel:<?php echo $book->Customer->phone ?>"><?php echo $book->Customer->phone ?></a>
                                        </td>

                                        <td class ="text-center">
                                            <?php echo $book->pax ?>                                                
                                        </td>

                                        <td class ="text-center">
                                            <select class="room" class="form-control" data-id="<?php echo $book->id ?>" >
                                                <?php foreach ($rooms as $room): ?>
                                                    <?php if ($room->id == $book->room_id): ?>
                                                        <option selected value="<?php echo $book->room_id ?>" data-id="<?php echo $room->name ?>">
                                                            <?php echo $room->name ?><span>
                                                        </option>
                                                    <?php else:?>
                                                        <option value="<?php echo $room->id ?>"><?php echo $room->name ?></option>
                                                    <?php endif ?>
                                                <?php endforeach ?>
                                            </select>
                                        </td>
                                        <td class ="text-center">
                                            <?php
                                                $start = Carbon::createFromFormat('Y-m-d',$book->start);
                                                echo $start->format('d M');
                                            ?>
                                        </td>
                                        <td class ="text-center">
                                            <?php
                                                $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                                                echo $finish->format('d M');
                                            ?>
                                        </td>
                                        <td class ="text-center"><?php echo $book->nigths ?></td>
                                        <td class ="text-center"><?php echo $book->total_price."€" ?></td>
                                        <td class ="text-center">
                                            <select class="status" class="form-control" data-id="<?php echo $book->id ?>" >
                                                <?php for ($i=1; $i < 9; $i++): ?> 
                                                    <?php if ($i == $book->type_book): ?>
                                                        <option selected value="<?php echo $i ?>"  data-id="aaaa"><?php echo $book->getStatus($i) ?></option>
                                                    <?php else: ?>
                                                        <option value="<?php echo $i ?>"><?php echo $book->getStatus($i) ?></option>
                                                    <?php endif ?>     
                                                <?php endfor; ?>
                                            </select>
                                        </td>
                                        
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>   
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-5 col-xs-12">
            <div class="panel">
                <ul class="nav nav-tabs nav-tabs-simple" role="tablist" data-init-reponsive-tabs="collapse">
                 <li class="active"><a href="#tabPrimera" data-toggle="tab" role="tab"><?php echo  $mes->format('M Y')?></a>
                 </li>
                 <li><a href="#tabSegunda" data-toggle="tab" role="tab"><?php echo  $mes->addMonth()->format('M Y')?> </a>
                 </li>
                 <li><a href="#tabTercera" data-toggle="tab" role="tab"><?php echo  $mes->addMonth()->format('M Y')?> </a>
                 </li>
                 <li><a href="#tabCuarta" data-toggle="tab" role="tab"><?php echo  $mes->addMonth()->format('M Y')?> </a>
                 </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tabPrimera">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="fc-border-separate" style="border:1px solid black;width: 100%">
                                    <thead>
                                        <tr>
                                           <td colspan="<?php echo count($arrayMonths[1])+1 ?>">
                                               <?php echo  $date->format('M Y')?>
                                           </td> 
                                        </tr>
                                        <tr>
                                            <td>Apto</td>
                                            <?php for ($i=1; $i <= count($arrayMonths[1]) ; $i++): ?> 
                                                <td style='border:1px solid black;width: 3%'>
                                                    <?php echo $i?> 
                                                </td> 
                                             <?php endfor; ?> 
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <?php foreach ($roomscalendar as $room): ?>
                                            <tr>
                                                <td>
                                                    <?php echo substr($room->name, 0,5)." " ?>
                                                </td>
                                                    <?php for ($j=1; $j <= count($arrayMonths[1]) ; $j++):?>
                                                            <?php if (isset($arrayReservas[$room->id][$j])): ?>
                                                                <?php if (strpos($arrayReservas[0][$room->id][$j],'start') != 0 || strpos($arrayReservas[0][$room->id][$j],'end') != 0 ): ?>
                                                                        <td style="border: 1px solid black">
                                                                            <div class="descrip-<?php echo $i?>">
                                                                                <div class="not-padding <?php if (strpos($arrayReservas[0][$room->id][$j],'end') != 0){ echo $arrayReservas[0][$room->id][$j];}else{} ?>" style="float:left;width: 5px">&nbsp;</div>
                                                                                <div class="not-padding " style="float:left;width: 5px">&nbsp;</div>
                                                                                <div class="not-padding <?php if (strpos($arrayReservas[0][$room->id][$j],'start') != 0){ echo $arrayReservas[0][$room->id][$j];}else{} ?>" style="float:right;width: 5px">&nbsp;</div>
                                                                            </div>
                                                                        </td>
                                                                <?php else: ?>
                                                                    <td style='border:1px solid black' class="<?php echo($arrayReservas[0][$room->id][$j]) ?>"></td>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <td style='border:1px solid black'></td>
                                                            <?php endif ?>
                                                    <?php endfor; ?> 
                                            </tr>
                                        <?php endforeach ?>

                                    </tbody>
                                </table>
                                <?php $date = $date->addMonth(); ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane " id="tabSegunda">
                        <div class="row">
                        <div class="col-md-12">
                            <table class="fc-border-separate" style="border:1px solid black;width: 100%">
                                <thead>
                                    <tr>
                                       <td colspan="<?php echo count($arrayMonths[2])+1 ?>">
                                           <?php echo  $date->format('M Y')?>
                                       </td> 
                                    </tr>
                                    <tr>
                                        <td>Apto</td>
                                        <?php for ($i=1; $i <= count($arrayMonths[2]) ; $i++): ?> 
                                            <td style='border:1px solid black;width: 3%'>
                                                <?php echo $i?> 
                                            </td> 
                                         <?php endfor; ?> 
                                    </tr> 
                                </thead>
                                <tbody>
                                    
                                    <?php foreach ($roomscalendar as $room): ?>
                                        <tr>
                                            <td>
                                                <?php echo substr($room->name, 0,5)." " ?>
                                            </td>
                                                <?php for ($j=1; $j <= count($arrayMonths[2]) ; $j++):?>
                                                        <?php if (isset($arrayReservas[1][$room->id][$j])): ?>
                                                            <?php if (strpos($arrayReservas[1][$room->id][$j],'start') != 0 || strpos($arrayReservas[1][$room->id][$j],'end') != 0 ): ?>
                                                                    <td style="border: 1px solid black">
                                                                        <div class="descrip-<?php echo $i?>">
                                                                            <div class="not-padding <?php if (strpos($arrayReservas[1][$room->id][$j],'end') != 0){ echo $arrayReservas[1][$room->id][$j];}else{} ?>" style="float:left;width: 5px">&nbsp;</div>
                                                                            <div class="not-padding " style="float:left;width: 5px">&nbsp;</div>
                                                                            <div class="not-padding <?php if (strpos($arrayReservas[1][$room->id][$j],'start') != 0){ echo $arrayReservas[1][$room->id][$j];}else{} ?>" style="float:right;width: 5px">&nbsp;</div>
                                                                        </div>
                                                                    </td>
                                                            <?php else: ?>
                                                                <td style='border:1px solid black' class="<?php echo($arrayReservas[1][$room->id][$j]) ?>"></td>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <td style='border:1px solid black'></td>
                                                        <?php endif ?>
                                                <?php endfor; ?> 
                                        </tr>
                                    <?php endforeach ?>

                                </tbody>
                            </table>
                            <?php $date = $date->addMonth(); ?>
                        </div>
                        </div>
                    </div>

                    <div class="tab-pane " id="tabTercera">
                        <div class="row">
                        <div class="col-md-12">
                            <table class="fc-border-separate" style="border:1px solid black;width: 100%">
                                <thead>
                                    <tr>
                                       <td colspan="<?php echo count($arrayMonths[1])+1 ?>">
                                           <?php echo  $date->format('M Y')?>
                                       </td> 
                                    </tr>
                                    <tr>
                                        <td>Apto</td>
                                        <?php for ($i=1; $i <= count($arrayMonths[3]) ; $i++): ?> 
                                            <td style='border:1px solid black;width: 3%'>
                                                <?php echo $i?> 
                                            </td> 
                                         <?php endfor; ?> 
                                    </tr> 
                                </thead>
                                <tbody>
                                    <?php foreach ($roomscalendar as $room): ?>
                                        <tr>
                                            <td>
                                                <?php echo substr($room->name, 0,5)." " ?>
                                            </td>
                                                <?php for ($j=1; $j <= count($arrayMonths[3]) ; $j++):?>
                                                        <?php if (isset($arrayReservas[2][$room->id][$j])): ?>
                                                            <?php if (strpos($arrayReservas[2][$room->id][$j],'start') != 0 || strpos($arrayReservas[2][$room->id][$j],'end') != 0 ): ?>
                                                                    <td style="border: 1px solid black">
                                                                        <div class="descrip-<?php echo $i?>">
                                                                            <div class="not-padding <?php if (strpos($arrayReservas[2][$room->id][$j],'end') != 0){ echo $arrayReservas[2][$room->id][$j];}else{} ?>" style="float:left;width: 5px">&nbsp;</div>
                                                                            <div class="not-padding " style="float:left;width: 5px">&nbsp;</div>
                                                                            <div class="not-padding <?php if (strpos($arrayReservas[2][$room->id][$j],'start') != 0){ echo $arrayReservas[2][$room->id][$j];}else{} ?>" style="float:right;width: 5px">&nbsp;</div>
                                                                        </div>
                                                                    </td>
                                                            <?php else: ?>
                                                                <td style='border:1px solid black' class="<?php echo($arrayReservas[2][$room->id][$j]) ?>"></td>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <td style='border:1px solid black'></td>
                                                        <?php endif ?>
                                                <?php endfor; ?> 
                                        </tr>
                                    <?php endforeach ?>

                                </tbody>
                            </table>
                            <?php $date = $date->addMonth(); ?>
                        </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tabCuarta">
                        <div class="row">
                        <div class="col-md-12">
                            <table class="fc-border-separate" style="border:1px solid black;width: 100%">
                                <thead>
                                    <tr>
                                       <td colspan="<?php echo count($arrayMonths[1])+1 ?>">
                                           <?php echo  $date->format('M Y')?>
                                       </td> 
                                    </tr>
                                    <tr>
                                        <td>Apto</td>
                                        <?php for ($i=1; $i <= count($arrayMonths[4]) ; $i++): ?> 
                                            <td style='border:1px solid black;width: 3%'>
                                                <?php echo $i?> 
                                            </td> 
                                         <?php endfor; ?> 
                                    </tr> 
                                </thead>
                                <tbody>
                                    <?php foreach ($roomscalendar as $room): ?>
                                        <tr>
                                            <td>
                                                <?php echo substr($room->name, 0,5)." " ?>
                                            </td>
                                                <?php for ($j=1; $j <= count($arrayMonths[4]) ; $j++):?>
                                                        <?php if (isset($arrayReservas[3][$room->id][$j])): ?>
                                                            <?php if (strpos($arrayReservas[3][$room->id][$j],'start') != 0 || strpos($arrayReservas[3][$room->id][$j],'end') != 0 ): ?>
                                                                    <td style="border: 1px solid black">
                                                                        <div class="descrip-<?php echo $i?>">
                                                                            <div class="not-padding <?php if (strpos($arrayReservas[3][$room->id][$j],'end') != 0){ echo $arrayReservas[3][$room->id][$j];}else{} ?>" style="float:left;width: 5px">&nbsp;</div>
                                                                            <div class="not-padding " style="float:left;width: 5px">&nbsp;</div>
                                                                            <div class="not-padding <?php if (strpos($arrayReservas[3][$room->id][$j],'start') != 0){ echo $arrayReservas[3][$room->id][$j];}else{} ?>" style="float:right;width: 5px">&nbsp;</div>
                                                                        </div>
                                                                    </td>
                                                            <?php else: ?>
                                                                <td style='border:1px solid black' class="<?php echo($arrayReservas[3][$room->id][$j]) ?>"></td>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <td style='border:1px solid black'></td>
                                                        <?php endif ?>
                                                <?php endfor; ?> 
                                        </tr>
                                    <?php endforeach ?>

                                </tbody>
                            </table>
                            <?php $date = $date->addMonth(); ?>
                        </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>

<div class="modal fade slide-up disable-scroll in" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content-wrapper">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-50"></i>
            </button>
            <div class="container-xs-height full-height">
                <div class="row-xs-height">
                    <div class="modal-body col-xs-height col-middle text-center">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@endsection

@section('scripts')

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


            /* $('#status, #room').change(function(event) { */
            $('.status, .room').change(function(event) {
                var id = $(this).attr('data-id');
                /* var room = $('#room').val();*/
                console.log($(this).attr('class'));
                var clase = $(this).attr('class');
                
                if (clase == "status") {
                   var status = $(this).val();
                }else{
                    var status = "";
                }if(clase == "room"){
                    var room = $(this).val();
                }else{
                    var room = "";
                }
                /* $.get('/admin/apartamentos/update/'+id, {  id: id, status:status, room:room }, function(data) { */
                $.get('reservas/changeBook/'+id, {status:status,room: room}, function(data) {
                    alert(data);
                    window.location.reload();
                });
            });

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
                    $('.nigths').empty();
                    $('.nigths').html(diferencia);
                }
            });

            $('#finish').change(function(event) {
                finish= $(this).val();
                var info = finish.split('/');
                finish = info[1] + '/' + info[0] + '/' + info[2];           
                if (start != 0) {
                    diferencia = Math.floor((  Date.parse(finish)- Date.parse(start) ) / 86400000);
                    $('.nigths').empty();
                    $('.nigths').val(diferencia);
                }
            });

            $('#newroom, .pax, .parking').change(function(event){ 

                var room = $('#newroom').val();
                var pax = $('.pax').val();
                var park = $('.parking').val();
                var beneficio = 0;
                var costPark = 0;
                var pricePark = 0;

                $.get('apartamentos/getPaxPerRooms/'+room).success(function( data ){
                    if (pax < data) {
                        $('.pax').attr('style' , 'background-color:red');
                    }else{
                        $('.pax').removeAttr('style');
                    }
                });

                $.get('reservas/getPricePark', {park: park, noches: diferencia}).success(function( data ) {
                    pricePark = data;
                    $.get('reservas/getPriceBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                        price = data;
                        price = (parseFloat(price) + parseFloat(pricePark));
                        $('.total').empty();
                        $('.total').val(price);
                            $.get('reservas/getCostPark', {park: park, noches: diferencia}).success(function( data ) {
                                costPark = data;
                                    $.get('reservas/getCostBook', {start: start, finish: finish, pax: pax, room: room, park: park}).success(function( data ) {
                                        cost = data;
                                        cost = (parseFloat(cost) + parseFloat(costPark));
                                        $('.cost').empty();
                                        $('.cost').val(cost);
                                        beneficio = price - cost;
                                        $('.beneficio').empty;
                                        $('.beneficio').val(beneficio);
                                    });
                            });
                    });
                });

                
            });
            
            $('.total').change(function(event) {
                var price = $(this).val();
                var cost = $('.cost').val();
                var beneficio = (parseFloat(price) - parseFloat(cost));
                console.log(beneficio);
                $('.beneficio').empty;
                $('.beneficio').val(beneficio);
            });

            
        });

    </script>

@endsection