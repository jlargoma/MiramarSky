<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use App\Http\Controllers\INEController;
use App\Revenue;
use App\RevenuePickUp;
use App\Rooms;
use App\Book;
use App\BookDay;
use App\Services\Wubook\RateChecker;
use Excel;

class RevenueController extends AppController
{


  public function index(Request $req){
    
    $oYear  = $this->getActiveYear();
//    \App\BookDay::createSeasson($oYear->start_date,$oYear->end_date);
    
    $year   = $oYear->year;
    $month  = $req->input('month',date('n'));
    $month  = 0;
    $oServ = new \App\Services\RevenueService();
    $oServ->setDates($year.'.'.$month,$oYear);
    $monthStart = $oServ->start;
    $monthFinish = $oServ->finish;
    $oServ->setBook();
    $oServ->setRooms();
    $oServ->createDaysOfMonths($year);
    $ADR_finde = $oServ->getADR_finde();
    $forfaits = $oServ->getForfaits();
    $datosMes = view('backend.revenue.dashboard.mes',[
        'books' => $oServ->books,
        'roomCh' => $oServ->rChannel,
        'roomSite' => null,
        'aSites' => null,
        'days' => $oServ->days,
        'months' => $oServ->months,
        'lstMonths' => $oServ->lstMonths,
        'month' =>$month,
        'nights'=>$oServ->countNightsSite(),
        'rvas'=>$oServ->countBookingsSite(),
        'ADR_finde'=>$ADR_finde,
        'forfaits'=>$forfaits,
        'aRatios'=>$oServ->getRatios()
    ]);
    
    /*************************************************************/
    $oLiquidacion = new \App\Liquidacion();
    $dataSeason = $oLiquidacion->getBookingAgencyDetailsBy_date($oYear->start_date,$oYear->end_date);
    $agencias = view('backend.revenue.dashboard.agencias',[
        'data' => $dataSeason,
        'agencyBooks' => $oLiquidacion->getArrayAgency()
    ]);
//    echo ($agencias); die;
    /*************************************************************/
    $disponiblidad = $this->data_disponibilidad($oYear,$month,$oServ->start,$oServ->finish,true,$oServ->lstMonths);
    
    /*************************************************************/
    $oServ->start  = $oYear->start_date;
    $oServ->finish = $oYear->end_date;
    /*************************************************************/
    $oServ->setBook(); 
    $bookingCount = $oServ->countBookingsSiteMonths();
    $ADR_finde = $oServ->getADR_finde();
    $auxADR = $ADR_finde;
    $aRatios = $oServ->getRatios($oYear->year);
    $auxADR = $aRatios[0];
    $ADR_semana = $auxADR['c_s'] > 0 ? $auxADR['t_s'] / $auxADR['c_s'] : $auxADR['t_s'];
    $ADR_finde  = $auxADR['c_f'] > 0 ? $auxADR['t_f'] / $auxADR['c_f'] : $auxADR['t_f'];
    $summary    = $oLiquidacion->summaryTemp(false,$oYear);
    $viewRatios = [
        'books' => $oServ->books,
        'aRatios' => $aRatios,
        'roomCh' => $oServ->rChannel,
        'days' => $oServ->days,
        'lstMonths' => $oServ->lstMonths,
        'year' =>$oYear->year,
        'mDays' =>$oServ->mDays,
        'yDays' =>$oServ->mDays[0],
        'time_start' => strtotime($oYear->start_date),
        'time_end' =>strtotime($oYear->end_date),
        'rvas'=>$oServ->countBookingsSite(),
        'summary' => $summary,
        'ADR_semana'=>moneda($ADR_semana),
        'ADR_finde'=>moneda($ADR_finde),
    ];
    $ratios = view('backend.revenue.dashboard.ratios',$viewRatios);
    /*************************************************************/
    // COMPARATIVA INGRS ANUALES
    $viewRatios['comparativaAnual'] = $oServ->comparativaAnual(date('Y'));
    $oServ->setDates(null,$oYear);
    $oServ->setBook();
    $comp_ingresos_anuales = view('backend.revenue.dashboard.comp_ingresos_anuales',$viewRatios);
    /*************************************************************/
    $oFixCosts = \App\FixCosts::getByRang($oYear->start_date,$oYear->end_date);
    $oFCItems = \App\FixCosts::getLst();
    $fixCosts  = [];
    $fixCostsMonths  = [0=>0];
    foreach ($oServ->lstMonths as $k=>$v) $fixCostsMonths[$k] = 0;
    foreach ($oFixCosts as $fc){
      if (!isset($fixCosts[$fc->concept])){
        $fixCosts[$fc->concept] = $fixCostsMonths;
      }
      $date = date('y.m', strtotime($fc->date));
      $fixCosts[$fc->concept][$date] = intval($fc->content);
    }    
    foreach ($oFCItems as $k=>$v){
      if (isset($fixCosts[$k]))  $fixCosts[$k][0] = array_sum($fixCosts[$k]);
      else $fixCosts[$k][0] = 0;
    }
   
    $presupuesto = view('backend.revenue.dashboard.presupuesto',[
        'aRatios' => $aRatios,
        'months' => $oServ->months,
        'year' =>$oYear->year,
        'days' => $oServ->days,
        'mDays' =>$oServ->mDays,
        'lstMonths' =>$oServ->lstMonths,
        'fixCosts' => $fixCosts,
        'FCItems' => $oFCItems,
        'time_start' => strtotime($oYear->start_date),
        'time_end' =>strtotime($oYear->end_date),
        'month' =>$month,
        'bookingCount'=>$bookingCount,
        'tProp'=>$oServ->getTotalProp(),
        'monthlyLimp'=>$oServ->getMonthSum('cost_limp', 'finish', $oYear->start_date, $oYear->end_date),
        'monthlyOta'=>$oServ->getMonthSum('PVPAgencia', 'finish', $oYear->start_date, $oYear->end_date),
        'comisionesTPV' => $oServ->commisionTPVBookingsSiteMonths()
    ]);
    
    $presupuesto_head = view('backend.revenue.dashboard.presupuesto_head',[
        'month' =>$month,
        'months' => $oServ->months,
        'presupuesto' => $presupuesto,
        'lstMonths' =>$oServ->lstMonths,
        ]);
    
    
    /*************************************************************/
    $oYear = $this->getActiveYear();
    $liq = LiquidacionController::static_prepareTables($oYear);
//    dd($liq);
    $liq['months']        = $oServ->months;
    $liq['ingrMonths']    = $oServ->getIngrMonths($liq['chRooms']);
    $ingrMes = view('backend.revenue.dashboard.ingresos',$liq);
    /*************************************************************/
    
    $balanceFF = [];
    $ffTot = $forfaits['totals'];
    $typeFF = [
          't'=>'Total',
          'forfaits'=>'Forfaits',
          'clases'=>'Clases',
          'equipos'=>'Equipos',
          ''=>'Otros',
    ];
    foreach ($typeFF as $k=>$n){
      $balanceFF[$k][0] = isset($ffTot[0][$k]) ? $ffTot[0][$k]: 0; 
      foreach($oServ->lstMonths as $mk => $month){
        $balanceFF[$k][$mk] = isset($ffTot[$mk][$k]) ? $ffTot[$mk][$k]: 0;  
      }
    }
    unset($typeFF['t']);
    $balance = view('backend.revenue.dashboard.balance',[
        'lstMonths' => $oServ->lstMonths,
        'ingr' => $liq['ingrMonths'],
        'tIngr' => $liq['t_all_rooms'],
        'gastos' => $oServ->getExpenses(),
        'year' => $oYear,
        'ingrExt' => $oServ->getIncomesYear($year),
        'balanceFF' => $balanceFF,
        'typeFF' => $typeFF,
    ]);
    /*************************************************************/ 
    $ffData = [
        'total'=>0,
        'to_pay'=>0,
        'pay'=>0,
        'q'=>0
        ];
    if (isset($ffTot[0]['t'])){
      $ffData['total'] = $ffTot[0]['t'];
      $ffData['to_pay'] = $ffTot[0]['p'];
      $ffData['pay'] = $ffTot[0]['c'];
      $ffData['q'] = $ffTot[0]['q'];
    }
    
    $books = \App\Book::where_type_book_sales(true)->with('payments')
            ->where('start', '>=', $oYear->start_date)
            ->where('start', '<=', $oYear->end_date)->get();
    
    $cobrado = $metalico = $banco = $vendido = 0;
    $diff = [];
    foreach ($books as $key => $book) {
      $aux = 0;
      if ($book->payments){
        foreach ($book->payments as $pay){
          $cobrado += $pay->import;
          $aux += $pay->import;
          if ($pay->type == 0 || $pay->type == 1) {
            $metalico += $pay->import;
          } else  {
            $banco += $pay->import;
          }
        }
      }
      if (intVal($aux) != intVal($book->total_price)){
        $diff[] = [$book->id, $book->customer->name, $aux,$book->total_price];
        // echo $book->id.': '.intVal($aux).'!='.intVal($book->total_price).'<br>';
      }
      $vendido += $book->total_price;
    }
   
    $contabilidad = view('backend.revenue.dashboard.contabilidad',[
        'year' => $oYear,
        'yDays' =>$oServ->mDays[0],
        'summary' => $summary,
        'ADR_semana'=>moneda($ADR_semana),
        'ADR_finde'=>moneda($ADR_finde),
        'ingr_cobrado'=>$cobrado,
        'ingr_metalico'=>$metalico,
        'ingr_banco'=>$banco,
        'ingr_vendido'=>$vendido,
        'ffData'=>$ffData,
        'diffPending'=>$diff,
    ]);
    /*************************************************************/    
    return view('backend.revenue.dashboard',[
        'datosMes' => $datosMes,
        'year' => $year,
        'month' =>$month,
        'disponiblidad' => $disponiblidad,
        'ingrMes' => $ingrMes,
        'balance' => $balance,
        'ratios' => $ratios,
        'presupuesto_head' => $presupuesto_head,
        'agencias' => $agencias,
        'comp_ingresos_anuales' => $comp_ingresos_anuales,
        'contabilidad' => $contabilidad,
        'path'=>$req->path()
      ]);
    
  }
  
  function getMonthKPI($month){
    $oYear   = $this->getActiveYear();
    $oServ = new \App\Services\RevenueService();
    $oServ2 = new \App\Services\RevenueService();
    if ($month > 0){
      $oServ->setDates($month,$oYear);
    } else {
      $oServ->setDates(null,$oYear);
    }
    $oServ->setBook();
    $oServ->setRooms();
    $ADR_finde = $oServ->getADR_finde();
    $forfaits = $oServ->getForfaits();
    
    $oServ2->setDates(null,$oYear);
    $oServ2->setBook();

    return view('backend.revenue.dashboard.mes',[
        'books' => $oServ->books,
        'roomCh' => $oServ->rChannel,
        'days' => $oServ->days,
        'months' => $oServ->months,
        'lstMonths' => $oServ->lstMonths,
        'month' =>$month,
        'nights'=>$oServ->countNightsSite(),
        'rvas'=>$oServ->countBookingsSite(),
        'ADR_finde'=>$ADR_finde,
        'forfaits'=>$forfaits,
        'aRatios'=>$oServ2->getRatios()
    ]);
  }
  
  function updOverview(Request $req){
    $year = $req->input('y');
    $month = $req->input('m');
    $key = $req->input('k');
    $value = $req->input('v');
    $site = $req->input('s');
    $monthSelect = $req->input('ms');
    $d = [];
    
    if ($month>0){
      $sSummary = \App\Settings::findOrCreate('revenue_disponibilidad_'.$year.'_'.$month, $site);
    } else {
      $sSummary = \App\Settings::findOrCreate('revenue_disponibilidad_'.$year, $site);
    }
    $d = json_decode($sSummary->content, true);
    if (!is_array($d)) $d = [];
    $d[$key] = $value;
    
    $sSummary->content = json_encode($d);
    $sSummary->save();
    
    return $this->getOverview($monthSelect);
  }
  function getOverview($month){
    $oYear   = $this->getActiveYear();
    $oServ = new \App\Services\RevenueService();
    $oServ->setDates($month,$oYear);
    $oServ->start  = $oYear->start_date;
    $oServ->finish = $oYear->end_date;
    $oServ->setRooms();
    $oServ->createDaysOfMonths($oYear->year);
    $oServ->setBook();
    $oFixCosts = \App\FixCosts::getByRang($oYear->start_date,$oYear->end_date);
    $oFCItems = \App\FixCosts::getLst();
    $fixCosts  = [];
    $fixCostsMonths  = [0=>0];
    foreach ($oServ->lstMonths as $k=>$v) $fixCostsMonths[$k] = 0;
    foreach ($oFixCosts as $fc){
      if (!isset($fixCosts[$fc->concept])){
        $fixCosts[$fc->concept] = $fixCostsMonths;
      }
      $date = date('y.m', strtotime($fc->date));
      $fixCosts[$fc->concept][$date] = intval($fc->content);
    } 
    foreach ($oFCItems as $k=>$v){
      if (isset($fixCosts[$k]))  $fixCosts[$k][0] = array_sum($fixCosts[$k]);
      else $fixCosts[$k][0] = 0;
    }
    return view('backend.revenue.dashboard.presupuesto',[
        'aRatios' => $oServ->getRatios($oYear->year),
        'lstMonths' => $oServ->lstMonths,
        'year' =>$oYear->year,
        'days' => $oServ->days,
        'mDays' =>$oServ->mDays,
        'fixCosts' => $fixCosts,
        'FCItems' => $oFCItems,
        'time_start' => strtotime($oYear->start_date),
        'time_end' =>strtotime($oYear->end_date),
        'month' =>$month,
        'ratios' => $oServ->getRatios(null),
        'bookingCount'=>$oServ->countBookingsSiteMonths(),
        'monthlyLimp'=>$oServ->getMonthSum('cost_limp', 'finish', $oYear->start_date, $oYear->end_date),
        'monthlyOta'=>$oServ->getMonthSum('PVPAgencia', 'finish', $oYear->start_date, $oYear->end_date),
        'comisionesTPV' => $oServ->commisionTPVBookingsSiteMonths()
    ]);
  }
  
  function getMonthDisp($date){
    $oYear   = $this->getActiveYear();
    $oServ = new \App\Services\RevenueService();
    $oServ->setDates($date,$oYear);
    $month = explode('.',$date);
    $month = $month[1];
    return $this->data_disponibilidad($oYear,$month,$oServ->start,$oServ->finish,true,$oServ->lstMonths);
  }

  /*******************************************************************************/
  /*******************************************************************************/
  /*******************************************************************************/
  
  public function disponibilidad(Request $req,$return=false){
    $oYear   = $this->getActiveYear();
    $defaultMonth = ($oYear->year-2000).'.'.date('m');
    $month = $req->input('month',$defaultMonth);
    $start  = firstDayMonth($oYear->year, $month);
    $finish = lastDayMonth($oYear->year, $month);
     //-------------------------------------
    $lstMonths = [];
    $aux = strtotime($oYear->start_date);
    $auxEnd = strtotime($oYear->end_date);
    $aMonths = getMonthsSpanish(null,FALSE, TRUE);
    while ($aux <= $auxEnd){
      $lstMonths[date('y.m',$aux)] = $aMonths[date('n',$aux)];
      $aux = strtotime('+1 months' , $aux);
    }
    //-------------------------------------
    return $this->data_disponibilidad($oYear,$month,null,null,false,$lstMonths);
  }
  
  function data_disponibilidad($oYear,$month,$start,$finish,$return=false,$lstMonths){
    $chNames = configOtasAptosName(null);
    $aMonths = getMonthsSpanish(null,FALSE, TRUE);
    /************************************************************/
    /********   Prepare days array               ****************/
    if (!($start && $finish)){
      $d1 = new \DateTime('20'. str_replace('.', '-', $month).'-01');
      $d2 = clone $d1;
      $d1->modify('first day of this month');
      $d2->modify('last day of this month');
      $start = $d1->format("Y-m-d");
      $finish = $d2->format("Y-m-d");
    }
     
    $startTime = strtotime($start);
    $endTime = strtotime($finish);
    $aLstDays = [];
    
    $dw = listDaysSpanish(true);
    $dwMin = ['D','L','M','M','J','V','S'];
    $startAux = $startTime;
    while ($startAux<=$endTime){
      $aLstDays[date('j_m_y',$startAux)] = $dw[date('w',$startAux)];
      $aLstDaysMin[date('j_m_y',$startAux)] = $dwMin[date('w',$startAux)];
      $startAux = strtotime("+1 day", $startAux);
    }
    /************************************************************/
    /********   Get Roooms                      ****************/
    $allRooms = \App\Rooms::where('state',1)
            ->where('channel_group','!=','')->get();
    $otas = [];
    $roomsID = [];
    $totalOtas = 0;
    foreach ($allRooms as $room){
      if (isset($otas[$room->channel_group])){
        $otas[$room->channel_group]++;
      } else {
        $otas[$room->channel_group] = 1;
      }
      $totalOtas++;
      $roomsID[] = $room->id;
    }
    
    
    /************************************************************/
    $lstBySite = [];
    $startAux = $startTime;
    $aLstNightEmpty = [];
    while ($startAux <= $endTime) {
      $aLstNightEmpty[date('j_m_y', $startAux)] = 0;
      $startAux = strtotime("+1 day", $startAux);
    }
    $books  = BookDay::where_book_times($start,$finish)
            ->whereIn('type', Book::get_type_book_sales(true,true))
            ->get();
    $aLstNight = $aLstNightEmpty;

    $tNigh = 0;
    $tPvp  = 0;

    $control = [];
    if ($books) {
      foreach ($books as $b) {
        $auxTime = date('j_m_y', strtotime($b->date));
        if (isset($aLstNight[$auxTime]))  $aLstNight[$auxTime]++;
        $tPvp  += $b->pvp;
      }
    }

    $lstDisp = [
        'days'=>$aLstNight,
        'avail'=> Rooms::avail(),
        'tNigh'=>array_sum($aLstNight),
        'tPvp'=>$tPvp,
    ];
    /*****************************************************************/
    /********   Get Bookings                     ****************/  
    $listDaysOtas = [];
    $oBook = new \App\Book();
    foreach ($otas as $apto=>$v){
      $listDaysOtas[$apto] = $oBook->getAvailibilityBy_channel($apto, $start, $finish,false,false,true);
    }
    $listDaysOtasTotal = null;
    foreach ($listDaysOtas as $k=>$v){
      if (!$listDaysOtasTotal) $listDaysOtasTotal = $v;
      else {
        foreach ($v as $d=>$v2){
          $listDaysOtasTotal[$d]+= $v2;
        }
      }
    }
    /*********************************************************************/
    /**********   SUMMARY                                 ****************/
    $totalMonth = $totalMonthOcc = $totalOtas*count($aLstDays);
    $nightsTemp = calcNights($oYear->start_date,$oYear->end_date);
    $totalSummary = $totalOtas*$nightsTemp;
    $totalSummaryOcc = 0;
    $monthPVP = $summaryPVP = 0; 

    $sqlBooks = \App\Book::where_type_book_sales(true,true)->whereIn('room_id',$roomsID);
    $books = \App\Book::w_book_times($sqlBooks, $start, $finish)->get();
    if ($books){
      $startMonth = strtotime($start);
      $endMonth = strtotime($finish);
      $totalMonthOcc = 0;
      foreach ($books as $book){
        $summaryPVP += $book->total_price;
        if (date('y.m',strtotime($book->start)) == $month || date('y.m',strtotime($book->finish)) == $month){
          $pvpPerNigh = ($book->nigths>0) ? $book->total_price/$book->nigths : 0;
          $startAux = strtotime($book->start);
          $finishAux = strtotime($book->finish);
          while ($startAux<$finishAux){
            if (date('y.m',$startAux) == $month){
              $monthPVP += $pvpPerNigh;
              $totalMonthOcc++;
            }
            $startAux = strtotime("+1 day", $startAux);
          }
        }
      }
    }
    //Total by seasson
    $totalSummaryOcc = \App\Book::whereIn('type_book', [1,2,11])
          ->where([['start','>=', $oYear->start_date ],['finish','<=', $oYear->end_date ]])
          ->whereIn('room_id',$roomsID)->sum('nigths');

    $occupPerc = ($totalMonth>0) ? (round($totalMonthOcc/$totalMonth*100)) : 0;
    $medPVP = ($totalMonthOcc>0) ? ($monthPVP/$totalMonthOcc) : 0;
    $occupPercSession = ($totalSummary>0) ? (round($totalSummaryOcc/$totalSummary*100)) : 0;
    $medPVPSession = ($totalSummaryOcc>0) ? ($summaryPVP/$totalSummaryOcc) : 0;
    
    /************************************************************/
    /*******  BY SETTING SUMMARY    ****************************/
    $sKeys = [
        'pres_n_hab'=>0,'pres_n_hab_perc'=>0,'pres_med_pvp'=>0,'pres_pvp'=>0,
        'foresc_n_hab'=>0,'foresc_n_hab_perc'=>0,'foresc_med_pvp'=>0,'foresc_pvp'=>0
        ];
    
    $sSummarySeasson = \App\Settings::findOrCreate('revenue_disponibilidad_'.$oYear->year);
    $sSummaryMonth = \App\Settings::findOrCreate('revenue_disponibilidad_'.$oYear->year.'_'.$month);
    $sSummarySeasson = json_decode($sSummarySeasson->content,true);
    $sSummaryMonth = json_decode($sSummaryMonth->content,true);
    $sSummarySeasson = $sSummarySeasson ? array_merge($sKeys,$sSummarySeasson) : $sKeys;
    $sSummaryMonth = $sSummaryMonth ? array_merge($sKeys,$sSummaryMonth) : $sKeys;
    
    $sSummarySeasson['pres_n_hab_perc'] = ($totalSummary>0) ? (round($sSummarySeasson['pres_n_hab']/$totalSummary*100)) : 0;
    $sSummarySeasson['foresc_n_hab_perc'] = ($totalSummary>0) ? (round($sSummarySeasson['foresc_n_hab']/$totalSummary*100)) : 0;
    $sSummaryMonth['pres_n_hab_perc'] = ($totalMonth>0) ? (round($sSummaryMonth['pres_n_hab']/$totalMonth*100)) : 0;
    $sSummaryMonth['foresc_n_hab_perc'] = ($totalMonth>0) ? (round($sSummaryMonth['foresc_n_hab']/$totalMonth*100)) : 0;
        
    /************************************************************/
    $mmonths = getMonthsSpanish(null,true,true);
    unset($mmonths[0]);
    /************************************************************/
    
    $data = [
      'year' => $oYear,
      'otas' => $otas,
      'chNames' => $chNames,
      'aLstDays' => $aLstDays,
      'listDaysOtas' => $listDaysOtas,
      'totalOtas'=>$totalOtas,
      'listDaysOtasTotal'=>$listDaysOtasTotal,
      'lstMonths' => $lstMonths,
      'mmonths' => $mmonths,
      'month_key' => $oYear->year.'_'.$month,
      'month' => $month,
      'totalMonthOcc' => $totalMonthOcc,
      'totalSummaryOcc' => $totalSummaryOcc,
      'totalMonth' => $totalMonth,
      'totalSummary' => $totalSummary,
        'summaryPVP' => $summaryPVP,
        'monthPVP' => $monthPVP,
        'occupPerc' => $occupPerc,
        'occupPercSession' => $occupPercSession,
        'medPVP' => $medPVP,
        'medPVPSession' => $medPVPSession,
        'sSummarySeasson' => $sSummarySeasson,
        'sSummaryMonth' => $sSummaryMonth,
        'lstDisp'=>$lstDisp,
        'aLstDaysMin'=>$aLstDaysMin,
    ];
    if ($return)  return view('backend.revenue.dashboard.disponibilidad',$data );
    return view('backend.revenue.disponibilidad',$data );
  }
 
  /**
   * UPD disponibilidad resumen
   * @param Request $req
   */
  public function updDisponib(Request $req) {
    $key = $req->input('key',null);
    $id  = $req->input('id',null);
    $val = $req->input('input',0);
    $site = $req->input('site',1);
    
    if (!$key || !$id ){
      return response()->json(['status'=>'error','msg'=>'las claves no existen']);
    }
    
    $sSummary = \App\Settings::findOrCreate('revenue_disponibilidad_'.$key, $site);
    if (!$sSummary){
      return response()->json(['status'=>'error','msg'=>'Registro no encontrado']);
    }
    $aSummary = json_decode($sSummary->content,true);
    if (!$aSummary){
      $sKeys = [
        'pres_n_hab'=>0,'pres_n_hab_perc'=>0,'pres_med_pvp'=>0,'pres_pvp'=>0,
        'foresc_n_hab'=>0,'foresc_n_hab_perc'=>0,'foresc_med_pvp'=>0,'foresc_pvp'=>0
        ];
      if (isset($sKeys[$id])){
        $sKeys[$id] = $val;
        $sSummary->content = json_encode($sKeys);
        $sSummary->save();
        return response()->json(['status'=>'OK']);
      }
    }
    if (isset($aSummary[$id])){
      $aSummary[$id] = $val;
      $sSummary->content = json_encode($aSummary);
      $sSummary->save();
      return response()->json(['status'=>'OK']);
    }
    return response()->json(['status'=>'error','msg'=>'Registro no actualizado']);
    
  }
  /**
   * Generate data from INE analytic
   * @param Request $req
   * @return type
   */
  public function donwlDisponib(Request $req) {
    
    $oYear   = $this->getActiveYear();
    $lstMonths = getMonthsSpanish(null,FALSE, TRUE);
    $defaultMonth = ($oYear->year-2000).'.'.date('m');
    $month = $req->input('month',$defaultMonth);
    $d1 = new \DateTime('20'. str_replace('.', '-', $month).'-01');
    $d2 = clone $d1;
    $d1->modify('first day of this month');
    $d2->modify('last day of this month');
    $start = $d1->format("Y-m-d");
    $finish = $d2->format("Y-m-d");
      
    $chNames = configOtasAptosName();
    /************************************************************/
    /********   Prepare days array               ****************/
    $startAux = strtotime($start);
    $endAux = strtotime($finish);
    $aLstDays = [];
    
    $dw = listDaysSpanish(true);
    while ($startAux<=$endAux){
      $aLstDays[date('d',$startAux)] = $dw[date('w',$startAux)];
      $startAux = strtotime("+1 day", $startAux);
    }
      
    /************************************************************/
    /********   Get Roooms                      ****************/
    $qry_ch = \App\Rooms::where('state',1);
    $allRooms = $qry_ch->where('channel_group','!=','')->get();
    

    $otas = [];
    $roomsID = [];
    $totalOtas = 0;
    foreach ($allRooms as $room){
      if (isset($otas[$room->channel_group])){
        $otas[$room->channel_group]++;
      } else {
        $otas[$room->channel_group] = 1;
      }
      $totalOtas++;
    }
    
    
    /************************************************************/
    /********   Get Bookings                     ****************/  
    $listDaysOtas = [];
    $oBook = new \App\Book();
    foreach ($otas as $apto=>$v){
      $listDaysOtas[$apto] = $oBook->getAvailibilityBy_channel($apto, $start, $finish,false,false,true);
    }
    $listDaysOtasTotal = null;
    foreach ($listDaysOtas as $k=>$v){
      if (!$listDaysOtasTotal) $listDaysOtasTotal = $v;
      else {
        foreach ($v as $d=>$v2){
          $listDaysOtasTotal[$d]+= $v2;
        }
      }
    }
    /************************************************************/
    
   
    $rowTit = ['',''];
    $listMonth = [];
    foreach($aLstDays as $d=>$w) $rowTit[] = $d;
    
    
    foreach($otas as $ch=>$nro){
      $chName = isset($chNames[$ch]) ? $chNames[$ch] : '-';
      $aux = [$chName,'Total'];
      foreach($aLstDays as $d=>$w){
        $aux[] = $nro;
      }
      $listMonth[] = $aux;
      ////////////////////////////////////////////////
      $aux = ['','Libres'];
      foreach($listDaysOtas[$ch] as $avail){
        $aux[] = ($avail>0) ? $avail : '-';
      }
      $listMonth[] = $aux;
      ////////////////////////////////////////////////
      $aux = ['','Ocupadas'];
      foreach($listDaysOtas[$ch] as $avail){
        $aux[] = $nro-$avail;
      }
      $listMonth[] = $aux;
    }
    ////////////////////////////////////////////////
    $aux = ['TOTAL','Total'];
    foreach($aLstDays as $d=>$w){
      $aux[] = $totalOtas;
    }
    $listMonth[] = $aux;
    ////////////////////////////////////////////////
    $aux = ['','Libres'];
    foreach($listDaysOtasTotal as $v){
      $aux[] = $v;
    }
    $listMonth[] = $aux;
    ////////////////////////////////////////////////
    $aux = ['','Ocupadas'];
    foreach($listDaysOtasTotal as $v){
      $aux[] = $totalOtas-$v;
    }
    $listMonth[] = $aux;
    ////////////////////////////////////////////////  
    
    $name = 'PickUp_'. $start.'_al_'.$finish;
    \Excel::create($name, function($excel)  use($rowTit,$listMonth)  {
       $excel->sheet("Mensual", function($sheet) use($rowTit,$listMonth) {
            $sheet->freezeFirstColumn();
            $sheet->row(1, $rowTit);
            $index = 2;
            foreach($listMonth as $r) {
                $sheet->row($index, $r); 
                $index++;
            }
            
        });
         
        })->export('xlsx');
  }
  /***************************************************************************/
  /***************************************************************************/
  /***************************************************************************/
  public function rate_shopper() {
    
    $oRateChecker = new RateChecker();
    
    $Competitors = $oRateChecker->getCompetitorsData();
    $Competitors = $oRateChecker->getRateData($Competitors);
    
    /********************/
    $rooms = ['Double or Twin Room','Superior Double or Twin Room'];
    $byRoom = [];
    $competitorsID = [];
    foreach ($Competitors as $k=>$v){
      $competitorsID[$k] = $v['name'];
      if (is_array($v['snaphot']['lstRooms']))
        foreach ($v['snaphot']['lstRooms'] as $lstRooms){
          $rName = $lstRooms['name'];
          //filtra habitaciones
          if (!in_array($rName, $rooms))  continue;
          
          
          if (!isset($byRoom[$rName])) $byRoom[$rName] = [];
          if (!isset($byRoom[$rName][$k])) $byRoom[$rName][$k] = [];
          foreach ($lstRooms['prices'] as $kPrice=>$price){
            if (isset($byRoom[$rName][$k][$kPrice])){
              if (is_numeric($price))
                $byRoom[$rName][$k][$kPrice] = ($byRoom[$rName][$k][$kPrice]+$price)/2;
            } else {
              $byRoom[$rName][$k][$kPrice] = intval($price);
            }
          }
        }
    }
    /********************/
    $range = [];
    $oneDay = 24 * 60 * 60;
    $wDay = listDaysSpanish(true);
    for($i=0;$i<$oRateChecker->maxRange;$i++){
      $time = $oRateChecker->startRange+($oneDay*$i)+1;
      $range[date('Ymd',$time)] = $wDay[date('w',$time)].' '.date('d/m',$time);
    }
    $oYear = $this->getActiveYear();
    return view('backend.revenue.rate-shopper',[
      'year' => $oYear,
      'competitors' => $Competitors,
      'byRoom' => $byRoom,
      'competitorsID' => $competitorsID,
      'range' => $range,
    ]);
    
  }
  
  
  public function setRateCheckWubook() {
    
    $oRateChecker = new RateChecker();
//    $oRateChecker->setCompetitorsData();
    $oRateChecker->setRateData();
  }
  
  
  
  
  
  /****************************************************************************/
  
  
  public function pickUp(Request $req){
    
    $oYear   = $this->getActiveYear();
    $months = getMonthsSpanish(null,false,true);

    
    $ch  = $req->input('ch_sel',null);
    $start  = $req->input('start',null);
    $finish = $req->input('finish',null);
    $sel_mes = $req->input('sel_mes',date('m'));
    if ($sel_mes) {
      $mesAux = $oYear->year.'-'.$sel_mes.'-01';
      $start = $mesAux;
      $d = \DateTime::createFromFormat('Y-m-d',$mesAux);
      $finish = $d->modify('last day of this month')->format('Y-m-d');
    }
    $qry_ch = \App\Rooms::where('state',1);
    $allChannels = $qry_ch->where('channel_group','!=','')
            ->groupBy('channel_group')->pluck('channel_group')->all();
    
    $qryRevenue = RevenuePickUp::where([['day','>=', $start ],['day','<=', $finish ]]);
    
    if ($ch){
      $qryRevenue->where('channel',$ch);
    }
    
    $allRevenue = $qryRevenue->get();
    
    if (!$ch){
      if (count($allRevenue)){
        $ProcessPickUp = new \App\Models\ProcessPickUp();
        $allRevenue = $ProcessPickUp->compactRevenue($allRevenue);
      }
    }
       
    $summary = [];
    $tOcup = 0;
    $tDisp = 0;
    $tIng = 0;
        
    $qrySumm = RevenuePickUp::whereYear('day','=',$oYear->year);
    if ($ch) $qrySumm->where('channel',$ch);
    $allSummay = $qrySumm->get();
    $sM = [];
    if (count($allSummay))
      foreach ($allSummay as $r){
        $m = date('n', strtotime($r->day));
        if (!isset($sM[$m])) $sM[$m] = ['tOcup'=>0,'tDisp'=>0,'tIng'=>0];
        
        $sM[$m]['tOcup'] += $r->ocupacion+$r->llegada;
        $sM[$m]['tDisp'] += $r->disponibilidad;
        $sM[$m]['tIng']  += $r->ingresos;
        
        $tOcup += $r->ocupacion+$r->llegada;
        $tDisp += $r->disponibilidad;
        $tIng  += $r->ingresos;
      }
      
    /************************************************************************/  
     if (count($sM))
      foreach ($sM as $m=>$v){
        $sM[$m]['perc'] = round( $v['tOcup']*100/ $v['tDisp']);
        $sM[$m]['pm']   = ($v['tOcup']>0) ? moneda($v['tIng']/$v['tOcup']) : '-';
        $sM[$m]['tIng'] = moneda($v['tIng']);
        
      }  
    /************************************************************************/
      
      
      
    
    $PickUpEvents = \App\RevenuePickUpEvents::where([['date','>=', $start ],['date','<=', $finish ]])->get();
    $lstPickUpEvents = [];
    if ($PickUpEvents){
      foreach ($PickUpEvents as $item){
        $lstPickUpEvents[$item->date] = $item->event;
      }
    }
    
    
    $oLiquidacion = new \App\Liquidacion();
    if ($ch) $roomsID = \App\Rooms::where('channel_group',$ch)->pluck('id');
    else  $roomsID = \App\Rooms::where('channel_group','!=','')->pluck('id');
    
    $dataSeason = $oLiquidacion->getBookingAgencyDetailsBy_date($start,$finish,$roomsID);
    
    $agencyBooks = $oLiquidacion->getArrayAgency();
    
    return view('backend.revenue.pickUp',[
      'year' => $oYear,
      'sel_mes' => $sel_mes,
      'months' => $months,
      'allRevenue' => $allRevenue,
      'lstPickUpEvents' => $lstPickUpEvents,
      'tOcup' => $tOcup,
      'tDisp' => ($tDisp>0) ? $tDisp : 1,
      'tIng' => $tIng,
      'summMonth' => $sM,
      'start'=>$start,
      'finish'=>$finish,
      'channels' => $allChannels,
      'ch_sel' => $ch,
      'range'=>date('d M, y', strtotime($start)).' - '.date('d M, y', strtotime($finish)),
      'totalSeason' => $dataSeason['totals'],
      'dataSeason' => $dataSeason['data'],
      'agencyBooks' => $agencyBooks,
    ]);
  }
  
   /**
   * Generate data from INE analytic
   * @param Request $req
   * @return type
   */
  public function donwlPickUp(Request $req) {
    
    $oYear   = $this->getActiveYear();
    $start  = $oYear->start_date;
    $finish = $oYear->end_date;
   
    
//    $ch  = $req->input('ch_sel',null);
    $ProcessPickUp = new \App\Models\ProcessPickUp();
    $site_id  = $req->input('site',2);
    $start  = $req->input('start',null);
    $finish = $req->input('finish',null);
    
    if (!$start) $start  = $oYear->start_date;
    if (!$finish) $finish = $oYear->end_date;

    
    $qryRevenue = RevenuePickUp::where([['day','>=', $start ],['day','<=', $finish ]]);
    
    /******************************************************/
    $oRevenue = RevenuePickUp::where([['day','>=', $start ],['day','<=', $finish ]])
            ->get();
    $oRevenue = $ProcessPickUp->compactRevenue($oRevenue);
        
    /******************************************************/
    /******************************************************/
    
    $name = 'PickUp_'. $start.'_al_'.$finish;
    $exelData = [
        [$site_1,$oRevenue_1],
        [$site_2,$oRevenue_2],
        [$site_3,$oRevenue_3],
        [$site_5,$oRevenue_5],
            ];
    
    \Excel::create($name, function($excel)  use($exelData)  {
        foreach ($exelData as $item){
            
            $site = $item[0];
            $oRevenue = $item[1];
            $excel->sheet($site, function($sheet) use($oRevenue) {
                $sheet->freezeFirstColumn();
                $sheet->row(1, [
                    'Fecha','Llegadas','Ocupadas','Salidas','Hab. Vend.','Hab. Disp.','Ocup.','Hab. Canceladas.','Prod. Hab.','Prod. Pen.','Prod. Extra','Total',
                    'PUP','ADR','Revenue','ADR ACTUAL','ADR PREVIO' 

                ]);

                $index = 2;
                foreach($oRevenue as $r) {
                    $day = date('d/m/Y', strtotime($r->day));
                    $sheet->row($index, [
                        $day,$r->llegada,$r->ocupacion,$r->salida,$r->llegada+$r->ocupacion,
                        $r->disponibilidad,$r->get_ocup_percent(),$r->cancelaciones,
                        ($r->ingresos),'-',($r->extras),($r->ingresos+$r->extras),
                        $r->disponibilidad,$r->get_ADR(),($r->ingresos),$r->get_ADR(),''
                    ]); 
                    $index++;
                }

            });
            
        }
      
         
        })->export('xlsx');
  }
   /**
   * Generate data from INE analytic
   * @param Request $req
   * @return type
   */
  public function generatePickUp(Request $req) {
    
    $oYear   = $this->getActiveYear();
    $start  = $oYear->start_date;
    $finish = $oYear->end_date;
    $ProcessPickUp = new \App\Models\ProcessPickUp();
    $ProcessPickUp->byChannel($start,$finish);
    return back()->with('success','Registros generados');
  }
   
  
  function updPickUp(Request $req){
    
    $date = $req->input('date');
    $val = $req->input('val');

    $oObj = \App\RevenuePickUpEvents::where('date',$date)->first();
    if (!$oObj){
      $oObj = new \App\RevenuePickUpEvents();
      $oObj->date = $date;
    }
    
    $oObj->event = $val;
    $oObj->save();
    
    return 'OK';
  }
  /****************************************************************************/
  function daily(Request $req){
    
    $oYear   = $this->getActiveYear();
    $ch  = $req->input('ch_sel',null);
    $defaultMonth = ($oYear->year-2000).'.'.date('m');
    $sel_mes = $req->input('sel_mes',$defaultMonth);
    return view('backend.revenue.vtas-dia',
            $this->get_dailyData($oYear,$sel_mes,$ch));
  }
  
  function get_dailyData($oYear,$sel_mes,$ch){
    
    //-------------------------------------
    $lstMonths = [];
    $aux = strtotime($oYear->start_date);
    $auxEnd = strtotime($oYear->end_date);
    $months = getMonthsSpanish(null,false,true);
    while ($aux <= $auxEnd){
      $lstMonths[date('y.m',$aux)] = $months[date('n',$aux)];
      $aux = strtotime('+1 months' , $aux);
    }
    //-------------------------------------
      
      
    $qry_rooms = \App\Rooms::where('state',1);
    $query2 = clone $qry_rooms;
    
    $allChannels = $query2->where('channel_group','!=','')
            ->groupBy('channel_group')->pluck('channel_group')->all();
    
    /***********************************************************/
    $roomsID = null;
    if ($ch){
        $roomsID = \App\Rooms::where('channel_group',$ch)->pluck('id');
        $lstRooms = \App\Rooms::where('channel_group',$ch)->pluck('name','id');
    }
    if (!$roomsID) $lstRooms = \App\Rooms::pluck('name','id');
    /***********************************************************/
    $agency = \App\Book::listAgency();
    $oCountry = new \App\Countries();
    /***********************************************************/
    $aSelMes = explode('.', $sel_mes);
    $qBooks = \App\Book::where('type_book','!=',0)->where('type_book','!=',4)
            ->whereYear('created_at', '=', (2000+$aSelMes[0]))
            ->whereMonth('created_at', '=', $aSelMes[1]);
 
    if ($roomsID && count($roomsID)>0) $qBooks->whereIn('room_id',$roomsID);
    $oBooks = $qBooks->orderBy('created_at')->get();
    $lstResul = [];
    $lstResulID = [];
    if ($oBooks){
        foreach ($oBooks as $b){
            
            $time = strtotime($b->created_at);
            $day = date('Ymd',$time);
            if (isset($lstResulID[$day])) $lstResulID[$day]= [];
            $lstResulID[$day][] = $b->id;
            
            $n  = $b->nigths;
            $tp = $b->total_price;
            $adr = ($n>0) ? $b->total_price/$n : $b->total_price;
            switch ($b->type_book){
                case 1:
                case 2:
                case 7:
                case 8:
                    $status = 'Aceptada';
                    break;
                case 3:
                case 5:
                case 9:
                case 11:
                case 99:
                    $status = 'En espera';
                    break;
                case 4: $status = 'Bloqueado'; break;
                case 6: $status = 'Denegada'; break;
                case 10: $status = 'Overbooking'; break;
                case 98: $status = 'cancel-XML'; break;
                case 0: $status = 'ELIMINADA'; break;
                default:$status = ' - '; break;
            }
            
            if ($b->user_id == 98) $agency = 'WEBDIRECT';
            else $agency = isset($agency[$b->agency]) ? $agency[$b->agency] : 'Directa';
                
            $lstResul[$b->id] = [
                'create' => convertDateToShow_text(date('Y-m-d',$time),true),
                'name' => $b->customer->name,
                'in'=>convertDateToShow_text($b->start,true),
                'end'=>convertDateToShow_text($b->finish,true),
                'nigth'=> $n,
                'adr'=> round($adr),
                'price'=> round($tp),
                'status'=> $status,
                'ch'=> $agency,
                'country'=> $oCountry->getCountry($b->customer->country),
                
            ];
            
        }
    }
    
    /*************************************************************************/
    /****   SUMMARY YEAR                             *************************/
    $oYear   = $this->getActiveYear();
    $start_year  = $oYear->start_date;
    $finish_year = $oYear->end_date;
        
    
    $qBooks = \App\Book::where('type_book','!=',0)->where('type_book','!=',4)
            ->where([['created_at', '>=', $start_year], ['created_at', '<=', $finish_year]]);
    if ($roomsID && count($roomsID)>0) $qBooks->whereIn('room_id',$roomsID);
    $oBoks = $qBooks->orderBy('created_at')->get();
    
    $t_n = 0;
    $t_tp = 0;
    $aTotal = [];
    for($i=1;$i<13;$i++){
      $aTotal[$i] = ['n'=>0,'tp'=>0,'adr'=>0,];
    }
    if ($oBoks){
      foreach ($oBoks as $b){
        $month = date('n', strtotime($b->created_at));
        $n = $b->nigths;
        $tp = $b->total_price;
        $t_n += $n;
        $t_tp += $tp;
        $aTotal[$month]['n'] += $n;
        $aTotal[$month]['tp'] += $tp;
      }
    }
    
    foreach ($aTotal as $k=>$v){
      $aTotal[$k]['adr'] = ($v['n']>0) ? moneda($v['tp']/$v['n']) : '--';
      $aTotal[$k]['tp'] = moneda($v['tp']);
    }
    
    $t_adr = ($t_n>0) ? ($t_tp/$t_n) : 0;
    
    /****   SUMMARY YEAR                             *************************/
    /*************************************************************************/
        return [
            'year' => $oYear,
            'lstResul' => $lstResul,
            'sel_mes' => $sel_mes,
            'months' => $months,
            'lstMonths' => $lstMonths,
            'start'=>null,
            'finish'=>null,
            'channels' => $allChannels,
            'ch_sel' => $ch,
            'range'=>null,
            't_n'=>$t_n,
            't_tp'=>$t_tp,
            't_adr'=>$t_adr,
            'aTotal'=>$aTotal,
        ];
    }
    
    function donwlDaily(Request $req){
         
        $oYear   = $this->getActiveYear();
        $ch  = $req->input('ch_sel',null);
        $sel_mes  = $req->input('sel_mes',null);
        
    $data = $this->get_dailyData($oYear,$sel_mes,$ch); 
    $aSelMes = explode('.', $sel_mes);
    $name = 'Ventas-dia-del-mes-'. $aSelMes[1].'-'.(2000+$aSelMes[0]);
    if ($data['ch_sel']) $name.= '-'.$data['ch_sel'];
    
    $lstResul = $data['lstResul'];
    $rowTit = [
        'Creada','CLIENTE','Check In',
        'Check Out','Edificio','Nº NOCHES',
        'ADR','PVP RVA','ESTADO DE RESERVA',
        'CANAL','ORIGEN: Cliente'
    ];
    \Excel::create($name, function($excel)  use($rowTit,$lstResul)  {
       $excel->sheet("Mensual", function($sheet) use($rowTit,$lstResul) {
            $sheet->freezeFirstColumn();
            $sheet->row(1, $rowTit);
            $index = 2;
            foreach($lstResul as $r) {
                $sheet->row($index, $r); 
                $index++;
            }
            
        });
        })->export('xlsx');
    }

  
    function updFixedcosts(Request $req){
     
      $key = $req->input('key');
      $val = $req->input('val');
      if (!is_numeric($val)){
        return 'Valor no válido';
      }
      
      
      $aux = explode('_', $req->input('m'));
      if (count($aux) != 2) $aux = explode('.', $req->input('m'));
      
      $date = '20'.$aux[0].'-'.$aux[1].'-01';
              
      $oObject = \App\FixCosts::where('date',$date)
              ->where('concept',$key)
              ->first();
      if (!$oObject){
        $oObject = new \App\FixCosts();
        $oObject->date    = $date;
        $oObject->concept = $key;
      }
      $oObject->content = $val;
      $oObject->save();
      
      $totalSite =  \App\FixCosts::where('date',$date)
              ->sum('content');
      $totalYear =  0;
      return response()->json([
          'status'=>'OK',
          'totam_mensual'=> intVal($totalSite),
          'total_year'=> intVal($totalYear),
          ]);
    }
    
    function getComparativaAnual($year){
      $oYear = \App\Years::where('year', $year)->first();
      if (!$oYear) die('Temporada no existente');
      $oServ = new \App\Services\RevenueService();
      $oServ->setDates(null,$oYear);
      $oServ->setRooms();
      $oServ->setBook();
      $oServ->createDaysOfMonths($year);
      $aRatios = $oServ->getRatios($year);
      $auxADR = $aRatios[0];
      $ADR_semana = $auxADR['c_s'] > 0 ? $auxADR['t_s'] / $auxADR['c_s'] : $auxADR['t_s'];
      $ADR_finde  = $auxADR['c_f'] > 0 ? $auxADR['t_f'] / $auxADR['c_f'] : $auxADR['t_f'];
      $oLiquidacion = new \App\Liquidacion();
          
     $viewRatios = [
        'books' => $oServ->books,
        'aRatios' => $aRatios,
        'roomCh' => $oServ->rChannel,
        'days' => $oServ->days,
        'lstMonths' => $oServ->lstMonths,
        'year' =>$oYear->year,
        'mDays' =>$oServ->mDays,
        'yDays' =>$oServ->mDays[0],
        'time_start' => strtotime($oYear->start_date),
        'time_end' =>strtotime($oYear->end_date),
        'rvas'=>$oServ->countBookingsSite(),
        'summary' => $oLiquidacion->summaryTemp(false,$oYear),
        'ADR_semana'=>moneda($ADR_semana),
        'ADR_finde'=>moneda($ADR_finde),
    ];
      /*************************************************************/
      // COMPARATIVA INGRS ANUALES
      $viewRatios['comparativaAnual'] = $oServ->comparativaAnual($year);
      return view('backend.revenue.dashboard.comp_ingresos_anuales',$viewRatios);
    }
    
    
    function getFixedcostsAnual($year){
      $oYear = \App\Years::where('year', $year)->first();
      if (!$oYear) die('Temporada no existente');
      
      $oServ = new \App\Services\RevenueService();
      $oServ->setDates(null,$oYear);
      $oFixCosts = \App\FixCosts::getByRang($oYear->start_date,$oYear->end_date);
      $oFCItems = \App\FixCosts::getLst();
      $fixCosts  = [];
      $fixCostsMonths  = [0=>0];
      $lstMonths = [];
      foreach ($oServ->lstMonths as $k=>$v){
        $aux = explode('.', $k);
        $lstMonths[$aux[0].'_'.$aux[1]] = $v .' '.$aux[0];
      }
      foreach ($lstMonths as $k=>$v)  $fixCostsMonths[$k] = 0;
      
      foreach ($oFixCosts as $fc){
        if (!isset($fixCosts[$fc->concept])){
          $fixCosts[$fc->concept] = $fixCostsMonths;
        }
        $date = date('y_m', strtotime($fc->date));
        $fixCosts[$fc->concept][$date] = intval($fc->content);
      }    
      foreach ($oFCItems as $k=>$v){
        if (isset($fixCosts[$k]))  $fixCosts[$k][0] = array_sum($fixCosts[$k]);
        else $fixCosts[$k] = $fixCostsMonths;
      }
    
      
      return view('backend.revenue.dashboard.presupuesto-modal',[
          'lstMonths' => $lstMonths,
          'year' =>$oYear->year,
          'days' => $oServ->days,
          'fixCosts' => $fixCosts,
          'FCItems' => $oFCItems
      ]);
    }
    
    function copyFixedcostsAnualTo($year){
      
      $oYearOld = \App\Years::where('year', $year-1)->first();
      if (!$oYearOld) return 'Temporada no existente';
      
       $oYear = \App\Years::where('year', $year)->first();
      if (!$oYear) return 'Temporada no existente';
     
      $oFixCosts = \App\FixCosts::getByRang($oYearOld->start_date,$oYearOld->end_date);
      if (count($oFixCosts)==0) return 'No hay datos cargados';
        
      \App\FixCosts::deleteByRang($oYear->start_date,$oYear->end_date);
       
      foreach ($oFixCosts as $item){
        
        $aux = explode('-', $item->date);
        $date = ($aux[0]+1).'-'.$aux[1].'-01';
        $oObject = \App\FixCosts::where('date',$date)
                ->where('concept',$item->concept)
                ->first();
        if (!$oObject){
          $oObject = new \App\FixCosts();
          $oObject->date    = $date;
          $oObject->concept = $item->concept;
        }
        $oObject->content = $item->content;
        $oObject->save();
      }
      
     
      return 'OK';
    }
    
    
  function balanceAnioNatural($year=null,$trim=null){
    if (!$year ) $year = date('Y');
    
    
    $monthEmpty = [
        '01'=>0,
        '02'=>0,
        '03'=>0,
        '04'=>0,
        '05'=>0,
        '06'=>0,
        '07'=>0,
        '08'=>0,
        '09'=>0,
        '10'=>0,
        '11'=>0,
        '12'=>0
    ];
   
    
    /************************************************/
    $sqlBooks = BookDay::where_type_book_sales();
    if ($trim){
      $trim = intval($trim);
      switch ($trim){
        case 1:
          $startTrim = $year.'-01-01';
          $endTrim = $year.'-04-01';
          $monthEmpty = ['01'=>0,'02'=>0,'03'=>0];
          break;
        case 2:
          $startTrim = $year.'-04-01';
          $endTrim = $year.'-07-01';
          $monthEmpty = ['04'=>0,'05'=>0,'06'=>0];
          break;
        case 3:
          $startTrim = $year.'-07-01';
          $endTrim = $year.'-10-01';
          $monthEmpty = ['07'=>0,'08'=>0,'09'=>0];
          break;
        case 4:
          $startTrim = $year.'-10-01';
          $endTrim = ($year+1).'-01-01';
          $monthEmpty = ['10'=>0, '11'=>0, '12'=>0];
          break;
      }
      $sqlBooks->where('date','<',$endTrim)->where('date','>=',$startTrim );
    } else {
      $sqlBooks->whereYear('date','=',$year);
    }
    $books = $sqlBooks->get();
     
    $monthBook = $monthProp = $result = $base = $iva = $tIva = $monthEmpty;

    if ($books)
    foreach ($books as $b){
      $monthBook[substr($b->date,5,2)] += $b->pvp;
    }

    /************************************************/
    
      
    $booksByYear = \App\Book::where('type_book', 2)
              ->whereYear('start', '=', $year)
              ->get();

//    $oPayments = \App\Expenses::getPaymentToPropYear($year);
    if ($booksByYear)
    foreach ($booksByYear as $p){
      if (isset($monthProp[substr($p->start,5,2)]))
        $monthProp[substr($p->start,5,2)] += $p->get_costProp();
    }
    
    $monthNames = getMonthsSpanish(null, true, true);
    
    /************************************************/
    
    foreach ($monthBook as $k=>$v){
      $result[$k] = $v-$monthProp[$k];
    }
    foreach ($result as $k=>$v){
      $base[$k] = $v/1.1;
    }
    foreach ($base as $k=>$v){
      $iva[$k] = $v*0.1;
    }
    foreach ($result as $k=>$v){
      $tIva[$k] = $base[$k]+$iva[$k];
    }
    $aux = [];
    foreach ($monthNames as $k=>$v){
      if ($k<10 && isset($monthEmpty['0'.$k])) $aux[$k] = $v;
      if ($k>9 && isset($monthEmpty[$k])) $aux[$k] = $v;
    }
    $monthNames =  $aux;
    
    
    return view('backend.revenue.dashboard.anioNatural',[
          'monthBook' => $monthBook,
          'year' =>$year,
          'trim' =>$trim,
          'monthProp' => $monthProp,
          'result' => $result,
          'monthNames' => $monthNames,
          'base' => $base,
          'tIva' => $tIva,
          'iva' => $iva,
      ]);
    
     dd($monthBook,$monthProp);
  }
}
