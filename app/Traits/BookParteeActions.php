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
              
              //Create Partee
              $partee = new \App\Services\ParteeService();
              if ($partee->conect()){

                $result = $partee->finish($BookPartee->partee_id);

                if ($result){

                  $BookPartee->status = 'FINALIZADO';
                  $BookPartee->log_data = $BookPartee->log_data .",". time() .'- FINALIZADO';
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
              return [
                'status'   => 'danger',
                'response' => "El registro Partee ya se encuentra en proceso de finalización."
              ];
            }
            
            if ($BookPartee->status == "VACIO"){
              
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
          $parteeToActive = BookPartee::whereIn('status', ['HUESPEDES',"FINALIZADO"])->get();
//          $parteeToActive = BookPartee::get();
          return view('backend/planning/_alarmsPartee',['alarms' => $parteeToActive]);
          
        }
}
