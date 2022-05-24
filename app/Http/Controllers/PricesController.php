<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Http\Requests;
use App\Services\Zodomus\Config as ZConfig;
use App\Prices;
use App\Models\prepareDefaultPrices;

class PricesController extends AppController {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $oYear = $this->getActiveYear();
    $startYear = new Carbon($oYear->start_date);
    $endYear = new Carbon($oYear->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;
   
    $seasonTemp = \App\Seasons::where('start_date', '>=', $startYear)
            ->where('finish_date', '<=', $endYear)
            ->orderBy('start_date', 'ASC')
            ->get();


    $oSeasonType = \App\TypeSeasons::orderBy('order', 'ASC')->get();
    
    $ch_group = configZodomusAptos();
    $allPrices = [];
    for($i=1; $i<15;$i++){
      foreach($oSeasonType as $season){
        $price = Prices::where('occupation', $i)->where('season', $season->id)->first();
        $aux = [
            'pvp'=>0,
            'cost'=>0,
            'benef'=>0,
        ];
        if ($price){
          $aux['pvp'] = $price->price;
          $aux['cost'] = $price->cost;
          
          if ($price->price > 0 && $price->cost > 0){
            $aux['benef'] = ( 1 - ($price->cost/$price->price) ) * 100;
          }
        }
        $allPrices[$i.'-'.$season->id] = $aux;
      }
    }
    
    $sentData = \App\ProcessedData::findOrCreate('create_baseSeason_'.$oYear->id);
    $sendDataInfo = 'No ha sido enviado aún';
    if ($sentData->content){
      $sentData->content = json_decode($sentData->content);
      $sendDataInfo = 'Enviado el '. convertDateTimeToShow_text($sentData->updated_at);
      $sendDataInfo .= "\n".'Por '.$sentData->content->u;
    }
    
    $sentData = \App\ProcessedData::findOrCreate('send_minStaySeason_'.$oYear->id);
    $sendDataInfo_minStay = 'No ha sido enviado aún';
    if ($sentData->content){
      $sentData->content = json_decode($sentData->content);
      $sendDataInfo_minStay = 'Enviado el '. convertDateTimeToShow_text($sentData->updated_at);
      $sendDataInfo_minStay .= "\n".'Por '.$sentData->content->u;
    }
    
    
    
    $SpecialSegment = \App\SpecialSegment::where('start','>=',$startYear)
                ->where('finish','<=',$endYear)
                ->get();
            
    $oSetting = new \App\Settings();
    $priceExtrPax = \App\Settings::getKeyValue('price_extr_pax');
     
    $dw = listDaysSpanish(true);
    /************************************************************************/
    
    $logMinStays = [];
    $min_stayLogs = \App\LogsData::where('key','min_stay_group')->orderBy('created_at','DESC')->get();
    if ($min_stayLogs){
      $usersNames = \App\User::getUsersNames();
      foreach ($min_stayLogs as $item){
        $dataLog = json_decode($item->long_info);
        $logMinStays[] = [
          'start'    => convertDateToShow_text($dataLog->startDate),
          'end'      => convertDateToShow_text($dataLog->endDate),
          'user'     => isset($usersNames[$dataLog->userID]) ?  $usersNames[$dataLog->userID] : '',
          'min_stay' => $dataLog->min_estancia,  
          'weekDays' => $dataLog->weekDays,  
        ];
      }
    }
    /************************************************************************/
    $oYear = $this->getActiveYear();
    $sRates = new \App\Services\Bookings\RatesRoom();
    $sRates->setDates($oYear);
    $sRates->setSeassonDays();
    
    /************************************************************************/
     
    return view('backend/prices/index', [
        'seasons' => $oSeasonType,
        'newseasons' => $oSeasonType,
        'seasonsTemp' => $seasonTemp,
        'logMinStays' => $logMinStays,
        'newtypeSeasonsTemp' => $oSeasonType,
        'typeSeasonsTemp' => $oSeasonType,
        'year' => $oYear,
        'diff' => $diff,
        'dw' => $dw,
        'startYear' => $startYear,
        'endYear' => $endYear,
        'ch_group' => $ch_group,
        'allPrices' => $allPrices,
        'sendDataInfo' => $sendDataInfo,
        'sendDataInfo_minStay' => $sendDataInfo_minStay,
        'specialSegments' => $SpecialSegment,
        'priceExtrPax' => $priceExtrPax,
        'settingsBooks' => $oSetting->settingsForBooks(),
        'extras'          => \App\Extras::all(),
        'calendar' => $sRates->getCalendar(),
        'sessionTypes' => $sRates->getSessionTypes(),
        'calStyles' => $sRates->getStyles(),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request) {
    $existPrice = \App\Prices::where('occupation', $request->input('occupation'))
                    ->where('season', $request->input('seasson'))->get();

    if (count($existPrice) == 0) {
      $price = new \App\Prices;
      $price->season = $request->input('season');
      $price->occupation = $request->input('occupation');
      $price->price = $request->input('price');
      $price->cost = $request->input('cost');
      if ($price->save()) {
        return redirect()->action('PricesController@index');
      }
    } else {
      return redirect()->action('PricesController@index');
    }
  }

  public function createExtras(Request $request) {
    $existExtra = \App\Extras::where('name', $request->input('name'))->get();

    if (count($existExtra) == 0) {
      $extra = new \App\Extras;
      $extra->name = $request->input('name');
      $extra->price = $request->input('price');
      $extra->cost = $request->input('cost');
      if ($extra->save()) {
        return redirect()->back();
      }
    } else {
      return redirect()->action('SettingsController@index');
    }
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
    
    $data = json_encode([
        'u' =>$cUser->email,
        'ip'=>getUserIpAddr()
    ]);
    $oLog = new \App\LogsData();
    $oLog->infoProceess('OTAs_prices','Se forzó el envío de los precios de la temporada: '.$year->start_date.' al '.$year->end_date,$data);
      
    $prepareDefaultPrices = new prepareDefaultPrices($year->start_date,$year->end_date);
    if ($prepareDefaultPrices->error){
      return back()->with('sent_error',$prepareDefaultPrices->error);
    }
    $prepareDefaultPrices->process();

    //BEGIN wubook
    $oAux = \App\ProcessedData::findOrCreate('wubookRate');
    $oAux->content=time();
    $oAux->save();
    
    return back()->with('sent','Precios cargados para ser enviados');
  }
  
  public function prepareYearMinStay(Request $request) {
    $year = $this->getActiveYear();
    $cUser = \Illuminate\Support\Facades\Auth::user();
    $data = json_encode([
        'u' =>$cUser->email,
        'ip'=>getUserIpAddr()
    ]);
    $oLog = new \App\LogsData();
    $oLog->infoProceess('OTAs_prices','Se forzó el envío de las estancias mínimas de la temporada: '.$year->start_date.' al '.$year->end_date,$data);
      
    
    $prepareMinStay = new \App\Models\PrepareMinStay($year->start_date,$year->end_date);
    if ($prepareMinStay->error){
      return back()->withErrors([$prepareMinStay->error]);
    }
    $prepareMinStay->process();
//    $prepareMinStay->process_justWubook();
    
    return back()->with('sent','Precios cargados para ser enviados');
  }

  
   /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function pricesOTAs() {
    
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    
    $otaConfig = new \App\Services\OtaGateway\Config();
    $agencies = $otaConfig->getAllAgency();
    $rooms = $otaConfig->getRoomsName();
    $prices_ota = \App\Settings::getContent('prices_ota'); 
    if ($prices_ota){
      $prices_ota = unserialize($prices_ota);
    } else {
      $prices_ota = [];
    }
    $aPricesOta = [];
    foreach ($rooms as $k=>$n){
      foreach($agencies as $name=>$id)
        $aPricesOta[$k.$id] = isset($prices_ota[$k.$id]) ? $prices_ota[$k.$id] : ['f'=>0,'p'=>0];
      
      $aPricesOta[$k.'t'] = isset($prices_ota[$k.'t']) ? $prices_ota[$k.'t'] : '';
    }

   
    /************************************************************************/
    $sentData = \App\ProcessedData::findOrCreate('create_baseSeason_'.$year->id);
    $sendDataInfo = 'No ha sido enviado aún';
    if ($sentData->content){
      $sentData->content = json_decode($sentData->content);
      $sendDataInfo = 'Enviado el '. convertDateTimeToShow_text($sentData->updated_at);
      $sendDataInfo .= "\n".'Por '.$sentData->content->u;
    }
    
    return view('backend/prices/pricesOTAs', [
        'aPricesOta' => $aPricesOta,
        'agencies' => $agencies,
        'sendDataInfo' => $sendDataInfo,
        'rooms' => $rooms,
    ]);
  }
  
  public function pricesOTAsUpd(Request $request) {
    
    $otaConfig = new \App\Services\OtaGateway\Config();
    $agencies = $otaConfig->getAllAgency();
    $rooms = $otaConfig->getRoomsName();
    
    
    $prices_ota = null;
    $oSetting = \App\Settings::where('key', 'prices_ota')->first();
    if ($oSetting){
      $prices_ota = $oSetting->content;
    }else{
      $oSetting = new \App\Settings();
      $oSetting->key   = 'prices_ota';
      $oSetting->value = '';
      $oSetting->name  =  "Porcentajes y extras de las OTAs";
    }

    if ($prices_ota){
      $prices_ota = unserialize($prices_ota);
    } else {
      $prices_ota = [];
    }
    
    $aPricesOta = [];
    foreach ($rooms as $k=>$n){
      foreach($agencies as $name=>$id)
        $aPricesOta[$k.$id] = isset($prices_ota[$k.$id]) ? $prices_ota[$k.$id] : ['f'=>0,'p'=>0];
      
      $aPricesOta[$k.'t'] = isset($prices_ota[$k.'t']) ? $prices_ota[$k.'t'] : '';
    }
    
    $ota = $request->input('ota');
    $room = $request->input('room');
    $type = $request->input('type');
//      dd($ota,$type);
    if ($ota == 0 && $type == 't'){
      $key = $room.'t';
      $aPricesOta[$key] = $request->input('val');
    } else {
      $key = $room.$ota;
      if (isset($aPricesOta[$key])){
        $aPricesOta[$key][$type] = intval($request->input('val'));
      }
    }
   
    $oSetting->content = serialize($aPricesOta);
    $oSetting->save();
    
    return response()->json(['status'=>'OK','msg'=>'datos cargados']);
    
  }
  
  function test($apto,$star,$end,$pax){
//    dd(  $apto,$star,$end,$pax);
    $room = \App\Rooms::find($apto);
    $Costo = $room->defaultCostPrice($star, $end, $pax);
    dd($Costo);
  }
}
