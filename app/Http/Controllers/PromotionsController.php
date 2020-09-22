<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Http\Requests;
use App\Services\Zodomus\Config as ZConfig;
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
    
    $aptos = configZodomusAptos();
    $ch_group = [];
    foreach ($aptos as $k=>$v){
      $ch_group[$k] = $v->name;
    }
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
          'value' => $item->value
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
    
    $oneDay = 24*60*60;
    $startAux = strtotime($start);
    $endAux = strtotime($finish);
    $days = [];
    while ($startAux<$endAux){
      $dateAux= date('Y-m-d',$startAux);
      $days[$dateAux] = (in_array($dateAux, $exceptions)) ? 0 : 1;
      $startAux+=$oneDay;
    }
    
    $oPromotion = new Promotions();
    $oPromotion->start  = $start;
    $oPromotion->finish = $finish;
    $oPromotion->value = $data['discount'];
    $oPromotion->rooms = serialize($data['ch_group']);
    $oPromotion->days  = serialize($days);
    $oPromotion->exceptions  = serialize($exceptions);
    $oPromotion->save();

    return redirect()->back();
    
  }

  public function delteExtraPrices(Request $request) {

      $id = $request->input('id');
      $extra = \App\Extras::find($id);
      if ($extra->id == $id){
        $extra->deleted = 1;
        if ($extra->save()) return "OK";
      }
      return 'Extra no encontrado';
  }
  
   /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  int                      $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request) {
    $key = explode('-',  $request->input('id'));
    if (!is_array($key) || count($key) != 2 ){
      return ('ops, algo salió mal.');
    }
    $oPrice = Prices::findOrCreate($key[0],$key[1]);
    $oPrice->price = $request->input('price');
    $oPrice->cost = $request->input('cost');

    if ($oPrice->save()) return 'OK';
    
     return ('ops, algo salió mal.');
  }

  public function updateExtra(Request $request) {
    $id = $request->input('id');
    $extraUpdate = \App\Extras::find($id);

    $extraUpdate->price = $request->input('extraprice');
    $extraUpdate->cost = $request->input('extracost');

    if ($extraUpdate->save()) {
      echo "Cambiada!!";
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function delete($id) {
    $price = \App\Prices::find($id);
    if ($price->delete()) {
      return redirect()->action('PricesController@index');
    }
  }
  
  /*******************************************/
  /*******************************************/
  public function prepareYearPrices(Request $request) {
    $year = $this->getActiveYear();
    $cUser = \Illuminate\Support\Facades\Auth::user();
    // Sólo lo puede ver jorge
    if ($cUser->email != "jlargo@mksport.es"){
      return redirect('no-allowed');
    }
    
    $store = \App\ProcessedData::findOrCreate('create_baseSeason_'.$year->id);
    $store->content = json_encode([
        'u' =>$cUser->email,
        'ip'=>getUserIpAddr()
    ]);
    $store->save();
    
    $prepareDefaultPrices = new prepareDefaultPrices($year->start_date,$year->end_date);
    if ($prepareDefaultPrices->error){
      return back()->with('sent_error',$prepareDefaultPrices->error);
    }
    $prepareDefaultPrices->process();
    
    return back()->with('sent','Precios cargados para ser enviados');
  }
  
  public function prepareYearMinStay(Request $request) {
    $year = $this->getActiveYear();
    $cUser = \Illuminate\Support\Facades\Auth::user();
    // Sólo lo puede ver jorge
//    if ($cUser->email != "jlargo@mksport.es"){
//      return redirect('no-allowed');
//    }
//    
    $store = \App\ProcessedData::findOrCreate('send_minStaySeason_'.$year->id);
    $store->content = json_encode([
        'u' =>$cUser->email,
        'ip'=>getUserIpAddr()
    ]);
    $store->save();
    
    $prepareMinStay = new \App\Models\PrepareMinStay($year->start_date,$year->end_date);
    if ($prepareMinStay->error){
      return back()->withErrors([$prepareMinStay->error]);
    }
    $prepareMinStay->process();
//    $prepareMinStay->process_justWubook();
    
    return back()->with('sent','Precios cargados para ser enviados');
  }

}
