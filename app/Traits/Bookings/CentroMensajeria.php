<?php

namespace App\Traits\Bookings;

use App\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Book;
use App\BookPartee;
use App\PaymentOrders;
use Illuminate\Support\Facades\Auth;
trait CentroMensajeria {
            

  public function createFianza($bookID) {
    $book = Book::find($bookID);
    if ($book) {
      $hasFianza = PaymentOrders::where('book_id', $book->id)->where('is_deferred', 1)->first();
      if (!$hasFianza) {
        $urlPayment = $this->createPaymentFianza($book);
        if ($urlPayment) {
          $this->sendEmail_FianzaPayment($book, 300, $urlPayment);
          return [
              'status' => 'success',
              'response' => "Fianza creada y Mail enviado",
          ];
        }
        return [
            'status' => 'success',
            'response' => "Fianza creada",
        ];
      }
    }
    return [
        'status' => 'danger',
        'response' => "Fianza ya creada",
    ];
  }

  public function showFianza($bookID) {



    $book = Book::find($bookID);
    $disableEmail = 'disabled';
    $disablePhone = 'disabled';
    if ($book) {
      if ($book->customer->email)
        $disableEmail = '';
      if ($book->customer->phone)
        $disablePhone = '';
    }

    if ($this->showPaymentFianzaForm($book))
      return;

    $Order = PaymentOrders::where('book_id', $book->id)->where('is_deferred', 1)->first();
    $showInfo = [];
    if ($Order) {

      $urlPay = getUrlToPay($Order->token);
      $link = '<a href="' . $urlPay . '" title="Ir a Partee">' . $urlPay . '</a>';
      $totalPayment = $Order->amount / 100;
      $subject = translateSubject('Fianza de reserva', $book->customer->country);
      $message = $this->getMailData($book, 'SMS_fianza');
      $message = str_replace('{payment_amount}', number_format($totalPayment, 2, ',', '.'), $message);
      $message = str_replace('{urlPayment}', $urlPay, $message);
      $message = $this->clearVars($message);
    } else {
      ?>
      <p class="alert alert-warning">Fianza no encontrada</p>
      <p class="text-center">
        <button type="button" class="createFianza btn btn-success" data-id="<?php echo $book->id; ?>">Crear Fianza</button>
      </p>
      <?php
      return;
    }
    ?>
    <div class="col-md-6 minH-4">
      <button class="sendFianzaSMS btn btn-default <?php echo $disablePhone; ?>" title="Enviar Texto Fianza por SMS" data-id="<?php echo $bookID; ?>">
        <i class="sendSMSIcon"></i>Enviar SMS
      </button>
    </div>
    <div class="col-md-6 minH-4">
      <button class="sendFianzaMail btn btn-default <?php echo $disableEmail; ?>" title="Enviar Texto Fianza por Correo" data-id="<?php echo $bookID; ?>">
        <i class="fa fa-inbox"></i> Enviar Email
      </button>
    </div>
    <div class="col-md-6 minH-4">
      <a href="whatsapp://send?text=<?php echo $message; ?>"
         data-action="share/whatsapp/share"
         data-original-title="Enviar Partee link"
         data-toggle="tooltip"
         class="btn btn-default <?php echo $disablePhone; ?>">
        <i class="fa  fa-whatsapp" aria-hidden="true" style="color: #000; margin-right: 7px;"></i>Enviar Whatsapp
      </a>
    </div>
    <div class="col-md-6 minH-4">
      <button class="showParteeLink btn btn-default" title="Mostrar link Fianza">
        <i class="fa fa-link"></i> Mostart Link
      </button>
    </div>  
    <div class="col-md-6 minH-4"> 
      <button class="btn btn-default copyMsgFianza" title="Copiar mensaje Fianza" data-msg="<?php echo strip_tags($message); ?>">
        <i class="far fa-copy"></i>  Copiar mensaje Fianza
      </button>
      <div id="copyMsgFianza"></div>
    </div>
    <div id="linkPartee" class="col-xs-12" style="display:none;max-width: 320px;overflow: auto;"><?php echo $link; ?></div>
    <?php
  }

  function sendFianzaMail(request $request) {
    $bookID = $request->input('id', null);

    $book = Book::find($bookID);
    if ($book) {
      $Order = PaymentOrders::where('book_id', $book->id)->where('is_deferred', 1)->first();
      $showInfo = [];
      if ($Order) {
        $urlPayment = getUrlToPay($Order->token);
        $totalPayment = $Order->amount;
        $this->sendEmail_FianzaPayment($book, $totalPayment, $urlPayment);
        return [
            'status' => 'success',
            'response' => "Mail de Fianza enviado",
        ];
      }
    }

    return [
        'status' => 'danger',
        'response' => "Registro de Fianza no encontrado."
    ];
  }

  /**
   * Send Fianza by SMS
   * @param Request $request
   * @return type
   */
  public function sendFianzaSMS(Request $request) {
    $bookID = $request->input('id', null);

    $book = Book::find($bookID);
    if ($book) {
      $Order = PaymentOrders::where('book_id', $book->id)->where('is_deferred', 1)->first();
      $showInfo = [];
      if ($Order) {
        $urlPayment = getUrlToPay($Order->token);
        $totalPayment = $Order->amount;
        //Send SMS
        $SMSService = new \App\Services\SMSService();
        if ($SMSService->conect()) {

          $message = $this->getMailData($book, 'SMS_fianza');
          $message = str_replace('{urlPayment}', $urlPayment, $message);
          $message = str_replace('{payment_amount}', $totalPayment, $message);
          $phone = $book->customer['phone'];
          $message = $this->clearVars($message);
          $message = strip_tags($message);

          if ($SMSService->sendSMS($message, $phone)) {
            return [
                'status' => 'success',
                'response' => "Mail de Fianza enviado",
            ];
          }
        }
      }
    }

    return [
        'status' => 'danger',
        'response' => "Registro de Fianza no encontrado."
    ];
  }

  public function showPaymentFianzaForm($book) {

    $Order = \App\BookDeferred::where('book_id', $book->id)
                    ->where('is_deferred', 1)->where('paid', 1)->first();

    if ($Order) {
      ?>

      <div class="col-md-12 minH-4">

      <?php
      if ($Order->was_confirm && $Order->payment > 0):
        echo '<p>Pago efectuado: <b>' . ($Order->payment / 100) . '€.</b></p>';
      else:
        ?>
          <div style="margin-bottom: 1em;">
            <label for="name">Cobrar de la fianza, la suma de:</label>
            <input class="form-control" type="number" id="amount_fianza" value="300">
          </div>
          <button class="sendPayment btn btn-success" title="Cobrar Fianza" data-id="<?php echo $book->id; ?>">
            <i class="fa fa-euro"></i> Generar Cobro
          </button>
          <p>
            <small>
              <strong>Importante:</strong> Sólo puedes efectuar un cobro a la Fianza (sin importart el monto).
            </small>
          </p>
        <?php
        endif;
        ?>

        <p>
          <strong>ID de Orden De Payland:</strong> <?php echo $Order->order_uuid; ?>
        </p>
      </div> 
      <?php
      return true;
    }
    return false;
  }

  
  /***********************************************************************/
  public function getCustomersRequestLst(){

    $items = \App\CustomersRequest::where('status',0)
                ->where('updated_at','>=',date('Y-m-d', strtotime('-7 days')))
                ->get();
     
    /*********************************************/
    
    $users = \App\User::where('role','!=','limpieza')->get();
    $aUsers = [];
    foreach ($users as $v){
      $aUsers[$v->id] = $v->name;
    }
    $isMobile = config('app.is_mobile');
    return view('backend/planning/_customers-request',compact('items','isMobile','aUsers'));

  }   
  public function hideCustomersRequest(Request $request) {
          
    $ID = $request->input('id',null);
    $user_id = $request->input('userID',null);
    $comment = $request->input('comments',null);

    $item = \App\CustomersRequest::find($ID);
    if ($item){
      $item->user_id = $user_id;
      $item->comment = $comment;
      $item->update_by = Auth::user()->id;
      $item->status =  1; // ignered;
      $item->save();
      return 'OK';
    }
    return 'error';
  }   
    
  public function saveCustomerRequest(Request $request) {
          
    $ID = $request->input('id',null);
    $user_id = $request->input('userID',null);
    $comment = $request->input('comments',null);
    $send_mail = $request->input('send_mail',null);

    $item = \App\CustomersRequest::find($ID);
    if ($item){
      if ($user_id == -2){
        $item->delete();
        return 'OK';
      }
      $item->user_id = $user_id;
      $item->comment = $comment;
      $item->update_by = Auth::user()->id;
      $item->save();
  
      if ($send_mail){
        $email = $item->email;
        $subject = "Reservas Apartamiento Sierra Nevada";
        $sended = Mail::send('backend.emails.base', [
            'mailContent' => nl2br($comment),
            'title'       => $subject,
        ], function ($message) use ($subject,$email) {
            $message->from(env('MAIL_FROM'));
            $message->to($email);
            $message->subject($subject);
        });
        if ($sended){
          $item->sentMail();
        } else {
          return 'errorMail';
        }
      }
      
      return 'OK';
    }
    return 'error';
  }   
  
  public function getCustomersRequest(Request $request) {
          
    $ID = $request->input('id',null);

    $item = \App\CustomersRequest::where('id', $ID)->first();
    if ($item){
      $status = 'Sin atender';
      
      if ($item->status == 1) $status = 'Ignorada';
      
      $booking = '';
      if ($item->book_id){
        $status = 'Converida a Reserva';
        $booking = '<a href="/admin/reservas/update/'.$item->book_id.'" tarjet="_black">'.$item->book_id.'</a>';
      }
    
      if (!$item->user_id){
        $item->user_id = Auth::user()->id;
      }
      
      $req = [
          'id'     => $item->id,
          'user_id'=> $item->user_id,
          'name'   => $item->name,
          'email'  => $item->email,
          'pax'    => $item->pax,
          'price'  => moneda($item->getMediaPrice()),
          'phone'  => '<a href="tel:+'.$item->phone.'">'.$item->phone.'</a>',
          'comment'=> $item->comment,
          'date'   => dateMin($item->start).' - '.dateMin($item->finish),
          'status' => $status,
          'booking'=> $booking,
          'created'=> convertDateTimeToShow_text($item->created_at),
          'canBooking' => ($item->start>=date('Y-m-d')),
          'mails' => $item->getMetasContet('mailSent')
      ];
      
      return response()->json($req);
    }
  }   
  
  public function getCustomersRequest_book($bookID) {
    $userID = Auth::user()->id;
    $comment = '';
    $item = \App\CustomersRequest::where('book_id', $bookID)->first();
    if ($item){
      if ($item->user_id){
        $userID = $item->user_id;
      }
      $comment = $item->comment;
    } else {
      $oBook = Book::find($bookID);
      $item = new \App\CustomersRequest();
      $item->user_id = $userID;
      $item->book_id = $bookID;
      $item->site_id =  $oBook->room->site_id;
      $item->pax =  $oBook->pax;
      $item->name = $oBook->customer->name.' B-'.$oBook->id;
      $item->status =  1; // ignered;
      $item->save();
    }
    
    
    $users = \App\User::where('role','!=','limpieza')->get();
    $aUsers = [];
    foreach ($users as $v){
      $aUsers[$v->id] = $v->name;
    }
    ?>
      <div class="">
        <div class="form-group">
          <label>Usuario</label>
          <select id="CRE_user" class="form-control">
            <?php  foreach ($users as $v):
              $select = ($v->id === $userID) ? 'selected' : '';
              echo '<option value="'.$v->id.'" '.$select.'>'.$v->name.'</option>';
              endforeach;
            ?>
            <option value="-2">ELIMINAR</option>
          </select>
        </div>
        <div class="form-group">
          <label>Comentario</label>
          <textarea class="form-control" id="CRE_comment" rows="5"><?php echo $comment; ?></textarea>
        </div>
        <button class="btn btn-primary" id="saveCustomerRequest" type="button" data-id="<?php echo $item->id; ?>">Guardar</button>
      </div>
    <?php
  }   
  /****************************************************************************/
      function showFormEncuesta($bookID) {
    $book = Book::find($bookID);
    $alerts = [];
    if (!$book->customer->email || trim($book->customer->email) == ''){
      $alerts[] = 'El cliente no posee un emial cargado.';
    }
    
    $alreadySent = \App\BookData::where('book_id',$book->id)
              ->where('key','sent_poll')->first();
    
    $bloqueada = false;
    if ($alreadySent){
      $content = json_decode($alreadySent->content);
      if($content)
      foreach ($content as $c){
        $date = date('d/m', strtotime($c->date));
        if (isset($c->status)){
          switch ($c->status){
            case 'block':
              $alerts[] = $date.' Bloqueado por '.$c->userName;
              $bloqueada = true;
              break;
            case 'unblock':
              $alerts[] = $date.' Desbloqueado por '.$c->userName;
              $bloqueada = false;
              break;
            default :
              $alerts[] = $date.' enviado a '.$c->mail;
              break;
          }
        }
      }
    }
    
    ?>
      <div class="box-new">
          <div>
            <ul>
            <?php
                foreach ($alerts as $a) echo '<li>'.$a.'</li>';
            ?>
            </ul>
          </div>
          <div class="row">
            <div class="col-md-4 col-xs-12 mb-1em">
              <?php if (!$bloqueada): ?>
              <button class="btn btn-primary form_sendEncuesta" 
                      type="button" 
                      data-id="<?php echo $bookID; ?>" 
                      data-action="send" 
                      <?php echo ($bloqueada) ? 'disabled' : ''; ?>>
                Enviar encuesta mail
              </button>
              <?php endif; ?>
            </div>
            <div class="col-md-4 col-xs-12 mb-1em">
              <button class="btn  btn-danger form_sendEncuesta" 
                      type="button" 
                      data-id="<?php echo $bookID; ?>" 
                      data-action="block" 
                      >
                <?php echo ($bloqueada) ? 'Desbloquear' : 'Bloquear' ?>  encuesta mail
              </button>
            </div>
            <div class="col-md-4 col-xs-12 mb-1em">
              <?php $text = "Hola, esperamos que hayas disfrutado de tu estancia con nosotros." . "\n" . "Nos gustaria que valorarás, para ello te dejamos este link : https://www.apartamentosierranevada.net/encuesta-satisfaccion/" . base64_encode($book->id); ?>
              <a href="whatsapp://send?text=<?php echo $text; ?>"
                 data-action="share/whatsapp/share"
                 data-original-title="Enviar encuesta de satisfacción"
                 data-toggle="tooltip"
                 class="btn btn-primary">
                Enviar por Whatsapp
              </a>
            </div>
          </div>
        </div>
    <?php
    
    return;
  }
  function sendEncuesta(Request $request) {
          
    $bookID = $request->input('id',null);
    $action = $request->input('action',null);
    $book = Book::find($bookID);
    $auth = Auth::user();
    $bloqueada = false;
    if (!$book){
      return response()->json(['status'=>'error','msg'=>"Reserva no encontrada"]);
    }
    
    /****************************************************/
    if ($action == 'block'){
      $alreadySent = \App\BookData::where('book_id',$book->id)
              ->where('key','sent_poll')->first();
      $dataSent = [
          'date' => date('Y-m-d H:i:s'),
          'status' => 'block',
          'userName' => $auth->name,
          'userID' => $auth->id,
          'mail' =>$book->customer->email
      ];
      if ($alreadySent){
        $content = json_decode($alreadySent->content,true);
        
        foreach ($content as $c){
          if (isset($c['status'])){
            switch ($c['status']){
              case 'block':
                $bloqueada = true;
                break;
              case 'unblock':
                $bloqueada = false;
                break;
            }
          }
        }
        if ($bloqueada){
          $dataSent['status'] = 'unblock';
          $bloqueada = false;
        }
        $content[] = $dataSent;
        $alreadySent->content = json_encode($content);
        $alreadySent->save();
        
      } else {
        $save = new \App\BookData();
        $save->book_id = $book->id;
        $save->key = 'sent_poll';
        $save->content = json_encode([$dataSent]);
        $save->save();
      }
      if ($bloqueada)  return response()->json(['status'=>'OK','msg'=>"encuesta desbloqueada"]);
      return response()->json(['status'=>'OK','msg'=>"encuesta bloqueada"]);
    }
    
    
    /****************************************************/
    if (!$book->customer->email || trim($book->customer->email) == ''){
      return response()->json(['status'=>'error','msg'=>'El cliente no posee un emial cargado.']);
    }
    
    $alreadySent = \App\BookData::where('book_id',$book->id)
              ->where('key','sent_poll')->first();
    
    if ($alreadySent){
      $content = json_decode($alreadySent->content);
      $bloqueada = false;
      if($content)
      foreach ($content as $c){
        if (isset($c->status)){
          switch ($c->status){
            case 'block':
              $bloqueada = true;
            case 'unblock':
              $bloqueada = false;
          }
        }
      }
      if ($bloqueada){
        return response()->json(['status'=>'error','msg'=>'La Encuesta está bloqueada.']);
      }
    }
    
    if ($this->sendEmail_Encuesta($book,"DANOS 5' Y TE INVITAMOS A DESAYUNAR")){
   
      $dataSent = [
          'date' => date('Y-m-d H:i:s'),
          'status' => 'sent',
          'userName' => $auth->name,
          'userID' => $auth->id,
          'mail' =>$book->customer->email
      ];
      if ($alreadySent){
        $content = json_decode($alreadySent->content,true);
        $content[] = $dataSent;
        $alreadySent->content = json_encode($content);
        $alreadySent->save();
      } else {
        $save = new \App\BookData();
        $save->book_id = $book->id;
        $save->key = 'sent_poll';
        $save->content = json_encode([$dataSent]);
        $save->save();
      }
      return response()->json(['status'=>'OK','msg'=>'Encuesta enviada']);
    }
    
    return response()->json(['status'=>'error','msg'=>'Encuesta no enviada']);
  }
  
}
