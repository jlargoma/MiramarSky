<?php

namespace App\Traits;

use App\Settings;
use Carbon\Carbon;
use App\Book;
use App\Repositories\CachedRepository;
use App\Models\Forfaits\ForfaitsOrderPayments;
use App\Models\Forfaits\ForfaitsOrderPaymentLinks;
use App\Models\Forfaits\ForfaitsOrders;
use App\Models\Forfaits\ForfaitsOrderItem;
use Illuminate\Http\Request;

trait ForfaitsPaymentsTraits {

  public function createPayment(Request $req) {

    $token = $req->header('token-ff');
    $client = $req->header('client');
    if (!$this->checkUserAdmin($token,$client)) return die('404');
    
    $token = $req->input('token', null);
    $key = $req->input('key', null);
    $amount = $req->input('data', null);
   

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
   

    return die('404');
  }

  public function createPaylandsUrl(Request $req) {
     
    $token = $req->header('token-ff');
    $client = $req->header('client');
    if (!$this->checkUserAdmin($token,$client)) return die('404');
    
    $key = $req->input('key', null);
    $amount = $req->input('data', null);

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
          return response()->json(['status' => 'ok', 'content' => $this->getPaymentText($urlPay,$amount)]);
        }
      }
      
    return die('404');
  }
  
    private function getPaymentText($urlPay,$amount) {
      $response = '<div class="col-md-2 col-xs-12">
                  <a href="whatsapp://send?text=En este link podrás realizar el pago de '.$amount.'€ correspondiente a los Forfaits - ' . $urlPay . '" data-action="share/whatsapp/share">
                      <i class="fa fa-whatsapp fa-3x" aria-hidden="true"></i>
                  </a>
          </div>
          <div class="col-md-10 col-xs-12 ">
              En este link podrás realizar el pago el pago de '.$amount.'€ correspondiente a los Forfaits<br>
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
        
    $token = $req->header('token-ff');
    $client = $req->header('client');
    if (!$this->checkUserAdmin($token,$client)) return die('404');
    
    $key = $req->input('key', null);

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
    
    return die('404');
  }
  
  function listOrders(){
    
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    $allOrders = ForfaitsOrders::where('book_id','>',0)->get();
    $lstOrders = [];
   
    $totals = [
        'totalPrice' =>0,
        'forfaits'   => 0,
        'class'      => 0,
        'material'   => 0,
        'totalPayment'=> 0,
        'totalToPay'  =>0,
  ];
            
    if ($allOrders){
      foreach ($allOrders as $order){
        if ($order->book_id>0){
          $book = Book::find($order->book_id);
          if ($book){
            $totalPrice = $order->total;
            $forfaits = ForfaitsOrderItem::where('order_id', $order->id)->where('type', 'forfaits')->WhereNull('cancel')->sum('total');
            $class = ForfaitsOrderItem::where('order_id', $order->id)->where('type', 'class')->WhereNull('cancel')->sum('total');
            $material = ForfaitsOrderItem::where('order_id', $order->id)->where('type', 'material')->WhereNull('cancel')->sum('total');
            $totalPayment =  ForfaitsOrderPayments::where('order_id', $order->id)->where('paid',1)->sum('amount');
            
            if ($totalPayment>0){
              $totalPayment = $totalPayment/100;
            }
            $totalToPay = $totalPrice - $totalPayment;
    
            $lstOrders[] = [
                'book'       => $book,
                'totalPrice' =>$totalPrice,
                'forfaits'   => $forfaits,
                'class'      => $class,
                'material'   => $material,
                'totalPayment'=> $totalPayment,
                'totalToPay'  =>$totalToPay,
            ];
            
            
            $totals['totalPrice'] = $totals['totalPrice']+$totalPrice;
            $totals['forfaits'] = $totals['forfaits']+$forfaits;
            $totals['class'] = $totals['class']+$class;
            $totals['material'] = $totals['material']+$material;
            $totals['totalPayment'] = $totals['totalPayment']+$totalPayment;
            $totals['totalToPay'] = $totals['totalToPay']+$totalToPay;
          }
        }
      }
      $rooms = \App\Rooms::all();
      
      return view('backend.forfaits.orders', ['orders' => $lstOrders,'year'=>$year,'rooms'=>$rooms,'totals'=>$totals]);
    }
    
    
    
   
    
    
    
    
    
    
    
    
    
    dd($allOrders);
  }
}
