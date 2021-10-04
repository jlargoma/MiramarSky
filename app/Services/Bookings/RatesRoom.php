<?php

namespace App\Services\Bookings;

use App\Rooms;
use App\Book;
use App\Seasons;
use DateTime;
use DateInterval;
use DatePeriod;

/**
 * Description of GetBooksLimp
 *
 * @author cremonapg
 */
class RatesRoom {

  var $allDay;
  var $start;
  var $end;
  var $seasson;

  public function __construct() {
    $this->allDay = [];
  }

  function setDates($oYear) {
    $this->start = $oYear->start_date;
    $this->end = $oYear->end_date;
    $year = $oYear->year-2000;
    $this->seasson = $year.'-'.($year+1);
  }

  function setSeassonDays() {

    $allDays = [];

    $inicio = new DateTime($this->start);
    $intervalo = new DateInterval('P1D');
    $fin = new DateTime($this->end);
    $periodo = new DatePeriod($inicio, $intervalo, $fin);
    foreach ($periodo as $fecha) {
      $allDay[$fecha->format('Ymd')] = 0;
    }


    $match1 = [['start_date', '>=', $this->start], ['start_date', '<=', $this->end]];
    $match2 = [['finish_date', '>=', $this->start], ['finish_date', '<=', $this->end]];
    $match3 = [['start_date', '<', $this->start], ['finish_date', '>', $this->end]];

    $oSeasons = Seasons::where(function ($query) use ($match1, $match2, $match3) {
              $query->where($match1)
                      ->orWhere($match2)
                      ->orWhere($match3);
            })->get();
    if ($oSeasons) {
      foreach ($oSeasons as $s) {
        $inicio = new DateTime($s->start_date);
        $intervalo = new DateInterval('P1D');
        $fin = new DateTime($s->finish_date);
        $periodo = new DatePeriod($inicio, $intervalo, $fin);
        foreach ($periodo as $fecha) {
          $allDay[$fecha->format('Ymd')] = $s->type;
        }
        $allDay[$fin->format('Ymd')] = $s->type;
      }
    }
    $this->allDay = $allDay;
  }

  function getCalendar() {

    $inicio = new DateTime($this->start);
    $iMonth = new DateInterval('P1M');
    $iDay = new DateInterval('P1D');
    $fin = new DateTime($this->end);
    $periodo = new DatePeriod($inicio, $iMonth, $fin);
    $months = getMonthsSpanish(null, false, true);
    $header = '<tr><th>L</th><th>M</th><th>M</th><th>J</th><th>V</th><th>S</th><th>D</th></tr>';

    $return = [];
    foreach ($periodo as $date) {
      $start = clone $date;
      $start->modify('first day of this month');
      $end = clone $date;
      $end->modify('last day of this month');
      $end->modify('+1 Days');
      $days = new DatePeriod($start, $iDay, $end);

      $html = '<h3>' . $months[$date->format('n')] . ' ' . $date->format('Y') . '</h3>'
              . '<table>' . $header . '<tr>';

      $N = $start->format('N')-1;
      if ($N > 0)
        $html .= '<td colspan="' . $N . '"></td>';
      $w = $start->format('W');
      $countTR = 0;
      if ($start->format('N') > 7) $countTR--; //ajusto las que comienzan el lunes
      foreach ($days as $d) {
        if ($d->format('N')==1) {
          $html .= '</tr><tr>';
          $countTR++;
        }
        $class = $this->allDay[$d->format('Ymd')];
        $html .= '<td class="s' . $class . '">' . $d->format('d') . '</td>';
      }
      $rest = substr($html, -5);
      if ($rest != '</tr>')
        $html .= '</tr>';
      
      
      //para que todos tengan la misma cantidad de items
      for($i=$countTR; $i<5; $i++)
        $html .= '<tr><td colspan="7">&nbsp; </td></tr>';
      
      $html .= '</table>';
      $return[] =  $html;
    }
    
    return $return;
  }
  
  function getSessionTypes(){
    $lst = \App\TypeSeasons::all();
    $result = '<div class="sessionType"><table><tr>';
    foreach ($lst as $s){
      $result .= '<td class="s'.$s->id.'">'.$s->name.'</td>';
    }
    $result .= '</tr></table></div>';
    return $result;
  }
  
  function getStyles(){
    return '<style>
        .rateCalendar{
          display: block;
          width: 100%;
          clear: both
        }
        .rateCalendar .item{
          width: 170px;
          padding: 7px;
          text-align: center;
          display: inline-block;
        }
        .rateCalendar .item h3{
          background-color: #2a5d9b;
          color: #FFF;
          font-size: 14px;
          text-align: center;
          margin: 0px;
          font-weight: bold;
        }
        .rateCalendar .item table{
          width: 100%;
          font-size: 12px;
          border: 1px solid #dadada;
        }
        .rateCalendar .item table th,
        .dt.day{
          background-color: #dadada;
          padding: 4px;
        }
        .rateCalendar .item table th,
        .rateCalendar .item table td,
        .rateCost table th,
        .rateCost table td{
          text-align: center;
          padding: 2px!important;
        }
        .rateCost table{
          width: 100%;
        }
        .rateCost table tbody tr {
          background-color: #FFF;
          border: 1px solid #eaeaea;
        }
        .rateCost table td {
          font-weight: bold;
          font-size: 14px !important;
        }
        .rateCost table th {
          font-size: 16px;
        }
        .rateCost table th small {
          font-size: 75%;
        }
        .sessionType {
          padding: 1em;
        }
        .sessionType table {
          width: 100%;
        }

        .sessionType table td {
          padding: 5px 15px !important;
          font-size: 17px;
          text-align: center;
        }
        .rateCalendar .item table td.s1,
        .sessionType table td.s1,
        .dt.s1,
        .rateCost table th.s1{
          /*.Alta*/
          background: #f0513c;
          color: white;
        }
        .rateCalendar .item table td.s2,
        .dt.s2,
        .sessionType table td.s2,
        .rateCost table th.s2{
          /*.Media*/
          background-color: #127bbd;
          color: white;
        }
        .rateCalendar .item table td.s0,
        .rateCalendar .item table td.s3,
        .dt.s0,
        .dt.s3,
        .sessionType table td.s3,
        .rateCost table th.s3{
          /*.Baja*/
          color: white;
          background-color: #91b85d;
        }
        .rateCalendar .item table td.s4,
        .dt.s4,
        .sessionType table td.s4,
        .rateCost table th.s4{
          /*.Premium*/
          background-color: #ff00b1;
          color: white;
        }
        .rateCalendar .item table td.s5,
        .dt.s5,
        .sessionType table td.s5,
        .rateCost table th.s5{
          /*Verano*/
          background-color: #51b1f778;
          color: #444444;
        }
        .min-5em{
          min-width: 5em;
        }
      </style>';
  }

  function getTarifas($oRoom = null){
    $seasons = \App\TypeSeasons::all();
    
    $html = '<table><thead><tr><th class="min-5em"> &nbsp;&nbsp;</th>';
    
    foreach ($seasons as $key => $season)
      $html .= '<th class ="s'.$season->id.'">'.$season->name.'<br/><small>Precio</small></th>';
    
    $html .= '</tr></thead><tbody>';
    for ($i=4; $i <= 14 ; $i++){
      if ($oRoom){
        if ($i >= $oRoom->minOcu && $i <= $oRoom->maxOcu){
          $html .= '<tr><td>'.$i.' Pers.</td>';
          foreach ($seasons as $key => $season){
            $price =  \App\Prices::where('occupation', $i)->where('season', $season->id )->first(); 
            $html .= '<td>'.($price ? moneda($price->cost) : '').'</td>';
          }
          $html .= '</tr>';
        }
      } else {
        $html .= '<tr><td>'.$i.' Pers.</td>';
        foreach ($seasons as $key => $season){
          $price =  \App\Prices::where('occupation', $i)->where('season', $season->id )->first(); 
          $html .= '<td>'.($price ? moneda($price->cost) : '').'</td>';
        }
        $html .= '</tr>';
      }
    }
       
    $html .= '</tbody></table>';
  
    return $html;
  }
  
  function printCalendar(){
    $return = '<div class="rateCalendar" >';
    foreach ($this->getCalendar() as $c) {
      $return .=  '<div class="item" >' . $c . '</div>';
    }
    $return .= '</div>';
    return $return;
    
    
  
    
  }
  
  function printTarifas($oRoom){
    return $this->getSessionTypes()
            .'<div class="rateCost">'.$this->getTarifas($oRoom).'</div>';
  }
  
  
  function getCalendar2() {

    $inicio = new DateTime($this->start);
    $iMonth = new DateInterval('P1M');
    $iDay = new DateInterval('P1D');
    $fin = new DateTime($this->end);
    $periodo = new DatePeriod($inicio, $iMonth, $fin);
    $months = getMonthsSpanish(null, false, true);
    $div = '<div class="dt ';
    $header = '<div>'
            . $div.' day">L</div>'
            . $div.' day">M</div>'
            . $div.' day">M</div>'
            . $div.' day">J</div>'
            . $div.' day">V</div>'
            . $div.' day">S</div>'
            . $div.' day">D</div>'
            . '</div>';

    $return = [];
    foreach ($periodo as $date) {
      $start = clone $date;
      $start->modify('first day of this month');
      $end = clone $date;
      $end->modify('last day of this month');
      $end->modify('+1 Days');
      $days = new DatePeriod($start, $iDay, $end);

      
       $html = '<div class="month">' . $months[$date->format('n')] . ' ' . $date->format('Y') . '</div>'
             . $header . '<div>';
       
//      $html = $header . '<div>';

      $N = $start->format('N')-1;
      for($i=0;$i<$N;$i++)  $html .= $div.'"></div>';
      $w = $start->format('W');
      $countTR = 0;
      if ($start->format('N') > 7) $countTR--; //ajusto las que comienzan el lunes
      foreach ($days as $d) {
        if ($d->format('N')==1) {
          $html .= '</div><div>';
          $countTR++;
        }
        $class = $this->allDay[$d->format('Ymd')];
        $html .= $div.' s' . $class . '">' . $d->format('d') . '</div>';
      }
      
      $N = 7-$end->format('N')+1;
      for($i=0;$i<$N;$i++)  $html .= $div.'"></div>';
      
      $rest = substr($html, -5);
      if ($rest != '</div>')
        $html .= '</div>';
      
      $return[] =  $html;
    }
    
    return $return;
  }
}
