<div class="row alert alert-info fade in alert-dismissable" style="margin-top: 30px; background-color: #daeffd!important;">
  <div class="col-xs-12">
    <div class="row btn-percents">
      <div class="col-sm-8 col-xs-7">
        <h5>
          GENERADOR DE LINKS y PAGOS PAYLAND
        </h5>
      </div>
      <div class="col-sm-4 col-xs-5 ">
        <button onclick="load_amount(1)" class="btn btn-success" type="button">100%</button>
        <button onclick="load_amount(0.5)" class="btn btn-success" type="button">50%</button>
      </div>
    </div>
  </div>
  <div class="col-md-8  col-xs-6">
    <input type="hidden" id="payland_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" id="payland_booking" value="{{ encriptID($customer) }}-{{ encriptID($id) }}">
    <?php 
    $toPay =  isset($payment_pend) ? $payment_pend : $book->total_price;
    if ($toPay<0) $toPay = 0;
    ?>
    <input type="hidden" id="total_price" value="<?php echo $toPay; ?>">
    <input type="number" class="form-control only-numbers" id="payland_paymentAmount" placeholder="importe..." required value="{{$toPay}}">
  </div>
  <div class="col-md-4 col-xs-6 push-20">
    <button onclick="_createPayment('link')" class="btn btn-success" type="button" id="_createPaymentLink">Link</button>
    <button onclick="_createPayment('form')" class="btn btn-success" type="button" id="_createPaymentForm">Pago</button>
    <button class="btn  @if(isset($hasVisa) && $hasVisa) btn-blue @else btn-info @endif" type="button" id="_getPaymentVisa">Visa</button>
  </div>
  <div class="col-md-5 @if(isset($visaHtml) && $visaHtml) open @endif" id="visaDataContent">
    {!!$visaHtml!!}
  </div>
  <div class="col-md-7" id="paymentDataContent"></div>
</div>
<script>
  function _createPayment(type) {
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
  $('#paymentDataContent').on("click", "#copy-link-stripe", function () {

    var link = $(this).find('#cpy_link');
    document.getElementById("cpy_link").style.display = "block";
    document.getElementById("cpy_link").select();
    document.execCommand("copy");
    document.getElementById("cpy_link").style.display = "none";

  });
  
  $('#_getPaymentVisa').on("click", function () {

    if ($('#visaDataContent').hasClass('open')){
      $('#visaDataContent').fadeOut('300').removeClass('open');
    } else {
      var url = "{{route('booking.get_visa')}}";
      var booking = $('#payland_booking').val();
      var _token = $('#payland_token').val();
      $.post(url, {
        _token: _token,
        booking: booking,
      }, function (data) {
        $('#visaDataContent').empty().append(data).fadeIn('300').addClass('open');

      });
    }

  });
  
  function copyElementToClipboard(element) {
    window.getSelection().removeAllRanges();
    let range = document.createRange();
    range.selectNode(typeof element === 'string' ? document.getElementById(element) : element);
    window.getSelection().addRange(range);
    document.execCommand('copy');
    window.getSelection().removeAllRanges();
  }
  
  $('#paymentDataContent').on("click", "#copyLinkStripe", function () {
    copyElementToClipboard('textPayment');
  });
  
  function load_amount(percent) {
    var total_price = $('#total_price').val();
    $('#payland_paymentAmount').val(total_price * percent);
  }
  $('#visaDataContent').on("click", '.copy_data', function () {
    var copyText = $(this).closest('div').find('input');
    copyText.select();
//  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

    /* Copy the text inside the text field */
    document.execCommand("copy");

  });

    $('#visaDataContent').on("click", '#_getPaymentVisaForce', function () {

    if (confirm('Refrescar datos de la targeta? (sólo se puede hacer una vez)')) {
      var url = "{{route('booking.get_visa')}}";
      var booking = $('#payland_booking').val();
      var _token = $('#payland_token').val();
      $.post(url, {
        _token: _token,
        booking: booking,
        force: true,
      }, function (data) {
        $('#visaDataContent').empty().append(data).fadeIn('300').addClass('open');

      });
    }

  });

  
</script>
<style>
  #visaDataContent{
    overflow: auto;
  }
  #visaDataContent div{
    clear: both;
    display: block;
    overflow: auto;
    margin: 1em 0;
  }
  #visaDataContent label{
    width: 20%;
    float: left;
    font-weight: 600;
    padding-top: 4px;
  }
  #visaDataContent input{
    width: 60%;
    border: none;
    float: left;
    padding: 4px;
  }
  #visaDataContent button{
    width: 12%;
    float: left;
    padding: 4px;
    margin-top: 2px;
  }
</style>