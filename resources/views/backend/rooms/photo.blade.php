<div class="col-md-4 col-md-offset-4">
    <div class="input-group">
        <form enctype="multipart/form-data" action="{{ url('admin/apartamentos/uploadFile') }}/<?php echo $room->nameRoom ?>" method="POST">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input name="uploadedfile" type="file" multiple/>
        <input type="submit" value="Subir archivo" />
        </form>
    </div>
    <div class="input-group col-md-12 padding-10 text-center">
        <button class="btn btn-complete bloquear" data-id="<?php echo $room->id ?>">Guardar</button>
    </div> 
  </div>