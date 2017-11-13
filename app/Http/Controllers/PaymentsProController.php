<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use App\Classes\Mobile;

class PaymentsProController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($year = "")
    {

        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();

        }



        if ($date->copy()->format('n') >= 9) {
            $date = new Carbon('first day of September '.$date->copy()->format('Y'));
        }else{
            $date = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
        }

        /*Calculamos los ingresos por reserva y room */
        $books = \App\Book::whereIn('type_book',[2])
                            ->whereNotIn('room_id',[106,107,108,109,111,112,113,126,134])
                            ->where('start','>=',$date->copy())
                            ->where('start','<=',$date->copy()->addYear())
                            ->orderBy('start', 'ASC')
                            ->get();

        $data    = array();
        $summary = [
                    'totalLimp'    => 0,
                    'totalAgencia' => 0,
                    'totalParking' => 0,
                    'totalLujo'    => 0,
                    'totalCost'    => 0,
                    'totalApto'    => 0,
                    'totalPVP'     => 0,
                    'pagos'        => 0,
                    ];

        /* Calculamos los pagos por room */
        $rooms = \App\Rooms::whereNotIn('id',[106,107,108,109,111,112,113,126,134])->orderBy('order', 'ASC')->get();
        foreach ($rooms as $room) {
            $paymentspro = \App\Paymentspro::where('room_id', $room->id)
                                            ->where('datePayment','>',$date->copy())
                                            ->where('datePayment','<',$date->copy()->addYear())
                                            ->get();

            if ( count($paymentspro) > 0) {

                foreach ($paymentspro as $payments) {

                    if ( isset($data[$room->id]['pagos']) ) {

                        $data[$room->id]['pagos'] += $payments->import;

                    }else{

                        $data[$room->id]['pagos'] = $payments->import;

                    }

                    $summary['pagos'] += $payments->import;
                }



            }else{
                $data[$room->id]['pagos'] = 0;
            }

            $data[$room->id]['totales']['totalLimp']    = 0;
            $data[$room->id]['totales']['totalAgencia'] = 0;
            $data[$room->id]['totales']['totalParking'] = 0;
            $data[$room->id]['totales']['totalLujo']    = 0;
            $data[$room->id]['totales']['totalCost']    = 0;
            $data[$room->id]['totales']['totalApto']    = 0;
            $data[$room->id]['totales']['totalPVP']     = 0;



        }
        foreach ($books as $book) {
            $costTotal = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->cost_limp + $book->PVPAgencia;

                
            $data[$book->room_id]['totales']['totalLimp']    += $book->cost_limp;
            $data[$book->room_id]['totales']['totalAgencia'] += $book->PVPAgencia;
            $data[$book->room_id]['totales']['totalParking'] += $book->cost_park;
            $data[$book->room_id]['totales']['totalLujo']    += $book->cost_lujo;
            $data[$book->room_id]['totales']['totalCost']    += $costTotal;
            $data[$book->room_id]['totales']['totalApto']    += $book->cost_apto;
            $data[$book->room_id]['totales']['totalPVP']     += $book->total_price;
 

            $summary['totalLimp']    += $book->cost_limp;
            $summary['totalAgencia'] += $book->PVPAgencia;
            $summary['totalParking'] += $book->cost_park;
            $summary['totalLujo']    += $book->cost_lujo;
            $summary['totalCost']    += $costTotal;
            $summary['totalApto']    += $book->cost_apto;
            $summary['totalPVP']     += $book->total_price;
        }


        return view('backend/paymentspro/index',[
                                                    'date'    => $date,
                                                    'data'    => $data,
                                                    'summary' => $summary,
                                                    'rooms'   => $rooms
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


        $fecha = Carbon::now();
        $paymentPro = new \App\Paymentspro();

        $paymentPro->room_id     = $request->id;
        $paymentPro->import      = $request->import;
        $paymentPro->comment     = $request->comment;
        $paymentPro->datePayment = $fecha->format('Y-m-d');
        $paymentPro->type        = $request->type;

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
        $typePayment = new \App\Paymentspro();
        $total = 0;
        $banco = 0;
        $metalico = 0;

        $room  = \App\Rooms::find($id);
        $month = Carbon::createFromFormat('Y',$month);

        if ($month->copy()->format('n') >= 9) {
            $date = new Carbon('first day of September '.$month->copy()->format('Y'));
        }else{
            $date = new Carbon('first day of September '.$month->copy()->subYear()->format('Y'));
        }

        $payments = \App\Paymentspro::where('room_id',$id)->where('datePayment','>',$date->copy())->where('datePayment','<',$date->copy()->addYear())->get();

        $pagado = 0;

        foreach ($payments as $payment) {
            if ($payment->type == 1 || $payment->type == 2) {
                if (isset($metalico)) {
                    $metalico += $payment->import;
                }else{
                    $metalico = $payment->import;
                }
            }elseif($payment->type == 3){
                if (isset($banco)) {
                    $banco += $payment->import;
                }else{
                    $banco = $payment->import;
                }
            }
            $pagado += $payment->import;
        }

        $books = \App\Book::where('start','>',$date->copy())->where('start','<',$date->copy()->addYear())->where('room_id',$id)->get();

        foreach ($books as $book) {
                $total += $book->cost_total;

        }
        if ($total > 0 && $request->debt > 0) {
           $deuda = ($total - $request->debt)/$total*100;
        }else{
            $deuda = $total;
        }
        

        return view('backend/paymentspro/_form',  [
                                                'room'        => $room,
                                                'payments'    => $payments,
                                                'debt'        => $request->debt,
                                                'total'       => $total,
                                                'deuda'       => $deuda,
                                                'typePayment' => $typePayment,
                                                'metalico'    => $metalico,
                                                'banco'       => $banco,
                                                'pagado'      => $pagado,
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

    public function getBooksByRoom($idRoom , Request $request)
    {
        $now = Carbon::now();

        if ( $request->year ) {
            if ($now->copy()->format('n') >= 9) {
                $date = new Carbon('first day of September '.$now->copy()->format('Y'));
            }else{
                $date = new Carbon('first day of September '.$now->copy()->subYear()->format('Y'));
            }
            
        }else{
            $date = new Carbon('first day of September '.$request->year);
        }

        $books = \App\Book::where('start' , '>=' , $date->format('Y-m-d'))
                            ->where('start', '<=', $date->copy()->AddYear()->SubMonth()->format('Y-m-d'))
                            ->whereIn('type_book',[2, 7, 8])
                            ->where('room_id',$idRoom)
                            ->orderBy('start', 'ASC')
                            ->get();


        return view('backend/paymentspro/_tableBooksByRoom', ['books' => $books, 'mobile' => new Mobile()]);
    }
}
