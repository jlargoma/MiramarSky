<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contents extends Model
{
  
  protected $fillable = ['key', 'field', 'content', 'created_at', 'updated_at'];

  
  static function getKeyLst(){
    return [
      'edificio' => 'El edificio - Home',  
      'edificio_page' => 'El edificio - Página',  
      'services' => 'Servicios - Home',  
      'meta_descripcion' => 'Meta Descripción',  
      'footer' => 'Pie de página',  
      'contacto' => 'Contacta',  
      'slider_home' => 'Slider home',  
      'fianza' => 'Condiciones de fianzas',  
      'buzon' => 'Página Buzon',  
      'resto' => 'Página Restauran',  
      'resto_1' => 'Restauran: Especialidad en carne y comida casera',  
      'resto_2' => 'Restauran: Comida granadina y andaluza',  
      'resto_3' => 'Restauran: Cocina de estilo mediterráneo o variada',  
      'resto_4' => 'Restauran: Pizzerias',  
      'resto_5' => 'Restauran: Comida rápida y en pista',  
      'resto_6' => 'Restauran: Bares',  
    ];
  }

  
  static function getKeyContent($key){
    switch ($key){
      case 'edificio':
        return [
          'title'=>['Título','string',null],  
          'content_1'=>['Primer texto','ckeditor',null],  
          'imagen_1'=>['Primer Imagen (recomendado: 393*390 px)','file',null],  
          'imagen_1_mobile'=>['Primer Imagen Mobil (andcho recomendado: 580px)','file',null],  
          'video_1'=>['Primer Video (andcho recomendado: 470px)','video',null],  
          'title_2'=>['Segundo Título','string',null],  
          'content_2'=>['Segundo texto','ckeditor',null],  
          'imagen_2'=>['Segunda Imagen (recomendado: 393*390 px)','file',null],  
//          'imagen_2_mobile'=>['Segunda Imagen Mobil','file',null],  
          'video_2'=>['Segundo Video (andcho recomendado: 470px)','video',null],  
        ];
        break;
      case 'edificio_page':
        $aux = [
          'title'=>['Título','string',null],  
          'content_1'=>['Primer texto','ckeditor',null],  
          'content_2'=>['Segundo texto','ckeditor',null],  
        ];
        for($i=0;$i<7;$i++) $aux['imagen_'.$i] = ['Imagen (555*604 px)','file',null];
        return $aux;
        break;
      case 'services':
        return [
          'title_1'=>['Título','string',null],  
          'imagen_1'=>['Primer Imagen ( 630*420 px )','file',null],  
          'imagen_1_mobile'=>['Primer Imagen Mobil( 630*420 px )','file',null],  
          'link_1'=>['Primer enlace','string',null],  
          'title_2'=>['Segundo Título','string',null],  
          'imagen_2'=>['Segunda Imagen ( 630*420 px )','file',null],  
          'imagen_2_mobile'=>['Segunda Imagen Mobil 630*420 px )','file',null],  
          'link_2'=>['Segundo enlace','string',null],  
          'title_3'=>['Tercer Título','string',null],  
          'imagen_3'=>['Tercer Imagen ( 630*420 px )','file',null],  
          'imagen_3_mobile'=>['Tercer Imagen Mobil( 630*420 px )','file',null],  
          'link_3'=>['Tercer enlace','string',null],  
        ];
        break;
      case 'meta_descripcion':
        return [
          'text'=>['Texto','textarea',null],  
        ];
        break;
      case 'slider_home':
        return [
          'title_1'=>['Título','string',null],  
          'content_1'=>['Primer texto','ckeditor',null],  
          'imagen_1'=>['Primer Imagen (recomendado 1665*700 px)','file',null],  
          'imagen_1_mobile'=>['Primer Imagen Mobil  (recomendado 425x620 px)','file',null],  
          'title_2'=>['Segundo Título','string',null],  
          'content_2'=>['Segundo texto','ckeditor',null],  
          'imagen_2'=>['Segunda Imagen','file',null],  
          'imagen_2_mobile'=>['Segunda Imagen Mobil','file',null],  
          'title_3'=>['Tercer Título','string',null],  
          'content_3'=>['Tercer texto','ckeditor',null],  
          'imagen_3'=>['Tercer Imagen','file',null],  
          'imagen_3_mobile'=>['Tercer Imagen Mobil','file',null],  
        ];
        break;
        case 'contacto':
          return [
            'title'=>['Título Información','string',null],  
            'subtitle'=>['SubTítulo Información','string',null],  
            'title_form'=>['Título formulario','string',null],  
            'content'=>['Texto de Contacto','ckeditor',null],  
            'imagen'=>['Imagen de fondo','file',null],  
          ];
        break;
        case 'footer':
          return [
            'imagen'=>['Imagen de fondo (recomendado: 1665*570 px)','file',null],  
          ];
        break;
        case 'fianza':
          return [
            'content'=>['Texto de condiciones de fianzas','ckeditor',null],  
          ];
        break;
        case 'buzon':
          return [
            'title'=>['Título de la página','string',null],  
            'content'=>['Texto principal','ckeditor',null],  
          ];
        break;
        case 'resto':
          return [
            'title'=>['Título de la página','string',null],  
            'content'=>['Texto principal','ckeditor',null],  
            'content_2'=>['Texto en el footer','ckeditor',null],  
          ];
        break;
        case 'resto_1':
          return self::resto_items();
        break;
        case 'resto_2':
           return self::resto_items();
        break;
        case 'resto_3':
           return self::resto_items();
        break;
        case 'resto_4':
           return self::resto_items();
        break;
        case 'resto_5':
           return self::resto_items();
        break;
        case 'resto_6':
           return self::resto_items();
        break;
    }
    return [ ];
  }
  
  static function resto_items() {
    return [
        'title'=>['Título de la sección','string',null],  
        'content'=>['Texto principal','ckeditor',null],  
        'content_1'=>['Primer contenido','ckeditor',null],  
        'content_1_img'=>['Primer imagen (recomendado 700*465 px)','file',null],  
        'content_2'=>['Segundo contenido','ckeditor',null],  
        'content_2_img'=>['Segundo imagen','file',null],  
        'content_3'=>['tercer contenido','ckeditor',null],  
        'content_3_img'=>['tercer imagen','file',null],  
        'content_4'=>['Cuarto contenido','ckeditor',null],  
        'content_4_img'=>['Cuarto imagen','file',null], 
        'content_5'=>['Quinto contenido','ckeditor',null],  
        'content_5_img'=>['Quinto imagen','file',null], 
        'content_6'=>['Sesto contenido','ckeditor',null],  
        'content_6_img'=>['Sesto imagen','file',null], 
        'content_7'=>['Septimo contenido','ckeditor',null],  
        'content_7_img'=>['Septimo imagen','file',null], 
  ];
  }
  
  public function getContentByKey($key,$mobile=false) {
    global $is_mobile;
    $lst = self::getKeyLst();
    $result = array();
    if ($key && isset($lst[$key])){
      $fields = self::getKeyContent($key);
      $site_id = config('app.site_id');
      $oContent = Contents::where('key',$key)->where('site_id',$site_id)->get();
      if ($oContent){
        foreach ($oContent as $cont){
          if (isset($fields[$cont->field])){
            $result[$cont->field] = $cont->content;
          } else {
            $result[$cont->field] = null;
          }
        }
      }
      foreach ($fields as $k=>$v){
          if (!isset($result[$k])){
            $result[$k] = null;
          }
      }
      
      if ($mobile && $is_mobile){
        foreach ($result as $k=>$v){
          if(trim($v) !='' && strpos($k,'mobile')>0){
            $aux = str_replace('_mobile', '', $k);
            $result[$aux] = $v;
          }
        }
      }
//    dd($result,$mobile, $is_mobile);  
    }
    
    return $result;
  }
}
