<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Auth;
use Mail;
use App\Classes\Mobile;
use URL;
use File;
use Route;
use App\ForfaitsPrices;
use App\ForfaitsCalendar;
use App\Http\Controllers\FortfaitsController;

class FortfaitsController extends Controller
{
    public static $fortfaits = [

                           'fortfait-adulto' => [
                                          'info' => 'Adulto entre 17 - 59 años',
                                          'precios' => [
                                                      1 => 0.00,
                                                      2 => 0.00,
                                                      3 => 0.00,
                                                      4 => 0.00,
                                                      5 => 0.00,
                                                      6 => 0.00,
                                                      7 => 0.00
                                                      ],
                                       ],

                           'fortfait-junior' => [
                                          'info' => 'Junior entre 6 - 12 años (adulto/senior con discapacidad)',
                                          'precios' => [
                                                      1 => 0.00,
                                                      2 => 0.00,
                                                      3 => 0.00,
                                                      4 => 0.00,
                                                      5 => 0.00,
                                                      6 => 0.00,
                                                      7 => 0.00
                                                      ],
                                       ],

                           'fortfait-junior-formula-familiar' => [
                                          'info' => 'Junior formula familiar ( min 1 adulto + 2 niños )',
                                          'precios' => [
                                                      1 => 0.00,
                                                      2 => 0.00,
                                                      3 => 0.00,
                                                      4 => 0.00,
                                                      5 => 0.00,
                                                      6 => 0.00,
                                                      7 => 0.00
                                                      ],
                                       ],

                           'fortfait-juvenil' => [
                                          'info' => 'Juvenil entre 13 - 16 años',
                                          'precios' => [
                                                      1 => 0.00,
                                                      2 => 0.00,
                                                      3 => 0.00,
                                                      4 => 0.00,
                                                      5 => 0.00,
                                                      6 => 0.00,
                                                      7 => 0.00
                                                      ],
                                       ],

                           'fortfait-juvenil-formula-familiar' => [
                                          'info' => 'Juvenil formula familiar ( min 1 adulto + 2 niños )',
                                          'precios' => [
                                                      1 => 0.00,
                                                      2 => 0.00,
                                                      3 => 0.00,
                                                      4 => 0.00,
                                                      5 => 0.00,
                                                      6 => 0.00,
                                                      7 => 0.00
                                                      ],
                                       ],

                           'fortfait-senior' => [
                                          'info' => 'Senior entre 60 - 69 años',
                                          'precios' => [
                                                      1 => 0.00,
                                                      2 => 0.00,
                                                      3 => 0.00,
                                                      4 => 0.00,
                                                      5 => 0.00,
                                                      6 => 0.00,
                                                      7 => 0.00
                                                      ],
                                       ],

                           'fortfait-adulto' => [
                                          'info' => 'Adulto entre 17 - 59 años',
                                          'precios' => [
                                                      1 => 0.00,
                                                      2 => 0.00,
                                                      3 => 0.00,
                                                      4 => 0.00,
                                                      5 => 0.00,
                                                      6 => 0.00,
                                                      7 => 0.00
                                                      ],
                                       ],
                        ];

   public static $cursillos = [
               'cursillo-esqui-semanal' => [
                                 'info' => 'SEMANA 3 HRS DIARIAS',
                                 'precios' => [
                                             1 => null,
                                             2 => 83.00,
                                             3 => 108.00,
                                             4 => 127.00,
                                             5 => 145.00,
                                             6 => null,
                                             7 => null
                                             ],
                                 ],
               'cursillo-esqui-finde' => [
                                 'info' => 'SEMANA 3 HRS DIARIAS',
                                 'precios' => [
                                             1 => null,
                                             2 => 83.00,
                                             3 => null,
                                             4 => null,
                                             5 => null,
                                             6 => null,
                                             7 => null
                                             ],
                                 ],
               'clases-particulares' => [
                                 'info' => '',
                                 'precios' => [
                                             1 => 47.00,
                                             2 => 51.00,
                                             3 => 56.00,
                                             4 => 60.00,
                                             5 => null,
                                             6 => null,
                                             7 => null
                                             ],
                                 ],
            ];
   public static $jardin = [

            'guarderia-jardin-alpino-am' => [
                              'info' => 'MAÑANAS 10:00.- A 13:00.-HRS',
                              'precios' => [
                                          1 => 45.00,
                                          2 => 83.00,
                                          3 => 105.00,
                                          4 => 122.00,
                                          5 => 145.00,
                                          6 => 153.00,
                                          7 => 160.00
                                          ],
                              ],
            'guarderia-jardin-alpino-pm' => [
                              'info' => 'TARDES 13:00.- A 16:00.-HRS',
                              'precios' => [
                                          1 => 38.00,
                                          2 => 71.00,
                                          3 => 89.00,
                                          4 => 104.00,
                                          5 => 123.00,
                                          6 => 129.00,
                                          7 => 135.00
                                          ],
                              ],
            'guarderia-jardin-alpino-full' => [
                              'info' => 'DIA COMPLETO 10:00.- A 16:00.-HRS',
                              'precios' => [
                                          1 => 76.00,
                                          2 => 138.00,
                                          3 => 175.00,
                                          4 => 204.00,
                                          5 => 241.00,
                                          6 => 254.00,
                                          7 => 266.00
                                          ],
                              ]
            ];


   public function forfait(Request $request)
    {   
         $mobile = new Mobile();
      $products = [
                  'fortfaits' => self::$fortfaits,
                  'cursillos' => self::$cursillos,
                  'jardin' => self::$jardin,
               ]; 


      return view('frontend.forfait.index', ['products' => $products, 'mobile' => $mobile] );
    }

    public static function calculatePrice(){
//        print_r($_POST);
        
        $price = NULL;
        $prices = [];
        
        $prices_blocks = [
            'promo' => 0,
            'spring' => 0,
            'season_low' => 0,
            'season_high' => 0
        ];
        
        $rates_priorities = ['season_high','season_low','promo','spring'];
        
        $start_date = date('Ymd',strtotime($_POST['start_date']));
        $end_date = date('Ymd',strtotime($_POST['end_date']));

        $type = $_POST['type'];
        $subtype = $_POST['subtype'];
        $quantity = $_POST['quantity'];
        $times = $_POST['times'];
        $ski_type = $_POST['ski_type'];
        $material_type = $_POST['material_type'];

        switch ($type){
            case 'forfait':
                
                $dates = FortfaitsController::dateRange($start_date,$end_date);
//                $days = count($dates);

                $calendar_sql = ForfaitsCalendar::  select("date","type")
                                                    ->where("date",">=",$start_date)
                                                    ->where("date","<=",$end_date)
                                                    ->get();
                foreach($calendar_sql as $item){
                    $prices[$item->date] = $item->type;
                }

                foreach($prices as $price_date => $rate){
                    if(!isset($prices_blocks[$rate])){
                        $prices_blocks[$rate] = 1;
                    }else{
                        $prices_blocks[$rate] += 1; 
                    }
                }
                
                arsort($prices_blocks);
//                print_r($prices_blocks);
                
                $rate_selected = NULL;
                $rate_value_selected = NULL;
                foreach($prices_blocks as $rate_key => $price_block){
                    if($rate_selected == NULL){
                        $rate_selected = $rate_key;
                        $rate_value_selected = $price_block;
                    }elseif($price_block == $rate_value_selected){
                        if(array_search($rate_key,$rates_priorities) > array_search($rate_selected,$rates_priorities)){
                            $rate_selected = $rate_key;
                            $rate_value_selected = $price_block;
                        }
                    }
                }

                if(count($prices) > 0){
                    $price = 0;

                    $prices_sql = ForfaitsPrices::  select("price_".$rate)
                                                    ->where("type","=","$subtype")
                                                    ->where("days","=","$times")
                                                    ->get();

                    foreach($prices_sql as $forfait){
                        $price += $forfait->{"price_".$rate}*$quantity;
                    }
                }

                $price = str_replace('.',',',$price);

                break;
            case 'material':
                $price = '';/*100*$times;*/
                break;
            
            case 'classes':
                $price = '';/*100*$times;*/
                break;
        }

        return $price;
    }
    
    public static function dateRange($startDate, $endDate){
        $range = array();

        if (is_string($startDate) === true) $startDate = strtotime($startDate);
        if (is_string($endDate) === true ) $endDate = strtotime($endDate);

        if ($startDate > $endDate) return UtilsController::dateRange($endDate, $startDate);

        do {
            $range[] = date('Ymd', $startDate);
            $startDate = strtotime("+ 1 day", $startDate);
        } while($startDate <= $endDate);

        return $range;
    }

}
