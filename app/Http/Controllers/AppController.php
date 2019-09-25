<?php

namespace App\Http\Controllers;

use App\Book;
use App\Repositories\CachedRepository;
use App\Rooms;
use App\Services\PaylandService;
use App\SizeRooms;
use App\Years;
use Carbon\Carbon;

class AppController extends Controller
{
    private $cachedRepository;
    private $paylandClient;
    const SANDBOX_ENV = "/sandbox";

    public function __construct(CachedRepository $cachedRepository)
    {
        $this->cachedRepository = $cachedRepository;
        $endPoint               = (env('PAYLAND_ENVIRONMENT') == "dev") ? env('PAYLAND_ENDPOINT') . self::SANDBOX_ENV : env('PAYLAND_ENDPOINT');
        $paylandConfig          = [
            'endpoint'  => $endPoint,
            'api_key'   => env('PAYLAND_API_KEY'),
            'signarute' => env('PAYLAND_SIGNATURE'),
            'service'   => env('PAYLAND_SERVICE')
        ];
        $this->paylandClient    = new PaylandService($paylandConfig);
    }

    protected function getPaylandApiClient()
    {
        return $this->paylandClient;
    }

    /**
     * @return mixed
     */
    protected static function getActiveYear()
    {
        $activeYear = Years::where('active', 1)->first();
        return $activeYear;
    }
    /**
     * @return mixed
     */
    protected static function getYearData($year)
    {
        $activeYear = Years::where('year', $year)->first();
        return $activeYear;
    }

    public function payBook($id, $payment)
    {
      $book = \App\Book::find($id);
      $realPrice = ($payment / 100);

      $payment = new \App\Payments();

      $date                 = Carbon::now()->format('Y-m-d');
      $payment->book_id     = $book->id;
      $payment->datePayment = $date;
      $payment->import      = $realPrice;
      $payment->comment     = "Pago desde Payland";
      $payment->type        = 2;
      $payment->save();

      $data['concept']     = $payment->comment;
      $data['date']        = $date;
      $data['import']      = $realPrice;
      $data['comment']     = $payment->comment;
      $data['typePayment'] = 2;
      $data['type']        = 0;

      LiquidacionController::addBank($data);
      if ($book->type_book != 2) {
        $book->changeBook(2, "", $book);
      }
      return true;
      
    }

    public function generateOrderPayment($params)
    {
        $response = $params;
        if (isset($response['_token'])) unset($params['_token']);
        $customer                    = \App\Customers::find($response['customer_id']);
        $response['amount']          = ($response['amount']) * 100;
        $response['customer_ext_id'] = $customer->email;
        $response['operative']       = "AUTHORIZATION";
        $response['secure']          = false;
        $response['signature']       = env('PAYLAND_SIGNATURE');
        $response['service']         = env('PAYLAND_SERVICE');
        $response['description']     = "COBRO RESERVA CLIENTE " . $customer->name;
        $response['url_ok']          .= "/" . $response['amount'];
        $response['url_ko']          .= "/" . $response['amount'];
        //dd($this->getPaylandApiClient());
        $orderPayment  = $this->paylandClient->payment($response);
        $urlToRedirect = $this->paylandClient->processPayment($orderPayment->order->token);
        return $urlToRedirect;

    }

    public function calculateRoomToFastPayment(SizeRooms $size, $start, $finish, $luxury)
    {
        $roomSelected   = null;
        $luxurySelected = ($luxury == "si") ? 1 : 0;
        $allRoomsBySize = Rooms::
                where('sizeApto', $size->id)
                ->where('state', 1)
                ->where('fast_payment', 1)
                ->orderBy('order_fast_payment', 'ASC')->limit($size->num_aptos_fast_payment)->get();
        
        $startDate  = $start->copy()->format('d/m/Y');
        $finishDate = $finish->copy()->format('d/m/Y');
        foreach ($allRoomsBySize as $room){
            $room_id = $room->id;
          if (Book::existDate($startDate,$finishDate, $room_id))
          {
            return ['isFastPayment'=>true,'id'=>$room_id];
          }
        }
        
        //search simple Rooms to Booking
        $oRoomsBySize = Rooms::
                where('sizeApto', $size->id)
                ->where('state', 1)
                ->orderBy('order_fast_payment', 'ASC')->first();
          if ($oRoomsBySize){
            return ['isFastPayment'=>false,'id'=>$oRoomsBySize->id];
          }
          
        return ['isFastPayment'=>false,'id'=>-1];
    }
    
    
    
    /**
     * Create link to new Payland
     * 
     * @param type $bookingID
     * @param type $clientID
     * @param type $client_email
     * @param type $description
     * @param type $amount
     * @return type
     */
    public function generateOrderPaymentBooking($bookingID,$clientID,$client_email,$description,$amount){
          
      $key_token = md5($bookingID.'-'.time().'-'.$clientID);

      $amount = ($amount * 100); // esto hay que revisar
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
      $response['url_post']        = route('payland.process.payment',$key_token);
      //dd($this->getPaylandApiClient());
      $paylandClient = $this->getPaylandApiClient();
      $orderPayment  = $paylandClient->payment($response);


      $BookOrders = new \App\BookOrders();
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

}