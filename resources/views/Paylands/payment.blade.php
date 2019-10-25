<div class="row alert alert-info fade in alert-dismissable" style="margin-top: 30px; background-color: #daeffd!important;">
    <div class="col-xs-12">
        <h2 class="text-center font-w300" style="letter-spacing: -1px;">
           GENERADOR DE LINKS y PAGOS PAYLAND
        </h2>
    </div>
  <div class="col-md-8  col-xs-6">
    <input type="hidden" id="payland_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" id="payland_booking" value="{{ encriptID($customer) }}-{{ encriptID($id) }}">
    <input type="number" class="form-control only-numbers" id="payland_paymentAmount" placeholder="importe..." required @if(isset($book)) value="{{ $book->total_price * 0.5 }}" @endif>
  </div>
  <div class="col-md-4 col-xs-6 push-20">
    <button onclick="_createPayment('link')" class="btn btn-success" type="button" id="_createPaymentLink">Link</button>
    <button onclick="_createPayment('form')" class="btn btn-success" type="button" id="_createPaymentForm">Pago</button>
  </div>
  <div class="col-md-12" id="paymentDataContent"></div>
</div>
<script>
    function _createPayment(type){
      var url = "{{route('payland.get_payment')}}";
      var amount = $('#payland_paymentAmount').val();
      var booking = $('#payland_booking').val();
      var _token = $('#payland_token').val();
      $.post(url, {
          _token: _token,
          type: type,
          booking: booking,
          amount: amount
        }, function (data) {
          $('#paymentDataContent').empty().append(data).fadeIn('300');

        });

    }
    $('#paymentDataContent').on("click","#copy-link-stripe", function(){
                 
      var link = $(this).find('#cpy_link');
      document.getElementById("cpy_link").style.display = "block";
      document.getElementById("cpy_link").select();
      document.execCommand("copy");
      document.getElementById("cpy_link").style.display = "none";

    });


    $('.only-numbers').keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190 ]) !== -1 ||
             // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
</script>