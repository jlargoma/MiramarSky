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
        $arrayTotales = array();
        for ($i=2015; $i <= intval(date('Y')) + 1; $i++){
            $arrayTotales[$i] = 0;
        }
        foreach ($books as $book) {
            $fecha = Carbon::createFromFormat('Y-m-d',$book->start);
            $arrayTotales[$fecha->copy()->format('Y')] += $book->total_price;           
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
        

        $gastos = \App\Expenses::where('date', '>=', $inicio->copy()->format('Y-m-d'))
                                ->Where('date', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                                ->orderBy('date', 'ASC')
                                ->get();

                                

        return view ('backend/sales/gastos',  [   
                                                        'date'         => $date,
                                                        'inicio'         => $inicio,
                                                        'gastos'         => $gastos,
                                                    ]);
    }


    public function gastoCreate(Request $request){

        // echo "<pre>";
        // print_r($request->input());
        // die();
        $gasto = new \App\Expenses();
        $gasto->concept = $request->input('concept');
        $gasto->date = Carbon::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
        $gasto->import = $request->input('importe');
        $gasto->typePayment = $request->input('type_payment');
        $gasto->type = $request->input('type');
        $gasto->comment = $request->input('comment');
        if ($request->input('type_payFor') == 1) {
            $gasto->PayFor = $request->input('stringRooms');
        }

        if ($gasto->save()) {
            return "OK";
        }
        
        


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



    static function getSalesByYear($year="")
    {
        $start = new Carbon('first day of September '.$year);
        $end  = $start->copy()->addYear();

        $books = \App\Book::whereIn('type_book', [2,7,8])
                            ->where('start', '>', $start->copy()->format('Y-m-d'))
                            ->where('start', '<=', $end->copy()->format('Y-m-d'))
                            ->orderBy('start', 'ASC')
                            ->get();
        $result = ['ventas' => 0,'cobrado' => 0,'pendiente' => 0, 'metalico' => 0 , 'banco' => 0];
        foreach ($books as $key => $book) {
            $result['ventas'] += $book->total_price;

            foreach ($book->pago as $key => $pay) {
                $result['cobrado'] += $pay->import;

                if ($pay->type == 0 || $pay->type == 1) {
                    $result['metalico'] += $pay->import;
                } elseif($pay->type == 2 || $pay->type == 3) {
                    $result['banco'] += $pay->import;
                }
                
            }
        
        }

        $result['pendiente'] = ($result['ventas'] - $result['cobrado']);

        return $result;


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
