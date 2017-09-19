<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use Auth;

class OwnedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($year = "")
    {
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }
        
        $firstDayOfTheYear = new Carbon('first day of September '.$date->copy()->format('Y'));
        
        $mes = array();
        $arrayReservas = array();
        $arrayMonths = array();

        $total = 0;
        $apto = 0;
        $park = 0;
        $lujo = 0;

        if (Auth::user()->id != 39 && Auth::user()->id != 1 && Auth::user()->id != 23) {
            $room = \App\Rooms::where('owned', Auth::user()->id)->first();
            
        }else{
            $room = \App\Rooms::where('owned', 39)->first();
        }

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
        $books = \App\Book::where('room_id', $room->id)->orderBy('start','ASC')->get();

        
        foreach ($books as $book) {
            $total +=  $book->cost_total;
            $apto += $book->cost_apto;
            $park += $book->cost_park;
            $lujo += $book->cost_lujo;
        }

        return view('backend.owned.index',[
                                            'user'        => \App\User::find(Auth::user()->id),
                                            'room'        => $room,
                                            'books'       => $books,
                                            'mes'         => $mes,
                                            'reservas'    => $arrayReservas,
                                            'date'        => new Carbon('first day of September 2016'),
                                            'arrayMonths' => $arrayMonths,
                                            'total'       => $total,
                                            'apto'       => $apto,
                                            'park'       => $park,
                                            'lujo'       => $lujo,
                                            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
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
    // Pagina de propietario


    public function bloqOwned(Request $request)
            {
                
                $room = \App\Rooms::where('owned', Auth::user()->id)->first();

                $book = new \App\Book();
                if ($book->existDate($request->start,$request->finish,$room->id)) {
                    $customer = \App\Customers::where('name' , 'Bloqueo '.Auth::user()->name)->first();
                    if (count($customer) > 0) {
                        echo "Ya existe ese usuario";
                        $book->user_id = Auth::user()->id;
                        
                    }else{
                        $bloqueo = new \App\Customers();
                        $bloqueo->user_id = Auth::user()->id;
                        $bloqueo->name = 'Bloqueo '.Auth::user()->name;

                        $bloqueo->save();

                        $book->user_id = Auth::user()->id;
                        $book->customer_id = $bloqueo->id;
                        $book->room_id = $room->id;
                        $book->start = Carbon::CreateFromFormat('d/m/Y',$request->start);
                        $book->finish = Carbon::CreateFromFormat('d/m/Y',$request->finish);
                        $book->type_book = 7;

                        $book->save();
                    }
                }else{
                    echo "ya hay una reserva";
                }
            }

}
