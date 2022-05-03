<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use \DB;
use App\Classes\Mobile;
use App\Book;
use App\Liquidacion;
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

    if (!Auth::user()->canSeeLiquidacion()){
      return redirect('no-allowed');
    }
    $oYear = $this->getActiveYear();
    $oLiq = new Liquidacion();
    $data = $this->getTableData();
    $data['summary'] = $oLiq->summaryTemp(false,$oYear);
    $data['stripeCost'] = $oLiq->getTPV($data['books']);
    $data['total_stripeCost'] = array_sum($data['stripeCost']);
    
    $cUser = Auth::user();
    /***************************************************************************/
    //es visible para Jaime ( subadministrador) y mariajo y jorge
    $salesByUser = [];
    if (in_array($cUser->id,[28,39,70])){
      $uIds = [39=>'Jorge',70=>'Mariajo',98=>'Web direct',11=>'OTAs'];
      
      $lstYears = \App\Years::where('year','<=',$oYear->year)->orderBy('year','DESC')->limit(5)->get();
      $type_book = Book::get_type_book_sales(true,true);
      $salesByUser = [39=>[],70=>[],11=>[],98=>[],0=>[]];
      $yearsLst = $salesByYear = [];
      foreach ($lstYears as $year){
        $yearsLst[] = substr($year->end_date,2,2).'-'.substr($year->start_date ,2,2);
        foreach ($uIds as $uID => $name){
          $salesByUser[$uID][$year->year] = 0;
          $tPvp = Book::where_book_times($year->start_date,$year->end_date)
                  ->where('user_id',$uID)
                  ->whereIn('type_book',$type_book)->sum('total_price');
          if ($tPvp)  $salesByUser[$uID][$year->year] = $tPvp;
        }
        
        $tPvp = Book::where_book_times($year->start_date,$year->end_date)
                  ->whereNotIn('user_id', array_keys($uIds))
                  ->whereIn('type_book',$type_book)->sum('total_price');
        if ($tPvp)  $salesByUser[0][$year->year] = $tPvp;
        else  $salesByUser[0][$year->year] = 0;
      }
      foreach($salesByUser as $k=>$v){
        foreach($v as $k1=>$v1){
          if (!isset($salesByYear[$k1])) $salesByYear[$k1] = 0;
          $salesByYear[$k1] += $v1;
        }
      }
      $data['salesByUser'] = $salesByUser;
      $data['salesByYear'] = $salesByYear;
      $data['uIdName'] = $uIds;
      $data['yearsLst'] = $yearsLst;
    }
    /***************************************************************************/
    
    if ($cUser->role == "admin"){
      return view('backend/sales/index', $data);
    }
    return view('backend/sales/index-subadmin', $data);
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
     
    $oLiq = new Liquidacion();
    $data['summary'] = $oLiq->summaryTemp(false,$data['year']);
    $data['stripeCost'] = $oLiq->getTPV($data['books']);
    $data['total_stripeCost'] = array_sum($data['stripeCost']);
             
    if (Auth::user()->role == "subadmin"){
      return view('backend/sales/_tableSummary-subadmin.blade', $data);
    }

    return view('backend/sales/_tableSummary', $data);
  }


  function getTableData($customerIDs=null,$agency=null,$roomID=null,$type=null){
      
        $totales = [
            "total" => 0,
            "coste" => 0,
            "costeApto" => 0,
            "costePark" => 0,
            "costeLujo" => 0,
            "costeLimp" => 0,
            "costeAgencia" => 0,
            "pendiente" => 0,
            "limpieza" => 0,
            "beneficio" => 0,
            "stripe" => 0,
            "obs" => 0,
            'adicionales'=>0,
            'banco'=>0,
            'caja'=>0,
            'luz_cost'=>0
        ];
        
    $year = $this->getActiveYear();
    $qry_books = Book::where_type_book_sales(true,true)
            ->where('start', '>=', $year->start_date)
            ->where('start', '<=', $year->end_date);
    
            
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

    $payments = [];
    $additionals = [];
    $t_books = count($books);
    $vta_agency = 0;
    foreach ($books as $key => $book) {

      $totales["total"] += $book->total_price;
      $totales["costeApto"] += $book->cost_apto;
      $totales["costePark"] += $book->cost_park;
      
      $banco = $book->getPayment(2) + $book->getPayment(3) + $book->getPayment(5);
      $caja  = $book->getPayment(0) + $book->getPayment(1);
      $totales["banco"]+= $banco;
      $totales["caja"] += $caja;
      
      $book->cost_total = $book->get_costeTotal();
      $book->total_ben = $book->total_price - $book->cost_total;
      $book->inc_percent = round($book->get_inc_percent(),2);
      $payments[$book->id] = ['banco'=>$banco,'caja'=>$caja];
      $book->pending = $book->total_price - array_sum($payments[$book->id]);
      
      $totales['coste'] += $book->cost_total;
      $totales["costeLujo"] += $book->get_costLujo();
      $totales["costeLimp"] += $book->cost_limp;
      $totales["costeAgencia"] += $book->PVPAgencia;
      $totales["limpieza"] += $book->sup_limp;
      $totales["stripe"] += paylandCost($book->getPayment(2));
      $totales["obs"] += $book->extraCost;
      $totales["pendiente"] += $book->pending;
      $totales["luz_cost"] += $book->luz_cost;

      $inc_percent = $book->get_inc_percent();
      if (round($inc_percent) <= $percentBenef) {
        if (!$book->has_low_profit) {
          $alert_lowProfits++;
        }
        $lowProfits[] = $book;
      }

    }
    $totales["beneficio"] = $totales["total"]-$totales["coste"];

    return [
          'books' => $books,
          'books_payments' => $payments,
          'lowProfits' => $lowProfits,
          'alert_lowProfits' => $alert_lowProfits,
          'percentBenef' => $percentBenef,
          'totales' => $totales,
          'year' => $year
          
      ];
     
  }

  //admin/perdidas-ganancias/{year?}
  public function perdidasGanancias() {
    $cUser = Auth::user();
    // Sólo lo puede ver jorge
    if ($cUser->email != "jlargo@mksport.es"){
      return $this->perdidasGananciasFuncional();
    }
      
      $data = $this->get_perdidasGanancias();
      $data = $this->perdidasGanaciasExcels($data);
      $benefJorge = \App\Settings::getKeyValue('benf_jorge');
      if ($benefJorge == null) $benefJorge = 100;
      $benefJaime = 100-$benefJorge;

      $benefJorge_perc = $benefJorge/100;
      $benefJaime_perc = $benefJaime/100;

      $data['repartoTemp_fix']=$data['ingr_reservas']+$data['otros_ingr']-
              ($data['gasto_operativo_baseImp']+$data['gasto_operativo_iva']);
      $data['repartoTemp_fix_iva1'] = $data['t_ingrTabl_iva']-$data['gasto_ff_iva']-$data['iva_soportado'];
      $data['repartoTemp_fix_iva2'] = $data['repartoTemp_fix_iva1']-$data['iva_jorge'];

      $repartoTemp_total1 = $data['repartoTemp_fix']-$data['t_iva'];
      $repartoTemp_total2 = $data['repartoTemp_fix']-$data['t_iva'];

      $data['repartoTemp_jorge1'] = $repartoTemp_total1*$benefJorge_perc;
      $data['repartoTemp_jaime1'] = $repartoTemp_total1*$benefJaime_perc;

      $data['repartoTemp_jorge2'] = $repartoTemp_total2*$benefJorge_perc;
      $data['repartoTemp_jaime2'] = $repartoTemp_total2*$benefJaime_perc;
      $data['benefJaime'] = $benefJaime;
      $data['benefJorge'] = $benefJorge;
    
    return view('backend/sales/perdidas_ganancias/index',$data);
  }
  public function perdidasGananciasFuncional() {
    $data = $this->get_perdidasGanancias();
    
    $expensesToDel = [
      "sueldos",
      "seg_social",
      "serv_prof",
      "alquiler",
      "seguros",
      "suministros",
      "equip_deco",
      "sabana_toalla",
      "bancario",
      "representacion",
      "publicidad",
      "mensaje",
      "varios",
    ];
    
    

    foreach ($expensesToDel as $k){
      if(isset($data['lstT_gast'][$k])){
        unset($data['lstT_gast'][$k]);
        unset($data['gastoType'][$k]);
        unset($data['listGasto'][$k]);
        unset($data['aExpensesPending'][$k]);
      }
    }
    
    $data = $this->perdidasGanaciasExcels($data);
    
  
    
    
    return view('backend/sales/perdidas_ganancias/index',$data);
  }
  
  
  public function get_perdidasGanancias() {
     
    $oLiq = new Liquidacion();
    $oYear = $this->getActiveYear();
    $startYear = new Carbon($oYear->start_date);
    $endYear = new Carbon($oYear->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;
    $lstMonths = lstMonths($startYear,$endYear,'ym',true);
    $ingresos = ['ventas'=>0,'ff'=>0];
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
    $totalPendingGasto = 0;
    $aIngrPending = [
        'ventas' => 0,
        ];
    $aux = $emptyMonths;
    $lstRvs = \App\BookDay::where_type_book_sales(true)
            ->where('date', '>=', $startYear)
            ->where('date', '<=', $endYear)->get();
    /*************************************************************************/
    /** @ToSee estimaciones sólo de las reservas vendidas? Pago a proveedores */
    $aExpensesPending = $oLiq->getExpensesEstimation($lstRvs);
    //dd($aExpensesPending);
    /*************************************************************************/
 
    foreach ($lstRvs as $key => $b) {
      $m = date('ym', strtotime($b->date));
      $value = $b->pvp;
      if (isset($aux[$m])) $aux[$m] += $value;
      if (isset($tIngByMonth[$m])) $tIngByMonth[$m] += $value;
      $lstT_ing['ventas'] += $value;
//      if ($b->agency != 0) $vta_agency++;
    }
    //--------------------------------------------------------------------------------------------//
    $ingresos['ventas'] = $aux;
    
    
    $ingrType = \App\Incomes::getTypes();
    foreach ($ingrType as $k=>$t){
      $ingresos[$k] = $emptyMonths;
      $lstT_ing[$k] = 0;
      $aIngrPending[$k] = 0;
    }
     
    /*************************************************************************/
        
    $auxFF = $oLiq->getFF_Data($startYear, $endYear);
    $ingresos['ff'] = $emptyMonths;
    $ingrType['ff'] = 'FORFAITs';
    $lstT_ing['ff'] = $auxFF['pay'];
//    $lstT_ing['ff_FFExpress'] = $auxFF['totalFFExpress'];
//    $lstT_ing['ff_ClassesMat'] = $auxFF['totalClassesMat'];
    $aIngrPending['ff'] = $auxFF['to_pay']+$auxFF['to_pay_mat'];
    $lstT_ing['ffTpay'] = $aIngrPending['ff'];
    /*************************************************************************/
    
    $ingresos['others'] = $emptyMonths;
    $lstT_ing['others'] = 0;
    $incomesLst = \App\Incomes::where('date', '>=', $startYear)->Where('date', '<=', $endYear)->get();
    if ($incomesLst){
      foreach ($incomesLst as $item){
        $m = date('ym', strtotime($item->date));
        $type = isset($ingrType[$item->type]) ? $item->type : 'others';
        $ingresos[$type][$m] += $item->import;
        if (isset($tIngByMonth[$m])) $tIngByMonth[$m] += $item->import;
        $lstT_ing[$type] += $item->import;
      }
    }

    $ingrType['ventas'] = 'VENTAS';
    $ingrType['others'] = 'OTROS INGRESOS';
    
    
    /*************************************************************************/
    /******       GASTOS                        ***********/
    $gastos = \App\Expenses::where('date', '>=', $startYear)
                    ->Where('date', '<=', $endYear)
                    ->where(function($q) {
                     $q->WhereNull('PayFor')
                       ->orWhere('PayFor','=','');
                    })
                    ->Where('type','!=','prop_pay')
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
    
    /***
     * Payment prop van todos los gastos específicos
     */
    $gastos = \App\Expenses::getPaymentToProp($startYear,$endYear);
    if ($gastos){
      foreach ($gastos as $g){
        $m = date('ym', strtotime($g->date));
        $listGastos['prop_pay'][$m] += $g->import;
        $lstT_gast['prop_pay'] += $g->import;
        if (isset($tGastByMonth[$m])) $tGastByMonth[$m] += $g->import;
      }
    }
    
    
    foreach ($gType as $k=>$v){
      if (isset($aExpensesPending[$k])){
        $aExpensesPending[$k] -= $lstT_gast[$k];
        if ($aExpensesPending[$k]<0) $aExpensesPending[$k] = 0;
      } else {
        $aExpensesPending[$k] = 0;
      }
    }
//dd($aExpensesPending);
    $aExpensesPending['excursion'] =  $auxFF['totalFFExpress']+$auxFF['to_pay']-$lstT_gast['excursion'];
    $aExpensesPending['prov_material'] = $auxFF['totalClassesMat']+$auxFF['to_pay_mat']-$lstT_gast['prov_material'];
    $aExpensesPendingOrig = $aExpensesPending;
    
    $oData = \App\ProcessedData::findOrCreate('PyG_Hide');
    if ($oData){
      $PyG_Hide = json_decode($oData->content,true);
      if ($PyG_Hide && is_array($PyG_Hide)){
        foreach ($PyG_Hide as $k){
          if(isset($aExpensesPending[$k])){
            $aExpensesPending[$k] = 'N/A';
          }
        }
      }
    }
      
    foreach ($aExpensesPending as $k=>$v){
      if ($v != 'N/A') $totalPendingGasto += intval($v);
    }
    
    
    /*****************************************************************/
    
    $expenses_fix = \App\Expenses::where('date', '=', $startYear)
                    ->WhereIn('type',['impuestos','iva'])
                    ->orderBy('date', 'DESC')->pluck('import','type')->toArray();
    
    if (!isset($expenses_fix['impuestos'])) $expenses_fix['impuestos'] = 0;
    if (!isset($expenses_fix['iva']))       $expenses_fix['iva'] = 0;

    unset($listGastos['comisiones']);
    $totalPendingImp = 0;
    /*****************************************************************/
       
    $totalIngr = array_sum($lstT_ing);
    $totalGasto = array_sum($lstT_gast);
    
    $summary = $oLiq->summaryTemp(false,$oYear);
  
    
//    dd($totalGasto,$lstT_gast);
    return ([
        'summary' =>$summary,
        'lstT_ing' => $lstT_ing,
        'totalIngr' => $totalIngr,
        'lstT_gast' => $lstT_gast,
        'totalGasto' => $totalGasto,
        'totalPendingGasto' => $totalPendingGasto,
        'totalPendingIngr' => array_sum($aIngrPending),
        'totalPendingImp' => $totalPendingImp,
        'ingresos' => $ingresos,
        'listGasto' => $listGastos,
        'aExpensesPending' => $aExpensesPending,
        'aExpensesPendingOrig' => $aExpensesPendingOrig,
        'aIngrPending' => $aIngrPending,
        'diff' => $diff,
        'lstMonths' => $lstMonths,
        'year' => $oYear,
        'tGastByMonth' => $tGastByMonth,
        'tIngByMonth' => $tIngByMonth,
        'ingrType' => $ingrType,
        'gastoType' => $gType,
        'expenses_fix' => $expenses_fix,
        'tExpenses_fix' => array_sum($expenses_fix),
        'ingr_bruto' => $totalIngr-$totalGasto,
        'ff_FFExpress' => $auxFF['totalFFExpress'],
        'ff_ClassesMat' => $auxFF['totalClassesMat']
    ]);
  }

  private function perdidasGanaciasExcels($data) {
      $tGastByMonth = [];
    foreach ($data['tGastByMonth'] as $k=>$v){
      $tGastByMonth[$k] = 0;
    }
    
    foreach ($data['listGasto'] as $k=>$months){
      foreach ($months as $m=>$v){
        if(isset($tGastByMonth[$m])) $tGastByMonth[$m] +=$v;
      }
    }
    /** @toSee pagos a propietarios no realizado aún? */
    $tPayProp = $data['lstT_gast']['prop_pay'];//+$data['aExpensesPending']['prop_pay'];
    $data['tGastByMonth'] = $tGastByMonth;
    
    $data['totalGasto'] = array_sum($data['lstT_gast']);
//    $data['ingr_bruto'] = $data['totalIngr']-$data['totalGasto'];
    
    $iva_jorge = 0;
    $iva_soportado = 0;

    /*****************************************************************/
    /******   FORMULAS EXCEL                          ***************/
    $ivas = [
        'ing_iva'=>21,
        'ff_FFExpress'=>10,
        'ff_ClassesMat'=>21,
        'ff_FFExpress_expense'=>10,
        'ff_ClassesMat_exp'=>21,
        'otros_ingr'=>21,
        'ff_ClassesMat_expense'=>10,
        'gasto_operativo'=>21,
    ];
    $oIvas = \App\Settings::whereIn('key', array_keys($ivas))->get();
    if ($oIvas){
      foreach ($oIvas as $iva){
        $ivas[$iva->key] = $iva->value;
      }
    }
    //INGRESOS POR VENTAS DE RESERVAS
//    $vtas_reserva = $data['lstT_ing']['ventas'];
    $ingr_reservas = $data['lstT_ing']['ventas']-$tPayProp-$data['aExpensesPending']['prop_pay'];
    $ing_baseImp   = $ingr_reservas/(1+($ivas['ing_iva']/100));
    $ing_iva       = $ingr_reservas-$ing_baseImp;
    
    
    $vtas_alojamiento = $ingr_reservas;
    $vtas_alojamiento_base = $ing_baseImp;
    $vtas_alojamiento_iva  = $ing_iva;
    
    //EXTRAORDINARIOS + RAPPEL CLASES + RAPPEL FORFAITS
    $data['otros_ingr'] = 0;
    $otherIng = $ingrType = \App\Incomes::getTypes();
    foreach ($otherIng as $k=>$v){
      if (isset($data['lstT_ing'][$k])){
         $data['otros_ingr'] += $data['lstT_ing'][$k];
      }
    }
    $data['otros_ingr_base'] = $data['otros_ingr']/(1+($ivas['otros_ingr']/100));
    $data['otros_ingr_iva']  = $data['otros_ingr']-$data['otros_ingr_base'];
    
    //INGRESOS POR VENTAS DE FORFAITS
//    $_ff_FFExpress_baseImp   = ($data['ff_FFExpress']>0) ? $data['ff_FFExpress']/1.1 : 0;
    $_ff_FFExpress_baseImp   = ($data['ff_FFExpress']>0) ? $data['ff_FFExpress']/(1+($ivas['ff_FFExpress']/100)) : 0;
    $_ff_FFExpress_iva       = $data['ff_FFExpress']-$_ff_FFExpress_baseImp;
    $g_excursion = $data['lstT_gast']['excursion'];
    $_ff_prov_baseImp   = ($g_excursion>0) ? $g_excursion/(1+($ivas['ff_FFExpress_expense']/100)) : 0;
    $_ff_prov_iva       = $g_excursion-$_ff_prov_baseImp;
    
    
//    $_ff_ClassesMat_baseImp  = ($data['ff_ClassesMat']>0) ? $data['ff_ClassesMat']/1.21 : 0;
    $_ff_ClassesMat_baseImp  = ($data['ff_ClassesMat']>0) ? $data['ff_ClassesMat']/(1+($ivas['ff_ClassesMat']/100)) : 0;
    $_ff_ClassesMat_iva      = $data['ff_ClassesMat']-$_ff_ClassesMat_baseImp;
    $g_material = $data['lstT_gast']['prov_material'];
    $_ff_mat_baseImp   = ($g_material>0) ? $g_material/(1+($ivas['ff_ClassesMat_exp']/100)) : 0;
    $_ff_mat_iva       = $g_material-$_ff_mat_baseImp;
    
    $ing_comision_baseImp   = ($data['lstT_ing']['rappel_forfaits']>0) ? $data['lstT_ing']['rappel_forfaits']/1.21 : 0;
    $ing_comision_iva       = ($ing_comision_baseImp>0) ? $ing_comision_baseImp/(1+($ivas['ff_ClassesMat']/100)) : 0;
    
    //TOTAL GASTOS PROV FORFAITS/CLASES
    
    
    $ing_ff_baseImp   = $_ff_ClassesMat_baseImp+$_ff_FFExpress_baseImp;
    $ing_ff_iva       = $_ff_FFExpress_iva+$_ff_ClassesMat_iva;
    
    $gasto_ff = $g_excursion+$g_material;
    $gasto_ff_baseImp = $_ff_prov_baseImp+$_ff_mat_baseImp;
    $gasto_ff_iva     = $_ff_prov_iva+$_ff_mat_iva;

    $gasto_operativo_baseImp = $gasto_operativo_iva = 0;
    $gastos_operativos = [
              "agencias",
              "amenities",
              "comision_tpv",
              "lavanderia",
              "limpieza",
              "mantenimiento"
            ];
    $tGastos_operativos = 0;
    $otherExpenses = $data['lstT_gast'];
    unset($otherExpenses['prop_pay']);
    unset($otherExpenses['excursion']);
    unset($otherExpenses['prov_material']);
    foreach ($gastos_operativos as $k){
      $tGastos_operativos += $data['lstT_gast'][$k];// + floatval ($data['aExpensesPending'][$k]);
      unset($otherExpenses[$k]);
    }
    
    $otherExpenses = array_sum($otherExpenses);
//    $tGastos_operativos
    
    $iva_soportado = round(($tGastos_operativos*($ivas['gasto_operativo']/100)),2);
    $gasto_operativo_iva     = $iva_soportado;
    $gasto_operativo_baseImp = $tGastos_operativos-$gasto_operativo_iva;
    $ivaTemp = \App\Settings::getKeyValue('IVA_'.$data['year']->year);
    if (!is_numeric($ivaTemp)) $ivaTemp = 0;
    
    $data['ingr_reservas'] = $ingr_reservas;
    $data['ing_baseImp'] = $ing_baseImp;
    $data['ing_iva'] = $ing_iva;
    $data['ing_ff_baseImp'] = $ing_ff_baseImp;
    $data['ing_ff_iva'] = $ing_ff_iva;
    $data['_ff_FFExpress_baseImp'] = $_ff_FFExpress_baseImp;
    $data['_ff_FFExpress_iva'] = $_ff_FFExpress_iva;
    $data['_ff_ClassesMat_baseImp'] = $_ff_ClassesMat_baseImp;
    $data['_ff_ClassesMat_iva'] = $_ff_ClassesMat_iva;
    
    $data['_ff_mat_baseImp'] = $_ff_mat_baseImp;
    $data['_ff_mat_iva'] = $_ff_mat_iva;
    $data['_ff_prov_baseImp'] = $_ff_prov_baseImp;
    $data['_ff_prov_iva'] = $_ff_prov_iva;
    
    $data['ing_comision_baseImp'] = $ing_comision_baseImp;
    $data['ing_comision_iva'] = $ing_comision_iva;
    $data['gasto_ff'] = $gasto_ff;
    $data['gasto_ff_baseImp'] = $gasto_ff_baseImp;
    $data['gasto_ff_iva'] = $gasto_ff_iva;
    $data['gasto_operativo_baseImp'] = $gasto_operativo_baseImp;
    $data['gasto_operativo_iva'] = $gasto_operativo_iva;
    $data['tGastos_operativos'] = $tGastos_operativos;
    $data['otherExpenses'] = $otherExpenses;
    $data['tPayProp'] = $tPayProp;
    
    $data['tIngr_base']   = $ing_baseImp+$ing_ff_baseImp+$ing_comision_baseImp;
    $data['tIngr_imp']    = $ing_iva+$ing_ff_iva+$ing_comision_iva;
    $data['tGastos_base'] = $gasto_ff_baseImp+$gasto_operativo_baseImp;
    $data['tGastos_imp']  = $gasto_ff_iva+$gasto_operativo_iva;
    
    
    $data['iva_jorge'] = $iva_jorge;
    $data['iva_soportado'] = $iva_soportado;
    $data['ivaTemp'] = $ivaTemp;
    
    $data['vtas_alojamiento']  = $vtas_alojamiento;
    $data['vtas_alojamiento_base']  = $vtas_alojamiento_base;
    $data['vtas_alojamiento_iva']  = $vtas_alojamiento_iva;
            
    
    
    
    $data['t_ingrTabl_base']  = $vtas_alojamiento_base+$ing_ff_baseImp+$data['otros_ingr_base']+$tPayProp+$data['aExpensesPending']['prop_pay'];
    $data['t_ingrTabl_iva']   = $vtas_alojamiento_iva+$ing_ff_iva+$data['otros_ingr_iva'];
    $data['t_gastoTabl_base'] = $tPayProp+$gasto_ff_baseImp+$gasto_operativo_baseImp;
    $data['t_gastoTabl_iva']  = $gasto_ff_iva+$gasto_operativo_iva;
    $data['ivas']  = $ivas;
    
    
    $ivaSoportado = \App\Settings::getKeyValue('IVA_SOP'.$data['year']->year);
    if (!$ivaSoportado) $ivaSoportado = round($data['t_gastoTabl_iva']);
    $data['ivaSoportado'] = $ivaSoportado;
    
    $data['t_iva'] = $data['t_ingrTabl_iva'] - $ivaSoportado + $data['ivaTemp'];
    
    $data['_ff_mat_baseImp'] = $_ff_mat_baseImp;
    $data['_ff_mat_iva'] = $_ff_mat_iva;
    $data['_ff_prov_baseImp'] = $_ff_prov_baseImp;
    $data['_ff_prov_iva'] = $_ff_prov_iva;
    
    
    /******   FORMULAS EXCEL                          ***************/
    /***************************************************************/
    return $data;
  }
  public function perdidasGananciasShowHide(Request $request) {
    
    $key   = $request->input('key');
    $value = $request->input('input');
    
    
    $oData = \App\ProcessedData::findOrCreate('PyG_Hide');
    if ($oData) $PyG_Hide = json_decode($oData->content,true);
      
    if (!$PyG_Hide) $PyG_Hide = [];
    
    if ($value == 'hide'){
      if (!in_array($key, $PyG_Hide)){
        $PyG_Hide[] = $key;
        $oData->content = json_encode($PyG_Hide);
        $oData->save();
        return 'OK';
      }
    }
    
    if ($value == 'show'){
      if (in_array($key, $PyG_Hide)){
        if (($key_array = array_search($key, $PyG_Hide)) !== false) {
            unset($PyG_Hide[$key_array]);
          $oData->content = json_encode($PyG_Hide);
          $oData->save();
          return 'OK';
        }
      }
    }
    
    return 'error';
    
  }
  
  public function perdidasGananciasUpdIVA(Request $request) {
    $val   = floatVal($request->input('val'));
    $type  = ($request->input('type'));
//    $jorge       = floatVal($request->input('jorge'));
    $temporada   = floatVal($request->input('temporada'));
    if ($type == 1) $key = 'IVA_'.$temporada;
    if ($type == 2) $key = 'IVA_SOP'.$temporada;
    
    $objt = \App\Settings::where('key',$key)->first();
    if (!$objt){
      $objt = new \App\Settings();
      $objt->key = $key;
      $objt->name = 'IVAs tempo. '.$key;
    }
        
    $objt->value = $val;
    if ($objt->save()) return 'OK';
    return 'error';
  }
  
  public function perdidasGananciasUpdBenef(Request $request) {
    $value   = floatVal($request->input('input'));
    
    $benefJorge = \App\Settings::where('key','benf_jorge')->first();
    if (!$benefJorge){
      $benefJorge = new \App\Settings();
      $benefJorge->key = 'benf_jorge';
      $benefJorge->name = '% Benf Jorge';
    }
        
    $benefJorge->value = $value;

    if ($benefJorge->save()) return 'OK';
    return 'error';
  }
  
  public function perdidasGananciasUpdIngr(Request $request) {
    
    $key     = $request->input('key');
    $value   = floatVal($request->input('input'));
    $month   = $request->input('month');
    
    
//    if ($value<0) return 'error';
    
    /* BEGIN: Save impuestos and IVA */
    if ($key == 'impuestos' || $key == 'iva'){
      $year = \App\Years::getActive();
      $expenses_fix = \App\Expenses::where('date', '=', $year->start_date)
                    ->Where('type',$key)->first();
      if (!$expenses_fix){
        $expenses_fix = new \App\Expenses();
        $expenses_fix->date = $year->start_date;
        $expenses_fix->type = $key;
      }
      
      $expenses_fix->import = $value;
      if ($expenses_fix->save())  return 'OK';
       return 'error';
    }
    /* END: Save impuestos and IVA */
    
    $date_y  = '20'.$month[0].$month[1];
    $date_m  = intval($month[2].$month[3]);
    
    $obj = \App\Incomes::where('year',$date_y)
            ->where('month',$date_m)
            ->where('concept',$key)->first();
    if ($obj){
      $obj->import = ($value);
      if ($obj->save())  return 'OK';
    } else {
      $obj = new \App\Incomes();
      $obj->import  = ($value);
      $obj->concept = $key;
      $obj->year    = $date_y;
      $obj->month   = $date_m;
      $obj->date    = $date_y.'-'.$date_m.'-01';
      if ($obj->save())  return 'OK';
    }
   
    return 'error';
    
  }
  
  public function perdidasGananciasShowDetail($key) {
    
    $year = \App\Years::getActive();
    $typePayment = \App\Expenses::getTypeCobro();
    $qry = \App\Expenses::where('date', '>=', $year->start_date)
            ->Where('date', '<=', $year->end_date)
            ->orderBy('date', 'DESC');
            
            
    if ($key == 'prop_pay'){
        $qry->where(function ($query) {
        $query->WhereNotNull('PayFor')
              ->Where('PayFor', '!=', '');
        });
    } else {
        $qry->where(function ($query) {
        $query->WhereNull('PayFor')
              ->orWhere('PayFor', '=', '');
        })->Where('type',$key);
    }
    
    
    $expense = $qry->orderBy('date', 'DESC')->get();
    $total = $qry->sum('import');
    return view('backend.sales.gastos._details',['items'=>$expense,'total'=>$total,'typePayment'=>$typePayment]);
  }
  /*************************************************************************/
  /************       GASTOS                                        ********/
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
    
    $date = Carbon::createFromFormat('d/m/Y', $request->input('fecha'));
    if ($date->format('Y')<2000){
      $date = Carbon::createFromFormat('d/m/y', $request->input('fecha'));
    }
    $gasto = new \App\Expenses();
    $gasto->concept = $request->input('concept');
    $gasto->date = $date->format('Y-m-d');
    $gasto->import = $request->input('import');
    $gasto->typePayment = $request->input('type_payment');
    $gasto->type = $request->input('type');
    $gasto->comment = $request->input('comment');

    if ($request->input('type_payFor') == 1) {
      $gasto->PayFor = $request->input('asig_rooms');
    }

    if ($gasto->save()) {
      if ($request->has('back')) return redirect()->back();
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
        return 'error';
      }
    }
 
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
  /************       GASTOS                                        ********/
  /*************************************************************************/
  /************       INGRESOS                                     ********/
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
        $type = isset($ingrType[$item->type]) ? $item->type : 'others';
        if (!isset($ingrMonths[$type][$date])) $ingrMonths[$type][$date] = 0;
        $ingrMonths[$type][$date] += $item->import;
        $ingrMonths[$type][0] += $item->import;
       
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
    $month = isset($aDate[1]) ? intval($aDate[1]) : null;
    $year  = isset($aDate[2]) ? $aDate[2] : null;
    $concept = $request->input('concept');
    $type = $request->input('type');
    $import = floatval($request->input('import',null));
    
    if ($month && $import){
        $ingreso = \App\Incomes::where('month',$month)
              ->where('year',$year)
              ->where('concept',$concept)
              ->first();
        
        if ($ingreso){
          $ingreso->import = $ingreso->import+$import;
        } else {
          $ingreso = new \App\Incomes();
          $ingreso->month = intval($month);
          $ingreso->year  = $year;
          $ingreso->concept  = $concept;
          $ingreso->type  = $type;
          $ingreso->date = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
          $ingreso->import = $import;
        }
      
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
   

    $current = ($year->year-2000).','.date('m');
    
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
  /************       INGRESOS                                     ********/
  /*************************************************************************/
  /************       AgencyDetail                                 ********/
  /***********************************************************************/
       
    public function getBookingAgencyDetails()
    {
        $agencyBooks    = [
            'years'  => [],
            'data'   => [],
            'items'  => [
                'fp'   => 'FAST PAYMENT',
                'wd'   => 'WEBDIRECT',
                'vd'   => 'V. Directa',
                'b'    => 'Booking',
                'ab'   => 'AirBnb',
                't'    => 'Trivago',
                'ag'   => 'Agoda',
                'ex'   => 'Expedia',
                'gh'   => 'GHotel',
                'bs'   => 'Bed&Snow',
                'jd'   => "Jaime Diaz",
                'se'   => 'S.essence',
                'c'    => 'Cerogrados',
                'h'    => 'HOMEREZ',
                'none' => 'Otras'
            ],
            'totals' => []
        ];
                  
        $yearLst = [];
        $aux = [];
       
        $oLiquidacion = new Liquidacion();
        $season    = self::getActiveYear();
        $yearFull  = $season->year;
        $yearLst[] = $yearFull;
        $year = $yearFull-2000;
        $dataSeason = $oLiquidacion->getBookingAgencyDetailsBy_date($season->start_date,$season->end_date);
        $agencyBooks['years'][$yearFull]    = $year . '-' . ($year + 1);
        $aux[$yearFull]     = $dataSeason['data'];
        $agencyBooks['totals'][$yearFull]   = $dataSeason['totals'];
        
      
        $season    = self::getYearData($yearFull-1);
        $yearFull  = $season->year;
        $yearLst[] = $yearFull;
        $year = $yearFull-2000;
        $dataSeason = $oLiquidacion->getBookingAgencyDetailsBy_date($season->start_date,$season->end_date);
        $agencyBooks['years'][$yearFull]    = $year . '-' . ($year + 1);
        $aux[$yearFull]     = $dataSeason['data'];
        $agencyBooks['totals'][$yearFull]   = $dataSeason['totals'];
        
        $season    = self::getYearData($yearFull-1);
        $yearFull  = $season->year;
        $yearLst[] = $yearFull;
        $year = $yearFull-2000;
        $dataSeason = $oLiquidacion->getBookingAgencyDetailsBy_date($season->start_date,$season->end_date);
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

        echo json_encode(array(
                             'status'      => 'true',
                             'agencyBooks' => $agencyBooks,
                             'yearLst' => $yearLst,
                         ));
    }
    
  /***************************************************************************/
  
  public function getTableCaja(Request $request, $isAjax = true) {

    $page = $request->input('page', null);
    $year = $request->input('year', null);
    $month = $request->input('month', null);
    if (!$year) {
      return response()->json(['status' => 'wrong']);
    }
    $total = 0;
    if ($year<100) $year = '20'.$year;
    
    
     //last month
    $year_prev = intval($year);
    $monthPrev = ($month>1) ? $month-1 : 12;
    if ($monthPrev == 12) $year_prev--;
    $totalPrev = 0;
    

    $oRooms = \App\Rooms::all();
    $aptos = [];
    foreach ($oRooms as $r){
      $aptos[$r->id] = $r->nameRoom;
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
    
    $gType = \App\Expenses::getTypes();
    $ingrType = \App\Incomes::getTypes();
    $response = [
        'status' => 'false',
        'respo_list' => [],
    ];
    
    $respo_list = array();
    
    
    /********************************************************************/
    //// Expenses
    
    $gastos = $qry->orderBy('date')->get();
   
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
            'key'=> 'gasto-'.$item->id
        ];
      }
    }
    
     $totalPrevExpenses = \App\Expenses::whereYear('date','=', $year_prev)
            ->whereMonth('date','=', $monthPrev)
            ->where('typePayment',2)->sum('import');
    if ($totalPrevExpenses){
      $totalPrev -= intval($totalPrevExpenses);
    }
    /********************************************************************/
    //// Incomes
    
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
              'key'=> 'ingreso-'.$item->id
          ];
        }
      }
      
    
    $totalPrevIncomes = \App\Incomes::whereYear('date','=', $year_prev)
            ->whereMonth('date','=', $monthPrev)->sum('import');
    if ($totalPrevIncomes){
      $totalPrev += intval($totalPrevIncomes);
    }
    
    /***************************************************/
    // Pagos de reservas CASH y Reintegros
    $qry = \App\Payments::whereIn('type',[0,4]);
     if ($month) {
      $qry->whereYear('datePayment','=', $year)->whereMonth('datePayment','=', $month);
    }else{
      $oYear = \App\Years::where('year', $year)->first();
      $qry->where('datePayment', '>=', $oYear->start_date)->Where('datePayment', '<=', $oYear->end_date);
    }
    $oIngrPayments = $qry->orderBy('datePayment')->get();
    $bookLst = [];
    if ($oIngrPayments){

        foreach ($oIngrPayments as $item){
          $bookLst[] = $item->book_id;
        }
          
        $aBookLst = Book::whereIn('id', array_unique($bookLst))->pluck('room_id','id')->toArray();
       
        foreach ($oIngrPayments as $item){
          $total += $item->import;

          if (!isset($respo_list[strtotime($item->date)])) $respo_list[strtotime($item->date)] = [];

          $concept = 'Reserva <a href="'.route('book.update',$item->book_id).'" target="_blank">'.$item->book_id.'</a>';
          $aptoName = '';
          if (isset($aBookLst[$item->book_id]) && isset($aptos[$aBookLst[$item->book_id]])){
            $aptoName = ($aptos[$aBookLst[$item->book_id]]);
          }
          $respo_list[strtotime($item->date)][] = [
              'id'=> -1,
              'concept'=> $concept,
              'date'=> convertDateToShow_text($item->date),
              'type'=> 'Payment',
              'debe'=> $item->import,
              'haber'=> '--',
              'comment'=> $item->comment,
              'aptos'=> $aptoName,
              'key'=> 'payment-'.$item->id
          ];
        }
    }
    
    $totalPrevIncomes = \App\Payments::whereIn('type',[0,4])
            ->whereYear('datePayment','=', $year_prev)
            ->whereMonth('datePayment','=', $monthPrev)->sum('import');
    if ($totalPrevIncomes){
      $totalPrev += intval($totalPrevIncomes);
    }
    /***************************************************/
    // Arqueos
    $qry = \App\Arqueos::whereYear('date','=', $year);
    if ($month>0)   $qry->whereMonth('date','=', $month);
    $oObj = $qry->orderBy('date')->get();
    if ($oObj){
      foreach ($oObj as $item){
        $total += $item->import;
        
        if (!isset($respo_list[strtotime($item->date)])) $respo_list[strtotime($item->date)] = [];
        $respo_list[strtotime($item->date)][] = [
            'id'=> $item->id,
            'concept'=> $item->observ,
            'date'=> convertDateToShow_text($item->date),
            'type'=> 'Arqueo',
            'debe'=> ($item->import>0) ? ($item->import) : '--',
            'haber'=> ($item->import<0) ? ($item->import*-1) : '--',
            'comment'=> '',
            'aptos'=> "",
            'key'=> 'arqueo-'.$item->id
        ];
        
        
      }
    }
    
    $totalPrevArqueos = \App\Arqueos::whereYear('date','=', $year_prev)
            ->whereMonth('date','=', $monthPrev)->sum('import');
    if ($totalPrevArqueos){
      $totalPrev += intval($totalPrevArqueos);
    }
    
    } else { 
      /***************************************************/
    // Pagos de reservas BANCO
    $qry = \App\Payments::whereIn('type',[2,3]);
     if ($month) {
      $qry->whereYear('datePayment','=', $year)->whereMonth('datePayment','=', $month);
    }else{
      $oYear = \App\Years::where('year', $year)->first();
      $qry->where('datePayment', '>=', $oYear->start_date)->Where('datePayment', '<=', $oYear->end_date);
    }
    $oIngrPayments = $qry->orderBy('datePayment')->get();
    $bookLst = [];
    if ($oIngrPayments){

        foreach ($oIngrPayments as $item){
          $bookLst[] = $item->book_id;
        }
          
        $aBookLst = Book::whereIn('id', array_unique($bookLst))->pluck('room_id','id')->toArray();
       
        foreach ($oIngrPayments as $item){
          $total += $item->import;

          if (!isset($respo_list[strtotime($item->date)])) $respo_list[strtotime($item->date)] = [];

          $concept = 'Reserva <a href="'.route('book.update',$item->book_id).'" target="_blank">'.$item->book_id.'</a>';
          $aptoName = '';
          if (isset($aBookLst[$item->book_id]) && isset($aptos[$aBookLst[$item->book_id]])){
            $aptoName = ($aptos[$aBookLst[$item->book_id]]);
          }
          $respo_list[strtotime($item->date)][] = [
              'id'=> -1,
              'concept'=> $concept,
              'date'=> convertDateToShow_text($item->date),
              'type'=> 'Payment',
              'debe'=> $item->import,
              'haber'=> '--',
              'comment'=> $item->comment,
              'aptos'=> $aptoName,
              'key'=> 'payment-'.$item->id
          ];
        }
    }
    
    $totalPrevIncomes = \App\Payments::whereIn('type',[0,4])
            ->whereYear('datePayment','=', $year_prev)
            ->whereMonth('datePayment','=', $monthPrev)->sum('import');
    if ($totalPrevIncomes){
      $totalPrev += intval($totalPrevIncomes);
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
    } 
      
     $response = [
          'status' => 'true',
          'total' => moneda($total),
          'respo_list' => $return,
          'totalPrev' => $totalPrev,
          'month_prev' => getMonthsSpanish($monthPrev,false)
      ];
    
     
    
    if ($isAjax) {
      return response()->json($response);
    } else {
      return $response;
    }
  }
  function delCajaItem(Request $request){
    $key = $request->input('key');
    if (!$key) return response()->json(['status'=>'Error','msg'=>'item no encontrado']);
    $aux = explode('-', $key);
    
    if (!(is_array($aux) && count($aux)==2))
      return response()->json(['status'=>'Error','msg'=>'item no encontrado']);
    
    $type = $aux[0];
    $id = $aux[1];
    $oObj = null;
    if ($type == 'ingreso') $oObj = \App\Incomes::find($id);
    if ($type == 'arqueo') $oObj = \App\Arqueos::find($id);
    if ($type == 'gasto') $oObj = \App\Expenses::find($id);
    if ($type == 'payment') 
       return response()->json(['status'=>'Error','msg'=>'No se puede eliminar un cobro de una reserva']);
    
    if (!$oObj) 
      return response()->json(['status'=>'Error','msg'=>'item no encontrado']);
    
    
    if ($type == 'ingreso'){
      if ($oObj->book_id) 
        return response()->json(['status'=>'Error','msg'=>'No se puede eliminar un cobro de una reserva']);
    }
    
    if ($oObj->delete()){
      return response()->json(['status'=>'OK','msg'=>'Registro eliminado.']);
    }
    
    return response()->json(['status'=>'Error','msg'=>'Ocurrió un error']);
    
  }
  /***************************************************************************/
    
  public function contabilidad() {
    
    $oLiq = new Liquidacion();
    $data = $this->prepareTables();
    $months_empty = $data['months_empty'];
    $lstMonths = $data['lstMonths'];
    $t_room_month = $data['t_room_month'];
    $oYear = $data['year'];
    $channels = $data['channels'];
    $chRooms = $data['chRooms'];
    $sales_rooms = $data['sales_rooms'];
    $books = $data['books'];
    $startYear = $data['startYear'];
    $endYear = $data['endYear'];
    $diff = $startYear->diffInMonths($endYear) + 1;
    $tBooks = count($books);
    
    /* INDICADORES DE LA TEMPORADA */
    
    $countDiasPropios = \App\Book::where('start', '>=', $startYear)
                  ->where('start', '<=', $endYear)
                  ->whereIn('type_book', [7,8])
                  ->sum('nigths');
    
    
    $summary = $oLiq->summaryTemp(false,$data['year']);

        
    $cobrado = $metalico = $banco = $vendido = $vta_prop = 0;
    $booksPay = \App\Book::where_type_book_sales(true)->with('payments')
            ->where('start', '>=', $startYear)
            ->where('start', '<=', $endYear)->get();
    
    $cobrado = $metalico = $banco = $vendido = 0;
    foreach ($booksPay as $key => $book) {
      if ($book->payments){
        foreach ($book->payments as $pay){
          $cobrado += $pay->import;
          if ($pay->type == 0 || $pay->type == 1) {
            $metalico += $pay->import;
          } else  {
            $banco += $pay->import;
          }
        }
      }
      $vendido += $book->total_price;
    }

    $totBooks = count($books);
    //First chart PVP by months
    $dataChartMonths = [];
    
    foreach ($lstMonths as $k=>$v){
      $val = isset($t_room_month[$k]) ? $t_room_month[$k] : 0;
      $dataChartMonths[getMonthsSpanish($v['m'])] = $val;
    }
    //*******************************************************************//
    $ffData = $oLiq->getFF_Data($oYear->year);
    $months_ff = null;
    $cachedRepository  = new \App\Repositories\CachedRepository();
    $ForfaitsItemController = new \App\Http\Controllers\ForfaitsItemController($cachedRepository);
    $months_obj = $ForfaitsItemController->getMonthlyData($oYear);
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
        'year' => $oYear,
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
        'summary' => $summary,
        ]);
  }
  
  public function prepareTables() {
    $year = $this->getActiveYear();
    return self::static_prepareTables($year);
  }
  
  static function static_prepareTables($year) {
      
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    $lstMonths = lstMonths($startYear,$endYear);
    
    $books = \App\BookDay::where_type_book_sales(true)
            ->where('date', '>=', $startYear)
            ->where('date', '<=', $endYear)->get();
    
    
    $months_empty = array();
    $months_empty[0] = 0;
    foreach ($lstMonths as $k=>$v) $months_empty[$k] = 0;
    
        
    $channels = configZodomusAptos();
    $chRooms = [];
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
      $date = date('ym', strtotime($book->date));
      $value = $book->pvp;
      if (!isset($sales_rooms[$book->room_id])) $sales_rooms[$book->room_id] = [];
      if (!isset($sales_rooms[$book->room_id][$date])) $sales_rooms[$book->room_id][$date] = 0;
      $sales_rooms[$book->room_id][$date] += $value;
      
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
  
  
  
  
  public function exportExcel(Request $request) {
    
    
    
    $oLiq = new Liquidacion();
    $data = $this->getTableData();
    $data['summary'] = $oLiq->summaryTemp(false,$data['year']);
    $data['stripeCost'] = $oLiq->getTPV($data['books']);
    
    $year = $data['year']->year;
    
//    return view('backend.sales._tableExcelExport', $data);
    
    Excel::create('Liquidacion ' . $year, function ($excel) use ($data) {

      $excel->sheet('Liquidacion', function ($sheet) use ($data) {

        $sheet->loadView('backend.sales._tableExcelExport',$data);
      });
    })->download('xlsx');
  }
  
  function arqueoCreate(Request $request){
    $date =  $request->input('fecha');
    $aDate = explode('/', $date);
    $month = isset($aDate[1]) ? intval($aDate[1]) : null;
    $year  = isset($aDate[2]) ? $aDate[2] : null;
    $import = floatval($request->input('import',null));
    
    
    if ($month && $import){
          $arqueo = new \App\Arqueos();
          $arqueo->month = intval($month);
          $arqueo->year  = $year;
          $arqueo->observ  = $request->input('observ');
          $arqueo->date = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
          $arqueo->import = $import;
      if ($arqueo->save()) {
        return redirect()->back();
      }
    }
    return redirect()->back()->withErrors(['No se pudo cargar el Arqueo.']);
  }
  
  static function addBank($data){
    return null;
  }
  
  
  function gastos_import(Request $request){
    $data = $request->all();
   
    $campos = [
      'date' =>-1,  
      'concept' =>-1,  
      'type' =>-1, 
      'import' =>-1,  
      'typePayment' =>-1, 
      'apto' =>-1,  
      'comment' =>-1,
      'filter' =>-1,
    ];
    
  
    foreach ($data as $k=>$v){
      if ($v != '' && !is_array($v)){
        preg_match('/column_([0-9]*)/', $k, $colID);
        if (isset($colID[1]) && isset($campos[$v])){
          $campos[$v] = $colID[1];
        }
      } 
    }
          
    $info = [];
    foreach ($campos as $k=>$v){
      if (isset($data['cell_'.$v])){
        $info[$k] = $data['cell_'.$v];
      }
    }
    if (count($info) == 0) return back();
        
    /********   FILTRAR REGISTROS   *********************/
    if (isset($info['filter'])){
      foreach ($info['filter'] as $k=>$v){
        if ($v == 1){
          foreach ($campos as $k2=>$v2){
            $info[$k2][$k]=null;
          }
          
        }
      }
    }      
    /***************************************************/
    $expensesType = \App\Expenses::getTypes();
    $aRooms = \App\Rooms::getRoomList()->toArray();
    foreach ($aRooms as $k=>$v) $aRooms[$k] = strtoupper($v); 
//    foreach ($aRooms as $r) echo $r.'<br>'; die;
    /***************************************************/

    $campos = [
      'date' =>'Fecha',  
      'concept' =>'Concepto',  
      'type' =>'Tipo de Gasto', 
      'import' =>'Precio',  
      'typePayment' =>'Metodo de Pago', 
      'apto' =>'Apto',  
      'comment' =>'Comentario',
    ];
    
    $today = date('Y-m-d');
    $insert = [];
    $newEmpty = [
         'concept'=>null,'date'=>null,'import'=>null,'typePayment'=>null,
         'type'=>null,'comment'=>null,'PayFor'=>null
       ];
    
    $total = count(current($info));
    for($i = 0; $i<$total; $i++){
      $new = $newEmpty;
      foreach ($campos as $k=>$v){

        $value = '';
        if (!isset($info[$k])) continue;
        if (!isset($info[$k][$i])) continue;
        if (!($info[$k][$i])) continue;
        $variab = $info[$k][$i];
        
        switch ($k){
          case 'date':
            $new['date'] =  ($variab != '') ? convertDateToDB($variab) : $today;
            break;
          case 'import':
            $orig = $variab;
            $variab = floatval(str_replace(',','.',str_replace('.','', $variab)));
            $new['import'] = $variab;
            break;
          case 'typePayment':
            $aux = strtolower($variab);
            $idType = 0;
            if ($aux == 'banco'){$idType = 3;}
            if ($aux == 'cash') {$idType = 2;}
            $new['typePayment'] = $idType;
            break;
          case 'type':
            $type = array_search($variab,$expensesType);
            $new['type'] = $type;
            break;
          case 'apto':
            $aptoIDs = '';
            $value = '';
            $aAptoLst = explode(',',$variab);
            if (count($aAptoLst)){
              foreach ($aAptoLst as $r){
                $aptoID = array_search(strtoupper(trim($r)),$aRooms);
                if ($aptoID){
                  $aptoIDs .= $aptoID.',';
                }
              }
            }
            $new['PayFor'] = $aptoIDs;
            break;
          default:
            echo $k.' -> '.$variab.'<br>';
            $new[$k] = $variab;
            break;
        }
      }
            
      $hasVal = false;
      foreach ($new as $value){
        if ($value) $hasVal = true;
      }
      if ($hasVal)  $insert[] = $new;
    }
    $countInsert = count($insert);
    if ($countInsert>0)
      \App\Expenses::insert($insert);
    
    return back()->with(['success'=>$countInsert . ' Registros inportados']);
    
    $this->printData($campos,$info,$aRooms,$expensesType);
  }
  
  function printData($campos,$info,$aRooms,$expensesType){
        $newEmpty = [
          'concept'=>null,'date'=>null,'import'=>null,'typePayment'=>null,
          'type'=>null,'comment'=>null,'site_id'=>null,
        ];
    ?>
<style>
  th {
    background-color: #cccccc;
    padding: 7px !important;
    text-align: left;
}
td {
    padding: 7px;
    border: 1px solid #b7b7b7;
}
</style>
<?php
    echo '<table><thead><tr>';
    foreach ($campos as $k=>$v){
      echo '<th>'.$v.'</th>';
    }
    echo '</tr></thead>';
    echo '<tbody>';
    
    $total = count(current($info));
    for($i = 0; $i<$total; $i++){
      echo '<tr>';
      $new = $newEmpty;
      foreach ($campos as $k=>$v){

        $value = '';
        if (!isset($info[$k])){ echo '<td>---</td>'; continue;}
        if (!isset($info[$k][$i])){ echo '<td>---</td>'; continue;}
        if (!($info[$k][$i])){ echo '<td>---</td>'; continue;}
        $variab = $info[$k][$i];
        switch ($k){
          case 'date':
            $value = ($variab != '') ? convertDateToDB($variab) : 'Día/Mes/Año';
            $new['date'] =  ($variab != '') ? convertDateToDB($variab) : $today;
            break;
          case 'import':
            $orig = $variab;
            $variab = floatval(str_replace(',','.',str_replace('.','', $variab)));
            $value = moneda($variab,true,2).' (<b>'.$orig.'</b>)';
            $new['import'] = $variab;
            break;
          case 'typePayment':
            $value = 'Método no encontrado';
            $aux = strtolower($variab);
            $idType = 0;
            if ($aux == 'banco'){$value = 'Banco'; $idType = 3;}
            if ($aux == 'cash') {$value = 'CASH'; $idType = 2;}
            if ($aux == 'tarjeta') $value = 'Tarjeta visa';
            
            $new['typePayment'] = $idType;
            break;
          case 'type':
            $type = array_search($variab,$expensesType);
            $new['type'] = $type;
            $value = ($type) ? $variab : $type;
            break;
          case 'apto':
            $aptoIDs = '';
            $value = '';
            $aAptoLst = explode(',',$variab);
            if (count($aAptoLst)){
              foreach ($aAptoLst as $r){
                $aptoID = array_search(strtoupper(trim($r)),$aRooms);
                if ($aptoID){
                  $aptoIDs .= $aptoID.',';
                  $value .= $r.',';
                } else {
                  $value .= $r.'(no existe),';
                }
              }
            }
            $new['PayFor'] = $aptoIDs;
            break;
          default:
            $value = $variab;
            $new[$k] = $value;
            break;
        }
        echo '<td>'.$value.'</td>';
      }
      echo '</tr>';
      $insert[] = $new;
    }
    echo '</tbody></table>';
    
//    dd($insert);
//    \App\Expenses::insert($insert);
    die;
    
   
   
  }
  
    
  public function updateBook(Request $request) {
    $id = $request->input('id');
    $field = $request->input('field');
    $val = $request->input('val');
    
    $resp = ['status'=>'error','msg'=>''];
    
    $oBook = Book::find($id);
    if (!$oBook || $oBook->id != $id){
      $resp['msg'] = 'Reserva no encontrada';
      return response()->json($resp);
    }
    $aFields = ['luz_pvp','luz_cost','cost_limp','extraCost','cost_apto','cost_park','total_price'];
    if (in_array($field,$aFields)){

      $oBook->{$field} = intval($val);
      $oBook->cost_total = 9999999999999;
      $oBook->save();
      
      $resp['status'] = 'OK';
      $resp['msg'] = 'Reserva actualizada';
      return response()->json($resp);
      
    }
        
    $resp['msg'] = 'No se ha actualizado el valor';
    return response()->json($resp);
  }
  
}
