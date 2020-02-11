<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\DB;
use App\Services\Zodomus\Zodomus;
use App\Services\Zodomus\Config as ZConfig;
use App\Rooms;
use App\SizeRooms;
use App\Book;

/// /admin/zodomus/import?detail=1
class ZodomusImportAll extends Command {

  /**
   * Customer ID for assign to the new books
   *
   * @var int
   */
  private $customer_id = 1780;

  /**
   * User ID for assign to the new books
   *
   * @var int
   */
  private $user_id = 39;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'zodomus:importAll';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import Booking from zodomus';

  /**
   * The console command result.
   *
   * @var string
   */
  var $result = array();

  /**
   * The console command result.
   *
   * @var string
   */
  var $sZodomus = array();

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    $this->result = array();
    $this->sZodomus = new Zodomus();
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    
    
    
    $cannels = configZodomusAptos();
    $Zodomus = new Zodomus();
    $alreadySent = [];
    $reservas = [];
    foreach ($cannels as $cg => $apto) {
     
      //get all channels
      foreach ($apto->rooms as $room) {
        if (true) {
          $keyIteration = $room->channel . '-' . $room->propID;
          if (in_array($keyIteration, $alreadySent))
            continue;

          $channelId = $room->channel;
          $propertyId = $room->propID;
          $bookings = $Zodomus->getBookingsQueue($room->channel, $room->propID);
       
          if ($bookings && $bookings->status->returnCode == 200) {
            $alreadySent[] = $keyIteration;
            
            foreach ($bookings->reservations as $book) {
              $reservas[] = [
                  $room->channel, $room->propID,$book->id
                ];
            }
          }
        }
      }
    }
    $response = null;
    foreach ($reservas as $r){
      $alreadyExist = \App\Book::where('external_id', $r[2])->first();
      if (!$alreadyExist){
        $response = $this->importAnReserv($r[0],$r[1],$r[2]);
      }
    }
    
  }

  function importAnReserv($channelId,$propertyId,$reservationId,$force=false){
    
        $oZodomus = new Zodomus();
        $zConfig = new ZConfig();

        $param = [
                "channelId" =>  $channelId,
                "propertyId" => $propertyId,
                "reservationId" =>  $reservationId,
              ];

        $reservation = $oZodomus->getBooking($param);
        
        if ($reservation && isset($reservation->status) && $reservation->status->returnCode == 200){
          $booking = $reservation->reservations;
          if (!isset($booking->rooms)) {
            if ($booking->reservation->status == 3) { //Cancelada
              $alreadyExist = \App\Book::where('external_id', $reservationId)->get();
              if ($alreadyExist) {
                foreach ($alreadyExist as $item){
                  $response = $item->changeBook(3, "", $item);
                  echo $item->id.' cancelado - ';
                  if ($response['status'] == 'success' || $response['status'] == 'warning') {
                    //Ya esta disponible
                    $item->sendAvailibilityBy_status();
                  }
                }
              }
            }
            return;
          }
          //Una reserva puede tener multiples habitaciones
          $rooms = $booking->rooms;
          foreach ($rooms as $room){
            $roomId = $room->id;
            $roomReservationId = $room->roomReservationId;
             //check if exists
            $alreadyExist = \App\Book::where('external_id', $reservationId)
                    ->where(function ($query) use ($roomReservationId) {
                                            $query->where('external_roomId', $roomReservationId)
                                                  ->orWhereNull('external_roomId');
                                        })->first();
                                       
            if ($alreadyExist){
              if ($booking->reservation->status == 3){ //Cancelada

                $response = $alreadyExist->changeBook(3, "", $alreadyExist);
                if ($response['status'] == 'success' || $response['status'] ==  'warning'){
                  //Ya esta disponible
                  $alreadyExist->sendAvailibilityBy_status();
                }
              }
              
              continue;
            }              
            
            $cg = $oZodomus->getChannelManager($channelId,$propertyId,$roomId);
            if (!$cg){//'no se encontro channel'
              continue;
            }
            
            $totalPrice = $room->totalPrice;
            if (isset($room->priceDetails)){
              foreach ($room->priceDetails as $priceDetails){
                if ($totalPrice < $priceDetails->total){
                  $totalPrice = $priceDetails->total;
                }
              }
            }
            
            $rateId   = isset($room->prices[0]) ? $room->prices[0]->rateId : 0;
            $comision = $zConfig->get_comision($totalPrice,$channelId);
            $reserv = [
                'channel' => $channelId,
                'propertyId' => $propertyId,
                'rate_id' => $rateId,
                'comision'=>$comision,
                'external_roomId' => $roomReservationId,
                'channel_group' => $cg,
                'agency' => $zConfig->getAgency($channelId),
                'reser_id' => $reservationId,
                'status' => $booking->reservation->status,
                'customer' => $booking->customer,
                'totalPrice' => $totalPrice,
                'numberOfGuests' => $room->numberOfGuests,
                'mealPlan' => $room->mealPlan,
                'start' => $room->arrivalDate,
                'end' => $room->departureDate,
            ];
            
            $oZodomus->saveBooking($cg,$reserv);
          }
        }
        
    }
    
  

}
