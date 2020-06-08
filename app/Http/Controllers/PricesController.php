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
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;
   
    $seasonTemp = \App\Seasons::where('start_date', '>=', $startYear)
            ->where('finish_date', '<=', $endYear)
            ->orderBy('start_date', 'ASC')
            ->get();


    $oSeasonType = \App\TypeSeasons::orderBy('order', 'ASC')->get();
    
    $aptos = configZodomusAptos();
    $ch_group = [];
    foreach ($aptos as $k=>$v){
      $ch_group[$k] = $v->name;
    }
        
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
    
    $sentData = \App\ProcessedData::findOrCreate('create_baseSeason_'.$year->id);
    $sendDataInfo = 'No ha sido enviado aún';
    if ($sentData->content){
      $sentData->content = json_decode($sentData->content);
      
      $sendDataInfo = 'Enviado el '. convertDateTimeToShow_text($sentData->updated_at);
      $sendDataInfo .= "\n".'Por '.$sentData->content->u;
    }
    
    $SpecialSegment = \App\SpecialSegment::where('start','>=',$startYear)
                ->where('finish','<=',$endYear)
                ->get();
            
    $oSetting = new \App\Settings();
    $priceExtrPax = \App\Settings::getKeyValue('price_extr_pax');
     
    return view('backend/prices/index', [
        'seasons' => $oSeasonType,
        'newseasons' => $oSeasonType,
        'seasonsTemp' => $seasonTemp,
        'newtypeSeasonsTemp' => $oSeasonType,
        'typeSeasonsTemp' => $oSeasonType,
        'year' => $year,
        'diff' => $diff,
        'startYear' => $startYear,
        'endYear' => $endYear,
        'ch_group' => $ch_group,
        'allPrices' => $allPrices,
        'sendDataInfo' => $sendDataInfo,
        'specialSegments' => $SpecialSegment,
        'priceExtrPax' => $priceExtrPax,
        'settingsBooks' => $oSetting->settingsForBooks(),
        'extras'          => \App\Extras::all(),
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
  public function prepareYearPricesAndMinStay(Request $request) {
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

}
