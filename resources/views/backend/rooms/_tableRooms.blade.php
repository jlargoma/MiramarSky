<?php

use \App\Classes\Mobile;

$mobile = new Mobile();
?>
<style>
  .fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
  }
  .fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
  }
  .btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
  }
  input{
    min-height: 35px!important;
  }
  .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
    vertical-align: middle;
  }
  .padding-room{
    padding: 5px 10px!important;
  }
  .input-group-addon.bg-transparent{
    border: none;
  }
  input.room_editable {
    width: 5em;
    text-align: center;
    margin-bottom: 4px;
    background-color: #ececec;
    border: none;
    font-weight: 800;
  }
    table.tRooms thead th {
    background-color: #48b0f7;
    color: #FFF;
    /*white-space: nowrap;*/
    padding: 8px 4px;
  }
  table.tRooms input.room_editable {
    width: 3em;
    padding: 0 !important;
    min-width: 0;
  }
</style>
<div class="table-responsive">
  <table class="table tRooms">
    <thead>
      <tr>
        <th>#</th>
        <th>Nombre tRooms APTO</th>
        <th>Contr</th>
        <th>Contr2</th>
        <th>Lujo</th>
        <th>OCU. MIN</th>
        <th>OCU. MAX</th>
        <th>Estado</th>
        <th>Acc</th>
      </tr>
    </thead>
    <tbody id="lstRooms">
<?php foreach ($rooms as $room): ?>
        <tr data-name="{{strtolower($room->name.' '.$room->nameRoom)}}" data-chn="{{$room->channel_group}}">
          <td class="text-center" >
            <input class="orden order-<?php echo $room->id ?>" type="number" name="orden" data-id="<?php echo $room->id ?>" value="<?php echo $room->order ?>" style="width: 100%;text-align: center;border-style: none none">
          </td>
          <td class="text-left" >
            <a class="aptos" data-id="<?php echo $room->id ?>" style="cursor: pointer;">
  <?php echo $room->name ?> (<?php echo $room->nameRoom ?>)
            </a>
          </td>
          <td class="text-center">
            <?php $status = (isset($aContrs[$room->id])) ? $aContrs[$room->id] : ""; ?>
            <button class="btn btnContract  {{$status}}" data-id="{{$room->id}}" title="Contrato {{$status}}">
              <i class="fa fa-file"></i>
            </button>
          </td>
          <td class="text-center">
            <?php
            $status = (isset($aContrs2[$room->id])) ? $aContrs2[$room->id] : ""; ?>
            <button class="btn btnContract2  {{$status}}" data-id="{{$room->id}}" title="Contrato de REPRESENTACION{{$status}}">
              <i class="fa fa-file"></i>
            </button>
          </td>
          <td class="text-center" >
            <span class="input-group-addon bg-transparent">
              <input type="checkbox" class="estado lux_<?php echo $room->id ?>" data-id="<?php echo $room->id ?>" name="luxury" data-init-plugin="switchery" data-size="small" data-color="primary" <?php echo ($room->luxury == 0) ? "" : "checked" ?>/>
            </span>
          </td>
          <td class="text-center">
            <input class="room_editable" data-id="{{$room->id}}" id="minOcu-{{$room->id}}" value="{{$room->minOcu}}" >
          </td>
          <td class="text-center">
            <input class="room_editable" data-id="{{$room->id}}"  id="maxOcu-{{$room->id}}" value="{{$room->maxOcu}}" >
          </td>
          <td class="text-center" >
            <span class="input-group-addon bg-transparent">
              <input type="checkbox" class="estado status_<?php echo $room->id ?>" data-id="<?php echo $room->id ?>" name="state" data-init-plugin="switchery" data-size="small" data-color="success" <?php echo ($room->state == 0) ? "" : "checked" ?>> 
            </span>
          </td>
          <td class="text-center nowrap" >
            <a type="button" class="btn btn-default btn-sm" href="https://www.apartamentosierranevada.net/fotos/<?php echo $room->nameRoom ?>" target="_blank" data-original-title="Enlace de Apartamento" data-toggle="tooltip">
              <i class="fa fa-paperclip"></i>
            </a>
            <button type="button" class="btn btn-success btn-sm uploadFile action-rooms-table" data-toggle="modal" data-target="#modalFiles" data-id="<?php echo $room->nameRoom ?>" title="Subir imagenes aptos">
                    <i class="fa fa-upload" aria-hidden="true"></i>
            </button> 
          </td>
        </tr>
<?php endforeach ?>
    </tbody>
  </table>
</div>

<script type="text/javascript">

  $('.uploadFile').click(function (event) {
    var id = $(this).attr('data-id');
    $.get('/admin/apartamentos/fotos/' + id, function (data) {
      $('.upload-body').empty().append(data);
    });
  });

  $('.uploadHeader').click(function (event) {
    var id = $(this).attr('data-id');
    $.get('/admin/apartamentos/headers/room/' + id, function (data) {
      $('.upload-body').empty().append(data);
    });
  });
  $('.room_editable').change(function (event) {
    var id = $(this).data('id');
    

    var minOcu = $('#minOcu-' + id).val();
    var maxOcu = $('#maxOcu-' + id).val();
    var data = {
      id: id,
      
      maxOcu: maxOcu,
      minOcu: minOcu,
      _token: "{{csrf_token()}}"
    }
    
    $.post('/admin/apartamentos/update',data , function (data) {
      if (data == 'OK') {
        window.show_notif('Registro modificado','success','');
      } else {
        window.show_notif(resp,'danger','');
      }
    });
  });

  $('.estado').change(function (event) {
    var id = $(this).attr('data-id');
    var state = $('.status_'+id).is(':checked');
    var luxury = $('.lux_'+id).is(':checked');

    if (state == true) {
      state = 1;
    } else {
      state = 0;
    }

    if (luxury == true) {
      luxury = 1;
    } else {
      luxury = 0;
    }

    $.get('/admin/apartamentos/state', {id: id,luxury: luxury, state: state}, function (data) {
      if (data == 0) {
        alert('No se puede cambiar')
        // $('.content-table-rooms').empty().load('/admin/apartamentos/rooms/getTableRooms');
      } else {
        alert('cambiado')
        // $('.content-table-rooms').empty().load('/admin/apartamentos/rooms/getTableRooms');
      }
    });
  });

  $('.assingToBooking').change(function (event) {
    var id = $(this).attr('data-id');
    var assing = $(this).is(':checked');

    if (assing == true) {
      assing = 1;
    } else {
      assing = 0;
    }

    $.get('/admin/apartamentos/assingToBooking', {id: id, assing: assing}, function (data) {

      alert(data);
      // $('.content-table-rooms').empty().load('/admin/apartamentos/rooms/getTableRooms');
    });
  });


  $('.orden').change(function (event) {
    var id = $(this).attr('data-id');
    var orden = $(this).val();

    $.get('/admin/apartamentos/update-order', {id: id, orden: orden}, function (data) {
      $('.content-table-rooms').empty().load('/admin/apartamentos/rooms/getTableRooms');
    });

  });
</script>
<?php if (isset($show) && !empty($show)): ?>
  <script type="text/javascript">
    $('.btn-emiling').click(function (event) {
      var id = $(this).attr('data-id');
      $('.modal-content.emailing').empty().load('/admin/apartamentos/email/' + id);
    });
  <?php if (!$mobile->isMobile()): ?>
      $('.aptos').click(function (event) {
        var id = $(this).attr('data-id');

        $.get('/admin/rooms/getUpdateForm', {id: id}, function (data) {
          $('.contentUpdateForm').empty().append(data)
        });
      });
  <?php else: ?>
      $('.aptos').click(function (event) {
        var id = $(this).attr('data-id');

        $.get('/admin/rooms/getUpdateForm', {id: id}, function (data) {
          $('.contentUpdateForm').empty().append(data);
          $('html,body').animate({
            scrollTop: $(".contentUpdateForm").offset().top},
                  'slow');
        });
      });
  <?php endif ?>
  </script>
  <script src="/assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
  <script src="/pages/js/pages.min.js"></script>
<?php endif; ?>