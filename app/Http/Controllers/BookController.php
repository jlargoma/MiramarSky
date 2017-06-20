<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use \Carbon\Carbon;
use Auth;
use Illuminate\Routing\Controller;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($month = "")
        {

            $mes = array();
            $arrayReservas = array();
            $totalPayments = array();
            $arrayMonths = array();
            
            if ( empty($month) ) {
                $date = Carbon::now();
            }else{
                $month = Carbon::createFromFormat('d-m-Y',$month);
                $date = $month->copy();

            }

            $firstDayOfTheYear = new Carbon('first day of September 2016');

            $arrayBooks = [
                            "nuevas" => [], 
                            "pagadas" => [],
                            "especiales" => []
                            ];

            $apartamentos = \App\Rooms::all();

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

            for ($i=1; $i <= 12; $i++) { 
                $mes[$firstDayOfTheYear->copy()->format('n')] = $firstDayOfTheYear->copy()->format('M Y');
                $firstDayOfTheYear = $firstDayOfTheYear->addMonth();
            }

            $firstDayOfTheYear = new Carbon('first day of September 2016');

            for ($i=1; $i <= 12; $i++) { 
                
                $startMonth = $firstDayOfTheYear->copy()->startOfMonth();
                $endMonth   = $firstDayOfTheYear->copy()->endOfMonth();
                $countDays  = $endMonth->diffInDays($startMonth);
                $day        = $startMonth;


                for ($j=1; $j <= $countDays+1 ; $j++) { 
                        $arrayMonths[$firstDayOfTheYear->copy()->format('n')] = $day->format('d');     

                        $day = $day->addDay();
                }
                
                $firstDayOfTheYear->addMonth();                                    

            }

            

            $pagos = \App\Payments::all();

            foreach ($pagos as $pago) {
                $totalPayments[$pago->book_id] = $pago->import;
            } 

            $start = new Carbon('first day of September 2016');
            $books = \App\Book::where('start','>',$start)->orderBy('start','desc')->get();

            foreach ($books as $key => $book) {
                if ($book->type_book == 1 || $book->type_book == 3 || $book->type_book == 4 || $book->type_book == 5 || $book->type_book == 6 ) {
                    $arrayBooks["nuevas"][] = $book;
                } elseif( $book->type_book == 2) {
                    $arrayBooks["pagadas"][] = $book;
                } elseif($book->type_book == 7 || $book->type_book == 8){
                    $arrayBooks["especiales"][] = $book;
                }
            }

            return view('backend/planning/index',[
                                                    'arrayBooks'    => $arrayBooks,
                                                    'arrayMonths'   => $arrayMonths,
                                                    'rooms'         => \App\Rooms::all(),
                                                    'roomscalendar' => \App\Rooms::where('id', '>=' , 5)->orderBy('name','DESC')->get(),
                                                    'arrayReservas' => $arrayReservas,
                                                    'mes'           => $mes,
                                                    'date'          => $date->subMonth(),
                                                    'book'          => new \App\Book(),
                                                    'extras'        => \App\Extras::all(),
                                                    'payment'       => $totalPayments,
                                                    'pagos' => \App\Payments::all(),
                                                    ]);
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
        {
            $book = new \App\Book();
            $extraPrice = 0 ;
            $extraCost  = 0;

            if ($request->extras != "") {
                foreach ($request->extras as $extra) {
                   $precios = \App\Extras::find($extra);
                   $extraPrice += $precios->price;
                   $extraCost += $precios->cost;
                }
            }
                if ($book->existDate($request->start,$request->finish,$request->newroom)) {
                    //creacion del cliente
                        $customer = new \App\Customers();
                        $customer->user_id = Auth::user()->id;
                        $customer->name = $request->name;
                        $customer->email = $request->email;
                        $customer->phone = $request->phone;
                        
                        if($customer->save()){
                            //Creacion de la reserva
                                $book->user_id       = Auth::user()->id;
                                $book->customer_id   = $customer->id;
                                $book->room_id       = $request->newroom;
                                $book->start         = Carbon::createFromFormat('d/m/Y',$request->start);
                                $book->finish        = Carbon::createFromFormat('d/m/Y',$request->finish);
                                $book->comment       = $request->comments;
                                $book->book_comments = $request->book_comments;
                                $book->type_book     = 3;
                                $book->pax           = $request->pax;
                                $book->nigths        = $request->nigths;
                                $book->agency        = $request->agencia;
                                $room                = \App\Rooms::find($request->newroom);
                                $book->sup_limp      = ($room->typeApto == 1) ? 30 : 50;
                                $book->sup_park      = $book->getPricePark($request->parking,$request->nigths);
                                $book->type_park     = $request->parking;
                                $book->cost_park     = $book->getCostPark($request->parking,$request->nigths);
                                $book->sup_lujo      = ($room->luxury == 1) ? 50 : 0;
                                $book->cost_lujo     = ($room->luxury == 1) ? 40 : 0;
                                $book->cost_apto     = $book->getCostBook($request->start,$request->finish,$request->pax,$request->newroom);
                                $book->cost_total    = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->agency + $extraCost + $book->agency;
                                $book->total_price   = $book->getPriceBook($request->start,$request->finish,$request->pax,$request->newroom) + $book->sup_park + $book->sup_lujo + $extraPrice;
                                $book->total_ben     = $book->total_price - $book->cost_total;
                                $book->extraPrice    = $extraPrice;
                                $book->extraCost     = $extraCost;
                                //Porcentaje de beneficio
                                $book->inc_percent   = number_format(( ($book->total_price * 100) / $book->cost_total)-100,2 , ',', '.') ;
                                $book->ben_jorge     = $book->getBenJorge($book->total_ben,$room->id);
                                $book->ben_jaime     = $book->getBenJaime($book->total_ben,$room->id);

                                if($book->save()){
                                    return redirect()->action('BookController@index');
                                };
                        };
                    
                    
                }else{
                
                }
        }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
        {
         $book = \App\Book::find($id);

         return view('backend/planning/update',  [
                                                    'book'   => $book ,
                                                    'rooms'  => \App\Rooms::all(),
                                                    'extras' => \App\Extras::all(),
                                                    'payments' => \App\Payments::where('book_id',$book->id)->get(),
                                                    'typecobro' => new \App\Book(),
                                                ]);
        }

    public function changeBook(Request $request, $id)
        {   
            
            if ( isset($request->room) && !empty($request->room)) {
                $book = \App\Book::find($id);

                if ($book->changeBook("",$request->room)) {
                    return "OK";
                }else{
                    return "Ya hay una reserva para ese apartamento";
                }

                    
            }
            if ( isset($request->status) && !empty($request->status)) {
                $book = \App\Book::find($id);
                
                if ($book->changeBook($request->status,"")) {
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

            $start = Carbon::createFromFormat('m/d/Y' , $request->start);
            $finish = Carbon::createFromFormat('m/d/Y' , $request->finish);
            $countDays = $finish->diffInDays($start);


            $paxPerRoom = \App\Rooms::getPaxRooms($request->pax,$request->room);

            $pax = $request->pax;
            if ($paxPerRoom > $request->pax) {
                $pax = $paxPerRoom;
            }

            $price = 0;

            for ($i=1; $i <= $countDays; $i++) { 

                $seasonActive = \App\Seasons::getSeason($start->copy());
                $prices = \App\Prices::where('season' ,  $seasonActive)
                                    ->where('occupation', $pax)->get();

                foreach ($prices as $precio) {
                    $price = $price + $precio->price;
                }
            }


            return $price;  
        }

    static function getCostBook(Request $request)
        {

            $start = Carbon::createFromFormat('m/d/Y' , $request->start);
            $finish = Carbon::createFromFormat('m/d/Y' , $request->finish);
            $countDays = $finish->diffInDays($start);


            $paxPerRoom = \App\Rooms::getPaxRooms($request->pax,$request->room);

            $pax = $request->pax;
            if ($paxPerRoom > $request->pax) {
                $pax = $paxPerRoom;
            }
            $cost = 0;
            for ($i=1; $i <= $countDays; $i++) { 

                $seasonActive = \App\Seasons::getSeason($start->copy());
                $costs = \App\Prices::where('season' ,  $seasonActive)
                                    ->where('occupation', $pax)->get();

                foreach ($costs as $key => $precio) {
                    $cost = $cost + $precio->cost;
                }
            }


            return $cost;  
        }


    //Funcion para actualizar la reserva
        public function saveUpdate(Request $request, $id)
            {
                echo "<pre>";
                $book = \App\Book::find($id);
                $room = \App\Rooms::find($request->newroom);

                $book->user_id       = Auth::user()->id;
                $book->customer_id   = $request->customer_id;
                $book->room_id       = $request->newroom;
                $book->start         = Carbon::createFromFormat('d/m/Y',$request->start);
                $book->finish        = Carbon::createFromFormat('d/m/Y',$request->finish);
                $book->comment       = $request->comments ;
                $book->book_comments = $request->book_comments;
                $book->pax           = $request->pax;
                $book->nigths        = $request->nigths;
                $book->sup_limp      = ($room->typeApto == 1) ? 30 : 50;
                $book->sup_park      = $book->getPricePark($request->parking,$request->nigths);
                $book->type_park     = $request->parking ;
                $book->cost_park     = $book->getCostPark($request->parking,$request->nigths);
                $book->sup_lujo      = ($room->luxury == 1) ? 50 : 0;
                $book->cost_lujo     = ($room->luxury == 1) ? 40 : 0;
                $book->cost_apto     = $book->getCostBook($request->start,$request->finish,$request->pax,$request->newroom);
                $book->cost_total    = $book->cost_apto + $book->cost_park + $book->cost_lujo;
                $book->total_price   = $request->total;
                $book->total_ben     = $book->total_price - $book->cost_total;
                $book->extra         = $request->extra ;
                $book->inc_percent   = number_format(( ($book->total_price * 100) / $book->cost_total)-100,2 , ',', '.') ;
                $book->ben_jorge     = $book->getBenJorge($book->total_ben,$room->id);
                $book->ben_jaime     = $book->getBenJaime($book->total_ben,$room->id);

                if ($book->save()) {
                    return redirect()->action('BookController@index');
                }
            }

    //Funcion para el precio del Aparcamiento
        static function getPricePark(Request $request)
            {
                print_r($request->park);
                // print_r($request->noches);
                die();
                $supPark = 0;
                switch ($request->park) {
                        case 1:
                            $supPark = 15 * $request->noches;
                            break;
                        case 2:
                            $supPark = 0;
                            break;
                        case 3:
                            $supPark = (15 * $request->noches) / 2;
                            break;
                        case 4:
                            $supPark = 0;
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
                            $supPark = (13.5 * $request->noches) / 2;
                            break;
                        case 4:
                            $supPark = 0;
                            break;
                    }
                return $supPark;
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

    //Funcion para cambiar el estado de los type_book

        public function changeBooks()
            {

                $books = \App\Book::where('type_book',1)->get();

                foreach ($books as $book) {
                    $book->type_book = 2;

                    $book->save();
                    echo "cambiada<br>";
                }
            }

    // Pagina de propietario
        public function owned()
            {
                $firstDayOfTheYear = new Carbon('first day of September 2016');
                
                $mes = array();
                $arrayReservas = array();
                $arrayMonths = array();

                $room = \App\Rooms::where('owned', Auth::user()->id)->first();

                $reservas = \App\Book::whereIn('type_book',[2,7,8])->where('room_id',$room->id)->orderBy('start', 'ASC')->get();

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

                for ($i=1; $i <= 12; $i++) { 
                    $mes[$firstDayOfTheYear->copy()->format('n')] = $firstDayOfTheYear->copy()->format('M Y');
                    $firstDayOfTheYear = $firstDayOfTheYear->addMonth();
                }

                for ($i=1; $i <= 12; $i++) { 
                    
                    $startMonth = $firstDayOfTheYear->copy()->startOfMonth();
                    $endMonth   = $firstDayOfTheYear->copy()->endOfMonth();
                    $countDays  = $endMonth->diffInDays($startMonth);
                    $day        = $startMonth;


                    for ($j=1; $j <= $countDays+1 ; $j++) { 
                            $arrayMonths[$firstDayOfTheYear->copy()->format('n')] = $day->format('d');     

                            $day = $day->addDay();
                    }
                    
                    $firstDayOfTheYear->addMonth();                                    

                }

                return view('backend.owned.index',[
                                                    'user'     => \App\User::find(Auth::user()->id),
                                                    'room'     => $room,
                                                    'books'    => \App\Book::where('room_id', $room->id)->orderBy('start','ASC')->get(),
                                                    'mes'      => $mes,
                                                    'reservas' => $arrayReservas,
                                                    'date' => new Carbon('first day of September 2016'),
                                                    'arrayMonths' => $arrayMonths,
                                                    ]);
            }

        public function bloqOwned(Request $request)
            {
                $start = Carbon::CreateFromFormat('d/m/Y',$request->start);
                $finish = Carbon::CreateFromFormat('d/m/Y',$request->finish);
                echo Auth::user()->id;
            }
}
