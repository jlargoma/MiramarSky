<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;
use App\RevenuePickUp;
use App\Rooms;
use App\Book;
/**
 * Description of prepareYearPricesAndMinStay
 *
 * @author cremonapg
 */
class ProcessPickUp {
  

  
  public function byChannel($start,$finish) {

    $roomsID = Rooms::where('state',1)->pluck('id')->all();
    $roomsChannels = Rooms::where('state',1)->pluck('channel_group','id')->all();
    
    $dispByCh = []; //disponibilidad por channels
    foreach ($roomsChannels as $r=>$ch){
      if (!isset($dispByCh[$ch])) $dispByCh[$ch] = 0;
      $dispByCh[$ch]++;
    }
    
    //Reservas del periodo
    $books = Book::whereIn('type_book', [1,2])
          ->where([['start','>=', $start ],['finish','<=', $finish ]])
          ->whereIn('room_id',$roomsID)->get();
    
    $oneDay = 24*60*60;
      
    //Prepara la disponibilidad por día de la reserva
    $startAux = strtotime($start);
    $endAux = strtotime($finish);
    $aLstDays = [];
    $auxDay = [];
    foreach ($dispByCh as $k=>$r){
      $auxDay[$k] = 0;
    }
    
    while ($startAux<=$endAux){
        $aLstDays[date('Y-m-d',$startAux)] = $auxDay;
        $startAux+=$oneDay;
      }
    $recidencias = $aLstDays;
    $ocupaciones = $aLstDays;
    $cancelaciones = $aLstDays;
    $ingresos = $aLstDays;
    $llegada = $aLstDays;
    $salida = $aLstDays;
    $extras = $aLstDays;
    
    /*******************************/
      $dailyPVP = $aLstDays;
      $aux = [];
      foreach ($roomsChannels as $r=>$ch){
        if (!in_array($ch, $aux)){
          $aux[] = $ch;
          $oRoom = Rooms::find($r);
          $priceLst = $oRoom->getPrices_byRange( $start,$finish);
          if ($priceLst){
            foreach ($priceLst as $d=>$p){
              $dailyPVP[$d][$ch]=$p;
            }
          }
        }
      }
    /*******************************/
    if ($books){
        foreach ($books as $b){
          $startAux = strtotime($b->start);
          $endAux = strtotime($b->finish);
          $pax = ($b->real_pax>0) ? $b->real_pax : 1;
          $ch = isset($roomsChannels[$b->room_id]) ? $roomsChannels[$b->room_id] : '';
          
          $priceNigths = $b->total_price;
          $extraPrice = $b->extraPrice;
          if ($b->nigths>0){
            $priceNigths =  round(($b->total_price/$b->nigths),2);
            $extraPrice =  round(($extraPrice/$b->nigths),2);
          }
          
          $day = date('Y-m-d',$startAux);
          if (isset($llegada[$day])){
            $llegada[$day][$ch]++;
            $ingresos[$day][$ch] += $priceNigths; 
            $extras[$day][$ch] += $extraPrice; 
            $startAux+=$oneDay;
          }
          //start in the second day
          while ($startAux<$endAux){
            $day = date('Y-m-d',$startAux);
            if (isset($aLstDays[$day][$ch])){
              $recidencias[$day][$ch] += $pax;
              $ocupaciones[$day][$ch] += 1; 
              $ingresos[$day][$ch] += $priceNigths; 
              $extras[$day][$ch] += $extraPrice; 
            }
            
            $startAux+=$oneDay;
          }
          
           if (isset($salida[date('Y-m-d',$endAux)]))
                  $salida[date('Y-m-d',$endAux)][$ch]++;
        }
      }
      
    /********************************************/
    /****   CANCELS             ****************/
      //Reservas del periodo
      $books = Book::where('type_book', 98)
          ->where([['start','>=', $start ],['finish','<=', $finish ]])
          ->whereIn('room_id',$roomsID)->get();
      if ($books){
        foreach ($books as $b){
          $startAux = strtotime($b->start);
          $endAux = strtotime($b->finish);
          $ch = isset($roomsChannels[$b->room_id]) ? $roomsChannels[$b->room_id] : '';
          while ($startAux<$endAux){
            $day = date('Y-m-d',$startAux);
            if (isset($aLstDays[$day][$ch])){
              $cancelaciones[$day][$ch] += 1; 
            }
            $startAux+=$oneDay;
          }
        }
      }
    /****   CANCELS             ****************/
    /******************************************/
      
//      var_dump($dispByCh); die;
      $insert = [];
      
      foreach ($ocupaciones as $day=>$channel){
        foreach ($channel as $ch=>$v){
          if ($ch != '')
          $insert[] = [
              'day'            => $day,
              'ocupacion'      => $v,
              'disponibilidad' => $dispByCh[$ch],
              'channel'        => $ch,
              'site_id'        => -1,
              'ingresos'       => $ingresos[$day][$ch],
              'cancelaciones'  => $cancelaciones[$day][$ch],
              'llegada'        => $llegada[$day][$ch],
              'salida'         => $salida[$day][$ch],
              'extras'         => $extras[$day][$ch],
              'pvp'            => $dailyPVP[$day][$ch],
          ];
        }
      }
      $allChannels = [];
      foreach ($dispByCh as $k=>$r){
        $allChannels[] = $k;
      }
      
      RevenuePickUp::where([['day','>=', $start ],['day','<=', $finish ]])
              ->delete();
      RevenuePickUp::insert($insert);
      
      
  }
  
  
  public function compactRevenue($allRevenue) {
    $arrayAux = [];
    foreach ($allRevenue as $r){
      if (isset($arrayAux[$r->day])){
        $arrayAux[$r->day]['ocupacion']     += $r->ocupacion;
        $arrayAux[$r->day]['llegada']       += $r->llegada;
        $arrayAux[$r->day]['salida']        += $r->salida;
        $arrayAux[$r->day]['disponibilidad'] += $r->disponibilidad;
        $arrayAux[$r->day]['ingresos']      += $r->ingresos;
        $arrayAux[$r->day]['extras']        += $r->extras;
        $arrayAux[$r->day]['pvp']           += $r->pvp;
        $arrayAux[$r->day]['cancelaciones'] += $r->cancelaciones;
        $arrayAux[$r->day]['cant']          += 1;
      } else {
        $arrayAux[$r->day]['ocupacion'] = $r->ocupacion;
        $arrayAux[$r->day]['llegada'] = $r->llegada;
        $arrayAux[$r->day]['salida'] = $r->salida;
        $arrayAux[$r->day]['disponibilidad'] = $r->disponibilidad;
        $arrayAux[$r->day]['ingresos'] = $r->ingresos;
        $arrayAux[$r->day]['extras'] = $r->extras;
        $arrayAux[$r->day]['pvp'] = $r->pvp;
        $arrayAux[$r->day]['cancelaciones'] = $r->cancelaciones;
        $arrayAux[$r->day]['cant'] = 1;
      }
     
    }
    $retur = [];
    foreach ($arrayAux as $day=>$data){
      $auxRevenue = new RevenuePickUp();
      $auxRevenue->day = $day;
      $auxRevenue->ocupacion = $data['ocupacion'];
      $auxRevenue->llegada = $data['llegada'];
      $auxRevenue->salida = $data['salida'];
      $auxRevenue->disponibilidad = $data['disponibilidad'];
      $auxRevenue->ingresos = $data['ingresos'];
      $auxRevenue->extras = $data['extras'];
      $auxRevenue->cancelaciones = $data['cancelaciones'];
      $auxRevenue->pvp = round($data['pvp']/$data['cant']);
      $retur[] = $auxRevenue;
    }
    return $retur;
  }
}
