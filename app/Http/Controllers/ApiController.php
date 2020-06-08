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
              'p' => $parkPvpSetting->value*$nigths
          ];
      }
      $parkPvpSetting = Settings::where('key', Settings::BREAKFAST_PVP_SETTING_CODE)->first();
      if($parkPvpSetting){
         $response[] = [
              'k'=> encriptID($parkPvpSetting->id),
              'n'=> 'Desayuno ('.$parkPvpSetting->value.'€ x pers. x día)',
              'p' => $parkPvpSetting->value*$pax*$nigths
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
      $oRooms = Rooms::where('channel_group',$channel_group)
              ->where('maxOcu','>=', $pax)->where('state',1)->get();
      if ($oRooms){
        foreach ($oRooms as $room){
          if ($book->availDate($startDate, $endDate, $room->id)){
            return [
                'price'   => $room->getPvp($startDate,$endDate,$pax),
                'minStay' => $room->getMin_estancia($startDate,$endDate)
                ];
             
          }
        }
      }
      return null;
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
      
      if($usr['c_phone'] != '123-789') {
        $response =  'No hay información disponible';
      return response()->json(['success'=>false,'data'=>$response],401);
      }
        
        
      $pax = $request->input('pax',null);
      $date_start = $request->input('start',null);
      $date_finish = $request->input('end',null);
      
      
      /*  
       * ["item"]=>
              ["p"]=> price
              ["t"]=> title
              ["rt"]=> rate type
              ["rt_text"]=> rate type text
              ["ext"]=> extras [n=>name, k=>k_id,p=>price,v=>value(price),s=>selected(bool)]
              ["code"]=>k_id RoomTYpe
              ["pvp"]=> PVP 
       */
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
      
      $roomType = \App\RoomsType::where('id',$roomTypeID)
            ->where('status',1)                
            ->first();
   
    
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
        ];
        $response = $this->createBooking($date_start,$date_finish,$customer,$oRoom,$comments,$pax,$selected['ext'],$rate);
        if ($response){
          return response()->json(['data'=>$response],200);
        }
      }
      $response =  'No hay información disponible';
      return response()->json(['data'=>$response],401);
     
    }
    
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function createBooking($date_start,$date_finish,$cData,$room,$comments,$pax,$Extras,$rate)
    {
        //createacion del cliente
        $customer          = new \App\Customers();
        $customer->name    = $cData['name'];
        $customer->email   = $cData['email'];
        $customer->phone   = $cData['phone'];
        $customer->user_id = 1;
        if (!$customer->save()) return FALSE;
        
        //Creacion de la reserva
        $book   = new \App\Book();
        $book->room_id       = $room->id;
        $book->start         = $date_start;
        $book->finish        = $date_finish;
        $book->comment       = $comments;
        $book->type_book     = 3;//99;
        $book->pax           = $pax;
        $book->real_pax      = $pax;
        $book->nigths        = calcNights($date_start, $date_finish);
        $book->PVPAgencia    = 0;
        $book->is_fastpayment = 0;//1;
        $book->user_id = 1;
        $book->customer_id = $customer->id;
        
        if (!$book->save())  return FALSE;
        
        $this->setExtras($Extras,$book);
           
        
        $totales = $book->getPriceBook($date_start,$date_finish,$room->id);
        
        
                  
        $roomPrice = $this->getPriceOrig($totales['pvp']); //Booking price
        if ($rate == 1)  $pvp = $roomPrice - ($roomPrice*$this->discount_1);
        if ($rate == 2)  $pvp = $roomPrice - ($roomPrice*$this->discount_2);
          
        $book->total_price = $pvp;
        
        $book->cost_apto   = $totales['cost'];
        $book->extraPrice  = $totales['extra_fixed'];
        $book->extraCost   = $totales['cost_extra_fixed'];
        $book->sup_limp    = $totales['limp'];
        $book->cost_limp   = $totales['cost_limp'];
        $book->cost_total  = $book->cost_total + $totales['cost_extra_dynamic'];
        $book->real_price  = $pvp + $book->sup_limp + $book->extraPrice+$totales['extra_dynamic'];
        $book->total_price += $totales['extra_dynamic'];
        
        $book->comment     = $comments .PHP_EOL." - precio publicado: $pvp";
        
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
          $book->sendAvailibilityBy_dates($book->start, $book->finish);
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

    
    private function setExtras($aExtrs,$book){
      $qty = 1;
      if (count($aExtrs)>0){
        $aExtrsID = [];
        foreach ($aExtrs as $e){
          if ($e['s'] == "true") $aExtrsID[] = desencriptID($e['k']);
        }
        $extras = \App\ExtraPrices::getDynamicToFront();
        $bookingExtras = [];
        if ($extras){
          foreach ($extras as $oExtra){
            if (in_array($oExtra->id, $aExtrsID)){
              $oBookExtra = new \App\BookExtraPrices();
              $oBookExtra->book_id = $book->id;
              $oBookExtra->extra_id = $oExtra->id;
              $oBookExtra->qty = $qty;
              $oBookExtra->price = $oExtra->price;
              $oBookExtra->cost = $oExtra->cost*$qty;
              $oBookExtra->status = 1;
              $oBookExtra->vdor = null;
              $oBookExtra->type = $oExtra->type;
              $oBookExtra->fixed = 0;
              $oBookExtra->deleted = 0;
              $oBookExtra->save();
            }
          }
        }
      }
      
    }
    
    
    private function getPriceOrig($pvp) {
      $zConfig = new \App\Services\Zodomus\Config();
      return $zConfig->priceByChannel($pvp,1); //Booking price
    }
}
