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
    public function messages($lng='es')
    {
        //get all emial's options
        $settings = Settings::getKeysTxtMails($lng);
        //get from DB all messages
        $keysValue = Settings::whereIn('key', array_keys($settings))->get();
        $data      = [];
        if ($keysValue)
        {
            foreach ($keysValue as $item)
            {
                $data[$item->key] = $item->content;
            }
        }

        return view('backend/settings/txt-email', [
            'settings' => $settings,
            'data'     => $data,
            'lng'         => $lng
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
            
            if ($key == 'reservation_state_changed_reserv'){
              
              $key = 'reservation_state_changed_reserv_ota';
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
