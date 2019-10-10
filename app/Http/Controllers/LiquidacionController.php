<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use \DB;
use App\Classes\Mobile;
use Excel;
use Auth;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");

class LiquidacionController extends AppController {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $totales = [
        "total" => 0,
        "coste" => 0,
        "bancoJorge" => 0,
        "bancoJaime" => 0,
        "jorge" => 0,
        "jaime" => 0,
        "costeApto" => 0,
        "costePark" => 0,
        "costeLujo" => 0,
        "costeLimp" => 0,
        "costeAgencia" => 0,
        "benJorge" => 0,
        "benJaime" => 0,
        "pendiente" => 0,
        "limpieza" => 0,
        "beneficio" => 0,
        "stripe" => 0,
        "obs" => 0,
    ];
    $liquidacion = new \App\Liquidacion();

    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);

    $books = \App\Book::with([
                        'customer',
                        'payments',
                        'room.type'
                    ])->where('start', '>=', $startYear)
                    ->where('start', '<=', $endYear)
                    ->whereIn('type_book', [
                        2,
                        7,
                        8
                    ])->orderBy('start', 'ASC')->get();

    $alert_lowProfits = 0; //To the alert efect
    $percentBenef = DB::table('percent')->find(1)->percent;
    $lowProfits = [];

    foreach ($books as $key => $book) {

      // if($book->type_book != 7 && $book->type_book != 8){
      $totales["total"] += $book->total_price;
      $totales["costeApto"] += $book->cost_apto;
      $totales["costePark"] += $book->cost_park;
      if ($book->room->luxury == 1) {
        $costTotal = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->cost_limp + $book->PVPAgencia;
        $totales["costeLujo"] += $book->cost_lujo;
        $totales["coste"] += $costTotal;
      } else {
        $costTotal = $book->cost_apto + $book->cost_park + 0 + $book->cost_limp + $book->PVPAgencia;
        $totales["costeLujo"] += 0;
        $totales["coste"] += $costTotal;
      }

      $totales["costeLimp"] += $book->cost_limp;
      $totales["costeAgencia"] += $book->PVPAgencia;
      $totales["bancoJorge"] += $book->getPayment(2);
      $totales["bancoJaime"] += $book->getPayment(3);
      $totales["jorge"] += $book->getPayment(0);
      $totales["jaime"] += $book->getPayment(1);
      $totales["benJorge"] += $book->getJorgeProfit();
      $totales["benJaime"] += $book->getJaimeProfit();
      $totales["limpieza"] += $book->sup_limp;
      $totales["beneficio"] += $book->profit;
      $totales["stripe"] += $book->stripeCost;
      $totales["obs"] += $book->extraCost;
      $totales["pendiente"] += $book->pending;
      // }
      //Alarms
      $inc_percent = $book->get_inc_percent();
      if (round($inc_percent) <= $percentBenef) {
        if (!$book->has_low_profit) {
          $alert_lowProfits++;
        }
        $lowProfits[] = $book;
      }
    }


    $totBooks = (count($books) > 0) ? count($books) : 1;
    $diasPropios = \App\Book::where('start', '>=', $startYear)
                    ->where('finish', '<=', $endYear)
                    ->whereIn('type_book', [
                        7,
                        8
                    ])->orderBy('created_at', 'DESC')->get();

    $countDiasPropios = 0;
    foreach ($diasPropios as $key => $book) {
      $start = Carbon::createFromFormat('Y-m-d', $book->start);
      $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
      $countDays = $start->diffInDays($finish);

      $countDiasPropios += $countDays;
    }

    /* INDICADORES DE LA TEMPORADA */
    $data = [
        'days-ocupation' => 0,
        'total-days-season' => \App\SeasonDays::first()->numDays,
        'num-pax' => 0,
        'estancia-media' => 0,
        'pax-media' => 0,
        'precio-dia-media' => 0,
        'dias-propios' => $countDiasPropios,
        'agencia' => 0,
        'propios' => 0,
    ];

    foreach ($books as $key => $book) {

      $start = Carbon::createFromFormat('Y-m-d', $book->start);
      $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
      $countDays = $start->diffInDays($finish);

      /* Dias ocupados */
      $data['days-ocupation'] += $countDays;

      /* Nº inquilinos */
      $data['num-pax'] += $book->pax;


      if ($book->agency != 0) {
        $data['agencia'] ++;
      } else {
        $data['propios'] ++;
      }
    }

    $data['agencia'] = ($data['agencia'] / $totBooks) * 100;
    $data['propios'] = ($data['propios'] / $totBooks) * 100;

    /* Estancia media */
    $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

    /* Inquilinos media */
    $data['pax-media'] = ($data['num-pax'] / $totBooks);

    $mobile = new Mobile();
    if (Auth::user()->role == "subadmin"){
      return view('backend/sales/index-subadmin', [
          'books' => $books,
          'lowProfits' => $lowProfits,
          'alert_lowProfits' => $alert_lowProfits,
          'percentBenef' => $percentBenef,
          'totales' => $totales,
          'year' => $year,
          'data' => $data,
          'percentBenef' => DB::table('percent')->find(1)->percent,
      ]);
    }
    if (!$mobile->isMobile()) {
      return view('backend/sales/index', [
          'books' => $books,
          'lowProfits' => $lowProfits,
          'alert_lowProfits' => $alert_lowProfits,
          'percentBenef' => $percentBenef,
          'totales' => $totales,
          'year' => $year,
          'data' => $data,
          'percentBenef' => DB::table('percent')->find(1)->percent,
      ]);
    } else {
      return view('backend/sales/index', [
          'books' => $books,
          'lowProfits' => $lowProfits,
          'alert_lowProfits' => $alert_lowProfits,
          'percentBenef' => $percentBenef,
          'totales' => $totales,
          'year' => $year,
          'data' => $data,
          'percentBenef' => DB::table('percent')->find(1)->percent,
      ]);
    }
  }

  public function apto($year = "") {
    $now = Carbon::now();

    if (empty($year)) {
      if ($now->copy()->format('n') >= 6) {
        $date = new Carbon('first day of June ' . $now->copy()->format('Y'));
      } else {
        $date = new Carbon('first day of June ' . $now->copy()->subYear()->format('Y'));
      }
    } else {
      $date = new Carbon('first day of June ' . $year);
    }

    $rooms = \App\Rooms::all();
    $pendientes = array();
    $apartamentos = [
        "room" => [],
        "noches" => [],
        "pvp" => [],
        "pendiente" => [],
        "beneficio" => [],
        "%ben" => [],
        "costes" => [],
    ];
    $books = \App\Book::where('type_book', 2)->where('start', '>=', $date)
                    ->where('start', '<=', $date->copy()->addYear()->subMonth())->get();

    foreach ($books as $key => $book) {
      if (isset($apartamentos["noches"][$book->room_id])) {
        $apartamentos["noches"][$book->room_id] += $book->nigths;
        $apartamentos["pvp"][$book->room_id] += $book->total_price;
        $apartamentos['beneficio'][$book->room_id] += $book->total_ben;
        $apartamentos['costes'][$book->room_id] += $book->cost_total;
      } else {
        $apartamentos["noches"][$book->room_id] = $book->nigths;
        $apartamentos["pvp"][$book->room_id] = $book->total_price;
        $apartamentos['beneficio'][$book->room_id] = $book->total_ben;
        $apartamentos['costes'][$book->room_id] = $book->cost_total;
      }
    }

    $pagos = \App\Paymentspro::where('datePayment', '>=', $date)->where('datePayment', '<=', $date->copy()
                    ->addYear()
                    ->subMonth())
            ->get();

    foreach ($pagos as $pago) {
      if (isset($pendientes[$pago->room_id])) {
        $pendientes[$pago->room_id] += $pago->import;
      } else {
        $pendientes[$pago->room_id] = $pago->import;
      }
    }
    return view('backend/sales/liquidacion_apto', [
        'rooms' => $rooms,
        'apartamentos' => $apartamentos,
        'temporada' => $date,
        'pendientes' => $pendientes,
        'percentBenef' => DB::table('percent')->find(1)->percent,
    ]);
  }

  public function contabilidad() {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;
    $lstMonths = lstMonths($startYear,$endYear);
    $rooms = \App\Rooms::where('state', 1)->orderBy('order', 'ASC')->get();
    $books = \App\Book::type_book_sales()->with('payments')
            ->where('start', '>=', $startYear)
            ->where('start', '<=', $endYear)->get();
    
    $lstRooms = [];
    if ($rooms){
      foreach ($rooms as $r){
        $lstRooms[$r->id] = $r->name.' <b>'.$r->nameRoom.'</b>';
      }
    }
    $sales_rooms = [];
    
    $cobrado = $metalico = $banco = $vendido = 0;
    foreach ($books as $key => $book) {
      $date = date('ym', strtotime($book->start));
      
      if ($book->payments){
        foreach ($book->payments as $pay){
          $cobrado += $pay->import;

          if ($pay->type == 0 || $pay->type == 1) {
            $metalico += $pay->import;
          } else if ($pay->type == 2 || $pay->type == 3) {
            $banco += $pay->import;
          }
        }
      }
 
      //Rooom info
      $value = $book->total_price;
      if (!isset($sales_rooms[$book->room_id])) $sales_rooms[$book->room_id] = [];
      if (!isset($sales_rooms[$book->room_id][$date])) $sales_rooms[$book->room_id][$date] = 0;
      $sales_rooms[$book->room_id][$date] += $book->total_price;
      $vendido += $book->total_price;
      
    }
    //prepate Rooms Table
    $t_rooms = [];
    $t_room_month = [];
    $t_all_rooms = 0;
    foreach ($sales_rooms as $r => $data){
      foreach ($data as $month => $val){
        $t_all_rooms += $val;
        
        if (!isset($t_rooms[$r])) $t_rooms[$r] = 0;
        $t_rooms[$r] += $val;
        
        if (!isset($t_room_month[$month])) $t_room_month[$month] = 0;
        $t_room_month[$month] += $val;
        
      }
    }
    //First chart PVP by months
    $dataChartMonths = [];
    foreach ($lstMonths as $k=>$v){
      $val = isset($t_room_month[$k]) ? $t_room_month[$k] : 0;
      $dataChartMonths[getMonthsSpanish($v['m'])] = $val;
    }
    
    return view('backend/sales/contabilidad', [
        'year' => $year,
        'diff' => $diff,
        'sales_rooms' => $sales_rooms,
        'lstRooms' => $lstRooms,
        'lstMonths' => $lstMonths,
        't_rooms' => $t_rooms,
        't_room_month' => $t_room_month,
        't_all_rooms' => (is_numeric($t_all_rooms) && $t_all_rooms>0) ? $t_all_rooms:1,
        'cobrado' => $cobrado,
        'metalico' =>$metalico,
        'banco' =>$banco,
        'vendido'=>$vendido,
        'dataChartMonths' => $dataChartMonths
        ]);
  }

  public function gastos() {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;


    $gastos = \App\Expenses::where('date', '>=', $startYear)
                    ->Where('date', '<=', $endYear)
                    ->where('concept', 'NOT LIKE', '%LIMPIEZA RESERVA PROPIETARIO.%')
                    ->orderBy('date', 'DESC')->get();

    $totalMonthLimpieza = 0;
    $dateMonthLimpieza = '';
    foreach ($gastos as $key => $item) {
      if ($item->concept == 'LIMPIEZA MENSUAL') {
        $totalMonthLimpieza += $item->import;
        $dateMonthLimpieza = date('d M Y', strtotime($item->updated_at));
      }
    }

    $books = \App\Book::whereIn('type_book', [2])->where('start', '>', $startYear)
                    ->where('start', '<=', $endYear)
                    ->orderBy('start', 'ASC')->get();
    $totalStripep = 0;
    $comisionBooking = 0;
    $obsequios = 0;
    foreach ($books as $key => $book) {

      if (count($book->pago) > 0) {
        foreach ($book->pago as $key => $pay) {
          if ($pay->comment == 'Pago desde stripe') {
            $totalStripep += (((1.4 * $pay->import) / 100) + 0.25);
          }
        }
      }

      $comisionBooking += $book->PVPAgencia;
      $obsequios += $book->extraPrice;
    }


    return view('backend/sales/gastos/gastos', [
        'year' => $year,
        'gastos' => $gastos,
        'totalStripep' => $totalStripep,
        'comisionBooking' => $comisionBooking,
        'obsequios' => $obsequios,
        'totalMonthLimpieza' => $totalMonthLimpieza,
        'dateMonthLimpieza' => $dateMonthLimpieza,
    ]);
  }

  public function gastoCreate(Request $request) {

    //		var_dump($request->input());
    //		die();
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


      $data['concept'] = $request->input('concept');
      $data['date'] = Carbon::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
      $data['import'] = $request->input('importe');
      $data['comment'] = $request->input('comment');
      $data['typePayment'] = ($request->input('type_payment') == 1) ? 1 : 0;
      $data['type'] = 1;

      $this->addCashbox($data);
    } else if ($request->input('type_payment') == 0 || $request->input('type_payment') == 3 || $request->input('type_payment') == 4) {
      switch ($request->input('type_payment')) {
        case 0:
          $data['concept'] = $request->input('concept');
          $data['typePayment'] = 2;
          break;
        case 3:
          $data['concept'] = $request->input('concept');
          $data['typePayment'] = 2;
          break;
        case 4:
          $data['concept'] = $request->input('concept');
          $data['typePayment'] = 3;
          break;
      }
      $data['date'] = Carbon::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
      $data['import'] = $request->input('importe');
      $data['comment'] = $request->input('comment');
      $data['type'] = 1;

      $this->addBank($data);
    }
    if ($gasto->save()) {
      return "OK";
    }
  }

  public function updateGasto(Request $request, $id) {
    $gasto = \App\Expenses::find($id);
    $gasto->concept = $request->concept;
    $gasto->typePayment = $request->typePayment;
    $gasto->type = $request->type;
    $gasto->comment = $request->comment;

    if ($gasto->save()) {
      return "OK";
    }
  }

  public function getTableGastos() {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;
    $gastos = \App\Expenses::where('date', '>=', $startYear)
                    ->Where('date', '<=', $endYear)
                    ->orderBy('date', 'DESC')->get();

    $books = \App\Book::whereIn('type_book', [2])
            ->where('start', '>', $startYear)
            ->where('start', '<=', $endYear)
            ->orderBy('start', 'ASC')
            ->get();

    $totalStripep = 0;
    $comisionBooking = 0;
    $obsequios = 0;
    foreach ($books as $key => $book) {

      if (count($book->pago) > 0) {
        foreach ($book->pago as $key => $pay) {
          if ($pay->comment == 'Pago desde stripe') {
            $totalStripep += (((1.4 * $pay->import) / 100) + 0.25);
          }
        }
      }

      $comisionBooking += $book->PVPAgencia;
      $obsequios += $book->extraPrice;
    }

    return view('backend.sales.gastos._tableExpenses', [
        'gastos' => $gastos,
        'totalStripep' => $totalStripep,
        'comisionBooking' => $comisionBooking,
        'obsequios' => $obsequios,
    ]);
  }

  public function ingresos() {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;
    $lstMonths = lstMonths($startYear,$endYear);
    $t_year = 0;
    
    
    $books = \App\Book::type_book_sales()
            ->where('start', '>=', $startYear)
            ->where('start', '<=', $endYear)
            ->get();

    $salesBook = [];
    $tBooks = 0;
    if ($books){
      foreach ($books as $key => $book) {
        $date = date('ym', strtotime($book->start));
        if (!isset($salesBook[$date])) $salesBook[$date] = 0;
        $salesBook[$date] += $book->total_price;
        $tBooks += $book->total_price;
      }
    }
    $t_year += $tBooks;

    $arrayIncomes = array();
    $aIncomes = [
        ['INGRESOS EXTRAORDINARIOS',0],
        ['RAPPEL CLOSES',0],
        ['RAPPEL FORFAITS',0],
        ['RAPPEL ALQUILER MATERIAL',0]
    ];

    foreach ($aIncomes as $k=>$income) {
      $arrayIncomes[$k]=[];
    }
    
    $incomeMonth = [];
    foreach ($aIncomes as $k => $income) {
      $typeIncome = $income[0];
      $incomesLst = \App\Incomes::where('concept', $typeIncome)->where('date', '>', $startYear)
                      ->where('date', '<=', $endYear)->get();
      if ($incomesLst){
        foreach ($incomesLst as $item){
          $date = date('ym', strtotime($item->date));
          if (!isset($arrayIncomes[$k][$date])) $arrayIncomes[$k][$date] = 0;
          $arrayIncomes[$k][$date] += $item->import;
          $aIncomes[$k][1] += $item->import;
          
          if (!isset($incomeMonth[$date])) $incomeMonth[$date] = 0;
          $incomeMonth[$date] += $item->import;
        }
      }
    }
    foreach ($aIncomes as $k => $income) {
      $t_year += $income[1];
    }
    
    $tMonths = [];
    $total = 0;
    foreach ($lstMonths as $k=>$v){
      $aux = 0;
      if (isset($salesBook[$k]) && $salesBook[$k] > 0)
        $aux += $salesBook[$k];
      if (isset($incomeMonth[$k]) && $incomeMonth[$k] > 0)
        $aux += $incomeMonth[$k];
      
      $tMonths[$k] = $aux;
      $total += $aux;
    }
    
    return view('backend/sales/ingresos/ingresos', [
        'year' => $year,
        'diff' => $diff,
        't_year' => ($t_year>1) ? $t_year : 1,
        'totalBooks' => $tBooks,
        'arrayIncomes' => $arrayIncomes,
        'aIncomes' => $aIncomes,
        'salesBook' => $salesBook,
        'lstMonths'=>$lstMonths,
        'tMonths'=>$tMonths,
        'total'=>$total,
    ]);
  }

  public function ingresosCreate(Request $request) {
    $ingreso = new \App\Incomes();
    $ingreso->concept = $request->input('concept');
    $ingreso->date = Carbon::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
    $ingreso->import = $request->input('import');
    if ($ingreso->save()) {
      return redirect('/admin/ingresos');
    }
  }

  public function caja() {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);

    $cashJaime = \App\Cashbox::where('typePayment', 1)
            ->where('date', '>=', $startYear)
            ->where('date', '<=', $endYear)
            ->orderBy('date', 'ASC')
            ->get();
    $saldoInicial = \App\Cashbox::where('concept', 'SALDO INICIAL')->where('typePayment', 1)->first();

    $cashJorge = \App\Cashbox::where('typePayment', 0)
            ->where('date', '>=', $startYear)
            ->where('date', '<=', $endYear)
            ->get();

    return view('backend.sales.cashbox.cashbox', [
        'year' => $year,
        'cashJaime' => $cashJaime,
        'cashboxJor' => $cashJorge,
        'saldoInicial' => $saldoInicial,
    ]);
  }

  public function getTableMoves($year, $type) {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);

    if ($type == 'jaime') {

      $cashbox = \App\Cashbox::where('typePayment', 1)->where('date', '>=', $startYear)
                      ->where('date', '<=', $endYear)
                      ->orderBy('date', 'ASC')->get();
      $saldoInicial = \App\Cashbox::where('concept', 'SALDO INICIAL')->where('typePayment', 1)->first();
    } else {
      $cashbox = \App\Cashbox::where('typePayment', 0)->where('date', '>=', $startYear)
                      ->where('date', '<=', $endYear)
                      ->orderBy('date', 'ASC')->get();

      $saldoInicial = \App\Cashbox::where('concept', 'SALDO INICIAL')->where('typePayment', 0)->first();
    }
    return view('backend.sales.cashbox._tableMoves', [
        'cashbox' => $cashbox,
        'saldoInicial' => $saldoInicial,
    ]);
  }

  public function cashBoxCreate(Request $request) {

    $data = $request->input();
    $data['date'] = Carbon::createFromFormat('d/m/Y', $data['fecha'])->format('Y-m-d');
    $data['import'] = $data['importe'];
    $data['typePayment'] = $data['type_payment'];
    if ($this->addCashbox($data)) {
      return "OK";
    }
  }

  static function addCashbox($data) {

    $cashbox = new \App\Cashbox();
    $cashbox->concept = $data['concept'];
    $cashbox->date = Carbon::createFromFormat('Y-m-d', $data['date']);
    $cashbox->import = $data['import'];
    $cashbox->comment = $data['comment'];
    $cashbox->typePayment = $data['typePayment'];
    $cashbox->type = $data['type'];
    if ($cashbox->save()) {
      return true;
    } else {
      return false;
    }
  }

  public function bank() {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);

    $saldoInicial = \App\Bank::where('concept', 'SALDO INICIAL')->where('typePayment', 3)->first();

    $bankItems = \App\Bank::whereIn('typePayment', [2, 0, 3])
            ->where('date', '>=', $startYear)
            ->where('date', '<=', $endYear)
            ->orderBy('date', 'ASC')
            ->get();

    //Totals
    $totals = 0; //$saldoInicial->import; 
    foreach ($bankItems as $key => $cash):
      if ($cash->type == 1):
        $totals -= $cash->import;
      endif;
      if ($cash->type == 0):
        $totals += $cash->import;
      endif;
    endforeach;

    return view('backend.sales.bank.bank', [
        'year' => $year,
        'totals' => $totals,
        'bankItems' => $bankItems,
        'saldoInicial' => $saldoInicial,
    ]);
  }

  public function getTableMovesBank($year, $type) {
    if (empty($year)) {
      $date = Carbon::now();
      if ($date->copy()->format('n') >= 6) {
        $date = new Carbon('first day of June ' . $date->copy()->format('Y'));
      } else {
        $date = new Carbon('first day of June ' . $date->copy()->subYear()->format('Y'));
      }
    } else {
      $year = Carbon::createFromFormat('Y', $year);
      $date = $year->copy();
    }

    $inicio = new Carbon('first day of June ' . $date->copy()->format('Y'));
    if ($type == 'jaime') {

      $bank = \App\Bank::where('typePayment', 3)->where('date', '>=', $inicio->copy()->format('Y-m-d'))
                      ->where('date', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                      ->orderBy('date', 'ASC')->get();
      $saldoInicial = \App\Bank::where('concept', 'SALDO INICIAL')->where('typePayment', 3)->first();
    } else {
      $bank = \App\Bank::where('typePayment', 2)->where('date', '>=', $inicio->copy()->format('Y-m-d'))
              ->where('date', '<=', $inicio->copy()->addYear()->format('Y-m-d'))->orderBy('date', 'ASC')
              ->get();

      $saldoInicial = \App\Bank::where('concept', 'SALDO INICIAL')->where('typePayment', 2)->first();
    }
    return view('backend.sales.bank._tableMoves', [
        'bank' => $bank,
        'saldoInicial' => $saldoInicial,
    ]);
  }

  static function addBank($data) {

    $bank = new \App\Bank();
    $bank->concept = $data['concept'];
    $bank->date = Carbon::createFromFormat('Y-m-d', $data['date']);
    $bank->import = $data['import'];
    $bank->comment = $data['comment'];
    $bank->typePayment = $data['typePayment'];
    $bank->type = $data['type'];
    if ($bank->save()) {
      return true;
    } else {
      return false;
    }
  }

  public function perdidasGanancias() {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;
    $books = \App\Book::type_book_sales()->where('start', '>', $startYear)->where('start', '<=', $endYear)->get();
    /* INGRESOS */
    $arrayTotales = [
        'totales' => 0,
        'meses' => []
    ];

    $arrayExpensesPending = [
        'PAGO PROPIETARIO' => [],
        'AGENCIAS' => [],
        'STRIPE' => [],
        'LIMPIEZA' => [],
        'LAVANDERIA' => []
    ];

    for ($i = 1; $i <= $diff; $i++) {
      $arrayTotales['meses'][$i] = 0;

      $arrayExpensesPending['PAGO PROPIETARIO'][$i] = 0;
      $arrayExpensesPending['AGENCIAS'][$i] = 0;
      $arrayExpensesPending["STRIPE"][$i] = 0;

      $arrayExpensesPending['LIMPIEZA'][$i] = 0;
      $arrayExpensesPending['LAVANDERIA'][$i] = 0;
    }

    foreach ($books as $book) {
      $fecha = Carbon::createFromFormat('Y-m-d', $book->start);
      $arrayTotales['meses'][$fecha->copy()->format('n')] += $book->total_price;
      $arrayTotales['totales'] += $book->total_price;


      $arrayExpensesPending['PAGO PROPIETARIO'][$fecha->copy()
                      ->format('n')] += ($book->cost_apto + $book->cost_park + $book->cost_lujo);
      $arrayExpensesPending['AGENCIAS'][$fecha->copy()->format('n')] += $book->PVPAgencia;
      if (count($book->pago) > 0) {
        foreach ($book->pago as $key => $pay) {
          if ($pay->comment == 'Pago desde stripe') {
            // $arrayExpensesPending["STRIPE"][$fecha->copy()->format('n')] += ((1.4 * $book->total_price)/100)+0.25;
            $arrayExpensesPending["STRIPE"][$fecha->copy()
                            ->format('n')] += (((1.4 * $pay->import) / 100) + 0.25);
          }
        }
      }


      $arrayExpensesPending['LIMPIEZA'][$fecha->copy()->format('n')] += ($book->cost_limp - 10);
      $arrayExpensesPending['LAVANDERIA'][$fecha->copy()->format('n')] += 10;

      //
    }
    $arrayIncomes = array();
    $conceptIncomes = [
        'INGRESOS EXTRAORDINARIOS',
        'RAPPEL CLOSES',
        'RAPPEL FORFAITS',
        'RAPPEL ALQUILER MATERIAL'
    ];

    foreach ($conceptIncomes as $typeIncome) {
      for ($i = 1; $i <= $diff; $i++) {
        $arrayIncomes[$typeIncome][$i] = 0;
      }
    }
    foreach ($conceptIncomes as $typeIncome) {
      $incomes = \App\Incomes::where('concept', $typeIncome)->where('date', '>', $startYear)
                      ->where('date', '<=', $endYear)->get();

      if (count($incomes) > 0) {

        foreach ($incomes as $key => $income) {
          $fecha = Carbon::createFromFormat('Y-m-d', $income->date);
          $arrayIncomes[$typeIncome][$fecha->copy()->format('n')] += $income->import;
        }
      } else {
        for ($i = 1; $i <= $diff; $i++) {
          $arrayIncomes[$typeIncome][$i] = 0;
        }
      }
    }
    /* FIN INGRESOS */


    $conceptExpenses = [
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
    for ($i = 1; $i <= 12; $i++) {
      for ($j = 0; $j < count($conceptExpenses); $j++) {
        $arrayExpenses[$conceptExpenses[$j]][$i] = 0;
      }
    }


    $gastos = \App\Expenses::where('date', '>', $startYear)
                    ->Where('date', '<=', $endYear)
                    ->where('concept', 'NOT LIKE', '%LIMPIEZA RESERVA PROPIETARIO.%')
                    ->orderBy('date', 'DESC')->get();

    foreach ($gastos as $key => $gasto) {

      $fecha = Carbon::createFromFormat('Y-m-d', $gasto->date);
      if (!isset($arrayExpenses[$gasto->type])) {
        for ($i = 1; $i <= $diff; $i++) {
          $arrayExpenses[$gasto->type][$i] = 0;
        }
      }

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

    return view('backend/sales/perdidas_ganancias', [
        'arrayTotales' => $arrayTotales,
        'arrayIncomes' => $arrayIncomes,
        'arrayExpenses' => $arrayExpenses,
        'diff' => $diff,
        'year' => $year,
        'arrayExpensesPending' => $arrayExpensesPending,
    ]);
  }

  static function getSalesByYear($year = "") {
    // $array = [0 =>"Metalico Jorge", 1 =>"Metalico Jaime",2 =>"Banco Jorge",3=>"Banco Jaime"];

    if ($year == "") {
      $year = self::getActiveYear();
      $startYear = new Carbon($year->start_date);
      $endYear = new Carbon($year->end_date);
    } else {
      $start = new Carbon('first day of September ' . $year);
      $end = $start->copy()->addYear();
      $startYear = $start->copy()->format('Y-m-d');
      $endYear = $end->copy()->format('Y-m-d');
    }

    $books = \App\Book::type_book_sales()->with('payments')->where('start', '>=', $startYear)
                    ->where('start', '<=', $endYear)
                    ->orderBy('start', 'ASC')->get();

    $result = [
        'ventas' => 0,
        'cobrado' => 0,
        'pendiente' => 0,
        'metalico' => 0,
        'banco' => 0
    ];
    foreach ($books as $key => $book) {
      $result['ventas'] += $book->total_price;

      foreach ($book->payments as $key => $pay) {
        $result['cobrado'] += $pay->import;

        if ($pay->type == 0 || $pay->type == 1) {
          $result['metalico'] += $pay->import;
        } else if ($pay->type == 2 || $pay->type == 3) {
          $result['banco'] += $pay->import;
        }
      }
    }

    $result['pendiente'] = ($result['ventas'] - $result['cobrado']);

    return $result;
  }

  static function getSalesByYearByRoom($year = "", $room = "all") {
    if (empty($year)) {
      $date = Carbon::now();
    } else {
      $year = Carbon::createFromFormat('Y', $year);
      $date = $year->copy();
    }
    if ($date->copy()->format('n') >= 6) {
      $start = new Carbon('first day of June ' . $date->copy()->format('Y'));
    } else {
      $start = new Carbon('first day of June ' . $date->copy()->subYear()->format('Y'));
    }

    $end = $start->copy()->addYear();

    if ($room == "all") {
      $rooms = \App\Rooms::where('state', 1)->get(['id']);
      $books = \App\Book::whereIn('type_book', [2])->whereIn('room_id', $rooms)
                      ->where('start', '>=', $start->copy()->format('Y-m-d'))->where('start', '<=', $end->copy()
                              ->format('Y-m-d'))
                      ->orderBy('start', 'ASC')->get();

      $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                      ->Where('date', '<=', $end->copy()->format('Y-m-d'))->orderBy('date', 'DESC')->get();
    } else {

      $books = \App\Book::whereIn('type_book', [2])->where('room_id', $room)->where('start', '>=', $start->copy()
                              ->format('Y-m-d'))
                      ->where('start', '<=', $end->copy()->format('Y-m-d'))->orderBy('start', 'ASC')->get();

      $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                      ->Where('date', '<=', $end->copy()->format('Y-m-d'))
                      ->Where('PayFor', 'LIKE', '%' . $room . '%')->orderBy('date', 'DESC')->get();
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
      } else if ($gasto->type == 2 || $gasto->type == 3) {
        $banco += $gasto->import;
      }
    }
    $total += ($apto + $park + $lujo);

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

  static function getSalesByYearByRoomGeneral($year = "", $room = "all") {
    if (empty($year)) {
      $date = Carbon::now();
    } else {
      $year = Carbon::createFromFormat('Y', $year);
      $date = $year->copy();
    }
    if ($date->copy()->format('n') >= 9) {
      $start = new Carbon('first day of September ' . $date->copy()->format('Y'));
    } else {
      $start = new Carbon('first day of September ' . $date->copy()->subYear()->format('Y'));
    }

    $end = $start->copy()->addYear();
    $total = 0;
    $metalico = 0;
    $banco = 0;
    $pagado = 0;
    $metalico_jaime = 0;
    $metalico_jorge = 0;
    $banco_jorge = 0;
    $banco_jaime = 0;
    if ($room == "all") {
      $rooms = \App\Rooms::where('state', 1)->get(['id']);
      $books = \App\Book::whereIn('type_book', [
                          2,
                          7,
                          8
                      ])->whereIn('room_id', $rooms)->where('start', '>=', $start->copy()->format('Y-m-d'))
                      ->where('start', '<=', $end->copy()->format('Y-m-d'))->orderBy('start', 'ASC')->get();


      foreach ($books as $key => $book) {
        $total += ($book->cost_apto + $book->cost_park + $book->cost_lujo); //$book->total_price;
        if (count($book->pago) > 0) {
          foreach ($book->pago as $index => $pay) {
            switch ($pay->type) {
              case 0:
                $metalico_jaime += $pay->import;
                $metalico += $pay->import;
                break;
              case 1:
                $metalico_jorge += $pay->import;
                $metalico += $pay->import;
                break;
              case 2:
                $banco_jorge += $pay->import;
                $banco += $pay->import;
                break;
              case 3:
                $banco_jaime += $pay->import;
                $banco += $pay->import;
                break;
            }
          }
        }
      }

      $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                      ->Where('date', '<=', $start->copy()->addYear()->format('Y-m-d'))
                      ->orderBy('date', 'DESC')->get();
      foreach ($gastos as $payment) {
        if ($payment->typePayment == 0 || $payment->typePayment == 1) {
          $divisor = 0;
          if (preg_match('/,/', $payment->PayFor)) {
            $aux = explode(',', $payment->PayFor);
            for ($i = 0; $i < count($aux); $i++) {
              if (!empty($aux[$i])) {
                $divisor++;
              }
            }
          } else {
            $divisor = 1;
          }
          $metalico += ($payment->import / $divisor);
        } else if ($payment->typePayment == 2 || $payment->typePayment == 3) {
          $divisor = 0;
          if (preg_match('/,/', $payment->PayFor)) {
            $aux = explode(',', $payment->PayFor);
            for ($i = 0; $i < count($aux); $i++) {
              if (!empty($aux[$i])) {
                $divisor++;
              }
            }
          } else {
            $divisor = 1;
          }
          $banco += ($payment->import / $divisor);
        }

        $divisor = 0;
        if (preg_match('/,/', $payment->PayFor)) {
          $aux = explode(',', $payment->PayFor);
          for ($i = 0; $i < count($aux); $i++) {
            if (!empty($aux[$i])) {
              $divisor++;
            }
          }
        } else {
          $divisor = 1;
        }

        $pagado += ($payment->import / $divisor);
      }
    } else {

      $books = \App\Book::whereIn('type_book', [
                          2,
                          7,
                          8
                      ])->where('room_id', $room)->where('start', '>=', $start->copy()->format('Y-m-d'))
                      ->where('start', '<=', $end->copy()->format('Y-m-d'))->orderBy('start', 'ASC')->get();

      foreach ($books as $key => $book) {
        $total += ($book->cost_apto + $book->cost_park + $book->cost_lujo); //$book->total_price;
        if (count($book->pago) > 0) {
          foreach ($book->pago as $index => $pay) {
            switch ($pay->type) {
              case 0:
                $metalico_jaime += $pay->import;
                $metalico += $pay->import;
                break;
              case 1:
                $metalico_jorge += $pay->import;
                $metalico += $pay->import;
                break;
              case 2:
                $banco_jorge += $pay->import;
                $banco += $pay->import;
                break;
              case 3:
                $banco_jaime += $pay->import;
                $banco += $pay->import;
                break;
            }
          }
        }
      }

      $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                      ->Where('date', '<=', $start->copy()->addYear()->format('Y-m-d'))
                      ->Where('PayFor', 'LIKE', '%' . $room . '%')->orderBy('date', 'DESC')->get();

      foreach ($gastos as $payment) {
        if ($payment->typePayment == 0 || $payment->typePayment == 1) {
          $divisor = 0;
          if (preg_match('/,/', $payment->PayFor)) {
            $aux = explode(',', $payment->PayFor);
            for ($i = 0; $i < count($aux); $i++) {
              if (!empty($aux[$i])) {
                $divisor++;
              }
            }
          } else {
            $divisor = 1;
          }
          $metalico += ($payment->import / $divisor);
        } else if ($payment->typePayment == 2 || $payment->typePayment == 3) {
          $divisor = 0;
          if (preg_match('/,/', $payment->PayFor)) {
            $aux = explode(',', $payment->PayFor);
            for ($i = 0; $i < count($aux); $i++) {
              if (!empty($aux[$i])) {
                $divisor++;
              }
            }
          } else {
            $divisor = 1;
          }
          $banco += ($payment->import / $divisor);
        }

        $divisor = 0;
        if (preg_match('/,/', $payment->PayFor)) {
          $aux = explode(',', $payment->PayFor);
          for ($i = 0; $i < count($aux); $i++) {
            if (!empty($aux[$i])) {
              $divisor++;
            }
          }
        } else {
          $divisor = 1;
        }

        $pagado += ($payment->import / $divisor);
      }
    }


    return [
        'total' => $total,
        'banco' => $banco,
        'metalico' => $metalico,
        'pagado' => $pagado,
        'metalico_jaime' => 0,
        'metalico_jorge' => $metalico_jorge+$metalico_jaime,
        'banco_jorge' => $banco_jorge+$banco_jaime,
        'banco_jaime' => 0,
    ];
  }

  public function getHojaGastosByRoom($year = "", $id) {
    if (empty($year)) {
      $date = Carbon::now();
    } else {
      $year = Carbon::createFromFormat('Y', $year);
      $date = $year->copy();
    }
    $start = new Carbon('first day of September ' . $date->copy()->format('Y'));

    // return $start;
    $end = $start->copy()->addYear();
    if ($id != "all") {
      $room = \App\Rooms::find($id);
      $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                      ->Where('date', '<=', $end->copy()->format('Y-m-d'))
                      ->Where('PayFor', 'LIKE', '%' . $id . '%')->orderBy('date', 'DESC')->get();
    } else {
      $room = "all";
      $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                      ->Where('date', '<=', $end->copy()->format('Y-m-d'))->orderBy('date', 'DESC')->get();
    }

    return view('backend.sales.gastos._expensesByRoom', [
        'gastos' => $gastos,
        'room' => $room,
        'year' => $start
    ]);
  }

  public function getTableExpensesByRoom($year = "", $id) {

    if (empty($year)) {
      $date = Carbon::now();
    } else {
      $year = Carbon::createFromFormat('Y', $year);
      $date = $year->copy();
    }
    $start = new Carbon('first day of September ' . $date->copy()->format('Y'));

    // return $start;
    $end = $start->copy()->addYear();
    if ($id != "all") {
      $room = \App\Rooms::find($id);
      $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                      ->Where('date', '<=', $end->copy()->format('Y-m-d'))
                      ->Where('PayFor', 'LIKE', '%' . $id . '%')->orderBy('date', 'DESC')->get();
    } else {
      $room = "all";
      $gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
                      ->Where('date', '<=', $end->copy()->format('Y-m-d'))->orderBy('date', 'DESC')->get();
    }

    return view('backend.sales.gastos._tableExpensesByRoom', [
        'gastos' => $gastos,
        'room' => $room,
        'year' => $start
    ]);
  }

  static function setExpenseLimpieza($status, $room_id, $fecha) {
    $room = \App\Rooms::find($room_id);
    $expenseLimp = 0;

    if ($room->sizeApto == 1) {
      $expenseLimp = 30;
    } else if ($room->sizeApto == 2) {
      $expenseLimp = 50; //40;
    } else if ($room->sizeApto == 3 || $room->sizeApto == 4) {
      $expenseLimp = 100; //70;
    }

    $gasto = new \App\Expenses();
    $gasto->concept = "LIMPIEZA RESERVA PROPIETARIO. " . $room->nameRoom;
    $gasto->date = Carbon::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
    $gasto->import = $expenseLimp;
    $gasto->typePayment = 3;
    $gasto->type = 'LIMPIEZA';
    $gasto->comment = " LIMPIEZA RESERVA PROPIETARIO. " . $room->nameRoom;
    $gasto->PayFor = $room->id;
    if ($gasto->save()) {
      return true;
    } else {
      return false;
    }
  }

  public function searchByName(Request $request) {
    $now = Carbon::now();
    $totales = [
        "total" => 0,
        "coste" => 0,
        "bancoJorge" => 0,
        "bancoJaime" => 0,
        "jorge" => 0,
        "jaime" => 0,
        "costeApto" => 0,
        "costePark" => 0,
        "costeLujo" => 0,
        "costeLimp" => 0,
        "costeAgencia" => 0,
        "benJorge" => 0,
        "benJaime" => 0,
        "pendiente" => 0,
        "limpieza" => 0,
        "beneficio" => 0,
        "stripe" => 0,
        "obs" => 0,
    ];

    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);

    if ($request->searchString != "") {
      $customers = \App\Customers::where('name', 'LIKE', '%' . $request->searchString . '%')->get();

      if (count($customers) > 0) {
        $arrayCustomersId = [];
        foreach ($customers as $key => $customer) {
          if (!in_array($customer->id, $arrayCustomersId)) {
            $arrayCustomersId[] = $customer->id;
          }
        }

        if ($request->searchRoom && $request->searchRoom != "all") {

          $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>=', $startYear)
                  ->where('start', '<=', $endYear)
                  ->whereIn('type_book', [
                      2,
                      7,
                      8
                  ])
                  ->where('room_id', $request->searchRoom)
                  ->where('agency', $request->searchAgency)
                  ->orderBy('start', 'ASC')
                  ->get();

          $diasPropios = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>', $startYear)
                  ->where('finish', '<', $endYear)
                  ->whereIn('type_book', [
                      7,
                      8
                  ])
                  ->where('room_id', $request->searchRoom)
                  ->where('agency', $request->searchAgency)
                  ->orderBy('created_at', 'DESC')
                  ->get();
        } else {
          $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>=', $startYear)
                  ->where('start', '<=', $endYear)
                  ->whereIn('type_book', [
                      2,
                      7,
                      8
                  ])
                  ->where('agency', $request->searchAgency)
                  ->orderBy('start', 'ASC')
                  ->get();

          $diasPropios = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>', $startYear)
                  ->where('finish', '<', $endYear)
                  ->whereIn('type_book', [
                      7,
                      8
                  ])
                  ->where('agency', $request->searchAgency)
                  ->orderBy('created_at', 'DESC')
                  ->get();
        }

        $books->load([
            'customer',
            'payments',
            'room.type'
        ]);


        foreach ($books as $key => $book) {

          // if($book->type_book != 7 && $book->type_book != 8){
          $totales["total"] += $book->total_price;
          $totales["costeApto"] += $book->cost_apto;
          $totales["costePark"] += $book->cost_park;
          if ($book->room->luxury == 1) {
            $costTotal = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->cost_limp + $book->PVPAgencia;
            $totales["costeLujo"] += $book->cost_lujo;
            $totales["coste"] += $costTotal;
          } else {
            $costTotal = $book->cost_apto + $book->cost_park + 0 + $book->cost_limp + $book->PVPAgencia;
            $totales["costeLujo"] += 0;
            $totales["coste"] += $costTotal;
          }

          $totales["costeLimp"] += $book->cost_limp;
          $totales["costeAgencia"] += $book->PVPAgencia;
          $totales["bancoJorge"] += $book->getPayment(2);
          $totales["bancoJaime"] += $book->getPayment(3);
          $totales["jorge"] += $book->getPayment(0);
          $totales["jaime"] += $book->getPayment(1);
          $totales["benJorge"] += $book->getJorgeProfit();
          $totales["benJaime"] += $book->getJaimeProfit();
          $totales["limpieza"] += $book->sup_limp;
          $totales["beneficio"] += $book->profit;
          $totales["stripe"] += $book->stripeCost;
          $totales["obs"] += $book->extraCost;
          $totales["pendiente"] += $book->pending;
          // }
        }

        $totBooks = (count($books) > 0) ? count($books) : 1;
        $countDiasPropios = 0;
        foreach ($diasPropios as $key => $book) {
          $start = Carbon::createFromFormat('Y-m-d', $book->start);
          $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
          $countDays = $start->diffInDays($finish);

          $countDiasPropios += $countDays;
        }

        /* INDICADORES DE LA TEMPORADA */
        $data = [
            'days-ocupation' => 0,
            'total-days-season' => \App\SeasonDays::first()->numDays,
            'num-pax' => 0,
            'estancia-media' => 0,
            'pax-media' => 0,
            'precio-dia-media' => 0,
            'dias-propios' => $countDiasPropios,
            'agencia' => 0,
            'propios' => 0,
        ];

        foreach ($books as $key => $book) {

          $start = Carbon::createFromFormat('Y-m-d', $book->start);
          $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
          $countDays = $start->diffInDays($finish);

          /* Dias ocupados */
          $data['days-ocupation'] += $countDays;

          /* Nº inquilinos */
          $data['num-pax'] += $book->pax;


          if ($book->agency != 0) {
            $data['agencia'] ++;
          } else {
            $data['propios'] ++;
          }
        }

        $data['agencia'] = ($data['agencia'] / $totBooks) * 100;
        $data['propios'] = ($data['propios'] / $totBooks) * 100;

        /* Estancia media */
        $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

        /* Inquilinos media */
        $data['pax-media'] = ($data['num-pax'] / $totBooks);


        return view('backend/sales/_tableSummary', [
            'books' => $books,
            'totales' => $totales,
            'data' => $data,
            'percentBenef' => DB::table('percent')->find(1)->percent,
            'year' => $year
        ]);
      } else {
        return "<h2>No hay reservas para este término '" . $request->searchString . "'</h2>";
      }
    } else {

      if ($request->searchRoom && $request->searchRoom != "all") {

        $books = \App\Book::where('start', '>=', $startYear)
                ->where('start', '<=', $endYear)
                ->whereIn('type_book', [
                    2,
                    7,
                    8
                ])
                ->where('room_id', $request->searchRoom)
                ->where('agency', $request->searchAgency)
                ->orderBy('start', 'ASC')
                ->get();

        $diasPropios = \App\Book::where('start', '>', $startYear)
                ->where('room_id', $request->searchRoom)
                ->where('finish', '<', $endYear)
                ->whereIn('type_book', [
                    7,
                    8
                ])
                ->where('agency', $request->searchAgency)
                ->orderBy('created_at', 'DESC')
                ->get();
      } else {
        $books = \App\Book::where('start', '>=', $startYear)
                ->where('start', '<=', $endYear)
                ->whereIn('type_book', [
                    2,
                    7,
                    8
                ])
                ->where('agency', $request->searchAgency)
                ->orderBy('start', 'ASC')
                ->get();

        $diasPropios = \App\Book::where('start', '>', $startYear)
                ->where('finish', '<', $endYear)
                ->whereIn('type_book', [
                    7,
                    8
                ])
                ->where('agency', $request->searchAgency)
                ->orderBy('created_at', 'DESC')
                ->get();
      }


      foreach ($books as $key => $book) {
        // if($book->type_book != 7 && $book->type_book != 8){
        $totales["total"] += $book->total_price;
        $totales["costeApto"] += $book->cost_apto;
        $totales["costePark"] += $book->cost_park;
        if ($book->room->luxury == 1) {
          $costTotal = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->cost_limp + $book->PVPAgencia;
          $totales["costeLujo"] += $book->cost_lujo;
          $totales["coste"] += $costTotal;
        } else {
          $costTotal = $book->cost_apto + $book->cost_park + 0 + $book->cost_limp + $book->PVPAgencia;
          $totales["costeLujo"] += 0;
          $totales["coste"] += $costTotal;
        }

        $totales["costeLimp"] += $book->cost_limp;
        $totales["costeAgencia"] += $book->PVPAgencia;
        $totales["bancoJorge"] += $book->getPayment(2);
        $totales["bancoJaime"] += $book->getPayment(3);
        $totales["jorge"] += $book->getPayment(0);
        $totales["jaime"] += $book->getPayment(1);
        $totales["benJorge"] += $book->getJorgeProfit();
        $totales["benJaime"] += $book->getJaimeProfit();
        $totales["limpieza"] += $book->sup_limp;
        $totales["beneficio"] += $book->profit;
        $totales["stripe"] += $book->stripeCost;
        $totales["obs"] += $book->extraCost;
        $totales["pendiente"] += $book->pending;
        // }
      }
      $totBooks = (count($books) > 0) ? count($books) : 1;
      $countDiasPropios = 0;
      foreach ($diasPropios as $key => $book) {
        $start = Carbon::createFromFormat('Y-m-d', $book->start);
        $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
        $countDays = $start->diffInDays($finish);

        $countDiasPropios += $countDays;
      }

      /* INDICADORES DE LA TEMPORADA */
      $data = [
          'days-ocupation' => 0,
          'total-days-season' => \App\SeasonDays::first()->numDays,
          'num-pax' => 0,
          'estancia-media' => 0,
          'pax-media' => 0,
          'precio-dia-media' => 0,
          'dias-propios' => $countDiasPropios,
          'agencia' => 0,
          'propios' => 0,
      ];

      foreach ($books as $key => $book) {

        $start = Carbon::createFromFormat('Y-m-d', $book->start);
        $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
        $countDays = $start->diffInDays($finish);

        /* Dias ocupados */
        $data['days-ocupation'] += $countDays;

        /* Nº inquilinos */
        $data['num-pax'] += $book->pax;


        if ($book->agency != 0) {
          $data['agencia'] ++;
        } else {
          $data['propios'] ++;
        }
      }

      $data['agencia'] = ($data['agencia'] / $totBooks) * 100;
      $data['propios'] = ($data['propios'] / $totBooks) * 100;

      /* Estancia media */
      $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

      /* Inquilinos media */
      $data['pax-media'] = ($data['num-pax'] / $totBooks);

      return view('backend/sales/_tableSummary', [
          'books' => $books,
          'totales' => $totales,
          'data' => $data,
          'percentBenef' => DB::table('percent')->find(1)->percent,
          'year' => $year
      ]);
    }
  }

  public function searchByRoom(Request $request) {
    $now = Carbon::now();
    $totales = [
        "total" => 0,
        "coste" => 0,
        "bancoJorge" => 0,
        "bancoJaime" => 0,
        "jorge" => 0,
        "jaime" => 0,
        "costeApto" => 0,
        "costePark" => 0,
        "costeLujo" => 0,
        "costeLimp" => 0,
        "costeAgencia" => 0,
        "benJorge" => 0,
        "benJaime" => 0,
        "pendiente" => 0,
        "limpieza" => 0,
        "beneficio" => 0,
        "stripe" => 0,
        "obs" => 0,
    ];

    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);


    if ($request->searchString != "") {

      $customers = \App\Customers::where('name', 'LIKE', '%' . $request->searchString . '%')->get();

      if (count($customers) > 0) {
        $arrayCustomersId = [];
        foreach ($customers as $key => $customer) {
          if (!in_array($customer->id, $arrayCustomersId)) {
            $arrayCustomersId[] = $customer->id;
          }
        }

        if ($request->searchRoom && $request->searchRoom != "all") {

          $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>=', $startYear)
                  ->where('start', '<=', $endYear)
                  ->whereIn('type_book', [
                      2,
                      7
                          ], 8)
                  ->where('agency', $request->searchAgency)
                  ->where('room_id', $request->searchRoom)
                  ->orderBy('start', 'ASC')
                  ->get();

          $diasPropios = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>', $startYear)
                  ->where('finish', '<', $endYear)
                  ->whereIn('type_book', [
                      7,
                      8
                  ])->where('room_id', $request->searchRoom)
                  ->where('agency', $request->searchAgency)
                  ->orderBy('created_at', 'DESC')
                  ->get();
        } else {
          $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>=', $startYear)
                  ->where('start', '<=', $endYear)
                  ->whereIn('type_book', [
                      2,
                      7,
                      8
                  ])
                  ->where('agency', $request->searchAgency)
                  ->orderBy('start', 'ASC')
                  ->get();

          $diasPropios = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>', $startYear)
                  ->where('finish', '<', $endYear)
                  ->whereIn('type_book', [
                      7,
                      8
                  ])
                  ->where('agency', $request->searchAgency)
                  ->orderBy('created_at', 'DESC')
                  ->get();
        }

        $books->load([
            'customer',
            'payments',
            'room.type'
        ]);

        foreach ($books as $key => $book) {
          if ($book->type_book != 7) {
            $totales["total"] += $book->total_price;
            $totales["costeApto"] += $book->cost_apto;
            $totales["costePark"] += $book->cost_park;
            if ($book->room->luxury == 1) {
              $costTotal = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->cost_limp + $book->PVPAgencia;
              $totales["costeLujo"] += $book->cost_lujo;
              $totales["coste"] += $costTotal;
            } else {
              $costTotal = $book->cost_apto + $book->cost_park + 0 + $book->cost_limp + $book->PVPAgencia;
              $totales["costeLujo"] += 0;
              $totales["coste"] += $costTotal;
            }

            $totales["costeLimp"] += $book->cost_limp;
            $totales["costeAgencia"] += $book->PVPAgencia;
            $totales["bancoJorge"] += $book->getPayment(2);
            $totales["bancoJaime"] += $book->getPayment(3);
            $totales["jorge"] += $book->getPayment(0);
            $totales["jaime"] += $book->getPayment(1);
            $totales["benJorge"] += $book->getJorgeProfit();
            $totales["benJaime"] += $book->getJaimeProfit();
            $totales["limpieza"] += $book->sup_limp;
            $totales["beneficio"] += $book->profit;
            $totales["stripe"] += $book->stripeCost;
            $totales['obs'] += $book->extraCost;
            $totales["pendiente"] += $book->pending;
          }
        }

        $totBooks = (count($books) > 0) ? count($books) : 1;
        $countDiasPropios = 0;
        foreach ($diasPropios as $key => $book) {
          $start = Carbon::createFromFormat('Y-m-d', $book->start);
          $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
          $countDays = $start->diffInDays($finish);

          $countDiasPropios += $countDays;
        }

        /* INDICADORES DE LA TEMPORADA */
        $data = [
            'days-ocupation' => 0,
            'total-days-season' => \App\SeasonDays::first()->numDays,
            'num-pax' => 0,
            'estancia-media' => 0,
            'pax-media' => 0,
            'precio-dia-media' => 0,
            'dias-propios' => $countDiasPropios,
            'agencia' => 0,
            'propios' => 0,
        ];

        foreach ($books as $key => $book) {

          $start = Carbon::createFromFormat('Y-m-d', $book->start);
          $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
          $countDays = $start->diffInDays($finish);

          /* Dias ocupados */
          $data['days-ocupation'] += $countDays;

          /* Nº inquilinos */
          $data['num-pax'] += $book->pax;


          if ($book->agency != 0) {
            $data['agencia'] ++;
          } else {
            $data['propios'] ++;
          }
        }

        $data['agencia'] = ($data['agencia'] / $totBooks) * 100;
        $data['propios'] = ($data['propios'] / $totBooks) * 100;

        /* Estancia media */
        $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

        /* Inquilinos media */
        $data['pax-media'] = ($data['num-pax'] / $totBooks);


        return view('backend/sales/_tableSummary', [
            'year' => $year,
            'books' => $books,
            'totales' => $totales,
            'data' => $data,
            'percentBenef' => DB::table('percent')->find(1)->percent,
        ]);
      } else {
        return "<h2>No hay reservas para este término '" . $request->searchString . "'</h2>";
      }
    } else {


      if ($request->searchRoom && $request->searchRoom != "all") {

        $books = \App\Book::where('start', '>=', $startYear)
                        ->where('start', '<=', $endYear)
                        ->whereIn('type_book', [
                            2,
                            7
                        ])->where('room_id', $request->searchRoom)->orderBy('start', 'ASC')->get();

        $diasPropios = \App\Book::where('start', '>', $startYear)
                        ->where('room_id', $request->searchRoom)->where('finish', '<', $endYear)
                        ->whereIn('type_book', [
                            7,
                            8
                        ])->orderBy('created_at', 'DESC')->get();
      } else {
        $books = \App\Book::where('start', '>=', $startYear)
                ->where('start', '<=', $endYear)
                ->whereIn('type_book', [
                    2,
                    7
                ])
                ->where('agency', $request->searchAgency)
                ->orderBy('start', 'ASC')
                ->get();

        $diasPropios = \App\Book::where('start', '>', $startYear)
                ->where('finish', '<', $endYear)
                ->whereIn('type_book', [
                    7,
                    8
                ])
                ->where('agency', $request->searchAgency)
                ->orderBy('created_at', 'DESC')
                ->get();
      }
      $books->load([
          'customer',
          'payments',
          'room.type'
      ]);

      foreach ($books as $key => $book) {
        if ($book->type_book != 7) {
          $totales["total"] += $book->total_price;
          $totales["costeApto"] += $book->cost_apto;
          $totales["costePark"] += $book->cost_park;
          if ($book->room->luxury == 1) {
            $costTotal = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->cost_limp + $book->PVPAgencia;
            $totales["costeLujo"] += $book->cost_lujo;
            $totales["coste"] += $costTotal;
          } else {
            $costTotal = $book->cost_apto + $book->cost_park + 0 + $book->cost_limp + $book->PVPAgencia;
            $totales["costeLujo"] += 0;
            $totales["coste"] += $costTotal;
          }

          $totales["costeLimp"] += $book->cost_limp;
          $totales["costeAgencia"] += $book->PVPAgencia;
          $totales["bancoJorge"] += $book->getPayment(2);
          $totales["bancoJaime"] += $book->getPayment(3);
          $totales["jorge"] += $book->getPayment(0);
          $totales["jaime"] += $book->getPayment(1);
          $totales["benJorge"] += $book->getJorgeProfit();
          $totales["benJaime"] += $book->getJaimeProfit();
          $totales["limpieza"] += $book->sup_limp;
          $totales["beneficio"] += $book->profit;
          $totales["stripe"] += $book->stripeCost;
          $totales['obs'] += $book->extraCost;
          $totales["pendiente"] += $book->pending;
        }
      }
      $totBooks = (count($books) > 0) ? count($books) : 1;
      $countDiasPropios = 0;
      foreach ($diasPropios as $key => $book) {
        $start = Carbon::createFromFormat('Y-m-d', $book->start);
        $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
        $countDays = $start->diffInDays($finish);

        $countDiasPropios += $countDays;
      }

      /* INDICADORES DE LA TEMPORADA */
      $data = [
          'days-ocupation' => 0,
          'total-days-season' => \App\SeasonDays::first()->numDays,
          'num-pax' => 0,
          'estancia-media' => 0,
          'pax-media' => 0,
          'precio-dia-media' => 0,
          'dias-propios' => $countDiasPropios,
          'agencia' => 0,
          'propios' => 0,
      ];

      foreach ($books as $key => $book) {

        $start = Carbon::createFromFormat('Y-m-d', $book->start);
        $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
        $countDays = $start->diffInDays($finish);

        /* Dias ocupados */
        $data['days-ocupation'] += $countDays;

        /* Nº inquilinos */
        $data['num-pax'] += $book->pax;


        if ($book->agency != 0) {
          $data['agencia'] ++;
        } else {
          $data['propios'] ++;
        }
      }

      $data['agencia'] = ($data['agencia'] / $totBooks) * 100;
      $data['propios'] = ($data['propios'] / $totBooks) * 100;

      /* Estancia media */
      $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

      /* Inquilinos media */
      $data['pax-media'] = ($data['num-pax'] / $totBooks);


      return view('backend/sales/_tableSummary', [
          'year' => $year,
          'books' => $books,
          'totales' => $totales,
          'data' => $data,
          'percentBenef' => DB::table('percent')->find(1)->percent,
      ]);
    }
  }

  public function orderByBenefCritico(Request $request) {
    $now = Carbon::now();
    $totales = [
        "total" => 0,
        "coste" => 0,
        "bancoJorge" => 0,
        "bancoJaime" => 0,
        "jorge" => 0,
        "jaime" => 0,
        "costeApto" => 0,
        "costePark" => 0,
        "costeLujo" => 0,
        "costeLimp" => 0,
        "costeAgencia" => 0,
        "benJorge" => 0,
        "benJaime" => 0,
        "pendiente" => 0,
        "limpieza" => 0,
        "beneficio" => 0,
        "stripe" => 0,
        "obs" => 0,
    ];

    if (empty($request->year)) {
      $date = Carbon::now();
      if ($date->copy()->format('n') >= 9) {
        $date = new Carbon('first day of September ' . $date->copy()->format('Y'));
      } else {
        $date = new Carbon('first day of September ' . $date->copy()->subYear()->format('Y'));
      }
    } else {
      $year = Carbon::createFromFormat('Y', $request->year);
      $date = $year->copy();
    }

    $date = new Carbon('first day of September ' . $date->copy()->format('Y'));


    if ($request->searchString != "") {
      $customers = \App\Customers::where('name', 'LIKE', '%' . $request->searchString . '%')->get();

      if (count($customers) > 0) {
        $arrayCustomersId = [];
        foreach ($customers as $key => $customer) {
          if (!in_array($customer->id, $arrayCustomersId)) {
            $arrayCustomersId[] = $customer->id;
          }
        }

        if ($request->searchRoom && $request->searchRoom != "all") {

          $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>=', $date->format('Y-m-d'))
                  ->where('start', '<=', $date->copy()->addYear()->subMonth()->format('Y-m-d'))
                  ->whereIn('type_book', [
                      2,
                      7,
                      8
                  ])
                  ->where('room_id', $request->searchRoom)
                  ->where('agency', $request->searchAgency)
                  ->orderBy('start', 'ASC')
                  ->get();

          $diasPropios = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>', $date->copy()->subMonth())
                  ->where('finish', '<', $date->copy()->addYear())
                  ->whereIn('type_book', [
                      7,
                      8
                  ])
                  ->where('room_id', $request->searchRoom)
                  ->where('agency', $request->searchAgency)
                  ->orderBy('created_at', 'DESC')
                  ->get();
        } else {
          $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>=', $date->format('Y-m-d'))
                  ->where('start', '<=', $date->copy()->addYear()->subMonth()->format('Y-m-d'))
                  ->whereIn('type_book', [
                      2,
                      7,
                      8
                  ])
                  ->where('agency', $request->searchAgency)
                  ->orderBy('start', 'ASC')
                  ->get();

          $diasPropios = \App\Book::whereIn('customer_id', $arrayCustomersId)
                  ->where('start', '>', $date->copy()->subMonth())
                  ->where('finish', '<', $date->copy()->addYear())
                  ->whereIn('type_book', [
                      7,
                      8
                  ])
                  ->where('agency', $request->searchAgency)
                  ->orderBy('created_at', 'DESC')
                  ->get();
        }

        $books->load([
            'customer',
            'payments',
            'room.type'
        ]);


        foreach ($books as $key => $book) {

          // if($book->type_book != 7 && $book->type_book != 8){
          $totales["total"] += $book->total_price;
          $totales["costeApto"] += $book->cost_apto;
          $totales["costePark"] += $book->cost_park;
          if ($book->room->luxury == 1) {
            $costTotal = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->cost_limp + $book->PVPAgencia;
            $totales["costeLujo"] += $book->cost_lujo;
            $totales["coste"] += $costTotal;
          } else {
            $costTotal = $book->cost_apto + $book->cost_park + 0 + $book->cost_limp + $book->PVPAgencia;
            $totales["costeLujo"] += 0;
            $totales["coste"] += $costTotal;
          }

          $totales["costeLimp"] += $book->cost_limp;
          $totales["costeAgencia"] += $book->PVPAgencia;
          $totales["bancoJorge"] += $book->getPayment(2);
          $totales["bancoJaime"] += $book->getPayment(3);
          $totales["jorge"] += $book->getPayment(0);
          $totales["jaime"] += $book->getPayment(1);
          $totales["benJorge"] += $book->getJorgeProfit();
          $totales["benJaime"] += $book->getJaimeProfit();
          $totales["limpieza"] += $book->sup_limp;
          $totales["beneficio"] += $book->profit;
          $totales["stripe"] += $book->stripeCost;
          $totales["obs"] += $book->extraCost;
          $totales["pendiente"] += $book->pending;
          // }
        }

        $totBooks = (count($books) > 0) ? count($books) : 1;
        $countDiasPropios = 0;
        foreach ($diasPropios as $key => $book) {
          $start = Carbon::createFromFormat('Y-m-d', $book->start);
          $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
          $countDays = $start->diffInDays($finish);

          $countDiasPropios += $countDays;
        }

        /* INDICADORES DE LA TEMPORADA */
        $data = [
            'days-ocupation' => 0,
            'total-days-season' => \App\SeasonDays::first()->numDays,
            'num-pax' => 0,
            'estancia-media' => 0,
            'pax-media' => 0,
            'precio-dia-media' => 0,
            'dias-propios' => $countDiasPropios,
            'agencia' => 0,
            'propios' => 0,
        ];

        foreach ($books as $key => $book) {

          $start = Carbon::createFromFormat('Y-m-d', $book->start);
          $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
          $countDays = $start->diffInDays($finish);

          /* Dias ocupados */
          $data['days-ocupation'] += $countDays;

          /* Nº inquilinos */
          $data['num-pax'] += $book->pax;


          if ($book->agency != 0) {
            $data['agencia'] ++;
          } else {
            $data['propios'] ++;
          }
        }

        $data['agencia'] = ($data['agencia'] / $totBooks) * 100;
        $data['propios'] = ($data['propios'] / $totBooks) * 100;

        /* Estancia media */
        $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

        /* Inquilinos media */
        $data['pax-media'] = ($data['num-pax'] / $totBooks);


        return view('backend/sales/_tableSummary', [
            'books' => $books,
            'totales' => $totales,
            'data' => $data,
            'percentBenef' => DB::table('percent')->find(1)->percent,
            'temporada' => $date
        ]);
      } else {
        return "<h2>No hay reservas para este término '" . $request->searchString . "'</h2>";
      }
    } else {

      if ($request->searchRoom && $request->searchRoom != "all") {

        $books = \App\Book::where('start', '>=', $date)
                ->where('start', '<=', $date->copy()->addYear()->subMonth())
                ->whereIn('type_book', [
                    2,
                    7,
                    8
                ])
                ->where('room_id', $request->searchRoom)
                ->where('agency', $request->searchAgency)
                ->orderBy('start', 'ASC')
                ->get();

        $diasPropios = \App\Book::where('start', '>', $date->copy()->subMonth())
                ->where('room_id', $request->searchRoom)
                ->where('finish', '<', $date->copy()->addYear())
                ->whereIn('type_book', [
                    7,
                    8
                ])
                ->where('agency', $request->searchAgency)
                ->orderBy('created_at', 'DESC')
                ->get();
      } else {
        $books = \App\Book::where('start', '>=', $date)
                ->where('start', '<=', $date->copy()->addYear()->subMonth())
                ->whereIn('type_book', [
                    2,
                    7,
                    8
                ])
                ->where('agency', $request->searchAgency)
                ->orderBy('start', 'ASC')
                ->get();

        $diasPropios = \App\Book::where('start', '>', $date->copy()->subMonth())
                ->where('finish', '<', $date->copy()->addYear())
                ->whereIn('type_book', [
                    7,
                    8
                ])
                ->where('agency', $request->searchAgency)
                ->orderBy('created_at', 'DESC')
                ->get();
      }


      foreach ($books as $key => $book) {
        // if($book->type_book != 7 && $book->type_book != 8){
        $totales["total"] += $book->total_price;
        $totales["costeApto"] += $book->cost_apto;
        $totales["costePark"] += $book->cost_park;
        if ($book->room->luxury == 1) {
          $costTotal = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->cost_limp + $book->PVPAgencia;
          $totales["costeLujo"] += $book->cost_lujo;
          $totales["coste"] += $costTotal;
        } else {
          $costTotal = $book->cost_apto + $book->cost_park + 0 + $book->cost_limp + $book->PVPAgencia;
          $totales["costeLujo"] += 0;
          $totales["coste"] += $costTotal;
        }

        $totales["costeLimp"] += $book->cost_limp;
        $totales["costeAgencia"] += $book->PVPAgencia;
        $totales["bancoJorge"] += $book->getPayment(2);
        $totales["bancoJaime"] += $book->getPayment(3);
        $totales["jorge"] += $book->getPayment(0);
        $totales["jaime"] += $book->getPayment(1);
        $totales["benJorge"] += $book->getJorgeProfit();
        $totales["benJaime"] += $book->getJaimeProfit();
        $totales["limpieza"] += $book->sup_limp;
        $totales["beneficio"] += $book->profit;
        $totales["stripe"] += $book->stripeCost;
        $totales["obs"] += $book->extraCost;
        $totales["pendiente"] += $book->pending;
        // }
      }
      $totBooks = (count($books) > 0) ? count($books) : 1;
      $countDiasPropios = 0;
      foreach ($diasPropios as $key => $book) {
        $start = Carbon::createFromFormat('Y-m-d', $book->start);
        $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
        $countDays = $start->diffInDays($finish);

        $countDiasPropios += $countDays;
      }

      /* INDICADORES DE LA TEMPORADA */
      $data = [
          'days-ocupation' => 0,
          'total-days-season' => \App\SeasonDays::first()->numDays,
          'num-pax' => 0,
          'estancia-media' => 0,
          'pax-media' => 0,
          'precio-dia-media' => 0,
          'dias-propios' => $countDiasPropios,
          'agencia' => 0,
          'propios' => 0,
      ];

      foreach ($books as $key => $book) {

        $start = Carbon::createFromFormat('Y-m-d', $book->start);
        $finish = Carbon::createFromFormat('Y-m-d', $book->finish);
        $countDays = $start->diffInDays($finish);

        /* Dias ocupados */
        $data['days-ocupation'] += $countDays;

        /* Nº inquilinos */
        $data['num-pax'] += $book->pax;


        if ($book->agency != 0) {
          $data['agencia'] ++;
        } else {
          $data['propios'] ++;
        }
      }

      $data['agencia'] = ($data['agencia'] / $totBooks) * 100;
      $data['propios'] = ($data['propios'] / $totBooks) * 100;

      /* Estancia media */
      $data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

      /* Inquilinos media */
      $data['pax-media'] = ($data['num-pax'] / $totBooks);

      return view('backend/sales/_tableSummary', [
          'books' => $books,
          'totales' => $totales,
          'data' => $data,
          'percentBenef' => DB::table('percent')->find(1)->percent,
          'temporada' => $date
      ]);
    }
  }

  public function changePercentBenef(Request $request, $val) {
    DB::table('percent')->where('id', 1)->update(['percent' => $val]);
    return "Cambiado";
  }

  public function exportExcel(Request $request) {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);

    if ($request->searchString != "") {
      $customers = \App\Customers::where('name', 'LIKE', '%' . $request->searchString . '%')->get();

      if (count($customers) > 0) {
        $arrayCustomersId = [];
        foreach ($customers as $key => $customer) {
          if (!in_array($customer->id, $arrayCustomersId)) {
            $arrayCustomersId[] = $customer->id;
          }
        }


        if ($request->searchRoom && $request->searchRoom != "all") {

          $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                          ->where('start', '>=', $startYear)
                          ->where('start', '<=', $endYear)
                          ->whereIn('type_book', [
                              2,
                              7,
                              8
                          ])->where('room_id', $request->searchRoom)->orderBy('start', 'ASC')->get();
        } else {

          $books = \App\Book::whereIn('customer_id', $arrayCustomersId)
                          ->where('start', '>=', $startYear)
                          ->where('start', '<=', $endYear)
                          ->whereIn('type_book', [
                              2,
                              7,
                              8
                          ])->orderBy('start', 'ASC')->get();
        }
      }
    } else {

      if ($request->searchRoom != "all") {

        $books = \App\Book::where('start', '>=', $startYear)
                        ->where('start', '<=', $endYear)
                        ->whereIn('type_book', [
                            2,
                            7,
                            8
                        ])->where('room_id', $request->searchRoom)->orderBy('start', 'ASC')->get();
      } else {

        $books = \App\Book::where('start', '>=', $startYear)
                        ->where('start', '<=', $endYear)
                        ->whereIn('type_book', [
                            2,
                            7,
                            8
                        ])->orderBy('start', 'ASC')->get();
      }
    }
    Excel::create('Liquidacion ' . $year->year, function ($excel) use ($books) {

      $excel->sheet('Liquidacion', function ($sheet) use ($books) {

        $sheet->loadView('backend.sales._tableExcelExport', ['books' => $books]);
      });
    })->download('xlsx');
  }

  ///////////////////////////////////////////////////////////////////////
  ///////////  LIMPIEZA AREA        ////////////////////////////////////

  /**
   * Get Limpieza index
   * 
   * @return type
   */
  public function limpiezas() {

    $year = $this->getActiveYear();

    $obj1 = $this->getMonthlyLimpieza($year);
    $year2 = $this->getYearData($year->year - 1);
    $obj2 = $this->getMonthlyLimpieza($year2);
    $year3 = $this->getYearData($year2->year - 1);
    $obj3 = $this->getMonthlyLimpieza($year3);

    return view('backend/sales/limpiezas', [
        'year' => $year,
        'selected' => $obj1['selected'],
        'months_obj' => $obj1['months_obj'],
        'months_1' => $obj1,
        'months_2' => $obj2,
        'months_3' => $obj3]
    );
  }

  /**
   * Get Limpieza Objet by Year Object
   * 
   * @param Object $year
   * @return array
   */
  private function getMonthlyLimpieza($year) {


    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;
    $thisMonth = date('m');
    //get the books to these date range
    $lstBooks = \App\Book::type_book_sales()
            ->where('start', '>=', $startYear)
            ->where('start', '<=', $endYear)
            ->get();

    $arrayMonth = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $arrayMonthMin = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];
    $lstMonthlyCost = [];
    foreach ($lstBooks as $book) {
      $date = Carbon::createFromFormat('Y-m-d', $book->start);
      $cMonth = intval($date->format('n'));
      if (isset($lstMonthlyCost[$cMonth])) {
        $lstMonthlyCost[$cMonth] += floatval($book->cost_limp);
      } else {
        $lstMonthlyCost[$cMonth] = floatval($book->cost_limp);
      }
    }

    //Prepare objets to JS Chars
    $months_lab = '';
    $months_val = [];
    $months_obj = [];
    $aux = $startYear->format('n');
    $auxY = $startYear->format('y');
    $selected = null;
    for ($i = 0; $i < $diff; $i++) {
      $c_month = $aux + $i;
      if ($c_month > 12) {
        $c_month -= 12;
      }
      if ($c_month == 12) {
        $auxY++;
      }

      if ($thisMonth == $c_month) {
        $selected = "$auxY,$c_month";
      }

      $months_lab .= "'" . $arrayMonth[$c_month - 1] . "',";
      if (!isset($lstMonthlyCost[$c_month])) {
        $months_val[] = 0;
      } else {
        $months_val[] = $lstMonthlyCost[$c_month];
      }
      //Only to the Months select
      $months_obj[] = [
          'id' => $auxY . '_' . $c_month,
          'month' => $c_month,
          'year' => $auxY,
          'name' => $arrayMonthMin[$c_month - 1]
      ];
    }

    return [
        'year' => $year->year,
        'selected' => $selected,
        'months_obj' => $months_obj,
        'months_label' => $months_lab,
        'months_val' => implode(',', $months_val)
    ];
  }

  /**
   * Get the Limpieza by month-years to ajax table
   * 
   * @param Request $request
   * @return Json-Objet
   */
  public function get_limpiezas(Request $request, $isAjax = true) {

    $year = $request->input('year', null);
    $month = $request->input('month', null);
    if (!$year || !$month) {
      return response()->json(['status' => 'wrong']);
    }
    // First day of a specific month
    $d = new \DateTime($year . '-' . $month . '-01');
    $d->modify('first day of this month');
    $startYear = $d->format('Y-m-d');
    // First day of a specific month
    $d = new \DateTime($year . '-' . $month . '-01');
    $d->modify('last day of this month');
    $endYear = $d->format('Y-m-d');

    $month_cost = 0;
    $monthly = \App\Expenses::where('date', '=', $startYear)
            ->where('type', 'LIMPIEZA')
            ->where('concept', 'LIMPIEZA MENSUAL')
            ->first();
    if ($monthly) {
      $month_cost = $monthly->import;
    }


    $lstBooks = \App\Book::type_book_sales()->where('start', '>=', $startYear)
                    ->where('start', '<=', $endYear)
                    ->orderBy('start', 'ASC')->get();


    $respo_list = [];
    $total_limp = $month_cost; //start with the monthly cost
    $total_extr = 0;

    foreach ($lstBooks as $key => $book) {
      $agency = ($book->agency != 0) ? '/pages/' . strtolower($book->getAgency($book->agency)) . '.png' : null;
      $type_book = null;
      switch ($book->type_book) {
        case 2:
          $type_book = "C";
          break;
        case 7:
          $type_book = "P";
          break;
        case 8:
          $type_book = "A";
          break;
      }

      $start = Carbon::createFromFormat('Y-m-d', $book->start);
      $finish = Carbon::createFromFormat('Y-m-d', $book->finish);


      $respo_list[] = [
          'id' => $book->id,
          'name' => $book->customer->name,
          'agency' => $agency,
          'type' => $type_book,
          'limp' => $book->cost_limp,
          'extra' => $book->extraCost,
          'pax' => $book->pax,
          'apto' => $book->room->nameRoom,
          'check_in' => $start->formatLocalized('%d %b'),
          'check_out' => $finish->formatLocalized('%d %b'),
          'nigths' => $book->nigths
      ];

      $total_limp += floatval($book->cost_limp);
      $total_extr += floatval($book->extraCost);
    }

    $response = [
        'status' => 'true',
        'month_cost' => $month_cost,
        'respo_list' => $respo_list,
        'total_limp' => $total_limp,
        'total_extr' => $total_extr,
    ];
    if ($isAjax) {
      return response()->json($response);
    } else {
      return $response;
    }
  }

  /**
   * Update Limpieza or Extra values
   * 
   * @param Request $request
   * @return json
   */
  public function upd_limpiezas(Request $request) {
    $id = $request->input('id', null);
    $limp_value = $request->input('limp_value', null);
    $extr_value = $request->input('extr_value', null);
    $year = $request->input('year', null);
    $month = $request->input('month', null);

    if ($id) {
      if ($id == 'fix') {
        $dateTime = new \DateTime($year . '-' . $month . '-01');
        $date = $dateTime->format('Y-m-d');
        $monthItem = \App\Expenses::where('date', '=', $date)
                ->where('type', 'LIMPIEZA')
                ->where('concept', 'LIMPIEZA MENSUAL')
                ->first();

        if ($monthItem) {
          $monthItem->import = floatval($limp_value);
          $monthItem->save();
        } else {
          $monthItem = new \App\Expenses();
          $monthItem->type = 'LIMPIEZA';
          $monthItem->concept = 'LIMPIEZA MENSUAL';
          $monthItem->comment = 'LIMPIEZA MENSUAL';
          $monthItem->date = $date;
          $monthItem->import = floatval($limp_value);
          $monthItem->save();
        }
        return response()->json(['status' => 'true']);
      } else {
        if (!(is_numeric($limp_value) || empty($limp_value))) {
          return response()->json(['status' => 'false', 'msg' => "El valor de la Limpieza debe ser numérico"]);
        }
        if (!(is_numeric($extr_value) || empty($extr_value))) {
          return response()->json(['status' => 'false', 'msg' => "El valor Extra debe ser numérico"]);
        }
        $book = \App\Book::find($id);
        if ($book) {

          $book->cost_limp = floatval($limp_value);
          $book->extraCost = floatval($extr_value);
          $book->save();

          return response()->json(['status' => 'true']);
        }
      }
    }

    return response()->json(['status' => 'false', 'msg' => "No se han encontrado valores."]);
  }

  public function export_pdf_limpiezas(Request $request) {

    $year = $request->input('year', null);
    $month = $request->input('month', null);

    $arrayMonth = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $file_name = 'Costos-de-limpieza-' . $month . '-20' . $year;
    if (isset($arrayMonth[$month - 1])) {
      $title = $arrayMonth[$month - 1] . ' 20' . $year;
    } else {
      $title = ' 20' . $year;
    }
    $data = $this->get_limpiezas($request, false);
    $data['tit'] = $title;

    // Send data to the view using loadView function of PDF facade
    $pdf = \PDF::loadView('pdf.limpieza', $data);
    // Finally, you can download the file using download function
    return $pdf->download($file_name . '.pdf');
  }

  ///////////  LIMPIEZA AREA        ////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////
  
  
    private function getBookingAgencyDetailsBy_date($start,$end) {
        
      $start = new Carbon($start);
      $end   = new Carbon($end);
      
      $dataNode = [
            'reservations'      => 0,
            'total'             => 0,
            'commissions'       => 0,
            'reservations_rate' => 0,
            'total_rate'        => 0
        ];
      $data = [
                'fp'   => $dataNode, //  FAST PAYMENT
                'vd'   => $dataNode, // V. Directa
                'b'    => $dataNode, //Booking
                't'    => $dataNode, // Trivago
                'bs'   => $dataNode, // Bed&Snow
                'ab'   => $dataNode, // AirBnb
                'jd'   => $dataNode, // "Jaime Diaz",
                'se'   => $dataNode, // S.essence
                'c'   => $dataNode, //Cerogrados
                'none'   => $dataNode, // none
            ];
      
      $totals = ['total' => 0,'reservations' => 0,'commissions' => 0];
      $books = \App\Book::type_book_sales()->with('payments')
            ->where('start', '>=', $start)
            ->where('start', '<=', $end)->get();
      if ($books){
      foreach ($books as $book){
        $agency_name = 'none';
        switch ($book->agency){
          case 1: $agency_name = 'b';  break;
          case 2: $agency_name = 't';  break;
          case 3: $agency_name = 'bs';  break;
          case 4: $agency_name = 'ab';  break;
          case 5: $agency_name = 'jd';  break;
          case 6: $agency_name = 'se';  break;
          case 7: $agency_name = 'c';  break;
          default :
          
            if ($book->type_book == 99 || $book->is_fastpayment) // fastpayment
                $agency_name = 'fp';
            else
              $agency_name = 'vd';
            
          break;
        }
        $t = round(floatval($book->total_price), 2);
        $data[$agency_name]['total']        += $t;
        $data[$agency_name]['reservations'] += 1;
        $data[$agency_name]['commissions']  += str_replace(',', '.', $book->PVPAgencia);
        $totals['total']        += $t;
        $totals['reservations'] += 1;
        $totals['commissions']  += str_replace(',', '.', $book->PVPAgencia);
        
        
        }
        
        foreach ($data as $a=>$d){
          if ($d['reservations']>0 && $totals['reservations']>0)
            $data[$a]['reservations_rate'] = $d['reservations']/$totals['reservations']*100;
          if ($d['total']>0 && $totals['total']>0)
            $data[$a]['total_rate'] = $d['total']/$totals['total']*100;
        }
        
      }
      return  ['totals' => $totals,'data'=>$data];
    }
    
    public function getBookingAgencyDetails()
    {
        $agencyBooks    = [
            'years'  => [],
            'data'   => [],
            'items'  => [
                'fp'   => 'FAST PAYMENT',
                'vd'   => 'V. Directa',
                'b'    => 'Booking',
                't'    => 'Trivago',
                'bs'   => 'Bed&Snow',
                'ab'   => 'AirBnb',
                'jd'   => "Jaime Diaz",
                'se'   => 'S.essence',
                'c'   => 'Cerogrados',
            ],
            'totals' => []
        ];
                  
        $yearLst = [];
        $aux = [];
       
        $season    = self::getActiveYear();
        $yearFull  = $season->year;
        $yearLst[] = $yearFull;
        $year = $yearFull-2000;
        $dataSeason = $this->getBookingAgencyDetailsBy_date($season->start_date,$season->end_date);
        $agencyBooks['years'][$yearFull]    = $year . '-' . ($year + 1);
        $aux[$yearFull]     = $dataSeason['data'];
        $agencyBooks['totals'][$yearFull]   = $dataSeason['totals'];
        
      
        $season    = self::getYearData($yearFull-1);
        $yearFull  = $season->year;
        $yearLst[] = $yearFull;
        $year = $yearFull-2000;
        $dataSeason = $this->getBookingAgencyDetailsBy_date($season->start_date,$season->end_date);
        $agencyBooks['years'][$yearFull]    = $year . '-' . ($year + 1);
        $aux[$yearFull]     = $dataSeason['data'];
        $agencyBooks['totals'][$yearFull]   = $dataSeason['totals'];
        
        $season    = self::getYearData($yearFull-1);
        $yearFull  = $season->year;
        $yearLst[] = $yearFull;
        $year = $yearFull-2000;
        $dataSeason = $this->getBookingAgencyDetailsBy_date($season->start_date,$season->end_date);
        $agencyBooks['years'][$yearFull]    = $year . '-' . ($year + 1);
        $aux[$yearFull]     = $dataSeason['data'];
        $agencyBooks['totals'][$yearFull]   = $dataSeason['totals'];
        sort($yearLst);
        
        
        foreach ($agencyBooks['items'] as $k=>$n){
          $agencyBooks['data'][$k] = [];
          foreach ($yearLst as $y){
            if (isset($aux[$y])){
              $agencyBooks['data'][$k][$y] = $aux[$y][$k];
            }
          }
        }
//        dd($yearLst,$agencyBooks);
        echo json_encode(array(
                             'status'      => 'true',
                             'agencyBooks' => $agencyBooks,
                             'yearLst' => $yearLst,
                         ));
    }
}
