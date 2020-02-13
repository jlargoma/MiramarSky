<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed nameRoom
 * @property mixed owned
 * @property mixed sizeApto
 * @property mixed typeApto
 * @property mixed minOcu
 * @property mixed maxOcu
 * @property mixed luxury
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed order
 * @property mixed state
 * @property mixed parking
 * @property mixed locker
 * @property mixed cost_cleaning
 * @property mixed price_cleaning
 * @property mixed cost_gift
 * @property mixed price_gift
 *
 * @property HasOne extra
 */
class Rooms extends Model
{
    const GIFT_COST = 5;
    const GIFT_PRICE = 0;
    
    const LUXURY_PRICE = 50;
    const LUXURY_COST = 40;

    const PARKING_PRICE = 20;
    const PARKING_COST = 13.5;

    const CLEANING_MAX_PRICE = 100;
    const CLEANING_MAX_COST = 70;

	public function book()
    {
        return $this->hasMany('\App\Book', 'id', 'room_id');
    }

    public function sizeRooms()
    {
        return $this->hasOne('\App\SizeRooms', 'id', 'sizeApto');
    }

    public function typeAptos()
    {
        return $this->type();
    }

    public function type()
    {
        return $this->hasOne('\App\TypeApto', 'id', 'typeApto');
    }

    public function user()
    {
        return $this->hasOne('\App\User', 'id', 'owned');
    }

    public function extra()
    {
        return $this->hasOne(Extras::class, 'apartment_size', 'sizeApto');
    }

    public function paymentPro()
    {
        return $this->hasMany('\App\Paymentspro', 'id', 'room_id');
    }

    public static function getPaxRooms($pax, $room)
    {
      $obj = self::select('minOcu')->where('id', $room)->first();
      if ($obj){
        return $obj->minOcu;
      } else {
        return 0;
      }
    }

    public function isAssingToBooking()
    {
        $isAssing = false;
        $books = \App\Book::where('room_id', $this->id)->where('type_book', 9)->get();

        if (count($books) > 0) {
            $isAssing = true;
        } 

        return $isAssing;
        
    }

    public function getCostPropByYear($year)
    {
	    $activeYear = \App\Years::where('year', $year)->first();
	    if (!$activeYear)
	        return 0;
	    $startYear  = new Carbon($activeYear->start_date);
	    $endYear    = new Carbon($activeYear->end_date);

//        $books = \App\Book::whereIn('type_book', [2,7,8])
        $books = \App\Book::where('type_book',2)
                            ->where('room_id', $this->id)
                            ->where('start', '>=', $startYear)
                            ->where('finish', '<=', $endYear)
                            ->orderBy('start', 'ASC')
                            ->get();


        $total = 0;
        $apto = 0;
        $park = 0;
        $lujo = 0;
        foreach ($books as $book) {
          $apto  +=  $book->cost_apto;
          $park  +=  $book->cost_park;
          $lujo  +=  $book->cost_lujo;
        }
        $total += ( $apto + $park + $lujo);

        return $total;


    }

    static function getPvpByYear($year)
    {
	    $activeYear = \App\Years::where('year', $year)->first();
	    if (!$activeYear)
		    return 0;
	    $startYear  = new Carbon($activeYear->start_date);
	    $endYear    = new Carbon($activeYear->end_date);

        $total = \App\Book::whereIn('type_book', [2,7,8])
                            ->where('start', '>=', $startYear)
                            ->where('start', '<=', $endYear)
                            ->orderBy('start', 'ASC')
                            ->sum('total_price');

//
//        $total = 0;
//        foreach ($books as $book) {
//           $total  +=  $book->total_price;
//        }

        return $total;

    }

    static function getCostPropByMonth($year,$room_id = NULL)
    {
      
      $existYear = true;
      $year = \App\Years::where('year', $year)->first();
      if (!$year){
        $existYear = false;
        $year = Years::where('active', 1)->first();
      }
      $startYear = new Carbon($year->start_date);
      $endYear = new Carbon($year->end_date);
      $diff = $startYear->diffInMonths($endYear) + 1;
      $lstMonths = lstMonths($startYear,$endYear);
      
      $arrayMonth = [];
      foreach ($lstMonths as $k=>$v){
        $arrayMonth[getMonthsSpanish($v['m'])] = 0;
      }
      
      if (!$existYear){
        return $arrayMonth;
      }
      
      $qry = \App\Book::where('type_book', 2)
            ->where('start', '>=', $startYear)
            ->where('start', '<=', $endYear);
      
      if($room_id){
        $qry->where('room_id',$room_id);
      }
      
      $books = $qry->get();
      $aux= [];
      //get PVP by month
      if ($books){
        foreach ($books as $key => $book) {
          $date = date('n', strtotime($book->start));
          if (!isset($aux[$date])) $aux[$date] = 0;
          $aux[$date] += ($book->cost_apto + $book->cost_park + $book->cost_lujo);
        }
      }
      
      //Load the PVP into Monts list
      foreach ($lstMonths as $k=>$v){
        $month = $v['m'];
        if (isset($aux[$month]))
          $arrayMonth[getMonthsSpanish($month)] = $aux[$month];
      }
      
      
      return $arrayMonth;
    }
    static function getPvpByMonth($year,$room_id = NULL)
    {
      
      $existYear = true;
      $year = \App\Years::where('year', $year)->first();
      if (!$year){
        $existYear = false;
        $year = Years::where('active', 1)->first();
      }
      $startYear = new Carbon($year->start_date);
      $endYear = new Carbon($year->end_date);
      $diff = $startYear->diffInMonths($endYear) + 1;
      $lstMonths = lstMonths($startYear,$endYear);
      
      $arrayMonth = [];
      foreach ($lstMonths as $k=>$v){
        $arrayMonth[getMonthsSpanish($v['m'])] = 0;
      }
      
      if (!$existYear){
        return $arrayMonth;
      }
      
      $qry = \App\Book::where_type_book_sales()
            ->where('start', '>=', $startYear)
            ->where('start', '<=', $endYear);
      
      if($room_id){
        $qry->where('room_id',$room_id);
      }
      
      $books = $qry->get();
      $aux= [];
      //get PVP by month
      if ($books){
        foreach ($books as $key => $book) {
          $date = date('n', strtotime($book->start));
          if (!isset($aux[$date])) $aux[$date] = 0;
          $aux[$date] += $book->total_price;
        }
      }
      
      //Load the PVP into Monts list
      foreach ($lstMonths as $k=>$v){
        $month = $v['m'];
        if (isset($aux[$month]))
          $arrayMonth[getMonthsSpanish($month)] = $aux[$month];
      }
      
      
      return $arrayMonth;
    }

    /**
     * @return float
     */
    public function getCostGiftAttribute()
    {
        return self::GIFT_COST;
    }

    /**
     * @return float
     */
    public function getPriceGiftAttribute()
    {
        return self::GIFT_PRICE;
    }
    
  /**
   * 
   * @param type $start
   * @param type $finish
   */
  public function getPVP($start,$finish,$pax) {
    $defaults = $this->defaultCostPrice($start,$finish,$pax);
    $priceDay = $defaults['priceDay'];
    $oPrice = \App\DailyPrices::where('channel_group',$this->channel_group)
                ->where('date','>=',$start)
                ->where('date','<=',$finish)
                ->get();
   
    
    if ($oPrice) {
        $extra_pax = 0;
        if (($pax>$this->minOcu)){
          $extra_pax =  $this->price_extra_pax*($pax-$this->minOcu);
        }
        foreach ($oPrice as $p) {
          $priceDay[$p->date] = $p->price+$extra_pax;
        }
      }
    $price = 0;
    foreach ($priceDay as $p) {
      $price +=$p;
    }
//    dd($price);
    return $price;
  }
  
  public function priceLimpieza($sizeApto) {
    
    if ($sizeApto == 1 || $sizeApto == 5){
      $oExtra = \App\Extras::find(2);
    }
    if ($sizeApto == 2 || $sizeApto == 6 || $sizeApto == 9){
      $oExtra = \App\Extras::find(1);
    }
    if ($sizeApto == 3 || $sizeApto == 4 || $sizeApto == 7 || $sizeApto == 8){
      $oExtra = \App\Extras::find(3);
    }
    
    if (env('APP_APPLICATION') != "riad"){
      if ($this->id == 165 || $this->id == 122){
        $oExtra = \App\Extras::find(6);
      }
    }
    
    if ($oExtra){
      return  [
          'price_limp'=>floatval($oExtra->price),
          'cost_limp'=>floatval($oExtra->cost)
          ];
    } 
    
    return  [
          'price_limp'=>0,
          'cost_limp'=>0
          ];
  }
   
  /**
   * Get the default cost and price to pax and seassons
   * 
   * @param type $start
   * @param type $end
   * @return type
   */
  public function defaultCostPrice($start,$end,$pax) {
    
    $response = ['priceDay'=>null,'costDay'=>null];
    if ($start && $end){
      $startTime = strtotime($start);
      $endTime = strtotime($end);
      $startDate = date('Y-m-d',$startTime);
      $endDate = date('Y-m-d',$endTime);

      $priceDay = [];
      $costDay = [];
      $seassonDay = [];
      $day = 24*60*60;
      while ($startTime<$endTime){
        $priceDay[date('Y-m-d',$startTime)] = 600;
        $costDay[date('Y-m-d',$startTime)] = 1;
        $seassonDay[date('Y-m-d',$startTime)] = 1;
        $startTime += $day;
      }


      /* BEGIN: default values by price */

      $match1 = [['start_date','>=', $startDate ],['start_date','<=', $endDate ]];
      $match2 = [['finish_date','>=', $startDate ],['finish_date','<=', $endDate ]];
      $match3 = [['start_date','<', $startDate ],['finish_date','>', $endDate ]];

      $seasonActives = \App\Seasons::where($match1)
                      ->orWhere($match2)
                      ->orWhere($match3)
                      ->orderBy('start_date')
                      ->get();
      
      $seasonsActive = [1];
      if ($seasonActives){
        foreach ($seasonActives as $s){
          $s_start  = strtotime($s->start_date);
          $s_end    = strtotime($s->finish_date);
          $s_type   = $s->type; 
          
          $seasonsActive[] = $s_type;
         
          while ($s_start<=$s_end && $s_start<=$endTime){
            $s_auxDate = date('Y-m-d',$s_start);
            if (isset($seassonDay[$s_auxDate]))  $seassonDay[$s_auxDate] = $s_type;
            $s_start += $day;
          }
        }
      }

      $extra_pax = 0;
      $pricePax = $this->minOcu;
      if (($pax>$pricePax)){
        $extra_pax =  $this->price_extra_pax*($pax-$pricePax);
      }
        
      $priceList = [];
      $prices = \App\Prices::whereIn('season', array_unique($seasonsActive))->where('occupation', $pricePax)->get();
      
      if ($prices){
        foreach ($prices as $p){
          $priceList[$p->season] = [
              "p" => $p->price+$extra_pax,
              "c" => $p->cost
              ];
        }
      }

      foreach ($seassonDay as $s_time=>$s_type){
        if (isset($priceList[$s_type])){
          $priceDay[$s_time] = $priceList[$s_type]['p'];
          $costDay[$s_time]  = $priceList[$s_type]['c'];
        }
      }

      $response = ['priceDay'=>$priceDay,'costDay'=>$costDay];
      /* END: default values by price */
    }
    return $response;
  }
  
  
   /**
   * 
   * @param type $start
   * @param type $finish
   */
  public function getMin_estancia($start,$finish) {
    
    $oPrice = \App\DailyPrices::where('channel_group',$this->channel_group)
                ->where('date','>=',$start)
                ->where('date','<=',$finish)
                ->get();
    $return = 0;
    if ($oPrice) {
        foreach ($oPrice as $p) {
          if ($p->min_estancia && $p->min_estancia>$return)
          $return = $p->min_estancia;
        }
      }
    return $return;
  }
  
}
