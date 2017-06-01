<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;

class SeasonsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = new Carbon('first day of September 2016');
        return view('backend/seasons/index',[
                    'seasons'  => \App\Seasons::all(),
                    'newtypeSeasons' => \App\TypeSeasons::all(),
                    'typeSeasons' => \App\TypeSeasons::all(),
                    'date'       => $date,                 
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        
        
        $exist = \App\Seasons::existDate($request->input('start'),$request->input('finish'));
        if ($exist == false) {
            $seasons = new \App\Seasons();

            $start = $request->input('start');
            $start = Carbon::createFromFormat('d/m/Y',$start);
            $finish = $request->input('finish');
            $finish = Carbon::createFromFormat('d/m/Y',$finish);
            $start->format('Y-m-d');
            $finish->format('Y-m-d');

            $seasons->start_date = $start;
            $seasons->finish_date = $finish;
            $seasons->type = $request->input('type');
            if ($seasons->save()) {
                return redirect()->action('SeasonsController@index');
            }
        }else{
                echo "La fecha ya esta ocupada";            
        }
    }

     public function createType(Request $request)
    {
        $existTypeSeason = \App\TypeSeasons::where('name',$request->input('name'))->count();
        if ($existTypeSeason == 0) {
            $typeSeasons = new \App\TypeSeasons();

            $typeSeasons->name = $request->input('name');

            if ($typeSeasons->save()) {
                for ($i=4; $i <=8 ; $i++) { 
                    $price= new \App\Prices();
                    $price ->season = $typeSeasons->id;
                    $price ->occupation = $i;
                    $price ->save();
                }
                

                return redirect()->action('SeasonsController@index');
            }
        }else{
            echo "Ya existe este tipo";
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $season = \App\Seasons::find($id);

        if ($season->delete()) {
                return redirect()->action('SeasonsController@index');
            }

    }
}
