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

    static function getKeysTxtMails() {
      /*
        *Cuando nos llega una Solicitud el cliente recibe un texto por email
        *Cuando la reserva se cambia al estado: RESERVADO / DENEGADO / CONFIRMADO/ PAGADA LA SEÑAL
        *Recordatorio 2º pago
        * email  solicitud de pago Forfaits
        *email confirmación pago Forfait
        *SMS Partee (subir dni para el control diario de huéspedes)
       */
      return [
          'new_request_rva'                   =>'Solicitud RVA',
          'reservation_state_changed_reserv'  =>'Reservado RVA',
          'reservation_state_changed_confirm' =>'Confirmado RVA',
          'reservation_state_changed_cancel'  =>'Denegada RVA',
          'reserva-propietario'               =>'RVA Propietario',
          'second_payment_reminder'           =>'Recordatorio 2º pago',
          'Forfait_email_payment_request'     =>'Solicitud de pago Forfaits',
          'Forfait_email_confirmation_payment'=>'Confirmación pago Forfait',
          'SMS_Partee_msg'                    =>'Mensaje Partee (enviar por plataforma de terceros)',
          'SMS_Partee_upload_dni'             =>'SMS Partee (subir dni para el control diario de huéspedes)'
      ];
    }
    
    static function getContent($key) {
      $Object = Settings::where('key',$key)->first();
            
      if ($Object){
       return $Object->content;
      }
      
      return '';
      
    }
   
}
