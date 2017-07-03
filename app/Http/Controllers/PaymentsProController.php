<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PaymentsProController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
        {
            $books = \App\Book::all();

            $payments = array();

            foreach ($books as $book) {
                if (isset($payments[$book->room_id])) {
                  $payments[$book->room_id] += $book->total_price;
                }else{
                    $payments[$book->room_id] = $book->total_price;
                }
                
            }

            $payments = \App\PaymentsPro::all();

            $total_payments = array();

            foreach ($payments as $payments) {
                if (isset($total_payments[$payments->room_id])) {
                  $total_payments[$payments->room_id] += $payments->import;
                }else{
                    $total_payments[$payments->room_id] = $payments->import;
                }
                
            }

            return view('backend/paymentspro/index',[
                                                        'rooms' => \App\Rooms::all(),
                                                        'payments' => $payments,
                                                        'totalPayment' => $total_payments,
                                                        ]);
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
