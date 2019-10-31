<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forfaits\ForfaitsItem;
use App\Models\Forfaits\ForfaitsUser;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use App\Models\Forfaits\ForfaitsOrders;
use App\Models\Forfaits\ForfaitsOrderItem;
use App\Models\Forfaits\ForfaitsOrderPayments;
use Auth;
use App\Book;
use App\Traits\ForfaitsPaymentsTraits;
use Illuminate\Support\Facades\DB;

class ForfaitsItemController extends AppController {

  use ForfaitsPaymentsTraits;

  public function index($class = null) {
    $all = ForfaitsItem::all();
    $items = [];
    foreach ($all as $item) {
      if (!isset($items[$item->cat])) {
        $items[$item->cat] = [];
      }
      $items[$item->cat][] = $item;
    }
    if ($class) {
      if (isset($items[$class])) {
        $items = array($class => $items[$class]);
      }
    }

    $catMat = ForfaitsItem::getCategories();
    $catClass = ForfaitsItem::getClasses();

    return view('backend/forfaits/index', [
        'selClass' => $class,
        'items' => $items,
        'categ' => array_merge($catMat, $catClass),
    ]);
  }
  
  public function getBalance() {
    $curl = curl_init();
    $endpoint = env('FORFAIT_ENDPOINT') . 'getbalance';
    $Bearer = env('FORFAIT_TOKEN');
    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $Bearer"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
    echo "cURL Error #:" . $err;
    } else {
    return json_decode($response);
    }
  }

  public function edit($id) {
    $item = ForfaitsItem::find($id);
    $categ = $item->getCategory();
    return response()->json(['item' => $item, 'cat' => $categ]);
  }

  public function update(Request $req) {

    $id = $req->input('item_id', null);
    if ($id) {
      $obj = ForfaitsItem::find($id);
      $obj->name = $req->input('item_nombre', null);
      $obj->type = $req->input('item_tipo', null);
      $obj->equip = $req->input('item_equip', null);
      $obj->class = $req->input('item_class', null);
      $obj->status = $req->input('item_status', null);
      $obj->status = $req->input('item_status', null);
      $obj->regular_price = $req->input('regular_price', null);
      $obj->special_price = $req->input('special_price', null);
      $obj->hour_start = $req->input('hour_start', null);
      $obj->hour_end = $req->input('hour_end', null);
      $obj->save();
      return redirect()->back()->with('success', 'Forfaits Guardado');
    }
    return redirect()->back()->withErrors(['Forfaits no encontrado']);
  }

  public function loadComment(Request $req) {

    $id = $req->input('item_id', null);
    if ($id) {
      $obj = ForfaitsUser::find($id);
      $obj->more_info = $req->input('more_info', null);
      $obj->save();
      return redirect()->back()->with('success', 'Comentario Guardado');
    }
    return redirect()->back()->withErrors(['Forfaits no encontrado']);
  }

  public function getBookingFF($bookingID) {



    return $response;
  }

  /*   * **************************** */
  /*   * *****       API      ****** */
  /*   * ************************** */

  public function api_getClasses() {
    return response()->json(ForfaitsItem::getClasses());
  }

  public function api_getCategories() {
    return response()->json(ForfaitsItem::getCategories());
  }

  public function api_items($cat) {
    $items = ForfaitsItem::where('cat', $cat)->where('status', 1)->get();
    return response()->json($items);
  }

  /**
   * Get the Forfaits with the Insurances
   */
  public function getInsurances(Request $req) {

    $days = $req->input('days',7);
    $data = array(
        'days' => $days,
        'skiResortId' => env('FORFAIT_RESORTID')
    );
    $json = json_encode($data);
    $curl = curl_init();
    $endpoint = env('FORFAIT_ENDPOINT') . 'getinsurances';
    $Bearer = env('FORFAIT_TOKEN');
    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $Bearer"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return json_encode(['success' => false, 'data' => ['message' => $err]]);
    } else {
      return $response;
    }

  }
  public function getForfaitUser($forfaitLst,$usr_Insur,$isFamily=false) {

    $data = array(
        'forfaits' => $forfaitLst,
        'familyFormula' => $isFamily,
        'skiResortId' => env('FORFAIT_RESORTID')
    );
    
    if (count($usr_Insur)){
      $data['extras'] = array("insurances"=>$usr_Insur);
    }
    
    $json = json_encode($data);
    $curl = curl_init();
    $endpoint = env('FORFAIT_ENDPOINT') . 'getprices';
    $Bearer = env('FORFAIT_TOKEN');
    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $Bearer"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return json_encode(['success' => false, 'data' => ['message' => $err]]);
    } else {
      return $response;
    }
  }

  public function getForfaitSeasons() {
    $skiResortId = env('FORFAIT_RESORTID');
    $curl = curl_init();
    $endpoint = env('FORFAIT_ENDPOINT') . 'getseasons';
    $Bearer = env('FORFAIT_TOKEN');
    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $Bearer"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if (!$err) {
      $list = json_decode($response);

      if (isset($list->success) && $list->success) {
        foreach ($list->data->seasons as $lst) {
          if ($lst->skiResortId == $skiResortId) {
            $start = explode('/', $lst->startDate);
            $end = explode('/', $lst->endDate);
            return [
                'startDate' => $start[2] . '-' . $start[1] . '-' . $start[0],
                'endDate' => $end[2] . '-' . $end[1] . '-' . $end[0],
            ];
          }
        }
      }
    }
    return ['startDate' => null, 'endDate' => null];
  }

  private function getDays($start, $end) {
    $d_start = strtotime($start);
    $d_end = strtotime($end);
    $monToFr = 0;
    $satOrSun = 0;

    for ($d_start; $d_start <= $d_end; $d_start = strtotime('+1 day ' . date('Y-m-d', $d_start))) {
      $day = date('N', $d_start);
      if ($day < 6) {
        $monToFr++;
      } else {
        $satOrSun++;
      }
    }
    return ['monToFr' => $monToFr, 'satOrSun' => $satOrSun];
  }

  public function getCurrentCart($bookingID, $clientID) {
    $bookingID = desencriptID($bookingID);
    $clientID = desencriptID($clientID);
    if (is_numeric($bookingID) && is_numeric($clientID)) {
      $order = ForfaitsOrders::getByBook($bookingID);
      return $this->getCart($order);
    }
    return response()->json(['status' => 'error']);
  }

  /**
   * 
   * @param Request $req
   * @param type $returnJson
   * @return type
   */
  public function saveCart(Request $req, $returnJson = true) {
    $type = $req->input('type', null);
    $data = $req->input('data', null);
//    $id = $req->input('ID', null);

    $key = $req->input('key', null);
    $order = ForfaitsOrders::getByKey($key);
    if (!$order) {
      return die('404');
    }
    $orderID = $order->id;

//    var_dump($type,$data,$id); die;
    $error = '';
    switch ($type) {
      case 'forfaits':
        $error = $this->saveForfait($data, $orderID);
        break;
      case 'material':
        $error = $this->saveMaterial($data, $orderID);
        break;
      case 'classes':
        $error = $this->saveclass($data, $orderID);
        break;
    }
    $order->recalculate();
    $cart = $this->getCart($order);
    switch ($type) {
      case 'forfaits':
        $cart['error_forfait'] = $error;
        break;
      case 'classes':
        $cart['error_classes'] = $error;
        break;
    }
    if ($returnJson)
      return response()->json($cart);

    return $cart;
  }

  /**
   * 
   * @param Request $req
   * @param type $returnJson
   * @return type
   */
  public function cancelItem(Request $req, $returnJson = true) {
    $type = $req->input('type', null);
    $data = $req->input('data', null);
    $key = $req->input('key', null);
    $order = ForfaitsOrders::getByKey($key);
    if (!$order)
      return response()->json(['status' => 'error']);

    if ($type == 'forfait') {
      $item = ForfaitsOrderItem::find($data);
      if ($item->order_id == $order->id) {
        if ($item->ffexpr_status){
          $this->sendEmailCancelForfait($order,$item);
        }
        $item->cancel = 1;
        $item->save();
      }
    } else {
      if (isset($data['item']) && isset($data['item']['id'])) {
        $itemID = $data['item']['id'];
        $itemkey = $data['item']['item_key'];
        $item = ForfaitsOrderItem::find($itemID);
        if ($item && $item->order_id == $order->id) {
          $itemData = json_decode($item->data);
          //        var_dump($itemData);die;
          if ($itemData->item->item_key == $itemkey) {
            $item->cancel = 1;
            $item->save();
          }
        }
      }
    }

    $order->recalculate();
    $cart = $this->getCart($order);
    if ($returnJson)
      return response()->json($cart);

    return $cart;
  }

  /**
   * 
   * @param type $users
   * @param type $orderID
   * @return string
   */
  private function saveclass($class, $orderID) {
    $error = null;
    if (isset($class['item']) && isset($class['item']['item_key'])) {

      $itemKey = $class['item']['item_key'];
      $nro = isset($class['nro']) ? intval($class['nro']) : 1;
      $objIten = ForfaitsItem::where('item_key', $itemKey)->first();
      if ($objIten) {
        if ($objIten->cat == 'grupal_class') {
          $days = $this->getDays($class['date_start'], $class['date_end']);

          if (isset($days['monToFr']) && isset($days['monToFr'])) {
            $price = ( $days['monToFr'] * $objIten->regular_price ) + ( $days['satOrSun'] * $objIten->special_price );
            $price = $price * $nro;
          }
        } else {
          $hours = isset($class['hours']) ? intval($class['hours']) : 1;
          $day = date('N', strtotime($class['date_start']));
          if ($day > 0 && $day < 8) {
            if ($day < 6) {
              $price = $objIten->regular_price;
            } else {
              $price = $objIten->special_price;
            }
            $price = $price * $hours * $nro;
          }
        }

        $item = new ForfaitsOrderItem();
        $item->order_id = $orderID;
        $item->type = 'class';
        $item->data = json_encode($class);
        $item->total = $price;
        $item->status = 'new';
        $item->save();
      } else {
        $error = 'Item no encontrado';
      }
    } else {
      $error = 'Item no encontrado';
    }

    return $error;
  }

  /**
   * 
   * @param type $material
   * @param type $orderID
   */
  private function saveMaterial($material, $orderID) {
    //material
    $error = '';
    if (isset($material['item']) && isset($material['item']['item_key'])) {

      $itemKey = $material['item']['item_key'];
      $objIten = ForfaitsItem::where('item_key', $itemKey)->first();
      if ($objIten) {

        $nro = isset($material['nro']) ? intval($material['nro']) : 1;
        $days = $this->getDays($material['date_start'], $material['date_end']);

        if (isset($days['monToFr']) && isset($days['monToFr'])) {
          $price = ( $days['monToFr'] * $objIten->regular_price ) + ( $days['satOrSun'] * $objIten->special_price );
          $price = $price * $nro;
          $item = new ForfaitsOrderItem();
          $item->order_id = $orderID;
          $item->type = 'material';
          $item->data = json_encode($material);
          $item->total = $price;
          $item->status = 'new';
          $item->save();
        }
      } else {
        $error = 'Item no encontrado';
      }
    } else {
      $error = 'Item no encontrado';
    }

    return $error;
  }

  private function saveForfait($users, $orderID) {
    $error = null;
    $usr_forfaits = [];
    $usr_Insur = [];
    $isFamily = 0; // min 3 items
    if ($users) {
      foreach ($users as $usr) {
        $familyFormula = isset($usr['family']) ? $usr['family'] : false;
        if ($familyFormula){
          $isFamily++;
        }
        $usr_forfaits[] = [
            "age" => $usr['years'],
            "dateFrom" => date('d/m/Y', strtotime($usr['date_start'])),
            "dateTo" => date('d/m/Y', strtotime($usr['date_end'])),
            "familyFormula" => $familyFormula,
        ];
        if ($usr['insurance']){
          $usr_Insur[] = [
              'insuranceId' => $usr['insurance'],
              'clientDni' => $usr['dni'],
              'clientName' => $usr['name'],
              "dateFrom" => date('d/m/Y', strtotime($usr['date_start'])),
              "dateTo" => date('d/m/Y', strtotime($usr['date_end'])),
          ];
        }
        
      }
      
      $priceWithoutDisc = null;
      if ($isFamily>2){
        $forfaitsObj = $this->getForfaitUser($usr_forfaits,$usr_Insur,true);
        $forfaitsObj = json_decode($forfaitsObj);
        if (isset($forfaitsObj->success)) {
          $forfaitsObjWithoutDiscount = $this->getForfaitUser($usr_forfaits,$usr_Insur);
          $forfaitsObjAux = json_decode($forfaitsObjWithoutDiscount);
          if (isset($forfaitsObjAux->success) && isset($forfaitsObjAux->data->totalPrice)) 
            $priceWithoutDisc = floatval(str_replace(',','',$forfaitsObjAux->data->totalPrice));
        }
      } else {
        //clear al familyFormula
//        foreach ($usr_forfaits as $k=>$v) $usr_forfaits[$k]["familyFormula"] = false;
        
        $forfaitsObj = $this->getForfaitUser($usr_forfaits,$usr_Insur);
        $forfaitsObj = json_decode($forfaitsObj);
      }
      
      if (isset($forfaitsObj->success)) {
        if ($forfaitsObj->success && !isset($forfaitsObj->data->errors)) {
          $forfaits = $forfaitsObj->data->forfaits;
          $insurances = isset($forfaitsObj->data->insurances) ? $forfaitsObj->data->insurances : [];
          $totalForf = floatval(str_replace(',','',$forfaitsObj->data->totalPrice));
          $extra = $forfaitsObj->data->manageFees;
          if (is_array($forfaits) && count($forfaits) < 1) {
            $error = "Forfaits vacíos";
          } else {
            $item = new ForfaitsOrderItem();
            $item->order_id = $orderID;
            $item->type = 'forfaits';
            $item->data = json_encode($forfaits);
            $item->insurances = json_encode($insurances);
            $item->forfait_users = json_encode($users);
            $item->total = $totalForf;
            $item->extra = $extra;
            
            if ($priceWithoutDisc)
              $item->price_wd = $priceWithoutDisc;
            else
              $item->price_wd = $totalForf;
            
            $item->status = 'new';
            $item->save();
          }
        } else {
          $error = implode(', ',$forfaitsObj->data->errors->forfaits);
        }
      } else {
        $error = "Forfaits no encontrado";
      }
    }
    return $error;
  }

  /**
   * 
   * @param type $orderID
   * @return string
   */
  public function getCart($order) {


    $totalForf = $totalMat = $totalClas = 0;
    $classes = [];
    $materials = [];
    $users = [];
    $forfaits = [];
    $totalPrice = 0;
    $totalItems = 0;
    $extraForfait = 0;
    $price_wdForf = 0;
    if ($order) {
      $totalPrice = $order->total;

      $forfaitsLst = ForfaitsOrderItem::where('order_id', $order->id)->where('type', 'forfaits')->WhereNull('cancel')->get();
      if ($forfaitsLst) {
        foreach ($forfaitsLst as $f) {
          $aux = json_decode($f->data);
          $insurances = json_decode($f->insurances);
          $totalItems += count($aux);
          $forfaits[$f->id] = ['usr'=>$aux,'insur'=>$insurances];
          $totalForf += $f->total;
          $price_wdForf += $f->price_wd;
          $extraForfait += $f->extra;
        }
      }
      $classesLst = ForfaitsOrderItem::where('order_id', $order->id)->WhereNull('cancel')->where('type', 'class')->get();
      if ($classesLst) {
        foreach ($classesLst as $item) {
          $aux = json_decode($item->data);
          $aux->total = $item->total;
          $aux->item->id = $item->id;
          $classes[] = $aux;
          $totalItems++;
          $totalClas += $item->total;
        }
      }
      $objLst = ForfaitsOrderItem::where('order_id', $order->id)->WhereNull('cancel')->where('type', 'material')->get();
      if ($objLst) {
        foreach ($objLst as $item) {
          $aux = json_decode($item->data);
          $aux->total = $item->total;
          $aux->item->id = $item->id;
          $materials[] = $aux;
          $totalItems++;
          $totalMat += $item->total;
        }
      }
    }

    $totalPayment =  ForfaitsOrderPayments::where('order_id', $order->id)->where('paid',1)->sum('amount');
    if ($totalPayment>0){
      $totalPayment = $totalPayment/100;
    }
    $totalToPay = $totalPrice - $totalPayment;
    $result = [
        'status' => 'ok',
        'totalItems' => $totalItems,
        'materials' => $materials,
        'forfaits' => $forfaits,
        'forfaits_users' => $users,
        'classes' => $classes,
        'error_1' => '',
        'error_2' => '',
        'error_3' => '',
        'totalForf' => $totalForf,
        'totalForfOrig' => $price_wdForf,
        'totalMat' => $totalMat,
        'totalClas' => $totalClas,
        'totalPrice' => $totalPrice,
        'totalPayment' => $totalPayment,
        'totalToPay' => $totalToPay,
        'extraForfait' => $extraForfait,
    ];

    return $result;
  }

  public function bookingData($bookingID, $clientID, $returnJson = true) {
    $userData = [
        'user_name' => '',
        'user_email' => '',
        'user_phone' => '',
        'ff_status' => '',
        'ff_statusLst' => '',
        'apto' => '',
        'start' => '',
        'finish' => '',
        'key' => [$bookingID, $clientID]
    ];

    $bookingID = desencriptID($bookingID);
    $clientID = desencriptID($clientID);
    if (is_numeric($bookingID)) {
      $book = Book::find($bookingID);
      /** @todo: control de que ya no tenga un Forfaits + administrador datos */
      if ($book) {
        if ($book->customer_id == $clientID) {
          $client = $book->customer()->first();
          if ($client) {
            $userData['user_name'] = $client->name;
            $userData['user_email'] = $client->email;
            $userData['user_phone'] = $client->phone;
          }
          $room = $book->room()->first();
          $userData['apto'] = ($room->luxury) ? $room->sizeRooms->name . " - LUJO" : $room->sizeRooms->name . " - ESTANDAR";
          $userData['start'] = $book->start;
          $userData['finish'] = $book->finish;
          $userData['ff_status'] = $book->ff_status;
        }
      }
    }
    
    $result = [
      'userData' => $userData,
      'classes' => ForfaitsItem::getClasses(),
      'categories' => ForfaitsItem::getCategories(),
      'seasons' => $this->getForfaitSeasons(),
    ];
    
    

    if ($returnJson) {
      
      
      return response()->json($result);
    }

    return $result;
  }

  public function sendEmail(Request $req) {
    /** @todo Usar el código para obtener los datos del usuario */
    $bookingID = $req->input('bookingID', null);
    $userID = $req->input('userID', null);
    $text = $req->input('text', null);

    if ($bookingID && $userID) {
      $bookingID = desencriptID($bookingID);
      $clientID = desencriptID($userID);

      if (is_numeric($bookingID) && is_numeric($clientID)) {
        $book = Book::find($bookingID);
        if ($book) {
          if ($book->customer_id == $clientID) {

            $client = $book->customer()->first();
            if ($client) {
              $email = $client->email;
              $text = $req->input('text', null);
              $subject = 'consulta sobre Forfaits';
              $mailClientContent = '
                  <h1>Nueva consulta sobre ForFaits</h1>
                  <table>
                    <tr>
                    <th><b>Nombre</b></th>
                    <td>' . $client->name . '</td>
                    </tr>
                    <tr>
                    <th><b>Email</b></th>
                    <td>' . $email . '</td>
                    </tr>
                    <tr>
                    <th><b>Teléfono</b></th>
                    <td>' . $client->phone . '</td>
                    </tr>
                    <tr>
                  </table>
                  <p><b>Consulta</b></p>
                  <p>' . $text . '</p>
                  ';

              Mail::send('backend.emails.base', ['mailContent' => $mailClientContent, 'title' => $subject], function ($message) use ($email, $subject) {
                $message->from('reservas@apartamentosierranevada.net');
                $message->to($email);
                $message->subject($subject);
                $message->replyTo('reservas@apartamentosierranevada.net');
              });
              return response()->json(['ok']);
            }
          }
        }
      }
    }
    return response()->json(['error']);
  }

  public function sendBookingOrder($order,$lastItemIDPayment) {
    
    $error = [
        'order_id' => $order->id,
        'book_id' => $order->book_id,
        'detail' => '',
        'item_id' => '',
        'created_at' => date('Y-m-d H:i:s'),
    ];
    
    $book = Book::find($order->book_id);
    if ($book){
      $clientName = $book->customer->name;
      $clientEmail = $book->customer->email;
    } else {
      $error['detail'] = 'Reserva no encontrada';
      DB::table('forfaits_errors')->insert($error);
      return ;
    }
    
    $forfaitsLst = ForfaitsOrderItem::where('order_id', $order->id)
            ->where('type', 'forfaits')
            ->where('id','<=',$lastItemIDPayment)
            ->WhereNull('cancel')->get();
    if ($forfaitsLst) {
        foreach ($forfaitsLst as $f) {
          if ($f->ffexpr_status == 1) {
            continue; //return 'ForfaitsExpress ya reservado';
          }
          $result = $this->sendBooking($f,$clientName,$clientEmail);
          if ($result != 'ok'){
            $error['item_id'] = $f->id;
            $error['detail'] = $result;
            DB::table('forfaits_errors')->insert($error);
          }
        }
    }
      
  }
  /**
   * Send a Forfait to Create FFExpress booking
   * @param type $oForfait
   * @param type $clientName
   * @param type $clientEmail
   * @return string
   */
  public function sendBooking($oForfait,$clientName,$clientEmail) {

    if ($oForfait->type != 'forfaits'){
      return 'El Item no es Forfait';
    }
    if ($oForfait->cancel == 1){
        return 'El Item fue cancelado';
    }
    
    if ($oForfait->ffexpr_status == 1) {
      return 'ForfaitsExpress ya reservado';
    }

    $forfait_insurances = json_decode($oForfait->insurances);
    $forfait_data = json_decode($oForfait->data);
    if (!$forfait_data) {
      return 'Forfait sin items';
    }


    $data = [
        "forfaits" => [],
        "extras" => [
            "equipments" => [],
            "classes" => [],
            "insurances" => [],
        ],
        "skiResortId" => env('FORFAIT_RESORTID'),
        "clientName" => $clientName,
        "clientEmail" => $clientEmail,
        "paymentMethodId" => 11,
        "pickupPointId" => 4,
        "pickupPointAddress" => '',//env('FORFAIT_POINT_ADDRESS'),
        "familyFormula" => FALSE,
        "comments" => "",
    ];

    foreach ($forfait_data as $ff_data) {
      $data['forfaits'][] = [
          "age" => $ff_data->age,
          "dateFrom" => $ff_data->dateFrom,
          "dateTo" => $ff_data->dateTo
      ];
    }
    foreach ($forfait_insurances as $ff_data) {
      $data['insurances'][] = [
          "insuranceId" => $ff_data->insuranceId,
          "clientName" => $ff_data->clientName,
          "clientDni" => $ff_data->clientDni,
          "dateFrom" => $ff_data->dateFrom,
          "dateTo" => $ff_data->dateTo
      ];
    }
    
    $json = json_encode($data);
    $curl = curl_init();
    $endpoint = env('FORFAIT_ENDPOINT') . 'createbooking';
    $Bearer = env('FORFAIT_TOKEN');
    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $Bearer"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    if ($err) {
      return redirect()->back()->withErrors([$err]);
    } else {
      $r = json_decode($response);
      if (isset($r->success)) {
        if ($r->success) {

          if (isset($r->data->bookingNumber)) {
            $oForfait->ffexpr_bookingNumber = intval($r->data->bookingNumber);
          }

          $oForfait->ffexpr_status = 1;
          $oForfait->ffexpr_data = $oForfait->ffexpr_data . '|' . date('Y-m-d H:i') . ' ' . $response;
          $oForfait->save();
          return 'ok';
        } else {

          $msg = 'Error no definido';
          if (isset($r->data) && isset($r->data->message))
            $msg = $r->data->message;
          else $msg = json_encode($r->data);
          
          $oForfait->ffexpr_status = 2;
          $oForfait->ffexpr_data = $oForfait->ffexpr_data . '|' . date('Y-m-d H:i') . ' ' . $msg;
          $oForfait->save();
          return $msg;
//          return redirect()->back()->withErrors($r->data->message);
        }
      } else {
        $oForfait->ffexpr_status = 2;
        $oForfait->ffexpr_data = $oForfait->ffexpr_data . '|' . date('Y-m-d H:i') . ' ' . $response;
        $oForfait->save();
        return 'Error al enviar petisión ForfaitExpress';
//        return redirect()->back()->withErrors('Error al enviar petisión ForfaitExpress');
      }
    }
  }

  public function changeStatus(Request $req) {
    
    $token = $req->header('token-ff');
    $client = $req->header('client');
    if (!$this->checkUserAdmin($token,$client)) return die('404');
    
      $key = $req->input('key', null);
      $data = $req->input('data', null);
      if ($key) {
        $aKey = explode('-', $key);
        $bookingKey = isset($aKey[0]) ? ($aKey[0]) : null;
        $clientKey = isset($aKey[1]) ? ($aKey[1]) : null;
        $bookingID = desencriptID($bookingKey);
        $clientID = desencriptID($clientKey);
        $book = Book::find($bookingID);

        if ($book && $book->customer_id == $clientID) {
          $book->ff_status = intval($data);
          $book->save();
          return response()->json(['status' => 'ok']);
        }
      }
    
    
  }

  public function getUserAdmin(request $req) {

    $token  = $req->header('token-ff');
    $client = $req->header('client');
    if ($this->checkUserAdmin($token,$client)) {
      return response('1');
    }
    return response('0');
  }

  private function checkUserAdmin($token,$ipClient) {

    $token = desencriptID($token);
    if (is_numeric($token)) {
      $ip = explode('.', $ipClient);
      $aux = 1;
      if ($ip[0] > $ip[1]) {
        $aux = $ip[0] - $ip[1];
      } else {
        $aux = $ip[1] - $ip[0];
      }

      if ($ip[2] > $ip[3]) {
        $aux .= $ip[2] - $ip[3];
      } else {
        $aux .= $ip[3] - $ip[2];
      }

      $uID = $token / $aux;
      $user = \App\User::find($uID);
      if ($user) {
        return true;
      }
    }
    return FALSE;
  }

  public function getOpenData(Request $req) {

    $return = [
        'link' => env('FF_PAGE'),
        'admin' => null
    ];
    $bookID = $req->input('id');
    $book = Book::find($bookID);
    if ($book) {
      $customer = $book->customer_id;
      $return['link'] .= encriptID($bookID) . '-' . encriptID($customer);
      // create the Admin token
      $ip = explode('.', getUserIpAddr());
      $aux = 1;
      if ($ip[0] > $ip[1]) {
        $aux = $ip[0] - $ip[1];
      } else {
        $aux = $ip[1] - $ip[0];
      }

      if ($ip[2] > $ip[3]) {
        $aux .= $ip[2] - $ip[3];
      } else {
        $aux .= $ip[3] - $ip[2];
      }

      $token = $aux * Auth::user()->id;
      $return['admin'] = encriptID($token);
    }
    return $return;
  }

}
