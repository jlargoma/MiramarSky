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
	            $message->from('reservas@apartamentosierranevada.com', 'Miramarski Apartamento de lujo');
	            $message->to($data->customer->email); /* $data['email'] */
	            $message->subject('Info. Reserva aparatamentosierranevada.net');
	        });

    	}else{

    		/* Cliente */
			Mail::send(['html' => 'frontend.emails.bookSuccess'],[ 'data' => $data, 'admin' => 0], function ($message) use ($data) {
	            $message->from('reservas@apartamentosierranevada.com', 'Miramarski Apartamento de lujo');
	            $message->to($data->customer->email); /* $data['email'] */
	            $message->subject('Info. Reserva aparatamentosierranevada.net');
	        });

    		/* Admin */
    		Mail::send(['html' => 'frontend.emails.bookSuccess'],[ 'data' => $data, 'admin' => 1], function ($message) use ($data) {
	            $message->from('reservas@apartamentosierranevada.com', 'Miramarski Apartamento de lujo');
	            $message->to('iavila@daimonconsulting.com'); /* $data['email'] */
	            $message->subject('Nueva Solicitud de Reserva');
	        });


    	}

    }

}
