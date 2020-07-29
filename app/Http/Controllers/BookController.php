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
use App\Traits\BookCentroMensajeria;
use App\Traits\BookLogsTraits;
use App\BookPartee;
use App\Settings;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");

class BookController extends AppController
{
    use BookEmailsStatus, BookCentroMensajeria,BookLogsTraits;

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
        
        if (Auth::user()->role != "agente")
        {
          $rooms       = Rooms::orderBy('order')->get();
          $types       = Book::get_type_book_pending();
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
        $totalReserv = $query2->where('type_book', 1)->count();
        $query2 = clone $booksQry;
        $amountReserv = $query2->where('type_book', 1)->sum('total_price');
        
        $booksCount['deletes']      = Book::where('start', '>', $startYear)->where('finish', '<', $endYear)
                                               ->where('type_book', 0)->where("enable", "=", "1")->count();
        
        $booksCount['checkin']      = $this->getCounters('checkin');
        $booksCount['checkout']     = $this->getCounters('checkout');

        $books = $booksQry->whereIn('type_book', $types)->orderBy('created_at', 'DESC')->get();
        $stripe = null;
       
        $lastBooksPayment = \App\Payments::where('created_at', '>=', Carbon::today()->toDateString())
		                                 ->count();
       
        $mobile      = new Mobile();
        
        $alert_lowProfits = 0; //To the alert efect
        $percentBenef     = DB::table('percent')->find(1)->percent;
        $lowProfits       = $this->lowProfitAlert($startYear, $endYear, $percentBenef, $alert_lowProfits);

        $ff_pendientes = Book::where('ff_status',4)->where('type_book','>',0)->count();
        
        $parteeToActive = $this->countPartte();
        
        
        $now         = Carbon::now();
        $booksAlarms = \App\Book::where('start', '>', $startYear)->where('finish', '<', $endYear)
                                ->where('start', '>=', $now->format('Y-m-d'))->where('start', '<=', $now->copy()
                                                                                                        ->addDays(15)
                                                                                                        ->format('Y-m-d'))
                                ->where('type_book', 2)->get();
        $alarms      = array();
        foreach ($booksAlarms as $key => $book)
        {
            $dateStart = Carbon::createFromFormat('Y-m-d', $book->start);
            $diff      = $now->diffInDays($dateStart);

            if (count($book->payments) > 0)
            {
                $total = 0;
                foreach ($book->payments as $pay)
                {
                    $total += $pay->import;
                }
                //echo $total." DE ----> ".$book->total_price."<br>";

                $percent = 0;
                if ($total>0){
                  $percentAux = ($book->total_price / $total);
                  if ($percentAux>0)
                  $percent = 100 / $percentAux;
                }
                if ($percent < 100 && $diff <= 15) $alarms[] = $book;
            } else
            {
                if ($diff <= 15) $alarms[] = $book;
            }
        }
        
        
        //BEGIN: Processed data
        $bookOverbooking = null;
        $overbooking = [];
        $oData = \App\ProcessedData::where('key','overbooking')->get();
        foreach ($oData as $d){
          switch ($d->key){
            case 'overbooking':
              $overbooking = json_decode($d->content,true);
              break;
          }
        }
        //END: Processed data
        
        $ff_mount = null;

       
         
        return view('backend/planning/index',
                compact('books', 'mobile', 'stripe', 'inicio', 'rooms', 'roomscalendar', 'date',
                        'stripedsPayments', 'notifications', 'booksCount', 'alarms','lowProfits',
                        'alert_lowProfits','percentBenef','parteeToActive','lastBooksPayment',
                        'ff_pendientes','ff_mount','totalReserv','amountReserv','overbooking')
		);
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

    public function newBook(Request $request)
    {
        if (Auth::user()->role != "agente")
        {
            $rooms = \App\Rooms::where('state', '=', 1)->orderBy('order')->get();
        } else
        {
            $roomsAgents = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
            $rooms       = \App\Rooms::where('state', '=', 1)->whereIn('id', $roomsAgents)->orderBy('order')->get();
        }
        $extras = \App\Extras::all();
        return view('backend/planning/_nueva', compact('rooms', 'extras'));
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
        // 4 es el extra correspondiente a el obsequio
        $extraPrice = \App\Extras::find(4)->price;
        $extraCost  = \App\Extras::find(4)->cost;
        
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
        $book->extraPrice = $extraPrice;
        $book->extraCost  = $extraCost;
        $nigths = calcNights($book->start, $book->finish );
        $book->nigths        = $nigths;
                
        $room = \App\Rooms::find($request->input('newroom'));
        
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
        
    
        //from widget
        if ($request->input('from')) {
          
          $customer->user_id = (Auth::check()) ? Auth::user()->id : 1;
          if ($customer->save()) {

            $book->user_id = (Auth::check()) ? Auth::user()->id : 1;
            $book->customer_id = $customer->id;
            $book->cost_apto = $room->getCostRoom($date_start, $date_finish, $book->pax);
            $book->cost_total = $book->get_costeTotal();

            if ($request->input('priceDiscount') == "yes" || $request->input('price-discount') == "yes") {
              $discount = Settings::getKeyValue('discount_books');
              $book->real_price -= $discount;
              $book->ff_status = 4;
              $book->has_ff_discount = 1;
              $book->ff_discount = $discount;
            }

            $book->total_price = $book->real_price;
            $book->total_ben = $book->total_price - $book->cost_total;
            $book->promociones = 0;

            if ($book->save()) {

              if ($request->input('fast_payment') == 1) {
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
                $urlPayland = $this->generateOrderPaymentBooking(
                        $book->id, $book->customer->id, $client_email, $description, $amount
                );

                return view('frontend.bookStatus.bookPaylandPay', ['urlPayland' => $urlPayland]);
                
              } else {
                /* Notificacion via email */
                if ($customer->email) {
                  MailController::sendEmailBookSuccess($book, 1);
                }
                return view('frontend.bookStatus.bookOk');
              }
            }
          }
        } else {
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
                        $book->sup_limp    = 0;
                        $book->cost_limp   = 0;
                        $book->sup_park    = 0;
                        $book->cost_park   = 0;
                        $book->sup_lujo    = 0;
                        $book->cost_lujo   = 0;
                        $book->cost_apto   = 0;
                        $book->cost_total  = 0;
                        $book->total_price = 0;
                        $book->real_price  = 0;
                        $book->total_ben   = 0;
                        $book->extraCost   = 0;
                    }elseif ($book->type_book == 7){
                        $book->bookingProp($room);
                    }else{
                      $book->total_price = $request->input('total');
                      $book->cost_apto = ($request->input('costApto')) ? $request->input('costApto') : $room->getCostRoom($date_start,$date_finish,$book->pax);
                      $book->cost_total = ($request->input('cost')) ? $request->input('cost') : $book->cost_apto + $subTotalCost;
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

                      $book->sendAvailibilityBy_dates($book->start,$book->finish);
                        /* Creamos las notificaciones de booking */
                        /* Comprobamos que la room de la reserva este cedida a booking.com */
                        if ($room->isAssingToBooking())
                        {
                            $notification          = new \App\BookNotification();
                            $notification->book_id = $book->id;
                            $notification->save();
                        }
                        // MailController::sendEmailBookSuccess( $book, 0);
                        return redirect('admin/reservas');

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


    }

    public function update(Request $request, $id){
      
      $updateBlade = '';
      $hasVisa = false;
      $visaHtml = null;
      $oUser = Auth::user();
      if ( $oUser->role != "agente"){
         
          $book  = \App\Book::with('payments')->find($id);
          $rooms = \App\Rooms::orderBy('order')->get();
          if ( $oUser->role == "admin" || $oUser->role == "subadmin"){
            $oVisa = DB::table('book_visa')
                    ->where('book_id',$book->id)
                    ->first();
            if ($oVisa){
              $hasVisa = true;
              $visaData = json_decode($oVisa->visa_data,true);
              $fieldsCard = ["name","number",'date',"cvc",'type'];
              foreach ($fieldsCard as $f){
                if (isset($visaData[$f])){
                  if ($f == 'date') $visaData[$f] = str_replace ('/20', ' / ', $visaData[$f]);
                  $visaHtml .= '
                    <div>
                    <label>'.$f.'</label>
                    <input type="text" class="form-control" value="'.$visaData[$f].'" >
                    <button class="btn btn-success copy_data" type="button"><i class="fa fa-copy"></i></button>
                    </div>';
                }
              }
              $visaHtml .=  '<div class="btn btn-blue" type="button" id="_getPaymentVisaForce">Refrescar datos</div>';
            }
            
          }
          
       } else {
         
          $updateBlade = '-agente';
          $roomsAgents = \App\AgentsRooms::where('user_id', $oUser->id)->get(['room_id'])->toArray();
          $rooms       = \App\Rooms::whereIn('id', $roomsAgents)->orderBy('order')->get();
          $types       = [1,2];
          
          $book = \App\Book::with('payments')
                  ->whereIn('type_book',$types)
                  ->whereIn('room_id', $roomsAgents)
                  ->find($id);
       }

       if (!$book){
         return redirect('/admin/reservas');
       }
        $totalpayment = $book->sum_payments;

        // We are passing wrong data from this to view by using $book data, in order to correct data
        // an AJAX call has been made after rendering the page.
        $hasFiance = \App\Fianzas::where('book_id', $book->id)->first();

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
        
        $priceBook = $book->room->getPVP($book->start, $book->finish, $book->park)-$book->promociones;

        $email_notif = '';
        $send_notif = '';
        if ($book->customer_id ){
          $email_notif = $book->customer->email_notif ? $book->customer->email_notif : $book->customer->email;
          $send_notif = $book->customer->send_notif ? 'checked' : '';
        }
        
        return view('backend/planning/update'.$updateBlade, [
            'book'         => $book,
            'low_profit'   => $low_profit,
            'hasVisa'      => $hasVisa,
            'visaHtml'     => $visaHtml,
            'rooms'        => $rooms,
            'extras'       => \App\Extras::all(),
            'start'        => Carbon::createFromFormat('Y-m-d', $book->start)->format('d M,y'),
            'payments'     => $book->payments,
            'typecobro'    => new \App\Book(),
            'totalpayment' => $totalpayment,
            'mobile'       => new Mobile(),
            'hasFiance'    => $hasFiance,
            'stripe'       => StripeController::$stripe,
            'priceBook'    => $priceBook,
            'email_notif'  => $email_notif,
            'send_notif'   => $send_notif,
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

            $book->user_id     = Auth::user()->id;
            $book->customer_id = $request->input('customer_id');
            $book->room_id     = $request->input('newroom');

            $book->start               = $start;
            $book->finish              = $finish;
            $book->comment             = ltrim($request->input('comments'));
            $book->book_comments       = ltrim($request->input('book_comments'));
            $book->pax                 = $request->input('pax');
            $book->real_pax            = $request->input('real_pax');
            $book->nigths              = calcNights($book->start, $book->finish );
            $book->book_owned_comments = ($request->input('book_owned_comments')) ? $request->input('book_owned_comments') : "";
            
            if ($book->type_book == 7){$book->bookingProp($room);} 
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

              $book->total_price = $request->input('total'); // This can be modified in frontend
              if ($request->input('costApto'))   $book->cost_apto = $request->input('costApto');
              //Parking NO o Gratis
              if ($book->type_park == 2 || $book->type_park == 3) $book->cost_park = 0;
              if ($request->input('costParking'))  $book->cost_park  = $request->input('costParking');
              if(!$IS_agente){  
                $book->extra     = $request->input('extra');

                $book->extraPrice  = Rooms::GIFT_PRICE;
                $book->extraCost   = Rooms::GIFT_COST;
                $book->schedule    = $request->input('schedule');
                $book->scheduleOut = $request->input('scheduleOut');
                $book->promociones = ($request->input('promociones')) ? $request->input('promociones') : 0;

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
            if ($book->type_book == 8){
                $book->sup_limp    = 0;
                $book->cost_limp   = 0;
                $book->sup_park    = 0;
                $book->cost_park   = 0;
                $book->sup_lujo    = 0;
                $book->cost_lujo   = 0;
                $book->cost_apto   = 0;
                $book->cost_total  = 0;
                $book->total_price = 0;
                $book->real_price  = 0;
                $book->total_ben   = 0;
                $book->extraCost   = 0;

                $book->inc_percent = 0;
                $book->ben_jorge   = 0;
                $book->ben_jaime   = 0;

            }
            
            if ($book->save())
            {
               
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

    public function getCostPark($typePark, $nights)
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
    public function getCostLujo($typeLuxury)
    {
      //default, no or free
      if ($typeLuxury == 0 || $typeLuxury == 2 || $typeLuxury == 3) return 0;
      
        $costLuxury        = 0;
        $luxuryCostSetting = Settings::where('key', Settings::LUXURY_COST_SETTING_CODE)->first();
        if (!$luxuryCostSetting) return 0;

        switch ($typeLuxury)
        {
            case 1: // Yes
                $costLuxury = $luxuryCostSetting->value;
                break;
            case 2: // No
            case 3: // Free
                $costLuxury = 0;
                break;
            case 4: // 50%
                $costLuxury = $luxuryCostSetting->value / 2;
                break;
        }

        return $costLuxury;
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
          Mail::send('backend.emails.contestadoAdvanced', ['body' => nl2br($request->input('textEmail')),], function ($message) use ($book) {
              $message->from('reservas@apartamentosierranevada.net');
              $message->to($book->customer->email);
              $message->subject('Disponibilidad para tu reserva');
          });
          \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'sendEmailDisp','Disponibilidad para tu reserva',$request->input('textEmail'));
          // $book->send = 1;
          $book->type_book = 5;
          if ($book->save())  return 1;
        }
        return 0;
    }

    public function ansbyemail($id)
    {
        $book = \App\Book::find($id);

        return view('backend/planning/_answerdByEmail', [
            'book'   => $book,
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

      if ($book->type_book == 7) {
        \App\Expenses::delExpenseLimpieza($book->id);
      }


      $book->type_book = 0;

      if ($book->save()) {
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
    
  public function searchByName(Request $request){
        if ($request->searchString == '')
            return response()->json('', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        $year     = $this->getActiveYear();
        $dateFrom = new Carbon($year->start_date);
        $dateTo   = new Carbon($year->end_date);

        $customerIds = \App\Customers::where('name', 'LIKE', '%' . $request->searchString . '%')->pluck('id')
                                     ->toArray();

        if (count($customerIds) <= 0)
        {
            return "<h2>No hay reservas para este término '" . $request->searchString . "'</h2>";
        }

        $books = \App\Book::with('payments')->whereIn('customer_id', $customerIds)->where('start', '>=', $dateFrom)
                          ->where('start', '<=', $dateTo)->where('type_book', '!=', 9)->where('type_book', '!=', 0)
                          ->orderBy('start', 'ASC')->get();

        $payments = [];
        foreach ($books as $book){
            $payments[$book->id] = $book->payments->pluck('import')->sum();
        }

        return view('backend/planning/listados/_resultSearch', [
            'books'   => $books,
            'payment' => $payments
        ]);

    }

    public function getTableData(Request $request)
    {
        $mobile    = new Mobile();
        $year      = self::getActiveYear();
        $startYear = new Carbon($year->start_date);
        $endYear   = new Carbon($year->end_date);
        $books     = [];
        $uRole     = getUsrRole();

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
            ->with('room','payments','customer');
        } else
        {
            $roomsAgents = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
            $rooms       = \App\Rooms::whereIn('id', $roomsAgents)->orderBy('order')->get();
            $types       = [1];
            $booksQuery = Book::where_book_times($startYear, $endYear);
        }

        switch ($request->type) {
          case 'pendientes':
            if ($uRole != "agente") {
              $types = Book::get_type_book_pending();
              $books = $booksQuery->whereIn('type_book', $types)
                              ->orderBy('created_at', 'DESC')->get();
            } else {
              $roomsAgents = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
              $books = $booksQuery->where('type_book', 1)
                              ->where('user_id', Auth::user()->id)
                              ->whereIn('room_id', $roomsAgents)
                              ->orWhere('agency', Auth::user()->agent->agency_id)
                              ->with('room', 'payments')
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

            if ($uRole != "agente") {
              $books = $booksQuery->with('LogImages')->whereIn('type_book', [2])->orderBy('created_at', 'DESC')->get();
            } else {
              $books = $booksQuery->where('type_book', 2)->whereIn('room_id', $roomsAgents)
                              ->where('user_id', Auth::user()->id)
                              ->with('LogImages')
                              ->orWhere('agency', Auth::user()->agent->agency_id)
                              ->orderBy('created_at', 'DESC')->get();
            }
            break;
          case 'ff_pdtes_2':

            $dateX = Carbon::now();
            $books = \App\Book::where('ff_status', 4)->where('type_book', '>', 0)
                            ->where('start', '>=', $dateX->copy()->subDays(3))
                            ->with('room', 'payments', 'customer')
                            ->orderBy('start', 'ASC')->get();
    //                $books = \App\Book::where('start', '>=', $dateX->copy()->subDays(3))->where('start', '<=', $year->end_date)
    //                                  ->where('type_book', 2)->orderBy('start', 'ASC')->get();

            break;
          case 'checkin':
          case 'ff_pdtes':
            $dateX = Carbon::now();
            $books = \App\Book::where('start', '>=', $dateX->copy()->subDays(3))
                            ->where('start', '<=', $year->end_date)
                            ->with('room', 'payments', 'customer')
                            ->whereIn('type_book', [1, 2])->orderBy('start', 'ASC')->get();
            break;
          case 'checkout':
            $dateX = Carbon::now();
            $books = \App\Book::where('finish', '>=', $dateX->copy()->subDays(3))
                            ->where('finish', '<', $year->end_date)
                            ->with('room', 'payments', 'customer')
                            ->where('type_book', 2)->orderBy('finish', 'ASC')->get();
            break;
          case 'eliminadas':
            $books = \App\Book::where_book_times($startYear, $endYear)
                            ->where('type_book', 0)->orderBy('updated_at', 'DESC')->get();
            break;
          case 'cancel-xml':
            $books = \App\Book::where_book_times($startYear, $endYear)
                            ->where('type_book', 98)->orderBy('updated_at', 'DESC')->get();
            break;
          case 'blocked-ical':
            $books = \App\Book::where('start', '>=', $startYear->copy()->subDays(3))
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

        if ($request->type == 'confirmadas' || $request->type == 'checkin' || $request->type == 'ff_pdtes')
        {
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
            return view('backend/planning/_table', compact('books', 'rooms', 'type', 'mobile', 'payment'));
        } else
        {

            return view('backend/planning/_table', compact('books', 'rooms', 'type', 'mobile'));
        }
    }

    public function getLastBooks(Request $request)
    {
        $mobile = new Mobile();

        $year      = self::getActiveYear();
        $startYear = new Carbon($year->start_date);
        $endYear   = new Carbon($year->end_date);

        $booksAux = array();
        foreach (\App\Payments::orderBy('id', 'DESC')->get() as $key => $payment)
        {
            if (!in_array($payment->book_id, $booksAux))  $booksAux[] = $payment->book_id;
            if (count($booksAux) == 10) break;
        }
        $books = array();
        for ($i = 0; $i < count($booksAux); $i++){
            $books[] = \App\Book::find($booksAux[$i]);
        }

        return view('backend.planning._lastBookPayment', compact('books', 'mobile'));

    }

    function getAlertsBooking(Request $request)
    {
        return view('backend.planning._tableAlertBooking', compact('days', 'dateX', 'arrayMonths', 'mobile'));
    }

    public function getCalendarMobileView($month=null){
        $mes           = [];
        $arrayReservas = [];
        $arrayMonths   = [];
        $arrayDays     = [];
        $year          = $this->getActiveYear();
        $startYear     = new Carbon($year->start_date);
        $endYear       = new Carbon($year->end_date);

        $type_book_not = [0,3,6,12,98,99];
        $uRole = Auth::user()->role;
        $mobile = new Mobile();
        $isMobile = $mobile->isMobile();
        if (!$month){
          $month = strtotime($year->year.'-'.date('m').'-01');
          if (strtotime($startYear)>$month){
            $month = strtotime(($year->year+1).'-'.date('m').'-01');
          }
        }
        $currentM = date('n',$month);
        $startAux = new Carbon(date('Y-m-d', strtotime('-1 months',$month)));
        $endAux = new Carbon(date('Y-m-d', strtotime('+1 months',$month)));
        $startAux->firstOfMonth();
        $endAux->lastOfMonth();
        $books = \App\Book::where_book_times($startAux,$endAux)
                ->whereNotIn('type_book', $type_book_not)->orderBy('start', 'ASC')->get();

        
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");
        $uRole = Auth::user()->role;
        foreach ($books as $book)
        {
            $dia        = Carbon::createFromFormat('Y-m-d', $book->start);
            $start      = Carbon::createFromFormat('Y-m-d', $book->start);
            $finish     = Carbon::createFromFormat('Y-m-d', $book->finish);
            $diferencia = $start->diffInDays($finish);
            $event = $this->calendarEvent($book,$uRole,$isMobile);
           
            for ($i = 0; $i <= $diferencia; $i++)
            {
              $aux = $dia->copy();
              $arrayReservas[$book->room_id][$aux->format('Y')][$aux->format('n')][$aux->format('j')][] = $event;
              $dia = $dia->addDay();
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
            $rooms         = \App\Rooms::where('state', '=', 1)->get();
            $roomscalendar = \App\Rooms::where('state', '=', 1)->orderBy('order', 'ASC')->get();
        } else
        {
            $roomsAgents   = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
            $rooms         = \App\Rooms::where('state', '=', 1)->whereIn('id', $roomsAgents)->orderBy('order')->get();
            $roomscalendar = \App\Rooms::where('state', '=', 1)->whereIn('id', $roomsAgents)->orderBy('order', 'ASC')
                                       ->get();
        }
        $days = $arrayDays;

      $buffer = ob_html_compress(view('backend.planning.calendar.content', compact('arrayBooks', 'arrayMonths', 'arrayTotales', 'rooms', 'roomscalendar', 'arrayReservas', 'mes', 'date', 'extras', 'days', 'startYear', 'endYear','currentM','startAux')));
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
                $booksCount = \App\Book::where('finish', '>=', $dateX->copy()->subDays(3))->where('finish', '<', $year->end_date)
                                  ->where('type_book', 2)->orderBy('finish', 'ASC')->count();
                break;
            case 'eliminadas':
                $dateX      = Carbon::now();
                $booksCount = \App\Book::where('start', '>=', $startYear->copy()->format('Y-m-d'))
                                       ->where('finish', '<=', $endYear->copy()->format('Y-m-d'))
                                       ->whereIn('type_book', 0)->count();
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

    public function getAllDataToBook(Request $request){
        $data = ['costes'=>[],'totales'=>[],'calculated'=>[]];
        $room = \App\Rooms::with('extra')->find($request->room);
        if (!$room){
          return null;
        }
        if ($request->book_id){
          $book = Book::find($request->book_id);
          if ($book){
            $paymentTPV = $book->getPayment(2);
            if ($paymentTPV>0){
              $data['costes']['tpv'] = paylandCost($paymentTPV);
            }
          }
        }
        $promotion = $request->promotion ? floatval($request->promotion) : 0;
        $start  = $request->start;
        $finish = $request->finish;
        $data['costes']['parking']   = $this->getCostPark($request->park, $request->noches) * $room->num_garage;
        $data['costes']['book']      = $room->getCostRoom($start, $finish, $request->park)-$promotion;
        $data['costes']['lujo']      = $this->getCostLujo($request->lujo);
        $data['costes']['obsequio']  = Rooms::GIFT_COST;
        $data['costes']['agencia']   = (float) $request->agencyCost;
        $data['costes']['promotion'] = $promotion;
        
        $c_limp = $room->priceLimpieza($room->sizeApto);
        $data['totales']['limp'] = $c_limp['price_limp'];
        $data['costes']['limp']  = $c_limp['cost_limp'];

        $data['totales']['parking']  = $this->getPricePark($request->park, $request->noches) * $room->num_garage;
        $data['totales']['lujo']     = $this->getPriceLujo($request->lujo);
        $data['totales']['book']     = $room->getPVP($start,$finish,$request->pax);
        $data['totales']['obsequio'] = Rooms::GIFT_PRICE;

        if ($request->input('has_ff_discount',null)){
          $data['totales']['book'] = $data['totales']['book'] - $request->input('ff_discount_val',0);
        }
        
        // If the request comes with a price to show use it
        if (!empty($request->total_price))
        {
            $totalPrice                    = $request->total_price;
            $data['aux']['price_modified'] = $totalPrice;
        } else {
          $totalPrice = array_sum($data['totales']);
          // Otherwise if the request has a book and book exists
          if (isset($book) && $book->total_price != $book->real_price){
            // If the price has been modified in DB already
            $totalPrice                    = $book->total_price;
            $data['aux']['price_modified'] = $totalPrice;
          }
        }
        $totalPrice = ($totalPrice == 0) ? $data['totales']['book'] : $totalPrice;

        $totalCost = array_sum($data['costes']) - $promotion;
        $profit    = round($totalPrice - $totalCost,2);

        $data['calculated']['total_price']       = $totalPrice;
        $data['calculated']['total_cost']        = $totalCost;
        $data['calculated']['profit']            = $profit;
        $data['calculated']['profit_percentage'] = ($totalPrice>0) ? round(($profit / $totalPrice) * 100) : 0;
        $data['calculated']['real_price']        = array_sum($data['totales']);

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

        $start     = $request->input('start');
        $finish    = $request->input('end');
        $countDays = calcNights($start,$finish);
        $msg = null;
        $limp = 0;
        
        $SizeRooms = \App\SizeRooms::findSizeApto($request->input('apto'), $request->input('luxury'), $request->input('quantity'));

        
        $sizeRoom = $SizeRooms['sizeRoom'];
        $typeApto = $SizeRooms['typeApto'];
       
        if (isset($sizeRoom)){
          $size = \App\SizeRooms::find($sizeRoom);
        } else {
          return view('backend.bookStatus.bookError');
        }
       

        $roomAssigned = $this->calculateRoomToFastPayment($size, Carbon::createFromFormat('Y-m-d', $start),Carbon::createFromFormat('Y-m-d', $finish), $request->input('luxury'));


        $roomID = isset($roomAssigned['id']) ? $roomAssigned['id'] : -1;
        $paxPerRoom = \App\Rooms::getPaxRooms($request->input('quantity'), $roomID);
        $pax        = $request->input('quantity');
        if ($paxPerRoom > $pax)
        {
            $pax = $paxPerRoom;
        }
        
        $room = \App\Rooms::find($roomID);
        
        $minEstancia = $room->getMin_estancia($start,date('Y-m-d', strtotime($start)+24*60*60));
        if ($minEstancia>$countDays){
          $msg ='El apartamento tiene una estancia mínima de '.$minEstancia.' noches';
        }
              
        $price = $room->getPVP($start,$finish,$pax);
         
        if ($price > 0){

          $costes = $room->priceLimpieza($sizeRoom);
          $limp = $costes['price_limp'];

          if ($request->input('parking') == 'si')
          {
              $priceParking = BookController::getPricePark(1, $countDays) * $room->num_garage;
              $parking      = 1;
          } else
          {
              $priceParking = 0;
              $parking      = 2;
          }

          if ($request->input('luxury') == 'si')
          {
              $luxury = BookController::getPriceLujo(1);
          } else
          {
              $luxury = BookController::getPriceLujo(2);
          }
          $total   = $price + $priceParking + $limp + $luxury;
          
          $dni     = $request->input('dni');
          $address = $request->input('address');
          $setting = Settings::where('key', 'discount_books')->first();
            return view('backend.bookStatus.response', [
                'id_apto'      => $roomID,
                'isFastPayment' => $roomAssigned['isFastPayment'],
                'pax'          => $pax,
                'nigths'       => $countDays,
                'apto'         => $typeApto,
                'name'         => $request->input('name'),
                'phone'        => $request->input('phone'),
                'email'        => $request->input('email'),
                'start'        => $start,
                'finish'       => $finish,
                'parking'      => $parking,
                'priceParking' => $priceParking,
                'luxury'       => $luxury,
                'total'        => $total,
                'dni'          => $dni,
                'address'      => $address,
                'room'         => $room,
                'setting'      => ($setting) ? $setting : 0,
                'comment'      => $request->input('comment'),
                'msg'          => $msg,
            ]);
        } else
        {
            return view('frontend.bookStatus.bookError');
        }
    }
    
    public function getComment($bookID) {
      $book = Book::find($bookID);
      if ($book){
        
        $textComment = "";
        if (!empty($book->comment)) {
            $textComment .= "<b>COMENTARIOS DEL CLIENTE</b>:"."<br>"." ".$book->comment."<br>";
        }
        if (!empty($book->book_comments)) {
            $textComment .= "<b>COMENTARIOS DE LA RESERVA</b>:"."<br>"." ".$book->book_comments;
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
    private function calendarEvent($book,$uRole,$isMobile) {
      
      $class = $book->getStatus($book->type_book);
      if ($class == "Contestado(EMAIL)"){ $class = "contestado-email";}
      $classTd = ' class="td-calendar" ';
      $titulo = '';
      $agency = '';
      if (!$isMobile){
      $agency = ($book->agency != 0) ? "Agencia: ".$book->getAgency($book->agency) : "";
      $titulo = $book->customer->name.'&#10'.
              'Pax-real '.$book->real_pax.'&#10;'.
              Carbon::createFromFormat('Y-m-d',$book->start)->formatLocalized('%d %b').
              ' - '.Carbon::createFromFormat('Y-m-d',$book->finish)->formatLocalized('%d %b')
              .'&#10;';
      }
      $href = '';
      if ($uRole != "agente" && $uRole != "limpieza"){
        $titulo .='PVP:'.$book->total_price.'&#10';
        $href = ' href="'.url ('/admin/reservas/update').'/'.$book->id.'" ';
      }

      $titulo .= $agency;
      
      if ($isMobile) $titulo = '';
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
                if ($imported>1){
                   echo '<p class="alert alert-warning">Excedió el máximo de descargas para esta reserva.</p>';
                   return;
                }
              }
            }
            
            
            
            $booking = Book::find($bookingID);
            if ($booking && $booking->customer_id == $clientID){
              $visa_data = $this->getCreditCardData($booking);
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
    
  function sendEncuesta($bookID) {
    $book = Book::find($bookID);
    if (!$book->customer->email || trim($book->customer->email) == ''){
      return 'El cliente no posee un emial cargado.';
    }
    
    if ($this->sendEmail_Encuesta($book,"DANOS 5' Y TE INVITAMOS A DESAYUNAR"))
      return 'ok';
    
    return 'Encuesta no enviada';
  }
}
