<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        return view('backend/payments/index',[
                                                'pagos' => \App\Payments::all(),
                                                'book' => new \App\Book(),
                                                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $payment = new \App\Payments();
        
        $date = Carbon::createFromFormat('d/m/Y' ,$request->date);
        $payment->book_id = $request->id;
        $payment->datePayment = $date;
        $payment->import = $request->importe;
        $payment->comment = $request->comment;
        $payment->type = $request->type;

        if ($request->type == 1 || $request->type == 0) {

            $data['concept'] = ( $request->type == 0 )? 'COBRO METALICO JAIME':'COBRO METALICO JORGE';
            $data['date'] = $date->copy()->format('Y-m-d');
            $data['import'] = $request->importe;
            $data['comment'] = $request->comment;
            $data['typePayment'] = $request->type;
            $data['type'] = 0;

            LiquidacionController::addCashbox($data);
        }

        if ($payment->save()) {
            return 'cobro guardado';
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
        $paymentUpdate = \App\Payments::find($request->id);

        $paymentUpdate->import = $request->importe;
        if ($paymentUpdate->save()) {
            return "Importe cambiado";
        }

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
