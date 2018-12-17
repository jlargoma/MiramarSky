@extends('layouts.admin-master')
@section('content')
 
<?php
    $forfait_price = 0;
    $material_price = 0;
    $classes_price = 0;
    
    $ff_prices_total = 0;
    if($ff_request->request_prices != NULL){
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
    <div class="col-lg-12 col-md-12 h4 bold">Detalles de la Reserva</div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4 bold" style="background-color:#0000E6; color:#ffffff; padding:4px;">Estado</div>
        <?php
            if($book->ff_status == 0){
                echo '<div class="col-lg-8 col-md-8 h5 bold" style="background-color:#ffffff; color:#ffffff; margin:0; padding:6.2px;">No Gestionada</div>';
            }elseif($book->ff_status == 1){
                echo '<div class="col-lg-8 col-md-8 h5 bold" style="background-color:#cccccc; color:#ffffff; margin:0; padding:6.2px;">Cancelada</div>';
            }elseif($book->ff_status == 2){
                echo '<div class="col-lg-8 col-md-8 h5 bold" style="background-color:red; color:#ffffff; margin:0; padding:6.2px;">No Cobrada</div>';
            }elseif($book->ff_status == 3){
                echo '<div class="col-lg-8 col-md-8 h5 bold" style="background-color:green; color:#ffffff; margin:0; padding:6.2px;">Confirmada</div>';
            }
        ?>
    </div>

    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="background-color:#d1daff; padding:4px;">Número de la Reserva</div>
        <div class="col-lg-8 col-md-8 bold" style="background-color:#d1daff; padding:4px;"><?php echo $book->id; ?></div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Nombre del Cliente</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;"><?php echo $customer->name; ?></div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="background-color:#d1daff; padding:4px;">Teléfono</div>
        <div class="col-lg-8 col-md-8 bold" style="background-color:#d1daff; padding:4px;"><?php echo $customer->phone; ?></div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Total</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;"><?php echo str_replace('.',',',$ff_prices_total); ?>€</div>
    </div>

    <div class="col-lg-12 col-md-12 info" style="background:#0000E6; padding:8px; margin-bottom:6px;">
        <div class="col-lg-4 col-md-4 h4 bold" style="color:#ffffff;">Estado</div>
        <div class="col-lg-2 col-md-2"><a class="btn btn-raise update_ff_status" href="/admin/reservas/ff_change_status_popup/<?php echo $book->id; ?>/3" style="color:#ffffff; background-color:green;">Confirmada</a></div>
        <div class="col-lg-2 col-md-2"><a class="btn btn-raise update_ff_status" href="/admin/reservas/ff_change_status_popup/<?php echo $book->id; ?>/2" style="color:#ffffff; background-color:red;">No Cobrada</a></div>
        <div class="col-lg-2 col-md-2"><a class="btn btn-raise update_ff_status" href="/admin/reservas/ff_change_status_popup/<?php echo $book->id; ?>/1" style="background-color:#cccccc;">Cancelada</a></div>
        <div class="col-lg-2 col-md-2"><a class="btn btn-raise update_ff_status" href="/admin/reservas/ff_change_status_popup/<?php echo $book->id; ?>/0" style="background-color:#ffffff;">No Gestionada</a></div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Método de Pago</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;"></div>
    </div>
    
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="background-color:#d1daff; padding:4px;">Nombre Tarjeta de Crédito</div>
        <div class="col-lg-8 col-md-8 bold" style="background-color:#d1daff; padding:4px;"><?php echo $ff_request->cc_name ;?></div>
    </div>
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Tarjeta de Crédito</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;"><?php echo $ff_request->cc_pan ;?></div>
    </div>
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="background-color:#d1daff; padding:4px;">CV2</div>
        <div class="col-lg-8 col-md-8 bold" style="background-color:#d1daff; padding:4px;"><?php echo $ff_request->cc_cvc ;?></div>
    </div>
    <div class="container div_margin col-lg-12">
        <div class="col-lg-4 col-md-4" style="padding:4px;">Caducidad</div>
        <div class="col-lg-8 col-md-8 bold" style="padding:4px;"><?php echo $ff_request->cc_expiry ;?></div>
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

    <div class="container div_margin col-lg-12 col-md-12 h3 bold">Forfaits</div>
    <div class="container col-lg-12 col-md-12">
        @if($ff_request->request_forfaits != NULL)
            @foreach(unserialize($ff_request->request_forfaits) as $forfait_request_key => $forfait_request)
                <span>- <?php echo $forfait_request; ?></span><br/>
                <?php $forfait_price += $request_prices[$forfait_request_key]; ?>
            @endforeach
        @endif
    </div>
    <div class="container col-lg-12 col-md-12 bold" style="margin-top:12px;">
        <span class="bold">Subtotal:</span>
        <span class="bold"><?php echo str_replace('.',',',$forfait_price); ?>€</span>
    </div>
    
    <div class="container col-lg-12 col-md-12 h3 bold">Alquiler de Esquipos</div>
    <div class="container col-lg-12 col-md-12">
        @if($ff_request->request_material != NULL)
            @foreach(unserialize($ff_request->request_material) as $material_request_key => $material_request)
                <span>- <?php echo $material_request; ?></span><br/>
                <?php $material_price += $request_prices[$material_request_key]; ?>
            @endforeach
        @endif
    </div>
    <div class="container col-lg-12 col-md-12 bold" style="margin-top:12px;">
        <span class="bold">Subtotal:</span>
        <span class="bold h4"><?php echo str_replace('.',',',$material_price); ?>€</span>
    </div>
    
    <div class="container col-lg-12 col-md-12 h3 bold">Clases</div>
    <div class="container col-lg-12 col-md-12">
        @if($ff_request->request_classes != NULL)
            @foreach(unserialize($ff_request->request_classes) as $classes_request_key => $classes_request)
                <span>- <?php echo $classes_request; ?></span><br/>
                <?php $classes_price += $request_prices[$classes_request_key]; ?>
            @endforeach
        @endif
    </div>
    <div class="container col-lg-12 col-md-12 bold" style="margin-top:12px;">
        <span class="bold">Subtotal:</span>
        <span class="bold"><?php echo str_replace('.',',',$classes_price); ?>€</span>
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
</script>


@endsection