<?php

namespace App\Http\Controllers;

use App\AgentsRooms;
use Illuminate\Http\Request;

use App\Http\Requests;

class SettingsController extends AppController
{
	public function index()
	{
		return view('backend/settings/index', [
			'extras'          => \App\Extras::all(),
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
}
