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
	  					"secret_key"      => "sk_test_o40xNAzPuB6sGDEY3rPQ2KUN",
					  	"publishable_key" => "pk_test_YNbne14yyAOIrYJINoJHV3BQ"

                        // "secret_key" => "sk_live_JKRWYAtvJ31tqwZyqNErMEap",
                        // "publishable_key" => "pk_live_wEAGo29RoqPrXWiw3iKQJtWk",
					];


    /* Stripe Payments to FRONTEND */
    public function stripePayment($id_book)
    {

    		$id_book = base64_decode($id_book);


    		$book = \App\Book::find($id_book);

    		if ($book->type_book != 1  && $book->type_book != 2) {
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
                                                    'payments'       => \App\Payments::where('book_id',$book->id)->get(),
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
            return view('frontend.stripe.errorBookNotAvaliable', ['message' => $message,'book' => $book,'payments'       => \App\Payments::where('book_id',$book->id)->get(), 'mobile'  => new Mobile()]);

        }catch (Exception $e) {

            $message[] = "No puedes efectuar el pago en estos momentos";
            $message[] = $e->getMessage();//"Tu tarjeta ha rechazado el cobro.";
            $message[] = "otros";//"Tu tarjeta ha rechazado el cobro.";
            return view('frontend.stripe.errorBookNotAvaliable', ['message' => $message,'book' => $book,'payments'       => \App\Payments::where('book_id',$book->id)->get(), 'mobile'  => new Mobile()]);

        }
        

        
        if ($charge->status == "succeeded") {
            

                if ($book->changeBook(2, "", $book)) {

                    /* Make a charge on apartamentosierranevada.net */
                    $realPrice = ($price / 100);

                    $payment = new \App\Payments();
        
                    $date = Carbon::now()->format('Y-m-d');
                    $payment->book_id     = $book->id;
                    $payment->datePayment = $date;
                    $payment->import      = $realPrice;
                    $payment->comment     = "Pago desde stripe";
                    $payment->type        = 2;

                    $payment->save();



                    return view('frontend.stripe.stripe', [ 
                                                            'mobile'         => $mobile,
                                                            'payment'        => 0,
                                                            'stripe'         => self::$stripe,
                                                            'book'           => $book,
                                                            'payments'       => \App\Payments::where('book_id',$book->id)->get(),
                                                        ]);
                }


            }

        

    }



    /* Stripe Payments to BACKEND */

    public function stripePaymentBooking(Request $request)
    {
        $mobile = new Mobile();
        \Stripe\Stripe::setApiKey(self::$stripe['secret_key']);



        $token = $request->input('stripeToken');
        $email = $request->input('email');
        $price = $request->input('importe') * 100;
        
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

            $message[] = "Pago aceptado";
            $message[] = "Tu cobro se ha realizado correctamente";
            $message[] = "";
            
            if ( $request->input('id_book') ) {
                $book = \App\Book::find($request->input('id_book'));
                if ($charge->status == "succeeded") {
                    
                    if ($book->changeBook(2, "", $book)) {

                        /* Make a charge on apartamentosierranevada.net */
                        $realPrice = ($price / 100);

                        $payment = new \App\Payments();
            
                        $date = Carbon::now()->format('Y-m-d');
                        $payment->book_id     = $book->id;
                        $payment->datePayment = $date;
                        $payment->import      = $realPrice;
                        $payment->comment     = "Pago desde stripe";
                        $payment->type        = 2;

                        $payment->save();


                        $msg_params = array(
                          'msg_type' => 'success',
                          'msg_text' => 'my text to show',
                        );


                        return redirect('/admin/reservas/update/'.$book->id."?".http_build_query($msg_params));
                    }
                }

            }else{

                return view('backend.stripe.response', ['message' => $message, 'mobile'  => new Mobile()]);

            }

        } catch (\Stripe\Error\Card $e) {
        
            $message[] = "No puedes efectuar el pago en estos momentos";
            $message[] = "Tu tarjeta ha rechazado el cobro.";
            $message[] = "tarjeta";

            $msg_params = array(
              'msg_type' => 'success',
              'msg_text' => $message,
            );


            return redirect('/admin/reservas/update/'.$book->id."?".http_build_query($msg_params));

        }catch (Exception $e) {

            $message[] = "No puedes efectuar el pago en estos momentos";
            $message[] = $e->getMessage();//"Tu tarjeta ha rechazado el cobro.";
            $message[] = "otros";//"Tu tarjeta ha rechazado el cobro.";

            $msg_params = array(
              'msg_type' => 'success',
              'msg_text' => $message,
            );


            return redirect('/admin/reservas/update/'.$book->id."?".http_build_query($msg_params));

        }
    }


    public function fianza(Request $request)
    {
        $mobile = new Mobile();
        \Stripe\Stripe::setApiKey(self::$stripe['secret_key']);



        $token = $request->input('stripeToken');
        $email = $request->input('email');
        $price = $request->input('importe') * 100;
        
        $customer = \Stripe\Customer::create(array(
            'email' => $email,
            'source'  => $token
        ));


        $fianza = new \App\Fianzas();
        $fianza->book_id = $request->input('id_book');
        $fianza->customer_id = $customer->id;
        $fianza->currency = 'eur';
        $fianza->amount = $price;
        $fianza->save();

        return redirect('/admin/reservas/update/'.$request->input('id_book'));
    }

    public function payFianza(Request $request){

        $fianza = \App\Fianzas::find($request->input('id_fianza'));
        
        try {

            $charge = \Stripe\Charge::create(array(
                'customer' => $fianza->customer_id,
                'amount'   => $fianza->amount,
                'currency' => $fianza->currency
            ));

            return redirect()->back();

        } catch (\Stripe\Error\Card $e) {
        
            $message[] = "No puedes efectuar el pago en estos momentos";
            $message[] = "Tu tarjeta ha rechazado el cobro.";
            $message[] = "tarjeta";

            return response()->json($message); 
            // return view('backend.stripe.response', ['message' => $message, 'mobile'  => new Mobile()]);

        }catch (Exception $e) {

            $message[] = "No puedes efectuar el pago en estos momentos";
            $message[] = $e->getMessage();//"Tu tarjeta ha rechazado el cobro.";
            $message[] = "otros";//"Tu tarjeta ha rechazado el cobro.";

            return response()->json($message); 
            // return view('backend.stripe.response', ['message' => $message, 'mobile'  => new Mobile()]);

        }
    }

}
