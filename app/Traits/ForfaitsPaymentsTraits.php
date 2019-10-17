<?php

namespace App\Traits;

use App\Settings;
use Carbon\Carbon;
use App\Book;
use App\Repositories\CachedRepository;
use App\Models\Forfaits\ForfaitsOrderPayments;
use App\Models\Forfaits\ForfaitsOrderPaymentLinks;
use App\Models\Forfaits\ForfaitsOrders;
use Illuminate\Http\Request;

trait ForfaitsPaymentsTraits {

  public function createPayment(Request $req) {

    $token = $req->input('token', null);
    $key = $req->input('key', null);
    $amount = $req->input('data', null);
    if ($this->checkUserAdmin($token)) {

      if ($key) {
        $aKey = explode('-', $key);
        $bookingKey = isset($aKey[0]) ? ($aKey[0]) : null;
        $clientKey = isset($aKey[1]) ? ($aKey[1]) : null;
        $bookingID = desencriptID($bookingKey);
        $clientID = desencriptID($clientKey);
        $book = Book::find($bookingID);
        if ($book && $book->customer_id == $clientID) {
          $order = ForfaitsOrders::getByKey($key);
          if (!$order) {
            return die('404');
          }
          $orderID = $order->id;
          $description = 'Pago por orden de Forfaits';
          $urlPayland = $this->generateOrderPaymentForfaits($bookingID, $orderID, $book->customer->email, $description, $amount);
          return response()->json(['status' => 'ok', 'url' => $urlPayland]);
        }
      }
    }

    return die('404');
  }

  public function createPaylandsUrl(Request $req) {

    $token = $req->input('token', null);
    $key = $req->input('key', null);
    $amount = $req->input('data', null);
    if ($this->checkUserAdmin($token)) {

      if ($key) {
        $aKey = explode('-', $key);
        $bookingKey = isset($aKey[0]) ? ($aKey[0]) : null;
        $clientKey = isset($aKey[1]) ? ($aKey[1]) : null;
        $bookingID = desencriptID($bookingKey);
        $clientID = desencriptID($clientKey);
        $book = Book::find($bookingID);
        if ($book && $book->customer_id == $clientID) {
          $order = ForfaitsOrders::getByKey($key);
          if (!$order) {
            return die('404');
          }
          $orderID = $order->id;
          $description = 'Pago por orden de Forfaits';
          $token = md5('linktopay'.$bookingID.'-'.time().'-'.$orderID);
          $BookOrders = new ForfaitsOrderPaymentLinks();

          $BookOrders->book_id = $bookingID;
          $BookOrders->order_id = $orderID;
          $BookOrders->cli_email = $book->customer->email;
          $BookOrders->subject = $description;
          $BookOrders->amount = $amount;
          $BookOrders->status = 'new';
          $BookOrders->token = $token;
          $BookOrders->save();
          
          $urlPay = route('front.payments.forfaits',$token);
          return response()->json(['status' => 'ok', 'content' => $this->getPaymentText($urlPay)]);
        }
      }
    }

    return die('404');
  }
  
    private function getPaymentText($urlPay) {
      $response = '<div class="col-md-2 col-xs-12">
                  <a href="whatsapp://send?text=En este link podrás realizar el pago de los Forfaits - ' . $urlPay . '" data-action="share/whatsapp/share">
                      <i class="fa fa-whatsapp fa-3x" aria-hidden="true"></i>
                  </a>
          </div>
          <div class="col-md-10 col-xs-12 ">
              En este link podrás realizar el pago el pago de los Forfaits<br>
                  <a target="_blank" href="' . $urlPay . '">
                      ' . substr($urlPay, 0, 45) . '...     
                  </a>
              <div class="text-center">
                  <button class="btn btn-light" type="button" id="copyPayment" data-link="' . $urlPay . '">
                      <span class="bold">Copiar Link</span>
                  </button>  
                <input type="text" id="paymentLink" value="' . $urlPay . '" style="display:none;border: none;color: #fff;">
              </div>

          </div>';
      return $response;
    }

  public function thansYouPayment($key_token) {

    $bookOrder = ForfaitsOrderPayments::where('key_token', $key_token)->whereNull('paid')->first();
    if ($bookOrder) {
      $bookOrder->paid = true;
      $bookOrder->save();
      $amount = ($bookOrder->amount / 100) . ' €';
      \App\BookLogs::saveLogStatus($bookOrder->book_id, null, $bookOrder->cli_email, "Pago de Forfaits de $amount ($key_token)");
    }
    return redirect()->route('thanks-you-forfait');
  }

  public function errorPayment($key_token) {
    $bookOrder = ForfaitsOrderPayments::where('key_token', $key_token)->first();
    if ($bookOrder) {
      $amount = ($bookOrder->amount / 100) . ' €';
      \App\BookLogs::saveLogStatus($bookOrder->book_id, null, $bookOrder->cli_email, "Error en Pago de Forfaits de $amount ($key_token)");
    }
    return redirect()->route('paymeny-error');
  }

  public function processPayment(Request $request, $id) {
    die('ok');
  }

  public function paymentsForms($token) {
        
    if ($token){
      $payment = ForfaitsOrderPaymentLinks::where('token',$token)->first();
      if ($payment){
        $urlPayland = $this->generateOrderPaymentForfaits(
                $payment->book_id,
                $payment->order_id,
                $payment->cli_email,
                $payment->subject,
                $payment->amount
                );

        if (env('APP_APPLICATION') == "riad"){
          $background = assetV('img/riad/lockscreen.jpg');
        } else {
          $background = assetV('img/miramarski/lockscreen.jpg');
        }

         return view('frontend.bookStatus.paylandPay', ['urlPayland' => $urlPayland,'background'=>$background]);
      }
      return redirect()->route('paymeny-error');
    }
    return redirect()->route('paymeny-error');
  }
  
  public function getPayments(Request $req) {
    $token = $req->input('token', null);
    $key = $req->input('key', null);
    if ($this->checkUserAdmin($token)) {

      if ($key) {
        $aKey = explode('-', $key);
        $bookingKey = isset($aKey[0]) ? ($aKey[0]) : null;
        $clientKey = isset($aKey[1]) ? ($aKey[1]) : null;
        $bookingID = desencriptID($bookingKey);
        $clientID = desencriptID($clientKey);
        $book = Book::find($bookingID);
        if ($book && $book->customer_id == $clientID) {
          $order = ForfaitsOrders::getByKey($key);
          if (!$order) {
            return die('404');
          }
          $orderID = $order->id;
          
          
          $payments =  ForfaitsOrderPayments::where('order_id', $order->id)->where('paid',1)->get();
          $paymentsLst = [];
          if ($payments){
            foreach ($payments as $p){
              $paymentsLst[] = [
                  'date' => $p->updated_at->format('d M, y'),
                  'amount' => $p->amount/100,
              ];
            }
          }
         
          return response()->json(['status' => 'ok', 'content' => $paymentsLst]);
        }
      }
    }

    return die('404');
  }
}
