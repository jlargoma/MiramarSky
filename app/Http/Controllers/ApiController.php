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
//      where('status',1)
      
    }
    
    public function getItemsSuggest(Request $request) {

      $pax = $request->input('pax',null);
      $date_start = $request->input('start',null);
      $date_finish = $request->input('end',null);
      $response = [];
        
      $oItems = $this->getItems($pax);
      
      $nigths = calcNights($date_start,$date_finish);
      if ($oItems){
        $index = 0;
        foreach ($oItems as $item){
          $roomData = $this->getRoomsPvpAvail($date_start,$date_finish,$pax,$item->channel_group);
          if ($roomData['price']<1) continue;
          $roomPrice = $this->getPriceOrig($roomData['price']); //Booking price
          $minStay = $roomData['minStay'];
          
          $pvp_1 = $roomPrice - ($roomPrice*$this->discount_1);
          $pvp_2 = $roomPrice - ($roomPrice*$this->discount_2);
          $response[] = [
            'name' => $item->name,
            'max_pax' => $item->max_pax,
            'code'=>encriptID($item->id),
            'price'=> moneda($roomPrice),
            'pvp'=>$roomPrice,
            'price_1'=> moneda($pvp_1),
            'pvp_1'=>$pvp_1,
            'discount_1'=>$this->discount_1*100,
            'price_2'=> moneda($pvp_2),
            'pvp_2'=>$pvp_2,
            'discount_2'=>$this->discount_2*100,
            'minStay'=>($nigths<$minStay) ? $minStay : 0,
          ];
        }
  
      }
      
      if (count($response)>0)  return response()->json($response);
       return response('empty');
    }
    
    
    
    public function getExtasOpcion(Request $request) {
      $response = [];
      
      $pax = $request->input('pax',null);
      $date_start = $request->input('start',null);
      $date_finish = $request->input('end',null);
      $nigths = calcNights($date_start,$date_finish);
     
      $parkPvpSetting = Settings::where('key', Settings::PARK_PVP_SETTING_CODE)->first();
      if($parkPvpSetting){
         $response[] = [
              'k'=> encriptID($parkPvpSetting->id),
              'n'=> 'Parking ('.$parkPvpSetting->value.'€ x noche)',
              'p' => $parkPvpSetting->value*$nigths,
              'i'=> clearTitle('Parking'),
          ];
      }
      $parkPvpSetting = Settings::where('key', Settings::BREAKFAST_PVP_SETTING_CODE)->first();
      if($parkPvpSetting){
         $response[] = [
              'k'=> encriptID($parkPvpSetting->id),
              'n'=> 'Desayuno ('.$parkPvpSetting->value.'€ x pers. x día)',
              'p' => $parkPvpSetting->value*$pax*$nigths,
              'i'=> clearTitle('Desayuno'),
          ];
      }
     
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
                'price'   => 0,
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
            return [
                'price'   => $room->getPvp($startDate,$endDate,$pax),
                'minStay' => $room->getMin_estancia($startDate,$endDate),
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
      
//      if($usr['c_phone'] != '123-789') {
//        $response =  'No hay información disponible';
//      return response()->json(['success'=>false,'data'=>$response],401);
//      }
        
        
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
      $price = isset($roomData['price']) ? $roomData['price'] : 0;
      
      if ($nigths<$minStay){
        $response =  'Estancia mínima: '.$minStay.' Noches';
        return response()->json(['data'=>$response],401);
      }
      if ($price<1){
        $response =  'Ocurrió un error a procesar su reserva';
        return response()->json(['data'=>$response],401);
      }
      
      /*******************************/
      if ($roomType){
        $oRoom = $this->getRoomsWithAvail($date_start,$date_finish,$pax,$roomType->channel_group);
        $customer = [
            'name'  => $usr['c_name'],
            'email' => $usr['c_mail'],
            'phone' => $usr['c_phone'],
            'token' => isset($usr['token']) ? $usr['token'] : time(),
        ];
        $extras = isset($selected['ext']) ? $selected['ext'] : [];
        $response = $this->createBooking($date_start,$date_finish,$customer,$oRoom,$comments,$pax,$extras,$rate);
        if ($response){
          return response()->json(['data'=>$response,'c_token'=>$this->customerToken],200);
        }
      }
      $response =  'No hay información disponible';
      return response()->json(['data'=>$response,'c_token'=>$this->customerToken],401);
     
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

        $book->room_id       = $room->id;
        $book->start         = $date_start;
        $book->finish        = $date_finish;
        $book->comment       = $comments;
        $book->type_book     = 99;
        $book->pax           = $pax;
        $book->real_pax      = $pax;
        $book->nigths        = calcNights($date_start, $date_finish);
        $book->PVPAgencia    = 0;
        $book->is_fastpayment = 0;//1;
        $book->user_id = 1;
        $book->customer_id = $customer->id;
        
        if (!$book->save())  return FALSE;
        
        $this->setExtras($Extras,$book);
           
        
        /*************************************************************************************************/
//            if ($request->input('priceDiscount') == "yes" || $request->input('price-discount') == "yes") {
//              $discount = Settings::getKeyValue('discount_books');
//              $book->real_price -= $discount;
//              $book->ff_status = 4;
//              $book->has_ff_discount = 1;
//              $book->ff_discount = $discount;
//            }
        /*************************************************************************************************/
        
        $costes = $room->priceLimpieza($room->sizeApto);
        $book->sup_limp  = $costes['price_limp'];
        $book->cost_limp = $costes['cost_limp'];
        
        $pvp = $room->getPVP($date_start, $date_finish,$book->pax);
        $roomPrice = $this->getPriceOrig($pvp); //Booking price
        if ($rate == 1)  $pvp = $roomPrice - ($roomPrice*$this->discount_1);
        if ($rate == 2)  $pvp = $roomPrice - ($roomPrice*$this->discount_2);
          
        
        $book->real_price  = $pvp + $book->sup_park + $book->sup_lujo + $book->sup_limp;
        $book->cost_apto = $room->getCostRoom($date_start, $date_finish, $book->pax);
        $book->cost_total = $book->get_costeTotal();
        $book->total_price = $book->real_price;
        $book->total_ben = $book->total_price - $book->cost_total;
        $book->promociones = 0;
        $book->book_comments     = $comments .PHP_EOL." - precio publicado: $pvp";
        if ($book->save()) {
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
          return 'https://www.apartamentosierranevada.net/mantenimiento.html';
          //Prin box to payment
          $description = "COBRO RESERVA CLIENTE " . $book->customer->name;
          $urlPayland = 'url payland';
          $oPaylands = new \App\Models\Paylands();
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
    
    private function setExtras($aExtrs,$book){
          
      if (!$aExtrs || count($aExtrs)<1) return null;
      $extID = [];
      foreach ($aExtrs as $ext){
        if ($ext['s'] === "true") $extID[] = desencriptID($ext['k']);
      }
      //parking  
      $parkPvpSetting = Settings::where('key', Settings::PARK_PVP_SETTING_CODE)->first();
      if($parkPvpSetting){
        if (in_array($parkPvpSetting->id, $extID)){
          $cost = Settings::where('key', Settings::PARK_COST_SETTING_CODE)->first();
          $book->sup_park  = $parkPvpSetting->value*$book->nigths;
          $book->cost_park = $cost->value*$book->nigths;
          $book->type_park = 1;
          $book->save();
        }
      }
      
      //suplemento de lujo
      $parkPvpSetting = Settings::where('key', Settings::BREAKFAST_PVP_SETTING_CODE)->first();
      if($parkPvpSetting){
        if (in_array($parkPvpSetting->id, $extID)){
          $cost = Settings::where('key', Settings::LUXURY_COST_SETTING_CODE)->first();
          $book->type_luxury = 1;
          $book->sup_lujo    = $parkPvpSetting->value*$book->pax*$book->nigths;
          $book->cost_lujo   = $cost->value*$book->pax*$book->nigths;
          $book->save();
        }
      }
    }
    
    
    
    private function getPriceOrig($pvp) {
      $zConfig = new \App\Services\Zodomus\Config();
      return $zConfig->priceByChannel($pvp,1); //Booking price
    }
    
    public function changeCustomer(Request $request) {
      // c_name c_mail c_phone c_tyc 
      $cData = $request->input('usr',null);
      if ($cData){
        $token = $cData['token'];
        $oCustomer = \App\Customers::where('api_token',$token)->first();
        if ($oCustomer && $oCustomer->api_token === $token){
          
          if (isset($cData['c_name']))  $oCustomer->name    = $cData['c_name'];
          if (isset($cData['c_mail'])) $oCustomer->email   = $cData['c_mail'];
          if (isset($cData['c_phone'])) $oCustomer->phone   = $cData['c_phone'];
          
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
