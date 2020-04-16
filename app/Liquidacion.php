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
    public function summaryTemp() {
      return $this->get_summary(Book::getBy_temporada(),true);
    }

    /**
     * 
     * @param type $books
     * @return type
     */
    public function get_summary($books,$temporada=false) {
      $t_pax = $t_nights = $t_pvp = $t_cost = $vta_agency = 0;
      $t_books = count($books);
      
      $cost_prop = $cost_limp = $extraCost = 0;
      $PVPAgencia = $t_paymentTPV = 0;
      $totals = ['park'=>0,'apto'=>0,'lujo'=>0,'agency'=>0,'limp'=>0,'extra'=>0,'TPV'=>0];
      if ($t_books){
        foreach ($books as $book){
          /* Nº inquilinos */
          $t_pax += $book->pax;
          /* Dias ocupados */
          $t_nights += $book->nigths;
          
          $t_pvp  += $book->total_price;
          $totals['apto']   += $book->cost_apto;
          $totals['park']   += $book->cost_park;
          $totals['lujo']   += $book->get_costLujo();
          $totals['agency'] += $book->PVPAgencia;
          $totals['limp']   += $book->cost_limp;
          $totals['extra']  += $book->extraCost;
          $totals['TPV']    += paylandCost($book->getPayment(2));
          
          //CTE PROPIETARO = CTE APTO + CTE PARKING + CTE SUP LUJO
          $cost_prop  += $book->get_costProp();
          if ($book->agency != 0)  $vta_agency++;
        }
      }
       
      
        /***************************************/
       /*****    COSTOS                ********/
       // COSTE TOTAL= CTE PROPIETARIO + AGENCIAS(cta pyg) + 
       // AMENITIES (cta pyg)  + TPV (cta pyg) + LAVANDERIA (cta pyg) 
       // + LIMPIEZA (cta pyg) +REPARACION Y CONSERVACION (cta pyg)
      
      $expensesEstimate = $this->getExpensesEstimation($books);
      $expensesEstimate = $this->filterEstimates($expensesEstimate);
      $t_cost = $cost_prop;
      if ($temporada){
        $expensesPay = $this->getExpensesPayments();
        //Utilizo el mayor entre lo estimado y lo pagado
        $costes = [];
        foreach ($expensesPay as $type=>$val){
          if( $expensesEstimate[$type] > $val) $costes[$type] = $expensesEstimate[$type];
          else $costes[$type] =  $val;
        }
      } else {
        $costes = $expensesEstimate;
      }
       /*****    COSTOS                ********/
      /***************************************/
      
      
      $t_cost = round(array_sum($costes));
      $t_pvp = round($t_pvp);
      $benef = $benef_inc = 0;
      if($t_books>0){
        $benef = $t_pvp-$t_cost;
        $benef_inc = round(($benef)/$t_pvp*100);
      }
      $summary = [
          'total'=>$t_books,
          'total_pvp'=>$t_pvp,
          'cost_prop'=>$cost_prop,
          'costes'=>$costes,
          'prop_payment'=>$this->getTotalPaymentsProp(),
          'totals'=>$totals,
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
        $summary['vta_agency'] = round(($vta_agency / $t_books) * 100);
        $summary['vta_prop'] = 100-$summary['vta_agency'];    
      }
     
//      var_dump($summary);

      return $summary;
    }
    
    
    /**
     * 
     * @param type $startYear
     * @param type $endYear
     * @return type
     */
    public function getFF_Data($startYear,$endYear) {
    
    $allForfaits = Models\Forfaits\Forfaits::whereIn('status',[2,3])
            ->where('created_at', '>=', $startYear)->where('created_at', '<=', $endYear)->get();
      
    $totalPrice = $forfaits = $totalToPay = $totalToPay = $totalPayment = 0;
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
      $aExpensesPending['agencias']  += $book->PVPAgencia;
      $aExpensesPending['amenities'] += $book->extraCost;
      $aExpensesPending['limpieza']  += ($book->cost_limp - 10);
      $aExpensesPending['lavanderia'] += 10;
    }

    $stripeCost = $this->getTPV($books);
    $aExpensesPending['comision_tpv'] = array_sum($stripeCost);
 
      
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
                    ->orderBy('date', 'DESC')->get();
    
    foreach ($gastos as $g) {
      $aExpensesPayment[$g->type] += $g->import;
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
      $gastos = \App\Expenses::where('date', '>=',$startYear)
              ->where('date', '<=', $endYear)
              ->WhereNotNull('PayFor')   
              ->orderBy('date', 'DESC')->get();
      
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
  
}
