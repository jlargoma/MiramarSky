<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use Mail;

class MailController extends Controller
{
    public static function sendEmailBookSuccess( $data ,$admin = 0)
    {


    	if ($admin == 0) {

    		/* Cliente */
			Mail::send(['html' => 'frontend.emails.bookSuccess'],[ 'data' => $data, 'admin' => 0], function ($message) use ($data) {
	            $message->from('reservas@apartamentosierranevada.net', 'Miramarski Apartamento de lujo');
	            $message->to($data->customer->email); /* $data['email'] */
	            $message->subject('Info. Reserva aparatamentosierranevada.net');
	        });

    	}else{

    		/* Cliente */

			Mail::send(['html' => 'frontend.emails.bookSuccess'],[ 'data' => $data, 'admin' => 0], function ($message) use ($data) {
	            $message->from('reservas@apartamentosierranevada.net', 'Miramarski Apartamento de lujo');
	            $message->to($data->customer->email); /* $data['email'] */
	            $message->subject('Info. Reserva aparatamentosierranevada.net');
	        });

    		/* Admin */
    		Mail::send(['html' => 'frontend.emails.bookSuccess'],[ 'data' => $data, 'admin' => 1], function ($message) use ($data) {
	            $message->from('reservas@apartamentosierranevada.net', 'Miramarski Apartamento de lujo');
	            $message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */
	            $message->bcc('jlargo@mksport.es');
	            $message->bcc('jlargoma@gmail.com');
	            $message->subject('Nueva Solicitud de Reserva');
	        });


    	}

    }


        public static function sendContactFormEmail($data)
        {
        	
    		$contact = Mail::send(['html' => 'frontend.emails.contact'],[ 'data' => $data,], function ($message) use ($data) {
	            $message->from($data['email'], $data['name']);
	            $message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */
	            $message->bcc('jlargo@mksport.es');
	            $message->bcc('jlargoma@gmail.com');
	            $message->subject('Formulario de contacto MiramarSKI');
	        });


    		return $contact;

        }

}
