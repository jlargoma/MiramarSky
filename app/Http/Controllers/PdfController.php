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
        // if($request->has('download')){
        //     $pdf = PDF::loadView('pdf._pdfWithData');
        //     return $pdf->download('pdf._pdfWithData');
        // }
        return view('pdf._pdfWithData',[
        								'book' => $book,
        								'pendiente' => $pending,
        								]);
        

    }
}
