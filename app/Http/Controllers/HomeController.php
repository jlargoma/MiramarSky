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
class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $val = $request->cookie('showPopup');
        if ( !empty($val) ){
            $cookie = $request->cookie('showPopup');
        }else{
            $cookie = 0;
        }

        /* Detectamos el tipo de dispositivo*/
        $mobile = new Mobile();

        return view('frontend.home', [
                            'cookie'   => $cookie,
                            'mobile'   => $mobile,
                            ]);
    }


    public function apartamentoLujo(){
        $slides = File::allFiles(public_path().'/img/miramarski/galerias/apartamento-lujo');

        return view('frontend.pages._apartamentoLujo', [ 'slides' => $slides]);
    }
    public function estudioLujo(){

        return view('frontend.pages._estudioLujo');
    }
    public function apartamentoStandard(){

        return view('frontend.pages.apartamentoStandard');
    }
    public function estudioStandard(){

        return view('frontend.pages._estudioStandard');
    }
    public function edificio(){

        return view('frontend.pages._edificio');
    }
    public function contacto(){

        return view('frontend.contacto');
    }

}