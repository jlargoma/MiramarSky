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

class AppController extends Controller
{
    private $paylandClient;

    public function __construct()
    {
        $paylandConfig       = [
            'endpoint'  => env('PAYLAND_ENDPOINT'),
            'api_key'   => env('PAYLAND_API_KEY'),
            'signarute' => env('PAYLAND_SIGNATURE'),
            'service'   => env('PAYLAND_SERVICE')
        ];
        $this->paylandClient = new PaylandService($paylandConfig);
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
}