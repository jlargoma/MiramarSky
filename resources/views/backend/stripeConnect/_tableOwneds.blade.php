<table class="table table-hover  table-responsive-block">
    <thead>
    <tr>
        <th class="text-center bg-complete text-white" style="width: 1%"></th>
        <th class="text-center bg-complete text-white" style="width: 1%">Nombre</th>
        <th class="text-center bg-complete text-white" style="width: 5%">IBAN</th>
        <th class="text-center bg-complete text-white" style="width: 5%">Account stripe</th>
        <th class="text-center bg-complete text-white" style="width: 5%">Aceptar stripe</th>
    </tr>
    </thead>
    <tbody>
	<?php foreach ($owneds as $owned): ?>
    <tr>
        <td>
            <input type="checkbox" value="<?php echo $owned->id ?>" class="checkToTransfer checkbox-<?php echo
            $owned->id ?>">
        </td>
        <td class="text-center" style="padding: 15px 5px!important;"><?php echo strtoupper($owned->name) ?></td>
        <td class="text-center" style="padding: 15px 5px!important;">
            <input class="editable" type="text" data-id="<?php echo
            $owned->id ?>"
            value="<?php echo
            $owned->iban ?>" style="width: 100%;text-align: center;border-style: none none">
        </td>
        <td class="text-center" style="padding: 15px 5px!important;">
            @if($owned->account_stripe_connect)
                <span class="label label-success">{{$owned->account_stripe_connect}}</span>
            @else
                <span class="label label-danger">No conectado</span>
            @endif
        </td>
        <td class="text-center" style="padding: 15px 5px!important;">
            <?php if($owned->accept_stripe == 0 && !empty($owned->account_stripe_connect)): ?>
                <a class="btn btn-sm btn-primary" href="{{ url('/admin/stripe-connect/'.$owned->id
                .'/acceptStripeConnect') }}">
                    Aceptar C. stripe
                </a>
            <?php else:?>
                @if(empty($owned->account_stripe_connect))
                    <span class="label label-danger">No vinculada</span>
                @else
                    <span class="label label-invert">ACEPTADA</span>
                @endif

            <?php endif;?>
        </td>
    </tr>
	<?php endforeach ?>
    </tbody>
</table>