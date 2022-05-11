<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Classes\Mobile;
use App\Rooms;

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
  
  private  function getItems($year,$start,$end) {
    $rooms = Rooms::where('state',1)->orderBy('order', 'ASC')->get();
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
        'totalLuz' => 0,
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
      $data[$room->id]['totales']['totalLuz'] = 0;
      $data[$room->id]['pagos'] = 0;
      $data[$room->id]['coste_prop'] = 0;

//      $booksByRoom = \App\Book::where_type_book_prop(true)->where('room_id', $room->id)
      $booksByRoom = \App\Book::where_type_book_prop()
              ->where('room_id', $room->id)
              ->where('start', '>=', $start)
              ->where('start', '<=', $end)
              ->get();

      foreach ($booksByRoom as $book) {

              
        $costTotal = $book->get_costeTotal();
        $lujo = $book->get_costLujo();
        $data[$book->room_id]['coste_prop'] += $book->get_costProp();
        $data[$book->room_id]['totales']['totalLujo'] += $lujo;
        $data[$book->room_id]['totales']['totalLuz'] += $book->luz_cost;
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
        $summary['totalLuz'] += $book->luz_cost;
      }

      
    }
    
    $year2 = self::getActiveYear();
    $startYear = $year->start_date;
    $endYear = $year->end_date;
    $startYear = $start;
    $endYear = $end;
    /**************************************************/
    $oPayments = \App\Expenses::getPaymentToProp($startYear,$endYear);
    if (count($oPayments) > 0) {

        foreach ($oPayments as $pay) {
            $divisor = 0;

            $aux = explode(',', $pay->PayFor);
            foreach ($aux as $k => $i) {
                if (empty($i)) {
                    unset($aux[$k]);
                }
            }
            $divisor = count($aux);
            if ($divisor > 0) {
                $byRoom = ($pay->import / $divisor);
                foreach ($aux as $i) {
                    if (isset($data[$i])) $data[$i]['pagos'] += $byRoom;
//                    else echo $i.'-';
                }
            }
            $summary['pagos'] += $pay->import;
        }
    }
    /**************************************************/   
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

    $summary_liq = [
        'prop_cost'=>0,
        'total_pvp'=>0,
        'total_cost'=>0,
        'apto'=>0,
        'park'=>0,
        'lujo'=>0,
        'agency'=>0,
        'limp'=>0,
        'prop_payment'=>$summary['pagos'],
        'benef'=>0,
        'benef_inc'=>0,
        'luz'=>0,
        ];
            
    foreach ($data as $rID=>$d){
       
        $summary_liq['prop_cost'] += round($d['coste_prop']);
        $summary_liq['total_pvp'] += round($d['totales']['totalPVP']);
        $summary_liq['total_cost'] += round($d['totales']['totalCost']);
        $summary_liq['apto'] += $d['totales']['totalApto'];
        $summary_liq['park'] += $d['totales']['totalParking'];
        $summary_liq['lujo'] += $d['totales']['totalLujo'];
        $summary_liq['agency'] += $d['totales']['totalAgencia'];
        $summary_liq['limp'] += $d['totales']['totalLimp'];
        $summary_liq['luz'] += $d['totales']['totalLuz'];

    }
    
    $summary_liq['benef'] = $summary_liq['total_pvp'] - $summary_liq['total_cost'];
    $divisor = ($summary_liq['total_pvp'] == 0) ? 1 : $summary_liq['total_pvp'];
    $summary_liq['benef_inc'] = round(( $summary_liq['benef'] / $divisor ) * 100);
                    
                  
    /*****************************************************************/
    /***********  TXT Prop      */
    $roomOwned = [];
    $txtProp = null;
    foreach ($rooms as $r){
      $roomOwned[$r->id] = $r->owned;
    }
    $tTxtProp = 0;
    $txtUser = [];
    foreach ($data as $rID=>$d){
      $pendiente = $d['coste_prop'] - $d['pagos'];
      if ($pendiente>1 && isset($roomOwned[$rID])){
        if (!isset($txtUser[$roomOwned[$rID]])) $txtUser[$roomOwned[$rID]] = 0;
        $txtUser[$roomOwned[$rID]] += $pendiente;
        $tTxtProp+=$pendiente;
      }
    }
    if (count($txtUser)>0){
      $txtProp = [];
      $lstUser = \App\User::whereIn('id', array_keys($txtUser))->get();
      foreach ($lstUser as $u){
        $txtProp[] = [
          'name'=>$u->name,
          'mount'=>$txtUser[$u->id],
          'iban'=>$u->iban
        ];
      }
    }
    
    /*****************************************************************/
                    
                    
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
        't_limpProp' => $t_limpProp,
        'txtProp' => $txtProp,
        'tTxtProp'=>$tTxtProp,
        'typePaymentLst' => \App\Expenses::getTypeCobro(),
        'gTypeLst'=>\App\Expenses::getTypes()
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
    $room = Rooms::find($id);

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
      
      $oRoom = Rooms::find($idRoom);
      $nameRoom = $oRoom->name;
    }
    
    $oLiq = new \App\Liquidacion();
    $summary = $oLiq->get_summary($books);

    $totBooks = (count($books) > 0) ? count($books) : 1;

    $allRooms = Rooms::where('state',1)->orderBy('nameRoom')->pluck('nameRoom','id');
    return view('backend/paymentspro/_tableBooksByRoom', [
        'summary' => $summary,
        'books' => $books,
        'mobile' => new Mobile(),
        'nameRoom' => $nameRoom,
        'allRooms' => $allRooms,
        'idRoom' => $idRoom,
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
      $room = Rooms::find($request->idRoom);
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

  
   public function getLiquidationByRooms(Request $request) {
    $start = $request->input('filter_startDate',null);
    $finish = $request->input('filter_endDate',null);
    $roomID = $request->input('roomID',null);
    $is_modal = $request->input('modal',null);
    $year = self::getActiveYear();
    if (!($start && $finish)){
      $year = self::getActiveYear();
      $start = date('Y-m-d', strtotime($year->start_date));
      $finish =date('Y-m-d', strtotime($year->end_date));
    }
         
    $total = 0;
    $apto = 0;
    $park = 0;
    $luz  = 0;
    $lujo = 0;
    $costeProp = 0;
    $pagototal = 0;
    $lstRooms = Rooms::where('state',1)->orderBy('nameRoom')->pluck('nameRoom','id')->toArray();
    if ($roomID){
      $room = Rooms::find($roomID);
    } else {
      $room = Rooms::where('state',1)->first();
      $roomID = $room->id;
    }
    $books = \App\Book::where_type_book_prop()->where('room_id', $roomID)
            ->where('start', '>=', $start)
            ->where('finish', '<=', $finish)
            ->orderBy('start', 'ASC')
            ->get();
    if ($books){
      foreach ($books as $book) {
        $total += $book->get_costProp();
        $apto += $book->cost_apto;
        $park += $book->cost_park;
        $luz  += $book->luz_cost;
        $lujo += $book->get_costLujo();
      }
    }

    
    $startExp = date('Y-m-d', strtotime($year->start_date));
    $finishExp =date('Y-m-d', strtotime($year->end_date));
    $startExp = $start;
    $finishExp = $finish;
    $pagos = \App\Expenses::where('date', '>=', $startExp)
            ->where('date', '<=', $finishExp)
            ->where('PayFor', 'LIKE', '%' . $roomID . '%')
            ->orderBy('date', 'ASC')
            ->get();
    $payProp = 0;
    $lstPagos = [];
    $lstBimp = [];
    $lstIva = [];
    if ($pagos)
    foreach ($pagos as $pago) {
      $pagototal += $pago->import;
      
      $divisor = 0;
      if ( preg_match( '/,/' , $pago->PayFor ) ) {
       $aux = explode( ',' , $pago->PayFor );
       for ( $i = 0 ; $i < count( $aux ) ; $i++ ) {
        if ( !empty( $aux[ $i ] ) ) {
         $divisor++;
        }
       }

      } else {
       $divisor = 1;
      }
      $amount   = ($pago->import / $divisor);
      $lstBimp[$pago->id] = ($pago->bimp / $divisor);
      $lstIva[$pago->id]  = ($pago->iva / $divisor);
      $payProp += $amount;
      $lstPagos[$pago->id] = $amount;
          
    }
    

    $data = [
        'books' => $books,
        'costeProp' => $costeProp,
        'apto' => $apto,
        'park' => $park,
        'luz'  => $luz,
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
        'pagototalProp' => $payProp,
        'lstPagos' => $lstPagos,
        'lstBimp' => $lstBimp,
        'lstIva' => $lstIva,
        'rooms'  => $lstRooms,
        'roomID' => $roomID,
        'is_modal' => $is_modal,
        'gType' => \App\Expenses::getTypes(),
        'typePayment' => \App\Expenses::getTypeCobro()
    ];
    

    return view('backend/paymentspro/liquidationByRooms',$data);
    
  }

  public static function getHistoricProduction($room_id, Request $request) {

    $return = [];
    $year = self::getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    $oRoom = Rooms::find($room_id);
    
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
    
    $rooms = Rooms::where('state',1)->orderBy('order', 'ASC')->get();
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
      $costTotal = $book->get_costProp();
//      $costTotal = $book->get_costeTotal();
      
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
      $room = Rooms::find($id);
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
  
  function getHistorico_temp($roomID){
    $html = "";
    $oYears = \App\Years::orderBy('year')->get();
    $current = date('Y');
    foreach ($oYears as $year){
      if ($year->year > $current) continue;
      $start = date('Y-m-d', strtotime($year->start_date));
      $finish =date('Y-m-d', strtotime($year->end_date));


      $total = 0;
      $books = \App\Book::where_type_book_prop()->where('room_id', $roomID)
              ->where('start', '>=', $start)
              ->where('finish', '<=', $finish)
              ->get();
      if ($books){
        foreach ($books as $book) {
          $total += $book->get_costProp();
        }
      }
      $pagos = \App\Expenses::where('date', '>=', $start)
              ->where('date', '<=', $finish)
              ->where('PayFor', 'LIKE', '%' . $roomID . '%')
              ->get();
      $payProp = 0;
      if ($pagos)
      foreach ($pagos as $pago) {
        $divisor = 0;
        if ( preg_match( '/,/' , $pago->PayFor ) ) {
         $aux = explode( ',' , $pago->PayFor );
         for ( $i = 0 ; $i < count( $aux ) ; $i++ ) {
          if ( !empty( $aux[ $i ] ) ) {
           $divisor++;
          }
         }

        } else {
         $divisor = 1;
        }
        $payProp += ($pago->import / $divisor);

      }
      
      
      $html .= "<tr>"
          . '<td>'.date('Y', strtotime($year->start_date)). ' - ' .date('Y', strtotime($year->end_date)).'</td>'
          . '<td>'. moneda($total) .'</td>'
          . '<td>'. moneda($payProp) .'</td>'
          . '<td>'. moneda($total-$payProp) .'</td>'
          . "</tr>";
      
    }
    
    echo $html;
  }

  function payPropGroup(Request $req){

    $lstRooms = Rooms::get()->pluck('id');
    
    $date = $req->input('fecha',date('d/m/Y'));
    $concept = $req->input('concept','carga masiva SEPA19');
    $import = $req->input('import',0);
    $type_payment = $req->input('type_payment');
    $type = $req->input('type');
    $comment = $req->input('comment');
    $date = convertDateToDB($date);
    $nSaved = 0;
    foreach ($lstRooms as $rID){
      $import = intVal($req->input('cProp_'.$rID,0));
      if ($import>0){
        $obj = new \App\Expenses();
        $obj->concept = $concept;
        $obj->date = $date;
        $obj->import = $import;
        $obj->typePayment = $type_payment;
        $obj->type = $type;
        $obj->comment = $comment;
        $obj->PayFor = $rID;
        $obj->save();
        $nSaved++;
      }
    }
    
    return back()->with(['success'=>$nSaved.' Registros cargados']);
    
  }

  function getResumeByRoomsYear(){
    $oYear = $this->getActiveYear();

    $lstRooms = Rooms::where('state',1)->orderBy('order', 'ASC')->get();
    $result = [];
    $rRoomType = [];
    $year = $oYear->year;
    foreach ($lstRooms as $room){
      if (isset($room->user->name)){
        $own = ucfirst(substr($room->user->name, 0, 6));
        $own .= ' ('.substr($room->nameRoom, 0, 6).')';
      } else {
        $own = $room->nameRoom;
      }
      $aux = [
        'id'=> $room->id,
        'name'=> $own,
        'type'=>$room->sizeApto,
        'semaf'=>'green'
      ];
      for ($i = 0; $i < 3; $i++){
        $auxVal = $room->getCostPropByYear($year-$i);
        $aux[$year-$i] = $auxVal;
        if ($i == 0){
          if (!isset($rRoomType[$room->sizeApto])){
            $rRoomType[$room->sizeApto] =['t'=>0,'c'=>0];
          }
          $rRoomType[$room->sizeApto]['t'] += $auxVal;
          $rRoomType[$room->sizeApto]['c']++;
        }
      }
      $result[] = $aux;
    }

    $rRoomTypeMedia = [];
    $allSize = \App\SizeRooms::all()->pluck('name','id')->toArray();
    foreach ($rRoomType as $k=>$v){
      $rRoomType[$k]['p'] = round($v['t'] / $v['c']);
      if (isset($allSize[$k])){
        $rRoomTypeMedia[] = [
          'n'=> $allSize[$k],
          'v'=>$rRoomType[$k]['p']
        ];
      }
    }

    foreach ($result as $k=>$v){
      if (isset($rRoomType[$v['type']]) && $rRoomType[$v['type']]['p']>0){
        $media = $v[$year] / $rRoomType[$v['type']]['p'];
        if ($media<1){
          if ($media>0.89) 
            $result[$k]['semaf']='yellow';
          else 
            $result[$k]['semaf']='red';
        } else {
          if ($media<1.11) $result[$k]['semaf']='yellow';
        }
      }
    }
    
   
    return view('backend.paymentspro.blocks.resume-by-rooms-years',[
      'year'=>$oYear,
      'media'=>$rRoomTypeMedia,
      'result'=>$result
    ]);
  }

}
