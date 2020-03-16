<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;
use DB;
use Mail;
use App\Traits\BookEmailsStatus;
use App\BookPartee;

/**
 * Class Book
 * @property integer id
 * @property integer user_id
 * @property integer customer_id
 * @property integer room_id
 * @property Carbon  start
 * @property Carbon  finish
 * @property string  comment
 * @property string  book_comments
 * @property integer type_book
 * @property integer pax
 * @property integer nigths
 * @property integer agency
 * @property float   PVPAgencia
 * @property float   sup_limp
 * @property float   cost_limp
 * @property float   sup_park
 * @property integer type_park
 * @property float   cost_park
 * @property integer type_luxury
 * @property float   sup_lujo
 * @property float   cost_lujo
 * @property float   cost_apto
 * @property float   cost_total
 * @property float   total_price
 * @property float   total_ben
 * @property float   extraPrice
 * @property float   extraCost
 * @property float   extra
 * @property float   inc_percent
 * @property float   ben_jorge
 * @property float   ben_jaime
 * @property mixed   send
 * @property integer statusCobro
 * @property float   real_price
 * @property Carbon  created_at
 * @property Carbon  updated_at
 * @property integer schedule
 * @property integer scheduleOut
 * @property integer real_pax
 * @property string  book_owned_comments
 * @property float   promociones
 */
class Book extends Model {

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
  protected $agency_fix = 0;
  protected $typeBooks = [
      0 => 'ELIMINADA',
      1 => 'Reservado - stripe',
      2 => 'Pagada-la-señal',
      3 => 'SIN RESPONDER',
      4 => 'Bloqueado',
      5 => 'Contestado(EMAIL)',
      6 => 'Denegada',
      7 => 'Reserva Propietario',
      8 => 'ATIPICAS',
      //'SubComunidad',
      9 => 'Booking',
      10 => 'Overbooking',
      11 => 'blocked-ical',
      12 => 'ICAL - INVISIBLE',
      98 => 'cancel-XML',
      99 => 'FASTPAYMENT - SOLICITUD',
  ];
  
  var $typeBooksReserv = [
      1,// => 'Reservado - stripe',
      2,// => 'Pagada-la-señal',
      4,// => 'Bloqueado',
      7,// => 'Reserva Propietario',
      8,// => 'ATIPICAS',
      9,// => 'Booking',
      11,// => 'blocked-ical',
      99,// => 'blocked-ical',
  ];

  use BookEmailsStatus;

  public function customer() {
    return $this->hasOne('\App\Customers', 'id', 'customer_id');
  }

  public function room() {
    return $this->hasOne('\App\Rooms', 'id', 'room_id');
  }

  public function extrasBook() {
    return $this->hasMany('\App\ExtrasBooks', 'id', 'book_id');
  }

  public function pago() {
    return $this->hasMany('\App\Payments', 'book_id', 'id');
  }

  public function user() {
    return $this->hasOne('\App\User', 'id', 'user_id');
  }

  public function notifications() {
    return $this->hasMany('\App\BookNotification', 'book_id', 'id');
  }

  //Para poner nombre al dia del calendario//
  static function getDayWeek($dayweek) {
    $array = [
        1 => "L",
        2 => "M",
        3 => "X",
        4 => "J",
        5 => "V",
        6 => "S",
        0 => "D"
    ];

    return $dayweek = $array[$dayweek];
  }

  //Para poner nombre al estado de la reserva//
  public function getStatus($status) {
    return isset($this->typeBooks[$status]) ? $this->typeBooks[$status] : $status;
  }

  public function getTypeBooks() {
    return $this->typeBooks;
  }

  //Para poner nombre al tipo de cobro//
  static function getTypeCobro($typePayment=NULL) {
    $array = [
        0 => "CASH",//"Metalico Jorge",
        1 => "CASH",// "Metalico Jaime",
        2 => "TPV",//"Banco Jorge",
        3 => "TPV",//"Banco Jaime"
        4 => "REINTEGRO"//Devoluciones
    ];

    if (!is_null($typePayment)) return $typePayment = $array[$typePayment];
    
    return $array;
  }

  //Para poner nombre al parking de la reserva//
  static function getParking($parking) {
    $array = [
        1 => "Si",
        2 => "No",
        3 => "Gratis",
        4 => "50 %"
    ];

    return $parking = $array[$parking];
  }

  // Para poner nombre al suplemento de lujo en la reserva
  static function getSupLujo($lujo) {
    $array = [
        1 => "Si",
        2 => "No",
        3 => "Gratis",
        4 => "50 %"
    ];

    return $supLujo = $array[$lujo];
  }

   //Para poner nombre a la agencia//
  static function listAgency() {
    $array = [
        0 => "",
        1 => "Booking",
        2 => "Trivago",
        3 => "Bed&Snow",
        4 => "AirBnb",
        5 => "Jaime Diaz",
        6 => "S.essence",
        7 => "Cerogrados"
    ];
    
    for($i=1;$i<21;$i++){
      $array[] = 'Agencia-'.$i;
    }
    return $array;
  }
  
  //Para poner nombre a la agencia//
  static function getAgency($agency) {
    $array = self::listAgency();
    
    for($i=1;$i<21;$i++){
      $array[] = 'Agencia '.$i;
    }
//echo count($array); die;
    return isset($array[$agency]) ? $array[$agency] : 'Sin Nombre';
  }

  //Para comprobar el dia de la reserva en el calendario
  static function existDate($start, $finish, $room) {

    $books = \App\Book::where_type_book_reserved()
            ->where('room_id', $room)->get();
    $existStart = false;
    $existFinish = false;
    $requestStart = Carbon::createFromFormat('d/m/Y', $start);
    $requestFinish = Carbon::createFromFormat('d/m/Y', $finish);

    foreach ($books as $book) {
      if ($existStart == false && $existFinish == false) {

        $start = Carbon::createFromFormat('Y-m-d', $book->start);
        $finish = Carbon::createFromFormat('Y-m-d', $book->finish);

        if ($start < $requestStart && $requestStart < $finish) {
          $existStart = true;
        } elseif ($start <= $requestStart && $requestStart < $finish) {
          $existStart = true;
        } elseif ($requestStart <= $start && $start < $requestFinish) {
          $existStart = true;
        }
      } else {
        break;
      }
    }
    if ($existStart == false && $existFinish == false) {
      return true;
    } else {
      return false;
    }
  }
  
  //Para comprobar el dia de la reserva en el calendario
  static function availDate($startDate, $endDate, $room,$bookID=null) {

    
    
//    $match1 = [['start','<', $endDate ],[$endDate,'<','finish' ]];
//    $match2 = [[$startDate,'<','start'],['finish','<', $endDate]];
//    $match3 = [['start','<', $startDate ],[$endDate,'<', 'finish']];
//    $match4 = [['start','<', $startDate ],[$startDate,'<', 'finish' ]];
    
    $match1 = [['start','<', $endDate ],['finish','>',$endDate]];
    $match2 = [['start','>',$startDate],['finish','<', $endDate]];
    $match3 = [['start','<', $startDate ],['finish','>',$endDate]];
    $match4 = [['start','<=', $startDate ],['finish','>',$startDate]];
            
    $qry = self::where_type_book_reserved()->where('room_id',$room)
            ->where(function ($query) use ($match1,$match2,$match3,$match4) {
              $query->where($match1)
                      ->orWhere($match2)
                      ->orWhere($match3)
                      ->orWhere($match4);
            });
    if ($bookID && $bookID>0) {
      $qry->where('id','!=',$bookID);
    } 
     
    $booksCount = $qry->count();
    if ($booksCount>0) {
      return false;
    } else {
      return true;
    }
  }

  
  public function existDateOverrride($start, $finish, $room, $id_excluded) {

    

      if ($this->type_book == 3 || $this->type_book == 0 || $this->type_book == 6) {
        return true;
      } else {


        $requestStart = Carbon::createFromFormat('d/m/Y', $start);
        $requestFinish = Carbon::createFromFormat('d/m/Y', $finish);

        $books = \App\Book::where_type_book_reserved()->where('room_id', $room)
                ->where('id', '!=', $id_excluded)
                ->orderBy('start', 'DESC')
                ->get();

        $existStart = false;
        $existFinish = false;
        foreach ($books as $book) {
          if ($existStart == false && $existFinish == false) {
            $start = Carbon::createFromFormat('Y-m-d', $book->start);
            $finish = Carbon::createFromFormat('Y-m-d', $book->finish);

            if ($start <= $requestStart && $requestStart < $finish) {
              $existStart = true;
            } elseif ($requestStart <= $start && $start < $requestFinish) {
              $existStart = true;
            }
          } else {
            break;
          }
        }
        if ($existStart == false && $existFinish == false) {
          return true;
        } else {
          return false;
        }
      }
   
  }

  // Funcion para cambiar la reserva de habitacion o estado
  public function changeBook($status, $room, $book) {
    if (!empty($status)) {

      $response = ['status' => 'success', 'title' => 'OK', 'response' => ''];
      if ($this->type_book == 0){
        $response['status'] = "warning";
        $response['response'] = "La Reserva esta eliminada";
        return $response;
      }
      
      if ($status == 3 || $status == 10 || $status == 12 || $status == 6 || $status == 98) {
        $this->type_book = $status;
        $this->save();
        if ($status == 3)
          $response['response'] = "Estado Cambiado a Sin Responder";
        if ($status == 10)
          $response['response'] = "Reserva cambiada a Overbooking";
        if ($status == 12)
          $response['response'] = "Reserva cambiada a ICAL - INVISIBLE";
        if ($status == 98)
          $response['response'] = "Reserva cambiada a cancel-XML";
        if ($status == 6) {
          $this->sendEmailChangeStatus($book, 'Reserva denegada', $status);
          $response['response'] = "Reserva cambiada a ICAL - INVISIBLE";
        }
        
        \App\BookLogs::saveLogStatus($this->id, $this->room_id, $this->customer->email, $this->getStatus($status));
        return $response;
      } else {

        $dateStart = Carbon::createFromFormat('Y-m-d', $this->start);
        $dateFinish = Carbon::createFromFormat('Y-m-d', $this->finish);

        $roomStart = $dateStart->format('U');
        $roomFinish = $dateFinish->format('U');

        $isRooms = \App\Book::where_type_book_reserved()
                ->where('room_id', $this->room_id)
                ->where('id', '!=', $this->id)
                ->orderBy('start', 'DESC')
                ->get();

        $existStart = false;
        $existFinish = false;

        foreach ($isRooms as $isRoom) {
          if ($existStart == false) {

            $start = Carbon::createFromFormat('Y-m-d', $isRoom->start)->format('U');
            $finish = Carbon::createFromFormat('Y-m-d', $isRoom->finish)->format('U');

            if ($start < $roomStart && $roomStart < $finish) {
              $existStart = true;
            } elseif ($start <= $roomStart && $roomStart < $finish) {
              $existStart = true;
            } elseif ($roomStart <= $start && $start < $roomFinish) {
              $existStart = true;
            }
          } else {
            break;
          }
        }

        if ($existStart == false && $existFinish == false) {
          $this->type_book = $status;
          if ($status == 2) {
            $this->sendToPartee();
          }
          
          if ($this->customer->email == "") {
            $this->save();
            \App\BookLogs::saveLogStatus($this->id, $this->room_id, $this->customer->email, $this->getStatus($status));
            return [
                'status' => 'warning',
                'title' => 'Cuidado',
                'response' => "No tiene Email asignado"
            ];
          } else {

            switch ($status) {
              case '1':
                $this->sendEmailChangeStatus($book, 'Bloqueo de reserva y datos de pago', $status);
                break;
              case '2':
                $this->sendEmailChangeStatus($book, 'Confirmación de reserva (pago parcial)', $status);
                break;
              case '7':
                $this->sendEmailChangeStatus($book, 'Correo de Reserva de Propietario', $status);
                break;
            }
            if ($this->save()) {
              /* Creamos las notificaciones de booking */
              /* Comprobamos que la room de la reserva este cedida a booking.com */
              if ($this->room->isAssingToBooking()) {

                $isAssigned = \App\BookNotification::where('book_id', $book->id)->get();

                if (count($isAssigned) == 0) {
                  $notification = new \App\BookNotification();
                  $notification->book_id = $book->id;
                  $notification->save();
                }
              }
              $response['response'] = "Estado Cambiado";
              if ($status == 1)
                $response['response'] = "Email Enviado Reserva";
              if ($status == 2)
                $response['response'] = "Email Enviado Pagada la señal";
              if ($status == 4)
                $response['response'] = "Estado Cambiado a Bloqueado";
              if ($status == 5)
                $response['response'] = "Contestado por email";
              if ($status == 7)
                $response['response'] = "Estado Cambiado a Reserva Propietario";
              if ($status == 8)
                $response['response'] = "Estado Cambiado a Subcomunidad";

              \App\BookLogs::saveLogStatus($this->id, $this->room_id, $this->customer->email, $this->getStatus($status));
              return $response;
            }
          }
        } else {

          return [
              'status' => 'danger',
              'title' => 'Peligro',
              'response' => "No puedes cambiar el estado - Los aptos no están disponibles"
          ];
        }
      }
    }//if (!empty($status))

    if (!empty($room)) {
      $oRoom = Rooms::find($room);
      if ($oRoom){
        if (!$oRoom->state){
          return [
            'status' => 'danger',
            'title' => 'Peligro',
            'response' => "Este apartamento no está habilitado"
                ];
        }
      } else {
         return [
            'status' => 'danger',
            'title' => 'Peligro',
            'response' => "No se ha encontrado el apartamento seleccionado"
                ];
    }

      if ($this->type_book == 3) {
        $this->room_id = $room;
        if ($this->save()) {
          if ($this->room->isAssingToBooking()) {

            $isAssigned = \App\BookNotification::where('book_id', $book->id)->get();

            if (count($isAssigned) == 0) {
              $notification = new \App\BookNotification();
              $notification->book_id = $book->id;
              $notification->save();
            }
          } else {
            $deleted = \App\BookNotification::where('book_id', $book->id)->delete();
          }
          \App\BookLogs::saveLogStatus($this->id, $this->room_id, $this->customer->email, $this->getStatus($status));

          return [
              'status' => 'success',
              'title' => 'OK',
              'response' => "Apartamento cambiado correctamente"
          ];
        }
      } else {


        $dateStart = Carbon::createFromFormat('Y-m-d', $this->start);
        $dateFinish = Carbon::createFromFormat('Y-m-d', $this->finish);

        $roomStart = $dateStart->format('U');
        $roomFinish = $dateFinish->format('U');


        $isRooms = \App\Book::where_type_book_reserved()->where('room_id', $room)
                ->where('id', '!=', $this->id)
                ->orderBy('start', 'DESC')
                ->get();

        $existStart = False;
        $existFinish = False;

        foreach ($isRooms as $isRoom) {
          if ($existStart == False && $existFinish == False) {

            $start = Carbon::createFromFormat('Y-m-d', $isRoom->start)->format('U');
            $finish = Carbon::createFromFormat('Y-m-d', $isRoom->finish)->format('U');

            if ($start < $roomStart && $roomStart < $finish) {
              $existStart = true;
            } elseif ($start <= $roomStart && $roomStart < $finish) {
              $existStart = true;
            } elseif ($roomStart <= $start && $start < $roomFinish) {
              $existStart = true;
            }
          } else {
            break;
          }
        }
        if ($existStart == false && $existFinish == false) {
          $this->room_id = $room;
          if ($this->save()) {
            \App\BookLogs::saveLogStatus($this->id, $this->room_id, $this->customer->email, $this->getStatus($status));

            if ($this->room->isAssingToBooking()) {

              $isAssigned = \App\BookNotification::where('book_id', $book->id)->get();

              if (count($isAssigned) == 0) {
                $notification = new \App\BookNotification();
                $notification->book_id = $book->id;
                $notification->save();
              }
            } else {
              $deleted = \App\BookNotification::where('book_id', $book->id)->delete();
            }
            return [
                'status' => 'success',
                'title' => 'OK',
                'response' => "Apartamento cambiado correctamente"
            ];
          } else {
            return [
                'status' => 'danger',
                'title' => 'Peligro',
                'response' => "Error mientrar en el cambio de apartamento"
            ];
          }
        } else {
          return [
              'status' => 'danger',
              'title' => 'Peligro',
              'response' => "Este apartamento ya esta ocupado para estas fechas"
          ];
        }
      }
    }
  }

  //Funcion para calcular el beneficio de Jorge
  static public function getBenJorge($ben, $id) {
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
  static public function getBenJaime($ben, $id) {
    $room = \App\Rooms::find($id);
    $beneficio = 0;

    switch ($room->commission) {
      case '0':
        //Jorge
        $beneficio = 0;
        break;
      case '1':
        //Propietario
        $beneficio = $ben * 0.1;
        break;
      case '2':
        //Riesgo
        $beneficio = $ben * 0.35;
        break;
      case '3':
        $beneficio = 0;
        break;
    }
    return $beneficio;
  }

  public function getPayment($tipo) {
    return $this->payments->filter(function ($payment) use ($tipo) {
              return $payment->type == $tipo;
            })->sum('import');
  }

  public function getLastPayment() {
    $lastPayment = 0;
    if (count($this->payments) > 0) {
      foreach ($this->payments as $index => $payment) {
        $lastPayment = $payment->import;
      }
    }

    return $lastPayment;
  }

  public function SafetyBox() {
    return $this->hasOne(\App\BookSafetyBox::class)->first();
  }
  
  // Funcion para Sacar Ventas por temporada
  public function getVentas($year) {
    $ventas = [
        "Ventas" => [],
        "Ben" => [],
    ];

    $date = Carbon::CreateFromFormat('Y-m-d', $year);
    $books = \App\Book::where('type_book', 2)->where('start', '>=', $date->copy()->format('Y-m-d'))
                    ->where('start', '<=', $date->copy()->addYear()->format('Y-m-d'))->get();

    foreach ($books as $book) {
      $mes = Carbon::createFromFormat('Y-m-d', $book->start);
      $posicion = $mes->format('n');
      if ($posicion == 9 || $posicion == 10 || $posicion == 11) {
        $posicion = 12;
      } else if ($posicion == 5 || $posicion == 6) {
        $posicion = "04";
      }
      if (isset($ventas["Ventas"][$posicion])) {
        $ventas["Ventas"][$posicion] += $book->total_price;
        $ventas["Ben"][$posicion] += $book->total_ben;
      } else {
        $ventas["Ventas"][$posicion] = $book->total_price;
        $ventas["Ben"][$posicion] = $book->total_ben;
      }
    }
    if (isset($ventas["Ventas"][12])) {
      
    } else {
      $ventas["Ventas"][12] = "0";
      $ventas["Ben"][12] = "0";
    }
    if (isset($ventas["Ventas"][01])) {
      
    } else {
      $ventas["Ventas"][01] = "0";
      $ventas["Ben"][01] = "0";
    }
    if (isset($ventas["Ventas"][02])) {
      
    } else {
      $ventas["Ventas"][02] = "0";
      $ventas["Ben"][02] = "0";
    }
    if (isset($ventas["Ventas"][03])) {
      
    } else {
      $ventas["Ventas"][03] = "0";
      $ventas["Ben"][03] = "0";
    }
    if (isset($ventas["Ventas"][04])) {
      
    } else {
      $ventas["Ventas"][04] = "0";
      $ventas["Ben"][04] = "0";
    }

    return $ventas;
  }

  public static function getBeneficioJorge() {
    
  }

  public static function getBeneficioJaime() {
    
  }

  public function payments() {
    return $this->hasMany(Payments::class);
  }

  public function partee() {
    return $this->hasOne(BookPartee::class)->first();
  }

  public function getSumPaymentsAttribute() {
    return $this->payments->sum('import');
  }

  /**
   * Do not use this function without eager load
   *
   * @return int
   */
  public function getJorgeProfit() {
    return $this->profit * ($this->room->type->PercentJorge / 100);
  }

  /**
   * Do not use this function without eager load
   *
   * @return int
   */
  public function getJaimeProfit() {
    return $this->profit * ($this->room->type->PercentJaime / 100);
  }

  /**
   * @return mixed
   */
  public function getProfitAttribute() {
    return $this->total_price - $this->costs;
  }

  /**
   * @return mixed
   */
  public function getCostsAttribute() {
    return $this->cost_apto + $this->cost_park + $this->cost_lujo + $this->PVPAgencia + $this->cost_limp + $this->stripeCost + $this->extraCost;
  }

  /**
   * @return mixed
   */
  public function getStripeCostAttribute() {
    $totalStripe = $this->payments->filter(function ($payment) {
              return str_contains(strtolower($payment->comment), 'stripe');
            })->sum('import');


    return $totalStripe > 0 ? round(((1.4 * $totalStripe) / 100) + 0.25) : 0;
  }

  /**
   * @return float|int
   */
  public function getStripeCostRawAttribute() {
    $totalStripe = $this->payments->filter(function ($payment) {
              return str_contains(strtolower($payment->comment), 'stripe');
            })->sum('import');


    return $totalStripe > 0 ? ((1.4 * $totalStripe) / 100) + 0.25 : 0;
  }

  /**
   * @return int
   */
  public function getPendingAttribute() {
    return $this->total_price - $this->payments->sum('import');
  }

  /**
   * Trick applied here avoiding null results on DB
   *
   * @return mixed
   */
  public function getProfitPercentageRawAttribute() {
    if ($this->total_price == '0.00') {
      return 0;
    }

    return ($this->profit * 100) / $this->total_price;
  }

  /**
   * Trick applied here avoiding null results on DB
   *
   *
   * @return mixed
   */
  public function getProfitPercentageAttribute() {
    if ($this->total_price == '0.00') {
      return 0;
    }

    return round(($this->profit * 100) / $this->total_price);
  }

  public function hasSendPicture() {
    $sendPictures = DB::select("SELECT * FROM log_images WHERE book_id = '" . $this->id . "'");
    return (count($sendPictures) == 0) ? false : true;
  }

  public function getSendPicture() {
    $sendPictures = \App\LogImages::where('book_id', $this->id)->get();
    return (count($sendPictures) > 0) ? $sendPictures : false;
  }

  /**
   * Get the inc_percent from the book
   * 
   * @return int inc_percent
   */
  public function get_inc_percent() {
    $profit = $this->profit;
    $total_price = $this->total_price;
    $inc_percent = 0;

    if ($this->room->luxury == 0 && $this->cost_lujo > 0) {
      $profit = $this->profit - $this->cost_lujo;
      $total_price = $this->total_price - $this->sup_lujo;
    }

    if ($total_price != 0) {
      $inc_percent = ($profit / $total_price ) * 100;
    }

    return $inc_percent;
  }

  /**
   * Get the total cost
   * 
   * @return int $cost_total
   */
  public function get_costeTotal() {

    $cost_total = $this->cost_apto + $this->cost_park + $this->cost_lujo + $this->cost_limp + $this->PVPAgencia + $this->stripeCost + $this->extraCost;
    if ($this->room->luxury == 0 && $this->cost_lujo > 0) {
      $cost_total = $this->cost_total - $this->cost_lujo;
    }

    return $cost_total;
  }

  /**
   * Get object Book that has status 2,7,8
   * 
   * @return Object Query
   */
  static function where_type_book_sales() {
    
    //Pagada-la-señal / Reserva Propietario / ATIPICAS
                
    return self::whereIn('type_book', [2, 7, 8]);
  }
  /**
   * Get object Book that has status 2,7,8
   * 
   * @return Object Query
   */
  static function where_type_book_reserved() {
    return self::whereIn('type_book', [1,2,4,7,8,9,11]);
  }
  
  static function get_type_book_reserved() {
    return [1,2,4,7,8,9,11];
  }
  
  /**
   * Get object Book that has status 2,7,8
   * 
   * @return Object Query
   */
  static function where_book_times($startYear,$endYear) {
    
     return self::where(function ($query) use ($startYear,$endYear) {
       $query->where(function ($query2) use ($startYear,$endYear) {
          $query2->where('start', '>=', $startYear)->Where('start', '<=', $endYear);
        })->orWhere(function ($query2) use ($startYear,$endYear) {
          $query2->where('finish', '>=', $startYear)->Where('finish', '<=', $endYear);
        });
      });
  }
  


  /**
   * Send the Booking to Partee
   */
  public function sendToPartee() {
    
    if(env('PARTEE_DISABLE') == 1) return;
    
    $BookPartee = BookPartee::where('book_id', $this->id)->first();

    if ($BookPartee) {
      if ($BookPartee->partee_id > 0) {
        return FALSE;
      }
    } else {
      $BookPartee = new BookPartee();
      $BookPartee->book_id = $this->id;
    }

    //Create Partee
    $partee = new \App\Services\ParteeService();
    if ($partee->conect()) {

      $result = $partee->getCheckinLink($this->customer->email, strtotime($this->start));

      if ($result) {

        $BookPartee->link = $partee->response->checkInOnlineURL;
        $BookPartee->partee_id = $partee->response->id;
        $BookPartee->status = 'sent';
        $BookPartee->log_data = $BookPartee->log_data . "," . time() . '- Sent';
        $BookPartee->save();
      } else {
        $BookPartee->status = 'error';
        $BookPartee->log_data = $BookPartee->log_data . "," . time() . '-' . $partee->response;
        $BookPartee->save();
      }
    } else {

      $BookPartee->status = 'error';
      $BookPartee->log_data = $BookPartee->log_data . "," . time() . '-' . $partee->response;
      $BookPartee->save();
    }
  }

  public function get_ff_status($showAll = true) {
    $result = [
        'name' => '',
        'icon' => null
    ];

    switch ($this->ff_status) {
      case 0:
        if ($showAll) {
          $result = [
              'name' => 'No Gestionada',
              'icon' => asset('/img/miramarski/ski_icon_status_transparent.png')
          ];
        }
        break;
      case 1:
        $result = [
            'name' => 'Cancelada',
            'icon' => asset('/img/miramarski/ski_icon_status_grey.png')
        ];
        break;
      case 2:
        $result = [
            'name' => 'No Cobrada',
            'icon' => asset('/img/miramarski/ski_icon_status_red.png')
        ];
        break;
      case 3:
        $result = [
            'name' => 'Confirmada',
            'icon' => asset('/img/miramarski/ski_icon_status_green.png')
        ];
        break;
      case 4:
        $result = [
            'name' => 'Comprometida',
            'icon' => asset('/img/miramarski/ski_icon_status_orange.png')
        ];
        break;
    }
    return $result;
  }

  public function getPriceBook($dStart,$dEnd,$roomID,$pax=null) {

    $oRoom = Rooms::find($roomID);
    $return = [
      'status'        => 'error',  
      'msg'           => 'error',  
      'pvp'           => 0,  
      'parking'       => 0,
      'price_lux'     => 0,
      'price_limp'    => 0,
      'price_total'    => 0,
    ];
    if (!$oRoom){
      $return['msg'] = "Apto no encontrado";
      return $return;
    }
    if ($oRoom->state != 1){
      $return['msg'] = 'Apto '.$oRoom->name.' no Habilitado';
      return $return;
    }
    if (!$pax) $pax = $this->pax;
    
    $paxPerRoom = $oRoom->minOcu;
    if ($paxPerRoom > $pax)  $pax = $paxPerRoom;

    $price = $oRoom->getPVP($dStart,$dEnd,$pax);
   
    if ($price > 0){
      $return['pvp'] = $price;
      $costes = $oRoom->priceLimpieza($oRoom->sizeApto);
      $return['price_limp'] = $costes['price_limp'];

      $return['parking'] = Http\Controllers\BookController::getPricePark($this->type_park,$this->nigths) * $room->num_garage;
      $return['price_lux'] = Http\Controllers\BookController::getPriceLujo($this->type_luxury);

      $return['price_total'] =  $return['pvp']+ $return['parking']+ $return['price_lux']+ $return['price_limp'];
//          $total   = $price + $priceParking + $limp + $luxury;

    }
   
    $return['status'] = 'ok';
    return $return;
  }
  
  private function getDaysBetween($d1,$d2) {
        $datetime1 = date_create($d1);
        $datetime2 = date_create($d2);
        $interval = date_diff($datetime1, $datetime2);
        return  $interval->format('%a');
  }
  
  private function addtionals() {
    $specials = ['parking_book_cost',
        'parking_book_price',
        'luxury_book_cost',
        'luxury_book_price'];
    $return = [
        'parking_book_cost' => 0,
        'parking_book_price' => 0,
        'luxury_book_cost' => 0,
        'luxury_book_price' => 0,
        ];
    $settingS = \App\Settings::whereIn('key', $specials)->get();
    if ($settingS){
      foreach ($settingS as $s){
        $return[$s->key] = floatval($s->value);
      }
    }
    
    return $return;
    
  }
  
  
  /**
   * show Fianza Icon
   * @return string
   */
  public function getFianza() {
    //ignore Airbnb
    if ($this->agency == 4){
      return '';
    }
    
    
    $hasFianza = BookDeferred::where('book_id',$this->id)->where('paid',1)->first();
    $textToltip = 'Fianza no confirmada';
    $class = 'text-danger';
    if ($hasFianza){
      $textToltip = 'Fianza confirmada';
      $class = 'text-success';
      if ($hasFianza->was_confirm){
        $textToltip = "Fianza ya cobrada.";
      }
            
    }

    return '<div class="tooltip-2 sendFianza cursor '.$class.'" data-id="'.$this->id.'" >'
      . '<i class="fa fa-dollar-sign"></i>'
      . '<div class="tooltiptext">'.$textToltip.'</div>'
      . '</div>';
    
  }
  
  static function getMonthSum($field,$filter,$date1,$date2) {
    
    $typeBooks = '(2, 7, 8)';
  
    return DB::select('SELECT new_date, SUM('.$field.') as total '
            . ' FROM ('
            . '        SELECT '.$field.',DATE_FORMAT('.$filter.', "%m-%y") new_date '
            . '        FROM book'
            . '        WHERE type_book IN '.$typeBooks
            . '        AND '.$filter.' >= "'.$date1.'" '
            . '        AND '.$filter.' <= "'.$date2.'" '
            . '      ) AS temp_1 '
            . ' GROUP BY temp_1.new_date'
            );
    
  }
    
  /**
   * send to channel manager the availibility
   * @param type $available
   */
  public function sendAvailibilityBy_dates($start=null,$finish=null) {
    //check si es del mismo grupo
    $room = Rooms::find($this->room_id);
    if(!$start) $start = $this->start;
    if(!$finish) $finish = $this->finish;
    if (in_array($this->type_book,$this->typeBooksReserv)){
      $this->sendAvailibility($this->room_id,$start,$finish);
    }
  }
  /**
   * send to channel manager the availibility
   * @param type $available
   */
  public function sendAvailibilityBy_Rooms($old_room,$start=null,$finish=null) {
    //check si es del mismo grupo
    $room = Rooms::find($this->room_id);
    if(!$start) $start = $this->start;
    if(!$finish) $finish = $this->finish;
    if ($room){
      $oRooms = Rooms::where('channel_group',$room->channel_group)->pluck('id')->toArray();
      if (!in_array($old_room,$oRooms)){
        $this->sendAvailibility($old_room,$start,$finish);
      } 
      $this->sendAvailibility($this->room_id,$start,$finish);
    }
  }
  /**
   * send to channel manager the availibility
   * @param type $available
   */
  public function sendAvailibilityBy_status() {
    $this->sendAvailibility($this->room_id,$this->start,$this->finish);
  }
  /**
   * send to channel manager the availibility
   * @param type $available
   */
  public function sendAvailibility($room_id,$start,$finish) {
    
    $Zodomus  =  new \App\Services\Zodomus\Zodomus();
    $room     = Rooms::find($room_id);
    
    if ($room){
      $oRooms = Rooms::where('channel_group',$room->channel_group)->pluck('id')->toArray();
      
      $match1 = [['start','>=', $start ],['start','<=', $finish ]];
      $match2 = [['finish','>=', $start ],['finish','<=', $finish ]];
      $match3 = [['start','<', $start ],['finish','>', $finish ]];

      $books = self::where_type_book_reserved()->whereIn('room_id',$oRooms)
            ->where(function ($query) use ($match1,$match2,$match3) {
              $query->where($match1)
                      ->orWhere($match2)
                      ->orWhere($match3);
            })->get();
            
      $avail  = count($oRooms);
      $oneDay = 24*60*60;
      
      //Prepara la disponibilidad por día de la reserva
      $startAux = strtotime($start);
      $endAux = strtotime($finish);
      $aLstDays = [];
      while ($startAux<$endAux){
        $aLstDays[date('Y-m-d',$startAux)] = $avail;
        $startAux+=$oneDay;
      }
      
      $control = [];
      if ($books) {
        foreach ($books as $book) {
          //Resto los días reservados
          $startAux = strtotime($book->start);
          $endAux = strtotime($book->finish);

          while ($startAux < $endAux) {
            $auxTime = date('Y-m-d', $startAux);
            $keyControl = $book->room_id.'-'.$auxTime;
            if (!in_array($keyControl, $control)){
              if (isset($aLstDays[$auxTime]))
                $aLstDays[$auxTime] --;

              $control[] = $keyControl;
            }

            $startAux += $oneDay;
          }
        }
      }
    
      //Genero el listado para enviar a Zodomus
      $resultLst = [];
      $startAux2 = $end = $value = null;
      foreach ($aLstDays as $d => $v) {
        if ($value === null) {
          $value = $v;
          $startAux2 = $d;
        }
        if ($value != $v) {
          $resultLst[] = [
              "avail" => $value,
              "start" => $startAux2,
              "end" => date('Y-m-d', strtotime($end)+$oneDay),
          ];

          $value = $v;
          $startAux2 = $d;
        }

        $end = $d;
      }

      $resultLst[] = [
          "avail" => $v,
          "start" => $startAux2,
          "end" => date('Y-m-d', strtotime($end)+$oneDay),
      ];
      
      //buscos los OTAs
      $otas = [];
      $aptos = configZodomusAptos();
      foreach ($aptos as $cg => $apto){
        if ($cg == $room->channel_group)
          $otas = $apto->rooms;
      }
      //envío cada periodo de disponibilidad
      foreach ($resultLst as $data){
        foreach ($otas as $ota){

          $paramAvail = [
              "channelId" =>  $ota->channel,
              "propertyId" => $ota->propID,
              "roomId" =>  $ota->roomID,
              "dateFrom" => $data['start'],
              "dateTo" => $data['end'],
              "availability" =>  $data['avail'],
            ];
         
          $return = $Zodomus->setRoomsAvailability($paramAvail);
        }
      }
      
    }
  }
    
    
   /**
   * Get Availibility Room By channel
   * @param type $available
   */
  public function getAvailibilityBy_channel($apto, $start, $finish) {

    $Zodomus = new \App\Services\Zodomus\Zodomus();
    $oRooms = Rooms::where('channel_group', $apto)->pluck('id')->toArray();

    $match1 = [['start', '>=', $start], ['start', '<=', $finish]];
    $match2 = [['finish', '>=', $start], ['finish', '<=', $finish]];
    $match3 = [['start', '<', $start], ['finish', '>', $finish]];

    $books = self::where_type_book_reserved()->whereIn('room_id', $oRooms)
                    ->where(function ($query) use ($match1, $match2, $match3) {
                      $query->where($match1)
                      ->orWhere($match2)
                      ->orWhere($match3);
                    })->get();

    $avail = count($oRooms);
    $oneDay = 24 * 60 * 60;

    //Prepara la disponibilidad por día de la reserva
    $startAux = strtotime($start);
    $endAux = strtotime($finish);
    $aLstDays = [];
    while ($startAux <= $endAux) {
      $aLstDays[date('Y-m-d', $startAux)] = $avail;
      $startAux += $oneDay;
    }


    $control = [];
    if ($books) {
      foreach ($books as $book) {
        //Resto los días reservados
        $startAux = strtotime($book->start);
        $endAux = strtotime($book->finish);

        while ($startAux < $endAux) {
          $auxTime = date('Y-m-d', $startAux);
          $keyControl = $book->room_id.'-'.$auxTime;
          if (!in_array($keyControl, $control)){
            if (isset($aLstDays[$auxTime]))
              $aLstDays[$auxTime] --;
            
            $control[] = $keyControl;
          }

          $startAux += $oneDay;
        }
      }
    }
    
    return $aLstDays;
  }
  
  
  /**
   * send to channel manager the availibility
   * @param type $available
   */
  public function sendAvailibility_test($room_id=129,$start='2020-02-14',$finish='2020-02-17') {
    
    $Zodomus  =  new \App\Services\Zodomus\Zodomus();
    $room     = Rooms::find($room_id);
    
    if ($room){
      $oRooms = Rooms::where('channel_group',$room->channel_group)->pluck('id')->toArray();
      
      $match1 = [['start','>=', $start ],['start','<=', $finish ]];
      $match2 = [['finish','>=', $start ],['finish','<=', $finish ]];
      $match3 = [['start','<', $start ],['finish','>', $finish ]];

      $books = self::where_type_book_reserved()->whereIn('room_id',$oRooms)
            ->where(function ($query) use ($match1,$match2,$match3) {
              $query->where($match1)
                      ->orWhere($match2)
                      ->orWhere($match3);
            })->get();
            
      $avail  = count($oRooms);
      $oneDay = 24*60*60;
      
      //Prepara la disponibilidad por día de la reserva
      $startAux = strtotime($start);
      $endAux = strtotime($finish);
      $aLstDays = [];
      while ($startAux<$endAux){
        $aLstDays[date('Y-m-d',$startAux)] = $avail;
        $startAux+=$oneDay;
      }
      
      $control = [];
      if ($books) {
        foreach ($books as $book) {
          //Resto los días reservados
          $startAux = strtotime($book->start);
          $endAux = strtotime($book->finish);

          while ($startAux < $endAux) {
            $auxTime = date('Y-m-d', $startAux);
            $keyControl = $book->room_id.'-'.$auxTime;
            if (!in_array($keyControl, $control)){
              if (isset($aLstDays[$auxTime]))
                $aLstDays[$auxTime] --;

              $control[] = $keyControl;
            }

            $startAux += $oneDay;
          }
        }
      }
    
      //Genero el listado para enviar a Zodomus
      $resultLst = [];
      $startAux2 = $end = $value = null;
      foreach ($aLstDays as $d => $v) {
        if ($value === null) {
          $value = $v;
          $startAux2 = $d;
        }
        if ($value != $v) {
          $resultLst[] = [
              "avail" => $value,
              "start" => $startAux2,
              "end" => date('Y-m-d', strtotime($end)+$oneDay),
          ];

          $value = $v;
          $startAux2 = $d;
        }

        $end = $d;
      }

      $resultLst[] = [
          "avail" => $v,
          "start" => $startAux2,
          "end" => date('Y-m-d', strtotime($end)+$oneDay),
      ];
      
      //buscos los OTAs
      $otas = [];
      $aptos = configZodomusAptos();
      foreach ($aptos as $cg => $apto){
        if ($cg == $room->channel_group)
          $otas = $apto->rooms;
      }
      //envío cada periodo de disponibilidad
//      foreach ($resultLst as $data){
//        foreach ($otas as $ota){
//          
//          $avail = intval($data['avail']);
//
//          $paramAvail = [
//              "channelId" =>  $ota->channel,
//              "propertyId" => $ota->propID,
//              "roomId" =>  $ota->roomID,
//              "dateFrom" => $data['start'],
//              "dateTo" => $data['end'],
//              "availability" =>  ($avail<1) ? $avail : 1,
//            ];
//          $return = $Zodomus->setRoomsAvailability($paramAvail);
//            var_dump($paramAvail,$return);
//        }
//      }
      
      
      $paramAvail = [
              "channelId" =>  1,
              "propertyId" => 2092950,
              "roomId" =>  209295003,
              "dateFrom" => "2020-07-14",
              "dateTo" => "2020-07-15",
              "availability" =>   0,
            ];
          $return = $Zodomus->setRoomsAvailability($paramAvail);
           var_dump($paramAvail,$return);
      $paramAvail = [
              "channelId" =>  1,
              "propertyId" => 2092950,
              "roomId" =>  209295003,
              "dateFrom" => "2020-07-15",
              "dateTo" => "2020-07-16",
              "availability" =>   1,
            ];
          $return = $Zodomus->setRoomsAvailability($paramAvail);
           var_dump($paramAvail,$return);
      $paramAvail = [
              "channelId" =>  1,
              "propertyId" => 2092950,
              "roomId" =>  209295003,
              "dateFrom" => "2020-07-16",
              "dateTo" => "2020-07-17",
              "availability" =>   0,
            ];
          $return = $Zodomus->setRoomsAvailability($paramAvail);
           var_dump($paramAvail,$return);
          
      
    }
  }
  
  
  public function getCostBook(){
      $start     = Carbon::createFromFormat('Y-m-d', $this->start);
      $finish    = Carbon::createFromFormat('Y-m-d', $this->finish);
      $countDays = $finish->diffInDays($start);
      $pax = $this->pax;
      $paxPerRoom = Rooms::getPaxRooms($pax, $this->room_id);

      if ($paxPerRoom > $pax)
      {
          $pax = $paxPerRoom;
      }
      $costBook = 0;
      $counter  = $start->copy();
      for ($i = 1; $i <= $countDays; $i++)
      {
          $date = $counter->copy()->format('Y-m-d');

          $seasonActive = Seasons::getSeasonType($date);
          $costs        = Prices::getCostsFromSeason($seasonActive, $pax);

          foreach ($costs as $precio)
          {
              $costBook = $costBook + $precio['cost'];
          }

          $counter->addDay();
      }
      return $costBook;
  }

}
