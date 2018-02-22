<div class="row">
	<div class="col-xs-12"> 
		<h2 class="text-center font-w300" style="letter-spacing: -1px;">
			GENERADOR DE LINKS STRIPE
		</h2>
	</div>
	<div class="col-md-3 col-xs-12 push-20">
		<input type="number" class="form-control only-numbers" name="importe_stripe" id="importe_stripe" placeholder="importe..." />
	</div>
	<div class="col-md-3 col-xs-12 push-20">
		<button id="btnGenerate" class="btn btn-success" type="button">Generar</button>
	</div>
</div>
<div class="row content-importe-stripe"></div>
<script type="text/javascript">
	$(document).ready(function() {

		$('#btnGenerate').click(function(event) {
			var importe = $('#importe_stripe').val();

			if (importe == '') {
				alert('Rellena el importe a generar');
			}else{

				$.get('/admin/links-stripe',{ importe: importe }, function(data) {
					$('.content-importe-stripe').empty().append(data);
				});
			}

		});

		$(document).on("click","#copy-link-stripe", function(){
            var link = $(this).data("link");
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(link).select();
            document.execCommand("copy");
            $temp.remove();
        });


		$('.only-numbers').keydown(function (e) {
		    // Allow: backspace, delete, tab, escape, enter and .
		    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190 ]) !== -1 ||
		         // Allow: Ctrl/cmd+A
		        (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
		         // Allow: Ctrl/cmd+C
		        (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
		         // Allow: Ctrl/cmd+X
		        (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
		         // Allow: home, end, left, right
		        (e.keyCode >= 35 && e.keyCode <= 39)) {
		             // let it happen, don't do anything
		             return;
		    }
		    // Ensure that it is a number and stop the keypress
		    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		        e.preventDefault();
		    }
		});
	});
</script>