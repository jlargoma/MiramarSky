<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Product as Product;
use PDF;

class PdfController extends Controller
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
        $pdf = PDF::loadView('pdf._pdfWithData', ['data' => $data]);
        return $pdf->stream('documento-checkin-'.str_replace(' ', '-', strtolower($book->customer->name)).'.pdf');

    }




}
