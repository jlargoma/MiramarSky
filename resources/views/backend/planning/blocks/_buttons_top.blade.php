<?php
switch ($uRole):
  //['admin','subadmin','recepcionista','agente','jaime','limpieza','conserje']
  case 'admin':
  case 'subadmin':
  case 'recepcionista':
    $includeBlock ='backend.planning.blocks.min_blocks.btnTop-admin';
    break;
  case 'conserje':
    $includeBlock ='backend.planning.blocks.min_blocks.btnTop-conserje';
    break;
  default :
    $includeBlock ='backend.planning.blocks.min_blocks.btnTop-basic';
    break;
endswitch;
?>
 @include($includeBlock)
 @if(is_array($urgentes) && count($urgentes)>0)
  <div class="box-alerts-popup">
    <div class="content-alerts">
      <h2>Alertas Urgentes</h2>
      <button type="button" class="close" id="closeUrgente" >
        <i class="fa fa-times fa-2x" ></i>
      </button>
      @foreach($urgentes as $item)
      <div class="items">
        <button {!! $item['action'] !!}><i class="fa fa-bell" aria-hidden="true"></i> </button>
        {{$item['text']}}
      </div>
      @endforeach 
    </div>
  </div>
 @endif