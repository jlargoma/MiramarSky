@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{ asset('/js/datePicker01.js')}}"></script>

<style>
    .table.table-bookings tbody tr td{
        padding: 9px 4px !important;
    }
    .toggle_formNewExpense{
        cursor: pointer;
    }
    #formNewExpense{
        display: none;
        border: 2px solid #0fcfbd;
        padding: 29px 10px;
        margin-top: -13px;
    }
    .table.summary-temp{
        max-width: 540px;
        margin: auto;
        text-align: center;
    }
</style>
@endsection
@section('content')

<div class="container-fluid padding-25 sm-padding-10">
    
     <div class="container padding-5 sm-padding-10">
        <div class="row bg-white">
            <form method="GET" id="form_filterByRange"  class="col-md-12 col-xs-12"> 
                <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="col-md-3 col-md-offset-1 col-xs-12">
                    <h2 class="text-center">Liquidación Prop.</h2>
                </div>
                <div class="col-md-2 col-xs-12 sm-padding-10" style="padding: 10px">
                    <select id="rooms" name="roomID" class="form-control minimal">
                      <option value="-1" > -- </option>
                      @foreach($rooms as $k => $v)
                          <option value="{{ $k }}" @if ($k == $roomID) selected @endif >
                              {{ $v }}
                          </option>
                      @endforeach
                  </select>

                </div>
                <div class="col-md-2 col-xs-12 sm-padding-10" style="padding: 10px">
                    @include('backend.years._select')
                </div>
                <div class="col-md-3 col-xs-12 sm-padding-10" style="padding: 10px">
                    <div>
                      <input type="text" class="form-control daterange03" id="dateRangefilter" name="dateRangefilter" required="" readonly="">
                      <input type="hidden" class="filter_startDate" name="filter_startDate" value="{{$dates['start']}}">
                      <input type="hidden" class="filter_endDate" name="filter_endDate" value="{{$dates['finish']}}">
                    </div>
                </div>
            </form>
        </div>
        
    </div>
</div>
<div class="container-fluid">
    <div class="row mb-1em">
        <div class="col-md-6 col-xs-12">
         @include('backend.paymentspro.blocks.books')
        </div>
        <div class="col-md-6 col-xs-12">
         @include('backend.paymentspro.blocks.liquidacion')
        </div>
    </div>
         @include('backend.paymentspro.blocks.pagos')
         <div class="">
             <div class="table-responsive">
              <table class="table summary-temp table-bordered">
                  <tr>
                      <th>TEMPORADA</th>
                      <th>GENERADO</th>
                      <th>PAGADO</th>
                      <th>PENDIENTE</th>
                  </tr>
                  <tbody id="summaryTemps">
                    
                  </tbody>
              </table>
             </div>

         </div>
         
         <br/>
         <br/>
         <br/>
</div>
@endsection

@section('scripts')

<script>
var startDate = new Date("{{$startDate}}");
var endDate = new Date("{{$finishDate}}");
$(document).ready(function () {
  $('#rooms').on('change',function () {
    $('#form_filterByRange').submit();
  });

  $('.s_years').on('change',function () {
    var yearId = $(this).val();
    $.post("{{ route('years.change') }}", { year: yearId })
      .done(function( data ) {
        $('.filter_startDate').val('');
        $('.filter_endDate').val('');
        $('#form_filterByRange').submit();
      });
  });
  
   const hTable = $('#tableItems');
     
     
      function edit (currentElement,type) {
        switch(type){
          case 'price':
             var input = $('<input>', {type: "number",class: type})
            .val(currentElement.html())
            currentElement.data('value',currentElement.html());
            currentElement.html(input);
            input.focus(); 
          break;
          case 'type':
            var select = $('<select>', {class:' form-control'});
            select.data('t','type');
            <?php
                foreach ($gType as $k=>$v){
                  echo "var option = $('<option></option>');
                              option.attr('value', '$k');
                              option.text('$v');
                              select.append(option);";
                }
            ?>
            currentElement.data('value',currentElement.html());
            select.val(currentElement.data('current'));
            currentElement.html(select);
          break;
          case 'payment':
            var select = $('<select>', {class:' form-control'});
            select.data('t','payment');
            <?php
                foreach ($typePayment as $k=>$v){
                  echo "var option = $('<option></option>');
                              option.attr('value', '$k');
                              option.text('$v');
                              select.append(option);";
                }
            ?>
            currentElement.data('value',currentElement.html());
            select.val(currentElement.data('current'));
            currentElement.html(select);
          break;
          default:
             var input = $('<input>', {type: "text",class: type})
            .val(currentElement.html())
            currentElement.data('value',currentElement.html());
            currentElement.html(input);
            input.focus(); 
          break;
        }
     
      }
      hTable.on('click','.editable', function () {
        var that = $(this);
        if (!that.hasClass('tSelect')){
          clearAll();
          that.data('val',that.text());
          that.addClass('tSelect')
          var type = $(this).data('type');
          edit($(this),type);
        }
      });
      
            
      hTable.on('change','.selects',function (e) {
          var id = $(this).closest('tr').data('id');
          var input = $(this).find('select');
          updValues(id,input.data('t'),input.val(),$(this),$(this).find('option:selected').text());
      });

      hTable.on('change','.tSelect',function (e) {
        if ($(this).hasClass('selects')) return null;
        var id = $(this).closest('tr').data('id');
        var input = $(this).find('input');
        updValues(id,input.attr('class'),input.val(),$(this));
      });
      hTable.on('keyup','.tSelect',function (e) {
        if (e.keyCode == 13) {
          var id = $(this).closest('tr').data('id');
          var input = $(this).find('input');

          updValues(id,input.attr('class'),input.val(),$(this));
        } else {
          hTable.find('.tSelect').find('input').val($(this).find('input').val());
        }
      });
      var clearAll= function(){
         hTable.find('.tSelect').each(function() {
            $(this).text($(this).data('value')).removeClass('tSelect');
          });
        }

      var updValues = function(id,type,value,obj,text=null){
        var url = "/admin/gastos/update";
        $.ajax({
          type: "POST",
          method : "POST",
          url: url,
          data: {_token: "{{ csrf_token() }}",id: id, val: value,type:type},
          success: function (response)
          {
            if (response == 'ok') {
              clearAll();
              window.show_notif('OK','success','Registro Actualizado');
              if (text) obj.text(text);
              else  obj.text(value);
            } else {
              window.show_notif('Error','danger','Registro NO Actualizado');
            }
          }
        });
    
      }
          $('#tableItems').on('click','.del_expense', function(){
      if (confirm('Eliminar el registro definitivamente?')){
        var id = $(this).data('id');
        var that_tr = $(this).closest('tr');
        $.ajax({
          url: '/admin/gastos/del',
          type:'POST',
          data: {id:id, '_token':"{{csrf_token()}}"},
          success: function(response){
            location.reload();
          }
        });
      }
    });

  $('.toggle_formNewExpense').on('click',function(){
    $('#formNewExpense').toggle('slow');
  });
   $('#fecha').datepicker();
   $('#summaryTemps').load('/admin/paymentspro/historico_temp/{{$roomID}}');
});
        
</script>
@endsection