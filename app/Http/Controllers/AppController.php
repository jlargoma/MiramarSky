<?php

namespace App\Http\Controllers;

use App\Repositories\CachedRepository;
use App\Services\PaylandService;
use App\Years;
use Carbon\Carbon;

class AppController extends Controller
{
	private $cachedRepository;
    private $paylandClient;
    const SANDBOX_ENV = "/sandbox";

    public function __construct( CachedRepository $cachedRepository )
    {
	    $this->cachedRepository = $cachedRepository;
        $endPoint = (env('PAYLAND_ENVIRONMENT') == "dev")? env('PAYLAND_ENDPOINT'). self::SANDBOX_ENV : env('PAYLAND_ENDPOINT');
        $paylandConfig       = [
                                    'endpoint'  => $endPoint,
                                    'api_key'   => env('PAYLAND_API_KEY'),
                                    'signarute' => env('PAYLAND_SIGNATURE'),
                                    'service'   => env('PAYLAND_SERVICE')
                                ];
        $this->paylandClient = new PaylandService($paylandConfig);
    }
    protected function getPaylandApiClient()
    {
        return $this->paylandClient;
    }

    /**
     * @return mixed
     */
    protected static function getActiveYear()
    {
        $activeYear = Years::where('active', 1)->first();
        return $activeYear;
    }

    /**
     * @param array $params
     *  params must be content:
     *  - dates
     *  - pax
     * @return html-form  with rooms avaliable to book and pay
     */
    protected function searchRoomsAvaliables(array $params)
    {
        //TODO refactoring this function to look for rooms with capacity to accommodate the guests and that area
        // vailable to reserve
    }

    public function payBook($id, $payment)
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

            return true;
        }
        return false;
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
		$response['description']     = "COBRO RESERVA CLIENTE " . $customer->name;
		$response['url_ok']          .= "/" . $response['amount'];
		$response['url_ko']          .= "/" . $response['amount'];
		//dd($this->getPaylandApiClient());
		$orderPayment  = $this->paylandClient->payment($response);
		$urlToRedirect = $this->paylandClient->processPayment($orderPayment->order->token);
		return $urlToRedirect;

	}
}