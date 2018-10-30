<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;
use DB;
use Mail;

/**
 * Class Book
 * @property integer id
 * @property integer user_id
 * @property integer customer_id
 * @property integer room_id
 * @property Carbon  start
 * @property Carbon  finish
 * @property string  comment
 * @property string  book_comments
 * @property integer type_book
 * @property integer pax
 * @property integer nigths
 * @property integer agency
 * @property float   PVPAgencia
 * @property float   sup_limp
 * @property float   cost_limp
 * @property float   sup_park
 * @property integer type_park
 * @property float   cost_park
 * @property integer type_luxury
 * @property float   sup_lujo
 * @property float   cost_lujo
 * @property float   cost_apto
 * @property float   cost_total
 * @property float   total_price
 * @property float   total_ben
 * @property float   extraPrice
 * @property float   extraCost
 * @property float   extra
 * @property float   inc_percent
 * @property float   ben_jorge
 * @property float   ben_jaime
 * @property mixed   send
 * @property integer statusCobro
 * @property float   real_price
 * @property Carbon  created_at
 * @property Carbon  updated_at
 * @property integer schedule
 * @property integer scheduleOut
 * @property integer real_pax
 * @property string  book_owned_comments
 * @property float   promociones
 */
class Book extends Model
{
	protected $table = 'book';
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $status      = 0;
	protected $dayweek     = 0;
	protected $parking     = 0;
	protected $typePayment = 0;
	protected $banco       = 0;
	protected $cobJorge    = 0;
	protected $cobJaime    = 0;
	protected $pendiente   = 0;
	protected $agency      = 0;

	public function customer()
	{
		return $this->hasOne('\App\Customers', 'id', 'customer_id');
	}

	public function room()
	{
		return $this->hasOne('\App\Rooms', 'id', 'room_id');
	}

	public function extrasBook()
	{
		return $this->hasMany('\App\ExtrasBooks', 'id', 'book_id');
	}

	public function pago()
	{
		return $this->hasMany('\App\Payments', 'book_id', 'id');
	}

	public function user()
	{
		return $this->hasOne('\App\User', 'id', 'user_id');
	}

	public function notifications()
	{
		return $this->hasMany('\App\BookNotification', 'book_id', 'id');
	}


	//Para poner nombre al dia del calendario//
	static function getDayWeek($dayweek)
	{
		$array = [
			1 => "L",
			2 => "M",
			3 => "X",
			4 => "J",
			5 => "V",
			6 => "S",
			0 => "D"
		];

		return $dayweek = $array[$dayweek];
	}

	//Para poner nombre al estado de la reserva//
	static function getStatus($status)
	{
		$array = [
			0  => 'ELIMINADA',
			1  => 'Reservado - stripe',
			2  => 'Pagada-la-señal',
			3  => 'SIN RESPONDER',
			4  => 'Bloqueado',
			5  => 'Contestado(EMAIL)',
			6  => 'Denegada',
			7  => 'Reserva Propietario',
			8  => 'ATIPICAS',
			//'SubComunidad',
			9  => 'Booking',
			10 => 'Overbooking',
			11 => 'blocked-ical',
			12 => 'ICAL - INVISIBLE'
		];

		return $array[$status];
	}

	//Para poner nombre al tipo de cobro//
	static function getTypeCobro($typePayment)
	{
		$array = [
			0 => "Metalico Jorge",
			1 => "Metalico Jaime",
			2 => "Banco Jorge",
			3 => "Banco Jaime"
		];

		return $typePayment = $array[$typePayment];
	}

	//Para poner nombre al parking de la reserva//
	static function getParking($parking)
	{
		$array = [
			1 => "Si",
			2 => "No",
			3 => "Gratis",
			4 => "50 %"
		];

		return $parking = $array[$parking];
	}

	// Para poner nombre al suplemento de lujo en la reserva
	static function getSupLujo($lujo)
	{
		$array = [
			1 => "Si",
			2 => "No",
			3 => "Gratis",
			4 => "50 %"
		];

		return $supLujo = $array[$lujo];
	}

	//Para poner nombre a la agencia//
	static function getAgency($agency)
	{
		$array = [
			0 => "",
			1 => "Booking",
			2 => "Trivago",
			3 => "Bed&Snow",
			4 => "AirBnb",
			5 => "Jaime Diaz",
			6 => "S.essence"
		];

		return $agency = $array[$agency];
	}

	//Para comprobar el dia de la reserva en el calendario
	static function existDate($start, $finish, $room)
	{

		$books         = \App\Book::where('room_id', $room)->whereIn('type_book', [
			1,
			2,
			4,
			7,
			8
		])->get();
		$existStart    = false;
		$existFinish   = false;
		$requestStart  = Carbon::createFromFormat('d/m/Y', $start);
		$requestFinish = Carbon::createFromFormat('d/m/Y', $finish);

		foreach ($books as $book)
		{
			if ($existStart == false && $existFinish == false)
			{

				$start  = Carbon::createFromFormat('Y-m-d', $book->start);
				$finish = Carbon::createFromFormat('Y-m-d', $book->finish);

				if ($start < $requestStart && $requestStart < $finish)
				{
					$existStart = true;
				} elseif ($start <= $requestStart && $requestStart < $finish)
				{
					$existStart = true;
				} elseif ($requestStart <= $start && $start < $requestFinish)
				{
					$existStart = true;
				}
			} else
			{
				break;
			}
		}
		if ($existStart == false && $existFinish == false)
		{
			return true;
		} else
		{
			return false;
		}
	}

	public function existDateOverrride($start, $finish, $room, $id_excluded)
	{

		if ($room >= 5)
		{

			if ($this->type_book == 3 || $this->type_book == 0 || $this->type_book == 6)
			{
				return true;
			} else
			{


				$requestStart  = Carbon::createFromFormat('d/m/Y', $start);
				$requestFinish = Carbon::createFromFormat('d/m/Y', $finish);

				$books = \App\Book::where('room_id', $room)->whereIn('type_book', [
					1,
					2,
					4,
					7,
					8
				])
				                  ->where('id', '!=', $id_excluded)
				                  ->orderBy('start', 'DESC')
				                  ->get();

				//\App\Book::where('room_id',$room)->whereIn('type_book',[1,2,4,5,7,8])->where('id','!=',$id_excluded)->get();

				$existStart  = false;
				$existFinish = false;


				foreach ($books as $book)
				{
					if ($existStart == false && $existFinish == false)
					{
						$start  = Carbon::createFromFormat('Y-m-d', $book->start);
						$finish = Carbon::createFromFormat('Y-m-d', $book->finish);

						if ($start < $requestStart && $requestStart < $finish)
						{
							$existStart = true;
						} elseif ($start <= $requestStart && $requestStart < $finish)
						{
							$existStart = true;
						} elseif ($requestStart <= $start && $start < $requestFinish)
						{
							$existStart = true;
						}
					} else
					{
						break;
					}
				}
				if ($existStart == false && $existFinish == false)
				{
					return true;
				} else
				{
					return false;
				}
			}
		} else
		{
			return true;
		}
	}


	// Funcion para cambiar la reserva de habitacion o estado
	public function changeBook($status, $room, $book)
	{
		if (!empty($status))
		{
			if ($status == 3)
			{

				$this->type_book = $status;
				$this->save();

				return [
					'status'   => 'success',
					'title'    => 'OK',
					'response' => "Estado Cambiado a Sin Responder"
				];

			} elseif ($status == 6)
			{

				$this->type_book = $status;
				$this->save();

				Mail::send('backend.emails.cancelado',['book' => $book], function ($message) use
				($book) {
					$message->from('reservas@apartamentosierranevada.net');
					$message->to($this->customer->email);
					$message->subject('Reserva denegada');
				});

				return [
					'status'   => 'success',
					'title'    => 'OK',
					'response' => "Reserva cambiada a Denegada"
				];

			} elseif ($status == 10)
			{

				$this->type_book = $status;
				$this->save();

				return [
					'status'   => 'success',
					'title'    => 'OK',
					'response' => "Reserva cambiada a Overbooking"
				];
			} elseif ($status == 12)
			{

				$this->type_book = $status;
				$this->save();

				return [
					'status'   => 'success',
					'title'    => 'OK',
					'response' => "Reserva cambiada a ICAL - INVISIBLE"
				];
			} else
			{

				$dateStart  = Carbon::createFromFormat('Y-m-d', $this->start);
				$dateFinish = Carbon::createFromFormat('Y-m-d', $this->finish);

				$roomStart  = $dateStart->format('U');
				$roomFinish = $dateFinish->format('U');

				$isRooms = \App\Book::where('room_id', $this->room_id)
				                    ->whereIn('type_book', [
					                    1,
					                    2,
					                    4,
					                    7,
					                    8
				                    ])
				                    ->where('id', '!=', $this->id)
				                    ->orderBy('start', 'DESC')
				                    ->get();

				$existStart  = false;
				$existFinish = false;

				foreach ($isRooms as $isRoom)
				{
					if ($existStart == false)
					{

						$start  = Carbon::createFromFormat('Y-m-d', $isRoom->start)->format('U');
						$finish = Carbon::createFromFormat('Y-m-d', $isRoom->finish)->format('U');


						if ($start < $roomStart && $roomStart < $finish)
						{
							$existStart = true;
						} elseif ($start <= $roomStart && $roomStart < $finish)
						{
							$existStart = true;
						} elseif ($roomStart <= $start && $start < $roomFinish)
						{
							$existStart = true;
						}
					} else
					{
						break;
					}
				}

				if ($existStart == false && $existFinish == false)
				{

					$this->type_book = $status;

					if ($this->customer->email == "")
					{
						$this->save();
						return [
							'status'   => 'warning',
							'title'    => 'Cuidado',
							'response' => "No tiene Email asignado"
						];
					} else
					{
						switch ($status)
						{
							case '1':

								Mail::send('backend.emails.reservado', ['book' => $book], function ($message) use ($book) {
									$message->from('reservas@apartamentosierranevada.net');

									$message->to($book->customer->email);
									$message->subject('Bloqueo de reserva y datos de pago');
								});
								break;
							case '2':
								Mail::send('backend.emails.confirmado', ['book' => $book], function ($message) use ($book) {
									$message->from('reservas@apartamentosierranevada.net');

									$message->to($book->customer->email);
									$message->subject('Confirmación de reserva (pago parcial)');
								});
								break;
							case '4':
								// Mail::send('backend.emails.bloqueado',['book' => $book], function ($message) use ($book) {
								//     $message->from('reservas@apartamentosierranevada.net');
								//     $message->to('alquilerapartamentosmiramarski@gmail.com');
								//     $message->subject('Correo de Bloqueo');
								// });
								break;
							case '7':
								Mail::send('backend.emails.reserva-propietario', ['book' => $book], function ($message) use ($book) {
									$message->from('reservas@apartamentosierranevada.net');
									$message->to($book->customer->email);
									$message->subject('Correo de Reserva de Propietario');
								});
								break;
							case '8':
								// Mail::send('backend.emails.subcomunidad',['book' => $book], function ($message) use ($book) {
								//         $message->from('reservas@apartamentosierranevada.net');
								//         $message->to('alquilerapartamentosmiramarski@gmail.com');
								//         $message->subject('Correo de Subcomunidad');
								//     });
								break;
						}
						if ($this->save())
						{

							/* Creamos las notificaciones de booking */
							/* Comprobamos que la room de la reserva este cedida a booking.com */
							if ($this->room->isAssingToBooking())
							{

								$isAssigned = \App\BookNotification::where('book_id', $book->id)->get();

								if (count($isAssigned) == 0)
								{
									$notification          = new \App\BookNotification();
									$notification->book_id = $book->id;
									$notification->save();
								}


							}

							if ($status == 1)
							{
								return [
									'status'   => 'success',
									'title'    => 'OK',
									'response' => "Email Enviado Reserva"
								];
							} elseif ($status == 2)
							{
								return [
									'status'   => 'success',
									'title'    => 'OK',
									'response' => "Email Enviado Pagada la señal"
								];
							} elseif ($status == 4)
							{
								return [
									'status'   => 'success',
									'title'    => 'OK',
									'response' => "Estado Cambiado a Bloqueado"
								];
							} elseif ($status == 5)
							{
								return [
									'status'   => 'success',
									'title'    => 'OK',
									'response' => "Contestado por email"
								];
							}elseif ($status == 7)
							{
								return [
									'status'   => 'success',
									'title'    => 'OK',
									'response' => "Estado Cambiado a Reserva Propietario"
								];
							} elseif ($status == 8)
							{
								return [
									'status'   => 'success',
									'title'    => 'OK',
									'response' => "Estado Cambiado a Subcomunidad"
								];
							} else
							{
								return [
									'status'   => 'success',
									'title'    => 'OK',
									'response' => "Estado Cambiado"
								];
							}
						}
					}
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

		if (!empty($room))
		{


			if ($this->type_book == 3)
			{
				$this->room_id = $room;
				if ($this->save())
				{
					if ($this->room->isAssingToBooking())
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
						'response' => "Apartamento cambiado correctamente"
					];
				}
			}
			else
			{


				$dateStart  = Carbon::createFromFormat('Y-m-d', $this->start);
				$dateFinish = Carbon::createFromFormat('Y-m-d', $this->finish);

				$roomStart  = $dateStart->format('U');
				$roomFinish = $dateFinish->format('U');


				$isRooms = \App\Book::where('room_id', $room)->whereIn('type_book', [
					1,
					2,
					4,
					7,
					8
				])
				                    ->where('id', '!=', $this->id)
				                    ->orderBy('start', 'DESC')
				                    ->get();

				$existStart  = False;
				$existFinish = False;

				foreach ($isRooms as $isRoom)
				{
					if ($existStart == False && $existFinish == False)
					{

						$start  = Carbon::createFromFormat('Y-m-d', $isRoom->start)->format('U');
						$finish = Carbon::createFromFormat('Y-m-d', $isRoom->finish)->format('U');

						if ($start < $roomStart && $roomStart < $finish)
						{
							$existStart = true;
						} elseif ($start <= $roomStart && $roomStart < $finish)
						{
							$existStart = true;
						} elseif ($roomStart <= $start && $start < $roomFinish)
						{
							$existStart = true;
						}
					} else
					{
						break;
					}
				}
				if ($existStart == false && $existFinish == false)
				{
					$this->room_id = $room;
					if ($this->save())
					{
						if ($this->room->isAssingToBooking())
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
							'response' => "Apartamento cambiado correctamente"
						];
					} else
					{
						return [
							'status'   => 'danger',
							'title'    => 'Peligro',
							'response' => "Error mientrar en el cambio de apartamento"
						];
					}

				} else
				{
					return [
						'status'   => 'danger',
						'title'    => 'Peligro',
						'response' => "Este apartamento ya esta ocupado para estas fechas"
					];
				}


			}
		}
	}


	//Funcion para calcular el beneficio de Jorge
	static public function getBenJorge($ben, $id)
	{
		$room      = \App\Rooms::find($id);
		$beneficio = 0;

		switch ($room->commission)
		{
			case '0':
				//Jorge
				$beneficio = $ben;
				break;
			case '1':
				//Propietario
				$beneficio = $ben * 0.9;
				break;
			case '2':
				//Riesgo
				$beneficio = $ben * 0.65;
				break;
			case '3':
				$beneficio = 0;
				break;
		}
		return $beneficio;
	}

	//Funcion para calcular el beneficio de Jaime
	static public function getBenJaime($ben, $id)
	{
		$room      = \App\Rooms::find($id);
		$beneficio = 0;

		switch ($room->commission)
		{
			case '0':
				//Jorge
				$beneficio = 0;
				break;
			case '1':
				//Propietario
				$beneficio = $ben * 0.1;
				break;
			case '2':
				//Riesgo
				$beneficio = $ben * 0.35;
				break;
			case '3':
				$beneficio = 0;
				break;
		}
		return $beneficio;
	}

	public function getPayment($tipo)
	{
		return $this->payments->filter(function ($payment) use ($tipo) {
			return $payment->type == $tipo;
		})->sum('import');
	}

	// Funcion para Sacar Ventas por temporada
	public function getVentas($year)
	{
		$ventas = [
			"Ventas" => [],
			"Ben"    => [],
		];

		$date  = Carbon::CreateFromFormat('Y-m-d', $year);
		$books = \App\Book::where('type_book', 2)->where('start', '>=', $date->copy()->format('Y-m-d'))
		                  ->where('start', '<=', $date->copy()->addYear()->format('Y-m-d'))->get();

		foreach ($books as $book)
		{
			$mes      = Carbon::createFromFormat('Y-m-d', $book->start);
			$posicion = $mes->format('n');
			if ($posicion == 9 || $posicion == 10 || $posicion == 11)
			{
				$posicion = 12;
			} else if ($posicion == 5 || $posicion == 6)
			{
				$posicion = "04";
			}
			if (isset($ventas["Ventas"][$posicion]))
			{
				$ventas["Ventas"][$posicion] += $book->total_price;
				$ventas["Ben"][$posicion]    += $book->total_ben;
			} else
			{
				$ventas["Ventas"][$posicion] = $book->total_price;
				$ventas["Ben"][$posicion]    = $book->total_ben;
			}

		}
		if (isset($ventas["Ventas"][12]))
		{
		} else
		{
			$ventas["Ventas"][12] = "0";
			$ventas["Ben"][12]    = "0";
		}
		if (isset($ventas["Ventas"][01]))
		{
		} else
		{
			$ventas["Ventas"][01] = "0";
			$ventas["Ben"][01]    = "0";
		}
		if (isset($ventas["Ventas"][02]))
		{
		} else
		{
			$ventas["Ventas"][02] = "0";
			$ventas["Ben"][02]    = "0";
		}
		if (isset($ventas["Ventas"][03]))
		{
		} else
		{
			$ventas["Ventas"][03] = "0";
			$ventas["Ben"][03]    = "0";
		}
		if (isset($ventas["Ventas"][04]))
		{
		} else
		{
			$ventas["Ventas"][04] = "0";
			$ventas["Ben"][04]    = "0";
		}

		return $ventas;
	}

	public static function getBeneficioJorge()
	{

	}

	public static function getBeneficioJaime()
	{

	}

	public function payments()
	{
		return $this->hasMany(Payments::class);
	}

	public function getSumPaymentsAttribute()
	{
		return $this->payments->sum('import');
	}

	/**
	 * Do not use this function without eager load
	 *
	 * @return int
	 */
	public function getJorgeProfit()
	{
		return $this->profit * ($this->room->type->PercentJorge / 100);
	}

	/**
	 * Do not use this function without eager load
	 *
	 * @return int
	 */
	public function getJaimeProfit()
	{
		return $this->profit * ($this->room->type->PercentJaime / 100);
	}

	/**
	 * @return mixed
	 */
	public function getProfitAttribute()
	{
		return $this->total_price - $this->costs;
	}

	/**
	 * @return mixed
	 */
	public function getCostsAttribute()
	{
		return $this->cost_apto + $this->cost_park + $this->cost_lujo + $this->PVPAgencia + $this->cost_limp
			+ $this->stripeCost + $this->extraCost;
	}

	/**
	 * @return mixed
	 */
	public function getStripeCostAttribute()
	{
		$totalStripe = $this->payments->filter(function ($payment) {
			return str_contains(strtolower($payment->comment), 'stripe');
		})->sum('import');


		return $totalStripe > 0 ? round(((1.4 * $totalStripe) / 100) + 0.25) : 0;
	}

	/**
	 * @return float|int
	 */
	public function getStripeCostRawAttribute()
	{
		$totalStripe = $this->payments->filter(function ($payment) {
			return str_contains(strtolower($payment->comment), 'stripe');
		})->sum('import');


		return $totalStripe > 0 ? ((1.4 * $totalStripe) / 100) + 0.25 : 0;
	}

	/**
	 * @return int
	 */
	public function getPendingAttribute()
	{
		return $this->total_price - $this->payments->sum('import');
	}

	/**
	 * Trick applied here avoiding null results on DB
	 *
	 * @return mixed
	 */
	public function getProfitPercentageRawAttribute()
	{
		if ($this->total_price == '0.00')
		{
			return 0;
		}

		return ($this->profit * 100) / $this->total_price;
	}

	/**
	 * Trick applied here avoiding null results on DB
	 *
	 *
	 * @return mixed
	 */
	public function getProfitPercentageAttribute()
	{
		if ($this->total_price == '0.00')
		{
			return 0;
		}

		return round(($this->profit * 100) / $this->total_price);
	}

	public function hasSendPicture()
	{
		$sendPictures = DB::select("SELECT * FROM log_images 
									WHERE book_id = '" . $this->id . "'");

		return (count($sendPictures) == 0) ? false : true;
	}

	public function getSendPicture()
	{
		$sendPictures = \App\LogImages::where('book_id', $this->id)->get();
		return (count($sendPictures) > 0) ? $sendPictures : false;
	}
}
