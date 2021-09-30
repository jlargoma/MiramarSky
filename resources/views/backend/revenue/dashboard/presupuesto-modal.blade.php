<?php
$Y = $year; 
?>

<div class="presupuesto table-responsive" >
  <table class="table">
    <tr class="grey">
      <td>CONCEPTO</td>
      <td class="tcenter">ANUAL</td>
      @foreach ($lstMonths as $M=>$v2)
      <td class="tcenter">{{$v2}} (€)</td>
      @endforeach
    </tr>
    <?php
    $aTotals = [];
    $yearTotals = 0;
    foreach ($FCItems as $key => $concept):
      ?>
      <tr class="borders">
        <td>{{$concept}}</td>
        <?php
        $tYear = 0;
        $aux_attr = 'data-k="' . $key . '"  ';
        $auxValues = [];
        if (isset($fixCosts[$key])) {
          $val = $fixCosts[$key];
         
          $M = 0;
          if (!isset($aTotals["mdlFC$M"]))
            $aTotals["mdlFC$M"] = 0;
//           dd($val,$aTotals["mdlFC$M"]);
          $aTotals["mdlFC$M"] += $val[0];
          $tYear = array_sum($val);
          $yearTotals += $tYear;
          ?>
          
          <?php
          foreach ($lstMonths as $M => $v2) {
            if (!isset($aTotals["mdlFC$M"]))
              $aTotals["mdlFC$M"] = 0;
            $aTotals["mdlFC$M"] += $val[$M];
            $auxValues[$M] = ($val[$M]) ? $val[$M] : '';
          }
        } else {
          foreach ($lstMonths as $M => $v2) {
           $auxValues[$M] = '';
          }
        }
        ?>
        <td class="tcenter bold fixColTtalMdl  {{$key}}" data-v="{{$tYear}}">{{moneda($tYear)}}</td>
        @foreach ($auxValues as $M => $v2)
        <td class="text-right"><input class="fixcostMdl fixCol{{$M}} {{$key}}" <?php echo $aux_attr; ?> data-m="{{$M}}" value="{{$v2}}"></td>
        @endforeach
      </tr>
      <?php
    endforeach;
    ?>
    <tr class="grey">
      <td>TOTALES</td>
      <td id="fixColTtalMdl"  class="tcenter">{{moneda($yearTotals)}}</td>
      @foreach ($lstMonths as $M=>$v2)
      <td id="mdlFC{{$M}}" class="tcenter">
        <?php
        if (isset($aTotals["mdlFC$M"]))
          echo moneda($aTotals["mdlFC$M"]);
        ?>
      </td>
      @endforeach
    </tr>
  </table>
</div>

<script>
  $('.fixcostMdl').on('click', function(){
    
    $('.fixcostMdl').each(function( index ) {
      var obj = $(this);
      if (obj.val() == ''){
        obj.val(obj.data('old'));
      }
    });
    var obj = $(this);
    obj.data('old',obj.val());
    obj.val('');
  });

  $('.fixcostMdl').on('change', function(){
    var obj = $(this);
    var value = obj.val();
    var key  = obj.data('k');
    var m = obj.data('m');
    if (value == ''){
      obj.val(obj.data('old'));
      return ;
    }
    var data = {
      val: obj.val(),
      _token: "{{csrf_token()}}",
      key: key,
      m: m,
    }
    var ktotal = '#tFC' +  m;
    $.post("/admin/revenue/upd-fixedcosts", data).done(function (resp) {
      if (resp.status == 'OK') {
        window.show_notif('Registro modificado', 'success', '');
        $(ktotal).text(resp.totam_mensual);
        
        //---------------------------------------------------------//
        var tCol = 0;
        $('.fixCol'+m).each(function( index ) {
          console.log(parseInt($(this).val()),($(this).val()));
          if ($(this).val()) tCol += parseInt($(this).val());
        });
        console.log(tCol);
        $('#mdlFC'+m).text(tCol+' €');
        //---------------------------------------------------------//
        var tCol = 0;
        $('.fixcostMdl.'+key).each(function( index ) {
          if ($(this).val())  tCol += parseInt($(this).val());
        });
        $('.fixColTtalMdl.'+key).text(tCol+' €');
        $('.fixColTtalMdl.'+key).data('v',tCol);
        //---------------------------------------------------------//
        var tCol = 0;
        $('.fixColTtalMdl').each(function( index ) {
          if ($(this).data('v')) tCol += parseInt($(this).data('v'));
        });
        $('#fixColTtalMdl').text(tCol+' €');
        //---------------------------------------------------------//
        
      } else {
        window.show_notif(resp, 'danger', '');
      }
    });
  });
  </script>