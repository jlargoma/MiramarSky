<?php

namespace App\Traits;

use App\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Book;
use App\BookPartee;
use App\PaymentOrders;

trait BookCentroMensajeria {

  /**
   * Get the custom message to Partee
   */
  public function getParteeMsg() {
    //Get Book ID
    $bookID = intval($_REQUEST['bookID']);

    //Get Book object
    $book = Book::find($bookID);
    if (!$book) {
      die('empty');
    }
    //get BookPartee object
    $BookPartee = BookPartee::where('book_id', $bookID)->first();
    if ($BookPartee) {
      //Get msg content
      $content = $this->getMailData($book, 'SMS_Partee_msg');
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

    $bookID = $request->input('id', null);

    $BookPartee = BookPartee::where('book_id', $bookID)->first();

    if ($BookPartee) {

      if ($BookPartee->status == "FINALIZADO") {
        return [
            'status' => 'danger',
            'response' => "El registro Partee ya se encuentra finalizado."
        ];
      }

      if ($BookPartee->status == "HUESPEDES") {

        //Create Partee
        $partee = new \App\Services\ParteeService();
        if ($partee->conect()) {

          $result = $partee->finish($BookPartee->partee_id);

          if ($partee->response && isset($partee->responseCode) && $partee->responseCode == 200) {
            if ($partee->response->isError) {
              return [
                  'status' => 'danger',
                  'response' => $partee->response->errorMessage,
              ];
            }


            $BookPartee->status = 'FINALIZADO';
            $BookPartee->log_data = $BookPartee->log_data . "," . time() . '-FINALIZADO';
            $BookPartee->date_finish = date('Y-m-d H:i:s');
            $BookPartee->save();
            return [
                'status' => 'success',
                'response' => "Registro Partee finalizado",
            ];
          }
        }

        return [
            'status' => 'danger',
            'response' => $partee->response
        ];
      }

      return [
          'status' => 'danger',
          'response' => "El registro Partee aún no está preparado."
      ];
    }

    return [
        'status' => 'danger',
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

    $bookID = $request->input('id', null);

    //Get Book object
    $book = Book::find($bookID);
    if (!$book) {
      return [
          'status' => 'danger',
          'response' => "Reserva no encontrada."
      ];
    }
    if (!$book->customer->email || trim($book->customer->email) == '') {
      return [
          'status' => 'danger',
          'response' => "La Reserva no posee email."
      ];
    }
    $BookPartee = BookPartee::where('book_id', $bookID)->first();

    if ($BookPartee) {

      if ($BookPartee->status == "FINALIZADO") {
        return [
            'status' => 'danger',
            'response' => "El registro Partee ya se encuentra finalizado."
        ];
      }
      $link = '<a href="' . $BookPartee->link . '" title="Ir a Partee">' . $BookPartee->link . '</a>';
      $subject = translateSubject('Recordatorio para Completado de Partee', $book->customer->country);
      $message = $this->getMailData($book, 'SMS_Partee_upload_dni');
      $message = str_replace('{partee}', $link, $message);
      $message = $this->clearVars($message);
//            $message = strip_tags($message);

      $sended = Mail::send('backend.emails.base', [
                  'mailContent' => $message,
                  'title' => $subject
                      ], function ($message) use ($book, $subject) {
                $message->from(env('MAIL_FROM'));
                $message->to($book->customer->email);
                $message->subject($subject);
                $message->replyTo(env('MAIL_FROM'));
              });

      \App\BookLogs::saveLog($book->id, $book->room_id, $book->customer->email, 'SMS_Partee_upload_dni', $subject, $message);
      if ($sended) {
        $BookPartee->sentSMS = 2;
        $BookPartee->log_data = $BookPartee->log_data . "," . time() . '-' . 'sentMail';
        $BookPartee->save();
        return [
            'status' => 'success',
            'response' => "Registro Partee enviado",
        ];
      }
    }

    return [
        'status' => 'danger',
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

    $bookID = $request->input('id', null);

    //Get Book object
    $book = Book::find($bookID);
    if (!$book) {
      return [
          'status' => 'danger',
          'response' => "Reserva no encontrada."
      ];
    }

    $BookPartee = BookPartee::where('book_id', $bookID)->first();

    if ($BookPartee) {

      if ($BookPartee->status == "FINALIZADO") {
        return [
            'status' => 'danger',
            'response' => "El registro Partee ya se encuentra finalizado."
        ];
      }
      if ($BookPartee->status == "HUESPEDES") {


        if ($BookPartee->guestNumber) {
          if ($BookPartee->guestNumber == $book->pax) {
            return [
                'status' => 'danger',
                'response' => "El registro Partee ya se encuentra en proceso de finalización."
            ];
          }
        }
      }

      if ($BookPartee->status == "VACIO" || $BookPartee->status == "HUESPEDES") {

        //Send SMS
        $SMSService = new \App\Services\SMSService();
        if ($SMSService->conect()) {

          $message = $this->getMailData($book, 'SMS_Partee_upload_dni');
          $message = str_replace('{partee}', $BookPartee->link, $message);
          $phone = $book->customer['phone'];
          $message = $this->clearVars($message);
          $message = strip_tags($message);

          if ($SMSService->sendSMS($message, $phone)) {
            $BookPartee->sentSMS = 2;
            $BookPartee->log_data = $BookPartee->log_data . "," . time() . '-' . 'sentSMS';
            $BookPartee->save();
            return [
                'status' => 'success',
                'response' => "Registro Partee enviado",
            ];
          }
        }

        return [
            'status' => 'danger',
            'response' => $SMSService->response
        ];
      }

      return [
          'status' => 'danger',
          'response' => "El registro Partee aún no está preparado."
      ];
    }

    return [
        'status' => 'danger',
        'response' => "No existe el registro solicitado."
    ];
  }

  public function getParteeLst() {

    $today = Carbon::now();
    $books = \App\Book::where('start', '>=', $today->copy()->subDays(2))
                    ->where('start', '<=', $today->copy()->addDays(5))
                    ->where('type_book', 2)->orderBy('start', 'ASC')->get();

    $payment = array();
    foreach ($books as $key => $book) {
      $payment[$book->id] = 0;
      $payments = \App\Payments::where('book_id', $book->id)->get();
      if (count($payments) > 0)
        foreach ($payments as $key => $pay)
          $payment[$book->id] += $pay->import;
    }

    $rooms = \App\Rooms::where('state', '=', 1)->get();
//          foreach ($rooms as $r){
//            
//          }
//                
    $mobile = new \App\Classes\Mobile();
//          $parteeToActive = BookPartee::whereIn('status', ['HUESPEDES',"FINALIZADO"])->get();
//          $parteeToActive = BookPartee::get();
    return view('backend/planning/_alarmsPartee', compact('books', 'payment', 'rooms', 'mobile', 'today'));
  }

  public function showSendRemember($bookID) {
    $book = Book::find($bookID);
    $disableEmail = 'disabled';
    $disablePhone = 'disabled';
    if ($book) {
      if ($book->customer->email)
        $disableEmail = '';
      if ($book->customer->phone)
        $disablePhone = '';
    }
    $partee = BookPartee::where('book_id', $bookID)->first();
    $showInfo = [];
    if ($partee && $partee->partee_id > 0 && trim($partee->link) != '') {
      $data = $partee->log_data;
      if ($data) {
        preg_match_all('|([0-9])*(\-sentSMS)|', $data, $info);
        if (isset($info[0])) {
          foreach ($info[0] as $t) {
            $showInfo[intval($t)] = '<b>SMS</b> enviado el ' . date('d/m H:i', intval($t));
          }
        }
        preg_match_all('|([0-9])*(\-sentMail)|', $data, $info);
        if (isset($info[0])) {
          foreach ($info[0] as $t) {
            $showInfo[intval($t)] = '<b>Mail</b> enviado el ' . date('d/m H:i', intval($t));
          }
        }
      }
      $link = '<a href="' . $partee->link . '" title="Ir a Partee">' . $partee->link . '</a>';

      $subject = translateSubject('Recordatorio para Completado de Partee', $book->customer->country);
      $message = $this->getMailData($book, 'SMS_Partee_upload_dni');
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
      <button class="sendSMS btn btn-default <?php echo $disablePhone; ?>" title="Enviar Texto partee por SMS" data-id="<?php echo $bookID; ?>">
        <i class="sendSMSIcon"></i>Enviar SMS
      </button>
    </div>
    <div class="col-md-6 minH-4">
      <button class="sendParteeMail btn btn-default <?php echo $disableEmail; ?>" title="Enviar Texto partee por Correo" data-id="<?php echo $bookID; ?>">
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
      <button class="showParteeLink btn btn-default" title="Mostrar link Partee">
        <i class="fa fa-link"></i> Mostart Link
      </button>
    </div>  
    <div id="linkPartee" class="col-xs-12" style="display:none;"><?php echo $link; ?></div>
    <div class="col-md-6 minH-4"> 
      <button class="showParteeData btn btn-default" title="Mostrar Partee" data-partee_id="<?php echo $partee->partee_id; ?>">
        <i class="fa fa-eye"></i> Mostart Partee
      </button>
    </div>
    <div class="col-md-6 minH-4"> 
      <button class="btn btn-default" title="Mostrar Partee" onclick="copyParteeMsg(<?php echo $book->id; ?>, 'cpMsgPartee', 0)">
        <i class="far fa-copy"></i>  Copiar mensaje Partee
      </button>
      <div id="cpMsgPartee"></div>
    </div>
    <?php
    if (count($showInfo)) {
      ksort($showInfo);
      echo '<div class="col-md-12" style="margin-top:3em;" ><b>Histórico:</b><br>';
      echo implode('<br>', $showInfo);
      echo '</div>';
    }
  }

  function sendPartee($bookID) {
    $book = Book::find($bookID);
    if ($book) {
      $return = $book->sendToPartee();
    }
    return back();
  }

  function seeParteeHuespedes($id) {
    $partee = new \App\Services\ParteeService();
    if ($partee->conect()) {
      $partee->getCheckHuespedes($id);
      if ($partee) {
        $obj = $partee->response;
        if (!is_object($obj)) {
          echo 'No se ha encontrado el Registro asociado';
          return;
        }
        ?>
        <h1>Datos Partee</h1>
        <?php if ($obj->borrador): ?>
          <strong class="text-danger">Borrador: no enviado aún a la policia</strong>
        <?php else: ?>
          <strong class="text-success">Enviado a la policia</strong>
        <?php endif; ?>
        <p><b>Creado: </b> <?php echo date('d/m H:i', strtotime($obj->fecha_creacion)); ?>Hrs</p>
        <p><b>Entrada: </b> <?php echo date('d/m H:i', strtotime($obj->fecha_entrada)); ?>Hrs</p> 
        <?php if (isset($obj->checkin_online_url)): ?>
          <p><b>Enlace Checkin: </b> <a href="<?php echo $obj->checkin_online_url; ?>" ><?php echo $obj->checkin_online_url; ?></a></p>
        <?php endif; ?>
        <h3>Viajeros Cargados</h3>
        <?php
        if ($obj->viajeros):
          foreach ($obj->viajeros as $v):
            if ($v->borrador):
              ?>
              <strong>Borrador</strong>
            <?php endif; ?>
            <h4><?php echo $v->nombre_viajero . ' ' . $v->primer_apellido_viajero; ?></h4>
            <p><b>Sexo: </b> <?php echo $v->sexo_viajero; ?></p>
            <p><b>Dias estancia: </b> <?php echo $v->dias_estancia; ?></p>
            <p><b>Entrada: </b> <?php echo $v->fecha_expedicion_documento; ?></p>
            <p><b>Indentificación: </b> <?php echo $v->numero_identificacion . ' - ' . $v->pais_nacimiento_viajero; ?></p>
            <?php
          endforeach;
        else:
          ?>
          <p class="alert alert-warning">No han cargado datos aún</p>
        <?php
        endif;
        return;
      }
    }

    echo '<h1>Partee no encontrado</h1>';
  }

  /**
   * Check the Partee HUESPEDES completed
   */
  public function syncCheckInStatus() {
    $apiPartee = new \App\Services\ParteeService();

    //conect to Partee and get the JWT
    if ($apiPartee->conect()) {

      $today = Carbon::now();
      $books = \App\Book::where('start', '>=', $today->copy()->subDays(2))
                      ->where('start', '<=', $today->copy()->addDays(5))
                      ->where('type_book', 2)->orderBy('start', 'ASC')->get();


      if ($books) {
        foreach ($books as $Book) {
          //Read a $BookPartee            
          try {
            $BookPartee = $Book->partee();
            if ($BookPartee) {
              $partee_id = $BookPartee->partee_id;
              //check Partee status
              $result = $apiPartee->getCheckStatus($partee_id);
              if (isset($apiPartee->responseCode) && $apiPartee->responseCode == 200) {
                if ($apiPartee->response && $apiPartee->response != $apiPartee->response->status) {
                  //Save the new status
                  $log = $BookPartee->log_data . "," . time() . '-' . $apiPartee->response->status;
                  $BookPartee->status = $apiPartee->response->status;
                  $BookPartee->log_data = $log;
                  $BookPartee->has_checked = 1;
                  if ($apiPartee->response->status == 'HUESPEDES') {
                    $BookPartee->guestNumber = $apiPartee->response->guestNumber;
                    $BookPartee->date_complete = date('Y-m-d H:i:s');
                  }
                  if ($apiPartee->response->status == 'FINALIZADO') {
                    $BookPartee->date_finish = date('Y-m-d H:i:s');
                  }
                  $BookPartee->save();
                }
              } else {
                if (isset($apiPartee->responseCode) && $apiPartee->responseCode == 404) {
                  $log = $BookPartee->log_data . "," . time() . '-NotFound ' . $BookPartee->partee_id;
                  $BookPartee->partee_id = -1;
                  $BookPartee->save();
                }
              }
            }
          } catch (\Exception $e) {
            Log::error("Error CheckIn Partee " . $BookPartee->id . ". Error  message => " . $e->getMessage());
            continue;
          }
        }
      }
    }

    return redirect('/admin/reservas');
  }

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

  /*   * ************************************************** */

  public function updSafetyBox($bookID, $value, $min = false) {

    if ($value == -1) {
      $oObject = \App\BookSafetyBox::where('book_id', $bookID)
              ->update(['deleted' => 1, 'key' => -1]);
      if ($min)
        return 'ok';
      return $this->showSafetyBox($bookID);
    }

    $book = Book::find($bookID);
    if ($book) {
      $otherBooks = Book::where('start', $book->start)
                      ->join('book_safety_boxes', 'book_id', '=', 'book.id')
                      ->where('key', $value)->count();
      if ($otherBooks > 0) {
        return 'overlap';
      }
    }

    $oObject = \App\BookSafetyBox::where('book_id', $bookID)->first();
    if ($oObject) {
      $oObject->key = $value;
      $oObject->deleted = null;
      $oObject->save();
    } else {
      $oObject = new \App\BookSafetyBox();
      $oObject->book_id = $bookID;
      $oObject->key = $value;
      $oObject->save();
    }
    if ($min)
      return 'ok';
    return $this->showSafetyBox($bookID);
  }

  public function showSafetyBox($bookID) {
    $book = Book::find($bookID);
    $disableEmail = 'disabled';
    $disablePhone = 'disabled';
    $messageSMS = null;
    $caja = null;
    if ($book) {
      if ($book->customer->email)
        $disableEmail = '';
      if ($book->customer->phone)
        $disablePhone = '';
    }
    $oObject = \App\BookSafetyBox::where('book_id', $bookID)->first();
    $showInfo = [];

    $used = [];
    if ($book) {
      $used = Book::select('book_safety_boxes.key')->where('start', $book->start)
                      ->join('book_safety_boxes', 'book_id', '=', 'book.id')->pluck('book_safety_boxes.key')->all();
    }


    if ($oObject && !$oObject->deleted) {
      $data = $oObject->log;
      $caja = $oObject->key;
      if ($data) {
        preg_match_all('|([0-9])*(\-sentSMS)|', $data, $info);
        if (isset($info[0])) {
          foreach ($info[0] as $t) {
            $showInfo[intval($t)] = '<b>SMS</b> enviado el ' . date('d/m H:i', intval($t));
          }
        }
        preg_match_all('|([0-9])*(\-sentMail)|', $data, $info);
        if (isset($info[0])) {
          foreach ($info[0] as $t) {
            $showInfo[intval($t)] = '<b>Mail</b> enviado el ' . date('d/m H:i', intval($t));
          }
        }
      }
//            $subject = translateSubject('Recordatorio de Datos del buzón',$book->customer->country);
//            $message = $this->getMailData($book,'book_email_buzon');
//            $message = $this->clearVars($message);
      $messageSMS = $this->getMailData($book, 'SMS_buzon');
      $messageSMS = str_replace('{buzon}', $oObject->getBuzon(), $messageSMS);
      $messageSMS = str_replace('{buzon_color}', $oObject->getBuzonColor(), $messageSMS);
      $messageSMS = str_replace('{buzon_caja}', $oObject->getBuzonCaja(), $messageSMS);
      $messageSMS = str_replace('{buzon_key}', $oObject->getKey(), $messageSMS);
      $messageSMS = $this->clearVars($messageSMS);
    }
    ?>
    <div class="col-md-5 mb-1em">
      <b><?php echo $book->customer->name; ?></b>
    </div>
    <div class="col-md-3 mb-1em">
      <b>Apto: </b><?php echo $book->room->nameRoom; ?>
    </div>
    <div class="col-md-4 mb-1em">
      <b>Check-in: </b><?php echo dateMin($book->start) . ' - ' . $book->schedule . ' Hrs'; ?>
    </div>
    <div class="col-md-12 mb-1em"><br/></div>
    <?php
    if (!is_null($messageSMS)):
      ?>
      <input type="hidden" value="<?php echo csrf_token(); ?>" id="buzon_csrf_token">
      <div class="col-xs-6 minH-4">
        <button class="sendBuzonSMS btn btn-default <?php echo $disablePhone; ?>" title="Enviar Texto Buzón por SMS" data-id="<?php echo $bookID; ?>">
          <i class="sendSMSIcon"></i>Enviar SMS
        </button>
      </div>
      <div class="col-xs-6 minH-4">
        <button class="sendBuzonMail btn btn-default <?php echo $disableEmail; ?>" title="Enviar Texto Buzón por Correo" data-id="<?php echo $bookID; ?>">
          <i class="fa fa-inbox"></i> Enviar Email
        </button>
      </div>
      <div class="col-xs-6 minH-4 mb-1em">
        <a href="whatsapp://send?text=<?php echo $messageSMS; ?>"
           data-action="share/whatsapp/share"
           data-original-title="Enviar Buzon link"
           data-toggle="tooltip"
           class="btn btn-default <?php echo $disablePhone; ?>">
          <i class="fa  fa-whatsapp" aria-hidden="true" style="color: #000; margin-right: 7px;"></i>Enviar Whatsapp
        </a>
      </div>
      <div class="col-xs-6 minH-4 mb-1em"> 
        <button class="btn btn-default" title="Copiar mensaje Buzon" onclick="copyBuzonMsg(<?php echo $book->id; ?>, 'cpMsgBuzon', 0)">
          <i class="far fa-copy"></i>  Copiar mensaje Buzon
        </button>
        <div id="cpMsgBuzon"></div>
      </div>
    <?php endif; ?>
    <div class="col-md-6 minH-4 mb-1em" style="padding-right: 2em;overflow: auto;"> 
      <label>Caja Asignada</label>
      <select id="change_CajaAsig" class="form-control" data-id="<?php echo $book->id; ?>">
        <option value="-1"> -- </option>
    <?php
    $lst = \App\BookSafetyBox::$keys_name;
    foreach ($lst as $k => $v) {
      $selected = ($caja == $k) ? 'selected' : '';
      $disabled = (in_array($k, $used) && $caja != $k) ? 'disabled' : '';
      echo '<option value="' . $k . '" ' . $selected . ' ' . $disabled . '>' . $v . '</option>';
    }
    ?>
      </select>
    </div>
    <?php
    if (count($showInfo)) {
      ksort($showInfo);
      echo '<div class="col-md-6" ><b>Histórico:</b><br>';
      echo implode('<br>', $showInfo);
      echo '</div>';
    }
  }

  /**
   * Get the custom message to Cajas
   */
  public function getSafetyBoxMsg($bookID) {
    //Get Book object
    $book = Book::find($bookID);

    if (!$book) {
      die('empty');
    }
    //get BookSafetyBox object
    $oObject = \App\BookSafetyBox::where('book_id', $bookID)->first();
    if ($oObject) {
      //Get msg content
      $content = $this->getMailData($book, 'SMS_buzon');
      $content = str_replace('{buzon}', $oObject->getBuzon(), $content);
      $content = str_replace('{buzon_key}', $oObject->getKey(), $content);
      $content = str_replace('{buzon_color}', $oObject->getBuzonColor(), $content);
      $content = str_replace('{buzon_caja}', $oObject->getBuzonCaja(), $content);
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
  public function sendSafetyBoxSMS(Request $request) {

    $bookID = $request->input('id', null);

    //Get Book object
    $book = Book::find($bookID);
    if (!$book) {
      return [
          'status' => 'danger',
          'response' => "Reserva no encontrada."
      ];
    }

    //get BookSafetyBox object
    $oObject = \App\BookSafetyBox::where('book_id', $bookID)->first();
    if ($oObject) {
      //Send SMS
      $SMSService = new \App\Services\SMSService();
      if ($SMSService->conect()) {

        $messageSMS = $this->getMailData($book, 'SMS_buzon');
        $messageSMS = str_replace('{buzon}', $oObject->getBuzon(), $messageSMS);
        $messageSMS = str_replace('{buzon_key}', $oObject->getKey(), $messageSMS);
        $messageSMS = str_replace('{buzon_color}', $oObject->getBuzonColor(), $messageSMS);
        $messageSMS = str_replace('{buzon_caja}', $oObject->getBuzonCaja(), $messageSMS);
        $messageSMS = $this->clearVars($messageSMS);
        $message = strip_tags($messageSMS);
        if ($SMSService->sendSMS($message, $phone)) {
          $oObject->log = $oObject->log . "," . time() . '-' . 'sentSMS';
          $oObject->save();
          return [
              'status' => 'success',
              'response' => "Registro enviado",
          ];
        }
      }

      return [
          'status' => 'danger',
          'response' => $SMSService->response
      ];
    }

    return [
        'status' => 'danger',
        'response' => "El registro aún no está preparado."
    ];
  }

  /**
   * Send Partee to Finish CheckIn
   * 
   * @param Request $request
   * @return type
   */
  public function sendSafetyBoxMail(Request $request) {

    $bookID = $request->input('id', null);

    //Get Book object
    $book = Book::find($bookID);
    if (!$book) {
      return [
          'status' => 'danger',
          'response' => "Reserva no encontrada."
      ];
    }
    if (!$book->customer->email || trim($book->customer->email) == '') {
      return [
          'status' => 'danger',
          'response' => "La Reserva no posee email."
      ];
    }
    //get BookSafetyBox object
    $oObject = \App\BookSafetyBox::where('book_id', $bookID)->first();
    if ($oObject) {
      $subject = translateSubject('Recordatorio para retiro de llaves', $book->customer->country);
      $message = $this->getMailData($book, 'book_email_buzon');
      $message = str_replace('{buzon}', $oObject->getBuzon(), $message);
      $message = str_replace('{buzon_key}', $oObject->getKey(), $message);
      $message = str_replace('{buzon_color}', $oObject->getBuzonColor(), $message);
      $message = str_replace('{buzon_caja}', $oObject->getBuzonCaja(), $message);
      $message = $this->clearVars($message);

      $sended = Mail::send('backend.emails.base', [
                  'mailContent' => $message,
                  'title' => $subject
                      ], function ($message) use ($book, $subject) {
                $message->from(config('mail.from.address'));
                $message->to($book->customer->email);
                $message->subject($subject);
                $message->replyTo(config('mail.from.address'));
              });

      \App\BookLogs::saveLog($book->id, $book->room_id, $book->customer->email, 'book_email_buzon', $subject, $message);
      if ($sended) {
        $oObject->log = $oObject->log . "," . time() . '-' . 'sentMail';
        $oObject->save();
        return [
            'status' => 'success',
            'response' => "Registro enviado",
        ];
      }
    }

    return [
        'status' => 'danger',
        'response' => "El registro aún no está preparado."
    ];
  }

  public function getSafetyBoxLst() {

    $today = Carbon::now();
    $books = \App\Book::select('book.*', 'book_safety_boxes.key')
                    ->join('book_safety_boxes', 'book_id', '=', 'book.id')
                    ->where('start', '>=', $today->copy()->subDays(3))
                    ->whereNull('deleted')
                    ->whereYear('start', '=', $today->copy()->format('Y'))
                    ->orderBy('start', 'ASC')->get();
    $safety_boxes = \App\BookSafetyBox::$keys_name;
    $isMobile = config('app.is_mobile');
    $today = $today->format('Y-m-d');
    return view('backend/planning/_safety-boxs', compact('books', 'safety_boxes', 'isMobile', 'today'));
  }

  public function getCustomersRequestLst() {

    $items = \App\CustomersRequest::where('status', 0)
            ->where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))
            ->get();

    $isMobile = config('app.is_mobile');
    return view('backend/planning/_customers-request', compact('items', 'isMobile'));
  }

  public function hideCustomersRequest(Request $request) {

    $ID = $request->input('id', null);

    $items = \App\CustomersRequest::where('id', $ID)->update(['status' => 1]);

    return 'ok';
  }

}
