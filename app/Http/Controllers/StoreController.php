<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use Mail;
use App\Classes\Mobile;

class StoreController extends Controller
{
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

        $start = Carbon::createFromFormat('d M, y' , trim($date[0]))->format('d/m/Y');
        $finish = Carbon::createFromFormat('d M, y' , trim($date[1]))->format('d/m/Y');
        

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
}
