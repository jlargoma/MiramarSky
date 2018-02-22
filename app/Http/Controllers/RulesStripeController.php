<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class RulesStripeController extends Controller
{
    

	public function update(Request $request)
	{
		
		$rule = \App\RulesStripe::find($request->id);
		$rule->percent = $request->percent;
		$rule->numDays = $request->numDays;

		if ($rule->save()) {
			return "OK";
		}

	}

}
