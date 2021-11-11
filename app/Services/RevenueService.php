<?php

namespace App\Services;
use App\Settings;
use App\Book;
use App\BookDay;
use Illuminate\Support\Facades\DB;
/**
 * 
 */
class RevenueService
{
    public $response;
    public $start;
    public $finish;
    public $days;
    public $months;
    public $books;
    public $booksSQL;
    public $booksOcup;
    public $type_book;
    public $rChannel;
    public $lstMonths;
    public $mDays;


    public function __construct()
    {
      $this->start = null;
      $this->finish = null;
      $this->books = null;
      $this->booksOcup = null;
      $this->type_book = BookDay::get_type_book_sales(true,true);
      
    }
    
    function setDates($date,$oYear=null) {
      $month = null;
      if ($date){
        $aux = explode('.',$date);
        $month = $aux[1];
        $year  = $aux[0];
      }
      if ($month<13 && $month>0){
        
        $d1 = new \DateTime($year.'-'.$month.'-01');
        $d2 = clone $d1;
        $d1->modify('first day of this month');
        $d2->modify('last day of this month');
        
        $this->start  = $d1->format("Y-m-d");
        $this->finish = $d2->format("Y-m-d");
        $this->days = ($d1->diff($d2))->days+1;
        
      } else {
        $d1 = new \DateTime($oYear->start_date);
        $d2 = new \DateTime($oYear->end_date);
        $d1->modify('first day of this month');
        $d2->modify('last day of this month');
        
        $this->start  = $d1->format("Y-m-d");
        $this->finish = $d2->format("Y-m-d");
        $this->days = ($d1->diff($d2))->days+1;
      }
      $this->months = getMonthsSpanish(null,true,true);
      unset($this->months[0]);
      
      //----------------------------------------//
      $this->lstMonths = [];
      if ($oYear){
        $aux = strtotime($oYear->start_date);
        $auxEnd = strtotime($oYear->end_date);
        $months = getMonthsSpanish(null,true,true);
        while ($aux <= $auxEnd){
          $this->lstMonths[date('y.m',$aux)] = $this->months[date('n',$aux)];
          $aux = strtotime('+1 months' , $aux);
        }
      }
    }
    
    
    //-----------------------------------------------------------//
    function setBook($type=null) {
      $this->booksSQL = BookDay::where('date', '>=', $this->start)
              ->where('date', '<=', $this->finish)
              ->whereIn('type',$this->type_book);
      $this->books = $this->booksSQL->get();
    }
    
    function setRooms() {
      $rooms = \App\Rooms::all();
      $aux = $aux2 = [];
      foreach ($rooms as $r){
        $aux[$r->id] = $r->channel_group;
      }
      
      $this->rChannel = $aux;
    }
    
    function getExtras(){
      /// BEGIN: Extras
      return [array(),array(),array()];
    }
    
    function getExpenses(){
      $m = array();
      //----------------------------------------//
      foreach ($this->lstMonths as $i => $value) $m[$i] = 0;
      $lstG = $m;
      $gastos = \App\Expenses::where('date', '>=', $this->start)
            ->where('date', '<=', $this->finish)
                    ->orderBy('date', 'DESC')->get();
      if($gastos){
        foreach ($gastos as $g){
          $lstG[date('y.m', strtotime($g->date))] += $g->import;
        }
        foreach ($lstG as $k=>$g){
          $lstG[$k] = round($g);
        }
      }
      return $lstG;
    }
    function getTotalExpenses($year){
      return \App\Expenses::whereYear('date', '=', $year)
                    ->sum('import');
    }
    
    function getIngrMonths($data){
      $aux = [];
      if($data){
        foreach ($data as $k=>$d){
          unset($d['months'][0]);
          foreach ($d['months'] as $k=>$v)
            $aux[$k] = isset ($aux[$k]) ? $aux[$k]+$v : $v;
        }
      }
      return $aux;
    }
    
    function countNightsSite() {
      return count($this->books);
    }
    
    function countBookingsSite() {
      $lst = $this->books;
      $result = 0;
      $books  = [];
      foreach ($lst as $b){
        if (!in_array($b->book_id, $books)){
          $books[] = $b->book_id;
          $result++;
        }
      }
      return $result;
    }
    function countBookingsSiteMonths() {
      $lst = $this->books;
      $result = [];
      $books  = [];
      $auxMonths = [0=>0];
      foreach ($this->lstMonths as $k2=>$v2)  $auxMonths[$k2] = 0;
      $result = $auxMonths;
      foreach ($lst as $b){
        if (!in_array($b->book_id, $books)){
          $books[] = $b->book_id;
          $am = date('y.m',strtotime($b->date));
          $result[$am]++;
          $result[0]++;
        }
      }
      
      return $result;
    }
    
    function getRatios($year = null){
      $ar = ['p'=>0,'n'=>0,
          't_s'=>0,// Total PVP Semana
          'c_s'=>0,// Total Noches Semana
          't_f'=>0,// Total PVP fin de semana (viernes&sabado)
          'c_f'=>0,// Total Noches fin de semana (viernes&sabado)
          ];

      $aRatios = [0=>$ar];
      foreach ($this->lstMonths as $k2=>$v2)  $aRatios[$k2] = $ar;
      foreach ($this->books as $b){
        $am = date('y.m',strtotime($b->date));
        $aRatios[$am]['p']+= $b->pvp;
        $aRatios[$am]['n']++;
        $day = date('w', strtotime($b->date));
        if ($day<5){
          $aRatios[$am]['c_s']++;
          $aRatios[$am]['t_s'] += $b->pvp;
        } else {
          $aRatios[$am]['c_f']++;
          $aRatios[$am]['t_f'] += $b->pvp;
        }
      }

      foreach ($aRatios as $k=>$v)
        foreach ($v as $k2=>$v2)
          $aRatios[0][$k2] += $v2;

      return $aRatios;
    }
    
    function createDaysOfMonths($year){
      $this->mDays = [];
      foreach ($this->months as $k=>$v){
        $this->mDays[$k] = cal_days_in_month(CAL_GREGORIAN, $k,$year);
      }
      $this->mDays[0] = array_sum($this->mDays);
    }
    
    function getIncomesYear($year){
      return \App\Incomes::getIncomesYear($year);
    }
    

    function getMonthSum($field,$filter,$date1,$date2) {
      $result = [];
      $lst = DB::select('SELECT new_date,room_id, SUM('.$field.') as total '
            . ' FROM ('
            . '        SELECT '.$field.',room_id,DATE_FORMAT('.$filter.', "%y.%m") new_date '
            . '        FROM book'
            . '        WHERE type_book IN ('.implode(',',$this->type_book).')'
            . '        AND '.$filter.' >= "'.$date1.'" '
            . '        AND '.$filter.' <= "'.$date2.'" '
            . '      ) AS temp_1 '
            . ' GROUP BY temp_1.room_id,temp_1.new_date'
            );
    
      foreach ($lst as $v){
          if (!isset($result[$v->new_date])) 
            $result[$v->new_date] = 0;
          $result[$v->new_date] += $v->total;
      }
      return $result;
    }
    
    
    function commisionTPVBookingsSiteMonths() {
      $lst = $this->books;
      $result = [];
      $books  = [];
      $auxMonths = [0=>0];
      foreach ($this->lstMonths as $k2=>$v2)  $aux[$k2] = 0;
      
      foreach ($lst as $b){
        if (!in_array($b->book_id, $books)){
          $books[] = $b->book_id;
        }
      }
      $payments = \App\BookOrders::where('paid',1)
              ->whereIn('book_id',$books)
              ->groupBy('updated_at')
              ->selectRaw('sum(amount) as sum, updated_at')->pluck('sum','updated_at');
      
      foreach ($payments as $d=>$p){
        if (isset($aux[date('y.m',strtotime($d))]))
          $aux[date('y.m',strtotime($d))] += $p;
      }
      foreach ($aux as $d=>$p){
        $result[$d] = round(paylandCost($p/100));
      }
      return $result;
    }
    
    function getADR_finde() {
      $lst = $this->books;
      $result = [
          't_s'=>0,// Total PVP Semana
          'c_s'=>0,// Total Noches Semana
          't_f'=>0,// Total PVP fin de semana (viernes&sabado)
          'c_f'=>0,// Total Noches fin de semana (viernes&sabado)
          ];
      foreach ($lst as $b){
          $day = date('w', strtotime($b->date));
          if ($day<5){
            $result['c_s']++;
            $result['t_s'] += $b->pvp;
          } else {
            $result['c_f']++;
            $result['t_f'] += $b->pvp;
          }
      }
      return $result;
    }
    
    
    function comparativaAnual($year){
      
      $startInic = $this->start;
      $startFinish = $this->finish;
      for($i=0;$i<5;$i++){
        $aux_year = $year-$i;
        
        $totalYear = 0;
        $oYear = \App\Years::where('year', $aux_year)->first();
        if (!$oYear) continue;
        
        $totalAnual[$aux_year] = ['months'=>null,'nigths'=>null,'pvp'=>null];
        $this->setDates(null,$oYear);
        $totalYearMonth = [0=>0];
        foreach ($this->lstMonths as $k2=>$v2)  $totalYearMonth[$k2] = 0;
        $this->setBook();
        $nigths = $pvp = 0;
       
        foreach ($this->books as $b){
          $am = date('y.m',strtotime($b->date));
          $totalYearMonth[$am] += $b->pvp;
          $totalYearMonth[0] += $b->pvp;
          $nigths++;
          $pvp += $b->pvp;
        }
//        $totalAnual[$aux_year] = $pvp;
        if ($totalYearMonth){
          $totalAnual[$aux_year]['months']  = $totalYearMonth;
          $totalAnual[$aux_year]['nigths'] = $nigths;
          $totalAnual[$aux_year]['pvp'] = $pvp;
        }
      }
      return $totalAnual;
    }
    
    function getForfaits(){
      
      $aB_IDs = [];
      $aBIDsFF = [];
      $count = 0;
      $auxStatus = [
          0,//0 'No Gestionada',
          0,//1 'Cancelada',
          0,//2 'No Cobrada',
          0,//3 'Confirmada',
          0//4 'Comprometida',
      ];
      $result = [0=>$auxStatus];
      foreach ($this->lstMonths as $k2=>$v2){
        $result[$k2] = $auxStatus;
        $books[$k2] = [];
        $totals[$k2] = 0;
      }
      
      foreach ($this->books as $b){
        if (!in_array($b->book_id, $aB_IDs)){
          $am = date('y.m',strtotime($b->date));
          $result[$am][$b->forfait]++;
          $result[0][$b->forfait]++;
          $books[$am][] = $b->book_id;
          $aB_IDs[] = $b->book_id;
        }
      }
        
//      dd($result,$totals2);
      foreach ($books as $m=>$bIDs){
        $auxItems = \App\Models\Forfaits\Forfaits::getAllOrdersSoldByBooks($bIDs);
        if ($auxItems && count($auxItems)>0){
          $total = \App\Models\Forfaits\Forfaits::getTotalByTypeForfatis($auxItems);
          foreach ($auxItems as $item) $aBIDsFF[] = $item->book_id;
          if ($total){
            $totals[$m] = $total;
          }
        }
      }
      $aBIDsFF = array_unique($aBIDsFF);
      $auxT = [];
      foreach ($totals as $ff){
        if (is_array($ff)){
          foreach ($ff as $k=>$v){
            if (!isset($auxT[$k])) $auxT[$k] = 0;
            $auxT[$k] += $v;
          }
        }
      }
      $totals[0] = $auxT;
      return ['lst'=>$result,'totals'=>$totals,'count'=>count($aBIDsFF)];
        
    }
}