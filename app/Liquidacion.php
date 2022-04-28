<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Forfaits\ForfaitsOrderPayments;
use App\Expenses;

class Liquidacion
{	

    function getTPV($books) {
      $bIds = [];
      if($books){
        foreach ($books as $book){
          if ($book->stripeCost < 1){
            $bIds[] = $book->id;
          }
        }
      }
          
      $payments = \App\Payments::whereIn('type',[2,3])->whereIn('book_id',$bIds)
              ->groupBy('book_id')->selectRaw('sum(import) as sum, book_id')->pluck('sum','book_id');
      $stripeCost = [];
      if($books){
        foreach ($books as $book){
          $stripeCost[$book->id] = 0;
          if (isset($payments[$book->id])){
            $stripeCost[$book->id] = paylandCost($payments[$book->id]);
          }
        }
      }

      return $stripeCost;
    }
  
    /**
     * 
     * @return type
     */
    public function summaryTemp($qry=false,$oYear=null) {
        if ($qry){
          return $this->get_summary($qry->get(),true);
        }
      return $this->get_summary(BookDay::getBy_temporada($oYear),true);
    }

    /**
     * 
     * @param type $books
     * @return type
     */
    public function get_summary($lstRvs,$temporada=false) {
      $bLstID = [];
      $t_pax = $t_nights = $t_pvp = $t_cost = $vta_agency = $vta_prop = 0;
      foreach ($lstRvs as $key => $b) {
        $m = date('ym', strtotime($b->date));
        $t_pvp += $b->pvp;
        $t_nights++;
        if (!isset($bLstID[$b->book_id])){
          $bLstID[$b->book_id] = 1;
          
          
          
          $t_pax += $b->pax;
        }
        
        $vtaProp = false;
        if ($b->agency == 0 || $b->agency == 31){
          $vtaProp = true;
        } elseif ($b->type_book == 99 || $b->is_fastpayment){
          $vtaProp = true;
        }

        if ($vtaProp)  $vta_prop+=$b->pvp;
        else $vta_agency+=$b->pvp;
          
        $t_cost += $b->costs;
      }
      //------------------------------------
      /***************************************/
      /*****    COSTOS                ********/
       // COSTE TOTAL= CTE PROPIETARIO + AGENCIAS(cta pyg) + 
       // AMENITIES (cta pyg)  + TPV (cta pyg) + LAVANDERIA (cta pyg) 
       // + LIMPIEZA (cta pyg) +REPARACION Y CONSERVACION (cta pyg)
      $expensesEstimate = $this->getExpensesEstimation($lstRvs);
      $expensesEstimate = $this->filterEstimates($expensesEstimate);
      $t_cost = 0;
      if ($temporada){
        $expensesPay = $this->getExpensesPayments();
        //Utilizo el mayor entre lo estimado y lo pagado
        $costes = [];
        foreach ($expensesPay as $type=>$val){
          if( $expensesEstimate[$type] > $val)
            $costes[$type] = $expensesEstimate[$type];
          else 
            $costes[$type] =  $val;
        }
      } else {
        $costes = $expensesEstimate;
      }
       /*****    COSTOS                ********/
      /***************************************/
      $t_cost = array_sum($costes);
      $t_books = count($bLstID);
      $t_pvp = round($t_pvp);
      $benef = $benef_inc = 0;
      if($t_books>0){
        $benef = $t_pvp-$t_cost;
        $benef_inc = round(($benef)/$t_pvp*100);
      }
      
      $summary = [
          'total'=>$t_books,
          'total_pvp'=>$t_pvp,
          'total_cost'=>$t_cost,
          'benef'=>$benef,
          'benef_inc'=>$benef_inc,
          'pax'=>$t_pax,
          'nights'=>$t_nights,
          'nights-media' => ($t_nights>0) ? ceil($t_nights/$t_books) : 0,
          'vta_prop'=>0,
          'vta_agency'=>0,
          'daysTemp'=>\App\SeasonDays::first()->numDays,//$startYear->diffInDays($endYear)
        ];
      if($t_books>0){
        $summary['vta_agency'] = round(($vta_agency / $t_pvp) * 100);
        $summary['vta_prop'] = 100-$summary['vta_agency'];    
      }
     
      return $summary;
    }
    
    
    /**
     * 
     * @param type $startYear
     * @param type $endYear
     * @return type
     */
    public function getFF_Data($year) {
      $total = 0;
      $ffItems = [];
      $allForfaits =  Models\Forfaits\Forfaits::getAllOrdersSold(null,$year);
      if ($allForfaits && count($allForfaits)>0){
        $ffItems = Models\Forfaits\Forfaits::getTotalByTypeForfatis($allForfaits);
        $totalClassesMat = 0;
        if (isset($ffItems['clases'])) $totalClassesMat += $ffItems['clases'];
        if (isset($ffItems['equipos'])) $totalClassesMat += $ffItems['equipos'];
        return [
            'q'=>isset($ffItems['q']) ? $ffItems['q'] : 0,
            'to_pay'=> isset($ffItems['p']) ? $ffItems['p'] : 0,
            'to_pay_mat'=>0,
            'total'=>isset($ffItems['t']) ? $ffItems['t'] : 0,
            'pay'=>isset($ffItems['c']) ? $ffItems['c'] : 0,
            'totalFFExpress'=>isset($ffItems['forfaits']) ? $ffItems['forfaits'] : 0,
            'totalClassesMat'=>$totalClassesMat,
        ];
      }

      
      
       return [
            'q'=>0,
            'to_pay'=>  0,
            'to_pay_mat'=>0,
            'total'=> 0,
            'pay'=>0,
            'totalFFExpress'=>0,
            'totalClassesMat'=>0,
        ];
    
    
  }
  
  /**
   * 
   * @param type $books
   * @return type
   */
  function getExpensesEstimation($books){
    
//    AGENCIAS(cta pyg) + 
//        AMENITIES (cta pyg)  + TPV (cta pyg) + LAVANDERIA (cta pyg) 
//        + LIMPIEZA (cta pyg) +REPARACION Y CONSERVACION (cta pyg)
    
    $aExpensesPending = $aExpensesPayment = Expenses::getExpensesBooks();
    
    foreach ($books as $key => $book) {
      $aExpensesPending['prop_pay']  += $book->get_costProp();
      $aExpensesPending['agencias']  += $book->pvpAgenc;
      $aExpensesPending['amenities'] += $book->extr;
      $aExpensesPending['comision_tpv'] += $book->pvpComm;
      if ($book->limp > 10){
        $aExpensesPending['limpieza']  += ($book->limp - 10);
        $aExpensesPending['lavanderia'] += 10;
      } else {
        $aExpensesPending['lavanderia'] += $book->limp;
      }
    }
      
    return $aExpensesPending;
  }
  
  private function filterEstimates($aExpensesPending) {
       
    
    $oData = \App\ProcessedData::findOrCreate('PyG_Hide');
    if ($oData){
      $PyG_Hide = json_decode($oData->content,true);
      if ($PyG_Hide && is_array($PyG_Hide)){
        foreach ($PyG_Hide as $k){
          if(isset($aExpensesPending[$k])) $aExpensesPending[$k] = 0;
        }
      }
    }
    return $aExpensesPending;
  }
  
  /**
   * 
   * @return type
   */
  function getExpensesPayments(){
    
//    AGENCIAS(cta pyg) + 
//        AMENITIES (cta pyg)  + TPV (cta pyg) + LAVANDERIA (cta pyg) 
//        + LIMPIEZA (cta pyg) +REPARACION Y CONSERVACION (cta pyg)
    
    $aExpensesPayment = Expenses::getExpensesBooks();
    
    $activeYear = Years::getActive();
    $gastos = \App\Expenses::where('date', '>=', $activeYear->start_date)
                    ->Where('date', '<=', $activeYear->end_date)
                    ->WhereIn('type',array_keys($aExpensesPayment))
                    ->where(function ($query) {
                        $query->WhereNull('PayFor')->orWhere('PayFor', '=', '');
                    })
                    ->Where('type','!=','prop_pay')
                    ->orderBy('date', 'DESC')->get();
    
    foreach ($gastos as $g) {
      $aExpensesPayment[$g->type] += $g->import;
    }
    
    // Payment prop van con los gastos específicos
    $gastos = \App\Expenses::where('date', '>=', $activeYear->start_date)
                    ->Where('date', '<=', $activeYear->end_date)
                    ->WhereNotNull('PayFor')->Where('PayFor', '!=', '')
                    ->orderBy('date', 'DESC')->get();
    if ($gastos){
      foreach ($gastos as $g) {
        $aExpensesPayment['prop_pay'] += $g->import;
      }
    }
    return $aExpensesPayment;
  }

  /**
   * Get payment to prop and others expenses to the prop
   * @return type
   */
  function getTotalPaymentsProp(){
    $activeYear = Years::getActive();
    return Expenses::getTotalPaymentToProp($activeYear->start_date,$activeYear->end_date);
  }
  
  
  
  /**
   * Obtener la HOJA DE GASTOS para propietarios
   * 
   * @param type $year
   * @param type $room
   * @return type
   */
   static function getSalesByYearByRoomGeneral($room = "all") {

    $year = Years::getActive();
    $startYear = $year->start_date;
    $endYear = $year->end_date;

    $total = 0;
    $tarjeta = 0;
    $metalico = 0;
    $banco = 0;
    $pagado = 0;
    if ($room == "all") {
//      $rooms = \App\Rooms::where('state', 1)->get(['id']);
      $books = \App\Book::where_type_book_sales()
//              ->whereIn('room_id', $rooms)
              ->where('start', '>=',$startYear)
              ->where('start', '<=', $endYear)
              ->orderBy('start', 'ASC')->get();


      foreach ($books as $key => $book) {
        $total += $book->get_costProp();
      }
      $gastos = \App\Expenses::getPaymentToProp($startYear,$endYear);
      foreach ($gastos as $payment) {
        switch ($payment->typePayment){
          case 0:
            $tarjeta += $payment->import;
            break;
          case 1:
          case 2:
            $metalico += $payment->import;
            break;
          case 3:
            $banco += $payment->import;
            break;
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
        $total += $book->get_costProp();
      }

      $gastos = \App\Expenses::where('date', '>=',$startYear)
              ->where('date', '<=', $endYear)
              ->Where('PayFor', 'LIKE', '%' . $room . '%')
              ->orderBy('date', 'DESC')->get();

      foreach ($gastos as $payment) {
        
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
         
        $import = round($payment->import / $divisor,2);
        switch ($payment->typePayment){
          case 0:
            $tarjeta += $import;
            break;
          case 1:
          case 2:
            $metalico += $import;
            break;
          case 3:
            $banco += $import;
            break;
        }
        
        $pagado += $import;
      }
    }


    return [
        'total' => $total,
        'banco' => $banco,
        'metalico' => $metalico,
        'tarjeta' => $tarjeta,
        'pagado' => $pagado,
    ];
  }
  
  function getBookingAgencyDetailsBy_date($start,$end,$roomsID=null) {
        
      $dataNode = [
            'reservations'      => 0,
            'total'             => 0,
            'commissions'       => 0,
            'reservations_rate' => 0,
            'total_rate'        => 0
        ];
      $data = [
                'vd'   => $dataNode, // V. Directa
                'b'    => $dataNode, //Booking
                'ab'   => $dataNode, // AirBnb
                'ag'   => $dataNode, //Agoda
                'ex'   => $dataNode, //Expedia
                't'    => $dataNode, // Trivago
                'gh'   => $dataNode, //google-hotel
                'bs'   => $dataNode, // Bed&Snow
                'jd'   => $dataNode, // "Jaime Diaz",
                'se'   => $dataNode, // S.essence
                'c'    => $dataNode, //Cerogrados
                'h'    => $dataNode, //HOMEREZ
                'none' => $dataNode, // none
            ];
      $totals = ['total' => 0,'reservations' => 0,'commissions' => 0];
      $sqlBooks = \App\BookDay::where_type_book_sales(true,true)
            ->where('date', '>=', $start)
            ->where('date', '<=', $end);
      if ($roomsID && count($roomsID)>0) $sqlBooks->whereIn('room_id',$roomsID);
      $books = $sqlBooks->get();
      
      //-------------------------------
      $oPVPAgencia = \App\Book::whereIn('id',$sqlBooks->groupBy('book_id')->pluck('book_id'))
              ->get();
      $commAge = [];
      if ($oPVPAgencia){
        foreach ($oPVPAgencia as $b){
          $commAge[$b->id] = ($b->nigths>0) ? $b->PVPAgencia / $b->nigths : $b->PVPAgencia;
        }
      }
      //-------------------------------
      
      if ($books){
      foreach ($books as $book){
        $agency_name = 'none';
        switch ($book->agency){
           case 0: $agency_name = 'vd';break;
           case 1: $agency_name = 'b';  break;
           case 4: $agency_name = 'ab';  break;
           case 28: $agency_name = 'ex';  break;
           case 98: $agency_name = 'ag';  break;
           case 99: $agency_name = 'gh';  break;
           case 5: $agency_name = 'jd';  break;
           case 3: $agency_name = 'bs';  break;
           case 2: $agency_name = 't';  break;
           case 6: $agency_name = 'se';  break;
           case 7: $agency_name = 'c';  break;
           case 29: $agency_name = 'h';  break;
           case 31: $agency_name = 'vd';  break;
           case 999999: $agency_name = 'gh';  break; 
           default: $agency_name = 'none'; break;
        }
        if ($book->type_book == 99 || $book->is_fastpayment) // fastpayment
                  $agency_name = 'vd';
        
        $PVPAgencia = isset($commAge[$book->book_id]) ? $commAge[$book->book_id] : 0;
        $t = $book->pvp;
        $data[$agency_name]['total']        += $t;
        $data[$agency_name]['reservations'] += 1;
        $data[$agency_name]['commissions']  += $PVPAgencia;
        $totals['total']        += $t;
        $totals['reservations'] += 1;
        $totals['commissions']  += $PVPAgencia;
        }
        
        foreach ($data as $a=>$d){
          if ($d['reservations']>0 && $totals['reservations']>0)
            $data[$a]['reservations_rate'] = round($d['reservations']/$totals['reservations']*100);
          if ($d['total']>0 && $totals['total']>0)
            $data[$a]['total_rate'] = round($d['total']/$totals['total']*100);
        }

      }
      return  ['totals' => $totals,'data'=>$data];
    }
    
     function getArrayAgency(){
      return [
                'fp'   => 'FAST PAYMENT',
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
                'none' => 'Otras',
            ];
    }
}
