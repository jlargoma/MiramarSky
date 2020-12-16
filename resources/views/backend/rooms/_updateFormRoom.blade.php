<div class="col-xs-12 push-20">
    <h3 class="text-center font-w300">
        Datos de <span class="font-w800"><?php echo $room->name ?> (<?php echo $room->nameRoom ?>)</span>
    </h3>
</div>
<div class="row push-20">
    <form class="form" action="{{ url('admin/apartamentos/saveupdate') }}" method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="id" value="<?php echo $room->id; ?>">
        <div class="">
            <div class="col-md-12 push-20"></div>
            <div class="col-md-4 col-xs-12 push-20">
                <label for="name">Nombre</label>
                <input type="text" name="name" class="form-control" value="<?php echo $room->name; ?>"/>
            </div>
            <div class="col-md-2 col-xs-4 push-20">
                <label for="nameRoom">piso</label>
                <input type="text" name="nameRoom" class="form-control" value="<?php echo $room->nameRoom; ?>"/>
            </div>
            <div class="col-md-2 col-xs-4 push-20">
                <label for="parking">Parking</label>
                <input type="text" name="parking" class="form-control only-numbers" value="<?php echo $room->parking; ?>"/>
            </div>
            <div class="col-md-2 col-xs-4 push-20">
                <label for="locker">Taquilla</label>
                <input type="text" name="locker" class="form-control only-numbers" value="<?php echo $room->locker; ?>"/>
            </div>
            <div class="col-md-3 col-xs-12 push-20">
                <label for="sizeApto">Tamaño Apto.</label>
                <select class="form-control minimal" name="sizeApto">
                    <?php foreach (\App\SizeRooms::all() as $size): ?>                                   
                      <option value="<?php echo $size->id; ?>" <?php echo ($size->id == $room->sizeApto) ? "selected" : "" ?>>
                          <?php echo $size->name ?>
                      </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-3 col-xs-12 push-20">
                <label for="sizeApto">Zodomus Apto.</label>
                <select class="form-control minimal" name="channel_group">
                    <option value=""> -- </option>
                    <?php foreach ($zodomusAptos as $id => $data): ?>                                   
                      <option value="{{$id}}" <?php echo ($id == $room->channel_group) ? "selected" : "" ?>>
                          {{$data->name}}
                      </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-2 col-xs-4 push-20">
                <label for="minOcu">Ocu. Min</label>
                <input type="number" name="minOcu" class="form-control" value="<?php echo $room->minOcu; ?>"/>
            </div>
            <div class="col-md-2 col-xs-4 push-20">
                <label for="maxOcu">Ocu. Max</label>
                <input type="number" name="maxOcu" class="form-control" value="<?php echo $room->maxOcu; ?>"/>
            </div>
        </div>
        <div class="">
            <div class="">
                <div class="col-xs-12 push-20"></div>

                <div class="col-md-3 col-xs-12 push-20">
                    <label for="owned">Prop.</label>
                    <select class="form-control minimal" name="owned">
                        <?php $owneds = \App\User::whereIn('role', ['propietario', 'admin'])->get() ?>
                        <?php foreach ($owneds as $key => $owned): ?>
                          <?php if (($owned->role == 'propietario') || ($owned->role == 'admin') || $owned->name == 'jorge'): ?>
                            <?php if ($owned->name == $room->user->name) {
                              $selected = "selected";
                            } else {
                              $selected = "";
                            } ?>
                            <option value="<?php echo $owned->id; ?>" <?php echo $selected ?> >
    <?php echo $owned->name ?>
                            </option>
  <?php endif ?>
<?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-3 col-xs-12 push-20">
                    <label for="type">T. Apto.</label>

                    <select class=" form-control minimal" name="type">
                        <?php foreach (\App\TypeApto::all() as $tipo): ?>
                          <?php if ($tipo->id == $room->typeApto) {
                            $selected = "selected";
                          } else {
                            $selected = "";
                          } ?>
                          <option value="<?php echo $tipo->id; ?>" <?php echo $selected ?> >
  <?php echo $tipo->name ?>
                          </option>
<?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-2 col-xs-12 push-20">
                    <label for="minOcu">% Min Benef</label>
                    <input type="number" name="profit_percent" class="form-control" value="<?php echo $room->profit_percent; ?>"/>
                </div>
                <div class="col-md-2 col-xs-12 push-20">
                    <label for="num_garage">Plz parking</label>
                    <input type="number" name="num_garage" class="form-control" value="<?php echo $room->num_garage; ?>"/>
                </div>
                <div class="col-md-2 col-xs-12 push-20">
                    <label for="limp_prop">Limp. Prop.</label>
                    <input type="number" name="limp_prop" class="form-control" value="<?php echo $room->limp_prop; ?>"/>
                </div>
            </div>
        </div>
        <div class="col-md-12 push-20 ">
          <h4 class="text-center">Descripción del apto</h4>
          <textarea class="form-control" name="description" rows="7"><?php echo $room->description; ?></textarea>
        </div>
        <div class="col-md-12 push-20 ">
          <h4 class="text-center">Detalle del apto</h4>
          <textarea id="room_detail" name="room_detail" rows="7"><?php echo $frontText['detail']; ?></textarea>
        </div>
        <div class="col-md-12 push-20 ">
          <h4 class="text-center">Caraterísticas del apto</h4>
          <textarea id="room_charact" name="room_charact" rows="7"><?php echo $frontText['charact']; ?></textarea>
        </div>
        <div class="col-xs-12 text-center push-20">
            <button class="btn btn-success btn-cons" type="submit">
                <span class="bold">GUARDAR</span>
            </button>
        </div>
    </form>
    {!! $room->content_front !!}
</div>

<script type="text/javascript">

  $(document).on("click", "#copy-link-export", function () {
      var link = $(this).data("link");
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val(link).select();
      document.execCommand("copy");
      $temp.remove();
  });

  $('#icalForm').submit(function (event) {
      event.preventDefault();
      var _token = $('input[name="_token"]').val();
      var idRoom = $('input[name="id"]').val();
      var urlIcal = $('input[name="urlIcal"]').val();

      var url = $(this).attr('action');
      $.post(url, {_token: _token, idRoom: idRoom, urlIcal: urlIcal}, function (data) {
          $('#listUrl').empty().load('/ical/urls/getAllUrl/' + idRoom);
          $('input[name="urlIcal"]').val('');
      });

  });


  CKEDITOR.replace('room_detail', window["confiCKEDITOR"]  );
  CKEDITOR.replace('room_charact', window["confiCKEDITOR"]  );
          


</script>
