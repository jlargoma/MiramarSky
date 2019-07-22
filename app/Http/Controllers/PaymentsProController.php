<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use App\Classes\Mobile;

class PaymentsProController extends AppController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$year      = self::getActiveYear();
		$startYear = new Carbon($year->start_date);
		$endYear   = new Carbon($year->end_date);

		$rooms = \App\Rooms::orderBy('order', 'ASC')->get();

		/*Calculamos los ingresos por reserva y room */

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

		foreach ($rooms as $room)
		{

			$data[$room->id]                            = array();
			$data[$room->id]['totales']['totalLimp']    = 0;
			$data[$room->id]['totales']['totalAgencia'] = 0;
			$data[$room->id]['totales']['totalParking'] = 0;
			$data[$room->id]['totales']['totalLujo']    = 0;
			$data[$room->id]['totales']['totalCost']    = 0;
			$data[$room->id]['totales']['totalApto']    = 0;
			$data[$room->id]['totales']['totalPVP']     = 0;
			$data[$room->id]['pagos']                   = 0;

			$booksByRoom = \App\Book::where('room_id', $room->id)
			                        ->whereIn('type_book', [
				                        2,
				                        7,
				                        8
			                        ])
			                        ->where('start', '>=', $startYear)
			                        ->where('finish', '<=', $endYear)
			                        ->get();


			foreach ($booksByRoom as $book)
			{

				if ($room->luxury == 1)
				{
					$data[$book->room_id]['totales']['totalLujo'] += $book->cost_lujo;
					$costTotal                                    = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->cost_limp + $book->PVPAgencia;
					$summary['totalLujo']                         += $book->cost_lujo;
				} else
				{
					$data[$book->room_id]['totales']['totalLujo'] += 0;
					$costTotal                                    = $book->cost_apto + $book->cost_park + 0 + $book->cost_limp + $book->PVPAgencia;
					$summary['totalLujo']                         += 0;
				}
				$data[$book->room_id]['totales']['totalLimp']    += $book->cost_limp;
				$data[$book->room_id]['totales']['totalAgencia'] += $book->PVPAgencia;
				$data[$book->room_id]['totales']['totalParking'] += $book->cost_park;


				$data[$book->room_id]['totales']['totalCost'] += $costTotal;
				$data[$book->room_id]['totales']['totalApto'] += $book->cost_apto;
				$data[$book->room_id]['totales']['totalPVP']  += $book->total_price;

				$summary['totalLimp']    += $book->cost_limp;
				$summary['totalAgencia'] += $book->PVPAgencia;
				$summary['totalParking'] += $book->cost_park;

				$summary['totalCost'] += $costTotal;
				$summary['totalApto'] += $book->cost_apto;
				$summary['totalPVP']  += $book->total_price;

			}


			$gastos = \App\Expenses::where('date', '>=', $startYear)
			                       ->Where('date', '<=', $endYear)
			                       ->Where('PayFor', 'LIKE', '%' . $room->id . '%')
			                       ->orderBy('date', 'DESC')
			                       ->get();

			if (count($gastos) > 0)
			{

				foreach ($gastos as $gasto)
				{
					$divisor = 0;

					if (preg_match('/,/', $gasto->PayFor))
					{
						$aux = explode(',', $gasto->PayFor);
						for ($i = 0; $i < count($aux); $i++)
						{
							if (!empty($aux[$i]))
							{
								$divisor++;
							}
						}

					} else
					{
						$divisor = 1;
					}

					$data[$room->id]['pagos'] += ($gasto->import / $divisor);
					$summary['pagos']         += ($gasto->import / $divisor);
				}
			}

		}
		return view('backend/paymentspro/index', [
			'year'    => $year,
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
		$fecha      = Carbon::now();
		$paymentPro = new \App\Paymentspro();

		$paymentPro->room_id     = $request->id;
		$paymentPro->import      = $request->import;
		$paymentPro->comment     = $request->comment;
		$paymentPro->datePayment = $fecha->format('Y-m-d');
		$paymentPro->type        = $request->type;

		if ($paymentPro->save())
		{
			return redirect()->action('PaymentsProController@index');
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int                      $id
	 * @return \Illuminate\Http\Response
	 */
	public function update($id, $month = "", Request $request)
	{
		$typePayment = new \App\Paymentspro();
		$total       = 0;
		$banco       = 0;
		$metalico    = 0;

		$room = \App\Rooms::find($id);

		$date = empty($month) ? Carbon::now() : Carbon::createFromFormat('Y', $month);

		$start = new Carbon('first day of September');
		$start = $date->format('n') >= 9 ? $start : $start->subYear();

		// $payments = \App\Paymentspro::where('room_id',$id)
		//                             ->where('datePayment','>',$start->copy())
		//                             ->where('datePayment','<',$start->copy()->addYear())
		//                             ->get();
		$gastos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
		                       ->Where('date', '<=', $start->copy()->addYear()->format('Y-m-d'))
		                       ->Where('PayFor', 'LIKE', '%' . $room->id . '%')
		                       ->orderBy('date', 'DESC')
		                       ->get();


		$pagado   = 0;
		$metalico = 0;
		$banco    = 0;
		foreach ($gastos as $payment)
		{
			if ($payment->typePayment == 0 || $payment->typePayment == 1)
			{
				$divisor = 0;
				if (preg_match('/,/', $payment->PayFor))
				{
					$aux = explode(',', $payment->PayFor);
					for ($i = 0; $i < count($aux); $i++)
					{
						if (!empty($aux[$i]))
						{
							$divisor++;
						}
					}

				} else
				{
					$divisor = 1;
				}
				$metalico += ($payment->import / $divisor);

			} elseif ($payment->typePayment == 2 || $payment->typePayment == 3)
			{
				$divisor = 0;
				if (preg_match('/,/', $payment->PayFor))
				{
					$aux = explode(',', $payment->PayFor);
					for ($i = 0; $i < count($aux); $i++)
					{
						if (!empty($aux[$i]))
						{
							$divisor++;
						}
					}

				} else
				{
					$divisor = 1;
				}
				$banco += ($payment->import / $divisor);

			}

			$divisor = 0;
			if (preg_match('/,/', $payment->PayFor))
			{
				$aux = explode(',', $payment->PayFor);
				for ($i = 0; $i < count($aux); $i++)
				{
					if (!empty($aux[$i]))
					{
						$divisor++;
					}
				}

			} else
			{
				$divisor = 1;
			}

			$pagado += ($payment->import / $divisor);
		}
		$total = 0;
		$apto  = 0;
		$park  = 0;
		$lujo  = 0;


		$books = \App\Book::whereIn('type_book', [2])
		                  ->where('room_id', $room->id)
		                  ->where('start', '>=', $start->copy()->format('Y-m-d'))
		                  ->where('start', '<=', $start->copy()->addYear()->format('Y-m-d'))
		                  ->orderBy('start', 'ASC')
		                  ->get();

		foreach ($books as $book)
		{

			$apto += $book->cost_apto;
			$park += $book->cost_park;
			if ($book->room->luxury == 1)
			{
				$lujo += $book->cost_lujo;
			} else
			{
				$lujo += 0;
			}
		}
		$total += ($apto + $park + $lujo);

		if ($total > 0 && $request->debt > 0)
		{
			$deuda = ($total - $request->debt) / $total * 100;
		} else
		{
			$deuda = $total;
		}


		return view('backend/paymentspro/_form', [
			'room'        => $room,
			'payments'    => $gastos,
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
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}

	public function getBooksByRoom($idRoom, Request $request)
	{
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
			"obs"          => 0,
		];

		$year      = self::getActiveYear();
		$startYear = new Carbon($year->start_date);
		$endYear   = new Carbon($year->end_date);

		if ($idRoom == "all")
		{
			$books = \App\Book::where('start', '>=', $startYear)
			                  ->where('start', '<=', $endYear)
			                  ->whereIn('type_book', [
				                  2,
				                  7,
				                  8
			                  ])
			                  ->orderBy('start', 'ASC')
			                  ->get();
		} else
		{
			$books = \App\Book::where('start', '>=', $startYear)
			                  ->where('start', '<=', $endYear)
			                  ->whereIn('type_book', [
				                  2,
				                  7,
				                  8
			                  ])
			                  ->where('room_id', $idRoom)
			                  ->orderBy('start', 'ASC')
			                  ->get();
		}


		foreach ($books as $key => $book)
		{
			$totales["total"]        += $book->total_price;
			$totales["coste"]        += ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp + $book->extraCost);
			$totales["costeApto"]    += $book->cost_apto;
			$totales["costePark"]    += $book->cost_park;
			$totales["costeLujo"]    += $book->cost_lujo;
			$totales["costeLimp"]    += $book->cost_limp;
			$totales["costeAgencia"] += $book->PVPAgencia;
			$totales["bancoJorge"]   += $book->getPayment(2);
			$totales["bancoJaime"]   += $book->getPayment(3);
			$totales["jorge"]        += $book->getPayment(0);
			$totales["jaime"]        += $book->getPayment(1);
			$totales["benJorge"]     += $book->ben_jorge;
			$totales["benJaime"]     += $book->ben_jaime;
			$totales["pendiente"]    += $book->getPayment(4);
			$totales["limpieza"]     += $book->sup_limp;
			$totales["beneficio"]    += ($book->total_price - ($book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $book->cost_limp));

			$totalStripep  = 0;
			$stripePayment = \App\Payments::where('book_id', $book->id)->where('comment', 'LIKE', '%stripe%')->get();
			foreach ($stripePayment as $key => $stripe):
				$totalStripep += $stripe->import;
			endforeach;
			if ($totalStripep > 0):
				$totales["stripe"] += ((1.4 * $totalStripep) / 100) + 0.25;
			endif;

			$totales['obs'] += $book->extraCost;

		}
		$totBooks = (count($books) > 0) ? count($books) : 1;

		$diasPropios      = \App\Book::where('start', '>', $startYear)->where('finish', '<', $endYear)
		                             ->whereIn('type_book', [
			                             7,
			                             8
		                             ])->orderBy('created_at', 'DESC')->get();
		$countDiasPropios = 0;
		foreach ($diasPropios as $key => $book)
		{
			$start     = Carbon::createFromFormat('Y-m-d', $book->start);
			$finish    = Carbon::createFromFormat('Y-m-d', $book->finish);
			$countDays = $start->diffInDays($finish);

			$countDiasPropios += $countDays;
		}
		$data = [
			'days-ocupation'    => 0,
			'total-days-season' => \App\SeasonDays::first()->numDays,
			'num-pax'           => 0,
			'estancia-media'    => 0,
			'pax-media'         => 0,
			'precio-dia-media'  => 0,
			'dias-propios'      => $countDiasPropios,
			'agencia'           => 0,
			'propios'           => 0,
		];

		foreach ($books as $key => $book)
		{

			$start     = Carbon::createFromFormat('Y-m-d', $book->start);
			$finish    = Carbon::createFromFormat('Y-m-d', $book->finish);
			$countDays = $start->diffInDays($finish);

			/* Dias ocupados */
			$data['days-ocupation'] += $countDays;

			/* Nº inquilinos */
			$data['num-pax'] += $book->pax;


			if ($book->agency != 0)
			{
				$data['agencia']++;
			} else
			{
				$data['propios']++;
			}

		}

		$data['agencia'] = ($data['agencia'] / $totBooks) * 100;
		$data['propios'] = ($data['propios'] / $totBooks) * 100;

		/* Estancia media */
		$data['estancia-media'] = ($data['days-ocupation'] / $totBooks);

		/* Inquilinos media */
		$data['pax-media'] = ($data['num-pax'] / $totBooks);


		return view('backend/paymentspro/_tableBooksByRoom', [
			'books'   => $books,
			'mobile'  => new Mobile(),
			'totales' => $totales,
			'data'    => $data,
		]);
	}


	public function getLiquidationByRoom(Request $request)
	{

		$aux = str_replace('Abr', 'Apr', $request->date);

		$date = explode('-', $aux);

		$start  = Carbon::createFromFormat('d M, y', trim($date[0]));
		$finish = Carbon::createFromFormat('d M, y', trim($date[1]));


		$total = 0;
		$apto  = 0;
		$park  = 0;
		$lujo  = 0;

		if ($request->idRoom != 'all')
		{
			$room  = \App\Rooms::find($request->idRoom);
			$books = \App\Book::where('room_id', $room->id)
			                  ->whereIn('type_book', [
				                  2,
				                  7,
				                  8
			                  ])
			                  ->where('start', '>=', $start->copy()->format('Y-m-d'))
			                  ->where('finish', '<=', $finish->copy()->format('Y-m-d'))
			                  ->orderBy('start', 'ASC')
			                  ->get();
		} else
		{
			$room  = "all";
			$books = \App\Book::whereIn('type_book', [
				2,
				7,
				8
			])
			                  ->where('start', '>=', $start->copy()->format('Y-m-d'))
			                  ->where('finish', '<=', $finish->copy()->format('Y-m-d'))
			                  ->orderBy('start', 'ASC')
			                  ->get();
		}


		foreach ($books as $book)
		{

			if ($book->type_book != 7 && $book->type_book != 8 && $book->type_book != 9)
			{

				$apto += $book->cost_apto;
				$park += $book->cost_park;
				if ($book->room->luxury == 1)
				{
					$lujo += $book->cost_lujo;
				} else
				{
					$lujo += 0;
				}


			}


		}
		$total += ($apto + $park + $lujo);

		$pagos = \App\Expenses::where('date', '>=', $start->copy()->format('Y-m-d'))
		                      ->where('date', '<=', $start->copy()->addYear()->format('Y-m-d'))
		                      ->where('PayFor', 'LIKE', '%' . $room->id . '%')
		                      ->orderBy('date', 'ASC')
		                      ->get();

		$pagototal = 0;
		foreach ($pagos as $pago)
		{

			$pagototal += $pago->import;

		}

		return view('backend/paymentspro/_liquidationByRoom', [
			'books'         => $books,
			'costeProp'     => $request->costeProp,
			'apto'          => $apto,
			'park'          => $park,
			'lujo'          => $lujo,
			'total'         => $total,
			'room'          => $room,
			'dates'         => [
				'start'  => $start,
				'finish' => $finish,
			],
			'mobile'        => new Mobile(),
			'pagos'         => $pagos,
			'pagototal'     => $pagototal,
			'pagototalProp' => 0,
		]);

	}

	public static function getHistoricProduction($room_id, Request $request)
	{

		$year      = self::getActiveYear();
		$startYear = new Carbon($year->start_date);
		$endYear   = new Carbon($year->end_date);

		return view('backend/paymentspro/_historicProductionGraphic', [
			'year'  => $year,
			'room_id' => $room_id
		]);
	}
}
