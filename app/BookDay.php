<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookDay extends Model
{
  /*SELECT * FROM `book_days` where room_id in (SELECT id  FROM `rooms` WHERE `site_id` = 2) and type in (1,2,7,8)  and years(date) = 2020
ORDER BY `book_days`.`date` DESC*/
  
  /*SELECT month(date),count(*) FROM `book_days` where room_id in (SELECT id FROM `rooms` WHERE `site_id` = 2) and type in (1,2,7,8) and year(date) = 2020 GROUP BY month(date)
*/
  
  
  static function createSeasson($start,$end){
    $type_book = self::get_type_book_sales(true,true);
    $lst = Book::where_book_times($start,$end)
              ->whereIn('type_book',$type_book)->get();
    self::where('date','>=',$start)->where('date','<=',$end)->delete();
    $errors = [];
    $start = strtotime($start);
    $end = strtotime($end);
    $insert = [];
    foreach ($lst as $b){
      $b_start = strtotime($b->start);
      $b_finish = strtotime($b->finish);
      $nigth = 0; //control de noches
      $pvp = $b->total_price;
      $cost_apto = $b->cost_apto;
      $cost_park = $b->cost_park;
      $lujo = $b->get_costLujo();
      $PVPAgencia = $b->PVPAgencia;
      $cost_limp = $b->cost_limp;
      $extraCost = $b->extraCost;
      $pvpComm = paylandCost($b->getPayment(2));
      if($b->nigths>0){
        $pvp = $pvp / $b->nigths;
        $cost_apto = $cost_apto / $b->nigths;
      }
      $extrs = '';
      $tCosts = $b->get_costeTotal();
      if ($b_start == $b_finish){
        if ($b_start>=$start && $b_start<=$end)
          $insert[] = [
              'book_id'=>$b->id,
              'room_id'=>$b->room_id,
              'agency'=>$b->agency,
              'type'=>$b->type_book,
              'pax'=>$b->pax,
              'date'=>date('Y-m-d', $b_start),
              'pvp'=>$pvp,
              'extrs'=>$extrs,
              'costs'=>$tCosts,
              'type'=>$b->type_book,
              'apto'=>$cost_apto,
              'lujo'=>$lujo,
              'park'=>$cost_park,
              'pvpAgenc'=>$PVPAgencia,
              'limp'=>$cost_limp,
              'extr'=>$extraCost,
              'pvpComm'=>$pvpComm,
          ];
        continue;
      }
      while ($b_start < $b_finish) {
        if ($b_start>=$start && $b_start<=$end)
          $insert[] = [
              'book_id'=>$b->id,
              'room_id'=>$b->room_id,
              'agency'=>$b->agency,
              'type'=>$b->type_book,
              'pax'=>$b->pax,
              'date'=>date('Y-m-d', $b_start),
              'pvp'=>$pvp,
              'extrs'=>$extrs,
              'costs'=>$tCosts,
              'type'=>$b->type_book,
              'apto'=>$cost_apto,
              'lujo'=>$lujo,
              'park'=>$cost_park,
              'pvpAgenc'=>$PVPAgencia,
              'limp'=>$cost_limp,
              'extr'=>$extraCost,
              'pvpComm'=>$pvpComm,
          ];
        
        $b_start = strtotime('+1 day', $b_start);
        $nigth++;
        $tCosts = 0;
        $cost_park = 0;
        $lujo = 0;
        $PVPAgencia = 0;
        $cost_limp = 0;
        $extraCost = 0;
        $pvpComm = 0;
      }
      if ($nigth != $b->nigths){
//        $errors[$b->id] = $nigth.'!='. $b->nigths;
        $errors[] = $b->id;
      }

    }
    self::insert($insert);
    return $errors;
  }
  
   /**
   * Get object Book that has status 2,7,8
   * 
   * @return Object Query
   */
  static function where_type_book_sales($reservado_stripe=false,$ota=false) {
    $types = self::get_type_book_sales($reservado_stripe,$ota);
    return self::whereIn('type',$types);
  }
  
  static function get_type_book_sales($reservado_stripe=false,$ota=false) {
//    return [2,7];
     $types = [2, 7, 8];
    if ($reservado_stripe) $types[] = 1;
    if ($ota) $types[] = 11;
    //Pagada-la-señal / Reserva Propietario / ATIPICAS
    $types[] = 10; //Agrega OVERBOOKING
    return $types;
  }
  
    /**
   * Get object Book that has status 2,7,8
   * 
   * @return Object Query
   */
  static function where_book_times($startYear,$endYear) {
    
     return self::where('date','>=',$startYear)
             ->where('date','<=',$endYear);
  }
  
    
  static function getBy_temporada(){
    $activeYear = Years::getActive();
    return self::where_type_book_sales(true,true)
            ->where('date', '>=', $activeYear->start_date)
            ->where('date', '<=', $activeYear->end_date)->get();
  }
  
  
  function get_costProp(){
//    var_dump($this->book_id,$this->costs,$this->apto, $this->park,$this->lujo);
//    echo "\n";
    return $this->apto +  $this->park +  $this->lujo;
  }
}