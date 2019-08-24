<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use App\Repositories\CachedRepository;
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
use App\Seasons;
use App\Prices;
use App\Traits\BookEmailsStatus;
use App\Traits\BookParteeActions;
use App\BookPartee;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");

class BookController extends AppController
{
    use BookEmailsStatus, BookParteeActions;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $year      = $this->getActiveYear();
        $startYear = new Carbon($year->start_date);
        $endYear   = new Carbon($year->end_date);

        if (Auth::user()->role != "agente")
        {
            $roomsAgents = \App\Rooms::all(['id'])->toArray();
            $rooms       = \App\Rooms::where('state', '=', 1)->get();
            $types       = [
                1,
                3,
                4,
                5,
                6,
                10,
                11
            ];
        } else
        {
            $roomsAgents = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
            $rooms       = \App\Rooms::where('state', '=', 1)->whereIn('id', $roomsAgents)->orderBy('order')->get();
            $types       = [1];
        }

        if (Auth::user()->role != "agente")
        {
            $booksCollection = \App\Book::where('start', '>=', $startYear)->where('start', '<=', $endYear)
                                        ->whereIn('room_id', $roomsAgents)->orderBy('created_at', 'DESC')->get();
        } else
        {
            $booksCollection = \App\Book::whereIn('room_id', $roomsAgents)->where([
                                                                                      [
                                                                                          'start',
                                                                                          '>=',
                                                                                          $startYear
                                                                                      ],
                                                                                      [
                                                                                          'start',
                                                                                          '<=',
                                                                                          $endYear
                                                                                      ],
                                                                                      [
                                                                                          'user_id',
                                                                                          Auth::user()->id
                                                                                      ]
                                                                                  ])
                                        ->orWhere(function ($query) use ($roomsAgents, $types) {
                                            $query->where('agency', Auth::user()->agent->agency_id)
                                                  ->whereIn('room_id', $roomsAgents);
                                        })->orderBy('created_at', 'DESC')->get();
        }

        $booksCount['pending'] = $booksCollection->where('type_book', 3)->count();

        $booksCount['special']      = $booksCollection->whereIn('type_book', [
            7,
            8
        ])->count();
        $booksCount['confirmed']    = $booksCollection->where('type_book', 2)->count();
        $booksCount['blocked-ical'] = $booksCollection->whereIn('type_book', [
            11,
            12
        ])->where("enable", "=", "1")->count();
        $booksCount['deletes']      = \App\Book::where('start', '>', $startYear)->where('finish', '<', $endYear)
                                               ->where('type_book', 0)->where('comment', 'LIKE', '%Antiguos cobros%')
                                               ->where("enable", "=", "1")->count();
        $booksCount['checkin']      = $this->getCounters('checkin');
        $booksCount['checkout']     = $this->getCounters('checkout');//$booksCount['confirmed'] - $booksCount['checkin'];

        $books = $booksCollection->whereIn('type_book', $types);

        $stripe           = StripeController::$stripe;
        $stripedsPayments = \App\Payments::where('comment', 'LIKE', '%stripe%')
                                         ->where('created_at', '>=', Carbon::today()->toDateString())->get();

        // Notificaciones de alertas booking
        /*$notifications = \App\BookNotification::whereHas('book', function ($q) {
            return $q->where('type_book', '<>', 3)
                     ->orWhere('type_book', '<>', 5)
                     ->orWhere('type_book', '<>', 6);
        })->count();*/

        $mobile      = new Mobile();
        $now         = Carbon::now();
        $booksAlarms = \App\Book::where('start', '>', $startYear)->where('finish', '<', $endYear)
                                ->where('start', '>=', $now->format('Y-m-d'))->where('start', '<=', $now->copy()
                                                                                                        ->addDays(15)
                                                                                                        ->format('Y-m-d'))
                                ->where('type_book', 2)->get();
        $alarms      = array();
        foreach ($booksAlarms as $key => $book)
        {
            $dateStart = Carbon::createFromFormat('Y-m-d', $book->start);
            $diff      = $now->diffInDays($dateStart);

            if (count($book->payments) > 0)
            {
                $total = 0;
                foreach ($book->payments as $pay)
                {
                    $total += $pay->import;
                }
                //echo $total." DE ----> ".$book->total_price."<br>";

                $percent = 100 / ($book->total_price / $total);
                if ($percent < 100) if ($now->diffInDays($dateStart) <= 15) $alarms[] = $book;
            } else
            {
                if ($diff <= 15) $alarms[] = $book;
            }
        }

        $alert_lowProfits = 0; //To the alert efect
        $percentBenef     = DB::table('percent')->find(1)->percent;
        $lowProfits       = $this->lowProfitAlert($startYear, $endYear, $percentBenef, $alert_lowProfits);

        $parteeToActive = BookPartee::where('status', 'HUESPEDES')->get();

        return view('backend/planning/index', compact('books', 'mobile', 'stripe', 'inicio', 'rooms', 'roomscalendar', 'date', 'stripedsPayments', 'notifications', 'booksCount', 'alarms', 'lowProfits', 'alert_lowProfits', 'percentBenef', 'parteeToActive'));
    }

    private function lowProfitAlert($startYear, $endYear, $percentBenef, &$alert)
    {

        $booksAlarms = \App\Book::where('start', '>', $startYear)->where('finish', '<', $endYear)
                                ->whereIn('type_book', [
                                    2,
                                    7,
                                    8
                                ])->orderBy('start', 'ASC')->get();

        $alarms = array();

        foreach ($booksAlarms as $key => $book)
        {
            $inc_percent = $book->get_inc_percent();
            if (round($inc_percent) <= $percentBenef)
            {
                if (!$book->has_low_profit)
                {
                    $alert++;
                }
                $alarms[] = $book;
            }
        }
        return $alarms;
    }

    public function newBook(Request $request)
    {
        if (Auth::user()->role != "agente")
        {
            $rooms = \App\Rooms::where('state', '=', 1)->orderBy('order')->get();
        } else
        {
            $roomsAgents = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
            $rooms       = \App\Rooms::where('state', '=', 1)->whereIn('id', $roomsAgents)->orderBy('order')->get();
        }
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
            $customer->user_id = (Auth::check()) ? Auth::user()->id : 1;
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
                $book->user_id       = (Auth::check()) ? Auth::user()->id : 1;
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
                if ($room->sizeApto == 1 || $room->sizeApto == 5)
                {

                    $book->sup_limp  = \App\Extras::find(2)->price;//30;
                    $book->cost_limp = \App\Extras::find(2)->cost;//30;

                    $book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
                    $book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
                } elseif ($room->sizeApto == 2 || $room->sizeApto == 6)
                {

                    $book->sup_limp  = \App\Extras::find(1)->price;//50;
                    $book->cost_limp = \App\Extras::find(1)->cost;//40;

                    $book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
                    $book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
                } elseif ($room->sizeApto == 3 || $room->sizeApto == 4 || $room->sizeApto == 7 || $room->sizeApto == 8)
                {

                    $book->sup_limp  = \App\Extras::find(3)->price;//100;
                    $book->cost_limp = \App\Extras::find(3)->cost;//70;

                    $book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
                    $book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
                }

                $book->type_park = $request->input('parking', 0);

                $book->type_luxury = $request->input('type_luxury', 0);
                $book->sup_lujo    = $this->getPriceLujo($request->input('type_luxury'));
                $book->cost_lujo   = $this->getCostLujo($request->input('type_luxury'));
                // if ($room->typeApto == 3 || $room->typeApto == 1) {
                //     $book->cost_apto     = 0;
                // }else{
                $book->cost_apto = $this->getCostBook($start, $finish, $request->input('pax'), $request->input('newroom')) + $book->cost_limp;
                // }

                $book->cost_total = $book->cost_apto + $book->cost_park + $book->cost_lujo + $book->PVPAgencia + $extraCost;

                $book->total_price = ($this->getPriceBook($start, $finish, $request->input('pax'), $request->input('newroom')) + $book->sup_park + $book->sup_lujo + $book->sup_limp) - $request->input('discount');

                $book->real_price = ($this->getPriceBook($start, $finish, $request->input('pax'), $request->input('newroom')) + $book->sup_park + $book->sup_lujo + $book->sup_limp) - $request->input('discount');


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
                        //MailController::sendEmailBookSuccess($book, 1);
                    }
                    if ($request->input('fast_payment') == 1)
                    {
                        $urlPayland = $this->generateOrderPayment([
                                                                      'customer_id' => $book->customer->id,
                                                                      'amount'      => $book->total_price,
                                                                      'url_ok'      => route('payland.thanks.payment', ['id' => $book->id]),
                                                                      'url_ko'      => route('payland.thanks.payment', ['id' => $book->id]),
                                                                  ]);

                        return view('frontend.bookStatus.bookPaylandPay', ['urlPayland' => $urlPayland]);
                    } else
                    {
                        return view('frontend.bookStatus.bookOk');
                    }
                } else
                {

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
                        //$book->sup_limp   = ($room->sizeApto == 1 || $room->sizeApto == 5) ? 30 : 50;
                        $room = \App\Rooms::find($request->input('newroom'));
                        if ($room->sizeApto == 1 || $room->sizeApto == 5)
                        {
                            $book->sup_limp  = \App\Extras::find(2)->price;//30;
                            $book->cost_limp = \App\Extras::find(2)->cost;//30;

                            $book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
                            $book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
                        } elseif ($room->sizeApto == 2 || $room->sizeApto == 6)
                        {

                            $book->sup_limp  = \App\Extras::find(1)->price;//50;
                            $book->cost_limp = \App\Extras::find(1)->cost;//40;

                            $book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
                            $book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
                        } elseif ($room->sizeApto == 3 || $room->sizeApto == 4 || $room->sizeApto == 7 || $room->sizeApto == 8)
                        {

                            $book->sup_limp  = \App\Extras::find(3)->price;//100;
                            $book->cost_limp = \App\Extras::find(3)->cost;//70;

                            $book->sup_park  = $this->getPricePark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
                            $book->cost_park = $this->getCostPark($request->input('parking'), $request->input('nigths')) * $room->num_garage;
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
        $hasFiance = \App\Fianzas::where('book_id', $book->id)->first();

        /**
         * Check low_profit alert
         */
        $low_profit   = false;
        $inc_percent  = $book->get_inc_percent();
        $percentBenef = DB::table('percent')->find(1)->percent;

        if (round($inc_percent) <= $percentBenef)
        {
            if (!$book->has_low_profit)
            {
                $low_profit = true;
            }
        }

        //END: Check low_profit alert

        return view('backend/planning/update', [
            'book'         => $book,
            'low_profit'   => $low_profit,
            'rooms'        => \App\Rooms::where('state', '=', 1)->orderBy('order')->get(),
            'extras'       => \App\Extras::all(),
            'start'        => Carbon::createFromFormat('Y-m-d', $book->start)->format('d M,y'),
            'payments'     => $book->payments,
            'typecobro'    => new \App\Book(),
            'totalpayment' => $totalpayment,
            'mobile'       => new Mobile(),
            'hasFiance'    => $hasFiance,
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
            if ($book->type_book == 7)
            {
                $book->sup_park  = 0;
                $book->sup_limp  = ($room->sizeApto == 1) ? 30 : 50;
                $book->cost_limp = ($room->sizeApto == 1) ? 30 : 40;

                $book->sup_lujo  = 0;
                $book->cost_lujo = 0;

                $book->real_price = ($room->sizeApto == 1) ? 30 : 50;
            } else
            {
                $book->sup_park  = $computedData->totales->parking;
                $book->sup_limp  = $computedData->totales->limp;
                $book->cost_limp = $computedData->costes->limp;

                $book->sup_lujo   = $computedData->totales->lujo;
                $book->cost_lujo  = $computedData->costes->lujo;
                $book->real_price = $computedData->calculated->real_price;
            }

            $book->cost_park  = $request->input('costParking');
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
    public static function getPricePark($typePark, $nights)
    {
        $priceParking   = 0;
        $parkPvpSetting = \App\Settings::where('key', SettingsController::PARK_PVP_SETTING_CODE)->first();
        if (!$parkPvpSetting) return 0;

        switch ($typePark)
        {
            case 1: // Yes
                $priceParking = $parkPvpSetting->value * $nights;
                break;
            case 2: // No
            case 3: // Free
                $priceParking = 0;
                break;
            case 4: // 50%
                $priceParking = ($parkPvpSetting->value * $nights) / 2;
                break;
        }
        return $priceParking;

    }

    public function getCostPark($typePark, $nights)
    {
        $costParking     = 0;
        $parkCostSetting = \App\Settings::where('key', SettingsController::PARK_COST_SETTING_CODE)->first();
        if (!$parkCostSetting) return 0;

        switch ($typePark)
        {
            case 1: // Yes
                $costParking = $parkCostSetting->value * $nights;
                break;
            case 2: // No
            case 3: // Free
                $costParking = 0;
                break;
            case 4: // 50%
                $costParking = ($parkCostSetting->value * $nights) / 2;
                break;
        }
        return $costParking;

    }

    public static function getPriceLujo($typeLuxury)
    {
        $priceLuxury      = 0;
        $luxuryPvpSetting = \App\Settings::where('key', SettingsController::LUXURY_PVP_SETTING_CODE)->first();
        if (!$luxuryPvpSetting) return 0;

        switch ($typeLuxury)
        {
            case 1: // Yes
                $priceLuxury = $luxuryPvpSetting->value;
                break;
            case 2: // No
            case 3: // Free
                $priceLuxury = 0;
                break;
            case 4: // 50%
                $priceLuxury = $luxuryPvpSetting->value / 2;
                break;
        }

        return $priceLuxury;
    }

    /**
     * @param $typeLuxury
     * @return float|int
     */
    public function getCostLujo($typeLuxury)
    {
        $costLuxury        = 0;
        $luxuryCostSetting = \App\Settings::where('key', SettingsController::LUXURY_COST_SETTING_CODE)->first();
        if (!$luxuryCostSetting) return 0;

        switch ($typeLuxury)
        {
            case 1: // Yes
                $costLuxury = $luxuryCostSetting->value;
                break;
            case 2: // No
            case 3: // Free
                $costLuxury = 0;
                break;
            case 4: // 50%
                $costLuxury = $luxuryCostSetting->value / 2;
                break;
        }

        return $costLuxury;
    }

    public function getCostBook($start, $finish, $pax, $room)
    {
        $start     = Carbon::createFromFormat('d/m/Y', $start);
        $finish    = Carbon::createFromFormat('d/m/Y', $finish);
        $countDays = $finish->diffInDays($start);

        $paxPerRoom = Rooms::getPaxRooms($pax, $room);

        if ($paxPerRoom > $pax)
        {
            $pax = $paxPerRoom;
        }
        $costBook = 0;
        $counter  = $start->copy();
        for ($i = 1; $i <= $countDays; $i++)
        {
            $date = $counter->copy()->format('Y-m-d');

            $seasonActive = Seasons::getSeasonType($date);
            $costs        = Prices::getCostsFromSeason($seasonActive, $pax);

            //            $seasonActive = $this->cachedRepository->getSeasonType($date);
            //            $costs        = $this->cachedRepository->getCostsFromSeason($seasonActive, $pax);

            foreach ($costs as $precio)
            {
                $costBook = $costBook + $precio['cost'];
            }

            $counter->addDay();
        }
        return $costBook;
    }

    public static function getPriceBook($start, $finish, $pax, $room)
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
            $date         = $counter->format('Y-m-d');
            $seasonActive = Seasons::getSeasonType($date);
            $costs        = Prices::getCostsFromSeason($seasonActive, $pax);

            //            $seasonActive = $this->cachedRepository->getSeasonType($date);
            //            $costs        = $this->cachedRepository->getCostsFromSeason($seasonActive, $pax);

            foreach ($costs as $precio)
            {
                $priceBook = $priceBook + $precio['price'];
            }
            $counter->addDay();
        }

        return $priceBook;
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
                    $book->comment .= "\n Antiguos cobros: \n Fecha: " . $pago->datePayment . ", Importe: " . $pago->import . "\n Tipo de pago: " . $pago->type;

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
                                            ->where('concept', "LIMPIEZA RESERVA PROPIETARIO. " . $book->room->nameRoom);

                if ($expenseLimp->count() > 0)
                {
                    $expenseLimp->first()->delete();
                }

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
    /*static function getTotalBook(Request $request)
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
            // Rooms para grandes capacidades
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

            $seasonActive = \App\Seasons::getSeasonType($auxDate->copy()->format('Y-m-d'));
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
    }*/

    public function searchByName(Request $request)
    {
        if ($request->searchString == '')
        {
            return response()->json('', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $year     = $request->input('year', date('Y'));
        $dateFrom = (new Carbon("first day of September {$year}"));
        $dateTo   = $dateFrom->copy()->addYear();

        $customerIds = \App\Customers::where('name', 'LIKE', '%' . $request->searchString . '%')->pluck('id')
                                     ->toArray();

        if (count($customerIds) <= 0)
        {
            return "<h2>No hay reservas para este término '" . $request->searchString . "'</h2>";
        }

        $books = \App\Book::with('payments')->whereIn('customer_id', $customerIds)->where('start', '>=', $dateFrom)
                          ->where('start', '<=', $dateTo)->where('type_book', '!=', 9)->where('type_book', '!=', 0)
                          ->orderBy('start', 'ASC')->get();

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
        $mobile    = new Mobile();
        $year      = self::getActiveYear();
        $startYear = new Carbon($year->start_date);
        $endYear   = new Carbon($year->end_date);


        if (Auth::user()->role != "agente")
        {
            $roomsAgents = \App\Rooms::all(['id'])->toArray();
            $rooms       = \App\Rooms::where('state', '=', 1)->get();
            $types       = [
                1,
                3,
                4,
                5,
                6,
                10,
                11
            ];
        } else
        {
            $roomsAgents = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
            $rooms       = \App\Rooms::where('state', '=', 1)->whereIn('id', $roomsAgents)->orderBy('order')->get();
            $types       = [1];
        }

        switch ($request->type)
        {
            case 'pendientes':


                if (Auth::user()->role != "agente")
                {
                    $books = \App\Book::where('start', '>=', $startYear)->where('finish', '<=', $endYear)
                                      ->whereIn('type_book', $types)->whereIn('room_id', $roomsAgents)
                                      ->orderBy('created_at', 'DESC')->get();
                } else
                {
                    $books = \App\Book::where('start', '>=', $startYear)->where('finish', '<=', $endYear)
                                      ->whereIn('type_book', $types)->where('user_id', Auth::user()->id)
                                      ->whereIn('room_id', $roomsAgents)
                                      ->orWhere(function ($query) use ($roomsAgents, $types) {
                                          $query->where('agency', Auth::user()->agent->agency_id)
                                                ->whereIn('room_id', $roomsAgents)->whereIn('type_book', $types);
                                      })->orderBy('created_at', 'DESC')->get();
                }
                break;
            case 'especiales':
                $books = \App\Book::where('start', '>=', $startYear)->where('finish', '<=', $endYear)
                                  ->whereIn('type_book', [
                                      7,
                                      8
                                  ])->orderBy('created_at', 'DESC')->get();
                break;
            case 'confirmadas':

                if (Auth::user()->role != "agente")
                {
                    $books = \App\Book::where('start', '>=', $startYear)->where('finish', '<=', $endYear)
                                      ->whereIn('type_book', [2])->whereIn('room_id', $roomsAgents)
                                      ->orderBy('created_at', 'DESC')->get();
                } else
                {
                    $books = \App\Book::where('start', '>=', $startYear)->where('finish', '<=', $endYear)
                                      ->whereIn('type_book', [2])->whereIn('room_id', $roomsAgents)
                                      ->where('user_id', Auth::user()->id)
                                      ->orWhere(function ($query) use ($roomsAgents) {
                                          $query->where('agency', Auth::user()->agent->agency_id)
                                                ->whereIn('room_id', $roomsAgents)->whereIn('type_book', [2]);
                                      })->orderBy('created_at', 'DESC')->get();
                }
                break;
            case 'checkin':
                $dateX = Carbon::now();
                $books = \App\Book::where('start', '>=', $dateX->copy()->subDays(3))->where('start', '<=', $dateX)
                                  ->where('type_book', 2)->orderBy('start', 'ASC')->get();
                break;
            case 'checkout':
                $dateX = Carbon::now();
                $books = \App\Book::where('finish', '>=', $dateX->copy()->subDays(3))->where('finish', '<', $dateX)
                                  ->where('type_book', 2)->orderBy('finish', 'ASC')->get();
                break;
            case 'eliminadas':
                $books = \App\Book::where('start', '>=', $startYear)->where('finish', '<=', $endYear)
                                  ->where('type_book', 0)->orderBy('updated_at', 'DESC')->get();
                break;
            case 'blocked-ical':
                $books = \App\Book::where('start', '>=', $startYear->copy()->subDays(3))
                                  ->where('finish', '<=', $endYear)->whereIn('type_book', [
                        11,
                        12
                    ])->orderBy('updated_at', 'DESC')->get();
                break;
        }


        $type = $request->type;

        if ($request->type == 'confirmadas' || $request->type == 'checkin')
        {
            $payment = array();
            foreach ($books as $key => $book)
            {
                $payment[$book->id] = 0;
                $payments           = \App\Payments::where('book_id', $book->id)->get();
                if (count($payments) > 0) foreach ($payments as $key => $pay) $payment[$book->id] += $pay->import;

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

        $year      = self::getActiveYear();
        $startYear = new Carbon($year->start_date);
        $endYear   = new Carbon($year->end_date);

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
        $mobile      = new Mobile();
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
        $rooms             = \App\Rooms::where('state', 1)->orderBy('order', 'ASC')->get();
        $typesRoom         = [
            '2dorm-lujo'   => [
                'total'  => 0,
                'name'   => '2DL',
                'months' => []
            ],
            'estudio'      => [
                'total'  => 0,
                'name'   => 'EST',
                'months' => []
            ],
            '2dorm-stand'  => [
                'total'  => 0,
                'name'   => '2DS',
                'months' => []
            ],
            'chalet'       => [
                'total'  => 0,
                'name'   => 'CHL',
                'months' => []
            ],
            '10pers-stand' => [
                'total'  => 0,
                'name'   => '3DS',
                'months' => []
            ],

            '12pers-stand' => [
                'total'  => 0,
                'name'   => '4DS',
                'months' => []
            ]
        ];
        $book              = new \App\Book();
        $auxDate           = $firstDayOfTheYear->copy();
        foreach ($rooms as $key => $room)
        {
            if ($room->luxury == 1 && $room->sizeApto == 2)
            {
                $typesRoom['2dorm-lujo']['total'] += 1;
            }

            if ($room->luxury == 1 && $room->sizeApto == 1)
            {
                $typesRoom['estudio']['total'] += 1;
            }

            if ($room->luxury == 0 && $room->sizeApto == 2)
            {

                if ($room->id == 144)
                {
                    $typesRoom['chalet']['total'] += 1;
                } else
                {
                    $typesRoom['2dorm-stand']['total'] += 1;
                }
            }

            if ($room->luxury == 0 && $room->sizeApto == 1)
            {
                $typesRoom['estudio']['total'] += 1;
            }


            if ($room->luxury == 0 && $room->sizeApto == 3)
            {
                $typesRoom['10pers-stand']['total'] += 1;
            }

            if ($room->luxury == 0 && $room->sizeApto == 4)
            {
                $typesRoom['12pers-stand']['total'] += 1;
            }
        }

        for ($i = 1; $i <= 12; $i++)
        {
            $startMonth = $auxDate->copy()->startOfMonth();
            $day        = $startMonth;

            $arrayMonths[$auxDate->copy()->format('n')] = $day->copy()->format('t');
            for ($j = 1; $j <= $day->copy()->format('t'); $j++)
            {
                $arrayDays[$auxDate->copy()->format('n')][$j] = $book->getDayWeek($day->copy()->format('w'));
                foreach ($typesRoom as $key => $room)
                {
                    $typesRoom[$key]['months'][$day->copy()->format('n')][$day->copy()
                                                                              ->format('j')] = $typesRoom[$key]['total'];
                }

                $day = $day->copy()->addDay();
            }
            $auxDate->addMonth();
        }

        $dateX = $date->copy();

        $reservas = \App\Book::whereIn('type_book', [
            1,
            2,
            4,
            7
        ])->where('start', '>=', $firstDayOfTheYear->copy())->where('finish', '<=', $firstDayOfTheYear->copy()
                                                                                                      ->addYear())
                             ->get();

        foreach ($reservas as $reserva)
        {
            $room = \App\Rooms::find($reserva->room_id);

            $start  = Carbon::createFromFormat('Y-m-d', $reserva->start);
            $finish = Carbon::createFromFormat('Y-m-d', $reserva->finish);
            $diff   = $start->diffInDays($finish);
            $dia    = Carbon::createFromFormat('Y-m-d', $reserva->start);
            for ($i = 1; $i <= $diff; $i++)
            {
                if ($room->luxury == 1 && $room->sizeApto == 2)
                {
                    $typesRoom['2dorm-lujo']['months'][$dia->copy()->format('n')][$dia->copy()->format('j')] -= 1;
                }
                if ($room->luxury == 1 && $room->sizeApto == 1)
                {
                    $typesRoom['estudio']['months'][$dia->copy()->format('n')][$dia->copy()->format('j')] -= 1;
                }
                if ($room->luxury == 0 && $room->sizeApto == 2)
                {
                    if ($room->id == 144)
                    {
                        $typesRoom['chalet']['months'][$dia->copy()->format('n')][$dia->copy()->format('j')] -= 1;
                    } else
                    {
                        $typesRoom['2dorm-stand']['months'][$dia->copy()->format('n')][$dia->copy()->format('j')] -= 1;
                    }

                }
                if ($room->luxury == 0 && $room->sizeApto == 1)
                {
                    $typesRoom['estudio']['months'][$dia->copy()->format('n')][$dia->copy()->format('j')] -= 1;
                }
                if ($room->luxury == 1 && $room->sizeApto == 3)
                {
                    $typesRoom['10pers-lujo']['months'][$dia->copy()->format('n')][$dia->copy()->format('j')] -= 1;
                }
                if ($room->luxury == 0 && $room->sizeApto == 3)
                {
                    $typesRoom['10pers-stand']['months'][$dia->copy()->format('n')][$dia->copy()->format('j')] -= 1;
                }
                if ($room->luxury == 1 && $room->sizeApto == 4)
                {
                    $typesRoom['12pers-lujo']['months'][$dia->copy()->format('n')][$dia->copy()->format('j')] -= 1;
                }
                if ($room->luxury == 0 && $room->sizeApto == 4)
                {
                    $typesRoom['12pers-stand']['months'][$dia->copy()->format('n')][$dia->copy()->format('j')] -= 1;
                }

                $dia->addDay();
            }
        }
        $days = $arrayDays;

        return view('backend.planning._calendarToBooking', compact('days', 'dateX', 'arrayMonths', 'mobile', 'typesRoom'));
    }

    function getAlertsBooking(Request $request)
    {
        return view('backend.planning._tableAlertBooking', compact('days', 'dateX', 'arrayMonths', 'mobile'));
    }

    public function getCalendarMobileView()
    {

        $mes           = [];
        $arrayReservas = [];
        $arrayMonths   = [];
        $arrayDays     = [];
        $year          = $this->getActiveYear();
        $startYear     = new Carbon($year->start_date);
        $endYear       = new Carbon($year->end_date);

        $books = \App\Book::whereIn('type_book', [
            1,
            2,
            4,
            5,
            7,
            8,
            9
        ])->where('start', '>=', $startYear->format('Y-m-d'))->where('finish', '<=', $endYear->format('Y-m-d'))
                          ->orderBy('start', 'ASC')->get();

        foreach ($books as $book)
        {
            $dia        = Carbon::createFromFormat('Y-m-d', $book->start);
            $start      = Carbon::createFromFormat('Y-m-d', $book->start);
            $finish     = Carbon::createFromFormat('Y-m-d', $book->finish);
            $diferencia = $start->diffInDays($finish);
            for ($i = 0; $i <= $diferencia; $i++)
            {
                $arrayReservas[$book->room_id][$dia->copy()->format('Y')][$dia->copy()->format('n')][$dia->copy()
                                                                                                         ->format('j')][] = $book;
                $dia                                                                                                      = $dia->addDay();
            }
        }

        $firstDayOfTheYear = $startYear->copy()->firstOfMonth();
        $diffInMonths      = $startYear->diffInMonths($endYear) + 1;

        for ($i = 1; $i <= $diffInMonths; $i++)
        {
            $mes[$firstDayOfTheYear->copy()->format('n')] = $firstDayOfTheYear->copy()->format('M Y');

            $startMonth = $firstDayOfTheYear->copy()->startOfMonth();
            $day        = $startMonth;

            $arrayMonths[$firstDayOfTheYear->copy()->format('n')] = $day->copy()->format('t');

            for ($j = 1; $j <= $day->copy()->format('t'); $j++)
            {

                $arrayDays[$firstDayOfTheYear->copy()->format('n')][$j] = \App\Book::getDayWeek($day->copy()
                                                                                                    ->format('w'));
                $day                                                    = $day->copy()->addDay();

            }

            $firstDayOfTheYear->addMonth();

        }

        //unset($arrayMonths[6]);
        //unset($arrayMonths[7]);
        //unset($arrayMonths[8]);

        if (Auth::user()->role != "agente")
        {
            $rooms         = \App\Rooms::where('state', '=', 1)->get();
            $roomscalendar = \App\Rooms::where('state', '=', 1)->orderBy('order', 'ASC')->get();
        } else
        {
            $roomsAgents   = \App\AgentsRooms::where('user_id', Auth::user()->id)->get(['room_id'])->toArray();
            $rooms         = \App\Rooms::where('state', '=', 1)->whereIn('id', $roomsAgents)->orderBy('order')->get();
            $roomscalendar = \App\Rooms::where('state', '=', 1)->whereIn('id', $roomsAgents)->orderBy('order', 'ASC')
                                       ->get();
        }
        $days = $arrayDays;

        return view('backend/planning/calendar', compact('arrayBooks', 'arrayMonths', 'arrayTotales', 'rooms', 'roomscalendar', 'arrayReservas', 'mes', 'date', 'extras', 'days', 'startYear', 'endYear'));
    }

    static function getCounters($type)
    {
        $year       = self::getActiveYear();
        $startYear  = new Carbon($year->start_date);
        $endYear    = new Carbon($year->end_date);
        $booksCount = 0;
        switch ($type)
        {
            case 'pendientes':
                $booksCount = \App\Book::where('start', '>=', $startYear->copy()->format('Y-m-d'))
                                       ->where('finish', '<=', $endYear->copy()->format('Y-m-d'))
                                       ->whereIn('type_book', [
                                           3,
                                           11
                                       ])->count();
                break;
            case 'especiales':
                $booksCount = \App\Book::where('start', '>=', $startYear->copy()->format('Y-m-d'))
                                       ->where('finish', '<=', $endYear->copy()->format('Y-m-d'))
                                       ->whereIn('type_book', [
                                           7,
                                           8
                                       ])->count();
                break;
            case 'confirmadas':
                $booksCount = \App\Book::where('start', '>=', $startYear->copy()->format('Y-m-d'))
                                       ->where('finish', '<=', $endYear->copy()->format('Y-m-d'))
                                       ->whereIn('type_book', [2])->count();
                break;
            case 'checkin':
                $dateX      = Carbon::now();
                $booksCount = \App\Book::where('start', '>=', $dateX->copy()->subDays(3))->where('finish', '<=', $dateX)
                                       ->where('type_book', 2)->count();
                break;
            case 'blocked-ical':
                $dateX      = Carbon::now();
                $booksCount = \App\Book::where('start', '>=', $startYear->copy()->format('Y-m-d'))
                                       ->where('finish', '<=', $endYear->copy()->format('Y-m-d'))
                                       ->whereIn('type_book', [
                                           11,
                                           12
                                       ])->count();
                break;
            case 'checkout':
                $dateX      = Carbon::now();
                $booksCount = \App\Book::where('start', '>=', $dateX->copy()->subDays(3))->where('start', '<=', $dateX)
                                       ->where('type_book', 2)->count();
                break;
            case 'eliminadas':
                $dateX      = Carbon::now();
                $booksCount = \App\Book::where('start', '>=', $startYear->copy()->format('Y-m-d'))
                                       ->where('finish', '<=', $endYear->copy()->format('Y-m-d'))
                                       ->whereIn('type_book', 0)->count();
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
            $this->sendEmail_secondPayBook($book, 'Recordatorio de pago Apto. de lujo Miramarski - ' . $book->customer->name);
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

    /**
     * Enable/Disable alerts has_low_profit
     *
     * @param Request $request
     * @return type
     *
     */
    public function toggleAlertLowProfits(Request $request)
    {
        $book = \App\Book::find($request->id);
        if ($book)
        {
            $book->has_low_profit = !$book->has_low_profit;
            $book->save();
            if ($book->has_low_profit)
            {
                return [
                    'status'   => 'success',
                    'title'    => 'OK',
                    'response' => "Alarma desactivada para  " . $book->customer->name
                ];
            } else
            {
                return [
                    'status'   => 'success',
                    'title'    => 'OK',
                    'response' => "Alarma activada para  " . $book->customer->name
                ];
            }
        } else
        {
            return [
                'status'   => 'warning',
                'title'    => 'Cuidado',
                'response' => "Registro no encontrado"
            ];
        }


    }

    /**
     * Enable alerts has_low_profit to all
     *
     * @param Request $request
     * @return type
     *
     */
    public function activateAlertLowProfits()
    {
        if (Auth::user()->role == 'admin')
        {
            $year      = $this->getActiveYear();
            $startYear = new Carbon($year->start_date);
            $endYear   = new Carbon($year->end_date);

            $book = \App\Book::where('start', '>', $startYear)->where('finish', '<', $endYear)
                             ->where('has_low_profit', TRUE)->update(['has_low_profit' => FALSE]);
            return [
                'status'   => 'success',
                'title'    => 'OK',
                'response' => "Alarma activada para  todos los registros"
            ];
        } else
        {
            return [
                'status'   => 'warning',
                'title'    => 'Cuidado',
                'response' => "No tiene permisos para la acción que desea realizar"
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
        $books = \App\Book::where('start', '>=', $date->copy())->where('finish', '<=', $date->copy()
                                                                                            ->addDays($daysToCheck))
                          ->where('type_book', 2)->where('send', 0)->orderBy('created_at', 'DESC')->get();


        foreach ($books as $key => $book)
        {
            if ($book->send == 0)
            {
                if (!empty($book->customer->email) && $book->id == 3908)
                {
                    $book->send = 1;
                    $book->save();
                    $this->sendEmail_secondPayBook($book, 'Recordatorio de pago Apto. de lujo Miramarski - ' . $book->customer->name);
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

        if ($request->book_id)
        {
            $book = Book::find($request->book_id);
        }

        $promotion = $request->promotion ? floatval($request->promotion) : 0;

        if ($room->sizeApto == 1 || $room->sizeApto == 5)
        {
            $data['totales']['limp']  = \App\Extras::find(2)->price;//30;
            $data['costes']['limp'] = \App\Extras::find(2)->cost;//30;

        } elseif ($room->sizeApto == 2 || $room->sizeApto == 6)
        {
            $data['totales']['limp']  = \App\Extras::find(1)->price;//50;
            $data['costes']['limp'] = \App\Extras::find(1)->cost;//40;

        } elseif ($room->sizeApto == 3 || $room->sizeApto == 4 || $room->sizeApto == 7 || $room->sizeApto == 8)
        {
            $data['totales']['limp']  = \App\Extras::find(3)->price;//100;
            $data['costes']['limp'] = \App\Extras::find(3)->cost;//70;
        }

        $data['costes']['parking']   = $this->getCostPark($request->park, $request->noches) * $room->num_garage;
        $data['costes']['book']      = $this->getCostBook($request->start, $request->finish, $request->pax, $request->room) - $promotion;
        $data['costes']['lujo']      = $this->getCostLujo($request->lujo);
        $data['costes']['obsequio']  = Rooms::GIFT_COST;
        $data['costes']['agencia']   = (float) $request->agencyCost;
        $data['costes']['promotion'] = $promotion;

        $data['totales']['parking']  = $this->getPricePark($request->park, $request->noches) * $room->num_garage;
        $data['totales']['lujo']     = $this->getPriceLujo($request->lujo);
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
        $totalPrice = ($totalPrice == 0) ? $data['totales']['book'] : $totalPrice;

        $totalCost = array_sum($data['costes']) - $promotion;
        $profit    = $totalPrice - $totalCost;

        $data['calculated']['total_price']       = $totalPrice;
        $data['calculated']['total_cost']        = $totalCost;
        $data['calculated']['profit']            = $profit;
        $data['calculated']['profit_percentage'] = round(($profit / $totalPrice) * 100);
        $data['calculated']['real_price']        = array_sum($data['totales']);

        return $data;
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

    public static function getBookFFData(Request $request, $request_id)
    {

        $ff_request = [];

        $book     = \App\Book::find($request_id);
        $customer = \App\Customers::find($book->customer_id);

        if ($book->ff_request_id != NULL)
        {
            $ff_request = \App\Solicitudes::find($book->ff_request_id);
        }

        return view('/backend/planning/listados/_ff_popup')->with('book', $book)->with('customer', $customer)
                                                           ->with('ff_request', $ff_request);
    }

    public static function updateBookFFStatus(Request $request, $request_id, $status)
    {
        $book            = \App\Book::find($request_id);
        $book->ff_status = $status;

        if ($book->save())
        {
            return redirect('/admin/reservas/ff_status_popup/' . $request_id);
        }
    }

    public static function getBookingAgencyDetails()
    {

        $years = [];

        $year      = self::getActiveYear();
        $startYear = new Carbon($year->start_date);
        $endYear   = new Carbon($year->end_date);

        $year_season      = date('y', strtotime($startYear));
        $year_season_full = date('Y', strtotime($startYear));

        $years[] = $year_season . '-' . ($year_season + 1);
        $years[] = ($year_season - 1) . '-' . $year_season;
        $years[] = ($year_season - 2) . '-' . ($year_season - 1);

        $years_full[] = (int) $year_season_full;
        $years_full[] = (int) $year_season_full - 1;
        $years_full[] = (int) $year_season_full - 2;

        sort($years);
        sort($years_full);

        $dataNode = [
            'reservations'      => 0,
            'total'             => 0,
            'commissions'       => 0,
            'reservations_rate' => 0,
            'total_rate'        => 0
        ];

        $yearsNode = [
            $years[0] => $dataNode,
            $years[1] => $dataNode,
            $years[2] => $dataNode
        ];

        $agencyBooks    = [
            'years'  => $years,
            'data'   => [
                'V. Directa' => $yearsNode,
                'Booking'    => $yearsNode,
                'Trivago'    => $yearsNode,
                'Bed&Snow'   => $yearsNode,
                'AirBnb'     => $yearsNode
            ],
            'totals' => $yearsNode
        ];
        $auxYearOne     = \App\Years::where('year', $years_full[0])->first();
        $auxYearTwo     = \App\Years::where('year', $years_full[1])->first();
        $auxYearThree   = \App\Years::where('year', $years_full[2])->first();
        $agencyBooksSQL = Book::where("created_at", ">=", $auxYearThree->start_date)->get();

        foreach ($agencyBooksSQL as $book)
        {
            $book_date = Carbon::createFromFormat('Y-m-d', $book->start);

            if ($book_date >= $auxYearOne->start_date && $book_date <= $auxYearOne->end_date)
            {
                $season = $years[0];

            } elseif ($book_date >= $auxYearTwo->start_date && $book_date <= $auxYearTwo->end_date)
            {
                $season = $years[1];
            } elseif ($book_date >= $auxYearThree->start_date && $book_date <= $auxYearThree->end_date)
            {
                $season = $years[2];
            }

            if ($book->agency == 0)
            {
                $agency_name = 'V. Directa';
            } elseif ($book->agency == 1)
            {
                $agency_name = 'Booking';
            } elseif ($book->agency == 2)
            {
                $agency_name = 'Trivago';
            } elseif ($book->agency == 3)
            {
                $agency_name = 'Bed&Snow';
            } elseif ($book->agency == 4)
            {
                $agency_name = 'AirBnb';
            }

            if (isset($season))
            {
                $agencyBooks['data'][$agency_name][$season]['total']        += $book->real_price;
                $agencyBooks['data'][$agency_name][$season]['reservations'] += 1;
                $agencyBooks['data'][$agency_name][$season]['commissions']  += str_replace(',', '.', $book->PVPAgencia);

                $agencyBooks['totals'][$season]['total']        += $book->real_price;
                $agencyBooks['totals'][$season]['reservations'] += 1;
                $agencyBooks['totals'][$season]['commissions']  += str_replace(',', '.', $book->PVPAgencia);
            }

        }

        foreach ($agencyBooks['data'] as $agency_name => $seasons)
        {
            foreach ($seasons as $season_key => $season_data)
            {

                $agencyBooks['data'][$agency_name][$season_key]['reservations_rate'] = 0;
                $agencyBooks['data'][$agency_name][$season_key]['total_rate']        = 0;

                if ($season_data['reservations'] !== 0 && $agencyBooks['totals'][$season_key]['reservations'] !== 0)
                {
                    $agencyBooks['data'][$agency_name][$season_key]['reservations_rate'] = round(($season_data['reservations'] / $agencyBooks['totals'][$season_key]['reservations']) * 100, 2);
                }

                if ($season_data['total'] !== 0 && $agencyBooks['totals'][$season_key]['total'] !== 0)
                {
                    $agencyBooks['data'][$agency_name][$season_key]['total_rate'] = round(($season_data['total'] / $agencyBooks['totals'][$season_key]['total']) * 100, 2);
                }

            }
        }

        echo json_encode(array(
                             'status'      => 'true',
                             'agencyBooks' => $agencyBooks
                         ));
    }

    public function demoFormIntegration(Request $request)
    {
        $minMax = \App\Rooms::where('state', 1)->selectRaw('min(minOcu) as min, max(maxOcu) as max')->first();
        return view('backend.form_demo', ['minMax' => $minMax]);
    }

    public function apiCheckBook(Request $request)
    {
        $rooms   = [];
        $auxDate = explode('-', $request->input('result')['dates']);
        $start   = Carbon::createFromFormat('d M, y', trim($auxDate[0]));
        $finish  = Carbon::createFromFormat('d M, y', trim($auxDate[1]));
        $name    = $request->input('result')['name'];
        $email   = $request->input('result')['email'];
        $phone   = $request->input('result')['phone'];
        $dni     = $request->input('result')['dni'];
        $pax     = $request->input('result')['pax'];

        $roomsWithPax = \App\Rooms::where('state', 1)->where('minOcu', '<=', $pax)->where('maxOcu', '>=', $pax)->get();
        foreach ($roomsWithPax as $index => $roomsWithPax)
        {
            if (\App\Book::existDate($start->copy()->format('d/m/Y'), $finish->copy()
                                                                             ->format('d/m/Y'), $roomsWithPax->id)) $rooms[] = $roomsWithPax;
        }

        $instantPayment = (\App\Settings::where('key', 'instant_payment')
                                        ->first()) ? \App\Settings::where('key', 'instant_payment')
                                                                  ->first()->value : false;

        return view('backend.api.response-book-request', [
            'rooms'          => $rooms,
            'start'          => $start,
            'finish'         => $finish,
            'pax'            => $pax,
            'name'           => $name,
            'email'          => $email,
            'phone'          => $phone,
            'dni'            => $dni,
            'instantPayment' => $instantPayment,
        ]);
    }

}
