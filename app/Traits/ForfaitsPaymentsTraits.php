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
use App\Traits\BookEmailsStatus;

trait ForfaitsPaymentsTraits {

  use BookEmailsStatus;
  
  public function createPayment(Request $req) {

    $token = $req->header('token-ff');
    $client = $req->header('client');
    $amount = null;
    if (!$this->checkUserAdmin($token,$client)){
      //return die('404');
      $amount = $req->input('data', null);
    }
    
    
    
    $token = $req->input('token', null);
    $key = $req->input('key', null);

    if ($key) {
        $order = ForfaitsOrders::getByKey($key);
        if (!$order) {
          return die('404');
        }
        $book = Book::find($order->book_id);
        if ($book){
          $cli_email = $book->customer->email;
        } else {
          $cli_email = $order->email;
        }
        
        if (!$amount){
          $totalPayment =  ForfaitsOrderPayments::where('order_id', $order->id)->where('paid',1)->sum('amount');
          if ($totalPayment>0){
            $totalPayment = $totalPayment/100;
          }
          $amount = $order->total - $totalPayment;
          if ($amount<0){
            $amount = 0;
          }
        }

        $orderID = $order->id;
        $description = 'Pago por orden de Forfaits';
        $last_item_id = ForfaitsOrderItem::getLastItem($orderID);
        $urlPayland = $this->generateOrderPaymentForfaits($order->book_id, $orderID, $cli_email, $description, $amount,$last_item_id);
        
        return response()->json(['status' => 'ok', 'url' => $urlPayland]);
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
        $order = ForfaitsOrders::getByKey($key);
        
        if (!$order) {
          return die('404');
        }
        $bookingID = $order->book_id;
        $book = Book::find($order->book_id);
        if ($book){
          $cli_email = $book->customer->email;
        } else {
          $cli_email = $order->email;
        }
        
        $orderID = $order->id;
        $description = 'Pago por orden de Forfaits';
        $token = md5('linktopay'.$bookingID.'-'.time().'-'.$orderID);
        $last_item_id = ForfaitsOrderItem::getLastItem($orderID);
        $PaymentOrders = new ForfaitsOrderPaymentLinks();

        $PaymentOrders->book_id = $bookingID;
        $PaymentOrders->order_id = $orderID;
        $PaymentOrders->cli_email = $cli_email;
        $PaymentOrders->subject = $description;
        $PaymentOrders->amount = $amount;
        $PaymentOrders->status = 'new';
        $PaymentOrders->token = $token;
        $PaymentOrders->last_item_id = $last_item_id;
        $PaymentOrders->save();
//          $urlPay = 'https://miramarski.com/payments-forms-forfaits?t='.$token;
        $urlPay = route('front.payments.forfaits',$token);
        return response()->json(['status' => 'ok', 'content' => $this->getPaymentText($urlPay,$amount)]);
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

    $PaymentOrder = ForfaitsOrderPayments::where('key_token', $key_token)->whereNull('paid')->first();
    if ($PaymentOrder) {
      $PaymentOrder->paid = true;
      $PaymentOrder->save();
      $amount = ($PaymentOrder->amount / 100) . ' €';
      
      $bookID = $PaymentOrder->book_id;
      if (!$bookID) $bookID = -1;
      
      \App\BookLogs::saveLogStatus($bookID, null, $PaymentOrder->cli_email, "Pago de Forfaits de $amount ($key_token)");
      $order = ForfaitsOrders::find($PaymentOrder->order_id);
      if ($order){
        $this->sendBookingOrder($order,$PaymentOrder->last_item_id);
        //send email
        $book = Book::find($PaymentOrder->book_id);
        if ($book){
          $cli_email = $book->customer->email;
          $cli_name = $book->customer->name;
          $subject = translateSubject('Confirmación de Pago',$book->customer->country);
        } else {
          $cli_name = $order->name;
          $cli_email = $order->email;
          $subject = 'Confirmación de Pago';
        }
        
        $orderText = $this->renderOrder($PaymentOrder->order_id);
        $this->sendEmail_confirmForfaitPayment($cli_email,$cli_name,$subject,$orderText,$amount,$book);
      }
    }
    return redirect()->route('thanks-you-forfait');
  }

  public function errorPayment($key_token) {
    $PaymentOrder = ForfaitsOrderPayments::where('key_token', $key_token)->first();
    if ($PaymentOrder) {
      $amount = ($PaymentOrder->amount / 100) . ' €';
      $bookID = $PaymentOrder->book_id;
      if (!$bookID) $bookID = -1;
      \App\BookLogs::saveLogStatus($bookID, null, $PaymentOrder->cli_email, "Error en Pago de Forfaits de $amount ($key_token)");
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
    
    return die('404');
  }
  
  function listOrders(){
    
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    $allOrders = ForfaitsOrders::whereNotNull('total')->get();
//    $allOrders = ForfaitsOrders::where('book_id','>',0)->get();
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
          
          $book = Book::find($order->book_id);
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
                'id'       => $order->id,
                'name'       => $order->name,
                'email'       => $order->email,
                'phon'       => $order->phone,
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
      $rooms = \App\Rooms::all();
      
      $obj1  = $this->getMonthlyData($year);
      
      $balance = $this->getBalance();
      $ff_mount = 0;
      if (isset($balance->success) && $balance->success ){
        $ff_mount = $balance->data->total;
      }
      
      $errors = [];
//      $errors = DB::table('forfaits_errors')->whereNull('watched')->get();
         
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
    public function renderOrder($orderID = -1)
    {
      $order = ForfaitsOrders::find($orderID);
      $orderItems = $this->getCart($order);
      if ($orderItems){
        $text = '';
        if (count($orderItems['forfaits'])){
          foreach ($orderItems['forfaits'] as $f){
            if ( isset($f['usr']) ){
              foreach ($f['usr'] as $usr){
                $text .= '<tr><td>'
                        .'Forfaits '.$usr->days.' Dias'
                        .'<br/>'.$usr->typeTariffName
                        . '<br/>Edad: '.$usr->age
                        . '<br/>Inicio: '.$usr->dateFrom
                        . '<br/>Fin: '.$usr->dateFrom
                        . '</td><td  class="tcenter">1</td><td class="tright">'.$usr->price.'€</td></tr>';
              }
            }
            if ( isset($f['insur']) ){
              foreach ($f['insur'] as $insur){
                $text .= '<tr><td>'
                        .$order->getInsurName($insur->insuranceId)
                        . '<br/>'.$insur->clientName
                        . '<br/>DIN: '.$insur->clientDni
                        . '<br/>Inicio: '.$insur->dateFrom
                        . '<br/>Fin: '.$insur->dateFrom
                        . '</td><td class="tcenter">1</td><td class="tright">'.$insur->price.'€</td></tr>';
              }
            }
          }
        }
        
        
        if (count($orderItems['materials'])){
          foreach ($orderItems['materials'] as $m){
                $text .= '<tr><td>'
                        .$m->item->name.' '.$m->item->type
                        . '<br/>Días: '.$m->total_days
                        . '<br/>Inicio: '.date('d/m/Y', strtotime($m->date_start))
                        . '<br/>Fin: '.date('d/m/Y', strtotime($m->date_end))
                        . '</td><td  class="tcenter">'.$m->nro.'</td><td class="tright">'.number_format($m->total, 2).'€</td></tr>';
          }
        }
        
        if (count($orderItems['classes'])){
          foreach ($orderItems['classes'] as $c){
                $text .= '<tr><td>'
                        .'Clase:'.$c->item->name.' '.$c->item->type
                        . '<br/>Fecha: '.date('d/m/Y', strtotime($c->date_start));
                
                if ($c->start){
                  $text .= 'Inicio '.$c->start.':00 Hrs | '.$c->hours.' Horas';
                } else {
                  $text .= 'al '.date('d/m/Y', strtotime($c->date_end)).''
                          . '('.$c->total_days.' días)';
                  
                }
                $text .=   '<br/>Idioma:'.$c->language;
                if (isset($c->level)) $text .= '<br/>Nivel:'.$c->level;
                
                $text .= '</td><td class="tcenter">'.$c->nro.'</td><td class="tright">'.number_format($c->total, 2).'€</td></tr>';
          }
        }
        
        if ($orderItems['totalForfOrig'] != $orderItems['totalForf']){
            $resume = '<tr>
                    <td ><b>SubTotal Forfaits</b></td>
                    <td class="center" style="text-decoration:line-through;">'.number_format($orderItems['totalForfOrig'], 2).'€</td>
                    <td class="tright">'.number_format($orderItems['totalForf'], 2).'€</td>
                  </tr>';
        } else {
            $resume = '<tr>
                    <td colspan="2"><b>SubTotal Forfaits</b></td>
                    <td class="tright">'.number_format($orderItems['totalForf'], 2).'€</td>
                  </tr>';
        }
        
        
         $resume .= '<tr>
                    <td colspan="2"><b>SubTotal Materiales</b></td>
                    <td class="tright">'.number_format($orderItems['totalMat'], 2).'€</td>
                  </tr><tr>
                    <td colspan="2"><b>SubTotal Clases</b></td>
                    <td class="tright">'.number_format($orderItems['totalClas'], 2).'€</td>
                  </tr><tr>
                    <td colspan="2"><b>Total</b></td>
                    <td class="tright">'.number_format($orderItems['totalPrice'], 2).'€</td>
                  </tr>';
        
      }
      return '<table class="forfait">
                  <tr class="forfaitHeader">
                  <th>Item</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  </th>'.$text.$resume.' 
                  </table>';
     
    }
   
    public function sendClientEmail($orderKey, $control) {
      $orderID = desencriptID($orderKey);
      if (is_numeric($orderID) &&  $control == getKeyControl($orderID)){
        //send email
        $order = ForfaitsOrders::find($orderID);
        if ($order){
          $book = Book::find($order->book_id);
          $orderText = $this->renderOrder($order->id);
          
          
          $book = Book::find($order->book_id);
          if ($book){
            $cli_email = $book->customer->email;
            $cli_name = $book->customer->name;
            $subject = translateSubject('Solicitud Forfait',$book->customer->country);
          } else {
            $cli_name = $order->name;
            $cli_email = $order->email;
            $subject = 'Solicitud Forfait';
          }
                  
          $link = env('FF_PAGE').$orderKey.'-'.$control;
          $this->sendEmail_linkForfaitPayment($cli_email,$cli_name,$subject,$orderText,$link,$book);
          return response()->json(['status' => 'ok']);
        }
      }
      return response()->json(['status' => 'error']);
    }
    
    /**
     * Send email to the Forfait-admin with the canceled Item
     * 
     * @param type $order
     * @param type $item
     * @return
     */
    public function sendEmailCancelForfait($order,$item) {

      if ($order && $item) {
        //send email
        $forfaits = json_decode($item->data);
        $insurances = json_decode($item->insurances);
        $text = '';
        if ($forfaits){
          foreach ($forfaits as $usr){
            $text .= '<tr><td>'
              .'Forfaits '.$usr->days.' Dias'
              .'<br/>'.$usr->typeTariffName
              . '<br/>Edad: '.$usr->age
              . '<br/>Inicio: '.$usr->dateFrom
              . '<br/>Fin: '.$usr->dateFrom
              . '</td><td  class="tcenter">1</td><td class="tright">'.$usr->price.'€</td></tr>';
          }
        }
        if ($insurances){
          foreach ($insurances as $insur){
            $text .= '<tr><td>'
                    .$order->getInsurName($insur->insuranceId)
                    . '<br/>'.$insur->clientName
                    . '<br/>DIN: '.$insur->clientDni
                    . '<br/>Inicio: '.$insur->dateFrom
                    . '<br/>Fin: '.$insur->dateFrom
                    . '</td><td class="tcenter">1</td><td class="tright">'.$insur->price.'€</td></tr>';
              }
            }
        
        $orderText = '<table class="forfait">
                  <tr class="forfaitHeader">
                  <th>Item</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  </th>'.$text.'</table>';
        
        if ($order){
          $link = env('FF_PAGE').encriptID($order->id).'-'.getKeyControl($order->id);
          $this->sendEmail_CancelForfaitItem($orderText,$link);
          return response()->json(['status' => 'ok']);
        }
      }
      return response()->json(['status' => 'error']);
    }
}
