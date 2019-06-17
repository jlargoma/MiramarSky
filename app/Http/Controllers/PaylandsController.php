<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaylandsController extends AppController
{
    public function payment(Request $request)
    {

        $params = $request->all();
        unset($params['_token']);
        $customer = \App\Customers::find($params['customer_id']);
        $params['amount']          = ($params['amount']) * 100;
        $params['customer_ext_id'] = $customer->email;
        $params['operative']       = "AUTHORIZATION";
        $params['secure']          = true;
        $params['signature']       = env('PAYLAND_SIGNATURE');
        $params['service']         = env('PAYLAND_SERVICE');
        $params['description']     = "COBRO RESERVA CLIENTE" . $customer->name;

        $orderPayment = $this->getPaylandApiClient()->payment($params);
        $urlToRedirect = $this->getPaylandApiClient()->processPayment($orderPayment->order->token);
        //dd($urlToRedirect);
        return \redirect($urlToRedirect);
    }
}
