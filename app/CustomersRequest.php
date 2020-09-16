<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomersRequest extends Model
{
  public static function createOrEdit($data,$site_id){
    if (isset($data['usr'])){
      $usr = $data['usr'];
      $email = isset($usr['c_mail']) ? trim($usr['c_mail']) : '';
      $customer = CustomersRequest::where('email',$email)->first();
      if (!$customer){
        $customer = new CustomersRequest();
        $customer->email = $email;
        $customer->site_id = $site_id;
      }
      
      if(isset($usr['c_name']))  $customer->name = trim($usr['c_name']);
      if(isset($usr['c_phone'])) $customer->phone = trim($usr['c_phone']);
      if(isset($usr['c_ip']))    $customer->ip = trim($usr['c_ip']);
      if(isset($data['pax']))    $customer->pax = trim($data['pax']);
      if(isset($data['start']))  $customer->start = trim($data['start']);
      if(isset($data['end']))    $customer->finish = trim($data['end']);
      $customer->status = 0;
      $customer->save();
    }   
  }
}
