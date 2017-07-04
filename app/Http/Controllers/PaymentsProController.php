<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;

class PaymentsProController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($month = "")
        {

            if ( empty($month) ) {
                $date = Carbon::createFromDate(2016, 9, 1);
            }else{
                $month = Carbon::createFromFormat('Y',$month);
                $date = $month->copy()->addMonth(6);

            }

            $books = \App\Book::where('start','>',$date->copy())->where('start','<',$date->copy()->addYear())->get();

            $total = array();

            foreach ($books as $book) {
                if (isset($total[$book->room_id])) {
                  $total[$book->room_id] += $book->cost_total;
                }else{
                    $total[$book->room_id] = $book->cost_total;
                }  
            }

            $paymentspro = \App\PaymentsPro::where('datePayment','>',$date->copy())->where('datePayment','<',$date->copy()->addYear())->get();

            $total_payments = array();

            foreach ($paymentspro as $payments) {
                if (isset($total_payments[$payments->room_id])) {
                  $total_payments[$payments->room_id] += $payments->import;
                }else{
                    $total_payments[$payments->room_id] = $payments->import;
                }
                
            }

            $total_debt = array();
            $rooms = \App\Rooms::all();
            foreach ($rooms as $room) {
                if (isset($total_payments[$room->id]) && isset($total[$room->id]) ) {
                    $total_debt[$room->id] = $total[$room->id] - $total_payments[$room->id] ;
                }elseif(!isset($total_payments[$room->id]) && isset($total[$room->id])){
                    $total_debt[$room->id] = $total[$room->id];
                }else{
                    $total_debt[$room->id] = 0;
                }
            }

            return view('backend/paymentspro/index',[
                                                        'date'         => $date->subMonth(),
                                                        'rooms'        => \App\Rooms::all(),
                                                        'total'        => $total,
                                                        'totalPayment' => $total_payments,
                                                        'debt'         => $total_debt,
                                                        ]);
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        echo "<pre>";
        $fecha = Carbon::now();
        $paymentPro = new \App\PaymentsPro();

        $paymentPro->room_id     = $request->id;
        $paymentPro->import      = $request->import;
        $paymentPro->comment     = $request->comment;
        $paymentPro->datePayment = $fecha->format('Y-m-d');

        if ($paymentPro->save()) {
           return redirect()->action('PaymentsProController@index');
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
    public function update($id,$month = "", Request $request)
    {

        $room = \App\Rooms::find($id);
        $month = Carbon::createFromFormat('Y',$month);
        $date = $month->copy()->addMonth(6);

        $payments = \App\PaymentsPro::where('room_id',$id)->where('datePayment','>',$date->copy())->where('datePayment','<',$date->copy()->addYear())->get();

        return view('backend/paymentspro/_form',  [
                                                'room' => $room,
                                                'payments' => $payments,
                                                'debt' => $request->debt,
                                            ]);
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
