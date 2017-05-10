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
                    'prices' => \App\Prices::all(),
                ]);
    }

    public function newPrices()
    {
        return view('backend/prices/new-prices',[
                                                'seasons' =>\App\Seasons::all(),
                                            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $price = new \App\Prices();

        $price->name = $request->input('name');
        $price->occupation = $request->input('occupation');
        $price->pricehigh = $request->input('priceHigh');
        $price->priceMed = $request->input('priceMed');
        $price->priceLow = $request->input('priceLow');
        $price->costHigh = $request->input('costHigh');
        $price->costMed = $request->input('costMed');
        $price->costLow = $request->input('costLow');
        
        if ($price->save()) {
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
        $pricesUpdate->name = $request->input('name');
        $pricesUpdate->occupation = $request->input('occupation');
        $pricesUpdate->pricehigh = $request->input('priceHigh');
        $pricesUpdate->priceMed = $request->input('priceMed');
        $pricesUpdate->priceLow = $request->input('priceLow');
        $pricesUpdate->costHigh = $request->input('costHigh');
        $pricesUpdate->costMed = $request->input('costMed');
        $pricesUpdate->costLow = $request->input('costLow');

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
        $priceDelete = \App\Prices::find($id);
        if ($priceDelete->delete()) {
            return redirect()->action('PricesController@index');
        }
    }
}
