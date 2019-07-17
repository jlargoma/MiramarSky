<?php

namespace App\Traits; 
use App\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\BookPartee;


trait BookEmailsStatus {
  
  /**
   * 
   * @param type $book
   * @param type $subject
   * @param type $status
   */
  public function sendEmailChangeStatus($book,$subject,$status) {
          
          $mailClientContent = $this->getMailData($book,$this->getKeyTemplate($status));
          setlocale(LC_TIME, "ES"); 
          setlocale(LC_TIME, "es_ES"); 
  
          switch ($status){
            case "1":
              $percent = $this->getPercent($book);
              $mount_percent = number_format(($book->total_price*$percent),2,',','.');
              $PaylandsController = new \App\Http\Controllers\PaylandsController();
//              $urlToPayment = $PaylandsController->generateOrderPayment([
//                            'customer_id' => $book->customer->id,
//                            'amount'      => (round($book->total_price * $percent)),
//                            'url_ok'      => route('payland.thanks.payment', ['id' => $book->id]),
//                            'url_ko'      => route('payland.thanks.payment', ['id' => $book->id]),
//                          ]);
              $urlToPayment = '$urlToPayment';
              
              $mailClientContent = str_replace('{urlToPayment}', $urlToPayment, $mailClientContent);
              $mailClientContent = str_replace('{mount_percent}', $mount_percent, $mailClientContent);
              $mailClientContent = str_replace('{percent}', $percent, $mailClientContent);
              break;
            
            case "2":

              $linkPartee = null;
              $BookPartee = BookPartee::where('book_id',$book->id)->first();
          
              if ( $BookPartee && $BookPartee->partee_id>0 ) { 
                $linkPartee = $BookPartee->link;
              }
              $mailClientContent = str_replace('{partee}', $linkPartee, $mailClientContent);
              $mailClientContent = str_replace('{LastPayment}', number_format($book->getLastPayment(),2,',','.'), $mailClientContent);
              break;
          }
          
          Mail::send('backend.emails.base', 
            ['mailContent' => $mailClientContent,'title'=>$subject], function ($message) use ($book,$subject) {
              $message->from('reservas@apartamentosierranevada.net');
              $message->to($book->customer->email);
              $message->subject($subject);
            });
                 
        }

        /**
         * 
         * @param type $book
         * @param type $subject
         */
        public function sendEmail_secondPayBook($book,$subject) {
          $mailClientContent = $this->getMailData($book,'second_payment_reminder');
          setlocale(LC_TIME, "ES"); 
          setlocale(LC_TIME, "es_ES"); 
          
          $totalPayment = 0;
          $payments = \App\Payments::where('book_id', $book->id)->get();
          if ( count($payments) > 0) {
                  foreach ($payments as $key => $pay) {
                          $totalPayment += $pay->import;
                  }
          }
          $percent = 100 - (round(($totalPayment/$book->total_price) * 100));
          $pendiente = ($book->total_price - $totalPayment);
          $urlPaymeny = 'https://miramarski.com/reservas/stripe/pagos/'.base64_encode($book->id).'/'.base64_encode(round($pendiente));
          $mailClientContent = str_replace('{pend_percent}', $percent, $mailClientContent);
          $mailClientContent = str_replace('{total_payment}', number_format($totalPayment,2,',','.'), $mailClientContent);
          $mailClientContent = str_replace('{pend_payment}', number_format($pendiente,2,',','.'), $mailClientContent);
          $mailClientContent = str_replace('{urlPaymeny_rest}', $urlPaymeny, $mailClientContent);
        
          
           Mail::send('backend.emails.base', 
            ['mailContent' => $mailClientContent,'title'=>$subject], function ($message) use ($book,$subject) {
              $message->from('reservas@apartamentosierranevada.net');
              $message->to($book->customer->email);
              $message->subject($subject);
              $message->replyTo('reservas@apartamentosierranevada.net');
            });
            
        }
        
        
        private function getKeyTemplate($status) {
          switch ($status){
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
         * @param String $key
         * @return String HTML
         */
        public function getMailData($data,$keyTemp) {
          
          $mailClientContent = Settings::getContent($keyTemp);
         
          $dataContent = array(
                'customer_name' =>  $data->customer->name,
                'customer_email' =>  $data->customer->email,
                'customer_phone' =>  $data->customer->phone,
                'room' =>  $data->room->sizeRooms->name,
                'room_type' =>  ($data->type_luxury == 1)? "Lujo" : "Estandar",
                'room_name' =>  $data->room->name,
                'date_start' =>  date('d-m-Y', strtotime($data->start)),
                'date_end' =>  date('d-m-Y', strtotime($data->finish)),
                'nigths' =>  $data->nigths,
                'pax' =>  $data->pax,
                'sup_lujo' =>  number_format($data->sup_lujo,0,'','.'),
                'comment' =>  $data->comment,
                'book_comments' =>  $data->book_comments,
                'total_price' =>  number_format($data->total_price,0,'','.'),
                'url-condiciones-generales'=>url('/condiciones-generales'),
                'url-forfait'=>url('/forfait'),
              );
          
           /** process the mail content */
          foreach ($dataContent as $k=>$v){
            $mailClientContent = str_replace('{'.$k.'}', $v, $mailClientContent);
          }
          
          return $mailClientContent;
          
        }
                
        public function getPercent($book) {
          $percent = 0.5;
          $date = Carbon::createFromFormat('Y-m-d', $book->start);
          $now = Carbon::now();
          $rule = \App\RulesStripe::find(1);
          $diff = $now->diffInDays($date);

          if ( $diff <= $rule->numDays ) {
                  $percent = ($rule->percent/100);
          }else{
          $percent = 0.50;
          }
          return $percent;
        }

}
