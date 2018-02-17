<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;
class Rooms extends Model
{

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
        return $this->hasOne('\App\TypeApto', 'id', 'typeApto');
    }

    public function user()
    {
        return $this->hasOne('\App\User', 'id', 'owned');
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


}
