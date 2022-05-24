<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\DB;
use App\WobookAvails;
use App\Book;
use App\Services\Wubook\WuBook;
use App\Services\OtaGateway\OtaGateway;

///admin/Wubook/Availables?detail=1
class WubookAvailables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wubook:sendAvaliables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark Avaliables rooms in wubook';
    
    
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
       $this->check_and_send();
    }
    
    private function check_and_send(){
       //clear before dates
    WobookAvails::where('date','<',date('Y-m-d'))->delete();
    
    
    /**********************************/
    $otaGateway = new \App\Services\OtaGateway\Config();
    $otaGatewayRooms = $otaGateway->getRooms();
    $ogAvail = [];
    /*******************************************/
    
  
    
    $list = WobookAvails::orderBy('id','desc')->get();
    $items = [];
    $delIDs = [];
    $already = [];
    if ($list){
      foreach ($list as $v){
        if (!in_array($v->channel_group.$v->date,$already)){
          $already[] = $v->channel_group.$v->date;
          /************   OTA GATEWAY  ***********************/
          if (isset($otaGatewayRooms[$v->channel_group])){
            $og_rID = $otaGatewayRooms[$v->channel_group];
            if (!isset($ogAvail[$og_rID])) $ogAvail[$og_rID] = [];
            $ogAvail[$og_rID][$v->date] = $v->avail;
          }
          /************   OTA GATEWAY  ***********************/
          /*****************************************/
          
        }
        $delIDs[] = $v->id;
      }
      
    }
    /************   OTA GATEWAY  ***********************/
    $OtaGateway = new OtaGateway();
    if (count($ogAvail)>0){
      $OtaGateway->conect();
      $result = $OtaGateway->sendAvailability(['availability'=>$ogAvail]);
      $OtaGateway->disconect();
      if ($OtaGateway->responseCode != 200){
        dd($OtaGateway->response);
      }
    }
    /************   OTA GATEWAY  ***********************/
    /**************************************************/
//    dd($ogAvail,$delDay);
    if (count($delIDs)>0){
        //delete the aux table data
        WobookAvails::whereIn('id',$delIDs)->delete();
    }
    /*************************************************/
    
    }
    
    
  private function getChannels() {
    return \App\Rooms::where('state',1)->groupBy('channel_group')->pluck('channel_group')->toArray();
  }

}
