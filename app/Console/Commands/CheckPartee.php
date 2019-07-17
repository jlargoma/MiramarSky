<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\BookPartee;

class CheckPartee extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'partee:check';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Check partee checkin status';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $this->checkInStatus();
  }

  /**
   * Check the Partee HUESPEDES completed
   */
  public function checkInStatus() {
    $apiPartee = new \App\Services\ParteeService();
    
    //conect to Partee and get the JWT
    if ($apiPartee->conect()) {

      //List the no-complete partee
      $listBookPartee = BookPartee::where('partee_id','>',0)
              ->whereNotIn('status', ['HUESPEDES', 'FINALIZADO'])
              ->get();

      foreach ($listBookPartee as $BookPartee) {
        //Read a $BookPartee            
        try {

          $partee_id = $BookPartee->partee_id;
          //check Partee status
          $result = $apiPartee->getCheckStatus($partee_id);
          if ($result) {
            
            //Save the new status
            $log = $BookPartee->log_data . "," . time() . '-' . $apiPartee->response->status;
            $BookPartee->status = $apiPartee->response->status;
            $BookPartee->log_data = $log;
            $BookPartee->guestNumber = $apiPartee->response->guestNumber;
            $BookPartee->save();

          } else {
            
            $log = $BookPartee->log_data . "," . time() . '-' . $apiPartee->response;
            Log::error($log);
            $BookPartee->status = 'error';
            $BookPartee->log_data = $log;
            $BookPartee->save();
            
          }
        } catch (\Exception $e) {
          Log::error("Error CheckIn Partee " . $BookPartee->id . ". Error  message => " . $e->getMessage());
          echo $e->getMessage();
          continue;
        }
      }
    } else {
      //Can't conect to partee
      Log::error("Error Conect Partee " . $apiPartee->response);
      echo $apiPartee->response;
    }
  }

}
