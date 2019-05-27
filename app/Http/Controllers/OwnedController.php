<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use Auth;
use Mail;
use App\Classes\Mobile;

class OwnedController extends AppController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($name, $year = "")
	{

		// Rooms
		$room = \App\Rooms::where('nameRoom', $name)->first();

		if ($room->owned == Auth::user()->id)
		{
			$room = \App\Rooms::where('nameRoom', $name)->first();

		} elseif (Auth::user()->role == 'admin')
		{
			$room = \App\Rooms::where('nameRoom', $name)->first();
		} else
		{
			return view('errors.owned-access');
		}
		// Año
		if (empty($year))
		{
			$date = Carbon::now();
		} else
		{
			$year = Carbon::createFromFormat('Y', $year);
			$date = $year->copy();

		}

		if ($date->copy()->format('n') >= 9)
		{
			$firstDayOfTheYear = new Carbon('first day of September ' . $date->copy()->format('Y'));
			$date              = new Carbon('first day of September ' . $date->copy()->format('Y'));
		} else
		{
			$firstDayOfTheYear = new Carbon('first day of September ' . $date->copy()->subYear()->format('Y'));
			$date              = new Carbon('first day of September ' . $date->copy()->subYear()->format('Y'));
		}

		// Variables
		$mes           = array();
		$arrayReservas = array();
		$arrayMonths   = array();
		$arrayDays     = array();


		$total = 0;
		$apto  = 0;
		$park  = 0;
		$lujo  = 0;

		// Datos

		$reservas = \App\Book::whereIn('type_book', [
			1,
			2,
//			4,
			7,
			8
		])->where('room_id', $room->id)->where('start', '>=', $firstDayOfTheYear->copy())
		                     ->where('start', '<=', $firstDayOfTheYear->copy()->addYear())->orderBy('start', 'ASC')
		                     ->get();

		$books = \App\Book::where('room_id', $room->id)->whereIn('type_book', [
			2,
			7,
			8
		])->where('start', '>=', $firstDayOfTheYear->copy())->where('start', '<=', $firstDayOfTheYear->copy()
		                                                                                             ->addYear())
		                  ->orderBy('start', 'ASC')->get();

		foreach ($reservas as $reserva)
		{
			$dia        = Carbon::createFromFormat('Y-m-d', $reserva->start);
			$start      = Carbon::createFromFormat('Y-m-d', $reserva->start);
			$finish     = Carbon::createFromFormat('Y-m-d', $reserva->finish);
			$diferencia = $start->diffInDays($finish);
			for ($i = 0; $i <= $diferencia; $i++)
			{
				$arrayReservas[$reserva->room_id][$dia->copy()->format('Y')][$dia->copy()->format('n')][$dia->copy()
				                                                                                            ->format('j')][] = $reserva;
				$dia                                                                                                         = $dia->addDay();
			}
		}

		$ax = $firstDayOfTheYear->copy();
		for ($i = 1; $i <= 12; $i++)
		{
			$mes[$ax->copy()->format('n')] = $ax->copy()->format('M Y');
			$ax                            = $ax->addMonth();
		}
		$book = new \App\Book();
		for ($i = 1; $i <= 12; $i++)
		{

			$startMonth = $firstDayOfTheYear->copy()->startOfMonth();
			$endMonth   = $firstDayOfTheYear->copy()->endOfMonth();
			$countDays  = $endMonth->diffInDays($startMonth);
			$day        = $startMonth;


			$arrayMonths[$firstDayOfTheYear->copy()->format('n')] = $day->copy()->format('t');


			for ($j = 1; $j <= $day->copy()->format('t'); $j++)
			{

				$arrayDays[$firstDayOfTheYear->copy()->format('n')][$j] = $book->getDayWeek($day->copy()->format('w'));

				$day = $day->copy()->addDay();

			}

			$firstDayOfTheYear->addMonth();

		}

		unset($arrayMonths[6]);
		unset($arrayMonths[7]);
		unset($arrayMonths[8]);

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

		// $paymentspro = \App\Paymentspro::where('room_id',$room->id)->where('datePayment','>=',$date->copy()->format('Y-m-d'))->where('datePayment','<=',$date->copy()->addYear()->format('Y-m-d'))->get();

		$gastos    = \App\Expenses::where('date', '>=', $date->copy()->format('Y-m-d'))
		                          ->where('date', '<=', $date->copy()->addYear()->format('Y-m-d'))
		                          ->where('PayFor', 'LIKE', '%' . $room->id . '%')
		                          ->orderBy('date', 'ASC')
		                          ->get();
		$pagototal = 0;
		foreach ($gastos as $pago)
		{

			$pagototal += $pago->import;

		}
		$estadisticas['ingresos'] = array();
		$estadisticas['clientes'] = array();
		$dateStadistic            = $date->copy()->startOfMonth();

		for ($i = $dateStadistic->copy()->format('n'); $i < 21; $i++)
		{
			if ($i > 12)
			{
				$x = $dateStadistic->copy()->format('n');
			} else
			{
				$x = $i;
			}
			$ingresos = 0;
			$clientes = 0;

			$bookStadistic = \App\Book::whereIn('type_book', [2])
			                          ->where('room_id', $room->id)
			                          ->whereYear('start', '=', $dateStadistic->copy()->format('Y'))
			                          ->whereMonth('start', '=', $dateStadistic->copy()->format('m'))
			                          ->get();

			if (count($bookStadistic) > 0)
			{

				foreach ($bookStadistic as $key => $book)
				{

					$ingresos += $book->cost_total;
					$clientes += $book->pax;


				}

			}

			$estadisticas['ingresos'][$x] = round($ingresos);
			$estadisticas['clientes'][$x] = round($clientes);


			$dateStadistic->addMonth();
		}


		return view('backend.owned.index', [
			'user'          => \App\User::find(Auth::user()->id),
			'room'          => $room,
			'books'         => $books,
			'mes'           => $mes,
			'reservas'      => $arrayReservas,
			'date'          => $date,
			'days'          => $arrayDays,
			'arrayMonths'   => $arrayMonths,
			'total'         => $total,
			'apto'          => $apto,
			'park'          => $park,
			'lujo'          => $lujo,
			'pagos'         => $gastos,
			'pagototal'     => $pagototal,
			'mobile'        => new Mobile(),
			'estadisticas'  => $estadisticas,
			'inicio'        => $date,
			'roomscalendar' => \App\Rooms::where('nameRoom', $name)->get(),
			'arrayReservas' => $arrayReservas,
		]);


	}


	// Pagina de propietario

	public function operativaOwned()
	{

		return view('backend.owned.operativa', ['mobile' => new Mobile()]);
	}

	public function tarifasOwned($name)
	{
		$room = \App\Rooms::where('nameRoom', $name)->first();

		if ($room->owned == Auth::user()->id)
		{


			$room = \App\Rooms::where('nameRoom', $name)->first();

		} elseif (Auth::user()->role == 'admin')
		{

			$room = \App\Rooms::where('nameRoom', $name)->first();

		} else
		{

			return view('errors.owned-access');

		}
		return view('backend.owned.tarifa', [
			'mobile' => new Mobile(),
			'room'   => $room
		]);
	}

	public function descuentosOwned()
	{

		return view('backend.owned.descuento', ['mobile' => new Mobile()]);
	}

	public function fiscalidadOwned()
	{

		return view('backend.owned.fiscalidad', ['mobile' => new Mobile()]);
	}

	public function bloqOwned(Request $request)
	{

		$aux = str_replace('Abr', 'Apr', $request->input('fechas'));

		$date = explode('-', $aux);

		$start  = Carbon::createFromFormat('d M, y', trim($date[0]))->format('d/m/Y');
		$finish = Carbon::createFromFormat('d M, y', trim($date[1]))->format('d/m/Y');

		$diff = Carbon::createFromFormat('d M, y', trim($date[1]))->diffInDays(Carbon::createFromFormat('d M, y', trim($date[0])));

		$room = \App\Rooms::find($request->input('room'));

		$book = new \App\Book();

		if ($book->existDate($start, $finish, $room->id))
		{


			$bloqueo          = new \App\Customers();
			$bloqueo->user_id = Auth::user()->id;
			$bloqueo->name    = 'Bloqueo ' . Auth::user()->name;


			$bloqueo->save();


			$book->user_id     = Auth::user()->id;
			$book->customer_id = $bloqueo->id;
			$book->room_id     = $room->id;
			$book->start       = Carbon::CreateFromFormat('d/m/Y', $start)->format('Y-m-d');
			$book->finish      = Carbon::CreateFromFormat('d/m/Y', $finish)->format('Y-m-d');
			$book->nigths      = $diff;
			$book->type_book   = 7;
			$book->sup_limp    = ($room->sizeApto == 1) ? 30 : 50;
			$book->cost_limp   = ($room->sizeApto == 1) ? 30 : 40;
			$book->sup_park    = 0;
			$book->cost_park   = 0;
			$book->sup_lujo    = 0;
			$book->cost_lujo   = 0;
			$book->cost_apto   = 0;
			$book->cost_total  = ($room->sizeApto == 1) ? 30 : 40;
			$book->total_price = ($room->sizeApto == 1) ? 30 : 50;
			$book->real_price  = ($room->sizeApto == 1) ? 30 : 50;
			$book->total_ben   = $book->total_price - $book->cost_total;

			$book->inc_percent = round(($book->total_ben / $book->total_price) * 100, 2);
			$book->ben_jorge   = $book->total_ben * $room->typeAptos->PercentJorge / 100;
			$book->ben_jaime   = $book->total_ben * $room->typeAptos->PercentJaime / 100;

			LiquidacionController::setExpenseLimpieza(7, $room->id, $finish);

			if ($book->save())
			{
				/* Cliente */
				Mail::send(['html' => 'backend.emails.bloqueoPropietario'], ['book' => $book], function ($message) use ($book) {
					$message->from('bloqueos@apartamentosierranevada.net');
					$message->to('reservas@apartamentosierranevada.net');
					//$message->to('iavila@daimonconsulting.com');
					$message->subject('BLOQUEO ' . strtolower($book->user->name));
				});
			}

			return "Reserva Guardada";

		} else
		{
			return "No se puede guardar reserva";
		}
	}


	public function facturasOwned($name)
	{
		$room = \App\Rooms::where('nameRoom', $name)->first();

		if ($room->owned == Auth::user()->id)
		{


			$room = \App\Rooms::where('nameRoom', $name)->first();

		} elseif (Auth::user()->role == 'admin')
		{

			$room = \App\Rooms::where('nameRoom', $name)->first();

		} else
		{

			return view('errors.owned-access');

		}
		// Año
		if (empty($year))
		{
			$date = Carbon::now();
		} else
		{
			$year = Carbon::createFromFormat('Y', $year);
			$date = $year->copy();

		}

		if ($date->copy()->format('n') >= 9)
		{
			$date = new Carbon('first day of September ' . $date->copy()->format('Y'));
		} else
		{
			$date = new Carbon('first day of September ' . $date->copy()->subYear()->format('Y'));
		}

		$books = \App\Book::where('room_id', $room->id)->whereIn('type_book', [
			2,
			7,
			8
		])->where('start', '>=', $date->copy())->where('start', '<=', $date->copy()->addYear())->orderBy('start', 'ASC')
		                  ->get();


		return view('backend.owned.facturas', [
			'mobile' => new Mobile(),
			'books'  => $books,
			'date'   => $date,
			'room'   => $room
		]);
	}
}
