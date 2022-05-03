<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\DB;
use App\Services\OtaGateway\OtaGateway;
use App\Services\OtaGateway\Config as oConfig;
use App\Rooms;
use App\SizeRooms;
use App\Book;
use \App\Traits\Bookings\LoadByOTA;

/// /admin/ota-gateway/import?detail=1
// http://miramarski.virtual/admin/ota-gateway/import?detail=1
class OG_ImportAll extends Command {
  use LoadByOTA;
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
  private $user_id = 11;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'OGImportAll:import';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import Booking from OTA GATEWAY';

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
  var $sOta = array();

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    $this->result = array();
    $this->sOta = new OtaGateway();
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $OtaGateway = $this->sOta;
    $oConfig = new oConfig();
   
//    include_once dirname(dirname(dirname(dirname(__FILE__)))) . '/public/tests/ota-bookings.php';
//    $oBookings = \json_decode($bookings);
   
    $start = date('Y-m-d', strtotime(' -2 days'));
    $end = date('Y-m-d', strtotime(' +1 days'));
    $OtaGateway->conect();
    $oBookings = $OtaGateway->getBookings($start,$end);
    if (!$oBookings) return null;
    if (count($oBookings->bookings)==0) return null;
          
    $oBookings = $oBookings->bookings;
    $this->loadBooking($oBookings);
    $OtaGateway->disconect();
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
            <th  style="min-width: 90px;">Estado</th>
            <th  style="min-width: 90px;">Aptos</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($this->result as $book): 
            $status = 'New';
            if ($book['status'] == 2) $status = 'cancel';
            if ($book['status'] == 3) $status = 'update';
          ?>
            <tr>
              <td style="height: 5em;"><?php echo $book['channel']; ?></td>
              <td><?php echo $book['bkg_number'].'<br>'.$book['external_roomId'].'<br>'.$book['reser_id']; ?></td>
              <td><?php echo $book['customer_name'].'<br>'.$book['customer_email']; ?></td>
              <td><?php echo $book['start']; ?></td>
              <td><?php echo $book['end']; ?></td>
              <td><?php echo $status; ?></td>
              <td><?php echo $book['channel_group']; ?></td>
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
