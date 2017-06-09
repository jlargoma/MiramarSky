<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function index()
    {

        $date = Carbon::now();
        $mes = Carbon::now();
        $start = new Carbon('first day of September 2016');
        $firstDayOfTheYear = new Carbon('first day of September 2016');

        $arrayBooks = [
                        "nuevas" => [], 
                        "pagadas" => [],
                        "especiales" => []
                        ];

        

        $arrayReservas = array();
        $apartamentos = \App\Rooms::all();
        
        foreach ($apartamentos as $apartamento) {
            $mesinicio = Carbon::now()->subMonth();
            for ($i=0; $i < 4; $i++) { 

                $arrayReservas[$i][$apartamento->id] = $this->getCalendar($apartamento->id,$mesinicio);
                $mesinicio->addMonth();
            }
            
        }

        $firstDayOfTheYear = Carbon::now()->subMonth();

        for ($i=1; $i <= 4; $i++) { 
            
            $startMonth = $firstDayOfTheYear->copy()->startOfMonth();
            $endMonth = $firstDayOfTheYear->copy()->endOfMonth();
            $countDays = $endMonth->diffInDays($startMonth);
            $day = $startMonth;


            for ($j=1; $j <= $countDays+1 ; $j++) { 
                    $arrayMonths[$i][$j] = $day->format('d');     

                    $day = $day->addDay();
            }

            $firstDayOfTheYear->addMonth();                                    

        }
        $books = \App\Book::where('start','>',$start)
                            ->orderBy('start','desc')
                            ->get();

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
                                                'roomscalendar' => \App\Rooms::where('id', '>=' , 5)->get(),
                                                'arrayReservas' => $arrayReservas,
                                                'mes'           => $mes->subMonth(),
                                                'date'          => $date->subMonth(),
                                                'book'          => new \App\Book(),
                                                'extras'        => \App\Extras::all(),
                                                'pagos'         => \App\Payments::all(),
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
            echo "<pre>";
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
                                $room                = \App\Rooms::find($request->newroom);
                                $book->sup_limp      = ($room->typeApto == 1) ? 30 : 50;
                                $book->sup_park      = $book->getPricePark($request->parking,$request->nigths);
                                $book->type_park     = $request->parking;
                                $book->cost_park     = $book->getCostPark($request->parking,$request->nigths);
                                $book->sup_lujo      = ($room->luxury == 1) ? 50 : 0;
                                $book->cost_lujo     = ($room->luxury == 1) ? 40 : 0;
                                $book->cost_apto     = $book->getCostBook($request->start,$request->finish,$request->pax,$request->newroom);
                                $book->cost_total    = $book->cost_apto + $book->cost_park + $book->cost_lujo;
                                $book->total_price   = $book->getPriceBook($request->start,$request->finish,$request->pax,$request->newroom) + $book->sup_park + $book->sup_lujo;
                                $book->total_ben     = $book->total_price - $book->cost_total;
                                //Porcentaje de beneficio
                                $book->inc_percent   = number_format(( ($book->total_price * 100) / $book->cost_total)-100,2 , ',', '.') ;
                                $book->ben_jorge     = $book->getBenJorge($book->total_ben,$room->id);
                                $book->ben_jaime     = $book->getBenJaime($book->total_ben,$room->id);
                                if($book->save()){
                                    return redirect()->action('BookController@index');
                                };

                        };
                    
                    
                }else{
                    return "va mal";
                }
            die;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
        {
            //
        }

    public function getCalendar($id,$mes)
        {

            $reservas = \App\Book::whereIn('type_book' , [1,2,7,8])
                                    ->where('room_id', $id)
                                    ->whereMonth('start', '=' ,$mes->copy()->format('m'))
                                    ->get();

            if (count($reservas) > 0) {

                foreach ($reservas as $key => $reserva) {

                    $startMonth = $mes->copy()->startOfMonth();
                    $endMonth = $mes->copy()->endOfMonth();
                    $countDays = $endMonth->diffInDays($startMonth);
                    $day = $startMonth->copy();


                    for ($i=1; $i <= $countDays+1 ; $i++) { 
                        if ($day->copy()->format('Y-m-d') <= $reserva->finish && $day->copy()->format('Y-m-d') >= $reserva->start) {
                            switch ($reserva->type_book) {
                                case '1':
                                    if ($day->copy()->format('Y-m-d') == $reserva->start) {
                                        $status = "Reservado start";
                                    } elseif($day->copy()->format('Y-m-d') > $reserva->start && $day->copy()->format('Y-m-d')  < $reserva->finish) {
                                        $status = "Reservado";
                                    }elseif ($day->copy()->format('Y-m-d') == $reserva->finish){
                                       $status = "Reservado end"; 
                                    }else{
                                        $status = "";
                                    }
                                    break;
                                case '2':
                                    if ($day->copy()->format('Y-m-d') == $reserva->start) {
                                        $status = "Pagada-la-señal start";
                                    } elseif($day->copy()->format('Y-m-d') > $reserva->start && $day->copy()->format('Y-m-d')  < $reserva->finish) {
                                        $status = "Pagada-la-señal";
                                    }elseif ($day->copy()->format('Y-m-d') == $reserva->finish){
                                       $status = "Pagada-la-señal end"; 
                                    }else{
                                        $status = "";
                                    }
                                    break;
                                case '7':
                                    if ($day->copy()->format('Y-m-d') == $reserva->start) {
                                        $status = "Bloqueado start";
                                    } elseif($day->copy()->format('Y-m-d') > $reserva->start && $day->copy()->format('Y-m-d')  < $reserva->finish) {
                                        $status = "Bloqueado";
                                    }elseif ($day->copy()->format('Y-m-d') == $reserva->finish){
                                       $status = "Bloqueado end"; 
                                    }else{
                                        $status = "";
                                    }
                                    break;
                                case '8':
                                    if ($day->copy()->format('Y-m-d') == $reserva->start) {
                                        $status = "SubComunidad start";
                                    } elseif($day->copy()->format('Y-m-d') > $reserva->start && $day->copy()->format('Y-m-d')  < $reserva->finish) {
                                        $status = "SubComunidad";
                                    }elseif ($day->copy()->format('Y-m-d') == $reserva->finish){
                                       $status = "SubComunidad end"; 
                                    }else{
                                        $status = "";
                                    }
                                    break;
                            }
                                $arrayReservas[$i] = $status;

                                $day = $day->addDay();
                          } else {
                              $day = $day->addDay();
                          }      
                    }
                    
                }

            } else {

                $arrayReservas = "no hay reservas para este mes";

            }

            return $arrayReservas;
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

}
