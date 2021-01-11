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
<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
@endsection

@section('content')

@if($errors->any())
<p class="alert alert-danger">{{$errors->first()}}</p>
@endif
@if (\Session::has('success'))
<p class="alert alert-success">{!! \Session::get('success') !!}</p>
@endif

<div class="container-fluid padding-10 sm-padding-10" id="updateBooking">
  @include('backend.planning.blocks.header-basic')
  <div class="row center text-center">
    <!-- DATOS DE LA RESERVA -->
    <div class="col-md-6 col-xs-12">
      <div class="overlay loading-div" style="background-color: rgba(255,255,255,0.6); ">
        <div style="position: absolute; top: 50%; left: 35%; width: 40%; z-index: 1011; color: #000;">
          <i class="fa fa-spinner fa-spin fa-5x"></i><br>
          <h3 class="text-center font-w800" style="letter-spacing: -2px;">CALCULANDO...</h3>
        </div>
      </div>
      <form role="form" action="" method="get">
        <textarea id="computed-data" style="display: none"></textarea>
        <input id="book-id" type="hidden" name="book_id" value="{{ $book->id }}">
        <!-- DATOS DEL CLIENTE -->
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        
        @include('backend.planning.blocks.form-basic-A')
        @include('backend.planning.blocks.form-basic-B')
      </form>
    </div>

  </div>
  <button style="display: none;" id="btnEmailing" class="btn btn-success btn-cons m-b-10" type="button"
          data-toggle="modal" data-target="#modalEmailing"></button>
  @include('backend.planning.blocks.form-upd-modals')
</div>
@endsection

