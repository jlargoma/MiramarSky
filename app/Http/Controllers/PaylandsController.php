<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\BookOrders;

class PaylandsController extends AppController
{
	public function payment(Request $request)
	{
          
          $booking = $request->input('booking', null);
          $amount = $request->input('amount', null);
          if ($booking && $amount){
            $aux = explode('-', $booking);
            if (is_array($aux) && count($aux) == 2){
              $bookingID = desencriptID($aux[1]);
              $clientID = desencriptID($aux[0]);
              
              $book = \App\Book::find($bookingID);
              if ($book){
                if ($book->customer_id == $clientID){
                  $client = $book->customer()->first();
                  $urlToRedirect = $this->generateOrderPaymentBooking(
                          $bookingID,
                          $clientID,
                          $client->email,
                          $client->name,
                          ($amount * 100) // esto hay que revisar
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
                          ($amount * 100) // esto hay que revisar
                          );
        }

        private function generateOrderPaymentBooking($bookingID,$clientID,$client_email,$client_name,$amount){
          
          $key_token = md5($bookingID.'-'.time().'-'.$clientID);
          $description = "COBRO RESERVA CLIENTE " . $client_name;
          $response['_token']          = null;
          $response['amount']          = $amount;
          $response['customer_ext_id'] = $client_email;
          $response['operative']       = "AUTHORIZATION";
          $response['secure']          = false;
          $response['signature']       = env('PAYLAND_SIGNATURE');
          $response['service']         = env('PAYLAND_SERVICE');
          $response['description']     = $description;
          $response['url_ok']          = route('payland.thanks.payment',$key_token);
          $response['url_ko']          = route('payland.error.payment',$key_token);
          $response['url_post']          = route('payland.process.payment',$key_token);
          //dd($this->getPaylandApiClient());
          $paylandClient = $this->getPaylandApiClient();
          $orderPayment  = $paylandClient->payment($response);
          
          
          $BookOrders = new BookOrders();
          $BookOrders->book_id = $bookingID;
          $BookOrders->cli_id = $clientID;
          $BookOrders->cli_email = $client_email;
          $BookOrders->subject = $description;
          $BookOrders->key_token = $key_token;
          $BookOrders->order_uuid = $orderPayment->order->uuid;
          $BookOrders->order_created = $orderPayment->order->created;
          $BookOrders->amount = $orderPayment->order->amount;
          $BookOrders->refunded = $orderPayment->order->refunded;
          $BookOrders->currency = $orderPayment->order->currency;
          $BookOrders->additional = $orderPayment->order->additional;
          $BookOrders->service = $orderPayment->order->service;
          $BookOrders->status = $orderPayment->order->status;
          $BookOrders->token = $orderPayment->order->token;
          $BookOrders->transactions = json_encode($orderPayment->order->transactions);
          $BookOrders->client_uuid = $orderPayment->client->uuid;
          $bo_id = $BookOrders->save();

          
          $urlToRedirect = $paylandClient->processPayment($orderPayment->order->token);
          return $urlToRedirect;
        
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
        $params['url_ok']          = route('payland.thanks.payment', ['id' => $book->id, 'payment' => $params['amount']]);
        $params['url_ko']          = route('payland.thanks.payment', ['id' => $book->id, 'payment' => $params['amount']]);

        $orderPayment  = $this->getPaylandApiClient()->payment($params);
        $urlToRedirect = $this->getPaylandApiClient()->processPayment($orderPayment->order->token);
        $url           = $urlToRedirect;

        $response      = '<div class="col-md-2 col-xs-12">
                                <h2 class="text-center" style="font-size: 18px; line-height: 18px; margin: 0;">
                                                                    
                                    <a href="whatsapp://send?text=En este link podrás realizar el pago de la señal.&#10; En el momento en que efectúes el pago, te llegará un email - ' . $url . '" data-action="share/whatsapp/share">
                                        <i class="fa fa-whatsapp fa-3x" aria-hidden="true"></i>
                                    </a>
                                </h2>
                            </div>
                            <div class="col-md-10 col-xs-12">
                                <h2 class="text-center" style="font-size: 24px; line-height: 15px">
                                    <span style="font-size: 18px;">En este link podrás realizar el pago de la señal.<br> En el momento en que efectúes el pago, te legará un email</span><br>
                                    <a target="_blank" href="' . $url . '">
                                        ' . substr($url, 0, 45) . '...     
                                    </a>
                                </h2>
                                <div class="row text-center">
                                    <button class="btn btn-cons" type="button" id="copy-link-stripe" data-link="' . $url . '">
                                        <span class="bold">Copiar Link</span>
                                    </button>  
                                </div>
        
                            </div>';

        return $response;
    }

    public function thansYouPayment($key_token)
    {
////        $book = \App\Book::find($id);
////        $this->payBook($id, $payment);
      return redirect()->route('thanks-you');
        
    }
    public function errorPayment($key_token)
    {
      return redirect()->route('paymeny-error');
    }
    public function processPayment(Request $request, $id, $payment=null)
    {
      
      file_put_contents(storage_path()."/testapto".time()."-$id", json_encode($payment)."\n". json_encode($request->all()));
      var_dump($request->all());
      dd($id, $payment);
//        $book = \App\Book::find($id);
//        $this->payBook($id, $payment);
//        return redirect()->route('thanks-you');
    }
    
}
