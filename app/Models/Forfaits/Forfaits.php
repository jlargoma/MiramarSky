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
      case 5:
        $result = [
            'name' => 'INTERESADOS',
            'icon' => asset('/img/miramarski/ski_icon_status_consulta.png')
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
      foreach ($orders as $o){
        if ($o->status == 2)
          $payment = 3;
      }
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
    $bIDs = null;
    $bIDsQry = \App\Book::where_type_book_sales(true,true);
    if ($month){
      $bIDsQry->whereYear('start','=', $year)
              ->whereMonth('start','=', $month);
    } else {
      $activeYear = \App\Years::where('year', $year)->first();
      if (!$activeYear) return 0;
      $startYear  = $activeYear->start_date;
      $endYear    = $activeYear->end_date;
      $bIDsQry->where('start', '>=', $startYear)->where('start', '<=', $endYear);
    }
    $bIDs = $bIDsQry->pluck('id')->toArray();
    if ($bIDs) $bIDs = array_unique($bIDs);
    return self::getAllOrdersSoldByBooks($bIDs);
  }
  
  static function getAllOrdersSoldByBooks($bIDs) {
    
    $qry = Forfaits::select('forfaits_orders.*','forfaits.book_id')
            ->join('forfaits_orders','forfaits.id','=','forfaits_orders.forfats_id');
    $qry->whereIn('forfaits.book_id',$bIDs);
    return $qry->whereIn('forfaits.status', [2,3]) // cobrada
            ->where('forfaits_orders.status', '!=',3) // no cancel
            ->get();
  }


  static function getTotalByYear($year){
  
    $total = 0;
    $ffItems = [];
    $allForfaits =  self::getAllOrdersSold(null,$year);
    if ($allForfaits && count($allForfaits)>0){
      $ffItems = self::getTotalByTypeForfatis($allForfaits);
      $total = $ffItems['t'];
    }
    
    return $total;
  }
  
  static function getTotalByForfatis($Forfaits){
    die('en construcción');
    $total = 0;
    $ffItems = [];
    if (count($Forfaits)){
      foreach ($Forfaits as $order){
        if ($order->quick_order){
          $total += $order->total;
        } else {
          $ffItems[] = $order->id;
        }
      }
    }
    if ($ffItems){
//      dd($ffItems);
      $total += ForfaitsOrderItem::whereIn('order_id',$ffItems)->where('type', 'forfaits')->WhereNull('cancel')->sum('total');
    }
    return $total;
  }
  static function getTotalByTypeForfatis($Forfaits){
    $totals = ['t'=>0,'p'=>0,'c'=>0,'q'=>0];
    $ffItems = [];
    if (count($Forfaits)){
      foreach ($Forfaits as $order){
        if (!isset($totals[$order->type])) $totals[$order->type] = 0;
        if ($order->quick_order){
          $totals['t'] += $order->total;
          $totals[$order->type] += $order->total;
          
          if ($order->status == 2) $totals['c'] += $order->total; //cobrado
            else $totals['p'] += $order->total; //pendiente
          
        } else {
          $ffItems[] = $order->id;
          $totals['t'] += $order->total;
          if ($order->status == 2) $totals['c'] += $order->total; //cobrado
            else $totals['p'] += $order->total; //pendiente
            
        }
      }
      $totals['q'] = count($Forfaits);
    }
    if ($ffItems){
      if (!isset($totals['forfaits'])) $totals['forfaits'] = 0;
      $aux = ForfaitsOrderItem::whereIn('order_id',$ffItems)->where('type', 'forfaits')->WhereNull('cancel')->sum('total');
      $totals['forfaits'] += $aux;
    }
    return $totals;
  }
}

//SELECT t1.id,t1.book_id,t1.status,t1.total,t2.total FROM `forfaits_orders` as t1 INNER JOIN forfaits_order_items as t2 ON t1.id=t2.order_id WHERE t1.total != t2.total AND t1.status != 3 AND t2.type = "forfaits" ORDER BY `t1`.`book_id` ASC
