<?php

namespace App\Http\Controllers;

use App\AgentsRooms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use App\Settings;

class SettingsController extends AppController
{
	/*
	 * key basics to get price of book and pay it
	 * */
	const PARK_COST_SETTING_CODE   = "parking_book_cost";
	const PARK_PVP_SETTING_CODE    = "parking_book_price";
	const LUXURY_COST_SETTING_CODE = "luxury_book_cost";
	const LUXURY_PVP_SETTING_CODE  = "luxury_book_price";


	private $settingsForBooks = [
		self::PARK_COST_SETTING_CODE   => 'Cost Sup Park',
		self::PARK_PVP_SETTING_CODE    => 'PVP Sup Park',
		self::LUXURY_COST_SETTING_CODE => 'Cost Sup Lujo',
		self::LUXURY_PVP_SETTING_CODE  => 'PVP Sup Lujo',
		//'book_instant_payment',
	];

	public function index()
	{
		return view('backend/settings/index', [
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

		if ($setting->save())
			return new Response(\GuzzleHttp\json_encode([
				                                            'status'  => 'OK',
				                                            'message' => 'Datos Guardados correctamente',
			                                            ]), 200);
		else
			return new Response(\GuzzleHttp\json_encode([
				                                            'status'  => 'KO',
				                                            'message' => 'Error durante el proceso de guardado, intentelo de nuevo más tarde',
			                                            ]), 200);
	}
        
        /**
         * Get messages page
         */
        public function messages() {
          //get all emial's options
          $settings = Settings::getKeysTxtMails();
          //get from DB all messages
          $keysValue = Settings::whereIn('key',array_keys($settings))->get();
          $data = [];
          if ($keysValue){
            foreach ($keysValue as $item){
              $data[$item->key] = $item->content;
            }
          }
          
          return view('backend/settings/txt-email', [
                  'settings'  => $settings,
                  'data' =>$data
                  ]);
        }
        
        /**
         * Save the email template setting
         * 
         * @param Request $request
         * @return type
         */
        public function messages_upd(Request $request) {
          
          $settings = Settings::getKeysTxtMails();
          $key = $request->input('key');
          
          //key controll
          if ($key && isset($settings[$key])){
            
            $value  = $request->input($key,null);
            $Object = Settings::where('key',$key)->first();
            
            if ($Object){
              
              $Object->content = $value;
              $Object->save();
              
            } else {
              
              $Object = new Settings();
              $Object->key = $key;
              $Object->name = $settings[$key];
              $Object->value = 0;
              $Object->content = $value;
              $Object->save();
              
            }
          }
          return back()->with('status', 'Profile updated!');
        }
}
