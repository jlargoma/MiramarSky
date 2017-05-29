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
    protected $parking = 0;


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

    //Para poner nombre al parking de la reserva//
        static function getParking($parking)
        {
            $array = [1 =>"Si", 2 =>"Gratis",3 =>"50 %",4 =>"No"];

            return $parking = $array[$parking];
        }

    //Para comprobar el dia de la reserva en el calendario
        static function existDate($start,$finish,$room)
        {
        	if ($room != 3) {
                $books = \App\Book::where('id',$room)->get();
                $existStart = False;
                $existFinish = False;        
                $requestStart = Carbon::createFromFormat('Y-m-d',$start);
                $requestFinish = Carbon::createFromFormat('Y-m-d',$finish);
                foreach ($books as $book) {
                    if ($existStart == False && $existFinish == False) {
                        $start = Carbon::createFromFormat('Y-m-d', $book->start);
                        $finish = Carbon::createFromFormat('Y-m-d', $book->finish);                
                        $existStart = Carbon::create($requestStart->year,$requestStart->month,$requestStart->day)->between($start, $finish);
                        $existFinish = Carbon::create($requestFinish->year,$requestFinish->month,$requestFinish->day)->between($start, $finish);
                    }
                    else{
                        break;
                    }
                }
                if ($existStart == False && $existFinish == False) {
                    return True;
                }else{
                    return False;
                }
            }else{
                return true;
            }   
        }

    // Funcion para comprobar el precio de la reserva
        static function getPriceBook($start,$finish,$pax,$room)
        {
            $totalPrices = 0;

            for ($i=$start; $i < $finish; $i++) { 
                $seasonActive = \App\Seasons::getSeason($i);


                $prices = \App\Prices::where('season' ,  $seasonActive)
                                    ->where('occupation', $pax)->get();

                foreach ($prices as $key => $price) {
                    $totalPrices = $totalPrices + $price->price;
                }
                

                echo $totalPrices."<br>";

            } 
            
            die();       
            // return "llega";
        }

    // Funcion para cambiar la reserva de habitacion o estado
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

    // Funcion para buscar las reservas pasadas
        static public function oldBooks()
            {
                $date = Carbon::now();
                $books = \App\Book::where('start' ,'<' , $date)
                                    ->whereNotIn('type_book',[7,8])->get();

                return $books;
            }

    // Funcion para buscar las reservas Bloqueadas
       static public function bloqBooks()
        {
            $date = Carbon::now();
            $books = \App\Book::where('type_book', 7)
                                ->get();

            return $books;
        }

    // Funcion para buscar las reservas de subcomunidad
        static public function subBooks()
        {
            $date = Carbon::now();
            $books = \App\Book::where('type_book', 8)
                                ->get();

            return $books;
        }

    // Funcion para buscar las reservas proximas
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
