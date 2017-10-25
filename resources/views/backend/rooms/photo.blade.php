<div class="col-md-12 text-center">
    <div class="row">
    	<table class="table table-hover demo-table-search table-responsive " id="tableWithSearchRoom">
    		<thead>
    			<th class ="text-center bg-complete text-white">Foto</th>
    			<th class ="text-center bg-complete text-white">Borrar</th>
    		</thead>
    		<tbody>
		    	<?php  while ($archivo = $directory->read()): ?>
		    		<?php if ($archivo != '.' && $archivo != '..' ): ?>
						<tr class="<?php echo $archivo ?>">
							<td class="text-center"><img src="{{ asset('/img/miramarski/apartamentos') }}/<?php echo $room->nameRoom."/".$archivo ;?>" alt="" height="50"></td>
							<td class="text-center"><a class="btn btn-tag btn-danger" type="button" data-toggle="tooltip" title="" data-name="<?php echo $archivo ?>" data-original-title="Eliminar Reserva" onclick="return confirm('¿Quieres Eliminar la reserva?');"><i class="fa fa-trash-o"></i></a></td>
						</tr>
		    		<?php endif ?>					
		    	<?php endwhile ; ?>
    		</tbody>
    	</table>
    	
    </div>
    <div class="row">
        <form enctype="multipart/form-data" action="{{ url('admin/apartamentos/uploadFile') }}/<?php echo $room->nameRoom ?>" method="POST">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input name="uploadedfile" type="file" multiple/>
            <input type="submit" value="Subir archivo" />
        </form>
    </div>
</div>

<script type="text/javascript">

	$('.btn-danger').click(function(event) {
	    var name = $(this).attr('data-name');
	    $.get('/admin/apartamentos/deletePhoto/'+name, function(data) {
	    	if (data == "OK") {
	    		$('.name').hide();
	    	}
	    });
	});

</script>