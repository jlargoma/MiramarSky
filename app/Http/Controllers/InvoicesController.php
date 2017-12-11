<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use PDF;
use \Carbon\Carbon;
use Auth;
use Mail;
use App\Classes\Mobile;

class InvoicesController extends Controller
{
    public function index($year='')
    {
    	if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();
        }

        if ($date->copy()->format('n') >= 9) {
            $start = new Carbon('first day of September '.$date->copy()->format('Y'));
        }else{
            $start = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
        }


        $books = \App\Book::where('start','>',$date->copy()->subMonth())
                            ->where('finish','<',$date->copy()->addYear())
                            ->where('type_book',2)
                            ->orderBy('created_at','DESC')
                            ->get();


        return view('backend/invoices/index', compact('books','mobile'));
        
    }


    public function view($data)
    {
        $data = base64_decode($data);
        $data = explode('-', $data);



        $id = $data[0];
        $book = \App\Book::find($id);

        $num = $data[1];

        return view('invoices/invoice', compact('book', 'num'));

    }

    public function download($data)
    {
        $data = base64_decode($data);
        $data = explode('-', $data);

  

        $id = $data[0];
        $book = \App\Book::find($id);

        $num = $data[1];

        $numFact = substr($book->room->nameRoom , 0,2).Carbon::CreateFromFormat('Y-m-d',$book->start)->format('Y').str_pad($num, 5, "0", STR_PAD_LEFT);
        $pdf = PDF::loadView('invoices/invoice', [ 'book' => $book, 'num' => $num]);
        return $pdf->stream('factura-'.$numFact.'-'.str_replace(' ', '-', strtolower($book->customer->name)).'.pdf');
    }
}
