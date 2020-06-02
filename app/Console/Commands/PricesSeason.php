<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\DB;
use App\Services\Wubook\WuBook;
use App\Services\Zodomus\Zodomus;
use App\ProcessedData;

///admin/Wubook/Availables?detail=1
class PricesSeason extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'OTAs:sendPricesSeason';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send prices by season to Zodomus and Wubook';
    
    
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
       $this->check_and_send_zodumos();
       $this->check_and_send_wubooks();
    }
    
    private function check_and_send_zodumos(){
      
      $oZodomus = new Zodomus();
      $items = ProcessedData::where('key','SendToZoodomus')->limit(45)->get();
      if ($items){
        foreach ($items as $item){
          $data = json_decode($item->content,true);
          $errorMsg = $oZodomus->setRates($data[0],$data[1]);
          if ($errorMsg){
            $item->name = $errorMsg;
            $item->key = 'SendToZoodomus-error';
            $item->save();
          } else {
            $item->delete();
          }
        }
      }
    }
    
    private function check_and_send_wubooks(){
      $WuBook = new WuBook();

      $items = ProcessedData::where('key','SendToWubook')->limit(15)->get();

      if (count($items)>0){
        $WuBook->conect();
        foreach ($items as $item){
          $data = json_decode($item->content,true);
          $response = $WuBook->set_Prices($data['start'],$data['prices']);
          if ($response) { $item->delete();}
          else {
            $item->key = 'SendToWubook-error';
            $item->save();
          }
        }
        $WuBook->disconect();
      }
    }
    
}
