<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Classes\Mobile;
use App\Book;
use Auth;
use App\RoomsElectricity;


class DataDisController extends AppController {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($name = "") {
    
    return view('backend.planning.dataDis.index',['content'=>'']);
    
  }
  public function calendar($month=null) {
    if ($month == 'all') $month = null;
    return $this->getCalendarView($month,[115,142,163,155]);
//    return $this->getCalendarView(null,[115,142]);
    
  }


  public function getCalendarView($month=null,$roomIDs= null){
        $mes           = [];
        $arrayReservas = [];
        $arrayMonths   = [];
        $arrayDays     = [];
        $oYear          = $this->getActiveYear();
        $startYear     = $oYear->start_date;
        $endYear       = $oYear->end_date;
        $totalSales    = 0;
        
        $type_book_sales = [1,2,8,11];
        $uRole = Auth::user()->role;
        $mobile = new Mobile();
        $isMobile = $mobile->isMobile();
        if (!$month){
          $month = strtotime($oYear->year.'-'.date('m').'-01');
          if (strtotime($startYear)>$month){
            $month = strtotime(($oYear->year+1).'-'.date('m').'-01');
          }
        }
        /*------------------------------------------------*/
        
        $aux = strtotime($startYear);
        $auxEnd = strtotime($endYear);
        $aMonths = getMonthsSpanish(null,true,true);
        while ($aux <= $auxEnd){
          $auxMonth = date('n',$aux);
          $auxYear = date('y',$aux);
          $lstMonths[$aux] = [
              't'=>$aMonths[$auxMonth].' '.$auxYear,
              'm'=>$auxMonth
          ];
          $aux = strtotime('+1 months' , $aux);
        }
        /*------------------------------------------------*/
        $currentM = date('n',$month);
        $startAux = date('Y-m-01', strtotime('-1 months',$month));
        $endAux = date('Y-m-t', strtotime('+1 months',$month));
        /*------------------------------------------------*/
        $oRooms = \App\Rooms::where('state', '=', 1)->whereIn('id', $roomIDs)->orderBy('order', 'ASC')->get();
        $emptyReservas = arrayDays($startAux,$endAux,'Y-m-d','array');
        foreach ($oRooms as $r) $arrayReservas[$r->id] = $emptyReservas;
        /*------------------------------------------------*/
        $arrayReservas = $this->getBookingEvents($startAux,$endAux,$arrayReservas,$isMobile);
        /*------------------------------------------------*/
        $aRoomsElectricity = $this->getRoomsElectricity($startAux,$endAux,$roomIDs);
        /*------------------------------------------------*/
        $dayWeek = ["D","L","M","X","J","V", "S"];
        
        $lstDays = arrayDays($startAux,$endAux,'y-n-d','w');
        foreach ($lstDays as $d=>$week){
          $aDay = explode('-', $d);
          $mes[$aDay[1]] = $aDay[0];
          $arrayMonths[$aDay[1]] = $aDay[2];
          $days[$aDay[1]][intval($aDay[2])] = $dayWeek[$week];
        }
        
        
        foreach ($mes as $m=>$y) $mes[$m] = $aMonths[$m].' '.$y;
        
       
        $buffer = ob_html_compress(view('backend.planning.dataDis.calendar.content', 
                    compact('arrayMonths','oRooms', 'arrayReservas', 'mes','lstMonths',
                      'days', 'startYear', 'endYear','currentM','startAux',
                      'lstDays','aRoomsElectricity','aMonths')
                  ));
      return view('backend.planning.dataDis.calendar.index',['content'=>$buffer]);
      
    }
    
     /**
     * Prepare the event to show in the calendar
     * @param type $book
     * @param type $uRole
     * @return type
     */
    private function calendarEvent($book,$isMobile) {
      
      $class = $book->getStatus($book->type_book);
      if ($class == "Contestado(EMAIL)"){ $class = "contestado-email";}
      $classTd = ' class="td-calendar" ';
      $titulo = '';
      $agency = '';
      $href = 'href="'.url ('/admin/reservas/update').'/'.$book->id.'" ';
      if (true){
        $agency = ($book->agency != 0) ? "Agencia: ".$book->getAgency($book->agency).'<br/>' : "";
        $titulo = $book->customer->name.'<br/>'.
              'Pax-real '.$book->real_pax.'<br/>'.
              dateMin($book->start).' - '.dateMin($book->finish).'<br/>';
      
        $titulo .= $agency;
      }
      
      if ($isMobile){
        $titulo .= '<div class="calLink" data-'.$href.'>Consumo diario</div>';
        $href = '#';
      }
      $return = json_encode([
          'start' => $book->start,
          'finish' => $book->finish,
          'type_book'=>$book->type_book,
          'titulo'  => $titulo,
          'classTd' => $classTd,
          'href' => $href,
          'class' => $class,
      ]);
      
      return json_decode($return);
    }
    
    /**
     * 
     * @param type $startAux
     * @param type $endAux
     * @param type $arrayReservas
     * @return type
     */
    function getBookingEvents($startAux,$endAux,$arrayReservas,$isMobile){
      $type_book_not = [0,3,6,12,98,99];
      $books = Book::where_book_times($startAux,$endAux)
              ->whereNotIn('type_book', $type_book_not)
              ->whereIn('room_id', array_keys($arrayReservas))
              ->orderBy('start', 'ASC')->get();

      foreach ($books as $book)
      {
        $start = strtotime($book->start);
        $finish = strtotime($book->finish);
        $event = $this->calendarEvent($book,$isMobile);
        while($start<$finish){
          $auxDate = date('Y-m-d',$start);
          if (isset($arrayReservas[$book->room_id][$auxDate]))
            $arrayReservas[$book->room_id][$auxDate][] = $event;
          $start = strtotime("+1 day", $start);
        }

        $auxDate = date('Y-m-d',$finish);
        if (isset($arrayReservas[$book->room_id][$auxDate]))
          $arrayReservas[$book->room_id][$auxDate][] = $event;

      }
      return $arrayReservas;
      
    }
    
    function getRoomsElectricity($startAux,$endAux,$roomIDs){
        $lstCons = RoomsElectricity::whereIn('room_id',$roomIDs)
                ->where('day','>=',$startAux)
                ->where('day','<=',$endAux)->get();
        $aResult = [];
        if ($lstCons){
          foreach ($lstCons as $item){
            if (!isset($aResult[$item->room_id]))
              $aResult[$item->room_id] = [];
            
            $aResult[$item->room_id][$item->day] = $item->consumption;
          }
        }
        return $aResult;
    }
    
    
    function getDay($rID,$day){
      
      $dataDis = new \App\Services\DataDis\Api();
      $oConfig = new \App\Services\DataDis\Config();
      $oRoom = \App\Rooms::find($rID);
      if (!$oRoom){
        return '<div class="alert alert-warning">Habitación no encontrada</div>';
      }
      
      $aRooms = $oConfig->getRooms();
      if (!isset($aRooms[$rID])){
        return '<div class="alert alert-warning">Habitación no encontrada</div>';
      }
      $rName = $oRoom->nameRoom.' ('.$oRoom->name.')';
      
      //-----------------------------------------------------------------//
      $cKey = md5('DD'.$rID.$day);
      $sCache = new \App\Services\CacheData($cKey);
      $cache = $sCache->get();
      if ($cache){
        return view('backend.planning.dataDis.daily',$cache);
      }
      
      //-----------------------------------------------------------------//
    
    
    
      $data = [];
      $hours = [];
      if ($dataDis->conect()){
        $date = str_replace('-','/',$day);
        $rData = $aRooms[$rID];
        $param = [
            'cups'=>[$rData['cups']],
            'distributor' => $rData['distributorCode'],
            'fechaInicial' => $date,
            'fechaFinal' => $date,
            'tipoPuntoMedida' => $rData['pointType'],
        ];
        
        $result = $dataDis->getTimeCurveDataDays($param);
        if ($result){
     
          
          if (isset($result->response->timeCurveList))
            foreach ($result->response->timeCurveList as $r){
              $hour = explode(':', $r->hour);
              $hours[] = $hour[0].' hr';
              $data[] = $r->measureMagnitudeActive;
            }
        }
      }
      $viewData = ['nRoom'=>$rName,'hours'=>$hours,'data'=>$data,'day'=> convertDateToShow_text($day)];
      if (count($data)) $sCache->set($viewData,36000000);
      
      return view('backend.planning.dataDis.daily',$viewData);
    }
    
    
}
