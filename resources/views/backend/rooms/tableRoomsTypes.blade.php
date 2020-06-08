<div class="table-responsive" style="padding: 1em;">
  <table class="table ">
    <thead>
      <tr>
        <th>Tipo de Habitacion</th>
        <th>Min PAX</th>
        <th>Max PAX</th>
        <th>URL SLUG</th>
      </tr>
    </thead>
    <tbody>
      @if($ch_group)
      @foreach($ch_group as $k=>$name)
      <tr>
        <td>{{$name}}</td>
        
        <td class="text-center">
          <input class="editable" data-id="{{$k}}"  data-type="minPax" value="{{$minPax[$k]}}" >
        </td>
        <td class="text-center">
          <input class="editable" data-id="{{$k}}" data-type="maxPax" value="{{$maxPax[$k]}}" >
        </td>
        <td class="text-center">
          <input class="editable" data-id="{{$k}}" data-type="slug" value="{{$slug[$k]}}" >
        </td>
      </tr>
      @endforeach
      @endif
    </tbody>
  </table>
</div>

<style>
  .editable{
    background-color: #f3f3f3;
    margin: 2px 7px;
    border-style: none;
    padding: 0px 12px;
    cursor: pointer;
  }
</style>
