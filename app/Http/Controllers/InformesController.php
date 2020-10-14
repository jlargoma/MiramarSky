<?php

namespace App\Http\Controllers;

use App\Classes\Mobile;
use Carbon\Carbon;
use App\Book;
use Illuminate\Http\Request;
use App\Http\Requests;

class InformesController extends AppController {

  public function sales_index(Request $request, $year = "") {

    $year = $this->getActiveYear();
    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $diff = $startYear->diffInMonths($endYear) + 1;
    $lstMonths = lstMonths($startYear, $endYear);

    $months_empty = array();
    $months_label = array();

    foreach ($lstMonths as $m => $v) {
      $aux = getMonthsSpanish($v['m']);
      $lstMonths[$m]['name'] = $aux;
      $months_label[] = "'$aux'";
      $months_empty[$m] = 0;
    }
    $salesGroupUser = array();
    $book = Book::where_type_book_reserved()
            ->select('total_price','customers_requests.user_id','book.start')
            ->Join('customers_requests', 'customers_requests.book_id', '=', 'book.id')
            ->where('book.start','>=',$startYear)->where('book.start','<=',$endYear)
            ->get();
    if ($book) {
      foreach ($book as $b){
        $m = date('ym', strtotime($b->start));
        $userID = $b->user_id;
        if (!isset($salesGroupUser[$userID])){
          $salesGroupUser[$userID] = $months_empty;
        }
        $salesGroupUser[$userID][$m] += $b->total_price;
        
      }

    }
    
    
 
    $allUsers = \App\User::all();
    $users = [];
    foreach ($allUsers as $u) $users[$u->id] = $u->name;
      
    $colors = colors();
    return view('backend/sales/sales/index', [
        'year' => $year,
        'salesGroupUser' => $salesGroupUser,
        'users' => $users,
        'lstMonths' => $lstMonths,
        'months_label' => $months_label,
        'month_sel' => date('m'),
        'year_sel' => ($year->year - 2000),
        'colors' => $colors,
        'isMobile' => config('isMobile'),
            ]
    );
  }

  /**
   * Get the SALES by month-years to ajax table
   * 
   * @param Request $request
   * @return Json-Objet
   */
  public function get_sales_list(Request $request, $isAjax = true) {

    $year = $request->input('year', null);
    $month = $request->input('month', null);
    $uID_sel = $request->input('user_id', null);
    if (!$year && !$month) {
      return response()->json(['status' => 'wrong']);
    }

    $response = [
        'status' => 'false',
        'respo_list' => [],
    ];

    $extrasVal  = ['conv'=>0];
    $totalType = $extrasVal;
    $monthsVdor = [];
    /** Resrvas vendidas por sistema */
    $salesUser = array();
    $qry_book = Book::where_type_book_reserved()
        ->select('total_price','customers_requests.user_id','book.start')
        ->Join('customers_requests', 'customers_requests.book_id', '=', 'book.id');
    if ($month){
      $qry_book->whereYear('book.start', '=', '20' . $year)->whereMonth('book.start', '=', $month);
    } else {
      $year = $this->getActiveYear();
      $startYear = new Carbon($year->start_date);
      $endYear = new Carbon($year->end_date);
      $qry_book->where('book.start','>=',$startYear)->where('book.start','<=',$endYear);
    }
    
    $book = $qry_book->get();
    if ($book) {
      foreach ($book as $b){
        $m = date('n', strtotime($b->start));
        $userID = $b->user_id;
        if ($uID_sel && $uID_sel != $userID) continue;
        if (!isset($salesUser[$b->user_id])) $salesUser[$b->user_id] = $extrasVal;
        $salesUser[$b->user_id]['conv'] = $b->total_price;
        $totalType['conv'] += $b->total_price;
      }
    }
    /** Resrvas vendidas por sistema */
    
    
      $allUsers = \App\User::all();
      $users = [];
      foreach ($allUsers as $u) $users[$u->id] = $u->name;
      
      $result = [];
      if (count($salesUser)>0){
        foreach ($salesUser as $uId=>$v){
          
          $aux = [];
          $aux[] = isset($users[$uId]) ? $users[$uId] : 'Usuario';
          foreach ($v as $k2=>$v2){
            $aux[] = moneda($v2,false);
            $aux[] = ($totalType[$k2] > 0) ? intval($v2/$totalType[$k2]*100) : 0 ;
          }
          $result[] = $aux;
          
        }
      }
      $response = [
          'status' => 'true',
          'result' => $result,
          'totalType' => $totalType,
          'totalConv' => moneda($totalType['conv']),
      ];
    

    if ($isAjax) {
      return response()->json($response);
    } else {
      return $response;
    }
  }

  /**
   * Get SALES Objet by Year Object
   * 
   * @param Object $year
   * @return array
   */
  private function getMonthlySales($year) {


    $startYear = new Carbon($year->start_date);
    $endYear = new Carbon($year->end_date);
    $arrayMonth = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];
    $lstMonthlyCost = [];


    $monthlyCost = \App\Book::getMonthSum('cost_limp', 'finish', $startYear, $endYear);
    foreach ($monthlyCost as $item) {
      $cMonth = intval(substr($item->new_date, 0, 2));
      $lstMonthlyCost[$cMonth] = floatval($item->total);
    }

    //Prepare objets to JS Chars
    $months_lab = '';
    $months_val = [];
    $months_obj = [];
    $thisMonth = date('m');
    $dates = getArrayMonth($startYear, $endYear);
    $selected = null;
    foreach ($dates as $d) {

      if ($thisMonth == $d['m']) {
        $selected = $d['y'] . ',' . $d['m'];
      }

      $months_lab .= "'" . $arrayMonth[$d['m'] - 1] . "',";
      if (!isset($lstMonthlyCost[$d['m']])) {
        $months_val[] = 0;
      } else {
        $months_val[] = $lstMonthlyCost[$d['m']];
      }
      //Only to the Months select
      $months_obj[] = [
          'id' => $d['y'] . '_' . $d['m'],
          'month' => $d['m'],
          'year' => $d['y'],
          'name' => $arrayMonthMin[$d['m'] - 1]
      ];
    }

    return [
        'year' => $year->year,
        'selected' => $selected,
        'months_obj' => $months_obj,
        'months_label' => $months_lab,
        'months_val' => implode(',', $months_val)
    ];
  }

}
