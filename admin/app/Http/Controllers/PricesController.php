<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PricesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('backend/prices/index',[

                    'seasons' => \App\TypeSeasons::all(),
                    'newseasons' => \App\TypeSeasons::all(),
                    'extras' => \App\Extras::all(),
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $existPrice = \App\Prices::where('occupation',$request->input('occupation'))
                                    ->where('season',$request->input('seasson'))->get();

        if (count($existPrice)  == 0) {
            $price = new \App\Prices;
            $price->season = $request->input('season') ;
            $price->occupation = $request->input('occupation');
            $price->price = $request->input('price');
            $price->cost = $request->input('cost');
            if ($price->save()) {
                return redirect()->action('PricesController@index');
            }
        }else{
                return redirect()->action('PricesController@index');
        }
     
    }
    
    public function createExtras(Request $request)
    {
        $existExtra = \App\Extras::where('name',$request->input('name'))->get();

        if (count($existExtra)  == 0) {
            $extra = new \App\Extras;
            $extra->name = $request->input('name');
            $extra->price = $request->input('price');
            $extra->cost = $request->input('cost');
            if ($extra->save()) {
                return redirect()->action('PricesController@index');
            }
        }else{
                return redirect()->action('PricesController@index');
        }
     
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id                   = $request->input('id');
        $pricesUpdate          = \App\Prices::find($id);

        $pricesUpdate->price = $request->input('price');
        $pricesUpdate->cost = $request->input('cost');

        if ($pricesUpdate->save()) {
            echo "Cambiada!!";
        }
    }

    public function updateExtra(Request $request)
    {
        $id                   = $request->input('id');
        $extraUpdate          = \App\Extras::find($id);

        $extraUpdate->price = $request->input('extraprice');
        $extraUpdate->cost = $request->input('extracost');

        if ($extraUpdate->save()) {
            echo "Cambiada!!";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $price = \App\Prices::find($id);
        if ( $price->delete() ) {
            return redirect()->action('PricesController@index');
        }
    }
}
