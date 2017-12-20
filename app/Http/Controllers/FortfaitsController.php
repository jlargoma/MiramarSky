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


	

}
