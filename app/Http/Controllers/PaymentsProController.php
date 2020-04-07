<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Classes\Mobile;

class PaymentsProController extends AppController {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $year = self::getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    return $this->getItems($year,$startYear,$endYear);
  }
    
  public function indexByDate(Request $req) {
    $range = $req->input('dateRangefilter',null);
    $filter_startDate = $req->input('filter_startDate',null);
    $filter_endDate = $req->input('filter_endDate',null);
    if ($range){
      if ($filter_startDate && $filter_endDate){
        $startYear = new Carbon($filter_startDate);
        $endYear = new Carbon($filter_endDate);
        $year = self::getYearData($startYear->format('Y'));
        return $this->getItems($year,$startYear,$endYear);
      }
    }
    return $this->index();
  }
  private  function getItems($year,$startYear,$endYear) {
    $rooms = \App\Rooms::orderBy('order', 'ASC')->get();

    /* Calculamos los ingresos por reserva y room */
    $data = array();
    $summary = [
        'totalLimp' => 0,
        'totalAgencia' => 0,
        'totalParking' => 0,
        'totalLujo' => 0,
        'totalCost' => 0,
        'totalApto' => 0,
        'totalPVP' => 0,
        'pagos' => 0,
    ];

    foreach ($rooms as $room) {

      $data[$room->id] = array();
      $data[$room->id]['totales']['totalLimp'] = 0;
      $data[$room->id]['totales']['totalAgencia'] = 0;
      $data[$room->id]['totales']['totalParking'] = 0;
      $data[$room->id]['totales']['totalLujo'] = 0;
      $data[$room->id]['totales']['totalCost'] = 0;
      $data[$room->id]['totales']['totalApto'] = 0;
      $data[$room->id]['totales']['totalPVP'] = 0;
      $data[$room->id]['pagos'] = 0;

      $booksByRoom = \App\Book::where('room_id', $room->id)
              ->where('type_book', 2)
              ->where('start', '>=', $startYear)
              ->where('start', '<=', $endYear)
              ->get();

      foreach ($booksByRoom as $book) {

        if ($room->luxury == 1) {
          $data[$book->room_id]['totales']['totalLujo'] += $book->cost_lujo;
          $costTotal = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->cost_limp + $book->PVPAgencia;
          $summary['totalLujo'] += $book->cost_lujo;
        } else {
          $data[$book->room_id]['totales']['totalLujo'] += 0;
          $costTotal = $book->cost_apto + $book->cost_park + 0 + $book->cost_limp + $book->PVPAgencia;
          $summary['totalLujo'] += 0;
        }
        $data[$book->room_id]['totales']['totalLimp'] += $book->cost_limp;
        $data[$book->room_id]['totales']['totalAgencia'] += $book->PVPAgencia;
        $data[$book->room_id]['totales']['totalParking'] += $book->cost_park;


        $data[$book->room_id]['totales']['totalCost'] += $costTotal;
        $data[$book->room_id]['totales']['totalApto'] += $book->cost_apto;
        $data[$book->room_id]['totales']['totalPVP'] += $book->total_price;

        $summary['totalLimp'] += $book->cost_limp;
        $summary['totalAgencia'] += $book->PVPAgencia;
        $summary['totalParking'] += $book->cost_park;

        $summary['totalCost'] += $costTotal;
        $summary['totalApto'] += $book->cost_apto;
        $summary['totalPVP'] += $book->total_price;
      }

      $gastos = \App\Expenses::getListByRoom($startYear->format('Y-m-d'),$endYear->format('Y-m-d'),$room->id);
      if (count($gastos) > 0) {

        foreach ($gastos as $gasto) {
          $divisor = 0;

          if (preg_match('/,/', $gasto->PayFor)) {
            $aux = explode(',', $gasto->PayFor);
            for ($i = 0; $i < count($aux); $i++) {
              if (!empty($aux[$i])) {
                $divisor++;
              }
            }
          } else {
            $divisor = 1;
          }

          $data[$room->id]['pagos'] += ($gasto->import / $divisor);
          $summary['pagos'] += ($gasto->import / $divisor);
        }
      }
    }
    
        $gastos = \App\Expenses::where('date', '>=', $startYear)
                    ->Where('date', '<=', $endYear)
                    ->where('concept', 'NOT LIKE', '%LIMPIEZA RESERVA PROPIETARIO.%')
                    ->orderBy('date', 'DESC')->get();
        
        
   
  /**********   COSTO DE LIMPIENZA  ******************/
    $cost_limpByRoom = \App\Book::where('type_book', 7)
              ->where('start', '>=', $startYear)
              ->where('start', '<=', $endYear)->orderBy('start')->get();

    $limp_prop = [];
    $t_limpProp = 0;
    if ($cost_limpByRoom){
      $auxBookIds = [];
      foreach ($cost_limpByRoom as $book) { 
        $auxBookIds[] = $book->id;
      }
      
      $auxExpenses = \App\Expenses::whereIn('book_id',$auxBookIds)->where('type','limpieza')->pluck('import','book_id');
      foreach ($cost_limpByRoom as $book) {   
        if (!isset($limp_prop[$book->room_id])) $limp_prop[$book->room_id] = [];
        $limp_prop[$book->room_id][] = [
            'id' => $book->id,
            'cost' => $book->cost_limp,
            'start' => convertDateToShow_text($book->start),
            'finish' => convertDateToShow_text($book->finish),
            'import' => isset($auxExpenses[$book->id]) ? $auxExpenses[$book->id] : 0,
        ];
        $t_limpProp += $book->cost_limp;
      } 
    }
        
    return view('backend/paymentspro/index', [
        'year' => $year,
        'gastos' => $gastos,
        'data' => $data,
        'summary' => $summary,
        'rooms' => $rooms,
        'startYear' => $startYear,
        'endYear' => $endYear,
        'limp_prop' => $limp_prop,
        't_limpProp' => $t_limpProp
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request) {
    $fecha = Carbon::now();
    $paymentPro = new \App\Paymentspro();

    $paymentPro->room_id = $request->id;
    $paymentPro->import = $request->import;
    $paymentPro->comment = $request->comment;
    $paymentPro->datePayment = $fecha->format('Y-m-d');
    $paymentPro->type = $request->type;

    if ($paymentPro->save()) {
      return redirect()->action('PaymentsProController@index');
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  int                      $id
   * @return \Illuminate\Http\Response
   */
  public function update($id, $month = "", Request $request) {
    $typePayment = new \App\Paymentspro();
    $total = $apto = $park = $lujo = $deuda = $pagado = 0;
    $room = \App\Rooms::find($id);

    $date = empty($month) ? Carbon::now() : Carbon::createFromFormat('Y', $month);

    $year = self::getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    $roomID = $room->id;
    $gastos = \App\Expenses::getListByRoom($startYear->format('Y-m-d'),$endYear->format('Y-m-d'),$roomID);
    $types = \App\Expenses::getTypes();
    $typePay = \App\Expenses::getTypeCobro();
            
    $lstGastos = [];
    $lstByPayment = [];
    foreach ($typePay as $k=>$v){
      $lstByPayment[$k] = 0;
    }
    
    
    
    foreach ($gastos as $payment) {
      $aux = [
          'type_payment' => isset($typePay[$payment->typePayment]) ? $typePay[$payment->typePayment] : '--',
          'date' => convertDateToShow($payment->date),
          'type' => isset($types[$payment->type]) ? $types[$payment->type] : $payment->type,
          'comment' => $payment->comment,
          'concept' => $payment->concept,
          'import' => 0,
          'ID' => $payment->is,
      ];
      
      $divisor = 0;
      if (preg_match('/,/', $payment->PayFor)) {
        $aux2 = explode(',', $payment->PayFor);
        for ($i = 0; $i < count($aux2); $i++) {
          if (!empty($aux2[$i])) {
            $divisor++;
          }
        }
      } else {
        $divisor = 1;
      }
      $aux['import'] = round($payment->import / $divisor,2);
      $lstGastos[] = $aux;
      
      if (isset($lstByPayment[$payment->typePayment])){
        $lstByPayment[$payment->typePayment] += $aux['import'];
      }
      
      $pagado += $aux['import'];
    }

    
    $books = \App\Book::whereIn('type_book', [2])
            ->where('room_id', $room->id)
            ->where('start', '>=', $startYear->format('Y-m-d'))
            ->Where('start', '<=', $endYear->format('Y-m-d'))
            ->orderBy('start', 'ASC')
            ->get();

    foreach ($books as $book) {

      $apto += $book->cost_apto;
      $park += $book->cost_park;
      if ($book->room->luxury == 1) {
        $lujo += $book->cost_lujo;
      } else {
        $lujo += 0;
      }
    }
    $total += ($apto + $park + $lujo);

    if ($total > 0 && $request->debt > 0) {
      $deuda = ($total - $request->debt) / $total * 100;
    } else {
      $deuda = $total;
    }


    return view('backend/paymentspro/_form', [
        'lstGastos' => $lstGastos,
        'lstByPayment' => $lstByPayment,
        'room' => $room,
        'debt' => $request->debt,
        'total' => $total,
        'deuda' => $deuda,
        'typePayment' => $typePay,
        'pagoProp' => $pagado,
        'gType' => $types,
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    //
  }

  public function getBooksByRoom($idRoom, Request $request) {
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

    $year = self::getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $startDate = $request->input('start',null);
    $endDate = $request->input('end',null);

    if ($idRoom == "all") {
      $books = \App\Book::where_type_book_sales()->where('start', '>=', $startDate)
              ->where('start', '<=', $endDate)
              ->orderBy('start', 'ASC')
              ->get();
    } else {
      $books = \App\Book::where_type_book_sales()->where('start', '>=', $startDate)
              ->where('start', '<=', $endDate)
              ->where('room_id', $idRoom)
              ->orderBy('start', 'ASC')
              ->get();
    }


    foreach ($books as $key => $book) {
      $totales["total"] += $book->total_price;
      $totales["coste"] += ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp + $book->extraCost);
      $totales["costeApto"] += $book->cost_apto;
      $totales["costePark"] += $book->cost_park;
      $totales["costeLujo"] += $book->cost_lujo;
      $totales["costeLimp"] += $book->cost_limp;
      $totales["costeAgencia"] += $book->PVPAgencia;
      $totales["bancoJorge"] += $book->getPayment(2);
      $totales["bancoJaime"] += $book->getPayment(3);
      $totales["jorge"] += $book->getPayment(0);
      $totales["jaime"] += $book->getPayment(1);
      $totales["benJorge"] += $book->ben_jorge;
      $totales["benJaime"] += $book->ben_jaime;
      $totales["pendiente"] += $book->getPayment(4);
      $totales["limpieza"] += $book->sup_limp;
      $totales["beneficio"] += ($book->total_price - ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp));

      $totalStripep = 0;
      $stripePayment = \App\Payments::where('book_id', $book->id)->where('comment', 'LIKE', '%stripe%')->get();
      foreach ($stripePayment as $key => $stripe):
        $totalStripep += $stripe->import;
      endforeach;
      if ($totalStripep > 0):
        $totales["stripe"] += ((1.4 * $totalStripep) / 100) + 0.25;
      endif;

      $totales['obs'] += $book->extraCost;
    }
    $totBooks = (count($books) > 0) ? count($books) : 1;

    $diasPropios = \App\Book::where('start', '>', $startYear)->where('finish', '<', $endYear)
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


    return view('backend/paymentspro/_tableBooksByRoom', [
        'books' => $books,
        'mobile' => new Mobile(),
        'totales' => $totales,
        'data' => $data,
    ]);
  }

  public function getLiquidationByRoom(Request $request) {

    $start = $request->input('start',null);
    $finish = $request->input('end',null);

    $total = 0;
    $apto = 0;
    $park = 0;
    $lujo = 0;

    if ($request->idRoom != 'all') {
      $room = \App\Rooms::find($request->idRoom);
      $books = \App\Book::where_type_book_sales()->where('room_id', $room->id)
              ->where('start', '>=', $start)
              ->where('finish', '<=', $finish)
              ->orderBy('start', 'ASC')
              ->get();
    } else {
      $room = "all";
      $books = \App\Book::where_type_book_sales()->where('start', '>=', $start)
              ->where('finish', '<=', $finish)
              ->orderBy('start', 'ASC')
              ->get();
    }


    foreach ($books as $book) {

      if ($book->type_book != 7 && $book->type_book != 8 && $book->type_book != 9) {

        $apto += $book->cost_apto;
        $park += $book->cost_park;
        if ($book->room->luxury == 1) {
          $lujo += $book->cost_lujo;
        } else {
          $lujo += 0;
        }
      }
    }
    $total += ($apto + $park + $lujo);
    $roomID = isset($room->id) ? $room->id : -1;
    $pagos = \App\Expenses::where('date', '>=', $start)
            ->where('date', '<=', $finish)
            ->where('PayFor', 'LIKE', '%' . $roomID . '%')
            ->orderBy('date', 'ASC')
            ->get();

    $pagototal = 0;
    foreach ($pagos as $pago) {

      $pagototal += $pago->import;
    }

    return view('backend/paymentspro/_liquidationByRoom', [
        'books' => $books,
        'costeProp' => $request->costeProp,
        'apto' => $apto,
        'park' => $park,
        'lujo' => $lujo,
        'total' => $total,
        'room' => $room,
        'startDate' => date('d M Y', strtotime($start)),
        'finishDate' => date('d M Y', strtotime($finish)),
        'dates' => [
            'start' => $start,
            'finish' => $finish,
        ],
        'mobile' => new Mobile(),
        'pagos' => $pagos,
        'pagototal' => $pagototal,
        'pagototalProp' => 0,
    ]);
  }

  public static function getHistoricProduction($room_id, Request $request) {

    $return = [];
    $year = self::getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    $oRoom = \App\Rooms::find($room_id);
    
    $CostProp = $oRoom->getCostPropByYear($year->year);
    
    if ($CostProp){
      $return[] = ['y'=>($year->year-2000),'val'=>$CostProp];
    } else {
      $return[] = ['y'=>($year->year-2000),'val'=>0];
    }
    
    $aux = $year->year-1;
    $CostProp = $oRoom->getCostPropByYear($aux);
    if ($CostProp){
      $return[] = ['y'=>($aux-2000),'val'=>$CostProp];
    } else {
      $return[] = ['y'=>($aux-2000),'val'=>0];
    }
    
    $aux = $aux-1;
    $CostProp = $oRoom->getCostPropByYear($aux);
    if ($CostProp){
      $return[] = ['y'=>($aux-2000),'val'=>$CostProp];
    } else {
      $return[] = ['y'=>($aux-2000),'val'=>0];
    }
      
    return view('backend/paymentspro/_historicProductionGraphic', [
        'oRoom' => $oRoom,
        'info_years' => $return,
        'year' => $year,
        'room_id' => $room_id
    ]);
  }
  
  public function getLiquidationByMonth() {
    
    $year = self::getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    $lstMonths = getArrayMonth($startYear,$endYear,true);
    
    $rooms = \App\Rooms::where('state',1)->orderBy('order', 'ASC')->get();
    $roomLujo = [];
    $lstRooms = [];
    $roomsIDs = [];
    foreach ($rooms as $room) {
      if ($room->luxury == 1) {
        $roomLujo[] = $room->id;
      }
      $roomsIDs[] = $room->id;
      $lstRooms[$room->id] = ucfirst($room->name).' ('.$room->nameRoom.')';
    }
    
    
    
    $booksByRoom = \App\Book::where('type_book', 2)
              ->whereIn('room_id',$roomsIDs)
              ->where('start', '>=', $startYear)
              ->where('start', '<=', $endYear)
              ->get();

    $roomLst = [];
    foreach ($booksByRoom as $book) {
          
      
      $roomID = $book->room_id;
      $costTotal = $book->cost_apto + $book->cost_park;
      if (in_array($roomID, $roomLujo)) {
         $costTotal += $book->cost_lujo;
      }
      
      $date = date('ym', strtotime($book->start));
      if (!isset($roomLst[$roomID])) $roomLst[$roomID] = [$date=>$costTotal];
      elseif(!isset($roomLst[$roomID][$date])){
        $roomLst[$roomID][$date] = $costTotal;
      } else {
        $roomLst[$roomID][$date] += $costTotal;
      }
    }
    
    //totals by rooms
    $t_room_month = [];
    $t_rooms = [];
    $t_all_rooms = 0;
    foreach ($roomLst as $roomID => $item){
      foreach ($item as $m => $v){
        if (!isset($t_room_month[$m])) $t_room_month[$m] = $v;
        else $t_room_month[$m] += $v;
        
        if (isset($t_rooms[$roomID])) $t_rooms[$roomID] += $v;
        else $t_rooms[$roomID] = $v;
        
        $t_all_rooms += $v;
      }
    }
    
    return view('backend/paymentspro/byMonths', [
        'lstRooms' => $lstRooms,
        'roomLst' => $roomLst,
        'lstMonths'=>$lstMonths,
        't_rooms' => $t_rooms,
        't_room_month' =>$t_room_month,
        't_all_rooms' =>$t_all_rooms,
    ]);
    
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
         
      $cost_limpByRoom = \App\Book::where('type_book', 7)
              ->where('room_id',$id)
              ->where('start', '>=', $start)
              ->where('start', '<=', $end)->orderBy('start')->get();
      
    } else {
      $room = "all";
      $gastos = \App\Expenses::where('date', '>=', $start)
                      ->Where('date', '<=', $end)->orderBy('date', 'DESC')->get();
      
      $cost_limpByRoom = \App\Book::where('type_book', 7)
           ->where('start', '>=', $start)
           ->where('start', '<=', $end)->orderBy('start')->get();
    }
    
    /**********   COSTO DE LIMPIENZA  ******************/
 

    $limp_prop = [];
    $t_limpProp = 0;
    if ($cost_limpByRoom){
      $auxBookIds = [];
      foreach ($cost_limpByRoom as $book) { 
        $auxBookIds[] = $book->id;
      }
      
      $auxExpenses = \App\Expenses::whereIn('book_id',$auxBookIds)->where('type','limpieza')->pluck('import','book_id');
      foreach ($cost_limpByRoom as $book) {   
        if (!isset($limp_prop[$book->room_id])) $limp_prop[$book->room_id] = [];
        $limp_prop[$book->room_id][] = [
            'id' => $book->id,
            'cost' => $book->cost_limp,
            'start' => convertDateToShow_text($book->start),
            'finish' => convertDateToShow_text($book->finish),
            'import' => isset($auxExpenses[$book->id]) ? $auxExpenses[$book->id] : 0,
        ];
        $t_limpProp += $book->cost_limp;
      } 
    }

    return view('backend.paymentspro.gastos._expensesByRoom', [
        'gastos' => $gastos,
        'room' => $room,
        'year' => $start,
        'limp_prop' => $limp_prop,
        't_limpProp' => $t_limpProp,
    ]);
  }


  /**
   * copy the view of the Owner
   */
  function seeLiquidationProp(Request $req){
    
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    $id = $req->input('id');
    $total = $apto = $park = $lujo = 0;
    
      $books = \App\Book::where('type_book',2)->where('room_id', $id)
                  ->where('start', '>=', $startYear)
                  ->where('start', '<=', $endYear)
                  ->orderBy('start', 'ASC')->get();
    
     foreach ($books as $book) {
        $apto += $book->cost_apto;
        $park += $book->cost_park;
        if ($book->room->luxury == 1) {
          $lujo += $book->cost_lujo;
        } else {
          $lujo += 0;
        }
    }
    $total += ($apto + $park + $lujo);
    
    
    $gastos = \App\Expenses::where('date', '>=', $startYear)
            ->where('date', '<=', $endYear)
            ->where('PayFor', 'LIKE', '%' . $id . '%')
            ->orderBy('date', 'ASC')
            ->get();
    $pagototal = 0;
    foreach ($gastos as $pago) {
      $pagototal += $pago->import;
    }
    
    
            
    return view('backend.owned._liquidation', [
        'total' => $total,
        'pagos' => $gastos,
        'pagototal' => $pagototal,
        'mobile' => new Mobile(),
    ]);
  }
}
