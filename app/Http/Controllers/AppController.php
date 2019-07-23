<?php
/**
 * Created by PhpStorm.
 * User: Ian Avila
 * Date: 27/05/2019
 * Time: 19:56
 */

namespace App\Http\Controllers;

use App\Services\PaylandService;
use App\Years;
use Carbon\Carbon;

class AppController extends Controller
{
    private $paylandClient;
    const SANDBOX_ENV = "/sandbox";
    public function __construct()
    {
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
     * @return mixed
     */
    protected static function getYearData($year)
    {
        $activeYear = Years::where('year', $year)->first();
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
}