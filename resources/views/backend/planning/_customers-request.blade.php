<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
  <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
</button>

<div class="col-md-12 not-padding content-last-books">
  <div class="alert alert-info fade in alert-dismissable" style="max-height: 600px; overflow-y: auto;position: relative;">
    <!-- <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> -->
    <!-- <strong>Info!</strong> This alert box indicates a neutral informative change or action. -->
    <h4 class="text-center">CONSULTAS REALIZADAS POR WEB</h4>
    @if(count($items)>0)
    <div class="table-responsive" style="overflow-y: hidden;">
      <table class="table table-mobile">
        <thead>
          <tr class ="text-center bg-success text-white">
            @if($isMobile)
            <th class="th-bookings static" style="width: 130px; padding: 14px !important;background-color: #10cfbd;">  
              Nombre
            </th>
            <th class="th-bookings first-col" style="padding-left: 130px!important"></th>
            @else
            <th class="th-bookings static" style="background-color: #10cfbd;">  
              Nombre
            </th>
            <th class="th-bookings first-col"></th> 
            @endif
            <th class="th-bookings text-center th-2">Tel.</th>
            <th class="th-bookings text-center th-2">Email</th>
            <th class="th-bookings text-center th-2">Pax</th>
            <th class="th-bookings text-center th-4">IN - OUT </th>
            <th class="th-bookings text-center th-1">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr data-site='{{$item->site_id}}'>
              @if($isMobile)
              <td class ="text-left static" style=" width: 130px;color: black;overflow-x: scroll;    padding: 5px 6px !important; ">  
                @else
              <td class ="text-left" style="position: relative; padding: 7px !important;">  
                @endif
                {{$item->name}}
              </td>
              @if($isMobile)
              <td class="text-center first-col" style="height: 2em;padding: 5px; padding-left: 130px!important">
              @else
              <td class="text-center">
              @endif
              </td>
              <td >
                <a href="tel:<?php echo $item->phone ?>">{{$item->phone}}</a>
              </td>
              <td >{{$item->email}}</td>
              <td >{{$item->pax}}</td>
              <td data-order="{{$item->start}}"  style="width:20%!important">
                <b>{{dateMin($item->start)}}</b>
                <span>-</span>
                <b>{{dateMin($item->finish)}}</b>
              </td>
              <td class="text-center">
                <i class="fa fa-trash hideCustomerRequest" data-id="{{$item->id}}"></i>
              </td>
            </tr>
        <?php endforeach ?>
        </tbody>
      </table>
      <div id="conteiner_msg_lst">
        <div class="box-msg-lst">
          <div id="box_msg_lst"></div>
          <button type="button" class="btn btn-default" id="box_msg_close">Cerrar</button>
        </div>
      </div>
      @else
      <p class="alert alert-warning">
        No existen registros.
      </p>
      @endif
    </div>
  </div> 


