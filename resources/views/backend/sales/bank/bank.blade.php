<?php   use \Carbon\Carbon;  
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
@extends('layouts.admin-master')

@section('title') Contabilidad  @endsection

@section('externalScripts') 
	<style type="text/css">
        .selectCash.selected{
            font-weight: 800;
            color: #48b0f7;
        }
    </style>
@endsection

@section('content')
<div class="container padding-5 sm-padding-10">
	<div class="row bg-white">
		<div class="col-md-12 col-xs-12">

			<div class="col-md-3 col-md-offset-3 col-xs-12">
				<h2 class="text-center">
					BANCO
				</h2>
			</div>
			<div class="col-md-2 col-xs-12 sm-padding-10" style="padding: 10px">
				<select id="fecha" class="form-control minimal">
                     <?php $fecha = $inicio->copy()->SubYears(2); ?>
                     <?php if ($fecha->copy()->format('Y') < 2015): ?>
                         <?php $fecha = new Carbon('first day of September 2015'); ?>
                     <?php endif ?>
                 
                     <?php for ($i=1; $i <= 3; $i++): ?>                           
                         <option value="<?php echo $fecha->copy()->format('Y'); ?>" 
                            <?php if (  $fecha->copy()->format('Y') == date('Y') || 
                                        $fecha->copy()->addYear()->format('Y') == date('Y') 
                                    ){ echo "selected"; }?> >
                            <?php echo $fecha->copy()->format('Y')."-".$fecha->copy()->addYear()->format('Y'); ?> 
                         </option>
                         <?php $fecha->addYear(); ?>
                     <?php endfor; ?>
                 </select>     
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row bg-white push-30">
		<div class="col-lg-8 col-md-10 col-xs-12 push-20">
			
			@include('backend.sales._button-contabiliad')
		
		</div>
	</div>
	
	<div class="row bg-white">
        <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-xs-12">
            <div class="col-lg-6 col-md-6">
                <h2 class="text-center selectCash selected" data-type="jaime" style="cursor: pointer;">
                    <?php $totalJaime = 0;//$saldoInicial->import; ?>
                    <?php foreach ($bankJaime as $key => $cash): ?>
                        <?php if ($cash->type == 1): ?>
                            <?php $totalJaime -= $cash->import ?>
                        <?php endif ?>
                        <?php if ($cash->type == 0): ?>
                            <?php $totalJaime += $cash->import ?>
                        <?php endif ?>
                        
                        
                    <?php endforeach ?>
                    BANCO JAIME (<?php echo number_format($totalJaime, 0, ',','.') ?>€)
                </h2>
            </div>
            <div class="col-lg-6 col-md-6">
                <h2 class="text-center selectCash" data-type="jorge" style="cursor: pointer;">
                    <?php $totalJorge = 0;//$saldoInicial->import; ?>
                    <?php foreach ($bankJorge as $key => $cash): ?>
                        <?php if ($cash->type == 1): ?>
                            <?php $totalJorge -= $cash->import ?>
                        <?php endif ?>
                        <?php if ($cash->type == 0): ?>
                            <?php $totalJorge += $cash->import ?>
                        <?php endif ?>
                        
                        
                    <?php endforeach ?>
                    BANCO JORGE (<?php echo number_format($totalJorge, 0, ',','.') ?>€)
                </h2>
            </div>
        </div>
	    <div class="col-md-12 col-xs-12 contentBank">
           
           @include('backend.sales.bank._tableMoves', ['bank' => $bankJaime, 'saldoInicial' => $saldoInicial ])
	       
        </div>
	</div>
</div>
	
@endsection	


@section('scripts')
<script type="text/javascript">

	$('#fecha').change(function(event) {
	    
	    var year = $(this).val();
	    window.location = '/admin/banco/'+year;

	});


    $('.selectCash').click(function(event) {
        var type = $(this).attr('data-type');
        var year = $('#fecha').val();

        $('.selectCash').each(function() {
            $(this).removeClass('selected');
        });

        $(this).addClass('selected');

        $('.contentBank').empty().load('/admin/banco/getTableMoves/'+year+'/'+type);

        // 


    });


</script>
@endsection