<?php

namespace App\Traits;

use App\Settings;
use Carbon\Carbon;
use App\Book;
use App\Repositories\CachedRepository;
use App\Models\Forfaits\ForfaitsOrderPayments;
use App\Models\Forfaits\ForfaitsOrderPaymentLinks;
use App\Models\Forfaits\ForfaitsOrders;
use App\Models\Forfaits\Forfaits;
use App\Models\Forfaits\ForfaitsOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\BookEmailsStatus;

trait ForfaitsPaymentsTraits {

  use BookEmailsStatus;
  
  var $listOrders = [];
  
  public function createPaylandsOrder($key) {
    
    $oForfait = Forfaits::getByKey($key);
    if (!$oForfait) {
      return 'error';
    }
        
    $order = ForfaitsOrders::where('forfats_id',$oForfait->id)->where('status',0)->first();
    if (!$order) {
      return 'error';
    }
    $bookingID = $oForfait->book_id;
    $book = Book::find($bookingID);
    if ($book){
      $cli_email = $book->customer->email;
    } else {
      $cli_email = $oForfait->email;
    }
        
//          $totalPayment =  ForfaitsOrderPayments::where('order_id', $order->id)->where('paid',1)->sum('amount');
//          if ($totalPayment>0){
//            $totalPayment = $totalPayment/100;
//          }
//          $amount = $order->total - $totalPayment;
    $amount = $order->total;
    if ($amount<0){
      $amount = 0;
    }
        
    if ($amount<=0) return 'error';
        
    $description = 'Pago por orden de Forfaits';
    $token = md5('linktopay'.$bookingID.'-'.time().'-'.$order->id);
    
    $PaymentOrders = new ForfaitsOrderPaymentLinks();
    $PaymentOrders->book_id = $bookingID;
    $PaymentOrders->order_id = $order->id;
    $PaymentOrders->cli_email = $cli_email;
    $PaymentOrders->subject = $description;
    $PaymentOrders->amount = $amount;
    $PaymentOrders->status = 'new';
    $PaymentOrders->token = $token;
    $PaymentOrders->last_item_id = null;
    $PaymentOrders->save();
    
   
    $order->status=1;
    $order->save();
        
    $this->listOrders = [];
    if ($oForfait){
      $oForfait->status = 2;
      $oForfait->save();
      
      $book = Book::find($oForfait->book_id);
      if ($book){
        $book->ff_status = 2;
        $book->save();
      }
      
      $orderLst = $oForfait->orders()->get();
      if ($orderLst){
        foreach ($orderLst as $item){
          $orders[] = ['id'=>$item->id,'total'=>$item->total,'status'=>$item->status];
        }          
      }
      $this->listOrders = $orders;
    }
    
    
    return $token;
  }
  
  /**
   * 
   * @param Request $req
   * @return type
   */
  public function createPayment(Request $req) {

    $token = $req->header('token-ff');
    $client = $req->header('client');
    $token = $req->input('token', null);
    $key = $req->input('key', null);

    if ($key) {
        $token = $this->createPaylandsOrder($key);
        
        if (!$token || $token == 'error') return die('404');
        
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
          
          
          return response()->json([
              'status' => 'ok',
              'url' => $urlPayland,
              'orders_list'=>$this->listOrders
                  ]);
        }
      }
    return die('404');
  }

  public function createPaylandsUrl(Request $req) {
     
    $token = $req->header('token-ff');
    $client = $req->header('client');
    if (!$this->checkUserAdmin($token,$client)) return die('404');
    
    $key = $req->input('key', null);
    

    if ($key) {
        $token = $this->createPaylandsOrder($key);
        if (!$token || $token == 'error') return die('404');

        $payment = ForfaitsOrderPaymentLinks::where('token',$token)->first();
        if ($payment){
        
          if (env('APP_APPLICATION') == "riad" || env('APP_APPLICATION') == "miramarLocal"){
            $urlPay = route('front.payments.forfaits',$token);
          } else {
            $urlPay = 'https://miramarski.com/payments-forms-forfaits?t='.$token;
          }
          
          $amount = $payment->amount;
          return response()->json([
              'status' => 'ok',
              'content' => $this->getPaymentText($urlPay,$amount),
              'orders_list'=>$this->listOrders
                  ]);
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

    $PaymentOrder = ForfaitsOrderPayments::where('key_token', $key_token)->whereNull('paid')->first();
    if ($PaymentOrder) {
      $PaymentOrder->paid = true;
      $PaymentOrder->save();
      $amount = ($PaymentOrder->amount / 100) . ' €';
      
      $bookID = $PaymentOrder->book_id;
      if (!$bookID) $bookID = -1;
      
      \App\BookLogs::saveLogStatus($bookID, null, $PaymentOrder->cli_email, "Pago de Forfaits de $amount ($key_token)");
      $order = ForfaitsOrders::find($PaymentOrder->order_id);
      $successful = false;
      if ($order){
        $oForfait = Forfaits::getByBook($PaymentOrder->book_id);
        $this->sendBookingOrder([$order->id],$oForfait,null);
        $orderStatus = null;
        $totalPrice = $order->total;
        $order->status = 2; //cobrada
        $order->save();
         
        $orderText = $this->renderOrder($PaymentOrder->order_id);
        $successful = true;
       
      } else {
        $oForfait = Forfaits::find($PaymentOrder->forfats_id);
        if ($oForfait){
          $allOrders = $oForfait->orders()->get();
          $ordersLst = [];
          $lastOrderId = $PaymentOrder->last_item_id;
          foreach ($allOrders as $order){
            if ($order->status == 1 && $order->id <=$lastOrderId){
               $ordersLst[] = $order->id;
               $order->status = 2;
               $order->save();
            }
          }
          $this->sendBookingOrder($ordersLst,$oForfait,null);
          $orderText = $this->renderOrderList($ordersLst);
          $successful = true;
        }
      }
      
      
      
      
      if ($successful){
       /** Check booking Status */
        $book_status = $oForfait->checkStatus();
        if ($book_status){
          $oForfait->status = $book_status;
          $oForfait->save();
        }

        //send email
        $book = Book::find($PaymentOrder->book_id);
        if ($book){
          $cli_email = $book->customer->email;
          $cli_name = $book->customer->name;
          if ($book_status){
            $book->ff_status = $book_status;
            $book->save();
          }
          $subject = translateSubject('Confirmación de Pago',$book->customer->country);
        } else {
          $cli_name = $oForfait->name;
          $cli_email = $oForfait->email;
          $subject = 'Confirmación de Pago';
        }
        
       
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
    $dir = storage_path().'/payland';
    if (!file_exists($dir)) {
        mkdir($dir, 0775, true);
    }
    file_put_contents($dir."/$id-".time(), $id."\n". json_encode($request->all()));
    die('ok');
  }

  public function paymentsForms($token) {
        
    if ($token){
      $payment = ForfaitsOrderPaymentLinks::where('token',$token)->first();
      if ($payment){
        
        $order = ForfaitsOrders::find($payment->order_id);
        if (!$order) return redirect()->route('paymeny-error');
        
        
        if ($order->type){
          //http://miramarski.virtual/payments-forms-forfaits/5a65d2a727d1c85de7ee298e3993c7b1
            
            $orderText ='<h3>DETALLE DE TU COMPRA</h3>
                  <table class="forfait">
                  <tr class="forfaitHeader">
                  <th colspan="2"><b>'.ucfirst($order->type).' ('.$order->quantity.')</th>
                  </th>
                  <tr>
                  <td colspan="2">'.nl2br($order->detail).'</td>
                  </tr>
                  <tr>
                  <td >Total</td>
                  <td class="tright">'.$order->total.'€</td>
                  </tr>
                  </table>';
            
          } else {
            //http://miramarski.virtual/payments-forms-forfaits/fe4353212db091a5539106dda28ed83e
            $orderText = '<h3>Forfaits</h3>';
            $orderText .= $this->renderOrder($order->id);
          }
          
        
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

        
         return view('frontend.bookStatus.paylandPayForfait', [
             'urlPayland' => $urlPayland,
             'background'=>$background,
             'orderText' => $orderText]);
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
      
      $oForfait = Forfaits::getByKey($key);
      if (!$oForfait) {  return 'error';  }
    
      $orders = [];
      $orderLst = $oForfait->orders()->get();
      if ($orderLst){
        foreach ($orderLst as $item){
          $orders[] = $item->id;
        }          
      }
      $payments =  ForfaitsOrderPayments::whereIn('order_id', $orders)->where('paid',1)->get();
      $paymentsLst = [];
      if ($payments){
        foreach ($payments as $p){
          $paymentsLst[] = [
              'order_id' => $p->order_id,
              'date' => $p->updated_at->format('d M, y'),
              'amount' => $p->amount/100,
          ];
        }
      }
      $payments =  ForfaitsOrderPayments::where('forfats_id', $oForfait->id)->where('paid',1)->get();
      if ($payments){
        foreach ($payments as $p){
          $paymentsLst[] = [
              'order_id' => '-',
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
    
    $allForfaits = Forfaits::where('status','!=',1)
            ->where('created_at', '>=', $startYear)->where('created_at', '<=', $endYear)->get();

    $lstOrders = [];
   
    $totals = [
        'orders'      => 0,
        'totalSale'   => 0,
        'totalPrice'  => 0,
        'forfaits'    => 0,
        'class'       => 0,
        'material'    => 0,
        'quick_order' => 0,
        'totalPayment'=> 0,
        'totalToPay'  => 0,
      ];
            
    if ($allForfaits){
      foreach ($allForfaits as $forfait){
          
          $book = Book::find($forfait->book_id);
          $allOrders = $forfait->orders()->get();
          $quick_order = 0;
          $totalPrice = 0;
          $totalPayment = 0;
          $forfaits = $class = $material = 0;
          $common_ordersID = [];
          $ordersID = [];
          $ff_sent = NULL;
          $ff_item_total = NULL;
          if ($allOrders){
            foreach ($allOrders as $order){
              
              if ($order->status == 3){
                continue; //ORder cancel
              }
              
              if ($order->quick_order){
                $quick_order += $order->total;
              } else {
                $common_ordersID[] = $order->id;
              }
                
              $ordersID[] = $order->id;
              $totalPrice += $order->total;
            }
            
            if (count($common_ordersID)>0){
              
              $forfaits = ForfaitsOrderItem::whereIn('order_id',$common_ordersID)->where('type', 'forfaits')->WhereNull('cancel')->sum('total');
              $class = ForfaitsOrderItem::whereIn('order_id',$common_ordersID)->where('type', 'class')->WhereNull('cancel')->sum('total');
              $material = ForfaitsOrderItem::whereIn('order_id',$common_ordersID)->where('type', 'material')->WhereNull('cancel')->sum('total');
              
            }
            
            if (count($ordersID)>0){
              
              $totalPayment =  ForfaitsOrderPayments::whereIn('order_id', $ordersID)->where('paid',1)->sum('amount');
              
              if ($totalPayment>0){
                $totalPayment = $totalPayment/100;
              }
              $totalPayment2 =  ForfaitsOrderPayments::where('forfats_id', $forfait->id)->where('paid',1)->sum('amount');
              
              if ($totalPayment2>0){
                $totalPayment += $totalPayment2/100;
              }
              
              $ff_sent = ForfaitsOrderItem::where('order_id', $order->id)
                      ->where('type', 'forfaits')
                      ->where('ffexpr_status', 1)
                      ->WhereNull('cancel')->count();
              $ff_item_total = ForfaitsOrderItem::where('order_id', $order->id)
                      ->where('type', 'forfaits')
                      ->WhereNull('cancel')->count();
              
              
            }
            
            $totalToPay = $totalPrice - $totalPayment;
//dd($forfait);
              $lstOrders[] = [
                  'id'       => $forfait->id,
                  'name'       => $forfait->name,
                  'email'       => $forfait->email,
                  'phon'       => $forfait->phone,
                  'book'       => $book,
                  'totalPrice' =>$totalPrice,
                  'forfaits'   => $forfaits,
                  'class'      => $class,
                  'material'   => $material,
                  'quick_order'   => $quick_order,
                  'totalPayment'=> $totalPayment,
                  'totalToPay'  =>$totalToPay,
                  'ff_sent'     => $ff_sent,
                  'status'     => $forfait->get_ff_status(),
                  'ff_item_total'=> $ff_item_total,
              ];
//              if (true){  /** @FFToDo no mostrar las canceladas**/
              
                $totals['orders']++;
                $totals['totalSale'] = $totals['totalSale']+$totalPrice;
                $totals['totalPrice'] = $totals['totalPrice']+$totalPrice;
                $totals['forfaits'] = $totals['forfaits']+$forfaits;
                $totals['class'] = $totals['class']+$class;
                $totals['material'] = $totals['material']+$material;
                $totals['quick_order'] = $totals['quick_order']+$quick_order;
                $totals['totalPayment'] = $totals['totalPayment']+$totalPayment;
                if ($totalToPay>0)
                $totals['totalToPay'] = $totals['totalToPay']+$totalToPay;
          }
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
         
      $booksCheckin = Book::where('start', date('Y-m-d'))->where('type_book', 2)->count();
      $ff_checkin = 0;
      if ($booksCheckin>0 && count($lstOrders)>0){
        $ff_checkin = ceil($booksCheckin/(count($lstOrders))*100);
      }
                
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
          'ff_checkin'=> $ff_checkin,
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
                        . '<br/>Fin: '.$usr->dateTo
                        . '</td><td  class="tcenter">1</td><td class="tright">'.$usr->price.'€</td></tr>';
              }
            }
            if ( isset($f['insur']) ){
              foreach ($f['insur'] as $insur){
                $text .= '<tr><td>'
                        .$order->getInsurName($insur->insuranceId)
                        . '<br/>'.$insur->clientName
                        . '<br/>DNI: '.$insur->clientDni
                        . '<br/>Inicio: '.$insur->dateFrom
                        . '<br/>Fin: '.$insur->dateTo
                        . '</td><td class="tcenter">1</td><td class="tright">'.$insur->price.'€</td></tr>';
              }
            }
          }
        }
        
        if ($orderItems['extraForfait']>0){
          $text .= '<tr><td>Extra'
                  . '</td><td class="tcenter">1</td><td class="tright">' . $orderItems['extraForfait'] . '€</td></tr>';
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
                    <td ><b>Total Forfaits </b></td>
                    <td class="center" style="text-decoration:line-through;">'.number_format($orderItems['totalForfOrig'], 2).'€</td>
                    <td class="tright">'.number_format($orderItems['totalForf'], 2).'€</td>
                  </tr>';
        } else {
            $resume = '<tr>
                    <td colspan="2"><b>Total Forfaits </b></td>
                    <td class="tright">'.number_format($orderItems['totalForf'], 2).'€</td>
                  </tr>';
        }
        

        
      }
      return '<table class="forfait">
                  <tr class="forfaitHeader">
                  <th>Item</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  </th>'.$text.$resume.' 
                  </table>';
     
    }
    
    
    /**
     * Render all orders by OrderIDs array
     * 
     * @param type $orderIDs
     * @return type
     */
    public function renderOrderList($orderIDs)
    {
      $orders = ForfaitsOrders::whereIn('id',$orderIDs)->where('quick_order',1)->get();
      $oOrder = new ForfaitsOrders();
      $text_ff = $textQ = '';
      $totalOrders = 0;
      $totalForf = $price_wdForf = $extraForfait = 0;
      foreach ($orders as $order){
        $textQ .= '<tr><td>'
                        .ucfirst($order->type)
                        .'<br/>'.nl2br($order->detail)
                        . '</td><td  class="tcenter">'.$order->quantity.'</td><td class="tright">'.$order->total.'€</td></tr>';
      
        $totalOrders += $order->total;
      }
      
      
      $ordersItems = ForfaitsOrderItem::whereIn('order_id',$orderIDs)
              ->where('type','forfaits')
              ->whereNull('cancel')
              ->get();
      
      foreach ($ordersItems as $ffItem){
        $ffUsr = json_decode($ffItem->data);
        if ( isset($ffUsr) ){
          foreach ($ffUsr as $usr){
            $text_ff .= '<tr><td>'
                    .'Forfaits '.$usr->days.' Dias'
                    .'<br/>'.$usr->typeTariffName
                    . '<br/>Edad: '.$usr->age
                    . '<br/>Inicio: '.$usr->dateFrom
                    . '<br/>Fin: '.$usr->dateTo
                    . '</td><td  class="tcenter">1</td><td class="tright">'.$usr->price.'€</td></tr>';
          }
        }
        $insurances = json_decode($ffItem->insurances);
        if ( isset($insurances) ){
          foreach ($insurances as $insur){
            $text_ff .= '<tr><td>'
                    .$oOrder->getInsurName($insur->insuranceId)
                    . '<br/>'.$insur->clientName
                    . '<br/>DNI: '.$insur->clientDni
                    . '<br/>Inicio: '.$insur->dateFrom
                    . '<br/>Fin: '.$insur->dateTo
                    . '</td><td class="tcenter">1</td><td class="tright">'.$insur->price.'€</td></tr>';
          }
        }
        
        if ( $ffItem->extra > 0){
           $text_ff .= '<tr><td>Extra'
                    . '</td><td class="tcenter">1</td><td class="tright">'.$ffItem->extra.'€</td></tr>';
        }
        $totalForf += $ffItem->total+$ffItem->extra;
        $price_wdForf += $ffItem->price_wd+$ffItem->extra;
      }
      
      $totalOrders += $totalForf;
      
      $resume = '';
      if ($totalForf>0){
        if ($totalForf != $price_wdForf){
              $resume = '<tr>
                      <td ><b>SubTotal Forfaits </b></td>
                      <td class="center" style="text-decoration:line-through;">'.number_format($price_wdForf, 2).'€</td>
                      <td class="tright">'.number_format($totalForf, 2).'€</td>
                    </tr>';
          } else {
              $resume = '<tr>
                      <td colspan="2"><b>SubTotal Forfaits </b></td>
                      <td class="tright">'.number_format($totalForf, 2).'€</td>
                    </tr>';
        }
      }
      
      $resume .= '<tr>
                    <td colspan="2"><b>Total </b></td>
                    <td class="tright">'.number_format($totalOrders, 2).'€</td>
                  </tr>';
        
      return '<table class="forfait">
                  <tr class="forfaitHeader">
                  <th>Item</th>
                  <th>Cantidad</th>
                  <th>Precio</th>
                  </th>'.$text_ff.$textQ.$resume.' 
                  </table>';
      
      
      print_r($orderText); die;
    }
   
    public function sendOrdenToClient(Request $req) {
      
      $key = $req->input('key', null);
      $type = $req->input('type', 'mail');
      $oID = $req->input('oID', null);
               
      $token = $req->header('token-ff');
      $client = $req->header('client');
      if (!$this->checkUserAdmin($token,$client)) return die('404');
    
      $oForfait = Forfaits::getByKey($key);
      if (!$oForfait) {
        return response()->json(['status' => 'error1']);
      }
      
      if ($oForfait){
          $order = ForfaitsOrders::where('forfats_id',$oForfait->id)->where('id',$oID)->first();
//          if (!$order) {
//            return response()->json(['status' => 'error2']);
//          }
          
          $book = Book::find($oForfait->book_id);
          if ($book){
            $cli_email = $book->customer->email;
            $cli_name = $book->customer->name;
            $phone = $book->customer->phone;
            $subject = translateSubject('Solicitud Forfait',$book->customer->country);
          } else {
            $cli_name = $oForfait->name;
            $cli_email = $oForfait->email;
            $phone = $oForfait->phone;
            $subject = 'Solicitud Forfait';
          }
           
          /** @FFToDo habilitar link**/
//          $link = env('FF_PAGE').$orderKey.'-'.$control;
//          $link = '';
          $urlPay = '';
          $amount = 'NaN';
          if ($order) {
            $payment = ForfaitsOrderPaymentLinks::where('order_id',$oID)->first();
            if ($payment){

              if (env('APP_APPLICATION') == "riad" || env('APP_APPLICATION') == "miramarLocal"){
                $urlPay = route('front.payments.forfaits',$payment->token);
              } else {
                $urlPay = 'https://miramarski.com/payments-forms-forfaits?t='.$payment->token;
              }
              $amount = $payment->amount;
            }
          } else {
            if (env('APP_APPLICATION') == "riad" || env('APP_APPLICATION') == "miramarLocal"){
              $urlPay = route('front.payments.forfaits.all',$key);
            } else {
              $urlPay = 'https://miramarski.com/payments-forms-forfaits?f='.md5(time()).'&t='.$key;
            }
            
            $amount = ForfaitsOrders::where('forfats_id',$oForfait->id)
                ->where('status',1)->sum('total');
            
          }
     
          if ($type == 'sms'){
            if (!$phone || trim($phone) == ''){
              return response()->json(['status' => 'error','msg'=>'el usuario no posee teléfono']);
            }
            //Send SMS
            $SMSService = new \App\Services\SMSService();
            if ($SMSService->conect()){

              $message = Settings::getContent('SMS_forfait');
              $message = str_replace('{customer_name}', $cli_name, $message);
              $message = str_replace('{link_forfait}', $urlPay, $message);
              $message = str_replace('{total_payment}', $amount, $message);
              $message = $this->clearVars($message);
              $message = strip_tags($message);

              if ($SMSService->sendSMS($message,$phone)){
                return response()->json(['status' => 'ok']);
              }
              return response()->json(['status' => 'error','msg'=>'No se pudo enviar el SMS']);
            } else {
              return response()->json(['status' => 'error','msg'=>'No se pudo conectar con el servicio de mensajería']);
            }
        
          }
          if ($type == 'text'){
            $message = $message_wsp = Settings::getContent('SMS_forfait');
            $message = str_replace('{customer_name}', $cli_name, $message);
            $message = str_replace('{link_forfait}', $urlPay, $message);
            $message = str_replace('{total_payment}', $amount, $message);
            $message = $this->clearVars($message);
            $message = strip_tags($message);
            return response()->json(['status' => 'ok','msg'=>$message,'wsp'=>$message]);
          }
          if ($type == 'mail'){
            
            if ($order) {
              if ($order->type){
                $orderText = $order->detail;
              } else {
                $orderText = $this->renderOrder($order->id);
              }
            } else {
              $allOrders = $oForfait->orders()->get();
              $ordersLst = [];
              foreach ($allOrders as $order){
                if ($order->status == 1){
                   $ordersLst[] = $order->id;
                }
              }
              $orderText = $this->renderOrderList($ordersLst);
            }
            
            $this->sendEmail_linkForfaitPayment($cli_email,$cli_name,$subject,$orderText,$urlPay,$book);
          }
          return response()->json(['status' => 'ok']);
        }
      return response()->json(['status' => 'error3']);
    }
    
    
    
    public function showOrder(Request $req) {
      
      $key = $req->input('key', null);
      $type = $req->input('type', 'mail');
      $oID = $req->input('oID', null);
               
      $token = $req->header('token-ff');
      $client = $req->header('client');
      if (!$this->checkUserAdmin($token,$client)) response()->json(['status' => 'error0']);
    
      $oForfait = Forfaits::getByKey($key);
      if (!$oForfait) {
        return response()->json(['status' => 'error1']);
      }
      
      if ($oForfait){
          $order = ForfaitsOrders::where('forfats_id',$oForfait->id)->where('id',$oID)->first();
          if (!$order) {
            return response()->json(['status' => 'error2']);
          }
          $urlPay = '';
          $payment = ForfaitsOrderPaymentLinks::where('order_id',$oID)->first();
          if ($payment){
        
            if (env('APP_APPLICATION') == "riad" || env('APP_APPLICATION') == "miramarLocal"){
              $urlPay = route('front.payments.forfaits',$payment->token);
            } else {
              $urlPay = 'https://miramarski.com/payments-forms-forfaits?t='.$payment->token;
            }
            $amount = $payment->amount;
          }
          if ($order->type){
             $orderText ='<h3>Orden Rápida</h3>
                  <table class="forfait">
                  <tr class="forfaitHeader">
                  <th colspan="2"><b>'.ucfirst($order->type).' ('.$order->quantity.')</th>
                  </th>
                  <tr>
                  <td colspan="2">'.nl2br($order->detail).'</td>
                  </tr>
                  <tr>
                  <td >Total</td>
                  <td class="tright">'.$order->total.'€</td>
                  </tr>
                  </table>';
          } else {
            $orderText = $this->renderOrder($order->id);
          }
          
          $orderText .="<p>Link del pago: <a href='$urlPay' target='_black'>$urlPay</a></p>";
     
          return response()->json(['status' => 'ok','msg'=>$orderText]);
        }
      return response()->json(['status' => 'error3']);
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
              . '<br/>Fin: '.$usr->dateTo
              . '</td><td  class="tcenter">1</td><td class="tright">'.$usr->price.'€</td></tr>';
          }
        }
        if ($insurances){
          foreach ($insurances as $insur){
            $text .= '<tr><td>'
                    .$order->getInsurName($insur->insuranceId)
                    . '<br/>'.$insur->clientName
                    . '<br/>DNI: '.$insur->clientDni
                    . '<br/>Inicio: '.$insur->dateFrom
                    . '<br/>Fin: '.$insur->dateTo
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
    
    public function sendClassToAdmin(Request $req) {
      $emailAdmin = $req->input('email');
      $token = $req->header('token-ff');
      $client = $req->header('client');
      $orderKey = $req->input('token', null);
      $control = $req->input('key', null);
      
      if (!($orderKey && $control && $token && $client)){
        return response()->json(['status' => 'error']);
      }
      if (!$this->checkUserAdmin($token,$client)){
        return response()->json(['status' => 'error']);
      }
      
      $orderID = desencriptID($orderKey);
      if (is_numeric($orderID) &&  $control == getKeyControl($orderID)){
        
        $order = ForfaitsOrders::find($orderID);
        $userData = [
          'user_name' => '',
          'user_email' => '',
          'user_phone' => '',
          'apto' => '',
          'start' => '',
          'finish' => '',
        ];
        $book = Book::find($order->book_id);
        if ($book) {
              $client = $book->customer()->first();
              if ($client) {
                $userData['user_name'] = $client->name;
                $userData['user_email'] = $client->email;
                $userData['user_phone'] = $client->phone;
              }
              $room = $book->room()->first();
              $userData['apto'] = ($room->luxury) ? $room->sizeRooms->name . " - LUJO" : $room->sizeRooms->name . " - ESTANDAR";
              $userData['start'] = $book->start;
              $userData['finish'] = $book->finish;
            } else {
              $userData['user_name'] = $order->name;
              $userData['user_email'] = $order->email;
              $userData['user_phone'] = $order->phone;
            }

        $textUser = '<tr class="forfaitHeader">
          <th colspan="6">DATOS CLIENTE</th>
          </tr>';

        $textUser .= '<tr>
          <th colspan="2">Nombre del Cliente</th>
          <td colspan="4">'.$userData['user_name'].' - '.$userData['user_email'].' - '.$userData['user_phone'].'</td>
          </tr>';
        if ($userData['start']){
          $textUser .= '<tr>
          <th colspan="2">In / Out</th>
          <td colspan="4">'.convertDateToShow($userData['start']).' / '.convertDateToShow($userData['finish']).'</td>
          </tr>';
        }
        if ($userData['apto']){
          $textUser .= '<tr>
          <th colspan="2">Apartamiento</th>
          <td colspan="4">'.$userData['apto'].'</td>
          </tr>';
        }


        $orderItems = $this->getCart($order);
        if ($orderItems){
          $text = '
          <tr class="forfaitHeader">
          <th colspan="6">CLASES</th>
          </tr>
          <tr class="">
            <th>Cant</th>
            <th>Nombre</th>
            <th>Cursado</th>
            <th>Idioma</th>
            <th>Nivel</th>
            <th>precio</th>
          </tr>

          ';

          if (count($orderItems['classes'])){

            foreach ($orderItems['classes'] as $c){
                  $text .= '<tr><td>'.$c->nro.'</td>'
                          .'<td>'.$c->item->name.' '.$c->item->type.'</td>'
                          . '<td>'.date('d/m/Y', strtotime($c->date_start));

                  if ($c->start){
                    $text .= '<br/>Inicio '.$c->start.':00 Hrs <br/> '.$c->hours.' Horas';
                  } else {
                    $text .= ' al '.date('d/m/Y', strtotime($c->date_end)).''
                            . '<br/> ('.$c->total_days.' días)';

                  }
                  $text .=   '</td><td>'.$c->language.'</td><td>';
                  if (isset($c->level)) $text .= $c->level;

                  $text .= '</td><td class="tright">'.number_format($c->total, 0,'','.').'€</td></tr>';
            }
          }



           $resume = '<tr>
                      <td colspan="5"><b>Total Clases</b></td>
                      <td class="tright">'.number_format($orderItems['totalClas'], 0,'','.').'€</td>
                    </tr>';

        }
        $orderText = '<table class="forfait">
                    '.$textUser.$text.$resume.' 
                    </table>';

           $subject = 'SOLICUTD RVA CLASES ( '.$userData['user_name'].' )';
           $this->sendEmail_ForfaitClasses($orderText,$emailAdmin,$subject);
           $order->sent_class = 'Enviado a '.$emailAdmin.' - '.date('d/m/Y H:i');
           $order->save();
          return response()->json(['status' => 'ok','text'=> $order->sent_class]);
        }

        return response()->json(['status' => 'error']);
      }
      
      
      
  function quickOrders(Request $req) {
    
    $key = $req->input('key', null);
    $amount = $req->input('amount', null);
    $quantity = $req->input('qty', null);
    $detail = $req->input('detail', null);
    $typePayment = $req->input('type', null);
    $p_type = $req->input('p_type', null);
      
    $token = $req->header('token-ff');
    $client = $req->header('client');
    if (!$this->checkUserAdmin($token,$client)) return die('404');
    
    
    if ($amount && $amount>0){
      
      $oForfait = Forfaits::getByKey($key);
      if (!$oForfait) {
        return 'error';
      }
        
      //create the new orden
      $order = new ForfaitsOrders();
      $order->forfats_id = $oForfait->id;
      $order->status = 1;
      $order->quantity = $quantity;
      $order->detail = $detail;
      $order->type = $p_type;
      $order->total = $amount;
      $order->quick_order = 1;
      $order->save();
      $bookingID = $oForfait->book_id;
      $book = Book::find($bookingID);
      if ($book){
        $cli_email = $book->customer->email;
      } else {
        $cli_email = $oForfait->email;
      }

        // craete the payment
        $description = 'Pago por orden de Forfaits';
        $token = md5('linktopay'.$bookingID.'-'.time().'-'.$order->id);

        $PaymentOrders = new ForfaitsOrderPaymentLinks();
        $PaymentOrders->book_id = $bookingID;
        $PaymentOrders->order_id = $order->id;
        $PaymentOrders->cli_email = $cli_email;
        $PaymentOrders->subject = $description;
        $PaymentOrders->amount = $amount;
        $PaymentOrders->status = 'new';
        $PaymentOrders->token = $token;
        $PaymentOrders->last_item_id = null;
        $PaymentOrders->save();

        if ($oForfait){
          $oForfait->status = 2;
          $oForfait->save();

          $book = Book::find($oForfait->book_id);
          if ($book){
            $book->ff_status = 2;
            $book->save();
          }

          $orderLst = $oForfait->orders()->get();
          if ($orderLst){
            foreach ($orderLst as $item){
              $orders[] = ['id'=>$item->id,'total'=>$item->total,'status'=>$item->status];
            }          
          }
          $this->listOrders = $orders;
        }
    

        if ($typePayment == 'link'){
          
          if (env('APP_APPLICATION') == "riad" || env('APP_APPLICATION') == "miramarLocal"){
            $urlPay = route('front.payments.forfaits',$token);
          } else {
            $urlPay = 'https://miramarski.com/payments-forms-forfaits?t='.$token;
          }
          return response()->json(['status' => 'ok',
              'content' => $this->getPaymentText($urlPay,$amount),
              'orders_list'=>$this->listOrders,
              'order_id'=>$order->id
                  ]);
          
        } else{
          
          $urlPayland = $this->generateOrderPaymentForfaits(
                  $PaymentOrders->book_id,
                  $PaymentOrders->order_id,
                  $PaymentOrders->cli_email,
                  $PaymentOrders->subject,
                  $PaymentOrders->amount,
                  $PaymentOrders->last_item_id
                  );
          return response()->json([
              'status' => 'ok',
              'url' => $urlPayland,
              'orders_list'=>$this->listOrders
                  ]);
          
        }
      
    
    }

    return response()->json(['status' => 'error']);
  }
  
  function getFFOrders(Request $req){
    
    $key = $req->input('key', null);

    $oForfait = Forfaits::getByKey($key);
    if (!$oForfait) {  return 'error';  }
    
    $orders = [];
    $ff_status = $oForfait->checkStatus();
    
    $orderLst = $oForfait->orders()->get();
    if ($orderLst){
      foreach ($orderLst as $item){
        $type = $item->type;
        if (!$type) $type = 'forfaits';
        if ($item->status != 3)
        $orders[] = ['id'=>$item->id,'type'=>ucfirst($type),'total'=>$item->total,'status'=>$item->status];
      }          
    }
    
    return response()->json([
        'status'=>'ok',
        'orders_list' => $orders,
        'ff_status' => $ff_status,
        'resume'=>$oForfait->resume()
            ]);
  }
  
  function removeOrder(Request $req) {
    
    $key = $req->input('key', null);
    $ordenID = $req->input('orden', null);

    $oForfait = Forfaits::getByKey($key);
    if (!$oForfait) {  return 'error';  }
    if (!$ordenID || $ordenID<1) {  return 'error';  }
    
    $orders = [];
    $ff_status = 0;
    $orderLst = $oForfait->orders()->get();
    if ($orderLst){
      foreach ($orderLst as $item){
        if ($ordenID == $item->id){
          $item->status = 3;
          $item->save();
        }
//        if ($item->status!=3)
//          $orders[] = ['id'=>$item->id,'total'=>$item->total,'status'=>$item->status];
      }          
    }
    $ff_status = $oForfait->checkStatus();
    if ($ff_status){
      $oForfait->status = $ff_status;
      $oForfait->save();
      
      $book = Book::find($oForfait->book_id);
      if ($book){
        $book->ff_status = $ff_status;
        $book->save();
      }
          
          
    }
    
    return response()->json([
        'status'=>'ok',
        'orders_list' => null,
        'ff_status' => $ff_status
            ]);
  }
  
  function ordersHistory(Request $req){

    $key = $req->input('key', null);

    $forfait = Forfaits::getByKey($key);
    if (!$forfait) {  return 'error';  }
    
    else{
      $orders = $forfait->orders()->get();
      $lstOrderResponse = [
          'quick_order' => [],
          'forfaits'    => []
      ];
      $totalItemsQ = $totalItemsF = 0;
      $totalForf = $price_wdForf = $extraForfait = $totalQO = 0;
      if ($orders && count($orders)>0){
        foreach ($orders as $order){
          $forfaits = [];
          $aux = [
            'order' => $order->id,
            'total' => $order->total,
            'forfaits'=>null,
            'quick_order'=>null,
          ];
          
          if ($order->quick_order){
            $totalItemsQ++;
            $aux['quick_order'] = [
                'type'=>$order->type,
                'q'=>$order->quantity,
                'd'=>$order->detail
            ];
            $totalQO += $order->total;
          $lstOrderResponse['quick_order'][] = $aux;
          } else {
            $forfaitsLst = ForfaitsOrderItem::where('order_id', $order->id)->where('type', 'forfaits')->WhereNull('cancel')->get();
            if ($forfaitsLst) {
              foreach ($forfaitsLst as $f) {
                $f_aux = json_decode($f->data);
                $insurances = json_decode($f->insurances);
                $totalItemsF += count($f_aux);
                $forfaits[$f->id] = ['usr'=>$f_aux,'insur'=>$insurances];
                $totalForf += $f->total;
                $price_wdForf += $f->price_wd;
                $extraForfait += $f->extra;
              }
            }
            $aux['forfaits'] = $forfaits;
            $lstOrderResponse['forfaits'][] = $aux;
          }
          
        }
      }
      $result = [
        'status' => 'ok',
        'orders' => $lstOrderResponse,
        'totalItemsQ' => $totalItemsQ,
        'totalItemsF' => $totalItemsF,
        'totalForf' => $totalForf,
        'totalQO' => $totalQO,
        'price_wdForf' => $price_wdForf,
        'extraForfait' => $extraForfait,
      ];
      
      return response()->json($result);
    }
    return response()->json(['status' => 'error']);
  }
  
  /**
   * Show the payment widget to payland
   * 
   * @param Request $req
   * @return string
   */
   function getPayment(Request $req) {
    
    $key = $req->input('key', null);
    $ordenID = $req->input('order', null);

    $oForfait = Forfaits::getByKey($key);
    if (!$oForfait) {  return 'error 1';  }
    if (!$ordenID || $ordenID<1) {  return 'error 2';  }
    
    $order = ForfaitsOrders::where('forfats_id',$oForfait->id)
            ->where('status',1)
            ->where('id',$ordenID)->first();
    
    if (!$order) return 'error 3';
    $payment = ForfaitsOrderPaymentLinks::where('order_id',$order->id)->first();
    if ($payment){
      $urlPayland = $this->generateOrderPaymentForfaits(
              $payment->book_id,
              $payment->order_id,
              $payment->cli_email,
              $payment->subject,
              $payment->amount,
              $payment->last_item_id
              );

      return response()->json([
              'status' => 'ok',
              'url' => $urlPayland
                  ]);
    }
        
        
    return 'error 4';
  }
  
  public function getResumeBy_book($id) {
    $oForfait = Forfaits::where('book_id',$id)->first();
    if ($oForfait){
      $resume = $oForfait->resume();
      echo $resume;
    } else {
      echo '<p>Sin datos</p>';
    }
  }
  public function getResume($id) {
    $oForfait = Forfaits::find($id);
    if ($oForfait){
      $resume = $oForfait->resume();
      echo $resume;
    } else {
      echo '<p>Sin datos</p>';
    }
  }
  
  
  /**
   * Pay all no-payment orders
   * 
   * @param type $token
   * @return type
   */
  public function paymentsFormsAll($token) {
        
    if ($token){
      $oForfait = Forfaits::getByKey($token);
      if (!$oForfait) {
        return redirect()->route('paymeny-error');
      }
      
      if ($oForfait){
          $book_id = $oForfait->book_id;
          $book = Book::find($book_id);
          if ($book){
            $cli_email = $book->customer->email;
          } else {
            $cli_email = $oForfait->email;
          }
           
          $allOrders = $oForfait->orders()->get();
          $ordersLst = [];
          $lastOrderId = null;
          foreach ($allOrders as $order){
            if ($order->status == 1){
               $ordersLst[] = $order->id;
               $lastOrderId = $order->id;
            }
          }
           

        $amount = ForfaitsOrders::whereIn('id',$ordersLst)->sum('total');
        $orderText = $this->renderOrderList($ordersLst);
           
        $urlPayland = $this->generateOrderPaymentForfaits(
                $book_id,
                -1,
                $cli_email,
                'Pago de Forfaits',
                $amount,
                $lastOrderId,
                $oForfait->id
                );

        if (env('APP_APPLICATION') == "riad"){
          $background = assetV('img/riad/lockscreen.jpg');
        } else {
          $background = assetV('img/miramarski/lockscreen.jpg');
        }

        
         return view('frontend.bookStatus.paylandPayForfait', [
             'urlPayland' => $urlPayland,
             'background'=>$background,
             'orderText' => $orderText]);
      }
      return redirect()->route('paymeny-error');
    }
    return redirect()->route('paymeny-error');
  }
}
