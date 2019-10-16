<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ForfaitsItem;
use App\ForfaitsUser;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use App\ForfaitsOrders;
use App\ForfaitsOrderItem;

class ForfaitsItemController extends Controller
{
  public function index($class = null) {
    $all = ForfaitsItem::all();
    $items = [];
    foreach ($all as $item){
      if (!isset($items[$item->cat])){
        $items[$item->cat] = [];
      }
      $items[$item->cat][] = $item;
    }
    if ($class){
      if (isset($items[$class])){
        $items = array($class=>$items[$class]);
      }
    }
    
    $catMat = ForfaitsItem::getCategories();
    $catClass = ForfaitsItem::getClasses();
    
    return view('backend/forfaits/index', [
        'selClass'=> $class,
        'items'=> $items,
        'categ'=> array_merge($catMat,$catClass),
        ]);
  }
  public function edit($id) {
    $item = ForfaitsItem::find($id);
    $categ = $item->getCategory();
    return response()->json(['item'=>$item,'cat'=>$categ] );
  }
  
  
  public function update(Request $req) {
    
    $id = $req->input('item_id', null);
    if ($id){
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
    if ($id){
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
  /*******************************/
  /*******       API      *******/
  /*****************************/
  public function api_getClasses() {
    return response()->json(ForfaitsItem::getClasses());
  }

  public function api_getCategories() {
    return response()->json(ForfaitsItem::getCategories());
  }
  
  public function api_items($cat) {
    $items = ForfaitsItem::where('cat',$cat)->where('status',1)->get();
    return response()->json($items);
  }
  
  public function getForfaitUser($forfaitLst) {
    
    $data = array(
        'forfaits'=>$forfaitLst,
        'skiResortId'=>env('FORFAIT_RESORTID')
        );
    $json = json_encode($data);
    $curl = curl_init();
    $endpoint = env('FORFAIT_ENDPOINT').'getprices';
    $Bearer = env('FORFAIT_TOKEN');
    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $Bearer"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
//    var_dump($forfaitLst,$response); die;
    curl_close($curl);
    if ($err) {
      return json_encode(['success'=>false, 'data'=>['message'=>$err]]);
    } else {
      return $response;
    }
  }
  public function getForfaitSeasons() {
    $skiResortId = env('FORFAIT_RESORTID');
    $curl = curl_init();
    $endpoint = env('FORFAIT_ENDPOINT').'getseasons';
    $Bearer = env('FORFAIT_TOKEN');
    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $Bearer"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return ['success'=>false, 'data'=>$err];
    } else {
      $list = json_decode($response);
      
      if (isset($list->success) && $list->success){
      foreach ($list->data->seasons as $lst){
        if ($lst->skiResortId == $skiResortId){
          $start = explode('/', $lst->startDate);
          $end = explode('/', $lst->endDate);
          return [
              'startDate'=> $start[2].'-'.$start[1].'-'.$start[0],
              'endDate'=>$end[2].'-'.$end[1].'-'.$end[0],
                  ];
        }
      }
      }
     return ['success'=>false, 'data'=>'no dates'];
    }
  }


  private function getDays($start,$end){
    $d_start = strtotime($start); 
    $d_end = strtotime($end); 
    $monToFr = 0;
    $satOrSun = 0;

    for($d_start; $d_start<=$d_end; $d_start=strtotime('+1 day ' . date('Y-m-d',$d_start))){ 
      $day = date('N',$d_start);
      if ($day<6){
        $monToFr++;
      } else {
        $satOrSun++;
      }
    } 
    return ['monToFr'=>$monToFr,'satOrSun'=>$satOrSun];
  }

  public function getCurrentCart($bookingID,$clientID) {
    $bookingID = desencriptID($bookingID);
    $clientID = desencriptID($clientID);
    if (is_numeric($bookingID) && is_numeric($clientID)){
      $order = ForfaitsOrders::getByBook($bookingID);
      return $this->getCart($order);
    } 
    return response()->json(['status'    => 'error']);
  }
  
  /**
   * 
   * @param Request $req
   * @param type $returnJson
   * @return type
   */
  public function saveCart(Request $req, $returnJson=true) {
    $type = $req->input('type', null);
    $data = $req->input('data', null);
//    $id = $req->input('ID', null);
    
    $key = $req->input('key', null);
    $order = ForfaitsOrders::getByKey($key);
    if (!$order){
      return die('404');
    }
    $orderID = $order->id;
    
//    var_dump($type,$data,$id); die;
    $error = '';
    switch ($type){
      case 'forfaits':
        $error = $this->saveForfait($data,$orderID);
        break;
      case 'material':
        $error = $this->saveMaterial($data,$orderID);
        break;
      case 'classes':
        $error = $this->saveclass($data,$orderID);
        break;
    }
    $order->recalculate();
    $cart = $this->getCart($order);
    switch ($type){
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
  public function cancelItem(Request $req, $returnJson=true) {
    $type = $req->input('type', null);
    $data = $req->input('data', null);
    $key = $req->input('key', null);
    $order = ForfaitsOrders::getByKey($key);
    if (!$order)  return response()->json(['status'    => 'error']);
    
    if ($type == 'forfait'){
      $item = ForfaitsOrderItem::find($data);
      if ($item->order_id == $order->id){
        $item->cancel = 1;
        $item->save();
      }
    } else {
      if ( isset($data['item']) && isset($data['item']['id']) ){
        $itemID = $data['item']['id'];
        $itemkey = $data['item']['item_key'];
        $item = ForfaitsOrderItem::find($itemID);
        if ($item && $item->order_id == $order->id){
          $itemData = json_decode($item->data);
  //        var_dump($itemData);die;
          if ($itemData->item->item_key == $itemkey){
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
  private function saveclass($class,$orderID) {
    $error = null;
    if (isset($class['item']) && isset($class['item']['item_key'])){

      $itemKey = $class['item']['item_key'];
      $nro = isset($class['nro']) ? intval($class['nro']) : 1;
      $objIten = ForfaitsItem::where('item_key',$itemKey)->first();
      if ($objIten){
        if ($objIten->cat == 'grupal_class'){
          $days = $this->getDays($class['date_start'], $class['date_end']);

          if ( isset($days['monToFr']) && isset($days['monToFr']) ){
            $price = ( $days['monToFr'] * $objIten->regular_price ) + ( $days['satOrSun'] * $objIten->special_price ); 
            $price = $price * $nro;
          }
        } else {
          $hours = isset($class['hours']) ? intval($class['hours']) : 1;
          $day = date('N',strtotime($class['date_start']));
          if ( $day > 0 && $day < 8 ){
            if ($day<6){
              $price = $objIten->regular_price;
            } else {
              $price = $objIten->special_price;
            }
            $price = $price * $hours * $nro;
          }
        }
        
        $item = new ForfaitsOrderItem();
        $item->order_id     = $orderID;
        $item->type         = 'class';
        $item->data         = json_encode($class);
        $item->total        = $price;
        $item->status       = 'new';
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
  private function saveMaterial($material,$orderID) {
    //material
    $error = '';
    if (isset($material['item']) && isset($material['item']['item_key'])){

      $itemKey = $material['item']['item_key'];
      $objIten = ForfaitsItem::where('item_key',$itemKey)->first();
      if ($objIten){

        $nro = isset($material['nro']) ? intval($material['nro']) : 1;
        $days = $this->getDays($material['date_start'], $material['date_end']);

        if ( isset($days['monToFr']) && isset($days['monToFr']) ){
          $price = ( $days['monToFr'] * $objIten->regular_price ) + ( $days['satOrSun'] * $objIten->special_price ); 
          $price = $price * $nro;
          $item = new ForfaitsOrderItem();
          $item->order_id     = $orderID;
          $item->type         = 'material';
          $item->data         = json_encode($material);
          $item->total        = $price;
          $item->status       = 'new';
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
  private function saveForfait($users,$orderID) {
    $error = null;
    $usr_forfaits = [];
    if ($users) {
      foreach ($users as $usr) {
        $usr_forfaits[] = [
            "age" => $usr['years'],
            "dateFrom" => date('d/m/Y', strtotime($usr['date_start'])),
            "dateTo" => date('d/m/Y', strtotime($usr['date_end'])),
        ];
      }
     
      /** @todo enviar info a ForfaitExpress */
      $forfaitsObj = $this->getForfaitUser($usr_forfaits);
      $forfaitsObj = json_decode($forfaitsObj);
      
      if (isset($forfaitsObj->success)){
        if ($forfaitsObj->success){
          $forfaits = $forfaitsObj->data->forfaits;
          $totalForf = $forfaitsObj->data->totalPrice;
          if (is_array($forfaits) && count($forfaits)<1){
            $error = "Forfaits vacíos";
          } else {
            $item = new ForfaitsOrderItem();
            $item->order_id     = $orderID;
            $item->type         = 'forfaits';
            $item->data         = json_encode($forfaits);
            $item->forfait_users= json_encode($users);
            $item->total        = $totalForf;
            $item->status       = 'new';
            $item->save();
          }
        } else {
          $error = $forfaitsObj->data->message;
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
    if ($order){
      $totalPrice = $order->total;
      
      $forfaitsLst = ForfaitsOrderItem::where('order_id',$order->id)->where('type','forfaits')->WhereNull('cancel')->get();
      if ($forfaitsLst){
        foreach ($forfaitsLst as $f){
          $aux = json_decode($f->data);
          $totalItems += count($aux);
          $forfaits[$f->id] = $aux;
          $totalForf += $f->total;
        }
      }
      $classesLst = ForfaitsOrderItem::where('order_id',$order->id)->WhereNull('cancel')->where('type','class')->get();
      if ($classesLst){
        foreach ($classesLst as $item){
          $aux = json_decode($item->data);
          $aux->total = $item->total;
          $aux->item->id = $item->id;
          $classes[] = $aux;
          $totalItems++;
          $totalClas += $item->total;
        }
      }
      $objLst = ForfaitsOrderItem::where('order_id',$order->id)->WhereNull('cancel')->where('type','material')->get();
      if ($objLst){
        foreach ($objLst as $item){
          $aux = json_decode($item->data);
          $aux->total = $item->total;
          $aux->item->id = $item->id;
          $materials[] = $aux;
          $totalItems++;
          $totalMat += $item->total;
        }
      }
    }
    
    $totalPayment = 55;
    $totalToPay = $totalPrice-$totalPayment;
    $result = [
        'status'    => 'ok',
        'totalItems'=> $totalItems,
        'materials' => $materials,
        'forfaits'  => $forfaits,
        'forfaits_users' => $users,
        'classes'   => $classes,
        'error_1'   => '',
        'error_2'   => '',
        'error_3'   => '',
        'totalForf'   => $totalForf,
        'totalMat'   => $totalMat,
        'totalClas'   => $totalClas,
        'totalPrice'   => $totalPrice,
        'totalPayment'   => $totalPayment,
        'totalToPay'   => $totalToPay,
    ];

    return $result;
   
  }
  
  public function bookingData($bookingID,$clientID,$returnJson = true) {
    $result = [
      'user_name' => '',
      'user_email' => '',
      'user_phone' => '',
      'apto' => '',
      'start' => '',
      'finish' => '',
      'key'   => [$bookingID,$clientID]
    ];
    
    $bookingID = desencriptID($bookingID);
    $clientID = desencriptID($clientID);
    if (is_numeric($bookingID)){
      $book = \App\Book::find($bookingID);
      /** @todo: control de que ya no tenga un Forfaits */
      if ($book){
        if ($book->customer_id == $clientID){
          $client = $book->customer()->first();
          if ($client){
            $result['user_name'] = $client->name;
            $result['user_email'] = $client->email;
            $result['user_phone'] = $client->phone;
          }
          $room = $book->room()->first();
          $result['apto'] = ($room->luxury) ? $room->sizeRooms->name . " - LUJO" : $room->sizeRooms->name . " - ESTANDAR";
          $result['start'] = $book->start;
          $result['finish'] = $book->finish;
      }
    }}
    if ($returnJson){
      return response()->json($result);
    }
    
    return $result;
  }
  
  
  public function checkout(Request $req) {
    $cart = $this->saveCart( $req, false);
    $user = $req->input('user', null); 
    $ID   = $req->input('ID', null); 
    $userForm = $req->input('userForm', null); 
    if ($user && $userForm && $cart){
      if (isset($user['key']) || true){
        $bookingKey = isset($user['key'][0]) ? ($user['key'][0]) : null;
        $clientKey = isset($user['key'][1]) ? ($user['key'][1]) : null;
        $bookingID = desencriptID($bookingKey);
        $clientID = desencriptID($clientKey);
        $ID = desencriptID($ID);
    
        if (is_numeric($bookingID) && is_numeric($clientID)){
          $book = \App\Book::find($bookingID);
          /** @todo: control de que ya no tenga un Forfaits */
          if ($book){
            if ($book->customer_id == $clientID){
                /** BEGIN: save booking **/
                if ($ID){
                  $forfaitItem = ForfaitsUser::findOrNew($ID);
                } else {
                  $forfaitItem = new ForfaitsUser();
                }
                           
                $forfaitItem->book_id = $bookingID;
                $forfaitItem->room_id = $book->room_id;
                $forfaitItem->cli_id = $book->customer_id;
                $forfaitItem->name  = isset($user['name']) ? $user['name'] : null;
                $forfaitItem->email = isset($user['email']) ? $user['namemaile'] : null;
                $forfaitItem->phone = isset($user['phone']) ? $user['phone'] : null;
                $forfaitItem->forfait_data = json_encode($cart['forfaits']);
                $forfaitItem->forfait_users = json_encode($cart['forfaits_users']);
                $forfaitItem->materials_data = json_encode($cart['materials']);
                $forfaitItem->classes_data = json_encode($cart['classes']);
                $forfaitItem->forfait_total = $cart['totalForf'];
                $forfaitItem->materials_total = $cart['totalMat'];
                $forfaitItem->classes_total = $cart['totalClas'];
                $forfaitItem->total = $cart['totalPrice'];
                $forfaitItem->status = 'new';
                $forfaitItem->save();
                /** END: save booking **/

                $return = $cart;
                $return['userInfo'] = $this->bookingData($bookingKey,$clientKey,false);
                  
                return response()->json($return);
              
            }
          }
        }
    
      }
    }
    
    return response()->json(['error']);
  }

  
  public function sendEmail(Request $req) {
    /** @todo Usar el código para obtener los datos del usuario*/
    
    $bookingID = $req->input('bookingID', null); 
    $userID    = $req->input('userID', null); 
    $text      = $req->input('text', null); 
    
    if ($bookingID && $userID){
      $bookingID = desencriptID($bookingID);
      $clientID = desencriptID($userID);
        
      if (is_numeric($bookingID) && is_numeric($clientID)){
        $book = \App\Book::find($bookingID);
        if ($book){
          if ($book->customer_id == $clientID){
            
            $client = $book->customer()->first();
            if ($client){
              $email = $client->email;
              $text = $req->input('text', null); 
              $subject = 'consulta sobre Forfaits';
              $mailClientContent = '
                  <h1>Nueva consulta sobre ForFaits</h1>
                  <table>
                    <tr>
                    <th><b>Nombre</b></th>
                    <td>'.$client->name.'</td>
                    </tr>
                    <tr>
                    <th><b>Email</b></th>
                    <td>'.$email.'</td>
                    </tr>
                    <tr>
                    <th><b>Teléfono</b></th>
                    <td>'.$client->phone.'</td>
                    </tr>
                    <tr>
                  </table>
                  <p><b>Consulta</b></p>
                  <p>'.$text.'</p>
                  ';

              Mail::send('backend.emails.base', 
                      ['mailContent' => $mailClientContent,'title'=>$subject], function ($message) use ($email,$subject) {
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
  
  public function createPaylandsUrl($customer_id,$total_price,$key) {
    
    $urlPayland = $this->generateOrderPayment([
                    'customer_id' => $customer_id,
                    'amount'      => $total_price,
                    'url_ok'      => route('payland.thanks.payment', ['id' => $key]),
                    'url_ko'      => route('payland.thanks.payment', ['id' => $key]),
            ]);
    
  }
  
  public function sendBooking(Request $req) {
    
    $id = $req->input('item_id');
    
     
    
    
    $oForfait = ForfaitsUser::find($id);
    if (!$oForfait){
      return redirect()->back()->withErrors(['Forfaits no encontrado']);
    }
    if ($oForfait->ffexpr_status == 1){
      return redirect()->back()->withErrors(['ForfaitsExpress ya reservado']);
    }
    
    
    
//    dd($oForfait);
    $cliente = \App\Customers::find($oForfait->cli_id);
    $forfait_data = json_decode($oForfait->forfait_data);
    if (!($cliente && $forfait_data)){
      return redirect()->back()->withErrors(['Forfait sin items']);
    }
    
    
    
    $data = [
      "forfaits" => [],
      "extras" => [
        "equipments" => [],
        "classes" => []
      ],
      "skiResortId" => env('FORFAIT_RESORTID'),
      "clientName" => $cliente->name,
      "clientEmail" => $cliente->email,
      "paymentMethodId" => 11,
      "pickupPointId" => 5,
      "pickupPointAddress" =>  env('FORFAIT_POINT_ADDRESS'),
      "familyFormula" => FALSE,
      "comments" => "",
    ];
     
    foreach ($forfait_data as $ff_data){
      $data['forfaits'][] = [
        "age" => $ff_data->age,
        "dateFrom" => $ff_data->dateFrom,
        "dateTo" => $ff_data->dateTo
      ];
    }
    $json = json_encode($data);
    $curl = curl_init();
    $endpoint = env('FORFAIT_ENDPOINT').'createbooking';
    
    $Bearer = env('FORFAIT_TOKEN');
    curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
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
      if (isset($r->success)){
        if ($r->success){
        
        if (isset($r->data->bookingNumber)){
          $oForfait->ffexpr_bookingNumber = intval($r->data->bookingNumber);
        }
        
        $oForfait->ffexpr_status = 1;
        $oForfait->ffexpr_data = $oForfait->ffexpr_data.'|'.date('Y-m-d H:i').' '.$response;
        $oForfait->save();
        return redirect()->back()->with('success', 'Se ha reservado el item en ForfaitExpress'); 
        } else {
        
          $oForfait->ffexpr_status = 2;
          $oForfait->ffexpr_data = $oForfait->ffexpr_data.'|'.date('Y-m-d H:i').' '.$r->data->message;
          $oForfait->save();
          return redirect()->back()->withErrors($r->data->message);
        }
        
      } else {
        $oForfait->ffexpr_status = 2;
        $oForfait->ffexpr_data = $oForfait->ffexpr_data.'|'.date('Y-m-d H:i').' '.$response;
        $oForfait->save();
        return redirect()->back()->withErrors('Error al enviar petisión ForfaitExpress');
      }
    }
  }

}
