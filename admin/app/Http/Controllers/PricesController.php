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
        return view('backend/prices/prices',[
                    'countOccupations' => \App\SizeRooms::distinct()->get(['minOcu']),
                    'seasons' => \App\TypeSeasons::all(),
                ]);
    }

    public function newPrices()
    {
        return view('backend/prices/new-prices',[
                                                'seasons' =>\App\TypeSeasons::all(),
                                            ]);
    }
    public function newSpecialPrices()
    {
        return view('backend/prices/new-special-prices',[
                                                'seasons' =>\App\TypeSeasons::all(),
                                            ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $alta = \App\TypeSeasons::where('name','Alta')->first();
        $media = \App\TypeSeasons::where('name','Media')->first();
        $baja = \App\TypeSeasons::where('name','Baja')->first();

        $alta  = $alta->id;
        $media = $media->id;
        $baja  = $baja->id;

        $price = new \App\Prices;
        $price->season = $alta ;
        $price->occupation = $request->input('occupation');
        $price->price = $request->input('priceHigh');
        $price->cost = $request->input('costHigh');
        $price->save();

        $price = new \App\Prices;
        $price->season =  $media;
        $price->occupation = $request->input('occupation');
        $price->price = $request->input('priceMed');
        $price->cost = $request->input('costMed');
        $price->save();

        $price = new \App\Prices;
        $price->season = $baja ;
        $price->occupation = $request->input('occupation');
        $price->price = $request->input('priceLow');
        $price->cost = $request->input('costLow');
        
        if ($price->save()) {
            return redirect()->action('PricesController@index');
        }
    }

    public function createSpecial(Request $request)
    {

        $special = new \App\Prices;
        $special->occupation = $request->input('occupation');
        $special->season = $request->input('season');
        $special->price = $request->input('price');
        $special->cost = $request->input('cost');

        if ($special->save()) {
            return redirect()->action('PricesController@index');
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
