<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use App\Repositories\CachedRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use \Carbon\Carbon;
use Auth;
use Mail;
use Illuminate\Routing\Controller;
use App\Classes\Mobile;
use App\Rooms;
use App\Book;
use App\Seasons;
use App\Prices;
use App\Traits\BookEmailsStatus;
use App\Traits\Bookings\CentroMensajeria;
use App\Traits\Bookings\Partee;
use App\Traits\Bookings\SafetyBox;
use App\Traits\BookLogsTraits;
use App\BookPartee;
use App\Settings;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");

class BookController extends AppController
{
    use BookEmailsStatus, CentroMensajeria,Partee,SafetyBox,BookLogsTraits;

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
 
        $year      = $this->getActiveYear();
        $startYear = $year->start_date;
        $endYear   = $year->end_date;
        $oUser = Auth::user();
        if (!$oUser->canSeePlanning()){
          return redirect('no-allowed');
        }
        if ($oUser->role != "agente")
        {
          $rooms       = Rooms::orderBy('order')->get();
          $types       = Book::get_type_book_pending();
          unset($types[array_search(4,$types)]);
          $booksQry = Book::where('start', '>=', $startYear)
                  ->where('start', '<=', $endYear);
                  
            
        } else {
          $roomsAgents = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
          $rooms       = Rooms::whereIn('id', $roomsAgents)->orderBy('order')->get();
          $types       = [1];

          if (!Auth::user()->agent)  return redirect('no-allowed');
          
          $booksQry = Book::whereIn('room_id', $roomsAgents)
                  ->where([
                            ['start','>=',$startYear],
                            ['start','<=',$endYear],
                            ['user_id',Auth::user()->id]
                          ])
                  ->orWhere('agency', Auth::user()->agent->agency_id);
        }
        
        $booksCount['pending'] = 0;
        $booksCount['special'] = 0;
        $booksCount['confirmed']= 0;
        $booksCount['blocked-ical'] = 0;
        $booksCount['deletes'] = 0;
        $booksCount['checkin'] = 0;
        $booksCount['checkout']=0;
        $query2 = clone $booksQry;
        $booksCount['pending'] = $query2->where('type_book', 3)->count();
        $query2 = clone $booksQry;
        $booksCount['reservadas'] = $query2->where('type_book',1)->count();
        $query2 = clone $booksQry;
        $booksCount['special'] = $query2->whereIn('type_book', [7,8])->count();
        $query2 = clone $booksQry;
        $booksCount['confirmed']    = $query2->where('type_book', 2)->count();
        $query2 = clone $booksQry;
        $booksCount['cancel-xml']    = $query2->where('type_book', 98)->count();
        $query2 = clone $booksQry;
        $booksCount['blocked-ical'] = $query2->whereIn('type_book', [11,12])->where("enable", "=", "1")->count();
        $query2 = clone $booksQry;
        $booksCount['blocks']    = $query2->where('type_book', 4)->count();
        $query2 = clone $booksQry;
        $totalReserv = $query2->where('type_book', 1)->count();
        $query2 = clone $booksQry;
        $amountReserv = $query2->where('type_book', 1)->sum('total_price');
        
        $booksCount['deletes']      = Book::where('start', '>', $startYear)->where('finish', '<', $endYear)
                                               ->where('type_book', 0)->where("enable", "=", "1")->count();
        
        $booksCount['checkin']      = $this->getCounters('checkin') + $this->getCounters('especiales');
        $booksCount['checkout']     = $this->getCounters('checkout');
        $booksCount['ff_interesado']= $this->getCounters('INTERESADOS');

        $books = []; 
        $stripe = null;
       
        $lastBooksPayment = \App\Payments::where('created_at', '>=', Carbon::today()->toDateString())
		                                 ->count();
       
        $mobile      = new Mobile();
        
        $alert_lowProfits = 0; //To the alert efect
        $percentBenef     = DB::table('percent')->find(1)->percent;
        $lowProfits       = $this->lowProfitAlert($startYear, $endYear, $percentBenef, $alert_lowProfits);

        $ff_pendientes = Book::where('ff_status',4)->where('type_book','>',0)->count();
        
        $parteeToActive = $this->countPartte();
        
        //BEGIN: Processed data
        $bookOverbooking = null;
        $alarmsCheckPaxs = null;
        $errorsOtaPrices = null;
        $overbooking = [];
        $alarms = 0;
        $oData = \App\ProcessedData::whereIn('key',['overbooking','alarmsPayment','checkPaxs'])->get();
        foreach ($oData as $d){
          switch ($d->key){
            case 'overbooking':
              $overbooking = json_decode($d->content,true);
              break;
            case 'alarmsPayment':
              if (trim($d->content) != '')
                $alarms = count(json_decode($d->content));
              break;
            case 'checkPaxs':
              if (trim($d->content) != '')
                $alarmsCheckPaxs = json_decode($d->content,true);
              break;
          }
        }
        //END: Processed data
        
        $ff_mount = null;
        $urgentes = null;
        if ($parteeToActive > 0){
          $urgentes[] = [
              'action' => 'class="btn btn-danger" id="btnParteeToActive2" test-target="#modalParteeToActive"',
              'text'   => 'Hay PARTEEs no enviados a la policía'
              ];
        }
        $countNotPaidYet = $this->countNotPaidYet();
        if($countNotPaidYet){
          $urgentes[] = [
              'action' => 'class="btn btn-danger  btn-tables" data-type="checkin"',
              'text'   => 'Hay Clientes alojados que no han abonado el 100%'
              ];          
        }
        if(is_array($overbooking) && count($overbooking)>0){
          $urgentes[] = [
              'action' => 'class="btn btn-danger btn-tables"  data-type="overbooking"',
              'text'   => 'Tienes un OVERBOOKING'
              ];          
        }
        if(is_array($alarmsCheckPaxs) && count($alarmsCheckPaxs)>0){
          $urgentes[] = [
              'action' => 'class="btn btn-danger"  type="button" data-toggle="modal" data-target="#modalPAXs"',
              'text'   => 'Se deben controlar el PAXs en reservas'
              ];          
        }
        
        
    
        $errorsOtaPrices = \App\PricesOtas::count();
        if(($errorsOtaPrices)>0){
          $urgentes[] = [
              'action' => 'class="btn btn-danger"  type="button" id="goOtasPrices"',
              'text'   => 'Se deben controlar algunos precios en las OTAs'
              ];          
        }
        /****************************************************************/
        /*bookings_without_Cvc*/
        $bookings_without_Cvc = \App\ProcessedData::findOrCreate('bookings_without_Cvc');
        $bookings_without_Cvc = json_decode($bookings_without_Cvc->content,true);
        if ($bookings_without_Cvc){
          $bookings_without_Cvc = count($bookings_without_Cvc);
        } else {
          $bookings_without_Cvc = 0;
        }
        /****************************************************************/
        //BEGIN: LOGs OTA
        $ota_errLogs = $this->getOTAsLogErros_qty();
        /****************************************************************/
        return view('backend/planning/index',
                compact('books', 'mobile', 'stripe', 'rooms', 
                        'booksCount', 'alarms','lowProfits','alarmsCheckPaxs','errorsOtaPrices',
                        'alert_lowProfits','percentBenef','parteeToActive','lastBooksPayment',
                        'ff_pendientes','ff_mount','totalReserv','amountReserv','overbooking',
                        'urgentes','bookings_without_Cvc','ota_errLogs')
		);
    }

    private function countNotPaidYet() {
      $today = Carbon::now();
      $lstBook =  Book::where('start', '>=', $today->copy()->subDays(3))
              ->where('start', '<=', $today->copy())
              ->whereIn('type_book',[1,2])->get();
      
      if ($lstBook){
        foreach ($lstBook as $book){
          if($book->pending > 1){
            return true;
          }
        }
      }
      
      return null;
    }
    private function countPartte() {
      $today = Carbon::now();
      return Book::Join('book_partees','book.id','=','book_id')
              ->where('start', '>=', $today->copy()->subDays(2))
              ->where('start', '<=', $today->copy())
              ->where('book_partees.status', '!=', 'FINALIZADO')
              ->where('type_book', 2)->orderBy('start', 'ASC')->count();
    }

    private function lowProfitAlert($startYear, $endYear, $percentBenef, &$alert)
    {

        $booksAlarms = \App\Book::where('start', '>', $startYear)->where('finish', '<', $endYear)
                                ->whereIn('type_book', [2,7,8])->orderBy('start', 'ASC')->get();

        $alarms = array();

        foreach ($booksAlarms as $key => $book)
        {
            $inc_percent = $book->get_inc_percent();
            if (round($inc_percent) <= $percentBenef)
            {
                if (!$book->has_low_profit)
                {
                    $alert++;
                }
                $alarms[] = $book;
            }
        }
        return $alarms;
    }

     public function newBook(Request $request){
        if (Auth::user()->role != "agente")
        {
            $rooms = \App\Rooms::where('state', '=', 1)->orderBy('order')->get();
        } else
        {
            $roomsAgents = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
            $rooms       = \App\Rooms::where('state', '=', 1)->whereIn('id', $roomsAgents)->orderBy('order')->get();
        }
        
        $data = [
             'name'      => '',
             'email'     => '',
             'pax'       => '',
             'phone'     => "",
             'book_comments'   => "",
             'date'      => '',
             'start'     => '',
             'finish'    => '',
             'nigths'    => '',
             'newRoomID' => '',
             'luxury'    => 2
           ];
         
        $form_data = $request->input('form_data',null);
        $info = $request->input('info',null);
        if ($form_data){
          foreach ($form_data as $k=>$v){
            $data[$v['name']] = $v['value'];
          }
          $data['pax'] =$data['quantity'];
          $data['nigths'] = calcNights($data['start'],$data['finish']);
        }
        if ($info){
          $info = unserialize($info);
          $data['pvp_promo']= $info['pvp_promo'];
          $data['disc_pvp'] = $info['pvp_discount'];
          $data['luxury']   = ($info['lux'] !== 0) ? 1 : 2;
          $roomCode = trim($info['sugID']);
          $data['newRoomID'] = desencriptID($roomCode);
      
          
        }
        
        return view('backend/planning/_nueva', compact('rooms','data'));
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $mobile = new Mobile();

        $date_start  = $request->input('start',null);
        $date_finish  = $request->input('finish',null);
        if (!$date_start || !$date_finish ) die('error');
        $room = \App\Rooms::find($request->input('newroom'));
        $oFixExtra = \App\Extras::loadFixed($room->sizeApto);
        
        //createacion del cliente
        $customer          = new \App\Customers();
        $customer->name    = $request->input('name');
        $customer->email   = $request->input('email');
        $customer->phone   = ($request->input('phone')) ? $request->input('phone') : "";
        $customer->DNI     = ($request->input('dni')) ? $request->input('dni') : "";
        $customer->address = ($request->input('address')) ? $request->input('address') : "";
        $customer->country = ($request->input('country')) ? $request->input('country') : "";
        $customer->province= ($request->input('province')) ? $request->input('province') : "";     
//        $customer->city    = ($request->input('city')) ? $request->input('city') : "";
        //Creacion de la reserva
        $book   = new \App\Book();
        $book->room_id       = $request->input('newroom');
        $book->start         = $date_start;
        $book->finish        = $date_finish;
        $book->comment       = ($request->input('comments')) ? ltrim($request->input('comments')) : "";
        $book->book_comments = ($request->input('book_comments')) ? ltrim($request->input('book_comments')) : "";
        $book->book_owned_comments = ($request->input('book_owned_comments')) ? $request->input('book_owned_comments') : "";
        $book->type_book     = ($request->input('status')) ? $request->input('status') : 3;
        $book->pax           = $request->input('pax',0);
        $book->real_pax      = $request->input('pax',0);
        $book->agency        = $request->input('agency',0);
        $book->PVPAgencia    = ($request->input('agencia')) ? $request->input('agencia') : 0;
        $book->is_fastpayment = ($book->type_book == 99 ) ? 1:0;
        $book->extraPrice = $oFixExtra->giftPVP;
        $book->extraCost  = $oFixExtra->giftCost;
        $nigths = calcNights($book->start, $book->finish );
        $book->nigths        = $nigths;
        $book->luz_cost      = $request->input('luz_cost',0);//$oFixExtra->luzCost;
        
        
        
        $discount = $request->input('ff_discount',null);
        if ($discount && $discount>0){
          $book->has_ff_discount = 1;
          $book->ff_discount = $discount;
        }
                
                
        if (!$room) die('error');
        $costes = $room->priceLimpieza($room->sizeApto);
        $book->sup_limp  = $costes['price_limp'];
        $book->cost_limp = $costes['cost_limp'];
        //parking        
        $book->sup_park  = $this->getPricePark($request->input('parking'), $nigths) * $room->num_garage;
        $book->cost_park = $this->getCostPark($request->input('parking'), $nigths) * $room->num_garage;
        $book->type_park = $request->input('parking', 0);
        //suplemento de lujo
        $book->type_luxury = $request->input('type_luxury', 0);
        $book->sup_lujo    = $this->getPriceLujo($request->input('type_luxury'));
        $book->cost_lujo   = $this->getCostLujo($request->input('type_luxury'));

        $book->real_price  = $room->getPVP($date_start, $date_finish,$book->pax) + $book->sup_park + $book->sup_lujo + $book->sup_limp;
        

            $isReservable = 0;
            if (in_array($book->type_book, $book->get_type_book_reserved())){
              if ($book->availDate($date_start, $date_finish, $request->input('newroom')))
                $isReservable = 1;
            } else { $isReservable = 1; }

            if ($isReservable == 1){

                //createacion del cliente
                $customer->user_id = (Auth::check()) ? Auth::user()->id : 23;
                if ($customer->save()){
                  $book->user_id             = $customer->user_id;
                  $book->customer_id         = $customer->id;
                  if ($book->type_book == 8){
                      $book->bookingFree();
                    }elseif ($book->type_book == 7){
                      $book->bookingFree();    
                      $book->total_price = $request->input('total');
                    }else{
                      $book->total_price = $request->input('total');
                      $book->cost_apto = ($request->input('costApto')) ? $request->input('costApto') : $room->getCostRoom($date_start,$date_finish,$book->pax);
                      $book->cost_total = ($request->input('cost')) ? $request->input('cost') : $book->get_costeTotal();
                      if ($request->input('costParking'))
                      $book->cost_park = round($request->input('costParking'),2);
                      if (isset($request->priceDiscount) && $request->input('priceDiscount') == "yes"){
                        $discount = Settings::getKeyValue('discount_books');
                        $book->ff_status = 4;
                        $book->ff_discount = $discount;
                        $book->has_ff_discount = 1;
                        $book->real_price  -=  $request->input('discount');
                      }
                      $book->total_ben = intval($book->total_price) - intval($book->cost_total);
                    }
                    
                    $book->schedule    = ($request->input('schedule')) ? $request->input('schedule') : 0;
                    $book->scheduleOut = ($request->input('scheduleOut')) ? $request->input('scheduleOut') : 12;
                    $book->promociones = ($request->input('promociones')) ? $request->input('promociones') : 0;

                    if ($book->save())
                    {
                      if ($book->type_book == 7){
                        $book->bookingProp();
                      }
                      $meta_price = $room->getRoomPrice($book->start, $book->finish, $book->park);
                      $book->setMetaContent('price_detail', serialize($meta_price));
                      $book->sendAvailibilityBy_dates($book->start,$book->finish);
                        /* Creamos las notificaciones de booking */
                        /* Comprobamos que la room de la reserva este cedida a booking.com */
                        if ($room->isAssingToBooking())
                        {
                            $notification          = new \App\BookNotification();
                            $notification->book_id = $book->id;
                            $notification->save();
                        }
                        $customerRequest = $request->input('cr_id',null);
                        if ($customerRequest){
                          $oCustomerRequest = \App\CustomersRequest::find($customerRequest);
                          if ($oCustomerRequest){
                            $oCustomerRequest->book_id = $book->id;
                            $oCustomerRequest->status = 2;
                            $oCustomerRequest->save();
                          }
                        }
                        // MailController::sendEmailBookSuccess( $book, 0);
                        return redirect(route('book.update',$book->id));

                    }
                }

            } else {
              if ( Auth::user()->role != "agente" ){
                return view('backend/planning/_formBook', [
                    'request' => (object) $request->input(),
                    'book'    => new \App\Book(),
                    'rooms'   => \App\Rooms::where('state', '=', 1)->get(),
                    'mobile'  => $mobile
                ]);
              } else {
                return redirect('admin/reservas')->withErrors(['Error: El apartamento ya tiene una reserva confirmada']);
              }
            }
    }

    public function update(Request $request, $id){
      
      $updateBlade = '';
      $hasVisa = false;
      $visaHtml = null;
      $partee = null;
      $oUser = Auth::user();
      $creditCardData = '';
      if ( $oUser->canEditBooking()){
         
          $book  = \App\Book::with('payments')->find($id);
          if (!$book) return redirect('/admin/reservas')->withErrors(['Reserva no encontrada']);
          $rooms = \App\Rooms::orderBy('order')->get();
          if ( $oUser->role == "admin" || $oUser->role == "subadmin"){
            $oVisa = DB::table('book_visa')
                    ->where('book_id',$book->id)
                    ->first();
            if ($oVisa){
              $hasVisa = true;
              $visaData = json_decode($oVisa->visa_data,true);
              $fieldsCard = ["name","number",'date',"cvc",'type','expiration_date'];
              $fieldsName = ["name"=>'Nombre',"number"=>'Nro tarj','date'=>'Vto','expiration_date'=>'Vto',"cvc"=>'CVC','type'=>'Tipo'];
              foreach ($fieldsCard as $f){
                if (isset($visaData[$f])){
                  if ($f == 'date' || $f == 'expiration_date') $visaData[$f] = str_replace ('/20', ' / ', $visaData[$f]);
                  if ($f !== 'cvc' && $f !== 'number'){
                    $visaHtml .= '
                      <div>
                      <label>'.$fieldsName[$f].'</label>
                      <input type="text" class="form-control" value="'.$visaData[$f].'">
                      <button class="btn btn-success copy_data" type="button"><i class="fa fa-copy"></i></button>
                      </div>';
                  }
                  
                }
              }
              
              $visaHtml .= '
                <div>
                <label>Nro tarj</label>
                <input type="text" class="form-control cc_upd" value="'.$oVisa->cc_number.'"  id="cc_number">
                <button class="btn btn-success copy_data" type="button"><i class="fa fa-copy"></i></button>
                </div>                
                <div>
                <label>CVC</label>
                <input type="text" class="form-control cc_upd" value="'.$oVisa->cvc.'"  id="cc_cvc">
                <button class="btn btn-success copy_data" type="button"><i class="fa fa-copy"></i></button>
                <a href="https://online.bnovo.ru/dashboard?q='.$book->external_id.'" class="btn btn-bnovo" target="_black"></a>
                </div>';                

              
              $visaHtml .=  '<div class="btn btn-blue" type="button" id="_getPaymentVisaForce">Refrescar datos</div>';
            }
                    
            $oBookData = \App\BookData::findOrCreate('creditCard',$book->id);
            $creditCardData = $oBookData->content;
          }
          
        if($book->type_book == 2 || $book->type_book == 1 ){
          $partee = $book->partee();
          if (!$partee){
            $partee = new \App\BookPartee();
          }  
        }
          
       } else {
        if ( $oUser->role == "agente"){
          $updateBlade = '-agente';
          $roomsAgents = \App\AgentsRooms::where('user_id', $oUser->id)->get(['room_id'])->toArray();
          $rooms       = \App\Rooms::whereIn('id', $roomsAgents)->orderBy('order')->get();
          $types       = [1,2];
          
          $book = \App\Book::with('payments')
                  ->whereIn('type_book',$types)
                  ->whereIn('room_id', $roomsAgents)
                  ->find($id);
        } else {
          $updateBlade = '-basic';
          $rooms = \App\Rooms::orderBy('order')->get();
          $book  = \App\Book::with('payments')->find($id);
        }
       }

       if (!$book){
         return redirect('/admin/reservas');
       }
        $totalpayment = $book->sum_payments;
        
        $payment_pend = floatVal($book->total_price)-$totalpayment;
        $hasFiance = null;

        /**
         * Check low_profit alert
         */
        $low_profit   = false;
        $inc_percent  = round($book->get_inc_percent());
        $percentBenef = DB::table('percent')->find(1)->percent;

        $book->cost_total = $book->get_costeTotal();
        $book->inc_percent = $inc_percent;
        $book->total_ben = round(intval($book->total_price) - $book->cost_total,2);
        if ($inc_percent <= $percentBenef && !$book->has_low_profit)
          $low_profit = true;
        //END: Check low_profit alert
        
        $priceBook = $book->getMetaContent('price_detail');
        if ($priceBook){
          $priceBook = unserialize($priceBook);
        } else {
          $priceBook = $book->room->getRoomPrice($book->start, $book->finish, $book->park);
        }
        if (!isset($priceBook['PRIVEE'])) $priceBook['PRIVEE'] = 0;

        $email_notif = '';
        $send_notif = '';
        if ($book->customer_id ){
          $email_notif = $book->customer->email_notif ? $book->customer->email_notif : $book->customer->email;
          $send_notif = $book->customer->send_notif ? 'checked' : '';
        }
        
        $otaURL = $this->getOtaURL($book);
        
        /*************************************/
        $cliHasPhotos = \App\BookData::findOrCreate('client_has_photos',$book->id);
        if ($cliHasPhotos)  $cliHasPhotos = $cliHasPhotos->content;
        $cliHasBed = \App\BookData::findOrCreate('client_has_beds',$book->id);
        if ($cliHasBed)  $cliHasBed = $cliHasBed->content;
        $cliHasBabyCarriage = \App\BookData::findOrCreate('client_has_babyCarriage',$book->id);
        if ($cliHasBabyCarriage)  $cliHasBabyCarriage = $cliHasBabyCarriage->content;
        
        
        /*************************************/
    
        return view('backend/planning/update'.$updateBlade, [
            'book'         => $book,
            'oUser'        => $oUser,
            'low_profit'   => $low_profit,
            'hasVisa'      => $hasVisa,
            'visaHtml'     => $visaHtml,
            'rooms'        => $rooms,
            'extras'       => \App\Extras::all(),
            'start'        => Carbon::createFromFormat('Y-m-d', $book->start)->format('d M,y'),
            'payments'     => $book->payments,
            'typecobro'    => new \App\Book(),
            'totalpayment' => $totalpayment,
            'payment_pend' => $payment_pend,
            'mobile'       => new Mobile(),
            'hasFiance'    => $hasFiance,
            'stripe'       => StripeController::$stripe,
            'priceBook'    => $priceBook,
            'email_notif'  => $email_notif,
            'send_notif'   => $send_notif,
            'otaURL'       => $otaURL,
            'partee'       => $partee,
            'cliHasPhotos' => $cliHasPhotos,
            'cliHasBed'    => $cliHasBed,
            'cliHasBabyCarriage'    => $cliHasBabyCarriage,
            'creditCardData' => $creditCardData,
        ]);
    }

    //Funcion para actualizar la reserva
    public function saveUpdate(Request $request, $id){
      $IS_agente = false;
      if ( Auth::user()->role == "agente"){
         return [
                'status'   => 'warning',
                'title'    => 'Cuidado',
                'response' => "No puedes hacer cambios en la reserva"
            ];
       }

       $book = Book::find($id);
       if (!$book){
         return [
                'status'   => 'danger',
                'title'    => 'ERROR',
                'response' => "RESERVA NO ENCONTRADA"
            ];
       }
       
        $computedData = json_decode($request->input('computed_data'));
        
        $start = $request->input('start');
        $finish = $request->input('finish');
        $customer          = \App\Customers::find($request->input('customer_id'));
        $customer->DNI     = ($request->input('dni')) ? $request->input('dni') : "";
        $customer->address = ($request->input('address')) ? $request->input('address') : "";
        $customer->country = ($request->input('country')) ? $request->input('country') : "";
//        $customer->city    = ($request->input('city')) ? $request->input('city') : "";
        $customer->province = ($request->input('province')) ? $request->input('province') : 28;
        $customer->save();

        
        if (!in_array($book->type_book, $book->typeBooksReserv) || Book::availDate($start, $finish, $request->input('newroom'),$book->id))
        {

            $OldRoom   = $book->room_id;
            $oldStart  = $book->start;
            $oldFinish = $book->finish;

            $room = \App\Rooms::find($request->input('newroom'));

            //$book->user_id     = Auth::user()->id;
            $book->customer_id = $request->input('customer_id');
            $book->room_id     = $request->input('newroom');

            $book->start               = $start;
            $book->finish              = $finish;
            $book->comment             = ltrim($request->input('comments'));
            $book->book_comments       = ltrim($request->input('book_comments'));
            $book->pax                 = $request->input('pax');
            $book->real_pax            = $request->input('real_pax');
            $book->promociones         = $request->input('promociones');
            $book->nigths              = calcNights($book->start, $book->finish );

            
            if ($book->type_book == 7){
//              $book->bookingProp($room);
              $book->bookingFree();
            } 
            else
            {
              if ($computedData){
                $book->sup_park  = $computedData->totales->parking;
                $book->sup_limp  = $computedData->totales->limp;
                $book->cost_limp = $computedData->costes->limp;
                $book->real_price = $computedData->calculated->real_price;
              }
            }

            if ($book->type_book !== 8){
              $book->type_park  = $request->input('parking');
              $book->agency     = $request->input('agency');
              $book->PVPAgencia = $request->input('agencia') ? : 0;
              $book->type_luxury = $request->input('type_luxury');
              if ($computedData){
                $book->sup_lujo    = intval($computedData->totales->lujo);
                $book->cost_lujo   = $computedData->costes->lujo;
              }

              $book->total_price = $request->input('total_pvp'); // This can be modified in frontend
              if ($request->input('costApto'))   $book->cost_apto = $request->input('costApto');
              //Parking NO o Gratis
              if ($book->type_park == 2 || $book->type_park == 3) $book->cost_park = 0;
              if ($request->input('costParking'))  $book->cost_park  = $request->input('costParking');
              if(!$IS_agente){  
                $book->extra     = $request->input('extra');
                $book->schedule    = $request->input('schedule');
                $book->scheduleOut = $request->input('scheduleOut');
                $book->luz_cost    = intval($request->input('luz_cost',0));
                if ($request->updMetaPrice == 1){
                  $book->promociones = ($request->input('promociones')) ? $request->input('promociones') : 0;
                  $book->book_owned_comments = ($request->input('book_owned_comments')) ? $request->input('book_owned_comments') : "";
                }

                $book->has_ff_discount = $request->input('has_ff_discount',0);
                if (!$book->has_ff_discount && $book->ff_status == 4){
                  $book->ff_status = 0;
                } else {
                  if ($book->has_ff_discount && $book->ff_status == 0){
                    $book->ff_status = 4;
                  }
                }
                $book->ff_discount = $request->input('ff_discount',0);
                if ($computedData){
                  $book->real_price  = $computedData->calculated->real_price; // This cannot be modified in frontend
                }
              }
            }
            if ($book->type_book == 8 || $book->type_book == 7){
                $book->bookingFree();
            }
            $book->cost_total = $book->get_costeTotal();
            if ($book->save()){
              
              if ($request->updMetaPrice == 1){
                $meta_price = $room->getRoomPrice($book->start, $book->finish, $book->park);
                $book->setMetaContent('price_detail', serialize($meta_price));
              }
              
              
              //si esta reservada, cambio la disponibilidad
              if (in_array($book->type_book,$book->typeBooksReserv)){
               
                  $auxStart = $book->start;
                  $auxFinish = $book->finish;
                  if($oldStart != $auxStart || $oldFinish != $auxFinish){
                    if ($oldStart<$auxStart) $date1 = $oldStart;
                    else $date1 = $auxStart;
                    if ($oldFinish>$auxFinish) $date2 = $oldFinish;
                    else $date2 = $auxFinish;

                    if ($OldRoom != $book->room_id){
                      $book->sendAvailibilityBy_Rooms($OldRoom,$date1,$date2);
                    } else {
                      $book->sendAvailibilityBy_dates($date1,$date2);
                    }
                  } else {
                    if ($OldRoom != $book->room_id){
                      $book->sendAvailibilityBy_Rooms($OldRoom);
                    }
                  }
              }
              
              
              if ($book->room->isAssingToBooking())
              {

                  $isAssigned = \App\BookNotification::where('book_id', $book->id)->get();

                  if (count($isAssigned) == 0)
                  {
                      $notification          = new \App\BookNotification();
                      $notification->book_id = $book->id;
                      $notification->save();
                  }
              } else
              {
                  $deleted = \App\BookNotification::where('book_id', $book->id)->delete();
              }

              return [
                  'status'   => 'success',
                  'title'    => 'OK',
                  'response' => "ACTUALIZACION CORRECTA"
              ];
            }
        } else
        {

            return [
                'status'   => 'danger',
                'title'    => 'ERROR',
                'response' => "NO HAY DISPONIBILIDAD EN EL PISO PARA LAS FECHAS SELECCIONADAS"
            ];
        }
    }

  /**
   * Change Status or Rooms
   * @param Request $request
   * @param type $id
   * @return type
   */
  public function changeBook(Request $request, $id){
  
      if (isset($request->room) && !empty($request->room)){

          $book = \App\Book::find($id);
          $oldRoom = $book->room_id;
          $isReservable = 0;
          if (in_array($book->type_book, $book->get_type_book_reserved())){
            if (book::availDate($book->start, $book->finish, $request->room,$book->id)){
               $isReservable = 1;
            } else return ['status' => 'danger', 'title' => 'Error', 'response' => 'Habitación No disponible','changed'=>false];
          } else  $isReservable = 1;

          if ($isReservable){
            $book->room_id = $request->room;
            $book->save();
            $book->sendAvailibilityBy_Rooms($oldRoom);
            return ['status' => 'success', 'title' => 'OK', 'response' => 'Habitación modificada','changed'=>false];
          }

          return ['status' => 'danger', 'title' => 'Error', 'response' => 'Habitación no modificada','changed'=>false];

      }

      if (isset($request->status) && !empty($request->status)){
        
          $book = Book::find($id);
          $oldStatus = $book->type_book;
          $response = $book->changeBook($request->status, "", $book);
          if ($response['changed']){
              $book->sendAvailibilityBy_status();
          }
          return $response;

      } else {
        
          return [
              'status'   => 'danger',
              'title'    => 'Error',
              'response' => 'No hay datos para cambiar, por favor intentalo de nuevo'
          ];
      }
    }

    /**
     * @param        $park             Parking Option (Yes, No, Free, 50%)
     * @param        $noches           Book Nights
     * @param string $room             Room ID
     * @return float|int
     */
    public static function getPricePark($typePark, $nights)
    {
        $priceParking   = 0;
        $parkPvpSetting = Settings::where('key', Settings::PARK_PVP_SETTING_CODE)->first();
        if (!$parkPvpSetting) return 0;
        if ($nights<1) return 0;
        switch ($typePark)
        {
            case 1: // Yes
                $priceParking = $parkPvpSetting->value * $nights;
                break;
            case 2: // No
            case 3: // Free
                $priceParking = 0;
                break;
            case 4: // 50%
                $priceParking = ($parkPvpSetting->value * $nights) / 2;
                break;
        }
        return $priceParking;

    }

    public static function getCostPark($typePark, $nights)
    {
        $costParking     = 0;
        $parkCostSetting = Settings::where('key', Settings::PARK_COST_SETTING_CODE)->first();
        if (!$parkCostSetting) return 0;
        if ($nights<1) return 0;
        switch ($typePark)
        {
            case 1: // Yes
                $costParking = $parkCostSetting->value * $nights;
                break;
            case 2: // No
            case 3: // Free
                $costParking = 0;
                break;
            case 4: // 50%
                $costParking = ($parkCostSetting->value * $nights) / 2;
                break;
        }
        return $costParking;

    }

    public function getPVPParking(){
      $costParking     = 0;
      $parkCostSetting = Settings::where('key', Settings::PARK_PVP_SETTING_CODE)->first();
      if (!$parkCostSetting) return 0;
      return $parkCostSetting->value;
    }
    public function getPVPLujo(){
      $luxuryPvpSetting = Settings::where('key', Settings::LUXURY_PVP_SETTING_CODE)->first();
      if (!$luxuryPvpSetting) return 0;
      return $luxuryPvpSetting->value;
    }
    public static function getPriceLujo($typeLuxury)
    {
      //default, no or free
      if ($typeLuxury == 0 || $typeLuxury == 2 || $typeLuxury == 3) return 0;
      
        $priceLuxury      = 0;
        $luxuryPvpSetting = Settings::where('key', Settings::LUXURY_PVP_SETTING_CODE)->first();
        if (!$luxuryPvpSetting) return 0;

        switch ($typeLuxury)
        {
            case 1: // Yes
                $priceLuxury = $luxuryPvpSetting->value;
                break;
            case 2: // No
            case 3: // Free
                $priceLuxury = 0;
                break;
            case 4: // 50%
                $priceLuxury = $luxuryPvpSetting->value / 2;
                break;
        }

        return $priceLuxury;
    }

    /**
     * @param $typeLuxury
     * @return float|int
     */
    public static function getCostLujo($typeLuxury)
    {
      //default, no or free
      if ($typeLuxury == 0 || $typeLuxury == 2 || $typeLuxury == 3) return 0;
      
        $costLuxury        = 0;
        $luxuryCostSetting = Settings::where('key', Settings::LUXURY_COST_SETTING_CODE)->first();
        if (!$luxuryCostSetting) return 0;

        switch ($typeLuxury)
        {
            case 1: // Yes
                $costLuxury = floatval($luxuryCostSetting->value);
                break;
            case 2: // No
            case 3: // Free
                $costLuxury = 0;
                break;
            case 4: // 50%
                $costLuxury = floatval($luxuryCostSetting->value) / 2;
                break;
        }

        return round($costLuxury,2);
    }

    //Funcion para coger la reserva mobil
    public function tabReserva($id)
    {
        $book = \App\Book::find($id);
        return $book;
    }

    //Funcion para Cobrar desde movil
    public function cobroBook($id)
    {
        $book     = \App\Book::find($id);
        $payments = \App\Payments::where('book_id', $id)->get();
        $pending  = 0;

        foreach ($payments as $payment){
            $pending += $payment->import;
        }

        return view('backend/planning/_updateCobro', [
            'book'     => $book,
            'pending'  => $pending,
            'payments' => $payments,
        ]);
    }

    // Funcion para Cobrar
    public function saveCobro(Request $request)
    {
        $payment = new \App\Payments();

        $payment->book_id     = $request->id;
        $payment->datePayment = Carbon::CreateFromFormat('d-m-Y', $request->fecha);
        $payment->import      = $request->import;
        $payment->type        = $request->tipo;

        if ($payment->save()){
            return redirect()->action('BookController@index');
        }

    }

    // Funcion para elminar cobro
    public function deleteCobro($id)
    {
      $payment = \App\Payments::find($id);
      if ($payment->delete()){
          return redirect()->back();
        }

    }

    //Funcion para guardar Fianza
    public function saveFianza(Request $request)
    {
        $fianza = new \App\Bail();

        $fianza->id_book    = $request->id;
        $fianza->date_in    = Carbon::CreateFromFormat('d-m-Y', $request->fecha);
        $fianza->import_in  = $request->fianza;
        $fianza->comment_in = $request->comentario;
        $fianza->type       = $request->tipo;

        if ($fianza->save()){
            return redirect()->action('BookController@index');
        }

    }

    public function emails($id)
    {
      $book = \App\Book::find($id);
      return view('backend.emails.comprobar-fechas', ['book' => $book]);
    }

    public function sendEmail(Request $request){
        $book = \App\Book::find($request->input('id'));
        if ($book){
           if (!$book->customer->email || trim($book->customer->email) == '') return 'El cliente no posee email';
          $mailClientContent = $request->input('textEmail');
          $subject = 'Disponibilidad para tu reserva';
          $sended = Mail::send('backend.emails.base', [
              'mailContent' => $mailClientContent,
              'title'       => $subject
          ], function ($message) use ($book, $subject) {
              $message->from(env('MAIL_FROM'));
              $message->to($book->customer->email);
              $message->subject($subject);
              $message->replyTo(env('MAIL_FROM'));
          });
          \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'sendEmailDisp','Disponibilidad para tu reserva',$request->input('textEmail'));
          // $book->send = 1;
          $book->type_book = 5;
          if ($book->save())  return 'OK';
        }
        return 'Reserva no encontrada';
    }

    public function ansbyemail($id)
    {
        $book = \App\Book::find($id);
        $mail = $this->getMailData($book, 'reserva-constestado-email');
        return view('backend/planning/_answerdByEmail', [
            'book'   => $book,
            'text_mail'   => $mail,
            'mobile' => new Mobile()
        ]);
    }

  public function delete($id) {
    try {
      $book = Book::find($id);
      $payments = $book->payments;
      if (count($payments) > 0) {
        $total = 0;
        foreach ($payments as $index => $pago){
          $total += $pago->import;
        }
        if ($total>0){
          return [
              'status' => 'danger',
              'title' => 'Error:',
              'response' => "La Reserva posee cargos asociados."
          ];
        }
      }
      foreach ($book->notifications as $key => $notification) {
        $notification->delete();
      }

      //Borro el gasto generado por la limpieza
      \App\Expenses::delExpenseLimpieza($book->id);


      $book->type_book = 0;

      if ($book->save()) {
        $book->sendAvailibilityBy_status($book->start,$book->finish);
        return [
            'status' => 'success',
            'title' => 'OK',
            'response' => "Reserva enviada a eliminadas"
        ];
      }
    } catch (Exception $e) {

      return [
          'status' => 'danger',
          'title' => 'Error',
          'response' => "No se ha podido borrar la reserva error: " . $e->message()
      ];
    }
  }
    
    public function searchByName(Request $request)
    {
        if ($request->searchString == '')
        {
            return response()->json('', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $year     = $this->getActiveYear();
        $dateFrom = $year->start_date;
        $dateTo   = $year->end_date;

        $aCustomer = explode(' ', $request->searchString);
        $customerIds = [];
        if (is_array($aCustomer)){
          $sql = \App\Customers::whereNotNull('id');
          foreach ($aCustomer as $name)
            $sql->where('name', 'LIKE', '%' . $name . '%');
          
          $customerIds = $sql->pluck('id')->toArray();
        }

        if (count($customerIds) <= 0)
        {
          $otaID = intval($request->searchString);
            $books = Book::with('payments')
                ->where('external_id', $otaID)
                ->orWhere('bkg_number', $otaID)
                ->orWhere('id', $otaID)
                ->orderBy('start', 'ASC')->get();
            if (count($books)==0){
              return "<h2>No hay reservas para este término '" . $request->searchString . "'</h2>";
            }
        } else {
          $books = Book::with('payments')
                ->whereIn('customer_id', $customerIds)
                ->where('start', '>=', $dateFrom)
                ->where('start', '<=', $dateTo)
                ->where('type_book', '!=', 9)->where('type_book', '!=', 0)
                ->orderBy('start', 'ASC')->get();
        }

        $payments = [];
        $paymentStatus = array();
        foreach ($books as $book)
        {
          $amount = $book->payments->pluck('import')->sum();
          $payments[$book->id] = $amount;
          if ($amount>=$book->total_price) $paymentStatus[$book->id] = 'paid';
           else {
             if ($amount>=($book->total_price/2)) $paymentStatus[$book->id] = 'medium-paid';
           }
        }
        
        
        return view('backend/planning/listados/_resultSearch', [
            'books'   => $books,
            'payment' => $payments,
            'paymentStatus'=>$paymentStatus
        ]);

    }

    public function getTableData(Request $request)
    {
        $mobile    = new Mobile();
        $year      = self::getActiveYear();
        $startYear = new Carbon($year->start_date);
        $endYear   = new Carbon($year->end_date);
        $books     = [];
        $pullSent  = [];
        $cliHas    = [];
        $oUser     = Auth::user();
        $uRole     = getUsrRole();

        if (!$oUser->canSeePlanning()){
          return '<p><h2>Ups!! No tienes autorización para ver el contenido solicitado.</h2></p>';
        }
        if ($uRole == "limpieza"){
          if (!($request->type == 'checkin' || $request->type == 'checkout')){
            $request->type = 'checkin';
          }
        }
          
        if ($uRole != "agente")
        {
            $roomsAgents = \App\Rooms::all(['id'])->toArray();
            $rooms       = \App\Rooms::orderBy('order')->get();
            $types       = Book::get_type_book_pending();
            $booksQuery = Book::where_book_times($startYear, $endYear)
            ->with('room','payments','customer','leads');
        } else
        {
            $roomsAgents = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
            $rooms       = \App\Rooms::whereIn('id', $roomsAgents)->orderBy('order')->get();
            $types       = [1];
            $roomsAgents = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
            $agency = Auth::user()->agent->agency_id;
            $booksQuery = Book::where_book_times($startYear, $endYear)
                ->with('room','payments','customer','leads')
                ->where(function ($query2) use ($roomsAgents,$agency) {
                    $query2->whereIn('room_id', $roomsAgents)
                    ->orWhere('agency', Auth::user()->agent->agency_id);
                });
                
        }

        switch ($request->type) {
          case 'pendientes':
            if ($uRole != "agente") {
              $types = Book::get_type_book_pending();
              unset($types[array_search(4,$types)]);
              $books = $booksQuery->whereIn('type_book', $types)
                              ->orderBy('created_at', 'DESC')->get();
            } else {
              
              $books = $booksQuery->where('type_book', 1)
                              ->where('user_id', Auth::user()->id)
                              ->orderBy('created_at', 'DESC')->get();
            }
            $bg_color = '#295d9b';
            break;
          case 'reservadas':
            $books = $booksQuery->where('type_book', 1)
                            ->orderBy('created_at', 'DESC')->get();
            $bg_color = '#53ca57';
            break;
          case 'especiales':
            $books = $booksQuery->whereIn('type_book', [7, 8])->orderBy('created_at', 'DESC')->get();
            $bg_color = 'orange';
            break;
          case 'confirmadas':
              $books = $booksQuery->with('LogImages')->where('type_book', 2)->orderBy('created_at', 'DESC')->get();
            break;
          case 'ff_pdtes_2':

            $dateX = Carbon::now();
            $books = \App\Book::where('ff_status', 4)->where('type_book', '>', 0)
                            ->where('start', '>=', $dateX->copy()->subDays(3))
                            ->with('room','payments','customer','leads')
                            ->orderBy('start', 'ASC')->get();
    //                $books = \App\Book::where('start', '>=', $dateX->copy()->subDays(3))->where('start', '<=', $year->end_date)
    //                                  ->where('type_book', 2)->orderBy('start', 'ASC')->get();

            break;
          case 'checkin':
          case 'ff_pdtes':
            $dateX = Carbon::now();
            // agregamos las especiales 7, 8
            $booksQuery = \App\Book::where('start', '>=', $dateX->copy()->subDays(3))
                            ->where('start', '<=', $year->end_date)
                            ->with('room','payments','customer','leads')
                            ->whereIn('type_book', [1, 2, 7, 8])->orderBy('start', 'ASC');
            if ($uRole == "agente")
            {
                $booksQuery->where(function ($query2) use ($roomsAgents,$agency) {
                    $query2->whereIn('room_id', $roomsAgents)
                    ->orWhere('agency', Auth::user()->agent->agency_id);
                });
            }
            $books = $booksQuery->get();
            $cliHas = Book::cliHas_lst($booksQuery->pluck('id'));
            break;
          case 'checkout':
            $dateX = Carbon::now();
            $booksQuery = \App\Book::where('finish', '>=', date('Y-m-d'))
                            ->where('finish', '<', $year->end_date)
                            ->with('room','payments','customer','leads')
                            ->whereIn('type_book',[1,2,7,8])->orderBy('finish', 'ASC');
            
            if ($uRole == "agente"){
                $booksQuery->where(function ($query2) use ($roomsAgents,$agency) {
                    $query2->whereIn('room_id', $roomsAgents)
                    ->orWhere('agency', Auth::user()->agent->agency_id);
                });
            }
            $books = $booksQuery->get();
            
            if ($books){
              $bList = [];
              foreach ($books as $book){
                $bList[] = $book->id;
              }

              if (count($bList) > 0){
                $pullSent = \App\BookData::whereIn('book_id',$bList)
                  ->where('key','sent_poll')->pluck('book_id')->toArray();
              }
            }
            break;
          case 'blocks':
                $books = $booksQuery->where('type_book', 4)
                  ->orderBy('created_at', 'DESC')->limit(500)->get();

            $bg_color = '#448eff';
          break;
          case 'eliminadas':
            $books = $booksQuery->where('type_book', 0)->orderBy('updated_at', 'DESC')->get();
            break;
          case 'cancel-xml':
            $books = $booksQuery->where('type_book', 98)->orderBy('updated_at', 'DESC')->get();
            break;
          case 'ff_interesado':
            $books = $booksQuery->where('ff_status', 5)
                  ->where('start', '>=', date('Y-m-d'))
                  ->with('room','payments','customer','leads')
                  ->whereIn('type_book', Book::get_type_book_sales(true,true))
                  ->orderBy('start', 'ASC')->get();
            $cliHas = Book::cliHas_lst($booksQuery->pluck('id'));
            break;
          case 'blocked-ical':
            $books = $booksQuery->where('start', '>=', $startYear->copy()->subDays(3))
                            ->with('room', 'payments', 'customer')
                            ->where('finish', '<=', $endYear)->whereIn('type_book', [11, 12])
                            ->orderBy('updated_at', 'DESC')->get();
            break;
          case 'overbooking':
            //BEGIN: Processed data
            $bookOverbooking = null;
            $overbooking = [];
            $oData = \App\ProcessedData::where('key', 'overbooking')->first();
            if ($oData)
              $overbooking = json_decode($oData->content, true);
            $books = Book::whereIn('id', $overbooking)->orderBy('updated_at', 'DESC')->get();
            break;
        }


        $type = $request->type;
        $payment = array();
        $paymentStatus = array();
        foreach ($books as $book){
          $amount = $book->payments->pluck('import')->sum();
          $payment[$book->id] = $amount;
          if ($amount>=$book->total_price) $paymentStatus[$book->id] = 'paid';
           else {
             if ($amount>=($book->total_price/2)) $paymentStatus[$book->id] = 'medium-paid';
           }
        }
           
        return view('backend/planning/_table', compact('books', 'rooms', 'type', 'mobile','pullSent','payment','cliHas'));
    }

    /**
     * /reservas/api/lastsBooks/{type?}
     * 
     * @param type $type
     * @return type
     */
    public function getLastBooks($type=null)
    {
        $mobile = new Mobile();

        $year      = self::getActiveYear();
        $startYear = new Carbon($year->start_date);
        $endYear   = new Carbon($year->end_date);
        $bMonth = [];
        $bWeek = [];
        $total = 0;
        $cW = date('W');
        $cMonth = date('n');
        
        $alarmsPayment = [];
        $oData = \App\ProcessedData::where('key','alarmsPayment')->first();
        if (trim($oData->content) != ''){
          $alarmsPayment = json_decode($oData->content);
        }
        /*************************************************************************************/
        $uRole     = getUsrRole();
        if ($uRole == "limpieza" || $uRole == "agente"){
          die;
        }
        /*************************************************************************************/
        switch ($type){
          case 'pendientes':
            if (count($alarmsPayment)>0){
              $qry_lst = Book::whereIn('id',$alarmsPayment);
            } else $qry_lst = Book::where('id',-1);
            break;
          case 'week':
            $fecha_actual = date("Y-m-d");
            $qry_lst = Book::where('updated_at','>',date("Y-m-d",strtotime($fecha_actual."- 7 days")));
            break;
          case 'month':
            $fecha_actual = date("Y-m-d");
            $qry_lst = Book::where('updated_at','>',date("Y-m-d",strtotime($fecha_actual."- 31 days")));
            break;
          default :
            $qry_lst = Book::where_book_times($startYear, $endYear)->limit(50);
            break;
        }
        
        $toDay = new \DateTime();
        $toDayEnd = date('Y-m-d');
        $daysToCheck = \App\DaysSecondPay::find(1)->days;
        
        $lst = $qry_lst->whereIn('type_book',[1,2,7,8,98])
            ->with('room','payments','customer','leads')
            ->orderBy('start','DESC')->get();

        $books = [];
        foreach ($lst as $book)
        {
          $aux = [
              'agency' => ($book->agency != 0) ? '/pages/'.strtolower($book->getAgency($book->agency)).'.png' : null,
              'id'=> $book->id,
              'name'=> $book->customer->name,
              'url'=> url ('/admin/reservas/update').'/'.$book->id,
              'room'=>substr($book->room->nameRoom,0,5),
              'start'=>convertDateToShow($book->start),
              'finish'=>convertDateToShow($book->finish),
              'pvp'=> moneda($book->total_price),
              'status'=>'',
              'tbook'=>$book->type_book,
              'btn-send'=>($book->send == 1) ? 'btn-default' : 'btn-primary',
              'payment'=>0,
              'toPay'=>0,
              'percent'=>0,
              'week'=>'',
              'month'=>'',
          ];
          
          $payments = \App\Payments::where('book_id', $book->id)->get();
          $paymentBook = 0;
          $countPays = 0;
          if ( count($payments) > 0){
            foreach ($payments as $key => $payment){
              $paymentBook += $payment->import;
              $countPays++;
            }
          }
          
          $total += $paymentBook;
          $aux['payment'] = $paymentBook;
          $aux['toPay'] = $book->total_price-$paymentBook;
          $aux['percent'] = ($book->total_price>0) ? round(($paymentBook/$book->total_price)*100): 0;
          
          $aux['retrasado'] = false;
          //Pago retrasado
          if (!in_array($book->type_book, [98,7,8])){
            if ($toDayEnd >= $book->start) $aux['retrasado'] = true;
            else {
              $date2 = new \DateTime($book->start);
              $diff = $date2->diff($toDay);
              $aux['retrasado'] = ($diff->days<$daysToCheck) ? true : false;
            }
          }

          
          if($countPays > 0){
              $aux['status'] = $countPays. 'º PAGO OK';
          } else {
            switch($book->type_book){
              case 1: $aux['status'] = 'RESERVADO'; break;
              case 2: $aux['status'] = 'PENDT COBRO'; break;
              case 7: $aux['status'] = 'PROPIETARIO'; break;
              case 8: $aux['status'] = 'ATIPICA'; break;
              case 98: $aux['status'] = 'CANCEL'; break;
            } 
          }
          $books[] = $aux;
          
          /*
           * 
           * controlar el pago,y ver la fecha del pago para ver si lo ponemos en la semana o mes
           * 
           */
        }
        
        
        return view('backend.planning._lastBookPayment', compact('books','bMonth','bWeek', 'mobile','total','type','alarmsPayment'));
//        dd($books,$bWeek);
//        
        
        
        
        /************************************************************************************/

        
        $lst = \App\Payments::orderBy('id', 'DESC')->limit(100)->get();
        $booksAux = array();
        $bMonth = [];
        $bWeek = [];
        $total = 0;
        $cW = date('W');
        $cMonth = date('n');
        foreach ($lst as $key => $payment)
        {
            if (!in_array($payment->book_id, $booksAux)){
              $booksAux[] = $payment->book_id;
              $total++;
              $timer = strtotime($payment->datePayment);
              if (date('n',$timer) == $cMonth){
                $bMonth[] = $payment->book_id;
                if (date('W',$timer) == $cW){
                  $bWeek[] = $payment->book_id;
                } 
              }
            }
            if ($total == 50) break;
        }
        
        $books = Book::whereIn('id',$booksAux)->get();
        return view('backend.planning._lastBookPayment', compact('books','bMonth','bWeek', 'mobile'));

    }

    function getAlertsBooking(Request $request)
    {
        return view('backend.planning._tableAlertBooking', compact('days', 'dateX', 'arrayMonths', 'mobile'));
    }

    public function getCalendarMobileView($month=null){
      return $this->getCalendarView($month,null);
    }
    public function getCalendarChannelView($chGr,$month=null){
      $roomIDs = Rooms::where('channel_group',$chGr)->pluck('id');
      return $this->getCalendarView($month,$roomIDs,false);
    }
    
    public function getCalendarView($month=null,$roomIDs= null,$showTotals=true){
      
      if (!Auth::check()) return null;
        $mes           = [];
        $arrayReservas = [];
        $arrayMonths   = [];
        $arrayDays     = [];
        $year          = $this->getActiveYear();
        $startYear     = new Carbon($year->start_date);
        $endYear       = new Carbon($year->end_date);
        $totalSales    = 0;
        $type_book_not = [0,3,6,12,98,99];
        $type_book_sales = [1,2,8,11];
        $uRole = Auth::user()->role;
        $mobile = new Mobile();
        $isMobile = $mobile->isMobile();
        if (!$month){
          $month = strtotime($year->year.'-'.date('m').'-01');
          if (strtotime($startYear)>$month){
            $month = strtotime(($year->year+1).'-'.date('m').'-01');
          }
        }
        
        if ($showTotals){
          //just to jlargo && MariaJose
          $usrAdmin = Auth::user()->email;
          if( $usrAdmin != "jlargo@mksport.es" && $usrAdmin != "info@eysed.es"){
            $totalSales = null;
            $showTotals = false;
          }
        }
        
        $currentM = date('n',$month);
        $startAux = new Carbon(date('Y-m-d', strtotime('-1 months',$month)));
        $endAux = new Carbon(date('Y-m-d', strtotime('+1 months',$month)));
        $startAux->firstOfMonth();
        $endAux->lastOfMonth();
        $sqlBook = Book::where_book_times($startAux,$endAux)
                ->select('book.*',DB::raw("book_data.content as 'client_has_photos'"))
                ->whereNotIn('type_book', $type_book_not);
        
        if ($roomIDs){
          $sqlBook->whereIn('room_id', $roomIDs);
        }
        
        $books = $sqlBook->leftJoin('book_data', function($join)
                         {
                             $join->on('book.id','=','book_data.book_id');
                             $join->on('book_data.key','=',DB::raw("'client_has_photos'"));
                         })
                ->orderBy('start', 'ASC')->get();
        /****************************************/    
        $bookings_without_Cvc = \App\ProcessedData::findOrCreate('bookings_without_Cvc');
        $bookings_without_Cvc = json_decode($bookings_without_Cvc->content,true);
        if (!$bookings_without_Cvc || !is_array($bookings_without_Cvc)){
          $bookings_without_Cvc = [];
        }
        /****************************************/  
        
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");
        $uRole = Auth::user()->role;
        foreach ($books as $book)
        {
          $start = strtotime($book->start);
          $finish = strtotime($book->finish);
          $salesMonth = 0;
          $pvpNight = ($book->nigths>0) ? $book->total_price / $book->nigths : $book->total_price;
          $event = $this->calendarEvent($book,$uRole,$isMobile,$bookings_without_Cvc);
          while($start<$finish){
            $arrayReservas[$book->room_id][date('Y',$start)][date('n',$start)][date('j',$start)][] = $event;
            if (date('n',$start) ==  $currentM) $salesMonth += $pvpNight;
            $start = strtotime("+1 day", $start);
          }
          $arrayReservas[$book->room_id][date('Y',$start)][date('n',$start)][date('j',$start)][] = $event;

            if ($showTotals){
              if (!$book->nigths || $book->nigths == 0) $salesMonth = $pvpNight;
                if (in_array($book->type_book,$type_book_sales)){
                    $totalSales += $salesMonth;
                }
            }
        }
        
         
        
        
        $firstDayOfTheYear = $startAux->copy();
        $dayWeek = ["D","L","M","X","J","V", "S"];
        for ($i = 1; $i < 4; $i++)
        {

            $mes[$firstDayOfTheYear->copy()->format('n')] = $firstDayOfTheYear->copy()->format('M Y');

            $startMonth = $firstDayOfTheYear->copy()->startOfMonth();
            $day        = $startMonth;

            $arrayMonths[$firstDayOfTheYear->copy()->format('n')] = $day->copy()->format('t');

            for ($j = 1; $j <= $day->copy()->format('t'); $j++)
            {

                $arrayDays[$firstDayOfTheYear->copy()->format('n')][$j] = $dayWeek[$day->copy()->format('w')];
                $day                                                    = $day->copy()->addDay();

            }

            $firstDayOfTheYear->addMonth();

        }

        if (Auth::user()->role != "agente")
        {
            $sqlRooms = \App\Rooms::where('state', '=', 1);
        } else
        {
            $roomsAgents   = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
            $sqlRooms = \App\Rooms::where('state', '=', 1)->whereIn('id', $roomsAgents);
        }
        
        if ($roomIDs){
          $sqlRooms->whereIn('id', $roomIDs);
        }
        
        $roomscalendar = $sqlRooms->orderBy('order', 'ASC')->get();
        $days = $arrayDays;
        
      $buffer = ob_html_compress(view('backend.planning.calendar.content', 
              compact('arrayMonths',
                      'roomscalendar', 'arrayReservas', 'mes', 
                      'days', 'startYear', 'endYear','currentM','startAux','totalSales')));
      return view('backend.planning.calendar.index',['content'=>$buffer]);
      
    }

    static function getCounters($type)
    {
        $year       = self::getActiveYear();
        $startYear  = new Carbon($year->start_date);
        $endYear    = new Carbon($year->end_date);
        $booksCount = 0;
        switch ($type)
        {
            case 'pendientes':
                $booksCount = \App\Book::where('start', '>=', $startYear->copy()->format('Y-m-d'))
                                       ->where('finish', '<=', $endYear->copy()->format('Y-m-d'))
                                       ->whereIn('type_book', [
                                           3,
                                           11
                                       ])->count();
                break;
            case 'especiales':
                $booksCount = \App\Book::where('start', '>=', $startYear->copy()->format('Y-m-d'))
                                       ->where('finish', '<=', $endYear->copy()->format('Y-m-d'))
                                       ->whereIn('type_book', [
                                           7,
                                           8
                                       ])->count();
                break;
            case 'confirmadas':
                $booksCount = \App\Book::where('start', '>=', $startYear->copy()->format('Y-m-d'))
                                       ->where('finish', '<=', $endYear->copy()->format('Y-m-d'))
                                       ->whereIn('type_book', [2])->count();
                break;
            case 'checkin':
                $dateX      = Carbon::now();
                $booksCount = \App\Book::where('start', '>=', $dateX->copy()->subDays(3))->where('start', '<=', $year->end_date)
                                  ->where('type_book', 2)->orderBy('start', 'ASC')->count();
//                $booksCount = \App\Book::where('start', '>=', $dateX->copy()->subDays(3))->where('finish', '<=', $dateX)
//                                       ->where('type_book', 2)->count();
                break;
            case 'blocked-ical':
                $dateX      = Carbon::now();
                $booksCount = \App\Book::where('start', '>=', $startYear->copy()->format('Y-m-d'))
                                       ->where('finish', '<=', $endYear->copy()->format('Y-m-d'))
                                       ->whereIn('type_book', [
                                           11,
                                           12
                                       ])->count();
                break;
            case 'checkout':
                $dateX      = Carbon::now();
                $booksCount = \App\Book::where('finish', '>=', $dateX->copy())->where('finish', '<', $year->end_date)
                                  ->where('type_book', 2)->orderBy('finish', 'ASC')->count();
                break;
            case 'eliminadas':
                $dateX      = Carbon::now();
                $booksCount = \App\Book::where('start', '>=', $startYear->copy()->format('Y-m-d'))
                                       ->where('finish', '<=', $endYear->copy()->format('Y-m-d'))
                                       ->whereIn('type_book', 0)->count();
                break;
            case 'INTERESADOS':
                $booksCount = \App\Book::where('ff_status', 5)
                  ->where('start', '>=', date('Y-m-d'))
                  ->with('room','payments','customer','leads')
                  ->whereIn('type_book', Book::get_type_book_sales(true,true))
                  ->count();
              
                break;
        }

        return $booksCount;
    }

    public function sendSencondEmail(Request $request)
    {
        $book = \App\Book::find($request->id);
        if (!empty($book->customer->email))
        {
            $book->send = 1;
            $book->save();
            
//            $sended = $this->sendEmail_secondPayBook($book, 'Recordatorio de pago Apto. de lujo Miramarski - ' . $book->customer->name);
            $subject = 'Recordatorio Pago '.env('APP_NAME').' '.$book->customer->name;
            $sended = $this->sendEmail_secondPayBook($book, $subject);
            if ($sended)
            {
                return [
                    'status'   => 'success',
                    'title'    => 'OK',
                    'response' => "Recordatorio enviado correctamente"
                ];
            } else
            {
                return [
                    'status'   => 'danger',
                    'title'    => 'Error',
                    'response' => "El email no se ha enviado, por favor intentalo de nuevo"
                ];
            }
        } else
        {
            return [
                'status'   => 'warning',
                'title'    => 'Cuidado',
                'response' => "Este cliente no tiene email"
            ];
        }


    }

    /**
     * Enable/Disable alerts has_low_profit
     *
     * @param Request $request
     * @return type
     *
     */
    public function toggleAlertLowProfits(Request $request)
    {
        $book = \App\Book::find($request->id);
        if ($book)
        {
            $book->has_low_profit = !$book->has_low_profit;
            $book->save();
            if ($book->has_low_profit)
            {
                return [
                    'status'   => 'success',
                    'title'    => 'OK',
                    'response' => "Alarma desactivada para  " . $book->customer->name
                ];
            } else
            {
                return [
                    'status'   => 'success',
                    'title'    => 'OK',
                    'response' => "Alarma activada para  " . $book->customer->name
                ];
            }
        } else
        {
            return [
                'status'   => 'warning',
                'title'    => 'Cuidado',
                'response' => "Registro no encontrado"
            ];
        }


    }

    /**
     * Enable alerts has_low_profit to all
     *
     * @param Request $request
     * @return type
     *
     */
    public function activateAlertLowProfits()
    {
        if (Auth::user()->role == 'admin')
        {
            $year      = $this->getActiveYear();
            $startYear = new Carbon($year->start_date);
            $endYear   = new Carbon($year->end_date);

            $book = \App\Book::where('start', '>', $startYear)->where('finish', '<', $endYear)
                             ->where('has_low_profit', TRUE)->update(['has_low_profit' => FALSE]);
            return [
                'status'   => 'success',
                'title'    => 'OK',
                'response' => "Alarma activada para  todos los registros"
            ];
        } else
        {
            return [
                'status'   => 'warning',
                'title'    => 'Cuidado',
                'response' => "No tiene permisos para la acción que desea realizar"
            ];
        }

    }

    public function cobrarFianzas($id)
    {
        $book      = \App\Book::find($id);
        $hasFiance = \App\Fianzas::where('book_id', $book->id)->first();
        $stripe    = StripeController::$stripe;
        return view('backend/planning/_fianza', compact('book', 'hasFiance', 'stripe'));
    }

    /**
     * getDataBook
     * @param Request $request
     * @return type
     */
    public function getAllDataToBook(Request $request){
        $data = ['costes'=>[],'totales'=>[],'calculated'=>[],'public'=>[]];
        $room = \App\Rooms::with('extra')->find($request->room);
        if (!$room){
          return null;
        }
               
        $loadedParking = $loadedCostRoom = false;
        $loadedCostLuz = ($request->room == $request->currentRoom);
        if ($request->book_id){
          $book = Book::find($request->book_id);
          if ($book){
            $paymentTPV = $book->getPayment(2);
            if ($paymentTPV>0){
              $data['costes']['tpv'] = paylandCost($paymentTPV);
            }
            
            $sammeDate = false;
            if ($request->start == $book->start && $request->finish  == $book->finish){
              if ($request->pax == $book->pax && $request->room == $book->room_id ){
//                $loadedCostRoom = true;
                $data['costes']['book'] = $book->cost_apto;
                if ($request->park == $book->type_park){
                  $loadedParking = true;
                  $data['costes']['parking'] = $book->cost_park;
                }
              }
            }
          }
        }
        $promotion = $request->promotion ? floatval($request->promotion) : 0;
        $start  = $request->start;
        $finish = $request->finish;
        $pax= $request->pax;
        $noches = calcNights($start, $finish);
        
        if (!$loadedParking)
          $data['costes']['parking']   = round($this->getCostPark($request->park, $noches) * $room->num_garage);
        if (!$loadedCostRoom)
          $data['costes']['book']      = $room->getCostRoom($start, $finish, $pax)-$promotion;
        
        //------------------------------------
        $oFixExtra = \App\Extras::loadFixed($room->sizeApto);
        if ($loadedCostLuz){
          $data['costes']['luz']  = (empty($request->luzCost)) ? $oFixExtra->luzCost : intval($request->luzCost);
        } else {
          $data['costes']['luz']  = $oFixExtra->luzCost;
        }
        //------------------------------------
        
        $data['costes']['lujo'] = $this->getCostLujo($request->lujo);
        $data['costes']['obsequio']  = $oFixExtra->giftCost;
                
        $data['costes']['agencia']   = (float) $request->agencyCost;
        $data['costes']['promotion'] = $promotion;
        
        $c_limp = $room->priceLimpieza($room->sizeApto);
        $data['totales']['limp'] = $c_limp['price_limp'];
        $data['costes']['limp']  = $c_limp['cost_limp'];

        $data['totales']['parking']  = $this->getPricePark($request->park, $noches) * $room->num_garage;
        $data['totales']['lujo']     = $this->getPriceLujo($request->lujo);
        $data['totales']['obsequio'] = $oFixExtra->giftPVP;
        $data['public'] = $room->getRoomPrice($start, $finish, $pax);
        /*****************************************************************/
        /* El parking y supl lujo ya están incluidos en el precio OTA */
        $parkingPVP = $this->getPVPParking() * $noches * $room->num_garage;
        switch ($request->park){
            //keep the pvp
            case 1: $parkingPVP = 0; break;
            // 50%
            case 4: $parkingPVP = $parkingPVP / 2; break;
        }
        
        $lujoPVP = 0;
        if ($room->luxury){
          $lujoPVP    = floatval($this->getPVPLujo());
          switch ($request->lujo){
              //keep the pvp
              case 1: $lujoPVP = 0; break;
              // 50%
              case 4: $lujoPVP = $lujoPVP / 2; break;
          }
        }
        $data['public']['pvp'] = $data['public']['pvp'] - $parkingPVP - $lujoPVP;
        /*****************************************************************/
        $totalPrice = $data['public']['pvp'];
        $totalCost = array_sum($data['costes']) - $promotion;
        $profit    = round($totalPrice - $totalCost);
        $data['calculated']['total_price']       = $totalPrice;
        $data['calculated']['total_cost']        = round($totalCost);
        $data['calculated']['profit']            = $profit;
        $data['calculated']['profit_percentage'] = ($totalPrice>0) ? round(($profit / $totalPrice) * 100) : 0;
        $data['calculated']['real_price']        = array_sum($data['totales']);
        $data['aux']['min_day']        = $room->getMin_estancia($start, $finish);
        $data['aux']['moreInfo']       = $room->meta_title;
        
        
        return $data;
    }

    public function saveComment(Request $request, $idBook, $type)
    {
        $book = \App\Book::find($idBook);
        switch ($type)
        {
            case '1': $book->comment = $request->value;
                break;
            case '2': $book->book_comments = $request->value;
                break;
            case '3': $book->book_owned_comments = $request->value;
                break;
        }
        $book->save();
        return ['status'   => 'success','title'    => 'OK','response' => "Comentarios guardados" ];
    }

    public static function getBookFFData(Request $request, $request_id)
    {

        $ff_request = [];
         $book     = \App\Book::find($request_id);
         $customer = \App\Customers::find($book->customer_id);

                $forfaitItem = \App\ForfaitsUser::where('book_id',$request_id)->first();
                $ff_data = [
                    'forfait_data' => [],
                    'materials_data' => [],
                    'classes_data' => [],
                    'forfait_total' => null,
                    'materials_total' => null,
                    'classes_total' => null,
                    'total' => null,
                    'status' => null,
                    'created' => null,
                    'more_info' => null,
                    'id' => null,
                    'ffexpr_status' =>null,
                    'bookingNumber'=>null

                ];
                if ($forfaitItem){
                  $ff_data = [
                    'forfait_data' => json_decode($forfaitItem->forfait_data),
                    'materials_data' => json_decode($forfaitItem->materials_data),
                    'classes_data' => json_decode($forfaitItem->classes_data),
                    'forfait_total' => $forfaitItem->forfait_total,
                    'materials_total' => $forfaitItem->materials_total,
                    'classes_total' => $forfaitItem->classes_total,
                    'total' => $forfaitItem->total,
                    'status' => $forfaitItem->status,
                    'created' => $forfaitItem->created_at,
                    'more_info' => $forfaitItem->more_info,
                    'id' => $forfaitItem->id,
                    'ffexpr_status' =>$forfaitItem->ffexpr_status,
                    'bookingNumber' =>$forfaitItem->ffexpr_bookingNumber
                  ];
                }
                
              return view('/backend/planning/listados/_ff_popup')->with('book', $book)
		                                                   ->with('customer', $customer)
		                                                   ->with('pickupPointAddress', env('FORFAIT_POINT_ADDRESS'))
		                                                   ->with('ff_data', $ff_data);
	}

    public static function updateBookFFStatus(Request $request, $request_id, $status)
    {
        $book            = \App\Book::find($request_id);
        $book->ff_status = $status;

        if ($book->save())
        {
            return redirect('/admin/reservas/ff_status_popup/' . $request_id);
        }
    }

    /**
     * Get Booking from CALCULAR RESERVAS
     * @param Request $request
     * @return type
     */
    
    public function getTotalBook(Request $request)
    {
        $start      = $request->input('start');
        $finish     = $request->input('finish');
        $pax        = $request->input('quantity');
        $size_apto  = $request->input('size_apto_id');
        $countDays  = calcNights($start,$finish);
        $luxury     = $request->input('luxury');

        if ($luxury == -1) $luxury = null;
        $oGetRoomsSuggest = new \App\Services\Bookings\GetRoomsSuggest();
        $oGetRoomsSuggest->size_apto = $size_apto;
        $oGetRoomsSuggest->luxury = $luxury;
        $rooms = $oGetRoomsSuggest->getItemsSuggest($pax,$start,$finish,$size_apto,$luxury);
        $otaPrices = $oGetRoomsSuggest->getOtasPrices($rooms,$start,$finish);
        $oSetting = new Settings();
        $url = $oSetting->getLongKeyValue('gha_sitio');
        foreach ($rooms as $k=>$v){
          unset($rooms[$k]['infoCancel']);
        }
//        dd($otaPrices);
        return view('backend.planning.calculateBook.response', [
                'pax'   => $pax,
                'nigths'=> $countDays,
                'rooms' => $rooms,
                'urlGH' => $url,
                'name'  => $request->input('name'),
                'start' => $start,
                'finish'=> $finish,
                'otaPrices'=> $otaPrices,
            ]);
        
    }
    
    public function getComment($bookID) {
      $book = Book::find($bookID);
      if ($book){
        
        $textComment = "";
        if (!empty($book->comment)) {
            $textComment .= "<b>COMENTARIOS DEL CLIENTE</b>:"."<br>"." ".$book->comment."<br>";
        }
        if (!empty($book->book_comments)) {
            $textComment .= "<b>COMENTARIOS DE LA RESERVA</b>:"."<br>"." ".$book->book_comments."<br>";
        }
        if (!empty($book->book_owned_comments)) {
            $textComment .= "<b>COMENTARIOS PROPIETARIO</b>:"."<br>"." ".$book->book_owned_comments."<br>";
        }
        echo $textComment;
      } else {
        echo '<p>Sin datos</p>';
      }
    }

    /**
     * Prepare the event to show in the calendar
     * @param type $book
     * @param type $uRole
     * @return type
     */
    private function calendarEvent($book,$uRole,$isMobile,$bookings_without_Cvc) {
      
      $class = $book->getStatus($book->type_book);
      if ($class == "Contestado(EMAIL)"){ $class = "contestado-email";}
      $classTd = ' class="td-calendar" ';
      $titulo = '';
      $agency = '';
      $href = '';
      $href = 'href="'.url ('/admin/reservas/update').'/'.$book->id.'" ';
      $vistaCompleta = in_array($uRole, ['admin','subadmin']);
//      if (!$isMobile){
      if (true){
        $agency = ($book->agency != 0) ? "Agencia: ".$book->getAgency($book->agency).'<br/>' : "";
        $titulo = $book->customer->name.'<br/>';

        if ($book->client_has_photos)  $titulo.= '<i class="c_h_photo fas fa-camera"></i>';

        $titulo.= 
              Carbon::createFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b').
              ' - '.Carbon::createFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b')
              .'  | <b>Pax</b> '.$book->real_pax.'<br/>';
      
      
        if ($vistaCompleta){
          $titulo .='<b>PVP</b>:'.$book->total_price.' | ';
          $titulo .= substr(strtoupper($book->user->name), 0, 8).'<br/>';
        }

        $titulo .= $agency;
        if ($vistaCompleta && $book->agency == 1){
          if (in_array($book->id, $bookings_without_Cvc)){
            $titulo.= '<b class="text-danger">FALTAN DATOS VISA</b><br />';
          } else {
            $titulo.= '<b>OK DATOS VISA</b><br />';
          }
        }
        if ($vistaCompleta && $book->type_book == 2){
            $amount = $book->payments->pluck('import')->sum();
            $falta = intval(intval($book->total_price) - $amount);
            if ($falta>5){
               $titulo.= '<b class="text-danger">PENDIENTE PAGO: '. $falta .'€</b>';
               $classTd = ' class="td-calendar bordander" ';
            } else {
              $titulo.= '<b>PENDIENTE PAGO: '. $falta .'€</b>';
            }
        }
      }
      
      
      if ($isMobile){
        $titulo .= '<div class="calLink" data-'.$href.'>IR</div>';
        $href = '#';
      }
      $return = json_encode([
          'start' => $book->start,
          'finish' => $book->finish,
          'type_book'=>$book->type_book,
          'titulo'  => $titulo,
          'classTd' => $classTd,
          'href' => $href,
          'class' => $class,
      ]);
      
      return json_decode($return);
    }
    
    /**
     * Return the visa date
     * @param Request $request
     * @return string
     */
    function updVisa(Request $request){
      $bookingID = $request->input('id', null);
      $clientID = $request->input('idCustomer', null);
      $visa_data = $request->input('visa_data', null);
//      $cc_number = $request->input('cc_number', null);
      
      $oUser = Auth::user();
      $response = [
                  'title' => 'Error',
                  'status' => 'warning',
                  'response' => 'Algo ha salido mal',
              ];
      if ( $oUser->role == "admin" || $oUser->role == "subadmin"){
//        $visa_data
          $oVisa = DB::table('book_visa')
                    ->where('book_id',$bookingID)
                    ->where('customer_id',$clientID)
                    ->first();
          if ($oVisa){
            DB::table('book_visa')
                          ->where('id', $oVisa->id)
                          ->update([
                              'cvc' => $cc_cvc,
                              'cc_number' => $cc_number,
                              'updated_at'=>date('Y-m-d H:m:s'),
                              'imported' => $oVisa->imported+1]);
               
              $response = [
                  'title' => 'OK',
                  'status' => 'success',
                  'response' => 'Dato Guardado',
              ];
            
            $lst = Book::whereNotNull('external_id')->join('book_visa','book_id','=','book.id')->whereNull('cvc')->pluck('book_id');
//            $sentUPD = \App\ProcessedData::findOrCreate('bookings_without_Cvc');
//            $sentUPD->content = json_encode($lst);
//            $sentUPD->save();
          }
      }
      
      return response()->json($response);
    }
    /**
     * Return the visa date
     * @param Request $request
     * @return string
     */
    function getVisa(Request $request){
        $booking = $request->input('booking', null);
        $force = $request->input('force', null);
        $imported = 0;
        $fieldsCard = ["name","number",'date',"cvc",'type'];
        if ($booking){
          $aux = explode('-', $booking);
          if (is_array($aux) && count($aux) == 2){
            $bookingID = desencriptID($aux[1]);
            $clientID = desencriptID($aux[0]);
            
            $oVisa = DB::table('book_visa')
                    ->where('book_id',$bookingID)
                    ->where('customer_id',$clientID)
                    ->first();
            
            if ($oVisa){
              if (!$force){
                $visaData = json_decode($oVisa->visa_data, true);
                if ($visaData){
                  foreach ($fieldsCard as $f){
                    if (isset($visaData[$f])){
                      if ($f == 'date') $visaData[$f] = str_replace ('/20', ' / ', $visaData[$f]);
                      echo '
                        <div>
                        <label>'.$f.'</label>
                        <input type="text" class="form-control" value="'.$visaData[$f].'" >
                        <button class="btn btn-success copy_data" type="button"><i class="fa fa-copy"></i></button>
                        </div>';
                    }
                  }
                  echo '<div class="btn btn-blue" type="button" id="_getPaymentVisaForce">Refrescar datos</div>';
                  return ;
                }
              } else {
                $imported = $oVisa->imported;
                if ($imported>11){
                   echo '<p class="alert alert-warning">Excedió el máximo de descargas para esta reserva.</p>';
                   return;
                }
              }
            }
            
            
            
            $booking = Book::find($bookingID);
            if ($booking && $booking->customer_id == $clientID){
              $visa_data = $this->getCreditCardData($booking);
//              $visa_data = json_encode(["name"=>'test',"number"=>112321,'date'=>'2020-12-02',"cvc"=>'','type'=>'']);
              if ($visa_data){
                if ($oVisa){
                  DB::table('book_visa')
                          ->where('id', $oVisa->id)
                          ->update([
                              'visa_data' => $visa_data,
                              'updated_at'=>date('Y-m-d H:m:s'),
                              'imported' => $imported+1]);

                } else {
                  DB::table('book_visa')->insert([
                    'book_id' =>$bookingID,
                    'user_id'=>Auth::user()->id,
                    'customer_id'=>$clientID,
                    'visa_data'=>($visa_data),
                    'imported' => 1,
                    'created_at'=>date('Y-m-d H:m:s'),
                    'updated_at'=>date('Y-m-d H:m:s'),
                   ]);
                }

                if ($visa_data){
                  $visaData = json_decode($visa_data, true);

                  foreach ($fieldsCard as $f){
                    if (isset($visaData[$f])){
                      if ($f == 'date') $visaData[$f] = str_replace ('/20', ' / ', $visaData[$f]);
                      echo '
                        <div>
                        <label>'.$f.'</label>
                        <input type="text" class="form-control" value="'.$visaData[$f].'" >
                        <button class="btn btn-success copy_data" type="button"><i class="fa fa-copy"></i></button>
                        </div>';
                    }
                  }
                  echo '<div class="btn btn-blue" type="button" id="_getPaymentVisaForce">Refrescar datos</div>';

                }else { echo '<p class="alert alert-warning">Error de servicio.</p>';}
              } else { echo '<p class="alert alert-warning">Error de servicio.</p>';}

              return ;
            }
          }
        }
        
        return 'Datos no encontrados';
    }
    private function getCreditCardData($booking) {
      $visa_data = null;
      
      if (!$booking->propertyId) $booking->propertyId = 1542253; 
      if ($booking->external_id && $booking->propertyId){
        
        // Zodomus
        if ($booking->agency == 1 || $booking->agency == 6){
          $oZodomus = new \App\Services\Zodomus\Zodomus();
          $creditCard = $oZodomus->reservations_cc($booking->propertyId,$booking->external_id);
          if ($creditCard && isset($creditCard->status) && $creditCard->status->returnCode == 200){
            $visa_data = json_encode($creditCard->customerCC);
          }
        }
        //Wubook
        if ($booking->agency == 4 || $booking->agency == 999999){
          $oWubook = new \App\Services\Wubook\WuBook();
          $oWubook->conect();
          $visa_data = json_encode($oWubook->getCC_Data($booking->external_id));
          $oWubook->disconect();
        }
      }
      return $visa_data;
    }
    function getIntercambio(){
      return view('backend.planning._bookIntercambio');
    }
    
    function getIntercambioSearch($block,$search){
      
      if (trim($search) == '') return '';

      $year     = $this->getActiveYear();
      $dateFrom = new Carbon($year->start_date);
      $dateTo   = new Carbon($year->end_date);

      $books = null;
      
      if (is_numeric($search)){
        $books = Book::find($search);
        if (!$books){
          return '';
        }
        return view('backend.planning.listados._bookIntercambio',['block'=>$block,'books'=>null,'book'=>$books]);
      } else {
        $customerIds = \App\Customers::where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%')->pluck('id')->toArray();
        if (count($customerIds) > 0)
        {
           
          $books = \App\Book::whereIn('customer_id', $customerIds)->where('start', '>=', $dateFrom)
                        ->where('start', '<=', $dateTo)->where('type_book', '!=', 9)->where('type_book', '!=', 0)
                        ->orderBy('start', 'ASC')->get();
        }
      }
      return view('backend.planning.listados._bookIntercambio',['block'=>$block,'books'   => $books,'book'=>null]);
    }
    
    function intercambioChange(Request $request){
      
      $book_1 = $request->input('book_1',null);
      $book_2 = $request->input('book_2',null);
    
      if (!$book_1 || !$book_2){
        return response()->json(['status'=>'error','msg'=>'Debe seleccionar ambas reservas']);
      }
      if ($book_1 == $book_2){
        return response()->json(['status'=>'error','msg'=>'Ambas reservas no pueden ser la misma']);
      }
      
      $b1 = Book::find($book_1);
      $b2 = Book::find($book_2);
      
      if (!$b1 || !$b2){
        return response()->json(['status'=>'error','msg'=>'Debe seleccionar ambas reservas']);
      }
      
      $r1 = $b1->room_id;
      $r2 = $b2->room_id;
      
      
      $b1->room_id = $r2;
      $b1->save();
      
      $b2->room_id = $r1;
      $b2->save();
      
      return response()->json(['status'=>'ok']);
      
    }
    
     /**
     * Change the data to notific
     * @param Request $request
     * @return string
     */
    function changeMailNotif(Request $request){
        $bookingID = $request->input('booking', null);
        if ($bookingID){
          $booking = Book::find($bookingID);
          if ($booking->customer_id>1){
            $booking->customer->email_notif = $request->input('email_notif', null);
            $booking->customer->send_notif = ($request->input('send_notif')=='true') ? 1:0;

            $booking->customer->save();
            return response()->json([
                'title' => 'OK',
                'status' => 'success',
                'response' => 'Datos de contacto cambiados',
            ]);
          }
        }
        return response()->json([
                  'title' => 'Error',
                  'status' => 'warning',
                  'response' => 'Algo ha salido mal',
              ]);
    }
    

  
  function getOtaURL($book){
        $otaURL = null;
        if ($book->agency == 1 && isset($_COOKIE["OTA_BOOKING"])) {
          $prop_channel = [
              'DDE' => 2092950,
              'DDL' => 2092950,
              'EstS' => 2092950,
              'EstL' => 2092950,
              'ESTG' => 4284223,
              '7J' => 2942008,
              '9R' => 6037501,
              '9F' => 2942955,
              '10I' => 5813366,
              'CHLT' => 2798863,
          ];
          if (isset($prop_channel[$book->room->channel_group])){
          $sess = $_COOKIE["OTA_BOOKING"];
          $otaURL = 'https://admin.booking.com/hotel/hoteladmin/extranet_ng/manage/booking.html?res_id='.$book->external_id
                  . '&hotel_id='.$prop_channel[$book->room->channel_group]	
                  . '&extranet_search=1&'.$sess;
          }
        }
        
        if ($book->agency == 28){
          $prop_channel = [
            'DDE' => 51813430,
            'DDL' => 51813430,
            'EstS' => 51813430,
            'EstL' => 51813430,
            'ESTG' => 55092492,
            '7J' => 51813430,
            '9F' => 51813430,
            '10I' => 51813430,
            'CHLT' => 55092491,
          ];
          
          if (isset($prop_channel[$book->room->channel_group])){
          $otaURL = 'https://apps.expediapartnercentral.com/lodging/reservations/reservationDetails.html?htid='.$prop_channel[$book->room->channel_group]
                  . '&reservationIds='.$book->external_id;
          }
        }
        
        return $otaURL;
        
    }
    
  /***********************************************************************/
  
    public function getBooksWithoutCvc() {

      $bookings_without_Cvc = \App\ProcessedData::findOrCreate('bookings_without_Cvc');
      $bookings_without_Cvc = json_decode($bookings_without_Cvc->content,true);
      $aVisasData = [];
      $bookLst = null;
      if ($bookings_without_Cvc){
        $bookLst = Book::whereIn('id',$bookings_without_Cvc)->with('customer')->get();
        

        $oVisas = \App\BookData::where('key','creditCard')
                    ->whereIn('book_id',$bookings_without_Cvc)
                    ->get();
//        dd($oVisas);
        if ($oVisas){
            foreach ($oVisas as $visa){
                $aVisasData[$visa->book_id] = $visa->content;
            }
        }
      }
      $isMobile = config('app.is_mobile');
      return view('backend/planning/_load-cvc',compact('bookLst','isMobile','aVisasData'));

  }   
    public function save_creditCard(Request $request){
      
      $oUser = Auth::user();
      if ( !( $oUser->role == "admin" || $oUser->role == "subadmin")){
            return [
                'status'   => 'danger',
                'title'    => 'ERROR',
                'response' => "No posees permisos"
            ];
      }
      $bookingID = $request->input('bID', null);
      $creditCardData = $request->input('data', null);
       

      $book = Book::find($bookingID);
      if (!$book){
        return [
                'status'   => 'danger',
                'title'    => 'ERROR',
                'response' => "RESERVA NO ENCONTRADA"
            ];
      }
       
      $oBookData = \App\BookData::findOrCreate('creditCard',$bookingID);
      $oBookData->content = $creditCardData;
      $oBookData->save();
      
              return [
                'status'   => 'success',
                'title'    => 'OK',
                'response' => "REGISTRO GUARDADO"
            ];
  }   

  function removeAlertPax(Request $request){
    $oUser = Auth::user();
    if ( !( $oUser->role == "admin" || $oUser->role == "subadmin")){
      return "No posees permisos";
    }
    $bookingID = $request->input('bID', null);
    $oData = \App\ProcessedData::where('key','checkPaxs')->first();
    if ($oData){
      $content = json_decode($oData->content,true);
      foreach ($content as $k=>$v){
        if ($v['bookID'] == $bookingID){
          unset($content[$k]);
        }
      }
      $oData->content = json_encode($content);
      $oData->save();
    }
      
    return 'OK';
  }
  
  function getOTAsLogErros_qty(){
    $resp = '';
    $dir = storage_path().'/logs/OTAs'.date('Ym').'.log';
    $count = 0;
    
    $lastView = strtotime('-1 weeks');
    $lastRead = \App\ProcessedData::findOrCreate('log_OTA_readed');
    if ($lastRead->content)  $lastView = $lastRead->content;
    
    /** TEST */
//    $dir = storage_path().'/logs/OTAs202110.log';
//    $lastView = strtotime('2021-10-26 13:52:48');
    /** TEST */
    
    if (file_exists($dir)) {
      $lines = file($dir);
      $lines = array_reverse($lines);
      foreach ($lines as $num => $lin) {
        $dTime = strtotime(substr($lin, 1,19));
        if ($lastView<$dTime){
            $count++;
        }
      }
    }
    return $count;
  }
  
  function getLogErros_notRead(){
    $resp = '<div class="e_logs">';//
    $dir = storage_path().'/logs/OTAs'.date('Ym').'.log';
    $count = 0;
    
    $lastView = strtotime('-1 weeks');
    $lastRead = \App\ProcessedData::findOrCreate('log_OTA_readed');
    if ($lastRead->content)  $lastView = $lastRead->content;
//    $lastView = strtotime('-2 days');
    
    if (file_exists($dir)) {
      $lines = file($dir);
      $lines = array_reverse($lines);
      foreach ($lines as $num => $lin) {
        $dTime = strtotime(substr($lin, 1, 19));
        if ($lastView < $dTime) {
          $data = explode('OtaGateway.ERROR: ', $lin);
          $dataJson = str_replace(' [] []', '', $data[1]);
          if (str_contains($lin, '} {')) {
            $aux = explode('} {', $dataJson);
            $dataJson = $aux[0] . '}';
          }

          $aData = json_decode($dataJson);

          if (isset($aData->message)) {
            $count++;
            $resp .= "<b>{$data[0]}</b>: " . ($aData->message) . "<br />";
          } else {
            if (isset($aData->errors)) {
              foreach ($aData->errors as $v) {
                if (isset($v->message)) {
                  $count++;
                  $resp .= "<b>{$data[0]}</b>: " . ($v->message) . "<br />";
                }
              }
            }
          }
        }
      }
    }
    
    if ($count == 0){
      $resp .= '<div class="alert alert-warning">No hay registros sin leer</div>';
    }
    
    echo $resp.'</div>';
        
    $lastRead->content = time();
    $lastRead->save();
    
    return;
  }
  
  function toggleCliHas(Request $request){
    $bID  = $request->input("bid");
    $type = $request->input("type");
    $oBook = Book::find($bID);
    if (!$oBook){
      return response()->json(['status'=>'error','result'=>'reserva no encontrada']);
    }
    
    switch ($type){
      case 'photos':
        $oData = \App\BookData::findOrCreate('client_has_photos',$bID);
        break;
      case 'beds':
        $oData = \App\BookData::findOrCreate('client_has_beds',$bID);
        break;
      case 'babyCarriage':
        $oData = \App\BookData::findOrCreate('client_has_babyCarriage',$bID);
        break;
    }
    
    
    $oData->content = ($oData->content) ? false : true;
    $oData->save();
    
    return response()->json(['status'=>'OK','result'=>$oData->content]);
  }
}
