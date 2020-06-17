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
    $array[999999] = 'Google';
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
  static function where_type_book_sales($reservado_stripe=false) {
    if ($reservado_stripe) return self::whereIn('type_book', [1, 2, 7, 8]);
    //Pagada-la-señal / Reserva Propietario / ATIPICAS
                
    return self::whereIn('type_book', [2, 7, 8]);
  }
  static function where_type_book_prop() {
    return self::whereIn('type_book', [2, 7]);
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
  
  static function get_type_book_pending() {
    return [3,4,5,6,10,11,99];
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

      $return['parking'] = Http\Controllers\BookController::getPricePark($this->type_park,$this->nigths) * $oRoom->num_garage;
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
    
    $typeBooks = '(2,7,8)';
  
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
        
        $WubookAvailDays[] = [
          'channel_group' => $room->channel_group,
          'date'          => $d,
          'avail'         => $v
        ];
         
      }
      
      //save the new availibility
      if (count($WubookAvailDays)){
        \App\WobookAvails::insert($WubookAvailDays);
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
  public function getAvailibilityBy_channel($apto, $start, $finish,$return = false) {

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

  public function bookingProp($room) {
    
    $this->sup_park    = 0;
    $this->cost_park   = 0;
    $this->sup_lujo    = 0;
    $this->cost_lujo   = 0;
    $this->cost_apto   = 0;
    $this->sup_limp  = ($room->sizeApto == 1) ? 30 : 50;
    $this->cost_limp = is_null($room->limp_prop) ? 30 : $room->limp_prop;
    $this->real_price = ($room->sizeApto == 1) ? 30 : 50;
    $this->cost_total = ($room->sizeApto == 1) ? 30 : 40;
    $this->total_price = $this->real_price;
      
    \App\Expenses::setExpenseLimpieza($this->id, $room, $this->finish,$this->cost_limp);
                
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
            
            if ($status == 7){
              /* Asiento automatico para reservas subcomunidad*/
              $this->bookingProp(\App\Rooms::find($this->room_id));
            } else {
              //Remove automatic expenses
              \App\Expenses::delExpenseLimpieza($this->id);
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
    if (count($this->payments) > 0) {
      foreach ($this->payments as $index => $payment) {
        $lastPayment = $payment->import;
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
    return Book::where_type_book_sales()
            ->where('start', '>=', $activeYear->start_date)
            ->where('start', '<=', $activeYear->end_date)->get();
  }
}