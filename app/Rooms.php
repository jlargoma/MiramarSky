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

    const PARKING_PRICE = 18;
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

    public static function getPaxRooms($pax,$room)
    {
        $room = \App\Rooms::where('id', $room)->first();
        
        return $room->minOcu;    
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

        $start = new Carbon('01-09-'. $year);
        $start = $start->format('n') >= 9 ? $start : $start->subYear();
        $inicio = $start->copy();

        $books = \App\Book::whereIn('type_book', [2])
                            ->where('room_id', $this->id)
                            ->where('start', '>', $inicio->copy()->format('Y-m-d'))
                            ->where('start', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
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

        $start = new Carbon('01-09-'. $year);
        $start = $start->format('n') >= 9 ? $start : $start->subYear();
        $inicio = $start->copy();

        $books = \App\Book::whereIn('type_book', [2])
                            ->where('start', '>', $inicio->copy()->format('Y-m-d'))
                            ->where('start', '<=', $inicio->copy()->addYear()->format('Y-m-d'))
                            ->orderBy('start', 'ASC')
                            ->get();


        $total = 0;
        foreach ($books as $book) {
           $total  +=  $book->total_price;
        }

        return $total;

    }

    static function getPvpByMonth($year)
    {
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES");

        $start = new Carbon('01-09-'. $year);
        $start = $start->format('n') >= 9 ? $start : $start->subYear();
        $inicio = $start->copy();

        
        $dateInit = $inicio->copy();
        $arrayMonth = array();
        for ($i=1; $i <= 12 ; $i++) { 
            $arrayMonth[$dateInit->copy()->formatLocalized('%B')] = 0;

            $dateInit->addMonth();
        }

        $dateInit = $inicio->copy();
       
        for ($i=1; $i <= 12 ; $i++) { 
            

            $books = \App\Book::whereIn('type_book', [2])
                                ->where('start', '>', $dateInit->copy()->format('Y-m-d'))
                                ->where('start', '<=', $dateInit->copy()->addMonth()->format('Y-m-d'))
                                ->orderBy('start', 'ASC')
                                ->get();

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
