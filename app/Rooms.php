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

        $books = \App\Book::whereIn('type_book', [2,7,8])
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

        $books = \App\Book::whereIn('type_book', [2,7,8])
                            ->where('start', '>=', $startYear)
                            ->where('start', '<=', $endYear)
                            ->orderBy('start', 'ASC')
                            ->get();


        $total = 0;
        foreach ($books as $book) {
           $total  +=  $book->total_price;
        }

        return $total;

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
      
      $qry = \App\Book::type_book_sales()
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
    
}
