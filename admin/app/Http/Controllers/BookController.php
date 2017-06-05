<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;

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
        $apartamentos = \App\Rooms::where('id',5)->get();
        foreach ($apartamentos as $apartamento) {
            $mesinicio = Carbon::now()->subMonth();
            for ($i=0; $i < 4; $i++) { 

                $arrayReservas[$i][$apartamento->id] = $this->getCalendar($apartamento->id,$mesinicio,$i);
            $mesinicio = $mes->addMonth();
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
                                                'roomscalendar' => \App\Rooms::all(),
                                                'arrayReservas'    => $arrayReservas,
                                                'mes'           => $mes->subMonth(),
                                                'date'          => $date->subMonth(),
                                                'book'          => new \App\Book(),
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
            if ($book->existDate($request->start,$request->finish,$request->room)) {
                return "va bien";
            }else{
                return "va mal";
            }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    static function getPriceBook(Request $request){
            
        $book = new \App\Book();

        $price = $book->getPriceBook($request->start,$request->finish,$request->pax,$request->room,$request->park);

        return $price;
    }
    static function getCostBook(Request $request){
            
        $book = new \App\Book();

        $cost = $book->getCostBook($request->start,$request->finish,$request->pax,$request->room,$request->park);
        
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

    public function getCalendar($id,$mes,$posicion)
    {

        $firstDayOfTheYear = $mes;

        $reservas = \App\Book::whereIn('type_book' , [1,2,7,8])
                                ->where('room_id', $id)
                                ->whereMonth('start', '=' ,$firstDayOfTheYear->copy()->format('m'))
                                ->get();

        if (count($reservas) > 0) {

            foreach ($reservas as $key => $reserva) {

                $startMonth = $firstDayOfTheYear->copy()->startOfMonth();
                $endMonth = $firstDayOfTheYear->copy()->endOfMonth();
                $countDays = $endMonth->diffInDays($startMonth);
                $day = $startMonth->copy();


                for ($i=1; $i <= $countDays ; $i++) { 
                    if ($day->copy()->format('Y-m-d')  <= $reserva->finish && $day->copy()->format('Y-m-d') >= $reserva->start) {
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

                            default:
                                # code...
                                break;
                        }
                          $arrayReservas[$posicion][$id][$i] = $status;
                          $day = $day->addDay();
                      } else {
                          $day = $day->addDay();
                      }      
                }
                
            }

        } else {

            $arrayReservas[$posicion][$id] = "no hay reservas para este mes";

        }

        return $arrayReservas;
    }
}
