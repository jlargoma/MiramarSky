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
    ];
  }
  
  
  static function getKeyContent($key){
    switch ($key){
      case 'edificio':
        return [
          'title'=>['Título','string',null],  
          'content_1'=>['Primer texto','ckeditor',null],  
          'imagen_1'=>['Primer Imagen','file',null],  
          'title_2'=>['Segundo Título','string',null],  
          'content_2'=>['Segundo texto','ckeditor',null],  
          'imagen_2'=>['Segunda Imagen','file',null],  
        ];
        break;
      case 'services':
        return [
          'title_1'=>['Título','string',null],  
          'imagen_1'=>['Primer Imagen ( 630*420 px )','file',null],  
          'link_1'=>['Primer enlace','string',null],  
          'title_2'=>['Segundo Título','string',null],  
          'imagen_2'=>['Segunda Imagen ( 630*420 px )','file',null],  
          'link_2'=>['Segundo enlace','string',null],  
          'title_3'=>['Tercer Título','string',null],  
          'imagen_3'=>['Tercer Imagen ( 630*420 px )','file',null],  
          'link_3'=>['Tercer enlace','string',null],  
        ];
        break;
    }
    return [ ];
  }
  
  public function getContentByKey($key) {
    
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
      
    }
    
    return $result;
  }
}
