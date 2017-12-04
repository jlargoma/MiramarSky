<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use Mail;
use App\Classes;
use App\Classes\Mobile;
use \Stripe\Stripe as Stripe;
class StoreController extends Controller
{
    public static   $stripe = [
                        "secret_key"      => "sk_test_rqTqvjOVCPcnme6FL7TWPVpY",
                        "publishable_key" => "pk_test_4bixTma4boJT7CFkldwfdHQZ"

                        // "secret_key" => "sk_live_JKRWYAtvJ31tqwZyqNErMEap",
                        // "publishable_key" => "pk_live_wEAGo29RoqPrXWiw3iKQJtWk",
                    ];


    public function index(Request $request)
    {
    	

    	return view('frontend.store.index', [
    											'mobile' => new Mobile(),
    											]);
    }




    public function searchBook(Request $request)
    {
    	$aux = str_replace('Abr', 'Apr', $request->date);

        $date = explode('-', $aux);

        $start = Carbon::createFromFormat('d M, y' , trim($date[0]))->format('Y-m-d');
        $finish = Carbon::createFromFormat('d M, y' , trim($date[1]))->format('Y-m-d');
        

    	$arrayCustomers = array();
    	$customers = \App\Customers::where('email', 'LIKE', '%'.$request->email.'%')->get();
    	foreach ($customers as $key => $customer) {
    		if (!in_array($customer->id, $arrayCustomers)) {
    			$arrayCustomers[] = $customer->id;
    		}
    	}

    	$books = \App\Book::whereIn('customer_id', $arrayCustomers)
    						->whereIn('type_book', [1,2,7,8])
    						->where('start','=', $start)
    						->where('finish','=', $finish)
    						->get();

    	return view('frontend.store.responses._searchBook', [ 'books' => $books ]);
    }


    public function cartByBook($id)
    {
                
        $book = \App\Book::find(base64_decode($id));
        $products = \App\Products::where('status', 1)->get();
        $hasOrders = \App\Orders::where('book_id', $book->id)->where('status', 0)->first();
        if (count($hasOrders) == 0) {
            $order = new \App\Orders();
            $order->book_id = $book->id;
            $order->date = Carbon::createFromFormat('Y-m-d', $book->start)->format('Y-m-d');
            $order->status  = 0;
            $order->save();
        }else{
            $order = $hasOrders;
        }

        

        return view('frontend.store.summary', [ 
                                                'book'     => $book, 
                                                'order'    => $order, 
                                                'products' => $products,
                                                'mobile'   => new Mobile(),
                                             ]);

    }

    public function searchByName(Request $request)
    {
        $products = \App\Products::where('name', 'LIKE', '%'.$request->searchString.'%')->get();
        $order    = \App\Orders::find($request->order);
        return view('frontend.store._tableProducts', [ 
                                                    'products' => $products,
                                                    'order' => $order,
                                                    'mobile'   => new Mobile(),
                                                 ]);
    }

    public function addCart(Request $request)
    {
        
        $orderProducts             = new \App\Products_orders();
        $orderProducts->order_id   = $request->order;
        $orderProducts->product_id = $request->product;
        $orderProducts->quantity   = $request->qty;

        if ($orderProducts->save()) {
            return "OK";
        }

    }

    public function getSummaryCart(Request $request)
    {
        $orderProducts = \App\Products_orders::where('order_id', $request->order)->get();
        $order = \App\Orders::find($request->order);
        return view('frontend.store._summaryCart', ['ordersProducts' => $orderProducts, 'order' => $order]);

    }


    public function checkout(Request $request, $id)
    {
        
        $orderProducts = \App\Products_orders::where('order_id', base64_decode($id))->get();
        $order = \App\Orders::find(base64_decode($id));

        \Stripe\Stripe::setApiKey(self::$stripe['secret_key']);


        return view('frontend.store.checkout', [
                                                'ordersProducts' => $orderProducts,
                                                'order'          => $order,
                                                'mobile'         => new Mobile(),
                                                'stripe'         => self::$stripe,
                                                'payment'        => 0,
                                                ]);


    }

    public function payment(Request $request)
    {
        $mobile = new Mobile();
        \Stripe\Stripe::setApiKey(self::$stripe['secret_key']);
        $order = \App\Orders::find($request->input('id_order'));



        $token = $request->input('stripeToken');
        $email = $request->input('stripeEmail');
        $price = $request->input('importe') * 100;
        $book  =  $order->book;

        
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

            if ($charge->status == "succeeded") {
                $order->status = 1;
                if ($order->save()) {
                    // Mail confirmacion
                    // 
                    // 
                    // 
                    // 
                    // 
                    $message[] = "Pago aceptado";
                    $message[] = "Tu pedido se ha realizado correctamente";
                    $message[] = "";
                    return view('frontend.store.checkout', [
                                                    'message'  => $message,
                                                    'order'    => $order,
                                                    'payment'  => 1,
                                                    'stripe'         => self::$stripe,

                                                    'mobile'   => new Mobile()
                                                ]);
                }
                
                


            }

        } catch (\Stripe\Error\Card $e) {
        
            $message[] = "No puedes efectuar el pago en estos momentos";
            $message[] = "Tu tarjeta ha rechazado el cobro.";
            $message[] = "tarjeta";
            return view('frontend.store.checkout', [
                                                    'message'  => $message,
                                                    'order'    => $order,
                                                    'payment'  => 1,
                                                    'stripe'         => self::$stripe,
                                                    'mobile'   => new Mobile()
                                                ]);

        }catch (Exception $e) {

            $message[] = "No puedes efectuar el pago en estos momentos";
            $message[] = $e->getMessage();//"Tu tarjeta ha rechazado el cobro.";
            $message[] = "otros";//"Tu tarjeta ha rechazado el cobro.";
            return view('frontend.store.checkout', [
                                                    'message'  => $message,
                                                    'order'    => $order,
                                                    'payment'  => 1,
                                                    'stripe'         => self::$stripe,
                                                    'mobile'   => new Mobile()
                                                ]);

        }
        

        


    }



        


        // foreach ($array as $key => $pr) {
        //     $product = new \App\Products();
        //     $product->name = ucfirst($pr['nombre']);
        //     $product->description = strtolower($pr['nombre'])."...";
        //     $product->image = '';
        //     $product->tax_id = 2;
        //     $product->unity = $pr['precio'] - ( $pr['precio'] * 0.21);
        //     $product->price = $pr['precio'];
        //     $product->status = 1;
        //     $product->save();
        // }  
        // 
        // $array = [

        //         ['nombre' => '/img/supermercado/productos/PLATANOS.jpeg'],
        // ['nombre' => '/img/supermercado/productos/MANZANA GOLDEN.jpeg'],
        // ['nombre' => '/img/supermercado/productos/NARANJAS.jpeg'],
        // ['nombre' => '/img/supermercado/productos/MANDARINA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/LECHUGA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/TOMATE.jpeg'],
        // ['nombre' => '/img/supermercado/productos/CEBOLLA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PIMIENTO VERDE.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PATATAS.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PAPEL HIGIENICO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/SERVILLETA.jpeg '],
        // ['nombre' => '/img/supermercado/productos/ROLLO COCINA.jpeg '],
        // ['nombre' => '/img/supermercado/productos/BAYETA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/ESTROPAJO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/FAIRY.jpeg'],
        // ['nombre' => '/img/supermercado/productos/BASURA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/ALUMINIO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/TRANSPARENTE.jpeg'],
        // ['nombre' => '/img/supermercado/productos/SANEX DERMO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PANTENE CLASICO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/jamon-cocido-lonchas-finissimas-campofrio.jpg'],
        // ['nombre' => '/img/supermercado/productos/pechuga-de-pavo-lonchas-finissimas-campofrio.jpg'],
        // ['nombre' => '/img/supermercado/productos/MORTADELA CLASICA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/JAMON CURADO NAVIDUL'],
        // ['nombre' => '/img/supermercado/productos/JAMON IBERICO DE BELLOTA ANTONIO ALVAREZ '],
        // ['nombre' => '/img/supermercado/productos/CHORIZO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/SALCHICHON.jpeg'],
        // ['nombre' => '/img/supermercado/productos/BACON.jpeg'],
        // ['nombre' => '/img/supermercado/productos/ALHAMBRA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/MAHOU.jpeg'],
        // ['nombre' => '/img/supermercado/productos/JOVEN.jpeg'],
        // ['nombre' => '/img/supermercado/productos/RAMON BILBAO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PROTOS.jpeg'],
        // ['nombre' => '/img/supermercado/productos/BLANCO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/MUMM.jpeg'],
        // ['nombre' => '/img/supermercado/productos/CAVA FREIXENET SEMI SECO'],
        // ['nombre' => '/img/supermercado/productos/BIMBO SIN CORTEZA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PAN ORTIZ.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PAN INTEGRAL.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PAN BURGER BIMBO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/KETCHUP.jpeg'],
        // ['nombre' => '/img/supermercado/productos/MAYONESA CALVE.jpeg'],
        // ['nombre' => '/img/supermercado/productos/MOSTAZA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/TOMATE ORLANDO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/NAPOLITANA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/BOLOÑESA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PATE.jpeg'],
        // ['nombre' => '/img/supermercado/productos/ZANAHORIA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/REMOLACHA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/MAIZ.jpeg'],
        // ['nombre' => '/img/supermercado/productos/ACEITUNA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/ATUN'],
        // ['nombre' => '/img/supermercado/productos/BONITO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/LAYS.jpeg'],
        // ['nombre' => '/img/supermercado/productos/RUFFLES JAMON.jpeg'],
        // ['nombre' => '/img/supermercado/productos/DORITOS.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PALOMITAS SAL.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PALOMITAS MANTEQUILLA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/COCKTAIL.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PIPAS.jpeg'],
        // ['nombre' => '/img/supermercado/productos/CACAHUETE PELADO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/MAIZ GIGANTE.jpeg'],
        // ['nombre' => '/img/supermercado/productos/CHOCOLATE.jpeg'],
        // ['nombre' => '/img/supermercado/productos/CHOCOLATE ALMENDRAS.jpeg'],
        // ['nombre' => '/img/supermercado/productos/KIT KAT.jpeg'],
        // ['nombre' => '/img/supermercado/productos/SNICKERS.jpeg'],
        // ['nombre' => '/img/supermercado/productos/TWIX XTRA.jpeg'],
        // ['nombre' => '/img/supermercado/productos/KINDER BUENO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/ARROZ SOS.jpeg'],
        // ['nombre' => '/img/supermercado/productos/MACARRONES.jpeg'],
        // ['nombre' => '/img/supermercado/productos/SPAGHETTI.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PURE NATURAL.jpeg'],
        // ['nombre' => '/img/supermercado/productos/PURE CON LECHE.jpeg'],
        // ['nombre' => '/img/supermercado/productos/CALDO DE POLLO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/SOPA DE POLLO.jpeg'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],
        // ['nombre' => '/img/supermercado/productos/no-image.png'],

        //             ];
        // 
        // 

}
