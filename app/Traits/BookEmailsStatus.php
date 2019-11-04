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
      if (!$book->customer->email || trim($book->customer->email) == '') return;
        $cachedRepository  = new CachedRepository();
        $keyMail = $this->getKeyTemplate($status);
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
        $urlPaymeny        = $PaylandsController->generateOrder($pendiente,'',$book->id);
//        $urlPaymeny        = 'https://miramarski.com/reservas/stripe/pagos/' . base64_encode($book->id) . '/' . base64_encode(round($pendiente));
        $mailClientContent = str_replace('{pend_percent}', $percent, $mailClientContent);
        $mailClientContent = str_replace('{total_payment}', number_format($totalPayment, 2, ',', '.'), $mailClientContent);
        $mailClientContent = str_replace('{pend_payment}', number_format($pendiente, 2, ',', '.'), $mailClientContent);
        $mailClientContent = str_replace('{urlPaymeny_rest}', $urlPaymeny, $mailClientContent);

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
            'url-forfait'               => url('/forfait'),
        );
        
        if (env('APP_APPLICATION') == 'riad'){
          $dataContent['room'] = $data->room->nameRoom;
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
    public function sendEmail_confirmForfaitPayment($book, $orderText,$totalPayment)
    {
      if (!$book->customer->email || trim($book->customer->email) == '') return;
        $mailClientContent = $this->getMailData($book, 'Forfait_email_confirmation_payment');
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");

        $subject = translateSubject('Confirmación de Pago',$book->customer->country);
        
        $mailClientContent = str_replace('{total_payment}', $totalPayment, $mailClientContent);
        $mailClientContent = str_replace('{forfait_order}', $orderText, $mailClientContent);
        $mailClientContent = $this->clearVars($mailClientContent);
        $sended = Mail::send('backend.emails.base', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($book, $subject) {
            $message->from(env('MAIL_FROM_FORFAITS'));
            $message->to($book->customer->email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM_FORFAITS'));
        });
        
        \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'second_payment_reminder',$subject,$mailClientContent);

        return $sended;
    }
    
    /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_linkForfaitPayment($book, $orderText,$link)
    {
      if (!$book->customer->email || trim($book->customer->email) == '') return;
        $mailClientContent = $this->getMailData($book, 'Forfait_email_payment_request');
        setlocale(LC_TIME, "ES");
        setlocale(LC_TIME, "es_ES");

        $subject = translateSubject('Solicitud Forfait',$book->customer->country);
        
        $mailClientContent = str_replace('{forfait_order}', $orderText, $mailClientContent);
        $mailClientContent = str_replace('{link_forfait}', $link, $mailClientContent);
        $mailClientContent = $this->clearVars($mailClientContent);
        $sended = Mail::send('backend.emails.base', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($book, $subject) {
            $message->from(env('MAIL_FROM_FORFAITS'));
            $message->to($book->customer->email);
            $message->subject($subject);
            $message->replyTo(env('MAIL_FROM_FORFAITS'));
        });
        
        \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'send_payment_Forfait',$subject,$mailClientContent);

        return $sended;
    }
    
     /**
     *
     * @param type $book
     * @param type $subject
     */
    public function sendEmail_CancelForfaitItem($book, $orderText,$link)
    {
      if (!$book->customer->email || trim($book->customer->email) == '') return;
        $subject = translateSubject('Forfait Cancelado',$book->customer->country);
        
        $mailClientContent = '<strong>Se ha cancelado un Forfait ya pagado en ForfaitExpress</strong><br/><br/>';
        $mailClientContent .= $orderText.'<br/><br/>';
        $mailClientContent .= "Url de la Orden: <a href='$link' title='Ver Orden'>$link</a>";
        $mailClientContent .= '<p><strong>Compruebe que el forfait fue cancelado correctamente en <a href="forfaitexpress.com">www.forfaitexpress.com</a></strong></p>';
        $sended = Mail::send('backend.emails.base', [
            'mailContent' => $mailClientContent,
            'title'       => $subject
        ], function ($message) use ($book, $subject) {
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
        $sended = Mail::send('backend.emails.base', [
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
}
