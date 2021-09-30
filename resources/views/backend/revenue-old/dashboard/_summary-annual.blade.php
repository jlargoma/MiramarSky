<div class="table-responsive summary" >
  <table class="table">
    <tr class="resume-head">
      <th class="static"></th>
      <th class="first-col"></th>
      <th>Nº Habitación</th>
      <th>% Ocupación</th>
      <th>Precio Medio</th>
      <th>Revenue</th>
    </tr>
    <tr>
      <td class="static">Real</td>
      <td class="first-col"></td>
      <td>{{$tOcupANUAL}}</td>
      <td>{{round( $tOcupANUAL*100/$tDispANUAL)}} % </td>
      <td>@if($tOcupANUAL>0){{moneda($tIngANUAL/$tOcupANUAL)}}@endif</td>
      <td>{{moneda($tIngANUAL)}}</td>
    </tr>
  </table>
</div>