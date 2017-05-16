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
       
        $fechas = \App\Seasons::find(1);
        $from = $fechas->start_date;
        $to = $fechas->finish_date;
        $users = \App\Seasons::whereBetween('start_date', [$fechas->start_date, $fechas->finish_date])->get();
        return view('backend/seasons/seasons',[
                    'seasons' => \App\Seasons::all(),
                    'from' => $from,
                    'to' => $to,
                    'user' => $users,

                ]);
    }

    public function newSeasons()
    {
        return view('backend/seasons/new-seasons',[
                                                    'seasons' => \App\TypeSeasons::all()]);
    }
    public function newTypeSeasons()
    {
        return view('backend/seasons/new-type-seasons',[
                    'types' => \App\TypeSeasons::all()
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $seasons = new \App\Seasons();

        $start = $request->input('start');
        $start = Carbon::createFromFormat('d/m/Y', $start);
        $finish = $request->input('finish');
        $finish = Carbon::createFromFormat('d/m/Y', $finish);
        $start->format('Y-m-d');
        $finish->format('Y-m-d');
        
        $seasons->start_date = $start;
        $seasons->finish_date = $finish;
        $seasons->type = $request->input('type');
        
        if ($seasons->save()) {
            return redirect()->action('SeasonsController@index');
        }
    }

     public function createType(Request $request)
    {
        $existTypeSeason = \App\TypeSeasons::where('name',$request->input('name'))->count();
        if ($existTypeSeason == 0) {
            $typeSeasons = new \App\TypeSeasons();

            $typeSeasons->name = $request->input('name');
            
            if ($typeSeasons->save()) {
                return redirect()->action('SeasonsController@index');
            }
        }else{
            echo "Ya existe este tipo";
        }
        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
