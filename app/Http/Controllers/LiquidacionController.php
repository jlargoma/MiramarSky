<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use \DB;
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
        if ( empty($year) ) {
            $date = Carbon::now();
            if ($date->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$date->copy()->format('Y'));
            }else{
                $date = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
            }
            
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }

        $date = new Carbon('first day of September '.$date->copy()->format('Y'));
        
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
                                                    'percentBenef' => DB::table('percent')->find(1)->percent,
                                                ]);
        }else{
            return view('backend/sales/index',  [
                                                    'books'   => $books,
                                                    'totales' => $totales,
                                                    'temporada' => $date,
                                                    'data' => $data,
                                                    'percentBenef' => DB::table('percent')->find(1)->percent,
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
                                                        'percentBenef' => DB::table('percent')->find(1)->percent,
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
                                ->orderBy('date', 'DESC')
                                ->get();

        $books = \App\Book::whereIn('type_book', [2])
                            ->where('start', '>', $inicio->copy()->format('Y-m-d'))
                            ->where('start', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                            ->orderBy('start', 'ASC')
                            ->get();
        $totalStripep = 0;
        foreach ($books as $key => $book) {
            
            if (count($book->pago) > 0) {
                foreach ($book->pago as $key => $pay) {
                    if ($pay->comment == 'Pago desde stripe') {
                        $totalStripep +=  (((1.4 * $pay->import)/100)+0.25);
                    }
                    
                }
            }

        }

                                

        return view ('backend/sales/gastos/gastos',  [   
                                                        'date'         => $date,
                                                        'inicio'         => $inicio,
                                                        'gastos'         => $gastos,
                                                        'totalStripep'         => $totalStripep,
                                                    ]);
    }

    public function gastoCreate(Request $request)
    {

    
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
        if ($request->input('type_payment') == 1 || $request->input('type_payment') == 2) {

            $data['concept'] = ( $request->input('type_payment') == 1 )? 'GASTO METALICO JAIME':'GASTO METALICO JORGE';
            $data['date'] = Carbon::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
            $data['import'] = $request->input('importe');
            $data['comment'] = $request->input('comment');
            $data['typePayment'] = ($request->input('type_payment') == 1)? 1: 0;
            $data['type'] = 1;

            $this->addCashbox($data);
        }
        if ($gasto->save()) {
            return "OK";
        }
    }

    public function updateGasto(Request $request, $id)
    {
        $gasto = \App\Expenses::find($id);
        $gasto->concept = $request->concept;
        $gasto->typePayment = $request->typePayment;
        $gasto->type = $request->type;
        $gasto->comment = $request->comment;

        if ($gasto->save()) {
            return "OK";
        }
    }


    public function getTableGastos($year='')
    {
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }
        
        $inicio = new Carbon('first day of September '.$year->format('Y'));
        

        $gastos = \App\Expenses::where('date', '>=', $inicio->copy()->format('Y-m-d'))
                                ->Where('date', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                                ->orderBy('date', 'DESC')
                                ->get();
                                
        $books = \App\Book::whereIn('type_book', [2])
                            ->where('start', '>', $inicio->copy()->format('Y-m-d'))
                            ->where('start', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                            ->orderBy('start', 'ASC')
                            ->get();

        $totalStripep = 0;
        foreach ($books as $key => $book) {
            
            if (count($book->pago) > 0) {
                foreach ($book->pago as $key => $pay) {
                    $totalStripep +=  (((1.4 * $pay->import)/100)+0.25);
                }
            }

        }                  

        return view ('backend/sales/gastos/_tableExpenses',  [
                                                        'gastos'         => $gastos,
                                                        'totalStripep'         => $totalStripep,
                                                    ]);
    }


    public function ingresos($year="")
    {
        if ( empty($year) ) {
            $date = Carbon::now();
            if ($date->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$date->copy()->format('Y'));
            }else{
                $date = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
            }
            
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }

        $inicio = new Carbon('first day of September '.$date->copy()->format('Y'));


        $books = \App\Book::whereIn('type_book', [2])
                            ->where('start', '>', $inicio->copy()->format('Y-m-d'))
                            ->where('start', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                            ->get();

        $arrayTotales = ['totales' => 0, 'meses' => []];
        for ($i=1; $i <=12 ; $i++) { 
              $arrayTotales['meses'][$i] = 0;
        }
        foreach ($books as $book) {
            $fecha = Carbon::createFromFormat('Y-m-d',$book->start);
            $arrayTotales['meses'][$fecha->copy()->format('n')] += $book->total_price;    

            $arrayTotales['totales'] += $book->total_price;           
        }
        $arrayIncomes = array();
        $conceptIncomes = ['INGRESOS EXTRAORDINARIOS', 'RAPPEL CLOSES', 'RAPPEL FORFAITS', 'RAPPEL ALQUILER MATERIAL'];

        foreach ($conceptIncomes as $typeIncome) {
            for ($i=1; $i <= 12 ; $i++) { 
                $arrayIncomes[$typeIncome][$i] = 0;
            }
        }
        foreach ($conceptIncomes as $typeIncome) {
            $incomes = \App\Incomes::where('concept', $typeIncome)
                                    ->where('date', '>', $inicio->copy()->format('Y-m-d'))
                                    ->where('date', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                                    ->get();

            if ( count($incomes) > 0) {
                
                foreach ($incomes as $key => $income) {
                    $fecha = Carbon::createFromFormat('Y-m-d',$income->date);
                    $arrayIncomes[$typeIncome][$fecha->copy()->format('n')] += $income->import;    
                }
            } else {
                for ($i=1; $i <= 12 ; $i++) { 
                    $arrayIncomes[$typeIncome][$i] = 0;
                }
            }
            

        }
        // echo "<pre>";
        // print_r($arrayIncomes);
        // die();

        return view ('backend/sales/ingresos/ingresos',  [   
                                                        'inicio'         => $inicio,
                                                        'arrayTotales'         => $arrayTotales,
                                                        'incomes'         => $arrayIncomes,
                                                    ]);
    }


    public function ingresosCreate(Request $request)
    {
        $ingreso = new \App\Incomes();
        $ingreso->concept = $request->input('concept');
        $ingreso->date = Carbon::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
        $ingreso->import = $request->input('import');
        if ($ingreso->save()) {
            return redirect('/admin/ingresos');
        }
    }


    public function caja($year="")
    {
        if ( empty($year) ) {
            $date = Carbon::now();
            if ($date->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$date->copy()->format('Y'));
            }else{
                $date = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
            }
            
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }

        $inicio = new Carbon('first day of September '.$date->copy()->format('Y'));

        $cashJaime = \App\Cashbox::where('typePayment', 1)
                                    ->where('date', '>=', $inicio->copy()->format('Y-m-d'))
                                    ->where('date', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                                    ->orderBy('date', 'ASC')
                                    ->get();
        $saldoInicial = \App\Cashbox::where('concept', 'SALDO INICIAL')->where('typePayment', 1)->first();

        // $cashJorge = \App\Cashbox::where('typePayment', 0)
        //                             ->where('date', '>', $inicio->copy()->format('Y-m-d'))
        //                             ->where('date', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
        //                             ->get();

        return view('backend.sales.cashbox.cashbox', [
                                                        'inicio'    => $inicio, 
                                                        'cashJaime' => $cashJaime, 
                                                        'saldoInicial' => $saldoInicial, 
                                                    ]);
    }


    public function getTableMoves($year, $type)
    {
        if ( empty($year) ) {
            $date = Carbon::now();
            if ($date->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$date->copy()->format('Y'));
            }else{
                $date = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
            }
            
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }

        $inicio = new Carbon('first day of September '.$date->copy()->format('Y'));
        if ($type == 'jaime') {

            $cashbox = \App\Cashbox::where('typePayment', 1)
                                    ->where('date', '>=', $inicio->copy()->format('Y-m-d'))
                                    ->where('date', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                                    ->orderBy('date', 'ASC')
                                    ->get();
            $saldoInicial = \App\Cashbox::where('concept', 'SALDO INICIAL')->where('typePayment', 1)->first();

        }else{
            $cashbox = \App\Cashbox::where('typePayment', 0)
                                        ->where('date', '>=', $inicio->copy()->format('Y-m-d'))
                                        ->where('date', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                                        ->orderBy('date', 'ASC')
                                        ->get();

            $saldoInicial = \App\Cashbox::where('concept', 'SALDO INICIAL')->where('typePayment', 0)->first();

        }
        return view('backend.sales.cashbox._tableMoves', [
                                                        'cashbox'         => $cashbox, 
                                                        'saldoInicial'    => $saldoInicial, 
                                                    ]);
    }


    static function addCashbox($data)
    {
        
        $cashbox = new \App\Cashbox();
        $cashbox->concept = $data['concept'];
        $cashbox->date = Carbon::createFromFormat('Y-m-d', $data['date']);
        $cashbox->import = $data['import'];
        $cashbox->comment = $data['comment'];
        $cashbox->typePayment = $data['typePayment'];
        $cashbox->type = $data['type'];
        if ($cashbox->save()) {
            return true;
        }else{
            return false;
        }

    }

    static function addBank($data)
    {
        
        $bank = new \App\Bank();
        $bank->concept = $data['concept'];
        $bank->date = Carbon::createFromFormat('Y-m-d', $data['date']);
        $bank->import = $data['import'];
        $bank->comment = $data['comment'];
        $bank->typePayment = $data['typePayment'];
        $bank->type = $data['type'];
        if ($bank->save()) {
            return true;
        }else{
            return false;
        }

    }



    public function perdidasGanancias($year="")
    {
        if ( empty($year) ) {
            $date = Carbon::now();
            if ($date->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$date->copy()->format('Y'));
            }else{
                $date = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
            }
            
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }

        $inicio = new Carbon('first day of September '.$date->copy()->format('Y'));

        $books = \App\Book::whereIn('type_book', [2])
                            ->where('start', '>', $inicio->copy()->format('Y-m-d'))
                            ->where('start', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                            ->get();
        /* INGRESOS */
        $arrayTotales = ['totales' => 0, 'meses' => []];

        $arrayExpensesPending = ['PAGO PROPIETARIO' => [], 'AGENCIAS' => [], 'STRIPE' => [], 'LIMPIEZA' => [], 'LAVANDERIA' => []];

        for ($i=1; $i <=12 ; $i++) { 
            $arrayTotales['meses'][$i] = 0;

            $arrayExpensesPending['PAGO PROPIETARIO'][$i] = 0;
            $arrayExpensesPending['AGENCIAS'][$i] = 0;
            $arrayExpensesPending["STRIPE"][$i] = 0;

            $arrayExpensesPending['LIMPIEZA'][$i] = 0;
            $arrayExpensesPending['LAVANDERIA'][$i] = 0;
        }

        

        foreach ($books as $book) {
            $fecha = Carbon::createFromFormat('Y-m-d',$book->start);
            $arrayTotales['meses'][$fecha->copy()->format('n')] += $book->total_price;    
            $arrayTotales['totales'] += $book->total_price;        


            $arrayExpensesPending['PAGO PROPIETARIO'][$fecha->copy()->format('n')] += ($book->cost_apto + $book->cost_park + $book->cost_lujo);
            $arrayExpensesPending['AGENCIAS'][$fecha->copy()->format('n')] += $book->PVPAgencia;
            if (count($book->pago) > 0) {
                foreach ($book->pago as $key => $pay) {
                    if ($pay->comment == 'Pago desde stripe') {
                        // $arrayExpensesPending["STRIPE"][$fecha->copy()->format('n')] += ((1.4 * $book->total_price)/100)+0.25;
                        $arrayExpensesPending["STRIPE"][$fecha->copy()->format('n')] +=  (((1.4 * $pay->import)/100)+0.25);
                    }
                    
                }
            }
            

            $arrayExpensesPending['LIMPIEZA'][$fecha->copy()->format('n')] += ($book->cost_limp - 10);
            $arrayExpensesPending['LAVANDERIA'][$fecha->copy()->format('n')] += 10;
            
            //

        }
        $arrayIncomes = array();
        $conceptIncomes = ['INGRESOS EXTRAORDINARIOS', 'RAPPEL CLOSES', 'RAPPEL FORFAITS', 'RAPPEL ALQUILER MATERIAL'];

        foreach ($conceptIncomes as $typeIncome) {
            for ($i=1; $i <= 12 ; $i++) { 
                $arrayIncomes[$typeIncome][$i] = 0;
            }
        }
        foreach ($conceptIncomes as $typeIncome) {
            $incomes = \App\Incomes::where('concept', $typeIncome)
                                    ->where('date', '>', $inicio->copy()->format('Y-m-d'))
                                    ->where('date', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                                    ->get();

            if ( count($incomes) > 0) {
                
                foreach ($incomes as $key => $income) {
                    $fecha = Carbon::createFromFormat('Y-m-d',$income->date);
                    $arrayIncomes[$typeIncome][$fecha->copy()->format('n')] += $income->import;    
                }
            } else {
                for ($i=1; $i <= 12 ; $i++) { 
                    $arrayIncomes[$typeIncome][$i] = 0;
                }
            }
        }
        /* FIN INGRESOS */


        $conceptExpenses = 
                            [
                            'PAGO PROPIETARIO',
                            'AGENCIAS',
                            'STRIPE',
                            'SERVICIOS PROF INDEPENDIENTES',
                            'VARIOS',
                            'REGALO BIENVENIDA',
                            'LAVANDERIA',
                            'LIMPIEZA',
                            'EQUIPAMIENTO VIVIENDA',
                            'DECORACION',
                            'MENAJE',
                            'SABANAS Y TOALLAS',
                            'IMPUESTOS',
                            'GASTOS BANCARIOS',
                            'MARKETING Y PUBLICIDAD',
                            'REPARACION Y CONSERVACION',
                            'SUELDOS Y SALARIOS',
                            'SEG SOCIALES',
                            'MENSAJERIA',
                            'COMISIONES COMERCIALES'
                        ];

        /* GASTOS */
        for ($i=1; $i <= 12; $i++) { 
            for ($j=0; $j < count($conceptExpenses); $j++) { 
                $arrayExpenses[$conceptExpenses[$j]][$i] = 0;
            }
            
        }

        $gastos = \App\Expenses::where('date', '>', $inicio->copy()->format('Y-m-d'))
                                ->Where('date', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                                ->orderBy('date', 'DESC')
                                ->get();

        foreach ($gastos as $key => $gasto) {

            $fecha = Carbon::createFromFormat('Y-m-d',$gasto->date);
            $arrayExpenses[$gasto->type][$fecha->copy()->format('n')] += $gasto->import;    

        }

        // for ($i=1; $i <= 12; $i++) { 
        //     $arrayExpenses['PAGO PROPIETARIO'][$i] += $arrayExpensesPending['PAGO PROPIETARIO'][$i];
        //     $arrayExpenses['AGENCIAS'][$i] += $arrayExpensesPending['AGENCIAS'][$i];
        //     $arrayExpenses['STRIPE'][$i] += $arrayExpensesPending['STRIPE'][$i];
        //     $arrayExpenses['LIMPIEZA'][$i] += $arrayExpensesPending['LIMPIEZA'][$i];
        //     $arrayExpenses['LAVANDERIA'][$i] += $arrayExpensesPending['LAVANDERIA'][$i];
        // }

        /* FIN GASTOS */

        // echo "<pre>";
        // print_r($arrayExpenses);
        // die();

        return view ('backend/sales/perdidas_ganancias', [
                                                            'arrayTotales' => $arrayTotales, 
                                                            'arrayIncomes' => $arrayIncomes, 
                                                            'arrayExpenses' => $arrayExpenses, 
                                                            'inicio' => $inicio,
                                                            'arrayExpensesPending' => $arrayExpensesPending
                                                        ]);
    }



    static function getSalesByYear($year="")
    {
        // $array = [0 =>"Metalico Jorge", 1 =>"Metalico Jaime",2 =>"Banco Jorge",3=>"Banco Jaime"];
        if ($year == "") {
            $year = date('Y');
        }
        $start = new Carbon('first day of September '.$year);
        $end  = $start->copy()->addYear();

        $books = \App\Book::whereIn('type_book', [2])
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

    static function getSalesByYearByRoom($year="", $room="all")
    {
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }
        if ($date->copy()->format('n') >= 9) {
            $start = new Carbon('first day of September '.$date->copy()->format('Y'));
        }else{
            $start = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
        }

        $end  = $start->copy()->addYear();

        if ($room == "all") {
            $rooms = \App\Rooms::where('state', 1)->get(['id']);
            $books = \App\Book::whereIn('type_book', [2])
                            ->whereIn('room_id', $rooms)
                            ->where('start', '>=', $start->copy()->format('Y-m-d'))
                            ->where('start', '<=', $end->copy()->format('Y-m-d'))
                            ->orderBy('start', 'ASC')
                            ->get();

            $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                                ->Where('date', '<=', $end->copy()->format('Y-m-d'))
                                ->orderBy('date', 'DESC')
                                ->get();

        }else{

            $books = \App\Book::whereIn('type_book', [2])
                        ->where('room_id', $room)
                        ->where('start', '>=', $start->copy()->format('Y-m-d'))
                        ->where('start', '<=', $end->copy()->format('Y-m-d'))
                        ->orderBy('start', 'ASC')
                        ->get();

            $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                                ->Where('date', '<=', $end->copy()->format('Y-m-d'))
                                ->Where('PayFor', 'LIKE', '%'.$room.'%')
                                ->orderBy('date', 'DESC')
                                ->get();
        }
        
        // $result = ['ventas' => 0,'cobrado' => 0,'pendiente' => 0, 'metalico' => 0 , 'banco' => 0];
        $total = 0;
        $apto = 0;
        $park = 0;
        $lujo = 0;
        $metalico = 0;
        $banco = 0;
        foreach ($gastos as $gasto) {

            if ($gasto->type == 0 || $gasto->type == 1) {
                $metalico += $gasto->import;
            } elseif($gasto->type == 2 || $gasto->type == 3) {
                $banco += $gasto->import;
            }
        }
        $total += ( $apto + $park + $lujo);

        return [
                'total' => $total,
                'apto' => $apto,
                'park' => $park,
                'lujo' => $lujo,
                'room' => $room,
                'banco' => $banco,
                'metalico' => $metalico,
                'pagado' => $gastos->sum('import'),
            ];


    }

    public function getHojaGastosByRoom($year="", $id)
    {
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }
        $start = new Carbon('first day of September '.$date->copy()->format('Y'));
        
        // return $start;
        $end  = $start->copy()->addYear();
        if ($id != "all") {
            $room = \App\Rooms::find($id);
            $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                                ->Where('date', '<=', $end->copy()->format('Y-m-d'))
                                ->Where('PayFor', 'LIKE', '%'.$id.'%')
                                ->orderBy('date', 'ASC')
                                ->get();
        }else{
            $room = "all";
            $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                                ->Where('date', '<=', $end->copy()->format('Y-m-d'))
                                ->orderBy('date', 'ASC')
                                ->get();

        }
        

        

        return view('backend.sales.gastos._expensesByRoom', ['gastos' => $gastos, 'room' => $room]);
    }

    static function setExpenseLimpieza($status, $room_id, $fecha)
    {
        $room = \App\Rooms::find($room_id);
        $expenseLimp = 0;
        if ($room->sizeApto == 1) {
            $expenseLimp     = 30;
         } elseif($room->sizeApto == 2) {
            $expenseLimp     = 40;
        }elseif($room->sizeApto == 3 || $room->sizeApto == 4){
            $expenseLimp     = 70;
        }
        
        $gasto = new \App\Expenses();
        $gasto->concept = "Limpieza reserva prop. ".$room->nameRoom;
        $gasto->date = Carbon::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
        $gasto->import = $expenseLimp;
        $gasto->typePayment = 1;
        $gasto->type = 'LIMPIEZA';
        $gasto->comment = 'CARGO DE LIMPIEZA PARA EL '.$room->nameRoom.' CORRESPONDIENTE A LA RESERVA PROPIETARIO';
        $gasto->PayFor = $room->nameRoom;
        if ($gasto->save()) {
            return true;
        } else {
            return false;
        }
        
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

        if ( empty($request->year) ) {
            $date = Carbon::now();
            if ($date->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$date->copy()->format('Y'));
            }else{
                $date = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
            }
            
        }else{
            $year = Carbon::createFromFormat('Y',$request->year);
            $date = $year->copy();

        }

        $date = new Carbon('first day of September '.$date->copy()->format('Y'));


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
                                                        'data' => $data,
                                                        'percentBenef' => DB::table('percent')->find(1)->percent,
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
                                                    'data' => $data,
                                                    'percentBenef' => DB::table('percent')->find(1)->percent,
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

        if ( empty($request->year) ) {
            $date = Carbon::now();
            if ($date->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$date->copy()->format('Y'));
            }else{
                $date = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
            }
            
        }else{
            $year = Carbon::createFromFormat('Y',$request->year);
            $date = $year->copy();

        }

        $date = new Carbon('first day of September '.$date->copy()->format('Y'));


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
                                                        'percentBenef' => DB::table('percent')->find(1)->percent,
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
                                                    'data' => $data,
                                                    'percentBenef' => DB::table('percent')->find(1)->percent,
                                                ]);

        }
    }

    public function changePercentBenef(Request $request, $val)
    {
        DB::table('percent')->where('id', 1)->update(['percent' => $val]);
        return "Cambiado";
    }
}
