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
class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        /* Detectamos el tipo de dispositivo*/
        $mobile = new Mobile();

        if (!$mobile->isMobile()){
            $slides = File::allFiles(public_path().'/img/miramarski/edificio/');             
        }else{
            $slides = File::allFiles(public_path().'/img/miramarski/edificio-mobile'); 
        }
        $val = $request->cookie('showPopup');
        if ( !empty($val) ){
            $cookie = $request->cookie('showPopup');
        }else{
            $cookie = 0;
        }

        

        return view('frontend.home', [
                            'cookie'   => $cookie,
                            'mobile'   => $mobile,
                            'slidesEdificio'   => $slides,
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
                $aptoHeading       = "APARTAMENTOS DOS DORM - ESTANDAR ";
                $aptoHeadingMobile = "Apto Standard";

                $typeApto = 2;
                break;
            
            case 'estudio standard':
                $aptoHeading       = "ESTUDIOS – ESTANDAR";
                $aptoHeadingMobile = "Estudio Standard";

                $typeApto = 4;
                break; 
            default:
                $room = \App\Rooms::where('nameRoom',$url)->first();
                if (count($room) > 0) {
                    $aptoHeading       = ($room->luxury) ? $room->sizeRooms->name." - LUJO" : $room->sizeRooms->name." - ESTANDAR";
                    $aptoHeadingMobile = ($room->luxury) ? $room->sizeRooms->name." - lujo" : $room->sizeRooms->name." - estandar";
                    if ($room->sizeApto == 1 && $room->luxury == 0) {
                        $typeApto = 4;
                    }elseif ($room->sizeApto == 1 && $room->luxury == 1) {
                        $typeApto = 3;
                    }elseif ($room->sizeApto == 2 && $room->luxury == 0) {
                        $typeApto = 2;
                    }else{
                        $typeApto = 1;
                    }
                    break;
                }else{
                    return view('errors.notexist-apartmanet');
                }
                        
        }


        if ($url == 'apartamento-standard-sierra-nevada' || $url == 'apartamento-lujo-sierra-nevada'  || $url == 'estudio-lujo-sierra-nevada'  || $url == 'estudio-standard-sierra-nevada' ) {
            $slides = File::allFiles(public_path().'/img/miramarski/galerias/'.$url);
            $directory = '/img/miramarski/galerias/';
        }else{
            
            $slides = File::allFiles(public_path().'/img/miramarski/apartamentos/'.$url); 
            $directory = '/img/miramarski/apartamentos/';            

         
        }
        $aptos  = ['apartamento-lujo-sierra-nevada', 'estudio-lujo-sierra-nevada','apartamento-standard-sierra-nevada','estudio-standard-sierra-nevada'];


        return view('frontend.pages._apartamento2', [ 
                                                    'slides'            => $slides, 
                                                    'mobile'            => new Mobile(),
                                                    'aptoHeading'       => $aptoHeading,
                                                    'aptoHeadingMobile' => $aptoHeadingMobile,
                                                    'typeApto'          => $typeApto,
                                                    'aptos'             => $aptos,
                                                    'url'               => $url,
                                                    'directory'         => $directory,
                                                ]);
        

       
    }
    
    public function galeriaApartamento($apto){

        $room = \App\Rooms::where('nameRoom' , $apto)->first();


        if ($room->sizeApto == 2 && $room->luxury == 1) {
            $aptoHeading       = "APARTAMENTOS DOS DORM - DE LUJO ";
            $aptoHeadingMobile = "Apto de lujo 2 DORM";

            $typeApto = 1;
        }elseif($room->sizeApto == 2 && $room->luxury == 0){
            $aptoHeading       = "APARTAMENTOS DOS DORM - ESTANDAR ";
            $aptoHeadingMobile = "Apto Standard";

            $typeApto = 2;
        }elseif($room->sizeApto == 1 && $room->luxury == 1){
            $aptoHeading       = "ESTUDIOS – DE LUJO";
            $aptoHeadingMobile = "Estudio de lujo";

            $typeApto = 3;
        }else{
            $aptoHeading       = "ESTUDIOS – ESTANDAR";
            $aptoHeadingMobile = "Estudio Standard";

            $typeApto = 4;
        }


        $slides = File::allFiles(public_path().'/img/miramarski/galerias/'.$apto); 
        $aptos  = ['apartamento-lujo-sierra-nevada', 'estudio-lujo-sierra-nevada','apartamento-standard-sierra-nevada','estudio-standard-sierra-nevada'];


        return view('frontend.pages._galeriaApartamentos', [ 
                                                    'galeria'           => 1,
                                                    'slides'            => $slides, 
                                                    'mobile'            => new Mobile(),
                                                    'aptoHeading'       => $aptoHeading,
                                                    'aptoHeadingMobile' => $aptoHeadingMobile,
                                                    'typeApto'          => $typeApto,
                                                    'aptos'             => $aptos,
                                                    'url'               => $apto,
                                                ]);
        

       
    }

    public function edificio(){

        // return view('frontend.pages._edificio');
    }

    public function contacto(){
        return view('frontend.contacto', ['mobile' => new Mobile(),]);
    }

    // Correos frontend
        public function formContacto(Request $request){
            
            $data['name']    = $request->input('name');
            $data['email']   = $request->input('email');
            $data['phone']   = $request->input('phone');
            $data['subject'] = $request->input('subject');
            $data['message'] = $request->input('message');


            $contact = Mail::send(['html' => 'frontend.emails.contact'],[ 'data' => $data,], function ($message) use ($data) {
                $message->from($data['email'], $data['name']);
                $message->to('reservas@apartamentosierranevada.net'); 
                $message->subject('Formulario de contacto MiramarSKI');
            });

            if ( $contact ) {
                return view('frontend.contacto', ['mobile' => new Mobile(),'contacted' => 1]);
            }else{
                return view('frontend.contacto', ['mobile' => new Mobile(),'contacted' => 0]);

            }
        }

        public function formAyuda(Request $request){
            
            $data['name']    = $request->input('name');
            $data['email']   = $request->input('email');
            $data['phone']   = $request->input('phone');
            $data['subject'] = $request->input('subject');
            $data['message'] = $request->input('message');

            $contact = Mail::send(['html' => 'frontend.emails.ayuda'],[ 'data' => $data,], function ($message) use ($data) {
                $message->from($data['email'], $data['name']);
                $message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */
                // $message->bcc('jlargo@mksport.es');
                // $message->bcc('jlargoma@gmail.com');
                $message->subject('Formulario de Ayudanos a Mejorar MiramarSKI');
            });

            if ( $contact ) {
                return view('frontend.ayudanos-a-mejorar', ['mobile' => new Mobile(),'contacted' => 1]);
            }else{
                return view('frontend.ayudanos-a-mejorar', ['mobile' => new Mobile(),'contacted' => 0]);

            }            
        }

        public function formPropietario(Request $request){
            
            $data['name']    = $request->input('name');
            $data['email']   = $request->input('email');
            $data['phone']   = $request->input('phone');
            $data['subject'] = $request->input('subject');
            $data['message'] = $request->input('message');

            $contact = Mail::send(['html' => 'frontend.emails.propietario'],[ 'data' => $data,], function ($message) use ($data) {
                $message->from($data['email'], $data['name']);
                $message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */
                // $message->bcc('jlargo@mksport.es');
                // $message->bcc('jlargoma@gmail.com');
                $message->subject('Formulario de Propietario MiramarSKI');
            });

            if ( $contact ) {
                return view('frontend.eres-propietario', ['mobile' => new Mobile(),'contacted' => 1]);
            }else{
                return view('frontend.eres-propietario', ['mobile' => new Mobile(),'contacted' => 0]);

            }
        }

        public function formGrupos(Request $request){
            
            $data['name']    = $request->input('name');
            $data['email']   = $request->input('email');
            $data['phone']   = $request->input('phone');
            $data['destino'] = $request->input('destino');
            $data['personas'] = $request->input('personas');
            $data['message'] = $request->input('message');

            $contact = Mail::send(['html' => 'frontend.emails.grupos'],[ 'data' => $data,], function ($message) use ($data) {
                $message->from($data['email'], $data['name']);
                $message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */
                // $message->to('jbaz@daimonconsulting.com'); /* $data['email'] */
                // $message->bcc('jlargo@mksport.es');
                // $message->bcc('jlargoma@gmail.com');
                $message->subject('Formulario de Grupos MiramarSKI');
            });

            if ( $contact ) {
                return view('frontend.grupos', ['mobile' => new Mobile(),'contacted' => 1]);
            }else{
                return view('frontend.grupos', ['mobile' => new Mobile(),'contacted' => 0]);

            }
        }
    // Correos frontend


    static function getPriceBook(Request $request){

        $aux = str_replace('Abr', 'Apr', $request->input('fechas'));

        $date = explode('-', $aux);
       
        $start = Carbon::createFromFormat('d M, y' , trim($date[0]));
        $finish = Carbon::createFromFormat('d M, y' , trim($date[1]));
        $countDays = $finish->diffInDays($start);

        
        if ($request->input('apto') == '2dorm' && $request->input('luxury') == 'si') {
            $roomAssigned = 115;
            $typeApto  = "2 DORM Lujo";
            $limp = 50;
        }elseif($request->input('apto') == '2dorm' && $request->input('luxury') == 'no'){
            $roomAssigned = 122;
            $typeApto  = "2 DORM estandar";
            $limp = 50;
        }elseif($request->input('apto') == 'estudio' && $request->input('luxury') == 'si'){
            $roomAssigned = 138;
            $limp = 30;
            $typeApto  = "Estudio Lujo";

        }elseif($request->input('apto') == 'estudio' && $request->input('luxury') == 'no'){
            $roomAssigned = 110;
            $typeApto  = "Estudio estandar";
            $limp = 30;
        }


        $paxPerRoom = \App\Rooms::getPaxRooms($request->input('quantity'), $roomAssigned);

        $pax = $request->input('quantity');

        if ($paxPerRoom > $request->input('quantity')) {
            $pax = $paxPerRoom;
        }

        $price = 0;

        for ($i=1; $i <= $countDays; $i++) { 

            $seasonActive = \App\Seasons::getSeason($start->copy()->format('Y-m-d'));
            if ($seasonActive == null) {
               $seasonActive = 0;
            }
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
            $parking = 2;
        }

        if ($request->input('luxury') == 'si') {
            $luxury = 50;
        }else{
            $luxury = 0;
        }
        
        $total =  $price + $priceParking + $limp + $luxury;  
        $dni = $request->input('dni');
        $address = $request->input('address');

        if ($seasonActive != 0) {
            return view('frontend.bookStatus.response', [
                                                            'id_apto' => $roomAssigned,
                                                            'pax'     => $pax,
                                                            'nigths'  => $countDays,
                                                            'apto'    => $typeApto,
                                                            'name'    => $request->input('name'),
                                                            'phone'   => $request->input('phone'),
                                                            'email'   => $request->input('email'),
                                                            'start'   => $start,
                                                            'finish'  => $finish,
                                                            'parking' => $parking,
                                                            'priceParking' => $priceParking,
                                                            'luxury'  => $luxury,
                                                            'total'   => $total,
                                                            'dni'   => $dni,
                                                            'address'   => $address,
                                                            'comment' => $request->input('comment'),
                                                        ]);

        } else {
            return view('frontend.bookStatus.bookError');
        }
              
    }

    public function form()
    {
        return view('frontend._formBook', [ 'mobile' => new Mobile() ]);
    }

    public function terminos()
    {
        return view('frontend.terminos', [ 'mobile' => new Mobile() ]);
    }
    
    public function politicaPrivacidad(){
        return view('frontend.privacidad', [ 'mobile' => new Mobile() ]);
    }

    public function politicaCookies(){
        return view('frontend.cookies', [ 'mobile' => new Mobile() ]);
    }


    public function condicionesGenerales(){
        return view('frontend.condiciones-generales', [ 'mobile' => new Mobile() ]);
    }

    public function preguntasFrecuentes(){
        return view('frontend.preguntas-frecuentes', [ 'mobile' => new Mobile() ]);
    }

    public function eresPropietario(){
        return view('frontend.eres-propietario', [ 'mobile' => new Mobile() ]);
    }

    public function grupos(){
        return view('frontend.grupos', [ 'mobile' => new Mobile() ]);
    }

    public function quienesSomos(){
        return view('frontend.quienes-somos', [ 'mobile' => new Mobile() ]);
    }

    public function ayudanosAMejorar(){
        return view('frontend.ayudanos-a-mejorar', [ 'mobile' => new Mobile() ]);
    }

    public function avisoLegal(){
        return view('frontend.aviso-legal', [ 'mobile' => new Mobile() ]);
    }

    public function huesped(){
        return view('frontend.huesped', [ 'mobile' => new Mobile() ]);
    }


    public function tiempo()
    {
        $aptos  = ['apartamento-lujo-sierra-nevada', 'estudio-lujo-sierra-nevada','apartamento-standard-sierra-nevada','estudio-standard-sierra-nevada'];

        return view('frontend.tiempo', [ 
                                        'mobile' => new Mobile() ,
                                        'aptos'  => $aptos,
                                    ]);
    }

    public function getCitiesByCountry(Request $request)
    {
        
        return view('frontend.responses._citiesByCountry', [ 'cities'  => \App\Cities::where('code_country', $request->code )->orderBy('city', 'ASC')->get() ]);

    }






    public function solicitudForfait(Request $request)
        {   
            $arrayProducts = array();

            $data = $request->input();

            $solicitud         = new \App\Solicitudes();
            $solicitud->name   = $data['nombre'];
            $solicitud->email  = $data['email'];
            $solicitud->phone  = $data['telefono'];
            $solicitud->start  = Carbon::createFromFormat('d-m-Y', $data['date-entrada'])->format('Y-m-d');
            $solicitud->finish = Carbon::createFromFormat('d-m-Y', $data['date-salida'])->format('Y-m-d');

            if ($solicitud->save()) {
                
                foreach ($data['carrito'] as $carrito) { 
                    $carrito   = ltrim($carrito);
                    $producto               = new \App\SolicitudesProductos();
                    $producto->id_solicitud = $solicitud->id;
                    $producto->name         = $carrito;
                    // $producto->price = $solicitud->id;
                    if ($producto->save()) {
                        $arrayProducts[] = $producto;
                    }

                }

                // return view('frontend.emails._responseSolicitudForfait' ,['solicitud' => $solicitud, 'productos' => $arrayProducts,'data' => $data]);


                $sended = Mail::send(['html' => 'frontend.emails._responseSolicitudForfait'],['solicitud' => $solicitud, 'productos' => $arrayProducts,'data' => $data], function ($message) use ($data) {
                    $message->from('reservas@apartamentosierranevada.net');
                    $message->to('iavila@daimonconsulting.com');
                    $message->replyTo($data['email']);
                    $message->subject('Solicitud de FORFAIT');
                });


                if ($sended) {
                    return redirect()->back();
                }


            }



        }


 
}



