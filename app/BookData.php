<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookData extends Model
{
  protected $table = 'book_data';
  
  // Put this in any model and use
  // Modelname::findOrCreate($id);
  public static function findOrCreate($k,$bID)
  {
      $obj = static::where('key',$k)->where('book_id',$bID)->first();
      if ($obj) return $obj;
      
      $obj = new static;
      $obj->key = $k;
      $obj->book_id = $bID;
      $obj->content = '';
      $obj->save();
      return $obj;
  }
}