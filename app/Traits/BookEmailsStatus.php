<?php

namespace App\Traits;

use App\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\BookPartee;
use App\Repositories\CachedRepository;

trait BookEmailsStatus
{

    /**
     *
     * @param type $book
     * @param type $subject
     * @param type $status
     */
    public function sendEmailChangeStatus($book, $subject, $status)
    {
      if ($book->customer->send_mails == false || !$book->customer->email || trim($book->customer->email) == '') return;
        $cachedRepository  = new CachedRepository();
        $otaAgencies = [1,4];
        if ($status == 1){
          if (in_array($book->agency,$otaAgencies)){ 
            $keyMail = $this->getKeyTemplate('1.1');
            $subject = "Confirmación de reserva";
          } else {
            $keyMail = $this->getKeyTemplate($status);
          }
        } else {
          //don't send with OTAs
          if ($status == 2 && in_array($book->agency,$otaAgencies)) return;
          $keyMail = $this->getKeyTemplate($status);
        }
        if (!$keyMail){
          return;
        }
        $mailClientContent = $this->getMailData($book, $keyMail);
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");

        $subject = translateSubject($subject,$book->customer->country);
        switch ($status)
        {
            case "1":
                $percent            = $this->getPercent($book);
                $mount_percent      = number_format(($book->total_price * $percent), 2, ',', '.');
                $PaylandsController = new \App\Http\Controllers\PaylandsController($cachedRepository);
                
                $amount             = (round($book->total_price * $percent));
                $urlToPayment       = $PaylandsController->generateOrder($amount,'',$book->id);
                $mailClientContent = str_replace('{urlToPayment}', $urlToPayment, $mailClientContent);
                $mailClientContent = str_replace('{mount_percent}', $mount_percent, $mailClientContent);
                if ($percent<1){
                  $percent = $percent*100;
                }
                $mailClientContent = str_replace('{percent}', $percent, $mailClientContent);
                break;

            case "2":

                $linkPartee = null;
                $BookPartee = BookPartee::where('book_id', $book->id)->first();

                if ($BookPartee && $BookPartee->partee_id > 0)
                {
                    $linkPartee = $BookPartee->link;
                }
                $mailClientContent = str_replace('{partee}', $linkPartee, $mailClientContent);
                $mailClientContent = str_replace('{LastPayment}', number_format($book->getLastPayment(), 2, ',', '.'), $mailClientContent);
                break;
        }

        $mailClientContent = $this->clearVars($mailClientContent);

        Mail::send('backend.emails.base', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($book, $subject) {
            $message->from(env('MAIL_FROM'));
            $message->to($book->customer->email);
            $message->subject($subject);
        });

        \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,$keyMail,$subject,$mailClientContent);
    }

    /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_secondPayBook($book, $subject)
    {
      if (!$book->customer->email || trim($book->customer->email) == '') return;
      
        $mailClientContent = $this->getMailData($book, 'second_payment_reminder');
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");

        $totalPayment = 0;
        $payments     = \App\Payments::where('book_id', $book->id)->get();
        if (count($payments) > 0)
        {
            foreach ($payments as $key => $pay)
            {
                $totalPayment += $pay->import;
            }
        }
        $percent           = 100 - (round(($totalPayment / $book->total_price) * 100));
        $pendiente         = ($book->total_price - $totalPayment);
        $cachedRepository  = new CachedRepository();
        $PaylandsController= new \App\Http\Controllers\PaylandsController($cachedRepository);
        $urlPayment        = $PaylandsController->generateOrder($pendiente,'',$book->id);
//        $urlPayment        = 'https://miramarski.com/reservas/stripe/pagos/' . base64_encode($book->id) . '/' . base64_encode(round($pendiente));
        $mailClientContent = str_replace('{pend_percent}', $percent, $mailClientContent);
        $mailClientContent = str_replace('{total_payment}', number_format($totalPayment, 2, ',', '.'), $mailClientContent);
        $mailClientContent = str_replace('{pend_payment}', number_format($pendiente, 2, ',', '.'), $mailClientContent);
        $mailClientContent = str_replace('{urlPayment_rest}', $urlPayment, $mailClientContent);

        $mailClientContent = $this->clearVars($mailClientContent);

        $sended = Mail::send('backend.emails.base', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($book, $subject) {
            $message->from(env('MAIL_FROM'));
            $message->to($book->customer->email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM'));
        });
        
        \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'second_payment_reminder',$subject,$mailClientContent);

        return $sended;
    }

    /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_confirmSecondPayBook($book, $subject,$totalPayment)
    {
      if (!$book->customer->email || trim($book->customer->email) == '') return;
        $mailClientContent = $this->getMailData($book, 'second_payment_confirm');
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");

        $totalPayment = 0;
        $payments     = \App\Payments::where('book_id', $book->id)->get();
        if (count($payments) > 0)
        {
            foreach ($payments as $key => $pay)
            {
                $totalPayment += $pay->import;
            }
        }
        $pendiente         = ($book->total_price - $totalPayment);
        if ($pendiente>0) return; //only if the Booking is totally payment
        
        $mailClientContent = str_replace('{total_payment}', number_format($totalPayment, 2, ',', '.'), $mailClientContent);
        $mailClientContent = $this->clearVars($mailClientContent);

        $sended = Mail::send('backend.emails.base', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($book, $subject) {
            $message->from(env('MAIL_FROM'));
            $message->to($book->customer->email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM'));
        });
        
        \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'second_payment_reminder',$subject,$mailClientContent);

        return $sended;
    }


    private function getKeyTemplate($status)
    {
      $key = null;
        switch ($status)
        {
            case 1:
            case "1":
                $key = 'reservation_state_changed_reserv';
                break;
            case 1.1:
            case "1.1":
                $key = 'reservation_state_changed_reserv_ota';
                break;
            case 2:
            case "2":
                $key = 'reservation_state_changed_confirm';
                break;
            case 6:
            case "6":
                $key = 'reservation_state_changed_cancel';
                break;
            case 7:
            case "7":
                $key = 'reserva-propietario';
                break;
        }
        return $key;
    }

    /**
     *
     * @param Booking $data
     * @param String  $key
     * @return String HTML
     */
    public function getMailData($data, $keyTemp)
    {
      
        $mailClientContent = Settings::getContent($keyTemp,$data->customer->country);

        $dataContent = array(
            'customer_name'             => $data->customer->name,
            'customer_email'            => $data->customer->email,
            'customer_phone'            => $data->customer->phone,
            'room'                      => $data->room->sizeRooms->name,
            'room_type'                 => ($data->type_luxury == 1) ? "Lujo" : "Estandar",
            'room_name'                 => $data->room->name,
            'date_start'                => date('d-m-Y', strtotime($data->start)),
            'date_end'                  => date('d-m-Y', strtotime($data->finish)),
            'nigths'                    => $data->nigths,
            'pax'                       => $data->pax,
            'sup_lujo'                  => number_format($data->sup_lujo, 0, '', '.'),
            'comment'                   => $data->comment,
            'book_comments'             => $data->book_comments,
            'total_price'               => number_format($data->total_price, 0, '', '.'),
            'url-condiciones-generales' => url('/condiciones-generales'),
            'link_forfait'               => '',
        );
        
        if (env('APP_APPLICATION') == 'riad'){
          $dataContent['room'] = $data->room->nameRoom;
        }
        
        
        $orderFF = \App\Models\Forfaits\Forfaits::getByBook($data->id);
        if ($orderFF){
          $dataContent['link_forfait'] = env('FF_PAGE').encriptID($orderFF->id).'-'. getKeyControl($orderFF->id);
        } else {
          $dataContent['link_forfait'] = env('FF_PAGE');
        }
        /** process the mail content */
        foreach ($dataContent as $k => $v)
        {
            $mailClientContent = str_replace('{' . $k . '}', $v, $mailClientContent);
        }
        return $mailClientContent;

    }

    public function getPercent($book)
    {
        $percent = 0.5;
        $date    = Carbon::createFromFormat('Y-m-d', $book->start);
        $now     = Carbon::now();
        $rule    = \App\RulesStripe::find(1);
        $diff    = $now->diffInDays($date);

        if ($diff <= $rule->numDays)
        {
            $percent = ($rule->percent / 100);
        } else
        {
            $percent = 0.50;
        }
        return $percent;
    }

    /**
     * Clear all not loaded vars
     * @param type $text
     * @return type
     */
    public function clearVars($text)
    {

        return preg_replace('/\{(\w+)\}/i', '', $text);

    }

    
    /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_confirmForfaitPayment($cli_email,$cli_name,$subject, $orderText,$totalPayment,$book)
    {
      if (trim($cli_email) == '') return;
        $mailClientContent = $mailClientContent = Settings::getContent('Forfait_email_confirmation_payment');
        $mailClientContent = str_replace('{customer_name}', $cli_name, $mailClientContent);
        $mailClientContent = str_replace('{total_payment}', $totalPayment, $mailClientContent);
        $mailClientContent = str_replace('{forfait_order}', $orderText, $mailClientContent);
        $mailClientContent = $this->clearVars($mailClientContent);
        $sended = Mail::send('backend.emails.forfait', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($cli_email, $subject) {
            $message->from(env('MAIL_FROM_FORFAITS'));
            $message->to($cli_email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM_FORFAITS'));
        });
        if ($book){
          \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'second_payment_reminder',$subject,$mailClientContent);
        }

        return $sended;
    }
    
    /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_linkForfaitPayment($cli_email,$cli_name,$subject,$orderText,$link,$book)
    {
      if (trim($cli_email) == '') return;
        $mailClientContent = $mailClientContent = Settings::getContent('Forfait_email_payment_request');
        $mailClientContent = str_replace('{customer_name}', $cli_name, $mailClientContent);
        $mailClientContent = str_replace('{link_forfait}', $link, $mailClientContent);
        $mailClientContent = str_replace('{forfait_order}', $orderText, $mailClientContent);
        $mailClientContent = $this->clearVars($mailClientContent);
        
        $sended = Mail::send('backend.emails.forfait', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($cli_email, $subject) {
            $message->from(env('MAIL_FROM_FORFAITS'));
            $message->to($cli_email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM_FORFAITS'));
        });
        
        if ($book){
          \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'send_payment_Forfait',$subject,$mailClientContent);
        }

        return $sended;
    }
    
     /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_CancelForfaitItem($orderText,$link)
    {
        $subject = 'Forfait Cancelado';
        
        $mailClientContent = '<strong>Se ha cancelado un Forfait ya pagado en ForfaitExpress</strong><br/><br/>';
        $mailClientContent .= $orderText.'<br/><br/>';
        $mailClientContent .= "Url de la Orden: <a href='$link' title='Ver Orden'>$link</a>";
        $mailClientContent .= '<p><strong>Compruebe que el forfait fue cancelado correctamente en <a href="forfaitexpress.com">www.forfaitexpress.com</a></strong></p>';
        $sended = Mail::send('backend.emails.forfait', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($subject) {
            $message->from(env('MAIL_FROM_FORFAITS'));
            $message->to(env('MAIL_FROM_FORFAITS'));
            $message->subject($subject);
        });
        
        return $sended;
    }
    
     /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_RemindForfaitPayment($book, $orderID,$link)
    {
      if (!$book->customer->email || trim($book->customer->email) == '') return;
        $mailClientContent = $this->getMailData($book, 'Forfait_email_payment_request');
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");

        $subject = translateSubject('Recordatorio de pago de Forfait',$book->customer->country);
        
        
        $cachedRepository       = new CachedRepository();
        $ForfaitsItemController = new \App\Http\Controllers\ForfaitsItemController($cachedRepository);
        
        $orderText = $ForfaitsItemController->renderOrder($orderID);
        
        $mailClientContent = str_replace('{forfait_order}', $orderText, $mailClientContent);
        $mailClientContent = str_replace('{link_forfait}', $link, $mailClientContent);
        $mailClientContent = $this->clearVars($mailClientContent);
        $sended = Mail::send('backend.emails.forfait', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($book, $subject) {
            $message->from(env('MAIL_FROM_FORFAITS'));
            $message->to($book->customer->email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM_FORFAITS'));
        });
        
        \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'send_forfait_payment_reminder',$subject,$mailClientContent);

        return $sended;
    }
    
     /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_FianzaPayment($book,$total_price, $link)
    {
      if (!$book->customer->email || trim($book->customer->email) == '') return;
        $mailClientContent = $this->getMailData($book, 'fianza_request_deferred');
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");

        $subject = translateSubject('Generar Fianza',$book->customer->country);
        
        $mailClientContent = str_replace('{payment_amount}', $total_price, $mailClientContent);
        $mailClientContent = str_replace('{urlPayment}', $link, $mailClientContent);
        $mailClientContent = $this->clearVars($mailClientContent);
        $sended = Mail::send('backend.emails.base', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($book, $subject) {
            $message->from(env('MAIL_FROM'));
            $message->to($book->customer->email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM'));
        });
        
        \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'fianza_request_deferred',$subject,$mailClientContent);

        return $sended;
    }
    
        /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_confirmDeferrend($book, $subject,$totalPayment)
    {
      if (!$book->customer->email || trim($book->customer->email) == '') return;
        $mailClientContent = $this->getMailData($book, 'fianza_confirm_deferred');
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");
        
        $mailClientContent = str_replace('{payment_amount}', number_format($totalPayment, 2, ',', '.'), $mailClientContent);
        $mailClientContent = $this->clearVars($mailClientContent);

        $sended = Mail::send('backend.emails.base', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($book, $subject) {
            $message->from(env('MAIL_FROM'));
            $message->to($book->customer->email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM'));
        });
        
        \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'fianza_confirm_deferred',$subject,$mailClientContent);

        return $sended;
    }
    
     /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_ForfaitClasses($orderText,$email,$subject)
    {
       
        
        $mailClientContent = 'Hola, confírmanos disponibilidad para las clases de este cliente:<br/><br/>';
        $mailClientContent .= $orderText.'<br/><br/>';
        $sended = Mail::send('backend.emails.forfait', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($subject,$email) {
            $message->from(env('MAIL_FROM_FORFAITS'));
            $message->to($email);
            $message->subject($subject);
        });
        
        return $sended;
    }
     /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_ForfaitNewOrder($order,$link)
    {
       
      $subject = 'Nueva solicitud pública de Forfaits';
      
      $mailClientContent = 'Hola, un nuevo usuario ha solicitado Forfaits desde la parte pública:<br/><br/>';
       
      $clientMail = $order->email;
      $mailClientContent .= '<b>Nombre:</b>:'.$order->name.'<br/><br/>';
      $mailClientContent .= '<b>e-mail:</b>:'.$clientMail.'<br/><br/>';
      $mailClientContent .= '<b>Teléfono:</b>:'.$order->phone.'<br/><br/>';
      $mailClientContent .= '<b>Petición:</b>:'.$order->more_info.'<br/><br/>';

      $mailClientContent .= '<br/><br/>Puedes acceder a la vista pública de la orden a travéz del enlace'
              . '<br/><a href="'.$link.'" title="Ir al Forfaits">'.$link.'</a><br/><br/>';
        $sended = Mail::send('backend.emails.forfait', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($subject,$clientMail) {
            $message->from($clientMail);
            $message->to(env('MAIL_FROM_FORFAITS'));
            $message->subject($subject);
        });
        
        return $sended;
    }
    
     /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_confirmCobros($book, $subject,$lastPayment,$email)
    {
      if (!$email || trim($email) == '') return;
        $mailClientContent = $this->getMailData($book, 'payment_receipt');
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");
        
        
        $totalPayment = 0;
        $payments     = \App\Payments::where('book_id', $book->id)->get();
        $cobros_list  = '';
        if ($payments){
          $cobros_list = '<table style="width: 100%; text-align: left;">
                          <tr><th width="30%">Fecha</th><th width="30%">Importe</th><th width="30%">Método</th></tr>
                          <tr><td colspan="3">&nbsp;</td></tr>';

          foreach ($payments as $key => $pay){
            $cobros_list.= '<tr>
                            <th>'. convertDateToShow_text($pay->datePayment,true).'</th>
                            <th>'.round($pay->import,2).' €</th>
                            <th>'.$book->getTypeCobro($pay->type).'</th>
                          </tr>';
            $totalPayment += $pay->import;
           
          }
          
          $cobros_list .= '</table>';
        }
        $pendiente  = ($book->total_price - $totalPayment);
       
        
        $status = 'Su reserva se encuentra al corriente de pago';
        if ($pendiente>0){
          $status = 'Su reserva tiene pendiente de abonar '.round($pendiente,2).' €';
        }
        
        $linkPartee = null;
        $BookPartee = BookPartee::where('book_id', $book->id)->first();
        if ($BookPartee && $BookPartee->partee_id > 0){
            $linkPartee = $BookPartee->link;
        }
        $mailClientContent = str_replace('{partee}', $linkPartee, $mailClientContent);
                
        if ($book->priceOTA<1) $book->priceOTA = $book->total_price;
                
        
        $mailClientContent = str_replace('{total_payment}', number_format($totalPayment, 2, ',', '.'), $mailClientContent);
        $mailClientContent = str_replace('{LastPayment}', number_format($lastPayment, 2, ',', '.'), $mailClientContent);
        $mailClientContent = str_replace('{priceOTA}', number_format($book->priceOTA, 2, ',', '.'), $mailClientContent);
        $mailClientContent = str_replace('{cobros_estado}', $status, $mailClientContent);
        $mailClientContent = str_replace('{cobros_list}', $cobros_list, $mailClientContent);
        $mailClientContent = $this->clearVars($mailClientContent);
        
        $sended = Mail::send('backend.emails.base', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($email, $subject) {
            $message->from(env('MAIL_FROM'));
            $message->to($email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM'));
        });
        
        \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'second_payment_reminder',$subject,$mailClientContent);

        return $sended;
    }
    
    
     
    /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_Encuesta($book, $subject)
    {
      if (!$book->customer->email || trim($book->customer->email) == '') return false;
        $mailClientContent = $this->getMailData($book, 'send_encuesta');
        
        $subject = $this->getMailData($book, 'send_encuesta_subject');
        
        $linkG = 'https://www.google.com/search?sxsrf=ALeKk01I274_tkiAimCZMu39266psYDjpg%3A1585320803146&ei=YxN-XoG_CJ2l5OUPuLmp4AE&hotel_occupancy=&q=Apartamentos+en+Sierra+Nevada+-+Zona+Baja+Con+Piscina.&oq=Apartamentos+en+Sierra+Nevada+-+Zona+Baja+Con+Piscina.&gs_lcp=CgZwc3ktYWIQAzIGCAAQFhAeOgQIIxAnUIleWImCAWDxhwFoAHAAeACAAXmIAbEEkgEDMS40mAEAoAEBoAECqgEHZ3dzLXdpeg&sclient=psy-ab&ved=0ahUKEwjB_M2a9LroAhWdErkGHbhcChwQ4dUDCAs&uact=5';
        $link = '<a href="'.$linkG.'" title="Cargar opinión"><img src="'.url('/img/g_store.jpg').'" width="80px" height="80px"></a>';
        $email = $book->customer->email;
        
                
        $mailClientContent = str_replace('{url_encuesta}', 'https://www.apartamentosierranevada.net/encuesta-satisfaccion/' . base64_encode($book->id), $mailClientContent);
        $mailClientContent = str_replace('{google_link}', $link, $mailClientContent);
        $mailClientContent = $this->clearVars($mailClientContent);
        
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");
        $sended = Mail::send('backend.emails.base', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($email, $subject) {
            $message->from(env('MAIL_FROM'));
            $message->to($email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM'));
        });
        
        \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'second_encuesta',$subject,$mailClientContent);

        return $sended;
    }
}
