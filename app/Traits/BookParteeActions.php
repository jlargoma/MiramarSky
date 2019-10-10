<?php

namespace App\Traits; 
use App\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Book;
use App\BookPartee;


trait BookParteeActions {
  
  
        /**
         * Get the custom message to Partee
         */
        public function getParteeMsg() {
          //Get Book ID
          $bookID = intval($_REQUEST['bookID']);
          
          //Get Book object
          $book = Book::find($bookID);
          if (!$book){
            die('empty');
          }
          //get BookPartee object
          $BookPartee = BookPartee::where('book_id',$bookID)->first();
          if ($BookPartee){
            //Get msg content
            $content = $this->getMailData($book,'SMS_Partee_msg');
            $content = str_replace('{partee}', $BookPartee->link, $content);
            $content = $this->clearVars($content);
            die($content);
          } else {
            die('empty');
          }
        }
        
        /**
         * Send Partee to Finish CheckIn
         * 
         * @param Request $request
         * @return type
         */
        public function finishParteeCheckIn(Request $request) {
          
          $bookID = $request->input('id',null);
          
          $BookPartee = BookPartee::where('book_id',$bookID)->first();
          
          if ($BookPartee){
            
            if ($BookPartee->status == "FINALIZADO"){
              return [
                'status'   => 'danger',
                'response' => "El registro Partee ya se encuentra finalizado."
              ];
            }
            
            if ($BookPartee->status == "HUESPEDES"){
              
               if (true){

                  $BookPartee->status = 'FINALIZADO';
                  $BookPartee->log_data = $BookPartee->log_data .",". time() .'-FINALIZADO';
                  $BookPartee->save();
                  return [
                    'status'   => 'success',
                    'response' => "Registro Partee finalizado",
                  ];

                } 
                
              //Create Partee
              $partee = new \App\Services\ParteeService();
              if ($partee->conect()){

                $result = $partee->finish($BookPartee->partee_id);

                if ($result){

                  $BookPartee->status = 'FINALIZADO';
                  $BookPartee->log_data = $BookPartee->log_data .",". time() .'-FINALIZADO';
                  $BookPartee->save();
                  return [
                    'status'   => 'success',
                    'response' => "Registro Partee finalizado",
                  ];

                } 
              }
          
              return [
                'status'   => 'danger',
                'response' => $partee->response
              ];
             
            }
            
            return [
              'status'   => 'danger',
              'response' => "El registro Partee aún no está preparado."
            ];
              
          }
          
          return [
            'status'   => 'danger',
            'response' => "No existe el registro solicitado."
          ];
        }
        
                /**
         * Send Partee to Finish CheckIn
         * 
         * @param Request $request
         * @return type
         */
        public function finishParteeMail(Request $request) {
          
          $bookID = $request->input('id',null);
          
          //Get Book object
          $book = Book::find($bookID);
          if (!$book){
            return [
                'status'   => 'danger',
                'response' => "Reserva no encontrada."
              ];
          }
          if (!$book->customer->email || trim($book->customer->email) == ''){
              return [
                'status'   => 'danger',
                'response' => "La Reserva no posee email."
              ];
          }
          $BookPartee = BookPartee::where('book_id',$bookID)->first();
          
          if ($BookPartee){
            
            if ($BookPartee->status == "FINALIZADO"){
              return [
                'status'   => 'danger',
                'response' => "El registro Partee ya se encuentra finalizado."
              ];
            }
            var_dump($BookPartee->link);
            $link = '<a href="'.$BookPartee->link.'" title="Ir a Partee">'.$BookPartee->link.'</a>';
            $subject = 'Recordatorio para Completado de Partee';
            $message = $this->getMailData($book,'SMS_Partee_upload_dni');
            $message = str_replace('{partee}', $link, $message);
            $message = $this->clearVars($message);
//            $message = strip_tags($message);
                
            $sended = Mail::send('backend.emails.base', [
                    'mailContent' => $message,
                    'title'       => $subject
                ], function ($message) use ($book, $subject) {
                    $message->from(env('MAIL_FROM'));
                    $message->to($book->customer->email);
                    $message->subject($subject);
                    $message->replyTo(env('MAIL_FROM'));
                });

            \App\BookLogs::saveLog($book->id,$book->room_id,$book->customer->email,'SMS_Partee_upload_dni',$subject,$message);
            if ($sended){
                  $BookPartee->sentSMS=2;
                  $BookPartee->log_data = $BookPartee->log_data .",". time() .'- SMS Sent';
                  $BookPartee->save();
                  return [
                    'status'   => 'success',
                    'response' => "Registro Partee enviado",
                  ];
              } 
            }
            
            return [
              'status'   => 'danger',
              'response' => "El registro Partee aún no está preparado."
            ];
              
          }
        
        
        /**
         * Send Partee to Finish CheckIn
         * 
         * @param Request $request
         * @return type
         */
        public function finishParteeSMS(Request $request) {
          
          $bookID = $request->input('id',null);
          
          //Get Book object
          $book = Book::find($bookID);
          if (!$book){
            return [
                'status'   => 'danger',
                'response' => "Reserva no encontrada."
              ];
          }
          
          $BookPartee = BookPartee::where('book_id',$bookID)->first();
          
          if ($BookPartee){
            
            if ($BookPartee->status == "FINALIZADO"){
              return [
                'status'   => 'danger',
                'response' => "El registro Partee ya se encuentra finalizado."
              ];
            }
            if ($BookPartee->status == "HUESPEDES"){
              
              
              if ($BookPartee->guestNumber){
                if ($BookPartee->guestNumber == $book->pax){
                  return [
                    'status'   => 'danger',
                    'response' => "El registro Partee ya se encuentra en proceso de finalización."
                  ];
                }
              }
            }
            
            if ($BookPartee->status == "VACIO" || $BookPartee->status == "HUESPEDES"){
              
              //Send SMS
              $SMSService = new \App\Services\SMSService();
              if ($SMSService->conect()){

                $message = $this->getMailData($book,'SMS_Partee_upload_dni');
                $message = str_replace('{partee}', $BookPartee->link, $message);
                $phone = $book->customer['phone'];
                $message = $this->clearVars($message);
                $message = strip_tags($message);
                
                if ($SMSService->sendSMS($message,$phone)){
                  $BookPartee->sentSMS=2;
                  $BookPartee->log_data = $BookPartee->log_data .",". time() .'- SMS Sent';
                  $BookPartee->save();
                  return [
                    'status'   => 'success',
                    'response' => "Registro Partee enviado",
                  ];

                } 
              }
          
              return [
                'status'   => 'danger',
                'response' => $SMSService->response
              ];
             
            }
            
            return [
              'status'   => 'danger',
              'response' => "El registro Partee aún no está preparado."
            ];
              
          }
          
          return [
            'status'   => 'danger',
            'response' => "No existe el registro solicitado."
          ];
        }
        
        public function getParteeLst(){
          
          $today = Carbon::now();
          $books = \App\Book::where('start', '>=', $today->copy()->subDays(2))
                  ->where('start', '<=', $today->copy()->addDays(5))
                  ->where('type_book', 2)->orderBy('start', 'ASC')->get();
                
          $payment = array();
            foreach ($books as $key => $book)
            {
                $payment[$book->id] = 0;
                $payments = \App\Payments::where('book_id', $book->id)->get();
                if (count($payments) > 0) 
                  foreach ($payments as $key => $pay) $payment[$book->id] += $pay->import;

            }
            
          $rooms = \App\Rooms::where('state', '=', 1)->get();
//          foreach ($rooms as $r){
//            
//          }
//                
          $mobile    = new \App\Classes\Mobile();
//          $parteeToActive = BookPartee::whereIn('status', ['HUESPEDES',"FINALIZADO"])->get();
//          $parteeToActive = BookPartee::get();
          return view('backend/planning/_alarmsPartee',compact('books','payment','rooms','mobile','today'));
          
        }
        
        public function showSendRemember($bookID) {
          $book = Book::find($bookID);
          $disableEmail = 'disabled';
          $disablePhone = 'disabled';
          if ($book){
            if ($book->customer->email) $disableEmail = '';
            if ($book->customer->phone) $disablePhone = '';
          }
          ?>
<div class="col-md-6">
  <button class="sendSMS btn btn-default <?php echo $disablePhone;?>" data-id="<?php echo $bookID;?>">
    <i class="sendSMSIcon"></i>Enviar SMS
  </button>
</div>
<div class="col-md-6">
  <button class="sendParteeMail btn btn-primary <?php echo $disableEmail;?>" data-id="<?php echo $bookID;?>">
    <i class="fa fa-inbox"></i> Enviar Email
  </button>
</div>
          <?php
        }
}
