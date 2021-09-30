<div class="table-responsive summary" >
  <table class="table">
    <tr>
      <th class="static" style="background-color: #FFF;"></th>
      <th class="first-col"></th>
      <th>Nº Habitación</th>
      <th>% Ocupación</th>
      <th>Precio Medio</th>
      <th>Revenue</th>
    </tr>
    <tr>
      <td class="static">Real</td>
      <td class="first-col"></td>
      <td>{{$tOcup}}</td>
      <td>{{round( $tOcup*100/$tDisp)}} % </td>
      <td>@if($tOcup>0){{moneda($tIng/$tOcup)}}@endif</td>
      <td>{{moneda($tIng)}}</td>
    </tr>
  </table>
</div>