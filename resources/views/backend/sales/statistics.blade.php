@extends('layouts.admin-master')

@section('title') Liquidacion @endsection

@section('externalScripts') 
	

@endsection

@section('content')
<?php use \Carbon\Carbon; ?>

<style>
	.table>thead>tr>th {
		padding:0px!important;
	}
	th{
		/*font-size: 15px!important;*/
	}
	td{
		font-size: 13px!important;
		padding: 10px 5px!important;
	}
	.pagos{
		background-color: rgba(255,255,255,0.5)!important;
	}

	td[class$="bi"] {border-left: 1px solid black;}
	td[class$="bf"] {border-right: 1px solid black;}
	
	.coste{
		background-color: rgba(200,200,200,0.5)!important;
	}
	th.text-center.bg-complete.text-white{
		padding: 10px 5px;
		font-weight: 300;
		font-size: 15px!important;
		text-transform: capitalize!important;
	}
</style>
<div class="container-fluid padding-5 sm-padding-10">


    <div class="row">
    	<div class="col-md-12 text-center">
    		<h2>Estadisticas</h2>
    	</div>
        <div class="col-md-12">
			<div class="tab-content">

			</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')


@endsection