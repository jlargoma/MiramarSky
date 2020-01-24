<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\DB;
use App\WubookQueues;
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
      $items = WubookQueues::where('sent',0)->get();
      $roomIDs = [];
      $roomKs = [];
      $start = $finish = null;
      foreach ($items as $item){
        $roomIDs[$item->rId_wubook] = $item->id_room;
        if (!$start || $start>$item->date_start) $start = $item->date_start;
        if (!$finish || $finish<$item->date_end) $finish = $item->date_end;
        
        
      }
      
      /** @toDo Si es un Apto de grupos, agrego los Aptos asociados */
      
      $booksReserv = Book::get_type_book_reserved();
      $rooms = [];
      $oneDay = 24*60*60;
      //find availibility
      $bookings = Book::whereIn('room_id',$roomIDs)
              ->where('start','>=',$start)
              ->where('finish','<=',$finish)
              ->get();
      
      if ($bookings){
        foreach ($bookings as $b){
          if(!isset($rooms[$b->room_id])) $rooms[$b->room_id] = [];

          $avail = 0;
          if (in_array($b->type_book,$booksReserv)) $avail = 1;
          $date = strtotime($b->start);
          $date_end = strtotime($b->finish);
          while ($date<=$date_end){
            $rooms[$b->room_id][$date] = $avail;
            $date +=$oneDay;
          }
        }
      }
     
//      ['id'=> 433743, 'days'=> [['avail'=> 1], ['avail'=> 1], ['avail'=> 1]]],
      $data = [];
      if ($rooms){
        foreach ($rooms as $k=>$values){
          
          $aux = [$k];
          $aux_time = strtotime($start);
          $aux_timeEnd = strtotime($finish);
           var_dump($values);
          foreach ($values as $time=>$avail){
            while($aux_time<$time){
              $aux[date('d/m',$aux_time)] = '';//[];
              $aux_time +=$oneDay;
            }
//            echo var_dump($time); die;
            $aux[date('d/m',$time)] = $avail;//[];
//            $aux[$aux_time] = ['avail'=> $avail];
            $aux_time +=$oneDay;
            
          }
          
//          while($aux_time<=$aux_timeEnd){
//            $aux[date('d/m',$aux_time)] = '';//[];
//            $aux_time +=$oneDay;
//          }
//          dd($aux,$start,$finish);
           var_dump($aux); 
          $rId_wubook = array_search($k,$roomIDs);
          $data[] =  ['id'=> $k, 'days'=> $aux];
     
        }
      }
      
      die;
      
      foreach ($data as $d){
        var_dump($d['days']);
//        echo $d['id'].' -> '.count($d['days']).'<br/>';
      }
      die;
      $wubook = new WuBook();
      $wubook->set_Closes($start,$data);
      var_dump($data); die;

      
      
      
      
      
      
      
      dd($rooms);
    }

}
