@extends('layouts.admin-master')
<?php 
use \Carbon\Carbon;
use App\Classes\Mobile;
$uRole = getUsrRole();
$is_mobile = $mobile->isMobile();
?>
@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts')
@include('backend.planning.blocks.styles-update')
@endsection

@section('content')

@if($errors->any())
<p class="alert alert-danger">{{$errors->first()}}</p>
@endif
@if (\Session::has('success'))
<p class="alert alert-success">{!! \Session::get('success') !!}</p>
@endif

<div class="container-fluid padding-10 sm-padding-10" id="updateBooking">
  @include('backend.planning.blocks.header-update')
  <div class="row center text-center">
    <!-- DATOS DE LA RESERVA -->
    <div class="col-md-6 col-xs-12">
      <div class="overlay loading-div" style="background-color: rgba(255,255,255,0.6); ">
        <div style="position: absolute; top: 50%; left: 35%; width: 40%; z-index: 1011; color: #000;">
          <i class="fa fa-spinner fa-spin fa-5x"></i><br>
          <h3 class="text-center font-w800" style="letter-spacing: -2px;">CALCULANDO...</h3>
        </div>
      </div>
      <form role="form" id="updateForm"
            action="{{ url('/admin/reservas/saveUpdate') }}/<?php echo $book->id ?>" method="post">
        <textarea id="computed-data" style="display: none"></textarea>
        <input id="book-id" type="hidden" name="book_id" value="{{ $book->id }}">
        <!-- DATOS DEL CLIENTE -->
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="customer_id" value="<?php echo $book->customer->id ?>">
        <input type="hidden" id="update_php" value="1">
        
        @include('backend.planning.blocks.form-upd-A')
        @include('backend.planning.blocks.form-upd-B')
        @include('backend.planning.blocks.form-upd-C')
        <div class="row col-xs-12 push-40 mt-1em padding-block">
          <div class="col-md-4 col-md-offset-4 text-center">
            <button class="btn btn-complete font-s24 font-w400 padding-block" type="submit"
                    style="min-height: 50px;width: 100%;" @if(getUsrRole() == "limpieza") disabled @endif>Guardar
            </button>
          </div>
        </div>
      </form>
    </div>
    <div class="col-md-6 col-xs-12 padding-block">
      @include('backend.planning.blocks.form-upd-rigth')
    </div>
  </div>
  <button style="display: none;" id="btnEmailing" class="btn btn-success btn-cons m-b-10" type="button"
          data-toggle="modal" data-target="#modalEmailing"></button>

  @include('backend.planning.blocks.form-upd-modals')
</div>
@endsection

