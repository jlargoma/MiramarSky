<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contents extends Model
{
  
  protected $fillable = ['key', 'field', 'content', 'created_at', 'updated_at'];

  
  static function getKeyLst(){
    return [
      'edificio' => 'El edificio - Home',  
      'services' => 'Servicios - Home',  
      'meta_descripcion' => 'Meta Descripción',  
      'footer' => 'Pie de página',  
      'contacto' => 'Contacta',  
      'slider_home' => 'Slider home',  
    ];
  }

  
  static function getKeyContent($key){
    switch ($key){
      case 'edificio':
        return [
          'title'=>['Título','string',null],  
          'content_1'=>['Primer texto','ckeditor',null],  
          'imagen_1'=>['Primer Imagen','file',null],  
          'imagen_1_mobile'=>['Primer Imagen Mobil','file',null],  
          'video_1'=>['Primer Video (andcho recomendado: 470px)','video',null],  
          'title_2'=>['Segundo Título','string',null],  
          'content_2'=>['Segundo texto','ckeditor',null],  
          'imagen_2'=>['Segunda Imagen','file',null],  
          'imagen_2_mobile'=>['Segunda Imagen Mobil','file',null],  
          'video_2'=>['Segundo Video (andcho recomendado: 470px)','video',null],  
        ];
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
          'imagen_1'=>['Primer Imagen','file',null],  
          'imagen_1_mobile'=>['Primer Imagen Mobil','file',null],  
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
            'imagen'=>['Imagen de fondo','file',null],  
          ];
        break;
    }
    return [ ];
  }
  
  public function getContentByKey($key,$mobile=false) {
    global $is_mobile;
    $lst = self::getKeyLst();
    $result = array();
    if ($key && isset($lst[$key])){
      $fields = self::getKeyContent($key);
      $oContent = Contents::where('key',$key)->get();
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
          if(strpos($k,'mobile')>0){
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
