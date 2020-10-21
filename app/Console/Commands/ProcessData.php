<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\DB;
use App\Book;
use App\Rooms;
use App\ProcessedData;

///admin/rum/Process-data?detail=1
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
       $this->bookingsWithoutCvc();
       $this->check_overbooking();
//       $this->check_customPricesWubook();
       $this->check_customPricesOtaGateway();
//       $this->check_customMinStayWubook();
       $this->check_customMinStayOtaGateway();
    }
    
    private function check_overbooking(){
      
      $endDate = date('Y-m-d', strtotime('+6 month'));
      $startDate = date('Y-m-d', strtotime('-1 week'));
      $oneDay = 24*60*60;
      $overbooking = [];
      
      
      $rooms = Rooms::select('id')->where('state',1)->get();
      foreach ($rooms as $room){
        
       $booksCount = book::where_type_book_reserved()
                ->where('room_id',$room->id)
                ->where('start','>=',$startDate)
                ->where('start','<=',$endDate)
                ->get();
       
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
      
    }

    function check_customPricesWubook(){
      
      $sentUPD_wubook = \App\ProcessedData::findOrCreate('sentUPD_wubook');
      $dates = json_decode($sentUPD_wubook->content);
      if ($dates){
        
        //increment  1 DAY
        $finish = date('Y-m-d', strtotime('+1 day', strtotime($dates->finish)));
        
        $sendPrices = new \App\Models\prepareDefaultPrices($dates->start,$finish);
        
        $sendPrices->process_justWubook();
        $sentUPD_wubook->content = null;
        $sentUPD_wubook->save();
      }
      
    }
    
    function check_customPricesOtaGateway(){
      
      $sentUPD = \App\ProcessedData::findOrCreate('sentUPD_OtaGateway');
      $dates = json_decode($sentUPD->content);
      if ($dates){
        
        //increment  1 DAY
        $finish = date('Y-m-d', strtotime('+1 day', strtotime($dates->finish)));
        
        $sendPrices = new \App\Models\prepareDefaultPrices($dates->start,$finish);
        if ($sendPrices->error){
          echo $sendPrices->error;
          $sentUPD->content = null;
          $sentUPD->save();
          
        }
       
        $sendPrices->process_OtaGateway();
        $sentUPD->content = null;
        $sentUPD->save();
      }
      
    }
    
     /**
   * 
   * @param Request $request
   * @return type
   */
  public function check_customMinStayWubook() {
    
    $sentUPD_wubook = \App\ProcessedData::findOrCreate('sentUPD_wubook_minStay');
    $dates = json_decode($sentUPD_wubook->content);
    if (!$dates){//No hay registros que enviar
      return null;
    }
    $start    = $dates->start;
    $today    = date('Y-m-d');
    $end      = $dates->finish;
    
    //No se pueden enviar registros anteriores a la fecha actual
    if ($today>$end){
      $sentUPD_wubook->content = null;
      $sentUPD_wubook->save();
      return null;
    }
    
    if ($start<$today) $start = $today;
    
    $oPrepareMinStay = new \App\Models\PrepareMinStay($start, $end);
    $oPrepareMinStay->process_justWubook();
    $sentUPD_wubook->content = null;
    $sentUPD_wubook->save();
  }
     /**
   * 
   * @param Request $request
   * @return type
   */
  public function check_customMinStayOtaGateway() {
    
    $sentUPD = \App\ProcessedData::findOrCreate('sentUPD_OtaGateway_minStay');
    $dates = json_decode($sentUPD->content);
    if (!$dates){//No hay registros que enviar
      return null;
    }
    $start    = $dates->start;
    $today    = date('Y-m-d');
    $end      = $dates->finish;

    //No se pueden enviar registros anteriores a la fecha actual
    if ($today>$end){
      $sentUPD->content = null;
      $sentUPD->save();
      return null;
    }
    
    if ($start<$today) $start = $today;
    
    $oPrepareMinStay = new \App\Models\PrepareMinStay($start, $end);
      
    if ($oPrepareMinStay->error){
      $sentUPD->content = null;
      $sentUPD->save();
    }
    $oPrepareMinStay->process_OtaGateway();
    $sentUPD->content = null;
    $sentUPD->save();
  }
  
  
  public function bookingsWithoutCvc() {
    
    $finish = date('Y-m-d', strtotime('-2 days'));
    $lst = Book::where_type_book_reserved()
            ->whereNotNull('external_id')
            ->join('book_visa','book_id','=','book.id')
            ->whereNull('cvc')
            ->whereNull('cc_number')
            ->where('finish', '>', $finish)
            ->where('agency', '!=', 4)
            ->pluck('book_id');
    $sentUPD = \App\ProcessedData::findOrCreate('bookings_without_Cvc');
    $sentUPD->content = json_encode($lst);
    $sentUPD->save();
  }
}
