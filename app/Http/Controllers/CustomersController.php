<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend/customers/index',['customers' => \App\Customers::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customer = new \App\Customers();

        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->phone = $request->input('phone');
        $customer->comments = $request->input('comment');

        if ($customer->save()) {
            return redirect()->action('CustomersController@index');
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
    public function update($id)
    {
        $customer = \App\Customers::find($id);

        return view('backend/customer/_form',  [
                                                'customer' => $customer
                                            ]);
    }
    
    public function save(Request $request)
    {
        $id                   = $request->input('id');
        $customerUpadate          = \App\Customers::find($id);

        $customerUpadate->name      = $request->input('name');
        $customerUpadate->email     = $request->input('email');
        $customerUpadate->phone     = $request->input('phone');
        $customerUpadate->comments  = $request->input('comments');

        if ($customerUpadate->save()) {
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
        $customer = \App\Customers::find($id);

        if ($customer->delete()) {
            return redirect()->action('CustomersController@index');
        }
    }
}
