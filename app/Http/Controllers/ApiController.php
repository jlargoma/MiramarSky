<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoomsType;
use App\RoomsPhotos;
use App\Rooms;
use App\Book;
use App\Http\Requests;
use App\Settings;

class ApiController extends AppController
{
  private $discount_1 = 0.15;
  private $discount_2 = 0.1;
  private $customerToken = null;
  
  public function __construct(){}
  
  public function index()
    {
      die('inicio');
      //  return view('api/index');
    }

    private function getItems($pax) {
      
      if (!is_numeric($pax) || $pax<0) return [];
      
      return RoomsType::where('min_pax','<=',$pax)
                ->where('max_pax','>=',$pax)
                ->get();
    }
    
    public function getItemsSuggest(Request $request) {

      $pax = $request->input('pax',null);
      $date_start = $request->input('start',null);
      $date_finish = $request->input('end',null);
      $response = [];
      $usr = $request->input('usr',null);
      
      //Save potencial customer data
      if (isset($usr['c_cp']) && trim($usr['c_cp']) == '')
        \App\CustomersRequest::createOrEdit($request->all(),-1);
        
      $oItems = $this->getItems($pax);
      
      $nigths = calcNights($date_start,$date_finish);
      if ($oItems){
        $index = 0;
        foreach ($oItems as $item){
         
          $roomData = $this->getRoomsPvpAvail($date_start,$date_finish,$pax,$item->channel_group);
          if ($roomData['prices']['pvp'] < 1) continue;
          $roomPrice = $roomData['prices'];
          $minStay = $roomData['minStay'];
          //descriminamos el precio de limpieza
          $pvp = $roomPrice['pvp_init'];
          $pvp_1 = $roomPrice['pvp'] - $roomPrice['price_limp'];
          // promociones tipo 7x4
          $hasPromo = 0;
          if ($roomPrice['promo_pvp']>0) $hasPromo = $roomPrice['promo_name'];
          
          ////////////////////////
          $pvp_2 = 99999;
          $response[] = [
            'name' => $item->name,
            'max_pax' => $item->max_pax,
            'code'=>encriptID($item->id),
            'price'=> moneda($pvp,true,2),
            'pvp'=>$pvp,
            'extr_costs'=>$roomPrice['price_limp'],
            'price_1'=> moneda($pvp_1,true,2),
            'pvp_1'=> round($pvp_1,2),
            'discount_1'=>$roomPrice['discount'],
            'pvp_discount'=>$roomPrice['discount_pvp'],
            'promo_name'=>$hasPromo,
            'pvp_promo'=>$roomPrice['promo_pvp'],
            'price_2'=> moneda($pvp_2),
            'pvp_2'=>$pvp_2,
            'discount_2'=>$this->discount_2*100,
            'minStay'=>($nigths<$minStay) ? $minStay : 0,
          ];
//          dd($roomData,$response);
        }
  
      }
//      dd($response);
      if (count($response)>0)  return response()->json($response);
       return response('empty');
    }
    
    
    
    public function getExtasOpcion(Request $request) {
      $response = [];
      return response()->json($response);
    }
    
    private function getRoomsImgs($id,$index) {
      
      $result = '';
      $photos = RoomsPhotos::where('gallery_key', $id)
              ->orderBy('main','DESC')->orderBy('position')->get();
            
      $start = true;
      if($photos){
        foreach($photos as $img){
          
          $file = url($img->file_rute.'/'.$img->file_name);
          if ($start){
            $result.='<a href="'.$file.'" class="bkg_gl_'.$index.'"><img class="photo" src="'.$file.'"></a>';
          } else {
            $result.='<a href="'.$file.'" class="bkg_gl_'.$index.'"></a>';
          }
          $start = false;
          
        }
      }
      
      return $result;
                  
    }
    
    
    private function getRoomsPvpAvail($startDate,$endDate,$pax,$channel_group){
      
      $book = new Book();
      $return = [
                'prices'   => 0,
                'minStay' => 0,
                'availiable' => 0
                ];
      
      $oRooms = Rooms::where('channel_group',$channel_group)
              ->where('maxOcu','>=', $pax)->where('state',1)->get();
      if ($oRooms){
        $availibility = $book->getAvailibilityBy_channel($channel_group, $startDate, $endDate);
        $availiable = count($oRooms);
        if (count($availibility)){
          foreach ($availibility as $day=>$avail){
            if ($avail<$availiable){
              $availiable = $avail;
            }
          }
        }
        foreach ($oRooms as $room){
          if ($book->availDate($startDate, $endDate, $room->id)){
            $meta_price = $room->getRoomPrice($startDate, $endDate,$pax);             
            return [
                'prices'      => $meta_price,
                'minStay'    => $room->getMin_estancia($startDate,$endDate),
                'availiable' => $availiable,
                ];
             
          }
        }
      }
      return $return;
    }
    
    private function getRoomsWithAvail($startDate,$endDate,$pax,$channel_group){
      $book = new Book();
      $oRooms = Rooms::where('channel_group',$channel_group)
              ->where('maxOcu','>=', $pax)->where('state',1)->get();
      if ($oRooms){
        foreach ($oRooms as $room){
          if ($book->availDate($startDate, $endDate, $room->id)){
            return $room;
          }
        }
      }
      return null;
    }
    
    public function getDetail($name) {
      $room = \App\RoomsType::where('name',$name)
                  ->where('status',1)                
                  ->first();
      if ($room){
        return $room->description;
      }
      return 'No hay información disponible';
    }
    
    
    public function finishBooking(Request $request) {

      // c_name c_mail c_phone c_tyc 
      $usr = $request->input('usr',null);
      
      $pax = $request->input('pax',null);
      $date_start = $request->input('start',null);
      $date_finish = $request->input('end',null);
      
      $selected = $request->input('item',null);
      $code = null;
      $comments = '';
      if (isset($selected['code'])){
        $code = trim($selected['code']);
      }

      $rate = isset($selected['rt']) ? $selected['rt'] : 2;

      if (isset($selected['rt_text'])){
        $comments = trim($selected['rt_text']);
      }
      $roomTypeID = desencriptID($code);
      $aborrar = [];
      
      $roomType = \App\RoomsType::where('id',$roomTypeID)->first();
   
    
      /***************************/
      $nigths = calcNights($date_start,$date_finish);
      $roomData = $this->getRoomsPvpAvail($date_start,$date_finish,$pax,$roomType->channel_group);
      
      $minStay = isset($roomData['minStay']) ? $roomData['minStay'] : 0;
      $prices = isset($roomData['prices']) ? $roomData['prices'] : 0;
      
      if ($nigths<$minStay){
        $response =  'Estancia mínima: '.$minStay.' Noches';
        return response()->json(['data'=>$response],401);
      }
      if ($prices['pvp']<1){
        $response =  'Ocurrió un error a procesar su reserva';
        return response()->json(['data'=>$response],401);
      }
      
      $widget_observations = Settings::getContent('widget_observations');
      /*******************************/
      if ($roomType){
        $oRoom = $this->getRoomsWithAvail($date_start,$date_finish,$pax,$roomType->channel_group);
        $customer = [
            'name'  => $usr['c_name'],
            'email' => $usr['c_mail'],
            'phone' => $usr['c_phone'],
            'c_observ' => $usr['c_observ'],
            'token' => isset($usr['token']) ? $usr['token'] : time(),
        ];
        $extras = isset($selected['ext']) ? $selected['ext'] : [];
        $response = $this->createBooking($date_start,$date_finish,$customer,$oRoom,$comments,$pax,$extras,$rate);
        if ($response){
          return response()->json(['data'=>$response,'c_token'=>$this->customerToken,'observations'=>$widget_observations],200);
        }
      }
      $response =  'No hay información disponible';
      return response()->json(['data'=>$response,'c_token'=>$this->customerToken,'observations'=>$widget_observations],401);
     
    }
    
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function createBooking($date_start,$date_finish,$cData,$room,$comments,$pax,$Extras,$rate)
    {
//      return 'https://www.apartamentosierranevada.net/mantenimiento.html';
      $alreadyExist = null;
      if ($cData && isset($cData['token'])){ //check if is a upd
        $token = $cData['token'];
        $customer = \App\Customers::where('api_token',$token)->first();
        if ($customer && $customer->api_token === $token){
          $alreadyExist = $customer->id;
        }
      }
      
      if (!$alreadyExist){
        //createacion del cliente
        $customer          = new \App\Customers();
        $customer->name    = $cData['name'];
        $customer->email   = $cData['email'];
        $customer->phone   = $cData['phone'];
        $customer->user_id = 1;
        if (!$customer->save()) return FALSE;
      }

      $customer->api_token= encriptID($customer->id).bin2hex(time());
      $customer->save();
      
      if ($alreadyExist) {
      //busco de la reserva
        $book = \App\Book::where('customer_id', $customer->id)->first();
        if (!$book || $book->customer_id != $customer->id) {
          $book = new \App\Book();  //Creacion de la reserva
        } else {
          $this->removeOldExtras($book);
        }
      } else {
        //Creacion de la reserva
        $book = new \App\Book();
      }
        $nigths = calcNights($date_start, $date_finish);
        $book->room_id       = $room->id;
        $book->start         = $date_start;
        $book->finish        = $date_finish;
        $book->comment       = isset($cData['c_observ']) ? $cData['c_observ']: '';
        $book->type_book     = 99;
        $book->pax           = $pax;
        $book->real_pax      = $pax;
        $book->nigths        = $nigths;
        $book->PVPAgencia    = 0;
        $book->is_fastpayment = 0;//1;
        $book->user_id = 1;
        $book->customer_id = $customer->id;
        
        if (!$book->save())  return FALSE;
        
        $this->setExtras($book,$room->luxury);
           
        /*************************************************************************************************/
        
        $costes = $room->priceLimpieza($room->sizeApto);
        $book->sup_limp  = $costes['price_limp'];
        $book->cost_limp = $costes['cost_limp'];
        
        /**********************************/
        
        $meta_price = $room->getRoomPrice($date_start, $date_finish,$book->pax);
        
        $pvp = $meta_price['pvp'];
        $pvp_total = $meta_price['pvp_init']+$meta_price['price_limp'];
        
        if ($meta_price['discount']>0){
          $comments .= PHP_EOL."Descuento: ".$meta_price['discount'].'%';
        }
        // promociones tipo 7x4
        $book->promociones = 0;
        if ($meta_price['promo_pvp']>0){
          $comments .= PHP_EOL."Promoción ".$meta_price['promo_name'];
          $book->promociones = $meta_price['promo_pvp'];
          $book->book_owned_comments = 'Promoción '.$meta_price['promo_name'].': '. moneda($meta_price['promo_pvp'],true,2);
        }
        // promociones tipo 7x4  
        
        $book->real_price = $pvp_total;
        $book->total_price= $pvp;
        $book->ff_status  = 4;
        $book->cost_apto  = $room->getCostRoom($date_start, $date_finish, $book->pax);
        $book->cost_total = $book->get_costeTotal();
        $book->total_ben  = $book->total_price - $book->cost_total;
        
        $book->book_comments = $comments .PHP_EOL.'Precio publicado: '.moneda($pvp);
        if ($book->save()) {
          $book->setMetaContent('price_detail', serialize($meta_price));
          $amount = ($book->total_price / 2);
          $client_email = 'no_email';
          if ($customer->emaill && trim($customer->emaill)) {
            $client_email = $customer->emaill;
          }

          //check if already exist another FastPayment to the user
          if ($client_email) {

            $clientExist = Book::select('book.*')->join('customers', function ($join) use($client_email) {
                      $join->on('book.customer_id', '=', 'customers.id')
                              ->where('customers.email', '=', $client_email);
                    })->where('type_book', 99)->get();

            if ($clientExist) {
              foreach ($clientExist as $oldBook) {
                if ($book->id != $oldBook->id) {
                  $oldBook->type_book = 0;
                  $oldBook->save();
                }
              }
            }
          }
          $this->customerToken = $customer->api_token;
//          $book->sendAvailibilityBy_dates($book->start, $book->finish);
//          return 'https://www.apartamentosierranevada.net/mantenimiento.html';
          //Prin box to payment
          $description = "COBRO RESERVA CLIENTE " . $book->customer->name;
          $urlPayland = 'url payland';
          
          $endPoint               = (env('PAYLAND_ENVIRONMENT') == "dev") ? env('PAYLAND_ENDPOINT') . self::SANDBOX_ENV : env('PAYLAND_ENDPOINT');
          $paylandConfig          = [
              'endpoint'  => $endPoint,
              'api_key'   => env('PAYLAND_API_KEY'),
              'signarute' => env('PAYLAND_SIGNATURE'),
              'service'   => env('PAYLAND_SERVICE')
          ];
          $oPaylands    = new \App\Services\PaylandService($paylandConfig);
          $urlPayland = $oPaylands->generateOrderPaymentBooking(
                  $book->id, $book->customer->id, $client_email, $description, $amount
          );

          return $urlPayland;
      }
      
    }
    
    
    
    
    
    
    
    
    
    /***********************************************/
    
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                $response['status'] = "Error";
                $response['data']['errors'] = "invalid credentials. Please check your email or password";
                return response()->json($response, 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            $response['status'] = "Error could_not_create_token";
            return response()->json($response, 500);
        }

        if ( Auth::attempt($credentials) ) {
            $tokenAux = compact('token');
            $response['status'] = "OK";
            $response['data']['user'] = Auth::user();
            $response['data']['token'] = $tokenAux['token'];
        }
        // all good so return the token
        return response()->json($response);
    }

    public function view() {
      return view('frontend.booking.view');
    }

    
    private function removeOldExtras($book){
      $book->sup_park = 0;
      $book->cost_park = 0;
      $book->type_park = 0;
      $book->type_luxury = 0;
      $book->sup_lujo = 0;
      $book->cost_lujo = 0;
      $book->save();
    }
    
    private function setExtras($book,$lux=false){
          
      //parking  
      $parkPvpSetting = Settings::where('key', Settings::PARK_PVP_SETTING_CODE)->first();
      if($parkPvpSetting){
          $cost = Settings::where('key', Settings::PARK_COST_SETTING_CODE)->first();
          $book->sup_park  = 0;
          $book->cost_park = $cost->value*$book->nigths;
          $book->type_park = 1;
      }
      
      //suplemento de lujo
      if ($lux){
        $parkPvpSetting = Settings::where('key', Settings::BREAKFAST_PVP_SETTING_CODE)->first();
        if($parkPvpSetting){
            $cost = Settings::where('key', Settings::LUXURY_COST_SETTING_CODE)->first();
            $book->type_luxury = 1;
            $book->sup_lujo    = 0;
            $book->cost_lujo   = $cost->value;
        }
      }
      
      $book->save();
    }
    
    
    
    private function getPriceOrig($pvp,$ChGr,$nigths) {
      $oConfig = new \App\Services\OtaGateway\Config();
      return round($oConfig->priceByChannel($pvp,99,$ChGr,false,$nigths),2); //Google Hotels price
    }
    
    public function changeCustomer(Request $request) {
      // c_name c_mail c_phone c_tyc 
      $cData = $request->input('usr',null);
      if ($cData){
        $token = $cData['token'];
        $oCustomer = \App\Customers::where('api_token',$token)->first();
        if ($oCustomer && $oCustomer->api_token === $token){
          
          if (!(isset($cData['c_name']) && isset($cData['c_mail']) && isset($cData['c_phone']))){
            return response()->json(['success'=>false,'data'=>'Los campos son requeridos'],401);
          }
          
          $name = trim($cData['c_name']);
          $email = trim($cData['c_mail']);
          $phone = trim($cData['c_phone']);
          
          if (strlen($name)<8 || strlen($name)>125)
            return response()->json(['success'=>false,'data'=>'El nombre es requerido.'],401);
          
          if (strlen($email)<8 || strlen($email)>125)
            return response()->json(['success'=>false,'data'=>'La dirección de correo es requerida.'],401);
            
          if(filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE)
            return response()->json(['success'=>false,'data'=>'La dirección de correo es requerida.'],401);
          
          if (strlen($phone)<6 || strlen($phone)>125)
            return response()->json(['success'=>false,'data'=>'El teléfono es requerido.'],401);
          
          
          if ($name)  $oCustomer->name    = $name;
          if ($email) $oCustomer->email   = $email;
          if ($phone) $oCustomer->phone   = $phone;
          
          $oCustomer->api_token= encriptID($oCustomer->id).bin2hex(time());
          $oCustomer->save();
          if (isset($cData['c_observ'])){
            $booking = Book::where('customer_id',$oCustomer->id)->first();
            if ($booking){
              $booking->comment   = $cData['c_observ'];
              $booking->save();
            }
          }
          
            
          return response()->json(['success'=>true,'data'=>$oCustomer->api_token],200);
        }
      }
        
      return response()->json(['success'=>false,'data'=>'Acceso denegado'],401);
    }
}
