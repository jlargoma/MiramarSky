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

    public function isde($value='')
    {
        $invoices = \App\Invoices::where('status' , 1)->get();
        return view('backend/invoices/isde/index', compact('invoices'));

    }

    public function solicitudes($value='')
    {
        $invoices = \App\Invoices::where('status' , 0)->get();
        return view('backend/invoices/isde/solicitudes', compact('invoices'));

    }


    public function viewIsde($id)
    {
        $invoice = \App\Invoices::find(base64_decode($id));
        return view('backend.invoices.isde.invoice',[ 'invoice' => $invoice]);
    }

    public function downloadIsde($id)
    {
        $invoice = \App\Invoices::find(base64_decode($id));

        $pdf = PDF::loadView('backend.invoices.isde.invoice', [ 'invoice' => $invoice]);
        return $pdf->stream('#SN'.Carbon::CreateFromFormat('Y-m-d',$invoice->start)->format('y').$invoice->id.' - '.str_replace(' ', '-', strtolower($invoice->name)).'.pdf');
       
    }

    public function updateIsde($id)
    {
        $invoice = \App\Invoices::find(base64_decode($id));
        return view('backend.invoices.isde._formUpdateInvoice',[ 'invoice' => $invoice]);
    }

    public function saveUpdate(Request $request)
    {
        $invoice = \App\Invoices::find($request->input('id'));
        $invoice->name = $request->input('name');
        $invoice->nif = $request->input('nif');
        $invoice->address = $request->input('address');
        $invoice->phone = $request->input('phone');
        $invoice->postalcode = $request->input('postalcode');
        $invoice->name_business = $request->input('name_business');
        $invoice->nif_business = $request->input('nif_business');
        $invoice->address_business = $request->input('address_business');
        $invoice->phone_business = $request->input('phone_business');
        $invoice->zip_code_business = $request->input('zip_code_business');
        $invoice->total_price = $request->input('total_price');

        $aux = str_replace('Abr', 'Apr', $request->input('fechas'));

        $date = explode('-', $aux);

        $start = Carbon::createFromFormat('d M, y' , trim($date[0]))->format('Y-m-d');
        $finish = Carbon::createFromFormat('d M, y' , trim($date[1]))->format('Y-m-d');

        $invoice->start = $start;
        $invoice->finish = $finish;
        $invoice->status = $request->input('status');

        if ($invoice->save()){
            if ($invoice->status == 1){
                return redirect()->action('InvoicesController@isde');
            }else{
                return redirect()->action('InvoicesController@solicitudes');
            }

        }

    }

    public function deleteIsde($id)
    {
        if (\App\Invoices::find(base64_decode($id))->delete()){
            return redirect()->action('InvoicesController@solicitudes');
        };

    }
}
