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
        $firstDayOfTheYear = Carbon::create(2016, 1, 1);

        $arrayBooks = [
                        "nuevas" => [], 
                        "pagadas" => [],
                        "especiales" => []
                        ];

        $books = \App\Book::where('start','>',$start)
                            ->orderBy('start','desc')
                            ->get();

        $arrayMonths = array();



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
                        
        foreach ($books as $key => $book) {
            if ($book->type_book == 1 || $book->type_book == 3 || $book->type_book == 4 || $book->type_book == 5 || $book->type_book == 6 ) {
                $arrayBooks["nuevas"][] = $book;
            } elseif( $book->type_book == 2) {
                $arrayBooks["pagadas"][] = $book;
            } elseif($book->type_book == 6 || $book->type_book == 7){
                $arrayBooks["especiales"][] = $book;
            }
        }

        return view('backend/planning/index',[
                                                'arrayBooks'    => $arrayBooks,
                                                'arrayMonths'   => $arrayMonths,
                                                'rooms'         => \App\Rooms::all(),
                                                'roomscalendar' => \App\Rooms::where('id' , '>' , 4)->get(),
                                                'mes'           => $mes->subMonth(),
                                                'date'          => $date->subMonth(),
                                                // 'enero'      => $firstDayOfTheYear,
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
}
