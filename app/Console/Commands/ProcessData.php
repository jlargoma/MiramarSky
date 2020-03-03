<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\DB;
use App\Book;
use App\Rooms;
use App\ProcessedData;

///admin/Wubook/Availables?detail=1
class ProcessData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ProcessData:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';
    
    
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
    public function __construct()
    {
        $this->result = array();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $this->check_overbooking();
    }
    
    private function check_overbooking(){
      
      $endDate = date('Y-m-d', strtotime('+6 month'));
      $startDate = date('Y-m-d', strtotime('-1 week'));
      $oneDay = 24*60*60;
      $overbooking = [];
      
      
      $rooms = Rooms::select('id')->where('state',1)->get();
      foreach ($rooms as $room){
        
        $booksCount = book::where_type_book_reserved()->where('room_id',$room->id)->get();
        $aLstDays = [];
        foreach ($booksCount as $b){


          //Prepara la disponibilidad por día de la reserva
          $startAux = strtotime($b->start);
          $endAux = strtotime($b->finish);

          while ($startAux<$endAux){
            $aLstDays[date('Y-m-d',$startAux)][] = $b->id;
            $startAux+=$oneDay;
          }

        }
        
        foreach ($aLstDays as $d=>$v){
          if (count($v)>1){
            foreach ($v as $bID){
              $overbooking[] = $bID;
            }
          }
        }
      
        
      }
         
      
      
      $overbooking = array_unique($overbooking);
      
      $oData = ProcessedData::findOrCreate('overbooking');
      $oData->content = json_encode($overbooking);
      $oData->save();
      dd($overbooking);
    }

}
