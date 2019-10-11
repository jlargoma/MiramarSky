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
                  $BookPartee->log_data = $BookPartee->log_data .",". time() .'-FINALIZADO';
                  $BookPartee->date_finish = date('Y-m-d H:i:s');
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
                  $BookPartee->log_data = $BookPartee->log_data .",".time() . '-' .'sentMail';
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
                  $BookPartee->log_data = $BookPartee->log_data .",".time() . '-' .'sentSMS';
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
          $partee = BookPartee::where('book_id',$bookID)->first();
          $showInfo = [];
          if ($partee && trim($partee->link) !=''){
            $data = $partee->log_data;
            if ($data){
              preg_match_all('|([0-9])*(\-sentSMS)|', $data, $info);
              if (isset($info[0])){
                foreach ($info[0] as $t){
                  $showInfo[intval($t)] = '<b>SMS</b> enviado el '.date('d/m H:i', intval($t));
                }
              }
              preg_match_all('|([0-9])*(\-sentMail)|', $data, $info);
              if (isset($info[0])){
                foreach ($info[0] as $t){
                  $showInfo[intval($t)] = '<b>Mail</b> enviado el '.date('d/m H:i', intval($t));
                }
              }
             
            }
            $link = '<a href="'.$partee->link.'" title="Ir a Partee">'.$partee->link.'</a>';
            $subject = 'Recordatorio para Completado de Partee';
            $message = $this->getMailData($book,'SMS_Partee_upload_dni');
            $message = str_replace('{partee}', $partee->link, $message);
            $message = $this->clearVars($message);
            
          } else {
            ?>
            <p class="alert alert-warning">Partee no encontrado</p>
            <p class="text-center">
              <a href="/admin/sendPartee/<?php echo $book->id; ?>">Crear Partee</a>
            </p>
            <?php
            return;
          }
         
          ?>
        <div class="col-md-6 minH-4">
          <button class="sendSMS btn btn-default <?php echo $disablePhone;?>" title="Enviar Texto partee por SMS" data-id="<?php echo $bookID;?>">
            <i class="sendSMSIcon"></i>Enviar SMS
          </button>
        </div>
        <div class="col-md-6 minH-4">
          <button class="sendParteeMail btn btn-default <?php echo $disableEmail;?>" title="Enviar Texto partee por Correo" data-id="<?php echo $bookID;?>">
            <i class="fa fa-inbox"></i> Enviar Email
          </button>
        </div>
        <div class="col-md-6 minH-4">
          <a href="whatsapp://send?text=<?php echo $message; ?>"
                 data-action="share/whatsapp/share"
                 data-original-title="Enviar Partee link"
                 data-toggle="tooltip"
                 class="btn btn-default <?php echo $disablePhone;?>">
            <i class="fa  fa-whatsapp" aria-hidden="true" style="color: #000; margin-right: 7px;"></i>Enviar Whatsapp
              </a>
        </div>
        <div class="col-md-6 minH-4">
          <button class="showParteeLink btn btn-default" title="Mostrar link Partee">
            <i class="fa fa-link"></i> Mostart Link
          </button>
        </div>  
        <div id="linkPartee" class="col-xs-12" style="display:none;"><?php echo $link; ?></div>
        <div class="col-xs-12"> 
          <button class="showParteeData btn btn-default" title="Mostrar Partee" data-partee_id="<?php echo $partee->partee_id; ?>">
            <i class="fa fa-eye"></i> Mostart Partee
          </button>
        </div>
          <?php
           if (count($showInfo)){
            ksort($showInfo);
            echo '<div class="col-md-12" style="margin-top:3em;" ><b>Histórico:</b><br>';
            echo implode('<br>', $showInfo);
            echo '</div>';
          }
        }
        function sendPartee($bookID) {
          $book = Book::find($bookID);
          if ($book){
            $return = $book->sendToPartee();
          }
          return back();
        }
        function seeParteeHuespedes($id){
          $partee = new \App\Services\ParteeService();
          if ($partee->conect()){
            $partee->getCheckHuespedes($id);
            if ($partee){
              $obj = $partee->response;
              ?>
                <h1>Partee</h1>
                <?php if ($obj->borrador): ?>
                <h2>Borrador: no enviado aún a la policia</h2>
                <?php endif; ?>
                <p><b>Creado: </b> <?php echo date('d/m H:i', strtotime($obj->fecha_creacion)); ?>Hrs</p>
                <p><b>Entrada: </b> <?php echo date('d/m H:i', strtotime($obj->fecha_entrada)); ?>Hrs</p> 
                <p><b>Enlace Checkin: </b> <a href="<?php echo $obj->checkin_online_url; ?>" ><?php echo $obj->checkin_online_url; ?></a></p>
                <h3>Viajeros Cargados</h3>
                <?php 
                if ($obj->viajeros):
                  foreach ($obj->viajeros as $v):
                    if ($v->borrador): ?>
                    <strong>Borrador</strong>
                    <?php endif; ?>
                      <h4><?php echo $v->nombre_viajero.' '.$v->primer_apellido_viajero; ?></h4>
                      <p><b>Sexo: </b> <?php echo $v->sexo_viajero; ?></p>
                      <p><b>Dias estancia: </b> <?php echo $v->dias_estancia; ?></p>
                      <p><b>Entrada: </b> <?php echo $v->fecha_expedicion_documento; ?></p>
                      <p><b>Indentificación: </b> <?php echo $v->numero_identificacion.' - '.$v->pais_nacimiento_viajero; ?></p>
                    <?php
                  endforeach;
                endif;
              return;
            }
          }
          
          echo '<h1>Partee no encontrado</h1>';
          
        }
}
