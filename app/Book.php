<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;
use DB;
use Mail;
use App\Traits\BookEmailsStatus;
use App\BookPartee;

class Book extends Model {

  protected $table = 'book';
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

  public function payments() {
    return $this->hasMany(Payments::class);
  }
  public function leads() {
    return $this->hasOne('\App\CustomersRequest', 'book_id', 'id');
  }
  public function notifications() {
    return $this->hasMany('\App\BookNotification', 'book_id', 'id');
  }
  public function LogImages() {
    return $this->hasMany('\App\LogImages', 'book_id', 'id');
  }

  public function partee() {
    return $this->hasOne(BookPartee::class)->first();
  }

  public function getSumPaymentsAttribute() {
    return $this->payments->sum('import');
  }
  public function getPayment($tipo) {
    return $this->payments->filter(function ($payment) use ($tipo) {
              return $payment->type == $tipo;
            })->sum('import');
  }

  public function user() {
    return $this->hasOne('\App\User', 'id', 'user_id');
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
        4 => "REINTEGRO",//Devoluciones
        5 => "BANCO"//BANCO
    ];

    if (!is_null($typePayment)) return $typePayment = $array[$typePayment];
    
    return $array;
  }

  //Para poner nombre al parking de la reserva//
  static function getParking($iten) {
    $array = [
        1 => "Si",
        2 => "No",
        3 => "Gratis",
        4 => "50 %"
    ];

    return isset($array[$iten]) ? $array[$iten] : 'No';
  }

  // Para poner nombre al suplemento de lujo en la reserva
  static function getSupLujo($lujo) {
    $array = [
        1 => "Si",
        2 => "No",
        3 => "Gratis",
        4 => "50 %"
    ];

    return isset($array[$lujo]) ? $array[$lujo] : 'No';
  }

   //Para poner nombre a la agencia//
  static function listAgency() {
//    $array = [
//        0 => "",
//        1 => "Booking",
//        2 => "Trivago",
//        3 => "Bed&Snow",
//        4 => "AirBnb",
//        5 => "Jaime Diaz",
//        6 => "S.essence",
//        7 => "Cerogrados"
//    ];
//    
//    for($i=1;$i<21;$i++){
//      $array[] = 'Agencia-'.$i;
//    }
//    $array[28] = 'Expedia';
//    $array[999999] = 'Google';
//    return $array;
    
    $array = [
        0 => "Directa",
        1 => "Booking",
        4 => "AirBnb"
        ];
    $array[28] = 'Expedia';
    $array[98] = 'Agoda';
    $array[99] = 'Google';
    $array[5] = "Jaime Diaz";
    $array[3] = "Bed&Snow";
    $array[2] = "Trivago";
    $array[6] = "S.essence";
    $array[7] = "Cerogrados";
    $array[29] = 'HOMEREZ';
    $array[31] = "WEBDIRECT";
    for($i=1;$i<21;$i++){
      $array[7+$i] = 'Agencia-'.$i;
    }
     $array[999999] = 'Google (no usar)' ;
    
    return $array;
    
    
  }
  
  //Para poner nombre a la agencia//
  static function getAgency($agency) {
    $array = self::listAgency();
    
    for($i=1;$i<21;$i++){
      $array[] = 'Agencia '.$i;
    }
    return isset($array[$agency]) ? $array[$agency] : 'Sin Nombre';
  }
  
  /**
   * Get the total cost
   * 
   * @return int $cost_total
   */
  public function get_costeTotal() {
    $cost_total = $this->get_costProp() + $this->cost_limp + $this->PVPAgencia + $this->extraCost;
    $paymentTPV = $this->getPayment(2);
    if ($paymentTPV>0) $cost_total += paylandCost($paymentTPV);
    return $cost_total;
  }
  
  function get_costProp(){
    $cost = $this->cost_apto +  $this->cost_park;
    $cost += $this->get_costLujo();
    return $cost;
  }
  
  function get_costLujo(){
//    if ($this->room){
//      if ($this->room->luxury != 1) return 0;
//    }
    if ($this->type_luxury == 1 || $this->type_luxury == 3 || $this->type_luxury == 4) {
      return $this->cost_lujo;
    }
    return 0;
  }
  
  /**
   * Get the benefit from the book
   * 
   * @return int inc_percent
   */
  public function get_inc_percent() {
    
    $total_price = $this->total_price;
    $profit = $total_price-$this->get_costeTotal();
    $inc_percent = 0;
    if ($total_price != 0) {
      $inc_percent = ($profit / $total_price ) * 100;
    }

    return $inc_percent;
  }
  
  public function hasSendPicture() {
    return DB::table('log_images')->where('book_id',$this->id)->count();
  }
  public function getSendPicture() {
    return DB::table('log_images')->where('book_id',$this->id)->get();
  }
  

  //Para comprobar el dia de la reserva en el calendario
  static function availDate($startDate, $endDate, $room,$bookID=null) {

    $qry = self::where_type_book_reserved()->where('room_id',$room)
            ->where('finish','>=',$startDate)->where('start','<=',$endDate);
    if ($bookID && $bookID>0) {
      $qry->where('id','!=',$bookID);
    } 
    $books = $qry->get();
    if (count($books)==0) return true;
    
    foreach ($books as $b){
      if ($b->finish == $startDate) continue;
      if ($b->start == $endDate)   continue;
      return false;
    }
    return true;
  }
  
  
    /**
   * Get object Book that has status 2,7,8
   * 
   * @return Object Query
   */
  static function where_type_book_sales($reservado_stripe=false,$ota=false,$overbooking=false) {
    
    $types = self::get_type_book_sales($reservado_stripe,$ota,$overbooking);
    return self::whereIn('type_book',$types);
    
  }
  static function where_type_book_prop() {
    return self::whereIn('type_book', [2, 7]);
  }
  /**
   * Get object Book that has status 2,7,8
   * 
   * @return Object Query
   */
  static function where_type_book_reserved($real=false) {
    if ($real) return self::whereIn('type_book', [1,2,7,8,9,10,11]);
    return self::whereIn('type_book', [1,2,4,7,8,9,10,11]);
  }
  
  static function get_type_book_sales($reservado_stripe=false,$ota=false,$overbooking=false) {
     $types = [2, 7, 8];
    if ($reservado_stripe) $types[] = 1;
    if ($ota) $types[] = 11;
    if ($overbooking) $types[] = 10;
    //Pagada-la-señal / Reserva Propietario / ATIPICAS
    return $types;
  }
  
  static function get_type_book_reserved() {
    return [1,2,4,7,8,9,10,11];
  }
  
  static function get_type_book_pending() {
    return [3,4,5,6,10,11,99];
  }
  
  /**
   * Get object Book that has status 2,7,8
   * 
   * @return Object Query
   */
  static function where_book_times($start,$finish) {
    
    $match1 = [['start', '>=', $start], ['start', '<=', $finish]];
    $match2 = [['finish', '>=', $start], ['finish', '<=', $finish]];
    $match3 = [['start', '<', $start], ['finish', '>', $finish]];

    return self::where(function ($query) use ($match1, $match2, $match3) {
                      $query->where($match1)
                      ->orWhere($match2)
                      ->orWhere($match3);
                    });
  }
  
  /**
   * Get object Book that has status 2,7,8
   * 
   * @return Object Query
   */
  static function w_book_times($qry,$start,$finish) {
      
    $match1 = [['start', '>=', $start], ['start', '<=', $finish]];
    $match2 = [['finish', '>=', $start], ['finish', '<=', $finish]];
    $match3 = [['start', '<', $start], ['finish', '>', $finish]];
    return $qry->where(function ($query) use ($match1, $match2, $match3) {
                      $query->where($match1)
                      ->orWhere($match2)
                      ->orWhere($match3);
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
      'cost_apto'     => 0,  
      'parking'       => 0,
      'price_lux'     => 0,
      'price_limp'    => 0,
      'price_extr'    => 0,
      'cost_parking'  => 0,
      'cost_limp'     => 0,
      'cost_lux'      => 0,
      'cost_extr'     => 0,
      'price_total'   => 0,
      'cost_total'    => 0,
        
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
      $return['price_limp']= $costes['price_limp'];
      $return['cost_limp'] = $costes['cost_limp'];
      $return['cost_apto'] = $oRoom->getCostRoom($dStart,$dEnd,$pax);

      $return['parking'] = Http\Controllers\BookController::getPricePark($this->type_park,$this->nigths) * $oRoom->num_garage;
      $return['cost_parking'] = Http\Controllers\BookController::getCostPark($this->type_park,$this->nigths) * $oRoom->num_garage;
      $return['price_lux'] = Http\Controllers\BookController::getPriceLujo($this->type_luxury);
      $return['cost_lux'] = Http\Controllers\BookController::getCostLujo($this->type_luxury);
      
      $extraPrice = \App\Extras::find(4);
      $return['price_extr'] = floatval($extraPrice->price);
      $return['cost_extr']  = floatval($extraPrice->cost);
      
      $return['price_total'] =  $return['pvp']+ $return['parking']+ $return['price_lux']+ $return['price_limp']+ $return['price_extr'];

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
    
    $typeBooks = '(1,2,4,7,8,11)';
    
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
   
  public function getCustomerName() {
    $cust = $this->customer;
    if ($cust) return $cust->name;
    return '--';
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
//      $oRooms = Rooms::where('channel_group',$room->channel_group)->pluck('id')->toArray();
      $oRooms = Rooms::RoomsCH_IDs($room->channel_group);
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
    $room     = Rooms::find($room_id);
    if ($room){
      $oOta = new Services\OtaGateway\OtaGateway();
      $oRooms = Rooms::RoomsCH_IDs($room->channel_group);
            
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
      
      
      //Prepara la disponibilidad por día de la reserva
      $today = strtotime(date('Y-m-d'));
      $startAux = strtotime($start);
      $endAux = strtotime($finish);
      if ($startAux<$today) $startAux = $today;
      $aLstDays = [];
      while ($startAux<$endAux){
        $aLstDays[date('Y-m-d',$startAux)] = $avail;
        $startAux = strtotime("+1 day", $startAux);
      }
      $control = [];
      if ($books) {
        foreach ($books as $book) {
          //Resto los días reservados
          $startAux = strtotime($book->start);
          $endAux = strtotime($book->finish);
          if ($startAux == $endAux){
            $auxTime = date('Y-m-d', $startAux);
            $keyControl = $book->room_id.'-'.$auxTime;
            if (!in_array($keyControl, $control)){
              if (isset($aLstDays[$auxTime]))
                $aLstDays[$auxTime]--;
              $control[] = $keyControl;
            }
          } else {
            while ($startAux < $endAux) {
              $auxTime = date('Y-m-d', $startAux);
              $keyControl = $book->room_id.'-'.$auxTime;
              if (!in_array($keyControl, $control)){
                if (isset($aLstDays[$auxTime]))
                  $aLstDays[$auxTime] --;

                $control[] = $keyControl;
              }

              $startAux = strtotime("+1 day", $startAux);
            }
          }
        }
      }
      if($oOta->conect()){
        $return = $oOta->sendAvailabilityByCh($room->channel_group,$aLstDays);
        return ($return == 200);
      } else {
        return false;
      }
    }
  }
    
    
   /**
   * Get Availibility Room By channel
   * @param type $available
   */
  public function getAvailibilityBy_channel($apto, $start, $finish,$return = false,$justSale=false,$real=false) {

//    $oRooms = Rooms::where('channel_group', $apto)->pluck('id')->toArray();
    $oRooms = Rooms::RoomsCH_IDs($apto);
    $match1 = [['start', '>=', $start], ['start', '<=', $finish]];
    $match2 = [['finish', '>=', $start], ['finish', '<=', $finish]];
    $match3 = [['start', '<', $start], ['finish', '>', $finish]];

    if ($justSale) $sqlBooks = self::where_type_book_sales();
    else  $sqlBooks = self::where_type_book_reserved($real);
    
    $books = $sqlBooks->whereIn('room_id', $oRooms)
                    ->where(function ($query) use ($match1, $match2, $match3) {
                      $query->where($match1)
                      ->orWhere($match2)
                      ->orWhere($match3);
                    })->get();

    $avail = count($oRooms);
   

    //Prepara la disponibilidad por día de la reserva
    $startAux = strtotime($start);
    $endAux = strtotime($finish);
    $aLstDays = [];
    while ($startAux <= $endAux) {
      $aLstDays[date('Y-m-d', $startAux)] = $avail;
      $startAux = strtotime("+1 day", $startAux);
    }


    $control = [];
    if ($books) {
      foreach ($books as $book) {
        //Resto los días reservados
        $startAux = strtotime($book->start);
        $endAux = strtotime($book->finish);

        if ($startAux == $endAux){
          $auxTime = date('Y-m-d', $startAux);
          $keyControl = $book->room_id.'-'.$auxTime;
          if (!in_array($keyControl, $control)){
            if (isset($aLstDays[$auxTime]))
              $aLstDays[$auxTime]--;
            $control[] = $keyControl;
          }
        } else {
          while ($startAux < $endAux) {
        
            $auxTime = date('Y-m-d', $startAux);
            $keyControl = $book->room_id.'-'.$auxTime;
            if (!in_array($keyControl, $control)){
              if (isset($aLstDays[$auxTime]))
                $aLstDays[$auxTime] --;

              $control[] = $keyControl;
            }

            $startAux = strtotime("+1 day", $startAux);
          }
        }
      }
    }
    if($return){
      return [$aLstDays,$avail];
    }
    return $aLstDays;
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


  public function bookingProp() {
    $room = $this->room;
    $cost_limp = is_null($room->limp_prop) ? 30 : $room->limp_prop;
    \App\Expenses::setExpenseLimpieza($this->id, $room, $this->finish,$cost_limp);
    $pagoAut = new Payments();
    $pagoAut->import = $cost_limp;
    $pagoAut->book_id = $this->id;
    $pagoAut->datePayment = $this->finish;
    $pagoAut->comment = 'Automatíco - Resrv. Prop.';
    $pagoAut->type = 5;
    $pagoAut->save();
//    \App\Incomes::setPropLimpieza($this->id, $room, $this->finish,$cost_limp);
  }
  public function bookingFree() {    
    $this->sup_park    = 0;
    $this->cost_park   = 0;
    $this->sup_lujo    = 0;
    $this->cost_lujo   = 0;
    $this->cost_apto   = 0;
    $this->sup_limp    = 0;
    $this->extraCost   = 0;
    $this->extraPrice  = 0;
//    $this->cost_limp   = 0;
    $this->real_price  = 0;
//    $this->cost_total  = 0;
    $this->total_ben   = 0;
    $this->inc_percent = 0;
    $this->ben_jorge   = 0;
    $this->ben_jaime   = 0;
//    $this->total_price = 0;
         
  }
  
  // Funcion para cambiar la reserva de estado
  public function changeBook($status, $room, $book) {
    $status = intval($status);
    $this->customer->send_mails = true;
    $response = ['status' => '', 'title' => 'OK', 'response' => '','changed'=>false];
    if (empty($status)){
      return ['status' => 'danger', 'title' => 'Error', 'response' => 'Sin estado','changed'=>false];
    }
    if ($this->type_book == 0){
        $response['status'] = "warning";
        $response['response'] = "La Reserva esta eliminada";
        return $response;
    }
    
//    if ($status == 7){  LO SACO PARA NO HACER PROBLEMAS - DEBEN ELIMINAR LA RESERVA Y CREARLA COMO RESERV PROP
//      /* Asiento automatico para reservas subcomunidad*/
//      $this->bookingFree();
//      $this->bookingProp(\App\Rooms::find($this->room_id));
//      $this->save();
//    } else {
//      //Remove automatic expenses
//      \App\Expenses::delExpenseLimpieza($this->id);
//    }
        
    if ($status == 3 || $status == 10 || $status == 12 || $status == 6 || $status == 98) {
      $this->type_book = $status;
      $this->save();
      $response['status'] = "success";
      $response['changed'] = true;
      $response['response'] = $this->getResponseStatusChanged($status);
      if ($status == 6) $this->sendEmailChangeStatus($book, 'Reserva denegada', $status);
     
      
      \App\BookLogs::saveLogStatus($this->id, $this->room_id, $this->customer->email, $this->getStatus($status));
      return $response;
      
    } else {
      
      //check if is Availiable Date
      if (Book::availDate($this->start, $this->finish, $this->room_id,$this->id)) {

        $this->type_book = $status;
        if ($status == 2) {
          $this->sendToPartee();
        }
            
        if ($this->customer->email == "") {
          $this->save();
          \App\BookLogs::saveLogStatus($this->id, $this->room_id, $this->customer->email, $this->getStatus($status));
          $response['status'] = 'warning';
          $response['title'] = 'Cuidado';
          $response['changed'] = true;
          $response['response'] = 'No tiene Email asignado';

          return $response;
        
        } else {
           switch ($status) {
              case 1:
                $this->sendEmailChangeStatus($book, 'Bloqueo de reserva y datos de pago', $status);
                break;
              case 2:
                $this->sendEmailChangeStatus($book, 'Confirmación de reserva (pago parcial)', $status);
                break;
              case 7:
                $this->sendEmailChangeStatus($book, 'Correo de Reserva de Propietario', $status);
                break;
            }
            

            if ($this->save()) {
              $response['status'] = "success";
              $response['changed'] = true;
              /** @ToDo: REVISAR: Creamos las notificaciones de booking */
              /* Comprobamos que la room de la reserva este cedida a booking.com */
              if ($this->room->isAssingToBooking()) {

                $isAssigned = \App\BookNotification::where('book_id', $book->id)->get();

                if (count($isAssigned) == 0) {
                  $notification = new \App\BookNotification();
                  $notification->book_id = $book->id;
                  $notification->save();
                }
              }
              $response['response'] = $this->getResponseStatusChanged($status);
              \App\BookLogs::saveLogStatus($this->id, $this->room_id, $this->customer->email, $this->getStatus($status));
              return $response;
            }
        }
      } // END: Check availibility
    }
    
    $response['status'] = 'danger';
    $response['title'] = 'Peligro';
    $response['response'] = 'No puedes cambiar el estado - Los aptos no están disponibles';
   
    return $response;
      
  }
  
  function getResponseStatusChanged($status) {
    $response = '';
    switch ($status) {
      case 3:  $response = "Estado Cambiado a Sin Responder";
        break;
      case 10: $response = "Reserva cambiada a Overbooking";
        break;
      case 12: $response = "Reserva cambiada a ICAL - INVISIBLE";
        break;
      case 98: $response = "Reserva cambiada a cancel-XML";
        break;
      case 6:  $response = "Reserva cambiada a ICAL - INVISIBLE";
        break;
      case 1:  $response = "Email Enviado Reserva";
        break;
      case 2:  $response = "Email Enviado Pagada la señal";
        break;
      case 7:  $response = "Estado Cambiado a Reserva Propietario";
        break;
      case 8:  $response = "Estado Cambiado a Subcomunidad";
        break;
      case 4:  $response = "Estado Cambiado a Bloqueado";
        break;
      case 5:  $response = "Contestado por email";
        break;
      default: $response = "Estado Cambiado";
        break;
    }
    return $response;
  }
  
  public function getLastPayment() {
    $lastPayment = 0;
    if (!$this->payments){
      $payments = \App\Payments::where('book_id', $this->id)->get();
      if ($payments){
        foreach ($payments as $payment){
          $lastPayment += $payment->import;
        }
      }
    } else {
      if (count($this->payments) > 0) {
        foreach ($this->payments as $index => $payment) {
          $lastPayment = $payment->import;
        }
      }
    }

    return $lastPayment;
  }
  
  function getJorgeProfit(){return 0;}
  function getJaimeProfit(){return 0;}
  
  public function SafetyBox() {
    return $this->hasOne(\App\BookSafetyBox::class)->first();
  }
  
  static function getBy_temporada(){
    $activeYear = Years::getActive();
    return Book::where_type_book_sales(true)
            ->where('start', '>=', $activeYear->start_date)
            ->where('start', '<=', $activeYear->end_date)->get();
  }
  
  
  
  /**
   * Show the price cell in plannings
   * @param type $payment
   */
  public function showPricePlanning($payment) {
    $pay = isset($payment[$this->id]) ? $payment[$this->id] : null;
    $color = (intval($this->total_price-$pay)<1)? ' style="color:green"' : ' style="color:red" ';
    
    echo '<div class="col-xs-6 not-padding">'.round($this->total_price) . '€';
    if ($pay !== null)
      echo '<p '.$color.'>'.round($pay).'€</p>';
    echo '</div>';
     if ($pay){ 
        $bg = '';
        if ($pay && $pay > 0 && $this->total_price > 0)
          $total = number_format(100 / ($this->total_price / $pay), 0);
        else {
          $total = 0;
          $bg = 'bg-success';
        }
        
        echo '<div class="col-xs-6 pay-percent '.$bg.'">
                <b '.$color.'>'.$total.'%</b>
              </div>';
        
     } else { 
       
       echo '<div class="col-xs-6 pay-percent bg-success">
                  <b style="color: red;">0%</b>
                </div>';
     }
           
  }
  
  
  function printExtraIcon(){
    return '';
    $lujo = $this->getSupLujo($this->type_luxury);
    $parking = $this->getParking($this->type_park);
    
    if ($parking != 'No')
       echo '<icon><i class="fas fa-parking" title="Parking ('.$parking.')"></i></icon>';
    if ($lujo != 'No')
       echo '<icon><i class="fas fa-star" title="Supl Lujo ('.$lujo.')"></i></icon>';
  }
  /**********************************************************************/
  /////////  book_meta //////////////
   public function setMetaContent($key,$content) {
      
      
    $updated =  DB::table('book_meta')->where('book_id',$this->id)
              ->where('meta_key',$key)
              ->update(['meta_value' => $content]);

    if (!$updated) {
      DB::table('book_meta')->insert(
            ['book_id' => $this->id, 'meta_key' => $key,'meta_value' => $content]
        );
    }
  }
  
  public function getMetaContent($key) {
    
    $book_meta = DB::table('book_meta')
            ->where('book_id',$this->id)->where('meta_key',$key)->first();
    
    if ($book_meta) {
      return $book_meta->meta_value;
    }
    return null;
  }
}