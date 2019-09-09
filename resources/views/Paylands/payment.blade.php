<div class="row alert alert-info fade in alert-dismissable" style="margin-top: 30px; background-color: #daeffd!important;">
    <div class="col-xs-12">
        <h2 class="text-center font-w300" style="letter-spacing: -1px;">
            GENERADOR COBROS
        </h2>
    </div>
    <div class="col-md-12 push-20">
        <form action="{{ route('payland.payment') }}" method="post" id="form-generate-payland">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="booking" value="{{ encriptID($customer) }}-{{ encriptID($id) }}">
            <input type="hidden" name="url_post" value="{{ route('payland.payment') }}">
            <input type="hidden" name="url_ok" value="{{ $routeToRedirect }}">
            <input type="hidden" name="url_ko" value="{{ $routeToRedirect }}">
            <div class="col-md-6">
                <input type="number" name="amount" step="0.01" min="1" class="form-control" placeholder="(importe)" required @if(isset($book)) value="{{ $book->total_price * 0.5 }}" @endif>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary btn-lg" type="submit">GENERAR COBRO</button>
            </div>
        </form>
    </div>
    <div class="col-md-12" id="content-response"></div>
</div>
<script>
    $('#form-generate-payland').submit(function (event) {
      event.preventDefault();
      var url = $(this).attr('action');
      var _token = $('input[name="_token"]').val();
      var booking = $('input[name="booking"]').val();
      var url_post = $('input[name="url_post"]').val();
      var url_ok = $('input[name="url_ok"]').val();
      var url_ko = $('input[name="url_ko"]').val();
      var amount = $('input[name="amount"]').val();

      $.post(url, {
        _token: _token,
        booking: booking,
        url_post: url_post,
        url_ok: url_ok,
        url_ko: url_ko,
        amount: amount
      }, function (data) {
        $('#content-response').empty().append(data).fadeIn('300');

      });
    });
</script>