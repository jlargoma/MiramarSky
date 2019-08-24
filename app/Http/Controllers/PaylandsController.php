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
        $params['url_ok']          = route('thanks-you');
        $params['url_ko']          = route('thanks-you');

        $orderPayment  = $this->getPaylandApiClient()->payment($params);
        $urlToRedirect = $this->getPaylandApiClient()->processPayment($orderPayment->order->token);
        $url           = $urlToRedirect;
        
        $response      = '<div class="send-wsp">
                            <a href="whatsapp://send?text=En este link podrás realizar el pago de la señal.&#10; En el momento en que efectúes el pago, te llegará un email - ' . $url . '" data-action="share/whatsapp/share">
                            <i class="fa fa-whatsapp fa-3x" aria-hidden="true"></i>
                            </a>
                          </div>
                          <div class="text">
                          En este link podrás realizar el pago de la señal. En el momento en que efectúes el pago, te legará un email.<br/>
                          <a target="_blank" href="' . $url . '">
                            ' . substr($url, 0, 45) . '... 
                          </a>
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
