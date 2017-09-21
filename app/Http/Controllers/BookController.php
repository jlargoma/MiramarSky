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

            $mes           = array();
            $arrayReservas = array();
            $totalPayments = array();
            $arrayMonths   = array();
            $arrayDays     = array();
            $arrayTotales  = array();
            $arrayPruebas  = array();
            $mobile = new Mobile();

            if ( empty($year) ) {
                $date = Carbon::now();
            }else{
                $year = Carbon::createFromFormat('Y',$year);
                $date = $year->copy();

            }


            $arrayBooks = [
                            "nuevas" => [],
                            "pagadas" => [],
                            "especiales" => []
                            ];

            $apartamentos = \App\Rooms::where('state','=',1);

            $reservas = \App\Book::whereIn('type_book',[1,2,7,8])->orderBy('start', 'ASC')->get();

            foreach ($reservas as $reserva) {
                $dia = Carbon::createFromFormat('Y-m-d',$reserva->start);
                $start = Carbon::createFromFormat('Y-m-d',$reserva->start);
                $finish = Carbon::createFromFormat('Y-m-d',$reserva->finish);
                $diferencia = $start->diffInDays($finish);
                for ($i=0; $i <= $diferencia; $i++) {
                    $arrayReservas[$reserva->room_id][$dia->copy()->format('Y')][$dia->copy()->format('n')][$dia->copy()->format('j')] = $reserva;
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
            if ($date->copy()->format('n') >= 9) {
                $start = new Carbon('first day of September '.$date->copy()->format('Y'));
            }else{
                $start = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
            }



            $pagos = \App\Payments::where('datePayment','>=',$start->copy()->format('Y-m-d'))->where('datePayment','<=',$start->copy()->addYear()->format('Y-m-d'))->get();
            $paymentSeason = [
                                "cash" => 0,
                                "banco" =>0,
                                "total" => 0,
                                ];

            foreach ($pagos as $pago) {
                if (isset($totalPayments[$pago->book_id])) {
                    $totalPayments[$pago->book_id] += $pago->import;
                }else{
                    $totalPayments[$pago->book_id] = $pago->import;
                }
                if ($pago->type == 0 || $pago->type == 1) {
                    $paymentSeason["cash"] += $pago->import;
                }else{
                    $paymentSeason["banco"] += $pago->import;
                }
                $paymentSeason["total"] += $pago->import;
            }

            $ventas = $book->getVentas($date->copy()->format('Y-m-d'));

            $ventasOld = $book->getVentas($date->copy()->subYear()->format('Y-m-d'));

            

            if(!$mobile->isMobile()){
                $books = \App\Book::where('start','>',$start)->where('finish','<',$start->copy()->addYear())->orderBy('start','ASC')->get();
            }else{
                // $date = Carbon::now();
                $books = \App\Book::where('start','>=',$date->copy()->subMonth())->where('finish','<',$date->copy()->addYear())->orderBy('start','ASC')->get();
                $proxIn = \App\Book::where('start','>=',$date->copy()->subWeek())->where('type_book',2)->orderBy('start','ASC')->get();
                $proxOut = \App\Book::where('start','>=',$date->copy()->subWeek())->where('type_book',2)->orderBy('start','ASC')->get();
            }
            foreach ($books as $key => $book) {
                if ($book->type_book == 1 || $book->type_book == 3 || $book->type_book == 4 || $book->type_book == 5 || $book->type_book == 6 ) {
                    $arrayBooks["nuevas"][] = $book;
                } elseif( $book->type_book == 2) {
                    $arrayBooks["pagadas"][] = $book;
                } elseif($book->type_book == 7 || $book->type_book == 8){
                    $arrayBooks["especiales"][] = $book;
                }
            }


            $books = \App\Book::whereIn('type_book', [2])->get();

            foreach ($books as $book) {
                $fecha = Carbon::createFromFormat('Y-m-d',$book->start);
                if ($fecha->copy()->format('n') >= 9) {
                    if(isset($arrayTotales[$fecha->copy()->format('Y')])){
                        $arrayTotales[$fecha->copy()->format('Y')] += $book->total_price;
                    }else{
                        $arrayTotales[$fecha->copy()->format('Y')] = $book->total_price;
                    }
                }else{
                    if(isset($arrayTotales[$fecha->copy()->subYear()->format('Y')])){
                        $arrayTotales[$fecha->copy()->subYear()->format('Y')] += $book->total_price;
                    }else{
                        $arrayTotales[$fecha->copy()->subYear()->format('Y')] = $book->total_price;
                    }
                }
            }



                if (!$mobile->isMobile()){
                    return view('backend/planning/index',[
                                                        'arrayBooks'    => $arrayBooks,
                                                        'arrayMonths'   => $arrayMonths,
                                                        'arrayTotales'  => $arrayTotales,
                                                        'rooms'         => \App\Rooms::where('state','=',1)->get(),
                                                        'roomscalendar' => \App\Rooms::where('id', '>=' , 5)->where('state','=',1)->orderBy('name','DESC')->get(),
                                                        'arrayReservas' => $arrayReservas,
                                                        'mes'           => $mes,
                                                        'date'          => $date,
                                                        'book'          => new \App\Book(),
                                                        'extras'        => \App\Extras::all(),
                                                        'payment'       => $totalPayments,
                                                        'pagos'         => \App\Payments::all(),
                                                        'days'          => $arrayDays,
                                                        'inicio'        => $start,
                                                        'paymentSeason' =>$paymentSeason,
                                                        'ventas'        => $ventas,
                                                        'ventasOld'     => $ventasOld,
                                                        ]);
                }else{
                    return view('backend/planning/index_mobile',[
                                                        'arrayBooks'    => $arrayBooks,
                                                        'arrayMonths'   => $arrayMonths,
                                                        'rooms'         => \App\Rooms::where('state','=',1)->get(),
                                                        'roomscalendar' => \App\Rooms::where('id', '>=' , 5)->where('state','=',1)->orderBy('name','DESC')->get(),
                                                        'arrayReservas' => $arrayReservas,
                                                        'mes'           => $mes,
                                                        'date'          => $date->subMonth(),
                                                        'book'          => new \App\Book(),
                                                        'extras'        => \App\Extras::all(),
                                                        'payment'       => $totalPayments,
                                                        'pagos'         => \App\Payments::all(),
                                                        'days'          => $arrayDays,
                                                        'inicio'        => $start->addMonth(3),
                                                        'proxIn'        => $proxIn,
                                                        'proxOut'       => $proxOut,
                                                        'paymentSeason' =>$paymentSeason,

                                                        ]);
                }
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $date = explode('-', $request->input('fechas'));

        $start = Carbon::createFromFormat('d M, y' , trim($date[0]))->format('d/m/Y');
        $finish = Carbon::createFromFormat('d M, y' , trim($date[1]))->format('d/m/Y');
        $book = new \App\Book();
        $extraPrice = 0 ;
        $extraCost  = 0;


        if ($request->input('extras') != "") {
            foreach ($request->input('extras') as $extra) {
               $precios = \App\Extras::find($extra);
               $extraPrice += $precios->price;
               $extraCost += $precios->cost;
            }
        }

        //createacion del cliente
            $customer = new \App\Customers();
            $customer->user_id = (Auth::check())?Auth::user()->id:23;
            $customer->name = $request->input('name');
            $customer->email = $request->input('email');
            $customer->phone = $request->input('phone');

            if($customer->save()){
                //Creacion de la reserva
                    $book->user_id       = $customer->user_id;
                    $book->customer_id   = $customer->id;
                    $book->room_id       = $request->input('newroom');
                    $book->start         = Carbon::createFromFormat('d/m/Y',$start);
                    $book->finish        = Carbon::createFromFormat('d/m/Y',$finish);
                    $book->comment       = $request->input('comments');
                    $book->book_comments = $request->input('book_comments');
                    $book->type_book     = 3;
                    $book->pax           = $request->input('pax');
                    $book->nigths        = $request->input('nigths');
                    $book->agency        = $request->input('agency');
                    $book->PVPAgencia    = $request->input('agencia');

                    $room                = \App\Rooms::find($request->input('newroom'));
                    $book->sup_limp      = ($room->sizeApto == 1) ? 30 : 50;


                    $book->sup_park      = $this->getPriceParkController($request->input('parking'), $request->input('nigths'));
                    $book->type_park     = $request->input('parking');


                    $book->cost_park     = $this->getCostParkController($request->input('parking'),$request->input('nigths'));
                    $book->type_luxury   = $request->input('type_luxury');
                    $book->sup_lujo      = $this->getPriceLujo($request->input('type_luxury'));
                    $book->cost_lujo     = $this->getCostLujo($request->input('type_luxury'));
                    $book->cost_apto     = $book->getCostBook($start,$finish,$request->input('pax'),$request->input('newroom'));
                    $book->cost_total    = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $extraCost;

                    $book->total_price   = $book->getPriceBook($start,$finish,$request->input('pax'),$request->input('newroom')) + $book->sup_park + $book->sup_lujo+ $book->sup_limp + $extraPrice;


                    $book->total_ben     = $book->total_price - $book->cost_total;


                    $book->extraPrice    = $extraPrice;
                    $book->extraCost     = $extraCost;
                    //Porcentaje de beneficio
                    $book->inc_percent   = number_format(( ($book->total_price * 100) / $book->cost_total)-100,2 , ',', '.') ;
                    $book->ben_jorge     = $book->getBenJorge($book->total_ben,$room->id);
                    $book->ben_jaime     = $book->getBenJaime($book->total_ben,$room->id);

                    if($book->save()){
                        /* Notificacion via email */
                        if ($customer->email) {
                           if ( $request->input('from') ){
                                MailController::sendEmailBookSuccess( $book, 1);
                                return view('frontend.bookStatus.bookOk');
                            }
                            else{
                                // MailController::sendEmailBookSuccess( $book, 0);
                                return redirect()->back();
                            }
                        }else{

                        }


                    };
            };


        return redirect('admin/reservas');
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
                if (!$mobile->isMobile()){
                    return view('backend/planning/update',  [
                                                                'book'   => $book ,
                                                                'rooms'  => \App\Rooms::all(),
                                                                'extras' => \App\Extras::all(),
                                                                'start' => Carbon::createFromFormat('Y-m-d',$book->start)->format('d M,y'),
                                                                'payments' => $payments,
                                                                'typecobro' => new \App\Book(),
                                                                'totalpayment' => $totalpayment,
                                                            ]);
                }else{
                    return view('backend/planning/update_mobile',  [
                                                                'book'   => $book ,
                                                                'rooms'  => \App\Rooms::all(),
                                                                'extras' => \App\Extras::all(),
                                                                'payments' => $payments,
                                                                'typecobro' => new \App\Book(),
                                                                'totalpayment' => $totalpayment,
                                                            ]);
                }
        }

    public function changeBook(Request $request, $id)
        {

            if ( isset($request->room) && !empty($request->room)) {
                $book = \App\Book::find($id);

                if ($book->changeBook("",$request->room,$book)) {
                    return "OK";
                }else{
                    return "Ya hay una reserva para ese apartamento";
                }


            }
            if ( isset($request->status) && !empty($request->status)) {
                $book = \App\Book::find($id);

                if ($book->changeBook($request->status,"",$book)) {
                    return "Estado cambiado";
                }else{
                    return "No se puede cambiar el estado";
                }
            }else{
                return "Valor nulo o vacio";
            }
        }

    static function getPriceBook(Request $request)
        {

            $start = Carbon::createFromFormat('d/m/Y' , $request->start);
            $finish = Carbon::createFromFormat('d/m/Y' , $request->finish);
            $countDays = $finish->diffInDays($start);

            $paxPerRoom = \App\Rooms::getPaxRooms($request->pax,$request->room);
            $room = \App\Rooms::find($request->room);
            $suplimp =  ($room->sizeApto == 1 )? 30 : 50 ;
            $pax = $request->pax;
            if ($paxPerRoom > $request->pax) {
                $pax = $paxPerRoom;
            }

            $price = 0;


            for ($i=1; $i <= $countDays; $i++) {

                $seasonActive = \App\Seasons::getSeason($start->copy()->format('Y-m-d'));

                $prices = \App\Prices::where('season' ,  $seasonActive)
                                    ->where('occupation', $pax)->get();

                foreach ($prices as $precio) {
                    $price = $price + $precio->price ;
                }
            }


            return $price + $suplimp;
        }

    static function getCostBook(Request $request)
        {


            $start = Carbon::createFromFormat('d/m/Y' , $request->start);
            $finish = Carbon::createFromFormat('d/m/Y' , $request->finish);
            $countDays = $finish->diffInDays($start);


            $paxPerRoom = \App\Rooms::getPaxRooms($request->pax,$request->room);
            
            $room = \App\Rooms::find($request->room);
            $suplimp =  ($room->sizeApto == 1 )? 30 : 40 ;

            $pax = $request->pax;
            if ($paxPerRoom > $request->pax) {
                $pax = $paxPerRoom;
            }
            $cost = 0;
            for ($i=1; $i <= $countDays; $i++) {

                $seasonActive = \App\Seasons::getSeason($start->copy()->format('Y-m-d'));
                $costs = \App\Prices::where('season' ,  $seasonActive)
                                    ->where('occupation', $pax)->get();

                foreach ($costs as $precio) {
                    $cost = $cost + $precio->cost ;
                }
            }

            return $cost + $suplimp;
        }

    //Funcion para actualizar la reserva
        public function saveUpdate(Request $request, $id)
            {
                $date = explode('-', $request->input('fechas'));

                $start = Carbon::createFromFormat('d M, y' , trim($date[0]))->format('d/m/Y');
                $finish = Carbon::createFromFormat('d M, y' , trim($date[1]))->format('d/m/Y');
                $book = new \App\Book();
                $extraPrice = 0 ;
                $extraCost  = 0;



                $book = \App\Book::find($id);
                $room = \App\Rooms::find($request->newroom);

                $book->user_id       = Auth::user()->id;
                $book->customer_id   = $request->customer_id;
                $book->room_id       = $request->newroom;
                $book->start         = Carbon::createFromFormat('d/m/Y',$start);
                $book->finish        = Carbon::createFromFormat('d/m/Y',$finish);
                $book->comment       = $request->comments;
                $book->book_comments = $request->book_comments;
                $book->pax           = $request->pax;
                $book->nigths        = $request->nigths;
                $book->sup_limp      = ($room->typeApto == 1) ? 35 : 50;
                $book->sup_park      = $book->getPricePark($request->parking,$request->nigths);
                $book->type_park     = $request->parking;

                $book->cost_park     = $book->getCostPark($request->parking,$request->nigths);

                $book->sup_lujo      = $this->getPriceLujo($request->input('type_luxury'));
                $book->cost_lujo     = $this->getCostLujo($request->input('type_luxury'));

                $book->cost_apto     = $book->getCostBook($start,$finish,$request->pax,$request->newroom);
                $book->cost_total    = $book->cost_apto + $book->cost_park + $book->cost_lujo;

                $book->total_price   = $request->total;
                $book->total_ben     = $book->total_price - $book->cost_total;
                $book->extra         = $request->extra;
                $book->inc_percent   = number_format(( ($book->total_price * 100) / $book->cost_total)-100,2 , ',', '.') ;
                $book->ben_jorge     = $book->getBenJorge($book->total_ben,$room->id);
                $book->ben_jaime     = $book->getBenJaime($book->total_ben,$room->id);

                if ($book->save()) {
                    return redirect('admin/reservas/update/'.$id);
                }
            }

    //Funcion para el precio del Aparcamiento
        static function getPricePark(Request $request)
            {

                $supPark = 0;
                switch ($request->park) {
                        case 1:
                            $supPark = 15 * $request->noches;
                            break;
                        case 2:
                            $supPark = 0;
                            break;
                        case 3:
                            $supPark = 0;
                            break;
                        case 4:
                            $supPark = (15 * $request->noches) / 2;
                            break;
                    }
                return $supPark;
            }

    //Funcion para el precio del Aparcamiento
        static function getPriceParkController($park,$noches)
            {

                $supPark = 0;
                switch ($park) {
                        case 1:
                            $supPark = 15 * $noches;
                            break;
                        case 2:
                            $supPark = 0;
                            break;
                        case 3:
                            $supPark = 0;
                            break;
                        case 4:
                            $supPark = (15 * $noches) / 2;
                            break;
                    }
                return $supPark;
            }

    //Funcion para el coste del Aparcamiento
        static function getCostPark(Request $request)
            {

                $supPark = 0;
                switch ($request->park) {
                        case 1:
                            $supPark = 13.5 * $request->noches;
                            break;
                        case 2:
                            $supPark = 0;
                            break;
                        case 3:
                            $supPark = 0;
                            break;
                        case 4:
                            $supPark = (13.5 * $request->noches) / 2;
                            break;
                    }
                return $supPark;
            }

    //Funcion para el coste del Aparcamiento
        static function getCostParkController($park,$noches)
            {

                $supPark = 0;
                switch ($park) {
                        case 1:
                            $supPark = 13.5 * $noches;
                            break;
                        case 2:
                            $supPark = 0;
                            break;
                        case 3:
                            $supPark = 0;
                            break;
                        case 4:
                            $supPark = (13.5 * $noches) / 2;
                            break;
                    }
                return $supPark;
            }

    //Funcion para el precio del Suplemento de Lujo
        static function getPriceLujo($lujo)
            {
                $supLujo = 0;
                if ($lujo > 4) {
                    $supLujo = $lujo;
                }else{

                    switch ($lujo) {
                        case 1:
                            $supLujo = 50;
                             break;
                        case 2:
                            $supLujo = 0;
                            break;
                        case 3:
                            $supLujo = 0;
                            break;
                        case 4:
                            $supLujo = 50/2;
                            break;
                    }
                }
                return $supLujo;
            }

    //Funcion para el precio del Suplemento de Lujo desde Admin
        static function getPriceLujoAdmin(Request $request)
            {
                $supLujo = 0;

                switch ($request->lujo) {
                    case 1:
                        $supLujo = 50;
                         break;
                    case 2:
                        $supLujo = 0;
                        break;
                    case 3:
                        $supLujo = 0;
                        break;
                    case 4:
                        $supLujo = 50/2;
                        break;
                }
                return $supLujo;
            }

    //Funcion para el precio del Suplemento de Lujo
        static function getCostLujo($lujo)
            {
                $supLujo = 0;
                if ($lujo > 4) {
                    $supLujo = $lujo - 10;
                } else {
                    switch ($lujo) {
                            case 1:
                                $supLujo = 0;
                                 break;
                            case 2:
                                $supLujo = 0;
                                break;
                            case 3:
                                $supLujo = 40;
                                break;
                            case 4:
                                $supLujo = 40/2;
                                break;
                        }
                }

                return $supLujo;
            }

    //Funcion para el precio del Suplemento de Lujo desde Admin
        static function getCostLujoAdmin(Request $request)
            {
                $supLujo = 0;
                switch ($request->lujo) {
                        case 1:
                            $supLujo = 0;
                             break;
                        case 2:
                            $supLujo = 0;
                            break;
                        case 3:
                            $supLujo = 40;
                            break;
                        case 4:
                            $supLujo = 40/2;
                            break;
                    }

                return $supLujo;
            }

    // Funcion para la migracion de la base antigua  a la nuevas
        // public function getBaseDatos()
        //     {
        //         $apartamentos = DB::connection('apartamento')->table('book')->get();

        //         echo "<pre>";
        //         foreach ($apartamentos as $apartamento) {
        //             $book = \App\Book::find($apartamento->ID);
        //             if (count($book) > 0) {
        //                 echo "Ya existe esta reserva ".$apartamento->ID." <br>";
        //             } else {
        //                 $book = new \App\Book();
        //                 $book->id            = $apartamento->ID;
        //                 $book->user_id       =$apartamento->UserID;
        //                 $book->customer_id   =$apartamento->CustomerID;
        //                 $book->room_id       =$apartamento->RoomID;
        //                 $book->start         =$apartamento->Start;
        //                 $book->finish        =$apartamento->Finish;
        //                 $book->comment       =($apartamento->Comment != "") ? $apartamento->Comment : "";
        //                 $book->book_comments =($apartamento->bookComments != "") ? $apartamento->bookComments : "";
        //                 $book->pax           =$apartamento->Pax;
        //                 $book->nigths        =$apartamento->noches;
        //                 $book->sup_limp      =$apartamento->supLimp;
        //                 $book->sup_park      =$apartamento->supPark;
        //                 $book->type_book     = $apartamento->Type;
        //                 $book->type_park     = 0;
        //                 $book->cost_park     =$apartamento->Parking;
        //                 $book->sup_lujo      =$apartamento->supLujo;
        //                 $book->cost_lujo     =$apartamento->LujoCoste;
        //                 $book->cost_apto     =$apartamento->costeApto;
        //                 $book->cost_total    =$apartamento->costeTotal;
        //                 $book->total_price   =$apartamento->totalPrice;
        //                 $book->total_ben     =$apartamento->ingresoNeto;
        //                 $book->extra         = 0;
        //                 $book->inc_percent   =$apartamento->porcIngreso;
        //                 $book->ben_jorge     = $book->getBenJorge($book->total_ben,$apartamento->RoomID);
        //                 $book->ben_jaime     = $book->getBenJaime($book->total_ben,$apartamento->RoomID);
        //                 $book->send          =$apartamento->send;
        //                 $book->statusCobro   =$apartamento->statusCobro;
        //                 if ($book->save()) {
        //                    echo "Insertado ID ".$apartamento->ID."<br>";
        //                 } else {
        //                     # code...
        //                 }
        //             }
        //         }
        //     }

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
        public function tabReserva($id){
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


    public function sendJaime(Request $request)
        {
            $book = \App\Book::find($request->id);
            Mail::send('backend.emails.jaime',['book' => $book], function ($message) use ($book) {
                                $message->from('jbaz@daimonconsulting.com', 'Miramarski');

                                $message->to($book->customer->email);
                                $message->subject('Correo a Jaime');
                            });

            $book->send = 1;
            $book->save();
          
        }

    public function sendEmail(Request $request)
        {
            $book = \App\Book::find($request->id);
            Mail::send('backend.emails.jaime',['book' => $book], function ($message) use ($book) {
                                $message->from('jbaz@daimonconsulting.com', 'Miramarski');

                                $message->to($book->customer->email);
                                $message->subject('Correo Contestado ');
                            });

            $book->send = 1;
            $book->type_book = 5;
            $book->save();
            return redirect()->back();
        }

    public function ansbyemail($id)
        {
            $book = \App\Book::find($id);

            return view('backend/planning/_answerdByEmail',  [
                                                                'book' => $book
                                                            ]);
        }

    public function delete($id)
        {
            $book = \App\Book::find($id);

            if ($book->delete()) {
                    return redirect()->action('BookController@index');
                }

        }
}
