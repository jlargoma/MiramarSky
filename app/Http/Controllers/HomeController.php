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
use App\Http\Controllers\FortfaitsController;

class HomeController extends AppController
{
	const minDays = 2;

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		return redirect('/admin/reservas');
		/* Detectamos el tipo de dispositivo*/
		/*$mobile = new Mobile();

		if (!$mobile->isMobile())
		{
		   $slides = File::allFiles(public_path() . '/img/miramarski/edificio/');
		} else
		{
		   $slides = File::allFiles(public_path() . '/img/miramarski/edificio/');
		}
		$val = $request->cookie('showPopup');
		if (!empty($val))
		{
		   $cookie = $request->cookie('showPopup');
		} else
		{
		   $cookie = 0;
		}


		return view('frontend.home', [
		   'cookie'         => $cookie,
		   'mobile'         => $mobile,
		   'slidesEdificio' => $slides,
		]);*/
	}


	public function apartamento($apto)
	{
          
          $roomType = \App\RoomsType::where('name',$apto)->first();
          if ($roomType){
            
            $aptoLst = \App\RoomsType::leftJoin('rooms_photos', function ($join) {
                  $join->on('rooms_photos.gallery_key', '=', 'rooms_types.gallery_key')
                       ->where('rooms_photos.main','=',1);
              })->where('rooms_types.name','!=',$apto)->where('rooms_types.status',1)->get();
        
            $slides = \App\RoomsPhotos::where('gallery_key',$roomType->name)->orderBy('position')->get();
            $directory = '/img/miramarski/galerias/';
            return view('frontend.pages.aptos', [
                    'slides'  => $slides,
                    'mobile'  => new Mobile(),
                    'aptoLst' => $aptoLst,
                    'apto'    => $roomType,
                    'aptoTitle' => $roomType->title,
                    'description' => $roomType->description,
                    'url'     => '',
            ]);
          }
          
          
          $room = \App\Rooms::where('nameRoom', $apto)->first();
          if ($room)
          {
            $aptoTitle = ($room->luxury) ? $room->sizeRooms->name . " - LUJO" : $room->sizeRooms->name . " - ESTANDAR";
            
            $aptoLst   = \App\RoomsType::leftJoin('rooms_photos', function ($join) {
              $join->on('rooms_photos.gallery_key', '=', 'rooms_types.gallery_key')
                   ->where('rooms_photos.main','=',1);
              })->where('rooms_types.status',1)->get();
        
            $slides    = \App\RoomsPhotos::where('room_id', $room->id)->orderBy('position')->get();
            $directory = '/img/miramarski/galerias/';
            return view('frontend.pages.aptos', [
                    'slides'  => $slides,
                    'mobile'  => new Mobile(),
                    'aptoLst' => $aptoLst,
                    'aptoTitle' => $aptoTitle,
                    'apto'    => $room,
                    'description' => $room->content_front,
            ]);

          } else
          {
                  return view('errors.notexist-apartmanet');
          }

          

	}

	public function galeriaApartamento($apto)
	{

		$room = \App\Rooms::where('nameRoom', $apto)->first();


		if ($room->sizeApto == 2 && $room->luxury == 1)
		{
			$aptoHeading       = "APARTAMENTOS DOS DORM - DE LUJO ";
			$aptoHeadingMobile = "Apto de lujo 2 DORM";

			$typeApto = 1;
		} elseif ($room->sizeApto == 2 && $room->luxury == 0)
		{
			$aptoHeading       = "APARTAMENTOS DOS DORM - ESTANDAR ";
			$aptoHeadingMobile = "Apto Standard";

			$typeApto = 2;
		} elseif ($room->sizeApto == 1 && $room->luxury == 1)
		{
			$aptoHeading       = "ESTUDIOS – DE LUJO";
			$aptoHeadingMobile = "Estudio de lujo";

			$typeApto = 3;
		} else
		{
			$aptoHeading       = "ESTUDIOS – ESTANDAR";
			$aptoHeadingMobile = "Estudio Standard";

			$typeApto = 4;
		}


		$slides = File::allFiles(public_path() . '/img/miramarski/galerias/' . $apto);
		$aptos  = [
			'apartamento-lujo-sierra-nevada',
			'estudio-lujo-sierra-nevada',
			'apartamento-standard-sierra-nevada',
			'estudio-standard-sierra-nevada'
		];


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

	public function edificio()
	{

		// return view('frontend.pages._edificio');
	}

	public function contacto()
	{
		return view('frontend.contacto', ['mobile' => new Mobile(),]);
	}

	// Correos frontend
	public function formContacto(Request $request)
	{

		$data['name']    = $request->input('name');
		$data['email']   = $request->input('email');
		$data['phone']   = $request->input('phone');
		$data['subject'] = $request->input('subject');
		$data['message'] = $request->input('message');


		$contact = Mail::send(['html' => 'frontend.emails.contact'], ['data' => $data,], function ($message) use ($data) {
			$message->from($data['email'], $data['name']);
			$message->to('reservas@apartamentosierranevada.net');
			$message->subject('Formulario de contacto MiramarSKI');
		});

		if ($contact)
		{
			return view('frontend.contacto', [
				'mobile'    => new Mobile(),
				'contacted' => 1
			]);
		} else
		{
			return view('frontend.contacto', [
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

		$contact = Mail::send(['html' => 'frontend.emails.ayuda'], ['data' => $data,], function ($message) use ($data) {
			$message->from($data['email'], $data['name']);
			$message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */
			// $message->bcc('jlargo@mksport.es');
			// $message->bcc('jlargoma@gmail.com');
			$message->subject('Formulario de Ayudanos a Mejorar MiramarSKI');
		});

		if ($contact)
		{
			return view('frontend.ayudanos-a-mejorar', [
				'mobile'    => new Mobile(),
				'contacted' => 1
			]);
		} else
		{
			return view('frontend.ayudanos-a-mejorar', [
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

		$contact = Mail::send(['html' => 'frontend.emails.propietario'], ['data' => $data,], function ($message) use ($data) {
			$message->from($data['email'], $data['name']);
			$message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */
			// $message->bcc('jlargo@mksport.es');
			// $message->bcc('jlargoma@gmail.com');
			$message->subject('Formulario de Propietario MiramarSKI');
		});

		if ($contact)
		{
			return view('frontend.eres-propietario', [
				'mobile'    => new Mobile(),
				'contacted' => 1
			]);
		} else
		{
			return view('frontend.eres-propietario', [
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

		$contact = Mail::send(['html' => 'frontend.emails.grupos'], ['data' => $data,], function ($message) use ($data) {
			$message->from($data['email'], $data['name']);
			$message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */
			// $message->to('jbaz@daimonconsulting.com'); /* $data['email'] */
			// $message->bcc('jlargo@mksport.es');
			// $message->bcc('jlargoma@gmail.com');
			$message->subject('Formulario de Grupos MiramarSKI');
		});

		if ($contact)
		{
			return view('frontend.grupos', [
				'mobile'    => new Mobile(),
				'contacted' => 1
			]);
		} else
		{
			return view('frontend.grupos', [
				'mobile'    => new Mobile(),
				'contacted' => 0
			]);

		}
	}

	public function form()
	{
		return view('frontend._formBook', ['mobile' => new Mobile()]);
	}

	public function terminos()
	{
		return view('frontend.terminos', ['mobile' => new Mobile()]);
	}

	public function politicaPrivacidad()
	{
		return view('frontend.privacidad', ['mobile' => new Mobile()]);
	}

	public function politicaCookies()
	{
		return view('frontend.cookies', ['mobile' => new Mobile()]);
	}


	public function condicionesGenerales()
	{
		return view('frontend.condiciones-generales', ['mobile' => new Mobile()]);
	}

	public function preguntasFrecuentes()
	{
		return view('frontend.preguntas-frecuentes', ['mobile' => new Mobile()]);
	}

	public function eresPropietario()
	{
		return view('frontend.eres-propietario', ['mobile' => new Mobile()]);
	}

	public function grupos()
	{
		return view('frontend.grupos', ['mobile' => new Mobile()]);
	}

	public function quienesSomos()
	{
		return view('frontend.quienes-somos', ['mobile' => new Mobile()]);
	}

	public function ayudanosAMejorar()
	{
		return view('frontend.ayudanos-a-mejorar', ['mobile' => new Mobile()]);
	}

	public function avisoLegal()
	{
		return view('frontend.aviso-legal', ['mobile' => new Mobile()]);
	}

	public function huesped()
	{
		return view('frontend.huesped', ['mobile' => new Mobile()]);
	}

	public function tiempo()
	{
		$aptos = [
			'apartamento-lujo-sierra-nevada',
			'estudio-lujo-sierra-nevada',
			'apartamento-standard-sierra-nevada',
			'estudio-standard-sierra-nevada'
		];

		return view('frontend.tiempo', [
			'mobile' => new Mobile(),
			'aptos'  => $aptos,
		]);
	}

	public function getCitiesByCountry(Request $request)
	{

		return view('frontend.responses._citiesByCountry', [
			'cities' => \App\Cities::where('code_country', $request->code)->orderBy('city', 'ASC')->get()
		]);

	}

	public function solicitudForfait(Request $request)
	{
		$arrayProducts     = array();
		$commissions       = [];
		$anon_request_text = NULL;

		// die();

		$data = $request->input();

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

		$solicitud->cc_name   = $_POST['cc_name'];
		$solicitud->cc_pan    = $_POST['cc_pan'];
		$solicitud->cc_expiry = $_POST['cc_expiry'];
		$solicitud->cc_cvc    = $_POST['cc_cvc'];

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

			// return view('frontend.emails._responseSolicitudForfait' ,['solicitud' => $solicitud, 'productos' => $arrayProducts,'data' => $data]);
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
					$sended = Mail::send(['html' => 'frontend.emails._responseSolicitudForfait'], [
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

			/*$sended = Mail::send(['html' => 'frontend.emails._responseSolicitudForfait'], [
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
		$date1 = Carbon::createFromFormat('d M, y', trim($request->date1));
		$date2 = Carbon::createFromFormat(' d M, y', trim($request->date2));

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
		return view('frontend.condiciones-contratacion', ['mobile' => new Mobile()]);
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
        return view('frontend.stripe.stripe', ['mobile' => new Mobile()]);
	}
}



