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
use App\Services\Wubook\RateChecker;
use Excel;

class RevenueController extends AppController
{


  public function index(Request $req){
    
    $season   = $this->getActiveYear();
    $start_carb  = new Carbon($season->start_date);
    $finish_carb = new Carbon($season->end_date);
    $year   = $season->year;
    
    $month  = $req->input('month',null);
    $ch  = $req->input('ch_sel',null);
    $start  = $req->input('start',null);
    $finish = $req->input('finish',null);
    $lstMonhs = lstMonths($start_carb,$finish_carb,'y.m',true);
    if ($month){
      $aux = explode('.', $month);
      if (count($aux)==2){
        $start = '20'.$aux[0].'-'.date("m-d", mktime(0, 0, 0, $aux[1], 1));
        $finish = date("Y-m-d", strtotime($start. '+1 months'));
      }
    }
    
    if (!$start) $start  = $season->start_date;
    if (!$finish) $finish = $season->end_date;
    

    
    
    $qry_ch = Rooms::where('state',1);
    $allChannels = $qry_ch->where('channel_group','!=','')
            ->groupBy('channel_group')->pluck('channel_group')->all();
    
    
    $qryRevenue = RevenuePickUp::where([['day','>=', $start ],['day','<=', $finish ]]);
    $qryRevenueANUAL = RevenuePickUp::where([['day','>=', $season->start_date ],['day','<=', $season->end_date ]]);
    if ($ch){
      $qryRevenue->where('channel',$ch);
      $qryRevenueANUAL->where('channel',$ch);
    }
    
    $allRevenue = $qryRevenue->get();
    $allRevenueANUAL = $qryRevenueANUAL->get();
    
   
    if (!$ch){
      if (count($allRevenue)){
        $ProcessPickUp = new \App\Models\ProcessPickUp();
        $allRevenue = $ProcessPickUp->compactRevenue($allRevenue);
        $allRevenueANUAL = $ProcessPickUp->compactRevenue($allRevenueANUAL);
      }
    }
    
    $summary = [];
    $tOcup = 0;
    $tDisp = 0;
    $tIng = 0;
    if (count($allRevenue))
      foreach ($allRevenue as $r){
        $tOcup += $r->ocupacion+$r->llegada;
        $tDisp += $r->disponibilidad;
        $tIng  += $r->ingresos;
      }
    $tOcupANUAL = 0;
    $tDispANUAL = 0;
    $tIngANUAL = 0;
    if (count($allRevenueANUAL))
      foreach ($allRevenueANUAL as $r){
        $tOcupANUAL += $r->ocupacion+$r->llegada;
        $tDispANUAL += $r->disponibilidad;
        $tIngANUAL  += $r->ingresos;
      }
    
      
      
      
    $PickUpEvents = \App\RevenuePickUpEvents::where([['date','>=', $start ],['date','<=', $finish ]])->get();
    $lstPickUpEvents = [];
    if ($PickUpEvents){
      foreach ($PickUpEvents as $item){
        $lstPickUpEvents[$item->date] = $item->event;
      }
    }
    
    
    return view('backend.revenue.index',[
      'year' => $year,
      'month' => $month,
      'allRevenue' => $allRevenue,
      'lstPickUpEvents' => $lstPickUpEvents,
      'tOcup' => $tOcup,
      'tDisp' => ($tDisp>0) ? $tDisp : 1,
      'tIng' => $tIng,
      'tOcupANUAL' => $tOcupANUAL,
      'tDispANUAL' => ($tDispANUAL>0) ? $tDispANUAL : 1,
      'tIngANUAL' => $tIngANUAL,
      'site' => -1,
      'start'=>$start,
      'finish'=>$finish,
      'channels' => $allChannels,
      'ch_sel' => $ch,
      'lstMonhs' => $lstMonhs,
      'range'=>date('d M, y', strtotime($start)).' - '.date('d M, y', strtotime($finish)),
    ]);
    

  }
  
    
  /*******************************************************************************/
  /*******************************************************************************/
  /*******************************************************************************/
  
  public function disponibilidad(Request $req){
    
    $year   = $this->getActiveYear();
    $month_key = $req->input('month',date('y.m'));
    $aux = explode('.', $month_key);
    if (count($aux)==2){
      $month  =  $aux[1];
      $start  = firstDayMonth('20'.$aux[0], $month);
      $finish = lastDayMonth('20'.$aux[0], $month);
    } else {
        $month_key = date('y.m');
        $month = date('m');
        $start  = firstDayMonth(date('Y'), $month);
        $finish = lastDayMonth(date('Y'), $month);
    }
    $chNames = configOtasAptosName();
    $lstMonths = lstMonths(new Carbon($year->start_date), new Carbon($year->end_date),"y.m",'long');
    /************************************************************/
    /********   Prepare days array               ****************/
    $startTime = strtotime($start);
    $endTime = strtotime($finish);
    $aLstDays = [];
    $dw = listDaysSpanish(true);
    $dwMin = ['D','L','M','M','J','V','S'];
    $startAux = $startTime;
    while ($startAux<=$endTime){
      $aLstDays[date('d',$startAux)] = $dw[date('w',$startAux)];
      $aLstDaysMin[date('j',$startAux)] = $dwMin[date('w',$startAux)];
      $startAux = strtotime("+1 day", $startAux);
    }
      
    /************************************************************/
    /********   Get Roooms                      ****************/
    $allRooms = Rooms::where('state',1)->get();
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
    /********   Get RESUMENES                    ****************/ 
    $lstBySite = [];
    $oRooms = Rooms::where('state',1)->pluck('id')->toArray();
    $sqlbooks = Book::where_type_book_reserved(true)
            ->whereIn('room_id', $oRooms);
    $sqlbooks = Book::w_book_times($sqlbooks,$start,$finish);
    $books    = $sqlbooks->get();

    $avail = count($oRooms);
    $startAux = $startTime;
    $aLstNight = [];
    while ($startAux <= $endTime) {
      $aLstNight[date('j', $startAux)] = 0;
      $startAux = strtotime("+1 day", $startAux);
    }

    $tNigh = 0;
    $tPvp  = 0;

    $control = [];
    if ($books) {
      foreach ($books as $book) {
        $b_start = strtotime($book->start);
        $b_finish = strtotime($book->finish);

        while ($b_start < $b_finish) {
            if (date('m', $b_start) == $month) {
                $auxTime = date('j', $b_start);
                $keyControl = $book->room_id . '-' . $auxTime;
                if (!in_array($keyControl, $control) || true) {
                    if (isset($aLstNight[$auxTime]))
                        $aLstNight[$auxTime]++;
                    $control[] = $keyControl;
                }
            }
            $b_start = strtotime("+1 day", $b_start);      
        }

        $tPvp  += $book->total_price;
      }
    }

    $lstBySite = [
        'days'=>$aLstNight,
        'avail'=>$avail,
        'tNigh'=>array_sum($aLstNight),
        'tPvp'=>$tPvp,
    ];

    /*****************************************************************/
    /********   Get Bookings                     ****************/  
    $listDaysOtas = [];
    $oBook = new Book();
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
//    dd($listDaysOtasTotal);
    /*********************************************************************/
    /**********   SUMMARY                                 ****************/
    $totalMonth = $totalMonthOcc = $totalOtas*count($aLstDays);
    $nightsTemp = calcNights($year->start_date,$year->end_date);
    $totalSummary = $totalOtas*$nightsTemp;
    $totalSummaryOcc = 0;
    $monthPVP = $summaryPVP = 0; 

    $sqlBooks = Book::where_type_book_sales()->whereIn('room_id',$roomsID);
    $books = Book::w_book_times($sqlBooks, $start, $finish)->get();
                    
    if ($books){
      $startMonth = strtotime($start);
      $endMonth = strtotime($finish);
      $totalMonthOcc = 0;
      foreach ($books as $book){
        $summaryPVP += $book->total_price;
        if (date('m',strtotime($book->start)) == $month || date('m',strtotime($book->finish)) == $month){
          $pvpPerNigh = $book->total_price/$book->nigths;
          $startAux = strtotime($book->start);
          $finishAux = strtotime($book->finish);
          while ($startAux<$finishAux){
            if (date('m',$startAux) == $month){
              $monthPVP += $pvpPerNigh;
              $totalMonthOcc++;
            }
            $startAux = strtotime("+1 day", $startAux);
          }
        }
      }
    }
    //Total by seasson
    $totalSummaryOcc = Book::whereIn('type_book', [1,2,11])
          ->where([['start','>=', $year->start_date ],['finish','<=', $year->end_date ]])
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
    
    $sSummarySeasson = \App\Settings::findOrCreate('revenue_disponibilidad_'.$year->year);
    $sSummaryMonth = \App\Settings::findOrCreate('revenue_disponibilidad_'.$year->year.'_'.$month);
    $sSummarySeasson = json_decode($sSummarySeasson->content,true);
    $sSummaryMonth = json_decode($sSummaryMonth->content,true);
    $sSummarySeasson = $sSummarySeasson ? array_merge($sKeys,$sSummarySeasson) : $sKeys;
    $sSummaryMonth = $sSummaryMonth ? array_merge($sKeys,$sSummaryMonth) : $sKeys;
    
    $sSummarySeasson['pres_n_hab_perc'] = ($totalSummary>0) ? (round($sSummarySeasson['pres_n_hab']/$totalSummary*100)) : 0;
    $sSummarySeasson['foresc_n_hab_perc'] = ($totalSummary>0) ? (round($sSummarySeasson['foresc_n_hab']/$totalSummary*100)) : 0;
    $sSummaryMonth['pres_n_hab_perc'] = ($totalMonth>0) ? (round($sSummaryMonth['pres_n_hab']/$totalMonth*100)) : 0;
    $sSummaryMonth['foresc_n_hab_perc'] = ($totalMonth>0) ? (round($sSummaryMonth['foresc_n_hab']/$totalMonth*100)) : 0;
        
    /************************************************************/
    return view('backend.revenue.disponibilidad',[
      'year' => $year,
      'otas' => $otas,
      'chNames' => $chNames,
      'aLstDays' => $aLstDays,
      'listDaysOtas' => $listDaysOtas,
      'totalOtas'=>$totalOtas,
      'listDaysOtasTotal'=>$listDaysOtasTotal,
      'lstMonths' => $lstMonths,
      'month_key' => $month_key,
      'month' => intVal($month),
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
        'lstBySite'=>$lstBySite,
        'aLstDaysMin'=>$aLstDaysMin,
    ]);
  }
 
  /**
   * UPD disponibilidad resumen
   * @param Request $req
   */
  public function updDisponib(Request $req) {
    $key = $req->input('key',null);
    $id  = $req->input('id',null);
    $val = $req->input('input',0);
    $site = 1;
    
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
    
    $season   = $this->getActiveYear();
    $lstMonths = getMonthsSpanish(null,FALSE, TRUE);
    $month_key = $req->input('month_key',date('y.m'));

    $aux = explode('.', $month_key);
    if (count($aux)==2){
      $month  =  $aux[1];
      $start  = firstDayMonth('20'.$aux[0], $month);
      $finish = lastDayMonth('20'.$aux[0], $month);
    } else {
      $month  = date('m');
      $start  = firstDayMonth($season->year, $month);
      $finish = lastDayMonth($season->year, $month);
    }
    $chNames = configOtasAptosName();
    /************************************************************/
    /********   Prepare days array               ****************/
    $startAux = strtotime($start);
    $endAux = strtotime($finish);
    $aLstDays = [];
    $oneDay = 24*60*60;
    $dw = listDaysSpanish(true);
    while ($startAux<=$endAux){
      $aLstDays[date('d',$startAux)] = $dw[date('w',$startAux)];
      $startAux = strtotime("+1 day", $startAux);
    }
      
    /************************************************************/
    /********   Get Roooms                      ****************/
    $allRooms = Rooms::where('state',1)->get();
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
    $oBook = new Book();
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
    foreach($aLstDays as $d=>$w) $rowTit[] = $d.'';
    
    
    foreach($otas as $ch=>$nro){
      $chName = isset($chNames[$ch]) ? $chNames[$ch] : '-';
      $aux = [$chName,'Total'];
      foreach($aLstDays as $d=>$w){
        $aux[] = $nro.'';
      }
      $listMonth[] = $aux;
      ////////////////////////////////////////////////
      $aux = ['','Libres'];
      foreach($listDaysOtas[$ch] as $avail){
        $aux[] = ($avail>0) ? strval($avail) : '-';
      }
      $listMonth[] = $aux;
      ////////////////////////////////////////////////
      $aux = ['','Ocupadas'];
      foreach($listDaysOtas[$ch] as $avail){
        $aux[] = strval($nro-$avail);
      }
      $listMonth[] = $aux;
    }
    
    ////////////////////////////////////////////////
    $aux = ['TOTAL','Total'];
    foreach($aLstDays as $d=>$w){
      $aux[] = strval($totalOtas);
    }
    $listMonth[] = $aux;
    ////////////////////////////////////////////////
    $aux = ['','Libres'];
    foreach($listDaysOtasTotal as $v){
      $aux[] = strval($v);
    }
    $listMonth[] = $aux;
    ////////////////////////////////////////////////
    $aux = ['','Ocupadas'];
    foreach($listDaysOtasTotal as $v){
      $aux[] = strval($totalOtas-$v);
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
    $oneDay = 24*60*60;
    $wDay = listDaysSpanish(true);
    for($i=0;$i<$oRateChecker->maxRange;$i++){
      $time = $oRateChecker->startRange+($oneDay*$i)+1;
      $range[date('Ymd',$time)] = $wDay[date('w',$time)].' '.date('d/m',$time);
    }
    $year = $this->getActiveYear();
//    dd($byRoom);
    return view('backend.revenue.rate-shopper',[
      'year' => $year,
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
  
  
  public function pickUpNew(Request $req){
    
    $season   = $this->getActiveYear();
    $start  = $season->start_date;
    $finish = $season->end_date;
    $months = null;

    
    $ch  = $req->input('ch_sel',null);
    $start  = $req->input('start',null);
    $finish = $req->input('finish',null);
//    
    if (!$start) $start  = $season->start_date;
    if (!$finish) $finish = $season->end_date;
    
    
    $qry_ch = Rooms::where('state',1);
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
    if (count($allRevenue))
      foreach ($allRevenue as $r){
        $tOcup += $r->ocupacion+$r->llegada;
        $tDisp += $r->disponibilidad;
        $tIng  += $r->ingresos;
      }
    
      
      
      
    $PickUpEvents = \App\RevenuePickUpEvents::where([['date','>=', $start ],['date','<=', $finish ]])->get();
    $lstPickUpEvents = [];
    if ($PickUpEvents){
      foreach ($PickUpEvents as $item){
        $lstPickUpEvents[$item->date] = $item->event;
      }
    }
    
    $oLiquidacion = new \App\Liquidacion();
    if ($ch) $roomsID = Rooms::where('channel_group',$ch)->pluck('id');
    else  $roomsID = Rooms::where('state',1)->pluck('id');
    $dataSeason = $oLiquidacion->getBookingAgencyDetailsBy_date($start,$finish,$roomsID);
    
    $agencyBooks    = [
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
        ];
    
    
    return view('backend.revenue.pickUp',[
      'year' => $season,
      'months' => $months,
      'allRevenue' => $allRevenue,
      'lstPickUpEvents' => $lstPickUpEvents,
      'tOcup' => $tOcup,
      'tDisp' => ($tDisp>0) ? $tDisp : 1,
      'tIng' => $tIng,
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
    
    $season   = $this->getActiveYear();
    $start  = $season->start_date;
    $finish = $season->end_date;
   
    
//    $ch  = $req->input('ch_sel',null);
    $ProcessPickUp = new \App\Models\ProcessPickUp();
    $start  = $req->input('start',null);
    $finish = $req->input('finish',null);
    
    if (!$start) $start  = $season->start_date;
    if (!$finish) $finish = $season->end_date;

    
    $qryRevenue = RevenuePickUp::where([['day','>=', $start ],['day','<=', $finish ]]);
    
    /******************************************************/
    $oRevenue = RevenuePickUp::where([['day','>=', $start ],['day','<=', $finish ]])
            ->get();
    $oRevenue = $ProcessPickUp->compactRevenue($oRevenue);
        
    /******************************************************/
    /******************************************************/
    
    $name = 'PickUp_'. $start.'_al_'.$finish;
    \Excel::create($name, function($excel)  use($oRevenue)  {
       $excel->sheet('PickUp MiramarSki', function($sheet) use($oRevenue) {
            $sheet->freezeFirstColumn();
            $sheet->row(1, [
                'Fecha','Llegadas','Ocupadas','Salidas','Hab. Vend.',
                'Hab. Disp.','Ocup.','Hab. Canceladas.','Prod. Hab.',
                'Prod. Pen.','Prod. Extra','Total',
                'PUP','ADR','Revenue','ADR ACTUAL','ADR PREVIO' 

            ]);

            $index = 2;
            foreach($oRevenue as $r) {
              $day = date('m/d/Y', strtotime($r->day));
              $ADR = $r->get_ADR();
              if (!$ADR) $ADR = '';
           
              $fields= [
                  "ocupacion"=>'',
                  "llegada"=>'',
                  "salida"=>'',
                  "disponibilidad"=>'',
                  "ingresos"=>'',
                  "extras"=>'',
                  "cancelaciones"=>'',
                  "pvp"=>'',
                ];
    
              foreach ($fields as $k=>$v){
                $fields[$k] = $r->$k.'';
              }
              
              $vendida = ($r->llegada+$r->ocupacion);
              $ocup_percent = $r->get_ocup_percent();
              $total =  ($r->ingresos+$r->extras);
              
              $result = [
                    $day,
                    $fields['llegada'],
                    $fields['ocupacion'],
                    $fields['salida'],
                    $vendida.'',
                    $fields['disponibilidad'],
                    $ocup_percent.'',
                    $fields['cancelaciones'],
                    $fields['ingresos'],
                    '-',
                    $fields['extras'],
                    $total.'',
                    $fields['disponibilidad'],
                    $ADR.'',
                    $fields['ingresos'],
                    $ADR.'',''
                ]; 
                $sheet->row($index,$result);
                $index++;
            }
            
        });
        })->export('xlsx');
  }
   /**
   * Generate data from INE analytic
   * @param Request $req
   * @return type
   */
  public function generatePickUp(Request $req) {
    
    $season = $this->getActiveYear();
    $start  = $season->start_date;
    $finish = $season->end_date;
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
  /****************************************************************************/
  /****************************************************************************/
  
  /****************************************************************************/
  function daily(Request $req){
    
    $year   = $this->getActiveYear();
    $ch  = $req->input('ch_sel',null);
    $start  = $req->input('start',null);
    $finish = $req->input('finish',null);
    if (!$start) $start  = $year->start_date;
    if (!$finish) $finish = $year->end_date;
    return view('backend.revenue.vtas-dia',
            $this->get_dailyData($year,$start,$finish,$ch));
  }
  
  function get_dailyData($year,$start,$finish,$ch_gr){
    
    $qry_rooms = Rooms::where('state',1);
    $query2 = clone $qry_rooms;
    
    $allChannels = $query2->where('channel_group','!=','')
            ->groupBy('channel_group')->pluck('channel_group')->all();
    
    /***********************************************************/
    $roomsID = null;
    if ($ch_gr){
        $roomsID = Rooms::where('channel_group',$ch_gr)->pluck('id');
        $lstRooms = Rooms::where('channel_group',$ch_gr)->pluck('name','id');
    }
    if (!$roomsID) $lstRooms = Rooms::pluck('name','id');
    /***********************************************************/
    $agency = Book::listAgency();
    $oCountry = new \App\Countries();
    /***********************************************************/
    
    $oBooks = new Book();
    $qBooks = Book::where('type_book','!=',0)->where('type_book','!=',4)
            ->where([['created_at', '>=', $start], ['created_at', '<=', $finish]]);
    if ($roomsID) $qBooks->whereIn('room_id',$roomsID);
    $oBoks = $qBooks->orderBy('created_at')->get();
     
    $lstResul = [];
    $lstResulID = [];
    $t_n = 0;
    $t_tp = 0;
    $t_adr = 0;
    if ($oBoks){
        foreach ($oBoks as $b){
            
            $time = strtotime($b->created_at);
            $day = date('Ymd',$time);
            if (isset($lstResulID[$day])) $lstResulID[$day]= [];
            $lstResulID[$day][] = $b->id;
            
            $n  = $b->nigths;
            $tp = $b->total_price;
            $adr = ($n>0) ? $b->total_price/$n : $b->total_price;
            
            $t_n += $n;
            $t_tp += $tp;
            $t_adr += $adr;
            
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
            
            if ($b->user_id == 98) $ch = 'WEBDIRECT';
            else $ch = isset($agency[$b->agency]) ? $agency[$b->agency] : 'Directa';
                
            $lstResul[$b->id] = [
                'create' => convertDateToShow_text(date('Y-m-d',$time),true),
                'name' => $b->customer->name,
                'in'=>convertDateToShow_text($b->start,true),
                'end'=>convertDateToShow_text($b->finish,true),
                'nigth'=> $n,
                'adr'=> round($adr),
                'price'=> round($tp),
                'status'=> $status,
                'ch'=> $ch,
                'country'=> $oCountry->getCountry($b->customer->country),
                
            ];
            
        }
    }
        return [
            'year' => $year,
            'lstResul' => $lstResul,
            'start'=>$start,
            'finish'=>$finish,
            'channels' => $allChannels,
            'ch_sel' => $ch_gr,
            'range'=>date('d M, y', strtotime($start)).' - '.date('d M, y', strtotime($finish)),
            't_n'=>$t_n,
            't_tp'=>$t_tp,
            't_adr'=>$t_adr,
        ];
    }
    
    function donwlDaily(Request $req){
         
        $year   = $this->getActiveYear();
        $ch  = $req->input('ch_sel',null);
        $start  = $req->input('start',null);
        $finish = $req->input('finish',null);
        if (!$start) $start  = $year->start_date;
        if (!$finish) $finish = $year->end_date;
        
        
        
    $data = $this->get_dailyData($year,$start,$finish,$ch);   
    $name = 'Ventas-dia-'. $start.'-al-'.$finish;
    if ($data['ch_sel']) $name.= '-'.$data['ch_sel'];
    
    $lstResul = $data['lstResul'];
    $rowTit = [
        'Creada','CLIENTE','Check In',
        'Check Out','Nº NOCHES',
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

}
