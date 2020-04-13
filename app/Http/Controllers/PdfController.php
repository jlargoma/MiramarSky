<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Product as Product;
use PDF;
use Carbon\Carbon;

class PdfController extends AppController
{
    public function invoice($id)
    {
        $book = \App\Book::find($id);
        $payments = \App\Payments::where('book_id', $id)->get();
        $pending = 0;

        foreach ($payments as $payment) {
            $pending += $payment->import;
        }

        $data =[ 'book' => $book, 'pendiente' => $pending];
        
//        return view('pdf._pdfWithData',[ 'data' => $data]);
        
        $pdf = PDF::loadView('pdf._pdfWithData', ['data' => $data]);
        return $pdf->stream('documento-checkin-'.str_replace(' ', '-', strtolower($book->customer->name)).'.pdf');

    }


	public function pdfPropietario( $id, $year = "" )
	{
		$room = \App\Rooms::find($id);
		$totales = [
			"total"        => 0,
			"coste"        => 0,
			"bancoJorge"   => 0,
			"bancoJaime"   => 0,
			"jorge"        => 0,
			"jaime"        => 0,
			"costeApto"    => 0,
			"costePark"    => 0,
			"costeLujo"    => 0,
			"costeLimp"    => 0,
			"costeAgencia" => 0,
			"benJorge"     => 0,
			"benJaime"     => 0,
			"pendiente"    => 0,
			"limpieza"     => 0,
			"beneficio"    => 0,
			"stripe"       => 0,
			"obs"  => 0,
		];
		if ( empty($year) ) {
			$date = Carbon::now();
			if ($date->copy()->format('n') >= 6) {
				$date = new Carbon('first day of June '.$date->copy()->format('Y'));
			}else{
				$date = new Carbon('first day of June '.$date->copy()->subYear()->format('Y'));
			}

		}else{
			$year = Carbon::createFromFormat('Y',$year);
			$date = $year->copy();

		}

		$books = \App\Book::with(['customer', 'payments', 'room.type'])
							->where('room_id' , $room->id)
							->where('start' , '>=' , $date)
							->where('start', '<=', $date->copy()->addYear()->subMonth())
							->whereIn('type_book',[2, 7, 8])
							->orderBy('start', 'ASC')
							->get();

		foreach ($books as $key => $book) {

			if($book->type_book != 7 && $book->type_book != 8){
				$totales["total"]        += $book->total_price;
				$totales["costeApto"]    += $book->cost_apto;
				$totales["costePark"]    += $book->cost_park;
                                $totales['coste'] += $book->get_costeTotal();
                                if ($book->type_luxury == 1 || $book->type_luxury == 3 || $book->type_luxury == 4) {
                                  $totales["costeLujo"] += $book->cost_lujo;
                                } 
				$totales["costeLimp"]    += $book->cost_limp;
				$totales["costeAgencia"] += $book->PVPAgencia;
				$totales["bancoJorge"]   += $book->getPayment(2);
				$totales["bancoJaime"]   += $book->getPayment(3);
				$totales["jorge"]        += $book->getPayment(0);
				$totales["jaime"]        += $book->getPayment(1);
				$totales["benJorge"]     += $book->getJorgeProfit();
				$totales["benJaime"]     += $book->getJaimeProfit();
				$totales["limpieza"]     += $book->sup_limp;
				$totales["beneficio"]    += $book->profit;
				$totales["stripe"]       += $book->stripeCost;
				$totales["obs"]          += $book->extraCost;
				$totales["pendiente"]    += $book->pending;
			}
		}

		$pagos = \App\Expenses::where('date', '>=', $date->copy()->format('Y-m-d'))
			->where('date', '<=', $date->copy()->addYear()->format('Y-m-d'))
			->where('PayFor', 'LIKE', '%'.$room->id.'%')
			->orderBy('date', 'ASC')
			->get();

		$pagototal = 0;
		foreach ($pagos as $pago) {

			$pagototal += $pago->import;

		}
		
		return view('backend.paymentspro.pdf.resumen', [
			'books' => $books,
			'room'  => $room,
			'pagos'  => $pagos,
			'pagototal'   => $pagototal,
			'pagototalProp' => 0,
			'total' => $totales["costeApto"] + $totales["costePark"] + $totales["costeLujo"],//$totales["total"],
			'apto'  => $totales["costeApto"],
			'park'  => $totales["costePark"],
			'lujo'  => $totales["costeLujo"],
		]);
		
	}

}
