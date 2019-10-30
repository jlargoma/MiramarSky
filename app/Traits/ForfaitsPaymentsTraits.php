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
use Illuminate\Support\Facades\DB;

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
          $last_item_id = ForfaitsOrderItem::getLastItem($orderID);
          $urlPayland = $this->generateOrderPaymentForfaits($bookingID, $orderID, $book->customer->email, $description, $amount,$last_item_id);
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
          $last_item_id = ForfaitsOrderItem::getLastItem($orderID);
          $BookOrders = new ForfaitsOrderPaymentLinks();

          $BookOrders->book_id = $bookingID;
          $BookOrders->order_id = $orderID;
          $BookOrders->cli_email = $book->customer->email;
          $BookOrders->subject = $description;
          $BookOrders->amount = $amount;
          $BookOrders->status = 'new';
          $BookOrders->token = $token;
          $BookOrders->last_item_id = $last_item_id;
          $BookOrders->save();
//          $urlPay = 'https://miramarski.com/payments-forms-forfaits?t='.$token;
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
      $order = ForfaitsOrders::find($bookOrder->order_id);
      if ($order){
        $this->sendBookingOrder($order,$bookOrder->last_item_id);
      }
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
                $payment->amount,
                $payment->last_item_id
                );

        if (env('APP_APPLICATION') == "riad"){
          $background = assetV('img/riad/lockscreen.jpg');
        } else {
          $background = assetV('img/miramarski/lockscreen.jpg');
        }

         return view('frontend.bookStatus.paylandPayForfait', ['urlPayland' => $urlPayland,'background'=>$background]);
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
    
            
            
            $ff_sent = ForfaitsOrderItem::where('order_id', $order->id)
                    ->where('type', 'forfaits')
                    ->where('ffexpr_status', 1)
                    ->WhereNull('cancel')->count();
            $ff_item_total = ForfaitsOrderItem::where('order_id', $order->id)
                    ->where('type', 'forfaits')
                    ->WhereNull('cancel')->count();
            $lstOrders[] = [
                'book'       => $book,
                'totalPrice' =>$totalPrice,
                'forfaits'   => $forfaits,
                'class'      => $class,
                'material'   => $material,
                'totalPayment'=> $totalPayment,
                'totalToPay'  =>$totalToPay,
                'ff_sent'     => $ff_sent,
                'ff_item_total'=> $ff_item_total,
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
      
      $obj1  = $this->getMonthlyData($year);
      
      $balance = $this->getBalance();
      $ff_mount = 0;
      if (isset($balance->success) && $balance->success ){
        $ff_mount = $balance->data->total;
      }
      
      $errors = DB::table('forfaits_errors')->whereNull('watched')->get();
         
      return view('backend.forfaits.orders', [
          'orders' => $lstOrders,
          'ff_mount' => $ff_mount,
          'year'=>$year,
          'rooms'=>$rooms,
          'errors'=>$errors,
          'totals'=>$totals,
          'year'=>$year,
          'selected'=>$obj1['selected'],
          'months_obj'=> $obj1['months_obj'],
          'monthValue'=> $obj1['monthValue'],
          'months_label'=> $obj1['months_label'],
              ]);
    }
    
  }
  
      /**
         * Get Limpieza Objet by Year Object
         * 
         * @param Object $year
         * @return array
      */
      private function getMonthlyData($year) {
          
          $startYear = new Carbon($year->start_date);
          $endYear   = new Carbon($year->end_date);
          $diff      = $startYear->diffInMonths($endYear) + 1;
          $thisMonth = date('m');
          $arrayMonth = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
          $arrayMonthMin = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];
          //Prepare objets to JS Chars
          $months_lab = '';
          $months_val = [];
          $months_obj = [];
          $aux = $startYear->format('n');
          $auxY = $startYear->format('y');
          $selected = null;
          for ($i=0; $i<$diff;$i++){
            $c_month = $aux+$i;
            if ($c_month>12){
              $c_month -= 12;
            }
            if ($c_month == 12){
              $auxY++;
            }
            
            if ($thisMonth == $c_month){
              $selected = "$auxY,$c_month";
            }
            
            $months_lab .= "'".$arrayMonth[$c_month-1]."',";
            //Only to the Months select
            $months_obj[] = [
                'id'    => $auxY.'_'.$c_month,
                'dateYear'    => '20'.$auxY,
                'month' => $c_month,
                'year'  => $auxY,
                'name'  => $arrayMonthMin[$c_month-1],
                'value' => 0
            ];
          }
          
          
          $monthValue = array();
          foreach ($months_obj as $k=>$months){
            $value = ForfaitsOrders::whereYear('created_at', '=', $months['dateYear'])
                    ->whereMonth('created_at', '=', $months['month'])->sum('total');
            if ($value){
              $months_obj[$k]['value'] = $value;
              $monthValue[] = $value;
            }
            else {
              $monthValue[] = 0;
              $months_obj[$k]['value'] = 0;
            }
            
          }
          
          return [
              'year'        => $year->year,
              'selected'    => $selected,
              'months_obj'  => $months_obj,
              'monthValue'  => implode(',', $monthValue),
              'months_label'=> $months_lab,
              ];
          
        }
        
        
        
          /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_confirmPayForfaits($email, $totalPayment)
    {
      /*
      $mailClientContent = Settings::getContent('Forfait_email_confirmation_payment',$data->customer->country);
      $totalPayment = 0;
        $payments     = \App\Payments::where('book_id', $book->id)->get();
        if (count($payments) > 0)
        {
            foreach ($payments as $key => $pay)
            {
                $totalPayment += $pay->import;
            }
        }
        $pendiente         = ($book->total_price - $totalPayment);
        if ($pendiente>0) return; //only if the Booking is totally payment
        
        $mailClientContent = str_replace('{total_payment}', number_format($totalPayment, 2, ',', '.'), $mailClientContent);
        $mailClientContent = $this->clearVars($mailClientContent);

        $sended = Mail::send('backend.emails.base', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($book, $subject) {
            $message->from(env('MAIL_FROM'));
            $message->to($email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM'));
        });
        
        \App\BookLogs::saveLog($book->id,$book->room_id,$email,'Forfait_email_confirmation_payment',$subject,$mailClientContent);

        return $sended;
       * */
       
    }
    
}
