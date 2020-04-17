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
      $data[$room->id]['coste_prop'] = 0;

//      $booksByRoom = \App\Book::where_type_book_prop(true)->where('room_id', $room->id)
      $booksByRoom = \App\Book::where_type_book_prop()
              ->where('room_id', $room->id)
              ->where('start', '>=', $startYear)
              ->where('start', '<=', $endYear)
              ->get();

      foreach ($booksByRoom as $book) {

              
        $costTotal = $book->get_costeTotal();
        $lujo = $book->get_costLujo();
        $data[$book->room_id]['coste_prop'] += $book->get_costProp();
        $data[$book->room_id]['totales']['totalLujo'] += $lujo;
        $summary["totalLujo"] += $lujo;

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
                    ->whereNull('book_id')
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
      
    
    
    $oLiq = new \App\Liquidacion();
    $summary_liq = $oLiq->summaryTemp();
    
    return view('backend/paymentspro/index', [
        'year' => $year,
        'gastos' => $gastos,
        'data' => $data,
        'summary' => $summary,
        'summary_liq' => $summary_liq,
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
      $total += $book->get_costProp();
    }
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
    
    $year = self::getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $startDate = $request->input('start',null);
    $endDate = $request->input('end',null);

    $nameRoom = 'Apartamentos';
    if ($idRoom == "all") {
      $books = \App\Book::where_type_book_prop()->where('start', '>=', $startDate)
              ->where('start', '<=', $endDate)
              ->orderBy('start', 'ASC')
              ->get();
    } else {
      $books = \App\Book::where_type_book_prop()->where('start', '>=', $startDate)
              ->where('start', '<=', $endDate)
              ->where('room_id', $idRoom)
              ->orderBy('start', 'ASC')
              ->get();
      
      $oRoom = \App\Rooms::find($idRoom);
      $nameRoom = $oRoom->name;
    }
    
    $oLiq = new \App\Liquidacion();
    $summary = $oLiq->get_summary($books);

    $totBooks = (count($books) > 0) ? count($books) : 1;

    return view('backend/paymentspro/_tableBooksByRoom', [
        'summary' => $summary,
        'books' => $books,
        'mobile' => new Mobile(),
        'nameRoom' => $nameRoom,
    ]);
  }

  public function getLiquidationByRoom(Request $request) {

    $start = $request->input('start',null);
    $finish = $request->input('end',null);
    $download = $request->input('download',null);

    $total = 0;
    $apto = 0;
    $park = 0;
    $lujo = 0;

    if ($request->idRoom != 'all') {
      $room = \App\Rooms::find($request->idRoom);
      $books = \App\Book::where_type_book_prop()->where('room_id', $room->id)
              ->where('start', '>=', $start)
              ->where('finish', '<=', $finish)
              ->orderBy('start', 'ASC')
              ->get();
    } else {
      $room = "all";
      $books = \App\Book::where_type_book_prop()->where('start', '>=', $start)
              ->where('finish', '<=', $finish)
              ->orderBy('start', 'ASC')
              ->get();
    }


    foreach ($books as $book) {

      $total += $book->get_costProp();

      $apto += $book->cost_apto;
      $park += $book->cost_park;
      $lujo += $book->get_costLujo();
    }
//    $total += ($apto + $park + $lujo);
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

    $data = [
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
    ];
    
//    
     
     if ($download){
       $file_name = 'Reservas';
       if (is_object($room)){
        $file_name = $room->name.' ('.$room->nameRoom.')';
       }
     
//    return view('backend/paymentspro/_liquidationByRoom-pdf',$data);
       // Send data to the view using loadView function of PDF facade
    $pdf = \PDF::loadView('backend/paymentspro/_liquidationByRoom-pdf', $data);
    // Finally, you can download the file using download function
    return $pdf->download($file_name . '.pdf');
     } else {
        return view('backend/paymentspro/_liquidationByRoom',$data);
     }
    
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
      $costTotal = $book->get_costeTotal();
      
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
        'year' => $year,
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
         $total += $book->get_costProp();
    }
//    $total += ($apto + $park + $lujo);
    
    
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
