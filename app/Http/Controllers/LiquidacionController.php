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
                "total"        => 0,       
                "coste"        => 0,       
                "bancoJorge"   => 0,  
                "bancoJaime"   => 0,  
                "jorge"        => 0,       
                "jaime"        => 0,       
                "costeApto"    => 0,   
                "costePark"    => 0,   
                "costeLujo"    => 0,   
                "costeLimp"    => 0,   
                "costeAgencia" => 0,
                "benJorge"     => 0,    
                "benJaime"     => 0,    
                "pendiente"    => 0,   
                "limpieza"     => 0,    
                "beneficio"    => 0,   
                "stripe"       => 0,
                "obs"  => 0,  
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
        $books = \App\Book::where('start' , '>=' , $date)->where('start', '<=', $date->copy()->AddYear()->SubMonth())->where('type_book',2)->orderBy('start', 'ASC')->get();

        foreach ($books as $key => $book) {
            $totales["total"]        += $book->total_price;
            $totales["coste"]        += ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp + $book->extraCost);
            $totales["costeApto"]    += $book->cost_apto;
            $totales["costePark"]    += $book->cost_park;
            $totales["costeLujo"]    += $book->cost_lujo;
            $totales["costeLimp"]    += $book->cost_limp;
            $totales["costeAgencia"] += $book->PVPAgencia;
            $totales["bancoJorge"]   += $book->getPayment(2);
            $totales["bancoJaime"]   += $book->getPayment(3);
            $totales["jorge"]        += $book->getPayment(0);
            $totales["jaime"]        += $book->getPayment(1);
            $totales["benJorge"]     += $book->ben_jorge;
            $totales["benJaime"]     += $book->ben_jaime;
            $totales["pendiente"]    += $book->getPayment(4);
            $totales["limpieza"]     += $book->sup_limp;
            $totales["beneficio"]    += ($book->total_price - ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp));

            $totalStripep = 0;
            $stripePayment = \App\Payments::where('book_id', $book->id)->where('comment', 'LIKE', '%stripe%')->get(); 
            foreach ($stripePayment as $key => $stripe):
                $totalStripep +=  $stripe->import;
            endforeach;
            if ($totalStripep > 0):
                $totales["stripe"] += ((1.4 * $totalStripep)/100)+0.25;
            endif;

            $totales['obs'] += $book->extraCost;

        }


        $totBooks    = (count($books) > 0)?count($books):1;
        $diasPropios = \App\Book::where('start','>',$date->copy()->subMonth())->where('finish','<',$date->copy()->addYear())->whereIn('type_book',[7,8])->orderBy('created_at','DESC')->get();

        $countDiasPropios = 0;
        foreach ($diasPropios as $key => $book) {
            $start = Carbon::createFromFormat('Y-m-d' , $book->start);
            $finish = Carbon::createFromFormat('Y-m-d' , $book->finish);
            $countDays = $start->diffInDays($finish);

            $countDiasPropios += $countDays;
        }

        /* INDICADORES DE LA TEMPORADA */
        $data = [
                    'days-ocupation'    => 0,
                    'total-days-season' => \App\SeasonDays::first()->numDays,
                    'num-pax'           => 0,
                    'estancia-media'    => 0,
                    'pax-media'         => 0,
                    'precio-dia-media'  => 0,
                    'dias-propios'      => $countDiasPropios,
                    'agencia'           => 0,
                    'propios'           => 0,
                ];

        foreach ($books as $key => $book) {

            $start = Carbon::createFromFormat('Y-m-d' , $book->start);
            $finish = Carbon::createFromFormat('Y-m-d' , $book->finish);
            $countDays = $start->diffInDays($finish);

            /* Dias ocupados */
            $data['days-ocupation'] += $countDays;

            /* Nº inquilinos */
            $data['num-pax'] += $book->pax;


            if ($book->agency != 0) {
                $data['agencia'] ++;
            }else{
                $data['propios'] ++;
            }

        }

        $data['agencia'] = ($data['agencia']/ $totBooks)*100;
        $data['propios'] = ($data['propios']/ $totBooks)*100;

        /* Estancia media */
        $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

        /* Inquilinos media */
        $data['pax-media'] = ($data['num-pax'] / $totBooks);



        $mobile = new Mobile();
        if (!$mobile->isMobile()){
            return view('backend/sales/index',  [
                                                    'books'   => $books,
                                                    'totales' => $totales,
                                                    'temporada' => $date,
                                                    'data' => $data,
                                                ]);
        }else{
            return view('backend/sales/index',  [
                                                    'books'   => $books,
                                                    'totales' => $totales,
                                                    'temporada' => $date,
                                                    'data' => $data,
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
                            "beneficio"  => [],
                            "%ben"      => [],
                            "costes"    => [],
                        ];
        $books = \App\Book::where('type_book',2)->where('start' , '>=' , $date)->where('start', '<=', $date->copy()->AddYear()->SubMonth())->get();

        foreach ($books as $key => $book) {
            if (isset($apartamentos["noches"][$book->room_id])) {
                $apartamentos["noches"][$book->room_id]   += $book->nigths;
                $apartamentos["pvp"][$book->room_id]      += $book->total_price;
                $apartamentos['beneficio'][$book->room_id] += $book->total_ben;
                $apartamentos['costes'][$book->room_id]   += $book->cost_total;
            }else{
                $apartamentos["noches"][$book->room_id]   = $book->nigths;
                $apartamentos["pvp"][$book->room_id]      = $book->total_price;
                $apartamentos['beneficio'][$book->room_id] = $book->total_ben;
                $apartamentos['costes'][$book->room_id]   = $book->cost_total;
            }
        }

        $pagos = \App\Paymentspro::where('datePayment' , '>=' , $date)->where('datePayment', '<=', $date->copy()->AddYear()->SubMonth())->get();

        foreach ($pagos as $pago) {
            if (isset($pendientes[$pago->room_id])) {
                $pendientes[$pago->room_id] += $pago->import;
            }else{
                $pendientes[$pago->room_id] = $pago->import;
            }
        }
        return view('backend/sales/liquidacion_apto',[
                                                        'rooms'        => $rooms,
                                                        'apartamentos' => $apartamentos,
                                                        'temporada'    => $date,
                                                        'pendientes'   => $pendientes,
                                                        ]);
    }

    public function perdidas()
    {
        return view ('backend/sales/perdidas_ganancias');
    }

    public function statistics($year="")
    {
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }
        if ($date->copy()->format('n') >= 9) {
            $inicio = new Carbon('first day of September '.$date->copy()->format('Y'));
        }else{
            $inicio = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
        }


        $arrayMonth       = [11 => "Noviembre",12 =>"Diciembre",1 => "Enero", 2 => "Febrero",3 => "Marzo",4=> "Abril"];
        $arrayStadisticas = array();
        $arrayYear        = array();
        $años             = array();
        $books            = \App\Book::whereIn('type_book',[2,7,8])->get();
        $arrayBooks       = array();
        $arrayPrices      = array();
        $arrayTotal       = 0;
        $ventas           = array();
        $arrayCobro       = array();
        $arrayMetodo      = array();
        
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
                if ($fecha->copy()->format('m') <= 11 && $fecha->copy()->format('m') >= 5) {
                    $mes = 12;
                }else{
                    $mes  = $fecha->copy()->format('n');
                }

                if (isset($arrayPrices[$año][$mes][$book->room_id])) {
                    $arrayPrices[$año][$mes][$book->room_id]['total_price'] += $book->total_price;

                }else{
                    $arrayPrices[$año][$mes][$book->room_id]['total_price'] = $book->total_price;

                }

                

                $arrayBooks[$año][$mes][] = $book;
            }

        $cobros = \App\Payments::all();


        //Pasa sacar los metodos de pago
            foreach ($cobros as $key => $cobro) {
                $fecha = Carbon::CreateFromFormat('Y-m-d',$cobro->datePayment);
                
                if ($fecha->format('n')<= 8  ) {
                    $año = ($fecha->format('Y')-1)."-".($fecha->format('Y'));
                }else{
                    $año = ($fecha->format('Y'))."-".($fecha->format('Y')+1);
                }

                if (isset($arrayCobro[$año])) {
                    $arrayCobro[$año] += $cobro->import;
                }else{
                    $arrayCobro[$año] = $cobro->import;
                }
                if ($cobro->type == 0 || $cobro->type == 1) {
                    if (isset($arrayMetodo["metalico"][$año])) {
                        $arrayMetodo["metalico"][$año] += $cobro->import;
                    }else{
                        $arrayMetodo["metalico"][$año] = $cobro->import;
                    }
                }else{
                    if (isset($arrayMetodo["banco"][$año])) {
                        $arrayMetodo["banco"][$año] += $cobro->import;
                    }else{
                        $arrayMetodo["banco"][$año] = $cobro->import;
                    }
                }
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



        $books = \App\Book::whereIn('type_book', [2,7,8])->get();

        foreach ($books as $book) {
            $fecha = Carbon::createFromFormat('Y-m-d',$book->start);
            if ($fecha->copy()->format('n') >= 5 && $fecha->copy()->format('n') <= 11) {
                $mes  = 12;
            }else{
                $mes = $fecha->copy()->format('n');
            }

            if ($fecha->copy()->format('n') >= 9) {

                if(isset($arrayTotales[$fecha->copy()->format('Y')])){
                    $arrayTotales[$fecha->copy()->format('Y')] += $book->total_price;
                }else{
                    $arrayTotales[$fecha->copy()->format('Y')] = $book->total_price;
                }
                //Cuandro de ventas
                if(isset($ventas[$fecha->copy()->format('Y')][$mes]["ventas"])){
                    $ventas[$fecha->copy()->format('Y')][$mes]["ventas"] += $book->total_price;
                    $ventas[$fecha->copy()->format('Y')][$mes]["beneficio"] += $book->total_ben;
                }else{
                    $ventas[$fecha->copy()->format('Y')][$mes]["ventas"] = $book->total_price;
                    $ventas[$fecha->copy()->format('Y')][$mes]["beneficio"] = $book->total_ben;
                }
            }else{

                if(isset($arrayTotales[$fecha->copy()->subYear()->format('Y')])){
                    $arrayTotales[$fecha->copy()->subYear()->format('Y')] += $book->total_price;
                }else{
                    $arrayTotales[$fecha->copy()->subYear()->format('Y')] = $book->total_price;
                }
                //Cuandro de ventas
                if(isset($ventas[$fecha->copy()->subYear()->format('Y')][$mes]["ventas"])){
                    $ventas[$fecha->copy()->subYear()->format('Y')][$mes]["ventas"] += $book->total_price;
                    $ventas[$fecha->copy()->subYear()->format('Y')][$mes]["beneficio"] += $book->total_ben;
                }else{
                    $ventas[$fecha->copy()->subYear()->format('Y')][$mes]["ventas"] = $book->total_price;
                    $ventas[$fecha->copy()->subYear()->format('Y')][$mes]["beneficio"] = $book->total_ben;
                }
            }


        }

        $rooms = \App\Rooms::where('state',1)->orderBy('order','ASC')->get();

        $totalRoom = array();
        $totalAño = 0;

        foreach ($rooms as $room) {
            $totalRoom[$room->id] = 0;
            $reservas = \App\Book::whereIn('type_book',[2,7,8])
                                ->where('start','>=', $inicio->copy()->format('Y-m-d'))
                                ->where('start','<=', $inicio->copy()->addYear()->format('Y-m-d'))
                                ->where('room_id',$room->id)->get();

            foreach ($reservas as $reserva) {
                if (isset($totalRoom[$room->id])) {
                    $totalRoom[$room->id] += $reserva->total_price;
                }else{
                    $totalRoom[$room->id] = $reserva->total_price;

                }
                $totalAño +=$reserva->total_price ;
            }

        }


        return view ('backend/sales/statistics',[   
                                                    'rooms'        => $rooms,
                                                    'arrayTotales' => $arrayTotales,
                                                    'date'         => $date,
                                                    'meses'        => $arrayMonth,
                                                    'estadisticas' => $arrayStadisticas,
                                                    'arrayYear'    => $arrayYear,
                                                    'leyenda'      => $leyenda,
                                                    'arrayBooks'   => $arrayBooks,
                                                    'arrayPrices'  => $arrayPrices,
                                                    'arrayCobro'   => $arrayCobro,
                                                    'arrayTotal'   => $arrayTotal,
                                                    'arrayMetodo'  => $arrayMetodo,
                                                    'años'         => $años,
                                                    'totalRoom'    => $totalRoom,
                                                    'totalAño'     => $totalAño,
                                                    'inicio'       => $inicio,
                                                    'ventas'       => $ventas,

                                                ]);
    }

    public function contabilidad($year="")
    {
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }
        if ($date->copy()->format('n') >= 9) {
            $inicio = new Carbon('first day of September '.$date->copy()->format('Y'));
        }else{
            $inicio = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
        }

        $rooms = \App\Rooms::where('state',1)->orderBy('order','ASC')->get();
        $books = \App\Book::whereIn('type_book', [2,7,8])->get();

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
        $priceBookRoom = array();
        foreach ($rooms as $key => $room) {
            for ($i=2015; $i <= intval(date('Y')) + 1; $i++) { 
                
                for ($j=1; $j <= 12 ; $j++) { 
                    $priceBookRoom[$room->id][$i][$j] = 0;
                }
            }
        }
        
        foreach ($books as $key => $book) {
            $auxDate = Carbon::createFromFormat('Y-m-d', $book->start);
            if ( ! isset($priceBookRoom[$book->room->id][$auxDate->copy()->format('Y')][$auxDate->copy()->format('n')])) {
                $priceBookRoom[$book->room->id][$auxDate->copy()->format('Y')][$auxDate->copy()->format('n')] = 0; 
            }else {
                $priceBookRoom[$book->room->id][$auxDate->copy()->format('Y')][$auxDate->copy()->format('n')] += $book->total_price;
            }
            
        }
        // echo "<pre>";
        // print_r($priceBookRoom);
        // die();

        return view ('backend/sales/contabilidad',  [   
                                                        'date'         => $date,
                                                        'inicio'         => $inicio,
                                                        'arrayTotales' => $arrayTotales,
                                                        'rooms'        => $rooms,
                                                        'priceBookRoom' => $priceBookRoom,
                                                    ]);
    }

    public function gastos($year="")
    {
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }
        if ($date->copy()->format('n') >= 9) {
            $inicio = new Carbon('first day of September '.$date->copy()->format('Y'));
        }else{
            $inicio = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
        }


        $gastos = \App\Expenses::whereYear('date', '>=', $date->copy()->format('Y'))
                                ->whereYear('date', '>=', $date->copy()->addYears(1)->format('Y'))->get();

        return view ('backend/sales/gastos',  [   
                                                        'date'         => $date,
                                                        'inicio'         => $inicio,
                                                    ]);
    }


    public function ingresos($year="")
    {
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }
        if ($date->copy()->format('n') >= 9) {
            $inicio = new Carbon('first day of September '.$date->copy()->format('Y'));
        }else{
            $inicio = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
        }

        return view ('backend/sales/ingresos',  [   
                                                        'date'         => $date,
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

    public function searchByName(Request $request)
    {
        $now = Carbon::now();
        $totales = [
                    "total"        => 0,       
                    "coste"        => 0,       
                    "bancoJorge"   => 0,  
                    "bancoJaime"   => 0,  
                    "jorge"        => 0,       
                    "jaime"        => 0,       
                    "costeApto"    => 0,   
                    "costePark"    => 0,   
                    "costeLujo"    => 0,   
                    "costeLimp"    => 0,   
                    "costeAgencia" => 0,
                    "benJorge"     => 0,    
                    "benJaime"     => 0,    
                    "pendiente"    => 0,   
                    "limpieza"     => 0,    
                    "beneficio"    => 0,   
                    "stripe"       => 0,
                    "obs"          => 0,
                ];

        if ($request->year) {
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


                if ($request->searchRoom && $request->searchRoom != "all") {
                    
                    $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                                        ->where('start' , '>=' , $date->format('Y-m-d'))
                                        ->where('start', '<=', $date->copy()->AddYear()->SubMonth()->format('Y-m-d'))
                                        ->where('type_book',2)
                                        ->where('room_id', $request->searchRoom)
                                        ->orderBy('start', 'ASC')
                                        ->get();

                    $diasPropios = \App\Book::whereIn('customer_id', $arrayCustomersId)
                                            ->where('start','>',$date->copy()->subMonth())
                                            ->where('finish','<',$date->copy()->addYear())
                                            ->whereIn('type_book',[7,8])
                                            ->where('room_id', $request->searchRoom)
                                            ->orderBy('created_at','DESC')
                                            ->get();

                } else {
                    $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                                        ->where('start' , '>=' , $date->format('Y-m-d'))
                                        ->where('start', '<=', $date->copy()->AddYear()->SubMonth()->format('Y-m-d'))
                                        ->where('type_book',2)
                                        ->orderBy('start', 'ASC')
                                        ->get();

                    $diasPropios = \App\Book::whereIn('customer_id', $arrayCustomersId)
                                            ->where('start','>',$date->copy()->subMonth())
                                            ->where('finish','<',$date->copy()->addYear())
                                            ->whereIn('type_book',[7,8])
                                            ->orderBy('created_at','DESC')
                                            ->get();

                }
                

                foreach ($books as $key => $book) {

                    $totales["total"]        += $book->total_price;
                    $totales["coste"]        += ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp);
                    $totales["costeApto"]    += $book->cost_apto;
                    $totales["costePark"]    += $book->cost_park;
                    $totales["costeLujo"]    += $book->cost_lujo;
                    $totales["costeLimp"]    += $book->cost_limp;
                    $totales["costeAgencia"] += $book->PVPAgencia;
                    $totales["bancoJorge"]   += $book->getPayment(2);
                    $totales["bancoJaime"]   += $book->getPayment(3);
                    $totales["jorge"]        += $book->getPayment(0);
                    $totales["jaime"]        += $book->getPayment(1);
                    $totales["benJorge"]     += $book->ben_jorge;
                    $totales["benJaime"]     += $book->ben_jaime;
                    $totales["pendiente"]    += $book->getPayment(4);
                    $totales["limpieza"]     += $book->sup_limp;
                    $totales["beneficio"]    += ($book->total_price - ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp));

                    $totalStripep = 0;
                    $stripePayment = \App\Payments::where('book_id', $book->id)->where('comment', 'LIKE', '%stripe%')->get(); 
                    foreach ($stripePayment as $key => $stripe):
                        $totalStripep +=  $stripe->import;
                    endforeach;
                    if ($totalStripep > 0):
                        $totales["stripe"] += ((1.4 * $totalStripep)/100)+0.25;
                    endif;

                    $totales['obs'] += $book->extraCost;
                
                }

                $totBooks    = (count($books) > 0)?count($books):1;
                $countDiasPropios = 0;
                foreach ($diasPropios as $key => $book) {
                    $start = Carbon::createFromFormat('Y-m-d' , $book->start);
                    $finish = Carbon::createFromFormat('Y-m-d' , $book->finish);
                    $countDays = $start->diffInDays($finish);

                    $countDiasPropios += $countDays;
                }

                /* INDICADORES DE LA TEMPORADA */
                $data = [
                            'days-ocupation'    => 0,
                            'total-days-season' => \App\SeasonDays::first()->numDays,
                            'num-pax'           => 0,
                            'estancia-media'    => 0,
                            'pax-media'         => 0,
                            'precio-dia-media'  => 0,
                            'dias-propios'      => $countDiasPropios,
                            'agencia'           => 0,
                            'propios'           => 0,
                        ];

                foreach ($books as $key => $book) {

                    $start = Carbon::createFromFormat('Y-m-d' , $book->start);
                    $finish = Carbon::createFromFormat('Y-m-d' , $book->finish);
                    $countDays = $start->diffInDays($finish);

                    /* Dias ocupados */
                    $data['days-ocupation'] += $countDays;

                    /* Nº inquilinos */
                    $data['num-pax'] += $book->pax;


                    if ($book->agency != 0) {
                        $data['agencia'] ++;
                    }else{
                        $data['propios'] ++;
                    }

                }

                $data['agencia'] = ($data['agencia']/ $totBooks)*100;
                $data['propios'] = ($data['propios']/ $totBooks)*100;

                /* Estancia media */
                $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

                /* Inquilinos media */
                $data['pax-media'] = ($data['num-pax'] / $totBooks);


                return view('backend/sales/_tableSummary',  [
                                                        'books'   => $books,
                                                        'totales' => $totales,
                                                        'data' => $data
                                                    ]);
            }else{
                return "<h2>No hay reservas para este término '".$request->searchString."'</h2>";
            }
        }else{

            if ($request->searchRoom && $request->searchRoom != "all") {

                $books = \App\Book::where('start' , '>=' , $date)
                                ->where('start', '<=', $date->copy()->AddYear()->SubMonth())
                                ->where('type_book', 2)
                                ->where('room_id', $request->searchRoom)
                                ->orderBy('start', 'ASC')
                                ->get();

                $diasPropios = \App\Book::where('start','>',$date->copy()->subMonth())
                                        ->where('room_id', $request->searchRoom)
                                        ->where('finish','<',$date->copy()->addYear())
                                        ->whereIn('type_book',[7,8])
                                        ->orderBy('created_at','DESC')
                                        ->get();
            } else {
                $books = \App\Book::where('start' , '>=' , $date)
                                    ->where('start', '<=', $date->copy()->AddYear()->SubMonth())
                                    ->where('type_book', 2)
                                    ->orderBy('start', 'ASC')
                                    ->get();

                $diasPropios = \App\Book::where('start','>',$date->copy()->subMonth())
                                            ->where('finish','<',$date->copy()->addYear())
                                            ->whereIn('type_book',[7,8])
                                            ->orderBy('created_at','DESC')
                                            ->get();
            }
            

            

            foreach ($books as $key => $book) {
                $totales["total"]        += $book->total_price;
                $totales["coste"]        += ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp);
                $totales["costeApto"]    += $book->cost_apto;
                $totales["costePark"]    += $book->cost_park;
                $totales["costeLujo"]    += $book->cost_lujo;
                $totales["costeLimp"]    += $book->cost_limp;
                $totales["costeAgencia"] += $book->PVPAgencia;
                $totales["bancoJorge"]   += $book->getPayment(2);
                $totales["bancoJaime"]   += $book->getPayment(3);
                $totales["jorge"]        += $book->getPayment(0);
                $totales["jaime"]        += $book->getPayment(1);
                $totales["benJorge"]     += $book->ben_jorge;
                $totales["benJaime"]     += $book->ben_jaime;
                $totales["pendiente"]    += $book->getPayment(4);
                $totales["limpieza"]     += $book->sup_limp;
                $totales["beneficio"]    += ($book->total_price - ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp));

                $totalStripep = 0;
                $stripePayment = \App\Payments::where('book_id', $book->id)->where('comment', 'LIKE', '%stripe%')->get(); 
                foreach ($stripePayment as $key => $stripe):
                    $totalStripep +=  $stripe->import;
                endforeach;
                if ($totalStripep > 0):
                    $totales["stripe"] += ((1.4 * $totalStripep)/100)+0.25;
                endif;
            
                $totales['obs'] += $book->extraCost;
            }
            $totBooks    = (count($books) > 0)?count($books):1;
            $countDiasPropios = 0;
            foreach ($diasPropios as $key => $book) {
                $start = Carbon::createFromFormat('Y-m-d' , $book->start);
                $finish = Carbon::createFromFormat('Y-m-d' , $book->finish);
                $countDays = $start->diffInDays($finish);

                $countDiasPropios += $countDays;
            }

            /* INDICADORES DE LA TEMPORADA */
            $data = [
                        'days-ocupation'    => 0,
                        'total-days-season' => \App\SeasonDays::first()->numDays,
                        'num-pax'           => 0,
                        'estancia-media'    => 0,
                        'pax-media'         => 0,
                        'precio-dia-media'  => 0,
                        'dias-propios'      => $countDiasPropios,
                        'agencia'           => 0,
                        'propios'           => 0,
                    ];

            foreach ($books as $key => $book) {

                $start = Carbon::createFromFormat('Y-m-d' , $book->start);
                $finish = Carbon::createFromFormat('Y-m-d' , $book->finish);
                $countDays = $start->diffInDays($finish);

                /* Dias ocupados */
                $data['days-ocupation'] += $countDays;

                /* Nº inquilinos */
                $data['num-pax'] += $book->pax;


                if ($book->agency != 0) {
                    $data['agencia'] ++;
                }else{
                    $data['propios'] ++;
                }

            }

            $data['agencia'] = ($data['agencia']/ $totBooks)*100;
            $data['propios'] = ($data['propios']/ $totBooks)*100;

            /* Estancia media */
            $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

            /* Inquilinos media */
            $data['pax-media'] = ($data['num-pax'] / $totBooks);


            return view('backend/sales/_tableSummary',  [
                                                    'books'   => $books,
                                                    'totales' => $totales,
                                                    'data' => $data
                                                ]);

        }  
    }

    public function searchByRoom(Request $request)
    {
        $now = Carbon::now();
        $totales = [
                    "total"        => 0,       
                    "coste"        => 0,       
                    "bancoJorge"   => 0,  
                    "bancoJaime"   => 0,  
                    "jorge"        => 0,       
                    "jaime"        => 0,       
                    "costeApto"    => 0,   
                    "costePark"    => 0,   
                    "costeLujo"    => 0,   
                    "costeLimp"    => 0,   
                    "costeAgencia" => 0,
                    "benJorge"     => 0,    
                    "benJaime"     => 0,    
                    "pendiente"    => 0,   
                    "limpieza"     => 0,    
                    "beneficio"    => 0, 
                    "stripe"       => 0,
                    "obs"  => 0, 
                ];

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

                if ( $request->searchRoom && $request->searchRoom != "all" ) {

                    $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                                    ->where('start' , '>=' , $date->format('Y-m-d'))
                                    ->where('start', '<=', $date->copy()->AddYear()->SubMonth()->format('Y-m-d'))
                                    ->where('type_book',2)
                                    ->where('room_id',$request->searchRoom)
                                    ->orderBy('start', 'ASC')
                                    ->get();

                    $diasPropios = \App\Book::whereIn('customer_id', $arrayCustomersId)
                                            ->where('start','>',$date->copy()->subMonth())
                                            ->where('finish','<',$date->copy()->addYear())
                                            ->whereIn('type_book',[7,8])
                                            ->where('room_id', $request->searchRoom)
                                            ->orderBy('created_at','DESC')
                                            ->get();

                }else{
                    $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                                    ->where('start' , '>=' , $date->format('Y-m-d'))
                                    ->where('start', '<=', $date->copy()->AddYear()->SubMonth()->format('Y-m-d'))
                                    ->where('type_book',2)
                                    ->orderBy('start', 'ASC')
                                    ->get();

                    $diasPropios = \App\Book::whereIn('customer_id', $arrayCustomersId)
                                            ->where('start','>',$date->copy()->subMonth())
                                            ->where('finish','<',$date->copy()->addYear())
                                            ->whereIn('type_book',[7,8])
                                            ->orderBy('created_at','DESC')
                                            ->get();
                }

                


                foreach ($books as $key => $book) {

                    $totales["total"]        += $book->total_price;
                    $totales["coste"]        += ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp);
                    $totales["costeApto"]    += $book->cost_apto;
                    $totales["costePark"]    += $book->cost_park;
                    $totales["costeLujo"]    += $book->cost_lujo;
                    $totales["costeLimp"]    += $book->cost_limp;
                    $totales["costeAgencia"] += $book->PVPAgencia;
                    $totales["bancoJorge"]   += $book->getPayment(2);
                    $totales["bancoJaime"]   += $book->getPayment(3);
                    $totales["jorge"]        += $book->getPayment(0);
                    $totales["jaime"]        += $book->getPayment(1);
                    $totales["benJorge"]     += $book->ben_jorge;
                    $totales["benJaime"]     += $book->ben_jaime;
                    $totales["pendiente"]    += $book->getPayment(4);
                    $totales["limpieza"]     += $book->sup_limp;
                    $totales["beneficio"]    += ($book->total_price - ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp));
                    $totalStripep = 0;
                    $stripePayment = \App\Payments::where('book_id', $book->id)->where('comment', 'LIKE', '%stripe%')->get(); 
                    foreach ($stripePayment as $key => $stripe):
                        $totalStripep +=  $stripe->import;
                    endforeach;
                    if ($totalStripep > 0):
                        $totales["stripe"] += ((1.4 * $totalStripep)/100)+0.25;
                    endif;
                
                    $totales['obs'] += $book->extraCost;
                }

                $totBooks    = (count($books) > 0)?count($books):1;
                $countDiasPropios = 0;
                foreach ($diasPropios as $key => $book) {
                    $start = Carbon::createFromFormat('Y-m-d' , $book->start);
                    $finish = Carbon::createFromFormat('Y-m-d' , $book->finish);
                    $countDays = $start->diffInDays($finish);

                    $countDiasPropios += $countDays;
                }

                /* INDICADORES DE LA TEMPORADA */
                $data = [
                            'days-ocupation'    => 0,
                            'total-days-season' => \App\SeasonDays::first()->numDays,
                            'num-pax'           => 0,
                            'estancia-media'    => 0,
                            'pax-media'         => 0,
                            'precio-dia-media'  => 0,
                            'dias-propios'      => $countDiasPropios,
                            'agencia'           => 0,
                            'propios'           => 0,
                        ];

                foreach ($books as $key => $book) {

                    $start = Carbon::createFromFormat('Y-m-d' , $book->start);
                    $finish = Carbon::createFromFormat('Y-m-d' , $book->finish);
                    $countDays = $start->diffInDays($finish);

                    /* Dias ocupados */
                    $data['days-ocupation'] += $countDays;

                    /* Nº inquilinos */
                    $data['num-pax'] += $book->pax;


                    if ($book->agency != 0) {
                        $data['agencia'] ++;
                    }else{
                        $data['propios'] ++;
                    }

                }

                $data['agencia'] = ($data['agencia']/ $totBooks)*100;
                $data['propios'] = ($data['propios']/ $totBooks)*100;

                /* Estancia media */
                $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

                /* Inquilinos media */
                $data['pax-media'] = ($data['num-pax'] / $totBooks);


                return view('backend/sales/_tableSummary',  [
                                                        'books'   => $books,
                                                        'totales' => $totales,
                                                        'data' => $data,
                                                    ]);
            }else{
                return "<h2>No hay reservas para este término '".$request->searchString."'</h2>";
            }
        }else{


            if ($request->searchRoom && $request->searchRoom != "all") {
                    
                    $books = \App\Book::where('start' , '>=' , $date)
                                        ->where('start', '<=', $date->copy()->AddYear()->SubMonth())
                                        ->where('type_book',2)
                                        ->where('room_id',$request->searchRoom)
                                        ->orderBy('start', 'ASC')
                                        ->get();

                    $diasPropios = \App\Book::where('start','>',$date->copy()->subMonth())
                                        ->where('room_id', $request->searchRoom)
                                        ->where('finish','<',$date->copy()->addYear())
                                        ->whereIn('type_book',[7,8])
                                        ->orderBy('created_at','DESC')
                                        ->get();



                }else{
                    $books = \App\Book::where('start' , '>=' , $date)
                                        ->where('start', '<=', $date->copy()->AddYear()->SubMonth())
                                        ->where('type_book',2)
                                        ->orderBy('start', 'ASC')
                                        ->get();

                    $diasPropios = \App\Book::where('start','>',$date->copy()->subMonth())
                                            ->where('finish','<',$date->copy()->addYear())
                                            ->whereIn('type_book',[7,8])
                                            ->orderBy('created_at','DESC')
                                            ->get();


                }

            foreach ($books as $key => $book) {
                $totales["total"]        += $book->total_price;
                $totales["coste"]        += ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp);
                $totales["costeApto"]    += $book->cost_apto;
                $totales["costePark"]    += $book->cost_park;
                $totales["costeLujo"]    += $book->cost_lujo;
                $totales["costeLimp"]    += $book->cost_limp;
                $totales["costeAgencia"] += $book->PVPAgencia;
                $totales["bancoJorge"]   += $book->getPayment(2);
                $totales["bancoJaime"]   += $book->getPayment(3);
                $totales["jorge"]        += $book->getPayment(0);
                $totales["jaime"]        += $book->getPayment(1);
                $totales["benJorge"]     += $book->ben_jorge;
                $totales["benJaime"]     += $book->ben_jaime;
                $totales["pendiente"]    += $book->getPayment(4);
                $totales["limpieza"]     += $book->sup_limp;
                $totales["beneficio"]    += ($book->total_price - ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp));
                $totalStripep = 0;
                $stripePayment = \App\Payments::where('book_id', $book->id)->where('comment', 'LIKE', '%stripe%')->get(); 
                foreach ($stripePayment as $key => $stripe):
                    $totalStripep +=  $stripe->import;
                endforeach;
                if ($totalStripep > 0):
                    $totales["stripe"] += ((1.4 * $totalStripep)/100)+0.25;
                endif;
            
                $totales['obs'] += $book->extraCost;
            }
            $totBooks    = (count($books) > 0)?count($books):1;
            $countDiasPropios = 0;
            foreach ($diasPropios as $key => $book) {
                $start = Carbon::createFromFormat('Y-m-d' , $book->start);
                $finish = Carbon::createFromFormat('Y-m-d' , $book->finish);
                $countDays = $start->diffInDays($finish);

                $countDiasPropios += $countDays;
            }

            /* INDICADORES DE LA TEMPORADA */
            $data = [
                        'days-ocupation'    => 0,
                        'total-days-season' => \App\SeasonDays::first()->numDays,
                        'num-pax'           => 0,
                        'estancia-media'    => 0,
                        'pax-media'         => 0,
                        'precio-dia-media'  => 0,
                        'dias-propios'      => $countDiasPropios,
                        'agencia'           => 0,
                        'propios'           => 0,
                    ];

            foreach ($books as $key => $book) {

                $start = Carbon::createFromFormat('Y-m-d' , $book->start);
                $finish = Carbon::createFromFormat('Y-m-d' , $book->finish);
                $countDays = $start->diffInDays($finish);

                /* Dias ocupados */
                $data['days-ocupation'] += $countDays;

                /* Nº inquilinos */
                $data['num-pax'] += $book->pax;


                if ($book->agency != 0) {
                    $data['agencia'] ++;
                }else{
                    $data['propios'] ++;
                }

            }

            $data['agencia'] = ($data['agencia']/ $totBooks)*100;
            $data['propios'] = ($data['propios']/ $totBooks)*100;

            /* Estancia media */
            $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

            /* Inquilinos media */
            $data['pax-media'] = ($data['num-pax'] / $totBooks);


            return view('backend/sales/_tableSummary',  [
                                                    'books'   => $books,
                                                    'totales' => $totales,
                                                    'data' => $data
                                                ]);

        }
    }
}
