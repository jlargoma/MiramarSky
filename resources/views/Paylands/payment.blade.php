<div class="row alert alert-info fade in alert-dismissable" style="margin-top: 30px; background-color: #daeffd!important;">
    <div class="col-xs-12">
        <h2 class="text-center font-w300" style="letter-spacing: -1px;">
            GENERADOR COBROS
        </h2>
    </div>
    <div class="col-md-12">
        <form action="{{ route('payland.payment') }}" method="post" target="_blank">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="customer_id" value="{{ $customer }}">
            <input type="hidden" name="url_post" value="{{ route('payland.payment') }}">
            <input type="hidden" name="url_ok" value="{{ $routeToRedirect }}">
            <input type="hidden" name="url_ko" value="{{ $routeToRedirect }}">
            <div class="col-md-6">
                <input type="number" name="amount" step="0.01" min="1" class="form-control" placeholder="(importe)" required>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary btn-lg" type="submit">GENERAR COBRO</button>
            </div>
        </form>
    </div>
</div>