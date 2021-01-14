<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use Auth;
use Mail;
use App\Classes\Mobile;
use App\Book;

class OwnedController extends AppController {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($name = "") {
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;

    if (empty($name)){
      if (count(Auth::user()->rooms) == 0)
        return view('backend.rooms.not_rooms_avaliables');
      else
        $room = Auth::user()->rooms[0];
    } else {
      if (count(Auth::user()->rooms) == 0 && Auth::user()->role != "admin")
        return view('backend.rooms.not_rooms_avaliables');
      else
        $room = \App\Rooms::where('nameRoom', 'LIKE', "%" . $name . "%")->first();
    }
    
    if ($room) {
      if ($room->owned != Auth::user()->id && Auth::user()->role != "admin")
        return view('errors.owned-access');
    } else
      return view('errors.owned-access');

    // Variables
    $mes = array();
    $arrayReservas = array();
    $arrayMonths = array();
    $arrayDays = array();
    $aMonthMin = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];

    $total = 0;
    $apto = 0;
    $park = 0;
    $lujo = 0;
    
    // Datos
    $reservas = Book::where_book_times($startYear,$endYear)->whereIn('type_book', [1, 2, 7])
            ->where('room_id', $room->id)
            ->orderBy('start', 'ASC')
            ->get();

    $books = Book::where_type_book_prop()->where('room_id', $room->id)
                    ->where('start', '>=', $startYear)
                    ->where('start', '<=', $endYear)
                    ->orderBy('start', 'ASC')->get();

    foreach ($reservas as $reserva) {
      $dia = Carbon::createFromFormat('Y-m-d', $reserva->start);
      $start = Carbon::createFromFormat('Y-m-d', $reserva->start);
      $finish = Carbon::createFromFormat('Y-m-d', $reserva->finish);
      $diferencia = $start->diffInDays($finish);
      for ($i = 0; $i <= $diferencia; $i++) {
        $arrayReservas[$reserva->room_id][$dia->copy()->format('Y')][$dia->copy()->format('n')][$dia->copy()->format('j')][] = $reserva;
        $dia = $dia->addDay();
      }
    }

    $ax = Carbon::createFromFormat('Y-m-d', $year->start_date);
    for ($i = 1; $i <= $diff; $i++) {
      $mes[$ax->copy()->format('n')] = $ax->copy()->format('M Y');
      $ax = $ax->addMonth();
    }
    $book = new Book();

    $aux = Carbon::createFromFormat('Y-m-d', $year->start_date);

    $dayweek = [
        1 => "L",
        2 => "M",
        3 => "X",
        4 => "J",
        5 => "V",
        6 => "S",
        0 => "D"
    ];
    for ($i = 1; $i <= $diff; $i++) {

      $startMonth = $aux->copy()->startOfMonth();
      $endMonth = $aux->copy()->endOfMonth();
      $countDays = $endMonth->diffInDays($startMonth);
      $day = $startMonth;


      $arrayMonths[$aux->copy()->format('n')] = $day->copy()->format('t');


      for ($j = 1; $j <= $day->copy()->format('t'); $j++) {

        $arrayDays[$aux->copy()->format('n')][$j] = $dayweek[$day->copy()->format('w')];

        $day = $day->copy()->addDay();
      }

      $aux->addMonth();
    }

    foreach ($books as $book) {
        $apto += $book->cost_apto;
        $park += $book->cost_park;
        $lujo += $book->get_costLujo();
    }
    $total += ($apto + $park + $lujo);

    // $paymentspro = \App\Paymentspro::where('room_id',$room->id)->where('datePayment','>=',$date->copy()->format('Y-m-d'))->where('datePayment','<=',$date->copy()->addYear()->format('Y-m-d'))->get();

    $gastos = \App\Expenses::where('date', '>=', $startYear)
            ->where('date', '<=', $endYear)
            ->where('PayFor', 'LIKE', '%' . $room->id . '%')
            ->orderBy('date', 'ASC')
            ->get();
    $pagototal = 0;
    foreach ($gastos as $pago) {
      $pagototal += $pago->import;
    }
    $estadisticas['ingresos'] = ['label'=>[],'val'=>[]];
    $estadisticas['clientes'] = ['label'=>[],'val'=>[]];
    $ingrs = $clients = array();
    if ($books){
      foreach ($books as $book){
        if ($book->type_book == 2){
          $cMonth = intval(date('n', strtotime($book->start)));

          if (!isset($ingrs[$cMonth])) $ingrs[$cMonth] = 0;
          if (!isset($clients[$cMonth])) $clients[$cMonth] = 0;
          $ingrs[$cMonth] += $book->get_costProp();//+$book->cost_park+$book->cost_lujo;
          $clients[$cMonth] += $book->pax;
        }
      }
    }
    
    $aux = $startYear->format('n');
   
    for ($i = 0; $i < $diff; $i++) {
      $c_month = $aux + $i;
      if ($c_month > 12) {
        $c_month -= 12;
      }
      $estadisticas['ingresos']['label'][] = '"'.$aMonthMin[$c_month-1].'"';
      $estadisticas['ingresos']['val'][] = isset($ingrs[$c_month]) ? round($ingrs[$c_month]) : 0;
      $estadisticas['clientes']['label'][] = '"'.$aMonthMin[$c_month-1].'"';
      $estadisticas['clientes']['val'][] = isset($clients[$c_month]) ? $clients[$c_month] : 0;
    }
    
    $estadisticas['ingresos']['label'] = implode(',', $estadisticas['ingresos']['label']);
    $estadisticas['ingresos']['val'] = implode(',', $estadisticas['ingresos']['val']);
    $estadisticas['clientes']['label'] = implode(',', $estadisticas['clientes']['label']);
    $estadisticas['clientes']['val'] = implode(',', $estadisticas['clientes']['val']);
    
   
    return view('backend.owned.index', [
        'user' => \App\User::find(Auth::user()->id),
        'room' => $room,
        'books' => $books,
        'mes' => $mes,
        'reservas' => $arrayReservas,
        'year' => $year,
        'days' => $arrayDays,
        'arrayMonths' => $arrayMonths,
        'total' => $total,
        'apto' => $apto,
        'park' => $park,
        'lujo' => $lujo,
        'pagos' => $gastos,
        'pagototal' => $pagototal,
        'mobile' => new Mobile(),
        'estadisticas' => $estadisticas,
        'roomscalendar' => [$room],
        'arrayReservas' => $arrayReservas,
        'diff' => $diff,
        'startYear' => $startYear,
        'endYear' => $endYear
    ]);
  }

  // Pagina de propietario
  public function operativaOwned() {

    return view('backend.owned.operativa', ['mobile' => new Mobile()]);
  }

  public function tarifasOwned($name) {
    $room = \App\Rooms::where('nameRoom', $name)->first();

    if ($room->owned == Auth::user()->id) {


      $room = \App\Rooms::where('nameRoom', $name)->first();
    } elseif (Auth::user()->role == 'admin') {

      $room = \App\Rooms::where('nameRoom', $name)->first();
    } else {

      return view('errors.owned-access');
    }
    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $diff = $startYear->diffInMonths($endYear);
//                dd($diff);
    return view('backend.owned.tarifa', [
        'mobile' => new Mobile(),
        'room' => $room,
        'startYear' => $startYear,
        'endYear' => $endYear,
        'diff' => $diff,
    ]);
  }

  public function descuentosOwned() {

    return view('backend.owned.descuento', ['mobile' => new Mobile()]);
  }

  public function fiscalidadOwned() {

    return view('backend.owned.fiscalidad', ['mobile' => new Mobile()]);
  }

  public function bloqOwned(Request $request) {

    $aux = str_replace('Abr', 'Apr', $request->input('fechas'));

    $date = explode('-', $aux);

    $start = Carbon::createFromFormat('d M, y', trim($date[0]));
    $finish = Carbon::createFromFormat('d M, y', trim($date[1]));
    $diff = $start->diffInDays($finish);
    $startDate = $start->format('Y-m-d');
    $finishDate = $finish->format('Y-m-d');
    $room = \App\Rooms::find($request->input('room'));

    $book = new Book();

    if (Book::availDate($startDate, $finishDate, $room->id)) {
      $bloqueo = new \App\Customers();
      $bloqueo->user_id = Auth::user()->id;
      $bloqueo->name = 'Bloqueo ' . Auth::user()->name;
      $bloqueo->save();
      $book->user_id = Auth::user()->id;
      $book->customer_id = $bloqueo->id;
      $book->room_id = $room->id;
      $book->start = $startDate;
      $book->finish = $finishDate;
      $book->nigths = $diff;
      $book->type_book = 7;
 
      $book->bookingProp($room);
      
      $book->total_ben = $book->total_price - $book->get_costeTotal();
      $book->inc_percent = $book->get_inc_percent();
      $book->ben_jorge = $book->total_ben * $room->typeAptos->PercentJorge / 100;
      $book->ben_jaime = $book->total_ben * $room->typeAptos->PercentJaime / 100;


      if ($book->save()) {
        \App\Expenses::setExpenseLimpieza($book->id, $room, $finish,$book->cost_limp);
        $book->sendAvailibilityBy_dates($book->start,$book->finish);
        /* Cliente */
        Mail::send(['html' => 'backend.emails.bloqueoPropietario'], ['book' => $book], function ($message) use ($book) {
          $message->from('bloqueos@apartamentosierranevada.net');
          $message->to('reservas@apartamentosierranevada.net');
          //$message->to('iavila@daimonconsulting.com');
          $message->subject('BLOQUEO ' . strtolower($book->user->name));
        });
      }

      return "Reserva Guardada";
    } else {
      return "No se puede guardar reserva";
    }
  }

  public function facturasOwned($name) {
    $room = \App\Rooms::where('nameRoom', $name)->first();
    if ($room->owned !== Auth::user()->id) {
      if (Auth::user()->role !== 'admin') {
         return view('errors.owned-access');
      }
    }
    
    $year = $this->getYearData(date('Y'));
    $startYear = new Carbon($year->start_date);
    $endYear   = new Carbon($year->end_date);

    //Pagada-la-señal y reserva-prop
    $books = Book::where_type_book_prop()->where('room_id', $room->id)
            ->where('start', '>=', $startYear)->where('start', '<=', $endYear)->orderBy('start', 'ASC')
            ->get();


    return view('backend.owned.facturas', [
        'mobile' => new Mobile(),
        'books' => $books,
        'date' => $startYear,
        'room' => $room
    ]);
  }

}
