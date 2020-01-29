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
class ZodomusImport extends Command {

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
  protected $signature = 'zodomus:import';

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
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    $this->result = array();
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
    $ZConfig = new ZConfig();
    $reservations = [];
    $reservIDs = [];
    foreach ($cannels as $cg => $apto) {
      //get all channels
      foreach ($apto->rooms as $room) {
        $bookings = $Zodomus->getBookings($room->channel, $room->propID);
        if ($bookings && $bookings->status->returnCode == 200) {
          
          foreach ($bookings->reservations as $book) {

            $reservIDs[] = $book->reservation->id;
            $reservations[] = [
                'channel' => $room->channel,
                'channel_group' => $cg,
                'agency' => $ZConfig->getAgency($room->channel),
                'reser_id' => $book->reservation->id,
                'status' => $book->reservation->status,
                'customer' => $book->customer,
                'totalPrice' => $book->rooms[0]->totalPrice,
                'numberOfGuests' => $book->rooms[0]->numberOfGuests,
                'mealPlan' => $book->rooms[0]->mealPlan,
                'start' => $book->rooms[0]->arrivalDate,
                'end' => $book->rooms[0]->departureDate,
            ];
          }
        }
      }
    }

    $this->loadBookings($reservations, $reservIDs);

    if (isset($_SERVER['REQUEST_METHOD'])) {
      echo 'ok';
    }

    if (isset($_GET['detail']))
      $this->printResults();
  }

  /**
   * Import ICaledar for the agencies
   */
  public function loadBookings($reservations, $reservIDs) {

    $agencies = [
        1 => 'booking',
        4 => 'airbnb',
    ];


    $alreadyExist = Book::whereIn('external_id', $reservIDs)->pluck('external_id')->toArray();

    foreach ($reservations as $reserv) {
      
      $agency = isset($agencies[$reserv['agency']]) ? $agencies[$reserv['agency']] : 'Zodomus';
      
      if (in_array($reserv['reser_id'], $alreadyExist)){
        $this->result[] = [
          $agency, $reserv['reser_id'],
            'Reserva ya registrada', $reserv['start'], $reserv['end'],'','',
            $reserv['channel_group']
        ];
        continue;
      }


      $roomID = $this->calculateRoomToFastPayment($reserv['channel_group'], $reserv['start'], $reserv['end']);
      if ($roomID<0){
        $this->result[] = [
          $agency, $reserv['reser_id'],'No dispone de Habitaciones para la reserva', $reserv['start'], $reserv['end'],'','',$reserv['channel_group']
        ];
        continue;
      }


      $nights = calcNights($reserv['start'], $reserv['end']);



      $book = new Book();

      //"customer" 
//        firstName-lastName-address-city-zipCode-countryCode-email
//        -phone-phoneCountryCode-phoneCityArea

      
      $rCustomer = $reserv['customer'];
      $customer = new \App\Customers();
      $customer->user_id = 23;
      $customer->name = $rCustomer->firstName . ' ' . $rCustomer->lastName . ' - ' . $agency;
      $customer->email = $rCustomer->email;
      $customer->phone = $rCustomer->phoneCountryCode . ' ' . $rCustomer->phoneCityArea . ' ' . $rCustomer->phone;
      $customer->DNI = "";
      $customer->save();

      //Create Book
      $book->user_id = $this->user_id;
      $book->customer_id = $customer->id;
      $book->room_id = $roomID;
      $book->start = $reserv['start'];
      $book->finish = $reserv['end'];
      $book->comment = $reserv['mealPlan'];
      $book->type_book = 11;
      $book->nigths = $nights;
      $book->agency = $reserv['agency'];
      $book->pax = $reserv['numberOfGuests'];
      $book->PVPAgencia = $reserv['totalPrice'];
      $book->total_price = $reserv['totalPrice'];
      $book->external_id = $reserv['reser_id'];

      $book->save();
      $this->result[] = [
          $agency, $book->external_id, $customer->name, $book->start, $book->finish, $nights,$book->id,$reserv['channel_group']
      ];
    }
  }

  public function calculateRoomToFastPayment($apto, $start, $finish) {

    $roomSelected = null;
    $allRoomsBySize = Rooms::
                    where('channel_group', $apto)
                    ->where('state', 1)
                    ->where('fast_payment', 1)
                    ->orderBy('order_fast_payment', 'ASC')->get();

    foreach ($allRoomsBySize as $room) {
      $room_id = $room->id;
      if (Book::availDate($start, $finish, $room_id)) {
        return $room_id;
      }
    }

    //search simple Rooms to Booking
    $oRoomsGroup = Rooms::select('id')
                    ->where('channel_group', $apto)
                    ->where('state', 1)
                    ->orderBy('fast_payment', 'ASC')
                    ->orderBy('order_fast_payment', 'ASC')->first();
    if ($oRoomsGroup) {
      return $oRoomsGroup->id;
      return ['isFastPayment' => false, 'id' => $oRoomsGroup->id];
    }

    return -1;
//    return ['isFastPayment' => false, 'id' => -1];
  }

  /**
   * Print result in the website
   * 
   * @return type
   */
  function printResults() {

    if (!isset($_SERVER['REQUEST_METHOD']))
      return;

    
    echo '<style>
table {
  border-collapse: collapse;
}

table, td, th {
  border: 1px solid black;
}
table, td, th,td {
 text-align: center;
 }
</style>';
    if (count($this->result)) {
      ?>
      <p>Registros Importados (<?php echo count($this->result); ?>)</p>
      <table class="table text-center">
        <thead>
          <tr>
            <th  style="min-width: 90px;">Agencia</th>
            <th  style="min-width: 90px;">ID Reserva</th>
            <th  style="min-width: 90px;">Cliente</th>
            <th  style="min-width: 90px;">CheckIn</th>
            <th  style="min-width: 90px;">CheckOut</th>
            <th  style="min-width: 90px;">Noches</th>
            <th  style="min-width: 90px;">Reserva Admin</th>
          </tr>
        </thead>
        <tbody>
      <?php foreach ($this->result as $book): ?>
            <tr>
              <td style="height: 5em;"><?php echo $book[0]; ?></td>
              <td><?php echo $book[1]; ?></td>
              <td><?php echo $book[2]; ?></td>
              <td><?php echo $book[3]; ?></td>
              <td><?php echo $book[4]; ?></td>
              <td><?php echo $book[5]; ?></td>
              <td><?php echo $book[6]; ?></td>
            </tr>
              <?php endforeach; ?>
        </tbody>
      </table>
      <?php
    } else {
      ?>
      <p class="alert alert-warning text-center mt-15">No hay registros que importar</p>
          <?php
        }
      }

}
    