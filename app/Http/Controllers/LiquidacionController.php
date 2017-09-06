<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use App\Classes\Mobile;
setlocale(LC_TIME, "ES"); 
setlocale(LC_TIME, "es_ES");

class LiquidacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index($year="")
        {   
            $now = Carbon::now();
            $totales = [
                            "total"        => [],
                            "coste"        => [],
                            "banco"        => [],
                            "jorge"        => [],
                            "jaime"        => [],
                            "costeApto"    => [],
                            "costePark"    => [],
                            "costeLujo"    => [],
                            "costeLimp"    => [],
                            "costeAgencia" => [],
                            "benJorge"     => [],
                            "benJaime"     => [],
                            "pendiente"    => [],
                            "beneficio"    =>[],
                        ];
            $liquidacion = new \App\Liquidacion();
            if (empty($year)) {
                if ($now->copy()->format('n') >= 9) {
                    $date = new Carbon('first day of September '.$now->copy()->format('Y'));
                }else{
                    $date = new Carbon('first day of September '.$now->copy()->subYear()->format('Y'));
                }
                
            }else{
                $date = new Carbon('first day of September '.$year);
            }
            $books = \App\Book::where('start' , '>=' , $date)->where('start', '<=', $date->copy()->AddYear()->SubMonth())->where('type_book',2)->get();

            foreach ($books as $key => $book) {
                if ($key == 0) {
                    $totales["total"] = $book->total_price;
                    $totales["coste"] = $book->cost_total;
                    $totales["banco"] = $book->getPayment(2);
                    $totales["jorge"] = $book->getPayment(0);
                    $totales["jaime"] = $book->getPayment(1);
                    $totales["costeApto"] = $book->cost_apto;
                    $totales["costePark"] = $book->cost_park;
                    $totales["costeLujo"] = $book->cost_lujo;
                    $totales["costeLimp"] = $book->sup_limp;
                    $totales["costeAgencia"] = $book->pvpAgency;
                    $totales["benJorge"] = $book->total_price;
                    $totales["benJaime"] = $book->total_price;
                    $totales["pendiente"] = $book->getPayment(4);
                    $totales["limpieza"] = $book->sup_limp;
                    $totales["beneficio"] = $book->total_ben;
                }
                $totales["total"] += $book->total_price;
                $totales["coste"] += $book->cost_total;
                $totales["banco"] += $book->getPayment(2);
                $totales["jorge"] += $book->getPayment(0);
                $totales["jaime"] += $book->getPayment(1);
                $totales["costeApto"] += $book->cost_apto;
                $totales["costePark"] += $book->cost_park;
                $totales["costeLujo"] += $book->cost_lujo;
                $totales["costeLimp"] += $book->sup_limp;
                $totales["costeAgencia"] += $book->pvpAgency;
                $totales["benJorge"] += $book->total_price;
                $totales["benJaime"] += $book->total_price;
                $totales["pendiente"] += $book->getPayment(4);
                $totales["limpieza"] += $book->sup_limp;
                $totales["beneficio"] += $book->total_ben;
 
            }

            $mobile = new Mobile();
            if (!$mobile->isMobile()){
                return view('backend/sales/index',  [
                                                        'books'   => $books,
                                                        'totales' => $totales,
                                                        'temporada' => $date,
                                                    ]);
            }else{
                return view('backend/sales/index_mobile',  [
                                                        'books'   => $books,
                                                        'totales' => $totales,
                                                        'temporada' => $date,
                                                    ]);
            }
        }

    public function apto($year = "")
        {
            $now = Carbon::now();

            if (empty($year)) {
                if ($now->copy()->format('n') >= 9) {
                    $date = new Carbon('first day of September '.$now->copy()->format('Y'));
                }else{
                    $date = new Carbon('first day of September '.$now->copy()->subYear()->format('Y'));
                }
                
            }else{
                $date = new Carbon('first day of September '.$year);
            }

            $rooms = \App\Rooms::all();
            $pendientes = array();
            $apartamentos = [   
                                "room"      => [],
                                "noches"    => [],
                                "pvp"       => [],
                                "pendiente" => [],
                                "ingresos"  => [],
                                "%ben"      => [],
                                "costes"    => [],
                            ];
            $books = \App\Book::where('type_book',2)->where('start' , '>=' , $date)->where('start', '<=', $date->copy()->AddYear()->SubMonth())->get();

            foreach ($books as $key => $book) {
                if (isset($apartamentos["noches"][$book->room_id])) {
                    $apartamentos["noches"][$book->room_id]   += $book->nigths;
                    $apartamentos["pvp"][$book->room_id]      += $book->total_price;
                    $apartamentos['ingresos'][$book->room_id] += $book->total_ben;
                    $apartamentos['costes'][$book->room_id]   += $book->cost_total;
                }else{
                    $apartamentos["noches"][$book->room_id]   = $book->nigths;
                    $apartamentos["pvp"][$book->room_id]      = $book->total_price;
                    $apartamentos['ingresos'][$book->room_id] = $book->total_ben;
                    $apartamentos['costes'][$book->room_id]   = $book->cost_total;
                }
            }

            $pagos = \App\PaymentsPro::where('datePayment' , '>=' , $date)->where('datePayment', '<=', $date->copy()->AddYear()->SubMonth())->get();

            foreach ($pagos as $pago) {
                if (isset($pendientes[$pago->room_id])) {
                    $pendientes[$pago->room_id] += $pago->import;
                }else{
                    $pendientes[$pago->room_id] = $pago->import;
                }
            }
            return view('backend/sales/liquidacion_apto',[
                                                            'rooms' => $rooms,
                                                            'apartamentos' => $apartamentos,
                                                            'temporada' => $date,
                                                            'pendientes' => $pendientes,
                                                            ]);
        }
    public function perdidas()
        {
            return view ('backend/sales/perdidas_ganancias');
        }
    public function statistics()
        {
            $arrayMonth = [11 => "Noviembre",12 =>"Diciembre",1 => "Enero", 2 => "Febrero",3 => "Marzo",4=> "Abril"];
            $arrayStadisticas = array();
            $arrayYear = array();
            $años = array();
            $books = \App\Book::where('type_book',2)->where('start','<','2016-05-01')->get();
            $arrayBooks = array();

            //Para sacar las reservas por temporada
            foreach ($books as $book) {
                $fecha = Carbon::CreateFromFormat('Y-m-d',$book->start);
                
                if ($fecha->format('m')< 11 ) {
                    $año = ($fecha->format('Y')-1)."-".($fecha->format('Y'));
                }else{
                    $año = ($fecha->format('Y'))."-".($fecha->format('Y')+1);
                }
                if (isset($arrayYear[$año])) {
                    $arrayYear[$año] += $book->total_price;
                }else{
                    $arrayYear[$año] = $book->total_price;
                }

                $arrayBooks[$año][$fecha->format('n')][] = $book;

            }

            //Para sacar los nombres de la temporada
            foreach ($arrayBooks as $key => $value){
                $años[] = $key;
            }

            //Para sacar la leyenda del grafico
            for ($i=0; $i <= count($arrayBooks) ; $i++) { 
                if ($i == 0) {
                    $leyenda = "['Mes',";
                }elseif($i == count($arrayBooks)){
                    $leyenda .= "'".$años[count($arrayBooks)-1]."'],";
                }else{
                    $leyenda .= "'".$años[$i-1]."',";
                }
            }
            // // Mes
            // foreach ($arrayMonth as $key => $stats) {
            //     $arrayStadisticas[$key] = "['".$stats."',";
            // }

            // //Primer Año
            // foreach ($arrayMonth as $key => $stats) {
            //     $arrayStadisticas[$key] .= "0,";
            // }

            // //Segundo Año
            // foreach ($arrayMonth as $key => $stats) {
            //     $arrayStadisticas[$key] .= ($key+5).",";
            // }

            // //Tercer  Año
            // foreach ($arrayMonth as $key => $stats) {
            //     $arrayStadisticas[$key] .= ($key+6)."],";
            // }
            


            return view ('backend/sales/statistics',[
                                                        'meses' => $arrayMonth,
                                                        'estadisticas' => $arrayStadisticas,
                                                        'arrayYear' => $arrayYear,
                                                        'leyenda' => $leyenda,
                                                        'arrayBooks' => $arrayBooks,
                                                        'años' => $años
                                                    ]);
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
