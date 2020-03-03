<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

/**
 * Description of ProcessedData
 *
 * @author cremonapg
 */
use Illuminate\Database\Eloquent\Model;

class ProcessedData extends Model{
  
      
  protected $table = 'processed_data';
      
  // Put this in any model and use
  // Modelname::findOrCreate($id);
  public static function findOrCreate($k)
  {
      $obj = static::where('key',$k)->first();
      if ($obj) return $obj;
      
      $obj = new static;
      $obj->key = $k;
      $obj->name = $k;
      $obj->save();
      return $obj;
  }

}
