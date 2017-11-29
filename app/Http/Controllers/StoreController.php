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
}
