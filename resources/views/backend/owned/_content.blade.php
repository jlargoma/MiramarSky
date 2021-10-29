<?php use \Carbon\Carbon;  setlocale(LC_TIME, "ES"); setlocale(LC_TIME, "es_ES"); ?>
<?php 
$dateStat = $startYear->copy();
$uRole =  Auth::user()->role;
?>
<style type="text/css"> 

	.S, .D{
	    background-color: rgba(0,0,0,0.2)!important;
	    color: red!important;
	}
	.total{
		border-right: 2px solid black !important;
		border-left: 2px solid black !important;
		font-weight: bold;
		color: black;
		background-color: rgba(0,100,255,0.2) !important;
	}

    .botones{
        padding-top: 0px!important;
        padding-bottom: 0px!important;
    }
    .nuevo{
        background-color: lightgreen;
        color: black;
        border-radius: 11px;
        width: 50px;
    }
	.table-hover > tr > td{
		padding: 3px!important;
	}
    a {
        color: black;
        cursor: pointer;
    }
    .btn-success2{
    	background-color: rgb(70, 195, 123)!important; 
    	font-size: 20px !important; 
    	border: rgb(70, 195, 123) !important; 
    	box-shadow: rgba(70, 195, 123, 0.5) 0px 0px 3px 2px !important; 
    	display: inline-block;
    	color: white!important;
    }

    .bloq-cont{
    	padding: 30px;
    	border: 2px solid #999999;
    	-moz-border-radius: 6px;
    	-webkit-border-radius: 6px;
    	border-radius: 6px;
    	box-shadow: inset 1px 1px 0 white, 1px 1px 0 white;
    	background: #f7f7f7;
    	margin-top: 15px;
    }
    .btn-danger2{
    	display:none;font-size: 20px !important;
    	background-color: rgb(228, 22, 22)!important;
    	border: rgb(201, 53, 53) !important;
    	box-shadow: 0px 0px 3px 2px rgba(228, 22, 22, 0.5)!important;
    	color: white!important;
    }
    .daterangepicker.dropdown-menu{
    	z-index: 3000!important;
    }
    .btn-cons {
        margin-right: 5px;
        min-width: 150px;
    }
    .nav-tabs-simple > li.active a{
		color: white;
		background-color: #3f3f3f;
    }
    .nav-tabs-simple > li.active{
			font-weight: 600;
			background: #e8e8e8;
		}
		table.calendar-table thead > tr > td {
		    width: 20px!important;
		    padding: 0px 5px!important;
		}
		.daterangepicker.dropdown-menu {
		    z-index: 3000!important;
		    top: 0px!important;
		}
		button.minimal{
		    background-image: linear-gradient(45deg, transparent 50%, gray 50%),linear-gradient(135deg, gray 50%, transparent 50%),linear-gradient(to right, #ccc, #ccc)!important;
			background-position: calc(100% - 20px) calc(1em + 2px),calc(100% - 15px) calc(1em + 2px),calc(100% - 2.5em) 0.5em!important;
			 
			background-size: 5px 5px,5px 5px,1px 1.5em!important;
			background-repeat: no-repeat;
		}
		.nav-tabs > li > a:hover, .nav-tabs > li > a:focus{
		    color: white!important;
		}

		.fechas > li.active{
		    background-color: rgb(81,81,81);
		}
		.fechas > li > a{
			color: white!important;
		}
		.nav-tabs ~ .tab-content{
		    padding: 0px;
		}
		a.dropdown-item{
			padding: 0 5px;
			margin-bottom: 10px;
			background-color: none!important;
			width: 100%;
			text-align: center;
		}
        
        select.form-control.selectorRoom {
    max-width: 320px;
    width: 90%;
    margin: 1em auto;
}
table.table th{
      color: #fff !important;
    background-color: #48b0f7;
    text-align: center;
}
.table tbody tr td{
  padding: 7px 0px !important;
}
    @media only screen and (max-width: 1024px){
		.buttons .col-lg-1.col-md-1{
			width: 12.333%;
		}
		.buttons .btn-cons{
			min-width: 100%!important;
			margin-right: 0px!important;
			width: 100%!important;
    	}
	}
   @media only screen and (min-width: 1025px){
		.buttons .col-lg-1.col-md-1{
			width: 11%;
		}
    	.buttons .btn-cons{
			min-width: 100%!important;
			margin-right: 0px!important;
			width: 100%!important;
    	}
    	
    }
</style>
<div class="container-fluid padding-10 sm-padding-10">
  @include('backend.owned.blocks.header')
  <div class="col-md-12 push-20 text-center" id="content-info" style="display: none;"></div>
  <div class="row push-20 text-center" id="content-info-ini">
    <?php if ($room): ?>
        <div class="col-md-7 col-xs-12 push-20">
            <h2 class="text-center push-10" style="font-size: 24px;"><b>Resumen</b></h2>
            <div class="row" style="border: none;">
                <table class="table table-bordered table-hover no-footer" >
                    <tr>
                        <th>TOT. ING</th>
                        <th>APTO</th>
                        <th>PARK</th>
                        <th>LUZ</th>
                        <th>S.LUJO</th>
                    </tr>
                    <tr>
                        <td class="text-center total" style="padding: 8px;">{{moneda($total)}}</td>
                        <td class="text-center" style="padding: 8px;">{{moneda($apto)}}</td>
                        <td class="text-center" style="padding: 8px;">{{moneda($park)}}</td>
                        <td class="text-center" style="padding: 8px;">{{moneda($luz)}}</td>
                        <td class="text-center" style="padding: 8px;">{{moneda($lujo)}}</td>
                    </tr>
                </table>
            </div>
          @include('backend.owned.blocks.bookingList')
        </div>
        <div class="col-md-5 col-xs-12 push-20">
          <h2 class="text-center push-10" style="font-size: 24px;"><b>Calendario</b></h2>
          @include('backend.owned.calendar')
        </div>
      <div class="row resumen estadisticas blocks">
          <div class="col-xs-12">
              <div class="row push-20">
                  <h2 class="text-center font-w800">
                      Estadísticas
                  </h2>
              </div>
              <div class="col-md-6 not-padding">
                  <canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
              </div>
              <div class="col-md-6 not-padding">
                  <canvas id="barChartClient" style="width: 100%; height: 250px;"></canvas>
              </div>
              <p class="font-s12 push-0">
                  <span class="text-danger">*</span> <i>Estas estadisticas estan generadas en base a las reservas que ya tenemos pagadas</i>
              </p>
          </div>

      </div>
    <?php else: ?>
      <div class="col-md-12">
        <div class="text-center"><h1 class="text-complete">NO TIENES UNA HABITACION ASOCIADA</h1></div>
      </div>
    <?php endif ?>
  </div>

</div>

	<form role="form">
	    <div class="form-group form-group-default required" style="display: none">
	        <label class="highlight">Message</label>
	        <input type="text" hidden="" class="form-control notification-message" placeholder="Type your message here" value="This notification looks so perfect!" required>
	    </div>
	    <button class="btn btn-success show-notification hidden" id="boton">Show</button>
	</form>
	<div class="modal fade slide-up in" id="modalBloq" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content-wrapper">
	            <div class="modal-content">
	            	<div class="block">
	            		<div class="block-header">
	            			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important;color: black!important"></i>
							</button>
	            			<h2 class="text-center">
	            				Bloqueo de fechas
	            			</h2>
	            		</div>
	            		
	            		<div class="row" style="padding:20px" id="dateBlockContent">
	            			<div class="col-md-4 col-md-offset-4">
	            				<h5 class="text-center"> Seleccione sus fechas</h5>
								<input type="text" class="form-control daterange1" id="fechas" name="fechas" required="" style="cursor: pointer; text-align: center;min-height: 28px;" readonly="" placeholder="Seleccione sus fechas">
								<div class="input-group col-md-12 padding-10 text-center">
								    <button class="btn btn-complete bloquear" disabled data-id="<?php echo $room->id ?>">Guardar</button>
								</div> 
	            			</div>
	            		</div>
	            	</div>

	            	
	            </div>
	        </div>
	      <!-- /.modal-content -->
	    </div>
	  <!-- /.modal-dialog -->
	</div>
	<div class="modal fade slide-up in" id="modalLiquidation" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content-wrapper">
	            <div class="modal-content">
	            	<div class="block">
	            		<div class="block-header">
	            			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14" style="font-size: 40px!important;color: black!important"></i>
	            			</button>
	            			<h2 class="text-center">
	            				Liquidación
	            			</h2>
	            		</div>
	            		<div class="block block-content not-padding table-responsive" style=" max-height: 650px; overflow-y: auto;">
	            			@include('backend.owned._liquidation')
	            		</div>
	            	</div>

	            	
	            </div>
	        </div>
	      <!-- /.modal-content -->
	    </div>
	  <!-- /.modal-dialog -->
	</div>


<script type="text/javascript">
	$(document).ready(function() {
		$('.selectorRoom').change(function(event) {
			var apto = $(this).val();
			var url = "/admin/propietario/"+apto;

			window.location.href = url;
		});
	});
</script>