<div class="contenedor">
  <div class="table-responsive ">
    <table class="table table-resumen table-excel">
      <thead>
        <tr class="resume-head">
          <th class="thSpecial" colspan="2"></th>
          <td class="first-col"></td>
          @if($aLstDays)
          @foreach($aLstDays as $d=>$w)
          <th>{{$w}}<br>{{$d}}</th>
          @endforeach
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach($otas as $ch=>$nro)
        <tr>
          <td rowspan="3" class="tdSpecial td1">{{show_isset($chNames,$ch)}}</td>
          <td class="tdSpecial td2 totals">Total</td>
          <td class="totals first-col"></td>
          @foreach($aLstDays as $d=>$w)
          <td class="totals ">{{$nro}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="tdSpecial td2">Libres</td>
          <td class="first-col"></td>
          @foreach($listDaysOtas[$ch] as $avail)
          <td class="avails {{($avail>0) ? 'number' : ''}}">{{($avail>0) ? $avail : '-'}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="tdSpecial td2">Ocupadas</td>
          <td class="first-col"></td>
          @foreach($listDaysOtas[$ch] as $avail)
          <td class=" ">{{$nro-$avail}}</td>
          @endforeach
        </tr>
        @endforeach
        <tr class="tr-total">
          <td rowspan="3" class="tdSpecial td1">TOTAL</td>
          <td class="tdSpecial td2 totals">Total</td>
          <td class="first-col totals"></td>
          @foreach($aLstDays as $d=>$w)
          <td class="totals  ">{{$totalOtas}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="tdSpecial td2">Libres</td>
          <td class="first-col"></td>
          @foreach($listDaysOtasTotal as $v)
          <td class="avails  {{($v>0) ? 'number' : ''}}">{{($v>0) ? $v : '-'}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="tdSpecial td2">Ocupadas</td>
          <td class="first-col"></td>
          @foreach($listDaysOtasTotal as $v)
          <td class=" ">{{$totalOtas-$v}}</td>
          @endforeach
        </tr>
      </tbody>
    </table>
  </div>
</div>
