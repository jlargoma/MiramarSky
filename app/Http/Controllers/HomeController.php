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
        $mobile = new Mobile();
        return view('frontend.pages._apartamentoLujo', [ 'slides' => $slides, 'mobile'   => $mobile,]);
    }
    public function estudioLujo(){

       
    }
    public function apartamentoStandard(){

        $slides = File::allFiles(public_path().'/img/miramarski/galerias/apartamento-standard');
        $mobile = new Mobile();
        return view('frontend.pages._apartamentoStandard', [ 'slides' => $slides, 'mobile'   => $mobile,]);
    }
    public function estudioStandard(){

        // return view('frontend.pages._estudioStandard');
    }
    public function edificio(){

        // return view('frontend.pages._edificio');
    }
    public function contacto(){

        return view('frontend.contacto');
    }


    static function getPriceBook(Request $request){

        
        $date = explode('-', $request->input('date'));
       
        $start = Carbon::createFromFormat('d/m/Y' , trim($date[0]));
        $finish = Carbon::createFromFormat('d/m/Y' , trim($date[1]));
        $countDays = $finish->diffInDays($start);

        $roomAssigned = 111;
        if ($request->input('apto') == '2dorm' && $request->input('luxury') == 'si') {
            
            $rooms = \App\Rooms::where('typeApto', 2)->where('luxury', 1)->get();
            if ( count($rooms) > 0) {
                foreach ($rooms as $key => $room) {
                    if ( \App\Book::existDate($start->copy()->format('d/m/Y'), $finish->copy()->format('d/m/Y'), $room->id) ) {
                        $roomAssigned =  $room->id;
                        break;
                    }
                }
            }

            $limp = 50;
        }elseif($request->input('apto') == '2dorm' && $request->input('luxury') == 'no'){
            $rooms = \App\Rooms::where('typeApto', 2)->where('luxury', 0)->get();

            if ( count($rooms) > 0) {
                foreach ($rooms as $key => $room) {
                    if ( \App\Book::existDate($start->copy()->format('d/m/Y'), $finish->copy()->format('d/m/Y'), $room->id) ) {
                        $roomAssigned =  $room->id;
                        break;
                    }
                }
            }
            $limp = 50;
        }elseif($request->input('apto') == 'estudio' && $request->input('luxury') == 'si'){
            $rooms = \App\Rooms::where('typeApto', 1)->where('luxury', 1)->get();
            if ( count($rooms) > 0) {
                foreach ($rooms as $key => $room) {
                    if ( \App\Book::existDate($start->copy()->format('d/m/Y'), $finish->copy()->format('d/m/Y'), $room->id) ) {
                        $roomAssigned =  $room->id;
                        break;
                    }
                }
            }
            /* $room = 116;  A definir por jorge */
            $limp = 35;


        }elseif($request->input('apto') == 'estudio' && $request->input('luxury') == 'no'){
            $rooms = \App\Rooms::where('typeApto', 1)->where('luxury', 0)->get();

            if ( count($rooms) > 0) {
                foreach ($rooms as $key => $room) {
                    if ( \App\Book::existDate($start->copy()->format('d/m/Y'), $finish->copy()->format('d/m/Y'), $room->id) ) {
                        $roomAssigned =  $room->id;
                        break;
                    }
                }
            }
            $limp = 35;
        }


        $paxPerRoom = \App\Rooms::getPaxRooms($request->input('quantity'), $roomAssigned);

        $pax = $request->input('quantity');
        if ($paxPerRoom > $request->input('quantity')) {
            $pax = $paxPerRoom;
        }

        $price = 0;

        for ($i=1; $i <= $countDays; $i++) { 

            $seasonActive = \App\Seasons::getSeason($start->copy());
            $prices = \App\Prices::where('season' ,  $seasonActive)
                                ->where('occupation', $pax)->get();

            foreach ($prices as $precio) {
                $price = $price + $precio->price;
            }
        }

        if ($request->input('parking') == 'si') {
            $parking = 15 * $countDays;
        }else{
            $parking = 0;
        }

        if ($request->input('luxury') == 'si') {
            $luxury = 50;
        }else{
            $luxury = 0;
        }
        
        $total =  $price + $parking + $limp + $luxury;  


        return view('frontend.bookStatus.response', [
                                                        'id_apto' => $roomAssigned,
                                                        'pax'     => $pax,
                                                        'nigths'  => $countDays,
                                                        'apto'    => ($request->input('apto') == '2dorm')?'Apartamento': 'estudio',
                                                        'name'    => $request->input('name'),
                                                        'email'   => $request->input('email'),
                                                        'start'   => $start,
                                                        'finish'  => $finish,
                                                        'parking' => $parking,
                                                        'luxury'  => $luxury,
                                                        'total'   => $total,
                                                        'comment' => $request->input('comment'),
                                                    ]);

    }

}



