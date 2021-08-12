<?php

namespace App\Http\Controllers;

use App\AgentsRooms;
use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SettingsController extends AppController {

  public function index() {
    //Prepare general settings values
    $generalKeys = Settings::getKeysSettingsGen();
    $generalValues = Settings::whereIn('key', array_keys($generalKeys))->get();

    if ($generalValues) {
      foreach ($generalValues as $s) {
        if (isset($generalKeys[$s->key])) {
          $generalKeys[$s->key]['val'] = $s->value;
        }
      }
    }
    //END: Prepare general settings values


    return view('backend/settings/index', [
        'general' => $generalKeys,
        'agentsRooms' => \App\AgentsRooms::all(),
        'specialSegments' => \App\SpecialSegment::orderBy('start', 'ASC')->get(),
        'year' => $this->getActiveYear(),
    ]);
  }

  public function createAgentRoom(Request $request) {

    $agent = AgentsRooms::create([
                "room_id" => $request->input('room_id'),
                "user_id" => $request->input('user_id'),
                "agency_id" => $request->input('agency_id'),
    ]);
    if ($agent) {
      return redirect()->back();
    }
  }

  public function deleteAgentRoom($id) {
    $agent = \App\AgentsRooms::find($id);
    if ($agent->delete()) {
      return redirect()->back();
    }
  }

  public function createUpdateSetting(Request $request) {
    $code = $request->input('code');
    $value = $request->input('value');
    $oSetting = \App\Settings::where('key', $code)->first();

    if (!$oSetting) {
      $oSetting = new \App\Settings();
    }

    $settingsForBooks = $oSetting->settingsForBooks();
    $oSetting->name = $settingsForBooks[$code];
    $oSetting->key = $code;
    $oSetting->value = $value;

    if ($oSetting->save())
      return new Response(\GuzzleHttp\json_encode([
                  'status' => 'OK',
                  'message' => 'Datos Guardados correctamente',
              ]), 200);
    else
      return new Response(\GuzzleHttp\json_encode([
                  'status' => 'KO',
                  'message' => 'Error durante el proceso de guardado, intentelo de nuevo más tarde',
              ]), 200);
  }

  /**
   * Save the general setting
   *
   * @param Request $request
   * @return type
   */
  public function upd_general(Request $request) {

    //Prepare general settings values
    $generalKeys = Settings::getKeysSettingsGen();
    if ($generalKeys) {
      foreach ($generalKeys as $k => $v) {
        $value = $request->input($k, '');
        $obj = Settings::firstOrNew(array('key' => $k));
        $obj->value = $value;
        $obj->save();
      }
    }
    $session_ota_booking = $request->input('session_ota_booking');
    if ($session_ota_booking && trim($session_ota_booking) != '') {
      $url = parse_url($session_ota_booking);
      if (isset($url['query'])) {
        $param = $url['query'];
        setcookie("OTA_BOOKING", $param, time() + 36000);
      }
    }
    return back()->with('success-gral', 'Setting updated!');
  }

  public function updExtraPaxPrice(Request $request) {

    $price = $request->input('price');

    $obj = Settings::where('key', 'price_extr_pax')->first();
    if (!$obj) {
      $obj = new Settings();
      $obj->key = 'price_extr_pax';
    }
    $obj->value = intval($price);
    $obj->save();
    return redirect()->back()->with('success', 'Precio por PAX estras guardado.');
  }

  public function updIva(Request $request) {
    $key = $request->input('key');
    $val = $request->input('val');

    $obj = Settings::where('key', $key)->first();
    if (!$obj) {
      $obj = new Settings();
      $obj->key = $key;
    }
    $obj->value = intval($val);
    if ($obj->save())
      return 'OK';
    return 'error';
  }

  /**
   * Get messages page
   */
  public function messages($key = 'new_request_rva') {
    //get all emial's options
    $settings = Settings::getKeysTxtMails();

    //get from DB all messages
    $data = ['es' => null, 'en' => null, 'es_ota' => null, 'en_ota' => null];
    $content = Settings::where('key', $key)->first();
    if ($content) {
      $data['es'] = $content->content;
    }
    $content = Settings::where('key', $key . '_en')->first();
    if ($content) {
      $data['en'] = $content->content;
    }
    if ($key == 'reservation_state_changed_reserv') {
      $content = Settings::where('key', $key . '_ota')->first();
      if ($content) {
        $data['es_ota'] = $content->content;
      }
      $content = Settings::where('key', $key . '_ota_en')->first();
      if ($content) {
        $data['en_ota'] = $content->content;
      }
    }


    $url_sp = '/admin/settings_msgs/es';
    $url_en = '/admin/settings_msgs/en';

    $kWSP = Settings::getKeysWSP();
    $ckeditor = true;
    if (in_array($key, $kWSP))
      $ckeditor = false;

    include_once app_path('Help/VariablesTxts.php');

    return view('backend/settings/txt-email', [
        'settings' => $settings,
        'data' => $data,
        'lng' => 'es',
        'key' => $key,
        'ckeditor' => $ckeditor,
        'kWSP' => $kWSP,
        'url_en' => $url_en,
        'url_sp' => $url_sp,
        'varsTxt' => $varsTxt,
    ]);
  }

  /**
   * Save the email template setting
   *
   * @param Request $request
   * @return type
   */
  public function messages_upd(Request $request, $lng = 'es') {

    $key = $request->input('key');
    $sNames = Settings::getKeysTxtMails();
    $n = isset($sNames[$key]) ? $sNames[$key] : '';

    $subKeys = ['', '_ota', '_en', '_ota_en'];
    foreach ($subKeys as $k) {
      $key2 = $key . $k;
      $text = $request->input($key2, null);
      if ($text)
        $this->saveTextMails($key2, $n, $text);
    }

    return back()->with('status', 'Setting updated!');
  }

  private function saveTextMails($key, $name, $text) {
    $Object = Settings::where('key', $key)->first();
    if ($Object) {
      $Object->content = $text;
      $Object->save();
    } else {

      $Object = new Settings();
      $Object->key = $key;
      $Object->name = $name;
      $Object->value = 0;
      $Object->content = $text;
      $Object->save();
    }
  }

  /**
   * /test-text/es/text_payment_link
   * @param type $lng
   */
  function testText($lng='es',$key=null,$ota=null){
    
    $settings = Settings::getKeysTxtMails();
    $name = isset($settings[$key]) ? $settings[$key] : $key;
   
    $keyFind = $key;
    if($ota) $keyFind .= '_ota';
//    if($lng == 'en') $keyFind .= '_en';
   
    //---------------------------------------------------------// 
    $data = [];
    if ($key){
      $data[$key] = ['es' => '', 'en'=>''];
      
      $keysValue = Settings::where('key', $keyFind)->get();
      foreach ($keysValue as $item) {
        $data[$key]['es'] = $item->content;
      }
      $keysValue = Settings::where('key', $keyFind.'_en')->get();
      foreach ($keysValue as $item) {
        $data[$key]['en'] = $item->content;
      }
    } else {
      foreach ($settings as $k1=>$v1)
      $keysValue = Settings::whereIn('key', array_keys($settings))->get();
      foreach ($keysValue as $item) {
        $data[$item->key]['es'] = $item->content;
      }
      
      $keysSett = [];
      foreach ($settings as $k=>$v) $keysSett[] = $k.'_en';
      $keysValue = Settings::whereIn('key', $keysSett)->get();
      foreach ($keysValue as $item) {
        $kSetting = str_replace('_en','',$item->key);
        $data[$kSetting]['en'] = $item->content;
      }
    }
    //---------------------------------------------------------// 
    include_once app_path('Help/VariablesTxts.php');
    
        
    $kWSP = Settings::getKeysWSP();
    $wsp = false;
    if ( in_array($key,$kWSP)) $wsp = true;
    
    return view('backend/settings/test-txt-email', [
        'data' => $data,
        'lng' => $lng,
        'key' => $key,
        'ota' => $ota,
        'wsp' => $wsp,
        'name' => $name,
        'varsTxt'=>$varsTxt,
        'settings' => $settings,
    ]);
  }
}
