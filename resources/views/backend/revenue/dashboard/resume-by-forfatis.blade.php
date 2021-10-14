<?php $ttFF = $balanceFF['t'] ?>
<h3>Resumen Forfatis</h3>
<div class=" table-responsive">
  <table class="table table-resumen t-r-ff">
    <thead>
      <tr class="resume-head">
        <th class="static">Concepto</th>
        <th class="first-col"></th>
        <th>Total<br/>{{moneda($ttFF[0])}}</th>
        @foreach($lstMonths as $k => $month)
        <?php $aux = $month.' '.substr($k, 0,2); ?>
        <th>{{$aux}}<br/>{{moneda($ttFF[$k],false)}}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      
      <?php 
      
      foreach ($typeFF as $k=>$n):
        ?>
      <tr class="text-center">
        <td class="static">{{$n}}</td>
        <td class="first-col"></td>
        <th class="text-center ">  
          {{moneda($balanceFF[$k][0])}}
        </th>
        @foreach($lstMonths as $mk => $month)
        <th class="text-center">{{moneda($balanceFF[$k][$mk],false)}}</th>
        @endforeach
      </tr>
      <?php
      endforeach;
      
      
        
        ?>

    </tbody>
  </table>
</div>
