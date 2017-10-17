<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Stripe\Stripe as Stripe;
use Mail;
use \Carbon\Carbon;
use Auth;
use App\Classes\Mobile;
use File;
class StripeController extends Controller
{

    public static 	$stripe = [
	  					"secret_key"      => "sk_live_JKRWYAtvJ31tqwZyqNErMEap",//"sk_test_o40xNAzPuB6sGDEY3rPQ2KUN",
					  	"publishable_key" => "pk_live_wEAGo29RoqPrXWiw3iKQJtWk",//"pk_test_YNbne14yyAOIrYJINoJHV3BQ"
					];



    public function stripePayment($id_book)
    {

    		$id_book = base64_decode($id_book);


    		$book = \App\Book::find($id_book);

    		if ($book->type_book != 1) {
    			$message[] = "No puedes efectuar el pago en estos momentos";
    			$message[] = "Esta reserva no esta confirmada aún.";
    			$message[] = "Disculpa las molestias";
    			return view('frontend.stripe.errorBookNotAvaliable', ['message' => $message, 'mobile'  => new Mobile()]);
    		}

    		\Stripe\Stripe::setApiKey(self::$stripe['secret_key']);
    		$mobile = new Mobile();

	        if (!$mobile->isMobile()){
	            $slides = File::allFiles(public_path().'/img/miramarski/edificio/');             
	        }else{
	            $slides = File::allFiles(public_path().'/img/miramarski/edificio-mobile'); 
	        }

    		return view('frontend.stripe.stripe', [ 
													'mobile'         => $mobile,
													'payment'        => 1,
													'stripe'         => self::$stripe,
													'book'           => $book,
													'slidesEdificio' => $slides,
												]);
    	}

    	
    public function stripePaymentResponse(Request $request)
    {
        \Stripe\Stripe::setApiKey(self::$stripe['secret_key']);



        $token = $request->input('stripeToken');
        $email = $request->input('stripeEmail');
        $price = $request->input('price');
        $book  =  \App\Book::find($request->input('id_book'));
        
        $customer = \Stripe\Customer::create(array(
            'email' => $email,
            'source'  => $token
        ));

        $charge = \Stripe\Charge::create(array(
            'customer' => $customer->id,
            'amount'   => $price,
            'currency' => 'eur'
        ));

        if ($charge->status == "succeeded") {
            

            if ($book->changeBook(2, "", $book)) {

                $mobile = new Mobile();

                if (!$mobile->isMobile()){
                    $slides = File::allFiles(public_path().'/img/miramarski/edificio/');             
                }else{
                    $slides = File::allFiles(public_path().'/img/miramarski/edificio-mobile'); 
                }

                return view('frontend.stripe.stripe', [ 
                                                        'mobile'         => $mobile,
                                                        'payment'        => 0,
                                                        'stripe'         => self::$stripe,
                                                        'book'           => $book,
                                                        'slidesEdificio' => $slides,
                                                    ]);
            }


        }


        

    }
}
