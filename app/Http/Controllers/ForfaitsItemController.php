<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ForfaitsItem;
use App\ForfaitsUser;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;

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
    curl_close($curl);
    if ($err) {
      return json_encode(['success'=>false, 'data'=>['message'=>$err]]);
    } else {
      return $response;
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
      $obj = ForfaitsUser::where('book_id',$bookingID)
              ->where('cli_id',$clientID)
              ->whereIn('status',['new','not_payment'])
              ->orderBy('id','DESC')->first();
  
      if ($obj){
        $forfaits = json_decode($obj->forfait_data);
        $totalItems = (count($forfaits)>0) ? 1 : 0;
        $a_materials = $a_clases = [];
        $materials = $obj->materials_data;
        if ($materials){
          $a_materials = json_decode($materials);
          $totalItems += count($a_materials);
        }
        $classes = $obj->classes_data;
        if ($classes){
          $a_clases = json_decode($classes);
          $totalItems += count($a_clases);
        }
        
        $result = [
            'status'      => 'ok',
            'totalItems'  => $totalItems,
            'materials'   => $a_materials,
            'forfaits'    => ($forfaits),
            'users'       => json_decode($obj->forfait_users),
            'classes'     => $a_clases,
            'totalForf'   => $obj->forfait_total,
            'totalMat'    => $obj->materials_total,
            'totalClas'   => $obj->classes_total,
            'totalPrice'  => $obj->total,
            'ID'          => encriptID($obj->id),
        ];
        return response()->json($result);
      }
    } 
    return response()->json(['status'    => 'error']);
  }
  public function getCart(Request $req, $returnJson=true) {
    $totalItems = 0;
    
    $materials  = $req->input('materials', null);
    $classes    = $req->input('classes', null);
    $forfaits   = [];
    $users   = $req->input('users', null);
    $error_1 = $error_2 = $error_3 = null;
    $totalPrice = 0;
    $totalForf = 0;
    $totalMat = 0;
    $totalClas = 0;
    
    
   
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
//      var_dump($forfaitsObj); die;
      if ($forfaitsObj->success){
        $forfaits = $forfaitsObj->data->forfaits;
        $totalForf = $forfaitsObj->data->totalPrice;
        $totalItems++;
      } else {
        $error_1 = $forfaitsObj->data->message;
      }
    }
    
    if ($materials){
      foreach ($materials as $k=>$v){
        if (isset($v['item']) && isset($v['item']['item_key'])){
          
          $itemKey = $v['item']['item_key'];
          $objIten = ForfaitsItem::where('item_key',$itemKey)->first();
          if ($objIten){
            
            $nro = isset($v['nro']) ? intval($v['nro']) : 1;
            $days = $this->getDays($v['date_start'], $v['date_end']);
            
            if ( isset($days['monToFr']) && isset($days['monToFr']) ){
              $price = ( $days['monToFr'] * $objIten->regular_price ) + ( $days['satOrSun'] * $objIten->special_price ); 
              $price = $price * $nro;
              $totalMat += $price;
              $materials[$k]['total'] = $price;
              $totalItems++;
            }
            
          } else {
            $error_2 = 'Item no encontrado';
          }
          
        } else {
          $error_2 = 'Item no encontrado';
        }
        
      }
    }
    
    if ($classes){
      foreach ($classes as $k=>$v){
        if (isset($v['item']) && isset($v['item']['item_key'])){
          
          $itemKey = $v['item']['item_key'];
          $nro = isset($v['nro']) ? intval($v['nro']) : 1;
          $objIten = ForfaitsItem::where('item_key',$itemKey)->first();
          if ($objIten){
            if ($objIten->cat == 'grupal_class'){
              $days = $this->getDays($v['date_start'], $v['date_end']);
            
              if ( isset($days['monToFr']) && isset($days['monToFr']) ){
                $price = ( $days['monToFr'] * $objIten->regular_price ) + ( $days['satOrSun'] * $objIten->special_price ); 
                $price = $price * $nro;
                $totalClas += $price;
                $classes[$k]['total'] = $price;
                $totalItems++;
              }
            } else {
              $hours = isset($v['hours']) ? intval($v['hours']) : 1;
              $day = date('N',strtotime($v['date_start']));
              if ( $day > 0 && $day < 8 ){
                if ($day<6){
                  $price = $objIten->regular_price;
                } else {
                  $price = $objIten->special_price;
                }

                $price = $price * $hours * $nro;
                $totalClas += $price;
                $classes[$k]['total'] = $price;
                $totalItems++;
              }
            }
            
          } else {
            $error_3 = 'Item no encontrado';
          }
          
        } else {
          $error_3 = 'Item no encontrado';
        }
        
      }
    }
    
    $totalPrice = $totalForf+$totalMat+$totalClas;
    $result = [
        'status'    => 'ok',
        'totalItems'=> $totalItems,
        'materials' => $materials,
        'forfaits'  => $forfaits,
        'forfaits_users' => $users,
        'classes'   => $classes,
        'error_1'   => $error_1,
        'error_2'   => $error_2,
        'error_3'   => $error_3,
        'totalForf'   => $totalForf,
        'totalMat'   => $totalMat,
        'totalClas'   => $totalClas,
        'totalPrice'   => $totalPrice,
    ];
    if ($returnJson)
      return response()->json($result);
    
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
          $result['start'] = $book->finish;
          $result['finish'] = $book->finish;
      }
    }}
    if ($returnJson){
      return response()->json($result);
    }
    
    return $result;
  }
  
  
  public function checkout(Request $req) {
    $cart = $this->getCart( $req, false);
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
  /*****************************************************************/
  /********     BORRAR                                  ************/
  /*****************************************************************/

  public function createItems() {
    $items_material = array(
    'packs_clases' => array(
        'name' => 'Packs clases',
        'cols' => array('name'=>1,'type'=>1,'equip'=>1,'class'=>1),
        'cnum' => 4,
        'item' => array(
            array(
                'id' => 'PC01',
                'name'=>'1 Pax',
                'type'=>'Esquí',
                'equip'=>'<ul><li>Esquís gama MEDIUM</li><li>Botas gama MEDIUM</li><li>Bastones Incluidos</li></ul>',
                'class'=>'3 Clases Colectivas. Duración 2h/día.',
            ),
            array(
                'id' => 'PC02',
                'name'=>'3 Pax',
                'type'=>'Esquí',
                'equip'=>'<ul><li>Esquís gama MEDIUM</li><li>Botas gama MEDIUM</li><li>Bastones Incluidos</li></ul>',
                'class'=>'2 Clases Colectivas. Duración 2h/día.',
            ),
            array(
                'id' => 'PC03',
                'name'=>'1 Pax',
                'type'=>'Snow',
                'equip'=>'<ul><li>Snowboard gama MEDIUM</li><li>Botas gama MEDIUM</li></ul>',
                'class'=>'3 Clases Colectivas .Duración 2h/día.',
            ),
            array(
                'id' => 'PC014',
                'name'=>'2 Pax',
                'type'=>'Snow',
                'equip'=>'<ul><li>Snowboard gama MEDIUM</li><li>Botas gama MEDIUM</li></ul>',
                'class'=>'2 Clases Colectivas .Duración 2h/día.',
            ),
          )
    ),
    'esqui' => array(
        'name' => 'Esqui',
        'cols' => array('name'=>1,'type'=>1,'equip'=>1,'class'=>0),
        'cnum' => 3,
        'item' => array(
            array(
                'id' => 'PE01',
                'name'=>'Pack',
                'type'=>'Adulto',
                'equip'=>'Esquis, Botas, Bastones',
                'class'=>null,
            ),
            array(
                'id' => 'PE02',
                'name'=>'Pack',
                'type'=>'Niño',
                'equip'=>'Esquis, Botas, Bastones',
                'class'=>null,
            ),
            array(
                'id' => 'PE03',
                'name'=>'Esquís + bastones',
                'type'=>'Adulto',
                'equip'=>'Esquis, Bastones',
                'class'=>null,
            ),
            array(
                'id' => 'PE04',
                'name'=>'Esquís + bastones',
                'type'=>'Niño',
                'equip'=>'Esquis, Bastones',
                'class'=>null,
            ),
            array(
                'id' => 'PE05',
                'name'=>'Botas',
                'type'=>'Adulto',
                'equip'=>'Botas',
                'class'=>null,
            ),
            array(
                'id' => 'PE06',
                'name'=>'Botas',
                'type'=>'Niño',
                'equip'=>'Botas',
                'class'=>null,
            ),
           
          )
    ),
    'snowboard' => array(
        'name' => 'Snowboard',
        'cols' => array('name'=>1,'type'=>1,'equip'=>1,'class'=>0),
        'cnum' => 3,
        'item' => array(
            array(
                'id' => 'SBOARD1',
                'name'=>'Pack',
                'type'=>'Adulto',
                'equip'=>'Tabla de Snowboard , Botas',
                'class'=>null,
            ),
            array(
                'id' => 'SBOARD2',
                'name'=>'Pack',
                'type'=>'Niño',
                'equip'=>'Tabla de Snowboard , Botas',
                'class'=>null,
            ),
            array(
                'id' => 'SBOARD3',
                'name'=>'Tabla de Snowboard',
                'type'=>'Adulto',
                'equip'=>'Tabla de Snowboard',
                'class'=>null,
            ),
            array(
                'id' => 'SBOARD4',
                'name'=>'Tabla de Snowboard',
                'type'=>'Niño',
                'equip'=>'Tabla de Snowboard',
                'class'=>null,
            ),
            array(
                'id' => 'SBOARD5',
                'name'=>'Botas',
                'type'=>'Adulto',
                'equip'=>'Botas',
                'class'=>null,
            ),
            array(
                'id' => 'SBOARD6',
                'name'=>'Botas',
                'type'=>'Niño',
                'equip'=>'Botas',
                'class'=>null,
            ),
          
          )
    ),
    'snowblade' => array(
        'name' => 'Snowblade',
        'cols' => array('name'=>1,'type'=>0,'equip'=>1,'class'=>0),
        'cnum' => 2,
        'item' => array(
            array(
                'id' => 'SBLADE1',
                'name'=>'Pack',
                'type'=>null,
                'equip'=>'Tabla de Snowblade , Botas',
                'class'=>null,
            ),
            array(
                'id' => 'SBLADE2',
                'name'=>'Snowblade',
                'type'=>null,
                'equip'=>'Tabla de Snowblade',
                'class'=>null,
            ),
            )
    ),
    'cascos' => array(
        'name' => 'Cascos',
        'cols' => array('name'=>1,'type'=>0,'equip'=>1,'class'=>0),
        'cnum' => 2,
        'item' => array(
            array(
                'id' => 'CASCO1',
                'name'=>'Casco Adulto',
                'type'=>null,
                'equip'=>'Casco para Adulto',
                'class'=>null,
            ),
            array(
                'id' => 'CASCO2',
                'name'=>'Casco Niño',
                'type'=>null,
                'equip'=>'Casco para Niño',
                'class'=>null,
            ),
            )
    ),
);
    
foreach ($items_material as $cat=>$item){
  foreach ($item['item'] as $i){
    $obj = new ForfaitsItem();
    $obj->item_key = $i['id'];
    $obj->name = $i['name'];
    $obj->type = $i['type'];
    $obj->equip = $i['equip'];
    $obj->class = $i['class'];
    $obj->status = 1;
    $obj->cat = $cat;
    $obj->save();
          
  }
}
  }
}
