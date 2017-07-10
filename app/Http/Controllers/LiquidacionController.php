<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;

class LiquidacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index($month="")
        {   
            $totales = [
                            "total" => [],
                            "coste" => [],
                            "banco" => [],
                            "jorge" => [],
                            "jaime" => [],
                            "benJorge" => [],
                            "benJaime" => [],
                            "pendiente" => [],
                            "limpieza" => [],
                        ];
            $liquidacion = new \App\Liquidacion();
            if (empty($month)) {
                $date = new Carbon('first day of September 2016');
            }else{
                $date = $month;
            }
            $books = \App\Book::where('start' , '>=' , $date)->where('type_book',2)->get();

            foreach ($books as $key => $book) {
                if ($key == 0) {
                    $totales["total"] = $book->total_price;
                    $totales["coste"] = $book->cost_total;
                    $totales["banco"] = $book->getPayment(2);
                    $totales["jorge"] = $book->getPayment(0);
                    $totales["jaime"] = $book->getPayment(1);
                    $totales["benJorge"] = $book->total_price;
                    $totales["benJaime"] = $book->total_price;
                    $totales["pendiente"] = $book->getPayment(4);
                    $totales["limpieza"] = $book->sup_limp;
                }
                $totales["total"] += $book->total_price;
                $totales["coste"] += $book->cost_total;
                $totales["banco"] += $book->getPayment(2);
                $totales["jorge"] += $book->getPayment(0);
                $totales["jaime"] += $book->getPayment(1);
                $totales["benJorge"] += $book->total_price;
                $totales["benJaime"] += $book->total_price;
                $totales["pendiente"] += $book->getPayment(4);
                $totales["limpieza"] += $book->sup_limp;
 
            }

            return view('backend/sales/index',  [
                                                    'books'   => $books,
                                                    'totales' => $totales,
                                                ]);
        }

    public function apto()
        {
            return view('backend/sales/liquidacion_apto');
        }
    public function perdidas()
        {
            return view ('backend/sales/perdidas_ganancias');
        }
    public function statistics()
        {
            return view ('backend/sales/statistics');
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // static  function getPayments()
    //     {
    //         return "hola";
    //     }

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
}
