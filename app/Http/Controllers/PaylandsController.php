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
		return \redirect($urlToRedirect);
	}

	public function processPaymentBook(Request $request, $id, $payment)
	{
		$book = \App\Book::find($id);
		if ($book->changeBook(2, "", $book))
		{
			$realPrice = ($payment / 100);

			$payment = new \App\Payments();

			$date                 = Carbon::now()->format('Y-m-d');
			$payment->book_id     = $book->id;
			$payment->datePayment = $date;
			$payment->import      = $realPrice;
			$payment->comment     = "Pago desde Payland";
			$payment->type        = 2;
			$payment->save();

			$data['concept']     = $payment->comment;
			$data['date']        = $date;
			$data['import']      = $realPrice;
			$data['comment']     = $payment->comment;
			$data['typePayment'] = 2;
			$data['type']        = 0;

			LiquidacionController::addBank($data);

			return redirect()->route('book.update', ['id' => $book->id]);
		}
	}

	public function generateOrderPayment($params)
	{
		$response = $params;
		if (isset($response['_token']))
			unset($params['_token']);
		$customer                    = \App\Customers::find($response['customer_id']);
		$response['amount']          = ($response['amount']) * 100;
		$response['customer_ext_id'] = $customer->email;
		$response['operative']       = "AUTHORIZATION";
		$response['secure']          = true;
		$response['signature']       = env('PAYLAND_SIGNATURE');
		$response['service']         = env('PAYLAND_SERVICE');
		$response['description']     = "COBRO RESERVA CLIENTE" . $customer->name;
		$response['url_ok']          .= "/" . $response['amount'];
		$response['url_ko']          .= "/" . $response['amount'];
		//dd($response);
		$orderPayment  = $this->getPaylandApiClient()->payment($response);
		$urlToRedirect = $this->getPaylandApiClient()->processPayment($orderPayment->order->token);
		return $urlToRedirect;

	}
}
