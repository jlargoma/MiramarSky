<h4>A) PAGO CON CRIPTOMONEDAS</h4>
<div class="payCriptoBox">
  <form action="{{route('front.payments-byCripto')}}" method="POST" class="payCripto">
    <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" id="tkn" name="tkn" value="{{ $token }}">
    <img src="/img/pagarCripto2.png" alt="paga 10% menos con criptos"/>
    <button class="btnpay">Pagar <span class="tachado">{{moneda($amount)}}</span> {{moneda($criptoPVP)}}</button>
    <div class="ahorro">Ahorro estimado {{moneda($discCripto)}}</div>
  </form>
</div>
                         
<style>
  .payCriptoBox {
    background-color: #ffffff9c;
    text-align: center;
    padding-top: 15px;
    padding-bottom: 36px;
}
form.payCripto {
    display: block;
    margin: 4px auto;
    width: 400px;
    background: #f5f5f7;
    -webkit-box-shadow: 0 12px 30px rgb(0 0 0 / 60%);
    -moz-box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
    -ms-box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
    -o-box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
    box-shadow: 0 12px 30px rgb(0 0 0 / 60%);
    -webkit-border-radius: 0 0 6px 6px;
    -moz-border-radius: 0 0 6px 6px;
    -ms-border-radius: 0 0 6px 6px;
    -o-border-radius: 0 0 6px 6px;
    border-radius: 6px;
    padding-bottom: 18px;
    max-width: 97%;
    text-decoration: none;
}
a.payCripto:hover {
 text-decoration: none; 
}
.btnpay {
    padding: 6px 12px;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid transparent;
    border-radius: 4px;
    cursor: pointer;
    outline: 0;
    -webkit-font-smoothing: antialiased;
    font-family: "Helvetica Neue", "Helvetica", Arial, sans-serif;
    font-weight: bold;
    font-size: 17px;
    color: #fff;
    text-shadow: 0 -1px 0 rgb(46 86 153 / 30%);
    background: #45b1e8;
    width: 88%;
    margin: 0 auto;
}
.ahorro {
    margin: 10px auto 1px;
    font-weight: bold;
    color: #4c4c01;
    background-color: yellow;
    border: 1px solid #cbcb12;
    padding: 5px;
    width: 170px;
    border-radius: 6px;
    font-size: 12px;
}
span.tachado {
    color: red;
    font-weight: 300;
    margin: 0 7px;
    text-decoration: line-through;
}
h4 {
    background-color: #ffffff9c;
    text-align: center;
    padding: 16px;
    font-size: 24px;
    color: #004c76;
    margin: 0;
}

  </style>