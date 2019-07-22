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
        return self::select('minOcu')->where('id', $room)->first()->minOcu;
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
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES");
	    $arrayMonth = ['Enero' => 0, 'Febrero' => 0, 'Marzo' => 0, 'Abril' => 0, 'Mayo' => 0, 'Junio' => 0, 'Julio' => 0, 'Agosto' => 0, 'Septiembre' => 0, 'Octubre' => 0, 'Noviembre' => 0, 'Diciembre' => 0];
		$actualYear = \App\Years::where('year', $year)->first();
	    if (!$actualYear)
		    return $arrayMonth;

	    $startYear = new Carbon($actualYear->start_date);
	    $endYear   = new Carbon($actualYear->end_date);
		$diff = $startYear->diffInMonths($endYear) + 1;

        $dateInit = $startYear->copy();
        $arrayMonth = array();
        for ($i=1; $i <= $diff ; $i++) {
            $arrayMonth[$dateInit->copy()->formatLocalized('%B')] = 0;

            $dateInit->addMonth();
        }

        $dateInit = $startYear->copy();
       
        for ($i=1; $i <= $diff ; $i++) {
            
            if($room_id == NULL){
                $books = \App\Book::whereIn('type_book', [2])
                                ->where('start', '>', $dateInit->copy()->format('Y-m-d'))
                                ->where('start', '<=', $dateInit->copy()->addMonth()->format('Y-m-d'))
                                ->orderBy('start', 'ASC')
                                ->get();
            }else{
                $books = \App\Book::whereIn('type_book', [2])
                                ->where('start', '>', $dateInit->copy()->format('Y-m-d'))
                                ->where('start', '<=', $dateInit->copy()->addMonth()->format('Y-m-d'))
                                ->where('room_id',"=","$room_id")
                                ->orderBy('start', 'ASC')
                                ->get();
            }

            foreach ($books as $book) {
               $arrayMonth[$dateInit->copy()->formatLocalized('%B')]  +=  $book->total_price;
            }

            $dateInit->addMonth();
            
        }
        
         return $arrayMonth;
        

    }

    /**
     * This little trick because 4 in Extras is obsequio
     *
     * @return float
     */
    public function getCostCleaningAttribute()
    {
        return $this->sizeApto > 3
            ? self::CLEANING_MAX_COST
            : $this->extra->cost;
    }

    /**
     * This little trick because 4 in Extras is obsequio
     *
     * @return float
     */
    public function getPriceCleaningAttribute()
    {
        return $this->sizeApto > 3
            ? self::CLEANING_MAX_PRICE
            : $this->extra->price;
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
