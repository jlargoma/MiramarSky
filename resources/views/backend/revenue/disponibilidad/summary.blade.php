<?php
$tDays = count($lstDisp['days']);
$tNigh = $lstDisp['tNigh'];
$tavail= $lstDisp['avail'];
$tPvp  = $lstDisp['tPvp'];
?>
<div class="table-responsive ">
  <table class="table table-resumen summary">
    <thead>
      <tr class="resume-head">
        <th class="static"></th>
        <th class="first-col"></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="static text-left">Nº NOCHES</td>
        <td class="first-col"></td>
        <td>{{$tNigh}}</td>
      </tr>
      <tr>
        <td class="static text-left">% OCUPACION</td>
        <td class="first-col"></td>
        <td>{{round($tNigh/($tavail*$tDays)*100)}}%</td>
      </tr>
      <tr>
        <td class="static text-left">ADR</td>
        <td class="first-col"></td>
        <td>@if($tNigh) {{moneda($tPvp/$tNigh)}} @endif</td>
      </tr>
      <tr>
        <td class="static text-left"><b>TOTAL PVP</b></td>
        <th class="first-col"></th>
        <th>{{moneda($tPvp)}}</th>
      </tr>
    </tbody>
  </table>
</div>
<p><small>Reservas SIN contar bloqueos, ni OVERBOOKING</small></p>