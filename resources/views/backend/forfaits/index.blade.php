<?php   use \Carbon\Carbon;
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 
<link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
    td,th{
        padding: 8px!important;
        font-size: 14px;
    }
</style>
@endsection

@section('content')
<h5>* Solo se muestran las últimas 100 solicitudes.</h5>
    <div class="container-fluid padding-25 sm-padding-10 bg-white">
        <div class="container clearfix col-lg-12">

               <table class="table-bordered">
                  <thead>
                  <tr>
                     <th>ID</th>
                     <th>Nombre</th>
                     <th>Email</th>
                     <th>Teléfono</th>
                     <th>Fecha Inicio</th>
                     <th>Fecha Final</th>
                     <th>Forfaits</th>
                     <th>Material</th>
                     <th>Clases</th>
                     <th>F. Solicitud</th>
                  </tr>
                  </head>
                  <tbody>
                     @foreach($requests as $request)
                        <tr>
                           <td>{{$request->id}}</td>
                           <td>{{$request->name}}</td>
                           <td>{{$request->email}}</td>
                           <td>{{$request->phone}}</td>
                           <td>{{$request->start}}</td>
                           <td>{{$request->finish}}</td>
                           <td>
                              @if($request->request_forfaits != NULL)
                                 <?php $stack = unserialize($request->request_forfaits); ?>
                                 @if(is_array($stack))
                                    @foreach($stack as $item)
                                       - {{$item}}<br/>
                                    @endforeach
                                 @endif
                                 
                              @endif
                           </td>
                           <td>
                              @if($request->request_material != NULL)
                                 <?php $stack = unserialize($request->request_material); ?>
                                 @if(is_array($stack))
                                    @foreach($stack as $item)
                                    - {{$item}}<br/>
                                 @endforeach
                              @endif
                                 
                              @endif
                           </td>
                           <td>
                              @if($request->request_classes != NULL)
                                 <?php $stack = unserialize($request->request_classes); ?>
                                 @if(is_array($stack))
                                    @foreach($stack as $item)
                                    - {{$item}}<br/>
                                 @endforeach
                              @endif
                                 
                              @endif
                           </td>
                           <td>{{$request->created_at}}</td>
                        </tr>
                     @endforeach
                  <t/body>
               </table>
            
            
            
        </div>
    </div>


@endsection

@section('scripts')

@endsection