<?php

namespace App\Services;

use App\Settings;
use App\Book;
use App\BookDay;
use Illuminate\Support\Facades\DB;
use App\Liquidacion;

/**
 * 
 */
class PerdGanancia
{
  public $response;
  public $start;
  public $finish;
  public $oYear;
  public $months;
  public $books;
  public $booksSQL;
  public $type_book;
  public $lstMonths;
  public $emptyMonths;
  public $ingr;
  public $gastos;
  public $ffData;
  public $excels;
  public $bruto;

  public function __construct()
  {
    $this->start = null;
    $this->finish = null;
    $this->books = null;
    $this->type_book = BookDay::get_type_book_sales(true, true);
  }

  function setDates($oYear = null)
  {
    $this->oYear = $oYear;
    $d1 = new \DateTime($oYear->start_date);
    $d2 = new \DateTime($oYear->end_date);
    $d1->modify('first day of this month');
    $d2->modify('last day of this month');

    $this->start  = $d1->format("Y-m-d");
    $this->finish = $d2->format("Y-m-d");
    $this->days = ($d1->diff($d2))->days + 1;
    $this->months = getMonthsSpanish(null, true, true);
    unset($this->months[0]);

    //----------------------------------------//
    $this->lstMonths = [];
    $intev = new \DateInterval('P1M');
    $periodo = new \DatePeriod($d1, $intev, $d2);
    foreach ($periodo as $f) {
      $aux = $f->format('Y-m');
      $this->lstMonths[$aux] = ['m' => $f->format('n'), 'y' => $f->format('y'), 'name' => getMonthsSpanish($f->format('n'))];
      $this->emptyMonths[$aux] = 0;
    }
  }
  //-----------------------------------------------------------//
  function setBook()
  {
    $this->booksSQL = BookDay::where('date', '>=', $this->start)
      ->where('date', '<=', $this->finish)
      ->whereIn('type', $this->type_book);
    $this->books = $this->booksSQL->get();
  }
  //-----------------------------------------------------------//
  function setObjs()
  {
    $ingresos = ['total' => $this->emptyMonths, 'rvs' => $this->emptyMonths];
    $ingrType = \App\Incomes::getTypes();
    $lstT_ing = ['rvs' => 0];
    $ingresos['ff'] = $this->emptyMonths;
    $lstT_ing['ff'] = 0;
    $aIngrPending['ff'] = 0;
    $aIngrPending['rvs'] = 0;

    foreach ($ingrType as $k => $t) {
      $ingresos[$k] = $this->emptyMonths;
      $lstT_ing[$k] = 0;
      $aIngrPending[$k] = 0;
    }

    $ingrType['rvs'] = 'RESERVAS';
    $ingrType['ff'] = 'FORFAITs y CLASES';
    $aIngrPending['others'] = 0;

    $this->ingr = (object) [
      'total' => (object) $lstT_ing,
      'table' => (object) $ingresos,
      'types' => (object) $ingrType,
      'pending' => (object) $aIngrPending,

    ];

    $listGastos = $lstT_gast = $aExpensesPending = [];
    $gType = \App\Expenses::getTypes();
    if ($gType) {
      foreach ($gType as $k => $v) {
        $listGastos[$k] = $this->emptyMonths;
        $lstT_gast[$k] = 0;
        $aExpensesPending[$k] = 0;
      }
    }

    $aExpensesPending['excursion'] = 0;
    $aExpensesPending['prov_material'] = 0;
    $customs = ['iva', 'impuestos'];
    $listGastos['total'] = $this->emptyMonths;
    $this->gastos = (object) [
      'total' => (object) $lstT_gast,
      'table' => (object) $listGastos,
      'types' => (object) $gType,
      'pending' => (object) $aExpensesPending,
      'pendingOrig' => null,
      'custom' => (object) $customs,
      'operativos' => null
    ];
  }
  //-----------------------------------------------------------//

  function setIngr()
  {
    $aux = $this->emptyMonths;
    foreach ($this->books as $b) {
      $m = substr($b->date, 0, 7);
      $value = $b->pvp;
      if (isset($aux[$m])) $aux[$m] += $value;
      if (isset($this->ingr->table->total[$m])) $this->ingr->table->total[$m] += $value;
      $this->ingr->total->rvs += $value;
    }
    $this->ingr->table->rvs = $aux;
    $this->ingr->total->rvs = round($this->ingr->total->rvs);
    //-----------------------------------------------------//
    $oLiq = new Liquidacion();
    $this->ffData = $oLiq->getFF_Data($this->start, $this->finish);
    $this->ingr->total->ff  = $this->ffData['pay'];
    $this->ingr->pending->ff  = $this->ffData['to_pay'] + $this->ffData['to_pay_mat'];
    //----------------------------------------------------//
    $incomesLst = \App\Incomes::where('date', '>=', $this->start)->Where('date', '<=', $this->finish)->get();
    if ($incomesLst) {
      foreach ($incomesLst as $i) {
        $m = substr($i->date, 0, 7);
        if (isset($this->ingr->table->total[$m])) $this->ingr->table->total[$m] += $i->import;
        if (isset($this->ingr->total->{$i->type})) {
          $this->ingr->total->{$i->type} += $i->import;
          $this->ingr->table->{$i->type}[$m] += $i->import;
        } else {
          $this->ingr->table->others[$m] += $i->import;
          $this->ingr->total->others += $i->import;
        }
      }
    }
  }

  //------------------------------------------------------------------------------------//
  function setExpenses()
  {
    $oLiq = new Liquidacion();
    /** @ToSee estimaciones sólo de las reservas vendidas? Pago a proveedores */
    $aPending = $oLiq->getExpensesEstimation($this->books);
    foreach ($aPending as $k => $v) {
      $this->gastos->pending->{$k} += $v;
    }

    $oGastos = \App\Expenses::where('date', '>=', $this->start)
      ->Where('date', '<=', $this->finish)
      ->where(function ($q) {
        $q->WhereNull('PayFor')
          ->orWhere('PayFor', '=', '');
      })
      ->WhereNotIn('type', ['prop_pay', 'impuestos', 'iva'])
      ->orderBy('date', 'DESC')->get();

    if ($oGastos) {
      foreach ($oGastos as $g) {
        $m = substr($g->date, 0, 7);
        if (isset($this->gastos->table->total[$m])) $this->gastos->table->total[$m] += $g->import;
        if (isset($this->gastos->total->{$g->type})) {
          $this->gastos->total->{$g->type} += $g->import;
          $this->gastos->table->{$g->type}[$m] += $g->import;
        } else {
          $this->gastos->table->others[$m] += $g->import;
          $this->gastos->total->others += $g->import;
        }
      }
    }

    /***
     * Payment prop van todos los gastos específicos
     */
    $gastos = \App\Expenses::getPaymentToProp($this->start, $this->finish);
    if ($gastos) {
      foreach ($gastos as $g) {
        $m = substr($g->date, 0, 7);
        if (isset($this->gastos->table->total[$m])) $this->gastos->table->total[$m] += $g->import;
        $this->gastos->table->prop_pay[$m] += $g->import;
        $this->gastos->total->prop_pay += $g->import;
      }
    }
    /*-----------------------------------------------------------*/
    foreach ($this->gastos->types as $k => $v) {
      if (isset($this->gastos->pending->{$k})) {
        $this->gastos->pending->{$k} -= $this->gastos->total->{$k};
        if ($this->gastos->pending->{$k} < 0) $this->gastos->pending->{$k} = 0;
      } else {
        $this->gastos->pending->{$k} = 0;
      }
    }
    /*-----------------------------------------------------------*/
    $this->gastos->pending->excursion =  $this->ffData['totalFFExpress'] + $this->ffData['to_pay'] - $this->gastos->total->excursion;
    $this->gastos->pending->prov_material = $this->ffData['totalClassesMat'] + $this->ffData['to_pay_mat'] - $this->gastos->total->prov_material;

    /*-----------------------------------------------------------*/
    $aux = [];
    foreach ($this->gastos->total as $k => $v)
      if ($k != 'prop_pay' && $k != 'excursion' && $k != 'prov_material') $aux[$k] = $v;
    $this->gastos->operativos = (object) $aux;

    /*-----------------------------------------------------------*/
    $this->gastos->pendingOrig = clone ($this->gastos->pending);

    $oData = \App\ProcessedData::findOrCreate('PyG_Hide');
    if ($oData) {
      $PyG_Hide = json_decode($oData->content, true);
      if ($PyG_Hide && is_array($PyG_Hide)) {
        foreach ($PyG_Hide as $k) {
          if (isset($this->gastos->pending->{$k})) {
            $this->gastos->pending->{$k} = 'N/A';
          }
        }
      }
    }
  }


  /*----------------------------------------------------------------------------*/
  function setExpenseCustom()
  {
    $expenses_fix = \App\Expenses::where('date', '=', $this->start)
      ->WhereIn('type', ['impuestos', 'iva'])
      ->orderBy('date', 'DESC')->pluck('import', 'type')->toArray();

    if (isset($expenses_fix['impuestos'])) $this->gastos->custom->impuestos = $expenses_fix['impuestos'];
    else  $this->gastos->custom->impuestos = 0;
    if (isset($expenses_fix['iva'])) $this->gastos->custom->impuestos = $expenses_fix['iva'];
    else  $this->gastos->custom->iva = 0;
  }

  /*----------------------------------------------------------------------------*/
  function excels()
  {
    $year = $this->oYear->year;
    $ivas = [
      'ing_iva' => 21,
      'ff_FFExpress' => 10,
      'ff_ClassesMat' => 21,
      'ff_FFExpress_expense' => 10,
      'ff_ClassesMat_exp' => 21,
      'otros_ingr' => 21,
      'ff_ClassesMat_expense' => 10,
      'gasto_operativo' => 21,
      'GastoOper_IVA_' . $year => null,
      'IVA_SOP' . $year => null,
      'IVA_' . $year => null,
      'impuestos' . $year => null,
    ];
    $oIvas = \App\Settings::whereIn('key', array_keys($ivas))->get();
    if ($oIvas) {
      foreach ($oIvas as $iva) {
        $ivas[$iva->key] = $iva->value;
      }
    }

    $ingr = ['base' => ['t' => 0], 'iva' => ['t' => 0], 'ivaVal' => ['t' => 0], 'total' => ['t' => 0]];

    $ingr_VtaProp = ($this->gastos->total->prop_pay + $this->gastos->pending->prop_pay);
    $ingr['base']['rvsProp'] = $ingr_VtaProp;
    $ingr['iva']['rvsProp'] = 0;
    $ingr['total']['rvsProp'] = $ingr_VtaProp;
    $ingr['total']['rvsInter'] = $this->ingr->total->rvs - $ingr_VtaProp;
    $ingr['base']['rvsInter'] = round($ingr['total']['rvsInter'] / (1 + ($ivas['ing_iva'] / 100)));
    $ingr['iva']['rvsInter'] = $ivas['ing_iva'];

    $ingr['total']['rvs'] = $ingr['total']['rvsInter'] + $ingr['total']['rvsProp'];
    $ingr['base']['rvs'] = $ingr['base']['rvsInter'] + $ingr['base']['rvsProp'];
    $ingr['iva']['rvs'] = 0;

    $othersIngr = 0;
    foreach ($this->ingr->total as $k => $v)
      if ($k != 'ff' && $k != 'rvs') $othersIngr += $v;
    $ingr['total']['others'] = $othersIngr;
    $ingr['base']['others'] = ($othersIngr > 0) ? $othersIngr / (1 + ($ivas['otros_ingr'] / 100)) : 0;
    $ingr['iva']['others'] = $ivas['otros_ingr'];


    $ingr['total']['ff'] = $this->ffData['totalFFExpress'];
    $ingr['base']['ff'] = ($this->ffData['totalFFExpress'] > 0) ? $this->ffData['totalFFExpress'] / (1 + ($ivas['ff_FFExpress'] / 100)) : 0;
    $ingr['iva']['ff'] = $ivas['ff_FFExpress'];

    $ingr['total']['clases'] = $this->ffData['totalClassesMat'];
    $ingr['base']['clases'] = ($this->ffData['totalClassesMat'] > 0) ? $this->ffData['totalClassesMat'] / (1 + ($ivas['ff_ClassesMat'] / 100)) : 0;
    $ingr['iva']['clases'] = $ivas['ff_ClassesMat'];

    $ingr['base']['t']  = $ingr['base']['rvs'] + $ingr['base']['ff'] + $ingr['base']['clases'] + $ingr['base']['others'];
    $ingr['total']['t'] = $ingr['total']['rvs'] + $ingr['total']['ff'] + $ingr['total']['clases'] + $ingr['total']['others'];
    foreach ($ingr['total'] as $k => $v) {
      $ingr['base'][$k] = round($ingr['base'][$k]);
      $ingr['ivaVal'][$k] = round($v - $ingr['base'][$k]);
    }

    //-------------------------------------------------------------------------//
    $gastos = ['base' => ['t' => 0], 'iva' => ['t' => 0], 'ivaVal' => ['t' => 0], 'total' => ['t' => 0]];
    $gastos['total']['payprop'] = $this->gastos->total->prop_pay;
    $gastos['base']['payprop'] = $this->gastos->total->prop_pay;
    $gastos['iva']['payprop'] = 0;

    $gastos['total']['excursion'] = $this->gastos->total->excursion;
    $gastos['base']['excursion'] = ($this->gastos->total->excursion > 0) ? $this->gastos->total->excursion / (1 + ($ivas['ff_FFExpress_expense'] / 100)) : 0;
    $gastos['iva']['excursion'] = $ivas['ff_FFExpress_expense'];

    $gastos['total']['material'] = $this->gastos->total->prov_material;
    $gastos['base']['material'] = ($this->gastos->total->prov_material > 0) ? $this->gastos->total->prov_material / (1 + ($ivas['ff_ClassesMat_exp'] / 100)) : 0;
    $gastos['iva']['material'] = $ivas['ff_ClassesMat_exp'];



    $others = 0;
    foreach ($this->gastos->operativos as $k => $v) $others += $v;
    $gastos['total']['others'] = $others;

    if ($ivas['GastoOper_IVA_' . $year])  $gastos['base']['others'] = $others - $ivas['GastoOper_IVA_' . $year];
    else $gastos['base']['others'] = ($others > 0) ? $others / (1 + ($ivas['gasto_operativo'] / 100)) : 0;
    $gastos['iva']['others'] = $ivas['gasto_operativo'];

    $total = 0;
    foreach ($gastos['total'] as $k => $v) {
      $gastos['base'][$k] = round($gastos['base'][$k]);
      $gastos['ivaVal'][$k] = round($v - $gastos['base'][$k]);
      $gastos['base']['t'] += $gastos['base'][$k];
      $total += $v;
    }
    $gastos['total']['t'] = $total;
    $gastos['ivaVal']['t'] = round($total - $gastos['base']['t']);


    //-------------------------------------------------------------------------//
    if (!$ivas['IVA_SOP' . $year]) $ivas['IVA_SOP' . $year] = $gastos['ivaVal']['t'];
    $oIva = [
      'REPERCUTIDO' => $ingr['ivaVal']['t'],
      'SOPORTADO' => $ivas['IVA_SOP' . $year],
      'ARQUEO' => $ivas['IVA_' . $year],
      'impuestos' => $ivas['impuestos' . $year],
      'toPay' => ($ingr['ivaVal']['t'] - (intval($ivas['IVA_SOP' . $year]) + intval($ivas['IVA_' . $year])))
    ];


    /*------------------------------------------------------------------*/

    $benefJorge = \App\Settings::getKeyValue('benf_jorge'.$this->oYear->year);
    if ($benefJorge == null) $benefJorge = 100;
    $benefJaime = 100 - $benefJorge;

    $bruto = [
      'ingr' => $ingr['total']['t'],
      'gastos' => $gastos['total']['t'],
      'ivaPAy' => $oIva['toPay'],
      'subTot' => ($ingr['total']['t'] - $gastos['total']['t'] - $oIva['toPay']),
      'gastoPend' => $this->sumTotal($this->gastos->pending),
      'total' => 0,
      'benfJorge' => 0,
      'benfJaime' => 0,
      'percJorge' => $benefJorge,
      'percJaime' => $benefJaime,
    ];
    $bruto['total'] = $bruto['subTot'] - $bruto['gastoPend'];
    if ($bruto['total']>0){
      $bruto['benfJorge'] = ceil($bruto['total']/100*$benefJorge);
      $bruto['benfJaime'] = $bruto['total']-$bruto['benfJorge'];
    }
    /*------------------------------------------------------------------*/

    $this->excels = (object)[
      'ingr' => (object)$ingr,
      'gastos' => (object)$gastos,
      'iva' => (object)$oIva,
      'bruto' => (object)$bruto,
    ];
    // dd($this->excels);

  }
  /*----------------------------------------------------------------------------*/
  function filtrerGastos($expensesToDel,$table,&$total=0){
    $expensesToDel[] = 'varios';
    $newTables = [];
    $auxVarios = [];
    foreach($table as $k=>$v){
      if (in_array($k,$expensesToDel)){
        foreach($v as $k2=>$v2){
          if (!isset($auxVarios[$k2])) $auxVarios[$k2] = 0;
          $auxVarios[$k2] += $v2;
          $total += $v2;
        }
      } else {
        $newTables[$k] = $v;
      }
    }
    $newTables['varios'] = $auxVarios;
    return (object)$newTables;
  }
  /*----------------------------------------------------------------------------*/
  function sumTotal($oObj)
  {
    $sum = 0;
    foreach ($oObj as $key => $value) {
      $sum += intVal($value);
    }
    return $sum;
  }
  function getData()
  {
    $oLiq = new Liquidacion();
    $summary = $oLiq->summaryTemp(false, $this->oYear);

    $totalIngr = $this->sumTotal($this->ingr->total);
    $totalGasto  = $this->sumTotal($this->gastos->total);

    return ([
      'summary' => $summary,
      'oIngr' => $this->ingr,
      'oGastos' => $this->gastos,
      //      'diff' => $diff,
      'lstMonths' => $this->lstMonths,
      'oYear' =>  $this->oYear,
      'ingr_bruto' => 9999999,
      'totalIngr' => $totalIngr,
      'totalPendingIngr' => $this->sumTotal($this->ingr->pending),
      'totalPendingGasto' => $this->sumTotal($this->gastos->pending),
      'totalGasto' => $totalGasto,
      'oExcel' => $this->excels,
    ]);




    // return ([
    //   'summary' => $summary,
    //   'lstT_ing' => $lstT_ing,
    //   'totalIngr' => $totalIngr,
    //   'lstT_gast' => $lstT_gast,
    //   'totalGasto' => $totalGasto,
    //   'totalPendingGasto' => $totalPendingGasto,
    //   'totalPendingIngr' => array_sum($aIngrPending),
    //   'totalPendingImp' => $totalPendingImp,
    //   'ingresos' => $ingresos,
    //   'listGasto' => $listGastos,
    //   'aExpensesPending' => $aExpensesPending,
    //   'aExpensesPendingOrig' => $aExpensesPendingOrig,
    //   'aIngrPending' => $aIngrPending,
    //   'diff' => $diff,
    //   'lstMonths' => $lstMonths,
    //   'year' => $oYear,
    //   'tGastByMonth' => $tGastByMonth,
    //   'tIngByMonth' => $tIngByMonth,
    //   'ingrType' => $ingrType,
    //   'gastoType' => $gType,
    //   'expenses_fix' => $expenses_fix,
    //   'tExpenses_fix' => array_sum($expenses_fix),
    //   'ingr_bruto' => $totalIngr - $totalGasto,
    //   'ff_FFExpress' => $this->ffData['totalFFExpress'],
    //   'ff_ClassesMat' => $this->ffData['totalClassesMat']
    // ]);
  }
  /*----------------------------------------------------------------------------*/
}
