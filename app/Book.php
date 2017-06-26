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
    protected $dayweek = 0;
    protected $parking = 0;
    protected $typePayment = 0;
    protected $banco = 0;
    protected $cobJorge = 0;
    protected $cobJaime = 0;
    protected $pendiente = 0;
    protected $agency = 0;

        public function customer()
            {
                return $this->hasOne('\App\Customers', 'id', 'customer_id');
            }

        public function room()
            {
                return $this->hasOne('\App\Rooms', 'id', 'room_id');
            }

        public function extrasBook()
            {
                return $this->hasMany('\App\ExtrasBooks', 'id', 'book_id');
            }

        public function pago()
            {
                return $this->hasMany('\App\Payments', 'book_id', 'id');
            }

    //Para poner nombre al dia del calendario//
       static function getDayWeek($dayweek)
            {
                $array = [1=> "L",2 =>"M", 3 =>"X",4 =>"J",5 =>"V", 6 =>"S",0 =>"D"];

                return $dayweek = $array[$dayweek];
            }

    //Para poner nombre al estado de la reserva//
	   static function getStatus($status)
            {
            	$array = [1 =>"Reservado", 2 =>"Pagada-la-señal",3 =>"SIN RESPONDER",4 =>"Denegado", 5 =>"Contestado(EMAIL)",6 =>"Cancelada", 7 =>"Bloqueado",8 =>"SubComunidad"];

            	return $status = $array[$status];
            }

    //Para poner nombre al tipo de cobro//
       static function getTypeCobro($typePayment)
            {
                $array = [0 =>"Metalico Jorge", 1 =>"Metalico Jaime",2 =>"Banco"];

                return $typePayment = $array[$typePayment];
            }

    //Para poner nombre al parking de la reserva//
        static function getParking($parking)
            {
                $array = [1 =>"Si", 2 =>"Gratis",3 =>"50 %",4 =>"No"];

                return $parking = $array[$parking];
            }
   
    //Para poner nombre a la agencia//
       static function getAgency($agency)
            {
                $array = [1 =>"Booking", 2 =>"Trivago"];

                return $agency = $array[$agency];
            }

    //Para comprobar el dia de la reserva en el calendario
        static function existDate($start,$finish,$room)
            {   
            	if ($room >= 5) {
                    
                    $books = \App\Book::where('room_id',$room)->whereIn('type_book',[1,2,7,8])->get();
                    $existStart = False;
                    $existFinish = False;        
                    $requestStart = Carbon::createFromFormat('d/m/Y',$start);
                    $requestFinish = Carbon::createFromFormat('d/m/Y',$finish);

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

                $start = Carbon::createFromFormat('d/m/Y' , $start);
                $finish = Carbon::createFromFormat('d/m/Y' , $finish);
                $countDays = $finish->diffInDays($start);


                $paxPerRoom = \App\Rooms::getPaxRooms($pax,$room);

                $pax = $pax;
                if ($paxPerRoom > $pax) {
                    $pax = $paxPerRoom;
                }

                $price = 0;

                for ($i=1; $i <= $countDays; $i++) { 

                    $seasonActive = \App\Seasons::getSeason($start->copy());
                    $prices = \App\Prices::where('season' ,  $seasonActive)
                                        ->where('occupation', $pax)->get();

                    foreach ($prices as $precio) {
                        $price = $price + $precio->price;
                    }
                }


                return $price; 
            }
    
    // Funcion para comprobar el precio de la reserva
        static function getCostBook($start,$finish,$pax,$room)
            {   
                
               $start = Carbon::createFromFormat('d/m/Y' , $start);
               $finish = Carbon::createFromFormat('d/m/Y' , $finish);
               $countDays = $finish->diffInDays($start);


               $paxPerRoom = \App\Rooms::getPaxRooms($pax,$room);

               if ($paxPerRoom > $pax) {
                   $pax = $paxPerRoom;
               }
               $cost = 0;
               for ($i=1; $i <= $countDays; $i++) { 

                   $seasonActive = \App\Seasons::getSeason($start->copy());
                   $costs = \App\Prices::where('season' ,  $seasonActive)
                                       ->where('occupation', $pax)->get();

                   foreach ($costs as $key => $precio) {
                       $cost = $cost + $precio->cost;
                   }
               }


               return $cost;  
            }

    //Funcion para el precio del Aparcamiento
        static function getPricePark($park,$noches)
            {
                $supPark = 0;
                switch ($park) {
                        case 1:
                            $supPark = 15 * $noches;
                            break;
                        case 2:
                            $supPark = 0;
                            break;
                        case 3:
                            $supPark = (15 * $noches) / 2;
                            break;
                        case 4:
                            $supPark = 0;
                            break;
                    }
                return $supPark;
            }
   
    //Funcion para el coste del Aparcamiento
        static function getCostPark($park,$noches)
            {
                $supPark = 0;
                switch ($park) {
                        case 1:
                            $supPark = 13.5 * $noches;
                            break;
                        case 2:
                            $supPark = 0;
                            break;
                        case 3:
                            $supPark = (13.5 * $noches) / 2;
                            break;
                        case 4:
                            $supPark = 0;
                            break;
                    }
                return $supPark;
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
                                                            ->between($start->copy()->addDay(),$finish->copy()->subDay());

                                $existFinish = Carbon::create(
                                                                $roomFinish->year,
                                                                $roomFinish->month,
                                                                $roomFinish->day)
                                                            ->between($start->copy()->addDay(),$finish->copy()->subDay());

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
        static public function paidBooks()
            {
                $date = Carbon::now();
                $books = \App\Book::where('start' ,'<' , $date)
                                    ->whereNotIn('type_book',[7,8])
                                    ->orderBy('start', 'desc')->get();

                return $books;
            }

    // Funcion para buscar las reservas especiales
       static public function specialBooks()
            {
                $date = Carbon::now();
                $books = \App\Book::whereIn('type_book', [7,8])
                                    ->get();

                return $books;
            }

    //Funcion para calcular el beneficio de Jorge
        static public function getBenJorge($ben,$id)
            {
                $room = \App\Rooms::find($id);
                $beneficio = 0;

                switch ($room->commission) {
                    case '0':
                        //Jorge
                        $beneficio = $ben;
                        break;
                    case '1':
                        //Propietario
                        $beneficio = $ben * 0.9;
                        break;
                    case '2':
                        //Riesgo
                        $beneficio = $ben * 0.65;
                        break;
                    case '3':
                        $beneficio = 0;
                        break;
                }
                return $beneficio;
            }
    
    //Funcion para calcular el beneficio de Jaime
        static public function getBenJaime($ben,$id)
            {
                $room = \App\Rooms::find($id);
                $beneficio = 0;

                switch ($room->commission) {
                    case '0':
                        //Jorge
                        $beneficio = $ben;
                        break;
                    case '1':
                        //Propietario
                        $beneficio = $ben * 0.9;
                        break;
                    case '2':
                        //Riesgo
                        $beneficio = $ben * 0.65;
                        break;
                    case '3':
                        $beneficio = 0;
                        break;
                }
                return $beneficio;
            }

    //Funcion para guardar los metodos de Pago
        public  function getPayment($tipo)
            {
                // $payments = \App\Payments::where('book_id',$id)->where('type',2)->get();

                // foreach ($payments as $payment) {
                    
                //     $this->banco += $payment->import;
                // }
                foreach ($this->pago as $pago) {
                    if ($pago->type == $tipo && $tipo == 2) {
                        $this->banco += $pago->import;
                    }else if ($pago->type == $tipo && $tipo == 1) {
                        $this->cobJorge += $pago->import;
                    }else if ($pago->type == $tipo && $tipo == 0) {
                        $this->cobJaime += $pago->import;
                    }
                    
                }
                if ($tipo == 2) {
                    return  $this->banco;
                }else if($tipo == 1) {
                    return  $this->cobJorge;
                }else if($tipo == 0) {
                    return  $this->cobJaime;
                }else if($tipo == 4){
                    return $this->banco + $this->cobJorge + $this->cobJaime;
                }
                
            }


}
