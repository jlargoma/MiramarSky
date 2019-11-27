@extends('layouts.master')
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
<link rel="stylesheet" href="{{ asset('/frontend/css/components/radio-checkbox.css')}}" type="text/css" />

<style type="text/css">
	#primary-menu ul li  a{
		color: #3F51B5!important;
	}
	#primary-menu ul li  a div{
		text-align: left!important;
	}
	label{
		color: white!important
	}
	#content-form-book {
    	padding: 40px 15px;
	}
	@media (max-width: 768px){
		.container-mobile{
			padding: 0!important
		}
		#primary-menu{
			padding: 40px 15px 0 15px;
		}
		#primary-menu-trigger {
		    color: #3F51B5!important;
		    top: 5px!important;
		    left: 5px!important;
		    border: 2px solid #3F51B5!important;
		}
		.container-image-box img{
			height: 180px!important;
		}

		#content-form-book {
			padding: 0px 0 40px 0
		}
		.daterangepicker {
			position: fixed!important;
		    top: 15%!important;
		}
		.img{
			max-height: 530px;
		}
		.button.button-desc.button-3d{
			background-color: #4cb53f!important;
		}

		.img.img-slider-apto{
			height: 250px!important;
		}
	}
	
</style>
@section('metadescription') {{ $aptoHeading }} en Sierra Nevada @endsection
@section('title') {{ $aptoHeading }} en Sierra Nevada @endsection

@section('content')
	
	<section id="content">

		<div class="container container-mobile clearfix push-0">
			<div class="row">
				<h1 class="center hidden-sm hidden-xs psuh-20"><?php echo strtoupper($aptoHeading); ?></h2>
				<h1 class="center hidden-lg hidden-md green push-10"><?php echo strtoupper($aptoHeadingMobile); ?></h2>
				
			</div>
		</div>
		
		<div class="row clearfix  push-30">
			<div class="col-xs-12 col-md-6">
				<div class="fslider" data-easing="easeInQuad">
					<div class="flexslider">
						<div class="slider-wrap">
							<?php foreach ($slides as $key => $slide): ?>
								<?php $fotos = explode(",", $slide->getFilename()) ?>
								<?php if (isset($fotos[1])): ?>
									<div class="slide" data-thumb="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $fotos[1] ?>">
										<a>
											<img class="img img-slider-apto" src="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $slide->getFilename() ?>" alt="<?php echo $fotos[2] ?>" title="<?php echo $fotos[3] ?>" style="height: 600px">
											<!-- <div class="flex-caption slider-caption-bg"><?php echo $slide->getFilename() ?></div> -->
										</a>
									</div>
								<?php else: ?>
									<div class="slide" data-thumb="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $slide->getFilename()  ?>">
										<a>
											<img class="img img-slider-apto" src="{{ asset('/img/miramarski/galerias/')}}/<?php echo $url ?>/<?php echo $slide->getFilename() ?>" alt="<?php echo $slide->getFilename()  ?>" title="<?php echo $slide->getFilename()  ?>" style="height: 600px">
											<!-- <div class="flex-caption slider-caption-bg"><?php echo $slide->getFilename() ?></div> -->
										</a>
									</div>
								<?php endif ?>
								
							<?php endforeach ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-md-6 clearfix center">

				<div class="col-md-12 push-0 not-padding-mobile">
					<?php if ($typeApto == 1): ?>
						@include('frontend.pages._infoAptoLujo')
					<?php elseif($typeApto == 2): ?>
						@include('frontend.pages._infoAptoStandard')
					<?php elseif($typeApto == 3): ?>
						@include('frontend.pages._infoEstudioLujo')
					<?php elseif($typeApto == 4): ?>
						@include('frontend.pages._infoEstudioStandard')
					<?php endif ?>
				</div>
				
			</div>¡
		</div>
	</section>
	
@endsection