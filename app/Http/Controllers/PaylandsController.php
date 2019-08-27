<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaylandsController extends AppController
{
	public function payment(Request $request)
	{
		$urlToRedirect        = $this->generateOrderPayment($request->all());
		//dd($params);
		//return \redirect($urlToRedirect);
        return view('backend.bookStatus.bookPaylandPay', [ 'url' => $urlToRedirect]);
	}

	public function processPaymentBook(Request $request, $id, $payment)
	{
        $book = \App\Book::find($id);
        $this->payBook($id, $payment);
        return redirect()->route('book.update', ['id' => $book->id]);

	}

    public function link(Request $request)
    {
        if ($request->book != 0)
            $book = \App\Book::find($request->book);
        //$importe = base64_encode($request->importe);
        $params['amount']          = ($request->importe) * 100;
        $params['customer_ext_id'] = "admin@" . $_SERVER['REQUEST_URI'];
        $params['operative']       = "AUTHORIZATION";
        $params['secure']          = false;
        $params['signature']       = env('PAYLAND_SIGNATURE');
        $params['service']         = env('PAYLAND_SERVICE');
        $params['description']     = "COBRO ESTANDAR";
        $params['url_ok']          = route('thanks-you');
        $params['url_ko']          = route('thanks-you');

        $orderPayment  = $this->getPaylandApiClient()->payment($params);
        $urlToRedirect = $this->getPaylandApiClient()->processPayment($orderPayment->order->token);
        $url           = $urlToRedirect;
        return view('backend.bookStatus.bookPaylandPay', [ 'url' => $url]);
    }

    public function thansYouPayment(Request $request, $id, $payment)
    {
        $book = \App\Book::find($id);
        $this->payBook($id, $payment);
        return redirect()->route('thanks-you');
    }
}
