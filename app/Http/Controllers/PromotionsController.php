<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Http\Requests;
use App\Services\OtaGateway\Config as oConfig;
use App\Promotions;

class PromotionsController extends AppController {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {

    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    $oConfig = new oConfig();
    $ch_group = $oConfig->getRoomsName();
    
    /***********************************************************************/
    $oPromotions = Promotions::where('start', '>=', $startYear)
            ->where('finish', '<=', $endYear)
            ->orderBy('start', 'ASC')
            ->get();
        
    $lstPromotions = [];
    if ($oPromotions){
      foreach ($oPromotions as $item){
        /**************/
        $lstRooms = [];
        $rooms = unserialize($item->rooms);
        if ($rooms)
          foreach ($rooms as $r){
            if (isset($ch_group[$r]))
              $lstRooms[] = $ch_group[$r];
          }
        /**************/
        $lstExcepts = [];
        $exceptions = unserialize($item->exceptions);
        if ($exceptions)
          foreach ($exceptions as $e){
           $lstExcepts[] = convertDateToShow_text($e);
          }
        /**************/
        $lstPromotions[] = [
          'start' =>convertDateToShow_text($item->start,true),
          'finish' =>convertDateToShow_text($item->finish,true),
          'rooms' => $lstRooms,
          'except' => $lstExcepts,
          'value' => $item->value,
          'id' => $item->id
        ];

      }
    }
    /***********************************************************************/
     
    return view('backend/prices/promotions', [
        'ch_group' => $ch_group,
        'lstPromotions' =>$lstPromotions
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function getItem($id) {
    /***********************************************************************/
    $item = Promotions::find($id);
    if ($item){
        /**************/
        $rooms = unserialize($item->rooms);
        if (!$rooms || !is_array($rooms)) $rooms = [];
        /**************/
        $exceptions = unserialize($item->exceptions);
        if (!$exceptions || !is_array($exceptions)) $exceptions = [];
        for($i=0; $i< count($exceptions); $i++){
          $exceptions[$i] = convertDateToShow($exceptions[$i],true);
        }
        /**************/
        return response()->json([
          'start' =>convertDateToShow($item->start,true),
          'finish' =>convertDateToShow($item->finish,true),
          'rooms' => $rooms,
          'except' => $exceptions,
          'value' => $item->value
        ]);
      }
      return 'not_found';
  }
  
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request) {
    
    $data = $request->all();
    
    $aRange = explode(' - ', $data['range']);
    $start  = convertDateToDB($aRange[0]);
    $finish  = convertDateToDB($aRange[1]);
    
    $exceptions = [];
    foreach ($data as $k=>$v){
      if (preg_match('/^date/', $k)){
        $exceptions[] = convertDateToDB($v);
      }
    }
    
    $chGroupSel = [];
    $oConfig = new oConfig();
    $ch_group = $oConfig->getRoomsName();
    foreach ($ch_group as $k=>$name) 
      if (isset ($data['apto'.$k])) $chGroupSel[] = $k;
    
      
    $oneDay = 24*60*60;
    $startAux = strtotime($start);
    $endAux = strtotime($finish);
    $days = [];
    while ($startAux<$endAux){
      $dateAux= date('Y-m-d',$startAux);
      $days[$dateAux] = (in_array($dateAux, $exceptions)) ? 0 : 1;
      $startAux+=$oneDay;
    }
    
    $oPromotion = null;
    if (isset($data['itemID']) && $data['itemID'])
      $oPromotion = Promotions::find($data['itemID']);
    if (!$oPromotion)  $oPromotion = new Promotions();
    
    $oPromotion->start  = $start;
    $oPromotion->finish = $finish;
    $oPromotion->value = $data['discount'];
    $oPromotion->rooms = serialize($chGroupSel);
    $oPromotion->days  = serialize($days);
    $oPromotion->exceptions  = serialize($exceptions);
    $oPromotion->save();

    return redirect()->back();
    
  }

  function delete(Request $request){
    $oPromotion = Promotions::find($request->input('id'));
    if ($oPromotion->delete()) return 'OK';
    
    return 'error';
  }

}
