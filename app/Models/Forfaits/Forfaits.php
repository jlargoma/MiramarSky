<?php

namespace App\Models\Forfaits;

use Illuminate\Database\Eloquent\Model;
use App\Models\Forfaits\ForfaitsOrderItem;
use \Carbon\Carbon;

class Forfaits extends Model
{
  
  static function getByBook($bookID) {
    $order = self::where('book_id',$bookID)->first();
    if ($order){
      return $order;
    } else {
      $order = new Forfaits();
      $order->book_id = $bookID;
      $order->save();
      return $order;
    }
  }
  
  static function getByKey($key) {
    $order = null;
     if ($key){
      $aKey = explode('-',$key);
      $orderID = isset($aKey[0]) ? ($aKey[0]) : null;
      $control = isset($aKey[1]) ? ($aKey[1]) : null;
      $orderID = desencriptID($orderID);
      
      if ($orderID>0 && $control == getKeyControl($orderID)){
        $order = Forfaits::find($orderID);
      }
     }
     return $order;
  }
 
  public function recalculate() {
    $TotalItems = ForfaitsOrderItem::where('order_id',$this->id)
            ->WhereNull('cancel')->sum('total');
    
    if ($TotalItems){
      if ($this->total < $TotalItems){
        $this->status = 2;
        if ($this->book_id){
          \App\Book::where('id', $this->book_id)->update(['ff_status' => 2]);
        }
      }
      $this->total = $TotalItems;
    }
    else {
      $this->total = 0;
    }
    
    $this->save();
  }
  public function getInsurName($id) {
    $insur = array(
      5 => 'Seguro Básico de Esquí',
      6 => 'Seguro Completo de Esquí'
        );
    
    return isset($insur[$id]) ? $insur[$id] : '';
  }
  
  public function get_ff_status($showAll = true) {
    $result = [
        'name' => '',
        'icon' => null
    ];

    switch ($this->status) {
      case 0:
        if ($showAll) {
          $result = [
              'name' => 'No Gestionada',
              'icon' => asset('/img/miramarski/ski_icon_status_transparent.png')
          ];
        }
        break;
      case 1:
        $result = [
            'name' => 'Cancelada',
            'icon' => asset('/img/miramarski/ski_icon_status_grey.png')
        ];
        break;
      case 2:
        $result = [
            'name' => 'Pendiente',
            'icon' => asset('/img/miramarski/ski_icon_status_red.png')
        ];
        break;
      case 3:
        $result = [
            'name' => 'Cobrada',
            'icon' => asset('/img/miramarski/ski_icon_status_green.png')
        ];
        break;
      case 4:
        $result = [
            'name' => 'Comprometida',
            'icon' => asset('/img/miramarski/ski_icon_status_orange.png')
        ];
        break;
    }
    return $result;
  }
  
  function orders() {
    return $this->hasMany('App\Models\Forfaits\ForfaitsOrders','forfats_id','id');
  }
  
  function checkStatus(){
    $orders = self::orders()->get();
    $payment = $this->status;
    if ($orders && count($orders)>0){
//      $payment = 3;
      foreach ($orders as $o){
        if ($o->status == 1)
          $payment = 2;
        
      }
    }
    return $payment;
  }
  
  /**
   * Get the forfaits resume
   * @return type
   */
  function resume() {
    
    $orders = self::orders()->get();
    $response = [
      'forfaits'=>0,
      'clases' => 0,
      'equipos' => 0,
      'otros' => 0,
      'status_forfaits'=>null,
      'status_clases' => null,
      'status_equipos' => null,
      'status_otros' => null,
      'total'=>0
    ];
    if ($orders && count($orders)>0){
      
      foreach ($orders as $order){
        if ($order->status == 1 || $order->status == 2){
          if ($order->quick_order){
            $type = $order->type;
            if (!isset($response[$order->type])){
              $type = 'otros';
            }
              
            $response[$type] += $order->total;
            
            if ($response['status_'.$type] != 1){
              $response['status_'.$type] = $order->status;
            }
            
          } else {
            
            $type = 'forfaits';
            $response[$type] += $order->total;
            
            if ($response['status_'.$type] != 1){
              $response['status_'.$type] = $order->status;
            }
            
          }
          $response['total'] += $order->total;
        }
       
      }
    }
    
    $lst = ['forfaits','clases','equipos','otros'];

    $text = '<table class="table">';
    foreach ($lst as $item){
      if($response[$item]>0){
        $status = ($response['status_'.$item] ==2) ? 'Pagado' : 'Pendiente';
        $text.='<tr><td>'. strtoupper($item) 
                .'</td><td>'
                . $response[$item].'€ <span class="'.$status.'">'.$status.'</span>'
                . '</td></tr>';
      }
    }
    $text .= '<tr><td>Total</td><td>'
            . $response['total'].'€'
            . '</td></tr></table>';

    
    return $text;
  }
  
  static function getAllOrdersSold($month,$year) {
    $qry = Forfaits::select('forfaits_orders.*')
            ->join('forfaits_orders','forfaits.id','=','forfaits_orders.forfats_id');
    
    
    if ($month){
      $qry->whereYear('forfaits_orders.created_at','=', $year)
              ->whereMonth('forfaits_orders.created_at','=', $month);
    } else {
      $activeYear = \App\Years::where('year', $year)->first();
      if (!$activeYear) return 0;
      $startYear  = new Carbon($activeYear->start_date);
      $endYear    = new Carbon($activeYear->end_date);
      $qry->whereYear('forfaits_orders.created_at', '>=', $startYear)
              ->where('forfaits_orders.created_at', '<=', $endYear);
    }
      
    
    return $qry->whereIn('forfaits.status', [2,3]) // cobrada
            ->where('forfaits_orders.status', '!=',3) // no cancel
            ->get();
  }


  static function getTotalByYear($year){
  
    $total = 0;
    $ffItems = [];
    $allForfaits =  self::getAllOrdersSold(null,$year);
    if (count($allForfaits)){
      foreach ($allForfaits as $order){
        if ($order->quick_order){
          $total += $order->total;
        } else {
          $ffItems[] = $order->id;
        }
      }
    }
    
    if ($ffItems){
      $total += ForfaitsOrderItem::whereIn('order_id',$ffItems)->where('type', 'forfaits')->WhereNull('cancel')->sum('total');
    }
    return $total;
  }
}
