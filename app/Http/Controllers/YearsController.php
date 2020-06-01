<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class YearsController extends AppController {

  public function changeActiveYear(Request $request) {
    foreach (\App\Years::all() as $key => $year) {
      $year->active = 0;
      $year->save();
    }

    if (!$request->has('year'))
      throw new ModelNotFoundException('Year not found', 404);

    $newYear = \App\Years::find($request->get('year'));
    $newYear->active = 1;

    if ($newYear->save())
      return new Response("OK, year changed", 200);
    else
      return new Response("Error undefined", 500);
  }

    
  public function changeMonthActiveYear(Request $request) {
    $start = $request->input('start');
    $end = $request->input('end');
    $yearID = $request->input('years');
    $oYear = \App\Years::find($yearID);
    if (!$oYear){
      $oYear = new \App\Years();
    }
    $oYear->start_date = convertDateToDB($start);
    $oYear->end_date = convertDateToDB($end);

    if ($oYear->save())
      return new Response("OK", 200);
    else
      return new Response("Error undefined", 500);
  }

  function getYear(Request $request) {
    $id = $request->input('id');
    $year = \App\Years::find($id);
    if ($year) {
      return response()->json(
                      [
                          date('d/m/Y', strtotime($year->start_date)),
                          date('d/m/Y', strtotime($year->end_date)),
                      ]
      );
    }
  }

}
