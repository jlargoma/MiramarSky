<?php

namespace App\Http\Controllers;

use App\Classes\Mobile;
use Auth;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Mail;
use Route;
use URL;

class HomeControllerNew extends AppController
{
    const minDays = 2;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      if (env('APP_APPLICATION') == 'riad'){
        return redirect()->route('dashboard.planning');
      }
        /* Detectamos el tipo de dispositivo*/
      
        $mobile = new Mobile();
        $val = $request->cookie('showPopup');
        if (!empty($val))
        {
            $cookie = $request->cookie('showPopup');
        } else
        {
            $cookie = 0;
        }
        return view('new.frontend.home', [
            'cookie'         => $cookie,
            'mobile'         => $mobile,
            'slidesEdificio' => null,
        ]);
    }

    
    private function getRoomData($url,$room) {
      $aptoHeading       = ($room->luxury) ? $room->sizeRooms->name  : $room->sizeRooms->name;
      $aptoHeadingMobile = ($room->luxury) ? $room->sizeRooms->name  : $room->sizeRooms->name;
      $typeApto          = $room->sizeRooms->id;
      $directory = public_path() . "/img/miramarski/apartamentos/" . $room->nameRoom.'/';
      $directoryThumbnail = "/img/miramarski/apartamentos/" . $room->nameRoom . "/thumbnails/";

      $photos = null;
      if ($room) {
        $photos = \App\RoomsPhotos::where('room_id', $room->id)->orderBy('main','DESC')->orderBy('position')->get();
//        dd($photos);
      }
    
       return view('new.frontend.pages.fotos', [
              'photos'            => $photos,
              'mobile'            => new Mobile(),
              'aptoHeading'       => $aptoHeading,
              'aptoHeadingMobile' => $aptoHeadingMobile,
              'typeApto'          => $typeApto,
              'room'              => $room,
              'url'               => $url,
              'directory'         => $directory,
              'directoryThumbnail'=> $directoryThumbnail,
          ]);
       
    }
    
    
    public function apartamento($apto)
    {
        $url  = $apto;
        $apto = str_replace('-', ' ', $apto);
        $apto = str_replace(' sierra nevada', '', $apto);
        $room = \App\Rooms::where('nameRoom', $url)->first();
        if ($room)
        {
          return $this->getRoomData($url,$room);
        } else {
          $room = \App\RoomsType::where('name',$url)->first();
          if ($room){
            
            $aptoHeading = $room->title;
            $photos = \App\RoomsPhotos::where('gallery_key', $room->id)
                    ->orderBy('main','DESC')->orderBy('position')->get();
            
            
            $aptos = array();
            $roomsType = \App\RoomsType::select('name')->where('status',1)->get();
            if ($roomsType){
              foreach ($roomsType as $v){
                $aptos[] = $v->name;
              }
            }
            return view('new.frontend.pages.apartamento', [
              'photos'   => $photos,
              'aptoHeading'   => $aptoHeading,
              'mobile'   => new Mobile(),
              'room'     => $room,
              'aptos'    => $aptos,
              'url'      => $url,
            ]);
            
          }
        }

        return redirect('404');
        
    
    }

    public function galeriaApartamento($apto)
    {
        $room = \App\Rooms::where('nameRoom', $apto)->first();
        if ($room->sizeApto == 2 && $room->luxury == 1)
        {
            $aptoHeading       = "APARTAMENTOS DOS DORM - DE LUJO ";
            $aptoHeadingMobile = "Apto de lujo 2 DORM";
            $typeApto          = 1;
        } elseif ($room->sizeApto == 2 && $room->luxury == 0)
        {
            $aptoHeading       = "APARTAMENTOS DOS DORM - ESTANDAR ";
            $aptoHeadingMobile = "Apto Standard";
            $typeApto          = 2;
        } elseif ($room->sizeApto == 1 && $room->luxury == 1)
        {
            $aptoHeading       = "ESTUDIOS – DE LUJO";
            $aptoHeadingMobile = "Estudio de lujo";
            $typeApto          = 3;
        } else
        {
            $aptoHeading       = "ESTUDIOS – ESTANDAR";
            $aptoHeadingMobile = "Estudio Standard";
            $typeApto          = 4;
        }
        $slides = File::allFiles(public_path() . '/img/miramarski/galerias/' . $apto);
        $aptos  = [
            'apartamento-lujo-sierra-nevada',
            'estudio-lujo-sierra-nevada',
            'apartamento-standard-sierra-nevada',
            'estudio-standard-sierra-nevada'
        ];
        return view('new.frontend.pages._galeriaApartamentos', [
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

    public function edificio()
    {
      $mobile = new Mobile();
      $aptos  = [
            'apartamento-lujo-sierra-nevada',
            'estudio-lujo-sierra-nevada',
            'apartamento-standard-sierra-nevada',
            'estudio-standard-sierra-nevada'
        ];
      $folder = '/img/miramarski/edificio/';
      $slides = File::allFiles(public_path() . $folder);
      if ($slides){
       foreach ($slides as $key => $slide)
        {
            $arraySlides[] = $folder.$slide->getFilename();
        }
        natcasesort($arraySlides);
      }
        
      return view('new.frontend.pages.edificio',[
          'aptoHeading' => 'Edificio Miramar Ski',
          'aptos'       => $aptos,
          'slides'      => $arraySlides,
          'mobile'      => $mobile,
              ]);
    }

    public function contacto()
    {
        return view('new.frontend.contacto', ['mobile' => new Mobile(),]);
    }

    // Correos frontend

    public function formContacto(Request $request)
    {
        $data['name']    = $request->input('name');
        $data['email']   = $request->input('email');
        $data['phone']   = $request->input('phone');
        $data['subject'] = $request->input('subject');
        $data['message'] = $request->input('message');
        $contact         = Mail::send(['html' => 'new.frontend.emails.contact'], ['data' => $data,], function ($message) use ($data) {
            $message->from($data['email'], $data['name']);
            $message->to('reservas@apartamentosierranevada.net');
            $message->subject('Formulario de contacto MiramarSKI');
        });
        if ($contact)
        {
            return view('new.frontend.contacto', [
                'mobile'    => new Mobile(),
                'contacted' => 1
            ]);
        } else
        {
            return view('new.frontend.contacto', [
                'mobile'    => new Mobile(),
                'contacted' => 0
            ]);
        }
    }

    public function formAyuda(Request $request)
    {
        $data['name']    = $request->input('name');
        $data['email']   = $request->input('email');
        $data['phone']   = $request->input('phone');
        $data['subject'] = $request->input('subject');
        $data['message'] = $request->input('message');
        $contact         = Mail::send(['html' => 'new.frontend.emails.ayuda'], ['data' => $data,], function ($message) use ($data) {
            $message->from($data['email'], $data['name']);
            $message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */ // $message->bcc('jlargo@mksport.es');
            // $message->bcc('jlargoma@gmail.com');
            $message->subject('Formulario de Ayudanos a Mejorar MiramarSKI');
        });
        if ($contact)
        {
            return view('new.frontend.ayudanos-a-mejorar', [
                'mobile'    => new Mobile(),
                'contacted' => 1
            ]);
        } else
        {
            return view('new.frontend.ayudanos-a-mejorar', [
                'mobile'    => new Mobile(),
                'contacted' => 0
            ]);
        }
    }

    public function formPropietario(Request $request)
    {
        $data['name']    = $request->input('name');
        $data['email']   = $request->input('email');
        $data['phone']   = $request->input('phone');
        $data['subject'] = $request->input('subject');
        $data['message'] = $request->input('message');
        $contact         = Mail::send(['html' => 'new.frontend.emails.propietario'], ['data' => $data,], function ($message) use ($data) {
            $message->from($data['email'], $data['name']);
            $message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */ // $message->bcc('jlargo@mksport.es');
            // $message->bcc('jlargoma@gmail.com');
            $message->subject('Formulario de Propietario MiramarSKI');
        });
        if ($contact)
        {
            return view('new.frontend.eres-propietario', [
                'mobile'    => new Mobile(),
                'contacted' => 1
            ]);
        } else
        {
            return view('new.frontend.eres-propietario', [
                'mobile'    => new Mobile(),
                'contacted' => 0
            ]);
        }
    }

    public function formGrupos(Request $request)
    {
        $data['name']     = $request->input('name');
        $data['email']    = $request->input('email');
        $data['phone']    = $request->input('phone');
        $data['destino']  = $request->input('destino');
        $data['personas'] = $request->input('personas');
        $data['message']  = $request->input('message');
        $contact          = Mail::send(['html' => 'new.frontend.emails.grupos'], ['data' => $data,], function ($message) use ($data) {
            $message->from($data['email'], $data['name']);
            $message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */ // $message->to('jbaz@daimonconsulting.com'); /* $data['email'] */
            // $message->bcc('jlargo@mksport.es');
            // $message->bcc('jlargoma@gmail.com');
            $message->subject('Formulario de Grupos MiramarSKI');
        });
        if ($contact)
        {
            return view('new.frontend.grupos', [
                'mobile'    => new Mobile(),
                'contacted' => 1
            ]);
        } else
        {
            return view('new.frontend.grupos', [
                'mobile'    => new Mobile(),
                'contacted' => 0
            ]);
        }
    }

    public function form()
    {
        return view('new.frontend._formBook', ['mobile' => new Mobile()]);
    }

    public function terminos()
    {
        return view('new.frontend.terminos', ['mobile' => new Mobile()]);
    }

    public function politicaPrivacidad()
    {
        return view('new.frontend.privacidad', ['mobile' => new Mobile()]);
    }

    public function politicaCookies()
    {
        return view('new.frontend.cookies', ['mobile' => new Mobile()]);
    }

    public function condicionesGenerales()
    {
        return view('new.frontend.condiciones-generales', ['mobile' => new Mobile()]);
    }

    public function preguntasFrecuentes()
    {
        return view('new.frontend.preguntas-frecuentes', ['mobile' => new Mobile()]);
    }

    public function eresPropietario()
    {
        return view('new.frontend.eres-propietario', ['mobile' => new Mobile()]);
    }

    public function grupos()
    {
        return view('new.frontend.grupos', ['mobile' => new Mobile()]);
    }

    public function quienesSomos()
    {
        return view('new.frontend.quienes-somos', ['mobile' => new Mobile()]);
    }

    public function ayudanosAMejorar()
    {
        return view('new.frontend.ayudanos-a-mejorar', ['mobile' => new Mobile()]);
    }

    public function avisoLegal()
    {
        return view('new.frontend.aviso-legal', ['mobile' => new Mobile()]);
    }

    public function huesped()
    {
        return view('new.frontend.huesped', ['mobile' => new Mobile()]);
    }

    public function tiempo()
    {
        $aptos = [
            'apartamento-lujo-sierra-nevada',
            'estudio-lujo-sierra-nevada',
            'apartamento-standard-sierra-nevada',
            'estudio-standard-sierra-nevada'
        ];
        return view('new.frontend.tiempo', [
            'mobile' => new Mobile(),
            'aptos'  => $aptos,
        ]);
    }

    public function getCitiesByCountry(Request $request)
    {
        return view('new.frontend.responses._citiesByCountry', [
            'cities' => \App\Cities::where('code_country', $request->code)->orderBy('city', 'ASC')->get()
        ]);
    }

    public function solicitudForfait(Request $request)
    {
        $arrayProducts     = array();
        $commissions       = [];
        $anon_request_text = NULL;
        // die();
        $data             = $request->input();
        $solicitud        = new \App\Solicitudes();
        $solicitud->name  = $data['nombre'];
        $solicitud->email = $data['email'];
        $solicitud->phone = $data['telefono'];
        if (!empty($data['date-entrada1']))
        {
            $solicitud->start = Carbon::createFromFormat('d-m-Y', $data['date-entrada1'])->format('Y-m-d');
        }
        if (!empty($data['date-salida1']))
        {
            $solicitud->finish = Carbon::createFromFormat('d-m-Y', $data['date-salida1'])->format('Y-m-d');
        }
        if (!isset($data['prices']))
        {
            $data['prices'] = [];
        }
        if (isset($data['forfaits']))
        {
            $solicitud->request_forfaits = serialize($data['forfaits']);
        }
        if (isset($data['material']))
        {
            $solicitud->request_material = serialize($data['material']);
        }
        if (isset($data['classes']))
        {
            $solicitud->request_classes = serialize($data['classes']);
        }
        if (isset($data['prices']))
        {
            $solicitud->request_prices = serialize($data['prices']);
        }
        foreach (FortfaitsController::getCommissions() as $commission)
        {
            $commissions[] = $commission->value;
        }
        $solicitud->commissions = serialize($commissions);
        $solicitud->cc_name     = $_POST['cc_name'];
        $solicitud->cc_pan      = $_POST['cc_pan'];
        $solicitud->cc_expiry   = $_POST['cc_expiry'];
        $solicitud->cc_cvc      = $_POST['cc_cvc'];
        if ($solicitud->save())
        {
            if (HomeController::getLastBookByPhone($solicitud->id, trim($data['telefono'])) === false)
            {
                $anon_request_text = '<span style="color:#red; font-weight:bold;">Solicitud Anónima - Consultar en Pestaña Forfaits</span>';
            }
            //print_r($_POST);
            //print_r($solicitud);
            //print_r($data);
            foreach ($data['carrito'] as $key => $carrito)
            {
                $carrito                = ltrim($carrito);
                $producto               = new \App\SolicitudesProductos();
                $producto->id_solicitud = $solicitud->id;
                $producto->name         = $carrito;
                // $producto->price = $solicitud->id;
                if ($producto->save())
                {
                    $arrayProducts[$key] = $producto;
                }
            }
            // echo "<pre>";
            // print_r($request->input());
            // echo "</pre>";
            // return view('new.frontend.emails._responseSolicitudForfait' ,['solicitud' => $solicitud, 'productos' => $arrayProducts,'data' => $data]);
            // die();
            $emailsTo = [
                'forfait@miramarski.com',
                'escuela@sierranevadaeee.com',
                $data['email']
            ];
            foreach ($emailsTo as $emailTo)
            {
                //         echo $emailTo.'<br/>';
                $arrayProductsCloned = $arrayProducts;
                $data['emailTo']     = $emailTo;
                if ($emailTo == 'escuela@sierranevadaeee.com')
                {
                    $products = [];
                    foreach ($arrayProducts as $key => $arrayProduct)
                    {
                        if (!isset($data['classes'][$key]) && !isset($data['material'][$key]))
                        {
                            unset($arrayProductsCloned[$key]);
                        }
                    }
                }
                //            print_r($arrayProductsCloned);
                if (count($arrayProductsCloned) > 0)
                {
                    $sended = Mail::send(['html' => 'new.frontend.emails._responseSolicitudForfait'], [
                        'solicitud'         => $solicitud,
                        'productos'         => $arrayProductsCloned,
                        'precios'           => $data['prices'],
                        'anon_request_text' => $anon_request_text,
                        'data'              => $data
                    ], function ($message) use ($data) {
                        $message->from('reservas@apartamentosierranevada.net');
                        $message->to($data['emailTo']);
                        $message->replyTo($data['email']);
                        $message->subject('Solicitud de FORFAIT');
                    });
                }
            }
            /*$sended = Mail::send(['html' => 'new.frontend.emails._responseSolicitudForfait'], [
               'solicitud' => $solicitud,
               'productos' => $arrayProducts,
               'precios' => $data['prices'],
               'data'      => $data
            ], function ($message) use ($data) {
               $message->from('reservas@apartamentosierranevada.net');
               // $message->to('iavila@daimonconsulting.com');
               // $message->to('joyragdoll@gmail.com');
               // $message->to('reservas@apartamentosierranevada.net');
               // $message->to('escuela@sierranevadaeee.com');
               // $message->to($data['email']);
               $message->replyTo($data['email']);
               $message->subject('Solicitud de FORFAIT');
            });*/
            if ($sended)
            {
                return redirect('/forfait?sended=true');
                //            return redirect()->back();
            }
        }
    }

    public function getForfaitsRequests()
    {
        $requests = \App\Solicitudes::where("enable", "=", 1)->orderBy('id', 'DESC')->get();
        return view('/backend/forfaits/index')->with('requests', $requests);
    }

    public function getDiffIndays(Request $request)
    {
        $date1   = Carbon::createFromFormat('d M, y', trim($request->date1));
        $date2   = Carbon::createFromFormat(' d M, y', trim($request->date2));
        $minDays = self::minDays;
        /* Check min Days by segment */
        $checkSpecialSegment = SpecialSegmentController::checkDates($date1, $date2);
        if ($checkSpecialSegment)
        {
            $minDays = $checkSpecialSegment->minDays;
        }
        return [
            'minDays'        => $minDays,
            'specialSegment' => $checkSpecialSegment,
            'diff'           => $date1->diffInDays($date2),
            'dates'          => $date1->copy()->format('d M, y') . ' - ' . $date2->copy()->format('d M, y')
        ];
    }

    public function condicionesContratacion(Request $request)
    {
        return view('new.frontend.condiciones-contratacion', ['mobile' => new Mobile()]);
    }

    public static function getLastBookByPhone($ff_request_id, $phone)
    {
        $customers = \App\Customers::where("phone", "=", "$phone")->orderBy('ID', 'DESC')->take(1)->get();
        foreach ($customers as $customer)
        {
            $books = \App\Book::where("customer_id", "=", "$customer->id")->orderBy('ID', 'DESC')->take(1)->get();
            foreach ($books as $book)
            {
                $db = \App\Book::find($book->id);
                if ($db->ff_request_id == NULL)
                {
                    $db->ff_request_id = $ff_request_id;
                    if ($db->save())
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function thanksYou(Request $request)
    {
        return view('new.frontend.stripe.stripe', ['mobile' => new Mobile()]);
    }
    public function paymenyError(Request $request)
    {
        return view('new.frontend.stripe.error', ['mobile' => new Mobile()]);
    }

    public function getPriceBook(Request $request)
    {
        $aux       = str_replace('Abr', 'Apr', $request->input('fechas'));
        $date      = explode('-', $aux);
        $start     = Carbon::createFromFormat('d M, y', trim($date[0]));
        $finish    = Carbon::createFromFormat('d M, y', trim($date[1]));
        $countDays = $finish->diffInDays($start);
        if ($request->input('apto') == '2dorm' && $request->input('luxury') == 'si')
        {
            //$roomAssigned = 115;
            $typeApto = "2 DORM Lujo";
            $sizeRoom = 6;
            $limp     = (int) \App\Extras::find(1)->price;
        } elseif ($request->input('apto') == '2dorm' && $request->input('luxury') == 'no')
        {
            //$roomAssigned = 122;
            $typeApto = "2 DORM estandar";
            $sizeRoom = 2;
            $limp     = (int) \App\Extras::find(1)->price;
        } elseif ($request->input('apto') == 'estudio' && $request->input('luxury') == 'si')
        {
            //$roomAssigned = 138;
            $limp     = (int) \App\Extras::find(2)->price;
            $sizeRoom = 5;
            $typeApto = "Estudio Lujo";
        } elseif ($request->input('apto') == 'estudio' && $request->input('luxury') == 'no')
        {
            //$roomAssigned = 110;
            $typeApto = "Estudio estandar";
            $sizeRoom = 1;
            $limp     = (int) \App\Extras::find(2)->price;
        } elseif ($request->input('apto') == 'chlt' && $request->input('luxury') == 'no')
        {
            //$roomAssigned = 144;
            $typeApto = "CHALET los pinos";
            $sizeRoom = 2;
            $limp     = (int) \App\Extras::find(1)->price;
        } elseif ($request->input('apto') == '3dorm')
        {
            /* Rooms para grandes capacidades */
            if ($request->input('quantity') >= 8 && $request->input('quantity') <= 10)
            {
                //$roomAssigned = 153;
                $sizeRoom = 3;
            } else
            {
                //$roomAssigned = 149;
                $sizeRoom = 4;
            }
            $typeApto = "3 DORM";
            $limp     = (int) \App\Extras::find(3)->price;
        }
        $size         = \App\SizeRooms::find($sizeRoom);
        $getRoomToBook = $this->calculateRoomToFastPayment($size, $start, $finish, $request->input('luxury'));
        $roomAssigned = $getRoomToBook['id'];
        $paxPerRoom = \App\Rooms::getPaxRooms($request->input('quantity'), $roomAssigned);
        $pax        = $request->input('quantity');
        if ($paxPerRoom > $pax)
        {
            $pax = $paxPerRoom;
        }
        $price   = 0;
        $counter = $start->copy();
        for ($i = 1; $i <= $countDays; $i++)
        {
            $seasonActive = \App\Seasons::getSeasonType($counter->copy()->format('Y-m-d'));
            if ($seasonActive == null)
            {
                $seasonActive = 0;
            }
            $prices = \App\Prices::where('season', $seasonActive)->where('occupation', $pax)->get();
            foreach ($prices as $precio)
            {
                $price = $price + $precio->price;
            }
            $counter->addDay();
        }
        $room = \App\Rooms::find($roomAssigned);
        if (!$room){
          return view('new.frontend.bookStatus.bookError');
        }
        if ($request->input('parking') == 'si')
        {
            $priceParking = BookController::getPricePark(1, $countDays) * $room->num_garage;
            $parking      = 1;
        } else
        {
            $priceParking = 0;
            $parking      = 2;
        }
        if ($request->input('luxury') == 'si')
        {
            $luxury = BookController::getPriceLujo(1);
        } else
        {
            $luxury = BookController::getPriceLujo(2);
        }
        $total   = $price + $priceParking + $limp + $luxury;
        $dni     = $request->input('dni');
        $address = $request->input('address');
        $setting = \App\Settings::where('key', 'discount_books')->first();
        if ($seasonActive != 0)
        {
            return view('new.frontend.bookStatus.response', [
                'id_apto'      => $roomAssigned,
                'pax'          => $pax,
                'nigths'       => $countDays,
                'apto'         => $typeApto,
                'name'         => $request->input('name'),
                'phone'        => $request->input('phone'),
                'email'        => $request->input('email'),
                'start'        => $start,
                'finish'       => $finish,
                'parking'      => $parking,
                'priceParking' => $priceParking,
                'luxury'       => $luxury,
                'total'        => $total,
                'dni'          => $dni,
                'address'      => $address,
                'room'         => $room,
                'isFastPayment'         => $getRoomToBook['isFastPayment'],
                'setting'      => ($setting) ? $setting : 0,
                'comment'      => $request->input('comment'),
            ]);
        } else
        {
          return view('new.frontend.bookStatus.bookError');
        }
    }
}
