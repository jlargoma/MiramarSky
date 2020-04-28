<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\DB;
use App\WobookAvails;
use App\Book;
use App\Services\Wubook\WuBook;

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
    
    $list = WobookAvails::orderBy('id','desc')->get();
    $items = [];
    $delIDs = [];
    $already = [];
    if ($list){
      foreach ($list as $v){
        if (!in_array($v->channel_group.$v->date,$already)){
          $already[] = $v->channel_group.$v->date;
          if (!isset($items[$v->channel_group])) $items[$v->channel_group] = [];
          $items[$v->channel_group][] = [
            'avail'=> $v->avail,
            'date'=> convertDateToShow($v->date,true)
            ];
        }
        if (!isset($delIDs[$v->channel_group])) $delIDs[$v->channel_group] = [];
        $delIDs[$v->channel_group][] = $v->id;
      }
      
    }

    $WuBook = new WuBook();
    //Get the Channel -> Wubook rooms ID

    $lstChannels = $this->getChannels();
    
    $roomdays = [];
    $delDay = [];
    if (count($lstChannels)>0){ //the Site has channels

      $rIDs = $WuBook->getRoomsEquivalent($lstChannels);
      if (count($rIDs)){ //the channels has a WuBook's room
        foreach ($rIDs as $ch=>$rid){
          if (isset($items[$ch]))
            $roomdays[] = ['id'=> $rid, 'days'=> $items[$ch]];
          if (isset($delIDs[$ch])){
            foreach ($delIDs[$ch] as $d) $delDay[] = $d;
          }
            
        }
      }
    }
//    dd($roomdays,$delDay);
    if (count($roomdays)>0){
      $WuBook->conect();
      if ($WuBook->set_Closes($roomdays)){
        //delete the aux table data
        WobookAvails::whereIn('id',$delIDs)->delete();
      }
      $WuBook->disconect();
      
    }
    /*************************************************/
    
    }
    
    
  private function getChannels() {
    return \App\Rooms::where('state',1)->groupBy('channel_group')->pluck('channel_group')->toArray();
  }

}
