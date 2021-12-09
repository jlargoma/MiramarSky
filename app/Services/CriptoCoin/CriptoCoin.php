<?php

namespace App\Services\CriptoCoin;

use Illuminate\Support\Facades\DB;
use App\BookCriptos;

class CriptoCoin {

  public $merchant_id;
  public $ipn_secret;
  public $parameters;
  public $siteResp;
  public $error_msg;

  public function __construct() {
    $this->ipn_secret = '9Ff6d29dB4635B72a36D677fAFBb18c48adDFB734611dE29896B3d4e22e5da56';
    $this->merchant_id = "ea84a699e42f3ce88d72e4b89058e83a";
    $this->parameters = [];
    $this->error_msg = '';
    $this->siteResp = 'https://www.apartamentosierranevada.net';
  }

  public function setParameters($name, $mail, $bID, $item_name, $item_desc, $amount) {

    $success_url = $this->siteResp . '/payByCripto?success=1';
    $cancel_url = $this->siteResp . '/payByCripto?cancel=1';

    $oOrder = new BookCriptos();
    $oOrder->book_id = $bID;
    $oOrder->amount = $amount;
    $oOrder->token = md5($mail . time());
    $oOrder->save();

    // CoinPayments.net Args
    $this->parameters = array(
        'cmd' => '_pay_auto',
        'merchant' => $this->merchant_id,
        'allow_extra' => 0,
        'currency' => 'EUR',
        'reset' => 1,
        'success_url' => $success_url,
        'cancel_url' => $cancel_url,
        'invoice' => "MIRAMARSKI-RVA-$bID",
        'custom' => $oOrder->token,
        'ipn_url' => 'https://admin.apartamentosierranevada.net/api/booking-cripto-payment',
        'first_name' => $name,
        'last_name' => '',
        'email' => $mail,
        'want_shipping' => 0,
        'item_name' => $item_name,
        'item_desc' => $item_desc,
        'quantity' => 0,
        'amountf' => $amount,
        'taxf' => 0,
    );
  }

  function getUrl() {
    $coinpayments_adr = "https://www.coinpayments.net/index.php?";
    $coinpayments_adr .= http_build_query($this->parameters, '', '&');
    return $coinpayments_adr;
  }

  function checkPayment($request) {
    if (!$this->check_ipn_request_is_valid($request)) {
      return $this->error_msg;
    }
    if (!$this->successful_request($request)) {
      return $this->error_msg;
    }
    return 'OK';
  }

  /**
   * Check CoinPayments.net IPN validity
   * */
  function check_ipn_request_is_valid($request) {

    $order = false;
    $error_msg = "Unknown error";
    $auth_ok = false;
    if ($request !== FALSE && !empty($request)) {
      if (isset($request['ipn_mode']) && $request['ipn_mode'] == 'hmac') {
        if (isset($_SERVER['HTTP_HMAC']) && !empty($_SERVER['HTTP_HMAC'])) {

          if (isset($request['merchant']) && $request['merchant'] == trim($this->merchant_id)) {
            $requestText = file_get_contents('php://input');
            $hmac = hash_hmac("sha512", $requestText, trim($this->ipn_secret));
//            echo $hmac; die;
            if ($hmac == $_SERVER['HTTP_HMAC']) {
              $auth_ok = true;
            } else {
              $error_msg = 'HMAC signature does not match';
            }
          } else {
            $error_msg = 'Error en código de seguridad';
          }
        } else {
          $error_msg = 'Firma HMAC no encontrada.';
        }
      } else {
        $error_msg = "Error en código de verificación IPN.";
      }
    } else {
      $error_msg = 'Error leyendo datos';
    }

    if ($auth_ok) {
      $error_msg = '';
      return true;
    }

    $this->error_msg = $error_msg;
    return false;
  }

  

  /**
   * Successful Payment!
   *
   * @access public
   * @param array $posted
   * @return void
   */
  function successful_request($posted) {
    $posted = stripslashes_deep($posted);
    $error_msg = "Unknown error";
    // Custom holds post ID
    if (!empty($posted['invoice']) && !empty($posted['custom'])) {
      if ($posted['status'] >= 100 || $posted['status'] == 2) {
        return $this->paymentSuccess($posted);
      } else if ($posted['status'] < 0) {
        $error_msg = "Pago cancelado";
      } else {
        $error_msg = "Pago pendiente";
      }
    }
    $this->error_msg = $error_msg;
    return false;
  }

  function paymentSuccess($posted) {
    $token = $posted['custom'];

    $oOrder = BookCriptos::where('token', $token)->first();
    if (!$oOrder) {
      $this->error_msg = 'Orden no encontrada';
      return false;
    }

    if ($oOrder->date_pay) {
      $this->error_msg = 'alreadyPayment';
      return false;
    }
    $oOrder->data_info = json_encode($oOrder);
    $oOrder->save();

    $book = \App\Book::find($oOrder->book_id);
    if (!$book) {
      $this->error_msg = 'Reserva no encontrada';
      return false;
    }
    $amount = $oOrder->amount;
    $oOrder->date_pay = date('Y-m-d H:i:s');
    $oOrder->save();

    $payment = new \App\Payments();
    $payment->book_id = $book->id;
    $payment->datePayment = date('Y-m-d');
    $payment->import = $amount;
    $payment->type = 6;
    $payment->save();

    if ($book->type_book != 2) {
      // WEBDIRECT
      if ($book->is_fastpayment == 1) {
        $book->type_book = 11;
        $book->is_fastpayment = 0;
        $book->save();
      } else
        $book->changeBook(2, "", $book);
    }
    $this->error_msg = '';
    return true;
  }

}

