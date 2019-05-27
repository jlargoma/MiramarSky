<?php

namespace App\Http\Controllers;

use App\Classes\Mobile;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class LimpiezaController extends AppController
{
    public function index(Request $request, $year = "")
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
        $now                     = Carbon::now();
        $rooms                   = \App\Rooms::where('state', '=', 1)->get();
        $booksCollection         = \App\Book::where('start', '>=', $now->copy()->subDays(3))
                                            ->where('start', '<=', $now->copy()->addYear())
                                            ->orderBy('start', 'ASC')
                                            ->get();
        $books                   = $booksCollection->whereIn('type_book', [2]);

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

        return view('backend.limpieza.index', [
            'mobile'     => new Mobile(),
            'books'      => $books,
            'rooms'      => $rooms,
            'payment'    => $payment,
        ]);
    }
}
