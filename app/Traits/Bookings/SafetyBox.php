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

trait SafetyBox {

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
                      ->where('box_id', $value)->count();
      if ($otherBooks > 0) {
        return 'overlap';
      }
    }

    $oObject = \App\BookSafetyBox::where('book_id', $bookID)->first();
    if ($oObject) {
      $oObject->box_id = $value;
      $oObject->deleted = null;
      $oObject->save();
    } else {
      $oObject = new \App\BookSafetyBox();
      $oObject->book_id = $bookID;
      $oObject->box_id = $value;
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
          $messageSMS   = null;
          $oObject   = null;
          $caja = null;
          if ($book){
            if ($book->customer->email) $disableEmail = '';
            if ($book->customer->phone) $disablePhone = '';
          }
          $SafetyBox = new \App\SafetyBox();
          
          $current = $SafetyBox->getByBook($book->id);
          $showInfo = [];
          $used = [];
          $lstBoxs = $SafetyBox->get(); 
          if ($book){
            foreach ($lstBoxs as $box){
              if ($SafetyBox->usedBy_day($box->id,$book->start,$book->finish)){
                $used[] = $box->id;
              }
            }
          }
      
          if ($current && !$current->deleted){
            $data = $current->log;
            $caja = $current->id;
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

            $messageSMS = $this->getSafeBoxMensaje($book,'book_email_buzon',$current);
            $whatsapp = str_replace('&nbsp;', ' ', $messageSMS);
            $whatsapp = str_replace('<strong>', '*', $whatsapp);
            $whatsapp = str_replace('</strong>', '*', $whatsapp);
            $whatsapp = str_replace('<br />', '%0D%0A', $whatsapp);
            $whatsapp = str_replace('</p>', '%0D%0A', $whatsapp);
            $whatsapp = strip_tags($whatsapp);
            $messageSMS = html_entity_decode(str_replace ("<br />", "\r\n", $whatsapp));
          } 
          
        ?>
  <p class="alert alert-warning">Los buzones se asignan de manera automática a las 13Hrs de cada día (check-in)</p>
        <div class="col-md-5 mb-1em">
          <b><?php echo $book->customer->name; ?></b>
        </div>
        <div class="col-md-3 mb-1em">
          <b>Apto: </b><?php echo $book->room->nameRoom; ?>
        </div>
        <div class="col-md-4 mb-1em">
          <b>Check-in: </b><?php echo dateMin($book->start).' - '.$book->schedule.' Hrs'; ?>
        </div>
        <div class="col-md-12 mb-1em"><br/></div>
        <?php
        if(!is_null($messageSMS)):
          ?>
        <input type="hidden" value="<?php echo csrf_token(); ?>" id="buzon_csrf_token">
        <div class="col-xs-6 minH-4">
          <button class="sendBuzonSMS btn btn-default <?php echo $disablePhone;?>" title="Enviar Texto Buzón por SMS" data-id="<?php echo $bookID;?>">
            <i class="sendSMSIcon"></i>Enviar SMS
          </button>
        </div>
        <div class="col-xs-6 minH-4">
          <button class="sendBuzonMail btn btn-default <?php echo $disableEmail;?>" title="Enviar Texto Buzón por Correo" data-id="<?php echo $bookID;?>">
            <i class="fa fa-inbox"></i> Enviar Email
          </button>
        </div>
        <div class="col-md-6 col-xs-12 minH-4 mb-1em">
          <a href="whatsapp://send?text=<?php echo $whatsapp; ?>"
                 data-action="share/whatsapp/share"
                 data-original-title="Enviar Buzon link"
                 data-toggle="tooltip"
                 class="btn btn-default <?php echo $disablePhone;?>">
            <i class="fa  fa-whatsapp" aria-hidden="true" style="color: #000; margin-right: 7px;"></i>Enviar Whatsapp
              </a>
        </div>
        <div class="col-md-6 col-xs-12 minH-4 mb-1em"> 
          <button class="btn btn-default" title="Copiar mensaje Buzon" onclick="copyBuzonMsg(<?php echo $book->id; ?>,'cpMsgBuzon',0)">
            <i class="far fa-copy"></i>  Copiar mensaje Buzon
          </button>
          <div id="cpMsgBuzon"></div>
        </div>
        <?php endif; ?>
        <div class="col-md-6 col-xs-12 minH-4 mb-1em" style="padding-right: 2em;overflow: auto;"> 
          <label>Caja Asignada</label>
          <select id="change_CajaAsig" class="form-control" data-id="<?php echo $book->id; ?>">
            <option value="-1"> -- </option>
            <?php 
            
            foreach ($lstBoxs as $box){
              $selected = ($caja == $box->id) ? 'selected': ''; 
              $disabled =  (in_array($box->id, $used) && $caja != $box->id) ? 'disabled': ''; 
              echo '<option value="'.$box->id.'" '.$selected.' '.$disabled.'>'.$box->box_name.'</option>';
            }
            ?>
          </select>
        </div>
        
          <?php if($caja){ ?>
          <div class="safebox_msgSuccess col-xs-12">
            <p class="text-danger">OK CAJA ASIGNADA:</p>
            El cliente recibirá por email los codigos el dia del checkin a las 14h<br/>
            (Ademas se lo puedes enviar tu manualmente en cualquier momento)
          </div>
          <?php } ?>
        
          <?php
           if (count($showInfo)){
            ksort($showInfo);
            echo '<div class="col-xs-12" ><b>Histórico:</b><br>';
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
//    $oObject = \App\BookSafetyBox::where('book_id', $bookID)->first();

    $SafetyBox = new \App\SafetyBox();
    $oObject = $SafetyBox->getByBook($bookID);
    if ($oObject) {
              
      //Get msg content
      $content = $this->getSafeBoxMensaje($book,'book_email_buzon',$oObject);
      $content = html_entity_decode($content);
      $content = strip_tags($content,'<b><br><strong>');
      $content = str_replace('<strong>', '*', $content);
      $content = (str_replace('</strong>', '*', $content));
      $content = str_replace('&nbsp;', ' ', $content);

//      $whatsapp = str_replace('<br />', '%0D%0A', $content);
//      $whatsapp = str_replace('</p>', '%0D%0A', $whatsapp);
//      $whatsapp = strip_tags($whatsapp);
            
      die($content);
    } else {
      die('empty');
    }
  }

  public function editSafetyBox() {
    $roomList = \App\Rooms::orderBy('nameRoom')->pluck('nameRoom','id');
    $lst = \App\SafetyBox::all();
    $canEdit = \App\SafetyBox::canEdit();
    if ($lst) {
      ?>
      <table class="table">
        <tr>
          <th>Caja</th>
          <th class="text-center">Hab.</th>
          <th class="text-center">Clave</th>
        </tr>
        <?php
        foreach ($lst as $sb):
          ?>
          <tr>
            <td><?php echo $sb->box_name; ?></td>
            <?php if ($canEdit): ?>
              <td>
                <select data-id="<?php echo $sb->id; ?>" data-field="room" class="form-control editSafeBox">
                  <option> -- </option>
                  <?php
                  foreach ($roomList as $k => $v) {
                    $selected = ($sb->room_id == $k) ? 'selected' : '';
                    echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
                  }
                  ?>
                </select>
              </td>
              <td>
                <input data-id="<?php echo $sb->id; ?>" data-field="key" class="form-control editSafeBox" value="<?php echo $sb->keybox; ?>">
              </td>
            <?php else: ?>
              <td><?php echo show_isset($roomList, $sb->room_id); ?></td>
              <td><?php echo $sb->keybox; ?></td>
            <?php endif; ?>
          </tr>
          <?php
        endforeach;
        ?>
      </table>
      <?php
    }
    if ($canEdit) {
      ?>
      <div class="box-new">
        <h3>Crear nueva caja de seguridad</h3>
        <div class="row">
          <div class="col-md-3 col-xs-6 mb-1em">
            <label>Caja</label>
            <input id="safetyBox_caja" class="form-control">
          </div>
          <div class="col-md-3 col-xs-6 mb-1em">
            <label>Color</label>
            <input id="safetyBox_color" class="form-control">
          </div>
          <div class="col-md-3 col-xs-6 mb-1em">
            <label>Habitación</label>
            <select id="safetyBox_room" class="form-control">
              <option> -- </option>
              <?php
              foreach ($roomList as $k => $v) {
                echo '<option value="' . $k . '">' . $v . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-3 col-xs-6 mb-1em">
            <label>Clave</label>
            <input id="safetyBox_clave" class="form-control">
          </div>
          <div class="col-md-3 col-xs-6 mb-1em">
            <input type="button" id="createSafetyBox" class="btn btn-success" value="Crear">
          </div>
        </div>
      </div>
      <?php
    }
  }

  public function updKeySafetyBox(Request $request) {

    if (!\App\SafetyBox::canEdit())
      return 'No tienes permiso para la tarea solicitada';
    $id = $request->input('id', null);
    $val = $request->input('val', null);
    $field = $request->input('field', null);

    if (!$id || !$val)
      return 'Caja no encontrada';

    $SafetyBox = \App\SafetyBox::find($id);
    if (!$SafetyBox || $SafetyBox->id != $id)
      return 'Caja no encontrada';

    if ($field == "room")
      $SafetyBox->room_id = $val;
    if ($field == "key")
      $SafetyBox->keybox = $val;
    if ($SafetyBox->save()) {
      return 'OK';
    }
    return 'No se pudo actualizar la clave de la Caja de seguridad';
  }

  public function createSafetyBox(Request $request) {

    if (!\App\SafetyBox::canEdit())
      return 'No tienes permiso para la tarea solicitada';

    $caja = $request->input('caja', null);
    $color = $request->input('color', null);
    $clave = $request->input('clave', null);
    $room_id = $request->input('room_id', null);
    if (!$caja || !$clave )
      return 'Debe ingresar todos los campos';

    $SafetyBox = new \App\SafetyBox();
    $SafetyBox->keybox = $clave;
    $SafetyBox->color = $color;
    $SafetyBox->caja = $caja;
    $SafetyBox->room_id = $room_id;
    if ($SafetyBox->save()) {
      return 'OK';
    }
    return 'No se pudo crear la Caja de seguridad';
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
    $SafetyBox = new \App\SafetyBox();
    $oObject = $SafetyBox->getByBook($bookID);
    $BookSafetyBox = \App\BookSafetyBox::where('book_id', $bookID)->first();
    if ($oObject) {
      //Send SMS
      $SMSService = new \App\Services\SMSService();
      if ($SMSService->conect()) {
        $messageSMS = $this->getSafeBoxMensaje($book,'SMS_buzon',$oObject);
        $message = strip_tags($messageSMS);
        if ($SMSService->sendSMS($message, $phone)) {
          $BookSafetyBox->log = $BookSafetyBox->log . "," . time() . '-' . 'sentSMS';
          $BookSafetyBox->save();
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
    $SafetyBox = new \App\SafetyBox();
    $oObject = $SafetyBox->getByBook($bookID);
    $BookSafetyBox = \App\BookSafetyBox::where('book_id', $bookID)->first();
    if ($oObject) {
      $sended = $this->sendSafeBoxMensaje($book, 'book_email_buzon', $oObject);
      if ($sended) {
        $BookSafetyBox->log = $BookSafetyBox->log . "," . time() . '-' . 'sentMail';
        $BookSafetyBox->save();
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
    $books = \App\Book::select('book.*', 'book_safety_boxes.box_id')
                    ->join('book_safety_boxes', 'book_id', '=', 'book.id')
                    ->where('start', '>=', $today->copy()->subDays(3))
                    ->whereNull('deleted')
                    ->whereYear('start', '=', $today->copy()->format('Y'))
                    ->orderBy('start', 'ASC')->get();
    $safety_boxes = \App\SafetyBox::all();
    $keys = $boxs = [];
    foreach ($safety_boxes as $sb) {
      $keys[$sb->id] = $sb->keybox;
      $boxs[$sb->id] = $sb->caja;
    }
    $isMobile = config('app.is_mobile');
    $today = $today->format('Y-m-d');
    return view('backend/planning/_safety-boxs', compact('books', 'keys', 'boxs', 'isMobile', 'today'));
  }

}
