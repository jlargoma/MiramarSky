@extends('layouts.admin-onlybody')

@section('title') Revenue @endsection

@section('externalScripts')
<script type="text/javascript">
  $(document).ready(function () {
     $('#selAnio').on('change',function (event) {
      window.location.href = '/admin/revenue/anioNatura/'+$(this).val();
    });
  });
</script>
<style>
  select.form-control{
    width: 250px;
  }
  .table-resumen.tAnioNatura th.static,
  .table-resumen.tAnioNatura td.static{
    min-width: 10em;
    white-space: break-spaces;
    height: 45px;
  }
  .table-resumen.tAnioNatura td{
    height: 45px;
  }
  </style>
@endsection

@section('content')
<select id="selAnio" class="form-control"> 
  @for($i=2019;$i<2025;$i++)
  <option value="{{$i}}" <?php echo ($i==$year) ? 'selected' : ''; ?>>{{$i}}</option>
  @endfor
</select>
<div class="anioNatura  table-responsive">
  <table class="table table-resumen tAnioNatura">
    <thead>
      <tr class="resume-head">
        <th class="static">AÑO {{$year}}</th>
        <th class="first-col"></th>
        @foreach($monthNames as $mn)
        @if($mn != '') <th>{{$mn}}</th> @endif
        @endforeach
        <th>Total Año</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="static">ING RESERVAS</td>
        <td class="first-col"></td>
        @foreach($monthBook as $mt)
          <td>{{moneda($mt)}}</td>
        @endforeach
        <td><b>{{moneda(array_sum($monthBook))}}</b></td>
      </tr>
      <tr>
        <td class="static">PAGO A PROPIETARIOS</td>
        <td class="first-col"></td>
         @foreach($monthProp as $mt)
          <td>{{moneda($mt)}}</td>
        @endforeach
        <td><b>{{moneda(array_sum($monthProp))}}</b></td>
      </tr>
      <tr>
        <td class="static">RLTDO INTERMEDIACIÓN</td>
        <td class="first-col"></td>
        @foreach($result as $mt)
          <td>{{moneda($mt)}}</td>
        @endforeach
        <td><b>{{moneda(array_sum($result))}}</b></td>
      </tr>
      <tr><th colspan="15">&nbsp;</th></tr>
      <tr>
        <td class="static">BASE IMP</td>
        <td class="first-col"></td>
        @foreach($base as $mt)
          <td>{{moneda($mt)}}</td>
        @endforeach
        <td><b>{{moneda(array_sum($base))}}</b></td>
      </tr>
      <tr>
        <td class="static">IVA 10%</td>
        <td class="first-col"></td>
        @foreach($iva as $mt)
          <td>{{moneda($mt)}}</td>
        @endforeach
        <td><b>{{moneda(array_sum($iva))}}</b></td>
      </tr>
      <tr>
        <td class="static">TOTAL TRAS INTERMEDIACIÓN INMOBILIARIA</td>
        <td class="first-col"></td>
        @foreach($tIva as $mt)
          <td>{{moneda($mt)}}</td>
        @endforeach
        <td><b>{{moneda(array_sum($tIva))}}</b></td>
      </tr>
    </tbody>
  </table>
</div>
@endsection