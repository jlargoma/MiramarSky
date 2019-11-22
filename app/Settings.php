<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'key',
        'value'
    ];
    
    static function getKeysSettingsGen() {
      return [
          'partee_apartament'   => array('label' => 'Partee: ID Apartamento','val'=>null),
          'send_sms_days'       => array('label' => 'Enviar SMS Partee a % días del CheckIn','val'=>null),
      ];
    }
    
    static function getKeyValue($key){     
      $obj = Settings::select('value')->where('key', $key)->first();
      if ($obj){
        return $obj->value;
      } else {
        return null;
      }
      
    }

    static function getKeysTxtMails($lng='es') {
      /*
        *Cuando nos llega una Solicitud el cliente recibe un texto por email
        *Cuando la reserva se cambia al estado: RESERVADO / DENEGADO / CONFIRMADO/ PAGADA LA SEÑAL
        *Recordatorio 2º pago
        * email  solicitud de pago Forfaits
        *email confirmación pago Forfait
        *SMS Partee (subir dni para el control diario de huéspedes)
       */
      
      $lst = [
          'new_request_rva'                   =>'Solicitud RVA',
          'reservation_state_changed_reserv'  =>'Reservado RVA',
          'reservation_state_changed_confirm' =>'Confirmado RVA',
          'reservation_state_changed_cancel'  =>'Denegada RVA',
          'reserva-propietario'               =>'RVA Propietario',
          'second_payment_reminder'           =>'Recordatorio 2º pago',
          'second_payment_confirm'            =>'Confirmación del 2º pago',
          'Forfait_email_payment_request'     =>'Solicitud de pago Forfaits',
          'Forfait_email_confirmation_payment'=>'Confirmación pago Forfait',
          'fianza_request_deferred'           =>'Envío Fianza',
          'fianza_confirm_deferred'           =>'Confirmación de Fianza',
          'SMS_forfait'                        =>'SMS Forfait',
          'SMS_fianza'                        =>'SMS Fianza',
          'SMS_Partee_msg'                    =>'Mensaje Partee (enviar por plataforma de terceros)',
          'SMS_Partee_upload_dni'             =>'SMS Partee (subir dni para el control diario de huéspedes)'
      ];
      if ($lng && $lng != 'es'){
        $lstNew = [];
        foreach ($lst as $k=>$v){
          $lstNew[$k.'_'.$lng] = $v." ($lng)";
        }
        return $lstNew;
      }
      
      return $lst;
    }
    
    static function getContent($key,$lng='es') {
      
      if ($lng && strtolower($lng) != 'es'){
        
        $Object = Settings::where('key',$key.'_en')->first();
        if (!$Object)
          $Object = Settings::where('key',$key)->first();
        
      } else {
        $Object = Settings::where('key',$key)->first();
        
      }
       
      
            
      if ($Object){
       return $Object->content;
      }
      
      return '';
      
    }
    
    static function CountriesLang(){
      $countryEs = [
          'Argentina','Bolivia','Chile','Colombia','Costa Rica','Cuba',
          'Ecuador','El Salvador','España','Guatemala','Guinea Ecuatorial',
          'Honduras','México','Nicaragua','Panamá','Paraguay','Perú',
          'República Dominicana','Uruguay','Venezuela'
          ];
      
      $lst = [];
      foreach ($countryEs as $c){
        $lst['es'] = $c;
      }
      
      $lst['en'] = 'Otros';
      return $lst;
    }
   
}
