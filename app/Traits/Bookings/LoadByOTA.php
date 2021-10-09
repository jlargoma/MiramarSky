<?php

namespace App\Traits\Bookings;

use App\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Book;
use App\Services\OtaGateway\OtaGateway;
use App\Services\OtaGateway\Config as oConfig;

trait LoadByOTA {

    
  private function loadBooking($oBookings) {
    $oConfig = new oConfig();
   
    foreach ($oBookings as $oBooking){
//      dd($oBooking);
      $channel_group = $oConfig->getChannelByRoom($oBooking->roomtype_id);
      $extra_array = is_string($oBooking->extra_array) ? json_decode($oBooking->extra_array) : $oBooking->extra_array;
      if (isset($extra_array->from_google) && $extra_array->from_google){
          $oBooking->ota_id = 'google-hotel';
      }
      
      //BEGIN: si es una cancelación por modificación -> la salto
      if ($oBooking->modified_to) {
        if ($this->otaModified($oBooking,$channel_group))
          continue; //sólo si se actualizó
      }
      //END: si es una cancelación por modificación -> la salto
               
      $reserv = [
                'channel' => $oBooking->ota_id,
                'bkg_number' => $oBooking->number,
                'rate_id' => $oBooking->plan_id,
                'external_roomId' => $oBooking->roomtype_id,
                'reser_id' => $oBooking->ota_booking_id,
                'comision'=>0,
                'channel_group' => $channel_group,
                'status' => $oBooking->status_id,
                'agency' => $oConfig->getAgency($oBooking->ota_id),
                'customer_name' => $oBooking->name.' '.$oBooking->surname,
                'customer_email' => $oBooking->email,
                'customer_phone' => $oBooking->phone,
                'customer_comment' => $oBooking->comment,
                'totalPrice' => $oBooking->amount,
                'adults' => $oBooking->adults,
                'children' => $oBooking->children,
                'extra_array' => $extra_array,
                'start' => $oBooking->arrival,
                'end' => $oBooking->departure,
                'modified_from' => $oBooking->modified_from,
                'modified_to' => $oBooking->modified_to,
              ];
      
      $bookID = $this->sOta->addBook($channel_group,$reserv);
//      echo $bookID.'<br/>';
//      dd($bookID);
//      var_dump($reserv,$oBooking); die;
      
        if ($bookID && $oBooking->ota_id == 'google-hotel'){

          $book = \App\Book::find($bookID);
          $body = 'Hola, ha entrado una nueva reserva desde Google Hotel:<br/><br/>';

          $customer = $book->customer;
          $subject = 'RESERVA GOOGLEHOTELS : '.$customer->name;
          $body .= '<b>Nombre:</b>: '.$customer->name.'<br/><br/>';
          $body .= '<b>e-mail:</b>: '.$customer->email.'<br/><br/>';
          $body .= '<b>Teléfono:</b>: '.$customer->phone.'<br/><br/>';
          $body .= '<b>Habitación:</b> '.$book->room->name.'<br/><br/>';
          $body .= '<b>PVP:</b>: '.number_format($book->total_price, 0, '', '.').'<br/><br/>';
          $body .= '<b>Fechas:</b> '.convertDateToShow_text($book->start).' - '. convertDateToShow_text($book->finish).'<br/><br/>';
          $body .= '<b>Noches:</b> '.$book->nigths.'<br/><br/>';
          $body .= '<b>Paxs:</b> '.$book->pax.'<br/><br/>';
          $body .= '<b>Comtentarios:</b> '.$book->book_comments.'<br/><br/>';

           $sended = \Illuminate\Support\Facades\Mail::send('backend.emails.base', [
                'mailContent' => $body,
                'title'       => $subject
            ], function ($message) use ($book, $subject) {
                $message->from(env('MAIL_FROM'));
                $message->to(env('MAIL_FROM'));
                $message->subject($subject);
            });
        
        }
    }
    
  }
  
   function otaModified($oBooking,$channel_group) {
    $site = '';
    $oBook = \App\Book::where('bkg_number', $oBooking->number)->first();
    if ($oBook) {
      $bkgNumbers = explode(',',$oBooking->modified_to);
      $updated = false;
      $newNumber = '';
      foreach ($bkgNumbers as $number){
        if ($updated) continue; //ya encontró uno
        $oBook2 = \App\Book::where('bkg_number', $number)->first();
        if (!$oBook2){ // aún no se ha cargado
          $updated = true;
          $newNumber = $number;
          $oBook->bkg_number = $number;
          $oBook->save();
        }
      }
      
      if (!$updated) return false; //no encontró lugar
      
      $bData = \App\BookData::findOrCreate('modified_to', $oBook->id);
      $bData->content .= $oBooking->number.',';
      $bData->save();

      \Illuminate\Support\Facades\Mail::send('backend.emails.base-admin', [
        'content' => 'La reserva ' . $oBooking->number . ' / ' . $channel_group .
        ' fue modificada por bkg_number ' . $newNumber,
            ], function ($message) {
              $message->from(env('MAIL_FROM'));
              $message->to('pingodevweb@gmail.com');
              $message->subject('Actualización de reservas');
            });
    }

    return true; //si no existe, la tomo como modificada
  }
}