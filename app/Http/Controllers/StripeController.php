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
	  					// "secret_key"      => "sk_test_o40xNAzPuB6sGDEY3rPQ2KUN",
					  	// "publishable_key" => "pk_test_YNbne14yyAOIrYJINoJHV3BQ"

                        "secret_key" => "sk_live_JKRWYAtvJ31tqwZyqNErMEap",
                        "publishable_key" => "pk_live_wEAGo29RoqPrXWiw3iKQJtWk",
					];



    public function stripePayment($id_book)
    {

    		$id_book = base64_decode($id_book);


    		$book = \App\Book::find($id_book);

    		if ($book->type_book != 1) {
    			$message[] = "No puedes efectuar el pago en estos momentos";
    			$message[] = "Esta reserva no esta confirmada aún.";
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
        $mobile = new Mobile();
        \Stripe\Stripe::setApiKey(self::$stripe['secret_key']);



        $token = $request->input('stripeToken');
        $email = $request->input('stripeEmail');
        $price = $request->input('price');
        $book  =  \App\Book::find($request->input('id_book'));
        
        $customer = \Stripe\Customer::create(array(
            'email' => $email,
            'source'  => $token
        ));

        try {

            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $price,
                'currency' => 'eur'
            ));

            

        } catch (\Stripe\Error\Card $e) {
        
            $message[] = "No puedes efectuar el pago en estos momentos";
            $message[] = "Tu tarjeta ha rechazado el cobro.";
            $message[] = "tarjeta";
            return view('frontend.stripe.errorBookNotAvaliable', ['message' => $message,'book' => $book, 'mobile'  => new Mobile()]);

        }catch (Exception $e) {

            $message[] = "No puedes efectuar el pago en estos momentos";
            $message[] = $e->getMessage();//"Tu tarjeta ha rechazado el cobro.";
            $message[] = "otros";//"Tu tarjeta ha rechazado el cobro.";
            return view('frontend.stripe.errorBookNotAvaliable', ['message' => $message,'book' => $book, 'mobile'  => new Mobile()]);

        }
        

        
        if ($charge->status == "succeeded") {
            

                if ($book->changeBook(2, "", $book)) {

                    /* Make a charge on apartamentosierranevada.net */



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
