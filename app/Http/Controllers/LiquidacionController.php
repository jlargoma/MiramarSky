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

    $oLiq = new Liquidacion();
    $data = $this->getTableData();
    $data['summary'] = $oLiq->summaryTemp();
//    dd($data['summary']);
    $data['stripeCost'] = $oLiq->getTPV($data['books']);
    $data['total_stripeCost'] = array_sum($data['stripeCost']);
    if (Auth::user()->role == "subadmin"){
      return view('backend/sales/index-subadmin', $data);
    }

    return view('backend/sales/index', $data);
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
    $data['summary'] = $oLiq->summaryTemp();
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
        ];
        
    $year = $this->getActiveYear();
    $qry_books = Book::where_type_book_sales()
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
      
      $banco = $book->getPayment(2) + $book->getPayment(3);
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
      $totales["stripe"] += $book->stripeCost;
      $totales["obs"] += $book->extraCost;
      $totales["pendiente"] += $book->pending;

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
      //O29-J30-N23
//      dd($data['t_ingrTabl_iva'],$data['gasto_ff_iva'],$data['iva_soportado']);
      $data['repartoTemp_fix_iva1'] = $data['t_ingrTabl_iva']-$data['gasto_ff_iva']-$data['iva_soportado'];
      $data['repartoTemp_fix_iva2'] = $data['repartoTemp_fix_iva1']-$data['iva_jorge'];

      $repartoTemp_total1 = $data['repartoTemp_fix']-$data['repartoTemp_fix_iva1'];
      $repartoTemp_total2 = $data['repartoTemp_fix']-$data['repartoTemp_fix_iva2'];

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
    
//        dd($data);
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
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
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
    /*************************************************************************/
    $books = \App\Book::where_type_book_sales()
            ->where('start', '>=', $startYear)
            ->where('start', '<=', $endYear)->get();
    $aExpensesPending = $oLiq->getExpensesEstimation($books);
    $aux = $emptyMonths;
    $vta_agency = $vta_prop = $tCosts = 0;
    $t_books = count($books);
    foreach ($books as $key => $book) {
      $m = date('ym', strtotime($book->start));
      $value = $book->total_price;
      $tCosts += $book->get_costeTotal();
      if (isset($aux[$m])) $aux[$m] += $value;
      if (isset($tIngByMonth[$m])) $tIngByMonth[$m] += $value;
      $lstT_ing['ventas'] += $value;
      if ($book->agency != 0) $vta_agency++;

    }
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
    $lstT_ing['ff'] = $auxFF['total'];
//    $lstT_ing['ff_FFExpress'] = $auxFF['totalFFExpress'];
//    $lstT_ing['ff_ClassesMat'] = $auxFF['totalClassesMat'];
    $aIngrPending['ff'] = 0;
    /*************************************************************************/
    
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
    
    $ingrType['ventas'] = 'VENTAS';
    $ingrType['others'] = 'OTROS INGRESOS';
    
    
    /*************************************************************************/
    /******       GASTOS                        ***********/
    $gastos = \App\Expenses::where('date', '>=', $startYear)
                    ->Where('date', '<=', $endYear)
                    ->WhereNull('PayFor')
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
    $gastos = \App\Expenses::where('date', '>=', $startYear)
                    ->Where('date', '<=', $endYear)
                    ->WhereNotNull('PayFor')
                    ->orderBy('date', 'DESC')->get();
//                    ->Where('type','=','prop_pay')
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

    $aExpensesPending['excursion'] = $lstT_ing['ff']-$lstT_gast['excursion'];
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
 
//    $impuestos = $listGastos['impuestos'];
//    unset($listGastos['prop_pay']);
    unset($listGastos['comisiones']);
    $totalPendingImp = 0;
//    $impEstimado = [];
//    
//    $gTypesImp = \App\Expenses::getTypesImp();
//    if ($gTypesImp){
//      foreach ($lstMonths as $k_m=>$m){
//        $impuestoM = 0;
//        foreach ($gTypesImp as $k_t=>$v){
//          $impuestoM += $listGastos[$k_t][$k_m];
//        }
//        
//        $impEstimado[$k_m] = ($tIngByMonth[$k_m]* 0.21 ) - ($impuestoM*0.21);
//        
//      }
//      
//    }
//      
//    $totalPendingImp = array_sum($impEstimado)-$lstT_gast['impuestos'];
//    ( T ingr * 0.21 ) - ( TGasto *0.21 )

    /*****************************************************************/
    
    $totalIngr = array_sum($lstT_ing);
    $totalGasto = array_sum($lstT_gast);
    
    
    $summary = $oLiq->summaryTemp();
  
    
    
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
        'year' => $year,
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
    
    $tPayProp = $data['lstT_gast']['prop_pay']+$data['aExpensesPending']['prop_pay'];
    $data['tGastByMonth'] = $tGastByMonth;
    
    $data['totalGasto'] = array_sum($data['lstT_gast']);
    $data['ingr_bruto'] = $data['totalIngr']-$data['totalGasto'];
    
    
    $iva_jorge = 0;
    $iva_soportado = 0;
    $ivaTemp = \App\Settings::getKeyValue('IVA_'.$data['year']->year);
    if ($ivaTemp){
      $ivaTemp = json_decode($ivaTemp);
      $iva_soportado = $ivaTemp[0];
      $iva_jorge     = $ivaTemp[1];
    }
    $resultIVA_modif = $iva_jorge+$iva_soportado;
    /*****************************************************************/
    /******   FORMULAS EXCEL                          ***************/
    //INGRESOS POR VENTAS DE RESERVAS
//    dd($data);
//    $vtas_reserva = $data['lstT_ing']['ventas'];
    $ingr_reservas = $data['lstT_ing']['ventas']-$tPayProp;
    $ing_baseImp   = $ingr_reservas/1.21;
    $ing_iva       = $ingr_reservas-$ing_baseImp;
    
    
    $vtas_alojamiento = $data['lstT_ing']['ventas'];
    $vtas_alojamiento_base = $ing_baseImp+$tPayProp;
    $vtas_alojamiento_iva  = $ing_iva;
    
    //EXTRAORDINARIOS + RAPPEL CLASES + RAPPEL FORFAITS
    $data['otros_ingr'] = $data['lstT_ing']['extr']
            +$data['lstT_ing']['rappel_clases']
            +$data['lstT_ing']['rappel_forfaits']
            +$data['lstT_ing']['rappel_alq_material']
            +$data['lstT_ing']['others'];
    $data['otros_ingr_base'] = $data['otros_ingr']/1.21;
    $data['otros_ingr_iva']  = $data['otros_ingr']-$data['otros_ingr_base'];
    
    
    
    
    //INGRESOS POR VENTAS DE FORFAITS
   
    
    $_ff_FFExpress_baseImp   = ($data['ff_FFExpress']>0) ? $data['ff_FFExpress']/1.1 : 0;
    $_ff_FFExpress_iva       = $data['ff_FFExpress']-$_ff_FFExpress_baseImp;
    $_ff_ClassesMat_baseImp  = ($data['ff_ClassesMat']>0) ? $data['ff_ClassesMat']/1.21 : 0;
    $_ff_ClassesMat_iva      = $data['ff_ClassesMat']-$_ff_ClassesMat_baseImp;
    
    $ing_comision_baseImp   = ($data['lstT_ing']['rappel_forfaits']>0) ? $data['lstT_ing']['rappel_forfaits']/1.21 : 0;
    $ing_comision_iva       = ($ing_comision_baseImp>0) ? $ing_comision_baseImp/1.21 : 0;
    
    //TOTAL GASTOS PROV FORFAITS/CLASES
    
    
    $ing_ff_baseImp   = $_ff_ClassesMat_baseImp+$_ff_FFExpress_baseImp;
    $ing_ff_iva       = $_ff_FFExpress_iva+$_ff_ClassesMat_iva;
    
    $gasto_ff = $data['ff_FFExpress']+$data['ff_ClassesMat'];
    $gasto_ff_baseImp = $_ff_ClassesMat_baseImp+$_ff_FFExpress_baseImp;
    $gasto_ff_iva     = $_ff_FFExpress_iva+$_ff_ClassesMat_iva;
//    $gasto_ff = $data['lstT_gast']['excursion'] + floatval ($data['aExpensesPending']['excursion']);
//    $gasto_ff_baseImp = $gasto_ff/1.1;
//    $gasto_ff_iva     = $gasto_ff-$gasto_ff_baseImp;
    
    
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
    foreach ($gastos_operativos as $k)
      $tGastos_operativos += $data['lstT_gast'][$k] + floatval ($data['aExpensesPending'][$k]);

    
    $gasto_operativo_iva     = $iva_soportado;
    $gasto_operativo_baseImp = $tGastos_operativos-$gasto_operativo_iva;
    
    
    $data['ingr_reservas'] = $ingr_reservas;
    $data['ing_baseImp'] = $ing_baseImp;
    $data['ing_iva'] = $ing_iva;
    $data['ing_ff_baseImp'] = $ing_ff_baseImp;
    $data['ing_ff_iva'] = $ing_ff_iva;
    $data['_ff_FFExpress_baseImp'] = $_ff_FFExpress_baseImp;
    $data['_ff_FFExpress_iva'] = $_ff_FFExpress_iva;
    $data['_ff_ClassesMat_baseImp'] = $_ff_ClassesMat_baseImp;
    $data['_ff_ClassesMat_iva'] = $_ff_ClassesMat_iva;
    
    $data['ing_comision_baseImp'] = $ing_comision_baseImp;
    $data['ing_comision_iva'] = $ing_comision_iva;
    $data['gasto_ff'] = $gasto_ff;
    $data['gasto_ff_baseImp'] = $gasto_ff_baseImp;
    $data['gasto_ff_iva'] = $gasto_ff_iva;
    $data['gasto_operativo_baseImp'] = $gasto_operativo_baseImp;
    $data['gasto_operativo_iva'] = $gasto_operativo_iva;
    $data['tGastos_operativos'] = $tGastos_operativos;
    $data['tPayProp'] = $tPayProp;
    
    $data['tIngr_base']   = $ing_baseImp+$ing_ff_baseImp+$ing_comision_baseImp;
    $data['tIngr_imp']    = $ing_iva+$ing_ff_iva+$ing_comision_iva;
    $data['tGastos_base'] = $gasto_ff_baseImp+$gasto_operativo_baseImp;
    $data['tGastos_imp']  = $gasto_ff_iva+$gasto_operativo_iva;
    
    
    $data['iva_jorge'] = $iva_jorge;
    $data['iva_soportado'] = $iva_soportado;
    $data['resultIVA_modif'] = $resultIVA_modif;
    
    $data['vtas_alojamiento']  = $vtas_alojamiento;
    $data['vtas_alojamiento_base']  = $vtas_alojamiento_base;
    $data['vtas_alojamiento_iva']  = $vtas_alojamiento_iva;
            
    
    
    
    $data['t_ingrTabl_base']  = $vtas_alojamiento_base+$ing_ff_baseImp+$data['otros_ingr_base'];
    $data['t_ingrTabl_iva']   = $vtas_alojamiento_iva+$ing_ff_iva+$data['otros_ingr_iva'];
    $data['t_gastoTabl_base'] = $tPayProp+$gasto_ff_baseImp+$gasto_operativo_baseImp;
    $data['t_gastoTabl_iva']  = $gasto_ff_iva+$gasto_operativo_iva;
 
    
    
    
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
    $soportado   = floatVal($request->input('soportado'));
    $jorge       = floatVal($request->input('jorge'));
    $temporada   = floatVal($request->input('temporada'));
    
    $key = 'IVA_'.$temporada;
    $objt = \App\Settings::where('key',$key)->first();
    if (!$objt){
      $objt = new \App\Settings();
      $objt->key = $key;
      $objt->name = 'IVAs tempo. '.$key;
    }
        
    $objt->value = json_encode(['0'=>$soportado,'1'=>$jorge]);
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
            ->Where('type',$key)->orderBy('date', 'DESC');
            
            
    if ($key != 'prop_pay')
      $qry->WhereNull('PayFor');
    
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
    $month = isset($aDate[1]) ? intval($aDate[1]) : null;
    $year  = isset($aDate[2]) ? $aDate[2] : null;
    $concept = $request->input('concept');
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
  /************       INGRESOS                                     ********/
  /*************************************************************************/
  /************       AgencyDetail                                 ********/
  /***********************************************************************/
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
      $books = \App\Book::where_type_book_sales()->with('payments')
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
  /***************************************************************************/
    
  public function contabilidad() {
    
    $oLiq = new Liquidacion();
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
    $tBooks = count($books);
    
    /* INDICADORES DE LA TEMPORADA */
    
    $countDiasPropios = \App\Book::where('start', '>', $startYear)
                  ->where('finish', '<', $endYear)
                  ->whereIn('type_book', [7,8])
                  ->sum('nigths');
    
    
    $summary = $oLiq->summaryTemp();

        
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
    $ffData = $oLiq->getFF_Data($startYear,$endYear);
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
        'summary' => $summary,
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
  
  
  
  
  public function exportExcel(Request $request) {
    
    
    
    $oLiq = new Liquidacion();
    $data = $this->getTableData();
    $data['summary'] = $oLiq->summaryTemp();
    $data['stripeCost'] = $oLiq->getTPV($data['books']);
    
    $year = $data['year']->year;
    
//    return view('backend.sales._tableExcelExport', $data);
    
    Excel::create('Liquidacion ' . $year, function ($excel) use ($data) {

      $excel->sheet('Liquidacion', function ($sheet) use ($data) {

        $sheet->loadView('backend.sales._tableExcelExport',$data);
      });
    })->download('xlsx');
    
    dd($data);
  }
  
  
  static function addBank($data){
    return null;
  }
  
}
