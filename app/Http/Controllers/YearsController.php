<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class YearsController extends AppController {

  public function changeActiveYear(Request $request) {
    
    if (!$request->has('year'))
      throw new ModelNotFoundException('Year not found', 404);

    $newYear = \App\Years::find($request->get('year'));
    setcookie('ActiveYear', $newYear->id, time() + (86400 * 30), "/"); // 86400 = 1 day
    return new Response("OK, year changed", 200);
  }

    
  public function changeMonthActiveYear(Request $request) {
    $start = $request->input('start');
    $end = $request->input('end');
    $yearID = $request->input('years');
    $active = $request->input('active');
    $oYear = \App\Years::find($yearID);
    if (!$oYear){
      $oYear = new \App\Years();
    }
    if ($active == 1){
      \App\Years::where('active',1)->update(['active' => 0]);
    }
    
    $oYear->start_date = convertDateToDB($start);
    $oYear->end_date = convertDateToDB($end);
    $oYear->active = $active;
    if ($oYear->save())
      return new Response("OK", 200);
    else
      return new Response("Error", 200);
  }

  function getYear(Request $request) {
    $id = $request->input('id');
    $year = \App\Years::find($id);
    if ($year) {
      return response()->json(
                      [
                          date('d/m/Y', strtotime($year->start_date)),
                          date('d/m/Y', strtotime($year->end_date)),
                          $year->active,
                      ]
      );
    }
  }

}
