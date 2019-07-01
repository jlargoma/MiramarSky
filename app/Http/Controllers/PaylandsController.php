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
        $this->payBook($id, $payment);
        return redirect()->route('book.update', ['id' => $book->id]);

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

    public function link(Request $request)
    {
        if ($request->book != 0)
            $book = \App\Book::find($request->book);
        //$importe = base64_encode($request->importe);
        $params['amount']          = ($request->importe) * 100;
        $params['customer_ext_id'] = "admin@" . $_SERVER['REQUEST_URI'];
        $params['operative']       = "AUTHORIZATION";
        $params['secure']          = true;
        $params['signature']       = env('PAYLAND_SIGNATURE');
        $params['service']         = env('PAYLAND_SERVICE');
        $params['description']     = "COBRO ESTANDAR";
        $params['url_ok']          = ($request->book == 0) ? route('dashboard.planning') : route('payland.proccess.payment.book', [ 'id' => $book->id, 'payment' => $params['amount']]);
        $params['url_ko']          = ($request->book == 0) ? route('dashboard.planning') : route('payland.proccess.payment.book', [ 'id' => $book->id, 'payment' => $params['amount']]);

        $orderPayment  = $this->getPaylandApiClient()->payment($params);
        $urlToRedirect = $this->getPaylandApiClient()->processPayment($orderPayment->order->token);
        $url           = $urlToRedirect;
        $response      = '<div class="col-md-2 col-xs-12">
                                <h2 class="text-center" style="font-size: 18px; line-height: 18px; margin: 0;">
                                                                    
                                    <a href="whatsapp://send?text=En este link podrás realizar el pago de la señal.&#10; En el momento en que efectúes el pago, te llegará un email - ' . $url . '" data-action="share/whatsapp/share">
                                        <i class="fa fa-whatsapp fa-3x" aria-hidden="true"></i>
                                    </a>
                                </h2>
                            </div>
                            <div class="col-md-10 col-xs-12">
                                <h2 class="text-center" style="font-size: 24px; line-height: 15px">
                                    <span style="font-size: 18px;">En este link podrás realizar el pago de la señal.<br> En el momento en que efectúes el pago, te legará un email</span><br>
                                    <a target="_blank" href="' . $url . '">
                                        ' . substr($url, 0, 45) . '...     
                                    </a>
                                </h2>
                                <div class="row text-center">
                                    <button class="btn btn-cons" type="button" id="copy-link-stripe" data-link="' . $url . '">
                                        <span class="bold">Copiar Link</span>
                                    </button>  
                                </div>
        
                            </div>';

        return $response;
    }

    public function thansYouPayment(Request $request, $id, $payment)
    {
        $book = \App\Book::find($id);
        $this->payBook($id, $payment);
        return redirect()->route('thanks-you');
    }
}
