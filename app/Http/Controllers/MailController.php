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
	            $message->from('reservas@apartamentosierranevada.net');
	            $message->to($data->customer->email); /* $data['email'] */
	            $message->subject('Solicitud disponibilidad');
	        });

    	}else{

    		/* Cliente */

			Mail::send(['html' => 'frontend.emails.bookSuccess'],[ 'data' => $data, 'admin' => 0], function ($message) use ($data) {
	            $message->from('reservas@apartamentosierranevada.net');
	            $message->to($data->customer->email); /* $data['email'] */
	            $message->subject('Solicitud disponibilidad');
	        });

    		/* Admin */
    		Mail::send(['html' => 'frontend.emails.bookSuccess'],[ 'data' => $data, 'admin' => 1], function ($message) use ($data) {
	            $message->from($data->customer->email);
	            // $message->to('iankurosaki17@gmail.com'); /* $data['email'] */
	            $message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */
	            $message->subject('Nueva Solicitud: '.$data->customer->name);
	        });


    	}

    }


        

}
