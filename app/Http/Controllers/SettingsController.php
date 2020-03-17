<?php

namespace App\Http\Controllers;

use App\AgentsRooms;
use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SettingsController extends AppController
{
    /*
     * key basics to get price of book and pay it
     * */
    const PARK_COST_SETTING_CODE      = "parking_book_cost";
    const PARK_PVP_SETTING_CODE       = "parking_book_price";
    const LUXURY_COST_SETTING_CODE    = "luxury_book_cost";
    const LUXURY_PVP_SETTING_CODE     = "luxury_book_price";
    const DISCOUNT_BOOKS_SETTING_CODE = "discount_books";


    private $settingsForBooks = [
        self::PARK_COST_SETTING_CODE   => 'Cost Sup Park',
        self::PARK_PVP_SETTING_CODE    => 'PVP Sup Park',
        self::LUXURY_COST_SETTING_CODE => 'Cost Sup Lujo',
        self::LUXURY_PVP_SETTING_CODE  => 'PVP Sup Lujo',
        self::DISCOUNT_BOOKS_SETTING_CODE  => 'Descuento directo sobre las reservas ',
        //'book_instant_payment',
    ];

    public function index()
    {
        //Prepare general settings values
        $generalKeys   = Settings::getKeysSettingsGen();
        $generalValues = Settings::whereIn('key', array_keys($generalKeys))->get();

        if ($generalValues)
        {
            foreach ($generalValues as $s)
            {
                if (isset($generalKeys[$s->key]))
                {
                    $generalKeys[$s->key]['val'] = $s->value;
                }
            }
        }
        //END: Prepare general settings values


        return view('backend/settings/index', [
            'general'         => $generalKeys,
            'extras'          => \App\Extras::all(),
            'settingsBooks'   => $this->settingsForBooks,
            'agentsRooms'     => \App\AgentsRooms::all(),
            'specialSegments' => \App\SpecialSegment::orderBy('start', 'ASC')->get(),
            'year'            => $this->getActiveYear(),
        ]);
    }

    public function createAgentRoom(Request $request)
    {

        $agent = AgentsRooms::create([
                                         "room_id"   => $request->input('room_id'),
                                         "user_id"   => $request->input('user_id'),
                                         "agency_id" => $request->input('agency_id'),

                                     ]);
        if ($agent)
        {
            return redirect()->back();
        }
    }

    public function deleteAgentRoom($id)
    {
        $agent = \App\AgentsRooms::find($id);
        if ($agent->delete())
        {
            return redirect()->back();
        }
    }

    public function createUpdateSetting(Request $request)
    {
        $code         = $request->input('code');
        $value        = $request->input('value');
        $issetSetting = \App\Settings::where('key', $code)->first();

        if ($issetSetting)
        {
            $setting = $issetSetting;
        } else
        {
            $setting = new \App\Settings();
        }

        $setting->name  = $this->settingsForBooks[$code];
        $setting->key   = $code;
        $setting->value = $value;

        if ($setting->save()) return new Response(\GuzzleHttp\json_encode([
                                                                              'status'  => 'OK',
                                                                              'message' => 'Datos Guardados correctamente',
                                                                          ]), 200); else
            return new Response(\GuzzleHttp\json_encode([
                                                            'status'  => 'KO',
                                                            'message' => 'Error durante el proceso de guardado, intentelo de nuevo más tarde',
                                                        ]), 200);
    }

    /**
   * Get messages page
   */
  public function messages($lng = 'es',$key=null) {
    //get all emial's options
    $settings = Settings::getKeysTxtMails($lng);
    /**
     * INSERT INTO settings(`name`,`key`,`content`,value,site_id) SELECT `name`,`key`,`content`,value,'3' as site_id FROM `settings`  WHERE `key` in ("new_request_rva","reservation_state_changed_reserv","reservation_state_changed_confirm","reservation_state_changed_cancel","reserva-propietario","second_payment_reminder","second_payment_confirm","Forfait_email_payment_request","Forfait_email_confirmation_payment","fianza_request_deferred","fianza_confirm_deferred","SMS_forfait","SMS_fianza","SMS_Partee_msg","SMS_Partee_upload_dni")
     */
    //get from DB all messages
    $keysValue = Settings::whereIn('key', array_keys($settings))->get();
    $data = [];
    if ($keysValue) {
      foreach ($keysValue as $item) {
        $data[$item->key] = $item->content;
      }
    }
    
    $url_sp = '/admin/settings_msgs/es';
    $url_en = '/admin/settings_msgs/en';
    if ($key){
      $url_en .= '/'.$key.'_en';
      $rest = substr($key, -3);
      if ($rest == '_en'){
        $url_sp .= '/'.substr($key,0, (strlen($key)-3));
      } else {
        $url_sp .= '/'.$key;
      }
    }
    return view('backend/settings/txt-email', [
        'settings' => $settings,
        'data' => $data,
        'lng' => $lng,
        'key' => $key,
        'url_en' => $url_en,
        'url_sp' => $url_sp,
    ]);
  }

    /**
     * Save the email template setting
     *
     * @param Request $request
     * @return type
     */
    public function messages_upd(Request $request,$lng='es')
    {

        $settings = Settings::getKeysTxtMails($lng);
        $key      = $request->input('key');

        //key controll
        if ($key && isset($settings[$key]))
        {

            $value  = $request->input($key, null);
            $Object = Settings::where('key', $key)->first();

            if ($Object)
            {

                $Object->content = $value;
                $Object->save();

            } else
            {

                $Object          = new Settings();
                $Object->key     = $key;
                $Object->name    = $settings[$key];
                $Object->value   = 0;
                $Object->content = $value;
                $Object->save();

            }
            
            $reKey = null;
            if ($key == 'reservation_state_changed_reserv') $reKey = 'reservation_state_changed_reserv_ota';
            if($key == 'reservation_state_changed_reserv_en') $reKey = 'reservation_state_changed_reserv_ota_en';
            if ($reKey){
              $value = $request->input($reKey, null);
              $Object = Settings::where('key', $reKey)->first();
              if ($Object) {

                $Object->content = $value;
                $Object->save();
              } else {

                $Object = new Settings();
                $Object->key = $reKey;
                $Object->name = $settings[$reKey];
                $Object->value = 0;
                $Object->content = $value;
                $Object->save();
              }

            }
        }
        return back()->with('status', 'Setting updated!');
    }

    /**
     * Save the general setting
     *
     * @param Request $request
     * @return type
     */
    public function upd_general(Request $request)
    {

        //Prepare general settings values
        $generalKeys = Settings::getKeysSettingsGen();
        if ($generalKeys)
        {
            foreach ($generalKeys as $k => $v)
            {
                $value      = $request->input($k, '');
                $obj        = Settings::firstOrNew(array('key' => $k));
                $obj->value = $value;
                $obj->save();
            }
        }
        return back()->with('success-gral', 'Setting updated!');
    }
}
