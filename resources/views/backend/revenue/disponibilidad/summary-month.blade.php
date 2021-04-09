<div class="table-responsive ">
  <table class="table table-resumen summary-month">
    <thead>
      <tr class="resume-head">
        <th class="static">Ocupadas</th>
        <th class="first-col"></th>
          @if($aLstDaysMin)
          @foreach($aLstDaysMin as $d=>$w)
          <th style="width: 20px !important;min-width: auto !important;">{{$w}}<br>{{$d}}</th>
          @endforeach
          @endif
        </tr>
      </thead>
      <tbody>
        <tr>
            <td class="static">MiramarSki</td>
            <th class="first-col"></th>
            <?php
                $aux_day   = $lstBySite['days'];
                $aux_avail = $lstBySite['avail'];
                if ($aux_avail<1) $aux_avail = 1;
                foreach($aLstDaysMin as $d=>$w):

                    $class = 's-grey';
                    $libres = ceil($aux_day[$d]/$aux_avail*100);
                    if ($libres>0) $class = 's-yellow';
                    if ($libres>40) $class = 's-orange';
                    if ($libres>60) $class = 's-green';
                    echo '<td class="'.$class.'">'.$aux_day[$d].'</td>';
                endforeach;
            ?>
        </tr>
      </tbody>
    </table>
</div>
