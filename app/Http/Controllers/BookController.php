<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use \Carbon\Carbon;
use Auth;
use Mail;
use Illuminate\Routing\Controller;
use App\Classes\Mobile;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");

class BookController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($year = "")
    {
        $date = empty($year) ? Carbon::now() : Carbon::createFromFormat('Y', $year);

        $start = new Carbon('first day of September');
        $start = $date->format('n') >= 9 ? $start : $start->subYear();
        $inicio = $start->copy();

        $booksCollection = \App\Book::with('customer')
            ->where('start', '>', $start->copy()->subMonth())
            ->where('finish', '<', $start->copy()->addYear())
            ->get();

        $books = $booksCollection->whereIn('type_book', [1,3,4,5,6,10])
            ->sortByDesc('created_at');

        $booksCount['pending'] = $booksCollection->where('type_book', 3)->count();
        $booksCount['special'] = $booksCollection->whereIn('type_book', [7,8])->count();
        $booksCount['confirmed'] = $booksCollection->where('type_book', 2)->count();
        $booksCount['checkin'] = $this->getCounters($start,'checkin');
        $booksCount['checkout'] = $booksCount['confirmed'] - $booksCount['checkin'];

        $rooms = \App\Rooms::where('state','=',1)->get();
        $roomscalendar = $rooms->filter(function($room) {
            return $room->id >= 5;
        })->sortBy('order');

        $stripe = StripeController::$stripe;
        $stripedsPayments = \App\Payments::where('comment', 'LIKE', '%stripe%')
            ->where('created_at', '>=', Carbon::today()->toDateString())
            ->get();
        
        // Notificaciones de alertas booking
        $notifications = \App\BookNotification::whereHas('book', function($q) { 
            return $q->where('type_book', '<>', 3)
                ->orWhere('type_book', '<>', 5)
                ->orWhere('type_book', '<>', 6);
        })->count();

        $mobile = new Mobile();

        return view(
            'backend/planning/index',
            compact('books','mobile', 'stripe','inicio', 'rooms', 'roomscalendar', 'date', 
                'stripedsPayments', 'notifications', 'booksCount')
        );
    }

    public function newBook(Request $request)
    {
        $rooms = \App\Rooms::where('state','=',1)->get();
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

        $aux = str_replace('Abr', 'Apr', $request->input('fechas'));

        $date = explode('-', $aux);

        $start = Carbon::createFromFormat('d M, y' , trim($date[0]))->format('d/m/Y');
        $finish = Carbon::createFromFormat('d M, y' , trim($date[1]))->format('d/m/Y');
        $book = new \App\Book();
        $extraPrice = 0 ;
        $extraCost  = 0 ;

        if ( $request->input('from') ) {
            if ($request->input('extras') != "") {
                    foreach ($request->input('extras') as $extra) {
                     $precios = \App\Extras::find($extra);
                     $extraPrice += $precios->price;
                     $extraCost += $precios->cost;
                }
            }

            //createacion del cliente
            $customer          = new \App\Customers();
            $customer->user_id = (Auth::check()) ?Auth::user()->id : 23;
            $customer->name    = $request->input('name');
            $customer->email   = $request->input('email');
            $customer->phone   = $request->input('phone');
            $customer->DNI     = ($request->input('dni'))?$request->input('dni'):"";
            $customer->address = ($request->input('address'))?$request->input('address'):"";
            $customer->country = ($request->input('country'))?$request->input('country'):"";
            $customer->city    = ($request->input('city'))?$request->input('city'):"";

            if($customer->save()){
                //Creacion de la reserva
                $book->user_id       = 39;
                $book->customer_id   = $customer->id;
                $book->room_id       = $request->input('newroom');
                $book->start         = Carbon::createFromFormat('d/m/Y',$start);
                $book->finish        = Carbon::createFromFormat('d/m/Y',$finish);
                $book->comment       = ($request->input('comments'))?ltrim($request->input('comments')): "" ;
                $book->book_comments = ($request->input('book_comments'))?ltrim($request->input('book_comments')): "" ;
                $book->type_book     = ( $request->input('status') ) ? $request->input('status') : 3;
                $book->pax           = $request->input('pax');
                $book->real_pax      = $request->input('pax');
                $book->nigths        = $request->input('nigths');
                $book->agency        = $request->input('agency');
                $book->PVPAgencia    = ( $request->input('agencia') )?$request->input('agencia'):0;

                $room                = \App\Rooms::find($request->input('newroom'));
                if ($room->sizeApto == 1) {

                    $book->sup_limp      = 30;
                    $book->cost_limp     = 30;

                    $book->sup_park      = $this->getPricePark($request->input('parking'), $request->input('nigths'));
                    $book->cost_park     = $this->getCostPark($request->input('parking'), $request->input('nigths'));
                } elseif($room->sizeApto == 2) {

                    $book->sup_limp      = 50;
                    $book->cost_limp     = 40;

                    $book->sup_park      = $this->getPricePark($request->input('parking'), $request->input('nigths'));
                    $book->cost_park     = $this->getCostPark($request->input('parking'),$request->input('nigths') );
                }elseif($room->sizeApto == 3 || $room->sizeApto == 4){

                    $book->sup_limp      = 100;
                    $book->cost_limp     = 90;

                    $book->sup_park      = $this->getPricePark($request->input('parking'), $request->input('nigths') , 3);
                    $book->cost_park      = $this->getCostPark($request->input('parking'), $request->input('nigths') , 3);
                }
                
                $book->type_park     = $request->input('parking');

                $book->type_luxury   = $request->input('type_luxury');
                $book->sup_lujo      = $this->getPriceLujo($request->input('type_luxury'));
                $book->cost_lujo     = $this->getCostLujo($request->input('type_luxury'));
                // if ($room->typeApto == 3 || $room->typeApto == 1) {
                //     $book->cost_apto     = 0;        
                // }else{
                    $book->cost_apto     = $this->getCostBook( $start, $finish, $request->input('pax'), $request->input('newroom')) + $book->cost_limp;
                // }
                
                $book->cost_total    = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $extraCost;

                $book->total_price   = $this->getPriceBook($start,$finish,$request->input('pax'),$request->input('newroom')) + $book->sup_park + $book->sup_lujo+ $book->sup_limp + $extraPrice;

                $book->real_price    = $book->getPriceBook($start,$finish,$request->input('pax'),$request->input('newroom')) + $book->sup_park + $book->sup_lujo+ $book->sup_limp + $extraPrice;


                $book->total_ben     = $book->total_price - $book->cost_total;


                $book->extraPrice    = $extraPrice;
                $book->extraCost     = $extraCost;
                    //Porcentaje de beneficio
                $book->inc_percent   = number_format(( ($book->total_price * 100) / $book->cost_total)-100,2 , ',', '.') ;
                $book->ben_jorge = $book->total_ben * $book->room->typeAptos->PercentJorge / 100;
                $book->ben_jaime = $book->total_ben * $book->room->typeAptos->PercentJaime / 100;
                $book->promociones     = 0;
                if($book->save()){
                    /* Notificacion via email */
                    if ($customer->email) {
                        MailController::sendEmailBookSuccess( $book, 1);
                        return view('frontend.bookStatus.bookOk'); 
                    }

                }
            }
        } else {
            $isReservable  = 0;
            if (in_array($request->input('status'), [1, 2, 4, 7, 8])) {
                if ($book->existDate($start, $finish, $request->input('newroom'))) {
                    $isReservable = 1;
                }
            } else {
                $isReservable = 1;
            }

            if ( $isReservable == 1 ) {
                if ($request->input('extras') != "") {
                    foreach ($request->input('extras') as $extra) {
                       $precios    = \App\Extras::find($extra);
                       $extraPrice += $precios->price;
                       $extraCost  += $precios->cost;
                    }
                }

                //createacion del cliente
                $customer          = new \App\Customers();
                $customer->user_id = (Auth::check())?Auth::user()->id:23;
                $customer->name    = $request->input('name');
                $customer->email   = $request->input('email');
                $customer->phone   = ($request->input('phone'))?$request->input('phone'):"";
                $customer->DNI     = ($request->input('dni'))?$request->input('dni'):"";
                $customer->address = ($request->input('address'))?$request->input('address'):"";
                $customer->country = ($request->input('country'))?$request->input('country'):"";
                $customer->city    = ($request->input('city'))?$request->input('city'):"";

                if($customer->save()){
                    //Creacion de la reserva
                    $room                = \App\Rooms::find($request->input('newroom'));

                    $book->user_id       = $customer->user_id;
                    $book->customer_id   = $customer->id;
                    $book->room_id       = $request->input('newroom');
                    $book->start         = Carbon::createFromFormat('d/m/Y',$start);
                    $book->finish        = Carbon::createFromFormat('d/m/Y',$finish);
                    $book->comment       = ($request->input('comments'))?$request->input('comments'): "";
                    $book->book_comments = ($request->input('book_comments'))?$request->input('book_comments'): "";
                    $book->book_owned_comments = ($request->input('book_owned_comments'))?$request->input('book_owned_comments'): "";
                    $book->type_book     = ( $request->input('status') ) ? $request->input('status') : 3;
                    $book->pax           = $request->input('pax');
                    $book->real_pax      = ($request->input('real_pax'))?$request->input('real_pax'):$request->input('pax');
                    $book->nigths        = $request->input('nigths');
                    $book->agency        = ($request->input('agency'))?$request->input('agency'):0;
                    $book->type_park     = $request->input('parking');
                    $book->type_luxury   = $request->input('type_luxury');

                    if ($request->input('status') == 8) {
                        $book->PVPAgencia    = ( $request->input('agencia') )?$request->input('agencia'):0;
                        $book->sup_limp      = 0 ;
                        $book->cost_limp     = 0 ;
                        $book->sup_park      = 0 ;
                        $book->cost_park     = 0 ;
                        $book->sup_lujo      = 0 ;
                        $book->cost_lujo     = 0 ;
                        $book->cost_apto     = 0 ;
                        $book->cost_total    = 0 ;
                        $book->total_price   = 0 ;
                        $book->real_price    = 0 ;
                        $book->total_ben     = 0 ;

                        $book->inc_percent   = 0 ;
                        $book->ben_jorge     = 0 ;
                        $book->ben_jaime     = 0 ;   
                    }elseif($request->input('status') == 7){
                        $book->PVPAgencia    = ( $request->input('agencia') )?$request->input('agencia'):0;
                        $book->sup_limp      = ($room->sizeApto == 1) ? 30 : 50;
                        $book->cost_limp     = ($room->sizeApto == 1) ? 30 : 40;
                        $book->sup_park      = 0 ;
                        $book->cost_park     = 0 ;
                        $book->sup_lujo      = 0 ;
                        $book->cost_lujo     = 0 ;
                        $book->cost_apto     = 0 ;
                        $book->cost_total    = ($room->sizeApto == 1) ? 30 : 40 ;
                        $book->total_price   = ($room->sizeApto == 1) ? 30 : 50 ;
                        $book->real_price    = ($room->sizeApto == 1) ? 30 : 50 ;
                        $book->total_ben     = $book->total_price - $book->cost_total ;

                        $book->inc_percent   = number_format(( ($book->total_price * 100) / $book->cost_total)-100,2 , ',', '.') ;
                        $book->ben_jorge = $book->total_ben * $book->room->typeAptos->PercentJorge / 100;
                        $book->ben_jaime = $book->total_ben * $book->room->typeAptos->PercentJaime / 100;
                    }else{

                        $book->PVPAgencia  = ( $request->input('agencia') )?$request->input('agencia'):0;
                        $book->sup_limp    = ($room->sizeApto == 1) ? 30 : 50;
                        if ($room->sizeApto == 1) {

                            $book->sup_limp      = 30;
                            $book->cost_limp     = 30;

                            $book->sup_park    = $this->getPricePark($request->input('parking'), $request->input('nigths'));
                            $book->cost_park   = $this->getCostPark($request->input('parking'),$request->input('nigths'));
                        } elseif($room->sizeApto == 2) {

                            $book->sup_limp      = 50;
                            $book->cost_limp     = 40;

                            $book->sup_park    = $this->getPricePark($request->input('parking'), $request->input('nigths'));
                            $book->cost_park   = $this->getCostPark($request->input('parking'),$request->input('nigths'));
                        }elseif($room->sizeApto == 3 || $room->sizeApto == 4){

                            $book->sup_limp      = 100;
                            $book->cost_limp     = 90;
                            $book->sup_park    = $this->getPricePark($request->input('parking'), $request->input('nigths') , 3);
                            $book->cost_park   = $this->getCostPark($request->input('parking'),$request->input('nigths'), 3);

                        }
                        
                        $book->sup_lujo    = $this->getPriceLujo($request->input('type_luxury'));
                        $book->cost_lujo   = $this->getCostLujo($request->input('type_luxury'));

                        // if ($room->typeApto == 3 || $room->typeApto == 1) {
                        //     $book->cost_apto     = 0;        
                        // }else{
                            $book->cost_apto     = ($request->input('costApto'))? $request->input('costApto'): $this->getCostBook($start,$finish,$request->input('pax'),$request->input('newroom')) + $book->cost_limp;
                        // }
                        $book->cost_total  = ($request->input('cost'))?$request->input('cost'):$book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $extraCost;

                        $book->total_price = $request->input('total');

                        $book->real_price  = $this->getPriceBook($start,$finish,$request->input('pax'),$request->input('newroom')) + $book->sup_park + $book->sup_lujo+ $book->sup_limp + $extraPrice;

                        $book->total_ben     = $book->total_price - $book->cost_total;

                        $book->inc_percent   = number_format(( ($book->total_price * 100) / $book->cost_total)-100,2 , ',', '.') ;
                        $book->ben_jorge     = $book->total_ben * $book->room->typeAptos->PercentJorge / 100;
                        $book->ben_jaime     = $book->total_ben * $book->room->typeAptos->PercentJaime / 100;
                    }


                    $book->extraPrice    = $extraPrice;
                    $book->extraCost     = $extraCost;
                    $book->schedule      = ($request->input('schedule'))?$request->input('schedule'):0;
                    $book->scheduleOut   = ($request->input('scheduleOut'))?$request->input('scheduleOut'):12;
                    $book->promociones     = ($request->input('promociones'))?$request->input('promociones'):0;

                    if($book->save()){

                        /* Creamos las notificaciones de booking */
                        /* Comprobamos que la room de la reserva este cedida a booking.com */
                        if ( $room->isAssingToBooking() ) {
                            $notification = new \App\BookNotification();
                            $notification->book_id = $book->id;
                            $notification->save();
                        }


                        // MailController::sendEmailBookSuccess( $book, 0);
                        return redirect('admin/reservas');

                    }
                }

            }else{

                return view('backend/planning/_formBook',  [
                    'request'   => (object) $request->input(),
                    'book'      => new \App\Book(),
                    'rooms'     => \App\Rooms::where('state','=',1)->get(),
                    'mobile'    => $mobile
                ]);
            }
        }



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request->input('    ')* @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = \App\Book::find($id);
        $payments = \App\Payments::where('book_id',$book->id)->get();
        $totalpayment = 0;
        foreach ($payments as $payment) {
            $totalpayment = $totalpayment + $payment->import;
        }


        $mobile = new Mobile();
        return view('backend/planning/update',  [
            'book'         => $book ,
            'rooms'        => \App\Rooms::where('state',1)->get(),
            'extras'       => \App\Extras::all(),
            'start'        => Carbon::createFromFormat('Y-m-d',$book->start)->format('d M,y'),
            'payments'     => $payments,
            'typecobro'    => new \App\Book(),
            'totalpayment' => $totalpayment,
            'mobile'       => $mobile,
            'stripe'        => StripeController::$stripe,
        ]);
    }


    //Funcion para actualizar la reserva
    public function saveUpdate(Request $request, $id)
    {
        

        $aux = str_replace('Abr', 'Apr', $request->input('fechas'));

        $date = explode('-', $aux);

        $start = Carbon::createFromFormat('d M, y' , trim($date[0]))->format('d/m/Y');
        $finish = Carbon::createFromFormat('d M, y' , trim($date[1]))->format('d/m/Y');
        $book = new \App\Book();
        $extraPrice = 0 ;
        $extraCost  = 0;


        $customer          = \App\Customers::find($request->input('customer_id'));
        $customer->DNI     = ($request->input('dni'))?$request->input('dni'):"";
        $customer->address = ($request->input('address'))?$request->input('address'):"";
        $customer->country = ($request->input('country'))?$request->input('country'):"";
        $customer->city    = ($request->input('city'))?$request->input('city'):"";

        $customer->save();


        if ( \App\Book::existDateOverrride($start,$finish,$request->input('newroom'), $id) ) {
            $book = \App\Book::find($id);
            $room = \App\Rooms::find($request->input('newroom'));

            $book->user_id             = Auth::user()->id;
            $book->customer_id         = $request->input('customer_id');
            $book->room_id             = $request->input('newroom');
            $book->start               = Carbon::createFromFormat('d/m/Y',$start);
            $book->finish              = Carbon::createFromFormat('d/m/Y',$finish);
            $book->comment             = ltrim($request->input('comments'));
            $book->book_comments       = ltrim($request->input('book_comments'));
            $book->book_owned_comments = ($request->input('book_owned_comments'))?$request->input('book_owned_comments'): "";
            $book->pax                 = $request->input('pax');
            $book->real_pax            = $request->input('real_pax');
            $book->nigths              = $request->input('nigths');
            if ($room->sizeApto == 1) {

                $book->sup_limp      = 30;
                $book->cost_limp     = 30;

                $book->sup_park    = $this->getPricePark($request->input('parking'), $request->input('nigths'));
                $book->cost_park   = $request->input('costParking');//$this->getCostParkController($request->input('parking'),$request->input('nigths'));
            } elseif($room->sizeApto == 2) {

                $book->sup_limp      = 50;
                $book->cost_limp     = 40;

                $book->sup_park    = $this->getPricePark($request->input('parking'), $request->input('nigths'));
                $book->cost_park   = $request->input('costParking');//$this->getCostParkController($request->input('parking'),$request->input('nigths'));
            }elseif($room->sizeApto == 3 || $room->sizeApto == 4){

                $book->sup_limp      = 100;
                $book->cost_limp     = 90;
                $book->sup_park    = $this->getPricePark($request->input('parking'), $request->input('nigths') , 3);
                $book->cost_park   = $request->input('costParking');//$this->getCostParkController($request->input('parking'),$request->input('nigths'), 3);

            }
            $book->type_park     = $request->input('parking');
            $book->agency        = $request->input('agency');
            $book->PVPAgencia    = $request->input('agencia');

            // $book->cost_park     = $request->input('costParking');

            $book->type_luxury     = $request->input('type_luxury');                
            $book->sup_lujo      = $this->getPriceLujo($request->input('type_luxury'));
            $book->cost_lujo     = $this->getCostLujo($request->input('type_luxury'));

            $book->cost_apto     = $request->input('costApto');
            $book->cost_total    = $request->input('cost');

            $book->total_price   = $request->input('total');
            $book->real_price    = $this->getPriceBook($start,$finish,$request->input('pax'),$request->input('newroom')) + $book->sup_park + $book->sup_lujo+ $book->sup_limp + $extraPrice;

            $book->total_ben     = $request->input('beneficio');
            $book->extra         = $request->input('extra');
            $book->inc_percent   = number_format(( ($book->total_price * 100) / $book->cost_total)-100,2 , ',', '.') ;
            $book->ben_jorge = $book->total_ben * $book->room->typeAptos->PercentJorge / 100;
            $book->ben_jaime = $book->total_ben * $book->room->typeAptos->PercentJaime / 100;


            $book->schedule       = $request->input('schedule');
            $book->scheduleOut    = $request->input('scheduleOut');
            $book->promociones     = ($request->input('promociones'))?$request->input('promociones'):0;
            if ($book->save()) {

                if ( $book->room->isAssingToBooking() ) {

                    $isAssigned = \App\BookNotification::where('book_id',$book->id)->get();

                    if (count($isAssigned) == 0) {
                        $notification = new \App\BookNotification();
                        $notification->book_id = $book->id;
                        $notification->save();
                    }
                }else{
                    $deleted = \App\BookNotification::where('book_id',$book->id)->delete();
                }

                return ['status' => 'success','title' => 'OK', 'response' => "ACTUALIZACION CORRECTA"];
            }
        }else{

            return ['status' => 'danger','title' => 'ERROR', 'response' => "NO HAY DISPONIBILIDAD EN EL PISO PARA LAS FECHAS SELECCIONADAS"];
        }
    }

    

    public function changeStatusBook(Request $request, $id)
    {
        if ( isset($request->status) && !empty($request->status)) {

            $book = \App\Book::find($id);
            $start = Carbon::createFromFormat('Y-m-d' , $book->start);
            $finish = Carbon::createFromFormat('Y-m-d' , $book->finish);

            $isReservable  = 0;

            if (in_array($request->status , [1, 2, 4, 5, 7, 8])) {

                if ($book->existDateOverrride($start->format('d/m/Y'), $finish->format('d/m/Y'), $book->room_id, $id)) {
                    $isReservable = 1;
                }

            }else{
                $isReservable = 1;
            }


            if ( $isReservable == 1 ) {

                return $book->changeBook($request->status,"",$book);
            }else{

                return ['status' => 'danger','title' => 'Peligro', 'response' => "No puedes cambiar el estado"];
            }

        }
    }


    public function changeBook(Request $request, $id)
    {

        if ( isset($request->room) && !empty($request->room)) {

            $book = \App\Book::find($id);
            return $book->changeBook("", $request->room, $book);
        }

        if ( isset($request->status) && !empty($request->status)) {

            $book = \App\Book::find($id);
            return $book->changeBook($request->status,"",$book);

        }else{
            return ['status' => 'danger','title' => 'Error', 'response' => 'No hay datos para cambiar, por favor intentalo de nuevo'];
        }
    }



    public function getPricePark($park, $noches, $room="", $multipler=""){

        $priceParking = 0;
        switch ($park) {
            case 1:
            $priceParking = 18 * $noches;
            break;
            case 2:
            $priceParking = 0;
            break;
            case 3:
            $priceParking = 0;
            break;
            case 4:
            $priceParking = (18 * $noches) / 2;
            break;
        }
        if ( $room != "") {
            if ($room == 149 || $room == 150) {
                $priceParking =  $priceParking * 3;
            }
        }
        return $priceParking;

    }

    public function getCostPark($park, $noches, $room="", $multipler=""){

        $costParking = 0;
        switch ($park) {
            case 1:
            $costParking = 13.5 * $noches;
            break;
            case 2:
            $costParking = 0;
            break;
            case 3:
            $costParking = 0;
            break;
            case 4:
            $costParking = (13.5 * $noches) / 2;
            break;
        }

        if ( $room != "") {
            if ($room == 149 || $room == 150) {
                $costParking =  $costParking * 3;
            }
        }

        
        return $costParking;

    }

    public function getPriceLujo($typeLuxury){
        $priceLuxury = 0;
        if ($typeLuxury > 4) {
            $priceLuxury = $typeLuxury;
        }else{

            switch ($typeLuxury) {
                case 1:
                $priceLuxury = 50;
                break;
                case 2:
                $priceLuxury = 0;
                break;
                case 3:
                $priceLuxury = 0;
                break;
                case 4:
                $priceLuxury = 50/2;
                break;
            }
        }
        return $priceLuxury;
    }

    public function getCostLujo($typeLuxury){
        $costLuxury = 0;
        if ($typeLuxury > 4) {
            $costLuxury = $typeLuxury - 10;
        } else {
            switch ($typeLuxury) {
                case 1:
                $costLuxury = 40;
                break;
                case 2:
                $costLuxury = 0;
                break;
                case 3:
                $costLuxury = 0;
                break;
                case 4:
                $costLuxury = 40/2;
                break;
            }
        }

        return $costLuxury;
    }

    public function getCostBook($start, $finish, $pax, $room){
        $start = Carbon::createFromFormat('d/m/Y' , $start);
        $finish = Carbon::createFromFormat('d/m/Y' , $finish);
        $countDays = $finish->diffInDays($start);


        $paxPerRoom = \App\Rooms::getPaxRooms($pax,$room);

        $room = \App\Rooms::find($room);

        $pax = $pax;
        if ($paxPerRoom > $pax) {
            $pax = $paxPerRoom;
        }
        $costBook = 0;
        for ($i=1; $i <= $countDays; $i++) {

            $seasonActive = \App\Seasons::getSeason($start->copy()->format('Y-m-d'));
            $costs = \App\Prices::where('season' ,  $seasonActive)
            ->where('occupation', $pax)->get();

            foreach ($costs as $precio) {
                $costBook = $costBook + $precio->cost ;
            }
        }

        return $costBook;
    }

    public function getPriceBook($start, $finish, $pax, $room){
        $start = Carbon::createFromFormat('d/m/Y' , $start);
        $finish = Carbon::createFromFormat('d/m/Y' , $finish);
        $countDays = $finish->diffInDays($start);


        $paxPerRoom = \App\Rooms::getPaxRooms($pax,$room);

        $room = \App\Rooms::find($room);

        $pax = $pax;
        if ($paxPerRoom > $pax) {
            $pax = $paxPerRoom;
        }
        $priceBook = 0;
        for ($i=1; $i <= $countDays; $i++) {

            $seasonActive = \App\Seasons::getSeason($start->copy()->format('Y-m-d'));
            $costs = \App\Prices::where('season' ,  $seasonActive)
            ->where('occupation', $pax)->get();

            foreach ($costs as $precio) {
                $priceBook = $priceBook + $precio->price ;
            }
        }

        return $priceBook;
    }


    // Funcion para la migracion de la base antigua  a la nuevas "cobros"
    public function getCobrosBD()
    {
        $cobros = DB::connection('apartamento')->table('cobros')->orderBy('ID' , 'ASC')->get();

        echo "<pre>";
        foreach ($cobros as $cobro) {
            $payment = \App\Payments::find($cobro->ID);
            if (count($payment) > 0) {
                echo "Ya existe este cobro  ".$cobro->ID." <br>";
            } else {
                $payment              = new \App\Payments();
                $payment->id          =$cobro->ID;
                $payment->book_id     =$cobro->bookID;
                $payment->datePayment =$cobro->dateCobro;
                $payment->import      =$cobro->import;
                $payment->type        =$cobro->type;
                $payment->comment     =$cobro->Comment;

                if ($payment->save()) {
                 echo "Insertado ID ".$cobro->ID."<br>";
             } else {
                                # code...
             }
         }
     }
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
        $book = \App\Book::find($id);
        $payments = \App\Payments::where('book_id', $id)->get();
        $pending = 0;

        foreach ($payments as $payment) {
            $pending += $payment->import;
        }

        return view('backend/planning/_updateCobro',[
            'book'    => $book,
            'pending' => $pending,
            'payments' => $payments,
        ]);
    }

    // Funcion para Cobrar
    public function saveCobro(Request $request)
    {
        $payment = new \App\Payments();

        $payment->book_id = $request->id;
        $payment->datePayment = Carbon::CreateFromFormat('d-m-Y',$request->fecha);
        $payment->import = $request->import;
        $payment->type = $request->tipo;

        if ($payment->save()) {
            return redirect()->action('BookController@index');
        }

    }

    // Funcion para elminar cobro
    public function deleteCobro($id)
    {
        $payment = \App\Payments::find($id);

        if ($payment->delete()) {
            return redirect()->back();
        }

    }

    //Funcion para gguardar Fianza
    public function saveFianza(Request $request)
    {
        $fianza = new \App\Bail();

        $fianza->id_book = $request->id;
        $fianza->date_in = Carbon::CreateFromFormat('d-m-Y',$request->fecha);
        $fianza->import_in = $request->fianza;
        $fianza->comment_in = $request->comentario;
        $fianza->type = $request->tipo;

        if ($fianza->save()) {
            return redirect()->action('BookController@index');
        }

    }


    public function emails($id)
    {
        $book = \App\Book::find($id);

        return view('backend.emails.comprobar-fechas',['book' => $book]);

        Mail::send('backend.emails.jaime',['book' => $book], function ($message) use ($book) {
            $message->from('reservas@apartamentosierranevada.net');

            $message->to($book->customer->email);
            $message->subject('Correo a Jaime');
        });

        $book->send = 1;
        $book->save();

    }

    public function sendEmail(Request $request)
    {

        $book = \App\Book::find($request->input('id'));
        Mail::send('backend.emails.contestadoAdvanced',['body' => $request->input('textEmail'),], function ($message) use ($book) {
            $message->from('reservas@apartamentosierranevada.net');

            $message->to($book->customer->email);
            $message->subject('Disponibilidad para tu reserva');
        });

        // $book->send = 1;
        $book->type_book = 5;
        if ($book->save()) {
            return 1;
        }else{
            return 0;
        }

    }

    public function ansbyemail($id)
    {
        $book = \App\Book::find($id);

        return view('backend/planning/_answerdByEmail',  [
            'book' => $book,
            'mobile' => new Mobile()
        ]);
    }

    public function delete($id)
    {
        
       try {
           $book = \App\Book::find($id);

            foreach ($book->notifications as $key => $notification) {
               $notification->delete();
            }
            foreach ($book->pago as $key => $payment) {
               $payment->delete();
            }

            $ordersBook = \App\Orders::where('book_id', $book->id)->get();
            if (count($ordersBook) > 0) {
                foreach ( $ordersBook as $key => $order) {
                    
                    $productos = $order->getProducts();
                    foreach ($productos as $key => $producto) {
                        $producto->delete();
                    }

                    $order->delete();

                }
            }

            $book->type_book = 0;

            if ($book->save()) {
                return ['status' => 'success','title' => 'OK', 'response' => "Reserva enviada a eliminadas"];
       
            }

            
       } catch (Exception $e) {

           return ['status' => 'danger','title' => 'Error', 'response' => "No se ha podido borrar la reserva error: ".$e->message()];

       }

    }

    /* FUNCION DUPLICADA DE HOMECONTROLLER PARA CALCULAR LA RESERVA DESDE EL ADMIN */


    static function getTotalBook(Request $request)
    {

        $aux = str_replace('Abr', 'Apr', $request->input('fechas'));

        $date = explode('-', $aux);
       
        $start = Carbon::createFromFormat('d M, y' , trim($date[0]));
        $finish = Carbon::createFromFormat('d M, y' , trim($date[1]));
        $countDays = $finish->diffInDays($start);

        if ($request->input('quantity') <= 8) {
            if ($request->input('apto') == '2dorm' && $request->input('luxury') == 'si') {
               $roomAssigned = 115;
               $typeApto  = "2 DORM Lujo";
               $limp = 50;
           }elseif($request->input('apto') == '2dorm' && $request->input('luxury') == 'no'){
               $roomAssigned = 122;
               $typeApto  = "2 DORM estandar";
               $limp = 50;
           }elseif($request->input('apto') == 'estudio' && $request->input('luxury') == 'si'){
               $roomAssigned = 138;
               $limp = 30;
               $typeApto  = "Estudio Lujo";

           }elseif($request->input('apto') == 'estudio' && $request->input('luxury') == 'no'){
               $roomAssigned = 110;
               $typeApto  = "Estudio estandar";
               $limp = 30;
           }
        } else {
            /* Rooms para grandes capacidades */

            $roomAssigned = 149;
            $typeApto  = "3 DORM Lujo";
            $limp = 100;


        }


        $paxPerRoom = \App\Rooms::getPaxRooms($request->input('quantity'), $roomAssigned);

        $pax = $request->input('quantity');

        if ($paxPerRoom > $request->input('quantity')) {
            $pax = $paxPerRoom;
        }

        $price = 0;

        for ($i=1; $i <= $countDays; $i++) { 

            $seasonActive = \App\Seasons::getSeason($start->copy()->format('Y-m-d'));
            if ($seasonActive == null) {
               $seasonActive = 0;
            }
            $prices = \App\Prices::where('season' ,  $seasonActive)
                                ->where('occupation', $pax)->get();

            foreach ($prices as $precio) {
                $price = $price + $precio->price;
            }
        }
 
        if ($request->input('parking') == 'si') {
            $priceParking = 18 * $countDays;
            if ($typeApto == "3 DORM Lujo") {
                $priceParking = $priceParking * 3;
            }
            $parking = 1;
        }else{
            $priceParking = 0;
            $parking = 4;
        }

        if ($request->input('luxury') == 'si') {
            $luxury = 50;
        }else{
            $luxury = 0;
        }
        
        $total =  $price + $priceParking + $limp + $luxury;  

        if ($seasonActive != 0) {
            return view('backend.bookStatus.response', [
                                                            'id_apto' => $roomAssigned,
                                                            'pax'     => $pax,
                                                            'nigths'  => $countDays,
                                                            'apto'    => $typeApto,
                                                            'name'    => $request->input('name'),
                                                            'phone'   => $request->input('phone'),
                                                            'email'   => $request->input('email'),
                                                            'start'   => $start,
                                                            'finish'  => $finish,
                                                            'parking' => $parking,
                                                            'priceParking' => $priceParking,
                                                            'luxury'  => $luxury,
                                                            'total'   => $total,
                                                        ]);

        } else {
            return view('backend.bookStatus.bookError');
        }
    }

    public function searchByName(Request $request)
    {
    
        $now = Carbon::now();

        if ( $request->year ) {
            if ($now->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$now->copy()->format('Y'));
            }else{
                $date = new Carbon('first day of September '.$now->copy()->subYear()->format('Y'));
            }
            
        }else{
            $date = new Carbon('first day of September '.$request->year);
        }


        if ($request->searchString != "") {

            $customers = \App\Customers::where('name', 'LIKE', '%'.$request->searchString.'%')->get();
            
            if (count($customers) > 0) {
                $arrayCustomersId = [];
                foreach ($customers as $key => $customer) {
                    if (!in_array($customer->id, $arrayCustomersId)) {
                        $arrayCustomersId[] = $customer->id;
                    }
                    
                }

                $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                                    ->where('start' , '>=' , $date->format('Y-m-d'))
                                    ->where('start', '<=', $date->copy()->AddYear()->SubMonth()->format('Y-m-d'))
                                    ->where('type_book', '!=', 9)
                                    ->orderBy('start', 'ASC')
                                    ->get();

                $payment = array();
                foreach ($books as $key => $book) {
                    $payment[$book->id] = 0;
                    $payments = \App\Payments::where('book_id', $book->id)->get();
                    if ( count($payments) > 0) {
                        
                        foreach ($payments as $key => $pay) {
                            $payment[$book->id] += $pay->import;
                        }

                    }

                }


                return view('backend/planning/responses/_resultSearch',  [ 'books' => $books, 'payment' => $payment ]);
            }else{
                return "<h2>No hay reservas para este término '".$request->searchString."'</h2>";
            }
        }else{

            return 0;
        }
    }

    public function changeCostes()
    {
        $books = \App\Book::all();


        foreach ($books as $book) {


               if ($book->room->typeAptos->id == 1  || $book->room->typeAptos->id == 3) {            

                   $book->cost_total = $book->cost_limp + $book->cost_park + $book->cost_lujo + $book->extraCost;
                   $book->total_ben = $book->total_price - $book->cost_total;
                   $book->cost_apto = 0;


                  
               }
            $book->ben_jorge = $book->total_ben * $book->room->typeAptos->PercentJorge / 100;
            $book->ben_jaime = $book->total_ben * $book->room->typeAptos->PercentJaime / 100;

            $book->save();
        }
            
        
    } 


    public function getTableData(Request $request)
    {
        $now = Carbon::now();
        $mobile = new Mobile();

        if ( $request->year ) {
            if ($now->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$now->copy()->format('Y'));
            }else{
                $date = new Carbon('first day of September '.$now->copy()->subYear()->format('Y'));
            }
            
        }else{
            $date = new Carbon('first day of September '.$request->year);
        }

        switch ($request->type) {
            case 'pendientes':
                $books = \App\Book::where('start','>',$date->copy()->subMonth())->where('finish','<',$date->copy()->addYear())->whereIn('type_book',[1,3,4,5,6,10])->orderBy('created_at','DESC')->get();
                break;
            case 'especiales':
                $books = \App\Book::where('start','>',$date->copy()->subMonth())->where('finish','<',$date->copy()->addYear())->whereIn('type_book',[7,8])->orderBy('created_at','DESC')->get();
                break;
            case 'confirmadas':
                $books = \App\Book::where('start','>',$date->copy()->subMonth())->where('finish','<',$date->copy()->addYear())->whereIn('type_book',[2])->orderBy('created_at','DESC')->get();
                break;
            case 'checkin':
                $dateX = Carbon::now();
                $books = \App\Book::where('start','>',$dateX->copy()->subDays(3))
                                    ->where('finish','<',$dateX->copy()->addYear())
                                    ->where('type_book',2)
                                    ->orderBy('start','ASC')
                                    ->get();
                break;
            case 'checkout':
                $dateX = Carbon::now();
                $books = \App\Book::where('start','>',$dateX->copy()->subDays(3))
                                    ->where('finish','<',$dateX->copy()->addYear())
                                    ->where('type_book',2)
                                    ->orderBy('start','ASC')
                                    ->get();
            case 'eliminadas':
                $books = \App\Book::where('start','>',$date->copy()->subDays(3))
                                    ->where('finish','<',$date->copy()->addYear())
                                    ->where('type_book',0)
                                    ->orderBy('updated_at','DESC')
                                    ->get();
                break;
        }

        $rooms = \App\Rooms::where('state','=',1)->get();
        $type = $request->type;

        if ($request->type == 'confirmadas' ||  $request->type == 'checkin') {
            $payment = array();
            foreach ($books as $key => $book) {
                $payment[$book->id] = 0;
                $payments = \App\Payments::where('book_id', $book->id)->get();
                if ( count($payments) > 0) {
                    
                    foreach ($payments as $key => $pay) {
                        $payment[$book->id] += $pay->import;
                    }

                }

            }

            return view('backend/planning/_table', compact('books', 'rooms', 'type', 'mobile', 'payment'));
        } else {

            return view('backend/planning/_table', compact('books', 'rooms', 'type', 'mobile'));
        }
    }


    public function getLastBooks(Request $request)
    {
        $mobile = new Mobile();

        if ( $request->year ) {
            if ($now->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$now->copy()->format('Y'));
            }else{
                $date = new Carbon('first day of September '.$now->copy()->subYear()->format('Y'));
            }
        }else{
            $date = new Carbon('first day of September '.$request->year);
        }
        $booksAux = array();
        foreach (\App\Payments::orderBy('id', 'DESC')->get() as $key => $payment) {
            if (!in_array($payment->book_id, $booksAux)) {
                $booksAux[] = $payment->book_id;
            }
            if (count($booksAux) == 10) {
                break;
            }
        }
        $books = array();
        for ($i=0; $i < count($booksAux); $i++) { 
            $books[] = \App\Book::find($booksAux[$i]);
        }

        return view('backend.planning._lastBookPayment', compact('books', 'mobile'));

    }

    public function getCalendarBooking(Request $request)
    {
        $mobile = new Mobile();

        $arrayMonths   = array();
        $arrayDays     = array();
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }
        $firstDayOfTheYear = new Carbon('first day of September '.$date->copy()->format('Y'));
        $book = new \App\Book();

        for ($i=1; $i <= 12; $i++) {
            $mes[$firstDayOfTheYear->copy()->format('n')] = $firstDayOfTheYear->copy()->format('M Y');

            $startMonth = $firstDayOfTheYear->copy()->startOfMonth();
            $endMonth   = $firstDayOfTheYear->copy()->endOfMonth();
            $countDays  = $endMonth->diffInDays($startMonth);
            $day        = $startMonth;


            $arrayMonths[$firstDayOfTheYear->copy()->format('n')] = $day->copy()->format('t');
            
            

            for ($j=1; $j <= $day->copy()->format('t') ; $j++) {

                $arrayDays[$firstDayOfTheYear->copy()->format('n')][$j] = $book->getDayWeek($day->copy()->format('w'));
                $day = $day->copy()->addDay();

            }

            $firstDayOfTheYear->addMonth();

        }



        $dateX = $date->copy();
        $days = $arrayDays;

        return view('backend.planning._calendarToBooking', compact('days', 'dateX', 'arrayMonths', 'mobile'));
    }


    function getAlertsBooking(Request $request)
    {
        return view('backend.planning._tableAlertBooking', compact('days', 'dateX', 'arrayMonths', 'mobile'));
    }

    public function getCalendarMobileView($year = "")
    {

        $mes           = array();
        $arrayReservas = array();
        $totalPayments = array();
        $arrayMonths   = array();
        $arrayDays     = array();
        $arrayTotales  = array();
        $arrayPruebas  = array();
        $mobile = new Mobile();
        $now = Carbon::now();
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();
        }
        if ($date->copy()->format('n') >= 9) {
            $date = new Carbon('first day of September '.$now->copy()->format('Y'));
        }else{
            $date = new Carbon('first day of September '.$now->copy()->subYear()->format('Y'));
        }
        $apartamentos = \App\Rooms::where('state','=',1);
        $reservas = \App\Book::whereIn('type_book',[1,2,4,5,7,8,9,10])->whereYear('start','>=', $date)->orderBy('start', 'ASC')->get();

        foreach ($reservas as $reserva) {
            $dia = Carbon::createFromFormat('Y-m-d',$reserva->start);
            $start = Carbon::createFromFormat('Y-m-d',$reserva->start);
            $finish = Carbon::createFromFormat('Y-m-d',$reserva->finish);
            $diferencia = $start->diffInDays($finish);
            for ($i=0; $i <= $diferencia; $i++) {
                $arrayReservas[$reserva->room_id][$dia->copy()->format('Y')][$dia->copy()->format('n')][$dia->copy()->format('j')][] = $reserva;
                $dia = $dia->addDay();
            }
        }



        $firstDayOfTheYear = new Carbon('first day of September '.$date->copy()->format('Y'));
        $book = new \App\Book();

        for ($i=1; $i <= 12; $i++) {
            $mes[$firstDayOfTheYear->copy()->format('n')] = $firstDayOfTheYear->copy()->format('M Y');

            $startMonth = $firstDayOfTheYear->copy()->startOfMonth();
            $endMonth   = $firstDayOfTheYear->copy()->endOfMonth();
            $countDays  = $endMonth->diffInDays($startMonth);
            $day        = $startMonth;


            $arrayMonths[$firstDayOfTheYear->copy()->format('n')] = $day->copy()->format('t');
            
            

            for ($j=1; $j <= $day->copy()->format('t') ; $j++) {

                $arrayDays[$firstDayOfTheYear->copy()->format('n')][$j] = $book->getDayWeek($day->copy()->format('w'));
                $day = $day->copy()->addDay();

            }

            $firstDayOfTheYear->addMonth();

        }

        unset($arrayMonths[6]);
        unset($arrayMonths[7]);
        unset($arrayMonths[8]);
        
        if ($date->copy()->format('n') >= 9) {
            $start = new Carbon('first day of September '.$date->copy()->format('Y'));
        }else{
            $start = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
        }

        $rooms = \App\Rooms::where('state','=',1)->get();
        $roomscalendar = \App\Rooms::where('id', '>=' , 5)->where('state','=',1)->orderBy('order','ASC')->get();
        $book = new \App\Book();
        $inicio = $start->copy();
        $days = $arrayDays;
        
        return view('backend/planning/calendar', compact('arrayBooks', 'arrayMonths', 'arrayTotales', 'rooms', 'roomscalendar', 'arrayReservas', 'mes', 'date', 'book', 'extras', 'days', 'inicio'));
    }

    static function getCounters($year, $type)
    {
        $now = Carbon::now();

        if ( $year ) {
            if ($now->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$now->copy()->format('Y'));
            } else {
                $date = new Carbon('first day of September '.$now->copy()->subYear()->format('Y'));
            }
        } else {
            $date = new Carbon('first day of September '.$year);
        }

        switch ($type) {
            case 'pendientes':
                $booksCount = \App\Book::where('start','>',$date->copy()->subMonth())
                    ->where('finish','<',$date->copy()->addYear())
                    ->whereIn('type_book',[3])
                    ->count();
                break;
            case 'especiales':
                $booksCount = \App\Book::where('start','>',$date->copy()->subMonth())
                    ->where('finish','<',$date->copy()->addYear())
                    ->whereIn('type_book',[7,8])
                    ->count();
                break;
            case 'confirmadas':
                $booksCount = \App\Book::where('start','>',$date->copy()->subMonth())
                    ->where('finish','<',$date->copy()->addYear())
                    ->whereIn('type_book',[2])
                    ->count();
                break;
            case 'checkin':
                $dateX = Carbon::now();
                $booksCount = \App\Book::where('start','>',$dateX->copy()->subDays(3))
                    ->where('finish','<',$dateX->copy()->addYear())
                    ->where('type_book',2)
                    ->count();
                break;
            case 'checkout':
                $dateX = Carbon::now();
                $booksCount = \App\Book::where('start','>=',$dateX->copy()->subDays(3))
                    ->where('type_book',2)
                    ->count();
                break;
        }

        return $booksCount;
    }


    public function sendSencondEmail(Request $request)
    {
        $book = \App\Book::find($request->id);
        if (!empty($book->customer->email)) {
            $book->send = 1;
            $book->save();
            $sended = Mail::send(['html' => 'backend.emails._secondPayBook'],[ 'book' => $book], function ($message) use ($book) {
                    $message->from('reservas@apartamentosierranevada.net');
                    // $message->to('iankurosaki17@gmail.com');
                    $message->to($book->customer->email);
                    $message->subject('Recordatorio de pago Apto. de lujo Miramarski - '.$book->customer->name);
                    $message->replyTo('reservas@apartamentosierranevada.net');
                });

            if ($sended) {
                return ['status' => 'success','title' => 'OK', 'response' => "Recordatorio enviado correctamente"];
            } else {
                return ['status' => 'danger','title' => 'Error', 'response' => "El email no se ha enviado, por favor intentalo de nuevo"];
            }
        } else {
           return ['status' => 'warning','title' => 'Cuidado', 'response' => "Este cliente no tiene email"];
        }
        
       
        
    }


    public function cobrarFianzas($id)
    {
        $book = \App\Book::find($id);
        $hasFiance = \App\Fianzas::where('book_id', $book->id)->first();
        $stripe = StripeController::$stripe;
        return view('backend/planning/_fianza', compact('book', 'hasFiance', 'stripe'));
    }


    public function checkSecondPay()
    {
        /* Esta funcion tiene una cron asosciada que se ejecuta los dia 1 y 15 de cada mes, es decir cad 2 semanas */
        $date = Carbon::now();
        $books = \App\Book::where('start','>',$date->copy())
                            ->where('finish','<',$date->copy()->addDays(15))
                            ->whereIn('type_book',[2])
                            ->orderBy('created_at','DESC')
                            ->get();

        foreach ($books as $key => $book) {
            if ($book->send == 0) {
                if (!empty($book->customer->email)) {
                    $book->send = 1;
                    $book->save();

                    $sended = Mail::send(['html' => 'backend.emails._secondPayBook'],[ 'book' => $book], function ($message) use ($book) {
                            $message->from('reservas@apartamentosierranevada.net');
                            // $message->to('iankurosaki17@gmail.com');
                            $message->to($book->customer->email);
                            $message->subject('Recordatorio de pago Apto. de lujo Miramarski - '.$book->customer->name);
                            $message->replyTo('reservas@apartamentosierranevada.net');
                        });

                    if ($sended = 1) {
                        echo json_encode( ['status' => 'success','title' => 'OK', 'response' => "Recordatorio enviado correctamente"]);
                        echo "<br><br>";
                    } else {
                        echo json_encode( ['status' => 'danger','title' => 'Error', 'response' => "El email no se ha enviado, por favor intentalo de nuevo"]);
                        echo "<br><br>";
                    }
                }
            }
            
        }

    }
    

    public function getAllDataToBook(Request $request)
    {
        $room  = \App\Rooms::find($request->input('room'));
        if ($room->sizeApto == 1) {

            $data['costes']['parking']  = $this->getCostPark($request->input('park'),$request->input('noches') );
            $data['totales']['parking'] = $this->getPricePark($request->input('park'), $request->input('noches'));

            $sup_limp      = 30;
            $cost_limp     = 30;

        } elseif($room->sizeApto == 2) {
            /* PARKING */
            $data['costes']['parking']  = $this->getCostPark($request->input('park'),$request->input('noches') );
            $data['totales']['parking'] = $this->getPricePark($request->input('park'), $request->input('noches'));

            $sup_limp      = 50;
            $cost_limp     = 40;

        }elseif($room->sizeApto == 3 || $room->sizeApto == 4){
            /* PARKING */
            $data['costes']['parking']  = $this->getCostPark($request->input('park'),$request->input('noches') , 3);
            $data['totales']['parking'] = $this->getPricePark($request->input('park'), $request->input('noches'), 3);

            $sup_limp      = 100;
            $cost_limp     = 90;
        
        }
       
        /* LUJO */
        $data['costes']['lujo'] = $this->getCostLujo($request->input('lujo'));
        $data['totales']['lujo'] = $this->getPriceLujo($request->input('lujo'));


        /* LIMPIEZA */

        $data['costes']['limp']  = $cost_limp;
        $data['totales']['limp'] = $sup_limp;

        /* RESERVA */
       
        $data['costes']['book'] = $this->getCostBook($request->input('start'),$request->input('finish'),$request->input('pax'),$request->input('room')) ;

        $data['totales']['book'] = $this->getPriceBook($request->input('start'),$request->input('finish'),$request->input('pax'),$request->input('room'));


        return $data;




    }
    
}
