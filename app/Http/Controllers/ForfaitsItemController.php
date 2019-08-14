<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ForfaitsItem;
use App\Http\Requests;

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
    $obj->id = $i['id'];
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
