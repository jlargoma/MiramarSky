<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\BookOrders;
use App\Traits\BookEmailsStatus;

class PaylandsController extends AppController
{
  use BookEmailsStatus;
    public function payment(Request $request){
          $booking = $request->input('booking', null);
          $amount = $request->input('amount', null);
          $is_deferred = $request->input('is_deferred', null); //fianza
          if ($booking && $amount){
            $aux = explode('-', $booking);
            if (is_array($aux) && count($aux) == 2){
              $bookingID = desencriptID($aux[1]);
              $clientID = desencriptID($aux[0]);
              
              $book = \App\Book::find($bookingID);
              if ($book){
                if ($book->customer_id == $clientID){
                  $client = $book->customer()->first();
                  $description = "COBRO RESERVA CLIENTE " . $client->name;
                  $client_email = 'no_email';
                  if ($client && trim($client->email)){
                    $client_email = $client->email;
                  }
          
                 
                  $urlToRedirect = $this->generateOrderPaymentBooking(
                          $bookingID,
                          $clientID,
                          $client_email,
                          $description,
                          $amount,
                          $is_deferred
                          );
                  return view('backend.bookStatus.bookPaylandPay', [ 'url' => $urlToRedirect]);
                } 
                return 'error 4';
              }
              return 'error 3';
            }
            return 'error 2';
          } 
          return 'error 1';
	}
        
        public function paymentTest() {
          $amount = 1;
           $urlToRedirect = $this->generateOrderPaymentBooking(
                          11,
                          22,
                          'test@tesset.com',
                          'test',
                          $amount
                          );
        }

    public function processPaymentBook(Request $request, $id, $payment)
    {
    $book = \App\Book::find($id);
    $this->payBook($id, $payment);
    return redirect()->route('book.update', ['id' => $book->id]);

    }

    public function link(Request $request)
    {
        if ($request->book != 0)
            $book = \App\Book::find($request->book);
        //$importe = base64_encode($request->importe);
        $params['amount']          = ($request->importe) * 100;
        $params['customer_ext_id'] = "admin@" . $_SERVER['REQUEST_URI'];
        $params['operative']       = "AUTHORIZATION";
        $params['secure']          = false;
        $params['signature']       = env('PAYLAND_SIGNATURE');
        $params['service']         = env('PAYLAND_SERVICE');
        $params['description']     = "COBRO ESTANDAR";
        $params['url_ok']          = route('thanks-you');
        $params['url_ko']          = route('thanks-you');

        $orderPayment  = $this->getPaylandApiClient()->payment($params);
        $urlToRedirect = $this->getPaylandApiClient()->processPayment($orderPayment->order->token);
        $url           = $urlToRedirect;
        return view('backend.bookStatus.bookPaylandPay', [ 'url' => $url]);
    }

    public function linkSingle(Request $request)
    {
      $amount = $request->input('importe',null);
      $subject= $request->input('subject',null);
      $bookingID = $request->input('book',null);
      
      $urlPay = $this->generateOrder($amount,$subject,$bookingID);
      if ($urlPay){
        return $this->getPaymentText($urlPay);
      }
      return 'error';
    }
    
    public function generateOrder($amount,$subject,$bookingID,$is_deferred=false) {
      $book = \App\Book::find($bookingID);
      $token = str_random(32).time();
      $urlPay = getUrlToPay($token);

      if ($amount){
        if ($book){
          $client = $book->customer()->first();
          $description = "COBRO RESERVA CLIENTE " . $client->name;
          $client_email = 'no_email';
          if ($client && trim($client->email)){
            $client_email = $client->email;
          }
          
          $PaymentOrders = new \App\PaymentOrders();
          $PaymentOrders->book_id = $bookingID;
          $PaymentOrders->cli_id = $client->id;
          $PaymentOrders->cli_email = $client_email;
          $PaymentOrders->amount = $amount;
          $PaymentOrders->status = 0;
          $PaymentOrders->token = $token;
          $PaymentOrders->description = $description;
          $PaymentOrders->is_deferred = $is_deferred;
          $PaymentOrders->save();
          return $urlPay;
        } else {
          $PaymentOrders = new \App\PaymentOrders();
          $PaymentOrders->book_id = -1;
          $PaymentOrders->cli_id = -1;
          $PaymentOrders->cli_email = env('PAYLAND_MAIL');
          $PaymentOrders->amount = $amount;
          $PaymentOrders->status = 0;
          $PaymentOrders->token = $token;
          $PaymentOrders->description = $subject;
          $PaymentOrders->is_deferred = $is_deferred;
          $PaymentOrders->save();
          
          return $urlPay;
        }
      }
      return null;
    }
    
    private function getPaymentText($urlPay,$is_deferred=false) {
      
      if ($is_deferred){
        $texto = 'En este link podrás realizar el pago de la Fianza.<br> En el momento en que efectúes el pago, te legará un email';
      } else {
        $texto = 'En este link podrás realizar el pago de la señal.<br> En el momento en que efectúes el pago, te legará un email';
      }
      
      $response = '<div class="col-md-2 col-xs-12">
              <h2 class="text-center" style="font-size: 18px; line-height: 18px; margin: 0;">

                  <a href="whatsapp://send?text='. str_replace('<br>', '&#10;',$texto).' - ' . $urlPay . '" data-action="share/whatsapp/share">
                      <i class="fa fa-whatsapp fa-3x" aria-hidden="true"></i>
                  </a>
              </h2>
          </div>
          <div class="col-md-10 col-xs-12">
              <h2 class="text-center" style="font-size: 24px; line-height: 15px">
                  <span style="font-size: 18px;">'.$texto.'</span><br>
                  <a target="_blank" href="' . $urlPay . '">
                      ' . substr($urlPay, 0, 45) . '...     
                  </a>
              </h2>
              <div class="row text-center">
                  <button class="btn btn-cons" type="button" id="copy-link-stripe" data-link="' . $urlPay . '">
                      <span class="bold">Copiar Link</span>
                  </button>  
                <input type="text" id="cpy_link" value="' . $urlPay . '" style="display:none;border: none;color: #fff;">
              </div>

          </div>';
      return $response;
    }

    public function thansYouPayment($key_token)
    {
      
      $bookOrder = BookOrders::where('key_token',$key_token)->whereNull('paid')->first();
      if ($bookOrder){
        $bookOrder->paid = true;
        $bookOrder->save();
        $amount = ($bookOrder->amount/100).' €';
        \App\BookLogs::saveLogStatus($bookOrder->book_id,null,$bookOrder->cli_email,"Pago de $amount ($key_token)");
        $book = \App\Book::find($bookOrder->book_id);
        if ($book){
          
           //temporalmente no envíe los mails
          $book->customer->send_mails = false;
          $book->customer->save();
          $this->payBook($bookOrder->book_id, $bookOrder->amount);
          $book->customer->send_mails = 1;
          $book->customer->save();
          
          //BEGIN: check if is a final payment
         

          if ($book->customer->send_notif){
            $subject = translateSubject('RECIBO PAGO RESERVA',$book->customer->country);
            $subject .= ' '. $book->customer->name;
            $this->sendEmail_confirmCobros($book,$subject,floatval($amount),$book->customer->email_notif);

          } else {
            
            //BEGIN: check if is a final payment
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
            if ($pendiente<=0){
              $subject = translateSubject('Confirmación de Pago',$book->customer->country);

              $subject .= ' '.env('APP_NAME').' '.$book->customer->name;
              $this->sendEmail_confirmSecondPayBook($book,$subject,$totalPayment);
            }
            //END: check if is a final payment
          }
        }
         
      }
      return redirect()->route('thanks-you');
        
    }
    public function errorPayment($key_token)
    {
      $bookOrder = BookOrders::where('key_token',$key_token)->first();
      if ($bookOrder){
        $amount = ($bookOrder->amount/100).' €';
        \App\BookLogs::saveLogStatus($bookOrder->book_id,null,$bookOrder->cli_email,"Error en Pago de $amount ($key_token)");
      }
      return redirect()->route('paymeny-error');
    }
    public function processPayment(Request $request, $id)
    {
      $dir = storage_path().'/payland';
      if (!file_exists($dir)) {
          mkdir($dir, 0775, true);
      }
      file_put_contents($dir."/$id-".time(), $id."\n". json_encode($request->all()));
      die('ok');

    }
    
    public function getOrders(Request $request, $isAjax = true) {
      
      
      
          $year = $request->input('year',null);
          $month = $request->input('month',null);
          if ($year && $month){
            // First day of a specific month
            $d = new \DateTime($year.'-'.$month.'-01');
            $d->modify('first day of this month');
            $startDate = $d->format('YmdHi');
             // First day of a specific month
            $d = new \DateTime($year.'-'.$month.'-01');
            $d->modify('last day of this month');
            $endDate = $d->format('Ymd').'2359';
          } else {
            $year = $this->getActiveYear();
            $d = str_replace('-','', $year->start_date);
            $startDate = $d.'0000';
            $d = str_replace('-','', $year->end_date);
            $endDate = $d.'2359';
          } 

          
          $orderPayment = $this->getPaylandApiClient()->getOrders($startDate,$endDate);
           $respo_list = [];
          $total_month = 0;
        if ($orderPayment){
          if ($orderPayment->message == 'OK')
          foreach ($orderPayment->transactions as $order){
            
            $time = strtotime($order->created);
            $amount = floatval($order->amount/100);
              
            $status = '';
            switch ($order->status){
              case 'SUCCESS':
                $status = 'pagada';
                $total_month += $amount;
                break;
              case 'REFUSED':
                $status = 'rechazada';
                break;
              case 'ERROR':
                $status = 'error';
                break;
            }
            $date = date('d M H:i',$time);
            $respo_list[] = [
                'customer' => $order->customerExtId,
                'customer_name' => $order->holder,
                'sourceType' => $order->sourceType,
                'pan' => $order->pan,
                'date' => $date,
                'status' => $status,
                'amount' => number_format($amount, 2, ',', '.'),
                'currency' => ($order->currency == 978) ? '€' : '$',
                
                  ];
            
            
          }
        }
         
          $response = [
                'status'     => 'true',
                'total_month' => number_format($total_month, 2, ',', '.'),
                'comision' => moneda(paylandCost($total_month)),
                'respo_list' => array_reverse($respo_list),
            ];
          if ($isAjax){
            return response()->json($response);
          }else {
            return $response;
          }
    }
    
    
    /**
         * Get Limpieza index
         * 
         * @return type
         */
        public function lstOrders() {
          
          $year = $this->getActiveYear();
          
          $obj1  = $this->getMonthlyData($year);
          return view('backend/sales/payland', [
              'year'        => $year,
              'selected'    => $obj1['selected'],
              'selectedID'  => $obj1['selectedID'],
              'months_obj'  => $obj1['months_obj'],
              'months_label'=> $obj1['months_label'],
              ]

                  );
        }
        
        public function getSummary() {
          
          $year = $this->getActiveYear();
          $d = str_replace('-','', $year->start_date);
          $startDate = $d.'0000';
          $d = str_replace('-','', $year->end_date);
          $endDate = $d.'2359';
      
          $today = date('Ymd');
          $totalToday = 0;
          $SUCCESS = $REFUSED = $ERROR = [];
          // prepare the chart
          $startYear = new Carbon($year->start_date);
          $endYear   = new Carbon($year->end_date);
          $diff      = $startYear->diffInMonths($endYear) + 1;
          $aux = $startYear->format('n');
          $auxY = $startYear->format('y');
          for ($i=0; $i<$diff;$i++){
            $c_month = $aux+$i;
            if ($c_month == 13){
              $auxY++;
            }
            if ($c_month>12){
              $c_month -= 12;
            }
            $SUCCESS[$auxY.'_'.$c_month] = 0;
            $REFUSED[$auxY.'_'.$c_month] = 0;
            $ERROR[$auxY.'_'.$c_month] = 0;
          }
          
          $orderPayment = $this->getPaylandApiClient()->getOrders($startDate,$endDate);
          
          $count = [
                  'SUCCESS' => 0,
                  'REFUSED' => 0,
                  'ERROR' => 0,
              ];
          if ($orderPayment){
          if ($orderPayment->message == 'OK')
          foreach ($orderPayment->transactions as $order){
            
            $time = strtotime($order->created);
            $month = date('y_n',$time);
            $amount = $order->amount/100;
            switch ($order->status){
              case 'SUCCESS':
                $SUCCESS[$month] += $amount;
                if (date('Ymd',$time) == $today){
                  $totalToday +=$amount;
                }
                break;
              case 'REFUSED':
                $REFUSED[$month] += $amount;
                break;
              case 'ERROR':
                $ERROR[$month] += $amount;
                break;
            }
            $count[$order->status]++;
          }
        }
        
        $totals = [
                'SUCCESS' => 0,
                'REFUSED' => 0,
                'ERROR' => 0,
            ];
        
        
        
        $result = [
            'SUCCESS' => [],
            'REFUSED' => [],
            'ERROR' => [],
        ];
        foreach ($SUCCESS as $r){ 
          $result['SUCCESS'][] = $r;
          $totals['SUCCESS'] += $r;
        }
        foreach ($REFUSED as $r){ 
          $result['REFUSED'][] = $r;
          $totals['REFUSED'] += $r;
        }
        foreach ($ERROR as $r){ 
          $result['ERROR'][] = $r;
          $totals['ERROR'] += $r;
        }
        
        $average = $totals['SUCCESS']/$count['SUCCESS'];
//        $totals['SUCCESS'] = number_format($totals['SUCCESS'], 2, ',', '.');
                
        $response = [
                'status'     => 'true',
                'result' => $result,
                'today' => number_format($totalToday, 2, ',', '.'),
                'average' => number_format($average, 2, ',', '.'),
                'season' => number_format($totals['SUCCESS'], 2, ',', '.'),
                'count' => $count,
                'totals' => $totals,
                'comision' => paylandCost($totals),
            ];
          

          return response()->json($response);
         
          
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
          $selectedID = null;
          for ($i=0; $i<$diff;$i++){
            $c_month = $aux+$i;
            if ($c_month == 13){
              $auxY++;
            }
            if ($c_month>12){
              $c_month -= 12;
            }
            
            if ($thisMonth == $c_month){
              $selected = "$auxY,$c_month";
              $selectedID = $auxY.'_'.$c_month;
            }
            
            $months_lab .= "'".$arrayMonth[$c_month-1]."',";
            //Only to the Months select
            $months_obj[] = [
                'id'    => $auxY.'_'.$c_month,
                'dateYear'    => '20'.$auxY,
                'month' => $c_month,
                'year'  => $auxY,
                'name'  => $arrayMonthMin[$c_month-1],
                't_pvp' => 0
            ];
          }
          
          foreach ($months_obj as $k=>$months){
            $tPVP = \App\Book::where_type_book_sales()
                    ->whereYear('start', '=', $months['dateYear'])
                    ->whereMonth('start', '=', $months['month'])
                    ->sum('total_price');
            if ($tPVP)
              $months_obj[$k]['t_pvp'] = $tPVP;
            
          }
          
          return [
              'year'        => $year->year,
              'selected'    => $selected,
              'selectedID'  => $selectedID,
              'months_obj'  => $months_obj,
              'months_label'=> $months_lab,
              ];
          
        }
        
      public function paymentsForms($token) {
        
        if ($token){

          $room = $name = $dates = null;
          
          $payment = \App\PaymentOrders::where('token',$token)->first();
          if ($payment){
            $book = \App\Book::find($payment->book_id);
            $request_dni = false;
            if ($book){
              $payments = \App\Payments::where('book_id', $book->id)->first();
              $customer = $book->customer;
              if($customer && !$customer->accepted_hiring_policies){
                $request_dni = true;
                $room = '';
                if ($book->room && $book->room->sizeRooms){
                  $room = $book->room->sizeRooms->name;
                  $room .= ($book->type_luxury == 1) ? " Lujo" : " Estandar";
                }
                $name = $book->customer->name;

                $start = strtotime($book->start);
                $finish = strtotime($book->finish);

                $monthStart = date('n',$start);
                $monthFinish = date('n',$finish);
                if ($monthStart == $monthFinish){
                  $dates = date('d',$start).'-'.date('d',$finish);
                  $dates .= ' '.getMonthsSpanish($monthStart);
                } else {
                  $dates = date('d',$start).' '.getMonthsSpanish($monthStart);
                  $dates .= ' - '.date('d',$finish).' '.getMonthsSpanish($monthFinish);
                }
              }
            }
            $urlPayland = $this->generateOrderPaymentBooking(
                    $payment->book_id,
                    $payment->cli_id,
                    $payment->cli_email,
                    $payment->description,
                    $payment->amount,
                    $payment->is_deferred
                    );
            
            if (env('APP_APPLICATION') == "riad"){
              $background = assetV('img/riad/lockscreen.jpg');
            } else {
              $background = assetV('img/miramarski/lockscreen.jpg');
            }
      
             return view('frontend.bookStatus.paylandPay', 
                     [
                         'urlPayland' => $urlPayland,
                         'background'=>$background,
                         'room' => $room,
                         'urlSend'=>route('front.payments.dni',$token),
                         'dates' => $dates,
                         'name' => $name,
                         'request_dni' =>$request_dni
                         ]);
          }
          return redirect()->route('paymeny-error');
        }
        return redirect()->route('paymeny-error');
      }
    
   public function saveDni(Request $request,$token) {
      $dni = $request->input('dni', null);
      $accepted_hiring_policies = $request->input('accepted_hiring_policies', null);
      $accepted_bail_conditions = $request->input('accepted_bail_conditions', null);
      
     
      if (trim($dni) == '' || strlen(trim($dni))<7){
        return response()->json('Por favor, ingrese su DNI para continuar');
      }
      if ($accepted_hiring_policies !== "true"){
        return response()->json('Por favor, acepte las políticas de contratación');
      }
      if ($accepted_bail_conditions !== "true"){
        return response()->json('Por favor, acepte las políticas de fianza');
      }
      if ($token){
          $payment = \App\PaymentOrders::where('token',$token)->first();
          if ($payment){
            $book = \App\Book::find($payment->book_id);
            $customer = $book->customer;
            if ($customer){
              $customer->dni = $dni;
              $customer->accepted_hiring_policies = date('Y-m-d H:i:s');
              $customer->accepted_bail_conditions = date('Y-m-d H:i:s');
              $customer->save();
              return response()->json('ok');
            }
            return response()->json('Reserva no encontrada');
          } else {
            return response()->json('Reserva no encontrada');
            echo 'Reserva no encontrada'; die;
          }
            return response()->json('Reserva no encontrada');
          echo 'Reserva no encontrada'; die;
      }

      return response()->json('Error: solicitud de pago no encontrada');
      echo 'Error: solicitud de pago no encontrada'; die;
   }
   public function getPaymentByType(Request $request) {
      $type = $request->input('type','link');
      if ($type == 'form'){
        return $this->payment($request);
      }
      if ($type == 'link'){
        $subject = '';
        $booking = $request->input('booking', null);
        $amount = $request->input('amount', null);
        if ($booking && $amount){
          $aux = explode('-', $booking);
          if (is_array($aux) && count($aux) == 2){
            $bookingID = desencriptID($aux[1]);
            $clientID = desencriptID($aux[0]);
            $is_deferred = $request->input('is_deferred', null); //fianza
            $urlPay = $this->generateOrder($amount,$subject,$bookingID,$is_deferred);
            if ($urlPay){
              return $this->getPaymentText($urlPay,$is_deferred);
            }
            
          }
        }

      }
      return 'error';
    }
           
    public function thansYouPaymentDeferred($key_token)
    {
      $bookOrder = \App\BookDeferred::where('key_token',$key_token)->whereNull('paid')->first();
      if ($bookOrder){
        $bookOrder->paid = true;
        $bookOrder->save();
        $totalPayment = ($bookOrder->amount/100);
        $amount = $totalPayment.' €';
        \App\BookLogs::saveLogStatus($bookOrder->book_id,null,$bookOrder->cli_email,"Confirmación de Fianza de $amount ($key_token)");

        $book = \App\Book::find($bookOrder->book_id);
        if ($book){
          $subject = translateSubject('Confirmación de Fianza',$book->customer->country);

          $subject .= ' en '.env('APP_NAME');
          $this->sendEmail_confirmDeferrend($book,$subject,$totalPayment);
        }
      }
              ?>
          <div style="text-align: center;margin-top: 2em; color: #fff;">
            <h2 style="line-height: 1;">
              ¡Muchas gracias por confiar en nosotros!
            </h2>
            <br>
            <p style="line-height: 1;">
              Te enviaremos un email con la confirmación de tu fianza.<br><br>
              Un saludo
            </p>
          </div>
          <?php
          return '';
        
    }
}
