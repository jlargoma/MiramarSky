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


    public function apartamento($apto){
        $url     = $apto;
        $apto    = str_replace('-', ' ', $apto);
        $apto    = str_replace(' sierra nevada', '', $apto);



        switch ($apto) {
            case 'apartamento lujo':
                $aptoHeading       = "APARTAMENTOS DOS DORM - DE LUJO ";
                $aptoHeadingMobile = "Apto de lujo 2 DORM";

                $typeApto = 1;
                break;
            
            case 'estudio lujo':
                $aptoHeading       = "ESTUDIOS – DE LUJO";
                $aptoHeadingMobile = "Estudio de lujo";

                $typeApto = 3;
                break;
            
            case 'apartamento standard':
                $aptoHeading       = "APARTAMENTOS DOS DORM - ESTADAR ";
                $aptoHeadingMobile = "Apto Standard";

                $typeApto = 2;
                break;
            
            case 'estudio standard':
                $aptoHeading       = "ESTUDIOS – ESTANDAR";
                $aptoHeadingMobile = "Estudio Standard";

                $typeApto = 4;
                break;            
        }


        $slides = File::allFiles(public_path().'/img/miramarski/galerias/'.$url); 
        $aptos  = ['apartamento-lujo-sierra-nevada', 'estudio-lujo-sierra-nevada','apartamento-standard-sierra-nevada','estudio-standard-sierra-nevada'];


        return view('frontend.pages._apartamento2', [ 
                                                    'slides'            => $slides, 
                                                    'mobile'            => new Mobile(),
                                                    'aptoHeading'       => $aptoHeading,
                                                    'aptoHeadingMobile' => $aptoHeadingMobile,
                                                    'typeApto'          => $typeApto,
                                                    'aptos'             => $aptos,
                                                    'url'               => $url,
                                                ]);
        

       
    }
    
    public function edificio(){

        // return view('frontend.pages._edificio');
    }
    public function contacto(){
        return view('frontend.contacto', ['mobile' => new Mobile(),]);
    }

    public function formContacto(Request $request){
        
        $data['name']    = $request->input('name');
        $data['email']   = $request->input('email');
        $data['phone']   = $request->input('phone');
        $data['subject'] = $request->input('subject');
        $data['message'] = $request->input('message');


        $contact = MailController::sendContactFormEmail($data);

        if ( $contact ) {
            return view('frontend.responses.success');
        }else{
            return view('frontend.responses.error');

        }
    }


    static function getPriceBook(Request $request){

        
        $date = explode('-', $request->input('date'));
       
        $start = Carbon::createFromFormat('d/m/Y' , trim($date[0]));
        $finish = Carbon::createFromFormat('d/m/Y' , trim($date[1]));
        $countDays = $finish->diffInDays($start);

        $roomAssigned = 111;
        if ($request->input('apto') == '2dorm' && $request->input('luxury') == 'si') {
            
            $rooms = \App\Rooms::where('typeApto', 2)->where('luxury', 1)->orderBy('order', 'ASC')->get();

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
            $rooms = \App\Rooms::where('typeApto', 2)->where('luxury', 0)->orderBy('order', 'ASC')->get();

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
            $rooms = \App\Rooms::where('typeApto', 1)->where('luxury', 1)->orderBy('order', 'ASC')->get();
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
            $rooms = \App\Rooms::where('typeApto', 1)->where('luxury', 0)->orderBy('order', 'ASC')->get();

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
            $priceParking = 15 * $countDays;
            $parking = 1;
        }else{
            $priceParking = 0;
            $parking = 4;
        }

        if ($request->input('luxury') == 'si') {
            $luxury = 50;
        }else{
            $luxury = 0;
        }
        
        $total =  $price + $priceParking + $limp + $luxury;  


        return view('frontend.bookStatus.response', [
                                                        'id_apto' => $roomAssigned,
                                                        'pax'     => $pax,
                                                        'nigths'  => $countDays,
                                                        'apto'    => ($request->input('apto') == '2dorm')?'Apto 2 DORM': 'Estudio',
                                                        'name'    => $request->input('name'),
                                                        'phone'   => $request->input('phone'),
                                                        'email'   => $request->input('email'),
                                                        'start'   => $start,
                                                        'finish'  => $finish,
                                                        'parking' => $parking,
                                                        'priceParking' => $priceParking,
                                                        'luxury'  => $luxury,
                                                        'total'   => $total,
                                                        'comment' => $request->input('comment'),
                                                    ]);

    }


    public function terminos()
    {
        return view('frontend.terminos', [ 'mobile' => new Mobile() ]);
    }
    
    public function politicaPrivacidad(){
        return view('frontend.privacidad', [ 'mobile' => new Mobile() ]);
    }

}



