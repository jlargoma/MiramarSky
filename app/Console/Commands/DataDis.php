<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

use App\Years;
use App\Services\LogsService;
use App\Services\DataDis\Api;
use App\Services\DataDis\Config as oConfig;
use App\RoomsElectricity;

class DataDis extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'DataDis:load';

  private $oConfig = null;

  private $sLog;

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    $this->sLog = new LogsService('schedule','RoomsElectricity');
    $this->oConfig = new oConfig();
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $dataDis = new Api();
    if ($dataDis->conect()){
      $date = date('Y-m-d', strtotime('-2 days'));
      
      $startDate = date('Y/m/d', strtotime('-3 days'));
      $endDate = date('Y/m/d');
//      $startDate = '2021/10/07';
//      $endDate = '2021/10/28';
      
      $aRooms = $this->oConfig->getRooms();
      foreach ($aRooms as $rID => $rData){
        
        $param = [
            'cups'=>[$rData['cups']],
            'distributor' => $rData['distributorCode'],
            'fechaInicial' => $startDate,
            'fechaFinal' => $endDate,
            'tipoPuntoMedida' => $rData['pointType'],
        ];
        
        //"semanaDia": "2021/10/10", "consumo": 0,
        $result = $dataDis->getTimeCurveData($param);
        if($result){
          foreach ($result as $r){
            if (isset($r->semanaDia) && count($r->semanaDia)>0){
              foreach ($r->semanaDia as $d){
                $day = str_replace('/','-',$d->semanaDia);
                $oObj = RoomsElectricity::where('room_id',$rID)
                        ->where('day',$day)->first();
                if (!$oObj){
                  $oObj = new RoomsElectricity();
                  $oObj->room_id = $rID;
                  $oObj->day = $day;
                }
                $oObj->consumption = $d->consumo;
                $oObj->save();
              }
            }
          }
        }
      }
    }
  
  }
 
}
