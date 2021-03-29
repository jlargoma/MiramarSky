<?php
$tDays = count($lstBySite['days']);
$tNigh = $lstBySite['tNigh'];
$tavail = $lstBySite['avail'];
$tPvp = $lstBySite['tPvp'];

?>
<div class="table-responsive ">
  <table class="table table-resumen summary">
    <thead>
      <tr class="resume-head">
        <th class="static"></th>
        <th class="first-col"></th>
        <th>TOTAL</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="static text-left">Nº NOCHES</td>
        <td class="first-col"></td>
        <td>{{$tNigh}} ({{$tavail*$tDays}})</td>
      </tr>
      <tr>
        <td class="static text-left">% OCUPACION</td>
        <td class="first-col"></td>
        <td>{{ceil($tNigh/($tavail*$tDays)*100)}}%</td>
      </tr>
      <tr>
        <td class="static text-left">ADR</td>
        <td class="first-col"></td>
        <td>@if($tNigh) {{moneda($tPvp/$tNigh)}} @endif</td>
      </tr>
      <tr>
        <td class="static text-left">TOTAL PVP</td>
        <td class="first-col"></td>
        <td>{{moneda($tPvp)}}</td>
      </tr>
    </tbody>
  </table>
</div>
<p><small>Reservas SIN contar bloqueos</small></p>