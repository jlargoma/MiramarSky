<div class="table-responsive">
  <table class="tableMonths" >
    <tr>
      <td data-k="0" class="sm <?php if($month == 0) echo 'active' ?> ">
        AÑO
      </td>
      @foreach($lstMonths as $k=>$v)
      <td data-k="{{$k}}" class="sm <?php if($month == $k) echo 'active' ?> ">
        <?php echo $v; ?>
      </td>
      @endforeach
    </tr>
  </table>  
</div>
<div class=" contenedor  mt-2em">
    <div class="col-md-4 col-xs-12">
        @include('backend.revenue.disponibilidad.summary')
    </div>
    <div class="col-md-8 col-xs-12">
        @include('backend.revenue.disponibilidad.summary-month')
    </div>
</div>
