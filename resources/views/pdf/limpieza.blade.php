<html>
  <head>
    <style>
      body{
        font-family: sans-serif;
        font-size: 11px;
      }
      @page {
        margin: 160px 50px;
      }
      header { 
        position: fixed;
        left: 0px;
        top: -160px;
        right: 0px;
        height: 100px;
        background-color: #295d9b;
        text-align: center;
        color: #fff;
      }
      header h1{
        margin: 10px 0;
      }
      header h2{
        margin: 0 0 10px 0;
      }
      footer {
        position: fixed;
        left: 0px;
        bottom: -50px;
        right: 0px;
        height: 40px;
        border-bottom: 2px solid #ddd;
      }
      footer .page:after {
        content: counter(page);
      }
      footer p {
        text-align: right;
      }

      table {
        border-collapse: collapse;
      }

      .content{
        margin: 0;
        padding: 0;
      }
      table, th, td {
        border: 1px solid #cecece;
      }
      th, td {
        padding: 8px;
      }

      table.table {
        width: 100%;
        margin: 15px auto;
      }

      table.table .header{
        background-color: #48b0f7;

      }
      .text-center{
        text-align: center;
      }
      .text-rigth{
        text-align: right;
      }
    </style>
  <body>
    <header>
      <h1>Costos de limpieza</h1>
      <h2>{{$tit}}</h2>
    </header>
    <footer>
      <p class="page">
        Página
      </p>
    </footer>
    <div id="content">
        <table class="table">
          <thead >
            <tr>
              <th class ="header">Nombre</th>
              <th class ="header text-center">T</th>
              <th class ="header text-center">Pax</th>
              <th class ="header text-center">apto</th>
              <th class ="header text-center">checkIn - checkOut</th>
              <th class ="header text-center">N</th>
              <th class ="header text-center">Limpieza<br><b>&#8364;&nbsp;{{$total_limp}}</b></th>
              <th class ="header text-center">Extras<br><b>&#8364;&nbsp;{{$total_extr}}</b></th>
            </tr>
          </thead>
          <tbody >
            <tr>
              <td colspan="6">Monto Fijo Mensual</td>
              <td class="text-rigth">{{$month_cost}}</td>
              <td></td>
            </tr>
            @foreach($respo_list as $item)
            <tr>
              <td>{{$item['name']}}</td>
              <td class="text-center">{{$item['type']}}</td>
              <td class="text-center">{{$item['pax']}}</td>
              <td class="text-center">{{$item['apto']}}</td>
              <td class="text-center">{{$item['check_in']}} - {{$item['check_out']}}</td>
              <td class="text-center">{{$item['nigths']}}</td>
              <td class="text-rigth">{{$item['limp']}}</td>
              <td class="text-rigth">{{$item['pax']}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="8"></td>
            </tr>
            <tr>
              <td colspan="6"><strong>Totales</strong></td>
              <td class="text-rigth">
                &#8364;&nbsp; {{$total_limp}}
              </td>
              <td class="text-rigth">&#8364;&nbsp; {{$total_extr}}</td>
            </tr>
          </tbody>
        </table>
    </div>
  </body>
</html>