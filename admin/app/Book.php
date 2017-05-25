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
    	$array = [1 =>"Reservado", 2 =>"Pagada-la-señal",3 =>"SIN RESPONDER",4 =>"Denegado", 5 =>"Contestado(EMAIL)",6 =>"Cancelada", 7 =>"Bloqueado",8 =>"SubComunidad"];

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
            $isRooms = \App\Book::where('room_id',$room)->get();

                $existStart = false;
                $existFinish = false;        
                $roomStart = Carbon::createFromFormat('Y-m-d',$this->start);
                $roomFinish = Carbon::createFromFormat('Y-m-d',$this->finish);
                foreach ($isRooms as $isRoom) {
                    if ($existStart == false && $existFinish == false) {
                        $start = Carbon::createFromFormat('Y-m-d', $isRoom->start);
                        
                        $finish = Carbon::createFromFormat('Y-m-d', $isRoom->finish); 

                        $existStart = Carbon::create(
                                                        $roomStart->year,
                                                        $roomStart->month,
                                                        $roomStart->day)
                                                    ->between($start,$finish);

                        $existFinish = Carbon::create(
                                                        $roomFinish->year,
                                                        $roomFinish->month,
                                                        $roomFinish->day)
                                                    ->between($start,$finish);

                    }else{
                        break;
                    }
                    
                }
                if ($existStart == false && $existFinish == false) {
                    $this->room_id = $room;
                    if($this->save()){
                       return true; 
                   }else{
                    return false;
                   }
                    
                }else{
                    return false;
                }

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

    // Funcion para buscar las nuevas reservas
    static public function newBooks()
    {
        $date = Carbon::now();
        $books = \App\Book::where('start' ,'>' , $date)
                            ->whereNotIn('type_book',[2,7,8])
                            ->get();

        return $books;
    }

    static public function oldBooks()
    {
        $date = Carbon::now();
        $books = \App\Book::where('start' ,'<' , $date)
                            ->whereNotIn('type_book',[7,8])->get();

        return $books;
    }

    static public function bloqBooks()
    {
        $date = Carbon::now();
        $books = \App\Book::where('type_book', 7)
                            ->get();

        return $books;
    }

    static public function subBooks()
    {
        $date = Carbon::now();
        $books = \App\Book::where('type_book', 8)
                            ->get();

        return $books;
    }

    static public function proxBooks()
    {
        $date = Carbon::now();
        $books = \App\Book::where('start' ,'>' , $date)
                            ->where('type_book', 2)
                            ->whereNotIn('type_book',[7,8])
                            ->get();

        return $books;
    }
}
