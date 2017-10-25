<div class="col-md-12 text-center">
    <div class="input-group">
        <form enctype="multipart/form-data" action="{{ url('admin/apartamentos/uploadFile') }}/<?php echo $room->nameRoom ?>" method="POST">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input name="uploadedfile" type="file" multiple/>
            <input type="submit" value="Subir archivo" />
        </form>
    </div>
</div>