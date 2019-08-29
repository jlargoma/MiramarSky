<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use Mail;
use App\Settings;

class MailController extends AppController
{
    public static function sendEmailBookSuccess( $data ,$admin = 0)
    {


      $mailClientContent = Settings::getContent('new_request_rva');
      $dataContent = array(
            'customer_name' =>  $data->customer->name,
            'customer_email' =>  $data->customer->email,
            'customer_phone' =>  $data->customer->phone,
            'room' =>  ($data->type_luxury == 1)? "Lujo" : "Estandar",
            'room_type' =>  ($data->type_luxury == 1)? "Lujo" : "Estandar",
            'date_start' =>  date('d-m-Y', strtotime($data->start)),
            'date_end' =>  date('d-m-Y', strtotime($data->finish)),
            'nigths' =>  $data->nigths,
            'pax' =>  $data->pax,
            'sup_lujo' =>  number_format($data->sup_lujo,0,'','.'),
            'comment' =>  $data->comment,
            'total_price' =>  number_format($data->total_price,0,'','.')
          );
      /** process the mail content */
      foreach ($dataContent as $k=>$v){
        $mailClientContent = str_replace('{'.$k.'}', $v, $mailClientContent);
      }
          
    	if ($admin == 0) {
          /* xCliente */

          
          
          Mail::send(['html' => 'frontend.emails.bookSuccess'],[ 'data' => $data, 'admin' => 0,'mailContent'=>$mailClientContent], function ($message) use ($data) {
              $message->from('reservas@apartamentosierranevada.net');
              $message->to($data->customer->email); /* $data['email'] */
              $message->subject('Solicitud disponibilidad');
          });

        }else{

    		/* Cliente */

		Mail::send(['html' => 'frontend.emails.bookSuccess'],[ 'data' => $data, 'admin' => 0,'mailContent'=>$mailClientContent], function ($message) use ($data) {
	            $message->from('reservas@apartamentosierranevada.net');
	            $message->to($data->customer->email); /* $data['email'] */
	            $message->subject('Solicitud disponibilidad');
	        });

    		/* Admin */
    		Mail::send(['html' => 'frontend.emails.bookSuccess'],[ 'data' => $data, 'admin' => 1,'mailContent'=>$mailClientContent], function ($message) use ($data) {
	            $message->from($data->customer->email);
	            // $message->to('iankurosaki17@gmail.com'); /* $data['email'] */
	            $message->to('reservas@apartamentosierranevada.net'); /* $data['email'] */
	            $message->subject('Nueva Solicitud: '.$data->customer->name);
	        });


    	}

        \App\BookLogs::saveLog($data->id,$data->room_id,$data->customer->email,'new_request_rva','Solicitud disponibilidad',$mailClientContent);
    }


        

}
