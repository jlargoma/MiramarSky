<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;
use DB;
class Book extends Model
{
	protected $table = 'book';
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $status = 0;


    public function customer()
    {
        return $this->hasOne('\App\Customers', 'id', 'customer_id');
    }

    public function room()
    {
        return $this->hasOne('\App\Rooms', 'id', 'room_id');
    }

    //Para poner nombre al estado de la reserva//
	static function getStatus($status)
    {
    	$array = [1 =>"Reservado", 2 =>"Pagada la señal",3 =>"SIN RESPONDER",4 =>"Denegado", 5 =>"Contestado(EMAIL)",6 =>"Cancelada", 7 =>"Bloqueado",8 =>"SubComunidad"];

    	return $status = $array[$status];
    }

    //Para comprobar el dia de la reserva en el calendario
    static function existDate($start,$finish)
    {
    	$fechas = \App\Seasons::all();
        $existStart = False;
        $existFinish = False;        
        $requestStart = Carbon::createFromFormat('d-m-Y',$start);
        $requestFinish = Carbon::createFromFormat('d-m-Y',$finish);
        foreach ($fechas as $fecha) {
            if ($existStart == False && $existFinish == False) {
                $start = Carbon::createFromFormat('Y-m-d', $fecha->start_date);
                $finish = Carbon::createFromFormat('Y-m-d', $fecha->finish_date);                
                $existStart = Carbon::create($requestStart->year,$requestStart->month,$requestStart->day)->between($start, $finish);
                $existFinish = Carbon::create($requestFinish->year,$requestFinish->month,$requestFinish->day)->between($start, $finish);
            }
        }
        if ($existStart == False && $existFinish == False) {
        	return False;
        }else{
        	return True;
        }
    }

    public function changeBook($status,$room)
    {
        if (!empty($status)) {
            $this->type_book = $status;
            if ($this->save()) {
                return "Cambiado!";
            }
        }
        if (!empty($room)) {
            $isStartReservable = 0;
            $isfinishReservable = 0;

            $books = \App\Book::where('room_id', $room)->get();

            $start = Carbon::createFromFormat('Y-m-d', $this->start);
            $finish = Carbon::createFromFormat('Y-m-d', $this->finish);
            $isStart = 0;
            $isFinish = 0;
            foreach ($books as $key => $book) {
                
                $bookStart = Carbon::createFromFormat('Y-m-d', $book->start);
                $bookFinish = Carbon::createFromFormat('Y-m-d', $book->finish);

               
               

                if (!$isStart && !$isFinish){
                    $isStartReservable = 1;
                    $isfinishReservable = 1;
                    break;
                }
 
            }


            return ['start' => $isStart, 'finish' => $isFinish, 'books' => $books];

            /*
            if ( $isStartReservable == 1 && $isfinishReservable == 1 ) {
                $this->room_id = $room;

               
                if ( $this->save() ) {
                    return true;
                }
            }else{
                return false;
            }
            */

        }
        
    }
}
