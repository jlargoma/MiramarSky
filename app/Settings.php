<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'key',
        'value'
    ];
    
    /*
     * key basics to get price of book and pay it
     * */
    const PARK_COST_SETTING_CODE      = "parking_book_cost";
    const PARK_PVP_SETTING_CODE       = "parking_book_price";
    const LUXURY_COST_SETTING_CODE    = "luxury_book_cost";
    const LUXURY_PVP_SETTING_CODE     = "luxury_book_price";
    const DISCOUNT_BOOKS_SETTING_CODE = "discount_books";
    const BREAKFAST_COST_SETTING_CODE = "breakfast_book_cost";
    const BREAKFAST_PVP_SETTING_CODE  = "breakfast_book_price";


    private $settingsForBooks = [
        self::PARK_COST_SETTING_CODE   => 'Cost Sup Park',
        self::PARK_PVP_SETTING_CODE    => 'PVP Sup Park',
        self::LUXURY_COST_SETTING_CODE => 'Cost Sup Lujo',
        self::LUXURY_PVP_SETTING_CODE  => 'PVP Sup Lujo',
        self::BREAKFAST_COST_SETTING_CODE   => 'Cost Desayuno',
        self::BREAKFAST_PVP_SETTING_CODE    => 'PVP Desayuno',
        self::DISCOUNT_BOOKS_SETTING_CODE  => 'Descuento directo sobre las reservas ',
        //'book_instant_payment',
    ];
    
    static function getKeysSettingsGen() {
      return [
          'partee_apartament'   => array('label' => 'Partee: ID Apartamento','val'=>null),
          'send_sms_days'       => array('label' => 'Enviar SMS Partee a % días del CheckIn','val'=>null),
      ];
    }
    public function settingsForBooks() {
      return $this->settingsForBooks;
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
      
      $lng = self::getLenguaje(strtoupper($lng));
      
      $lst = [
          'new_request_rva'                   =>'Solicitud RVA',
          'reservation_state_changed_reserv'  =>'Reservado RVA',
          'reservation_state_changed_reserv_ota'  =>'Reservado RVA',
          'reservation_state_changed_confirm' =>'Confirmado RVA',
          'reservation_state_changed_cancel'  =>'Denegada RVA',
          'reserva-propietario'               =>'RVA Propietario',
          'second_payment_reminder'           =>'Recordatorio 2º pago',
          'second_payment_confirm'            =>'Confirmación del 2º pago',
          'Forfait_email_payment_request'     =>'Solicitud de pago Forfaits',
          'Forfait_email_confirmation_payment'=>'Confirmación pago Forfait',
          'payment_receipt'                   =>'Mail de recibos de pagos',
          'book_email_buzon'                  =>'Envío de datos del Buzón',
          'fianza_request_deferred'           =>'Envío Fianza',
          'fianza_confirm_deferred'           =>'Confirmación de Fianza',
          'SMS_buzon'                         =>'SMS Buzón',
          'SMS_forfait'                        =>'SMS Forfait',
          'SMS_fianza'                        =>'SMS Fianza',
          'SMS_Partee_msg'                    =>'Mensaje Partee (enviar por plataforma de terceros)',
          'SMS_Partee_upload_dni'             =>'SMS Partee (subir dni para el control diario de huéspedes)',
          'send_encuesta'                     =>'Mail de Encuestas',
          'send_encuesta_subject'             =>'Asunto de Encuestas',
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
      
      $lng = self::getLenguaje(strtoupper($lng));
      $Object = null;
      if ($lng == 'en'){
        $Object = Settings::where('key',$key.'_en')->first();
      }

      if (!$Object){
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
   
    
    static function priceParking(){
      $parkCostSetting = self::where('key','parking_book_cost')->first();
      if (!$parkCostSetting) return 0;
      return $parkCostSetting->value;
    }
    
    static function priceLujo(){
      $luxuryCostSetting = self::where('key', 'luxury_book_cost')->first();
      if ($luxuryCostSetting)  return $luxuryCostSetting->value;
      return 0;
    }
}
