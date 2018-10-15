<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use \Carbon\Carbon;
use Auth;
use Mail;
use Illuminate\Routing\Controller;
use App\Classes\Mobile;
use App\Rooms;
use App\Book;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");

class BookController extends Controller
{
	private $bookRepository;

	public function __construct(BookRepository $bookRepository)
	{
		$this->bookRepository = $bookRepository;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($year = "")
	{
		if (empty($year))
		{
			$date = Carbon::now();
			if ($date->copy()->format('n') >= 9)
			{
				$date = new Carbon('first day of September ' . $date->copy()->format('Y'));
			} else
			{
				$date = new Carbon('first day of September ' . $date->copy()->subYear()->format('Y'));
			}

		} else
		{
			$year = Carbon::createFromFormat('Y', $year);
			$date = $year->copy();

		}

		$inicio = new Carbon('first day of September ' . $date->copy()->format('Y'));
		$start  = $inicio->copy();

		$booksCollection = \App\Book::with('customer')
		                            ->where('start', '>', $start->copy())
		                            ->where('finish', '<', $start->copy()->addYear())
		                            ->get();

		$books = $booksCollection->whereIn('type_book', [
			1,
			3,
			4,
			5,
			6,
			10,
			11
		])
		                         ->sortByDesc('created_at');

		$booksCount['pending']      = $booksCollection->where('type_book', 3)->count();
		$booksCount['special']      = $booksCollection->whereIn('type_book', [
			7,
			8
		])->count();
		$booksCount['confirmed']    = $booksCollection->where('type_book', 2)->count();
		$booksCount['blocked-ical'] = $booksCollection->whereIn('type_book', [
			11,
			12
		])->count();
		$booksCount['deletes']      = \App\Book::where('start', '>', $start->copy())
		                                       ->where('finish', '<', $start->copy()->addYear())->where('type_book', 0)
		                                       ->where('comment', 'LIKE', '%Antiguos cobros%')->count();
		$booksCount['checkin']      = $this->getCounters($start, 'checkin');
		$booksCount['checkout']     = $booksCount['confirmed'] - $booksCount['checkin'];

		$rooms         = \App\Rooms::where('state', '=', 1)->orderBy('order')->get();
		$roomscalendar = $rooms->filter(function ($room) {
			return $room->id >= 5;
		})->sortBy('order');

		$stripe           = StripeController::$stripe;
		$stripedsPayments = \App\Payments::where('comment', 'LIKE', '%stripe%')
		                                 ->where('created_at', '>=', Carbon::today()->toDateString())
		                                 ->get();

		// Notificaciones de alertas booking
		/*$notifications = \App\BookNotification::whereHas('book', function ($q) {
			return $q->where('type_book', '<>', 3)
			         ->orWhere('type_book', '<>', 5)
			         ->orWhere('type_book', '<>', 6);
		})->count();*/

		$mobile = new Mobile();

		$booksAlarms = \App\Book::where('start', '>=', Carbon::now())
		                        ->where('finish', '<=', Carbon::now()->copy()->addDays(15))
		                        ->where('type_book', 2)
		                        ->get();
		$alarms      = array();
		foreach ($booksAlarms as $key => $book)
		{
			$dateStart = Carbon::createFromFormat('Y-m-d', $book->start);
			$now       = Carbon::now();

			$payments = \App\Payments::where('book_id', $book->id)->get();


			if (count($payments) == 0)
			{
				if ($now->diffInDays($dateStart) <= 15):
					$alarms[] = $book;
				endif;
			} else
			{
				$total = 0;
				foreach ($payments as $key => $pay)
				{
					$total += $pay->import;
				}
				$percent = 100 / ($book->total_price / $total);
				if ($percent <= 25):
					if ($now->diffInDays($dateStart) <= 15):
						$alarms[] = $book;
					endif;
				endif;
			}


		}

		return view(
			'backend/planning/index',
			compact('books', 'mobile', 'stripe', 'inicio', 'rooms', 'roomscalendar', 'date',
			        'stripedsPayments', 'notifications', 'booksCount', 'alarms')
		);
	}

	public function newBook(Request $request)
	{
		$rooms  = \App\Rooms::where('state', '=', 1)->get();
		$extras = \App\Extras::all();
		return view('backend/planning/_nueva', compact('rooms', 'extras'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{

		$mobile = new Mobile();

		$aux = str_replace('Abr', 'Apr', $request->input('fechas'));

		$date = explode('-', $aux);

		$start  = Carbon::createFromFormat('d M, y', trim($date[0]))->format('d/m/Y');
		$finish = Carbon::createFromFormat('d M, y', trim($date[1]))->format('d/m/Y');
		$book   = new \App\Book();

		// 4 es el extra correspondiente a el obsequio
		$extraPrice = \App\Extras::find(4)->price;
		$extraCost  = \App\Extras::find(4)->cost;

		if ($request->input('from'))
		{

			//createacion del cliente
			$customer          = new \App\Customers();
			$customer->user_id = (Auth::check()) ? Auth::user()->id : 23;
			$customer->name    = $request->input('name');
			$customer->email   = $request->input('email');
			$customer->phone   = $request->input('phone');
			$customer->DNI     = ($request->input('dni')) ? $request->input('dni') : "";
			$customer->address = ($request->input('address')) ? $request->input('address') : "";
			$customer->country = ($request->input('country')) ? $request->input('country') : "";
			$customer->city    = ($request->input('city')) ? $request->input('city') : "";

			if ($customer->save())
			{
				//Creacion de la reserva
				$book->user_id       = 39;
				$book->customer_id   = $customer->id;
				$book->room_id       = $request->input('newroom');
				$book->start         = Carbon::createFromFormat('d/m/Y', $start);
				$book->finish        = Carbon::createFromFormat('d/m/Y', $finish);
				$book->comment       = ($request->input('comments')) ? ltrim($request->input('comments')) : "";
				$book->book_comments = ($request->input('book_comments')) ? ltrim($request->input('book_comments')) : "";
				$book->type_book     = ($request->input('status')) ? $request->input('status') : 3;
				$book->pax           = $request->input('pax');
				$book->real_pax      = $request->input('pax');
				$book->nigths        = $request->input('nigths');
				$book->agency        = $request->input('agency');
				$book->PVPAgencia    = ($request->input('agencia')) ? $request->input('agencia') : 0;

				$room = \App\Rooms::find($request->input('newroom'));
				if ($room->sizeApto == 1)
				{

					$book->sup_limp  = \App\Extras::find(2)->price;//30;
					$book->cost_limp = \App\Extras::find(2)->cost;//30;

					$book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths'));
					$book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths'));
				} elseif ($room->sizeApto == 2)
				{

					$book->sup_limp  = \App\Extras::find(1)->price;//50;
					$book->cost_limp = \App\Extras::find(1)->cost;//40;

					$book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths'));
					$book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths'));
				} elseif ($room->sizeApto == 3 || $room->sizeApto == 4)
				{

					$book->sup_limp  = \App\Extras::find(3)->price;//100;
					$book->cost_limp = \App\Extras::find(3)->cost;//70;

					$book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths'), $room->id, 3);
					$book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths'), $room->id, 3);
				}

				$book->type_park = $request->input('parking');

				$book->type_luxury = $request->input('type_luxury');
				$book->sup_lujo    = $this->getPriceLujo($request->input('type_luxury'));
				$book->cost_lujo   = $this->getCostLujo($request->input('type_luxury'));
				// if ($room->typeApto == 3 || $room->typeApto == 1) {
				//     $book->cost_apto     = 0;
				// }else{
				$book->cost_apto = $this->getCostBook($start, $finish, $request->input('pax'), $request->input('newroom')) + $book->cost_limp;
				// }

				$book->cost_total = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $extraCost;

				$book->total_price = $this->getPriceBook($start, $finish, $request->input('pax'), $request->input('newroom')) + $book->sup_park + $book->sup_lujo + $book->sup_limp;

				$book->real_price = $this->getPriceBook($start, $finish, $request->input('pax'), $request->input('newroom')) + $book->sup_park + $book->sup_lujo + $book->sup_limp;


				$book->total_ben = $book->total_price - $book->cost_total;


				$book->extraPrice = $extraPrice;
				$book->extraCost  = $extraCost;
				//Porcentaje de beneficio
				$book->inc_percent = round(($book->total_ben / $book->total_price) * 100, 2);
				$book->ben_jorge   = $book->total_ben * $book->room->typeAptos->PercentJorge / 100;
				$book->ben_jaime   = $book->total_ben * $book->room->typeAptos->PercentJaime / 100;
				$book->promociones = 0;

				if ($book->save())
				{
					/* Notificacion via email */
					if ($customer->email)
					{
						MailController::sendEmailBookSuccess($book, 1);
						return view('frontend.bookStatus.bookOk');
					}

				}
			}
		} else
		{
			$isReservable = 0;
			if (in_array($request->input('status'), [
				1,
				2,
				4,
				7,
				8
			]))
			{
				if ($book->existDate($start, $finish, $request->input('newroom')))
				{
					$isReservable = 1;
				}
			} else
			{
				$isReservable = 1;
			}

			if ($isReservable == 1)
			{

				//createacion del cliente
				$customer          = new \App\Customers();
				$customer->user_id = (Auth::check()) ? Auth::user()->id : 23;
				$customer->name    = $request->input('name');
				$customer->email   = $request->input('email');
				$customer->phone   = ($request->input('phone')) ? $request->input('phone') : "";
				$customer->DNI     = ($request->input('dni')) ? $request->input('dni') : "";
				$customer->address = ($request->input('address')) ? $request->input('address') : "";
				$customer->country = ($request->input('country')) ? $request->input('country') : "";
				$customer->city    = ($request->input('city')) ? $request->input('city') : "";

				if ($customer->save())
				{
					//Creacion de la reserva
					$room = \App\Rooms::find($request->input('newroom'));

					$book->user_id             = $customer->user_id;
					$book->customer_id         = $customer->id;
					$book->room_id             = $request->input('newroom');
					$book->start               = Carbon::createFromFormat('d/m/Y', $start);
					$book->finish              = Carbon::createFromFormat('d/m/Y', $finish);
					$book->comment             = ($request->input('comments')) ? $request->input('comments') : "";
					$book->book_comments       = ($request->input('book_comments')) ? $request->input('book_comments') : "";
					$book->book_owned_comments = ($request->input('book_owned_comments')) ? $request->input('book_owned_comments') : "";
					$book->type_book           = ($request->input('status')) ? $request->input('status') : 3;
					$book->pax                 = $request->input('pax');
					$book->real_pax            = ($request->input('real_pax')) ? $request->input('real_pax') : $request->input('pax');
					$book->nigths              = $request->input('nigths');
					$book->agency              = ($request->input('agency')) ? $request->input('agency') : 0;
					$book->type_park           = $request->input('parking');
					$book->type_luxury         = $request->input('type_luxury');

					if ($request->input('status') == 8)
					{
						$book->PVPAgencia  = ($request->input('agencia')) ? $request->input('agencia') : 0;
						$book->sup_limp    = 0;
						$book->cost_limp   = 0;
						$book->sup_park    = 0;
						$book->cost_park   = 0;
						$book->sup_lujo    = 0;
						$book->cost_lujo   = 0;
						$book->cost_apto   = 0;
						$book->cost_total  = 0;
						$book->total_price = 0;
						$book->real_price  = 0;
						$book->total_ben   = 0;

						$book->inc_percent = 0;
						$book->ben_jorge   = 0;
						$book->ben_jaime   = 0;

						/* Asiento automatico para reservas propietarios*/
						LiquidacionController::setExpenseLimpieza($request->input('status'), $room->id, $finish);
						/* Asiento automatico */

					} elseif ($request->input('status') == 7)
					{
						$book->PVPAgencia  = ($request->input('agencia')) ? $request->input('agencia') : 0;
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
						$book->ben_jorge   = $book->total_ben * $book->room->typeAptos->PercentJorge / 100;
						$book->ben_jaime   = $book->total_ben * $book->room->typeAptos->PercentJaime / 100;

						/* Asiento automatico para reservas subcomunidad*/
						LiquidacionController::setExpenseLimpieza($request->input('status'), $room->id, $finish);
						/* Asiento automatico */


					} else
					{

						$book->PVPAgencia = ($request->input('agencia')) ? $request->input('agencia') : 0;
						$book->sup_limp   = ($room->sizeApto == 1) ? 30 : 50;
						$room             = \App\Rooms::find($request->input('newroom'));
						if ($room->sizeApto == 1)
						{

							$book->sup_limp  = \App\Extras::find(2)->price;//30;
							$book->cost_limp = \App\Extras::find(2)->cost;//30;

							$book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths'));
							$book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths'));
						} elseif ($room->sizeApto == 2)
						{

							$book->sup_limp  = \App\Extras::find(1)->price;//50;
							$book->cost_limp = \App\Extras::find(1)->cost;//40;

							$book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths'));
							$book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths'));
						} elseif ($room->sizeApto == 3 || $room->sizeApto == 4)
						{

							$book->sup_limp  = \App\Extras::find(3)->price;//100;
							$book->cost_limp = \App\Extras::find(3)->cost;//70;

							$book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths'), $room->id, 3);
							$book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths'), $room->id, 3);
						}

						$book->sup_lujo  = $this->getPriceLujo($request->input('type_luxury'));
						$book->cost_lujo = $this->getCostLujo($request->input('type_luxury'));

						// if ($room->typeApto == 3 || $room->typeApto == 1) {
						//     $book->cost_apto     = 0;
						// }else{
						$book->cost_apto = ($request->input('costApto')) ? $request->input('costApto') : $this->getCostBook($start, $finish, $request->input('pax'), $request->input('newroom')) + $book->cost_limp;
						// }
						$book->cost_total = ($request->input('cost')) ? $request->input('cost') : $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $extraCost;

						$book->total_price = $request->input('total');

						$book->real_price = $this->getPriceBook($start, $finish, $request->input('pax'), $request->input('newroom')) + $book->sup_park + $book->sup_lujo + $book->sup_limp;

						$book->total_ben = $book->total_price - $book->cost_total;

						$book->inc_percent = round(($book->total_ben / $book->total_price) * 100, 2);
						$book->ben_jorge   = $book->total_ben * $book->room->typeAptos->PercentJorge / 100;
						$book->ben_jaime   = $book->total_ben * $book->room->typeAptos->PercentJaime / 100;
					}


					$book->extraPrice  = $extraPrice;
					$book->extraCost   = $extraCost;
					$book->schedule    = ($request->input('schedule')) ? $request->input('schedule') : 0;
					$book->scheduleOut = ($request->input('scheduleOut')) ? $request->input('scheduleOut') : 12;
					$book->promociones = ($request->input('promociones')) ? $request->input('promociones') : 0;

					if ($book->save())
					{

						/* Creamos las notificaciones de booking */
						/* Comprobamos que la room de la reserva este cedida a booking.com */
						if ($room->isAssingToBooking())
						{
							$notification          = new \App\BookNotification();
							$notification->book_id = $book->id;
							$notification->save();
						}


						// MailController::sendEmailBookSuccess( $book, 0);
						return redirect('admin/reservas');

					}
				}

			} else
			{


				return view('backend/planning/_formBook', [
					'request' => (object) $request->input(),
					'book'    => new \App\Book(),
					'rooms'   => \App\Rooms::where('state', '=', 1)->get(),
					'mobile'  => $mobile
				]);
			}
		}


	}

	public function update(Request $request, $id)
	{
		$book = \App\Book::with('payments')->find($id);

		$totalpayment = $book->sum_payments;

		// We are passing wrong data from this to view by using $book data, in order to correct data
		// an AJAX call has been made after rendering the page.

		return view('backend/planning/update', [
			'book'         => $book,
			'rooms'        => \App\Rooms::where('state', '=', 1)->orderBy('order')->get(),
			'extras'       => \App\Extras::all(),
			'start'        => Carbon::createFromFormat('Y-m-d', $book->start)->format('d M,y'),
			'payments'     => $book->payments,
			'typecobro'    => new \App\Book(),
			'totalpayment' => $totalpayment,
			'mobile'       => new Mobile(),
			'stripe'       => StripeController::$stripe,
		]);
	}


	//Funcion para actualizar la reserva
	public function saveUpdate(Request $request, $id)
	{
		$computedData = json_decode($request->input('computed_data'));
		$aux          = str_replace('Abr', 'Apr', $request->input('fechas'));

		$date = explode('-', $aux);

		$start  = Carbon::createFromFormat('d M, y', trim($date[0]))->format('d/m/Y');
		$finish = Carbon::createFromFormat('d M, y', trim($date[1]))->format('d/m/Y');

		$customer          = \App\Customers::find($request->input('customer_id'));
		$customer->DNI     = ($request->input('dni')) ? $request->input('dni') : "";
		$customer->address = ($request->input('address')) ? $request->input('address') : "";
		$customer->country = ($request->input('country')) ? $request->input('country') : "";
		$customer->city    = ($request->input('city')) ? $request->input('city') : "";
		$customer->save();

		$book = \App\Book::find($id);
		if ($book->existDateOverrride($start, $finish, $request->input('newroom'), $id))
		{

			$room = \App\Rooms::find($request->input('newroom'));

			$book->user_id     = Auth::user()->id;
			$book->customer_id = $request->input('customer_id');
			$book->room_id     = $request->input('newroom');

			$book->start               = Carbon::createFromFormat('d/m/Y', $start);
			$book->finish              = Carbon::createFromFormat('d/m/Y', $finish);
			$book->comment             = ltrim($request->input('comments'));
			$book->book_comments       = ltrim($request->input('book_comments'));
			$book->book_owned_comments = ($request->input('book_owned_comments')) ? $request->input('book_owned_comments') : "";
			$book->pax                 = $request->input('pax');
			$book->real_pax            = $request->input('real_pax');
			$book->nigths              = $request->input('nigths');

			$book->sup_park  = $computedData->totales->parking;
			$book->sup_limp  = $computedData->totales->limp;
			$book->cost_limp = $computedData->costes->limp;
			$book->cost_park = $request->input('costParking');

			$book->type_park  = $request->input('parking');
			$book->agency     = $request->input('agency');
			$book->PVPAgencia = $request->input('agencia') ? : 0;

			$book->type_luxury = $request->input('type_luxury');
			$book->sup_lujo    = $computedData->totales->lujo;
			$book->cost_lujo   = $computedData->costes->lujo;

			$book->cost_apto  = $request->input('costApto');
			$book->cost_total = $request->input('cost');

			$book->total_ben = $request->input('beneficio');
			$book->extra     = $request->input('extra');

			$book->extraPrice  = Rooms::GIFT_PRICE;
			$book->extraCost   = Rooms::GIFT_COST;
			$book->schedule    = $request->input('schedule');
			$book->scheduleOut = $request->input('scheduleOut');
			$book->promociones = ($request->input('promociones')) ? $request->input('promociones') : 0;

			$book->total_price = $request->input('total'); // This can be modified in frontend
			$book->real_price  = $computedData->calculated->real_price; // This cannot be modified in frontend
			$book->inc_percent = $book->profit_percentage;
			$book->ben_jorge   = $book->getJorgeProfit();
			$book->ben_jaime   = $book->getJaimeProfit();

			if ($book->save())
			{

				if ($book->room->isAssingToBooking())
				{

					$isAssigned = \App\BookNotification::where('book_id', $book->id)->get();

					if (count($isAssigned) == 0)
					{
						$notification          = new \App\BookNotification();
						$notification->book_id = $book->id;
						$notification->save();
					}
				} else
				{
					$deleted = \App\BookNotification::where('book_id', $book->id)->delete();
				}

				return [
					'status'   => 'success',
					'title'    => 'OK',
					'response' => "ACTUALIZACION CORRECTA"
				];
			}
		} else
		{

			return [
				'status'   => 'danger',
				'title'    => 'ERROR',
				'response' => "NO HAY DISPONIBILIDAD EN EL PISO PARA LAS FECHAS SELECCIONADAS"
			];
		}
	}


	public function changeStatusBook(Request $request, $id)
	{
		if (isset($request->status) && !empty($request->status))
		{

			$book   = \App\Book::find($id);
			$start  = Carbon::createFromFormat('Y-m-d', $book->start);
			$finish = Carbon::createFromFormat('Y-m-d', $book->finish);

			$isReservable = 0;

			if (in_array($request->status, [
				1,
				2,
				4,
				5,
				7,
				8
			]))
			{

				if ($book->existDateOverrride($start->format('d/m/Y'), $finish->format('d/m/Y'), $book->room_id, $id))
				{
					$isReservable = 1;
				}

			} else
			{
				$isReservable = 1;
			}


			if ($isReservable == 1)
			{

				return $book->changeBook($request->status, "", $book);
			} else
			{

				return [
					'status'   => 'danger',
					'title'    => 'Peligro',
					'response' => "No puedes cambiar el estado"
				];
			}

		}
	}


	public function changeBook(Request $request, $id)
	{

		if (isset($request->room) && !empty($request->room))
		{

			$book = \App\Book::find($id);
			return $book->changeBook("", $request->room, $book);
		}

		if (isset($request->status) && !empty($request->status))
		{

			$book = \App\Book::find($id);
			return $book->changeBook($request->status, "", $book);

		} else
		{
			return [
				'status'   => 'danger',
				'title'    => 'Error',
				'response' => 'No hay datos para cambiar, por favor intentalo de nuevo'
			];
		}
	}


	/**
	 * @param        $park             Parking Option (Yes, No, Free, 50%)
	 * @param        $noches           Book Nights
	 * @param string $room             Room ID
	 * @return float|int
	 */
	public function getPricePark($park, $noches, $room = "")
	{

		$priceParking = 0;
		switch ($park)
		{
			case 1: // Yes
				$priceParking = Rooms::PARKING_PRICE * $noches;
				break;
			case 2: // No
			case 3: // Free
				$priceParking = 0;
				break;
			case 4: // 50%
				$priceParking = (Rooms::PARKING_PRICE * $noches) / 2;
				break;
		}
		if ($room != "")
		{
			if ($room == 150)
			{
				$priceParking = $priceParking * 3;
			}
			if ($room == 149 || $room == 153)
			{
				$priceParking = $priceParking * 2;
			}
		}
		return $priceParking;

	}

	public function getCostPark($park, $noches, $room = "")
	{

		$costParking = 0;
		switch ($park)
		{
			case 1: // Yes
				$costParking = Rooms::PARKING_COST * $noches;
				break;
			case 2: // No
			case 3: // Free
				$costParking = 0;
				break;
			case 4: // 50%
				$costParking = (Rooms::PARKING_COST * $noches) / 2;
				break;
		}

		if ($room != "" && ($room == 150 || $room == 149 || $room == 153))
		{
			$costParking = $costParking * 2;
		}


		return $costParking;

	}

	public function getPriceLujo($typeLuxury)
	{
		$priceLuxury = 0;
		if ($typeLuxury > 4)
		{
			$priceLuxury = $typeLuxury;
		} else
		{
			switch ($typeLuxury)
			{
				case 1: // Yes
					$priceLuxury = Rooms::LUXURY_PRICE;
					break;
				case 2: // No
				case 3: // Free
					$priceLuxury = 0;
					break;
				case 4: // 50%
					$priceLuxury = Rooms::LUXURY_PRICE / 2;
					break;
			}
		}
		return $priceLuxury;
	}

	/**
	 * @param $typeLuxury
	 * @return float|int
	 */
	public function getCostLujo($typeLuxury)
	{
		$costLuxury = 0;
		if ($typeLuxury > 4)
		{
			$costLuxury = $typeLuxury - 10;
		} else
		{
			switch ($typeLuxury)
			{
				case 1: // Yes
					$costLuxury = Rooms::LUXURY_COST;
					break;
				case 2: // No
					$costLuxury = 0;
					break;
				case 3: // Free
					$costLuxury = 0;
					break;
				case 4: // 50%
					$costLuxury = Rooms::LUXURY_COST / 2;
					break;
			}
		}

		return $costLuxury;
	}

	public function getCostBook($start, $finish, $pax, $room)
	{
		$start     = Carbon::createFromFormat('d/m/Y', $start);
		$finish    = Carbon::createFromFormat('d/m/Y', $finish);
		$countDays = $finish->diffInDays($start);

		$paxPerRoom = Rooms::getPaxRooms($pax, $room);

		if ($paxPerRoom > $pax) {
			$pax = $paxPerRoom;
		}
		$costBook = 0;
		$counter  = $start->copy();
		for ($i = 1; $i <= $countDays; $i++) {
			$date = $counter->copy()->format('Y-m-d');

			$seasonActive = $this->bookRepository->getSeasonType($date);
			$costs = $this->bookRepository->getCostsFromSeason($seasonActive, $date);

			foreach ($costs as $precio) {
				$costBook = $costBook + $precio->cost;
			}

			$counter->addDay();
		}
		return $costBook;
	}

	public function getPriceBook($start, $finish, $pax, $room)
	{
		$start     = Carbon::createFromFormat('d/m/Y', $start);
		$finish    = Carbon::createFromFormat('d/m/Y', $finish);
		$countDays = $finish->diffInDays($start);


		$paxPerRoom = \App\Rooms::getPaxRooms($pax, $room);

		$room = \App\Rooms::find($room);

		$pax = $pax;
		if ($paxPerRoom > $pax)
		{
			$pax = $paxPerRoom;
		}
		$counter   = $start->copy();
		$priceBook = 0;
		for ($i = 1; $i <= $countDays; $i++)
		{
			$date = $counter->format('Y-m-d');
			$seasonActive = $this->bookRepository->getSeasonType($date);
			$costs = $this->bookRepository->getCostsFromSeason($seasonActive, $date);

			foreach ($costs as $precio)
			{
				$priceBook = $priceBook + $precio->price;
			}
			$counter->addDay();
		}

		return $priceBook;
	}


	// Funcion para la migracion de la base antigua  a la nuevas "cobros"
	public function getCobrosBD()
	{
		$cobros = DB::connection('apartamento')->table('cobros')->orderBy('ID', 'ASC')->get();

		echo "<pre>";
		foreach ($cobros as $cobro)
		{
			$payment = \App\Payments::find($cobro->ID);
			if (count($payment) > 0)
			{
				echo "Ya existe este cobro  " . $cobro->ID . " <br>";
			} else
			{
				$payment              = new \App\Payments();
				$payment->id          = $cobro->ID;
				$payment->book_id     = $cobro->bookID;
				$payment->datePayment = $cobro->dateCobro;
				$payment->import      = $cobro->import;
				$payment->type        = $cobro->type;
				$payment->comment     = $cobro->Comment;

				if ($payment->save())
				{
					echo "Insertado ID " . $cobro->ID . "<br>";
				} else
				{
					# code...
				}
			}
		}
	}

	//Funcion para coger la reserva mobil
	public function tabReserva($id)
	{
		$book = \App\Book::find($id);
		return $book;
	}

	//Funcion para Cobrar desde movil
	public function cobroBook($id)
	{
		$book     = \App\Book::find($id);
		$payments = \App\Payments::where('book_id', $id)->get();
		$pending  = 0;

		foreach ($payments as $payment)
		{
			$pending += $payment->import;
		}

		return view('backend/planning/_updateCobro', [
			'book'     => $book,
			'pending'  => $pending,
			'payments' => $payments,
		]);
	}

	// Funcion para Cobrar
	public function saveCobro(Request $request)
	{
		$payment = new \App\Payments();

		$payment->book_id     = $request->id;
		$payment->datePayment = Carbon::CreateFromFormat('d-m-Y', $request->fecha);
		$payment->import      = $request->import;
		$payment->type        = $request->tipo;

		if ($payment->save())
		{
			return redirect()->action('BookController@index');
		}

	}

	// Funcion para elminar cobro
	public function deleteCobro($id)
	{
		$payment = \App\Payments::find($id);

		if ($payment->delete())
		{
			return redirect()->back();
		}

	}

	//Funcion para gguardar Fianza
	public function saveFianza(Request $request)
	{
		$fianza = new \App\Bail();

		$fianza->id_book    = $request->id;
		$fianza->date_in    = Carbon::CreateFromFormat('d-m-Y', $request->fecha);
		$fianza->import_in  = $request->fianza;
		$fianza->comment_in = $request->comentario;
		$fianza->type       = $request->tipo;

		if ($fianza->save())
		{
			return redirect()->action('BookController@index');
		}

	}


	public function emails($id)
	{
		$book = \App\Book::find($id);

		return view('backend.emails.comprobar-fechas', ['book' => $book]);

		Mail::send('backend.emails.jaime', ['book' => $book], function ($message) use ($book) {
			$message->from('reservas@apartamentosierranevada.net');

			$message->to($book->customer->email);
			$message->subject('Correo a Jaime');
		});

		$book->send = 1;
		$book->save();

	}

	public function sendEmail(Request $request)
	{

		$book = \App\Book::find($request->input('id'));

		Mail::send('backend.emails.contestadoAdvanced', ['body' => nl2br($request->input('textEmail')),], function ($message) use ($book) {
			$message->from('reservas@apartamentosierranevada.net');

			$message->to($book->customer->email);
			$message->subject('Disponibilidad para tu reserva');
		});

		// $book->send = 1;
		$book->type_book = 5;
		if ($book->save())
		{
			return 1;
		} else
		{
			return 0;
		}

	}

	public function ansbyemail($id)
	{
		$book = \App\Book::find($id);

		return view('backend/planning/_answerdByEmail', [
			'book'   => $book,
			'mobile' => new Mobile()
		]);
	}

	public function delete($id)
	{

		try
		{
			$book = \App\Book::find($id);

			foreach ($book->notifications as $key => $notification)
			{
				$notification->delete();
			}


			if (count($book->pago) > 0)
			{
				foreach ($book->pago as $index => $pago)
				{
					$book->comment .= "\n Antiguos cobros: \n Fecha: " . $pago->datePayment . ", Importe: " .
						$pago->import . "\n Tipo de pago: " . $pago->type;

					if ($pago->type == 0 || $pago->type == 1)
					{
						$move = \App\Cashbox::where('date', $pago->datePayment)->where('import', $pago->import)
						                    ->first();
						if (count($move) > 0)
						{
							$move->delete();
						}

					} elseif ($pago->type == 2 || $pago->type == 3)
					{
						$move = \App\Bank::where('date', $pago->datePayment)->where('import', $pago->import)->first();
						if (count($move) > 0)
						{
							$move->delete();
						}
					}


					$pago->delete();
				}
			}

			if ($book->type_book == 7 || $book->type_book == 8)
			{
				$expenseLimp = \App\Expenses::where('date', $book->finish)
					// ->where('import', $book->total_price)
					                        ->where('concept', "LIMPIEZA RESERVA PROPIETARIO. " . $book->room->nameRoom)
				                            ->first();

				if (count($expenseLimp) > 0)
					$expenseLimp->delete();
			}


			$book->type_book = 0;

			if ($book->save())
			{
				return [
					'status'   => 'success',
					'title'    => 'OK',
					'response' => "Reserva enviada a eliminadas"
				];

			}


		} catch (Exception $e)
		{

			return [
				'status'   => 'danger',
				'title'    => 'Error',
				'response' => "No se ha podido borrar la reserva error: " . $e->message()
			];

		}

	}

	/* FUNCION DUPLICADA DE HOMECONTROLLER PARA CALCULAR LA RESERVA DESDE EL ADMIN */


	static function getTotalBook(Request $request)
	{

		$aux = str_replace('Abr', 'Apr', $request->input('fechas'));

		$date = explode('-', $aux);

		$start     = Carbon::createFromFormat('d M, y', trim($date[0]));
		$finish    = Carbon::createFromFormat('d M, y', trim($date[1]));
		$countDays = $finish->diffInDays($start);

		if ($request->input('apto') == '2dorm' && $request->input('luxury') == 'si')
		{
			$roomAssigned = 115;
			$typeApto     = "2 DORM Lujo";
			$limp         = (int) \App\Extras::find(1)->price;
		} elseif ($request->input('apto') == '2dorm' && $request->input('luxury') == 'no')
		{
			$roomAssigned = 122;
			$typeApto     = "2 DORM estandar";
			$limp         = (int) \App\Extras::find(1)->price;
		} elseif ($request->input('apto') == 'estudio' && $request->input('luxury') == 'si')
		{
			$roomAssigned = 138;
			$limp         = (int) \App\Extras::find(2)->price;
			$typeApto     = "Estudio Lujo";

		} elseif ($request->input('apto') == 'estudio' && $request->input('luxury') == 'no')
		{
			$roomAssigned = 110;
			$typeApto     = "Estudio estandar";
			$limp         = (int) \App\Extras::find(2)->price;
		} elseif ($request->input('apto') == 'chlt' && $request->input('luxury') == 'no')
		{
			$roomAssigned = 144;
			$typeApto     = "CHALET los pinos";
			$limp         = (int) \App\Extras::find(1)->price;
		} elseif (($request->input('apto') == '3dorm' && $request->input('luxury') == 'si') || $request->input('apto') == '3dorm' && $request->input('luxury') == 'no')
		{
			/* Rooms para grandes capacidades */
			if ($request->input('quantity') >= 8 && $request->input('quantity') <= 10)
			{
				$roomAssigned = 153;
			} else
			{
				$roomAssigned = 149;
			}

			$typeApto = "3 DORM Lujo";
			$limp     = (int) \App\Extras::find(3)->price;
		}


		$paxPerRoom = \App\Rooms::getPaxRooms($request->input('quantity'), $roomAssigned);

		$pax = $request->input('quantity');

		if ($paxPerRoom > $request->input('quantity'))
		{
			$pax = $paxPerRoom;
		}

		$price   = 0;
		$auxDate = $start->copy();
		for ($i = 1; $i <= $countDays; $i++)
		{

			$seasonActive = \App\Seasons::getSeason($auxDate->copy()->format('Y-m-d'));
			if ($seasonActive == null)
			{
				$seasonActive = 0;
			}
			$prices = \App\Prices::where('season', $seasonActive)
			                     ->where('occupation', $pax)->get();

			foreach ($prices as $precio)
			{
				$price = $price + $precio->price;
			}
			$auxDate->addDay();
		}

		if ($request->input('parking') == 'si')
		{
			$priceParking = 20 * $countDays;
			$parking      = 1;
		} else
		{
			$priceParking = 0;
			$parking      = 2;
		}

		if ($typeApto == "3 DORM Lujo")
		{
			if ($roomAssigned == 153 || $roomAssigned = 149)
				$priceParking = $priceParking * 2;
			else
				$priceParking = $priceParking * 3;
		}

		if ($request->input('luxury') == 'si')
		{
			$luxury = 50;
		} else
		{
			$luxury = 0;
		}

		$total = $price + $priceParking + $limp + $luxury;

		if ($seasonActive != 0)
		{
			return view('backend.bookStatus.response', [
				'id_apto'      => $roomAssigned,
				'pax'          => $pax,
				'nigths'       => $countDays,
				'apto'         => $typeApto,
				'name'         => $request->input('name'),
				'phone'        => $request->input('phone'),
				'email'        => $request->input('email'),
				'start'        => $start,
				'finish'       => $finish,
				'parking'      => $parking,
				'priceParking' => $priceParking,
				'luxury'       => $luxury,
				'total'        => $total,
			]);

		} else
		{
			return view('backend.bookStatus.bookError');
		}
	}

	public function searchByName(Request $request)
	{
		if ($request->searchString == '')
		{
			return response()->json('', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
		}

		$year     = $request->input('year', date('Y'));
		$dateFrom = (new Carbon("first day of September {$year}"));
		$dateTo   = $dateFrom->copy()->addYear();

		$customerIds = \App\Customers::where('name', 'LIKE', '%' . $request->searchString . '%')
		                             ->pluck('id')->toArray();

		if (count($customerIds) <= 0)
		{
			return "<h2>No hay reservas para este término '" . $request->searchString . "'</h2>";
		}

		$books = \App\Book::with('payments')
		                  ->whereIn('customer_id', $customerIds)
		                  ->where('start', '>=', $dateFrom)
		                  ->where('start', '<=', $dateTo)
		                  ->where('type_book', '!=', 9)
		                  ->where('type_book', '!=', 0)
		                  ->orderBy('start', 'ASC')
		                  ->get();

		$payments = [];
		foreach ($books as $book)
		{
			$payments[$book->id] = $book->payments->pluck('import')->sum();
		}

		return view('backend/planning/responses/_resultSearch', [
			'books'   => $books,
			'payment' => $payments
		]);

	}

	public function changeCostes()
	{
		$books = \App\Book::all();


		foreach ($books as $book)
		{


			if ($book->room->typeAptos->id == 1 || $book->room->typeAptos->id == 3)
			{

				$book->cost_total = $book->cost_limp + $book->cost_park + $book->cost_lujo + $book->extraCost;
				$book->total_ben  = $book->total_price - $book->cost_total;
				$book->cost_apto  = 0;


			}
			$book->ben_jorge = $book->total_ben * $book->room->typeAptos->PercentJorge / 100;
			$book->ben_jaime = $book->total_ben * $book->room->typeAptos->PercentJaime / 100;

			$book->save();
		}


	}


	public function getTableData(Request $request)
	{
		$mobile = new Mobile();


		if (empty($request->year))
		{
			$date = Carbon::now();
			if ($date->copy()->format('n') >= 9)
			{
				$date = new Carbon('first day of September ' . $date->copy()->format('Y'));
			} else
			{
				$date = new Carbon('first day of September ' . $date->copy()->subYear()->format('Y'));
			}

		} else
		{
			$year = Carbon::createFromFormat('Y', $request->year);
			$date = $year->copy();

		}

		$date = new Carbon('first day of September ' . $date->copy()->format('Y'));

		// echo "<pre>";
		// print_r($date);
		// die();

		switch ($request->type)
		{
			case 'pendientes':
				$books = \App\Book::where('start', '>', $date->copy())
				                  ->where('start', '<', $date->copy()->addYear())
				                  ->whereIn('type_book', [
					                  1,
					                  3,
					                  4,
					                  5,
					                  6,
					                  10,
					                  11
				                  ])
				                  ->orderBy('created_at', 'DESC')
				                  ->get();
				break;
			case 'especiales':
				$books = \App\Book::where('start', '>', $date->copy()->subMonth())
				                  ->where('start', '<', $date->copy()->addYear())
				                  ->whereIn('type_book', [
					                  7,
					                  8
				                  ])
				                  ->orderBy('created_at', 'DESC')
				                  ->get();
				break;
			case 'confirmadas':
				$books = \App\Book::where('start', '>', $date->copy()->subMonth())
				                  ->where('start', '<', $date->copy()->addYear())
				                  ->whereIn('type_book', [2])
				                  ->orderBy('created_at', 'DESC')
				                  ->get();
				break;
			case 'checkin':
				$dateX = Carbon::now();
				$books = \App\Book::where('start', '>', $dateX->copy()->subDays(3))
				                  ->where('start', '<', $dateX->copy()->addYear())
				                  ->where('type_book', 2)
				                  ->orderBy('start', 'ASC')
				                  ->get();
				break;
			case 'checkout':
				$dateX = Carbon::now();

				$books = \App\Book::where('start', '>', $dateX->copy()->subDays(3))
				                  ->where('start', '<', $dateX->copy()->addYear())
				                  ->where('type_book', 2)
				                  ->orderBy('start', 'ASC')
				                  ->get();
			case 'eliminadas':
				$books = \App\Book::where('start', '>', $date->copy()->subDays(3))
				                  ->where('start', '<', $date->copy()->addYear())
				                  ->where('type_book', 0)
				                  ->orderBy('updated_at', 'DESC')
				                  ->get();
				break;
			case 'blocked-ical':
				$books = \App\Book::where('start', '>', $date->copy()->subDays(3))
				                  ->where('finish', '<', $date->copy()->addYear())
				                  ->whereIn('type_book', [
					                  11,
					                  12
				                  ])
				                  ->orderBy('updated_at', 'DESC')
				                  ->get();
				break;
		}

		$rooms = \App\Rooms::where('state', '=', 1)->orderBy('order')->get();
		$type  = $request->type;

		if ($request->type == 'confirmadas' || $request->type == 'checkin')
		{
			$payment = array();
			foreach ($books as $key => $book)
			{
				$payment[$book->id] = 0;
				$payments           = \App\Payments::where('book_id', $book->id)->get();
				if (count($payments) > 0)
				{

					foreach ($payments as $key => $pay)
					{
						$payment[$book->id] += $pay->import;
					}

				}

			}

			return view('backend/planning/_table', compact('books', 'rooms', 'type', 'mobile', 'payment'));
		} else
		{

			return view('backend/planning/_table', compact('books', 'rooms', 'type', 'mobile'));
		}
	}


	public function getLastBooks(Request $request)
	{
		$mobile = new Mobile();

		if ($request->year)
		{
			if ($now->copy()->format('n') >= 9)
			{
				$date = new Carbon('first day of September ' . $now->copy()->format('Y'));
			} else
			{
				$date = new Carbon('first day of September ' . $now->copy()->subYear()->format('Y'));
			}
		} else
		{
			$date = new Carbon('first day of September ' . $request->year);
		}
		$booksAux = array();
		foreach (\App\Payments::orderBy('id', 'DESC')->get() as $key => $payment)
		{
			if (!in_array($payment->book_id, $booksAux))
			{
				$booksAux[] = $payment->book_id;
			}
			if (count($booksAux) == 10)
			{
				break;
			}
		}
		$books = array();
		for ($i = 0; $i < count($booksAux); $i++)
		{
			$books[] = \App\Book::find($booksAux[$i]);
		}

		return view('backend.planning._lastBookPayment', compact('books', 'mobile'));

	}

	public function getCalendarBooking(Request $request)
	{
		$mobile = new Mobile();

		$arrayMonths = array();
		$arrayDays   = array();
		if (empty($year))
		{
			$date = Carbon::now();
		} else
		{
			$year = Carbon::createFromFormat('Y', $year);
			$date = $year->copy();

		}
		$firstDayOfTheYear = new Carbon('first day of September ' . $date->copy()->format('Y'));
		$book              = new \App\Book();

		for ($i = 1; $i <= 12; $i++)
		{
			$mes[$firstDayOfTheYear->copy()->format('n')] = $firstDayOfTheYear->copy()->format('M Y');

			$startMonth = $firstDayOfTheYear->copy()->startOfMonth();
			$endMonth   = $firstDayOfTheYear->copy()->endOfMonth();
			$countDays  = $endMonth->diffInDays($startMonth);
			$day        = $startMonth;


			$arrayMonths[$firstDayOfTheYear->copy()->format('n')] = $day->copy()->format('t');


			for ($j = 1; $j <= $day->copy()->format('t'); $j++)
			{

				$arrayDays[$firstDayOfTheYear->copy()->format('n')][$j] = $book->getDayWeek($day->copy()->format('w'));
				$day                                                    = $day->copy()->addDay();

			}

			$firstDayOfTheYear->addMonth();

		}


		$dateX = $date->copy();
		$days  = $arrayDays;

		return view('backend.planning._calendarToBooking', compact('days', 'dateX', 'arrayMonths', 'mobile'));
	}


	function getAlertsBooking(Request $request)
	{
		return view('backend.planning._tableAlertBooking', compact('days', 'dateX', 'arrayMonths', 'mobile'));
	}

	public function getCalendarMobileView($year = "")
	{

		$mes           = array();
		$arrayReservas = array();
		$totalPayments = array();
		$arrayMonths   = array();
		$arrayDays     = array();
		$arrayTotales  = array();
		$arrayPruebas  = array();
		$mobile        = new Mobile();
		$now           = Carbon::now();
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
			$date = new Carbon('first day of September ' . $now->copy()->format('Y'));
		} else
		{
			$date = new Carbon('first day of September ' . $now->copy()->subYear()->format('Y'));
		}
		$apartamentos = \App\Rooms::where('state', '=', 1);
		$reservas     = \App\Book::whereIn('type_book', [
			1,
			2,
			4,
			5,
			7,
			8,
			9
		])->whereYear('start', '>=', $date)->orderBy('start', 'ASC')->get();

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


		$firstDayOfTheYear = new Carbon('first day of September ' . $date->copy()->format('Y'));
		$book              = new \App\Book();

		for ($i = 1; $i <= 12; $i++)
		{
			$mes[$firstDayOfTheYear->copy()->format('n')] = $firstDayOfTheYear->copy()->format('M Y');

			$startMonth = $firstDayOfTheYear->copy()->startOfMonth();
			$endMonth   = $firstDayOfTheYear->copy()->endOfMonth();
			$countDays  = $endMonth->diffInDays($startMonth);
			$day        = $startMonth;


			$arrayMonths[$firstDayOfTheYear->copy()->format('n')] = $day->copy()->format('t');


			for ($j = 1; $j <= $day->copy()->format('t'); $j++)
			{

				$arrayDays[$firstDayOfTheYear->copy()->format('n')][$j] = $book->getDayWeek($day->copy()->format('w'));
				$day                                                    = $day->copy()->addDay();

			}

			$firstDayOfTheYear->addMonth();

		}

		unset($arrayMonths[6]);
		unset($arrayMonths[7]);
		unset($arrayMonths[8]);

		if ($date->copy()->format('n') >= 9)
		{
			$start = new Carbon('first day of September ' . $date->copy()->format('Y'));
		} else
		{
			$start = new Carbon('first day of September ' . $date->copy()->subYear()->format('Y'));
		}

		$rooms         = \App\Rooms::where('state', '=', 1)->get();
		$roomscalendar = \App\Rooms::where('id', '>=', 5)->where('state', '=', 1)->orderBy('order', 'ASC')->get();
		$book          = new \App\Book();
		$inicio        = $start->copy();
		$days          = $arrayDays;

		return view('backend/planning/calendar', compact('arrayBooks', 'arrayMonths', 'arrayTotales', 'rooms', 'roomscalendar', 'arrayReservas', 'mes', 'date', 'book', 'extras', 'days', 'inicio'));
	}

	static function getCounters($year, $type)
	{
		$now = Carbon::now();

		if ($year)
		{
			if ($now->copy()->format('n') >= 9)
			{
				$date = new Carbon('first day of September ' . $now->copy()->format('Y'));
			} else
			{
				$date = new Carbon('first day of September ' . $now->copy()->subYear()->format('Y'));
			}
		} else
		{
			$date = new Carbon('first day of September ' . $year);
		}

		switch ($type)
		{
			case 'pendientes':
				$booksCount = \App\Book::where('start', '>', $date->copy()->subMonth())
				                       ->where('finish', '<', $date->copy()->addYear())
				                       ->whereIn('type_book', [
					                       3,
					                       11
				                       ])
				                       ->count();
				break;
			case 'especiales':
				$booksCount = \App\Book::where('start', '>', $date->copy()->subMonth())
				                       ->where('finish', '<', $date->copy()->addYear())
				                       ->whereIn('type_book', [
					                       7,
					                       8
				                       ])
				                       ->count();
				break;
			case 'confirmadas':
				$booksCount = \App\Book::where('start', '>', $date->copy()->subMonth())
				                       ->where('finish', '<', $date->copy()->addYear())
				                       ->whereIn('type_book', [2])
				                       ->count();
				break;
			case 'checkin':
				$dateX      = Carbon::now();
				$booksCount = \App\Book::where('start', '>', $dateX->copy()->subDays(3))
				                       ->where('finish', '<', $dateX->copy()->addYear())
				                       ->where('type_book', 2)
				                       ->count();
				break;
			case 'blocked-ical':
				$dateX      = Carbon::now();
				$booksCount = \App\Book::where('start', '>', $date->copy()->subMonth())
				                       ->where('finish', '<', $date->copy()->addYear())
				                       ->whereIn('type_book', [
					                       11,
					                       12
				                       ])
				                       ->count();
				break;
			case 'checkout':
				$dateX      = Carbon::now();
				$booksCount = \App\Book::where('start', '>=', $dateX->copy()->subDays(3))
				                       ->where('type_book', 2)
				                       ->count();
				break;
			case 'eliminadas':
				$dateX      = Carbon::now();
				$booksCount = \App\Book::where('start', '>', $date->copy()->subMonth())
				                       ->where('finish', '<', $date->copy()->addYear())
				                       ->whereIn('type_book', 0)
				                       ->count();
				break;
		}

		return $booksCount;
	}


	public function sendSencondEmail(Request $request)
	{
		$book = \App\Book::find($request->id);
		if (!empty($book->customer->email))
		{
			$book->send = 1;
			$book->save();
			$sended = Mail::send(['html' => 'backend.emails._secondPayBook'], ['book' => $book], function ($message) use ($book) {
				$message->from('reservas@apartamentosierranevada.net');
				// $message->to('iankurosaki17@gmail.com');
				$message->to($book->customer->email);
				$message->subject('Recordatorio de pago Apto. de lujo Miramarski - ' . $book->customer->name);
				$message->replyTo('reservas@apartamentosierranevada.net');
			});

			if ($sended)
			{
				return [
					'status'   => 'success',
					'title'    => 'OK',
					'response' => "Recordatorio enviado correctamente"
				];
			} else
			{
				return [
					'status'   => 'danger',
					'title'    => 'Error',
					'response' => "El email no se ha enviado, por favor intentalo de nuevo"
				];
			}
		} else
		{
			return [
				'status'   => 'warning',
				'title'    => 'Cuidado',
				'response' => "Este cliente no tiene email"
			];
		}


	}


	public function cobrarFianzas($id)
	{
		$book      = \App\Book::find($id);
		$hasFiance = \App\Fianzas::where('book_id', $book->id)->first();
		$stripe    = StripeController::$stripe;
		return view('backend/planning/_fianza', compact('book', 'hasFiance', 'stripe'));
	}


	public function checkSecondPay()
	{

		$daysToCheck = \App\DaysSecondPay::find(1)->days;
		/* Esta funcion tiene una cron asosciada que se ejecuta los dia 1 y 15 de cada mes, es decir cad 2 semanas */
		$date  = Carbon::now();
		$books = \App\Book::where('start', '>=', $date->copy())
		                  ->where('finish', '<=', $date->copy()->addDays($daysToCheck))
		                  ->where('type_book', 2)
		                  ->where('send', 0)
		                  ->orderBy('created_at', 'DESC')
		                  ->get();


		foreach ($books as $key => $book)
		{
			if ($book->send == 0)
			{
				if (!empty($book->customer->email) && $book->id == 3908)
				{
					$book->send = 1;
					$book->save();

					$sended = Mail::send(['html' => 'backend.emails._secondPayBook'], ['book' => $book], function ($message) use ($book) {
						$message->from('reservas@apartamentosierranevada.net');
						// $message->to('iankurosaki17@gmail.com');
						$message->to($book->customer->email);
						$message->subject('Recordatorio de pago Apto. de lujo Miramarski - ' . $book->customer->name);
						$message->replyTo('reservas@apartamentosierranevada.net');
					});

					if ($sended = 1)
					{
						echo json_encode([
							                 'status'   => 'success',
							                 'title'    => 'OK',
							                 'response' => "Recordatorios enviados correctamente"
						                 ]);
						echo "<br><br>";
					} else
					{
						echo json_encode([
							                 'status'   => 'danger',
							                 'title'    => 'Error',
							                 'response' => "El email no se ha enviado, por favor intentalo de nuevo"
						                 ]);
						echo "<br><br>";
					}
				} else
				{
					$book->send = 1;
					$book->save();
				}
			}

		}

	}


	public function getAllDataToBook(Request $request)
	{
		$room = \App\Rooms::with('extra')->find($request->room);

		if ($request->book_id) {
			$book = Book::find($request->book_id);
		}

		$promotion = $request->promotion ? floatval($request->promotion) : 0;

		$data['costes']['parking']   = $this->getCostPark($request->park, $request->noches, $room->id);
		$data['costes']['book']      = $this->getCostBook($request->start, $request->finish, $request->pax, $request->room) - $promotion;
		$data['costes']['lujo']      = $this->getCostLujo($request->lujo);
		$data['costes']['limp']      = (int) $room->cost_cleaning;
		$data['costes']['obsequio']  = Rooms::GIFT_COST;
		$data['costes']['agencia']   = (float) $request->agencyCost;
		$data['costes']['promotion'] = $promotion;

		$data['totales']['parking']  = $this->getPricePark($request->park, $request->noches, $room->id);
		$data['totales']['lujo']     = $this->getPriceLujo($request->lujo);
		$data['totales']['limp']     = (int) $room->price_cleaning;
		$data['totales']['book']     = $this->getPriceBook($request->start, $request->finish, $request->pax, $request->room);
		$data['totales']['obsequio'] = Rooms::GIFT_PRICE;

		// If the request comes with a price to show use it
		if (!empty($request->total_price))
		{
			$totalPrice                    = $request->total_price;
			$data['aux']['price_modified'] = $totalPrice;
		} else
		{
			// Otherwise if the request has a book and book exists
			if (isset($book))
			{
				// If the price has been modified in DB already
				if ($book->total_price != $book->real_price)
				{
					$totalPrice                    = $book->total_price;
					$data['aux']['price_modified'] = $totalPrice;
				} else
				{
					$totalPrice = array_sum($data['totales']);
				}
			} else
			{
				$totalPrice = array_sum($data['totales']);
			}
		}

		$totalCost = array_sum($data['costes']) - $promotion;
		$profit    = $totalPrice - $totalCost;

		$data['calculated']['total_price']       = $totalPrice;
		$data['calculated']['total_cost']        = $totalCost;
		$data['calculated']['profit']            = $profit;
		$data['calculated']['profit_percentage'] = round(($profit / $totalPrice) * 100);
		$data['calculated']['real_price']        = array_sum($data['totales']);

		return $data;
	}

	public function updateBooksCosts()
	{
		$reservas = \App\Book::whereIn('type_book', [
			1,
			2,
			4,
			5
		])->where('start', '>=', '2017-09-01')->get();
		//$reservas = \App\Book::whereIn('type_book',[1,2,4,5])->where('start','>=', '2017-09-01')->where('room_id', 138)->get();
		foreach ($reservas as $key => $book):

			$extraPrice = (int) \App\Extras::find(4)->price;
			$extraCost  = (int) \App\Extras::find(4)->cost;
			if ($book->room->sizeApto == 1)
			{

				$data['costes']['limp'] = (int) \App\Extras::find(2)->cost;

			} elseif ($book->room->sizeApto == 2)
			{

				$data['costes']['limp'] = (int) \App\Extras::find(1)->cost;

			} elseif ($book->room->sizeApto == 3 || $book->room->sizeApto == 4)
			{

				$data['costes']['limp'] = (int) \App\Extras::find(3)->cost;

			}

			$costParking = 0;
			switch ($book->type_park)
			{
				case 1:
					$costParking = 13.5 * $book->nigths;
					break;
				case 2:
					$costParking = 0;
					break;
				case 3:
					$costParking = 0;
					break;
				case 4:
					$costParking = (13.5 * $book->nigths) / 2;
					break;
			}

			if ($book->room_id != "")
			{
				if ($book->room_id == 150)
				{
					$costParking = $costParking * 3;
				} else if ($book->room_id == 149)
				{
					$costParking = $costParking * 2;
				}
			}

			/* LUJO */
			$data['costes']['lujo'] = $this->getCostLujo($book->type_luxury);

			/* PARKING */
			$data['costes']['parking'] = $costParking;


			/* RESERVA */
			$start  = Carbon::createFromFormat('Y-m-d', $book->start)->format('d/m/Y');
			$finish = Carbon::createFromFormat('Y-m-d', $book->finish)->format('d/m/Y');


			$data['costes']['book'] = $this->getCostBook($start, $finish, $book->pax, $book->room->id);

			$totalCost        = $data['costes']['book'] + $data['costes']['parking'] + $data['costes']['lujo'] + $data['costes']['limp'] + $book->PVPAgencia + $extraCost;
			$book->cost_apto  = $data['costes']['book'];
			$book->cost_park  = $data['costes']['parking'];
			$book->cost_lujo  = $data['costes']['lujo'];
			$book->cost_limp  = $data['costes']['limp'];
			$book->extraPrice = $extraPrice;
			$book->extraCost  = $extraCost;

			$book->cost_total = $totalCost;
			$book->total_ben  = $book->total_price - $totalCost;
			if ($book->total_price == 0)
			{
				$book->total_price = 1;
			}
			$book->inc_percent = round(($book->total_ben / $book->total_price) * 100, 2);
			$book->save();

		endforeach;


	}


	public function saveComment(Request $request, $idBook, $type)
	{
		$book = \App\Book::find($idBook);

		switch ($type)
		{
			case '1':
				$book->comment = $request->value;
				break;

			case '2':
				$book->book_comments = $request->value;
				break;

			case '3':
				$book->book_owned_comments = $request->value;
				break;
		}

		if ($book->save())
		{
			return [
				'status'   => 'success',
				'title'    => 'OK',
				'response' => "Comentarios guardados"
			];
		};


	}

}
