@extends('layouts.admin-master')
@section('content')
 
<?php
    $forfait_price = 0;
    $material_price = 0;
    $classes_price = 0;
    
    $ff_prices_total = 0;
    if(isset($ff_request->request_prices) && $ff_request->request_prices != NULL){
       $request_prices = unserialize($ff_request->request_prices);
       
       if($ff_request->request_forfaits != NULL){
            foreach($request_prices as $request_price_key => $request_price){
                $ff_prices_total += $request_price;
            }
       }
   }
?>   

<style type="text/css">
    div.div_margin{
        margin-bottom:8px;
    }
</style>

<script type="text/javascript" src="{{asset('/forfait/js/bootbox.min.js')}}"></script>

<div class="container col-lg-12">
    <div class="col-lg-12 col-md-12 h4 bold row">
        <!--<div class="col-lg-8 col-xs-8">-->
            Detalles de la Reserva
        <!--</div>-->
<!--        <div class="col-lg-4 col-xs-4 text-right">
            <?php
//                if($book->ff_status == 0){
//                    echo '<img src="'.asset('/img/miramarski/ski_icon_status_transparent.png').'" style="max-width:30px;"/>';
//                }elseif($book->ff_status == 1){
//                    echo '<img src="'.asset('/img/miramarski/ski_icon_status_grey.png').'" style="max-width:30px;"/>';
//                }elseif($book->ff_status == 2){
//                    echo '<img src="'.asset('/img/miramarski/ski_icon_status_red.png').'" style="max-width:30px;"/>';
//                }elseif($book->ff_status == 3){
//                    echo '<img src="'.asset('/img/miramarski/ski_icon_status_green.png').'" style="max-width:30px;"/>';
//                }
            ?>
        </div>-->
    </div>

    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4 bold" style="background-color:#0000E6; color:#ffffff; padding:4px;">Estado</div>
        <div class="col-lg-2 col-md-2 col-xs-5 text-center" style="margin-bottom:8px;"><a class="btn btn-raise update_ff_status" href="/admin/reservas/ff_change_status_popup/{{$book->id}}/3" style="@if($book->ff_status == 3)color:#ffffff; background-color:green;@else background-color:#cccccc;@endif">Confirmada</a></div>
        <div class="col-lg-2 col-md-2 col-xs-5 text-center" style="margin-bottom:8px;"><a class="btn btn-raise update_ff_status" href="/admin/reservas/ff_change_status_popup/{{$book->id}} ?>/2" style="@if($book->ff_status == 2)color:#ffffff; background-color:red;@else background-color:#cccccc;@endif">No Cobrada</a></div>
        <div class="col-lg-2 col-md-2 col-xs-5 text-center" style="margin-bottom:8px;"><a class="btn btn-raise update_ff_status" href="/admin/reservas/ff_change_status_popup/{{$book->id}} ?>/1" style="@if($book->ff_status == 1)background-color:#7F7F7F; color:#ffffff;@else background-color:#cccccc;@endif">Cancelada</a></div>
        <div class="col-lg-2 col-md-2 col-xs-5 text-center" style="margin-bottom:8px;"><a class="btn btn-raise update_ff_status" href="/admin/reservas/ff_change_status_popup/{{$book->id}} ?>/0" style="@if($book->ff_status == 0)background-color:#7F7F7F; color:#ffffff;@else background-color:#cccccc;@endif">No Gestionada</a></div>
    </div>

    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="background-color:#d1daff; padding:4px;">Número de la Reserva</div>
        <div class="col-lg-8 col-md-8 bold" style="background-color:#d1daff; padding:4px;"><?php echo $book->id; ?></div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Fecha de Solicitud</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;">{{date('d/m/Y H:i:s',strtotime($book->created_at))}}</div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="background-color:#d1daff; padding:4px;">Nombre del Cliente</div>
        <div class="col-lg-8 col-md-8 bold" style="background-color:#d1daff; padding:4px;"><?php echo $customer->name; ?>&nbsp;</div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Teléfono</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;"><?php echo $customer->phone; ?></div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="background-color:#d1daff; padding:4px;">Total</div>
        <div class="col-lg-8 col-md-8 bold" style="background-color:#d1daff; padding:4px;"><?php echo str_replace('.',',',$ff_prices_total); ?>€</div>
    </div>

    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Método de Pago</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;"></div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="background-color:#d1daff; padding:4px;">Nombre Tarjeta de Crédito</div>
        <div class="col-lg-8 col-md-8 bold" style="background-color:#d1daff; padding:4px;">
            <?php if(isset($ff_request->cc_name)){echo $ff_request->cc_name;}?>&nbsp;
        </div>
    </div>
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Tarjeta de Crédito</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;">
            <?php if(isset($ff_request->cc_pan)){echo $ff_request->cc_pan;}?>&nbsp;
        </div>
    </div>
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="background-color:#d1daff; padding:4px;">CV2</div>
        <div class="col-lg-8 col-md-8 bold" style="background-color:#d1daff; padding:4px;">
            <?php if(isset($ff_request->cc_cvc)){echo $ff_request->cc_cvc;}?>&nbsp;
        </div>
    </div>
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Caducidad</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;">
            <?php if(isset($ff_request->cc_expiry)){echo $ff_request->cc_expiry;}?>
        </div>
    </div>

<!--    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="background-color:#d1daff; padding:4px;">Lugar de Recogida</div>
        <div class="col-lg-8 col-md-8 bold" style="background-color:#d1daff; padding:4px;">&nbsp;</div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Detalles de Recogida</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;"></div>
    </div>-->
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="background-color:#d1daff; padding:4px;">Anotaciones del Cliente</div>
        <div class="col-lg-8 col-md-8 bold" style="background-color:#d1daff; padding:4px;">
            <span>
                <?php echo $customer->comments;
                    if(empty($customer->comments)){
                        echo '&nbsp';
                    }
                ?>
            </span>
        </div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Anotaciones de la Reserva</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;">
            <span><?php echo $book->book_comments; ?></span>
        </div>
    </div>

    <div class="container div_margin col-lg-12 col-md-12 col-xs-12 h3 bold">
        <div class="col-lg-3 col-md-3 col-xs-3 col-lg-offset-3 col-md-offset-3 col-xs-offset-3 text-center" style="margin-bottom:8px;"><a id="new_request" class="btn btn-raise btn-info" href="{{url('/forfait')}}" onclick="window.open(this.href, 'Solicitud - FF','left=200,top=20,width=1500,height=900,toolbar=0,resizable=0'); return false;" style="color:#ffffff; background-color:green;">Nueva Solicitud FF/Material/Clases</a><br/>
            <span class="h5">Recuerde Refrescar esta página<br/>después de generar una nueva solicitud.</span>
        </div>
        @if(isset($ff_request->id))
            <div class="col-lg-6 col-md-6 col-xs-6" style="margin-bottom:8px;"><button id="delete_request_items" class="btn btn-raise btn-danger" href="#" data-request-id="{{$ff_request->id}}" style="color:#ffffff; background-color:red;">Eliminar FF/Material/Clases</button></div>
        @else
            <div class="col-lg-6 col-md-6 col-xs-6" style="margin-bottom:8px;"><button id="delete_request_items" class="btn btn-raise btn-danger" href="#" data-request-id="" style="color:#ffffff; background-color:grey;" disabled="disabled">Eliminar FF/Material/Clases</button></div>
        @endif
        
    </div>

    <div class="container div_margin col-lg-12 col-md-12 h3 bold">Forfaits</div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        
        <div class="col-lg-3 col-xs-3" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Nombre</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Primer día de Esquí</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Última día de Esquí</div>
        <div class="col-lg-1 col-xs-1" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Días</div>
        <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Precio</div>
        <div class="col-lg-2 col-xs-2 text-center" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Edad</div>
        
        @if(isset($ff_request->request_forfaits) && $ff_request->request_forfaits != NULL)
            <?php $x=1; ?>
            @foreach(unserialize($ff_request->request_forfaits) as $forfait_request_key => $forfait_request)
                <div class="col-lg-12 col-xs-12" style="padding: 4px 0 4px 0; @if($x % 2 == 1) background-color:#d1daff; @endif">  
                    
                    <?php
                        $forfait_string_array = explode('al',$forfait_request);
                    
                        $name = trim(explode('- Del',$forfait_string_array[0])[0]);
                        $start_date = trim(explode('- Del',$forfait_string_array[0])[1]);
                        $end_date = trim(explode(' - ',$forfait_string_array[1])[0]);
                    ?>
                    
                    <div class="col-lg-3 col-xs-3" style="padding: 4px 0 4px 0;">
                        <span>{{$name}}</span>
                    </div>
                    <div class="col-lg-2 col-xs-3" style="padding: 4px 0 4px 0;">{{date('d/m/Y',strtotime($start_date))}}</div>
                    <div class="col-lg-2 col-xs-3" style="padding: 4px 0 4px 0;">{{date('d/m/Y',strtotime($end_date))}}</div>
                    <div class="col-lg-1 col-xs-1" style="padding: 4px 0 4px 0;"></div>
                    <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0;">
                        
                        <?php
                            echo str_replace('.',',',$request_prices[$forfait_request_key]).'€';
                            $forfait_price += $request_prices[$forfait_request_key];
                        ?>
                        
                    </div>
                    <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0;"></div>
                </div>
                <?php $x++; ?>
            @endforeach
        @endif
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 bold" style="margin-top:12px;">
        <div class="col-lg-4 col-xs-4" style="padding: 4px 0 4px 0;">&nbsp;</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0;">&nbsp;</div>
        <div class="col-lg-2 col-xs-2 text-right bold" style="padding: 4px 0 4px 0;">Subtotal:</div>
        <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0;">{{str_replace('.',',',$forfait_price)}}€</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0;">&nbsp;</div>
    </div>
    
    <div class="container col-lg-12 col-md-12 h3 bold">Alquiler de Esquipos</div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        
        <div class="col-lg-3 col-xs-3" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Equipos</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Primer día de Esquí</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Última día de Esquí</div>
        <div class="col-lg-1 col-xs-1" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Días</div>
        <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Precio</div>
        <div class="col-lg-2 col-xs-2 text-center" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Edad</div>
        
        @if(isset($ff_request->request_material) && $ff_request->request_material != NULL)
            <?php $x=1; ?>
            @foreach(unserialize($ff_request->request_material) as $material_request_key => $material_request)
                <div class="col-lg-12 col-xs-12" style="padding: 4px 0 4px 0; @if($x % 2 == 1) background-color:#d1daff; @endif">  
                    
                    <?php
//                        $material_string_array = explode('al',$material_request);
//                    
//                        $name = trim(explode('- Del',$material_string_array[0])[0]);
//                        $start_date = trim(explode('- Del',$material_string_array[0])[1]);
//                        $end_date = trim(explode(' - ',$material_string_array[1])[0]);
                    ?>
                    
                    <div class="col-lg-4 col-xs-4" style="padding: 4px 0 4px 0;">
                        <span>{{$material_request}}</span>
                    </div>
                    <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0;"></div>
                    <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0;"></div>
                    <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0;">
                        
                        <?php
                            echo str_replace('.',',',$request_prices[$material_request_key]).'€';
                            $material_price += $request_prices[$material_request_key];
                        ?>
                        
                    </div>
                    <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0;"></div>
                </div>
                <?php $x++; ?>
            @endforeach
        @endif
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 bold" style="margin-top:12px;">
        <div class="col-lg-4 col-xs-4" style="padding: 4px 0 4px 0;">&nbsp;</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0;">&nbsp;</div>
        <div class="col-lg-2 col-xs-2 text-right bold" style="padding: 4px 0 4px 0;">Subtotal:</div>
        <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0;">{{str_replace('.',',',$material_price)}}€</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0;">&nbsp;</div>
    </div>
    
    <div class="container col-lg-12 col-md-12 h3 bold">Clases</div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        
        <div class="col-lg-3 col-xs-3" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Clases</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Primer día de Esquí</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Última día de Esquí</div>
        <div class="col-lg-1 col-xs-1" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Días</div>
        <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Precio</div>
        <div class="col-lg-2 col-xs-2 text-center" style="padding: 4px 0 4px 0; background-color:#b3c2ff">Edad</div>
        
        @if(isset($ff_request->request_classes) && $ff_request->request_classes != NULL)
            <?php $x=1; ?>
            @foreach(unserialize($ff_request->request_classes) as $classes_request_key => $classes_request)
                <div class="col-lg-12 col-xs-12" style="padding: 4px 0 4px 0; @if($x % 2 == 1) background-color:#d1daff; @endif">  
                    
                    <?php
//                        $classes_string_array = explode('al',$classes_request);
//                        print_r($classes_string_array);
//                    
//                        $name = trim(explode('- Del',$classes_string_array[0])[0]);
//                        $start_date = trim(explode('- Del',$classes_string_array[0])[1]);
//                        $end_date = trim(explode(' - ',$classes_string_array[1])[0]);
                    ?>
                    
                    <div class="col-lg-4 col-xs-4" style="padding: 4px 0 4px 0;">
                        <span>{{$classes_request}}</span>
                    </div>
                    <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0;"></div>
                    <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0;"></div>
                    <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0;">
                        
                        <?php
                            echo str_replace('.',',',$request_prices[$classes_request_key]).'€';
                            $classes_price += $request_prices[$classes_request_key];
                        ?>
                        
                    </div>
                    <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0;"></div>
                </div>
                <?php $x++; ?>
            @endforeach
        @endif
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 bold" style="margin-top:12px;">
        <div class="col-lg-4 col-xs-4" style="padding: 4px 0 4px 0;">&nbsp;</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0;">&nbsp;</div>
        <div class="col-lg-2 col-xs-2 text-right bold" style="padding: 4px 0 4px 0;">Subtotal:</div>
        <div class="col-lg-2 col-xs-2 text-right" style="padding: 4px 0 4px 0;">{{str_replace('.',',',$classes_price)}}€</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0;">&nbsp;</div>
    </div>
    
    <div class="col-lg-12 col-md-12 col-xs-12 bold h3" style="margin-top:20px; margin-bottom:40px;">
        <div class="col-lg-4 col-xs-3" style="padding: 4px 0 4px 0;">&nbsp;</div>
        <div class="col-lg-2 col-xs-5 text-right bold" style="padding: 4px 0 4px 0;">Total:</div>
        <div class="col-lg-2 col-xs-3 text-center" style="padding: 4px 0 4px 0;">{{str_replace('.',',',$ff_prices_total)}}€</div>
        <div class="col-lg-2 col-xs-2" style="padding: 4px 0 4px 0;">&nbsp;</div>
    </div>

</div>

<script type="text/javascript">
    $('.update_ff_status').click(function(event){
        event.preventDefault();

        new_status = $(this).text();
        url = $(this).attr('href');
        
        bootbox.confirm('<div style="margin-top:12px">¿Está seguro que desea cambiar el estado a <span class="bold">'+new_status+'?</div>', function(result){
            if(result === true){
                window.location.replace(url);
            }
        });

    });
    
    $('button#delete_request_items').click(function(){
        request_id = $(this).attr('data-request-id');
        bootbox.confirm('<div style="margin-top:12px">¿Está seguro que desea <strong>Eliminar</strong> la solicitud de FF/Material/Clases?</div>', function(result){
            if(result === true){
    
                $.ajax({
                    type: "POST",
                    url: "/ajax/forfaits/deleteRequestPopup",
                    data: {request_id:request_id, book_id:{{$book->id}}},
                    dataType:'json',
    //                async: false,
                    success: function(response){
                        console.log(response);
                        if(response == true){
                            location.reload();
                        }
                    },
                    error: function(response){
    //                    console.log(response);
                    }
                });
                
            }
        });
    });
    
</script>


@endsection