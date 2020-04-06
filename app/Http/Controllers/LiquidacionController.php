<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use \DB;
use App\Classes\Mobile;
use App\Book;
use Excel;
use Auth;
use App\Models\Forfaits\Forfaits;
use App\Models\Forfaits\ForfaitsOrderPayments;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");

class LiquidacionController extends AppController {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {

    $data = $this->getTableData();

    $data['stripeCost'] = $this->getTPV($data['books']);
    $data['total_stripeCost'] = array_sum($data['stripeCost']);
    if (Auth::user()->role == "subadmin"){
      return view('backend/sales/index-subadmin', $data);
    }

    return view('backend/sales/index', $data);
  }

  public function getFF_Data($startYear,$endYear) {
    $allForfaits = Forfaits::where('status','!=',1)
            ->where('created_at', '>=', $startYear)->where('created_at', '<=', $endYear)->get();
      
    $totalPrice = $forfaits = $totalToPay = $totalToPay = 0;
    $forfaitsIDs = $ordersID = $common_ordersID = array();
    if ($allForfaits){
      foreach ($allForfaits as $forfait){
        $allOrders = $forfait->orders()->get();
        if ($allOrders){
          foreach ($allOrders as $order){
            if ($order->status == 3){
              continue; //ORder cancel
            }

            if (!$order->quick_order){
              $common_ordersID[] = $order->id;
            }
            $totalPrice += $order->total;
            $ordersID[] = $order->id;
          }
        }
        $forfaitsIDs[] = $forfait->id;
      }
      /*--------------------------------*/
      if (count($ordersID)>0){
        $totalPayment = ForfaitsOrderPayments::whereIn('order_id', $ordersID)->where('paid',1)->sum('amount');
        if ($totalPayment>0){
          $totalPayment = $totalPayment/100;
        }
        $totalPayment2 =  ForfaitsOrderPayments::whereIn('forfats_id', $forfaitsIDs)->where('paid',1)->sum('amount');

        if ($totalPayment2>0){
          $totalPayment += $totalPayment2/100;
        }
      }
      $totalToPay = $totalPrice - $totalPayment;
     //---------------------------------------------------------/ 
    }
    
    if ($totalToPay<0) $totalToPay = 0;
    return [
        'q'=>count($ordersID),
        'to_pay'=>$totalToPay,
        'total'=>$totalPrice,
        'pay'=>$totalPayment
    ];
      
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

  public function prepareTables() {
      
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    $lstMonths = lstMonths($startYear,$endYear);
    
    $books = \App\Book::where_type_book_sales(true)->with('payments')
            ->where('start', '>=', $startYear)
            ->where('start', '<=', $endYear)->get();
    
    
    $months_empty = array();
    $months_empty[0] = 0;
    foreach ($lstMonths as $k=>$v) $months_empty[$k] = 0;
    
        
    $aptos = configZodomusAptos();
    $channels = [];
    $chRooms = [];
    foreach ($aptos as $k => $item) 
    {
      $channels[$k] = $item->name;
    }

    foreach ($channels as $k=>$v){
      $rooms = \App\Rooms::where('state', 1)->where('channel_group',$k)->orderBy('order', 'ASC')->get();
      foreach ($rooms as $r){
        $channel_group = $r->channel_group;
        if (!isset($chRooms[$channel_group])){
          $chRooms[$channel_group] = [
              'rooms'=>[],
              'channel'=>$v,
              'months'=>$months_empty
              ];
        }

        $chRooms[$channel_group]['rooms'][$r->id] = $r->name;
      }
    }
    $sales_rooms = [];
    foreach ($books as $key => $book) {
      $date = date('ym', strtotime($book->start));
      $value = $book->total_price;
      if (!isset($sales_rooms[$book->room_id])) $sales_rooms[$book->room_id] = [];
      if (!isset($sales_rooms[$book->room_id][$date])) $sales_rooms[$book->room_id][$date] = 0;
      $sales_rooms[$book->room_id][$date] += $book->total_price;
      
    }
    //group Rooms
    foreach ($chRooms as $ch=>$d1){
      $auxMonth = $months_empty;
      foreach ($d1['rooms'] as $roomID=>$d3 ){
        if (isset($sales_rooms[$roomID])){
          foreach ($sales_rooms[$roomID] as $date=>$total ){
            $auxMonth[$date] += $total;
            $auxMonth[0] += $total;
          }
        }
        $chRooms[$ch]['months'] = $auxMonth;
      }
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
    return [
        'books' => $books,
        'lstMonths' => $lstMonths,
        't_room_month' => $t_room_month,
        'months_empty' => $months_empty,
        'year' => $year,
        'sales_rooms' => $sales_rooms,
        't_rooms' => $t_rooms,
        't_room_month' => $t_room_month,
        't_all_rooms' => $t_all_rooms,
        'chRooms' => $chRooms,
        'channels' => $channels,
        'startYear' => $startYear,
        'endYear' => $endYear,
        ];
  }
  
  public function contabilidad() {
    
    $data = $this->prepareTables();
    $months_empty = $data['months_empty'];
    $lstMonths = $data['lstMonths'];
    $t_room_month = $data['t_room_month'];
    $year = $data['year'];
    $channels = $data['channels'];
    $chRooms = $data['chRooms'];
    $sales_rooms = $data['sales_rooms'];
    $books = $data['books'];
    $startYear = $data['startYear'];
    $endYear = $data['endYear'];
    $diff = $startYear->diffInMonths($endYear) + 1;
    
    
    /* INDICADORES DE LA TEMPORADA */
    
    $countDiasPropios = \App\Book::where('start', '>', $startYear)
                  ->where('finish', '<', $endYear)
                  ->whereIn('type_book', [7,8])
                  ->sum('nigths');
    
    
    $dataResume = [
        'days-ocupation' => 0,
        'total-days-season' => \App\SeasonDays::first()->numDays,
        'num-pax' => 0,
        'estancia-media' => 0,
        'pax-media' => 0,
        'precio-dia-media' => 0,
        'dias-propios' => $countDiasPropios,
        'agencia' => 0,
        'propios' => 0,
        'total_price' =>0,
        'beneficio' =>0,
    ];

        
    $cobrado = $metalico = $banco = $vendido = $vta_prop = 0;
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
//      if (!isset($sales_rooms[$book->room_id])) $sales_rooms[$book->room_id] = [];
//      if (!isset($sales_rooms[$book->room_id][$date])) $sales_rooms[$book->room_id][$date] = 0;
//      $sales_rooms[$book->room_id][$date] += $book->total_price;
      $vendido += $book->total_price;
      
      $dataResume['total_price'] += $book->total_price;
      $dataResume['beneficio']   += $book->profit;
      
      /* Dias ocupados */
      $dataResume['days-ocupation'] += $book->nigths;

      /* Nº inquilinos */
      $dataResume['num-pax'] += $book->pax;


      if ($book->agency != 0) {
        $dataResume['agencia']++;
      } else {
        $dataResume['propios']++;
        $vta_prop += $book->total_price;
      }
    }
    $totBooks = count($books);
//    echo $vta_prop.' - '.$vendido;
    $dataResume['agencia'] = ($vta_prop / $vendido) * 100;
    $dataResume['propios'] = 100 - $dataResume['agencia'];
    $dataResume['estancia-media'] = ($dataResume['days-ocupation'] / $totBooks);
    //First chart PVP by months
    $dataChartMonths = [];
    
    foreach ($lstMonths as $k=>$v){
      $val = isset($t_room_month[$k]) ? $t_room_month[$k] : 0;
      $dataChartMonths[getMonthsSpanish($v['m'])] = $val;
    }
    //*******************************************************************//
    $ffData = $this->getFF_Data($startYear,$endYear);
//    dd($ffData);
    $months_ff = null;
    $cachedRepository  = new \App\Repositories\CachedRepository();
    $ForfaitsItemController = new \App\Http\Controllers\ForfaitsItemController($cachedRepository);
    $months_obj = $ForfaitsItemController->getMonthlyData($year);
    if ($months_obj){
      $months_ff = $months_obj['months_obj'];
    }
    //*******************************************************************//
    
    
    
     /// BEGIN: Disponibilidad
    $book = new \App\Book();
    $ch_monthOcup = array();
    $ch_monthOcupPercent = array();
    $monthsDays = $months_empty;
    foreach ($lstMonths as $k=>$v){
      $monthsDays[$k] = cal_days_in_month(CAL_GREGORIAN, $v['m'],$v['y']);
    }
    foreach ($channels as $ch=>$d){
      $ch_monthOcup[$ch] = $months_empty;
      $ch_monthOcupPercent[$ch] = $months_empty;
      $availibility = $book->getAvailibilityBy_channel($ch, $startYear, $endYear,true);
      
      foreach ($availibility[0] as $day=>$used){
        if ($used>0){
         $ch_monthOcup[$ch][date('ym', strtotime($day))] += $used;
        }
      }
      foreach ($ch_monthOcup[$ch] as $k=>$avail){
      
        if ($k>0){
         
          $aux = $availibility[1]*$monthsDays[$k];
          if ($aux>0){   
            $aux2 = $aux-$avail;
//            $ch_monthOcupPercent[$ch][$k] = $avail.'+'.$aux;
            $ch_monthOcupPercent[$ch][$k] = round($aux2/$aux*100);
          }
        }
      }
    }
    // END: Disponibilidad
    ///////////////////////////////
    
    return view('backend/sales/contabilidad', [
        'year' => $year,
        'diff' => $diff,
        'sales_rooms' => $sales_rooms,
        'lstMonths' => $lstMonths,
        't_rooms' => $data['t_rooms'],
        't_room_month' => $data['t_room_month'],
        't_all_rooms' => (is_numeric($data['t_all_rooms']) && $data['t_all_rooms']>0) ? $data['t_all_rooms']:1,
        't_room_month' => $t_room_month,
        'cobrado' => $cobrado,
        'months_extras' => null,
        'metalico' =>$metalico,
        'banco' =>$banco,
        'vendido'=>$vendido,
        'chRooms'=>$chRooms,
        'dataChartMonths' => $dataChartMonths,
        'ffData'=>$ffData,
        'months_ff' => $months_ff,
        'ch_monthOcupPercent' => $ch_monthOcupPercent,
        't_book' => $totBooks,
        'dataResume' => $dataResume,
        ]);
  }

   public function gastos($current=null) {
    
    $year = $this->getActiveYear();
    
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;
    $lstMonths = lstMonths($startYear,$endYear);
    
    if (!$current){
      $current = ($year->year-2000).',0';
    }
    
    $months_empty = array();
    foreach ($lstMonths as $k=>$v){
      $months_empty[$k] = 0;
    }
    $months_empty[0] = 0;
    
    $yearMonths = [
        ($year->year)-2 => [],
        ($year->year)-1 => [],
        ($year->year) => $months_empty,
    ];
    
    $oRooms = \App\Rooms::where('state', 1)->orderBy('nameRoom', 'ASC')->get();
    $aptos = [];
    foreach ($oRooms as $r){
      $aptos[$r->id] = $r->nameRoom;
    }
     
    $gastos = \App\Expenses::where('date', '>=', $startYear)
                    ->Where('date', '<=', $endYear)
                    ->orderBy('date', 'DESC')->get();

    $gType = \App\Expenses::getTypes();
    $gTypeGroup = \App\Expenses::getTypesGroup();
    $gTypeGroup_g = $gTypeGroup['groups'];
    
    $listGastos = array();
    $listGastos_g = array();
    if ($gTypeGroup_g){
      foreach ($gTypeGroup_g as $k=>$v){
        $listGastos_g[$v] = $months_empty;
      }
      $listGastos_g['otros'] = $months_empty;
    }
    if ($gType){
      foreach ($gType as $k=>$v){
        $listGastos[$k] = $months_empty;
      }
    }
    $totalYearAmount = 0;
    
    if ($gastos){
      foreach ($gastos as $g){
        $month = date('ym', strtotime($g->date));
        $totalYearAmount += $g->import;
        $yearMonths[$year->year][$month] += $g->import;
        
        $gTipe = isset($gTypeGroup_g[$g->type]) ? $gTypeGroup_g[$g->type] : 'otros';
        
        if (isset($listGastos_g[$gTipe])){
          $listGastos_g[$gTipe][$month] += $g->import;
          $listGastos_g[$gTipe][0] += $g->import;
        }
        
        if (isset($listGastos[$g->type])){
          $listGastos[$g->type][$month] += $g->import;
          $listGastos[$g->type][0] += $g->import;
        }
      }
    }
    $totalYear=[$year->year=>$totalYearAmount];
              
              
//dd($listGastos,$listGastos_g);

    
    $auxYear = ($year->year)-1;
    $totalYear[$auxYear] = 0;
    $activeYear = \App\Years::where('year', $auxYear)->first();
    $aux_startYear = new Carbon($activeYear->start_date);
    $aux_endYear = new Carbon($activeYear->end_date);
    $gastos = \App\Expenses::where('date', '>=', $aux_startYear)
                    ->Where('date', '<=', $aux_endYear)
                    ->orderBy('date', 'DESC')->get();
    
    
    $lstMonths_aux = lstMonths($aux_startYear,$aux_endYear);
    $yearMonths[$auxYear][0] = 0;
    foreach ($lstMonths_aux as $k=>$v){
      $yearMonths[$auxYear][$k] = 0;
    }
    if ($gastos){
      foreach ($gastos as $g){
        $month = date('ym', strtotime($g->date));
        if (!isset($yearMonths[$auxYear][$month])) $yearMonths[$auxYear][$month] = 0;
        $yearMonths[$auxYear][$month] += $g->import;
        $totalYear[$auxYear] += $g->import;
      }
    }
    
    $auxYear = ($year->year)-2;
    $totalYear[$auxYear] = 0;
    $activeYear = \App\Years::where('year', $auxYear)->first();
    $aux_startYear = new Carbon($activeYear->start_date);
    $aux_endYear = new Carbon($activeYear->end_date);
    $gastos = \App\Expenses::where('date', '>=', $aux_startYear)
                    ->Where('date', '<=', $aux_endYear)
                    ->orderBy('date', 'DESC')->get();
    $lstMonths_aux = lstMonths($aux_startYear,$aux_endYear);
    $yearMonths[$auxYear][0] = 0;
    foreach ($lstMonths_aux as $k=>$v){
      $yearMonths[$auxYear][$k] = 0;
    }
    if ($gastos){
      foreach ($gastos as $g){
        $month = date('ym', strtotime($g->date));
        if (isset($yearMonths[$auxYear][$month]))    $yearMonths[$auxYear][$month] += $g->import;
        $totalYear[$auxYear] += $g->import;
      }
    }
    
    
    
     //First chart PVP by months
    $dataChartMonths = [];
    foreach ($lstMonths as $k=>$v){
      $val = isset($listGastos[$k]) ? $listGastos[$k] : 0;
      $dataChartMonths[getMonthsSpanish($v['m'])] = $val;
    }
    
   
    return view('backend/sales/gastos/index', [
        'year' => $year,
        'lstMonths' => $lstMonths,
        'dataChartMonths' => $dataChartMonths,
        'gType' => $gType,
        'gTypeGroup' => $gTypeGroup['names'],
        'listGasto_g' => $listGastos_g,
        'gastos' => $listGastos,
        'current' => $current,
        'totalYear' => $totalYear,
        'total_year_amount' => $totalYearAmount,
        'yearMonths' => $yearMonths,
        'aptos' => $aptos,
        'typePayment' => \App\Expenses::getTypeCobro()
    ]);
  }

  
  public function gastoCreate(Request $request) {
    $gasto = new \App\Expenses();
    $gasto->concept = $request->input('concept');
    $gasto->date = Carbon::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d');
    $gasto->import = $request->input('import');
    $gasto->typePayment = $request->input('type_payment');
    $gasto->type = $request->input('type');
    $gasto->comment = $request->input('comment');

    if ($request->input('type_payFor') == 1) {
      $gasto->PayFor = $request->input('asig_rooms');
    }

    if ($gasto->save()) {
      return "ok";
    }
  }

  public function updateGasto(Request $request) {
    
    $id = $request->input('id');
    $type = $request->input('type');
    $val = $request->input('val');
    $gasto = \App\Expenses::find($id);
    if ($gasto){
      $save = false;
      switch ($type){
        case 'price':
          $gasto->import = $val;
          $save = true;
          break;
        case 'comm':
          $gasto->comment = $val;
          $save = true;
          break;
        case 'concept':
          $gasto->concept = $val;
          $save = true;
          break;
        case 'type':
          $gasto->type = $val;
          $save = true;
          break;
        case 'payment':
          $gasto->typePayment = $val;
          $save = true;
          break;
      }
      
      if ($save){
        if ($gasto->save()) {
          return "ok";
        }
      }
    }

    return 'error';
 
  }

  public function getTableGastos(Request $request, $isAjax = true) {
    
    $year = $request->input('year', null);
    $month = $request->input('month', null);
    if (!$year) {
      return response()->json(['status' => 'wrong']);
    }
    
    if ($year<100) $year = '20'.$year;
    
    
    if ($month) {
      $qry = \App\Expenses::whereYear('date','=', $year);
      $qry->whereMonth('date','=', $month);
    }else{
      $oYear = \App\Years::where('year', $year)->first();
      $qry = \App\Expenses::where('date', '>=', $oYear->start_date)->Where('date', '<=', $oYear->end_date);
//    dd($startYear,$endYear,$oYear);
    }
    
    
    $oRooms = \App\Rooms::all();
    $aptos = [];
    foreach ($oRooms as $r){
      $aptos[$r->id] = $r->nameRoom;
    }
    
    $gastos = $qry->orderBy('date', 'DESC')->get();
    $gType = \App\Expenses::getTypes();
    $response = [
        'status' => 'false',
        'respo_list' => [],
    ];
    $totalMounth = 0;
    $typePayment = \App\Expenses::getTypeCobro();
    if ($gastos){
      $respo_list = array();
      foreach ($gastos as $item){
        
        $lstAptos = array();
        if ($item->PayFor){
          $aux = explode(',',$item->PayFor);
          if(is_array($aux)){
            foreach ($aux as $i){
              if (isset($aptos[$i])) $lstAptos[] = $aptos[$i];
            }
          } else {
            if (isset($aptos[$aux])) $lstAptos[] = $aptos[$aux];
          }
        }
        
        
        $respo_list[] = [
            'id'=> $item->id,
            'concept'=> $item->concept,
            'date'=> convertDateToShow_text($item->date),
            'typePayment'=> isset($typePayment[$item->typePayment]) ? $typePayment[$item->typePayment] : '--',
            'typePayment_v'=> $item->typePayment,
            'type'=> isset($gType[$item->type]) ? $gType[$item->type] : '--',
            'type_v'=> $item->type,
            'comment'=> $item->comment,
            'import'=> $item->import,
            'aptos' => (count($lstAptos)>0) ? implode(', ', $lstAptos) : 'GENERICO',
            'aptos_v' => (count($lstAptos)>0) ? implode(',', $lstAptos) : 'GENERICO',
        ];
        $totalMounth += $item->import;
      }
     
      $response = [
          'status' => 'true',
          'respo_list' => $respo_list,
          'totalMounth' => moneda($totalMounth),
      ];
    }
    
    if ($isAjax) {
      return response()->json($response);
    } else {
      return $response;
    }
  }

    public function ingresos() {
      $data = $this->prepareTables();
      $months_empty = $data['months_empty'];
      $lstMonths = $data['lstMonths'];
      $t_room_month = $data['t_room_month'];
      $year = $data['year'];
      $channels = $data['channels'];
      $chRooms = $data['chRooms'];
      $sales_rooms = $data['sales_rooms'];
      $books = $data['books'];
      $startYear = $data['startYear'];
      $endYear = $data['endYear'];
      $diff = $startYear->diffInMonths($endYear) + 1;
    
    //First chart PVP by months
    $dataChartMonths = [];
    foreach ($lstMonths as $k=>$v){
      $val = isset($t_room_month[$k]) ? $t_room_month[$k] : 0;
      $dataChartMonths[getMonthsSpanish($v['m'])] = $val;
    }
    
    
    // BEGIN: Ingr X mes
    $ingrType = \App\Incomes::getTypes();
    $ingrMonths = array();
    foreach ($ingrType as $k=>$t){
      $ingrMonths[$k] = $months_empty;
    }
    
    $incomesLst = \App\Incomes::where('date', '>=', $startYear)->Where('date', '<=', $endYear)->get();
    if ($incomesLst){
      foreach ($incomesLst as $item){
        $date = date('ym', strtotime($item->date));
        $concept = isset($ingrType[$item->concept]) ? $item->concept : 'others';
        if (!isset($ingrMonths[$concept][$date])) $ingrMonths[$concept][$date] = 0;
        $ingrMonths[$concept][$date] += $item->import;
        $ingrMonths[$concept][0] += $item->import;
       
      }
    }
    
    // END: Ingr X mes
    // BEGIN: Ingr X mes
    $months_ff = null;
    $cachedRepository  = new \App\Repositories\CachedRepository();
    $ForfaitsItemController = new \App\Http\Controllers\ForfaitsItemController($cachedRepository);
    $months_obj = $ForfaitsItemController->getMonthlyData($year);
    if ($months_obj){
      $months_ff = $months_obj['months_obj'];
    }
    // END: Ingr X mes
    ///////////////////////////////
    return view('backend/sales/ingresos/index', [
        'year' => $year,
        'sales_rooms' => $data['sales_rooms'],
        'lstMonths' => $lstMonths,
        't_rooms' => $data['t_rooms'],
        't_room_month' => $data['t_room_month'],
        't_all_rooms' => (is_numeric($data['t_all_rooms']) && $data['t_all_rooms']>0) ? $data['t_all_rooms']:1,
        'dataChartMonths' => $dataChartMonths,
        'chRooms'=>$chRooms,
        'channels'=>$data['channels'],
        'ingrMonths' => $ingrMonths,
        'ingrType' => $ingrType,
        'months_ff' => $months_ff,
        ]);
  }
  
  public function ingresosUpd(Request $request) {
    
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    
    $type = $request->input('k',null);
    $val  = $request->input('val',null);
    $m    = $request->input('m',null);
    $y    = $request->input('y',null);
               
    if (!is_numeric($val)) return 'error';
    if ($y) $y += 2000;
    if ($m<10) $m = "0$m";
    
    $ingreso = \App\Incomes::where('month',$m)
              ->where('year',$y)
              ->where('concept',$type)
              ->first();
    if (!$ingreso){
      $ingreso = new \App\Incomes();
      $ingreso->concept = $type;
      $ingreso->month = $m;
      $ingreso->year  = $y;
      if ($m<10) $m = '0'.$m;
      $ingreso->date = $y.'-'.$m.'-01';
    }
    
    $ingreso->import = $val;
    if ($ingreso->save()) {
      return 'ok';
    }
    return 'error';
  }
    public function ingresosCreate(Request $request) {
    $date =  $request->input('fecha');
    $aDate = explode('/', $date);
    $month = isset($aDate[1]) ? $aDate[1] : null;
    $year  = isset($aDate[2]) ? $aDate[2] : null;
    $concept = $request->input('concept');
    $import = $request->input('import',null);
    if ($month && $import){
        $ingreso = new \App\Incomes();
        $ingreso->month = intval($month);
        $ingreso->year  = $year;
        $ingreso->concept  = $concept;
        $ingreso->date = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        $ingreso->import = $import;
      
      if ($ingreso->save()) {
        return redirect()->back();
      }
    }
    return redirect()->back()->withErrors(['No se pudo cargar el ingreso.']);
  }

  public function caja() {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $lstMonths = lstMonths($startYear,$endYear);
    $ingrType = \App\Incomes::getTypes();
    $gType = \App\Expenses::getTypes();
    
    $months_empty = array();
    for($i=0;$i<13;$i++) $months_empty[$i] = 0;
    
    $gastos = \App\Expenses::where('date', '>=', $year->start_date)
            ->where('typePayment',2)
            ->where('date', '<=', $year->end_date)->sum('import');
    $incomesLst = \App\Incomes::where('date', '>=', $year->start_date)
            ->where('date', '<=', $year->end_date)->sum('import');
    $totalYear = $incomesLst-$gastos;
   

    $current = ($year->year-2000).',0';
    
    return view('backend/sales/caja/index', [
        'year' => $year,
        'lstMonths' => $lstMonths,
        'ingrType' => $ingrType,
        'gType' => $gType,
        'current' => $current,
        'totalYear' => $totalYear,
        'page' => 'caja',
        'typePayment' => \App\Expenses::getTypeCobro()
    ]);
    
  }
  
  public function bank() {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $lstMonths = lstMonths($startYear,$endYear);
    $ingrType = \App\Incomes::getTypes();
    $gType = \App\Expenses::getTypes();
    
    $months_empty = array();
    for($i=0;$i<13;$i++) $months_empty[$i] = 0;
    
    $gastos = \App\Expenses::where('date', '>=', $year->start_date)
            ->where('typePayment','!=',2)
            ->where('date', '<=', $year->end_date)->sum('import');
    $totalYear = $gastos;

    $current = ($year->year-2000).',0';
    
    return view('backend/sales/caja/index', [
        'year' => $year,
        'lstMonths' => $lstMonths,
        'ingrType' => $ingrType,
        'gType' => $gType,
        'current' => $current,
        'totalYear' => $totalYear,
        'page' => 'banco',
        'typePayment' => \App\Expenses::getTypeCobro()
    ]);
    
  }
  /**
   * Get the Caja by month-years to ajax table
   * 
   * @param Request $request
   * @return Json-Objet
   */
  
  public function getTableCaja(Request $request, $isAjax = true) {

    $page = $request->input('page', null);
    $year = $request->input('year', null);
    $month = $request->input('month', null);
    if (!$year) {
      return response()->json(['status' => 'wrong']);
    }
    $total = 0;
    if ($year<100) $year = '20'.$year;
    
    
    

    $oRooms = \App\Rooms::all();
    $aptos = [];
    foreach ($oRooms as $r){
      $aptos[$r->id] = $r->name;
    }
    
    if ($month) {
      $qry = \App\Expenses::whereYear('date','=', $year);
      $qry->whereMonth('date','=', $month);
    }else{
      $oYear = \App\Years::where('year', $year)->first();
      $qry = \App\Expenses::where('date', '>=', $oYear->start_date)->Where('date', '<=', $oYear->end_date);
    }
    
    if(isset($page)){
      if($page =='banco'){
        $qry->whereIn('typePayment',[1,3]);
      } else {
        $qry->where('typePayment',2);
      }
      
    }
    
    
    $gastos = $qry->orderBy('date')->get();
    $gType = \App\Expenses::getTypes();
    $ingrType = \App\Incomes::getTypes();
    $response = [
        'status' => 'false',
        'respo_list' => [],
    ];
    
    $respo_list = array();
    $typePayment = Book::getTypeCobro();
    if ($gastos){
      foreach ($gastos as $item){
        
        $lstAptos = array();
        if ($item->PayFor){
          $aux = explode(',',$item->PayFor);
          if(is_array($aux)){
            foreach ($aux as $i){
              if (isset($aptos[$i])) $lstAptos[] = $aptos[$i];
            }
          } else {
            if (isset($aptos[$aux])) $lstAptos[] = $aptos[$aux];
          }
        }
        
        $total -= $item->import;
        if (!isset($respo_list[strtotime($item->date)])) $respo_list[strtotime($item->date)] = [];
        $respo_list[strtotime($item->date)][] = [
            'id'=> $item->id,
            'concept'=> $item->concept,
            'date'=> convertDateToShow_text($item->date),
            'type'=> isset($gType[$item->type]) ? $gType[$item->type] : '--',
            'haber'=> $item->import,
            'debe'=> '--',
            'comment'=> $item->comment,
            'aptos' => (count($lstAptos)>0) ? implode(', ', $lstAptos) : 'TODOS',
        ];
      }
    }
    if ($month) {
      $qry = \App\Incomes::whereYear('date','=', $year);
      $qry->whereMonth('date','=', $month);
    }else{
      $oYear = \App\Years::where('year', $year)->first();
      $qry = \App\Incomes::where('date', '>=', $oYear->start_date)->Where('date', '<=', $oYear->end_date);
    }
    
   
    if($page !=='banco'){
      $oIngr = $qry->orderBy('date')->get();

      if ($oIngr){

        foreach ($oIngr as $item){
          $total += $item->import;

          if (!isset($respo_list[strtotime($item->date)])) $respo_list[strtotime($item->date)] = [];

          $respo_list[strtotime($item->date)][] = [
              'id'=> $item->id,
              'concept'=> isset($ingrType[$item->concept]) ? $ingrType[$item->concept] : $item->concept,
              'date'=> convertDateToShow_text($item->date),
              'type'=> isset($ingrType[$item->type]) ? $ingrType[$item->type] : '--',
              'debe'=> $item->import,
              'haber'=> '--',
              'comment'=> '',
              'aptos'=> '',
          ];
        }
      }
    }
    
    $return = [];
    if (count($respo_list)>0){
      ksort($respo_list);
      foreach ($respo_list as $d => $array){
        foreach ($array as $item){
          $return[] = $item;
        }
      }
      
     $response = [
          'status' => 'true',
          'total' => moneda($total),
          'respo_list' => $return,
      ];
    }
    
     
    
    if ($isAjax) {
      return response()->json($response);
    } else {
      return $response;
    }
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
    $lstMonths = lstMonths($startYear,$endYear,'ym',true);
    $ingresos = [];
    $lstT_ing = [
        'ff' => 0,
        'ventas' => 0,
    ];
    
    
    $emptyMonths = [];
    foreach ($lstMonths as $k=>$m){
      $emptyMonths[$k] = 0;
    }
    $tIngByMonth = $emptyMonths;
    $tGastByMonth = $emptyMonths;
    
    
    
    $aExpensesPending = [
        'prop_pay' => 0,
        'agencias' => 0,
        'comision_tpv' => 0,
        'limpieza' => 0,
        'lavanderia' => 0
        ];
    

    $aIngrPending = [
        'ventas' => 0,
        ];
    /*************************************************************************/
    $books = \App\Book::where_type_book_sales(true)->with('payments')
            ->where('start', '>=', $startYear)
            ->where('start', '<=', $endYear)->get();
    $aux = $emptyMonths;
    foreach ($books as $key => $book) {
//      $aExpensesPending['prop_pay'] += ($book->cost_apto + $book->cost_park + $book->cost_lujo);
      $aExpensesPending['agencias'] += $book->PVPAgencia;
   
      $aExpensesPending['limpieza'] += ($book->cost_limp - 10);
      $aExpensesPending['lavanderia'] += 10;
    
      $m = date('ym', strtotime($book->start));
      $value = 0;
    
      foreach ($book->payments as $pay) {
        $value += $pay->import;
        if ($pay->type ==2 || $pay->type ==3)
           $aExpensesPending['comision_tpv'] += paylandCost($pay->import);
      }
      if (isset($aux[$m])) $aux[$m] += $value;
      if (isset($tIngByMonth[$m])) $tIngByMonth[$m] += $value;
      $lstT_ing['ventas'] += $value;
      
      if ($book->total_price-$value>0)
        $aIngrPending['ventas'] += $book->total_price-$value;
    }
    $ingresos['ventas'] = $aux;
    
    
    /*************************************************************************/
    
    $ingrType = \App\Incomes::getTypes();
    foreach ($ingrType as $k=>$t){
      $ingresos[$k] = $emptyMonths;
      $lstT_ing[$k] = 0;
      $aIngrPending[$k] = 0;
    }
    $ingresos['others'] = $emptyMonths;
    $lstT_ing['others'] = 0;
    $incomesLst = \App\Incomes::where('date', '>=', $startYear)->Where('date', '<=', $endYear)->get();
    if ($incomesLst){
      foreach ($incomesLst as $item){
        $m = date('ym', strtotime($item->date));
        $concept = isset($ingrType[$item->concept]) ? $item->concept : 'others';
        $ingresos[$concept][$m] += $item->import;
        if (isset($tIngByMonth[$m])) $tIngByMonth[$m] += $item->import;
        $lstT_ing[$concept] += $item->import;
      }
    }
    
    $ingrType['ff'] = 'FORFAITS';
    $ingrType['ventas'] = 'VENTAS';
    $ingrType['others'] = 'OTROS INGRESOS';
    
    
    /*************************************************************************/
    /******       GASTOS                        ***********/
    $gastos = \App\Expenses::where('date', '>=', $startYear)
                    ->Where('date', '<=', $endYear)
                    ->WhereNull('PayFor')
                    ->orderBy('date', 'DESC')->get();

    $lstT_gast = [];
    $listGastos = [];
    
    $gType = \App\Expenses::getTypes();
    if ($gType){
      foreach ($gType as $k=>$v){
        $listGastos[$k] = $emptyMonths;
        $lstT_gast[$k] = 0;
      }
    }
    if ($gastos){
      foreach ($gastos as $g){
        $m = date('ym', strtotime($g->date));
        if (isset($listGastos[$g->type])){
          $listGastos[$g->type][$m] += $g->import;
          $lstT_gast[$g->type] += $g->import;
          if (isset($tGastByMonth[$m]) && $g->type != 'impuestos') $tGastByMonth[$m] += $g->import;
        }
      }
    }
    
    foreach ($gType as $k=>$v){
      if (isset($aExpensesPending[$k])){
        $aExpensesPending[$k] -= $lstT_gast[$k];
      } else {
        $aExpensesPending[$k] = 0;
      }
    }

    /*****************************************************************/
    
    $impuestos = $listGastos['impuestos'];
    unset($listGastos['impuestos']);
    unset($listGastos['prop_pay']);
    
    $impEstimado = [];
    
    $gTypesImp = \App\Expenses::getTypesImp();
    if ($gTypesImp){
      foreach ($lstMonths as $k_m=>$m){
        $impuestoM = 0;
        foreach ($gTypesImp as $k_t=>$v){
          $impuestoM += $listGastos[$k_t][$k_m];
        }
        
        $impEstimado[$k_m] = ($tIngByMonth[$k_m]* 0.21 ) - ($impuestoM*0.21);
        
      }
      
    }
      
    $totalPendingImp = array_sum($impEstimado)-$lstT_gast['impuestos'];
//    ( T ingr * 0.21 ) - ( TGasto *0.21 )

    /*****************************************************************/
    $totalIngr = array_sum($lstT_ing);
    $totalGasto = array_sum($lstT_gast);
    return view('backend/sales/perdidas_ganancias', [
        'lstT_ing' => $lstT_ing,
        'totalIngr' => $totalIngr,
        'lstT_gast' => $lstT_gast,
        'totalGasto' => $totalGasto,
        'totalPendingGasto' => array_sum($aExpensesPending),
        'totalPendingIngr' => array_sum($aIngrPending),
        'totalPendingImp' => $totalPendingImp,
        'ingresos' => $ingresos,
        'listGasto' => $listGastos,
        'impuestos' => $impuestos,
        'impEstimado' => $impEstimado,
        'aExpensesPending' => $aExpensesPending,
        'aIngrPending' => $aIngrPending,
        'diff' => $diff,
        'lstMonths' => $lstMonths,
        'year' => $year,
        'tGastByMonth' => $tGastByMonth,
        'tIngByMonth' => $tIngByMonth,
        'ingrType' => $ingrType,
        'gastoType' => $gType,
        'ingr_bruto' => $totalIngr-$totalGasto,
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

    $books = \App\Book::where_type_book_sales()->with('payments')->where('start', '>=', $startYear)
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
      $year = self::getActiveYear();
    } else {
      $year = self::getYearData($year);
    }
    
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);

    $total = 0;
    $metalico = 0;
    $banco = 0;
    $pagado = 0;
    if ($room == "all") {
      $rooms = \App\Rooms::where('state', 1)->get(['id']);
      $books = \App\Book::where_type_book_sales()
              ->whereIn('room_id', $rooms)
              ->where('start', '>=',$startYear)
              ->where('start', '<=', $endYear)
              ->orderBy('start', 'ASC')->get();


      foreach ($books as $key => $book) {
        $total += ($book->cost_apto + $book->cost_park + $book->cost_lujo); //$book->total_price;
      }

      $gastos = \App\Expenses::where('date', '>=',$startYear)
              ->where('date', '<=', $endYear)
              ->orderBy('date', 'DESC')->get();
      
      foreach ($gastos as $payment) {
        if ($payment->typePayment == 2 || $payment->typePayment == 1) {
          $metalico += $payment->import;
        } else {
          $banco += ($payment->import );
        }
        $pagado += ($payment->import);
      }
    } else {

      $books = \App\Book::where_type_book_sales()
              ->where('room_id', $room)
              ->where('start', '>=',$startYear)
              ->where('start', '<=', $endYear)
              ->orderBy('start', 'ASC')->get();
      
      foreach ($books as $key => $book) {
        $total += ($book->cost_apto + $book->cost_park + $book->cost_lujo); //$book->total_price;
      }

      $gastos = \App\Expenses::where('date', '>=',$startYear)
              ->where('date', '<=', $endYear)
              ->Where('PayFor', 'LIKE', '%' . $room . '%')
              ->orderBy('date', 'DESC')->get();

      foreach ($gastos as $payment) {
        if ($payment->typePayment == 2 || $payment->typePayment == 1) {
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
        } else {
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
        'metalico_jorge' => 0,
        'banco_jorge' => 0,
        'banco_jaime' => 0,
    ];
  }

  public function getHojaGastosByRoom($year = "", $id) {
   
    if (empty($year)) {
      $year = self::getActiveYear();
    } else {
      $year = self::getYearData($year);
    }
    
    $start = new Carbon($year->start_date);
    $end = new Carbon($year->end_date);
    
    
    if ($id != "all") {
      $room = \App\Rooms::find($id);
      $gastos = \App\Expenses::where('date', '>=', $start)
                      ->Where('date', '<=', $end)
                      ->Where('PayFor', 'LIKE', '%' . $id . '%')->orderBy('date', 'DESC')->get();
    } else {
      $room = "all";
      $gastos = \App\Expenses::where('date', '>=', $start)
                      ->Where('date', '<=', $end)->orderBy('date', 'DESC')->get();
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

  static function setExpenseLimpieza($status, $room_id, $date) {
    $room = \App\Rooms::find($room_id);
    $expenseLimp = 0;

    if ($room->sizeApto == 1) {
      $expenseLimp = 30;
    } else if ($room->sizeApto == 2 || $room->sizeApto == 9) {
      $expenseLimp = 50; //40;
    } else if ($room->sizeApto == 3 || $room->sizeApto == 4) {
      $expenseLimp = 100; //70;
    }

    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
      $date = Carbon::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
    }

    $gasto = new \App\Expenses();
    $gasto->concept = "LIMPIEZA RESERVA PROPIETARIO. " . $room->nameRoom;
    $gasto->date = $date;
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

 
  public function searchByName(Request $request){
    return $this->searchByRoom( $request);
  }

  public function searchByRoom(Request $request) {
    $arrayCustomersId = null;
    $roomID = null;
    $type = null;
    $agency = null;
    if ($request->searchString != "") {
      $customers = \App\Customers::where('name', 'LIKE', '%' . $request->searchString . '%')->get();

      if (count($customers) > 0) {
        $arrayCustomersId = [];
        foreach ($customers as $key => $customer) {
          if (!in_array($customer->id, $arrayCustomersId)) {
            $arrayCustomersId[] = $customer->id;
          }
        }
      }
    }
    
    if ($request->searchRoom && $request->searchRoom != "all") {
      $roomID = $request->searchRoom;
    }

    if ($request->searchAgency && $request->searchAgency >0) {
      $agency = intval($request->searchAgency);
    }
    if ($request->searchType && $request->searchType >0) {
      $type = intval($request->searchType);
    }
       
      
    $data = $this->getTableData($arrayCustomersId,$agency,$roomID,$type);
     
    $data['stripeCost'] = $this->getTPV($data['books']);
    $data['total_stripeCost'] = array_sum($data['stripeCost']);
             
    if (Auth::user()->role == "subadmin"){
      return view('backend/sales/_tableSummary-subadmin.blade', $data);
    }

    return view('backend/sales/_tableSummary', $data);
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

    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    

    
    // calculate total by month: limp and extra
    $dates = getArrayMonth($startYear,$endYear);
    $t_month = [];
    foreach ($dates as $d){
      
      $t_month[$d['m'].'-'.$d['y']] = [
          'limp' => 0,
          'extra' => 0,
          'label'=> getMonthsSpanish($d['m']).' '.$d['y']
      ];
    }
    
    
    $extraCostBooks = 0;
    $monthlyCost = \App\Book::getMonthSum('extraCost','finish',$startYear,$endYear);
    if (count($monthlyCost)){
      foreach ($monthlyCost as $item){
        if (isset($t_month[$item->new_date])){
          $t_month[$item->new_date]['extra'] = $item->total;
          $extraCostBooks += $item->total;
        }
      }
    }
    $totalCostBooks = 0;
    $monthlyCost = \App\Book::getMonthSum('cost_limp','finish',$startYear,$endYear);
    if (count($monthlyCost)){
      foreach ($monthlyCost as $item){
        if (isset($t_month[$item->new_date])){
          $t_month[$item->new_date]['limp'] = $item->total;
          $totalCostBooks += $item->total;
        }
      }
    }
    
    $monthlyFix = \App\Expenses::select('import',DB::raw('DATE_FORMAT(date, "%m-%y") new_date'))
            ->where('date', '>=', $startYear)
            ->where('date', '<=', $endYear)
            ->where('type', 'LIMPIEZA')
            ->where('concept', 'LIMPIEZA MENSUAL')
            ->get();
    if (count($monthlyFix)) {
      foreach ($monthlyFix as $m){
        if (isset($t_month[$m->new_date])){
          $t_month[$m->new_date]['limp'] += $m->import;
          $totalCostBooks += $m->import;
        }
      }
    }
    
    
    
    
    
    return view('backend/sales/limpiezas', [
        'year' => $year,
        'selected' => $obj1['selected'],
        'months_obj' => $obj1['months_obj'],
        'months_1' => $obj1,
        'months_2' => $obj2,
        'months_3' => $obj3,
        'totalCostBooks'=>$totalCostBooks,
        'extraCostBooks'=>$extraCostBooks,
        't_month' => $t_month
            ]
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
   
    //get the books to these date range
//    $lstBooks = \App\Book::where_type_book_sales()
//            ->where('finish', '>=', $startYear)
//            ->where('finish', '<=', $endYear)
//            ->get();

    $arrayMonth = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $arrayMonthMin = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];
    $lstMonthlyCost = [];
    
    $monthlyCost = \App\Book::getMonthSum('cost_limp','finish',$startYear,$endYear);
    foreach ($monthlyCost as $item) {
      $cMonth = intval(substr($item->new_date,0,2));
      $lstMonthlyCost[$cMonth] = floatval($item->total);
    }

    //Prepare objets to JS Chars
    $months_lab = '';
    $months_val = [];
    $months_obj = [];
    $thisMonth = date('m');
    $dates = getArrayMonth($startYear,$endYear);
    $selected = null;
    foreach ($dates as $d) {
      
      if ($thisMonth == $d['m']) {
        $selected = $d['y'].','.$d['m'];
      }

      $months_lab .= "'" . $arrayMonth[$d['m'] - 1] . "',";
      if (!isset($lstMonthlyCost[$d['m']])) {
        $months_val[] = 0;
      } else {
        $months_val[] = $lstMonthlyCost[$d['m']];
      }
      //Only to the Months select
      $months_obj[] = [
          'id' => $d['y'] . '_' . $d['m'],
          'month' => $d['m'],
          'year' => $d['y'],
          'name' => $arrayMonthMin[$d['m'] - 1]
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


    $lstBooks = \App\Book::where_type_book_sales()->where('finish', '>=', $startYear)
                    ->where('finish', '<=', $endYear)
                    ->orderBy('finish', 'ASC')->get();


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

          $cost = $book->cost_total - ($book->cost_limp+$book->extraCost);
          $book->cost_limp = floatval($limp_value);
          $book->extraCost = floatval($extr_value);
          $book->cost_total = $cost + ($book->cost_limp+$book->extraCost);
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
      $books = \App\Book::where_type_book_sales(true)->with('payments')
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
          
            if ($book->agency>0) $agency_name = 'none';
            else {
              if ($book->type_book == 99 || $book->is_fastpayment) // fastpayment
                  $agency_name = 'fp';
              else
                $agency_name = 'vd';
            }
            
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
                'c'    => 'Cerogrados',
                'none' => 'Otras',
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
    
    function getTableData($customerIDs=null,$agency=null,$roomID=null,$type=null){
      
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
        'adicionales'=>0
    ];
    
    $liquidacion = new \App\Liquidacion();

    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);

    $qry_books = Book::where_type_book_sales(true)->with([
                        'customer',
                        'payments',
                        'room.type'
                    ])->where('start', '>=', $startYear)
            ->where('start', '<=', $endYear);
    
            
    if (is_array($customerIDs) && count($customerIDs)){
      $qry_books->whereIn('customer_id', $customerIDs);
    }
    
    if ($agency && $agency>0){
      $qry_books->where('agency', $agency);
    }
    if ($type && $type>0){
      $qry_books->where('type_book', $type);
    }
    if ($roomID && $roomID>0){
      $qry_books->where('room_id', $roomID);
    }
    
    $books = $qry_books->orderBy('start', 'ASC')->get();
            
    $alert_lowProfits = 0; //To the alert efect
    $percentBenef = DB::table('percent')->find(1)->percent;
    $lowProfits = [];

    $additionals = [];
    foreach ($books as $key => $book) {

      // if($book->type_book != 7 && $book->type_book != 8){
      $totales["total"] += $book->total_price;
      $totales["costeApto"] += $book->cost_apto;
      $totales["costePark"] += $book->cost_park;
//      if ($book->room->luxury == 1) {
      if ($book->type_luxury == 1 || $book->type_luxury == 3 || $book->type_luxury == 4) {
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
      
      $additionals[$book->id] = null;
    }


    $totBooks = (count($books) > 0) ? count($books) : 1;
    $diasPropios = \App\Book::where('start', '>=', $startYear)
                    ->where('start', '<=', $endYear)
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
    
    
     return [
          'books' => $books,
          'lowProfits' => $lowProfits,
          'alert_lowProfits' => $alert_lowProfits,
          'percentBenef' => $percentBenef,
          'totales' => $totales,
          'year' => $year,
          'data' => $data,
          'additionals' => $additionals
      ];
     
  }

  function getTPV($books) {
     $bIds = [];
    if($books){
      foreach ($books as $book){
        if ($book->stripeCost < 1){
          $bIds[] = $book->id;
        }
      }
    }
          
    $payments = \App\BookOrders::where('paid',1)->whereIn('book_id',$bIds)
            ->groupBy('book_id')->selectRaw('sum(amount) as sum, book_id')->pluck('sum','book_id');
    $stripeCost = [];
    if($books){
      foreach ($books as $book){
        $stripeCost[$book->id] = 0;
        if ($book->stripeCost < 1){
          if (isset($payments[$book->id])){
            $stripeCost[$book->id] = paylandCost($payments[$book->id]/100);
          }
        } else {
          $stripeCost[$book->id] = $book->stripeCost;
        }
      }
    }
    
    return $stripeCost;
  }
}
