<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $table = 'expenses';
     
  static function getTypes(){
    return [
        'prop_pay' => 'PAGO PROPIETARIOS',
        'excursion' => 'PROVEEDORES EXCURSIÓN',
        'agencias' => 'AGENCIAS',
        'amenities' => 'AMENITIES',
        'comision_tpv' => 'COMSION TPV',
        'lavanderia' => 'LAVANDERIA',
        'limpieza' => 'LIMPIEZA',
        'mantenimiento' => 'REPARACION Y CONSERVACION',
        'sueldos' => 'SUELDOS Y SALARIOS',
        'seg_social' => 'SEG SOCIALES',
        'serv_prof' => 'SERVICIOS PROF INDEPENDIENTES',
        'alquiler' => 'ALQUILER INMUEBLES',
        'seguros' => 'PRIMAS SEGUROS',
        'suministros' => 'SUMINISTROS',
        'equip_deco' => 'EQUIPACION Y DECORACION',
        'sabana_toalla' => 'TEXTIL Y MENAJE',
        'bancario' => 'GASTOS BANCARIOS',
        'representacion' => 'GASTOS REPRESENTACION',
        'publicidad' => 'MARKETING Y PUBLICIDAD',
        'mensaje' => 'MENSAJERIA',
        'varios' => 'VARIOS',
//        'impuestos' => 'IMPUESTOS',
//        'comisiones' => 'COMISIONES COMERCIALES',
    ];
  }
  
  
  static function getTypesImp(){
    return [
        'agencias' => 'AGENCIAS',
        'alquiler' => 'ALQUILER INMUEBLES',
        'amenities' => 'AMENITIES',
//        'comisiones' => 'COMISIONES COMERCIALES',
        'excursion' => 'PROV. EXCURSIÓN',
        'comision_tpv' => 'COMSION TPV',
        'equip_deco' => 'EQUIPACION Y DECORACION',
        'bancario' => 'GASTOS BANCARIOS',
        'representacion' => 'GASTOS REPRESENTACION',
        'lavanderia' => 'LAVANDERIA',
        'limpieza' => 'LIMPIEZA',
        'publicidad' => 'MARKETING Y PUBLICIDAD',
        'mensaje' => 'MENSAJERIA',
        'serv_prof' => 'SERVICIOS PROF INDEPENDIENTES',
        'suministros' => 'SUMINISTROS',
        'sabana_toalla' => 'TEXTIL Y MENAJE',
        'varios' => 'VARIOS',
    ];
  }
  
  static function getTypesGroup(){
    return [
            'names'=> [
              'agencias' => 'AGENCIAS',
              'alquiler' => 'ALQUILER INMUEBLES',
              'comision_tpv' => 'COMSION TPV',
              'limpieza' => 'LAVANDERIA Y LIMPIEZA',
              'prop_pay' => 'PAGO PROPIETARIOS',
              'otros' => 'RESTO GASTOS',
              'empleados' => 'SUELDOS Y SEG SOCIAL',
              'suministros' => 'SUMINISTROS',
                
            ],
            'groups' => [
                'agencias' => 'agencias',
                'alquiler' => 'alquiler',
                'comision_tpv' => 'comision_tpv',
                'lavanderia' => 'limpieza',
                'limpieza'   => 'limpieza',
                'prop_pay'   => 'prop_pay',
                'seg_social' => 'empleados',
                'sueldos'    => 'empleados',
                'suministros'=> 'suministros',
                'excursion'=> 'otros',
            ]];
        
  }
  
  //Para poner nombre al tipo de cobro//
  static function getTypeCobro($typePayment=NULL) {
    $array = [
        0 => "Tarjeta visa",//"Metalico Jorge",
        2 => "CASH",// "Metalico Jaime",
        3 => "Banco",//"Banco Jorge",
    ];

    if (!is_null($typePayment)){
      return isset($array[$typePayment]) ? $array[$typePayment] : $typePayment;
    }
    
    return $array;
  }
  
  static function getListByRoom($start,$end,$roomID){
    return self::where('date', '>=', $start)
            ->Where('date', '<=', $end)
            ->Where('PayFor', 'LIKE', '%' . $roomID. '%')       
            ->orderBy('date', 'DESC')
            ->get();
  }
  static function getPaymentToProp($start,$end){
    return self::where('date', '>=', $start)
                    ->Where('date', '<=', $end)
                    ->WhereNotNull('PayFor')
                    ->Where('PayFor', '!=','') 
                    ->orderBy('date', 'DESC')->get();
  }
  static function getTotalPaymentToProp($start,$end){
    return self::where('date', '>=', $start)
            ->Where('date', '<=', $end)
            ->WhereNotNull('PayFor')       
            ->Where('PayFor', '!=','')       
            ->sum('import');
  }
  
  static function getTypesOrderned(){
    $types =  [
      'prop_pay'=>"PAGO PROPIETARIOS", //PAGO PROPIETARIO</option>
      'agencias'=>"AGENCIAS", 
      'comision_tpv'=>"COMSION TPV",
      'serv_prof'=>"SERVICIOS PROF INDEPENDIENTES", // SERVICIOS PROF INDEPENDIENTES</option>
      'varios'=>"VARIOS", // VARIOS</option>
      'amenities'=>"AMENITIES", 
      'lavanderia'=>"LAVANDERIA", // LAVANDERIA</option>
      'limpieza'=>"LIMPIEZA", // LIMPIEZA</option>
      'equip_deco' => 'EQUIPACION Y DECORACION',
      'sabana_toalla'=>"TEXTIL Y  MENAJE", // SABANAS Y TOALLAS</option>
      'impuestos'=>"IMPUESTOS", // IMPUESTOS</option>
      'bancario'=>"GASTOS BANCARIOS", // GASTOS BANCARIOS</option>
      'publicidad'=>"MARKETING Y PUBLICIDAD", // MARKETING Y PUBLICIDAD</option>
      'mantenimiento'=>"REPARACION Y CONSERVACION", // REPARACION Y CONSERVACION</option>
      'sueldos'=>"SUELDOS Y SALARIOS", // SUELDOS Y SALARIOS</option>
      'seg_social'=>"SEG SOCIALES", // SEG SOCIALES</option>
      'mensaje'=>"MENSAJERIA", // MENAJE</option>
      'comisiones'=>"COMISIONES COMERCIALES", // COMISIONES COMERCIALES</option>
      'suministros'=>"SUMINISTROS", 
      'alquiler'=>"ALQUILER INMUEBLES",
      'seguros'=>"PRIMAS SEGUROS", 
      'representacion'=>"GASTOS REPRESENTACION", 
        'excursion' => 'PROVEEDORES EXCURSIÓN',
        
        
//      'decoracion'=>"DECORACIóN", // DECORACION</option>
//      'equi_vivienda'=>"EQUIPAMIENTO VIVIENDA", // EQUIPAMIENTO VIVIENDA</option>
//      'regalo_bienv'=>"AMENITIES", // REGALO BIENVENIDA</option>
    ];
    
    $aux = [];
    foreach ($types as $k=>$v){
      $aux[] = $v;
    }
    sort($aux);
    foreach ($aux as $k=>$v){
      $kType = array_search($v,$types);
      echo "'".$kType."' => '".$v."',<br/>";
    }
    die;
  }
  
  
  static function delExpenseLimpieza($book_id) {
    if ($book_id>0){
      \App\Expenses::where('book_id',$book_id)->where('type','limpieza')->delete();
      \App\Incomes::where('book_id',$book_id)->where('type','limp_prop')->delete();
    }
  }
  
  static function setExpenseLimpieza($book_id,$room, $date,$amount=null) {
    //UPDATE 
    if ($book_id>0){
      $obj = \App\Expenses::where('book_id',$book_id)->where('type','limpieza')->first();
      if ($obj){
        $obj->date = $date;
        $obj->save();
      } else {
        //create
        $obj = new \App\Expenses();
        $obj->concept = "LIMPIEZA RESERVA PROP. " . $room->nameRoom;
        $obj->date = $date;
        $obj->import = $amount;
        $obj->typePayment = 3;
        $obj->book_id = $book_id;
        $obj->type = 'limpieza';
        $obj->comment = "LIMPIEZA RESERVA PROPIETARIO " . $room->nameRoom;
        $obj->PayFor = $room->id;
        $obj->save();
      }
    }
    return true;
  }
  
  
  static function getExpensesBooks(){
    return [
        'prop_pay'      => 0,
        'agencias'      => 0,
        'comision_tpv'  => 0,
        'limpieza'      => 0,
        'lavanderia'    => 0,
        'amenities'     => 0,
        'mantenimiento' => 0,
      ];
  }
}
